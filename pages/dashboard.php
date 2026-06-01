<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit();
}
require_once '../includes/db.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Elit System IPTV</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="header">Dashboard - Elit System IPTV</div>
    <div class="form-container">
        <h2>Bem-vindo!</h2>
        <div class="dashboard-btns">
            <a class="dashboard-btn" href="clientes.php">
                <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 8 1.34 8 4v2H4v-2c0-2.66 5.3-4 8-4zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/></svg>
                Cadastrar Cliente
            </a>
            <a class="dashboard-btn" href="listar_clientes.php">
                <svg viewBox="0 0 24 24"><path d="M3 6h18v2H3V6zm0 5h18v2H3v-2zm0 5h12v2H3v-2z"/></svg>
                Listar/Filtrar Clientes
            </a>
            <a class="dashboard-btn" href="exportar_clientes.php" target="_blank">
                <svg viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h4V3h6v2h4a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2zM5 7v12h14V7H5zm7 2v5h3l-4 4-4-4h3V9h2z"/></svg>
                Exportar Clientes (Excel)
            </a>
            <a class="dashboard-btn" href="devedores.php">
                <svg viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                Cadastrar Devedor
            </a>
            <a class="dashboard-btn" href="cobranca.php">
                <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                Cobrança via WhatsApp
            </a>
            <a class="dashboard-btn logout-btn" href="logout.php">
                <svg viewBox="0 0 24 24"><path d="M16 13v-2H7V8l-5 4 5 4v-3h9zm3-9H5c-1.1 0-2 .9-2 2v6h2V6h14v12H5v-4H3v6c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2z"/></svg>
                Sair
            </a>
        </div>
    </div>
</body>
</html>