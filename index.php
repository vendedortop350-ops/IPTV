<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header('Location: pages/dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elit System IPTV - Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="header">Elit System IPTV</div>
    <div class="form-container">
        <form action="pages/login.php" method="post">
            <h2>Login</h2>
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
            <a class="link" href="pages/cadastro.php">Criar conta</a>
            <a class="link" href="recupera.php">Esqueci a senha</a>
        </form>
    </div>
</body>
</html>