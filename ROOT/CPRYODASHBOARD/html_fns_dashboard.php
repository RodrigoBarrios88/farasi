<?php
include_once('../html_fns.php');

function tabla_cumplimiento($codigo, $proceso, $sistema)
{
	$salida = '<table class="table table-striped dataTables-example" width="100%" >';
	$salida .= '<thead>';
	$salida .= '<tr>';
	$salida .= '<th class = "text-center" width = "10px">No.</th>';
	$salida .= '<th class = "text-center" width = "50px">Proceso</th>';
	$salida .= '<th class = "text-center" width = "30px">Sistema</th>';
	$salida .= '<th class = "text-center" width = "250px">Riesgo/Oportunidad</th>';
	$salida .= '<th class = "text-center" width = "20px">Cumplimiento</th>';
	$salida .= '<th class = "text-center" width = "20px">Evaluaci&oacute;n</th>';
	$salida .= '</tr>';
	$salida .= '</thead>';
	$salida .= '<tbody>';
	// Plan de Riesgo Aprobado
	$ClsRie = new ClsRiesgo();
	$ClsOpo = new ClsOportunidad();
	$ClsAct = new ClsActividad();
	$ClsPla = new ClsPlan();
	$i = 1;
	$clase = 1;
	while ($clase <= 2) {
		$aprobados = ($clase == 1) ? $ClsRie->get_riesgo() :  $ClsOpo->get_oportunidad();
		if (is_array($aprobados)) {
			foreach ($aprobados as $rowAprobado) {
				$salida .= '<tr>';
				//codigo
				$salida .= '<td class = "text-center" >';
				$salida .= '<div class="btn-group">';
				$salida .= '<button type="button" class="btn btn-dark btn-xs">' . $i . '.</button>';
				if ($clase == 1) {
					$codigo = trim($rowAprobado["rie_codigo"]);
					$salida .= '<button type="button" class="btn btn-dark btn-xs" onclick = "detalle(' . $codigo . ');" title = "Ver Detalles" ><span class="fa fa-search"></span></button>';
				} else {
					$codigo = trim($rowAprobado["opo_codigo"]);
					$salida .= '<button type="button" class="btn btn-dark btn-xs" onclick = "detalle_oportunidad(' . $codigo . ');" title = "Ver Detalles" ><span class="fa fa-search"></span></button>';
				}
				$salida .= '</div>';
				$salida .= '</td>';
				// Proceso
				$proceso = trim($rowAprobado["fic_nombre"]);
				$salida .= '<td class = "text-left">' . $proceso . '</td>';
				// Sistema
				$sistema = trim($rowAprobado["sis_nombre"]);
				$salida .= '<td class = "text-left">' . $sistema . '</td>';
				// Tipo
				$tipo = trim($rowAprobado["fod_descripcion"]);
				$tipo = nl2br($tipo);
				$salida .= '<td class = "text-left">' . $tipo . '</td>';
				//--
				$promedio_objetivo = 0;
				$promedioPunteo = 0;
				$total = 0;
				if ($clase == 1) {
					$codigo = trim($rowAprobado["rie_codigo"]);
					$result = $ClsPla->get_plan_ryo("", $codigo, "", "", "", "", 3);
				} else {
					$codigo = trim($rowAprobado["opo_codigo"]);
					$result = $ClsPla->get_plan_ryo("", "", $codigo, "", "", "", 3);
				}
				if (is_array($result)) {
					foreach ($result as $row) {
						$plan = $row["pla_codigo"];
						$numero_programaciones = intval($ClsAct->count_programacion("", $plan)); // Todas
						$numero_evaluaciones = intval($ClsAct->count_programacion("", $plan, "", "", 5)); // Finalizadas
						// Cumplimiento
						if ($numero_programaciones != 0) $promedio_objetivo += round(($numero_evaluaciones / $numero_programaciones) * 100, 2);
						// Evaluacion
						$evaluaciones = $ClsAct->get_programacion("", $plan, "", "", "5");
						$j = 0;
						if (is_array($evaluaciones)) {
							$pts = 0;
							foreach ($evaluaciones as $rowEvaluacion) {
								$puntuacion = intval($rowEvaluacion["pro_puntuacion"]);
								$j++;
								$pts += intval($puntuacion);
							}
						}
						if ($j != 0){
							$promedioPunteo += round($pts / $j, 2);
							$total += $j;
						}
					}
				}
				if($total != 0){
					$promedio_objetivo =  round($promedio_objetivo/$total,2);
					$promedioPunteo =  round($promedioPunteo/$total,2);
				}
				$salida .= '<td class = "text-center">' .  $promedio_objetivo .' %</td>';
				$salida .= '<td class = "text-center">' . $promedioPunteo . ' pts.</td>';
				$salida .= '</div>';
				$salida .= '</td>';
				//--
				$salida .= '</tr>';
				$i++;
			}
		}
		$clase++;
	}
	$salida .= '</tbody>';
	$salida .= '</table>';
	return $salida;
}

