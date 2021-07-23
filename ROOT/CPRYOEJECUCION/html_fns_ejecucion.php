<?php
include_once('../html_fns.php');

function tabla_ejecucion($usuario)
{
	$ClsPla = new ClsPlan();
	$ClsAct = new ClsActividad();
	$planes = $ClsPla->get_plan_ryo("", "", "", $usuario, "", "", 3); // Planes aprobados para el usuario
	if (is_array($planes)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%"> No. </th>';
		$salida .= '<th class = "text-left" width = "10%">Proceso</th>';
		$salida .= '<th class = "text-left" width = "10%">Sistema</th>';
		$salida .= '<th class = "text-center" width = "30%">Riesgo/Oportunidad</th>';
		$salida .= '<th class = "text-center" width = "30%">Actividad</th>';
		$salida .= '<th class = "text-center" width = "20%">Programaci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "10%"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($planes as $rowPlan) {
			$plan = trim($rowPlan["pla_codigo"]);
			// Enlazar con su riesgo o su oportunidad para la informacion
			$riesgo = trim($rowPlan["pla_riesgo"]);
			$oportunidad = trim($rowPlan["pla_oportunidad"]);
			$ClsRie = new ClsRiesgo();
			$ClsOpo = new ClsOportunidad();
			if ($riesgo != 0) $info = $ClsRie->get_riesgo($riesgo);
			else $info = $ClsOpo->get_oportunidad($oportunidad);
			if (is_array($info)) {
				foreach ($info as $row) {
					$proceso = utf8_decode($row["fic_nombre"]);
					$sistema = utf8_decode($row["sis_nombre"]);
					$tipo = utf8_decode($row["fod_descripcion"]);
				}
			}

			$actividades = $ClsAct->get_actividad("", $plan,1);
			if (is_array($actividades)) {
				foreach ($actividades as $rowActividad) {
					$actividad = trim($rowActividad["act_codigo"]);
					$descripcion = utf8_decode($rowActividad["act_descripcion"]);
					$descripcion = nl2br($descripcion);
					$result = $ClsAct->get_programacion("","", $actividad, date("d/m/Y"), "1,2");
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
	$sistemas = $ClSis->get_sistema("", "", "", $usuario);
	if (is_array($sistemas)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%"> No. </th>';
		$salida .= '<th class = "text-left" width = "10%">Proceso</th>';
		$salida .= '<th class = "text-left" width = "10%">Sistema</th>';
		$salida .= '<th class = "text-center" width = "20%">Riesgo/Oportunidad</th>';
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
			$ClsPla = new ClsPlan();
			$ClsAct = new ClsActividad();
			$planes = $ClsPla->get_plan_ryo("", "", "", "", "", "", 3); // Planes aprobados
			if (is_array($planes)) {
				foreach ($planes as $rowPlan) {
					$plan = trim($rowPlan["pla_codigo"]);
					// Enlazar con su riesgo o su oportunidad para la informacion
					$riesgo = trim($rowPlan["pla_riesgo"]);
					$oportunidad = trim($rowPlan["pla_oportunidad"]);
					$ClsRie = new ClsRiesgo();
					$ClsOpo = new ClsOportunidad();
					if ($riesgo != 0) $info = $ClsRie->get_riesgo($riesgo, "", "",$sistema_codigo);
					else $info = $ClsOpo->get_oportunidad($oportunidad, "","", $sistema_codigo);
					if (is_array($info)) {
						foreach ($info as $row) {
							$proceso = utf8_decode($row["fic_nombre"]);
							$sistema = utf8_decode($row["sis_nombre"]);
							$tipo = utf8_decode($row["fod_descripcion"]);
						}
						$actividades = $ClsAct->get_actividad("", $plan);
						foreach ($actividades as $rowActividad) {
							$actividad = trim($rowActividad["act_codigo"]);
							$descripcion = trim($rowActividad["act_descripcion"]);
							$descripcion = nl2br($descripcion);
							$result = $ClsAct->get_programacion("","", $actividad, "", "3,4");
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
									$hashkey = $ClsOpo->encrypt($codigo, $usuario);
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
///////////////// Extras /////////////////

function get_archivos($numero, $codigo)
{
	// Si no existe ningun archivo me devuelve false en la posicion 0
	$ClsAct = new ClsActividad();
	$evidencia = false;
	for ($i = 1; $i <= $numero; $i++) {
		$result = $ClsAct->get_archivo('', $codigo, $i);
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
