<?php

session_start();

if (!isset($_SESSION['usuarioID']) OR !isset($_SESSION['usuarioNome'])) {
    header("Location:login.php");
}

$id = $_GET['id'];

include 'conexao.php';

$sql = "UPDATE CONTA SET SITUACAO = 0 WHERE ID = $id";
$result = mysql_query($sql, $link);

if (!$result) {
    echo 'Erro MySQL: ' . mysql_error();
    exit;
}
else {
    header("Location: contas.php");
    exit();
}

?>