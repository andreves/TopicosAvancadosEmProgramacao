<?php

session_start();

////include 'functions.php';
//sec_session_start();

//// Desfaz todos os valores da sess�o
$_SESSION = array();

//// obt�m os par�metros da sess�o
//$params = session_get_cookie_params();

//// Deleta o cookie em uso.
//setcookie(session_name(), '', time() - 42000, $params["sec_session_id"]);

// Destr�i a sess�o
session_destroy();
header('Location: login.php');
exit();

?>