<?php
//serve apenas para finalizar a sessão e redirecionar o usuário para a página inicial
session_start();
session_destroy();
header("Location: index.php");
exit;
?>
