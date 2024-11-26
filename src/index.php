<?php
session_start();

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result(); 

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if ($user['lock_time'] && strtotime($user['lock_time']) > time()) {
            $remainingTime = strtotime($user['lock_time']) - time();
            echo "Você tá bloqueado. Tenta de novo em " . ceil($remainingTime) . " segundos.";
        } else {
            if ($password == $user['password']) {
                $_SESSION['username'] = $username;

                $stmt = $conn->prepare("UPDATE usuarios SET failed_attempts = 0, lock_time = NULL WHERE username = ?");
                $stmt->bind_param('s', $username);
                $stmt->execute();

                header("Location: bemvindo.php");
                exit;
            } else {
                $failedAttempts = $user['failed_attempts'] + 1;

                if ($failedAttempts >= 3) {
                    $lockTime = date("Y-m-d H:i:s", time() + 30);
                    $stmt = $conn->prepare("UPDATE usuarios SET failed_attempts = ?, lock_time = ? WHERE username = ?");
                    $stmt->bind_param('iss', $failedAttempts, $lockTime, $username);
                    $stmt->execute();
                    echo "Errou 3 vezes! Agora tá bloqueado por 30 segundos.";
                } else {
                    $stmt = $conn->prepare("UPDATE usuarios SET failed_attempts = ? WHERE username = ?");
                    $stmt->bind_param('is', $failedAttempts, $username);
                    $stmt->execute();
                    echo "Senha errada! Falta(m) " . (3 - $failedAttempts) . " tentativa(s).";
                }
            }
        }
    } else {
        echo "Usuário não encontrado!";
    }
    $stmt->close();
}

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
        document.getElementById("loginForm").addEventListener("submit", function (event) {
            const username = document.getElementById("username").value.trim();
            const password = document.getElementById("password").value.trim();
            if (!username || !password) {
                event.preventDefault();
                alert("Por favor, preencha todos os campos.");
            }
        });
    </script>
