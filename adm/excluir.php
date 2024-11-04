<?php
include 'conexao.php'; // Inclui o arquivo de conexão
include('protect.php');

// Verifica se o protocolo foi passado
if (isset($_GET['protocolo'])) {
    $protocolo = $_GET['protocolo'];

    // Prepare a consulta para exclusão
    $query = "DELETE FROM manifestacoes WHERE protocolo = :protocolo";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':protocolo', $protocolo);
    $stmt->execute();

    // Redireciona de volta para a página de listagem com um parâmetro de sucesso
    header("Location: listagem.php?mensagem=Exclusão bem-sucedida");
    exit();
} else {
    die("Protocolo não fornecido.");
}
