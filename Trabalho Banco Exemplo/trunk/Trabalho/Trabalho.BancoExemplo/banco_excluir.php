<?php

$codigo = $_GET['codigo'];

include 'conexao.php';

$sql = "UPDATE BANCO SET SITUACAO = 0 WHERE CODIGO = $codigo";
$result = mysql_query($sql, $link);

if (!$result) {
    echo 'Erro MySQL: ' . mysql_error();
    exit;
}
else {
    header("Location: bancos.php");
    exit();
}

?>