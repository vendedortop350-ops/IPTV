<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit();
}
require_once '../includes/db.php';

$venda = isset($_GET['vendas']) ? $_GET['vendas'] : '';
$sql = "SELECT id, nome, valor_mensalidade, nome_servico, vendas, criado_em FROM clientes";
$params = [];
if ($venda !== '') {
    $sql .= " WHERE vendas LIKE ?";
    $params[] = "%$venda%";
}
$stmt = $conn->prepare($sql);
if ($venda !== '') {
    $stmt->bind_param('s', $params[0]);
}
$stmt->execute();
$result = $stmt->get_result();

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="clientes_filtrados_'.date('Ymd_His').'.xls"');

echo "<table border='1'>";
echo "<tr><th>ID</th><th>Nome</th><th>Valor Mensalidade</th><th>Nome do Serviço</th><th>Vendas</th><th>Criado em</th></tr>";
while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>".htmlspecialchars($row['id'])."</td>";
    echo "<td>".htmlspecialchars($row['nome'])."</td>";
    echo "<td>R$ ".number_format($row['valor_mensalidade'],2,',','.')."</td>";
    echo "<td>".htmlspecialchars($row['nome_servico'])."</td>";
    echo "<td>".htmlspecialchars($row['vendas'])."</td>";
    echo "<td>".htmlspecialchars($row['criado_em'])."</td>";
    echo "</tr>";
}
echo "</table>";
exit();
