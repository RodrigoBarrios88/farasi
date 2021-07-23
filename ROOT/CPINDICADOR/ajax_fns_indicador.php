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
include_once('html_fns_indicador.php');

$request = $_REQUEST["request"];
switch ($request) {
	case "tabla":
		$codigo = $_REQUEST["codigo"];
		$usuario = $_REQUEST["usuario"];
		get_tabla($codigo, $usuario);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get_indicador($codigo);
		break;
	case "grabar":
		$proceso = $_REQUEST["proceso"];
		$sistema = $_REQUEST["sistema"];
		$categoria = $_REQUEST["categoria"];
		$clasificacion = $_REQUEST["clasificacion"];
		$nombre = $_REQUEST["nombre"];
		$descripcion = $_REQUEST["descripcion"];
		$ideal = $_REQUEST["ideal"];
		$max = $_REQUEST["max"];
		$min = $_REQUEST["min"];
		grabar_indicador($proceso, $sistema, $categoria, $clasificacion, $nombre, $descripcion, $ideal, $max, $min);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$proceso = $_REQUEST["proceso"];
		$sistema = $_REQUEST["sistema"];
		$categoria = $_REQUEST["categoria"];
		$clasificacion = $_REQUEST["clasificacion"];
		$nombre = $_REQUEST["nombre"];
		$descripcion = $_REQUEST["descripcion"];
		$ideal = $_REQUEST["ideal"];
		$max = $_REQUEST["max"];
		$min = $_REQUEST["min"];
		modificar_indicador($codigo, $proceso, $sistema, $categoria, $clasificacion, $nombre, $descripcion, $ideal, $max, $min);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_indicador($codigo, $situacion);
		break;
	case "grabar_programacion":
		$indicador = $_REQUEST["indicador"];
		$observacion = $_REQUEST["observacion"];
		$hini = $_REQUEST["hini"];
		$hfin = $_REQUEST["hfin"];
		$tipo = $_REQUEST["tipo"];
		$desde = $_REQUEST["desde"];
		$hasta = $_REQUEST["hasta"];
		$dias = $_REQUEST["dias"];
		grabar_programacion($indicador, $observacion, $hini, $hfin, $tipo, $desde, $hasta, $dias);
		break;
	case "modificar_programacion":
		$codigo = $_REQUEST["codigo"];
		$observacion = $_REQUEST["observacion"];
		$hini = $_REQUEST["hini"];
		$hfin = $_REQUEST["hfin"];
		$fecha = $_REQUEST["fecha"];
		modificar_programacion($codigo, $observacion, $hini, $hfin,$fecha);
		break;
	case "get_programacion":
		$codigo = $_REQUEST["codigo"];
		get_programacion($codigo);
		break;
	case "situacion_programacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_programacion($codigo, $situacion);
		break;
	case "tabla_programacion":
		$codigo = $_REQUEST["codigo"];
		$indicador = $_REQUEST["indicador"];
		get_tabla_programacion($codigo, $indicador);
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
function get_tabla($codigo, $usuario)
{
	$ClsInd = new ClsIndicador();
	$result = $ClsInd->get_indicador($codigo,"","","",$usuario);
	if (is_array($result)) {
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_indicadores($codigo,"","","",$usuario),
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

function get_indicador($codigo)
{
	$ClsInd = new Clsindicador();
	$result = $ClsInd->get_indicador($codigo,"","","","","","",1);
	$i = 0;
	$arr_data = array();
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data["codigo"] = trim($row["ind_codigo"]);
			$arr_data["proceso"] = trim($row["ind_proceso"]);
			$arr_data["sistema"] = trim($row["ind_sistema"]);
			$arr_data["categoria"] = trim($row["ind_categoria"]);
			$arr_data["clasificacion"] = trim($row["ind_clasificacion"]);
			$arr_data["nombre"] = trim($row["ind_nombre"]);
			$arr_data["descripcion"] = trim($row["ind_descripcion"]);
			$arr_data["ideal"] = trim($row["ind_lectura_ideal"]);
			$arr_data["max"] = trim($row["ind_lectura_maxima"]);
			$arr_data["min"] = trim($row["ind_lectura_minima"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_indicadores($codigo,"","","","","","",1),
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

function grabar_indicador($proceso, $sistema, $categoria, $clasificacion, $nombre, $descripcion, $ideal, $max, $min)
{
	$ClsInd = new ClsIndicador();
	if (
		$proceso != "" && $sistema != "" && $categoria != "" && $clasificacion != "" && $nombre != ""
		&& $descripcion != "" && $ideal != "" && $max != "" && $min != ""
	) {
		$nombre = utf8_decode(utf8_encode(trim($nombre)));
		$descripcion = utf8_decode(utf8_encode(trim($descripcion)));$codigo = $ClsInd->max_indicador();
		$codigo++; /// Maximo codigo de Version
		$sql = $ClsInd->insert_indicador($codigo, $proceso, $sistema, $categoria, $clasificacion, $nombre, $descripcion, $ideal, $max, $min);
		$rs = $ClsInd->exec_sql($sql);
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
		);echo json_encode($arr_respuesta);
	}
}

function modificar_indicador($codigo, $proceso, $sistema, $categoria, $clasificacion, $nombre, $descripcion, $ideal, $max, $min)
{
	$ClsInd = new Clsindicador();
	if (
		$proceso != "" && $sistema != "" && $categoria != "" && $clasificacion != "" && $nombre != ""
		&& $descripcion != "" && $ideal != "" && $max != "" && $min != "" && $codigo != ""
	) {
		$nombre = utf8_decode(utf8_encode(trim($nombre)));
		$descripcion = utf8_decode(utf8_encode(trim($descripcion)));
		$sql = $ClsInd->modifica_indicador($codigo, $proceso, $sistema, $categoria, $clasificacion, $nombre, $descripcion, $ideal, $max, $min);
		$rs = $ClsInd->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro actualizados satisfactoriamente...!"
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
		);echo json_encode($arr_respuesta);
	}
}


function situacion_indicador($codigo, $situacion)
{
	$ClsInd = new Clsindicador();
	$sql = $ClsInd->cambia_situacion_indicador($codigo, $situacion);
	$rs = $ClsInd->exec_sql($sql);
	if ($rs == 1) {
		$arr_respuesta = array(
			"status" => true,
			"data" => [],
			"message" => "Versión eliminada exitosamente...!"
		);echo json_encode($arr_respuesta);
	} else {
		$arr_respuesta = array(
			"status" => false,
			"sql" => $sql,
			"data" => [],
			"message" => "Error en la ejecución"
		);echo json_encode($arr_respuesta);
	}
}

////////// Programacion //////////
function get_tabla_programacion($codigo, $indicador)
{
	$ClsInd = new ClsIndicador();
	$result = $ClsInd->get_programacion($codigo, $indicador);
	if (is_array($result)) {
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_programacion($codigo, $indicador),
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


function get_programacion($codigo)
{
	$ClsInd = new Clsindicador();
	$result = $ClsInd->get_programacion($codigo, '');
	$i = 0;
	$arr_data = array();
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data["codigo"] = trim($row["pro_codigo"]);
			$arr_data["clasificacion"] = trim($row["cla_nombre"]);
			$arr_data["categoria"] = trim($row["cat_nombre"]);
			$arr_data["indicador"] = trim($row["ind_nombre"]);
			$arr_data["tipo"] = trim($row["pro_tipo"]);
			$arr_data["dia1"] = trim($row["pro_dia_1"]);
			$arr_data["dia2"] = trim($row["pro_dia_2"]);
			$arr_data["dia3"] = trim($row["pro_dia_3"]);
			$arr_data["dia4"] = trim($row["pro_dia_4"]);
			$arr_data["dia5"] = trim($row["pro_dia_5"]);
			$arr_data["dia6"] = trim($row["pro_dia_6"]);
			$arr_data["dia7"] = trim($row["pro_dia_7"]);
			$arr_data["diaMes"] = trim($row["pro_dia_mes"]);
			$arr_data["hini"] = substr($row["pro_hini"], 0, 5);
			$arr_data["hfin"] = substr($row["pro_hfin"], 0, 5);
			$arr_data["obs"] = trim($row["pro_observaciones"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_programacion($codigo, ''),
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


function modificar_programacion($codigo, $observacion, $hini, $hfin, $fecha)
{
	$ClsInd = new Clsindicador();
	if ($codigo != "" && $hini != "" && $hfin != "" && $fecha != "") {
		$sql = $ClsInd->modifica_programacion($codigo, $observacion, $hini, $hfin, $fecha);
		$rs = $ClsInd->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro actualizados satisfactoriamente...!"
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
		);echo json_encode($arr_respuesta);
	}
}

function grabar_programacion($indicador, $observacion, $hini, $hfin, $tipo, $desde, $hasta, $dias)
{
	$ClsInd = new ClsIndicador();
	$usuario = $_SESSION['codigo'];
	//decodificaciones de tildes y �'s
	$observacion = utf8_decode(utf8_encode($observacion));
	//Convierte un array de javascript a uno de php
	$dias = php_array($dias);
	if ($indicador != "" && $hini != "" && $hfin != "" && $desde != "" && $hasta != "" && $tipo != "") {
		$sql = "";
		for ($i = strtotime(regresa_fecha($desde)); $i <= strtotime(regresa_fecha($hasta)); $i += 86400) {
			$fecha = date("d/m/Y", $i);
			// Calcula el index en base al tipo de programacion
			if ($tipo == "W") {
				$index = date("w", $i);
				$index = ($index == 0) ? 7 : $index;
			} else if ($tipo == "M") {
				$index = date("d", $i);
				$index = intval($index);
			}
			if ($dias[$index - 1] == 1) {
				$sql .= $ClsInd->insert_programacion($indicador, $tipo, $fecha, $hini, $hfin, $observacion, $usuario);
			}
		}
		$rs = $ClsInd->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
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
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);echo json_encode($arr_respuesta);
	}
}


function situacion_programacion($codigo, $situacion)
{
	$ClsInd = new Clsindicador();
	$sql = $ClsInd->cambia_sit_programacion($codigo, $situacion);
	$rs = $ClsInd->exec_sql($sql);
	if ($rs == 1) {
		$arr_respuesta = array(
			"status" => true,
			"data" => [],
			"message" => "Versión eliminada exitosamente...!"
		);
		echo json_encode($arr_respuesta);
	} else {
		$arr_respuesta = array(
			"status" => false,
			"sql" => $sql,
			"data" => [],
			"message" => "Error en la ejecución"
		);echo json_encode($arr_respuesta);
	}
}
