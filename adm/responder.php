<?php
include 'conexao.php';
include('protect.php');

if (isset($_GET['protocolo'])) {
    $protocolo = $_GET['protocolo'];

    // Busca a manifestação pelo protocolo
    $stmt = $pdo->prepare("SELECT * FROM manifestacoes WHERE protocolo = :protocolo");
    $stmt->bindParam(':protocolo', $protocolo);
    $stmt->execute();
    $manifestacao = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Verifica se o formulário de resposta foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resposta = trim($_POST['resposta']);

    // Atualiza a resposta no banco de dados
    $stmt = $pdo->prepare("UPDATE manifestacoes SET resposta = :resposta, status = 'respondido' WHERE protocolo = :protocolo");
    $stmt->bindParam(':resposta', $resposta);
    $stmt->bindParam(':protocolo', $protocolo);

    if ($stmt->execute()) {
        echo "<div class='container mt-5'>
                <div class='alert alert-success'>Manifestação respondida com sucesso!</div>
              </div>";
    } else {
        echo "<div class='container mt-5'>
                <div class='alert alert-danger'>Erro ao responder a manifestação.</div>
              </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responder Manifestação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    .m{
        margin-top: 45px;
    }
    .resposta{
        margin-top: 80px;
    }
</style>
<body>

<nav class="navbar navbar-light bg-light justify-content-between custom-padding">
    <a class="navbar-brand">
        <img src="../images/logo-1.png" alt="Logo" width="200" height="100" class="d-inline-block align-top">
    </a>
    
</nav>

<div class="container mt-5">
    <h2 class="text-center">Responder Manifestação</h2>

    <?php if ($manifestacao): ?>
        <form method="POST">
            <div class="mb-3">
                
                <label class="form-label m"><b>Manifestação</b></label>
                <p><?= htmlspecialchars($manifestacao['texto']) ?></p>
                <hr>
            </div>
            <div class="mb-3">
                <label for="resposta" class="form-label resposta">Sua Resposta</label>
                <textarea class="form-control" id="resposta" name="resposta" rows="4" required><?= htmlspecialchars($manifestacao['resposta'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="btn btn-success">Enviar Resposta</button>
            <a href="../adm/painel.php" class="btn btn-secondary">Voltar</a>
        </form>
    <?php else: ?>
        <div class="alert alert-warning">Manifestação não encontrada.</div>
    <?php endif; ?>
</div>

</body>
</html>
