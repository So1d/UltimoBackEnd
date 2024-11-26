<?php
// Inicia a sessão pra guardar informações entre as páginas
session_start();

// Inclui o arquivo de configuração do banco de dados (tipo o config.php)
require 'config.php';

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pega os valores do formulário (usuário e senha)
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepara a consulta SQL pra buscar o usuário pelo username
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->bind_param('s', $username); // Protege contra SQL Injection
    $stmt->execute(); // Executa a query
    $result = $stmt->get_result(); // Pega o resultado da query

    // Verifica se encontrou algum usuário com o username dado
    if ($result->num_rows > 0) {
        // Extrai os dados do usuário
        $user = $result->fetch_assoc();

        // Verifica se o usuário tá bloqueado
        if ($user['lock_time'] && strtotime($user['lock_time']) > time()) {
            // Calcula o tempo que falta pra desbloquear
            $remainingTime = strtotime($user['lock_time']) - time();
            echo "Você tá bloqueado. Tenta de novo em " . ceil($remainingTime) . " segundos.";
        } else {
            // Se não estiver bloqueado ou o tempo tiver passado
            if ($password == $user['password']) { // Aqui deveria usar hash, mas tá sem
                // Login deu certo, guarda o nome de usuário na sessão
                $_SESSION['username'] = $username;

                // Reseta as tentativas falhas e desbloqueia
                $stmt = $conn->prepare("UPDATE usuarios SET failed_attempts = 0, lock_time = NULL WHERE username = ?");
                $stmt->bind_param('s', $username);
                $stmt->execute();

                // Redireciona o cara pra página de bem-vindo
                header("Location: bemvindo.php");
                exit; // Finaliza pra garantir que o redirecionamento funciona
            } else {
                // Se a senha tiver errada, aumenta as tentativas falhas
                $failedAttempts = $user['failed_attempts'] + 1;

                if ($failedAttempts >= 3) {
                    // Bloqueia o usuário por 30 segundos se errar 3 vezes
                    $lockTime = date("Y-m-d H:i:s", time() + 30); // Calcula o tempo de bloqueio
                    $stmt = $conn->prepare("UPDATE usuarios SET failed_attempts = ?, lock_time = ? WHERE username = ?");
                    $stmt->bind_param('iss', $failedAttempts, $lockTime, $username);
                    $stmt->execute();
                    echo "Errou 3 vezes! Agora tá bloqueado por 30 segundos.";
                } else {
                    // Atualiza só o número de tentativas erradas
                    $stmt = $conn->prepare("UPDATE usuarios SET failed_attempts = ? WHERE username = ?");
                    $stmt->bind_param('is', $failedAttempts, $username);
                    $stmt->execute();
                    echo "Senha errada! Falta(m) " . (3 - $failedAttempts) . " tentativa(s).";
                }
            }
        }
    } else {
        // Se não achar o usuário, avisa
        echo "Usuário não encontrado!";
    }
    // Fecha a query preparada
    $stmt->close();
}

// Fecha a conexão com o banco 
$conn->close();
?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<div id="endregion"></div>
<body>
    <form method="POST" action="" id="loginForm">
        <input class="font" type="text" name="username" id="username" placeholder="Usuário" required>
        <input class="font" type="password" name="password" id="password" placeholder="Senha" required>
        <button class="font" type="submit">Login</button>
    </form>
 


</body>
</html>
    <style>

        body {
            font-family: Arial, sans-serif;
            background: lightblue;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            color: #fff;
        }
        form {
            background-color: #ffffff;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 100%;
            max-width: 350px;
        }
        input {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            outline: none;
        }
        .font{
            font-family: "Lexend", sans-serif;
            font-optical-sizing: auto;
            font-weight: <weight>;
            font-style: normal;
        }
        button {
            padding: 10px;
            font-size: 1rem;
            font-weight: bold;
            color: #fff;
            background-color: lightskyblue;
            border: none;
            cursor: pointer;
        }
        button:hover{
    background: #6cc3f9;
    transform:scale(1.0);
    }
    </style>
    <script>
        // Verificar se todos os campos estão preenchidos
        document.getElementById("loginForm").addEventListener("submit", function (event) {
                    // remove os espaços no começo e no fim
            const username = document.getElementById("username").value.trim();
            const password = document.getElementById("password").value.trim();
              //verfica se há algum campo vazio
            if (!username || !password) {
                event.preventDefault(); // Impede o envio
                alert("Por favor, preencha todos os campos.");
            }
        });
    </script>
