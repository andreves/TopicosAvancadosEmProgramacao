<?php

$link = mysql_connect('mysql.adickow.com', 'adickow01', 'cbG057bdfOYr');
mysql_select_db('adickow01', $link);
if (!$link) {
    die('Não foi possível conectar: ' . mysql_error());
}
//echo 'Conexão bem sucedida';
//mysql_close($link);

?>