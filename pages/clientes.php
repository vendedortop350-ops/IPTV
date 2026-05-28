<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
	header('Location: ../index.php');
	exit();
}
require_once '../includes/db.php';
$mensagem = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$nome = $_POST['nome'];
	$valor = $_POST['valor'];
	$servico = $_POST['servico'];
	$vendas = $_POST['vendas'];
	$sql = "INSERT INTO clientes (nome, valor_mensalidade, nome_servico, vendas) VALUES (?, ?, ?, ?)";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param('sdss', $nome, $valor, $servico, $vendas);
	if ($stmt->execute()) {
		$mensagem = '<p style="color: #0ea5e9; font-weight: bold;">Cliente cadastrado com sucesso!</p>';
	} else {
		$mensagem = '<p style="color: #f00; font-weight: bold;">Erro ao cadastrar cliente.</p>';
	}
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Cadastrar Cliente - Elit System IPTV</title>
	<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
	<div class="header">Cadastrar Cliente</div>
	<div class="form-container">
		<form method="post">
			<input type="text" name="nome" placeholder="Nome do Cliente" required>
			<input type="number" step="0.01" name="valor" placeholder="Valor da Mensalidade" required>
			<input type="text" name="servico" placeholder="Nome do Serviço" required>
			<input type="text" name="vendas" placeholder="Vendas (responsável ou canal)" required>
			<button type="submit">Cadastrar</button>
			<a class="link" href="dashboard.php">Voltar</a>
		</form>
		<?= $mensagem ?>
	</div>
</body>
</html>
