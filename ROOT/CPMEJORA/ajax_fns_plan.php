<?php
ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cache");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);

require_once("../recursos/mandrill/src/Mandrill.php"); //--correos
require_once("../../CONFIG/constructor.php"); //--correos
include_once('html_fns_mejora.php');

$request = $_REQUEST["request"];
switch ($request) {
		////////////////////// Actividades ////////////////
	case "tabla":
		$plan = $_REQUEST["plan"];
		tabla($plan);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get($codigo);
		break;
	case "grabar":
		$plan = $_REQUEST["plan"];
		$tipo = $_REQUEST["tipo"];
		$responsable = $_REQUEST["responsable"];
		$fini = $_REQUEST["fini"];
		$ffin = $_REQUEST["ffin"];
		$periodicidad = $_REQUEST["periodicidad"];
		$dini = $_REQUEST["dini"];
		$dfin = $_REQUEST["dfin"];
		$descripcion = $_REQUEST["descripcion"];
		grabar($plan, $periodicidad, $dini, $dfin, $fini, $ffin, $descripcion);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$plan = $_REQUEST["plan"];
		$tipo = $_REQUEST["tipo"];
		$fini = $_REQUEST["fini"];
		$ffin = $_REQUEST["ffin"];
		$periodicidad = $_REQUEST["periodicidad"];
		$dini = $_REQUEST["dini"];
		$dfin = $_REQUEST["dfin"];
		$descripcion = $_REQUEST["descripcion"];
		modificar($codigo, $plan, $periodicidad, $dini, $dfin, $fini, $ffin, $descripcion);
		break;
	case "update":
		$codigo = $_REQUEST["codigo"];
		$campo = $_REQUEST["campo"];
		$valor = $_REQUEST["valor"];
		update($codigo, $campo, $valor);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion($codigo, $situacion);
		break;
		////////////////////// Plan ////////////////
	case "situacion_plan":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_plan($codigo, $situacion);
		break;
	case "corregir_plan":
		$codigo = $_REQUEST["codigo"];
		$justificacion = $_REQUEST["justificacion"];
		corregir_plan($codigo, $justificacion);
		break;
	default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

////////////////// VERSIONES /////////////////////////
function get($codigo)
{
	$ClsAct = new ClsActividad();
	$result = $ClsAct->get_actividad_mejora($codigo);
	$arr_data = array();
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data["codigo"] = trim($row["act_codigo"]);
			$arr_data["tipo"] = trim($row["act_tipo"]);
			$arr_data["periodicidad"] = trim($row["act_periodicidad"]);
			$arr_data["desde"] = cambia_fecha($row["act_fecha_inicio"]);
			$arr_data["hasta"] = cambia_fecha($row["act_fecha_fin"]);
			$arr_data["descripcion"] = trim($row["act_descripcion"]);
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_actividades($codigo),
			"message" => ""
		);
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Aún no hay datos registrados..."
		);
	}
	echo json_encode($arr_respuesta);
}
function grabar($plan, $periodicidad, $dini, $dfin, $fini, $ffin, $descripcion)
{
	$ClsAct = new ClsActividad();
	if ($descripcion != "") {
		$codigo = $ClsAct->max_actividad_mejora();
		$codigo++;
		$sql = $ClsAct->insert_actividad_mejora($codigo, $plan, $periodicidad, $fini, $ffin, $descripcion); /// Inserta Version
		// Validaciones de Fecha
		$hoy = strtotime(date("Y-m-d"));
		$fini = strtotime(regresa_fecha($fini));
		$ffin = strtotime(regresa_fecha($ffin));
		$insertaProgra = false;
		if ($fini >= $hoy && $fini < $ffin && $dfin >= $dini && $periodicidad != "") {
			switch ($periodicidad) {
				case "W":
					// Fecha de Inicio
					$inicio = new DateTime(date("Y-m-d", $fini));
					for ($i = 1; $i <= 7; $i++) {
						if ($inicio->format("w") == $dini) break;
						$inicio->modify('+1 days');
					}
					// Fecha Fin
					$fin = new DateTime(date("Y-m-d", $ffin));
					for ($i = 1; $i <= 7; $i++) {
						if ($fin->format("w") == $dini) break;
						$fin->modify('+1 days');
					}
					while ($inicio->getTimestamp() <= $fin->getTimestamp()) {
						// Dia de Inicio
						$inicioTemporal = new DateTime($inicio->format("Y-m-d"));
						for ($i = 1; $i <= 7; $i++) {
							if ($inicioTemporal->format("w") == $dini) break;
							$inicioTemporal->modify('+1 days');
						}
						// Dia Fin
						$finTemporal = new DateTime($inicio->format("Y-m-d"));
						for ($i = 1; $i <= 7; $i++) {
							if ($finTemporal->format("w") == $dfin) break;
							$finTemporal->modify('+1 days');
						}
						// Programacion
						if ($inicioTemporal->getTimestamp() >= $fini && $finTemporal->getTimestamp() <= $ffin) {
							$insertaProgra = true;
							$sql .= $ClsAct->insert_programacion_mejora($codigo, $inicioTemporal->format("d/m/Y"), $finTemporal->format("d/m/Y")); /// Inserta Version
						}
						$inicio->modify('+1 week');
					}
					break;
				case "M":
					// Fecha de Inicio
					$m = date("m", $fini);
					$Y = date("Y", $fini);
					$dateInicio = strtotime(date("$Y-$m-$dini"));
					// Fecha Fin
					$m2 = date("m", $ffin);
					$Y2 = date("Y", $ffin);
					$dateFin = strtotime(date("$Y2-$m2-$dini"));
					while ($dateInicio <= $dateFin) {
						// Dia Fin
						$m = date("m", $dateInicio);
						$Y = date("y", $dateInicio);
						$finTemporal = date("$dfin/$m/$Y");
						// Programacion
						if ($dateInicio >= $fini && strtotime(regresa_fecha($finTemporal)) <= $ffin) {
							$insertaProgra = true;
							$sql .= $ClsAct->insert_programacion_mejora($codigo, date("d/m/Y", $dateInicio), $finTemporal); /// Inserta Version
						}
						$dateInicio = strtotime("+1 month", $dateInicio);
					}
					break;
				case "U":
					$insertaProgra = true;
					$sql .= $ClsAct->insert_programacion_mejora($codigo, date("d/m/Y", $fini),  date("d/m/Y", $ffin)); /// Inserta Version
					break;
			}
		} else {
			//devuelve un mensaje de manejo de errores
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Verifique las fechas de su planificacion"
			);
			echo json_encode($arr_respuesta);
			return;
		}
		if ($insertaProgra) {
			$rs = $ClsAct->exec_sql($sql);
			if ($rs == 1) {
				$arr_respuesta = array(
					"status" => true,
					"data" => tabla_actividades('', $plan),
					"sql" => $sql,
					"message" => "Registro guardado satisfactoriamente...!"
				);
				echo json_encode($arr_respuesta);
			} else {
				$arr_respuesta = array(
					"status" => false,
					"sql" => $sql,
					"data" => [],
					"message" => "Error en la transacción..."
				);
				echo json_encode($arr_respuesta);
			}
		} else {
			//devuelve un mensaje de manejo de errores
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Verifique el dia inicial de su programación"
			);
			echo json_encode($arr_respuesta);
			return;
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"message" => "Debe llenar los campos obligatorios..."
		);

		echo json_encode($arr_respuesta);
	}
}
function modificar($codigo, $plan, $periodicidad, $dini, $dfin, $fini, $ffin, $descripcion)
{
	$ClsAct = new ClsActividad();
	if ($codigo != "" && $descripcion != "") {
		$sql = $ClsAct->modifica_actividad_mejora($codigo, $plan, $periodicidad, $fini, $ffin, $descripcion); /// Inserta Version
		$sql .= $ClsAct->delete_programacion_mejora("", $codigo);
		// Validaciones de Fecha
		$fini = strtotime(regresa_fecha($fini));
		$ffin = strtotime(regresa_fecha($ffin));
		$insertaProgra = false;
		if ($fini < $ffin && $dfin >= $dini && $periodicidad != "") {
			switch ($periodicidad) {
				case "W":
					// Fecha de Inicio
					$inicio = new DateTime(date("Y-m-d", $fini));
					for ($i = 1; $i <= 7; $i++) {
						if ($inicio->format("w") == $dini) break;
						$inicio->modify('+1 days');
					}
					// Fecha Fin
					$fin = new DateTime(date("Y-m-d", $ffin));
					for ($i = 1; $i <= 7; $i++) {
						if ($fin->format("w") == $dini) break;
						$fin->modify('+1 days');
					}
					while ($inicio->getTimestamp() <= $fin->getTimestamp()) {
						// Dia de Inicio
						$inicioTemporal = new DateTime($inicio->format("Y-m-d"));
						for ($i = 1; $i <= 7; $i++) {
							if ($inicioTemporal->format("w") == $dini) break;
							$inicioTemporal->modify('+1 days');
						}
						// Dia Fin
						$finTemporal = new DateTime($inicio->format("Y-m-d"));
						for ($i = 1; $i <= 7; $i++) {
							if ($finTemporal->format("w") == $dfin) break;
							$finTemporal->modify('+1 days');
						}
						// Programacion
						if ($inicioTemporal->getTimestamp() >= $fini && $finTemporal->getTimestamp() <= $ffin) {
							$insertaProgra = true;
							$sql .= $ClsAct->insert_programacion_mejora($codigo, $inicioTemporal->format("d/m/Y"), $finTemporal->format("d/m/Y")); /// Inserta Version
						}
						$inicio->modify('+1 week');
					}
					break;
				case "M":
					// Fecha de Inicio
					$m = date("m", $fini);
					$Y = date("Y", $fini);
					$dateInicio = strtotime(date("$Y-$m-$dini"));
					// Fecha Fin
					$m2 = date("m", $ffin);
					$Y2 = date("Y", $ffin);
					$dateFin = strtotime(date("$Y2-$m2-$dini"));
					while ($dateInicio <= $dateFin) {
						// Dia Fin
						$m = date("m", $dateInicio);
						$Y = date("y", $dateInicio);
						$finTemporal = date("$dfin/$m/$Y");
						// Programacion
						if ($dateInicio >= $fini && strtotime(regresa_fecha($finTemporal)) <= $ffin) {
							$insertaProgra = true;
							$sql .= $ClsAct->insert_programacion_mejora($codigo, date("d/m/Y", $dateInicio), $finTemporal); /// Inserta Version
						}
						$dateInicio = strtotime("+1 month", $dateInicio);
					}
					break;
				case "U":
					$insertaProgra = true;
					$sql .= $ClsAct->insert_programacion_mejora($codigo, date("d/m/Y", $fini),  date("d/m/Y", $ffin)); /// Inserta Version
					break;
			}
		} else {
			//devuelve un mensaje de manejo de errores
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Verifique las fechas de su planificacion"
			);
			echo json_encode($arr_respuesta);
			return;
		}
		if ($insertaProgra) {
			$rs = $ClsAct->exec_sql($sql);
			if ($rs == 1) {
				$arr_respuesta = array(
					"status" => true,
					"data" => tabla_actividades('', $plan),
					"sql" => $sql,
					"message" => "Registro guardado satisfactoriamente...!"
				);
				echo json_encode($arr_respuesta);
			} else {
				$arr_respuesta = array(
					"status" => false,
					"sql" => $sql,
					"data" => [],
					"message" => "Error en la transacción..."
				);
				echo json_encode($arr_respuesta);
			}
		} else {
			//devuelve un mensaje de manejo de errores
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Verifique el dia inicial de su programación"
			);
			echo json_encode($arr_respuesta);
			return;
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"message" => "Debe llenar los campos obligatorios..."
		);

		echo json_encode($arr_respuesta);
	}
}
function tabla($plan)
{
	$ClsAct = new ClsActividad();
	$result = $ClsAct->get_actividad_mejora("", $plan);
	if (is_array($result)) {
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_actividades("", $plan),
			"message" => ""
		);
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Aún no hay datos registrados..."
		);
	}
	echo json_encode($arr_respuesta);
}
function situacion($codigo, $situacion)
{
	$ClsAct = new ClsActividad();
	if ($codigo != "" && $situacion != "") {
		$act = $ClsAct->get_actividad_mejora($codigo);
		if (is_array($act)) {
			foreach ($act as $row) {
				$tipo = trim($row["act_tipo"]);
				$plan = trim($row["act_plan"]);
				$sql = $ClsAct->cambia_situacion_actividad_mejora($codigo, $situacion);
				$rs = $ClsAct->exec_sql($sql);
				if ($rs == 1) {
					$arr_respuesta = array(
						"status" => true,
						"data" => tabla_actividades("", $plan),
						"message" => "Situación actualizada exitosamente..."
					);
				} else {
					$arr_respuesta = array(
						"status" => false,
						"data" => [],
						"message" => $sql
					);
				}
			}
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar datos obligatorios"
		);
	}
	echo json_encode($arr_respuesta);
}
function update($codigo, $campo, $valor)
{
	$ClsAct = new ClsActividad();
	if ($codigo != "" && $campo != "") {
		switch ($campo) {
			case 1:
				$db_campo = "act_comentario";
				break;
			default:
				$db_campo = "";
				break;
		}
		if ($db_campo != "") {
			$sql = $ClsAct->update_actividad_mejora($codigo, $db_campo, $valor);
		}
		$rs = $ClsAct->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro actualizado satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		} else {
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..." . $sql
			);
			echo json_encode($arr_respuesta);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);
		echo json_encode($arr_respuesta);
	}
}

////////////////////// Plan ////////////////
function situacion_plan($codigo, $situacion)
{
	$ClsPla = new ClsPlan();
	if ($codigo != "" && $situacion != "") {
		$sql = $ClsPla->cambia_situacion_plan_mejora($codigo, $situacion);
		$rs = $ClsPla->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"message" => "Situación actualizada exitosamente..."
			);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => $sql
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar datos obligatorios"
		);
	}
	echo json_encode($arr_respuesta);
}

function corregir_plan($codigo, $justificacion)
{
	$ClsPla = new ClsPlan();
	if ($codigo != "") {
		$sql = $ClsPla->update_plan_mejora($codigo, $justificacion);
		$rs = $ClsPla->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"message" => "Situación actualizada exitosamente..."
			);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => $sql
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar datos obligatorios"
		);
	}
	echo json_encode($arr_respuesta);
}
