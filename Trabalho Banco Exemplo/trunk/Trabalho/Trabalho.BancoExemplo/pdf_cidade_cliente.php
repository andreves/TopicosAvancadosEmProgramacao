<?php

session_start();

if (!isset($_SESSION['usuarioID']) OR !isset($_SESSION['usuarioNome'])) {
    header("Location:login.php");
}

$get_cidade = $_GET['cidade'];

//define('FPDF_FONTPATH', 'font/');
require('fpdf/fpdf.php');

//Connect to your database
include("conexao.php");

//Create new pdf file
$pdf=new FPDF();

//Disable automatic page break
$pdf->SetAutoPageBreak(false);

//Add first page
$pdf->AddPage();

//set initial y axis position per page
$y_axis_initial = 25;

//print column titles
$pdf->SetFillColor(232, 232, 232);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetY($y_axis_initial);
$pdf->SetX(25);
$pdf->Cell(25, 6, 'CIDADE', 1, 0, 'C', 1);
$pdf->Cell(25, 6, 'BANCO', 1, 0, 'C', 1);
$pdf->Cell(25, 6, 'AGENCIA', 1, 0, 'C', 1);
$pdf->Cell(25, 6, 'CLIENTE', 1, 0, 'C', 1);
$pdf->Cell(25, 6, 'RG', 1, 0, 'C', 1);
$pdf->Cell(25, 6, 'ENDERECO', 1, 1, 'C', 1);

$y_axis = $y_axis + $row_height;

//Select the Products you want to show in your PDF file
$result=mysql_query("SELECT c.CIDADE AS CITY, b.NOME AS BANCO_NOME, a.NOME AS AGENCIA_NOME, c.NOME AS NAME, c.RG AS RG_CLIENTE, c.ENDERECO AS ENDERECO_CLIENTE FROM CONTA cc
JOIN CLIENTE c ON c.CPF = cc.CLIENTE_CPF
JOIN AGENCIA a ON a.NUMERO = cc.AGENCIA_NUMERO
JOIN BANCO b ON b.CODIGO = a.BANCO_CODIGO
WHERE c.CIDADE = '$get_cidade'", $link);

//initialize counter
$i = 0;

//Set maximum rows per page
$max = 25;

//Set Row Height
$row_height = 6;

while($row = mysql_fetch_array($result))
{
    //If the current row is the last one, create new page and print column title
    if ($i == $max)
    {
        $pdf->AddPage();

        //print column titles for the current page
        $pdf->SetY($y_axis_initial);
        $pdf->SetX(25);
        $pdf->Cell(25, 6, 'CIDADE', 1, 0, 'C', 1);
        $pdf->Cell(25, 6, 'BANCO', 1, 0, 'C', 1);
        $pdf->Cell(25, 6, 'AGENCIA', 1, 0, 'C', 1);
        $pdf->Cell(25, 6, 'CLIENTE', 1, 0, 'C', 1);
        $pdf->Cell(25, 6, 'RG', 1, 0, 'C', 1);
        $pdf->Cell(25, 6, 'ENDERECO', 1, 1, 'C', 1);

        //Go to next row
        $y_axis = $y_axis + $row_height;

        //Set $i variable to 0 (first row)
        $i = 0;
    }

    $cidade = $row['CITY'];
    $banco = $row['BANCO_NOME'];
    $agencia = $row['AGENCIA_NOME'];
    $nome = $row['NAME'];
    $rg = $row['RG_CLIENTE'];
    $endereco = $row['ENDERECO_CLIENTE'];

    $pdf->SetY($y_axis);
    $pdf->SetX(25);
    $pdf->Cell(25, 6, $cidade, 1, 0, 'C', 1);
    $pdf->Cell(25, 6, $banco, 1, 0, 'C', 1);
    $pdf->Cell(25, 6, $agencia, 1, 0, 'C', 1);
    $pdf->Cell(25, 6, $nome, 1, 0, 'C', 1);
    $pdf->Cell(25, 6, $rg, 1, 0, 'C', 1);
    $pdf->Cell(25, 6, $endereco, 1, 1, 'C', 1);

    //Go to next row
    $y_axis = $y_axis + $row_height;
    $i = $i + 1;
}

mysql_close($link);

//Send file
$pdf->Output();


?>