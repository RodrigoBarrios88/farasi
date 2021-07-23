<?php
ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cache");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);
//--
include_once('html_fns_planning.php');

header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header("Access-Control-Allow-Origin: *");

///API REQUEST
$request = $_REQUEST["request"];
$_REQUEST = str_replace("undefined", "", $_REQUEST); ///vaSlida campos "undefined" desde javascript

if ($request != "") {
	switch ($request) {
			// Acciones
		case "grabar":
			$descripcion = $_REQUEST["descripcion"];
			$nombre = $_REQUEST["nombre"];
			$inicio = $_REQUEST["inicio"];
			$fin = $_REQUEST["fin"];
			$desde = $_REQUEST["desde"];
			$hasta = $_REQUEST["hasta"];
			$tipo = $_REQUEST["tipo"];
			$presupuesto = $_REQUEST["presupuesto"];
			$objetivo = $_REQUEST["objetivo"];
			grabar($descripcion, $nombre, $inicio, $fin, $desde, $hasta, $tipo, $presupuesto, $objetivo);
			break;
		case "delete":
			$codigo = $_REQUEST["codigo"];
			delete($codigo);
			break;
		case "modificar":
			$codigo = $_REQUEST["codigo"];
			$descripcion = $_REQUEST["descripcion"];
			$nombre = $_REQUEST["nombre"];
			$inicio = $_REQUEST["inicio"];
			$fin = $_REQUEST["fin"];
			$desde = $_REQUEST["desde"];
			$hasta = $_REQUEST["hasta"];
			$tipo = $_REQUEST["tipo"];
			$presupuesto = $_REQUEST["presupuesto"];
			$objetivo = $_REQUEST["objetivo"];
			modificar($codigo, $descripcion, $nombre, $inicio, $fin, $desde, $hasta, $tipo, $presupuesto, $objetivo);
			break;
		case "comentario":
			$codigo = $_REQUEST["codigo"];
			$comentario = $_REQUEST["comentario"];
			comentario($codigo, $comentario);
			break;
		case "observacion":
			$codigo = $_REQUEST["codigo"];
			$observacion = $_REQUEST["observacion"];
			observacion($codigo, $observacion);
			break;
		case "situacion_revision":
			$codigo = $_REQUEST["codigo"];
			$situacion = $_REQUEST["situacion"];
			$observacion = $_REQUEST["observacion"];
			situacion_revision($codigo, $situacion, $observacion);
			break;
		case "get":
			$codigo = $_REQUEST["codigo"];
			get($codigo);
			break;
			// Objetivos
		case "aprobacion":
			$codigo = $_REQUEST["codigo"];
			$objetivo = $_REQUEST["objetivo"];
			solicitar_aprobacion($codigo, $objetivo);
			break;
		default:
			$payload = array(
				"status" => false,
				"data" => [],
				"message" => "Parametros invalidos..."
			);
			echo json_encode($payload);
			break;
	}
} else {
	//devuelve un mensaje de manejo de errores
	$payload = array(
		"status" => false,
		"data" => [],
		"message" => "Delimite el desde de consulta a realizar..."
	);
	echo json_encode($payload);
}