function tabla_programacion($usuario)
{
	$salida = '<table class="table table-striped dataTables-example" width="100%" >';
	$salida .= '<thead>';
	$salida .= '<tr>';
	$salida .= '<th class = "text-center" width = "5%"> No. </th>';
	$salida .= '<th class = "text-left" width = "10%">Proceso</th>';
	$salida .= '<th class = "text-left" width = "10%">Sistema</th>';
	$salida .= '<th class = "text-center" width = "20%">Riesgo/Oportunidad</th>';
	$salida .= '<th class = "text-center" width = "20%">Actividad</th>';
	$salida .= '<th class = "text-center" width = "20%">Programaci&oacute;n</th>';
	$salida .= '<th class = "text-center" width = "20%">Responsable</th>';
	$salida .= '</tr>';
	$salida .= '</thead>';
	$salida .= '<tbody>';
	// Plan de Riesgo Aprobado
	$ClsRie = new ClsRiesgo();
	$ClsOpo = new ClsOportunidad();
	$ClsAct = new ClsActividad();
	$ClsPla = new ClsPlan();
	$i = 1;
	$clase = 1;
	while ($clase <= 2) {
		$aprobados = ($clase == 1) ? $ClsRie->get_riesgo() :  $ClsOpo->get_oportunidad();
		if (is_array($aprobados)) {
			foreach ($aprobados as $rowAprobado) {
				$proceso = trim($rowAprobado["fic_nombre"]);
				$tipo = trim($rowAprobado["fod_descripcion"]);
				$tipo = nl2br($tipo);
				$sistema = trim($rowAprobado["sis_nombre"]);
				//--
				if ($clase == 1) {
					$riesgo = trim($rowAprobado["rie_codigo"]);
					$result = $ClsPla->get_plan_ryo("", $riesgo, "", $usuario, "", "", 3);
				} else {
					$oportunidad = trim($rowAprobado["opo_codigo"]);
					$result = $ClsPla->get_plan_ryo("", "", $oportunidad, $usuario, "", "", 3);
				}
				if (is_array($result)) {
					foreach ($result as $row) {
						$plan = trim($row["pla_codigo"]);
						$result = $ClsAct->get_programacion("", $plan, "", date("d/m/Y"), "1,2");
						if (is_array($result)) {
							foreach ($result as $row) {
								$salida .= '<tr>';
								// No. 
								$salida .= '<td class = "text-center">' . $i . '.</td>';
								// Proceso
								$salida .= '<td class = "text-left">' . $proceso . '</td>';
								// Sistema
								$salida .= '<td class = "text-left">' . $sistema . '</td>';
								// Tipo
								$salida .= '<td class = "text-left">' . $tipo . '</td>';
								// Descripcion
								$descripcion = trim($row["act_descripcion"]);
								$descripcion = nl2br($descripcion);
								$salida .= '<td class = "text-left">' . $descripcion . '</td>';
								// Fechas
								$fini = cambia_fecha($row["pro_fecha_inicio"]);
								$ffin = cambia_fecha($row["pro_fecha_fin"]);
								$salida .= '<td class = "text-center">' . $fini . ' - ' . $ffin . '</td>';
								// Responsable
								$responsable = trim($row["usu_nombre"]);
								$salida .= '<td class = "text-left">' . $responsable . '</td>';
								//--
								$salida .= '</tr>';
								$i++;
							}
						}
					}
				}
			}
		}
		$clase++;
	}
	$salida .= '</tbody>';
	$salida .= '</table>';
	return $salida;
}

