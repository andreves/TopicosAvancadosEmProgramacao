<?php

session_start();

if (!isset($_SESSION['usuarioID']) OR !isset($_SESSION['usuarioNome'])) {
    header("Location:login.php");
}

include_once 'conexao.php';

$id = $_GET['id'];

$sql = "SELECT ID, NUMERO, SALDO, AGENCIA_NUMERO, CLIENTE_CPF FROM CONTA WHERE ID = $id";
$query = mysql_query($sql, $link);

$row_query = mysql_fetch_row($query);

$sql = 'SELECT CPF, NOME FROM CLIENTE WHERE SITUACAO = 1 ORDER BY NOME';
$result = mysql_query($sql, $link);

$sql = 'SELECT NUMERO, NOME FROM AGENCIA WHERE SITUACAO = 1 ORDER BY NOME';
$result_agencia = mysql_query($sql, $link);

if (!empty($_POST))
{
    $id_conta = $_POST["txtId"];
    $cliente = $_POST["txtCliente"];
    $agencia = $_POST["txtAgencia"];
    $numero = $_POST["txtNumero"];
    $saldo = $_POST["txtSaldo"];

    if ($saldo == null)
    {
    	$saldo = 0.00;
    }

    $sql = "UPDATE CONTA SET NUMERO = $numero, SALDO = $saldo, AGENCIA_NUMERO = '$agencia', CLIENTE_CPF = '$cliente' WHERE ID = $id_conta";
    $result = mysql_query($sql, $link);

    if ($result) {
        header("Location: contas.php");
        exit();
    }
    else {
        echo 'Erro MySQL: ' . mysql_error();
        exit;
    }
}

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="img/apple-icon.png" />
    <link rel="icon" type="image/png" sizes="96x96" href="img/favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>Contas | Bancos Exemplo</title>

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
                    <li class="active">
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
                        <a class="navbar-brand" href="#">Contas</a>
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
                                    <h4 class="title">Editar conta</h4>
                                </div>
                                <div class="content">
                                    <form method="post" action="conta_editar.php">
                                        <input type="hidden" value="<?php echo $row_query[0]; ?>" name="txtId" />
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Cliente <span class="text-danger">*</span></label>
                                                    <select class="form-control border-input" name="txtCliente" required>
                                                        <option value="">Selecione...</option>
                                                        <?php
                                                        while ($row = mysql_fetch_assoc($result)) {
                                                            if ($row['CPF'] == $row_query['4']) {                                                            
                                                        ?>
                                                        <option value="<?php echo $row['CPF'];?>" selected><?php echo $row['NOME'];?></option>
                                                        <?php
                                                            }
                                                            else {
                                                        ?>
                                                        <option value="<?php echo $row['CPF'];?>"><?php echo $row['NOME'];?></option>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Agência <span class="text-danger">*</span></label>
                                                    <select class="form-control border-input" name="txtAgencia" required>
                                                        <option value="">Selecione...</option>
                                                        <?php
                                                        while ($row_agencia = mysql_fetch_assoc($result_agencia)) {
                                                            if ($row_agencia['NUMERO'] == $row_query['3']) {
                                                        ?>
                                                        <option value="<?php echo $row_agencia['NUMERO'];?>" selected><?php echo $row_agencia['NUMERO'];?> - <?php echo $row_agencia['NOME'];?></option>
                                                        <?php
                                                            }
                                                            else {
                                                        ?>
                                                        <option value="<?php echo $row_agencia['NUMERO'];?>"><?php echo $row_agencia['NUMERO'];?> - <?php echo $row_agencia['NOME'];?></option>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Número <span class="text-danger">*</span></label>
                                                    <input type="text" name="txtNumero" class="form-control border-input" value="<?php echo $row_query[1]; ?>" placeholder="Digite aqui o número da conta" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Saldo</label>
                                                    <input type="text" name="txtSaldo" id="txtSaldo" class="form-control border-input" value="<?php echo $row_query[2]; ?>" placeholder="Digite aqui o saldo disponível" />
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-info btn-fill btn-wd">Salvar</button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <footer class="footer">
                <div class="container-fluid">
                    <!--<nav class="pull-left">
                <ul>

                    <li>
                        <a href="http://www.creative-tim.com">
                            Creative Tim
                        </a>
                    </li>
                    <li>
                        <a href="http://blog.creative-tim.com">
                            Blog
                        </a>
                    </li>
                    <li>
                        <a href="http://www.creative-tim.com/license">
                            Licenses
                        </a>
                    </li>
                </ul>
            </nav>-->
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

    <script>
        $(function () {
            $("[data-mask]").inputmask();
            $('input#txtSaldo').bind('keypress', mask.money)
        });

        var mask = {
            money: function () {
                var el = this
                , exec = function (v) {
                    v = v.replace(/\D/g, "");
                    v = new String(Number(v));
                    var len = v.length;
                    if (1 == len)
                        v = v.replace(/(\d)/, "0.0$1");
                    else if (2 == len)
                        v = v.replace(/(\d)/, "0.$1");
                    else if (len > 2) {
                        v = v.replace(/(\d{2})$/, '.$1');
                    }
                    return v;
                };

                setTimeout(function () {
                    el.value = exec(el.value);
                }, 1);
            }

        }
    </script>
</body>

</html>