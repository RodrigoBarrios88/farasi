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
	$periodo = $_REQUEST["periodo"];
	$fini = $_REQUEST["desde"];
	$ffin = $_REQUEST["hasta"];
	//if($periodo == "A"){
		$titulo = "Reporte actividad por actividad del $fini al $ffin";
	}else if($periodo == "D"){
		$titulo = "Reporte d�a a d�a del $fini al $ffin";
	}else if($periodo == "S"){
		$titulo = "Reporte semana a semana del $fini al $ffin";
	}else if($periodo == "M"){
		$titulo = "Reporte mes a mes del $fini al $ffin";
	}$pdf=new PDF('L','mm','Letter');  // si quieren el reporte horizontal$pdf->AddPage();
	$pdf->SetMargins(5,5,5);
	$pdf->Ln(2);

	$pdf->SetFont('Arial','B',12);
	$pdf->MultiCell(0, 5, trim('REPORTE PERI�DICO DE EJECUCI�N PRESUPUESTARIA'), 0 , 'L' , 0);
	$pdf->SetFont('Arial','',12);
	$pdf->MultiCell(0, 6, 'Fecha/Hora de generacion: '.date("d/m/Y H:i"), 0 , 'L' , 0);
	$pdf->MultiCell(0, 5, 'Generado por: '.$nombre_sesion, 0 , 'L' , 0);
	$pdf->Image('../../CONFIG/img/logo.jpg' , 315 ,5, 30 , 30,'JPG', '');
	$pdf->Ln(5);
	$pdf->SetFont('Arial','B',12);
	$pdf->MultiCell(0, 5, trim($titulo), 0 , 'L' , 0);
	$pdf->Ln(5);//////////////// ECABEZADOS DE TABLA /////////////////////////////////////////
	if($periodo == "A"){
	$pdf->SetWidths(array(10, 55, 45, 45, 30, 30, 30, 25));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;
	}else{
	$pdf->SetWidths(array(10, 75, 45, 45, 45, 45));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;
	$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;	
	}// EN EL ARRAY, CADA DATO ES UNA COLUMNA, IGUAL SE HACE PARA INGRESAR LOS DATOS
	$pdf->SetFont('Arial','B',10);  // AQUI LE ASIGNO EL TIPO DE LETRA Y TAMA�O
	$pdf->SetFillColor(216,216,216);	// AQUI LE DOY EL COLOR DE FONDO DE LAS CELDAS
    $pdf->SetTextColor(0);  // AQUI LE DOY COLOR AL TEXTO

	if($periodo == "A"){
	$pdf->Row(array('No.', '', 'Categoria', 'Activo', 'Presupuestado', 'Ejecutado','Direfencia','%'));
	}else{
	$pdf->Row(array('No.', '', 'Presupuestado', 'Ejecutado','Direfencia','%'));
	}if($periodo == "A"){
	$pdf->SetWidths(array(10, 55, 45, 45, 30, 30, 30, 25));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;
	$pdf->SetAligns(array('C', 'L', 'L', 'L', 'C', 'C', 'C', 'C'));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;
	}else{
	$pdf->SetWidths(array(10, 75, 45, 45, 45, 45));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;
	$pdf->SetAligns(array('C', 'L', 'L', 'L', 'C', 'C', 'C', 'C'));  // AQU� LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;	
	}$ClsPro = new ClsProgramacionPPM();
	$num = 1;
	$dia_inicio = "";
	$PROGRAMADO = 0;
	$EJECUTADO = 0;
	$DIFERENCIA = 0;
	//--
	if($periodo == "A"){
		$result = $ClsPro->get_programacion('',$activo,$usuario,$categoria,$sede, '', $area, $fini, $ffin,'','',4);
		$programado = 0;
		$ejecutado = 0;
		$diferencia = 0;
		$porcentaje = 0;
		$signo = "";
		if(is_array($result)){
			$num = 1;
			foreach($result as $row){
				$programado = 0;
				$ejecutado = 0;
				$codigo = Agrega_Ceros($row["pro_codigo"]);
				$fecha = cambia_fecha($row["pro_fecha"]);
				//--
				$programado = cambioMoneda($row["mon_cambio"], 1, $row["pro_presupuesto_programado"]); //realiza conversion de tipo de cambio
				$ejecutado = cambioMoneda($row["mon_cambio"], 1, $row["pro_presupuesto_ejecutado"]); //realiza conversion de tipo de cambio
				$diferencia = $programado - $ejecutado;
				if($diferencia != 0){
					if($diferencia > 0){
						$porcentaje = round(($ejecutado*100)/$programado);
						$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecuci�n
						$signo = "-";
					}else{
						$porcentaje = round(($programado*100)/$ejecutado);
						$diferencia = ($diferencia * -1);
						$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecuci�n
						$signo = "+";
					}
				}else{
					$porcentaje = 0;
					$signo = "";
				}
				$PROGRAMADO+=$programado;
				$EJECUTADO+=$ejecutado;
				$DIFERENCIA+=$DIFERENCIA;
				//categoria
				$categoria_nombre = utf8_decode($row["cat_nombre"]);
				//activo
				$activo_nombre = utf8_decode($row["act_nombre"]);
				//--
				$pdf->SetFont('Arial','',8);   // ASIGNO EL TIPO Y TAMA�O DE LA LETRA
				$pdf->SetFillColor(255,255,255);	// AQUI LE DOY EL COLOR DE FONDO DE LAS CELDAS
				$pdf->SetTextColor(0);  // LE ASIGNO EL COLOR AL TEXTO
				$no = $num.".";
				$pdf->Row(array($no,"Programaci�n #$codigo - $fecha", $categoria_nombre, $activo_nombre, "Q.".number_format($programado, 2, '.', ','),"Q.".number_format($ejecutado, 2, '.', ','),$signo." Q.".number_format($diferencia, 2, '.', ','),$signo." ".number_format($porcentaje, 0, '.', '')." %")); // AGREGO LOS DATOS A LA FILA, VIENE REPERESENTADO POR UN ARRAY 
				$num++;
			}
		}
		
	}else if($periodo == "D"){
		$fechaInicio = strtotime(regresa_fecha($fini));
		$fechaFin = strtotime(regresa_fecha($ffin));
		for($i = $fechaInicio; $i <= $fechaFin; $i+=86400){
			$fecha = date("d/m/Y", $i);
			$dia = date("w", $i);
			$dia = ($dia == 0)?7:$dia;
			$dia_nombre = Dias_Letra($dia);
			$result = $ClsPro->get_programacion('',$activo,$usuario,$categoria,$sede, '', $area, $fecha, $fecha,'','',4);
			$programado = 0;
			$ejecutado = 0;
			$diferencia = 0;
			$porcentaje = 0;
			$signo = "";
			if(is_array($result)){
				foreach($result as $row){
					$programado+= cambioMoneda($row["mon_cambio"], 1, $row["pro_presupuesto_programado"]); //realiza conversion de tipo de cambio
					$ejecutado+= cambioMoneda($row["mon_cambio"], 1, $row["pro_presupuesto_ejecutado"]); //realiza conversion de tipo de cambio
				}
			}
			$diferencia = $programado - $ejecutado;
			if($diferencia != 0){
				if($diferencia > 0){
					$porcentaje = round(($ejecutado*100)/$programado);
					$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecuci�n
					$signo = "-";
				}else{
					$porcentaje = round(($programado*100)/$ejecutado);
					$diferencia = ($diferencia * -1);
					$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecuci�n
					$signo = "+";
				}
			}else{
				$porcentaje = 0;
				$signo = "";
			}
			$PROGRAMADO+=$programado;
			$EJECUTADO+=$ejecutado;
			$DIFERENCIA+=$DIFERENCIA;
			//--
			$pdf->SetFont('Arial','',10);   // ASIGNO EL TIPO Y TAMA�O DE LA LETRA
			$pdf->SetFillColor(255,255,255);	// AQUI LE DOY EL COLOR DE FONDO DE LAS CELDAS
			$pdf->SetTextColor(0);  // LE ASIGNO EL COLOR AL TEXTO
			$no = $num.".";
			$pdf->Row(array($no,"$dia_nombre - $fecha","Q.".number_format($programado, 2, '.', ','),"Q.".number_format($ejecutado, 2, '.', ','),$signo." Q.".number_format($diferencia, 2, '.', ','),$signo." ".number_format($porcentaje, 0, '.', '')." %")); // AGREGO LOS DATOS A LA FILA, VIENE REPERESENTADO POR UN ARRAY 
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
				$result = $ClsPro->get_programacion('',$activo,$usuario,$categoria,$sede, '', $area, $fecha_ini, $fecha_fin,'','',4);
				$programado = 0;
				$ejecutado = 0;
				$diferencia = 0;
				$porcentaje = 0;
				$signo = "";
				if(is_array($result)){
					foreach($result as $row){
						$programado+= cambioMoneda($row["mon_cambio"], 1, $row["pro_presupuesto_programado"]); //realiza conversion de tipo de cambio
						$ejecutado+= cambioMoneda($row["mon_cambio"], 1, $row["pro_presupuesto_ejecutado"]); //realiza conversion de tipo de cambio
					}
				}
				$diferencia = $programado - $ejecutado;
				if($diferencia != 0){
					if($diferencia > 0){
						$porcentaje = round(($ejecutado*100)/$programado);
						$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecuci�n
						$signo = "-";
					}else{
						$porcentaje = round(($programado*100)/$ejecutado);
						$diferencia = ($diferencia * -1);
						$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecuci�n
						$signo = "+";
					}
				}else{
					$porcentaje = 0;
					$signo = "";
				}
				$PROGRAMADO+=$programado;
				$EJECUTADO+=$ejecutado;
				$DIFERENCIA+=$DIFERENCIA;
				//--
				$pdf->SetFont('Arial','',10);   // ASIGNO EL TIPO Y TAMA�O DE LA LETRA
				$pdf->SetFillColor(255,255,255);	// AQUI LE DOY EL COLOR DE FONDO DE LAS CELDAS
				$pdf->SetTextColor(0);  // LE ASIGNO EL COLOR AL TEXTO
				$no = $num.".";
				$pdf->Row(array($no,"Semana $i ($fecha_ini al $fecha_fin)","Q.".number_format($programado, 2, '.', ','),"Q.".number_format($ejecutado, 2, '.', ','),$signo." Q.".number_format($diferencia, 2, '.', ','),$signo." ".number_format($porcentaje, 0, '.', '')." %")); // AGREGO LOS DATOS A LA FILA, VIENE REPERESENTADO POR UN ARRAY 
				$num++;
			}
		}else{
			$salida.= '<tr>';
			$salida.= '<td colspan = "6" class = "text-center"><strong class="text-danger">Las fechas deben pertenecer al mismo a&ntilde;o...</strong></td>';
			$salida.= '</tr>';
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
				$result = $ClsPro->get_programacion('',$activo,$usuario,$categoria,$sede, '', $area, $fecha_ini, $fecha_fin,'','',4);
				$programado = 0;
				$ejecutado = 0;
				$diferencia = 0;
				$porcentaje = 0;
				$signo = "";
				if(is_array($result)){
					foreach($result as $row){
						$programado+= cambioMoneda($row["mon_cambio"], 1, $row["pro_presupuesto_programado"]); //realiza conversion de tipo de cambio
						$ejecutado+= cambioMoneda($row["mon_cambio"], 1, $row["pro_presupuesto_ejecutado"]); //realiza conversion de tipo de cambio
					}
				}
				$diferencia = $programado - $ejecutado;
				if($diferencia != 0){
					if($diferencia > 0){
						$porcentaje = round(($ejecutado*100)/$programado);
						$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecuci�n
						$signo = "-";
					}else{
						$porcentaje = round(($programado*100)/$ejecutado);
						$diferencia = ($diferencia * -1);
						$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecuci�n
						$signo = "+";
					}
				}else{
					$porcentaje = 0;
					$signo = "";
				}
				$PROGRAMADO+=$programado;
				$EJECUTADO+=$ejecutado;
				$DIFERENCIA+=$DIFERENCIA;
				//--
				$pdf->SetFont('Arial','',10);   // ASIGNO EL TIPO Y TAMA�O DE LA LETRA
				$pdf->SetFillColor(255,255,255);	// AQUI LE DOY EL COLOR DE FONDO DE LAS CELDAS
				$pdf->SetTextColor(0);  // LE ASIGNO EL COLOR AL TEXTO
				$no = $num.".";
				$pdf->Row(array($no,"$mes_nombre","Q.".number_format($programado, 2, '.', ','),"Q.".number_format($ejecutado, 2, '.', ','),$signo." Q.".number_format($diferencia, 2, '.', ','),$signo." ".number_format($porcentaje, 0, '.', '')." %")); // AGREGO LOS DATOS A LA FILA, VIENE REPERESENTADO POR UN ARRAY 
				$num++;
			}
		}else{
			$salida.= '<tr>';
			$salida.= '<td colspan = "6" class = "text-center"><strong class="text-danger">Las fechas deben pertenecer al mismo a&ntilde;o...</strong></td>';
			$salida.= '</tr>';
		}
	}
	//////////////// TOTALES DE TABLA ///////////////////
	$DIFERENCIA = $PROGRAMADO - $EJECUTADO;
	if($DIFERENCIA != 0){
		IF($DIFERENCIA > 0){
			$PORCENTAJE = ROUND(($EJECUTADO*100)/$PROGRAMADO);
			$PORCENTAJE = (100 - $PORCENTAJE); // SE RESTA ENTRE 100 PARA OBTENER EL PORCENTAJE DE AHORRO, NO EL DE EJECUCI�N
			$SIGNO = "-";
		}else{
			$PORCENTAJE = ROUND(($PROGRAMADO*100)/$EJECUTADO);
			$DIFERENCIA = ($DIFERENCIA * -1);
			$PORCENTAJE = (100 - $PORCENTAJE); // SE RESTA ENTRE 100 PARA OBTENER EL PORCENTAJE DE AHORRO, NO EL DE EJECUCI�N
			$SIGNO = "+";
		}
	}else{
		$PORCENTAJE = 0;
		$SIGNO = "";
	}// EN EL ARRAY, CADA DATO ES UNA COLUMNA, IGUAL SE HACE PARA INGRESAR LOS DATOS
	$pdf->SetFont('Arial','B',10);  // AQUI LE ASIGNO EL TIPO DE LETRA Y TAMA�O
	$pdf->SetFillColor(216,216,216);	// AQUI LE DOY EL COLOR DE FONDO DE LAS CELDAS
    $pdf->SetTextColor(0);  // AQUI LE DOY COLOR AL TEXTO

	if($periodo == "A"){
	$pdf->Row(array('', ' Totales ','','', "Q.".number_format($PROGRAMADO, 2, '.', ','), "Q.".number_format($EJECUTADO, 2, '.', ','), $SIGNO."Q.".number_format($DIFERENCIA, 2, '.', ','), "$SIGNO $PORCENTAJE %"));
	}else{
	$pdf->Row(array('', ' Totales ', "Q.".number_format($PROGRAMADO, 2, '.', ','), "Q.".number_format($EJECUTADO, 2, '.', ','), $SIGNO."Q.".number_format($DIFERENCIA, 2, '.', ','), "$SIGNO $PORCENTAJE %"));
	}$pdf->Output();?>