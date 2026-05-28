<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit();
}
require_once '../includes/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $valor = $_POST['valor'];
    $telefone = $_POST['telefone'];
    $vencimento = $_POST['vencimento'];
    $sql = "INSERT INTO devedores (nome, valor, telefone, data_vencimento) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sdss', $nome, $valor, $telefone, $vencimento);
    $stmt->execute();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar devedores - Elit System IPTV</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="header">Cadastrar Devedores</div>
    <div class="form-container">
        <form method="post">
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="number" step="0.01" name="valor" placeholder="Valor" required>
            <input type="text" name="telefone" placeholder="Telefone" required>
            <input type="date" name="vencimento" placeholder="Data de Vencimento" required>
            <button type="submit">Cadastrar</button>
            <a class="link" href="dashboard.php">Voltar</a>
        </form>
    </div>
</body>
</html>