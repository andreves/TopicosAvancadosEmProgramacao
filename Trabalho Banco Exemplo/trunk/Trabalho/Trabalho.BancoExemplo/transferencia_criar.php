<?php

session_start();

if (!isset($_SESSION['usuarioID']) OR !isset($_SESSION['usuarioNome'])) {
    header("Location:login.php");
}

include_once 'conexao.php';
$sql = 'SELECT CODIGO, NOME FROM BANCO WHERE SITUACAO = 1 ORDER BY NOME';
$lista_banco_origem = mysql_query($sql, $link);

$sql = 'SELECT CODIGO, NOME FROM BANCO WHERE SITUACAO = 1 ORDER BY NOME';
$lista_banco_destino = mysql_query($sql, $link);

if (!empty($_POST))
{
    $existe = true;
    $conta_origem = $_POST["txtContaOrigem"];
    $agencia_origem = $_POST["txtAgenciaOrigem"];
    $banco_origem = $_POST["txtBancoOrigem"];
    $conta_destino = $_POST["txtContaDestino"];
    $agencia_destino = $_POST["txtAgenciaDestino"];
    $banco_destino = $_POST["txtBancoDestino"];
    $valor = $_POST["txtValor"];
    $tipo = $_POST["txtTipo"];
    $data = $_POST["txtData"];
    $hora = $_POST["txtHora"];

    //Verifica se bancos são iguais
    if ($banco_origem != $banco_destino)
    {
    	echo"<script language='javascript' type='text/javascript'>alert('Os bancos são diferentes. Verifique os bancos e tente novamente.');window.location.href='transferencia_criar.php';</script>";
        $existe = false;
        return false;
    }
    

    //Verifica dados ORIGEM
    $verifica_conta_origem = mysql_query("SELECT * FROM CONTA WHERE NUMERO = $conta_origem;") or die("erro ao selecionar");
    if (mysql_num_rows($verifica_conta_origem) <= 0){
        echo"<script language='javascript' type='text/javascript'>alert('Esta conta origem não existe. Verifique a conta e tente novamente.');window.location.href='transferencia_criar.php';</script>";
        $existe = false;
        die();
    }

    $verifica_agencia_origem = mysql_query("SELECT * FROM CONTA WHERE NUMERO = $conta_origem AND AGENCIA_NUMERO = '$agencia_origem'") or die("erro ao selecionar");
    if (mysql_num_rows($verifica_agencia_origem) <= 0){
        echo"<script language='javascript' type='text/javascript'>alert('Esta agencia origem não existe nesta conta. Verifique a agência e tente novamente.');window.location.href='transferencia_criar.php';</script>";
        $existe = false;
        die();
    }

    $verifica_banco_origem = mysql_query("SELECT * FROM AGENCIA WHERE NUMERO = '$agencia_origem' AND BANCO_CODIGO = $banco_origem;") or die("erro ao selecionar");
    if (mysql_num_rows($verifica_banco_origem) <= 0){
        echo"<script language='javascript' type='text/javascript'>alert('Este banco origem não existe nesta agencia. Verifique o banco e tente novamente.');window.location.href='transferencia_criar.php';</script>";
        $existe = false;
        die();
    }

    if ($tipo == "DOC")
    {
    	$verifica_saldo_DOC = mysql_query("SELECT * FROM CONTA WHERE NUMERO = $conta_origem AND SALDO >= $valor;") or die("erro ao selecionar");
        if (mysql_num_rows($verifica_saldo_DOC) <= 0){
            echo"<script language='javascript' type='text/javascript'>alert('Você não tem saldo disponível para Transferência.');window.location.href='transferencia_criar.php';</script>";
            $existe = false;
            die();
        }
    }
    else if ($tipo == "TED")
    {
    	$verifica_saldo_TED = mysql_query("SELECT * FROM CONTA WHERE NUMERO = $conta_origem AND SALDO >= $valor;") or die("erro ao selecionar");
        if (mysql_num_rows($verifica_saldo_TED) <= 0){
            echo"<script language='javascript' type='text/javascript'>alert('Você não tem saldo disponível para Transferência.');window.location.href='transferencia_criar.php';</script>";
            $existe = false;
            die();
        }
    }

    //Verifica dados DESTINO
    $verifica_conta_destino = mysql_query("SELECT * FROM CONTA WHERE NUMERO = $conta_destino;") or die("erro ao selecionar");
    if (mysql_num_rows($verifica_conta_destino) <= 0){
        echo"<script language='javascript' type='text/javascript'>alert('Esta conta destino não existe. Verifique a conta e tente novamente.');window.location.href='transferencia_criar.php';</script>";
        $existe = false;
        die();
    }

    $verifica_agencia_destino = mysql_query("SELECT * FROM CONTA WHERE NUMERO = $conta_destino AND AGENCIA_NUMERO = '$agencia_destino'") or die("erro ao selecionar");
    if (mysql_num_rows($verifica_agencia_destino) <= 0){
        echo"<script language='javascript' type='text/javascript'>alert('Esta agencia destino não existe nesta conta. Verifique a agência e tente novamente.');window.location.href='transferencia_criar.php';</script>";
        $existe = false;
        die();
    }

    $verifica_banco_destino = mysql_query("SELECT * FROM AGENCIA WHERE NUMERO = '$agencia_destino' AND BANCO_CODIGO = $banco_destino;") or die("erro ao selecionar");
    if (mysql_num_rows($verifica_banco_destino) <= 0){
        echo"<script language='javascript' type='text/javascript'>alert('Este banco destino não existe nesta agencia. Verifique a banco e tente novamente.');window.location.href='transferencia_criar.php';</script>";
        $existe = false;
        die();
    }

    if ($existe) {
        
        $sql = "INSERT INTO TRANSFERENCIA (NUMERO_CONTA_DESTINO, NUMERO_AGENCIA_DESTINO, VALOR_DESTINO, DATA_DESTINO, HORA_DESTINO, TIPO_MOVIMENTACAO_DESTINO, NUMERO_CONTA_ORIGEM, NUMERO_AGENCIA_ORIGEM, VALOR_ORIGEM, DATA_ORIGEM, HORA_ORIGEM, TIPO_MOVIMENTACAO_ORIGEM) VALUES ($conta_destino, '$agencia_destino', $valor, '$data', '$hora', '$tipo', $conta_origem, '$agencia_origem', $valor, '$data', '$hora', '$tipo')";
        $result = mysql_query($sql, $link);

        //Atualiza conta Origem
        $sql = "SELECT SALDO FROM CONTA WHERE SITUACAO = 1 AND NUMERO = $conta_origem AND AGENCIA_NUMERO = '$agencia_origem';";
        $saldo_conta_origem = mysql_query($sql, $link);
        $row_saldo_conta_origem = mysql_fetch_row($saldo_conta_origem);

        $saldo_atual_origem = $row_saldo_conta_origem['0'];
        $saldo_novo_origem = 0;
        if ($tipo == "DOC") {
        	$saldo_novo_origem = $saldo_atual_origem - $valor;
        }else if ($tipo == "TED") {
            $saldo_novo_origem = $saldo_atual_origem - $valor;
        }

        $sql = "UPDATE CONTA SET SALDO = $saldo_novo_origem WHERE NUMERO = $conta_origem AND AGENCIA_NUMERO = '$agencia_origem';";
        $result_valor_origem = mysql_query($sql, $link);

        //Atualiza conta Destino
        $sql = "SELECT SALDO FROM CONTA WHERE SITUACAO = 1 AND NUMERO = $conta_destino AND AGENCIA_NUMERO = '$agencia_destino';";
        $saldo_conta_destino = mysql_query($sql, $link);
        $row_saldo_conta_destino = mysql_fetch_row($saldo_conta_destino);

        $saldo_atual_destino = $row_saldo_conta_destino['0'];
        $saldo_novo_destino = 0;
        $saldo_novo_destino = $saldo_atual_destino + $valor;

        $sql = "UPDATE CONTA SET SALDO = $saldo_novo_destino WHERE NUMERO = $conta_destino AND AGENCIA_NUMERO = '$agencia_destino';";
        $result_valor_destino = mysql_query($sql, $link);

        if ($result) {
            header("Location: transferencia.php");
            exit();
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

    <title>Tranferência | Bancos Exemplo</title>

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
                    <li class="active">
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
                        <a class="navbar-brand" href="#">Transferência</a>
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
                                    <h4 class="title">Adicionar transferência</h4>
                                </div>
                                <div class="content">
                                    <form method="post" action="transferencia_criar.php">
                                        <div class="row">
                                            <h5>Conta Origem</h5>
                                            <hr />
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>
                                                        Conta Origem <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" name="txtContaOrigem" class="form-control border-input" placeholder="Digite aqui o número da conta do cliente" required />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Agência Origem <span class="text-danger">*</span></label>
                                                    <input type="text" name="txtAgenciaOrigem" class="form-control border-input" placeholder="Digite aqui o número da agência do cliente" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>
                                                        Banco Origem
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-control border-input" name="txtBancoOrigem" required>
                                                        <option value="">Selecione...</option>
                                                        <?php
                                                        while ($row = mysql_fetch_assoc($lista_banco_origem)) {
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
                                            <h5>Conta Destino</h5>
                                            <hr />
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>
                                                        Conta Destino <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="text" name="txtContaDestino" class="form-control border-input" placeholder="Digite aqui o número da conta do cliente" required />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Agência Destino <span class="text-danger">*</span></label>
                                                    <input type="text" name="txtAgenciaDestino" class="form-control border-input" placeholder="Digite aqui o número da agência do cliente" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>
                                                        Banco Destino
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-control border-input" name="txtBancoDestino" required>
                                                        <option value="">Selecione...</option>
                                                        <?php
                                                        while ($row = mysql_fetch_assoc($lista_banco_destino)) {
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
                                            <h5>Informações</h5>
                                            <hr />
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Valor <span class="text-danger">*</span></label>
                                                    <input type="text" name="txtValor" id="txtValor" class="form-control border-input" placeholder="Digite aqui o valor" required />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>
                                                        Tipo de Transação
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-control border-input" name="txtTipo">
                                                        <option value="">Selecione...</option>
                                                        <option value="DOC">DOC</option>
                                                        <option value="TED">TED</option>
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