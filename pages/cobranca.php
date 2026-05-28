<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit();
}
require_once '../includes/db.php';
$sql = "SELECT * FROM devedores ORDER BY data_vencimento ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cobrança via WhatsApp - Elit System IPTV</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="header">Cobrança via WhatsApp</div>
    <div class="form-container">
        <h2>Devedores para Cobrança</h2>
        <table class="table">
            <tr>
                <th>Nome</th>
                <th>Valor</th>
                <th>Telefone</th>
                <th>Vencimento</th>
                <th>Ação</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['nome']) ?></td>
                <td>R$ <?= number_format($row['valor'],2,',','.') ?></td>
                <td><?= htmlspecialchars($row['telefone']) ?></td>
                <td><?= date('d/m/Y', strtotime($row['data_vencimento'])) ?></td>
                <td>
                    <?php
                    $msg = urlencode("Olá, ".$row['nome']."! Sua mensalidade Elit IPTV de R$ ".number_format($row['valor'],2,',','.')." vence em ".date('d/m/Y', strtotime($row['data_vencimento'])).". Por favor, entre em contato para pagamento. Obrigado!");
                    $zap = preg_replace('/\D/', '', $row['telefone']);
                    ?>
                    <a href="https://wa.me/55<?= $zap ?>?text=<?= $msg ?>" target="_blank" class="link">Cobrar via WhatsApp</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <a class="link" href="dashboard.php">Voltar</a>
    </div>
</body>
</html>