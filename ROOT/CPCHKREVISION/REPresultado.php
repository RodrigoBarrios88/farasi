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
	$periodo = $_REQUEST["periodo"];
	$situacion = "1,2";
	//--
	$fini = $_REQUEST["desde"];
	$ffin = $_REQUEST["hasta"];if($periodo == "D"){
		$titulo = "Reporte d�a a d�a del $fini al $ffin";
	}else if($periodo == "S"){
		$titulo = "Reporte semana a semana del $fini al $ffin";
	}else if($periodo == "M"){
		$titulo = "Reporte mes a mes del $fini al $ffin";
	}$ClsCat = new ClsCategoria();
	$result = $ClsCat->get_categoria_checklist($categoria,'',1);
	$categorias_nombre = "";
	if(is_array($result)){
		foreach($result as $row){
			$categorias_nombre.= utf8_decode($row["cat_nombre"]).", ";
		}
		$categorias_nombre = substr($categorias_nombre, 0, -2);
	}	$pdf=new PDF('L','mm','Letter');  // si quieren el reporte horizontal$pdf->AddPage();
	$pdf->SetMargins(5,5,5);
	$pdf->Ln(2);

	$pdf->SetFont('Arial','B',12);
	$pdf->MultiCell(0, 5, trim('REPORTE PERI�DICO DE RESULTADOS'), 0 , 'L' , 0);
	$pdf->SetFont('Arial','',12);
	$pdf->MultiCell(0, 6, 'Fecha/Hora de generacion: '.date("d/m/Y H:i"), 0 , 'L' , 0);
	$pdf->MultiCell(0, 5, 'Generado por: '.$nombre_sesion, 0 , 'L' , 0);
	$pdf->Image('../../CONFIG/img/logo.jpg' , 315 ,5, 30 , 30,'JPG', '');
	$pdf->Ln(5);
	$pdf->SetFont('Arial','B',12);
	$pdf->MultiCell(0, 5, trim($titulo), 0 , 'L' , 0);
	$pdf->SetFont('Arial','',12);
	$pdf->MultiCell(0, 5, trim("Reporte Conjunto, categor�as calculadas: ")." ".$categorias_nombre, 0 , 'L' , 0);
	$pdf->Ln(5);//////////////// ECABEZADOS DE TABLA /////////////////////////////////////////
	$pdf->SetWidths(array(10, 75, 45, 45, 45, 45));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C'));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNA// EN EL ARRAY, CADA DATO ES UNA COLUMNA, IGUAL SE HACE PARA INGRESAR LOS DATOS
	$pdf->SetFont('Arial','B',10);  // AQUI LE ASIGNO EL TIPO DE LETRA Y TAMA�O
	$pdf->SetFillColor(216,216,216);	// AQUI LE DOY EL COLOR DE FONDO DE LAS CELDAS
    $pdf->SetTextColor(0);  // AQUI LE DOY COLOR AL TEXTO

	for($i=0;$i<1;$i++){  // ESTE ES EL ENCABEZADO DE LA TABLA, 
		$pdf->Row(array('No.', '', 'Respuestas SI', 'Respuestas NO','Respuestas N/A','% Cumplimiento'));
	}

	$pdf->SetWidths(array(10, 75, 45, 45, 45, 45));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;
	$pdf->SetAligns(array('C', 'L', 'C', 'C', 'C', 'C'));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNA$ClsRev = new ClsRevision();
	$dia_inicio = "";
	$SI = 0;
	$NO = 0;
	$NA = 0;
	$total = 0;
	$num = 1;
	$TOTALSI = 0;
	$TOTALNO = 0;
	$TOTALNA = 0;
	if($periodo == "D"){
		$fechaInicio = strtotime(regresa_fecha($fini));
		$fechaFin = strtotime(regresa_fecha($ffin));
		for($i = $fechaInicio; $i <= $fechaFin; $i+=86400){
			$fecha = date("d/m/Y", $i);
			$dia = date("w", $i);
			$dia = ($dia == 0)?7:$dia;
			$dia_nombre = Dias_Letra($dia);
			$SI = $ClsRev->count_resultados('','','',$sede,$sector,$area,$categoria,$fecha,$fecha,$situacion,1);
			$NO = $ClsRev->count_resultados('','','',$sede,$sector,$area,$categoria,$fecha,$fecha,$situacion,2);
			$NA = $ClsRev->count_resultados('','','',$sede,$sector,$area,$categoria,$fecha,$fecha,$situacion,3);
			$total_si = $SI;
			$total_no = $NO;
			$total = $total_si + $total_no;
			if($total > 0){
				$porcentaje = round(($total_si*100)/$total);
			}else{
				$porcentaje = 0;
			}
			$TOTALSI+= $SI;
			$TOTALNO+= $NO;
			$TOTALNA+= $NA;
			//--
			$pdf->SetFont('Arial','',10);   // ASIGNO EL TIPO Y TAMA�O DE LA LETRA
			$pdf->SetFillColor(255,255,255);	// AQUI LE DOY EL COLOR DE FONDO DE LAS CELDAS
			$pdf->SetTextColor(0);  // LE ASIGNO EL COLOR AL TEXTO
			$no = $num.".";
			$pdf->Row(array($no,"$dia_nombre $fecha",$SI,$NO,$NA,"$porcentaje %")); // AGREGO LOS DATOS A LA FILA, VIENE REPERESENTADO POR UN ARRAY 
			$num++;
		}
	}else if($periodo == "S"){
		$fini = regresa_fecha($fini);
		$ffin = regresa_fecha($ffin);
		$anio1 = substr($fini, 0, 4);
		$anio2 = substr($ffin, 0, 4);
		if($anio1 == $anio2){
			$W1 = date("W", strtotime( date($fini) ));
			$W2 = date("W", strtotime( date($ffin) ));
			$dia_inicio = date("w", strtotime($fini));
			$num = 1;
			for($i = $W1; $i <= $W2; $i++){
				$fecha_ini = daysOfWeek($anio1,$i,1);
				$fecha_fin = daysOfWeek($anio1,($i+1),0);
				$fecha_ini = cambia_fecha($fecha_ini);
				$fecha_fin = cambia_fecha($fecha_fin);
				$SI = $ClsRev->count_resultados('','','',$sede,$sector,$area,$categoria,$fecha_ini,$fecha_fin,$situacion,1);
				$NO = $ClsRev->count_resultados('','','',$sede,$sector,$area,$categoria,$fecha_ini,$fecha_fin,$situacion,2);
				$NA = $ClsRev->count_resultados('','','',$sede,$sector,$area,$categoria,$fecha_ini,$fecha_fin,$situacion,3);
				$total_si = $SI;
				$total_no = $NO;
				$total = $total_si + $total_no;
				if($total > 0){
					$porcentaje = round(($total_si*100)/$total);
				}else{
					$porcentaje = 0;
				}
				$TOTALSI+= $SI;
				$TOTALNO+= $NO;
				$TOTALNA+= $NA;
				//--
				$pdf->SetFont('Arial','',10);   // ASIGNO EL TIPO Y TAMA�O DE LA LETRA
				$pdf->SetFillColor(255,255,255);	// AQUI LE DOY EL COLOR DE FONDO DE LAS CELDAS
				$pdf->SetTextColor(0);  // LE ASIGNO EL COLOR AL TEXTO
				$no = $num.".";
				$pdf->Row(array($no,"Semana $i ($fecha_ini al $fecha_fin)",$SI,$NO,$NA,"$porcentaje %")); // AGREGO LOS DATOS A LA FILA, VIENE REPERESENTADO POR UN ARRAY 
				$num++;
			}
		}else{
			$pdf->SetFont('Arial','B',12);  	// ASIGNO EL TIPO Y TAMA�O DE LA LETRA
			$pdf->SetFillColor(216,216,216);
			$pdf->Cell(265,5,trim('Las fechas deben pertenecer al mismo a�o...'),1,'','C',true);
		}
	}else if($periodo == "M"){
		$fini = regresa_fecha($fini);
		$ffin = regresa_fecha($ffin);
		$mes1 = substr($fini, 5, 2);
		$mes2 = substr($ffin, 5, 2);
		//--
		$anio1 = substr($fini, 0, 4);
		$anio2 = substr($ffin, 0, 4);
		if($anio1 == $anio2){
			$num = 1;
			for($i = $mes1; $i <= $mes2; $i++){
				$mes_nombre = Meses_Letra($i);
				$fecha_ini = "01/$i/$anio1";
				$fecha_fin = "31/$i/$anio1";
				//echo "$mes_nombre: $fecha_ini - $fecha_fin<br>";
				$SI = $ClsRev->count_resultados('','','',$sede,$sector,$area,$categoria,$fecha_ini,$fecha_fin,$situacion,1);
				$NO = $ClsRev->count_resultados('','','',$sede,$sector,$area,$categoria,$fecha_ini,$fecha_fin,$situacion,2);
				$NA = $ClsRev->count_resultados('','','',$sede,$sector,$area,$categoria,$fecha_ini,$fecha_fin,$situacion,3);
				$total_si = $SI;
				$total_no = $NO;
				$total = $total_si + $total_no;
				if($total > 0){
					$porcentaje = round(($total_si*100)/$total);
				}else{
					$porcentaje = 0;
				}
				$TOTALSI+= $SI;
				$TOTALNO+= $NO;
				$TOTALNA+= $NA;
				//--
				$pdf->SetFont('Arial','',10);   // ASIGNO EL TIPO Y TAMA�O DE LA LETRA
				$pdf->SetFillColor(255,255,255);	// AQUI LE DOY EL COLOR DE FONDO DE LAS CELDAS
				$pdf->SetTextColor(0);  // LE ASIGNO EL COLOR AL TEXTO
				$no = $num.".";
				$pdf->Row(array($no,$mes_nombre,$SI,$NO,$NA,"$porcentaje %")); // AGREGO LOS DATOS A LA FILA, VIENE REPERESENTADO POR UN ARRAY 
				$num++;
			}
		}else{
			$pdf->SetFont('Arial','B',12);  	// ASIGNO EL TIPO Y TAMA�O DE LA LETRA
			$pdf->SetFillColor(216,216,216);
			$pdf->Cell(265,5,trim('Las fechas deben pertenecer al mismo a�o...'),1,'','C',true);
		}
	}//////////////// TOTALES DE TABLA /////////////////////////////////////////
	$pdf->SetWidths(array(10, 75, 45, 45, 45, 45));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;
	$pdf->SetAligns(array('C', 'R', 'C', 'C', 'C', 'C'));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNA// EN EL ARRAY, CADA DATO ES UNA COLUMNA, IGUAL SE HACE PARA INGRESAR LOS DATOS
	$pdf->SetFont('Arial','B',10);  // AQUI LE ASIGNO EL TIPO DE LETRA Y TAMA�O
	$pdf->SetFillColor(216,216,216);	// AQUI LE DOY EL COLOR DE FONDO DE LAS CELDAS
    $pdf->SetTextColor(0);  // AQUI LE DOY COLOR AL TEXTO$TOTAL = $TOTALSI + $TOTALNO;
	if($TOTAL > 0){
		$PORCENTAJE = round(($TOTALSI*100)/$TOTAL);
	}else{
		$PORCENTAJE = 0;
	}

	for($i=0;$i<1;$i++){  // ESTE ES EL ENCABEZADO DE LA TABLA, 
		$pdf->Row(array('', ' Totales ', $TOTALSI, $TOTALNO, $TOTALNA, "$PORCENTAJE %"));
	}
	$pdf->Output();?>