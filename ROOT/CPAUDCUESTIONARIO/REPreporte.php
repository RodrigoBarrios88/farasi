<?php
	include_once('html_fns_cuestionario.php');
	validate_login("../");
$id = $_SESSION["codigo"];
	$nombre_sesion = utf8_decode($_SESSION["nombre"]);
	$rol = $_SESSION["rol"];
	$rol_nombre = utf8_decode($_SESSION["rol_nombre"]);
	$foto = $_SESSION["foto"];
	//$_POST
	$sede = $_REQUEST["sede"];
	$departamento = $_REQUEST["departamento"];
	$categoria = $_REQUEST["categoria"];
	$desde = $_REQUEST["desde"];
	$hasta = $_REQUEST["hasta"];
	$situacion = $_REQUEST["situacion"];
	//
	$columnas = $_REQUEST["columnas"];$pdf=new PDF('L','mm','Legal');  // si quieren el reporte horizontal$pdf->AddPage();
	$pdf->SetMargins(5,5,5);
	$pdf->Ln(2);

	$pdf->SetFont('Arial','B',12);
	$pdf->MultiCell(0, 5, 'REPORTE DE PROGRAMACI�N DE AUDITOR�AS', 0 , 'L' , 0);
	$pdf->SetFont('Arial','',12);
	$pdf->MultiCell(0, 6, 'Fecha/Hora de generacion: '.date("d/m/Y H:i"), 0 , 'L' , 0);
	$pdf->MultiCell(0, 5, 'Generado por: '.$nombre_sesion, 0 , 'L' , 0);
	$pdf->Image('../../CONFIG/img/logo.jpg' , 315 ,5, 30 , 30,'JPG', '');

	$pdf->Ln(10);
   ////////////////////////////////////// PARAMETROS ///////////////////////////////////////////
   $anchos = array("10");
   $alineaciones_titulos = array("C");
   $alineaciones = array("C");
   $titulos = array("No.");
   $campos = array();
	$i = 1;
	$ancho_total = 10;
	if(is_array($columnas)){
		foreach($columnas as $col){
			$parametros = parametrosDinamicosPDF($col);
			$anchos[$i] = $parametros['ancho'];
			$alineaciones_titulos[$i] = 'C';
			$alineaciones[$i] = $parametros['alineacion'];
			$titulos[$i] = utf8_decode($parametros['titulo']);
			$ancho_total+= $parametros['ancho']; 
			$i++;
		}
   }
	$i--;
   
   //print_r($titulos);	////////////////////////////////////// ENCABEZADOS ///////////////////////////////////////////
	$pdf->SetWidths($anchos);  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;
	$pdf->SetAligns($alineaciones_titulos);  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNA// EN EL ARRAY, CADA DATO ES UNA COLUMNA, IGUAL SE HACE PARA INGRESAR LOS DATOS
	$pdf->SetFont('Arial','B',10);  // AQUI LE ASIGNO EL TIPO DE LETRA Y TAMA�O
	$pdf->SetFillColor(216,216,216);	// AQUI LE DOY EL COLOR DE FONDO DE LAS CELDAS
   $pdf->SetTextColor(0);  // AQUI LE DOY COLOR AL TEXTO

	for($i=0;$i<1;$i++){  // ESTE ES EL ENCABEZADO DE LA TABLA, 
		$pdf->Row($titulos);
	}

	////////////////////////////////////// CUERPO ///////////////////////////////////////////
	$pdf->SetWidths($anchos);  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;
	$pdf->SetAligns($alineaciones);  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNA$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_programacion($codigo,$auditoria,$sede,$departamento,$categoria,$desde,$hasta,'','',$situacion,'');$i=1;
	if(is_array($result)){
		foreach($result as $row){
			$j = 1;
			//--
			if(is_array($columnas)){
				foreach($columnas as $col){
					$parametros = parametrosDinamicosPDF($col);
					$arrcampos[$j] = $parametros['campo'];
					if($col == "audit_codigo"){
						$arrcampos[$j] = '# '.Agrega_Ceros($row[$col]);
					}else if($col == "pro_situacion"){
						$campo = trim($row[$col]);
						$arrcampos[$j] = ($campo == 1)?'Pendiente':'Ejecutada';
					}else if($col == "audit_ponderacion"){
						$tipo = trim($row["audit_ponderacion"]);
						switch($tipo){
							case 1: $campo = "1-10"; break;
							case 2: $campo = "SI, NO, N/A"; break;
							case 3: $campo = "SAT, NO SAT"; break;
						}
						$arrcampos[$j] = $campo;
					}else if($col == "pro_fecha"){
						$fecha = cambia_fecha($row["pro_fecha"]);
						$hora = substr($row["pro_hora"],0,5);
						$arrcampos[$j] = "$fecha $hora";
					}else{
						$arrcampos[$j] = utf8_decode($row[$col]);
					}
					$j++;
				}
			}
			//---
			$pdf->SetFont('Arial','',8);   // ASIGNO EL TIPO Y TAMA�O DE LA LETRA
			$pdf->SetFillColor(255,255,255);	// AQUI LE DOY EL COLOR DE FONDO DE LAS CELDAS
			$pdf->SetTextColor(0);  // LE ASIGNO EL COLOR AL TEXTO
			$arrcampos[0] = $i.".";
			$pdf->Row($arrcampos); // AGREGO LOS DATOS A LA FILA, VIENE REPERESENTADO POR UN ARRAY 
			$i++;		
		}	////////////////////////////////////// PIE DE REPORTE ///////////////////////////////////////////
		$i--; //quita la uultima vuelta
			$pdf->SetFont('Arial','B',10);  	// ASIGNO EL TIPO Y TAMA�O DE LA LETRA
			$pdf->SetFillColor(216,216,216);
			$pdf->Cell($ancho_total,5,$i.' Registro(s).',1,'','R',true);	// AQUI ASIGNO UNA CELDA DEL ANCHO DE LA TABLA PARA PONER LA CANTIDAD DE REGISTROS
			
	}else{
		$pdf->SetFont('Arial','',10);  	// ASIGNO EL TIPO Y TAMA?O DE LA LETRA
		$pdf->SetFillColor(255,255,255);	// AQUI LE DOY EL COLOR DE FONDO DE LAS CELDAS
		$pdf->Cell($ancho_total,5,'No se Reportan Datos.',1,'','C',true);	// AQUI ASIGNO UNA CELDA DEL ANCHO DE LA TABLA PARA PONER LA CANTIDAD DE REGISTROS
		
		$y=$pdf->GetY();
		$y+=5;
		// Put the position to the right of the cell
		$pdf->SetXY(5,$y);
		//footer
		$pdf->SetFont('Arial','B',10);  	// ASIGNO EL TIPO Y TAMA�O DE LA LETRA
		$pdf->SetFillColor(216,216,216);
		$pdf->Cell($ancho_total,5,'0 Registro(s).',1,'','R',true);	// AQUI ASIGNO UNA CELDA DEL ANCHO DE LA TABLA PARA PONER LA CANTIDAD DE REGISTROS
	} 
	$pdf->Output();?>