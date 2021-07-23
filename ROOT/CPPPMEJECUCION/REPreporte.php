<?php
	include_once('html_fns_ejecucion.php');
	validate_login("../");
$id = $_SESSION["codigo"];
	$nombre_sesion = utf8_decode($_SESSION["nombre"]);
	$rol = $_SESSION["rol"];
	$rol_nombre = utf8_decode($_SESSION["rol_nombre"]);
	$foto = $_SESSION["foto"];
	//$_POST
	$sede = $_REQUEST["sede"];
	$area = $_REQUEST["area"];
	$activo = $_REQUEST["activo"];
	$usuario = $_REQUEST["usuario"];
	$situacion = $_REQUEST["situacion"];
	$desde = $_REQUEST["desde"];
	$hasta = $_REQUEST["hasta"];
	//
	$columnas = $_REQUEST["columnas"];
	$titulo = "REPORTE DE TAREAS";$pdf=new PDF('L','mm','Legal');  // si quieren el reporte horizontal$pdf->AddPage();
	$pdf->SetMargins(5,5,5);
	$pdf->Ln(2);

	$pdf->SetFont('Arial','B',12);
	$pdf->MultiCell(0, 5, $titulo, 0 , 'L' , 0);
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
	$pdf->SetAligns($alineaciones);  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNA$situacion = ($situacion == 5)?1:$situacion;
	$ClsPro = new ClsProgramacionPPM();
	$result = $ClsPro->get_programacion($codigo,$activo,$usuario,'',$sede, '', $area, $desde, $hasta,'','',$situacion);$i=1;
	if(is_array($result)){
		foreach($result as $row){
			$j = 1;
			//--
			if(is_array($columnas)){
				foreach($columnas as $col){
					$parametros = parametrosDinamicosPDF($col);
					$campo = $parametros['campo'];
					if($col == "pro_codigo"){
						$arrcampos[$j] = '# '.Agrega_Ceros($row[$campo]);
					}else if($col == "pro_foto1" || $col == "pro_foto2" || $col == "pro_firma"){
						$arrcampos[$j] = trim($row[$campo]).".jpg";
					}else if($col == "pro_fecha"){
						$arrcampos[$j] = cambia_fechaHora($row["pro_fecha"]);
					}else if($col == "pro_fecha_update"){
						$arrcampos[$j] = cambia_fechaHora($row["pro_fecha_update"]);
					}else if($col == "pro_presupuesto_programado" || $col == "pro_presupuesto_ejecutado"){
						$arrcampos[$j] = trim($row["mon_simbolo"]).'. '.trim($row[$campo]);
					}else if($col == "pro_situacion"){
						//conteo de dias
						$programado = trim($row["pro_fecha"]);
						$ahora = date("Y-m-d");
						$vencimiento = comparaFechas($programado, $ahora);
						$situacion = $row["pro_situacion"];
						if($situacion == 1){
							if($vencimiento == 1){ // Falta para que se cumpla
								$arrcampos[$j] = 'Programado';
							}else if($vencimiento == 2){ // ya se vencio
								$arrcampos[$j] = 'Vencido';
							}else{ // hoy corresponde
								$arrcampos[$j] = 'Para Hoy';
							}
						}else if($situacion == 2){
							$arrcampos[$j] = 'En Espera';
						}else if($situacion == 3){
							$arrcampos[$j] = 'En Proceso';
						}else if($situacion == 4){
							$arrcampos[$j] = 'Finalizado';
						}
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
	$pdf->Output($titulo,"I");?>