<?php
// Configurações do banco de dados
include 'conexao.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $protocolo = trim($_POST['protocolo']);

    // Busca a manifestação pelo protocolo
    $stmt = $pdo->prepare("SELECT * FROM manifestacoes WHERE protocolo = :protocolo");
    $stmt->bindParam(':protocolo', $protocolo);
    $stmt->execute();
    $manifestacao = $stmt->fetch(PDO::FETCH_ASSOC);
}


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Manifestação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    .custom-padding {
    padding-left: 15px !important;
    padding-right: 20px !important;
}

.b{
    font-weight: bold;
}

.c{
    margin-top: 80px;
}

.d{
    margin-top: 35px;
}
</style>
<body>

<nav class="navbar navbar-light bg-light justify-content-between custom-padding">
    <a class="navbar-brand">
        <img src="images/logo-1.png" alt="Logo" width="200" height="100" class="d-inline-block align-top">
        
    </a>
    <form class="form-inline">
        <button class="btn btn-primary my-2 my-sm-0 b" type="button" onclick="window.location.href='https://ipmur.com.br/'">
            Voltar para o Site
        </button>
    </form>
</nav>

<div class="container mt-5">
    <h2 class="text-center c">Consultar Manifestação</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="protocolo" class="form-label d"><b>Digite o número do protocolo.</b> OBS: Consulta válida apenas para manifestação Anônima.</label>
            <input type="text" class="form-control" id="protocolo" name="protocolo" required>
        </div>
        <button type="submit" class="btn btn-primary">Consultar</button>
    </form>

    <?php if (isset($manifestacao)): ?>
        <?php if ($manifestacao): ?>
            <div class="mt-4">
                <h4>Status: <?= htmlspecialchars($manifestacao['status']) ?></h4>
                <p><strong>Resposta:</strong> <?= htmlspecialchars($manifestacao['resposta'] ?? 'Ainda não respondida') ?></p>
            </div>
        <?php else: ?>
            <div class="alert alert-warning mt-4">Protocolo não encontrado.</div>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>
