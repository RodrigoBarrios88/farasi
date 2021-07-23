<?php
	include_once('html_fns_lista.php');
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
	$dia = $_REQUEST["dia"];
	$sit = $_REQUEST["sit"];
	//
	$columnas = $_REQUEST["columnas"];$pdf=new PDF('L','mm','Legal');  // si quieren el reporte horizontal$pdf->AddPage();
	$pdf->SetMargins(5,5,5);
	$pdf->Ln(2);

	$pdf->SetFont('Arial','B',12);
	$pdf->MultiCell(0, 5, 'REPORTE DE LISTAS DE CHEQUEO', 0 , 'L' , 0);
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
	$pdf->SetAligns($alineaciones_titulos);  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;// EN EL ARRAY, CADA DATO ES UNA COLUMNA, IGUAL SE HACE PARA INGRESAR LOS DATOS
	$pdf->SetFont('Arial','B',10);  // AQUI LE ASIGNO EL TIPO DE LETRA Y TAMA�O
	$pdf->SetFillColor(216,216,216);	// AQUI LE DOY EL COLOR DE FONDO DE LAS CELDAS
   $pdf->SetTextColor(0);  // AQUI LE DOY COLOR AL TEXTO

	for($i=0;$i<1;$i++){  // ESTE ES EL ENCABEZADO DE LA TABLA, 
		$pdf->Row($titulos);
	}

	////////////////////////////////////// CUERPO ///////////////////////////////////////////
	$pdf->SetWidths($anchos);  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;
	$pdf->SetAligns($alineaciones);  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;$ClsLis = new ClsLista();
	$result = $ClsLis->get_programacion($codigo,$lista,$sede,$sector,$area,$categoria,$dia,'',$sit);$i=1;
	if(is_array($result)){
		foreach($result as $row){
			$j = 1;
			//--
			if(is_array($columnas)){
				foreach($columnas as $col){
					$parametros = parametrosDinamicosPDF($col);
					$campo = $parametros['campo'];
					if($col == "list_codigo"){
						$arrcampos[$j] = '# '.Agrega_Ceros($row[$campo]);
					}else if($col == "list_situacion"){
						$campo = trim($row[$campo]);
						$arrcampos[$j] = ($campo == 1)?'Activa':'Inactiva';
					}else if($col == "list_fotos" || $col == "list_firma"){
						$campo = trim($row[$campo]);
						$arrcampos[$j] = ($campo == 1)?'Si':'No';
					}else if($col == "pro_dias"){
						$dias = "";
						$dia1 = trim($row["pro_dia_1"]);
						$dias.= ($dia1 == 1)?"Lun,":"";
						$dia2 = trim($row["pro_dia_2"]);
						$dias.= ($dia2 == 1)?"Mar,":"";
						$dia3 = trim($row["pro_dia_3"]);
						$dias.= ($dia3 == 1)?"Mie,":"";
						$dia4 = trim($row["pro_dia_4"]);
						$dias.= ($dia4 == 1)?"Jue,":"";
						$dia5 = trim($row["pro_dia_5"]);
						$dias.= ($dia5 == 1)?"Vie,":"";
						$dia6 = trim($row["pro_dia_6"]);
						$dias.= ($dia6 == 1)?"Sab,":"";
						$dia7 = trim($row["pro_dia_7"]);
						$dias.= ($dia7 == 1)?"Dom,":"";
						$diaMes = trim($row["pro_dia_mes"]);
						$dias .= ($diaMes != 0) ? "día $diaMes del mes " : ""; 
						$arrcampos[$j] = substr($dias,0,-1);
					}else if ($col == "pro_tipo") {
						$arrcampos[$j] = (trim($row["pro_tipo"]) == "S") ? "Semanal" : "Mensual";
					} else if($col == "pro_hini_hfin"){
						$arrcampos[$j] = trim($row["pro_hini"])."-".trim($row["pro_hfin"]);;
					}else{
						$arrcampos[$j] = utf8_decode($row[$campo]);
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