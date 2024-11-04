<?php
include 'conexao.php';

// Conexão com o banco de dados
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Função para gerar um número de protocolo único
function gerarProtocolo() {
    return strtoupper(substr(md5(uniqid(rand(), true)), 0, 10));
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $manifestacao = trim($_POST['manifestacao']);

    // Verifica se o campo não está vazio
    if (!empty($manifestacao)) {
        // Gera o número de protocolo
        $protocolo = gerarProtocolo();

        // Insere a manifestação no banco de dados
        $stmt = $pdo->prepare("INSERT INTO manifestacoes (protocolo, texto) VALUES (:protocolo, :texto)");
        $stmt->bindParam(':protocolo', $protocolo);
        $stmt->bindParam(':texto', $manifestacao);

        if ($stmt->execute()) {
            // Exibe a mensagem de sucesso com o protocolo
            echo "<div class='container mt-5 text-center'>
                    <div class='alert alert-success mx-auto' style='max-width: 600px;'>
                        <h4 class='alert-heading'>Manifestação Registrada!</h4>
                        <p>Obrigado por sua manifestação. Seu número de protocolo é:</p>
                        <h5 class='font-weight-bold'>$protocolo</h5>
                        <hr>
                        <p class='mb-0'>Você pode acompanhar o status do seu protocolo em nosso site. Agradecemos sua contribuição!</p>
                    </div>
                  </div>";
        } else {
            echo "<div class='container mt-5 text-center'>
                    <div class='alert alert-danger mx-auto' style='max-width: 600px;'>Erro ao salvar sua manifestação. Tente novamente mais tarde.</div>
                  </div>";
        }
    } else {
        echo "<div class='container mt-5 text-center'>
                <div class='alert alert-warning mx-auto' style='max-width: 600px;'>Por favor, preencha a manifestação.</div>
              </div>";
    }
}
?>
