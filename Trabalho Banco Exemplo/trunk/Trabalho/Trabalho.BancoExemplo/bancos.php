<?php

session_start();

if (!isset($_SESSION['usuarioID']) OR !isset($_SESSION['usuarioNome'])) {
    header("Location:login.php");
}

include 'conexao.php';

$sql = 'SELECT CODIGO, NOME, CNPJ FROM BANCO WHERE SITUACAO = 1';
$result = mysql_query($sql, $link);

if (!$result) {
    echo 'Erro MySQL: ' . mysql_error();
    exit;
}

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="img/apple-icon.png" />
    <link rel="icon" type="image/png" sizes="96x96" href="img/favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>Bancos | Bancos Exemplo</title>

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
                    <li class="active">
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
                        <a class="navbar-brand" href="#">Bancos</a>
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
                                        <h4 class="title">Lista de bancos</h4>
                                    </div>
                                    <div class="pull-right">
                                        <a href="banco_criar.php" class="btn btn-info btn-fill btn-md btn-ws">
                                            <i class="ti-plus"></i> Adicionar banco
                                        </a>
                                    </div>
                                </div>
                                <div class="content table-responsive table-full-width">
                                    <table class="table table-striped" id="example1">
                                        <thead>
                                            <th>Código</th>
                                            <th>Nome</th>
                                            <th>CNPJ</th>
                                            <th>Ações</th>
                                        </thead>
                                        <!--<tbody id="listBanco">

                                        </tbody>-->
                                        <tbody>
                                            <?php
                                            while ($row = mysql_fetch_assoc($result)) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $row['CODIGO'];?>
                                                </td>
                                                <td>
                                                    <?php echo $row['NOME'];?>
                                                </td>
                                                <td>
                                                    <?php echo $row['CNPJ'];?>
                                                </td>
                                                <td>
                                                    <a href="banco_editar.php?codigo=<?php echo $row['CODIGO']; ?>" class="btn btn-info btn-fill btn-sm">
                                                        <i class="ti-pencil"></i> Editar
                                                    </a>
                                                    <a href="banco_excluir.php?codigo=<?php echo $row['CODIGO']; ?>" onclick="return confirm('Deseja realmente excluir este item?')" class="btn btn-warning btn-fill btn-sm">
                                                        <i class="ti-trash"></i> Excluir
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>

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
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>

    <script>
        $('#example1').DataTable({
            "destroy": true,
            "pageLength": 50,
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false
        });
    </script>

    <!--<script>

        $(document).ready(function () {
            if ('' != null && '' != "") {
                var mensagem = '';
                $.growl.notice({ title: "Sucesso!", message: mensagem });
            }

            //$('#example1').hide();
            var cont = 1;

            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: 'bancos.php',
                contentType: 'application/json; charset=utf-8',
                error: function () {
                    alert("server access failure");
                    //$.growl.error({ title: ":(", message: "Server access failure" });
                },
                success: function (data) {
                    $.each(data, function (key, val) {

                        var library = '<tr>' +
                            '<td>' + cont + '</td>' +
                            '<td>' + val.Codigo + '</td>' +
                            '<td>' + val.Nome + '</td>' +
                            '<td>' + val.Cnpj + '</td>' +
                            '<td>' +
                                '<a href="banco_editar.php/' + val.Id + '" class="btn btn-info btn-fill btn-sm">' +
                                    'Editar' +
                                '</a>' +
                                '<a href="banco_excluir/' + val.Id + '" class="btn btn-warning btn-fill btn-sm" onclick="return confirm(\'Deseja realmente excluir?\');">' +
                                    'Deletar' +
                                '</a>' +
                            '</td>' +
                            '</tr>';

                        $('#listBanco').append(library);
                        cont++;
                    });

                    //$('#example1').show();

                    //$('#example1').DataTable({
                    //    "pageLength": 50,
                    //    "paging": true,
                    //    "lengthChange": true,
                    //    "searching": true,
                    //    "ordering": true,
                    //    "info": true,
                    //    "autoWidth": false
                    //});

                    //$('#boxCarregar').hide();
                }
            });
        });

    </script>-->

    <script src="js/bootstrap-checkbox-radio.js"></script>

    <script src="js/chartist.min.js"></script>

    <script src="js/bootstrap-notify.js"></script>

    <script src="js/paper-dashboard.js"></script>

    <script src="js/demo.js"></script>
</body>

</html>
