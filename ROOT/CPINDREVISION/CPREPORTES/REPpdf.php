<?php
//Incluir las librerias de FPDF 
include_once('html_fns_reportes.php');
validate_login("../");
$nombre_usuario = utf8_decode($_SESSION["nombre"]);
//$_POST
$ClsRev = new ClsRevision();
$id = $_SESSION["codigo"];
$hashkey = $_REQUEST["hashkey"];
$codigo = $ClsRev->decrypt($hashkey, $id);
//--
$result = $ClsRev->get_revision_indicador($codigo, "", "", "", "", "", 2);
//var_dump($result);
if (is_array($result)) {
    foreach ($result as $row) {
        $lectura = trim($row["rev_lectura"]);
        $observaciones = utf8_decode($row["rev_observaciones"]);
        $fecha = trim($row["rev_fecha_final"]);
        $fecha = cambia_fechaHora($fecha);
        $codigo_indicador = trim($row["ind_codigo"]);
        $nombre = utf8_decode($row["ind_nombre"]);
        $descripcion = utf8_decode($row["ind_descripcion"]);
        $descripcion = ($descripcion == "") ? "N/A" : $descripcion;
        $usuario_nombre = utf8_decode($row["ind_usuario"]);
        $usuario_anota = utf8_decode($row["rev_usuario"]);
        $objetivo = utf8_decode($row["obj_descripcion"]);
        $sistema = utf8_decode($row["obj_sistema"]);
        $proceso = utf8_decode($row["obj_proceso"]);
        $min = trim($row["ind_lectura_minima"]);
        $max = trim($row["ind_lectura_maxima"]);
        $ideal = trim($row["ind_lectura_ideal"]);
        $usuario = utf8_decode($row["ind_usuario"]);
        $unidad = utf8_decode($row["medida_nombre"]);
        $observacion = nl2br(utf8_decode($row["pro_observaciones"]));
        $arrArchivos = get_archivos(1, $codigo);
    }
}

// INICIA ESCRITURA DE PDF 
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AddPage();
$pdf->SetAutoPageBreak(false, 2);


$pdf->SetMargins(5, 5, 5);
$pdf->Ln(2);

$pdf->SetFont('Arial', '', 10);
$pdf->SetX(10);
$pdf->MultiCell(195, 5, utf8_decode('Fecha/Hora de impresión: ') . date("d/m/Y H:i"), 0, 'L', 0);
$pdf->SetX(10);
$pdf->MultiCell(195, 5, 'Impreso por: ' . utf8_decode($_SESSION["nombre"]), 0, 'L', 0);
$pdf->Image('../../../CONFIG/img/logo.jpg', 185, 10, 15, 15, 'JPG', '');
$pdf->SetFillColor(236, 240, 244);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY(10, 30);
$pdf->Cell(92.5, 10, utf8_decode('Toma de Datos'), 0, 0, 'C', 1);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY(112.5, 30);
$pdf->Cell(92.5, 10, utf8_decode('Código # ') . Agrega_Ceros($codigo), 0, 0, 'C', 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY(10, 45);
$pdf->MultiCell(92.5, 6, utf8_decode('Información'), 'B', 'C');

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY(112.5, 45);
$pdf->MultiCell(92.5, 6, utf8_decode('Planificación'), 'B', 'C');

$pdf->SetTextColor(0, 0, 0); // LETRA COLOR NEGRO
//----------- IZQUIERDA ------//
$pdf->SetFont('Arial', '', 10);
//---
$pdf->SetFillColor(236, 240, 244);
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(10, 56);
$pdf->Cell(20, 6, utf8_decode('Usuario:'), 0, 0, 'L');
$pdf->SetXY(30, 56);
$pdf->Cell(72.5, 6, $usuario, 0, 0, 'L', 1);

$pdf->SetFillColor(245, 247, 249);
$pdf->SetXY(10, 62);
$pdf->Cell(20, 6, utf8_decode('Indicador:'), 0, 0, 'L');
$pdf->SetXY(30, 62);
$pdf->Cell(72.5, 6, $nombre, 0, 0, 'L', 1);

$pdf->SetFillColor(236, 240, 244);
$pdf->SetXY(10, 68);
$pdf->Cell(20, 6, utf8_decode('Proceso:'), 0, 0, 'L');
$pdf->SetXY(30, 68);
$pdf->Cell(72.5, 6, $proceso, 0, 0, 'L', 1);

$pdf->SetFillColor(245, 247, 249);
$pdf->SetXY(10, 74);
$pdf->Cell(20, 6, utf8_decode('Sistema:'), 0, 0, 'L');
$pdf->SetXY(30, 74);
$pdf->Cell(72.5, 6, $sistema, 0, 0, 'L', 1);

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 247, 249);
$pdf->SetXY(10, 84);


//----------- DERECHA ------//
$pdf->SetFont('Arial', '', 10);
//---
$pdf->SetFillColor(236, 240, 244);
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(112.5, 56);
$pdf->Cell(20, 6, utf8_decode('Unidad de Medida:'), 0, 0, 'L');
$pdf->SetXY(145, 56);
$pdf->Cell(72.5, 6, $unidad, 0, 0, 'L', 1);

