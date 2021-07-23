<?php
include_once('../html_fns.php');

function tabla_cumplimiento($codigo, $proceso, $sistema, $usuario)
{
	$ClsInd = new ClsIndicador();
	$ClsRev = new ClsRevision();
	$result = $ClsInd->get_indicador($codigo, "", $proceso, $sistema, $usuario, "", "", 1);

	if (is_array($result)) {
		$salida = '<table id="tabla" class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida .= '<th class = "text-left" width = "75px">Nombre</th>';
		$salida .= '<th class = "text-left" width = "75px">Proceso</th>';
		$salida .= '<th class = "text-left" width = "75px">Sistema</th>';
		$salida .= '<th class = "text-center" width = "40px">Cumplimiento</th>';
		$salida .= '<th class = "text-center" width = "40px">Promedio</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 0;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = $row["ind_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-dark btn-xs">' . Agrega_Ceros($codigo) . '</button>';
			$salida .= '<button type="button" class="btn btn-dark btn-xs" onclick = "detalle(' . $codigo . ');" title = "Ver Detalles" ><span class="fa fa-search"></span></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			//nombre
			$nombre = trim($row["ind_nombre"]);
			$salida .= '<td class = "text-left">' . $nombre . '</td>';
			//proceso
			$proceso = trim($row["obj_proceso"]);
			$salida .= '<td class = "text-left">' . $proceso . '</td>';
			//sistema
			$sistema = trim($row["obj_sistema"]);
			$salida .= '<td class = "text-left">' . $sistema . '</td>';
			// Cumplimiento
			$indicador = trim($row["ind_codigo"]);
			$numero_programaciones = $ClsInd->count_programacion("", $indicador);
			$numero_revisiones = $ClsRev->count_revision_indicador("", $indicador);
			$promedio_indicador = 0;
			if ($numero_programaciones != 0) $promedio_indicador = $numero_revisiones / $numero_programaciones;
			$salida .= '<td class = "text-center">' . round($promedio_indicador * 100, 2) . ' %</td>';
			// Evaluacion
			$umed = trim($row["medida_nombre"]);
			$revisiones = $ClsRev->get_revision_indicador("", $indicador);
			$promedio_indicador = 0;
			$j = 0;
			if(is_array($revisiones)) {
				foreach ($revisiones as $rowRevision) {
					$lectura = trim($rowRevision["rev_lectura"]);
					$j++;
					$promedio_indicador += intval($lectura);
				}
			}
			if ($j != 0) $promedio_indicador = round($promedio_indicador / $j, 2);
			$salida .= '<td class = "text-center">' . $promedio_indicador  . ' ' . $umed . '</td>';	$salida .= '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}

function tabla_programacion($usuario = "")
{
	$ClsInd = new ClsIndicador();
	$result = $ClsInd->get_programacion("", "", "", "", "", "", "", $usuario, date("d/m/Y"), date("d/m/Y"));

	if (is_array($result)) {
		$salida = '<table id="tabla" class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "50px">C&oacute;digo</th>';
		$salida .= '<th class = "text-center" width = "50px">Horario</th>';
		$salida .= '<th class = "text-left" width = "50px">Proceso</th>';
		$salida .= '<th class = "text-left" width = "50px">Sistema</th>';
		$salida .= '<th class = "text-left" width = "50px">Indicador</th>';
		$salida .= '<th class = "text-left" width = "75px">Usuario que Programa</th>';
		$salida .= '<th class = "text-left" width = "75px">Observaci&oacute;n</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 0;
		foreach ($result as $row) {
			$salida .= '<tr>';	//codigo
			$codigo = Agrega_Ceros($row["pro_codigo"]);
			$salida .= '<td class = "text-center">' . $codigo . '</td>';
			//horario
			$lide = trim($row["pro_hini"]) . '-' . trim($row["pro_hfin"]);
			$salida .= '<td class = "text-center">' . $lide . '</td>';
			//proceso
			$proceso = utf8_decode($row["obj_proceso"]);
			$salida .= '<td class = "text-left">' . $proceso . '</td>';
			//sistema
			$sistema = utf8_decode($row["obj_sistema"]);
			$salida .= '<td class = "text-left">' . $sistema . '</td>';
			//nombre
			$nombre = utf8_decode($row["ind_nombre"]);
			$salida .= '<td class = "text-left">' . $nombre . '</td>';
			//usuario
			$usuario = utf8_decode(trim($row["pro_usuario"]));
			$salida .= '<td class = "text-left">' . $usuario . '</td>';
			//observacion
			$obs = trim($row["pro_observaciones"]);
			$salida .= '<td class = "text-left">' . $obs . '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}


function tabla_cumplimiento_usuario($usuario)
{
	$ClsFic = new ClsFicha();
	$ClsInd = new ClsIndicador();
	$ClsRev = new ClsRevision();
	$asignadas = $ClsFic->get_ficha_usuario("", "", $usuario);
	if (is_array($asignadas)) {
		$salida = '<table id="tabla" class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida .= '<th class = "text-left" width = "75px">Nombre</th>';
		$salida .= '<th class = "text-left" width = "75px">Proceso</th>';
		$salida .= '<th class = "text-left" width = "75px">Sistema</th>';
		$salida .= '<th class = "text-center" width = "40px">Cumplimiento</th>';
		$salida .= '<th class = "text-center" width = "40px">Promedio</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 0;
		foreach ($asignadas as $rowFicha) {
			$proceso = $rowFicha["fic_codigo"];
			$result = $ClsInd->get_indicador("", "", $proceso, "", "", "", "", 1);
			foreach ($result as $row) {
				$salida .= '<tr>';
				//codigo
				$codigo = $row["ind_codigo"];
				$salida .= '<td class = "text-center" >';
				$salida .= '<div class="btn-group">';
				$salida .= '<button type="button" class="btn btn-dark btn-xs">' . Agrega_Ceros($codigo) . '</button>';
				$salida .= '<button type="button" class="btn btn-dark btn-xs" onclick = "detalle(' . $codigo . ');" title = "Ver Detalles" ><span class="fa fa-search"></span></button>';
				$salida .= '</div>';
				$salida .= '</td>';
				//nombre
				$nombre = trim($row["ind_nombre"]);
				$salida .= '<td class = "text-left">' . $nombre . '</td>';
				//proceso
				$proceso = trim($row["obj_proceso"]);
				$salida .= '<td class = "text-left">' . $proceso . '</td>';
				//sistema
				$sistema = trim($row["obj_sistema"]);
				$salida .= '<td class = "text-left">' . $sistema . '</td>';
				// Cumplimiento
				$indicador = trim($row["ind_codigo"]);
				$numero_programaciones = $ClsInd->count_programacion("", $indicador);
				$numero_revisiones = $ClsRev->count_revision_indicador("", $indicador);
				$promedio_indicador = 0;
				if ($numero_programaciones != 0) $promedio_indicador = $numero_revisiones / $numero_programaciones;
				$salida .= '<td class = "text-center">' . round($promedio_indicador * 100, 2) . ' %</td>';
				// Evaluacion
				$umed = trim($row["medida_nombre"]);
				$revisiones = $ClsRev->get_revision_indicador("", $indicador);
				$promedio_indicador = 0;
				$j = 0;
				foreach ($revisiones as $rowRevision) {
					$lectura = trim($rowRevision["rev_lectura"]);
					$j++;
					$promedio_indicador += intval($lectura);
				}
				if ($j != 0) $promedio_indicador = round($promedio_indicador / $j, 2);
				$salida .= '<td class = "text-center">' . $promedio_indicador  . ' ' . $umed . '</td>';		$salida .= '</td>';
				//--
				$salida .= '</tr>';
				$i++;
			}
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}
