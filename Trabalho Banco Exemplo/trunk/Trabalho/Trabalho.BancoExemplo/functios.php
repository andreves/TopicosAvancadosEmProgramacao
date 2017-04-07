<?php

include 'conexao.php';

function sec_session_start() {
    $session_name = 'sec_session_id';   // Estabele�a um nome personalizado para a sess�o
    $secure = SECURE;
    // Isso impede que o JavaScript possa acessar a identifica��o da sess�o.
    $httponly = true;
    // Assim voc� for�a a sess�o a usar apenas cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: error_login.php");
        exit();
    }
    // Obt�m params de cookies atualizados.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"],
        $cookieParams["domain"],
        $secure,
        $httponly);
    // Estabelece o nome fornecido acima como o nome da sess�o.
    session_name($session_name);
    session_start();            // Inicia a sess�o PHP
    session_regenerate_id();    // Recupera a sess�o e deleta a anterior.
}


function login($email, $password, $mysqli) {
    // Usando defini��es pr�-estabelecidas significa que a inje��o de SQL (um tipo de ataque) n�o � poss�vel.
    if ($stmt = $mysqli->prepare("SELECT ID, LOGIN, SENHA, SALT
        FROM USUARIO
       WHERE EMAIL = ?
        LIMIT 1")) {
        $stmt->bind_param('s', $email);  // Relaciona  "$email" ao par�metro.
        $stmt->execute();    // Executa a tarefa estabelecida.
        $stmt->store_result();

        // obt�m vari�veis a partir dos resultados.
        $stmt->bind_result($user_id, $username, $db_password, $salt);
        $stmt->fetch();

        // faz o hash da senha com um salt excusivo.
        $password = hash('sha512', $password . $salt);
        if ($stmt->num_rows == 1) {
            // Caso o usu�rio exista, conferimos se a conta est� bloqueada
            // devido ao limite de tentativas de login ter sido ultrapassado

            if (checkbrute($user_id, $mysqli) == true) {
                // A conta est� bloqueada
                // Envia um email ao usu�rio informando que a conta est� bloqueada
                return false (falso);
            } else {
                // Verifica se a senha confere com o que consta no banco de dados
                // a senha do usu�rio � enviada.
                if ($db_password == $password) {
                    // A senha est� correta!
                    // Obt�m o string usu�rio-agente do usu�rio.
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
                    // prote��o XSS conforme imprimimos este valor
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['user_id'] = $user_id;
                    // prote��o XSS conforme imprimimos este valor
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/",
                                                                "",
                                                                $username);
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512',
                              $password . $user_browser);
                    // Login conclu�do com sucesso.
                    return true;
                } else {
                    // A senha n�o est� correta
                    // Registramos essa tentativa no banco de dados
                    $now = time();
                    $mysqli->query("INSERT INTO LOG(USUARIO_ID, TIME)
                                    VALUES ('$user_id', '$now')");
                    return false;
                }
            }
        } else {
            // Tal usu�rio n�o existe.
            return false;
        }
    }
}

function checkbrute($user_id, $mysqli) {
    // Registra a hora atual
    $now = time();

    // Todas as tentativas de login s�o contadas dentro do intervalo das �ltimas 2 horas.
    $valid_attempts = $now - (2 * 60 * 60);

    if ($stmt = $mysqli->prepare("SELECT TIME
                             FROM LOG <code><pre>
                             WHERE USUARIO_ID = ?
                            AND TIME > '$valid_attempts'")) {
        $stmt->bind_param('i', $user_id);

        // Executa a tarefa pr�-estabelecida.
        $stmt->execute();
        $stmt->store_result();

        // Se houve mais do que 5 tentativas fracassadas de login
        if ($stmt->num_rows > 5) {
            return true;
        } else {
            return false;
        }
    }
}

function login_check($mysqli) {
    // Verifica se todas as vari�veis das sess�es foram definidas
    if (isset($_SESSION['user_id'],
                        $_SESSION['username'],
                        $_SESSION['login_string'])) {

        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];

        // Pega a string do usu�rio.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];

        if ($stmt = $mysqli->prepare("SELECT SENHA
                                      FROM USUARIO
                                      WHERE ID = ? LIMIT 1")) {
            // Atribui "$user_id" ao par�metro.
            $stmt->bind_param('i', $user_id);
            $stmt->execute();   // Execute the prepared query.
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                // Caso o usu�rio exista, pega vari�veis a partir do resultado.                 $stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash('sha512', $password . $user_browser);

                if ($login_check == $login_string) {
                    // Logado!!!
                    return true;
                } else {
                    // N�o foi logado
                    return false;
                }
            } else {
                // N�o foi logado
                return false;
            }
        } else {
            // N�o foi logado
            return false;
        }
    } else {
        // N�o foi logado
        return false;
    }
}

?>