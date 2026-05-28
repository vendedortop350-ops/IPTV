<?php
require_once '../includes/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $nome, $email, $senha);
    if ($stmt->execute()) {
        header('Location: ../index.php');
        exit();
    } else {
        $erro = 'Erro ao cadastrar. E-mail já existe ou dados inválidos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Elit System IPTV</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="header">Cadastro - Elit System IPTV</div>
    <div class="form-container">
        <form method="post">
            <h2>Criar Conta</h2>
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Cadastrar</button>
            <a class="link" href="../index.php">Voltar ao login</a>
            <?php if (isset($erro)) echo '<p style="color:#f00">'.$erro.'</p>'; ?>
        </form>
    </div>
</body>
</html>