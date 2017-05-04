<?php

session_start();

if (!isset($_SESSION['usuarioID']) OR !isset($_SESSION['usuarioNome'])) {
    header("Location:login.php");
}

$get_id = $_GET['id'];

require('fpdf/fpdf.php');

include("conexao.php");

$result=mysql_query("SELECT dr.`DATA`, dr.HORA, c.NOME, c.RG, b.NOME, a.NOME, dr.NUMERO_CONTA, dr.VALOR, dr.CODIGO_BANCO, dr.NUMERO_AGENCIA FROM DEPOSITO_RETIRADA dr JOIN CLIENTE c ON c.RG = dr.RG_CLIENTE JOIN AGENCIA a ON a.NUMERO = dr.NUMERO_AGENCIA JOIN BANCO b ON b.CODIGO = dr.CODIGO_BANCO WHERE ID = $get_id", $link);
$row = mysql_fetch_row($result);

$pdf = new FPDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Courier','',12);
//for($i=1;$i<=10;$i++)
//    $pdf->Cell(0,6,'Printing line number '.$i,0,1);

$pdf->Cell(0,6,'Recibo de Saque',0,1);
$pdf->Cell(0,6,$row[0].' - '.$row[1],0,1);
$pdf->Cell(0,6,'=========================================',0,1);
$pdf->Cell(0,6,'---------------FAVORECIDO----------------',0,1);
$pdf->Cell(0,6,'=========================================',0,1);
//$pdf->Cell(0,6,'Cliente: '.$row[2],0,1);
$pdf->Cell(0,6,'RG: '.$row[3],0,1);
$pdf->Cell(0,6,'Banco: '.$row[4].' - '.$row[8],0,1);
$pdf->Cell(0,6,'Agência: '.$row[5].' - '.$row[9],0,1);
$pdf->Cell(0,6,'Conta: '.$row[6],0,1);
$pdf->Cell(0,6,'=========================================',0,1);
$pdf->Cell(0,6,'Valor: R$ '.$row[7],0,1);
$pdf->Cell(0,6,'=========================================',0,1);
$pdf->Output();

?>