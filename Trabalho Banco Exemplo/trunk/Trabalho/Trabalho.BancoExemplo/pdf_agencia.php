<?php

session_start();

if (!isset($_SESSION['usuarioID']) OR !isset($_SESSION['usuarioNome'])) {
    header("Location:login.php");
}

$get_banco = $_GET['banco'];

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
$pdf->Cell(30, 6, 'CÓD BANCO', 1, 0, 'C', 1);
$pdf->Cell(30, 6, 'CÓD AGÊNCIA', 1, 0, 'C', 1);
$pdf->Cell(35, 6, 'ENDEREÇO', 1, 0, 'C', 1);
$pdf->Cell(35, 6, 'CIDADE', 1, 0, 'C', 1);
$pdf->Cell(20, 6, 'ESTADO', 1, 1, 'C', 1);

$y_axis = $y_axis + $row_height;

//Select the Products you want to show in your PDF file
$result=mysql_query("SELECT BANCO_CODIGO, NUMERO, ENDERECO, CIDADE, ESTADO FROM AGENCIA WHERE BANCO_CODIGO = $get_banco", $link);

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
        $pdf->Cell(30, 6, 'CÓD BANCO', 1, 0, 'C', 1);
        $pdf->Cell(30, 6, 'NÚMERO', 1, 0, 'C', 1);
        $pdf->Cell(35, 6, 'ENDEREÇO', 1, 0, 'C', 1);
        $pdf->Cell(35, 6, 'CIDADE', 1, 0, 'C', 1);
        $pdf->Cell(20, 6, 'ESTADO', 1, 1, 'C', 1);

        //Go to next row
        $y_axis = $y_axis + $row_height;

        //Set $i variable to 0 (first row)
        $i = 0;
    }

    $banco = $row['BANCO_CODIGO'];
    $agencia = $row['NUMERO'];
    $endereco = $row['ENDERECO'];
    $cidade = $row['CIDADE'];
    $estado = $row['ESTADO'];

    $pdf->SetY($y_axis);
    $pdf->SetX(25);
    $pdf->Cell(30, 6, $banco, 1, 0, 'C', 1);
    $pdf->Cell(30, 6, $agencia, 1, 0, 'C', 1);
    $pdf->Cell(35, 6, $endereco, 1, 0, 'C', 1);
    $pdf->Cell(35, 6, $cidade, 1, 0, 'C', 1);
    $pdf->Cell(20, 6, $estado, 1, 1, 'C', 1);

    //Go to next row
    $y_axis = $y_axis + $row_height;
    $i = $i + 1;
}

mysql_close($link);

//Send file
$pdf->Output();


?>