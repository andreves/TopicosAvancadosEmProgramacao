<?php

$link = mysql_connect('mysql.adickow.com', 'adickow01', 'cbG057bdfOYr');
//$link = mysql_connect('127.0.0.1', 'root', 'root');
mysql_select_db('adickow01', $link);
if (!$link) {
    die('N�o foi poss�vel conectar: ' . mysql_error());
}
//echo 'Conex�o bem sucedida';
//mysql_close($link);

?>