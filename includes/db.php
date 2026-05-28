<?php
// Conexão com o banco de dados MySQL
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'elit_iptv';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Erro na conexão: ' . $conn->connect_error);
}
?>