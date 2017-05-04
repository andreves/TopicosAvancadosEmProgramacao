<?php

session_start();

if (!isset($_SESSION['usuarioID']) OR !isset($_SESSION['usuarioNome'])) {
    header("Location:login.php");
}

//define('FPDF_FONTPATH', 'font/');
require('fpdf/fpdf.php');

//Connect to your database
include("conexao.php");

//Create new pdf file
//$pdf=new FPDF('P', 'cm', 'A4');
$pdf=new FPDF();

//Disable automatic page break
$pdf->SetAutoPageBreak(false);

//$pdf->Open();

//Add first page
$pdf->AddPage();

//set initial y axis position per page
$y_axis_initial = 25;

//print column titles
$pdf->SetFillColor(232, 232, 232);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetY($y_axis_initial);
$pdf->SetX(25);
//$pdf->Cell(30, 6, 'CÓDIGO', 1, 0, 'C', 1);
//$pdf->Cell(40, 6, 'NOME', 1, 0, 'C', 1);
//$pdf->Cell(80, 6, 'CNPJ', 1, 1, 'C', 1);
$pdf->Cell(100, 6, 'NOME', 1, 0, 'C', 1);
$pdf->Cell(50, 6, 'CÓDIGO', 1, 1, 'C', 1);

$y_axis = $y_axis + $row_height;

//Select the Products you want to show in your PDF file
$result=mysql_query('SELECT CODIGO, NOME FROM BANCO ORDER BY NOME', $link);

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
        //$pdf->Cell(30, 6, 'CÓDIGO', 1, 0, 'C', 1);
        //$pdf->Cell(40, 6, 'NOME', 1, 0, 'C', 1);
        //$pdf->Cell(80, 6, 'CNPJ', 1, 1, 'C', 1);
        $pdf->Cell(100, 6, 'NOME', 1, 0, 'C', 1);
        $pdf->Cell(50, 6, 'CÓDIGO', 1, 1, 'C', 1);

        //Go to next row
        $y_axis = $y_axis + $row_height;

        //Set $i variable to 0 (first row)
        $i = 0;
    }

    $name = $row['NOME'];
    $code = $row['CODIGO'];

    $pdf->SetY($y_axis);
    $pdf->SetX(25);
    $pdf->Cell(100, 6, $name, 1, 0, 'C', 1);
    $pdf->Cell(50, 6, $code, 1, 1, 'C', 1);

    //Go to next row
    $y_axis = $y_axis + $row_height;
    $i = $i + 1;
}

mysql_close($link);

//Send file
$pdf->Output();


?>