function tabla_cumplimiento_usuario($usuario)
{
	$ClsFic = new ClsFicha();
	$ClsOpo = new ClsOportunidad();
	$ClsRie = new ClsRiesgo();
	$ClsAct = new ClsActividad();
	$ClsPla = new ClsPlan();
	$asignadas = $ClsFic->get_ficha_usuario("", "", $usuario);
	//var_dump($asignadas);
	if (is_array($asignadas)) {
		$salida = '<table id="tabla" class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">C&oacute;digo</th>';
		$salida .= '<th class = "text-center" width = "50px">Proceso</th>';
		$salida .= '<th class = "text-center" width = "30px">Sistema</th>';
		$salida .= '<th class = "text-center" width = "250px">Riesgo/Oportunidad</th>';
		$salida .= '<th class = "text-center" width = "20px">Cumplimiento</th>';
		$salida .= '<th class = "text-center" width = "20px">Evaluaci&oacute;n</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($asignadas as $rowFicha) {
			$ficha = $rowFicha["fic_codigo"];
			$clase = 1;
			while ($clase <= 2) {
				$aprobados = ($clase == 1) ? $ClsRie->get_riesgo("", "", $ficha) :  $ClsOpo->get_oportunidad("", "", $ficha);
				if (is_array($aprobados)) {
					foreach ($aprobados as $rowAprobado) {
						if ($clase == 1) {
							$codigo = trim($rowAprobado["rie_codigo"]);
							$result = $ClsPla->get_plan_ryo("", $codigo, "", $usuario, "", "", 3);
						} else {
							$codigo = trim($rowAprobado["opo_codigo"]);
							$result = $ClsPla->get_plan_ryo("", "", $codigo, $usuario, "", "", 3);
						}
						if (is_array($result)) {
							foreach ($result as $row) {
								$salida .= '<tr>';
								//codigo
								$salida .= '<td class = "text-center" >';
								$salida .= '<div class="btn-group">';
								$salida .= '<button type="button" class="btn btn-dark btn-xs">' . $i . '.</button>';
								if ($clase == 1) $salida .= '<button type="button" class="btn btn-dark btn-xs" onclick = "detalle(' . $codigo . ',' . $usuario . ');" title = "Ver Detalles" ><span class="fa fa-search"></span></button>';
								else $salida .= '<button type="button" class="btn btn-dark btn-xs" onclick = "detalle_oportunidad(' . $codigo . ',' . $usuario . ');" title = "Ver Detalles" ><span class="fa fa-search"></span></button>';
								$salida .= '</div>';
								$salida .= '</td>';
								// Proceso
								$proceso = trim($rowAprobado["fic_nombre"]);
								$salida .= '<td class = "text-left">' . $proceso . '</td>';
								// Sistema
								$sistema = trim($rowAprobado["sis_nombre"]);
								$salida .= '<td class = "text-left">' . $sistema . '</td>';
								// Tipo
								$tipo = trim($rowAprobado["fod_descripcion"]);
								$tipo = nl2br($tipo);
								$salida .= '<td class = "text-left">' . $tipo . '</td>';
								//--
								$plan = $row["pla_codigo"];
								$numero_programaciones = intval($ClsAct->count_programacion("", $plan)); // Todas
								$numero_evaluaciones = intval($ClsAct->count_programacion("", $plan, "", "", 5)); // Finalizadas
								// Cumplimiento
								$promedio_objetivo = 0;
								if ($numero_programaciones != 0) $promedio_objetivo = $numero_evaluaciones / $numero_programaciones;
								$salida .= '<td class = "text-center">' . round($promedio_objetivo * 100, 2) . ' %</td>';
								// Evaluacion
								$evaluaciones = $ClsAct->get_programacion("", $plan, "", "", "5");
								$promedioPunteo = 0;
								$j = 0;
								if (is_array($evaluaciones)) {
									foreach ($evaluaciones as $rowEvaluacion) {
										$puntuacion = trim($rowEvaluacion["pro_puntuacion"]);
										$j++;
										$promedioPunteo += intval($puntuacion);
									}
								}
								if ($j != 0) $promedioPunteo = round($promedioPunteo / $j, 2);
								$salida .= '<td class = "text-center">' . $promedioPunteo . ' pts.</td>';
								$salida .= '</div>';
								$salida .= '</td>';
								//--
								$salida .= '</tr>';
								$i++;
							}
						}
					}
				}
				$clase++;
			}
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}

function tabla_reportes($proceso, $sistema, $tipo, $usuario, $columnas)
{
	$ClsRie = new ClsRiesgo();
	$result = $ClsRie->get_riesgo("", "", $proceso, $sistema, $tipo, "", $usuario);
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">No.</th>';
		if (is_array($columnas)) {
			foreach ($columnas as $col) {
				$parametros = parametrosDinamicosHTML($col);
				$ancho = $parametros['ancho'];
				$titulo = $parametros['titulo'];
				$salida .= '<th class = "text-center" width = "' . $ancho . '">' . $titulo . '</th>';
			}
		} else {
			$salida .= '<th class = "text-center" width = "150px">Riesgo</th>';
			$salida .= '<th class = "text-center" width = "50">Proceso</th>';
			$salida .= '<th class = "text-center" width = "50">Sistema</th>';
			$salida .= '<th class = "text-center" width = "150px">Tipo</th>';
		}
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//--
			$salida .= '<td class = "text-center">' . $i . '.- </td>';
			//--
			if (is_array($columnas)) {
				foreach ($columnas as $col) {
					$parametros = parametrosDinamicosHTML($col);
					$campo = $parametros['campo'];
					$alineacion = $parametros['alineacion'];
					if ($col == "rie_codigo") {
						$campo = '# ' . Agrega_Ceros($row[$campo]);
					} else if ($col == "rie_fecha_registro") {
						$campo = cambia_fecha($row[$campo]);
					} else if ($col == "fod_tipo") {
						$campo = trim($row[$campo]);
						switch ($campo) {
							case 3:
								$campo = "Riesgo Interno";
								break;
							case 4:
								$campo = "Riesgo Externo";
								break;
						}
					} else if ($col == "rie_probabilidad") {
						$probabilidad = trim($row["rie_probabilidad"]);
						$campo = get_probabilidad($probabilidad);
					} else if ($col == "rie_impacto") {
						$impacto = trim($row["rie_impacto"]);
						$campo = get_impacto($impacto);
					} else if ($col == "rie_severidad") {
						$impacto = trim($row["rie_impacto"]);
						$probabilidad = trim($row["rie_probabilidad"]);
						$campo = intval($impacto) * intval($probabilidad);
					} else if ($col == "rie_condicion") {
						$impacto = trim($row["rie_impacto"]);
						$probabilidad = trim($row["rie_probabilidad"]);
						$campo = get_condicion(intval($impacto) * intval($probabilidad));
					} else if ($col == "rie_evaluacion_tipo") {
						$campo = trim($row[$campo]);
						switch ($campo) {
							case 1:
								$campo = "Evaluaci&oacute;n";
								break;
							case 2:
								$campo = "Reevaluaci&oacute;n";
								break;
						}
					} else if ($col == "rie_situacion") {
						$campo = trim($row[$campo]);
						switch ($campo) {
							case 1:
								$campo = "En edici&oacute;n";
								break;
							case 2:
								$campo = "En aprobaci&oacute;n";
								break;
							case 3:
								$campo = "Aprobado";
								break;
						}
					} else if ($col == "rie_accion") {
						$campo = get_accion_riesgo($row[$campo]);
					} else {
						$campo = utf8_decode(trim($row[$campo]));
					}
					//columna
					$salida .= '<td class = "' . $alineacion . '">' . $campo . '</td>';
				}
			} else {
				// Accion
				$descripcion = utf8_decode($row["fod_descripcion"]);
				$salida .= '<td class = "text-left">' . $descripcion . '</td>';
				// Proceso
				$proceso = utf8_decode($row["fic_nombre"]);
				$salida .= '<td class = "text-left">' . $proceso . '</td>';
				// Sistema
				$sistema = utf8_decode($row["sis_nombre"]);
				$salida .= '<td class = "text-left">' . $sistema . '</td>';
				// Tipo
				$tipo = trim($row["fod_tipo"]);
				switch ($tipo) {
					case 1:
						$campo = "Riesgo Interno";
						break;
					case 2:
						$campo = "Riesgo Externo";
						break;
				}
				$salida .= '<td class = "text-left">' . $tipo . '</td>';
			}
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}

	return $salida;
}

