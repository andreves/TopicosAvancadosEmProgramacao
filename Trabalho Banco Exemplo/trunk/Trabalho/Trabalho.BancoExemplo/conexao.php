<?php

$link = mysql_connect('mysql.adickow.com', 'adickow01', 'cbG057bdfOYr');
mysql_select_db('adickow01', $link);
if (!$link) {
    die('N�o foi poss�vel conectar: ' . mysql_error());
}
//echo 'Conex�o bem sucedida';
//mysql_close($link);

?>