function grabar($descripcion, $nombre, $inicio, $fin, $desde, $hasta, $tipo, $presupuesto, $objetivo)
{
	// Validaciones de Fecha
	$hoy = strtotime(date("Y-m-d"));
	$desde = strtotime(regresa_fecha($desde));
	$hasta = strtotime(regresa_fecha($hasta));
	if ($desde >= $hoy && $desde < $hasta && $fin >= $inicio) {
		$ClsAcc = new ClsAccion();
		$codigo = $ClsAcc->max_accion();
		$codigo++;
		$usuario = $_SESSION["codigo"];
		// Accion
		$sql = $ClsAcc->insert_accion($codigo, $objetivo, $usuario, $descripcion, $nombre, $presupuesto, "", $tipo, date("d/m/Y", $desde), date("d/m/Y", $hasta), 1);
		switch ($tipo) {
			case "U":
				$sql .= $ClsAcc->insert_programacion($codigo, date("d/m/Y", $desde),  date("d/m/Y", $hasta),  1);
				break;
			case "M":
				// Fecha de Inicio
				$m = date("m", $desde);
				$Y = date("Y", $desde);
				$dateInicio = strtotime(date("$Y-$m-$inicio"));
				// Fecha Fin
				$m2 = date("m", $hasta);
				$Y2 = date("Y", $hasta);
				$dateFin = strtotime(date("$Y2-$m2-$inicio"));
				while ($dateInicio <= $dateFin) {
					// Dia Fin
					$m = date("m", $dateInicio);
					$Y = date("y", $dateInicio);
					$finTemporal = date("$fin/$m/$Y");
					// Programacion
					if ($dateInicio >= $desde && strtotime(regresa_fecha($finTemporal)) <= $hasta)
						$sql .= $ClsAcc->insert_programacion($codigo, date("d/m/Y", $dateInicio), $finTemporal,  1);
					$dateInicio = strtotime("+1 month", $dateInicio);
				}
				break;
			case "W":
				// Fecha de Inicio
				$fini = new DateTime(date("Y-m-d", $desde));
				for ($i = 1; $i <= 7; $i++) {
					if ($fini->format("w") == $inicio) break;
					$fini->modify('+1 days');
				}
				// Fecha Fin
				$ffin = new DateTime(date("Y-m-d", $hasta));
				for ($i = 1; $i <= 7; $i++) {
					if ($ffin->format("w") == $inicio) break;
					$ffin->modify('+1 days');
				}
				while ($fini->getTimestamp() <= $ffin->getTimestamp()) {
					// Dia de Inicio
					$dini = new DateTime($fini->format("Y-m-d"));
					for ($i = 1; $i <= 7; $i++) {
						if ($dini->format("w") == $inicio) break;
						$dini->modify('+1 days');
					}
					// Dia Fin
					$dfin = new DateTime($fini->format("Y-m-d"));
					for ($i = 1; $i <= 7; $i++) {
						if ($dfin->format("w") == $fin) break;
						$dfin->modify('+1 days');
					}
					// Programacion
					if ($dini->getTimestamp() >= $desde && $dfin->getTimestamp() <= $hasta)
						$sql .= $ClsAcc->insert_programacion($codigo, $dini->format("d/m/Y"), $dfin->format("d/m/y"),  1);			
						$fini->modify('+1 week');
				}
				break;
		}
		$rs = $ClsAcc->exec_sql($sql);
		if ($rs == 1) {
			$data = tabla_acciones("", $objetivo, "", $usuario);
			$payload = array(
				"status" => true,
				"codigo" => $codigo,
				"data" => $data,
				"message" => "Accion creada satisfactoriamente..."
			);
			echo json_encode($payload);
		} else {
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => false,
				"data" => [],
				"message" => $sql
			);
			echo json_encode($payload);
		}
	} else {
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Verifique las fechas de su planificacion"
		);
		echo json_encode($payload);
	}
}

function modificar($codigo, $descripcion, $nombre, $inicio, $fin, $desde, $hasta, $tipo, $presupuesto, $objetivo)
{
	// Validaciones de Fecha
	if (strtotime(regresa_fecha($desde)) >= strtotime(regresa_fecha(date("d/m/Y"))) && strtotime(regresa_fecha($desde)) < strtotime(regresa_fecha($hasta)) && $fin >= $inicio) {
		$ClsAcc = new ClsAccion();
		$usuario = $_SESSION["codigo"];
		// Accion
		$sql = $ClsAcc->modifica_accion($codigo, $objetivo, $usuario, $descripcion, $nombre, $presupuesto, "", $tipo, $desde, $hasta, 1);
		// Programacion
		$sql .= $ClsAcc->delete_programacion("", $codigo);
		$pointer = strtotime(regresa_fecha($desde));
		$end = strtotime(regresa_fecha($hasta));
		switch ($tipo) {
			case "U":
				$sql .= $ClsAcc->insert_programacion($codigo, date("d/m/Y", $pointer),  date("d/m/Y", $end),  1);
				break;
			case "M":
				$desde = strtotime(regresa_fecha($desde));
				$m = date("m", $desde);
				$Y = date("Y", $desde);
				$dateInicio = strtotime(regresa_fecha(date("$inicio/$m/$Y")));
				$hasta = strtotime(regresa_fecha($hasta));
				$m2 = date("m", $hasta);
				$Y2 = date("Y", $hasta);
				$dateFin = strtotime(regresa_fecha(date("$inicio/$m2/$Y2")));
				while ($dateInicio <= $dateFin) {
					$m = date("m", $dateInicio);
					$Y = date("y", $dateInicio);
					$finTemporal = date("$fin/$m/$Y");
					if ($dateInicio >= $desde && strtotime(regresa_fecha($finTemporal)) <= $hasta)
						$sql .= $ClsAcc->insert_programacion($codigo, date("d/m/Y", $dateInicio), $finTemporal,  1);
					$dateInicio = strtotime("+1 month", $dateInicio);
				}
				break;
			case "W":
				$inicio = date("w", $inicio);
				$fin = date("w", $fin);
				$desde = strtotime(regresa_fecha($desde));
				$m = date("m", $desde);
				$Y = date("Y", $desde);
				$dateInicio = strtotime(regresa_fecha(date("$inicio/$m/$Y")));
				$hasta = strtotime(regresa_fecha($hasta));
				$m2 = date("m", $hasta);
				$Y2 = date("Y", $hasta);
				$dateFin = strtotime(regresa_fecha(date("$inicio/$m2/$Y2")));
				while ($dateInicio <= $dateFin) {
					$m = date("m", $dateInicio);
					$Y = date("y", $dateInicio);
					$finTemporal = date("$fin/$m/$Y");
					if ($dateInicio >= $desde && strtotime(regresa_fecha($finTemporal)) <= $hasta)
						$sql .= $ClsAcc->insert_programacion($codigo, date("d/m/Y", $dateInicio), $finTemporal,  1);
					$dateInicio = strtotime("+1 week", $dateInicio);
				}
				break;
		}
		$rs = $ClsAcc->exec_sql($sql);
		if ($rs == 1) {
			$data = tabla_acciones("", $objetivo, "", $usuario);
			$payload = array(
				"status" => true,
				"codigo" => $codigo,
				"data" => $data,
				"message" => "Accion modificada satisfactoriamente..."
			);
			echo json_encode($payload);
		} else {
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => false,
				"data" => [],
				"message" => $sql
			);
			echo json_encode($payload);
		}
	} else {
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Verifique las fechas de su planificacion"
		);
		echo json_encode($payload);
	}
}

