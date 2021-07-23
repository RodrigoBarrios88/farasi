<?php

	include_once('html_fns_revision.php');
	validate_login("../");
$id = $_SESSION["codigo"];
	$nombre_sesion = utf8_decode($_SESSION["nombre"]);
	$rol = $_SESSION["rol"];
	$rol_nombre = utf8_decode($_SESSION["rol_nombre"]);
	$foto = $_SESSION["foto"];
	//$_POST
	$sede = $_REQUEST["sede"];
	$sector = $_REQUEST["sector"];
	$area = $_REQUEST["area"];
	$categoria = $_REQUEST["categoria"];
	$revision = $_REQUEST["revision"];
	//--
	$desde = $_REQUEST["desde"];
	$hasta = $_REQUEST["hasta"];$ClsCat = new ClsCategoria();
	$result = $ClsCat->get_categoria_checklist($categoria,'',1);
	$categorias_nombre = "";
	if(is_array($result)){
		foreach($result as $row){
			$categorias_nombre.= utf8_decode($row["cat_nombre"]).", ";
		}
		$categorias_nombre = substr($categorias_nombre, 0, -2);
	}$pdf=new PDF('L','mm','Legal');  // si quieren el reporte horizontal$pdf->AddPage();
	$pdf->SetMargins(5,5,5);
	$pdf->Ln(2);

	$pdf->SetFont('Arial','B',12);
	$pdf->MultiCell(0, 5, 'REPORTE DE RESPUESTAS', 0 , 'L' , 0);
	$pdf->SetFont('Arial','',12);
	$pdf->MultiCell(0, 6, 'Fecha/Hora de generacion: '.date("d/m/Y H:i"), 0 , 'L' , 0);
	$pdf->MultiCell(0, 5, 'Generado por: '.$nombre_sesion, 0 , 'L' , 0);
	$pdf->Image('../../CONFIG/img/logo.jpg' , 315 ,5, 30 , 30,'JPG', '');
	$pdf->Ln(5);
	$pdf->SetFont('Arial','B',12);
	$pdf->MultiCell(0, 5, trim($titulo), 0 , 'L' , 0);
	$pdf->SetFont('Arial','',12);
	$pdf->MultiCell(0, 5, trim("Reporte Conjunto, categor�as calculadas: ")." ".$categorias_nombre, 0 , 'L' , 0);
	$pdf->Ln(5);$pdf->SetWidths(array(10, 20, 60, 50, 45, 130, 30));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C'));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNA// EN EL ARRAY, CADA DATO ES UNA COLUMNA, IGUAL SE HACE PARA INGRESAR LOS DATOS
	$pdf->SetFont('Arial','B',10);  // AQUI LE ASIGNO EL TIPO DE LETRA Y TAMA�O
	$pdf->SetFillColor(216,216,216);	// AQUI LE DOY EL COLOR DE FONDO DE LAS CELDAS
	$pdf->SetTextColor(0);  // AQUI LE DOY COLOR AL TEXTO

	for($i=0;$i<1;$i++){  // ESTE ES EL ENCABEZADO DE LA TABLA, 
		$pdf->Row(array('No.', trim('#Revisi�n'), 'Lista', trim('Categor�a'), 'Fecha', 'Pregunta', 'Respuesta'));
	}

	$pdf->SetWidths(array(10, 20, 60, 50, 45, 130, 30));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;
	$pdf->SetAligns(array('C', 'C', 'L', 'L', 'C', 'L', 'C'));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNA$ClsRev = new ClsRevision();
	$result = $ClsRev->get_resultados($revision,'','',$sede, $sector, $area, $categoria,$desde,$hasta);$i=1;
	if(is_array($result)){
		foreach($result as $row){
			//nombre
			$codigo = Agrega_Ceros($row["rev_codigo"]);
			//lista
			$lista = utf8_decode($row["list_nombre"]);
			//categoria
			$categoria = utf8_decode($row["cat_nombre"]);
			//fecha
			$fecha = cambia_fechaHora($row["resp_fecha_registro"]);
			//pregunta
			$pregunta = utf8_decode($row["pre_pregunta"]);
			//respuesta
			$resp = trim($row["resp_respuesta"]);
			if($resp == 1){
				$respuesta = 'SI';
			}else if($resp == 2){
				$respuesta = 'NO';
			}else{
				$respuesta = 'No aplica';
			}
			$pdf->SetFont('Arial','',10);   // ASIGNO EL TIPO Y TAMA�O DE LA LETRA
			$pdf->SetFillColor(255,255,255);	// AQUI LE DOY EL COLOR DE FONDO DE LAS CELDAS
			$pdf->SetTextColor(0);  // LE ASIGNO EL COLOR AL TEXTO
			$no = $i.".";
			$pdf->Row(array($no,$codigo,$lista,$categoria,$fecha,$pregunta,$respuesta)); // AGREGO LOS DATOS A LA FILA, VIENE REPERESENTADO POR UN ARRAY 
			$i++;			
		}
		$i--;
		$pdf->SetFont('Arial','B',10);  	// ASIGNO EL TIPO Y TAMA�O DE LA LETRA
		$pdf->SetFillColor(216,216,216);
		$pdf->Cell(345,5,$i.' Registro(s).',1,'','R',true);	// AQUI ASIGNO UNA CELDA DEL ANCHO DE LA TABLA PARA PONER LA CANTIDAD DE REGISTROS
	}$pdf->Output();?>