function tabla_reporte_oportunidades($proceso, $sistema, $usuario, $columnas)
{
	$ClsOpo = new ClsOportunidad();
	$result = $ClsOpo->get_oportunidad("", "", $proceso, $sistema, "", $usuario);
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">No.</th>';
		if (is_array($columnas)) {
			foreach ($columnas as $col) {
				$parametros = parametrosDinamicosHTML($col);
				$ancho = $parametros['ancho'];
				$titulo = $parametros['titulo'];
				$salida .= '<th class = "text-center" width = "' . $ancho . '">' . $titulo . '</th>';
			}
		} else {
			$salida .= '<th class = "text-center" width = "150px">Oportuinidad</th>';
			$salida .= '<th class = "text-center" width = "50">Proceso</th>';
			$salida .= '<th class = "text-center" width = "50">Sistema</th>';
		}
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//--
			$salida .= '<td class = "text-center">' . $i . '.- </td>';
			//--
			if (is_array($columnas)) {
				foreach ($columnas as $col) {
					$parametros = parametrosDinamicosHTML($col);
					$campo = $parametros['campo'];
					$alineacion = $parametros['alineacion'];
					if ($col == "opo_codigo") {
						$campo = '# ' . Agrega_Ceros($row[$campo]);
					} else if ($col == "opo_fecha_registro") {
						$campo = cambia_fecha($row[$campo]);
					} else if ($col == "opo_viabilidad") {
						$viabilidad = trim($row["opo_viabilidad"]);
						$campo = get_prioridad($viabilidad);
					} else if ($col == "opo_rentabilidad") {
						$rentabilidad = trim($row["opo_rentabilidad"]);
						$campo = get_prioridad($rentabilidad);
					} else if ($col == "opo_prioridad") {
						$rentabilidad = trim($row["opo_rentabilidad"]);
						$viabilidad = trim($row["opo_viabilidad"]);
						$campo = intval($rentabilidad) * intval($viabilidad);
					} else if ($col == "opo_condicion") {
						$rentabilidad = trim($row["opo_rentabilidad"]);
						$viabilidad = trim($row["opo_viabilidad"]);
						$campo = get_condicion_oportunidad(intval($rentabilidad) * intval($viabilidad));
					} else if ($col == "opo_situacion") {
						$campo = trim($row[$campo]);
						switch ($campo) {
							case 1:
								$campo = "En edici&oacute;n";
								break;
							case 2:
								$campo = "En aprobaci&oacute;n";
								break;
							case 3:
								$campo = "Aprobado";
								break;
						}
					} else if ($col == "opo_accion") {
						$campo = get_accion_oportunidad($row[$campo]);
					} else {
						$campo = utf8_decode(trim($row[$campo]));
					}
					//columna
					$salida .= '<td class = "' . $alineacion . '">' . $campo . '</td>';
				}
			} else {
				// Accion
				$descripcion = utf8_decode($row["fod_descripcion"]);
				$salida .= '<td class = "text-left">' . $descripcion . '</td>';
				// Proceso
				$proceso = utf8_decode($row["fic_nombre"]);
				$salida .= '<td class = "text-left">' . $proceso . '</td>';
				// Sistema
				$sistema = utf8_decode($row["sis_nombre"]);
				$salida .= '<td class = "text-left">' . $sistema . '</td>';
			}
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}

	return $salida;
}

