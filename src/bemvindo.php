<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo</title>
</head>
<body>
 <header>
    <h1 class="texto">Bem-vindo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p class="texto">Login realizado com sucesso.</p>
    <iframe width="373" height="210" src="https://www.youtube.com/embed/Inbg9Pcu7u8?si=5iV9lwzji6BTf-61" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    <a class="texto" href="logout.php">Sair</a>
</header>
</body>
</html>
<style>
        body {
            margin: 0;
            padding: 0;
            background: lightblue;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        header {
            background: lightskyblue;
            padding: 20px;
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
        .texto {
            color: whitesmoke;
            margin: 10px 0;
            font-family: "Lexend", sans-serif;
            font-optical-sizing: auto;
            font-weight: <weight>;
            font-style: normal;
        }
        a.texto {
            text-decoration: none;
            background-color: white;
            color: #6cc3f9;
            padding: 10px 20px;
            display: inline-block;
        }
        a:hover{
            color: lightskyblue;
            background-color: aliceblue;
            cursor: pointer;
        }
</style>