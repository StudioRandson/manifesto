<?php
session_start(); // Inicia a sessão

// Configurações de conexão
$host = 'localhost'; // O endereço do seu servidor, geralmente 'localhost'
$user = 'root'; // Seu usuário do MySQL
$pass = ''; // Sua senha do MySQL
$db = 'manifestacao'; // Seu banco de dados

// Cria a conexão
$mysqli = new mysqli($host, $user, $pass, $db);

// Verifica se houve erro na conexão
if ($mysqli->connect_error) {
    die("Falha na conexão: " . $mysqli->connect_error);
}

// Lógica de autenticação
if (isset($_POST['email']) && isset($_POST['senha'])) {
    if (strlen($_POST['email']) == 0) {
        echo "Preencha seu e-mail";
    } else if (strlen($_POST['senha']) == 0) {
        echo "Preencha sua senha";
    } else {
        // Escapando as entradas para evitar SQL Injection
        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $_POST['senha']; // Não escapar a senha, pois vamos verificar a senha diretamente

        // Usando uma consulta preparada
        $sql_code = "SELECT * FROM usuarios WHERE email = ? AND senha = ?";
        $stmt = $mysqli->prepare($sql_code);

        if ($stmt) {
            // Passa a senha como parâmetro na consulta
            $stmt->bind_param("ss", $email, $senha);
            $stmt->execute();
            $result = $stmt->get_result();

            // Verifica se algum usuário foi encontrado
            if ($result->num_rows == 1) {
                $usuario = $result->fetch_assoc();

                // Armazena os dados do usuário na sessão
                $_SESSION['id'] = $usuario['id'];
                $_SESSION['nome'] = $usuario['nome'];

                header("Location: painel.php");
                exit();
            } else {
                echo "Falha ao logar! E-mail ou senha incorretos.";
            }

            $stmt->close(); // Fecha o statement
        } else {
            echo "Erro na preparação da consulta: " . $mysqli->error;
        }
    }
}

$mysqli->close(); // Fecha a conexão
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="../images/icons/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="../vendor/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="../vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../css/util.css">
    <link rel="stylesheet" type="text/css" href="../css/main.css">
</head>
<body>
    
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                    <img src="../images/img-01.png" alt="IMG">
                </div>

                <form class="login100-form validate-form" method="POST">
                    <span class="login100-form-title">
                        Login Ipmur
                    </span>

                    <div class="wrap-input100 validate-input" data-validate="Campo Obrigatório">
                        <input class="input100" type="text" name="email" placeholder="Email" required>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Campo Obrigatório">
                        <input class="input100" type="password" name="senha" placeholder="Senha" required>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>
                    
                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" type="submit">
                            Login
                        </button>
                    </div>

                    <div class="text-center p-t-136">
                        <a class="txt2" href="https://ipmur.com.br/">
                            Voltar para o Site
                            <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="../vendor/bootstrap/js/popper.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../vendor/select2/select2.min.js"></script>
    <script src="../vendor/tilt/tilt.jquery.min.js"></script>
    <script>
        $('.js-tilt').tilt({
            scale: 1.1
        });
    </script>
    <script src="../js/main.js"></script>

</body>
</html>
