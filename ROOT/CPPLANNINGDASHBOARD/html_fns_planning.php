<?php
include_once('../html_fns.php');

function objetivos_dashboard($proceso, $sistema, $usuario, $desde, $hasta)
{
	$ClsObj = new ClsObjetivo();
	$ClsAcc = new ClsAccion();
	$ClsEva = new ClsEvaluacion();
	$result = $ClsObj->get_objetivo_asignado($proceso, $sistema, $usuario);

	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10%">C&oacute;digo</th>';
		$salida .= '<th class = "text-center" width = "10%">Proceso</th>';
		$salida .= '<th class = "text-center" width = "10%">Sistema</th>';
		$salida .= '<th class = "text-center" width = "30%">Objetivo</th>';
		$salida .= '<th class = "text-center" width = "10%">Cumplimiento</th>';
		$salida .= '<th class = "text-center" width = "18%">Estado</th>';
		$salida .= '<th class = "text-center" width = "10%">Evaluaci&oacute;n</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		foreach ($result as $row) {
			$salida .= '<tr>';
			// Codigo
			$codigo = trim($row["obj_codigo"]);
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<a type="button" class="btn btn-dark btn-xs">' . agrega_Ceros($codigo) . '</a>';
			$salida .= '<button type="button" class="btn btn-dark btn-xs" onclick = "verEvaluaciones(' . $codigo . ');" title = "Ver Evaluaciones" ><i class="fa fa-search"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			// //Proceso
			$proceso = trim($row["fic_nombre"]);
			$salida .= '<td class = "text-left">' . $proceso . '</td>';
			// Sistema
			$sistema = trim($row["sis_nombre"]);
			$salida .= '<td class = "text-left">' . $sistema . '</td>';
			// Descripcion
			$descripcion = trim($row["obj_descripcion"]);
			$descripcion = nl2br($descripcion);
			$salida .= '<td class = "text-left">' . $descripcion . '</td>';
			// Cumplimiento
			$objetivo = trim($row["obj_codigo"]);
			$numero_programaciones = $ClsAcc->count_programacion("", "", $objetivo, $usuario);
			$numero_evaluaciones = $ClsEva->count_evaluacion("", "", "", "", "", "", $objetivo, $usuario);
			$promedio_objetivo = 0;
			if ($numero_programaciones != 0) $promedio_objetivo = $numero_evaluaciones / $numero_programaciones;
			$salida .= '<td class = "text-center">' . round($promedio_objetivo * 100, 2) . ' %</td>';
			// Evaluacion
			$ClsEva = new ClsEvaluacion();
			$evaluaciones = $ClsEva->get_evaluacion("", "", "", "", "", $usuario, intval($codigo));
			$promedioPunteo = 0;
			$j = 0;
			foreach ($evaluaciones as $rowEvaluacion) {
				$puntuacion = trim($rowEvaluacion["eva_puntuacion"]);
				$j++;
				$promedioPunteo += intval($puntuacion);
			}
			if ($j != 0) $promedioPunteo = round($promedioPunteo / $j,2);
			$estado = $row['Estado'];
			switch($estado){
				case '1':
					$salida .= '<th class="text-primary">En edicion</th>';
					break;
				case '2':
					$salida .= '<th class="text-danger">En aprobaci&oacute;</th>';
					break;
				case '3':
					$salida .= '<th class="text-success">Aprobado</th>';
					break;
				case '4':
					$salida .= '<th class="text-success">En Proceso</th>';
					break;
				case '5':
					$salida .= '<th class="text-success">Cancelada</th>';
					break;
				case '6':
					$salida .= '<th class="text-success">Finalizada</th>';
					break;
				case '7':
					$salida .= '<th class="text-success">Vencida</th>';
					break;
				default:
					$salida .= '<th class="text-primary">Sin planes de accion</th>';
			}
			$salida .= '<td class = "text-center">' . $promedioPunteo . ' pts.</td>';	$salida .= '</div>';
			$salida .= '</td>';
			//--
			$salida .= '</tr>';
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}

	return $salida;
}

