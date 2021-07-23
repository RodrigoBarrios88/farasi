<?php
include_once('../html_fns.php');

function tabla_foda_asignados($proceso, $sistema, $usuario, $tipo, $identificar = true)
{
	$ClsFic = new ClsFicha();
	$asignadas = $ClsFic->get_ficha_usuario("", $proceso, $usuario, 3);
	if (is_array($asignadas)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%"> No. </th>';
		$salida .= '<th class = "text-left" width = "10%">Proceso</th>';
		$salida .= '<th class = "text-left" width = "10%">Sistema</th>';
		$salida .= '<th class = "text-center" width = "55%">Descripcion</th>';
		$salida .= '<th class = "text-center" width = "10%">Fecha de Detecci&oacute;n</th>';
		if ($identificar) $salida .= '<th class = "text-center" width = "10%px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($asignadas as $rowFicha) {
			$ficha = trim($rowFicha["fic_codigo"]);
			$ficha_nombre = utf8_decode($rowFicha["fic_nombre"]);
			$internos = $ClsFic->get_foda("", $ficha, $tipo);
			if (is_array($internos)) {
				foreach ($internos as $row) {
					$salida .= '<tr>';
					// No. 
					$salida .= '<td class = "text-center">' . $i . '.</td>';
					// Proceso
					$salida .= '<td class = "text-left">' . $ficha_nombre . '</td>';
					// Sistema
					$sistema = utf8_decode($row["sis_nombre"]);
					$salida .= '<td class = "text-left">' . $sistema . '</td>';
					// Descripcion
					$descripcion = utf8_decode($row["fod_descripcion"]);
					$descripcion = nl2br($descripcion);
					$salida .= '<td class = "text-left">' . $descripcion . '</td>';
					// Fecha de detección
					$fecha = utf8_decode($row["fod_fecha_registro"]);
					$salida .= '<td class = "text-left">' . $fecha . '</td>';
					//codigo
					$codigo = $row["fod_codigo"];
					$proceso = $row["fod_proceso"];
					//--
					if ($identificar) {
						$salida .= '<td class = "text-center" >';
						$salida .= '<div class="btn-group">';
						$salida .= '<button type="button" class="btn btn-dark btn-xs" onclick = "identificar(' . $codigo . ',' . $proceso . ');" title = "Identificar Riesgo" ><span class="fa fa-pencil-square-o"></span></button>';
						$salida .= '</div>';
						$salida .= '</td>';
					}
					//--
					$salida .= '</tr>';
					$i++;
				}
			}
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}

function tabla_riesgos($proceso, $sistema, $tipo, $usuario)
{
	$ClsFic = new ClsFicha();
	$ClsRie = new ClsRiesgo();
	$asignadas = $ClsFic->get_ficha_usuario("", $proceso, $usuario, 3);
	if (is_array($asignadas)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%"> No. </th>';
		$salida .= '<th class = "text-center" width = "25%">Riesgo</th>';
		$salida .= '<th class = "text-center" width = "20%">Origen</th>';
		$salida .= '<th class = "text-center" width = "20%">Causas</th>';
		$salida .= '<th class = "text-center" width = "20%">Consecuencias</th>';
		$salida .= '<th class = "text-center" width = "10%px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($asignadas as $rowFicha) {
			$ficha = trim($rowFicha["fic_codigo"]);
			$internos = $ClsRie->get_riesgo("", "", $ficha, $sistema, $tipo);
			if (is_array($internos)) {
				foreach ($internos as $row) {
					$salida .= '<tr>';
					// No. 
					$salida .= '<td class = "text-center">' . $i . '.</td>';
					// Descripcion
					$descripcion = utf8_decode($row["fod_descripcion"]);
					$descripcion = nl2br($descripcion);
					$salida .= '<td class = "text-left">' . $descripcion . '</td>';
					// Origen
					$origen = utf8_decode($row["rie_origen"]);
					$salida .= '<td class = "text-left">' . $origen . '</td>';
					// Causa
					$causa = utf8_decode($row["rie_causa"]);
					$salida .= '<td class = "text-left">' . $causa . '</td>';
					// Consecuencias
					$consecuencia = utf8_decode($row["rie_consecuencia"]);
					$salida .= '<td class = "text-left">' . $consecuencia . '</td>';
					//codigo
					$codigo = $row["rie_codigo"];
					//--
					$salida .= '<td class = "text-center" >';
					$salida .= '<div class="btn-group">';
					$salida .= '<button type="button" class="btn btn-dark btn-xs" onclick = "analizar(' . $codigo . ');" title = "Iniciar Analisis" ><span class="fa fa-arrow-right"></span></button>';
					$salida .= '</div>';
					$salida .= '</td>';
					//--
					$salida .= '</tr>';
					$i++;
				}
			}
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}

function tabla_oportunidades($proceso, $sistema, $usuario)
{
	$ClsFic = new ClsFicha();
	$asignadas = $ClsFic->get_ficha_usuario($proceso, "", $usuario, 3);
	if (is_array($asignadas)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%"> No. </th>';
		$salida .= '<th class = "text-left" width = "20%">Proceso</th>';
		$salida .= '<th class = "text-left" width = "20%">Sistema</th>';
		$salida .= '<th class = "text-center" width = "25%">Oportunidad</th>';
		$salida .= '<th class = "text-center" width = "10%">Fecha de Detecci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "10%px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($asignadas as $rowFicha) {
			$ficha = trim($rowFicha["fic_codigo"]);
			$ficha_nombre = utf8_decode($rowFicha["fic_nombre"]);
			$internos = $ClsFic->get_foda("", $ficha, 2, $sistema);
			if (is_array($internos)) {
				foreach ($internos as $row) {
					$salida .= '<tr>';
					// No. 
					$salida .= '<td class = "text-center">' . $i . '.</td>';
					// Proceso
					$salida .= '<td class = "text-left">' . $ficha_nombre . '</td>';
					// Sistema
					$sistema = utf8_decode($row["sis_nombre"]);
					$salida .= '<td class = "text-left">' . $sistema . '</td>';
					// Descripcion
					$descripcion = utf8_decode($row["fod_descripcion"]);
					$descripcion = nl2br($descripcion);
					$salida .= '<td class = "text-left">' . $descripcion . '</td>';
					// Fecha de detección
					$fecha = utf8_decode($row["fod_fecha_registro"]);
					$salida .= '<td class = "text-left">' . $fecha . '</td>';
					//codigo
					$codigo = $row["fod_codigo"];
					$proceso = $row["fod_proceso"];
					//--
					$salida .= '<td class = "text-center" >';
					$salida .= '<div class="btn-group">';
					$salida .= '<button type="button" class="btn btn-dark btn-xs" onclick = "analizarOportunidad(' . $codigo . ',' . $proceso . ');" title = "Iniciar Analisis" ><span class="fa fa-arrow-right"></span></button>';
					$salida .= '</div>';
					$salida .= '</td>';
					//--
					$salida .= '</tr>';
					$i++;
				}
			}
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}

function tabla_valorizacion($proceso, $sistema, $tipo, $usuario)
{
	$ClsFic = new ClsFicha();
	$ClsRie = new ClsRiesgo();
	$asignadas = $ClsFic->get_ficha_usuario("", $proceso, $usuario, 3);
	if (is_array($asignadas)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%"> No. </th>';
		$salida .= '<th class = "text-left" width = "25%">Riesgo</th>';
		$salida .= '<th class = "text-left" width = "20%">Probabilidad</th>';
		$salida .= '<th class = "text-left" width = "20%">Impacto</th>';
		$salida .= '<th class = "text-left" width = "20%">Severidad</th>';
		$salida .= '<th class = "text-center" width = "20%">Condicion</th>';
		$salida .= '<th class = "text-center" width = "10%px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($asignadas as $rowFicha) {
			$ficha = trim($rowFicha["fic_codigo"]);
			$internos = $ClsRie->get_riesgo("", "", $ficha, $sistema, $tipo);
			if (is_array($internos)) {
				foreach ($internos as $row) {
					$probabilidad = trim($row["rie_probabilidad"]);
					$impacto = trim($row["rie_impacto"]);
					if ($probabilidad != 0 && $impacto != 0) {
						$salida .= '<tr>';
						// No. 
						$salida .= '<td class = "text-center">' . $i . '.</td>';
						// Descripcion
						$descripcion = utf8_decode($row["fod_descripcion"]);
						$descripcion = nl2br($descripcion);
						$salida .= '<td class = "text-left">' . $descripcion . '</td>';
						// Probabilidad
						$salida .= '<td class = "text-left">' . get_probabilidad($probabilidad) . '</td>';
						// Impacto
						$salida .= '<td class = "text-left">' . get_impacto($impacto) . '</td>';
						// Severidad
						$severidad = intval($probabilidad) * intval($impacto);
						$salida .= '<td class = "text-left">' . $severidad . '</td>';
						// Condicion
						$salida .= '<td class = "text-left">' . get_condicion($severidad) . '</td>';
						//codigo
						$codigo = $row["rie_codigo"];
						//--
						$salida .= '<td class = "text-center" >';
						$salida .= '<div class="btn-group">';
						$salida .= '<button type="button" class="btn btn-dark btn-xs" onclick = "valorizar(' . $codigo . ');" title = "Iniciar Valorizaci&oacute;n" ><span class="fa fa-arrow-right"></span></button>';
						$salida .= '</div>';
						$salida .= '</td>';
						//--
						$salida .= '</tr>';
						$i++;
					}
				}
			}
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}

	return $salida;
}
function tabla_valorizacion_oportunidades($proceso, $sistema, $usuario)
{
	$ClsFic = new ClsFicha();
	$ClsOpo = new ClsOportunidad();
	$asignadas = $ClsFic->get_ficha_usuario("", $proceso, $usuario, 3);
	if (is_array($asignadas)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%"> No. </th>';
		$salida .= '<th class = "text-left" width = "25%">Oportunidad</th>';
		$salida .= '<th class = "text-left" width = "20%">Viabilidad</th>';
		$salida .= '<th class = "text-left" width = "20%">Rentabilidad</th>';
		$salida .= '<th class = "text-left" width = "20%">Prioridad</th>';
		$salida .= '<th class = "text-center" width = "20%">Condicion</th>';
		$salida .= '<th class = "text-center" width = "10%px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($asignadas as $rowFicha) {
			$ficha = trim($rowFicha["fic_codigo"]);
			$result = $ClsOpo->get_oportunidad("", "", $ficha, $sistema);
			if (is_array($result)) {
				foreach ($result as $row) {
					$viabilidad = trim($row["opo_viabilidad"]);
					$rentabilidad = trim($row["opo_rentabilidad"]);
					if ($viabilidad != 0 && $rentabilidad != 0) {
						$salida .= '<tr>';
						// No. 
						$salida .= '<td class = "text-center">' . $i . '.</td>';
						// Descripcion
						$descripcion = utf8_decode($row["fod_descripcion"]);
						$descripcion = nl2br($descripcion);
						$salida .= '<td class = "text-left">' . $descripcion . '</td>';
						// viabilidad
						$salida .= '<td class = "text-left">' . get_prioridad($viabilidad) . '</td>';
						// Rentabilidad
						$salida .= '<td class = "text-left">' . get_prioridad($rentabilidad) . '</td>';
						// Prioridad
						$prioridad = intval($viabilidad) * intval($rentabilidad);
						$salida .= '<td class = "text-left">' . $prioridad . '</td>';
						// Condicion
						$salida .= '<td class = "text-left">' . get_condicion_oportunidad($prioridad) . '</td>';
						//codigo
						$codigo = $row["opo_codigo"];
						$salida .= '<td class = "text-center" >';
						$salida .= '<div class="btn-group">';
						$salida .= '<button type="button" class="btn btn-dark btn-xs" onclick = "valorizarOportunidad(' . $codigo . ');" title = "Iniciar Valorizaci&oacute;n" ><span class="fa fa-arrow-right"></span></button>';
						$salida .= '</div>';
						$salida .= '</td>';
						//--
						$salida .= '</tr>';
						$i++;
					}
				}
			}
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}

function tabla_mis_riesgos($proceso, $sistema, $tipo, $usuario)
{
	$ClsSis = new ClsSistema();
	$ClsOpo = new ClsOportunidad();
	$ClsRie = new ClsRiesgo();
	$ClsPla = new ClsPlan();

	$salida = '<table class="table table-striped dataTables-example" width="100%" >';
	$salida .= '<thead>';
	$salida .= '<tr>';
	$salida .= '<th class = "text-center" width = "5%"> No. </th>';
	$salida .= '<th class = "text-left" width = "20%">Proceso</th>';
	$salida .= '<th class = "text-left" width = "20%">Sistema</th>';
	$salida .= '<th class = "text-left" width = "20%">Usuario</th>';
	$salida .= '<th class = "text-left" width = "25%">Riesgo/Oportunidad</th>';
	$salida .= '<th class = "text-center" width = "10%px"><i class="fa fa-cogs"></i></th>';
	$salida .= '</tr>';
	$salida .= '</thead>';
	$salida .= '<tbody>';
	$i = 1;
	$clase = 1;
	while ($clase <= 2) {
		$aprobados = ($clase == 1) ? $ClsRie->get_riesgo("", "", "", "", "", "", "") :  
		$ClsOpo->get_oportunidad("", "", "", "", "", "", "");
		if (is_array($aprobados)) {
			foreach ($aprobados as $rowAprobado) {
				if ($clase == 1) {
					$riesgo = trim($rowAprobado["rie_codigo"]);
					$planes = $ClsPla->get_plan_ryo("", $riesgo, "", $usuario, "", "", "3");
				} else {
					$oportunidad = trim($rowAprobado["opo_codigo"]);
					$planes = $ClsPla->get_plan_ryo("", "", $oportunidad, $usuario,  "", "", "3");
				}
				$proceso = utf8_decode($rowAprobado["fic_nombre"]);
				$sistema = utf8_decode($rowAprobado["sis_nombre"]);
				$descripcion = utf8_decode($rowAprobado["fod_descripcion"]);
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
						$usu = utf8_decode($row["usu_nombre"]);
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
						$situacion = trim($row["pla_situacion"]);
						if ($situacion  == 3) {
							if ($clase == 1) $salida .= '<a class="btn btn-info btn-xs" href = "CPREPORTES/REPactividadesRiesgos.php?hashkey=' . $hashkey . '" title = "Reporte de planes Aprobados" ><i class="fa fa-print"></i></a>';
							else $salida .= '<a class="btn btn-info btn-xs" href = "CPREPORTES/REPactividadesRiesgos.php?hashkey=' . $hashkey . '" title = "Reporte de planes Aprobados" ><i class="fa fa-print"></i></a>';
						}
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
	$salida .= '</tbody>';
	$salida .= '</table>';
	return $salida;
}

function tabla_tratamiento($proceso, $sistema, $tipo, $usuario)
{
	$ClsFic = new ClsFicha();
	$ClsRie = new ClsRiesgo();
	$ClsPlan = new ClsPlan();
	$asignadas = $ClsFic->get_ficha_usuario("", $proceso, $usuario, 3);
	//var_dump($asignadas);
	if (is_array($asignadas)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%"> No. </th>';
		$salida .= '<th class = "text-left" width = "45%">Riesgo</th>';
		$salida .= '<th class = "text-center" width = "20%">Condicion</th>';
		$salida .= '<th class = "text-center" width = "20%">Accion</th>';
		$salida .= '<th class = "text-center" width = "10%px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($asignadas as $rowFicha) {
			$ficha = trim($rowFicha["fic_codigo"]);
			$riesgos = $ClsRie->get_riesgo("", "", $ficha, $sistema, $tipo);
			if (is_array($riesgos)) {
				foreach ($riesgos as $row) {
					$riesgo = trim($row["rie_codigo"]);
					// Plan en edicion
					$edicion = true;
					$plan = $ClsPlan->get_plan_ryo("", $riesgo, "", $usuario);
					if (is_array($plan)) {
						foreach ($plan as $rowPlan) {
							if ($rowPlan["pla_situacion"] != 1) $edicion = false;
						}
					}
					//--
					$accion = trim($row["rie_accion"]);
					$probabilidad = trim($row["rie_probabilidad"]);
					$impacto = trim($row["rie_impacto"]);
					if ($edicion && $accion != 0 && $probabilidad != 0 && $impacto != 0) {
						$salida .= '<tr>';
						// No. 
						$salida .= '<td class = "text-center">' . $i . '.</td>';
						// Descripcion
						$descripcion = utf8_decode($row["fod_descripcion"]);
						$descripcion = nl2br($descripcion);
						$salida .= '<td class = "text-left">' . $descripcion . '</td>';
						// Condicion
						$severidad = intval($probabilidad) * intval($impacto);
						$salida .= '<td class = "text-left">' . get_condicion($severidad) . '</td>';
						// Accion
						$salida .= '<td class = "text-left">' . get_accion_riesgo($accion) . '</td>';
						//codigo
						$codigo = $row["rie_codigo"];
						$usu = $_SESSION["codigo"];
						$hashkey = $ClsRie->encrypt($codigo, $usu);
						//--
						$salida .= '<td class = "text-center" >';
						$salida .= '<div class="btn-group">';
						$salida .= '<a class="btn btn-dark btn-xs" href = "FRMplan.php?hashkey=' . $hashkey . '" title = "Ver Plan" ><i class="fas fa-clipboard"></i></a> ';
						$salida .= '</div>';
						$salida .= '</td>';
						//--
						$salida .= '</tr>';
						$i++;
					}
				}
			}
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}
function tabla_tratamiento_oportunidades($proceso, $sistema, $usuario)
{
	$ClsFic = new ClsFicha();
	$ClsOpo = new ClsOportunidad();
	$ClsPla = new ClsPlan();
	$asignadas = $ClsFic->get_ficha_usuario("", $proceso, $usuario, 3);
	if (is_array($asignadas)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%"> No. </th>';
		$salida .= '<th class = "text-left" width = "25%">Oportunidad</th>';
		$salida .= '<th class = "text-center" width = "20%">Condicion</th>';
		$salida .= '<th class = "text-center" width = "20%">Accion</th>';
		$salida .= '<th class = "text-center" width = "10%px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($asignadas as $rowFicha) {
			$ficha = trim($rowFicha["fic_codigo"]);
			$internos = $ClsOpo->get_oportunidad("", "", $ficha, $sistema);
			if (is_array($internos)) {
				foreach ($internos as $row) {
					$oportunidad = trim($row["opo_codigo"]);
					// Plan en edicion
					$edicion = true;
					$plan = $ClsPla->get_plan_ryo("", "", $oportunidad, $usuario);
					if (is_array($plan)) {
						foreach ($plan as $rowPlan) {
							if ($rowPlan["pla_situacion"] != 1) $edicion = false;
						}
					}
					$accion = trim($row["opo_accion"]);
					$viabilidad = trim($row["opo_viabilidad"]);
					$rentabilidad = trim($row["opo_rentabilidad"]);
					if ($edicion && $accion != 0 && $viabilidad != 0 && $rentabilidad != 0) {
						$salida .= '<tr>';
						// No. 
						$salida .= '<td class = "text-center">' . $i . '.</td>';
						// Descripcion
						$descripcion = utf8_decode($row["fod_descripcion"]);
						$descripcion = nl2br($descripcion);
						$salida .= '<td class = "text-left">' . $descripcion . '</td>';
						// Condicion
						$severidad = intval($viabilidad) * intval($rentabilidad);
						$salida .= '<td class = "text-left">' . get_condicion_oportunidad($severidad) . '</td>';
						// Accion
						$salida .= '<td class = "text-left">' . get_accion_oportunidad($accion) . '</td>';
						//codigo
						$codigo = $row["opo_codigo"];
						$usuario = $_SESSION["codigo"];
						$hashkey = $ClsOpo->encrypt($codigo, $usuario);
						//--
						$salida .= '<td class = "text-center" >';
						$salida .= '<div class="btn-group">';
						$salida .= '<a class="btn btn-dark btn-xs" href = "FRMplan_oportunidad.php?hashkey=' . $hashkey . '" title = "Ver Plan" ><i class="fas fa-clipboard"></i></a> ';
						$salida .= '</div>';
						$salida .= '</td>';
						//--
						$salida .= '</tr>';
						$i++;
					}
				}
			}
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}

function tabla_actividades($codigo, $plan = "", $tipo = "", $responsable = "")
{
	$ClsAct = new ClsActividad();
	$result = $ClsAct->get_actividad($codigo, $plan, $tipo, $responsable);
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" id="tabla" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%"><i class="fa fa-cogs"></th>';
		$salida .= '<th class = "text-center" width = "5%"> No. </th>';
		$salida .= '<th class = "text-left" width = "40%">Actividad</th>';
		if ($tipo == 1) {
			$salida .= '<th class = "text-center" width = "10%">Fecha de Inicio</th>';
			$salida .= '<th class = "text-center" width = "10%">Fecha Final</th>';
			$salida .= '<th class = "text-center" width = "10%">Programaci&oacute;n</th>';
		}
		$salida .= '<th class = "text-center" width = "30%">Comentario de Gerencia</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = $row["act_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "Seleccionar(' . $codigo . ');" title = "Editar Sistema" ><i class="fa fa-pencil"></i></button>';
			$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "Deshabilitar(' . $codigo . ');" title = "Eliminar Sistema" ><i class="fa fa-trash"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			// No. 
			$salida .= '<td class = "text-center">' . $i . '</td>';
			// Descripcion
			$descripcion = trim($row["act_descripcion"]);
			$descripcion = nl2br($descripcion);
			$salida .= '<td class = "text-left">' . $descripcion . '</td>';
			if ($tipo == 1) {
				// Fecha Inicio
				$fini = cambia_fecha($row["act_fecha_inicio"]);
				$salida .= '<td class = "text-center">' . $fini . '</td>';
				// Fecha Final
				$ffin = cambia_fecha($row["act_fecha_fin"]);
				$salida .= '<td class = "text-center">' . $ffin . '</td>';
				// Programacion
				$codigo = $row["act_codigo"];
				$salida .= '<td class = "text-center" >';
				$salida .= '<div class="btn-group">';
				$salida .= '<button type="button" class="btn btn-info btn-xs" onclick = "verProgramacion(' . $codigo . ');" title = "Programaci&oacute;n de Actividad" ><i class="fa fa-calendar"></i></button>';
				$salida .= '</div>';
				$salida .= '</td>';
			}
			// comentario
			$comentario = trim($row["act_comentario"]);
			$comentario = nl2br($comentario);
			$salida .= '<td class = "text-left">' . $comentario . '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}

function tabla_aprobacion($proceso, $sistema, $usuario)
{
	$ClsSis = new ClsSistema();
	$ClsOpo = new ClsOportunidad();
	$ClsRie = new ClsRiesgo();
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
		$salida .= '<th class = "text-left" width = "25%">Riesgo/Oportunidad</th>';
		$salida .= '<th class = "text-center" width = "10%px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($asignadas as $rowSistema) {
			$sistema_codigo = trim($rowSistema["sis_codigo"]);
			$clase = 1;
			/////////////////////////////////////////////
			while ($clase <= 2) {
				$aprobados = ($clase == 1) ? $ClsRie->get_riesgo("", "", "", $sistema_codigo) :  $ClsOpo->get_oportunidad("", "", "", $sistema_codigo);
				if (is_array($aprobados)) {
					foreach ($aprobados as $rowAprobado) {
						if ($clase == 1) {
							$riesgo = trim($rowAprobado["rie_codigo"]);
							$planes = $ClsPla->get_plan_ryo("", $riesgo, "", "", "", "", "2,3");
						} else {
							$oportunidad = trim($rowAprobado["opo_codigo"]);
							$planes = $ClsPla->get_plan_ryo("", "", $oportunidad, "",  "", "", "2,3");
						}
						$proceso = utf8_decode($rowAprobado["fic_nombre"]);
						$sistema = utf8_decode($rowAprobado["sis_nombre"]);
						$descripcion = utf8_decode($rowAprobado["fod_descripcion"]);
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
								$usu = utf8_decode($row["usu_nombre"]);
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
								$situacion = trim($row["pla_situacion"]);
								if ($situacion  == 2) {
									if ($clase == 1) $salida .= '<a class="btn btn-info btn-xs" href = "FRMplanes.php?hashkey=' . $hashkey . '" title = "Ver Planes" ><i class="fa fa-search"></i></a>';
									else $salida .= '<a class="btn btn-info btn-xs" href = "FRMplanes_oportunidad.php?hashkey=' . $hashkey . '" title = "Ver Planes" ><i class="fa fa-search"></i></a>';
								} else if ($situacion  == 3) {
									$salida .= '<button type="button" class="btn btn-white text-success btn-outline" onclick="solicitarCorreccion(' . $codigo . ');" title = "Actualizar Plan"><i class="fas fa-check-double"></i>Aprobada (Solicitar Actualizaci&oacute;n)</a>';
								}
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
			//////////////////////////////
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}

function tabla_materializacion($proceso, $sistema, $usuario, $tipo)
{
	$ClsSis = new ClsSistema();
	$ClsRie = new ClsRiesgo();
	$ClsPla = new ClsPlan();
	$asignadas = $ClsSis->get_sistema("", "", "", $usuario);
	if (is_array($asignadas)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%"> No. </th>';
		$salida .= '<th class = "text-left" width = "20%">Proceso</th>';
		$salida .= '<th class = "text-left" width = "20%">Sistema</th>';
		$salida .= '<th class = "text-left" width = "25%">Riesgo</th>';
		$salida .= '<th class = "text-left" width = "15%">Fecha de Registro</th>';
		$salida .= '<th class = "text-left" width = "15%">Condici&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "10%px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($asignadas as $rowSistema) {
			$sistema_codigo = trim($rowSistema["sis_codigo"]);
			$aprobados = $ClsRie->get_riesgo("", "", "", $sistema_codigo,"",$tipo,"");
			//var_dump($aprobados);
			if (is_array($aprobados)) {
				foreach ($aprobados as $row) {
					$salida .= '<tr>';
					// No. 
					$salida .= '<td class = "text-center">' . $i . '</td>';
					// Proceso
					$proceso = utf8_decode($row["fic_nombre"]);
					$salida .= '<td class = "text-left">' . $proceso . '</td>';
					// Sistema
					$sistema = utf8_decode($row["sis_nombre"]);
					$salida .= '<td class = "text-left">' . $sistema . '</td>';
					// Descripcion
					$descripcion = utf8_decode($row["fod_descripcion"]);
					$descripcion = nl2br($descripcion);
					$salida .= '<td class = "text-left">' . $descripcion . '</td>';
					// Fecha
					$fecha = cambia_fecha($row["rie_fecha_registro"]);
					$salida .= '<td class = "text-left">' . $fecha . '</td>';
					// Condicion
					$probabilidad = trim($row["rie_probabilidad"]);
					$impacto = trim($row["rie_impacto"]);
					$severidad = intval($probabilidad) * intval($impacto);
					$salida .= '<td class = "text-left">' . get_condicion($severidad) . '</td>';
					//codigo
					$codigo = trim($row["rie_codigo"]);
					$usuario = $_SESSION["codigo"];
					$hashkey = $ClsPla->encrypt($codigo, $usuario);
					//--
					$salida .= '<td class = "text-center" >';
					$salida .= '<div class="btn-group">';
					if($tipo == 1){
						$salida .= '<a class="btn btn-dark btn-xs" href = "FRMmaterializar.php?hashkey=' . $hashkey . '&materializado=no" title = "Materializar Riesgo" ><i class="fa fa-exclamation-triangle"></i></a> ';
					}elseif($tipo == 2){
						$salida .= '<a class="btn btn-dark btn-xs" href = "FRMmaterializar.php?hashkey=' . $hashkey . '&materializado=yes" title = "Materializar Riesgo" ><i class="fa fa-exclamation-triangle"></i></a> ';
					}
					$salida .= '</div>';
					$salida .= '</td>';
					//--
					$salida .= '</tr>';
					$i++;
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
	$ClsRie = new ClsRiesgo();
	$evidencia = false;
	for ($i = 1; $i <= $numero; $i++) {
		$result = $ClsRie->get_archivo('', $codigo, $i);
		if (is_array($result)) {
			foreach ($result as $row) {
				$strArchivo = trim($row["arc_archivo"]);
				if (file_exists('../../CONFIG/Fotos/RYO/' . $strArchivo . '.jpg')) {
					$strArchivo = '<a href="../../CONFIG/Fotos/RYO/' . $strArchivo . '.jpg" target="_blank"><img class="img-upload" src="../../CONFIG/Fotos/RYO/' . $strArchivo . '.jpg" alt="..."></a>';
					$evidencia = true;
				} else if (file_exists('../../CONFIG/Archivos/RYO/' . $strArchivo . '.pdf')) {
					$strArchivo = '<a href="../../CONFIG/Archivos/RYO/' . $strArchivo . '.pdf" target="_blank"><img class="img-upload" src="../../CONFIG/img/document.png" alt="..."></a>';
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


function get_impacto($impacto)
{
	switch ($impacto) {
		case 0:
			return "Sin Evaluar";
		case 1:
			return "Peque&ntilde;o";
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
			return "Sin Acci&oacute;n";
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
