<?php
include 'conexao.php'; // Inclui o arquivo de conexão
include('protect.php');

// Verifica se a conexão foi estabelecida
if (!isset($pdo)) {
    die("Erro: A conexão com o banco de dados não foi estabelecida.");
}

// Define o número de registros por página
$registros_por_pagina = 20;

// Verifica o número da página atual (padrão para 1 se não definido)
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_atual - 1) * $registros_por_pagina;

// Filtra por status e protocolo, se estiverem definidos
$status = isset($_GET['status']) ? $_GET['status'] : '';
$protocolo = isset($_GET['protocolo']) ? $_GET['protocolo'] : '';

// Monta a consulta com os filtros de status e protocolo
$query = "SELECT * FROM manifestacoes";
$conditions = [];
$params = [];

if ($status) {
    $conditions[] = "status = :status";
    $params[':status'] = $status;
}

if ($protocolo) {
    $conditions[] = "protocolo = :protocolo";
    $params[':protocolo'] = $protocolo;
}

if ($conditions) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$query .= " ORDER BY data DESC LIMIT :offset, :limit";

// Prepara a consulta
$stmt = $pdo->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $registros_por_pagina, PDO::PARAM_INT);
$stmt->execute();
$manifestacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta para contar o total de registros (sem o limite)
$total_query = "SELECT COUNT(*) FROM manifestacoes";
if ($conditions) {
    $total_query .= " WHERE " . implode(" AND ", $conditions);
}
$total_stmt = $pdo->prepare($total_query);
foreach ($params as $key => $value) {
    $total_stmt->bindValue($key, $value);
}
$total_stmt->execute();
$total_registros = $total_stmt->fetchColumn();
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Verifica se o protocolo para exclusão foi passado e executa a exclusão
if (isset($_GET['excluir'])) {
    $protocolo_excluir = $_GET['excluir'];

    // Realiza a exclusão no banco de dados
    $delete_stmt = $pdo->prepare("DELETE FROM manifestacoes WHERE protocolo = :protocolo");
    $delete_stmt->bindValue(':protocolo', $protocolo_excluir);
    $delete_stmt->execute();

    // Mensagem de sucesso (opcional)
    echo "<script>alert('Manifestação excluída com sucesso!');</script>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área Administrativa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function confirmarExclusao(protocolo) {
            if (confirm("Tem certeza que deseja excluir esta manifestação?")) {
                window.location.href = '?excluir=' + protocolo; // Mantém na mesma página
            }
        }
    </script>
</head>
<style>
    .custom-padding {
        padding-left: 15px !important;
        padding-right: 20px !important;
    }
    h2 {
        margin-bottom: 80px;
    }
    .table-striped {
        margin-top: 80px;
    }
</style>
<body>
<nav class="navbar navbar-light bg-light justify-content-between custom-padding">
    <a class="navbar-brand">
        <img src="../images/logo-1.png" alt="Logo" width="200" height="100" class="d-inline-block align-top">
    </a>
    <form class="form-inline">
        <button class="btn btn-primary my-2 my-sm-0" type="button" onclick="window.location.href='logout.php'">
            Sair 
        </button>
    </form>
</nav>

<div class="container mt-5">
    <h2 class="text-center">Área Administrativa - Manifestações</h2>

    <!-- Formulário de Filtro por Status e Protocolo -->
    <form method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="protocolo" class="form-control" placeholder="Número do Protocolo" value="<?= htmlspecialchars($protocolo) ?>">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select" aria-label="Filtrar por Status">
                    <option value="">Todos os Status</option>
                    <option value="pendente" <?= $status === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                    <option value="respondido" <?= $status === 'respondido' ? 'selected' : '' ?>>Respondido</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Protocolo</th>
                <th>Manifestação</th>
                <th>Data</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($manifestacoes as $manifestacao): ?>
                <tr>
                    <td><?= htmlspecialchars($manifestacao['protocolo']) ?></td>
                    <td><?= htmlspecialchars($manifestacao['texto']) ?></td>
                    <td><?= (new DateTime($manifestacao['data']))->format('d/m/Y H:i:s') ?></td>
                    <td><?= htmlspecialchars($manifestacao['status']) ?></td>
                    <td>
                        <a href="responder.php?protocolo=<?= htmlspecialchars($manifestacao['protocolo']) ?>" class="btn btn-sm btn-primary">Responder</a>
                        <button onclick="confirmarExclusao('<?= htmlspecialchars($manifestacao['protocolo']) ?>')" class="btn btn-sm btn-danger">Excluir</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Paginação -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <li class="page-item <?= $i === $pagina_atual ? 'active' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $i ?>&status=<?= htmlspecialchars($status) ?>&protocolo=<?= htmlspecialchars($protocolo) ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

</body>
</html>
