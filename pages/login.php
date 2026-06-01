<?php
session_start();
require_once '../includes/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $sql = "SELECT id, senha FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $senha_hash);
        $stmt->fetch();
        if (password_verify($senha, $senha_hash)) {
            $_SESSION['usuario_id'] = $id;
            header('Location: dashboard.php');
            exit();
        } else {
            $erro = 'senha esta errada confirma a senha denovo ou cria nova senha.';
        }
    } else {
        $erro = 'email não encontrado ou nao cadastrado.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Elit System IPTV</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="header">Login - Elit System IPTV</div>
    <div class="form-container">
        <form method="post">
            <h2>Entrar</h2>
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
            <a class="link" href="cadastro.php">Criar conta</a>
            <a class="link" href="../recupera.php">Esqueci a senha</a>
            <?php if (isset($erro)) echo '<p style="color:#f00">'.$erro.'</p>'; ?>
        </form>
    </div>
</body>
</html>