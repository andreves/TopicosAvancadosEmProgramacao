<?php

session_start();

if (!isset($_SESSION['usuarioID']) OR !isset($_SESSION['usuarioNome'])) {
    header("Location:login.php");
}

include_once 'conexao.php';

$sql = 'SELECT CODIGO, NOME FROM BANCO WHERE SITUACAO = 1 ORDER BY NOME';
$lista_banco = mysql_query($sql, $link);

$sql = 'SELECT CIDADE FROM CLIENTE GROUP BY CIDADE';
$lista_cidade = mysql_query($sql, $link);

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="img/apple-icon.png" />
    <link rel="icon" type="image/png" sizes="96x96" href="img/favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>Relatórios | Bancos Exemplo</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <link href="css/bootstrap.min.css" rel="stylesheet" />

    <link href="css/animate.min.css" rel="stylesheet" />

    <link href="css/paper-dashboard.css" rel="stylesheet" />

    <link href="css/demo.css" rel="stylesheet" />
    <link href="plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" />

    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css' />
    <link href="css/themify-icons.css" rel="stylesheet" />
    <link href="plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" />

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
                    <li class="active">
                        <a href="relatorios.php">
                            <i class="ti-money"></i>
                            <p>Relatórios</p>
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
                        <a class="navbar-brand" href="#">Relatórios</a>
                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="ti-settings"></i>
                                    <p class="notification">Olá</p>
                                    <p>
                                        <?php echo $_SESSION['usuarioNome']; ?>
                                    </p>
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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="header" style="padding-bottom: 70px;">
                                    <div class="pull-left">
                                        <h4 class="title">Relatórios</h4>
                                    </div>
                                    <!--<div class="pull-right">
                                        <a href="agencia_criar.php" class="btn btn-info btn-fill btn-md btn-ws">
                                            <i class="ti-plus"></i>Adicionar agência
                                        </a>
                                    </div>-->
                                </div>
                                <div class="content table-responsive table-full-width">
                                    <div class="row">
                                        <button class="btn btn-info btn-fill btn-md btn-ws" data-toggle="collapse" data-target="#bancos">1 - Relatório de bancos</button>
                                        <div class="col-md-12">
                                            <div id="bancos" class="collapse">
                                                <p>Clique no botão abaixo para imprimir o relatório do bancos:</p>
                                                <a href="pdf_banco.php" target="_blank" class="btn btn-default btn-flat btn-xs">
                                                    <i class="ti-printer"></i> Imprimir relatório
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <br />
                                    <div class="row">
                                        <button class="btn btn-info btn-fill btn-md btn-ws" data-toggle="collapse" data-target="#agencias">2 - Relatório de agências</button>
                                        <div class="col-md-12">
                                            <div id="agencias" class="collapse">
                                                <p>Selecione o banco e clique no botão abaixo para imprimir o relatório do agências:</p>
                                                <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <select class="form-control border-input" id="txtBancoAgencia" name="txtBancoAgencia" required>
                                                        <option value="">Selecione...</option>
                                                        <?php
                                                        while ($row = mysql_fetch_assoc($lista_banco)) {
                                                        ?>
                                                        <option value="<?php echo $row['CODIGO'];?>"><?php echo $row['NOME'];?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                                <a id="gerar_pdf_agencia" target="_blank" class="btn btn-default btn-flat btn-xs">
                                                    <i class="ti-printer"></i> Imprimir relatório
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <br />
                                    <div class="row">
                                        <button class="btn btn-info btn-fill btn-md btn-ws" data-toggle="collapse" data-target="#cliente_cidades">3 - Relatório de clientes por cidade</button>
                                        <div class="col-md-12">
                                            <div id="cliente_cidades" class="collapse">
                                                <p>Selecione o cidade e clique no botão abaixo para imprimir o relatório de clientes:</p>
                                                <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <select class="form-control border-input" id="txtCidadeCliente" name="txtCidadeCliente" required>
                                                        <option value="">Selecione...</option>
                                                        <?php
                                                        while ($row = mysql_fetch_assoc($lista_cidade)) {
                                                        ?>
                                                        <option value="<?php echo $row['CIDADE'];?>"><?php echo $row['CIDADE'];?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                                <a id="gerar_pdf_cidade" target="_blank" class="btn btn-default btn-flat btn-xs">
                                                    <i class="ti-printer"></i> Imprimir relatório
                                                </a>
                                            </div>
                                        </div>
                                    </div>
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
                        <a href="http://www.adickow.com" target="_blank">ADICKOW</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="js/jquery-1.10.2.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>

    <script>
        $("#txtBancoAgencia").change(function () {
            var _banco = $("#txtBancoAgencia").val();
            $("#gerar_pdf_agencia").attr("href", "pdf_agencia.php?banco=" + _banco);
        });

        $("#txtCidadeCliente").change(function () {
            var _cidade = $("#txtCidadeCliente").val();
            $("#gerar_pdf_cidade").attr("href", "pdf_cidade_cliente.php?cidade=" + _cidade);
        });
    </script>

    <script src="js/bootstrap-checkbox-radio.js"></script>

    <script src="js/chartist.min.js"></script>

    <script src="js/bootstrap-notify.js"></script>

    <script src="js/paper-dashboard.js"></script>

    <script src="js/demo.js"></script>
</body>

</html>