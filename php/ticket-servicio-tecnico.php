<?php
include "../fpdf/fpdf.php";
include('../phpqrcode/qrlib.php'); 

include 'conexion.php';
include '../generales.php';
require "../NumeroALetras.php";


$sql="SELECT tr.`id`, `dni`, `razon_social`, `celular`, tr.`fecha`, `idSubFamilia`, `marca`, `modelo`, `color`, `serie`, `estado`, u.usuNombres, fs.subfamilia, td.diagnostico, etapa, t.usuNombres as nomTecnico, monto
FROM `tecnico_recepcion` tr
inner join familia_sub fs on fs.id = tr.idSubFamilia
inner join usuario u on u.idUsuario = tr.idUsuario
left join tecnico_monto tm on tm.idRecepcion = tr.id
left join tecnico_diagnostico td on td.idRecepcion = tr.id
left join usuario t on t.idUsuario = td.idUsuario
WHERE tr.`id` = {$_GET['id']};";
$resultado=$cadena->query($sql);
//echo $sql;
$filas=array();
$row=$resultado->fetch_assoc();

$soy="Ticket de Servicio Técnico";

//Extraido de https://evilnapsis.com/2018/04/26/php-formato-de-ticket-basico-para-impresoras-de-tickets-con-fpdf/
$pdf = new FPDF($orientation='P',$unit='mm', array(75, ( 20 * ( $row['etapa']+1) )+ 60 )); //N° lineas * 6 + 60 espacio minimo //75 es los mm de ancho
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);    //Letra Arial, negrita (Bold), tam. 20
$textypos = 5;
$pdf->setY(2);
$pdf->setX(2);
$pdf->Image('../images/empresa_black.png', 20, 0, -300);
$pdf->setY(23);
$pdf->Ln();

$pdf->Cell( 0, $textypos, utf8_decode($soy), 0, 0, 'C');$pdf->Ln();$pdf->setX(2);
$pdf->SetFont('Arial','',10);    //Letra Arial, negrita (Bold), tam. 20
$pdf->Cell( 0, $textypos, utf8_decode('Cliente: '.$row['razon_social'] ), 0, 0 );$pdf->Ln();$pdf->setX(2);
$pdf->Cell( 0, $textypos, utf8_decode('Celular: '.$row['celular'] ), 0, 0 );$pdf->Ln();$pdf->Ln();$pdf->setX(2);
$pdf->MultiCell( 0, 4, utf8_decode("Equipo: {$row['marca']} {$row['modelo']} {$row['color']} . Serie: {$row['serie']}"), 0 );$pdf->Ln();$pdf->setX(2);
$pdf->MultiCell( 0, 4, utf8_decode('Estado inicial: '. $row['estado']), 0 );$pdf->Ln();$pdf->setX(2);

if( intval($row['etapa']) >1 ):
	$pdf->MultiCell( 0, 4, utf8_decode('Técnico: '. $row['nomTecnico']), 0 );$pdf->Ln();$pdf->setX(2);
	$pdf->MultiCell( 0, 4, utf8_decode('Diagnóstico: '. $row['diagnostico']), 0 );$pdf->Ln();$pdf->setX(2);
endif;
if( intval($row['etapa']) > 2 ):
	$pdf->MultiCell( 0, 4, utf8_decode('Monto: S/ '. number_format($row['monto'], 2) ), 0 );$pdf->Ln();$pdf->setX(2);
endif;
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->Line( $x+6, $y, $x+60, $y  );$pdf->Ln();$pdf->setX(2);
$pdf->MultiCell( 0, 4, utf8_decode('Gracias por su preferencia' ), 0, 'C' );$pdf->Ln();$pdf->setX(2);

$pdf->output();
