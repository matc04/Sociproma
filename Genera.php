
<?php
//PDF USING MULTIPLE PAGES
//CREATED BY: Carlos Vasquez S.
//E-MAIL: cvasquez@cvs.cl
//CVS TECNOLOGIA E INNOVACION
//SANTIAGO, CHILE

require('fpdf.php');

$link = mysql_connect("localhost", "root", "mt2212") or die ("ERRRRRO");
mysql_select_db("anestesia", $link) or die("EN ANestesia");

//Create new pdf file
$pdf=new FPDF();

//Disable automatic page break
$pdf->SetAutoPageBreak(false);

//Add first page
$pdf->AddPage();

//set initial y axis position per page
$y_axis_initial = 25;

//print column titles
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',12);
$pdf->SetY($y_axis_initial);
$pdf->SetX(25);
$pdf->Cell(30,6,'CODE',1,0,'L',1);
$pdf->Cell(100,6,'NAME',1,0,'L',1);
$pdf->Cell(30,6,'PRICE',1,0,'R',1);

//Set Row Height
$row_height = 6;
$y_axis = 0;

$y_axis = $y_axis + $row_height;

//Select the Products you want to show in your PDF file
$result=mysql_query('select num_recibo,id_doctor_cirujano, monto_total from paciente_intervencion where num_recibo = 4452',$link);

//initialize counter
$i = 0;

//Set maximum rows per page
$max = 25;


while($row = mysql_fetch_object($result))
{
    //If the current row is the last one, create new page and print column title
    if ($i == $max)
    {
        $pdf->AddPage();

        //print column titles for the current page
        $pdf->SetY($y_axis_initial);
        $pdf->SetX(25);
        $pdf->Cell(30,6,'CODE',1,0,'L',1);
        $pdf->Cell(100,6,'NAME',1,0,'L',1);
        $pdf->Cell(30,6,'PRICE',1,0,'R',1);
        
        //Go to next row
        $y_axis = $y_axis + $row_height;
        
        //Set $i variable to 0 (first row)
        $i = 0;
    }

    $code = $row->num_recibo;
    $price = $row->id_doctor_cirujano;
    $name = $row->monto_total;

    $pdf->SetY($y_axis);
    $pdf->SetX(25);
    $pdf->Cell(30,6,$code,1,0,'L',1);
    $pdf->Cell(100,6,$name,1,0,'L',1);
    $pdf->Cell(30,6,$price,1,0,'R',1);

    //Go to next row
    $y_axis = $y_axis + $row_height;
    $i = $i + 1;
}

mysql_close($link);

//Send file
$pdf->Output();
?>

