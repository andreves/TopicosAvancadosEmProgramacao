<?php

session_start();

if (!isset($_SESSION['usuarioID']) OR !isset($_SESSION['usuarioNome'])) {
    header("Location:login.php");
}

include_once 'conexao.php';
$sql = 'SELECT CODIGO, NOME FROM BANCO WHERE SITUACAO = 1 ORDER BY NOME';
$lista_banco = mysql_query($sql, $link);

if (!empty($_POST))
{
    $existe = true;
    $rg = $_POST["txtRG"];
    $conta = $_POST["txtNumConta"];
    $agencia = $_POST["txtNumAgencia"];
    $banco = $_POST["txtCodigoBanco"];
    $valor = $_POST["txtValor"];
    $tipo = $_POST["txtTipo"];
    $data = $_POST["txtData"];
    $hora = $_POST["txtHora"];

    $verifica_rg = mysql_query("SELECT * FROM CLIENTE WHERE RG = '$rg'") or die("erro ao selecionar");
    if (mysql_num_rows($verifica_rg) <= 0){
        echo"<script language='javascript' type='text/javascript'>alert('Este RG não existe. Verifique o RG e tente novamente.');window.location.href='retirada_deposito_criar.php';</script>";
        $existe = false;
        die();
    }

    $verifica_conta = mysql_query("SELECT cc.NUMERO FROM CLIENTE c JOIN CONTA cc ON cc.CLIENTE_CPF = c.CPF WHERE c.RG = '$rg' AND cc.NUMERO = $conta;") or die("erro ao selecionar");
    if (mysql_num_rows($verifica_conta) <= 0){
        echo"<script language='javascript' type='text/javascript'>alert('Esta conta não existe para este RG. Verifique a conta e tente novamente.');window.location.href='retirada_deposito_criar.php';</script>";
        $existe = false;
        die();
    }

    $verifica_agencia = mysql_query("SELECT * FROM CONTA WHERE NUMERO = $conta AND AGENCIA_NUMERO = '$agencia'") or die("erro ao selecionar");
    if (mysql_num_rows($verifica_agencia) <= 0){
        echo"<script language='javascript' type='text/javascript'>alert('Esta agencia não existe nesta conta. Verifique a agência e tente novamente.');window.location.href='retirada_deposito_criar.php';</script>";
        $existe = false;
        die();
    }

    $verifica_banco = mysql_query("SELECT * FROM AGENCIA WHERE NUMERO = '$agencia' AND BANCO_CODIGO = $banco;") or die("erro ao selecionar");
    if (mysql_num_rows($verifica_banco) <= 0){
        echo"<script language='javascript' type='text/javascript'>alert('Este banco não existe nesta agencia. Verifique o banco e tente novamente.');window.location.href='retirada_deposito_criar.php';</script>";
        $existe = false;
        die();
    }

    if ($tipo == "Saque")
    {
    	$verifica_saldo = mysql_query("SELECT * FROM CONTA WHERE NUMERO = $conta AND SALDO >= $valor;") or die("erro ao selecionar");
        if (mysql_num_rows($verifica_saldo) <= 0){
            echo"<script language='javascript' type='text/javascript'>alert('Você não tem saldo disponível para Saque.');window.location.href='retirada_deposito_criar.php';</script>";
            $existe = false;
            die();
        }
    }
    

    if($existe){

        $sql = "INSERT INTO DEPOSITO_RETIRADA (RG_CLIENTE, NUMERO_CONTA, NUMERO_AGENCIA, CODIGO_BANCO, VALOR, DATA, HORA, TIPO_MOVIMENTACAO) VALUES ('$rg', $conta, '$agencia', $banco, $valor, '$data', '$hora', '$tipo')";
        $result = mysql_query($sql, $link);

        $sql = "SELECT SALDO FROM CONTA WHERE SITUACAO = 1 AND NUMERO = $conta AND AGENCIA_NUMERO = '$agencia';";
        $saldo_conta = mysql_query($sql, $link);
        $row_saldo_conta = mysql_fetch_row($saldo_conta);

        $saldo_atual = $row_saldo_conta['0'];
        $saldo_novo = 0;
        if ($tipo == "Saque") {
        	$saldo_novo = $saldo_atual - $valor;
        }else{
            $saldo_novo = $saldo_atual + $valor;
        }

        $sql = "UPDATE CONTA SET SALDO = $saldo_novo WHERE NUMERO = $conta AND AGENCIA_NUMERO = '$agencia';";
        $result_valor = mysql_query($sql, $link);

        if ($result) {
            if ($conn->query($sql) === TRUE) {
                $last_id = $conn->insert_id;
            }

            if ($tipo == "Saque") {
                header("Location: saque_efetuado.php?id=$last_id");
                exit();
            }else{
                header("Location: deposito_efetuado.php?id=$last_id");
                exit();
            }
        }
        else {
            echo 'Erro MySQL: ' . mysql_error();
            exit;
        }
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

    <title>Retirada/Depósito | Bancos Exemplo</title>

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
                    <li class="active">
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
                        <a class="navbar-brand" href="#">Retirada/Depósito</a>
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
                                    <h4 class="title">Adicionar movimentação</h4>
                                </div>
                                <div class="content">
                                    <form method="post" action="retirada_deposito_criar.php">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>RG <span class="text-danger">*</span></label>
                                                    <input type="text" name="txtRG" class="form-control border-input" data-inputmask="'mask': '9999999999'" data-mask="data-mask" placeholder="Digite aqui o RG do cliente" required />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>
                                                        Número da Conta <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" name="txtNumConta" class="form-control border-input" placeholder="Digite aqui o número da conta do cliente" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Número da Agência <span class="text-danger">*</span></label>
                                                    <input type="text" name="txtNumAgencia" class="form-control border-input" placeholder="Digite aqui o número da agência do cliente" required />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>
                                                        Banco
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-control border-input" name="txtCodigoBanco" required>
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
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Valor <span class="text-danger">*</span></label>
                                                    <!--<input type="text" name="txtCNPJ" class="form-control border-input" data-inputmask="'mask': '99.999.999/9999-99'" data-mask="data-mask" placeholder="Digite aqui o CNPJ do banco" required />-->
                                                    <input type="text" name="txtValor" id="txtValor" class="form-control border-input" placeholder="Digite aqui o valor" required />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>
                                                        Tipo de Transação
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-control border-input" name="txtTipo" required>
                                                        <option value="">Selecione...</option>
                                                        <option value="Saque">Saque</option>
                                                        <option value="Depósito">Depósito</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" id="txtData" name="txtData" />
                                        <input type="hidden" id="txtHora" name="txtHora" />
                                        <div>
                                            <button type="submit" class="btn btn-info btn-fill btn-wd">Realizar movimentação</button>
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
        $(document).ready(function () {
            var d = new Date();

            var month = d.getMonth() + 1;
            var day = d.getDate();

            var output = (day < 10 ? '0' : '') + day + '/' + (month < 10 ? '0' : '') + month + '/' + d.getFullYear();

            $("#txtData").val(output);

            var dt = new Date();
            var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();

            $("#txtHora").val(time);

        });

        $(function () {
            $("[data-mask]").inputmask();
            $('input#txtValor').bind('keypress', mask.money)
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