function tabla_reporte_planes($proceso, $sistema, $tipo, $desde, $hasta, $usuario, $situacion, $columnas)
{
	// Plan de Riesgo Aprobado
	$ClsPla = new ClsPlan();
	$planes = $ClsPla->get_plan_ryo("", "", "", $usuario, $desde, $hasta, $situacion);
	if (is_array($planes)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">No.</th>';
		if (is_array($columnas)) {
			foreach ($columnas as $col) {
				$parametros = parametrosDinamicosHTML($col);
				$ancho = $parametros['ancho'];
				$titulo = $parametros['titulo'];
				$salida .= '<th class = "text-center" width = "' . $ancho . '">' . $titulo . '</th>';
			}
		}
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($planes as $row) {
			$salida .= '<tr>';
			//--
			$salida .= '<td class = "text-center">' . $i . '.- </td>';
			//--
			if (is_array($columnas)) {
				foreach ($columnas as $col) {
					$parametros = parametrosDinamicosHTML($col);
					$campo = $parametros['campo'];
					$alineacion = $parametros['alineacion'];
					if ($col == "pla_codigo") {
						$campo = '# ' . Agrega_Ceros($row[$campo]);
					} else if ($col == "pla_fecha_inicio" || $col == "pla_fecha_fin") {
						$campo = cambia_fecha($row[$campo]);
					} else if ($col == "pla_situacion") {
						switch ($row[$campo]) {
							case 1:
								$campo = "En edici&oacute;n";
								break;
							case 2:
								$campo = "En Aprobaci&oacute;n";
								break;
							case 3:
								$campo = "Aprobado";
								break;
						}
					} else if ($col == "fod_sistema" || $col == "fic_nombre" || $col == "fod_descripcion") {
						$campo = utf8_decode($row[$campo]);
					} else {
						$campo = utf8_decode(trim($row[$campo]));
					}
					//columna
					$salida .= '<td class = "' . $alineacion . '">' . $campo . '</td>';
				}
			}
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}

function tabla_reporte_evaluaciones($procesoFiltro, $sistemaFiltro, $desde, $hasta, $usuario, $situacion, $columnas)
{
	// Plan de Riesgo Aprobado
	$ClsPla = new ClsPlan();
	$planes = $ClsPla->get_plan_ryo("", "", "", $usuario);
	if (is_array($planes)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">No.</th>';
		if (is_array($columnas)) {
			foreach ($columnas as $col) {
				$parametros = parametrosDinamicosHTML($col);
				$ancho = $parametros['ancho'];
				$titulo = $parametros['titulo'];
				$salida .= '<th class = "text-center" width = "' . $ancho . '">' . $titulo . '</th>';
			}
		}
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		$ClsAct = new ClsActividad();
		foreach ($planes as $rowPlan) {
			$plan = trim($rowPlan["pla_codigo"]);
			// Enlazar con su riesgo o su oportunidad para la informacion
			$riesgo = trim($rowPlan["pla_riesgo"]);
			$oportunidad = trim($rowPlan["pla_oportunidad"]);
			$ClsRie = new ClsRiesgo();
			$ClsOpo = new ClsOportunidad();
			if ($riesgo != 0) $info = $ClsRie->get_riesgo($riesgo, "", $procesoFiltro, $sistemaFiltro);
			else $info = $ClsOpo->get_oportunidad($oportunidad, "", $procesoFiltro, $sistemaFiltro);
			if (is_array($info)) {
				foreach ($info as $row) {
					$proceso = utf8_decode($row["fic_nombre"]);
					$sistema = utf8_decode($row["sis_nombre"]);
					$descripcion = utf8_decode($row["fod_descripcion"]);
					$actividades = $ClsAct->get_programacion("", $plan, "", "", $situacion, $desde, $hasta);
					if (is_array($actividades)) {
						foreach ($actividades as $row) {
							$salida .= '<tr>';
							//--
							$salida .= '<td class = "text-center">' . $i . '.- </td>';
							//--
							if (is_array($columnas)) {
								foreach ($columnas as $col) {
									$parametros = parametrosDinamicosHTML($col);
									$campo = $parametros['campo'];
									$alineacion = $parametros['alineacion'];
									if ($col == "pro_codigo") {
										$campo = '# ' . Agrega_Ceros($row[$campo]);
									} else if ($col == "act_tipo") {
										$campo = trim($row[$campo]);
										switch ($campo) {
											case 1:
												$campo = "Plan de Acci&oacute;n";
												break;
											case 2:
												$campo = "Plan Inmediato";
												break;
										}
									} else if ($col == "pro_situacion") {
										$campo = trim($row[$campo]);
										switch ($campo) {
											case 0:
												$campo = "Cancelada";
												break;
											case 1:
												$campo = "Pendiente";
												break;
											case 2:
												$campo = "En Proceso";
												break;
											case 3:
												$campo = "Ejecutada";
												break;
											case 4:
												$campo = "En Evaluaci&oacute;n";
												break;
											case 5:
												$campo = "Finalizada";
												break;
										}
									} else if ($col == "pro_puntuacion") {
										$campo = (trim($row[$campo]) == 0) ? "Sin Puntuar" : trim($row[$campo]);
									} else if ($col == "pro_fecha" || $col == "pro_fecha_evaluacion") {
										$campo = ($row[$campo] == 0) ? "Sin Fecha" : cambia_fecha($row[$campo]);
									} else if ($col == "pro_fecha_inicio" || $col == "pro_fecha_fin") {
										$campo = cambia_fecha($row[$campo]);
									} else if ($col == "fic_nombre") {
										$campo = $proceso;
									} else if ($col == "fod_sistema") {
										$campo = $sistema;
									} else if ($col == "fod_descripcion") {
										$campo = $descripcion;
									} else {
										$campo = utf8_decode(trim($row[$campo]));
									}
									//columna
									$salida .= '<td class = "' . $alineacion . '">' . $campo . '</td>';
								}
							}
							//--
							$salida .= '</tr>';
							$i++;
						}
					}
				}
			}
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}

	return $salida;
}

function tabla_reporte_actividad($procesoFiltro, $sistemaFiltro, $tipo, $desde, $hasta, $usuario, $columnas)
{
	// Plan de Riesgo Aprobado
	$ClsPla = new ClsPlan();
	$planes = $ClsPla->get_plan_ryo("", "", "", $usuario);
	if (is_array($planes)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">No.</th>';
		if (is_array($columnas)) {
			foreach ($columnas as $col) {
				$parametros = parametrosDinamicosHTML($col);
				$ancho = $parametros['ancho'];
				$titulo = $parametros['titulo'];
				$salida .= '<th class = "text-center" width = "' . $ancho . '">' . $titulo . '</th>';
			}
		}
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;

		$ClsAct = new ClsActividad();
		foreach ($planes as $rowPlan) {
			$plan = trim($rowPlan["pla_codigo"]);
			// Enlazar con su riesgo o su oportunidad para la informacion
			$riesgo = trim($rowPlan["pla_riesgo"]);
			$oportunidad = trim($rowPlan["pla_oportunidad"]);
			$ClsRie = new ClsRiesgo();
			$ClsOpo = new ClsOportunidad();
			if ($riesgo != 0) $info = $ClsRie->get_riesgo($riesgo, "", $procesoFiltro, $sistemaFiltro);
			else $info = $ClsOpo->get_oportunidad($oportunidad, "", $procesoFiltro, $sistemaFiltro);
			if (is_array($info)) {
				foreach ($info as $row) {
					$proceso = utf8_decode($row["fic_nombre"]);
					$sistema = utf8_decode($row["sis_nombre"]);
					$descripcion = utf8_decode($row["fod_descripcion"]);
					$actividades = $ClsAct->get_actividad("", $plan, $tipo, "", "", "", $desde, $hasta);
					if (is_array($actividades)) {
						foreach ($actividades as $row) {
							$salida .= '<tr>';
							//--
							$salida .= '<td class = "text-center">' . $i . '.- </td>';
							//--
							if (is_array($columnas)) {
								foreach ($columnas as $col) {
									$parametros = parametrosDinamicosHTML($col);
									$campo = $parametros['campo'];
									$alineacion = $parametros['alineacion'];
									if ($col == "act_codigo") {
										$campo = '# ' . Agrega_Ceros($row[$campo]);
									} else if ($col == "act_tipo") {
										$campo = trim($row[$campo]);
										switch ($campo) {
											case 1:
												$campo = "Plan de Acci&oacute;n";
												break;
											case 2:
												$campo = "Plan Inmediato";
												break;
										}
									} else if ($col == "act_fecha_inicio" || $col == "act_fecha_fin" || $col == "pla_fecha_creacion"  || $col == "pla_fecha_revision") {
										if ($row[$campo] == "0000-00-00") $campo = "Sin Fecha";
										else $campo = cambia_fecha($row[$campo]);
									} else if ($col == "pla_situacion") {
										switch ($row[$campo]) {
											case 1:
												$campo = "En edici&oacute;n";
												break;
											case 2:
												$campo = "En Aprobaci&oacute;n";
												break;
											case 3:
												$campo = "Aprobado";
												break;
										}
									} else if ($col == "act_periodicidad") {
										$campo = trim($row[$campo]);
										switch ($campo) {
											case "W":
												$campo = "Semanal";
												break;
											case "M":
												$campo = "Mensual";
												break;
											case "U":
												$campo = "&Uacute;nica";
												break;
										}
									} else if ($col == "pro_situacion") {
										$campo = trim($row[$campo]);
										switch ($campo) {
											case 0:
												$campo = "Cancelada";
												break;
											case 1:
												$campo = "Pendiente";
												break;
											case 2:
												$campo = "En Proceso";
												break;
											case 3:
												$campo = "Ejecutada";
												break;
											case 4:
												$campo = "En Evaluaci&oacute;n";
												break;
											case 5:
												$campo = "Finalizada";
												break;
										}
									} else if ($col == "pro_puntuacion") {
										$campo = (trim($row[$campo]) == 0) ? "Sin Puntuar" : trim($row[$campo]);
									} else if ($col == "pro_fecha" || $col == "pro_fecha_evaluacion") {
										$campo = ($row[$campo] == 0) ? "Sin Fecha" : cambia_fecha($row[$campo]);
									} else if ($col == "pro_fecha_inicio" || $col == "pro_fecha_fin") {
										$campo = cambia_fecha($row[$campo]);
									} else if ($col == "fic_nombre") {
										$campo = $proceso;
									} else if ($col == "fod_sistema") {
										$campo = $sistema;
									} else if ($col == "fod_descripcion") {
										$campo = $descripcion;
									} else {
										$campo = utf8_decode(trim($row[$campo]));
									}
									//columna
									$salida .= '<td class = "' . $alineacion . '">' . $campo . '</td>';
								}
							}
							//--
							$salida .= '</tr>';
							$i++;
						}
					}
				}
			}
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}

	return $salida;
}
///////////////// Extras /////////////////
function combo_tipo_riesgo($name, $instruc = '', $class = '')
{
	$salida  = '<select name="' . $name . '" id="' . $name . '" onchange="' . $instruc . '" class = "' . $class . ' form-control">';
	$salida .= '<option value="">Seleccione</option>';
	$salida .= '<option value="3">Internos</option>';
	$salida .= '<option value="4">Externos</option>';
	$salida .= '</select>';
	return $salida;
}

function get_condicion($severidad)
{
	if ($severidad <= 5) return "Riesgo M&iacute;nimo";
	if ($severidad > 5 && $severidad <= 10) return "Riesgo Bajo";
	if ($severidad > 10 && $severidad <= 15) return "Riesgo Medio";
	if ($severidad > 15) return "Riesgo Alto";
}
function get_condicion_excel($severidad)
{
	if ($severidad <= 5) return "Riesgo Mínimo";
	if ($severidad > 5 && $severidad <= 10) return "Riesgo Bajo";
	if ($severidad > 10 && $severidad <= 15) return "Riesgo Medio";
	if ($severidad > 15) return "Riesgo Alto";
}

function get_condicion_oportunidad($prioridad)
{
	if ($prioridad <= 5) return "Trivial";
	if ($prioridad > 5 && $prioridad <= 10) return "Viable";
	if ($prioridad > 10 && $prioridad <= 15) return "Factible";
	if ($prioridad > 15) return "Prioritario";
}

function get_probabilidad($probabilidad)
{
	switch ($probabilidad) {
		case 0:
			return "Sin Evaluar";
		case 1:
			return "No ocurre en 5 a&ntilde;os";
		case 2:
			return "1 vez en 5 a&ntilde;os";
		case 3:
			return "1 vez en 2 a&ntilde;os";
		case 4:
			return "1 vez en 1 a&ntilde;o";
		case 5:
			return "M&aacute;s de una vez al a&ntilde;o";
	}
}

function get_probabilidad_excel($probabilidad)
{
	switch ($probabilidad) {
		case 0:
			return "Sin Evaluar";
		case 1:
			return "No ocurre en 5 años";
		case 2:
			return "1 vez en 5 años";
		case 3:
			return "1 vez en 2 años";
		case 4:
			return "1 vez en 1 años";
		case 5:
			return "Más de una vez al año";
	}
}

function get_impacto($impacto)
{
	switch ($impacto) {
		case 0:
			return "Sin Evaluar";
		case 1:
			return utf8_encode("Pequeño");
		case 2:
			return "Moderado";
		case 3:
			return "Grande";
		case 4:
			return "Catastrofico";
	}
}

function get_accion_riesgo($accion)
{
	switch ($accion) {
		case 0:
			return utf8_encode("Sin Acción");
		case 1:
			return "Eliminar";
		case 2:
			return "Mitigar";
		case 3:
			return "Compartir";
		case 4:
			return "Transferir";
		case 5:
			return "Aceptar";
	}
}
function get_accion_oportunidad($accion)
{
	switch ($accion) {
		case 0:
			return "Sin Evaluar";
		case 1:
			return "Explotar";
		case 2:
			return "Compartir";
		case 3:
			return "Mejorar";
		case 4:
			return "Aceptar";
	}
}
function get_prioridad($impacto)
{
	switch ($impacto) {
		case 0:
			return "Sin Evaluar";
		case 1:
			return "Muy Baja";
		case 2:
			return "Baja";
		case 3:
			return "Media";
		case 4:
			return "Alta";
		case 5:
			return "Muy Alta";
	}
}

function parametrosDinamicosHTML($columna)
{
	switch ($columna) {
			// Riesgos
		case "rie_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Riesgo";
			$respuesta["campo"] = "rie_codigo";
			break;
		case "fic_nombre":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Proceso";
			$respuesta["campo"] = "fic_nombre";
			break;
		case "fod_descripcion":
			$respuesta["ancho"] = "200";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Descripci&oacute;n";
			$respuesta["campo"] = "fod_descripcion";
			break;
		case "fod_tipo":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-justify";
			$respuesta["titulo"] = "Tipo";
			$respuesta["campo"] = "fod_tipo";
			break;
		case "fod_sistema":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Sistema";
			$respuesta["campo"] = "sis_nombre";
			break;
		case "rie_origen":
			$respuesta["ancho"] = "200";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Origen";
			$respuesta["campo"] = "rie_origen";
			break;
		case "rie_causa":
			$respuesta["ancho"] = "200";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Causa";
			$respuesta["campo"] = "rie_causa";
			break;
		case "rie_consecuencia":
			$respuesta["ancho"] = "200";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Consecuencia";
			$respuesta["campo"] = "rie_consecuencia";
			break;
		case "rie_probabilidad":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Probabilidad";
			$respuesta["campo"] = "rie_probabilidad";
			break;
		case "rie_impacto":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Impacto";
			$respuesta["campo"] = "rie_impacto";
			break;
		case "rie_severidad":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Severidad";
			$respuesta["campo"] = "rie_severidad";
			break;
		case "rie_condicion":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Condici&oacute;n";
			$respuesta["campo"] = "rie_condicion";
			break;
		case "rie_usuario":
		case "opo_usuario":
		case "usu_nombre":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-justify";
			$respuesta["titulo"] = "Usuario que Identifica";
			$respuesta["campo"] = "usu_nombre";
			break;
		case "rie_evaluacion_tipo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Evaluaci&oacute;n/Reevaluaci&oacute;n";
			$respuesta["campo"] = "rie_evaluacion_tipo";
			break;
		case "rie_accion":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Acci&oacute;n";
			$respuesta["campo"] = "rie_accion";
			break;
		case "rie_justificacion":
			$respuesta["ancho"] = "200";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Justificaci&oacute;n de la Revisi&oacute;n";
			$respuesta["campo"] = "rie_justificacion";
			break;
		case "rie_revisa":
		case "opo_revisa":
		case "usu_revisa":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Revisado Por";
			$respuesta["campo"] = "usu_revisa";
			break;
		case "rie_fecha_registro":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Fecha de Identificaci&oacute;n";
			$respuesta["campo"] = "rie_fecha_registro";
			break;
		case "rie_situacion":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Situaci&oacute;n";
			$respuesta["campo"] = "rie_situacion";
			break;
			///////////////////////////// Oportunidad //////////////////////
		case "opo_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo de Oportunidad";
			$respuesta["campo"] = "opo_codigo";
			break;
		case "opo_viabilidad":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Viabilidad";
			$respuesta["campo"] = "opo_viabilidad";
			break;
		case "opo_rentabilidad":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Rentabilidad";
			$respuesta["campo"] = "opo_rentabilidad";
			break;
		case "opo_prioridad":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Prioridad";
			$respuesta["campo"] = "opo_prioridad";
			break;
		case "opo_condicion":
			$respuesta["ancho"] = "75";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Condici&oacute;n";
			$respuesta["campo"] = "opo_condicion";
			break;
		case "opo_accion":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Acci&oacute;n";
			$respuesta["campo"] = "opo_accion";
			break;
		case "opo_justificacion":
			$respuesta["ancho"] = "200";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Justificaci&oacute;n de la Revisi&oacute;n";
			$respuesta["campo"] = "opo_justificacion";
			break;
		case "opo_fecha_registro":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Fecha de Identificaci&oacute;n";
			$respuesta["campo"] = "opo_fecha_registro";
			break;
		case "opo_situacion":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Situaci&oacute;n";
			$respuesta["campo"] = "opo_situacion";
			break;
			///////////////////////////// Actividad //////////////////////
		case "act_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo";
			$respuesta["campo"] = "act_codigo";
			break;
		case "act_responsable":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Responsable";
			$respuesta["campo"] = "usu_nombre";
			break;
		case "act_periodicidad":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Periodicidad";
			$respuesta["campo"] = "act_periodicidad";
			break;
		case "act_fecha_inicio":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha de Inicio";
			$respuesta["campo"] = "act_fecha_inicio";
			break;
		case "act_fecha_fin":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha Fin";
			$respuesta["campo"] = "act_fecha_fin";
			break;
		case "act_descripcion":
			$respuesta["ancho"] = "200";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Actividad";
			$respuesta["campo"] = "act_descripcion";
			break;
		case "act_comentario":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Comentario de la Revisi&oacute;n";
			$respuesta["campo"] = "act_comentario";
			break;
		case "act_tipo":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Tipo del Plan";
			$respuesta["campo"] = "act_tipo";
			break;
		case "act_situacion":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Situaci&oacute;n";
			$respuesta["campo"] = "act_situacion";
			break;
			///////////////////////////// Programacion //////////////////////
		case "pro_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo";
			$respuesta["campo"] = "pro_codigo";
			break;
		case "pro_fecha_inicio":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha de Inicio";
			$respuesta["campo"] = "pro_fecha_inicio";
			break;
		case "pro_fecha_fin":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha Fin";
			$respuesta["campo"] = "pro_fecha_fin";
			break;
		case "pro_fecha":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha Ejecutada";
			$respuesta["campo"] = "pro_fecha";
			break;
		case "pro_fecha_evaluacion":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha de Evaluaci&oacute;n";
			$respuesta["campo"] = "pro_fecha_evaluacion";
			break;
		case "pro_ejecucion":
			$respuesta["ancho"] = "200";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Observaciones de la Ejecuci&oacute;n";
			$respuesta["campo"] = "pro_ejecucion";
			break;
		case "pro_evaluacion":
			$respuesta["ancho"] = "200";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Observaciones de la Ejecuci&oacute;n";
			$respuesta["campo"] = "pro_evaluacion";
			break;
		case "pro_puntuacion":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Puntuaci&oacute;n";
			$respuesta["campo"] = "pro_puntuacion";
			break;
		case "usu_evalua":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Evaluado Por";
			$respuesta["campo"] = "usu_evalua";
			break;
		case "pro_situacion":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Situaci&oacute;n";
			$respuesta["campo"] = "pro_situacion";
			break;
			///////////////////////////// Planes //////////////////////
		case "pla_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo";
			$respuesta["campo"] = "pla_codigo";
			break;
		case "pla_responsable":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Responsable";
			$respuesta["campo"] = "usu_nombre";
			break;
		case "pla_fecha_creacion":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha de Creaci&oacute;n";
			$respuesta["campo"] = "pla_fecha_creacion";
			break;
		case "usu_revisa":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Usuario que Revisa";
			$respuesta["campo"] = "usu_revisa";
			break;
		case "pla_justificacion":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Justificaci&oacute;n de la Revisi&oacute;n";
			$respuesta["campo"] = "pla_justificacion";
			break;
		case "pla_fecha_revision":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha de la Revisi&oacute;n";
			$respuesta["campo"] = "pla_fecha_revision";
			break;
		case "pla_situacion":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Situaci&oacute;n";
			$respuesta["campo"] = "pla_situacion";
			break;
	}
	return $respuesta;
}

function parametrosDinamicosEXCEL($columna)
{
	switch ($columna) {
			// Riesgos
		case "rie_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Código Riesgo";
			$respuesta["campo"] = "rie_codigo";
			break;
		case "fic_nombre":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Proceso";
			$respuesta["campo"] = "fic_nombre";
			break;
		case "fod_descripcion":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Descripción";
			$respuesta["campo"] = "fod_descripcion";
			break;
		case "fod_tipo":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "J";
			$respuesta["titulo"] = "Tipo";
			$respuesta["campo"] = "fod_tipo";
			break;
		case "fod_sistema":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Sistema";
			$respuesta["campo"] = "sis_nombre";
			break;
		case "rie_origen":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Origen";
			$respuesta["campo"] = "rie_origen";
			break;
		case "rie_causa":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Causa";
			$respuesta["campo"] = "rie_causa";
			break;
		case "rie_consecuencia":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Consecuencia";
			$respuesta["campo"] = "rie_consecuencia";
			break;
		case "rie_probabilidad":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Probabilidad";
			$respuesta["campo"] = "rie_probabilidad";
			break;
		case "rie_impacto":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Impacto";
			$respuesta["campo"] = "rie_impacto";
			break;
		case "rie_severidad":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Severidad";
			$respuesta["campo"] = "rie_severidad";
			break;
		case "rie_condicion":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Condición";
			$respuesta["campo"] = "rie_condicion";
			break;
		case "rie_usuario":
		case "opo_usuario":
		case "usu_nombre":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "J";
			$respuesta["titulo"] = "Usuario que Identifica";
			$respuesta["campo"] = "usu_nombre";
			break;
		case "rie_evaluacion_tipo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Evaluación/Reevaluación";
			$respuesta["campo"] = "rie_evaluacion_tipo";
			break;
		case "rie_accion":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Acción";
			$respuesta["campo"] = "rie_accion";
			break;
		case "rie_justificacion":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Justificación de la Revisión";
			$respuesta["campo"] = "rie_justificacion";
			break;
		case "rie_revisa":
		case "opo_revisa":
		case "usu_revisa":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Revisado Por";
			$respuesta["campo"] = "usu_revisa";
			break;
		case "rie_fecha_registro":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Fecha de Identificación";
			$respuesta["campo"] = "rie_fecha_registro";
			break;
		case "rie_situacion":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Situación";
			$respuesta["campo"] = "rie_situacion";
			break;
			///////////////////////////// Oportunidad //////////////////////
		case "opo_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Código de Oportunidad";
			$respuesta["campo"] = "opo_codigo";
			break;
		case "opo_viabilidad":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Viabilidad";
			$respuesta["campo"] = "opo_viabilidad";
			break;
		case "opo_rentabilidad":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Rentabilidad";
			$respuesta["campo"] = "opo_rentabilidad";
			break;
		case "opo_prioridad":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Prioridad";
			$respuesta["campo"] = "opo_prioridad";
			break;
		case "opo_condicion":
			$respuesta["ancho"] = "75";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Condición";
			$respuesta["campo"] = "opo_condicion";
			break;
		case "opo_accion":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Acción";
			$respuesta["campo"] = "opo_accion";
			break;
		case "opo_justificacion":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Justificación de la Revisión";
			$respuesta["campo"] = "opo_justificacion";
			break;
		case "opo_fecha_registro":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Fecha de Identificación";
			$respuesta["campo"] = "opo_fecha_registro";
			break;
		case "opo_situacion":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Situación";
			$respuesta["campo"] = "opo_situacion";
			break;
			///////////////////////////// Actividad //////////////////////
		case "act_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Código";
			$respuesta["campo"] = "act_codigo";
			break;
		case "act_responsable":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Responsable";
			$respuesta["campo"] = "usu_nombre";
			break;
		case "act_periodicidad":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Periodicidad";
			$respuesta["campo"] = "act_periodicidad";
			break;
		case "act_fecha_inicio":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha de Inicio";
			$respuesta["campo"] = "act_fecha_inicio";
			break;
		case "act_fecha_fin":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Fin";
			$respuesta["campo"] = "act_fecha_fin";
			break;
		case "act_descripcion":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Actividad";
			$respuesta["campo"] = "act_descripcion";
			break;
		case "act_comentario":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Comentario de la Revisión";
			$respuesta["campo"] = "act_comentario";
			break;
		case "act_tipo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Tipo del Plan";
			$respuesta["campo"] = "act_tipo";
			break;
		case "act_situacion":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Situación";
			$respuesta["campo"] = "act_situacion";
			break;
			///////////////////////////// Programacion //////////////////////
		case "pro_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Código";
			$respuesta["campo"] = "pro_codigo";
			break;
		case "pro_fecha_inicio":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha de Inicio";
			$respuesta["campo"] = "pro_fecha_inicio";
			break;
		case "pro_fecha_fin":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Fin";
			$respuesta["campo"] = "pro_fecha_fin";
			break;
		case "pro_fecha":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Ejecutada";
			$respuesta["campo"] = "pro_fecha";
			break;
		case "pro_fecha_evaluacion":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha de Evaluación";
			$respuesta["campo"] = "pro_fecha_evaluacion";
			break;
		case "pro_ejecucion":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Observaciones de la Ejecución";
			$respuesta["campo"] = "pro_ejecucion";
			break;
		case "pro_evaluacion":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Observaciones de la Ejecución";
			$respuesta["campo"] = "pro_evaluacion";
			break;
		case "pro_puntuacion":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Puntuación";
			$respuesta["campo"] = "pro_puntuacion";
			break;
		case "usu_evalua":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Evaluado Por";
			$respuesta["campo"] = "usu_evalua";
			break;
		case "pro_situacion":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Situación";
			$respuesta["campo"] = "pro_situacion";
			break;
			///////////////////////////// Planes //////////////////////
		case "pla_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Código";
			$respuesta["campo"] = "pla_codigo";
			break;
		case "pla_responsable":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Responsable";
			$respuesta["campo"] = "usu_nombre";
			break;
		case "pla_fecha_creacion":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha de Creación";
			$respuesta["campo"] = "pla_fecha_creacion";
			break;
		case "usu_revisa":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Usuario que Revisa";
			$respuesta["campo"] = "usu_revisa";
			break;
		case "pla_justificacion":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Justificación de la Revisión";
			$respuesta["campo"] = "pla_justificacion";
			break;
		case "pla_fecha_revision":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha de la Revisión";
			$respuesta["campo"] = "pla_fecha_revision";
			break;
		case "pla_situacion":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Situación";
			$respuesta["campo"] = "pla_situacion";
			break;
	}
	return $respuesta;
}
