<?php
// Configurações de conexão
$host = 'localhost'; // O endereço do seu servidor, geralmente 'localhost'
$dbname = 'manifestacao'; // Nome do banco de dados
$user = 'root'; // Seu usuário do MySQL
$pass = ''; // Sua senha do MySQL

try {
    // Cria a conexão com o banco de dados
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Define o modo de erro do PDO para exceções
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Exibe uma mensagem de erro caso a conexão falhe
    die("Erro: A conexão com o banco de dados não foi estabelecida. " . $e->getMessage());
}
?>
