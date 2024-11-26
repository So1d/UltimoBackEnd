<?php
//define as váriaveis para a conexão com o banco de dados
$host = 'mysql';  
$user = 'user';
$password = 'pipocaqueimada';
$database = 'teste_db';
//Utiliza os paramêtro coletados para efetuar a conexão
$conn = new mysqli($host, $user, $password, $database);
//retorna uma mensagem de erro caso a conexão falhe, e utiliza a função die para "fechar" a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
} /*else{
    echo "Conexao efetuada";
}*/
?>
