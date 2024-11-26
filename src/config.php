<?php
$host = 'mysql';  
$user = 'user';
$password = 'pipocaqueimada';
$database = 'teste_db';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
} /*else{
    echo "Conexao efetuada";
}*/
?>
