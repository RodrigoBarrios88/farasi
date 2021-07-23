<?php
//Incluir las librerias de FPDF ddddd
include_once('html_fns_reportes.php');
validate_login("../");
$nombre_usuario = utf8_decode($_SESSION["nombre"]);
//$_POST
$ClsRev = new ClsRevision();
$id = $_SESSION["codigo"];
$hashkey = $_REQUEST["hashkey"];

$codigo = $ClsRev->decrypt($hashkey, $id);
$origen = $_REQUEST['origen'];
// obtiene 
$Clshall = new ClsHallazgo();
$ClsPla = new ClsPlan();
switch ($origen) {
	case 1:
		$result = $Clshall->get_hallazgo_auditoria_interna($codigo);
		break;
	case 2:
		$result = $Clshall->get_hallazgo_auditoria_externa($codigo);
		break;
	case 3:
		$result = $Clshall->get_hallazgo_queja($codigo);
		break;
	case 4:
		$result = $Clshall->get_hallazgo_indicador($codigo);
		break;
	case 5:
		$result = $Clshall->get_hallazgo_riesgo($codigo);
		break;
	case 6:
		$result = $Clshall->get_hallazgo_requisito($codigo);
		break;
}
if (is_array($result)) {
    foreach ($result as $row) {
		$proceso = utf8_decode($row["fic_nombre"]);
		$sistema = utf8_decode($row["sis_nombre"]);
		$hallazgo = utf8_decode($row["hal_descripcion"]);
		$tipo = get_tipo($row["hal_tipo"]);
		$origen = get_origen($row["hal_origen"]);
		$fecha = cambia_fechaHora($row["hal_fecha"]);
		$usuario = utf8_decode($row["usu_nombre"]);

	}
    $plan = $ClsPla->get_plan_mejora("", $codigo, "", $id);
 
	if (is_array($plan)) {
		foreach ($plan as $row) {
			$plan = trim($row["pla_codigo"]);
		}
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
$pdf->SetXY(10, 35);
$pdf->Cell(195, 6, utf8_decode('Analisis de causa Raiz'), 10, 0, 'C', true);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY(10, 45);
$pdf->Cell(92.5, 6, utf8_decode('Información'), 'B', 'C');

$pdf->SetTextColor(0, 0, 0); // LETRA COLOR NEGRO
//----------- IZQUIERDA ------//
$pdf->SetFont('Arial', '', 10);
//---
$pdf->SetFillColor(236, 240, 244);
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(10, 56);
$pdf->Cell(20, 6, utf8_decode('Responsable:'), 0, 0, 'L');
$pdf->SetXY(35, 56);
$pdf->Cell(60, 6, $nombre_usuario, 0, 0, 'L', 1);

$pdf->SetFillColor(245, 247, 249);
$pdf->SetXY(10, 62);
$pdf->Cell(20, 6, utf8_decode('Proceso:'), 0, 0, 'L');
$pdf->SetXY(35, 62);
$pdf->Cell(60, 6, $proceso, 0, 0, 'L', 1);

$pdf->SetFillColor(236, 240, 244);
$pdf->SetXY(10, 68);
$pdf->Cell(20, 6, utf8_decode('Sistema:'), 0, 0, 'L');
$pdf->SetXY(35, 68);
$pdf->Cell(60, 6, $sistema, 0, 0, 'L', 1);

$pdf->SetFillColor(245, 247, 249);
$pdf->SetXY(10, 74);
$pdf->Cell(20, 6, utf8_decode('Tipo:'), 0, 0, 'L');
$pdf->SetXY(35, 74);
$pdf->Cell(60, 6, $tipo, 0, 0, 'L', 1);

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 247, 249);
$pdf->SetXY(10, 84);


//----------- DERECHA ------//
$pdf->SetFont('Arial', '', 10);
//---
$pdf->SetFillColor(236, 240, 244);
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(112.5, 56);
$pdf->Cell(20, 6, utf8_decode('Fecha:'), 0, 0, 'L');
$pdf->SetXY(145, 56);
$pdf->Cell(60, 6, $fecha, 0, 0, 'L', 1);

$pdf->SetFillColor(245, 247, 249);
$pdf->SetXY(112.5, 62);
$pdf->Cell(20, 6, utf8_decode('Usuario Registra:'), 0, 0, 'L');
$pdf->SetXY(145, 62);
$pdf->Cell(60, 6, $usuario, 0, 0, 'L', 1);


$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(245, 247, 249);
$pdf->SetXY(112.5, 84);
$Y += 10;
$Y = $pdf->getY();


$pdf->SetFillColor(236, 240, 244);
$pdf->SetFont('Arial', '', 11);
$pdf->SetXY(10, $Y);
$pdf->Cell(195, 6, utf8_decode('Origen:'), 0, 0, 'L');
$Y += 5;
$pdf->SetXY(10, $Y);
$pdf->MultiCell(195, 6, $origen, 0, 'J', true);
$Y += 10;

$Y += 15;

/////---------- Actividades ----------------
$pdf->SetXY(10, $Y);
$pdf->SetWidths(array(25, 170));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;
$pdf->SetAligns(array('C', 'l'));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNA// EN EL ARRAY, CADA DATO ES UNA COLUMNA, IGUAL SE HACE PARA INGRESAR LOS DATOS
$pdf->SetFont('Arial', 'B', 10);  // AQUI LE ASIGNO EL TIPO DE LETRA Y TAMA�O
$pdf->SetFillColor(216, 216, 216);	// AQUI LE DOY EL COLOR DE FONDO DE LAS CELDAS
$pdf->SetTextColor(0);  // AQUI LE DOY COLOR AL TEXTO

// ESTE ES EL ENCABEZADO DE LA TABLA, 
$pdf->Row(array('Codigo', 'Causa'));

////////////////////////////////////// CUERPO ///////////////////////////////////////////
$pdf->SetWidths(array(25, 170));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;
$pdf->SetAligns(array('L', 'L'));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNA$ClsUsu = new ClsUsuario();
$ClsCau = new ClsCausa();
//var_dump($plan);
$result = $ClsCau->get_causa("", $plan);
$i = 1;
if (is_array($result)) {
	foreach ($result as $row) {
		//codigo
		$codigo = $row["cau_codigo"];
		$codigo = "# " . Agrega_Ceros($codigo);
		//descripcion
		$nombre = utf8_decode($row["cau_descripcion"]);
		
		$pdf->SetFont('Arial', '', 10);   // ASIGNO EL TIPO Y TAMA�O DE LA LETRA
		$pdf->SetFillColor(255, 255, 255);	// AQUI LE DOY EL COLOR DE FONDO DE LAS CELDAS
		$pdf->SetTextColor(0);  // LE ASIGNO EL COLOR AL TEXTO
		$no = $i . ".";
		$pdf->SetX(10);
		$pdf->Row(array($codigo, $nombre)); // AGREGO LOS DATOS A LA FILA, VIENE REPERESENTADO POR UN ARRAY 
		$i++;															// IGUAL QUE EL ENCABEZADO, Y ESTO SE HACE POR CADA REGISTRO
	}
	////////////////////////////////////// PIE DE REPORTE ///////////////////////////////////////////
	$i--;
	$pdf->SetFont('Arial', 'B', 10);  	// ASIGNO EL TIPO Y TAMA�O DE LA LETRA
	$pdf->SetFillColor(216, 216, 216);
	$pdf->SetX(10);
	$pdf->Cell(195, 5, $i . ' Registro(s).', 1, '', 'R', true);	// AQUI ASIGNO UNA CELDA DEL ANCHO DE LA TABLA PARA PONER LA CANTIDAD DE REGISTROS

} else {
	$pdf->SetFont('Arial', '', 10);  	// ASIGNO EL TIPO Y TAMA�O DE LA LETRA
	$pdf->SetFillColor(255, 255, 255);	// AQUI LE DOY EL COLOR DE FONDO DE LAS CELDAS
	$pdf->Cell(270, 5, 'No se Reportan Datos.', 1, '', 'C', true);	// AQUI ASIGNO UNA CELDA DEL ANCHO DE LA TABLA PARA PONER LA CANTIDAD DE REGISTROS

	$y = $pdf->GetY();
	$y += 5;
	// Put the position to the right of the cell
	$pdf->SetXY(5, $y);
	//footer
	$pdf->SetFont('Arial', 'B', 10);  	// ASIGNO EL TIPO Y TAMA�O DE LA LETRA
	$pdf->SetFillColor(216, 216, 216);
	$pdf->Cell(270, 5, '0 Registro(s).', 1, '', 'R', true);	// AQUI ASIGNO UNA CELDA DEL ANCHO DE LA TABLA PARA PONER LA CANTIDAD DE REGISTROS
}
$pdf->Output("Reporte de Actividades.pdf", "I");


$Y += 35;
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
$pdf->Cell(35, 6, date("d/m/Y H:i:s"), 0, 0, 'R');


//Salida de PDF, en esta parte se puede definir la salida, si es a pantalla o forzar la descarga
$codigo = Agrega_Ceros($codigo);
$pdf->Output("Toma de Datos $codigo.pdf", "I");
