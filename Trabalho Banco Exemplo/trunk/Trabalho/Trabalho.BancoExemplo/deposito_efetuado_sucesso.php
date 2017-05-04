<?php

session_start();

if (!isset($_SESSION['usuarioID']) OR !isset($_SESSION['usuarioNome'])) {
    header("Location:login.php");
}

$id_recibo = $_GET['id'];

//include_once 'conexao.php';

//$sql = 'SELECT CODIGO, NOME FROM BANCO WHERE SITUACAO = 1 ORDER BY NOME';
//$lista_banco = mysql_query($sql, $link);

//if (!empty($_POST))
//{
//    $numero = $_POST["txtNumero"];
//    $nome = $_POST["txtNome"];
//    $endereco = $_POST["txtEndereco"];
//    $cidade = $_POST["txtCidade"];
//    $estado = $_POST["txtEstado"];
//    $cep = $_POST["txtCEP"];
//    $banco_codigo = $_POST["txtBancoAgencia"];

//    $verifica_numero = mysql_query("SELECT * FROM AGENCIA WHERE NUMERO = '$numero'") or die("erro ao selecionar");
//    if (mysql_num_rows($verifica_numero) > 0){
//        echo"<script language='javascript' type='text/javascript'>alert('Este número de agência já existe');window.location.href='agencia_criar.php';</script>";
//        die();
//    }else{
//        $sql = "INSERT INTO AGENCIA (NUMERO, NOME, ENDERECO, CIDADE, ESTADO, CEP, BANCO_CODIGO) VALUES ('$numero', '$nome', '$endereco', '$cidade', '$estado', '$cep', $banco_codigo)";
//        $result = mysql_query($sql, $link);

//        if ($result) {
//            header("Location: agencias.php");
//            exit();
//        }
//        else {
//            echo 'Erro MySQL: ' . mysql_error();
//            exit;
//        }
//    }
//}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="img/apple-icon.png" />
    <link rel="icon" type="image/png" sizes="96x96" href="img/favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>Depósito | Bancos Exemplo</title>

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
        <div class="sidebar" data-background-color="white" data-active-color="danger">
            <div class="sidebar-wrapper">
                <div class="logo">
                    <a href="dashboard.php" class="simple-text">
                        Banco Exemplo
                    </a>
                </div>

                <ul class="nav">
                    <li>
                        <a href="dashboard.php">
                            <i class="ti-panel"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li>
                        <a href="bancos.php">
                            <i class="ti-view-list-alt"></i>
                            <p>Bancos</p>
                        </a>
                    </li>
                    <li>
                        <a href="agencias.php">
                            <i class="ti-view-list-alt"></i>
                            <p>Agências</p>
                        </a>
                    </li>
                    <li>
                        <a href="contas.php">
                            <i class="ti-view-list-alt"></i>
                            <p>Contas</p>
                        </a>
                    </li>
                    <li>
                        <a href="clientes.php">
                            <i class="ti-user"></i>
                            <p>Clientes</p>
                        </a>
                    </li>
                    <li>
                        <a href="retirada_deposito.php">
                            <i class="ti-money"></i>
                            <p>Retirada/Depósito</p>
                        </a>
                    </li>
                    <li>
                        <a href="deposito.php">
                            <i class="ti-money"></i>
                            <p>Depósito entre contas</p>
                        </a>
                    </li>
                    <li>
                        <a href="transferencia.php">
                            <i class="ti-money"></i>
                            <p>Transferência</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="main-panel">
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar bar1"></span>
                            <span class="icon-bar bar2"></span>
                            <span class="icon-bar bar3"></span>
                        </button>
                        <a class="navbar-brand" href="#">Depósito</a>
                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="ti-settings"></i>
                                    <p class="notification">Olá</p>
                                    <p><?php echo $_SESSION['usuarioNome']; ?></p>
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="editar_perfil.php">Editar perfil</a>
                                    </li>
                                    <li>
                                        <a href="logout.php">Logout</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="card">
                                <div class="header">
                                    <h4 class="title">Depósito efetuado com sucesso!</h4>
                                </div>
                                <div class="content">
                                    <a href="recibo_deposito_sucesso.php?id=<?php echo $id_recibo; ?>" target="_blank" class="btn btn-default btn-flat btn-xs">
                                        <i class="ti-printer"></i> Imprimir recibo
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="copyright pull-right">
                        &copy;
                        <script>document.write(new Date().getFullYear())</script>, desenvolvido por
                        <a href="http://www.adickow.com">ADICKOW</a>
                    </div>
                </div>
            </footer>

        </div>
    </div>

    <script src="js/jquery-1.10.2.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>

    <script src="js/bootstrap-checkbox-radio.js"></script>

    <script src="js/plugins/input-mask/jquery.inputmask.js"></script>

    <!--<script src="js/chartist.min.js"></script>-->

    <script src="js/bootstrap-notify.js"></script>

    <script src="js/paper-dashboard.js"></script>

    <script src="js/demo.js"></script>

</body>

</html>