function objetivos_gerencia($proceso, $sistema, $usuario)
{
	$ClsObj = new ClsObjetivo();
	$ClsAcc = new ClsAccion();
	$ClsEva = new ClsEvaluacion();
	$result = $ClsObj->get_objetivo_asignado($proceso, $sistema, $usuario);

	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">C&oacute;digo</th>';
		$salida .= '<th class = "text-center" width = "50px">Proceso</th>';
		$salida .= '<th class = "text-center" width = "30px">Sistema</th>';
		$salida .= '<th class = "text-center" width = "30px">Usuario</th>';
		$salida .= '<th class = "text-center" width = "250px">Objetivo</th>';
		$salida .= '<th class = "text-center" width = "20px">Cumplimiento</th>';
		$salida .= '<th class = "text-center" width = "20px">Evaluaci&oacute;n</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		foreach ($result as $row) {
			$salida .= '<tr>';
			// Codigo
			$codigo = trim($row["obj_codigo"]);
			$usuario = trim($row["fus_usuario"]);
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<a type="button" class="btn btn-dark btn-xs">' . agrega_Ceros($codigo) . '</a>';
			$salida .= '<button type="button" class="btn btn-dark btn-xs" onclick = "verEvaluaciones(' . $codigo . ','.$usuario.');" title = "Ver Evaluaciones" ><i class="fa fa-search"></i></button>';
			//$salida .= '<button type="button" class="btn btn-dark btn-xs" onclick = "verEvidencia(' . $codigo . ');" title = "Ver Evidencia" ><i class="fas fa-pen-nib"></i></button>';

			$salida .= '</div>';
			$salida .= '</td>';
			// Proceso
			$proceso = trim($row["fic_nombre"]);
			$salida .= '<td class = "text-left">' . $proceso . '</td>';
			// Sistema
			$sistema = trim($row["sis_nombre"]);
			$salida .= '<td class = "text-left">' . $sistema . '</td>';
			// Usuario
			$usuario = trim($row["usuario_nombre"]);
			$salida .= '<td class = "text-left">' . $usuario . '</td>';
			// Descripcion
			$descripcion = trim($row["obj_descripcion"]);
			$descripcion = nl2br($descripcion);
			$salida .= '<td class = "text-left">' . $descripcion . '</td>';
			// Cumplimiento
			$objetivo = trim($row["obj_codigo"]);
			$usuario = trim($row["fus_usuario"]);
			$numero_programaciones = $ClsAcc->count_programacion("", "", $objetivo, $usuario);
			$numero_evaluaciones = $ClsEva->count_evaluacion("", "", "", "", "", "", $objetivo, $usuario);
			$promedio_objetivo = 0;
			if ($numero_programaciones != 0) $promedio_objetivo = $numero_evaluaciones / $numero_programaciones;
			$salida .= '<td class = "text-center">' . round($promedio_objetivo * 100, 2) . ' %</td>';
			// Evaluacion
			$ClsEva = new ClsEvaluacion();
			$evaluaciones = $ClsEva->get_evaluacion("", "", "", "", "", $usuario, intval($codigo));
			$promedioPunteo = 0;
			$j = 0;
			if(is_array($evaluaciones)){
				foreach ($evaluaciones as $rowEvaluacion) {
					$puntuacion = trim($rowEvaluacion["eva_puntuacion"]);
					$j++;
					$promedioPunteo += intval($puntuacion);
				}
				if ($j != 0) $promedioPunteo = round($promedioPunteo / $j,2);
			}
			$salida .= '<td class = "text-center">' . $promedioPunteo . '  Pts</td>';	$salida .= '</div>';
			$salida .= '</td>';
			//--
			$salida .= '</tr>';
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}

	return $salida;
}
