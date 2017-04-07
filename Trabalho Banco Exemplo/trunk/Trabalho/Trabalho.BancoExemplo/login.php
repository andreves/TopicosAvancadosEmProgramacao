<?php

include 'conexao.php';
include_once 'functions.php';

sec_session_start(); // Nossa seguran�a personalizada para iniciar uma sess�o php.

if (isset($_POST['email'], $_POST['p'])) {
    $email = $_POST['email'];
    $password = $_POST['p']; // The hashed password.

    if (login($email, $password, $mysqli) == true) {
        // Login com sucesso
        header('Location: ../protected_page.php');
    } else {
        // Falha de login
        header('Location: ../index.php?error=1');
    }
} else {
    // As vari�veis POST corretas n�o foram enviadas para esta p�gina.
    echo 'Invalid Request';

?>