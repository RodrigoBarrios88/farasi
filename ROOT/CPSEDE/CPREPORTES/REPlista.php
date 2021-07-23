<?php

include_once('../../html_fns.php');
	validate_login("../");
$id = $_SESSION["codigo"];
	$nombre = utf8_decode($_SESSION["nombre"]);
	$nivel = $_SESSION["nivel"];
	//post
	$nom = trim($_REQUEST["nom"]);
	$dep = trim($_REQUEST["dep"]);
	$mun = trim($_REQUEST["mun"]);
	$contac = trim($_REQUEST["contac"]);
	$sit = trim($_REQUEST["sit"]);$pdf=new PDF('L','mm','Legal');  // si quieren el reporte horizontal$pdf->AddPage();
	$pdf->SetMargins(5,5,5);
	$pdf->Ln(2);

	$pdf->SetFont('Arial','B',12);
	$pdf->MultiCell(0, 5, 'REPORTE DE SEDES', 0 , 'L' , 0);
	$pdf->SetFont('Arial','',12);
	$pdf->MultiCell(0, 6, 'Fecha/Hora de generacion: '.date("d/m/Y H:i"), 0 , 'L' , 0);
	$pdf->MultiCell(0, 5, 'Generado por: '.$nombre_sesion, 0 , 'L' , 0);
	$pdf->Image('../../../CONFIG/img/logo.jpg' , 315 ,5, 30 , 30,'JPG', '');$pdf->Ln(10);$pdf->SetWidths(array(10, 15, 90, 130, 80, 20));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C'));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNA// EN EL ARRAY, CADA DATO ES UNA COLUMNA, IGUAL SE HACE PARA INGRESAR LOS DATOS
	$pdf->SetFont('Arial','B',10);  // AQUI LE ASIGNO EL TIPO DE LETRA Y TAMA�O
	$pdf->SetFillColor(216,216,216);	// AQUI LE DOY EL COLOR DE FONDO DE LAS CELDAS
    $pdf->SetTextColor(0);  // AQUI LE DOY COLOR AL TEXTO

	for($i=0;$i<1;$i++){  // ESTE ES EL ENCABEZADO DE LA TABLA, 
		$pdf->Row(array('No.', '# COD.', 'NOMBRE', utf8_decode('DIRECCI�N'),'COORDENADAS','SIT.'));
	}

	$pdf->SetWidths(array(10, 15, 90, 130, 80, 20));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;
	$pdf->SetAligns(array('C', 'C', 'L', 'L', 'L', 'C', 'C'));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNA$ClsSed = new ClsSede();
	$result = $ClsSed->get_sede($cod,$nom,$dep,$mun,$sit);$i=1;
	if(is_array($result)){
		foreach($result as $row){
			$cod = trim($row["sed_codigo"]);
			$codigo = "# ".Agrega_Ceros($cod);
			//nombre
			$nom = trim($row["sed_nombre"]);
			//direccion
			$dir = trim($row["sed_direccion"]);
			//telefono 1
			$coord = Trim($row["sed_latitud"])." | ".Trim($row["sed_longitud"]);
			//situacion
			$sit = $row["sed_situacion"];
			$sit = ($sit == 1)?"ACTIVA":"INACTIVA";
			//---
			$pdf->SetFont('Arial','',10);   // ASIGNO EL TIPO Y TAMA�O DE LA LETRA
			$pdf->SetFillColor(255,255,255);	// AQUI LE DOY EL COLOR DE FONDO DE LAS CELDAS
			$pdf->SetTextColor(0);  // LE ASIGNO EL COLOR AL TEXTO
			$no = $i.".";
			$pdf->Row(array($no,$codigo,$nom,$dir,$coord,$sit)); // AGREGO LOS DATOS A LA FILA, VIENE REPERESENTADO POR UN ARRAY 
			$i++;															// IGUAL QUE EL ENCABEZADO, Y ESTO SE HACE POR CADA REGISTRO
		}
		$i--;
			$pdf->SetFont('Arial','B',10);  	// ASIGNO EL TIPO Y TAMA�O DE LA LETRA
			$pdf->SetFillColor(216,216,216);
			$pdf->Cell(345,5,$i.' Registro(s).',1,'','R',true);	// AQUI ASIGNO UNA CELDA DEL ANCHO DE LA TABLA PARA PONER LA CANTIDAD DE REGISTROS
			
	}$pdf->SetDisplayMode(real,'default'); 
	$pdf->Output();?>