<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manifestação Anônima</title>
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


<div class="container mt-5 ">
    <h2 class="text-center c">Envie sua Manifestação Anônima</h2>
    <form action="salvar_manifestacao.php" method="POST">
        <div class="mb-3">
            <label for="manifestacao" class="form-label d">Digite sua manifestação no campo abaixo: </label>
            <textarea class="form-control" id="manifestacao" name="manifestacao" rows="4" maxlength="1000" required></textarea>
            <div class="form-text">Máximo de 1000 caracteres.</div>
        </div>
        <button type="submit" class="btn btn-primary">Enviar Manifestação</button>
    </form>
</div>

</body>
</html>