$pdf->SetFillColor(245, 247, 249);
$pdf->SetXY(112.5, 62);
$pdf->Cell(20, 6, utf8_decode('Lectura Mínima:'), 0, 0, 'L');
$pdf->SetXY(145, 62);
$pdf->Cell(72.5, 6, $min, 0, 0, 'L', 1);

$pdf->SetFillColor(236, 240, 244);
$pdf->SetXY(112.5, 68);
$pdf->Cell(20, 6, utf8_decode('Lectura Ideal:'), 0, 0, 'L');
$pdf->SetXY(145, 68);
$pdf->Cell(72.5, 6, $ideal, 0, 0, 'L', 1);

$pdf->SetFont('Arial', '', 9);
$pdf->SetFillColor(245, 247, 249);
$pdf->SetXY(112.5, 74);
$pdf->Cell(20, 6, utf8_decode('Lectura Máxima:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(145, 74);
$pdf->Cell(72.5, 6, "$max", 0, 0, 'L', 1);

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 247, 249);
$pdf->SetXY(112.5, 84);
$Y += 10;

$Y = $pdf->getY();
//---
$pdf->SetFillColor(236, 240, 244);
$pdf->SetFont('Arial', '', 11);
$pdf->SetXY(10, $Y);
$pdf->Cell(195, 6, utf8_decode('Objetivo:'), 0, 0, 'L');
$Y += 5;
$pdf->SetXY(10, $Y);
$pdf->MultiCell(195, 6, $objetivo, 0, 'J', true);
$Y += 13;

$pdf->SetFillColor(236, 240, 244);
$pdf->SetFont('Arial', '', 11);
$pdf->SetXY(10, $Y);
$pdf->Cell(195, 6, utf8_decode('Indicador:'), 0, 0, 'L');
$Y += 5;
$pdf->SetXY(10, $Y);
$pdf->MultiCell(195, 6, $descripcion, 0, 'J', true);
$Y += 10;

$pdf->SetFillColor(236, 240, 244);
$pdf->SetFont('Arial', '', 11);
$pdf->SetXY(10, $Y);
$pdf->Cell(195, 6, utf8_decode('Observaciones Especiales:'), 0, 0, 'L');
$Y += 5;
$pdf->SetXY(10, $Y);
$pdf->MultiCell(195, 6, $observacion, 0, 'J', true);
$Y += 10;


/////------- ACTIVO ---------
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY(10, $Y);
$pdf->MultiCell(92.5, 6, utf8_decode('Información de la toma de datos'), 'B', 'L');
$Y += 10;
//----- FILA 1 ------//
$pdf->SetFont('Arial', '', 10);
//---
$pdf->SetFillColor(236, 240, 244);
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(10, $Y);
$pdf->Cell(20, 6, utf8_decode('Lectura:'), 0, 0, 'L');
$pdf->SetXY(40, $Y);
$pdf->Cell(72.5, 6, $lectura, 0, 0, 'L', 1);

//---
$pdf->SetFillColor(236, 240, 244);
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(112.5, $Y);
$pdf->Cell(20, 6, utf8_decode('Fecha/Hora:'), 0, 0, 'L');
$pdf->SetXY(132.5, $Y);
$pdf->Cell(72.5, 6, $fecha, 0, 0, 'L', 1);

$Y += 5;
//----- FILA 2 ------//
$pdf->SetFillColor(236, 240, 244);
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(10, $Y);
$pdf->Cell(195, 6, utf8_decode('Observaciones:'), 0, 0, 'L');
$Y += 5;
$pdf->SetXY(10, $Y);
$pdf->MultiCell(195, 6, $observaciones, 0, 'J', true);
//--
$Y += 10;


$Y += 30;
if ($Y > 250) {
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(false, 2);
    $Y = 20;
}
$Y += 40;

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetXY(67.5, $Y);
$pdf->Cell(70, 6, $nombre_usuario, 'T', 0, 'C');
$Y += 5;
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(67.5, $Y);
$pdf->Cell(70, 6, utf8_decode('Firma'), 0, 0, 'C');


////// ------ FOOTER ----------------
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetXY(10, 264);
$pdf->Cell(10, 6, 'NOTA:', 0, 0, 'L');

$pdf->SetFont('Arial', '', 6);
$pdf->SetXY(20, 265);
$nota = 'Este documento es únicamente una herramienta para utlizar en lugares donde no se puede ingresar con dispositivos elctrónicos. La información debe ser registrada en el sistema en durante el horario habilitado de la lista al finalizar para ser valido, de lo contrario el sistema lo tomara como un incumplimiento. ';
$pdf->MultiCell(150, 3, utf8_decode($nota), 0, 'J', false);

$pdf->SetFont('Arial', 'I', 8);
$pdf->SetXY(175, 265);
$pdf->Cell(30, 6, date("d/m/Y H:i:s"), 0, 0, 'R');


//Salida de PDF, en esta parte se puede definir la salida, si es a pantalla o forzar la descarga
$codigo = Agrega_Ceros($codigo);
$pdf->Output("Toma de Datos $codigo.pdf", "I");
