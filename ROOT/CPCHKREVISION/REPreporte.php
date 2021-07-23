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
	$usuario = $_REQUEST["usuario"];
	//--
	$mes = date("m");
	$anio = date("Y");
	$desde = $_REQUEST["desde"];
	$desde = ($desde == "")?date("d/m/Y"):$desde; //valida que si no se selecciona fecha, coloque la del dia
	$hasta = $_REQUEST["hasta"];
	$hasta = ($hasta == "")?date("d/m/Y"):$hasta; //valida que si no se selecciona fecha, coloque la del dia
	//
	$columnas = $_REQUEST["columnas"];$pdf=new PDF('L','mm','Legal');  // si quieren el reporte horizontal$pdf->AddPage();
	$pdf->SetMargins(5,5,5);
	$pdf->Ln(2);

	$pdf->SetFont('Arial','B',12);
	$pdf->MultiCell(0, 5, 'REPORTE DE REVISIONES', 0 , 'L' , 0);
	$pdf->SetFont('Arial','',12);
	$pdf->MultiCell(0, 6, 'Fecha/Hora de generacion: '.date("d/m/Y H:i"), 0 , 'L' , 0);
	$pdf->MultiCell(0, 5, 'Generado por: '.$nombre_sesion, 0 , 'L' , 0);
	$pdf->Image('../../CONFIG/img/logo.jpg' , 315 ,5, 30 , 30,'JPG', '');

	$pdf->Ln(10);
   ////////////////////////////////////// PARAMETROS ///////////////////////////////////////////
	///// FIRMAS Y FOTOS //
	$anexo_A = false;
	$anexo_B = false;
	$revisiones_in = "";
	///------------------
   
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
	$pdf->SetAligns($alineaciones);  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;$ClsRev = new ClsRevision();
	$result = $ClsRev->get_revision($codigo,$lista,$usuario,$sede,$sector,$area,$categoria,$desde,$hasta,'1,2');$i=1;
	if(is_array($result)){
		foreach($result as $row){
			$j = 1;
			//--
			if(is_array($columnas)){
				foreach($columnas as $col){
					$parametros = parametrosDinamicosPDF($col);
					$campo = $parametros['campo'];
					if($col == "rev_codigo"){
						$arrcampos[$j] = '# '.Agrega_Ceros($row[$campo]);
					}else if($col == "rev_fecha_inicio"){
						$arrcampos[$j] = cambia_fechaHora($row[$campo]);
					}else if($col == "rev_fecha_final"){
						$arrcampos[$j] = cambia_fechaHora($row[$campo]);
					}else if($col == "rev_situacion"){
						$campo = trim($row[$campo]);
						$arrcampos[$j] = ($campo == 1)?'En Proceso':'Finalizado';
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
					}else if($col == "pro_hini_hfin"){
						$arrcampos[$j] = trim($row["pro_hini"])."-".trim($row["pro_hfin"]);;
					}else if ($col == "pro_tipo") {
						$arrcampos[$j] = (trim($row["pro_tipo"]) == "S") ? "Semanal" : "Mensual";
					} else if($col == "rev_nota"){
						$si = $row['rev_cont_si'];
						$no = $row['rev_cont_no'];
						$na = $row['rev_cont_na'];
						$total_si = ($si + $na);
						$total_no = $no;
						$total_respuestas = $total_si + $total_no;
						if($total_respuestas > 0){
							$porcent_si = round(($total_si*100)/$total_respuestas);
							$porcent_no = round(($total_no*100)/$total_respuestas);
							//$total_na = round(($total_na*100)/$total_respuestas);
						}else{
							$porcent_si = 0;
							$porcent_si = 0;
							//$total_na = 0;
						}
						$campo = "$porcent_si %";
						$arrcampos[$j] = $campo;
					}else if($col == "rev_firma"){
						$arrcampos[$j] = 'Anexo A No. # '.Agrega_Ceros($row["rev_codigo"]);
						$anexo_A = true;
					}else if($col == "rev_foto"){
						$arrcampos[$j] = 'Anexo B No. # '.Agrega_Ceros($row["rev_codigo"]);
						$anexo_B = true;
						$revisiones_in.= $row["rev_codigo"].","; // trae los codigos de revisiones a los que se buscaran las fotos
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
	}//////////////////////////////////////////// ANEXO A (FIRMAS) ///////////////////////////////////////////////////////////////
	if($anexo_A == true){
		$pdf->AddPage();
		$pdf->SetAutoPageBreak(false,2);
		
		$pdf->SetMargins(5,5,5);
		$pdf->Ln(10);
		$pdf->SetFont('Arial','B',12);
		$pdf->MultiCell(0, 5, 'ANEXO A (FIRMAS)', 0 , 'L' , 0);
		$pdf->SetFont('Arial','',12);
		$pdf->MultiCell(0, 6, 'Fecha/Hora de generacion: '.date("d/m/Y H:i"), 0 , 'L' , 0);
		$pdf->MultiCell(0, 5, 'Generado por: '.$nombre_sesion, 0 , 'L' , 0);
		$pdf->Image('../../CONFIG/img/logo.jpg' , 315 ,5, 30 , 30,'JPG', '');
		$pdf->Ln(10);
		
		$pdf->SetFont('Arial','B',10);
		$X = 20;
		$Y = 45;
		$columna = 1;
		$ClsRev = new ClsRevision();
		$result = $ClsRev->get_revision($codigo,$lista,$usuario,$sede,$sector,$area,$categoria,$desde,$hasta,'1,2');
		if(is_array($result)){
			foreach($result as $row){
				if($columna == 1){
					$X = 20;
				}else if($columna == 2){
					$X = 100;
				}else if($columna == 3){
					$X = 180;
				}else if($columna == 4){
					$X = 260;
				}
				$revision = Agrega_Ceros($row["rev_codigo"]);
				$requiere_firma = trim($row["list_firma"]);
				$requiere_fotos = trim($row["list_fotos"]);
				$strFirma = trim($row["rev_firma"]);
				$pdf->SetXY($X,$Y-6);
				$pdf->Cell(60, 6, "No. # $revision", 0, 0, 'C');
				if(file_exists('../../CONFIG/Fotos/FIRMAS/'.$strFirma.'.jpg') && $strFirma != ""){
					$pdf->Image('../../CONFIG/Fotos/FIRMAS/'.$strFirma.'.jpg', $X, $Y, 70 , 40,'JPG', '');
				}else{
					$pdf->Image('../../CONFIG/img/imageSign.jpg', $X, $Y, 70 , 40,'JPG', '');
				}
				if($columna == 4){
					$columna = 1;
					$Y+= 50;
					if($Y >= 190){
						//$pdf->SetXY($X,$Y-6);
						//$pdf->Cell(60, 6, "Aqui son $Y (1)", 1, 0, 'C');
						$pdf->AddPage();
						$pdf->SetAutoPageBreak(false,2);
						$pdf->SetMargins(5,5,5);
						$Y = 20;
					}
				}else{
					$columna++;
				}
			}
		}
	}
	//////////////////////////////////////////// ANEXO B (FOTOS) ///////////////////////////////////////////////////////////////
	if($anexo_B == true){
		$pdf->AddPage();
		$pdf->SetAutoPageBreak(false,2);
		
		$pdf->SetMargins(5,5,5);
		$pdf->Ln(10);
		$pdf->SetFont('Arial','B',12);
		$pdf->MultiCell(0, 5, 'ANEXO B (FOTOS)', 0 , 'L' , 0);
		$pdf->SetFont('Arial','',12);
		$pdf->MultiCell(0, 6, 'Fecha/Hora de generacion: '.date("d/m/Y H:i"), 0 , 'L' , 0);
		$pdf->MultiCell(0, 5, 'Generado por: '.$nombre_sesion, 0 , 'L' , 0);
		$pdf->Image('../../CONFIG/img/logo.jpg' , 315 ,5, 30 , 30,'JPG', '');
		$pdf->Ln(10);
		
		$pdf->SetFont('Arial','B',10);
		$X = 20;
		$Y = 45;
		$columna = 1;
		$revisiones_in = substr($revisiones_in,0,-1);
		$ClsRev = new ClsRevision();
		$result = $ClsRev->get_fotos('',$revisiones_in);
		if(is_array($result)){
			foreach($result as $row){
				if($columna == 1){
					$X = 20;
				}else if($columna == 2){
					$X = 100;
				}else if($columna == 3){
					$X = 180;
				}else if($columna == 4){
					$X = 260;
				}
				$revision = Agrega_Ceros($row["fot_revision"]);
				$strFoto = trim($row["fot_foto"]);
				$pdf->SetXY($X,$Y-6);
				$pdf->Cell(60, 6, "No. # $revision", 0, 0, 'C');
				if(file_exists('../../CONFIG/Fotos/REVISION/'.$strFoto.'.jpg') && $strFoto != ""){
					$pdf->Image('../../CONFIG/Fotos/REVISION/'.$strFoto.'.jpg', $X, $Y, 70 , 40,'JPG', '');
				}else{
					$pdf->Image('../../CONFIG/img/imagePhoto.jpg', $X, $Y, 70 , 40,'JPG', '');
				}
				if($columna == 4){
					$columna = 1;
					$Y+= 50;
					if($Y >= 190){
						//$pdf->SetXY($X,$Y-6);
						//$pdf->Cell(60, 6, "Aqui son $Y (1)", 1, 0, 'C');
						$pdf->AddPage();
						$pdf->SetAutoPageBreak(false,2);
						$pdf->SetMargins(5,5,5);
						$Y = 20;
					}
				}else{
					$columna++;
				}
			}
		}
	}
	$pdf->Output();?>