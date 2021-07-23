<?php
ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cache");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);
//--
include_once('html_fns_ryo.php');

header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header("Access-Control-Allow-Origin: *");

///API REQUEST
$request = $_REQUEST["request"];
$_REQUEST = str_replace("undefined", "", $_REQUEST); ///valida campos "undefined" desde javascript

if ($request != "") {
	switch ($request) {
		case "grabar":
			$riesgo = $_REQUEST["riesgo"];
			$origen = $_REQUEST["origen"];
			$causa = $_REQUEST["causa"];
			$consecuencia = $_REQUEST["consecuencia"];
			$probabilidad = $_REQUEST["probabilidad"];
			$impacto = $_REQUEST["impacto"];
			$resultado = $_REQUEST["resultado"];
			$condicion = $_REQUEST["condicion"];
			$accion = $_REQUEST["accion"];
			grabar($riesgo, $origen, $causa, $consecuencia, $probabilidad, $impacto, $resultado, $condicion, $accion);
			break;
		case "situacion":
			$codigo = $_REQUEST["codigo"];
			$situacion = $_REQUEST["situacion"];
			situacion($codigo, $situacion);
			break;
		case "situacion_oportunidad":
			$codigo = $_REQUEST["codigo"];
			$situacion = $_REQUEST["situacion"];
			situacion_oportunidad($codigo, $situacion);
			break;
		case "update":
			$codigo = $_REQUEST["codigo"];
			$campo = $_REQUEST["campo"];
			$valor = $_REQUEST["valor"];
			update($codigo, $campo, $valor);
			break;
		case "update_oportunidad":
			$codigo = $_REQUEST["codigo"];
			$campo = $_REQUEST["campo"];
			$valor = $_REQUEST["valor"];
			update_oportunidad($codigo, $campo, $valor);
			break;
		case "asignar_riesgo":
			$riesgo = $_REQUEST["riesgo"];
			$usuarios = $_REQUEST["usuarios"];
			asignar_riesgo($riesgo, $usuarios);
			break;
			////////////////////// Planes de Accion ////////////////
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

function grabar($riesgo, $origen, $causa, $consecuencia, $probabilidad, $impacto, $usuario, $evaluacion, $accion)
{
	$ClsRie = new ClsRiesgo();
	if ($riesgo != "") {
		$codigo = $ClsRie->max_riesgo();
		$codigo++; /// Maximo codigo de Version
		$sql = $ClsRie->insert_riesgo($codigo, $riesgo, $origen, $causa, $consecuencia, $probabilidad, $impacto, $usuario, $evaluacion, $accion); /// Inserta Version
		$rs = $ClsRie->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro guardado satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		} else {
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..."
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


function update($codigo, $campo, $valor)
{
	$ClsRie = new ClsRiesgo();
	if ($codigo != "" && $campo != "") {
		switch ($campo) {
			case 1:
				$db_campo = "rie_origen";
				break;
			case 2:
				$db_campo = "rie_causa";
				break;
			case 3:
				$db_campo = "rie_consecuencia";
				break;
			case 4:
				$db_campo = "rie_probabilidad";
				break;
			case 5:
				$db_campo = "rie_impacto";
				break;
			case 6:
				$db_campo = "rie_accion";
				break;
			case 7:
				$db_campo = "rie_evaluacion_tipo";
				break;
			case 8:
				$db_campo = "rie_justificacion";
				break;
			case 9:
				$db_campo = "rie_revisa";
				break;
			case 10:
				$valor = regresa_fecha($valor);
				$db_campo = "rie_fecha_materializacion";
				break;
			case 11:
				$db_campo = "rie_usuario_materializa";
				break;
			default:
				$db_campo = "";
				break;
		}
		if ($db_campo != "") {
			$sql = $ClsRie->modifica_riesgo($codigo, $db_campo, $valor);
		}
		$rs = $ClsRie->exec_sql($sql);
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

function update_oportunidad($codigo, $campo, $valor)
{
	$ClsOpo = new ClsOportunidad();
	if ($codigo != "" && $campo != "") {
		switch ($campo) {
			case 1:
				$db_campo = "opo_viabilidad";
				break;
			case 2:
				$db_campo = "opo_rentabilidad";
				break;
			case 3:
				$db_campo = "opo_accion";
				break;
			case 4:
				$db_campo = "opo_justificacion";
				break;
			case 5:
				$db_campo = "opo_revisa";
				break;
			default:
				$db_campo = "";
				break;
		}
		if ($db_campo != "") {
			$sql = $ClsOpo->modifica_oportunidad($codigo, $db_campo, $valor);
		}
		$rs = $ClsOpo->exec_sql($sql);
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

function situacion_plan($codigo, $situacion)
{
	$ClsPla = new ClsPlan();
	if ($codigo != "" && $situacion != "") {
		$sql = $ClsPla->cambia_situacion_plan_ryo($codigo, $situacion);
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
		$sql = $ClsPla->update_plan_ryo($codigo, $justificacion);
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

function situacion($codigo, $situacion)
{
	$ClsRie = new ClsRiesgo();
	if ($codigo != "" && $situacion != "") {
		$sql = $ClsRie->cambia_situacion_riesgo($codigo, $situacion);
		$rs = $ClsRie->exec_sql($sql);
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

function situacion_oportunidad($codigo, $situacion)
{
	$ClsOpo = new ClsOportunidad();
	if ($codigo != "" && $situacion != "") {
		$sql = $ClsOpo->cambia_situacion_oportunidad($codigo, $situacion);
		$rs = $ClsOpo->exec_sql($sql);
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

function asignar_riesgo($riesgo, $usuarios)
{
	$ClsRie = new ClsRiesgo();
	if ($riesgo != "") {
		$codigo = $ClsRie->max_riesgo_usuario($riesgo);
		$codigo++;
		$sql = $ClsRie->delete_riesgo_usuario($riesgo);
		if ($usuarios != "") {
			$arrUsuarios = explode(",", $usuarios);
			$count = count($arrUsuarios); //cuenta cuantas vienen en el array
		} else {
			$count = 0;
		}
		for ($i = 0; $i < $count; $i++) {
			$usuario = $arrUsuarios[$i];
			$sql .= $ClsRie->insert_riesgo_usuario($codigo, $riesgo, $usuario);
			$codigo++;
		}
		$rs = $ClsRie->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"sql" => $sql,
				"data" => [],
				"message" => "Registro guardado satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		} else {
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..."
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