function comentario($codigo, $comentario)
{
	// Accion
	$ClsAcc = new ClsAccion();
	$sql = $ClsAcc->modifica_accion($codigo, "", "", "", "", "", $comentario);

	$rs = $ClsAcc->exec_sql($sql);
	if ($rs == 1) {
		$payload = array(
			"status" => true,
			"message" => "Accion modificada satisfactoriamente..."
		);
		echo json_encode($payload);
	} else {
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"message" => $sql
		);
		echo json_encode($payload);
	}
}
function observacion($codigo, $observacion)
{
	// Accion
	$ClsRev = new ClsRevision();
	$sql = $ClsRev->modifica_revision_objetivo($codigo, $observacion);

	$rs = $ClsRev->exec_sql($sql);
	if ($rs == 1) {
		$payload = array(
			"status" => true,
			"message" => "Revision modificada satisfactoriamente..."
		);
		echo json_encode($payload);
	} else {
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"message" => $sql
		);
		echo json_encode($payload);
	}
}

function delete($codigo)
{
	// Accion
	$ClsAcc = new ClsAccion();
	$sql = $ClsAcc->cambia_situacion_accion($codigo, 0);
	// Borrar las programaciones
	$sql .= $ClsAcc->cambia_situacion_programacion("", $codigo, 0);

	$rs = $ClsAcc->exec_sql($sql);
	if ($rs == 1) {
		$payload = array(
			"status" => true,
			"message" => "Accion eliminada satisfactoriamente..."
		);
		echo json_encode($payload);
	} else {
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"message" => $sql
		);
		echo json_encode($payload);
	}
}

function get($codigo)
{
	// Accion
	$ClsAcc = new ClsAccion();
	$usuario =  $_SESSION["codigo"];
	$result = $ClsAcc->get_accion($codigo);
	$arr_data = array();
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data["codigo"] = trim($row["acc_codigo"]);
			$arr_data["objetivo"] = trim($row["acc_objetivo"]);
			$arr_data["usuario"] = trim($row["acc_usuario"]);
			$arr_data["nombre"] = trim($row["acc_nombre"]);
			$arr_data["descripcion"] = trim($row["acc_descripcion"]);
			$arr_data["comentario"] = trim($row["acc_comentario"]);
			$arr_data["presupuesto"] = trim($row["acc_presupuesto"]);
			$arr_data["tipo"] = trim($row["acc_tipo"]);
			$arr_data["fini"] = cambia_fecha($row["acc_fecha_inicio"]);
			$arr_data["ffin"] = cambia_fecha($row["acc_fecha_fin"]);
		}
		$data = tabla_acciones($codigo);
		$payload = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => $data,
			"message" => ""
		);
		echo json_encode($payload);
	} else {
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"message" => "Esta accion no existe..."
		);
		echo json_encode($payload);
	}
}

////////////////////// Objetivos ////////////////////////////
function solicitar_aprobacion($codigo, $objetivo)
{
	// TODO si ya existe la revision solo cambiarle situacion
	// Accion
	$ClsObj = new ClsObjetivo();
	if ($codigo == 0) {
		$codigo = $ClsObj->max_revision();
		$codigo++;
	}
	$sql = $ClsObj->insert_revision($codigo, $objetivo, "", 0, "", "", 2);
	$rs = $ClsObj->exec_sql($sql);
	if ($rs == 1) {
		$payload = array(
			"status" => true,
			"message" => "Se solicita aprobacion correctamente"
		);
		echo json_encode($payload);
	} else {
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"message" => $sql
		);
		echo json_encode($payload);
	}
}

function situacion_revision($codigo, $situacion, $observacion)
{
	// Accion
	$ClsObj = new ClsObjetivo();
	$sql = $ClsObj->cambia_situacion_revision($codigo, $observacion, $situacion);
	$rs = $ClsObj->exec_sql($sql);
	if ($rs == 1) {
		$payload = array(
			"status" => true,
			"message" => "Operacion realizada satisfactoriamente"
		);
		echo json_encode($payload);
	} else {
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"message" => $sql
		);
		echo json_encode($payload);
	}
}
