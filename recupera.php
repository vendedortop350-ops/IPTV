<?php
session_start();
require_once 'includes/db.php';

function adicionarColunasRecuperacao($conn) {
    $check1 = $conn->query("SHOW COLUMNS FROM usuarios LIKE 'recuperacao_codigo'");
    $check2 = $conn->query("SHOW COLUMNS FROM usuarios LIKE 'recuperacao_expira'");
    if ($check1 && $check2 && ($check1->num_rows === 0 || $check2->num_rows === 0)) {
        $sql = "ALTER TABLE usuarios ";
        $parts = [];
        if ($check1->num_rows === 0) {
            $parts[] = "ADD COLUMN recuperacao_codigo VARCHAR(10) NULL";
        }
        if ($check2->num_rows === 0) {
            $parts[] = "ADD COLUMN recuperacao_expira DATETIME NULL";
        }
        if (!empty($parts)) {
            $conn->query($sql . implode(', ', $parts));
        }
    }
}

function gerarCodigo($tamanho = 6) {
    return str_pad(rand(0, pow(10, $tamanho) - 1), $tamanho, '0', STR_PAD_LEFT);
}

adicionarColunasRecuperacao($conn);

$passo = 'email';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $email = trim($_POST['email'] ?? '');

    if ($action === 'send_code') {
        if (empty($email)) {
            $erro = 'Informe o seu e-mail.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erro = 'E-mail inválido.';
        } else {
            $sql = 'SELECT id FROM usuarios WHERE email = ?';
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $codigo = gerarCodigo();
                $expira = date('Y-m-d H:i:s', strtotime('+15 minutes'));
                $update = $conn->prepare('UPDATE usuarios SET recuperacao_codigo = ?, recuperacao_expira = ? WHERE email = ?');
                $update->bind_param('sss', $codigo, $expira, $email);
                $update->execute();
                $update->close();

                $assunto = 'Código de verificação - Elit IPTV';
                $mensagemEmail = "Seu código de verificação é: $codigo\n\n" .
                    "Use este código dentro de 15 minutos para redefinir sua senha.\n" .
                    "Se você não solicitou, ignore esta mensagem.";
                $headers = 'From: no-reply@elitiptv.local' . "\r\n" .
                    'Reply-To: no-reply@elitiptv.local' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

                if (@mail($email, $assunto, $mensagemEmail, $headers)) {
                    $mensagem = 'O código de verificação foi enviado para o seu e-mail.';
                } else {
                    $mensagem = 'Não foi possível enviar o e-mail automaticamente. Use este código para testar: ' . $codigo;
                }

                $passo = 'code';
            } else {
                $erro = 'E-mail não encontrado.';
            }
            $stmt->close();
        }
    } elseif ($action === 'verify_code') {
        $codigo = trim($_POST['codigo'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $confirmacao = $_POST['confirmacao'] ?? '';

        if (empty($email) || empty($codigo) || empty($senha) || empty($confirmacao)) {
            $erro = 'Preencha todos os campos.';
            $passo = 'code';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erro = 'E-mail inválido.';
            $passo = 'code';
        } elseif ($senha !== $confirmacao) {
            $erro = 'As senhas não coincidem.';
            $passo = 'code';
        } elseif (strlen($senha) < 6) {
            $erro = 'A senha deve ter pelo menos 6 caracteres.';
            $passo = 'code';
        } else {
            $sql = 'SELECT id FROM usuarios WHERE email = ? AND recuperacao_codigo = ? AND recuperacao_expira >= NOW()';
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $email, $codigo);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $novaSenhaHash = password_hash($senha, PASSWORD_DEFAULT);
                $update = $conn->prepare('UPDATE usuarios SET senha = ?, recuperacao_codigo = NULL, recuperacao_expira = NULL WHERE email = ?');
                $update->bind_param('ss', $novaSenhaHash, $email);
                if ($update->execute()) {
                    $mensagem = 'Senha atualizada com sucesso. Agora faça login com a nova senha.';
                    $passo = 'done';
                } else {
                    $erro = 'Erro ao atualizar a senha. Tente novamente mais tarde.';
                    $passo = 'code';
                }
                $update->close();
            } else {
                $erro = 'Código inválido ou expirado. Peça um novo código.';
                $passo = 'code';
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - Elit System IPTV</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="header">Recuperar Senha - Elit System IPTV</div>
    <div class="form-container">
        <?php if ($passo === 'email' || $passo === 'code'): ?>
            <form method="post">
                <h2>Recuperar Senha</h2>
                <input type="email" name="email" placeholder="Seu e-mail" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                <?php if ($passo === 'email'): ?>
                    <input type="hidden" name="action" value="send_code">
                    <button type="submit">Enviar código</button>
                <?php else: ?>
                    <input type="hidden" name="action" value="verify_code">
                    <input type="text" name="codigo" placeholder="Código de verificação" required>
                    <input type="password" name="senha" placeholder="Nova senha" required>
                    <input type="password" name="confirmacao" placeholder="Confirmar nova senha" required>
                    <button type="submit">Redefinir senha</button>
                <?php endif; ?>
                <a class="link" href="index.php">Voltar ao login</a>
                <?php if (isset($erro)) echo '<p style="color:#f00">'.htmlspecialchars($erro).'</p>'; ?>
                <?php if (isset($mensagem)) echo '<p style="color:#0a0">'.htmlspecialchars($mensagem).'</p>'; ?>
            </form>
        <?php elseif ($passo === 'done'): ?>
            <div class="message-box">
                <h2>Senha atualizada</h2>
                <p><?php echo htmlspecialchars($mensagem); ?></p>
                <a class="link" href="index.php">Ir para login</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
