<?php
include_once('../html_fns.php');

function tabla_ejecucion($usuario)
{
	$ClsPla = new ClsPlan();
	$ClsHal = new ClsHallazgo();
	$ClsAct = new ClsActividad();
	$planes = $ClsPla->get_plan_mejora("", "", $usuario, "", "", 3); // Planes aprobados para el usuario
	if (is_array($planes)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%"> No. </th>';
		$salida .= '<th class = "text-left" width = "10%">Proceso</th>';
		$salida .= '<th class = "text-left" width = "10%">Sistema</th>';
		$salida .= '<th class = "text-center" width = "30%">Hallazgo</th>';
		$salida .= '<th class = "text-center" width = "30%">Actividad</th>';
		$salida .= '<th class = "text-center" width = "20%">Programaci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "10%"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($planes as $rowPlan) {
			$plan = trim($rowPlan["pla_codigo"]);
			$origen = trim($rowPlan["hal_origen"]);
			$hallazgo = trim($rowPlan["pla_hallazgo"]);
			switch ($origen) {
				case 1:
					$info = $ClsHal->get_hallazgo_auditoria_interna($hallazgo);
					break;
				case 2:
					$info = $ClsHal->get_hallazgo_auditoria_externa($hallazgo);
					break;
				case 3:
					$info = $ClsHal->get_hallazgo_queja($hallazgo);
					break;
				case 4:
					$info = $ClsHal->get_hallazgo_indicador($hallazgo);
					break;
				case 5:
					$info = $ClsHal->get_hallazgo_riesgo($hallazgo);
					break;
				case 6:
					$info = $ClsHal->get_hallazgo_requisito($hallazgo);
					break;
			}
			if (is_array($info)) {
				foreach ($info as $row) {
					$proceso = utf8_decode($row["fic_nombre"]);
					$sistema = utf8_decode($row["sis_nombre"]);
					$tipo = utf8_decode($row["hal_descripcion"]);
				}
			}
			$actividades = $ClsAct->get_actividad_mejora("", $plan);
			if (is_array($actividades)) {
				foreach ($actividades as $rowActividad) {
					$actividad = trim($rowActividad["act_codigo"]);
					$descripcion = utf8_decode($rowActividad["act_descripcion"]);
					$descripcion = nl2br($descripcion);
					$result = $ClsAct->get_programacion_mejora("", "", $actividad, date("d/m/Y"), "1,2");
					if (is_array($result)) {
						foreach ($result as $row) {
							$salida .= '<tr>';
							// No. 
							$salida .= '<td class = "text-center">' . $i . '.</td>';
							// Proceso
							$salida .= '<td class = "text-left">' . $proceso . '</td>';
							// Sistema
							$salida .= '<td class = "text-left">' . $sistema . '</td>';
							// Riesgo Oportunidad
							$salida .= '<td class = "text-left">' . $tipo . '</td>';
							// Descripcion
							$salida .= '<td class = "text-left">' . $descripcion . '</td>';
							// Fechas
							$fini = cambia_fecha($row["pro_fecha_inicio"]);
							$ffin = cambia_fecha($row["pro_fecha_fin"]);
							$salida .= '<td class = "text-center">' . $fini . ' - ' . $ffin . '</td>';
							// Codigo
							$codigo = $row["pro_codigo"];
							$situacion = $row["pro_situacion"];
							$usuario = $_SESSION["codigo"];
							$hashkey = $ClsPla->encrypt($codigo, $usuario);
							//--
							$salida .= '<td class = "text-center" >';
							$salida .= '<div class="btn-group">';
							if ($situacion == 1) $salida .= '<a class="btn btn-info btn-xs" href = "FRMejecutar.php?hashkey=' . $hashkey . '" title = "Iniciar Ejecuci&oacute;n" ><i class="fas fa-play"></i> Iniciar</a> ';
							else $salida .= '<a class="btn btn-warning btn-xs" href = "FRMejecutar.php?hashkey=' . $hashkey . '" title = "Continuar (Ejecuci&oacute;n #' . Agrega_Ceros($codigo) . ')" > <i class="fas fa-redo"></i> Reanudar</a> ';
							$salida .= '</div>';
							$salida .= '</td>';
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

function tabla_evaluacion($usuario)
{
	// Sistemas asignados al usuario
	$ClSis = new ClsSistema();
	$ClsPla = new ClsPlan();
	$ClsAct = new ClsActividad();
	$ClsHal = new ClsHallazgo();
	$sistemas = $ClSis->get_sistema("", "", "", $usuario);
	$planes = $ClsPla->get_plan_mejora("", "", "", "", "",  3); // Planes aprobados
	if (is_array($sistemas) && is_array($planes)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%"> No. </th>';
		$salida .= '<th class = "text-left" width = "10%">Proceso</th>';
		$salida .= '<th class = "text-left" width = "10%">Sistema</th>';
		$salida .= '<th class = "text-center" width = "20%">Hallazgo</th>';
		$salida .= '<th class = "text-center" width = "20%">Actividad</th>';
		$salida .= '<th class = "text-center" width = "20%">Responsable</th>';
		$salida .= '<th class = "text-center" width = "20%">Fecha de Ejecuci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "5%"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($sistemas as $rowSistema) {
			$sistema_codigo = trim($rowSistema["sis_codigo"]);
			// Plan de Riesgo Aprobado
			foreach ($planes as $rowPlan) {
				$plan = trim($rowPlan["pla_codigo"]);
				$origen = trim($rowPlan["hal_origen"]);
				$hallazgo = trim($rowPlan["pla_hallazgo"]);
				switch ($origen) {
					case 1:
						$info = $ClsHal->get_hallazgo_auditoria_interna($hallazgo, "", "", $sistema_codigo);
						break;
					case 2:
						$info = $ClsHal->get_hallazgo_auditoria_externa($hallazgo, "", "", $sistema_codigo);
						break;
					case 3:
						$info = $ClsHal->get_hallazgo_queja($hallazgo, "", "", $sistema_codigo);
						break;
					case 4:
						$info = $ClsHal->get_hallazgo_indicador($hallazgo, "", "", $sistema_codigo);
						break;
					case 5:
						$info = $ClsHal->get_hallazgo_riesgo($hallazgo, "", "", $sistema_codigo);
						break;
					case 6:
						$info = $ClsHal->get_hallazgo_requisito($hallazgo, "", "", $sistema_codigo);
						//var_dump($info);
						break;
				}
				if (is_array($info)) {
					foreach ($info as $rowInfo) {
						$proceso = utf8_decode($rowInfo["fic_nombre"]);
						$sistema = utf8_decode($rowInfo["sis_nombre"]);
						$tipo = utf8_decode($rowInfo["hal_descripcion"]);
						$actividades = $ClsAct->get_actividad_mejora("", $plan);
						foreach ($actividades as $rowActividad) {
							$actividad = trim($rowActividad["act_codigo"]);
							$descripcion = trim($rowActividad["act_descripcion"]);
							$descripcion = nl2br($descripcion);
							$result = $ClsAct->get_programacion_mejora("", "", $actividad, "", "3,4");
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
									$salida .= '<td class = "text-left">' . $descripcion . '</td>';
									// Usuario
									$usuario = trim($row["usu_nombre"]);
									$salida .= '<td class = "text-center">' . $usuario . '</td>';
									// Fecha
									$fecha = cambia_fecha($row["pro_fecha"]);
									$salida .= '<td class = "text-center">' . $fecha . '</td>';
									// Codigo
									$codigo = $row["pro_codigo"];
									$situacion = $row["pro_situacion"];
									$usuario = $_SESSION["codigo"];
									$hashkey = $ClsHal->encrypt($codigo, $usuario);
									//--
									$salida .= '<td class = "text-center" >';
									$salida .= '<div class="btn-group">';
									if ($situacion == 3) $salida .= '<a class="btn btn-dark btn-xs" href = "FRMevaluar.php?hashkey=' . $hashkey . '" title = "Evaluar" ><i class="fas fa-clipboard-check"></i></a> ';
									else $salida .= '<a class="btn btn-warning btn-xs" href = "FRMevaluar.php?hashkey=' . $hashkey . '" title = "Continuar (Evaluacion #' . Agrega_Ceros($codigo) . ')" > <i class="fas fa-redo"></i> Reanudar</a> ';
									$salida .= '</div>';
									$salida .= '</td>';
									//--
									$salida .= '</tr>';
									$i++;
								}
							}
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

function tabla_eficacia($usuario)
{
	$ClsSis = new ClsSistema();
	$ClsHal = new ClsHallazgo();
	$ClsPla = new ClsPlan();
	$asignadas = $ClsSis->get_sistema("", "", "", $usuario);
	if (is_array($asignadas)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%"> No. </th>';
		$salida .= '<th class = "text-left" width = "20%">Proceso</th>';
		$salida .= '<th class = "text-left" width = "20%">Sistema</th>';
		$salida .= '<th class = "text-left" width = "20%">Usuario</th>';
		$salida .= '<th class = "text-left" width = "25%">Hallazgo</th>';
		$salida .= '<th class = "text-center" width = "10%px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		
		foreach ($asignadas as $rowSistema) {
			$sistema_codigo = trim($rowSistema["sis_codigo"]);
			for ($origenTipo = 1; $origenTipo <= 6; $origenTipo++) {
				$hallazgos = null;
				switch ($origenTipo) {
					case 1:
						$hallazgos = $ClsHal->get_hallazgo_auditoria_interna("", "", "", $sistema_codigo);
						break;
					case 2:
						$hallazgos = $ClsHal->get_hallazgo_auditoria_externa("", "", "", $sistema_codigo);
						break;
					case 3:
						$hallazgos = $ClsHal->get_hallazgo_queja("", "", "", $sistema_codigo);
						break;
					case 4:
						$hallazgos = $ClsHal->get_hallazgo_indicador("", "", "", $sistema_codigo);
						break;
					case 5:
						$hallazgos = $ClsHal->get_hallazgo_riesgo("", "", "", $sistema_codigo);
						break;
					case 6: 
						$hallazgos = $ClsHal->get_hallazgo_requisito("", "", "", $sistema_codigo);
						break;
				}
				if (is_array($hallazgos)) {
					foreach ($hallazgos as $rowHallazgo) {
						$hallazgo = trim($rowHallazgo["hal_codigo"]);
						$proceso = utf8_decode($rowHallazgo["fic_nombre"]);
						$sistema = utf8_decode($rowHallazgo["sis_nombre"]);
						$usu = utf8_decode($rowHallazgo["usu_nombre"]);
						$descripcion = utf8_decode($rowHallazgo["hal_descripcion"]);
						$planes = $ClsPla->get_plan_mejora("", $hallazgo, "", "", "", "3");
						if (is_array($planes)) {
							foreach ($planes as $row) {
								$salida .= '<tr>';
								// No. 
								$salida .= '<td class = "text-center">' . $i . '</td>';
								// Proceso
								$salida .= '<td class = "text-left">' . $proceso . '</td>';
								// Sistema
								$salida .= '<td class = "text-left">' . $sistema . '</td>';
								// Usuario
								$salida .= '<td class = "text-left">' . $usu . '</td>';
								// Descripcion
								$descripcion = nl2br($descripcion);
								$salida .= '<td class = "text-left">' . $descripcion . '</td>';
								//codigo
								$codigo = $row["pla_codigo"];
								$usuario = $_SESSION["codigo"];
								$hashkey = $ClsPla->encrypt($codigo, $usuario);
								//--
								$salida .= '<td class = "text-center" >';
								$salida .= '<div class="btn-group">';
								$origen = trim($row["hal_origen"]);
								$salida .= '<a class="btn btn-info btn-xs" href = "FRMverificar.php?hashkey=' . $hashkey . '&origen=' . $origen . '" title = "Verificar Eficacia" ><i class="fa fa-search"></i></a>';
								$salida .= '</div>';
								$salida .= '</td>';
								//--
								$salida .= '</tr>';
								$i++;
							}
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

function get_archivos($numero, $codigo)
{
	// Si no existe ningun archivo me devuelve false en la posicion 0
	$ClsAct = new ClsActividad();
	$evidencia = false;
	for ($i = 1; $i <= $numero; $i++) {
		$result = $ClsAct->get_archivo_mejora('', $codigo, $i);
		if (is_array($result)) {
			foreach ($result as $row) {
				$strArchivo = trim($row["arc_archivo"]);
				if (file_exists('../../CONFIG/Fotos/MEJORA/' . $strArchivo . '.jpg')) {
					$strArchivo = '<a href="../../CONFIG/Fotos/MEJORA/' . $strArchivo . '.jpg" target="_blank"><img class="img-upload" src="../../CONFIG/Fotos/MEJORA/' . $strArchivo . '.jpg" alt="..."></a>';
					$evidencia = true;
				} else if (file_exists('../../CONFIG/Archivos/MEJORA/' . $strArchivo . '.pdf')) {
					$strArchivo = '<a href="../../CONFIG/Archivos/MEJORA/' . $strArchivo . '.pdf" target="_blank"><img class="img-upload" src="../../CONFIG/img/document.png" alt="..."></a>';
					$evidencia = true;
				} else {
					$strArchivo = '<i class="fa fa-file-o fa-8x"></i>';
				}
			}
		} else {
			$strArchivo = '<i class="fa fa-file-o fa-8x"></i>';
		}
		$arrArchivos[$i] = $strArchivo;
	}
	$arrArchivos[0] = $evidencia;
	return $arrArchivos;
}

function get_tipo($tipo)
{
	switch ($tipo) {
		case 0:
			return "No Identificado";
		case 1:
			return "No conformidad";
		case 2:
			return "Observacion";
		case 3:
			return "Oportunidad de Mejora";
	}
}

function get_origen($tipo)
{
	switch ($tipo) {
		case 0:
			return "No Identificado";
		case 1:
			return "Auditor&iacute;a Interna";
		case 2:
			return "Auditor&iacute;a Externa";
		case 3:
			return "Salidas no Conformes";
		case 4:
			return "Incumplimiento de Indicadores";
		case 5:
			return "Riesgos Materializados";
		case 6:
			return "Incumplimiento Legal";
	}
}

function get_periodicidad($periodicidad)
{
	switch ($periodicidad) {
		case "U":
			return "&Uacute;nica";
		case "M":
			return "Mensual";
		case "W":
			return "Semanal";
	}
}
