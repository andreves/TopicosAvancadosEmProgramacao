<?php

session_start();

if (!isset($_SESSION['usuarioID']) OR !isset($_SESSION['usuarioNome'])) {
    header("Location:login.php");
}

$cpf = $_GET['cpf'];

include 'conexao.php';

$sql = "UPDATE CLIENTE SET SITUACAO = 0 WHERE CPF = $cpf";
$result = mysql_query($sql, $link);

if (!$result) {
    echo 'Erro MySQL: ' . mysql_error();
    exit;
}
else {
    header("Location: clientes.php");
    exit();
}

?>