<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit();
}
require_once '../includes/db.php';

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="clientes_'.date('Ymd_His').'.xls"');

echo "<table border='1'>";
echo "<tr><th>ID</th><th>Nome</th><th>Valor Mensalidade</th><th>Nome do Serviço</th><th>Vendas</th><th>Criado em</th></tr>";
$sql = "SELECT id, nome, valor_mensalidade, nome_servico, vendas, criado_em FROM clientes";
$result = $conn->query($sql);
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
