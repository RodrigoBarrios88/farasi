<?php 
include_once('../html_fns.php');

function tabla_resultados($periodo,$sede,$departamento,$categoria,$situacion,$fini,$ffin){
	$ClsEje = new ClsEjecucion();$salida = '<table class="table table-striped" width="100%" >';
	$salida.= '<thead>';
	$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "30px">No.</th>';
		$salida.= '<th class = "text-center" width = "150px"></th>';
		$salida.= '<th class = "text-center" width = "150px">Nota Promedio</th>';
		$salida.= '<th class = "text-center" width = "100px">Cantidad de Auditorias</th>';
		$salida.= '<th class = "text-center" width = "100px">Ponderacion</th>';
	$salida.= '</tr>';
	$salida.= '</thead>';
	$salida.= '<tbody>';
	$num = 1;
	$dia_inicio = "";
	$CANT = 0;
	$SUMA = 0;
	$PROMEDIO = 0;
	//--
	if($periodo == "D"){
		$fechaInicio = strtotime(regresa_fecha($fini));
		$fechaFin = strtotime(regresa_fecha($ffin));
		for($i = $fechaInicio; $i <= $fechaFin; $i+=86400){
			$fecha = date("d/m/Y", $i);
			$dia = date("w", $i);
			$dia = ($dia == 0)?7:$dia;
			$dia_nombre = Dias_Letra($dia);
			$result = $ClsEje->get_ejecucion('','','',$sede,$departamento,$categoria,$fecha,$fecha,$situacion);
			$cant = 0;
			$suma = 0;
			$promedio = 0;
			if(is_array($result)){
				foreach($result as $row){
					$nota = trim($row["eje_nota"]);
					$suma+= $nota;
					$cant++;
					//--
					$tipo = trim($row["audit_ponderacion"]);
					switch($tipo){
						case 1: $tipo = "1-10"; break;
						case 2: $tipo = "SI, NO, N/A"; break;
						case 3: $tipo = "SAT, NO SAT"; break;
					}
				}
				$promedio = $suma/$cant;
				$promedio = number_format($promedio,2,'.','');
			}
			$promedio = ($promedio == 0 && $cant == 0)?"":$promedio;
			$CANT+= $cant;
			$SUMA+= $suma;
			$salida.= '<tr>';
			//--
			$salida.= '<td class = "text-center">'.$num.'.- </td>';
			$salida.= '<td class = "text-left">'.$dia_nombre.' '.$fecha.'</td>';
			$salida.= '<td class = "text-center">'.$promedio.'</td>';
			$salida.= '<td class = "text-center">'.$cant.'</td>';
			$salida.= '<td class = "text-center">'.$tipo.'</td>';
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
				$result = $ClsEje->get_ejecucion('','','',$sede,$departamento,$categoria,$fecha_ini,$fecha_fin,$situacion);
				$cant = 0;
				$suma = 0;
				$promedio = 0;
				if(is_array($result)){
					foreach($result as $row){
						$nota = trim($row["eje_nota"]);
						$suma+= $nota;
						$cant++;
						//--
						$tipo = trim($row["audit_ponderacion"]);
						switch($tipo){
							case 1: $tipo = "1-10"; break;
							case 2: $tipo = "SI, NO, N/A"; break;
							case 3: $tipo = "SAT, NO SAT"; break;
						}
					}
					$promedio = $suma/$cant;
					$promedio = number_format($promedio,2,'.','');
				}
				$promedio = ($promedio == 0 && $cant == 0)?"":$promedio;
				$CANT+= $cant;
				$SUMA+= $suma;
				$salida.= '<tr>';
				//--
				$salida.= '<td class = "text-center">'.$num.'.- </td>';
				$salida.= '<td class = "text-left">Semana '.$i.' ('.$fecha_ini.' al '.$fecha_fin.')</td>';
				$salida.= '<td class = "text-center">'.$promedio.'</td>';
				$salida.= '<td class = "text-center">'.$cant.'</td>';
				$salida.= '<td class = "text-center">'.$tipo.'</td>';
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
				$result = $ClsEje->get_ejecucion('','','',$sede,$departamento,$categoria,$fecha_ini,$fecha_fin,$situacion);
				$cant = 0;
				$suma = 0;
				$promedio = 0;
				if(is_array($result)){
					foreach($result as $row){
						$nota = trim($row["eje_nota"]);
						$suma+= $nota;
						$cant++;
						//--
						$tipo = trim($row["audit_ponderacion"]);
						switch($tipo){
							case 1: $tipo = "1-10"; break;
							case 2: $tipo = "SI, NO, N/A"; break;
							case 3: $tipo = "SAT, NO SAT"; break;
						}
					}
					$promedio = $suma/$cant;
					$promedio = number_format($promedio,2,'.','');
				}
				$promedio = ($promedio == 0 && $cant == 0)?"":$promedio;
				$CANT+= $cant;
				$SUMA+= $suma;
				$salida.= '<tr>';
				//--
				$salida.= '<td class = "text-center">'.$num.'.- </td>';
				$salida.= '<td class = "text-left">'.$mes_nombre.'</td>';
				$salida.= '<td class = "text-center">'.$promedio.'</td>';
				$salida.= '<td class = "text-center">'.$cant.'</td>';
				$salida.= '<td class = "text-center">'.$tipo.'</td>';
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
	if($CANT > 0){
		$PROMEDIO = $SUMA/$CANT;
		$PROMEDIO = number_format($PROMEDIO,2,'.','');
	}
	$salida.= '<tr>';
	//--
	$salida.= '<th class = "text-center"> </th>';
	$salida.= '<th class = "text-right"> Totales &nbsp; </th>';
	$salida.= '<th class = "text-center">'.$PROMEDIO.'</th>';
	$salida.= '<th class = "text-center">'.$CANT.'</th>';
	$salida.= '<th class = "text-center">-</th>';
	//--
	$salida.= '</tr>';
	/////////---------
	$salida.= '</tbody>';
	$salida.= '</table>';return $salida;
}


function combo_semanas($name,$class='') {

	$salida .= '<select name="'.$name.'" id="'.$name.'" class = "'.$class.' form-control">';
	$salida .= '<option value="">Seleccione</option>';
	$salida .='</select>';return $salida;
}

function daysOfWeek($anio,$semana,$dia_semana){
	return date("Y-m-d", strtotime($anio."-W".$semana.'-'.$dia_semana));
}?>