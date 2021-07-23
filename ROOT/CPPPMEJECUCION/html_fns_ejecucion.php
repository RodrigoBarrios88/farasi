<?php 
include_once('../html_fns.php');


function tabla_programacion($activo,$usuario, $categoria, $sede, $area, $desde, $hasta){
	$ClsPro = new ClsProgramacionPPM();
	$result = $ClsPro->get_programacion('',$activo,$usuario,$categoria,$sede, '', $area, $desde, $hasta,'','','');
	if(is_array($result)){
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "10px">No.</th>';
		$salida.= '<th class = "text-center" width = "120px">Sede</th>';
		$salida.= '<th class = "text-center" width = "120px">Activo</th>';
		$salida.= '<th class = "text-center" width = "120px">Usuario</th>';
		$salida.= '<th class = "text-center" width = "100px">Categor&iacute;a</th>';
		$salida.= '<th class = "text-center" width = "100px">Fecha</th>';
		$salida.= '<th class = "text-center" width = "10px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "10px"><i class="fa fa-cogs"></i></th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=1;
		foreach($result as $row){
			$salida.= '<tr>';
			//No.
			$salida.= '<td class = "text-center">'.$i.'</td>';
			//sede
			$sede = utf8_decode($row["sed_nombre"]);
			$salida.= '<td class = "text-left">'.$sede.'</td>';
			//activo
			$activo = utf8_decode($row["act_nombre"]);
			$salida.= '<td class = "text-left">'.$activo.'</td>';
			//usuario
			$usuario = utf8_decode($row["usu_nombre"]);
			$salida.= '<td class = "text-left">'.$usuario.'</td>';
			//categoria
			$categoria = utf8_decode($row["cat_nombre"]);
			$salida.= '<td class = "text-left">'.$categoria.'</td>';
			//fecha.
			$fecha = cambia_fecha($row["pro_fecha"]);
			$salida.= '<td class = "text-center">'.$fecha.'</td>';
			//conteo de dias
			$programado = trim($row["pro_fecha"]);
			$ahora = date("Y-m-d");
			$vencimiento = comparaFechas($programado, $ahora);
			//ver
			$codigo = $row["pro_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsPro->encrypt($codigo, $usu);
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<a class="btn btn-white btn-xs" href = "../CPPPMPROGRA/CPREPORTES/REPorden.php?hashkey='.$hashkey.'" target="_blank" title = "Imprimir Orden de Trabajo" ><i class="fa fa-print"></i></a>';
					$salida.= '<button type="button" class="btn btn-success" onclick = "verProgramacion(\''.$hashkey.'\');" title = "Seleccionar Programaci&oacute;n" ><i class="fa fa-search"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//codigo
			$codigo = $row["pro_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsPro->encrypt($codigo, $usu);
			$situacion = $row["pro_situacion"];
			if($situacion == 1){
				if($vencimiento == 1){ // Falta para que se cumpla
					$class = 'btn-info';
					$texto = 'Programado';
					$disabled = 'disabled';
				}else if($vencimiento == 2){ // ya se vencio
					$class = 'btn-danger';
					$texto = 'Vencido';
					$disabled = '';
				}else{ // hoy corresponde
					$class = 'btn-info';
					$texto = 'Para Hoy';
					$disabled = '';
				}
			}else if($situacion == 2){
				$class = 'btn-warning';
				$texto = 'En Espera';
				$disabled = '';
			}else if($situacion == 3){
				$class = 'btn-success';
				$texto = 'En Proceso';
				$disabled = '';
			}else if($situacion == 4){
				$class = 'btn-white';
				$texto = 'Finalizado';
				$disabled = 'disabled';
			}
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn '.$class.'" onclick = "seleccionarProgramacion(\''.$hashkey.'\');" title = "Seleccionar Programaci&oacute;n" '.$disabled.' >'.$texto.' <i class="fa fa-angle-double-right"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}



function tabla_revisiones($activo, $usuario, $categoria, $sede, $area, $desde, $hasta, $situacion){
	$sit = ($situacion == 5)?1:$situacion;
	$ClsPro = new ClsProgramacionPPM();
	$result = $ClsPro->get_programacion('',$activo,$usuario,$categoria,$sede, '', $area, $desde, $hasta,'','',$sit);
	if(is_array($result)){
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "10px">No.</th>';
		$salida.= '<th class = "text-center" width = "120px">Sede</th>';
		$salida.= '<th class = "text-center" width = "120px">Activo</th>';
		$salida.= '<th class = "text-center" width = "120px">Usuario</th>';
		$salida.= '<th class = "text-center" width = "100px">Categor&iacute;a</th>';
		$salida.= '<th class = "text-center" width = "100px">Fecha</th>';
		$salida.= '<th class = "text-center" width = "100px">Situaci&oacute;n</th>';
		$salida.= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=1;
		foreach($result as $row){
			//conteo de dias
			$programado = trim($row["pro_fecha"]);
			$ahora = date("Y-m-d");
			$vencimiento = comparaFechas($programado, $ahora);
			$situacion = $row["pro_situacion"];
			if($situacion == 1){
				if($vencimiento == 1){ // Falta para que se cumpla
					$class = '';
					$texto = 'Programado';
				}else if($vencimiento == 2){ // ya se vencio
					$class = 'table-danger';
					$texto = 'Vencido';
				}else{ // hoy corresponde
					$class = '';
					$texto = 'Para Hoy';
				}
				$disabled = "disabled";
			}else if($situacion == 2){
				$class = 'table-warning';
				$texto = 'En Espera';
				$disabled = "disabled";
			}else if($situacion == 3){
				$class = 'table-info';
				$texto = 'En Proceso';
				$disabled = "disabled";
			}else if($situacion == 4){
				$class = 'table-success';
				$texto = 'Finalizado';
				$disabled = "";
			}
			$salida.= '<tr class="'.$class.'">';
			//No.
			$salida.= '<td class = "text-center">'.$i.'</td>';
			//sede
			$sede = utf8_decode($row["sed_nombre"]);
			$salida.= '<td class = "text-left">'.$sede.'</td>';
			//activo
			$activo = utf8_decode($row["act_nombre"]);
			$salida.= '<td class = "text-left">'.$activo.'</td>';
			//usuario
			$usuario = utf8_decode($row["usu_nombre"]);
			$salida.= '<td class = "text-left">'.$usuario.'</td>';
			//categoria
			$categoria = utf8_decode($row["cat_nombre"]);
			$salida.= '<td class = "text-left">'.$categoria.'</td>';
			//fecha.
			$fecha = cambia_fecha($row["pro_fecha"]);
			$salida.= '<td class = "text-center">'.$fecha.'</td>';
			//situacion.
			$salida.= '<td class = "text-center"><strong>'.$texto.'</strong></td>';
			//ver
			$codigo = $row["pro_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsPro->encrypt($codigo, $usu);
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white" onclick = "verProgramacion(\''.$hashkey.'\');" title = "Seleccionar Programaci&oacute;n" ><i class="fa fa-search"></i></button>';
					$salida.= '<button type="button" class="btn btn-success" onclick = "ejecutarPresupuesto('.$codigo.');" '.$disabled.' title = "Actualizar Presupuesto Ejecutado" ><i class="fa fa-dollar"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}


function tabla_presupuestos($activo, $usuario, $categoria, $sede, $area, $fini, $ffin, $periodo){
	$ClsPro = new ClsProgramacionPPM();$salida = '<table class="table table-striped" width="100%" >';
	$salida.= '<thead>';
	$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "30px">No.</th>';
		$salida.= '<th class = "text-center" width = "150px"></th>';
		if($periodo == "A"){
			$salida.= '<th class = "text-center" width = "100px">Categor&iacute;a</th>';
			$salida.= '<th class = "text-center" width = "100px">Activo</th>';
		}	
		$salida.= '<th class = "text-center" width = "150px">Programado</th>';
		$salida.= '<th class = "text-center" width = "100px">Ejecutado</th>';
		$salida.= '<th class = "text-center" width = "100px">Diferencia</th>';
		$salida.= '<th class = "text-center" width = "100px">%</th>';
	$salida.= '</tr>';
	$salida.= '</thead>';
	$salida.= '<tbody>';
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
						$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecución
						$signo = "-";
					}else{
						$porcentaje = round(($programado*100)/$ejecutado);
						$diferencia = ($diferencia * -1);
						$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecución
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
				$salida.= '<tr>';
				//--
				$salida.= '<td class = "text-center">'.$num.'.- </td>';
				$salida.= '<td class = "text-left"> Programaci&oacute;n #'.$codigo.' '.$fecha.'</td>';
				$salida.= '<td class = "text-left">'.$categoria_nombre.'</td>';
				$salida.= '<td class = "text-left">'.$activo_nombre.'</td>';
				$salida.= '<td class = "text-center">Q. '.number_format($programado, 2, '.', ',').'</td>';
				$salida.= '<td class = "text-center">Q. '.number_format($ejecutado, 2, '.', ',').'</td>';
				$salida.= '<td class = "text-center">'.$signo.' Q.'.number_format($diferencia, 2, '.', ',').'</td>';
				$salida.= '<td class = "text-center">'.$signo.' '.number_format($porcentaje, 0, '.', '').' %</td>';
				//--
				$salida.= '</tr>';
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
					$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecución
					$signo = "-";
				}else{
					$porcentaje = round(($programado*100)/$ejecutado);
					$diferencia = ($diferencia * -1);
					$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecución
					$signo = "+";
				}
			}else{
				$porcentaje = 0;
				$signo = "";
			}
			$PROGRAMADO+=$programado;
			$EJECUTADO+=$ejecutado;
			$DIFERENCIA+=$DIFERENCIA;
			$salida.= '<tr>';
			//--
			$salida.= '<td class = "text-center">'.$num.'.- </td>';
			$salida.= '<td class = "text-left">'.$dia_nombre.' '.$fecha.'</td>';
			$salida.= '<td class = "text-center">Q. '.number_format($programado, 2, '.', ',').'</td>';
			$salida.= '<td class = "text-center">Q. '.number_format($ejecutado, 2, '.', ',').'</td>';
			$salida.= '<td class = "text-center">'.$signo.' Q.'.number_format($diferencia, 2, '.', ',').'</td>';
			$salida.= '<td class = "text-center">'.$signo.' '.number_format($porcentaje, 0, '.', '').' %</td>';
			//--
			$salida.= '</tr>';
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
						$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecución
						$signo = "-";
					}else{
						$porcentaje = round(($programado*100)/$ejecutado);
						$diferencia = ($diferencia * -1);
						$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecución
						$signo = "+";
					}
				}else{
					$porcentaje = 0;
					$signo = "";
				}
				$PROGRAMADO+=$programado;
				$EJECUTADO+=$ejecutado;
				$DIFERENCIA+=$DIFERENCIA;
				$salida.= '<tr>';
				//--
				$salida.= '<td class = "text-center">'.$num.'.- </td>';
				$salida.= '<td class = "text-left"> Semana '.$i.' ('.$fecha_ini.' al '.$fecha_fin.')</td>';
				$salida.= '<td class = "text-center">Q. '.number_format($programado, 2, '.', ',').'</td>';
				$salida.= '<td class = "text-center">Q. '.number_format($ejecutado, 2, '.', ',').'</td>';
				$salida.= '<td class = "text-center">'.$signo.' Q.'.number_format($diferencia, 2, '.', ',').'</td>';
				$salida.= '<td class = "text-center">'.$signo.' '.number_format($porcentaje, 0, '.', '').' %</td>';
				//--
				$salida.= '</tr>';
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
						$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecución
						$signo = "-";
					}else{
						$porcentaje = round(($programado*100)/$ejecutado);
						$diferencia = ($diferencia * -1);
						$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecución
						$signo = "+";
					}
				}else{
					$porcentaje = 0;
					$signo = "";
				}
				$PROGRAMADO+=$programado;
				$EJECUTADO+=$ejecutado;
				$DIFERENCIA+=$DIFERENCIA;
				$salida.= '<tr>';
				//--
				$salida.= '<td class = "text-center">'.$num.'.- </td>';
				$salida.= '<td class = "text-left">'.$mes_nombre.'</td>';
				$salida.= '<td class = "text-center">Q.'.number_format($programado, 2, '.', ',').'</td>';
				$salida.= '<td class = "text-center">Q. '.number_format($ejecutado, 2, '.', ',').'</td>';
				$salida.= '<td class = "text-center">'.$signo.' Q.'.number_format($diferencia, 2, '.', ',').'</td>';
				$salida.= '<td class = "text-center">'.$signo.' '.number_format($porcentaje, 0, '.', '').' %</td>';
				//--
				$salida.= '</tr>';
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
			$PORCENTAJE = (100 - $PORCENTAJE); // SE RESTA ENTRE 100 PARA OBTENER EL PORCENTAJE DE AHORRO, NO EL DE EJECUCIÓN
			$SIGNO = "-";
		}else{
			$PORCENTAJE = ROUND(($PROGRAMADO*100)/$EJECUTADO);
			$DIFERENCIA = ($DIFERENCIA * -1);
			$PORCENTAJE = (100 - $PORCENTAJE); // SE RESTA ENTRE 100 PARA OBTENER EL PORCENTAJE DE AHORRO, NO EL DE EJECUCIÓN
			$SIGNO = "+";
		}
	}else{
		$PORCENTAJE = 0;
		$SIGNO = "";
	}
	$salida.= '<tr>';
	//--
	$salida.= '<th class = "text-center"> </th>';
	if($periodo == "A"){
		$salida.= '<th class = "text-center"> </th>';
		$salida.= '<th class = "text-center"> </th>';
	}	
	$salida.= '<th class = "text-right"> Totales &nbsp; </th>';
	$salida.= '<th class = "text-center">Q. '.number_format($PROGRAMADO, 2, '.', ',').'</th>';
	$salida.= '<th class = "text-center">Q. '.number_format($EJECUTADO, 2, '.', ',').'</th>';
	$salida.= '<th class = "text-center">'.$SIGNO.' Q.'.number_format($DIFERENCIA, 2, '.', ',').'</th>';
	$salida.= '<th class = "text-center">'.$SIGNO.' '.number_format($PORCENTAJE, 0, '.', '').' %</th>';
	//--
	$salida.= '</tr>';
	/////////---------
	$salida.= '</tbody>';
	$salida.= '</table>';return $salida;
}


function tabla_reportes($activo, $usuario, $categoria, $sede, $area, $desde, $hasta, $situacion, $columnas){
	$sit = ($situacion == 5)?1:$situacion;
	$ClsPro = new ClsProgramacionPPM();
	$result = $ClsPro->get_programacion('',$activo,$usuario,$categoria,$sede, '', $area, $desde, $hasta,'','',$sit);
	if(is_array($result)){
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "10px">No.</th>';
		if(is_array($columnas)){
			foreach($columnas as $col){
			   $parametros = parametrosDinamicosHTML($col);
			   $ancho = $parametros['ancho'];
			   $titulo = $parametros['titulo'];
			   $salida.= '<th class = "text-center" width = "'.$ancho.'">'.$titulo.'</th>';
			}
		}else{
			$salida.= '<th class = "text-center" width = "120px">Sede</th>';
			$salida.= '<th class = "text-center" width = "120px">Activo</th>';
			$salida.= '<th class = "text-center" width = "120px">Usuario</th>';
			$salida.= '<th class = "text-center" width = "100px">Categor&iacute;a</th>';
			$salida.= '<th class = "text-center" width = "100px">Fecha</th>';
			$salida.= '<th class = "text-center" width = "100px">Situaci&oacute;n</th>';
		}
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i = 1;
		foreach($result as $row){
			$salida.= '<tr>';
			//--
			$salida.= '<td class = "text-center">'.$i.'.- </td>';
			//--
			if(is_array($columnas)){
				foreach($columnas as $col){
					$parametros = parametrosDinamicosHTML($col);
					$campo = $parametros['campo'];
					$alineacion = $parametros['alineacion'];
					if($col == "pro_codigo"){
						$campo = '# '.Agrega_Ceros($row[$campo]);
					}else if($col == "pro_foto1" || $col == "pro_foto2" || $col == "pro_firma"){
						$campo = trim($row[$campo]).".jpg";
					}else if($col == "pro_fecha"){
						$campo = cambia_fechaHora($row["pro_fecha"]);
					}else if($col == "pro_fecha_update"){
						$campo = cambia_fechaHora($row["pro_fecha_update"]);
					}else if($col == "pro_presupuesto_programado" || $col == "pro_presupuesto_ejecutado"){
						$campo = trim($row["mon_simbolo"]).'. '.trim($row[$campo]);
					}else if($col == "pro_situacion"){
						//conteo de dias
						$programado = trim($row["pro_fecha"]);
						$ahora = date("Y-m-d");
						$vencimiento = comparaFechas($programado, $ahora);
						$situacion = $row["pro_situacion"];
						if($situacion == 1){
							if($vencimiento == 1){ // Falta para que se cumpla
								$campo = 'Programado';
							}else if($vencimiento == 2){ // ya se vencio
								$campo = 'Vencido';
							}else{ // hoy corresponde
								$campo = 'Para Hoy';
							}
						}else if($situacion == 2){
							$campo = 'En Espera';
						}else if($situacion == 3){
							$campo = 'En Proceso';
						}else if($situacion == 4){
							$campo = 'Finalizado';
						}
					}else{
						$campo = utf8_decode($row[$campo]);
					}
					//columna
					$salida.= '<td class = "'.$alineacion.'">'.$campo.'</td>';
				}
			}else{
				//sede
				$sede = utf8_decode($row["sed_nombre"]);
				$salida.= '<td class = "text-left">'.$sede.'</td>';
				//activo
				$activo = utf8_decode($row["act_nombre"]);
				$salida.= '<td class = "text-left">'.$activo.'</td>';
				//usuario
				$usuario = utf8_decode($row["usu_nombre"]);
				$salida.= '<td class = "text-left">'.$usuario.'</td>';
				//categoria
				$categoria = utf8_decode($row["cat_nombre"]);
				$salida.= '<td class = "text-left">'.$categoria.'</td>';
				//fecha.
				$fecha = cambia_fecha($row["pro_fecha"]);
				$salida.= '<td class = "text-center">'.$fecha.'</td>';
				//situacion.
				$programado = trim($row["pro_fecha"]);
				$ahora = date("Y-m-d");
				$vencimiento = comparaFechas($programado, $ahora);
				$situacion = $row["pro_situacion"];
				if($situacion == 1){
					if($vencimiento == 1){ // Falta para que se cumpla
						$texto = 'Programado';
					}else if($vencimiento == 2){ // ya se vencio
						$texto = 'Vencido';
					}else{ // hoy corresponde
						$texto = 'Para Hoy';
					}
				}else if($situacion == 2){
					$texto = 'En Espera';
				}else if($situacion == 3){
					$texto = 'En Proceso';
				}else if($situacion == 4){
					$texto = 'Finalizado';
				}
				$salida.= '<td class = "text-center"><strong>'.$texto.'</strong></td>';
			}
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}


function parametrosDinamicosHTML($columna){
	switch($columna){
		case "pro_codigo":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Orden de Trabajo";
			$respuesta["campo"] = "pro_codigo";
			break;
		case "pro_fecha":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha Programada";
			$respuesta["campo"] = "pro_fecha";
			break;
		case "usu_nombre":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Usuario Asignado (Responsable)";
			$respuesta["campo"] = "usu_nombre";
			break;
		case "pro_presupuesto_programado":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Presupuesto Asignado";
			$respuesta["campo"] = "pro_presupuesto_programado";
			break;
		case "pro_presupuesto_ejecutado":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Presupuesto Ejecutado";
			$respuesta["campo"] = "pro_presupuesto_ejecutado";
			break;
		case "pro_observaciones_programacion":
			$respuesta["ancho"] = "150";
			$respuesta["alineacion"] = "text-justify";
			$respuesta["titulo"] = "Observaciones en la Programaci&oacute;n";
			$respuesta["campo"] = "pro_observaciones_programacion";
			break;
		case "pro_observaciones_ejecucion":
			$respuesta["ancho"] = "150";
			$respuesta["alineacion"] = "text-justify";
			$respuesta["titulo"] = "Observaciones al Ejecutar";
			$respuesta["campo"] = "pro_observaciones_ejecucion";
			break;
		case "pro_foto1":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Foto al Inicio";
			$respuesta["campo"] = "pro_foto1";
			break;
		case "pro_foto2":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Foto Final";
			$respuesta["campo"] = "pro_foto2";
			break;
		case "pro_firma":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Firma";
			$respuesta["campo"] = "pro_firma";
			break;
		case "pro_fecha_update":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha Actualizaci&oacute;n";
			$respuesta["campo"] = "pro_fecha_update";
			break;
		case "pro_situacion":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Status";
			$respuesta["campo"] = "pro_situacion";
			break;
		case "act_nombre":
			$respuesta["ancho"] = "110";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Activo";
			$respuesta["campo"] = "act_nombre";
			break;
		case "act_marca":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Marca";
			$respuesta["campo"] = "act_marca";
			break;
		case "act_serie":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Serie";
			$respuesta["campo"] = "act_serie";
			break;
		case "act_modelo":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Modelo";
			$respuesta["campo"] = "act_modelo";
			break;
		case "act_parte":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "No. Parte";
			$respuesta["campo"] = "act_parte";
			break;
		case "act_proveedor":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Proveedor";
			$respuesta["campo"] = "act_proveedor";
			break;
		case "act_periodicidad":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Periodicidad";
			$respuesta["campo"] = "act_periodicidad";
			break;
		case "act_capacidad":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Capacidad";
			$respuesta["campo"] = "act_capacidad";
			break;
		case "act_cantidad":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Cantidad";
			$respuesta["campo"] = "act_cantidad";
			break;
		case "act_precio_nuevo":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Precio Original";
			$respuesta["campo"] = "act_precio_nuevo";
			break;
		case "act_precio_compra":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Precio de Adquicisi&oacute;n";
			$respuesta["campo"] = "act_precio_actual";
			break;
		case "act_precio_actual":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Precio Actual";
			$respuesta["campo"] = "act_precio_actual";
			break;
		case "act_fecha_registro":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha Registro";
			$respuesta["campo"] = "act_fecha_registro";
			break;
		case "act_fecha_update":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha Actualiza";
			$respuesta["campo"] = "act_fecha_update";
			break;
		case "cat_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Cod. Cate.";
			$respuesta["campo"] = "cat_codigo";
			break;
		case "cat_nombre":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Categoría";
			$respuesta["campo"] = "cat_nombre";
			break;
		case "cat_color":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "text-cente";
			$respuesta["titulo"] = "Color";
			$respuesta["campo"] = "cat_color";
			break;
		case "are_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo &Aacute;rea";
			$respuesta["campo"] = "are_codigo";
			break;
		case "are_nivel":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Nivel";
			$respuesta["campo"] = "are_nivel";
			break;
		case "are_nombre":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "&Aacute;rea";
			$respuesta["campo"] = "are_nombre";
			break;
		case "sec_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Sector";
			$respuesta["campo"] = "sec_codigo";
			break;
		case "sec_nombre":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Sector";
			$respuesta["campo"] = "sec_nombre";
			break;
		case "sed_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Sector";
			$respuesta["campo"] = "sed_codigo";
			break;
		case "sed_nombre":
			$respuesta["ancho"] = "110";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Sede";
			$respuesta["campo"] = "sed_nombre";
			break;
		case "sede_municipio":
			$respuesta["ancho"] = "110";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Departamento / Municipio";
			$respuesta["campo"] = "sede_municipio";
			break;
		case "sed_direccion":
			$respuesta["ancho"] = "150";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Direcci&oacute;n (Sede)";
			$respuesta["campo"] = "sed_direccion";
			break;
		case "sed_zona":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Zona";
			$respuesta["campo"] = "sed_zona";
			break;
	}	
	return $respuesta;
}


function parametrosDinamicosPDF($columna){
	switch($columna){
		case "pro_codigo":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Orden de Trabajo";
			$respuesta["campo"] = "pro_codigo";
			break;
		case "pro_fecha":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Programada";
			$respuesta["campo"] = "pro_fecha";
			break;
		case "usu_nombre":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Usuario Asignado (Responsable)";
			$respuesta["campo"] = "usu_nombre";
			break;
		case "pro_presupuesto_programado":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Presupuesto Asig.";
			$respuesta["campo"] = "pro_presupuesto_programado";
			break;
		case "pro_presupuesto_ejecutado":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Presupuesto Ejec.";
			$respuesta["campo"] = "pro_presupuesto_ejecutado";
			break;
		case "pro_observaciones_programacion":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "J";
			$respuesta["titulo"] = "Observaciones en la Programación";
			$respuesta["campo"] = "pro_observaciones_programacion";
			break;
		case "pro_observaciones_ejecucion":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "J";
			$respuesta["titulo"] = "Observaciones al Ejecutar";
			$respuesta["campo"] = "pro_observaciones_ejecucion";
			break;
		case "pro_foto1":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Foto Inicio";
			$respuesta["campo"] = "pro_foto1";
			break;
		case "pro_foto2":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Foto Final";
			$respuesta["campo"] = "pro_foto2";
			break;
		case "pro_firma":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Firma";
			$respuesta["campo"] = "pro_firma";
			break;
		case "pro_fecha_update":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Actualización";
			$respuesta["campo"] = "pro_fecha_update";
			break;
		case "pro_situacion":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Status";
			$respuesta["campo"] = "pro_situacion";
			break;
		case "act_codigo":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Activo";
			$respuesta["campo"] = "act_codigo";
			break;
		case "act_nombre":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Activo";
			$respuesta["campo"] = "act_nombre";
			break;
		case "act_marca":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Marca";
			$respuesta["campo"] = "act_marca";
			break;
		case "act_serie":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Serie";
			$respuesta["campo"] = "act_serie";
			break;
		case "act_modelo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Modelo";
			$respuesta["campo"] = "act_modelo";
			break;
		case "act_parte":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "No. Parte";
			$respuesta["campo"] = "act_parte";
			break;
		case "act_proveedor":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Proveedor";
			$respuesta["campo"] = "act_proveedor";
			break;
		case "act_periodicidad":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Periodicidad";
			$respuesta["campo"] = "act_periodicidad";
			break;
		case "act_capacidad":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Capacidad";
			$respuesta["campo"] = "act_capacidad";
			break;
		case "act_cantidad":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cantidad";
			$respuesta["campo"] = "act_cantidad";
			break;
		case "act_precio_nuevo":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Precio Original";
			$respuesta["campo"] = "act_precio_nuevo";
			break;
		case "act_precio_compra":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Precio Compra";
			$respuesta["campo"] = "act_precio_actual";
			break;
		case "act_precio_actual":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Precio Actual";
			$respuesta["campo"] = "act_precio_actual";
			break;
		case "act_fecha_registro":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Registro";
			$respuesta["campo"] = "act_fecha_registro";
			break;
		case "act_fecha_update":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Actualiza";
			$respuesta["campo"] = "act_fecha_update";
			break;
		case "cat_codigo":
			$respuesta["ancho"] = "20";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Cate.";
			$respuesta["campo"] = "cat_codigo";
			break;
		case "cat_nombre":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Categoría";
			$respuesta["campo"] = "cat_nombre";
			break;
		case "usu_nombre":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Usuario Registra";
			$respuesta["campo"] = "usu_nombre";
			break;
		case "are_codigo":
			$respuesta["ancho"] = "20";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Área";
			$respuesta["campo"] = "are_codigo";
			break;
		case "are_nivel":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Nivel";
			$respuesta["campo"] = "are_nivel";
			break;
		case "are_nombre":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Área";
			$respuesta["campo"] = "are_nombre";
			break;
		case "sec_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Sector";
			$respuesta["campo"] = "sec_codigo";
			break;
		case "sec_nombre":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Sector";
			$respuesta["campo"] = "sec_nombre";
			break;
		case "sed_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Sede";
			$respuesta["campo"] = "sed_codigo";
			break;
		case "sed_nombre":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Sede";
			$respuesta["campo"] = "sed_nombre";
			break;
		case "sede_municipio":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Dep/Municipio";
			$respuesta["campo"] = "sede_municipio";
			break;
		case "sed_direccion":
			$respuesta["ancho"] = "65";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Dirección (Sede)";
			$respuesta["campo"] = "sed_direccion";
			break;
		case "sed_zona":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Zona";
			$respuesta["campo"] = "sed_zona";
			break;
	}	
	return $respuesta;
}


function parametrosDinamicosEXCEL($columna){
	switch($columna){
		case "pro_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Orden de Trabajo";
			$respuesta["campo"] = "pro_codigo";
			break;
		case "pro_fecha":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Programada";
			$respuesta["campo"] = "pro_fecha";
			break;
		case "usu_nombre":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Usuario Asignado (Responsable)";
			$respuesta["campo"] = "usu_nombre";
			break;
		case "pro_presupuesto_programado":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Presupuesto Asig.";
			$respuesta["campo"] = "pro_presupuesto_programado";
			break;
		case "pro_presupuesto_ejecutado":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Presupuesto Ejec.";
			$respuesta["campo"] = "pro_presupuesto_ejecutado";
			break;
		case "pro_observaciones_programacion":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "J";
			$respuesta["titulo"] = "Observaciones en la Programacion";
			$respuesta["campo"] = "pro_observaciones_programacion";
			break;
		case "pro_observaciones_ejecucion":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "J";
			$respuesta["titulo"] = "Observaciones al Ejecutar";
			$respuesta["campo"] = "pro_observaciones_ejecucion";
			break;
		case "pro_foto1":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Foto Inicio";
			$respuesta["campo"] = "pro_foto1";
			break;
		case "pro_foto2":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Foto Final";
			$respuesta["campo"] = "pro_foto2";
			break;
		case "pro_firma":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Firma";
			$respuesta["campo"] = "pro_firma";
			break;
		case "pro_fecha_update":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Actualización";
			$respuesta["campo"] = "pro_fecha_update";
			break;
		case "pro_situacion":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Status";
			$respuesta["campo"] = "pro_situacion";
			break;
		case "act_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Activo";
			$respuesta["campo"] = "act_codigo";
			break;
		case "act_nombre":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Activo";
			$respuesta["campo"] = "act_nombre";
			break;
		case "act_marca":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Marca";
			$respuesta["campo"] = "act_marca";
			break;
		case "act_serie":
			$respuesta["ancho"] = "20";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Serie";
			$respuesta["campo"] = "act_serie";
			break;
		case "act_modelo":
			$respuesta["ancho"] = "20";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Modelo";
			$respuesta["campo"] = "act_modelo";
			break;
		case "act_parte":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "No. Parte";
			$respuesta["campo"] = "act_parte";
			break;
		case "act_proveedor":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Proveedor";
			$respuesta["campo"] = "act_proveedor";
			break;
		case "act_periodicidad":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Periodicidad";
			$respuesta["campo"] = "act_periodicidad";
			break;
		case "act_capacidad":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Capacidad";
			$respuesta["campo"] = "act_capacidad";
			break;
		case "act_cantidad":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cantidad";
			$respuesta["campo"] = "act_cantidad";
			break;
		case "act_precio_nuevo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Precio Original";
			$respuesta["campo"] = "act_precio_nuevo";
			break;
		case "act_precio_compra":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Precio Compra";
			$respuesta["campo"] = "act_precio_actual";
			break;
		case "act_precio_actual":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Precio Actual";
			$respuesta["campo"] = "act_precio_actual";
			break;
		case "act_fecha_registro":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Registro";
			$respuesta["campo"] = "act_fecha_registro";
			break;
		case "act_fecha_update":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Actualiza";
			$respuesta["campo"] = "act_fecha_update";
			break;
		case "cat_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Cate.";
			$respuesta["campo"] = "cat_codigo";
			break;
		case "cat_nombre":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Categoria";
			$respuesta["campo"] = "cat_nombre";
			break;
		case "usu_nombre":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Usuario Registra";
			$respuesta["campo"] = "usu_nombre";
			break;
		case "are_codigo":
			$respuesta["ancho"] = "20";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Area";
			$respuesta["campo"] = "are_codigo";
			break;
		case "are_nivel":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Nivel";
			$respuesta["campo"] = "are_nivel";
			break;
		case "are_nombre":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Area";
			$respuesta["campo"] = "are_nombre";
			break;
		case "sec_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Sector";
			$respuesta["campo"] = "sec_codigo";
			break;
		case "sec_nombre":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Sector";
			$respuesta["campo"] = "sec_nombre";
			break;
		case "sed_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Sede";
			$respuesta["campo"] = "sed_codigo";
			break;
		case "sed_nombre":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Sede";
			$respuesta["campo"] = "sed_nombre";
			break;
		case "sede_municipio":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Dep/Municipio";
			$respuesta["campo"] = "sede_municipio";
			break;
		case "sed_direccion":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Direccion (Sede)";
			$respuesta["campo"] = "sed_direccion";
			break;
		case "sed_zona":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Zona";
			$respuesta["campo"] = "sed_zona";
			break;
	}	
	return $respuesta;
}


function daysOfWeek($anio,$semana,$dia_semana){
	return date("Y-m-d", strtotime($anio."-W".$semana.'-'.$dia_semana));
}?>