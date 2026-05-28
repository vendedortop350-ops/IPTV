<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit();
}
require_once '../includes/db.php';

$venda = isset($_GET['vendas']) ? $_GET['vendas'] : '';
$servico = isset($_GET['servico']) ? $_GET['servico'] : '';
$valor_min = isset($_GET['valor_min']) ? $_GET['valor_min'] : '';
$valor_max = isset($_GET['valor_max']) ? $_GET['valor_max'] : '';
$data = isset($_GET['data']) ? $_GET['data'] : '';
$sql = "SELECT * FROM clientes WHERE 1=1";
$params = [];
$types = '';
if ($venda !== '') {
    $sql .= " AND vendas LIKE ?";
    $params[] = "%$venda%";
    $types .= 's';
}
if ($servico !== '') {
    $sql .= " AND nome_servico LIKE ?";
    $params[] = "%$servico%";
    $types .= 's';
}
if ($valor_min !== '') {
    $sql .= " AND valor_mensalidade >= ?";
    $params[] = $valor_min;
    $types .= 'd';
}
if ($valor_max !== '') {
    $sql .= " AND valor_mensalidade <= ?";
    $params[] = $valor_max;
    $types .= 'd';
}
if ($data !== '') {
    $sql .= " AND DATE(criado_em) = ?";
    $params[] = $data;
    $types .= 's';
}
$stmt = $conn->prepare($sql);
if (count($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Clientes - Elit System IPTV</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="header">Listar Clientes</div>
    <div class="form-container">
        <form method="get" style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center;">
            <input type="text" name="vendas" placeholder="Filtrar por vendas" value="<?= htmlspecialchars($venda) ?>">
            <input type="text" name="servico" placeholder="Filtrar por serviço" value="<?= htmlspecialchars($servico) ?>">
            <input type="number" step="0.01" name="valor_min" placeholder="Valor mín." value="<?= htmlspecialchars($valor_min) ?>" style="max-width:110px;">
            <input type="number" step="0.01" name="valor_max" placeholder="Valor máx." value="<?= htmlspecialchars($valor_max) ?>" style="max-width:110px;">
            <input type="date" name="data" placeholder="Data de cadastro" value="<?= htmlspecialchars($data) ?>" style="max-width:170px;">
            <button type="submit">Filtrar</button>
            <a class="link" href="dashboard.php">Voltar</a>
            <a class="link" href="exportar_clientes_filtrado.php?vendas=<?= urlencode($venda) ?>&servico=<?= urlencode($servico) ?>&valor_min=<?= urlencode($valor_min) ?>&valor_max=<?= urlencode($valor_max) ?>&data=<?= urlencode($data) ?>" target="_blank">Exportar filtrado (Excel)</a>
        </form>
        <table class="table">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Valor Mensalidade</th>
                <th>Nome do Serviço</th>
                <th>Vendas</th>
                <th>Criado em</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['nome']) ?></td>
                <td>R$ <?= number_format($row['valor_mensalidade'],2,',','.') ?></td>
                <td><?= htmlspecialchars($row['nome_servico']) ?></td>
                <td><?= htmlspecialchars($row['vendas']) ?></td>
                <td><?= htmlspecialchars($row['criado_em']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
