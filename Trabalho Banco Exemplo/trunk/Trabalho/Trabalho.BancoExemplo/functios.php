<?php

include 'conexao.php';

function sec_session_start() {
    $session_name = 'sec_session_id';   // Estabeleça um nome personalizado para a sessão
    $secure = SECURE;
    // Isso impede que o JavaScript possa acessar a identificação da sessão.
    $httponly = true;
    // Assim você força a sessão a usar apenas cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: error_login.php");
        exit();
    }
    // Obtém params de cookies atualizados.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"],
        $cookieParams["domain"],
        $secure,
        $httponly);
    // Estabelece o nome fornecido acima como o nome da sessão.
    session_name($session_name);
    session_start();            // Inicia a sessão PHP
    session_regenerate_id();    // Recupera a sessão e deleta a anterior.
}


function login($email, $password, $mysqli) {
    // Usando definições pré-estabelecidas significa que a injeção de SQL (um tipo de ataque) não é possível.
    if ($stmt = $mysqli->prepare("SELECT ID, LOGIN, SENHA
        FROM USUARIO
       WHERE EMAIL = ?
        LIMIT 1")) {
        $stmt->bind_param('s', $email);  // Relaciona  "$email" ao parâmetro.
        $stmt->execute();    // Executa a tarefa estabelecida.
        $stmt->store_result();

        // obtém variáveis a partir dos resultados.
        $stmt->bind_result($user_id, $username, $db_password, $salt);
        $stmt->fetch();

        if ($stmt->num_rows == 1) {
            // Caso o usuário exista, conferimos se a conta está bloqueada
            // Verifica se a senha confere com o que consta no banco de dados
            // a senha do usuário é enviada.
            if ($db_password == $password) {
                // A senha está correta!
                // Obtém o string usuário-agente do usuário.
                $user_browser = $_SERVER['HTTP_USER_AGENT'];
                // proteção XSS conforme imprimimos este valor
                $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                $_SESSION['user_id'] = $user_id;
                // proteção XSS conforme imprimimos este valor
                $username = preg_replace("/[^a-zA-Z0-9_\-]+/",
                                                            "",
                                                            $username);
                $_SESSION['username'] = $username;
                $_SESSION['login_string'] = hash('sha512',
                          $password . $user_browser);
                // Login concluído com sucesso.
                return true;
            } else {
                // A senha não está correta
                // Registramos essa tentativa no banco de dados
                $now = time();
                $mysqli->query("INSERT INTO LOG(USUARIO_ID, TIME)
                                    VALUES ('$user_id', '$now')");
                return false;
            }
        } else {
            // Tal usuário não existe.
            return false;
        }
    }
}

?>