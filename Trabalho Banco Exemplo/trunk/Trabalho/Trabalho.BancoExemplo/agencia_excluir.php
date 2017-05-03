<?php

session_start();

if (!isset($_SESSION['usuarioID']) OR !isset($_SESSION['usuarioNome'])) {
    header("Location:login.php");
}

$numero = $_GET['numero'];

include 'conexao.php';

$sql = "UPDATE AGENCIA SET SITUACAO = 0 WHERE NUMERO = '$numero'";
$result = mysql_query($sql, $link);

if (!$result) {
    echo 'Erro MySQL: ' . mysql_error();
    exit;
}
else {
    header("Location: agencias.php");
    exit();
}

?>