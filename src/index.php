<?php
session_start();
require 'config.php'; // Configuração da conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Preparando a consulta para pegar os dados do usuário
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Obtém o usuário
        $user = $result->fetch_assoc();

        // Verifica se o usuário está bloqueado
        if ($user['lock_time'] && strtotime($user['lock_time']) > time()) {
            $remainingTime = strtotime($user['lock_time']) - time();
            echo "Você está bloqueado. Tente novamente em " . ceil($remainingTime) . " segundos.";
        } else {
            // Se o usuário não estiver bloqueado ou o bloqueio expirar
            if ($password == $user['password']) {
                // Login bem-sucedido
                $_SESSION['username'] = $username;

                // Zera as tentativas falhas
                $stmt = $conn->prepare("UPDATE usuarios SET failed_attempts = 0, lock_time = NULL WHERE username = ?");
                $stmt->bind_param('s', $username);
                $stmt->execute();

                // Redireciona para a página de bem-vindo
                header("Location: bemvindo.php");
                exit; // Garante que o script será encerrado após o redirecionamento
            } else {
                // Senha incorreta
                $failedAttempts = $user['failed_attempts'] + 1;

                if ($failedAttempts >= 3) {
                    // Bloqueia o usuário por 30 segundos após 3 tentativas falhas
                    $lockTime = date("Y-m-d H:i:s", time() + 30); // 30 segundos de bloqueio
                    $stmt = $conn->prepare("UPDATE usuarios SET failed_attempts = ?, lock_time = ? WHERE username = ?");
                    $stmt->bind_param('iss', $failedAttempts, $lockTime, $username);
                    $stmt->execute();
                    echo "Você excedeu o número de tentativas. Seu acesso foi bloqueado por 30 segundos.";
                } else {
                    // Atualiza o número de tentativas falhas
                    $stmt = $conn->prepare("UPDATE usuarios SET failed_attempts = ? WHERE username = ?");
                    $stmt->bind_param('is', $failedAttempts, $username);
                    $stmt->execute();
                    echo "Senha incorreta! Você tem " . (3 - $failedAttempts) . " tentativa(s) restante(s).";
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

<!-- HTML para o login -->
<form method="POST" action="">
    <input type="text" name="username" placeholder="Usuário" required>
    <input type="password" name="password" placeholder="Senha" required>
    <button type="submit">Login</button>
</form>
