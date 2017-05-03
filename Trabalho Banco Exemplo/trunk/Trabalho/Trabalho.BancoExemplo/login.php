<?php

if (!empty($_POST))
{
    $login = $_POST['txtLogin'];
    $senha = $_POST['txtSenha'];

    include_once 'conexao.php';

    $verifica = mysql_query("SELECT ID, NOME FROM USUARIO WHERE LOGIN = '$login' AND SENHA = '$senha'") or die("erro ao selecionar");
    if (mysql_num_rows($verifica)<=0){
        echo"<script language='javascript' type='text/javascript'>alert('Login e/ou senha incorretos');window.location.href='login.php';</script>";
        die();
    }else{
        //$resultado = mysql_fetch_assoc($verifica);
        $sql = "SELECT ID, NOME FROM USUARIO WHERE LOGIN = '$login' AND SENHA = '$senha'";
        $result = mysql_query($sql, $link);
        $row = mysql_fetch_row($result);

        session_start();
        $_SESSION['usuarioID'] = $row[0];
        $_SESSION['usuarioNome'] = $row[1];
        header("Location:dashboard.php");
    }
}

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>Login Banco Exemplo</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <link href="css/bootstrap.min.css" rel="stylesheet" />

    <link href="css/animate.min.css" rel="stylesheet" />

    <link href="css/paper-dashboard.css" rel="stylesheet" />

    <link href="css/demo.css" rel="stylesheet" />

    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css' />
    <link href="css/themify-icons.css" rel="stylesheet" />

</head>
<body>

    <div class="wrapper">

        <div class="main-panel" style="float: none; width: 100%;">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4">
                            <div class="card card-user">
                                <div class="content">
                                    <h2 class="text-center">Banco Exemplo</h2>
                                    <form method="post" action="login.php">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <!--<label>Usuário</label>-->
                                                    <input type="text" class="form-control border-input" autocomplete="off" autofocus name="txtLogin" placeholder="usuário" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <!--<label>Senha</label>-->
                                                    <input type="password" class="form-control border-input" name="txtSenha" placeholder="senha" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-info btn-fill btn-wd">login</button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="js/jquery-1.10.2.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>

    <script src="js/bootstrap-checkbox-radio.js"></script>

    <script src="js/chartist.min.js"></script>

    <script src="js/bootstrap-notify.js"></script>

    <script src="js/paper-dashboard.js"></script>

    <script src="js/demo.js"></script>

</body>


</html>
