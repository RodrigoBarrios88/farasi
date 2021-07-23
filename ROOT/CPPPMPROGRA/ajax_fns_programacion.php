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
include_once('html_fns_programacion.php');

$request = $_REQUEST["request"];
switch ($request) {
		/////////////////////// Cuestionario ///////////////////
	case "tabla":
		$codigo = $_REQUEST["codigo"];
		get_tabla($codigo);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get_cuestionario($codigo);
		break;
	case "grabar":
		$categoria = $_REQUEST["categoria"];
		$nombre = $_REQUEST["nombre"];
		grabar_cuestionario($categoria, $nombre);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$categoria = $_REQUEST["categoria"];
		$nombre = $_REQUEST["nombre"];
		modificar_cuestionario($codigo, $categoria, $nombre);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_cuestionario($codigo, $situacion);
		break;
		//////////////////// Pregunta //////////////////////
	case "tabla_pregunta":
		$codigo = $_REQUEST["codigo"];
		get_tabla_pregunta($codigo);
		break;
	case "grabar_pregunta":
		$cuestionario = $_REQUEST["cuestionario"];
		$pregunta = $_REQUEST["pregunta"];
		grabar_pregunta($cuestionario, $pregunta);
		break;
	case "modificar_pregunta":
		$codigo = $_REQUEST["codigo"];
		$cuestionario = $_REQUEST["cuestionario"];
		$pregunta = $_REQUEST["pregunta"];
		modificar_pregunta($codigo, $cuestionario, $pregunta);
		break;
	case "situacion_pregunta":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_pregunta($codigo, $situacion);
		break;
	case "get_pregunta":
		$codigo = $_REQUEST["codigo"];
		get_pregunta($codigo);
		break;
		//////////////// Programacion ////////////////////
	case "grabar_programacion":
		$activo = $_REQUEST["activo"];
		$usuario = $_REQUEST["usuario"];
		$presupuesto = $_REQUEST["presupuesto"];
		$moneda = $_REQUEST["moneda"];
		$categoria = $_REQUEST["categoria"];
		$tipo = $_REQUEST["tipo"];
		$cuestionario = $_REQUEST["cuestionario"];
		$desde = $_REQUEST["desde"];
		$hasta = $_REQUEST["hasta"];
		$observacion = $_REQUEST["observacion"];
		$dias = $_REQUEST["dias"];
		grabar_programacion($activo, $usuario, $presupuesto, $moneda, $categoria, $tipo, $cuestionario, $desde, $hasta, $observacion, $dias);
		break;
	case "modificar_programacion":
		$codigo = $_REQUEST["codigo"];
		$presupuesto = $_REQUEST["presupuesto"];
		$moneda = $_REQUEST["moneda"];
		$categoria = $_REQUEST["categoria"];
		$cuestionario = $_REQUEST["cuestionario"];
		$fecha = $_REQUEST["fecha"];
		$observacion = $_REQUEST["observacion"];
		modificar_programacion($codigo, $presupuesto, $moneda, $categoria, $cuestionario, $fecha, $observacion);
		break;
	case "situacion_programacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_programacion($codigo, $situacion);
		break;
	case "reprogramar":
		$programacion = $_REQUEST["programacion"];
		$fechaold = $_REQUEST["fechaold"];
		$fechanew = $_REQUEST["fechanew"];
		$justificacion = $_REQUEST["justificacion"];
		reprogramar($programacion, $fechaold, $fechanew, $justificacion);
		break;
	case "reasignar":
		$programacion = $_REQUEST["programacion"];
		$usuarioold = $_REQUEST["usuarioold"];
		$usuarionew = $_REQUEST["usuarionew"];
		$justificacion = $_REQUEST["justificacion"];
		reasignar($programacion, $usuarioold, $usuarionew, $justificacion);
		break;
	case "get_periodicidad":
		$codigo = $_REQUEST['codigo'];
		get_periodicidad($codigo);
		break;
	case "tabla_reprogramacion":
		$activo = $_REQUEST['activo'];
		$usuario = $_REQUEST['usuario'];
		$categoria = $_REQUEST['categoria'];
		$area = $_REQUEST['area'];
		$desde = $_REQUEST['desde'];
		$hasta = $_REQUEST['hasta'];
		tabla_reprogramacionPPM($activo, $usuario, $categoria, $area, $desde, $hasta);
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
function get_tabla($codigo)
{
	$ClsCue = new ClsCuestionarioPPM();
	$result = $ClsCue->get_cuestionario($codigo, '', 1);
	if (is_array($result)) {
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_cuestionarios($codigo),
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


function get_cuestionario($codigo)
{
	$ClsCue = new ClsCuestionarioPPM();
	$result = $ClsCue->get_cuestionario($codigo, '', 1);
	$arr_data = array();
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data["codigo"] = trim($row["cue_codigo"]);
			$arr_data["categoria"] = trim($row["cue_categoria"]);
			$arr_data["nombre"] = trim($row["cue_nombre"]);
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_cuestionarios($codigo),
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


function grabar_cuestionario($categoria, $nombre)
{
	$ClsCue = new ClsCuestionarioPPM();
	//trim a cadena
	$nombre = trim($nombre);
	//--------
	//decodificaciones de tildes y �'s
	$nombre = utf8_decode(utf8_encode($nombre));
	//--------
	//$respuesta->alert("$nombre,$dep,$mun,$direc,$zona,$lat,$long");
	if ($categoria != "" && $nombre != "") {
		$codigo = $ClsCue->max_cuestionario();
		$codigo++; /// Maximo codigo de Cuestionario
		//$respuesta->alert("$id");
		$sql = $ClsCue->insert_cuestionario($codigo, $categoria, $nombre); /// Inserta Cuestionario
		//$respuesta->alert("$sql");

		$rs = $ClsCue->exec_sql($sql);
		if (
			$rs == 1
		) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro guardado satisfactoriamente...!"
			);
		} else {
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..."
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);
	}
	echo json_encode($arr_respuesta);
}

function modificar_cuestionario($codigo, $categoria, $nombre)
{
	$ClsCue = new ClsCuestionarioPPM();
	//trim a cadena
	$nombre = trim($nombre);
	//decodificaciones de tildes y �'s
	$nombre =  utf8_decode(utf8_encode($nombre));
	//--------
	//$respuesta->alert("$codigo,$sede,$sector,$nivel,$nombre");
	if ($codigo != "" && $categoria != "" && $nombre != "") {
		$sql = $ClsCue->modifica_cuestionario($codigo, $categoria, $nombre);
		//$respuesta->alert($sql);

		$rs = $ClsCue->exec_sql($sql);
		if (
			$rs == 1
		) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro modificado satisfactoriamente...!"
			);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..."
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);
	}
	echo json_encode($arr_respuesta);
}

function situacion_cuestionario($codigo, $situacion)
{
	$ClsCue = new ClsCuestionarioPPM();
	if ($codigo != "") {
		$sql = $ClsCue->cambia_sit_cuestionario($codigo, $situacion);
		//$respuesta->alert($sql);

		$rs = $ClsCue->exec_sql($sql);
		if (
			$rs == 1
		) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro eliminado satisfactoriamente...!"
			);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..."
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Error en la transacción..."
		);
	}
	echo json_encode($arr_respuesta);
}

////////////// Preguntas //////////////////

function grabar_pregunta($cuestionario, $pregunta)
{
	$ClsCue = new ClsCuestionarioPPM();
	//trim a cadena
	$pregunta = trim($pregunta);
	//decodificaciones de tildes y �'s
	$pregunta = utf8_decode(utf8_encode($pregunta));

	//--------
	if ($cuestionario != "" && $pregunta != "") {
		$codigo = $ClsCue->max_pregunta();
		$codigo++; /// Maximo codigo de Pregunta
		//$respuesta->alert("$id");
		$sql = $ClsCue->insert_pregunta($codigo, $cuestionario, $pregunta); /// Inserta Pregunta
		//$respuesta->alert("$sql");

		$rs = $ClsCue->exec_sql($sql);
		if (
			$rs == 1
		) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro guardado satisfactoriamente...!"
			);
		} else {
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..."
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);
	}
	echo json_encode($arr_respuesta);
}

function modificar_pregunta($codigo, $cuestionario, $pregunta)
{
	$ClsCue = new ClsCuestionarioPPM();
	//trim a cadena
	$pregunta = trim($pregunta);
	//decodificaciones de tildes y �'s
	$pregunta = utf8_decode(utf8_encode($pregunta));

	//--------
	if ($codigo != "" && $cuestionario != "" && $pregunta != "") {
		$sql = $ClsCue->modifica_pregunta($codigo, $cuestionario, $pregunta); /// Modifica Pregunta
		$rs = $ClsCue->exec_sql($sql);
		if (
			$rs == 1
		) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro modificado satisfactoriamente...!"
			);
		} else {
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..."
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);
	}
	echo json_encode($arr_respuesta);
}

function situacion_pregunta($codigo, $situacion)
{
	$ClsCue = new ClsCuestionarioPPM();
	//--------
	if ($codigo != "" && $situacion != "") {
		$sql = $ClsCue->cambia_sit_pregunta($codigo, $situacion); /// Modifica Pregunta
		$rs = $ClsCue->exec_sql($sql);
		if (
			$rs == 1
		) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro eliminado satisfactoriamente...!"
			);
		} else {
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..."
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);
	}
	echo json_encode($arr_respuesta);
}

function get_tabla_pregunta($cuestionario)
{
	$ClsCue = new ClsCuestionarioPPM();
	$result = $ClsCue->get_pregunta('', $cuestionario, '', 1);
	if (is_array($result)) {
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_preguntas('', $cuestionario),
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

function get_pregunta($codigo)
{
	$ClsCue = new ClsCuestionarioPPM();
	$result = $ClsCue->get_pregunta($codigo, '');
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data["codigo"] = trim($row["pre_codigo"]);
			$arr_data["cuestionario"] = trim($row["pre_cuestionario"]);
			$arr_data["pregunta"] = trim($row["pre_pregunta"]);
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_preguntas($codigo, ''),
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

////////////////////////////// Programacion ////////////////////////
function grabar_programacion($activo, $usuario, $presupuesto, $moneda, $categoria, $tipo, $cuestionario, $desde, $hasta, $observacion, $dias)
{
	$ClsPro = new ClsProgramacionPPM();
	//trim a cadena
	$activo = trim($activo);
	$usuario = trim($usuario);
	$tipo = trim($tipo);
	$presupuesto = trim($presupuesto);
	$moneda = trim($moneda);
	$cuestionario = trim($cuestionario);
	$observacion = trim($observacion);
	//Convierte un array de javascript a uno de php
	$dias = php_array($dias);

	if ($activo != "" && $usuario != "" && $categoria != "" && $tipo != "" && $presupuesto != "" && $moneda != "" && $desde != "" && $hasta != "") {
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
				$sql .= $ClsPro->insert_programacion($fecha, $activo, $usuario, $categoria, $tipo, $presupuesto, $moneda, $cuestionario, $observacion);
			}
		}

		$rs = $ClsPro->exec_sql($sql);
		if (
			$rs == 1
		) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				//"sql" => $sql,
				"message" => "Registro grabado satisfactoriamente...!"
			);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..."
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);
	}
	echo json_encode($arr_respuesta);
}

function situacion_programacion($codigo, $situacion)
{
	$ClsPro = new ClsProgramacionPPM();
	//--------
	if ($codigo != "" && $situacion != "") {
		$sql = $ClsPro->cambia_sit_programacion($codigo, date("d/m/Y H:i:s"), $situacion); /// Modifica Pregunta
		$rs = $ClsPro->exec_sql($sql);
		if (
			$rs == 1
		) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro eliminado satisfactoriamente...!"
			);
		} else {
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..."
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);
	}
	echo json_encode($arr_respuesta);
}

function modificar_programacion($codigo, $presupuesto, $moneda, $categoria, $cuestionario, $fecha, $observacion)
{
	$ClsPro = new ClsProgramacionPPM();
	//trim a cadena
	$observacion = trim($observacion);
	$moneda = trim($moneda);
	//decodificaciones de tildes y �'s
	$observacion = utf8_decode(utf8_encode($observacion));

	if ($codigo != "" && $categoria != "" && $fecha != "" && $presupuesto != "" && $moneda != "" && $cuestionario != "") {
		$sql = $ClsPro->modifica_programacion($codigo, $categoria, $fecha, $presupuesto, $moneda, $cuestionario, $observacion);
		//$respuesta->alert($sql);

		$rs = $ClsPro->exec_sql($sql);
		if (
			$rs == 1
		) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro modificado satisfactoriamente...!"
			);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..."
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);
	}
	echo json_encode($arr_respuesta);
}

///////////////////////// Reprogramacion ///////////////////////

function reprogramar($programacion, $fechaold, $fechanew, $justificacion)
{
	$ClsPro = new ClsProgramacionPPM();
	//trim a cadena
	$justificacion = trim($justificacion);
	//--------
	//decodificaciones de tildes y �'s
	$justificacion = utf8_decode(utf8_encode($justificacion));

	if ($programacion != "" && $fechaold != "" && $fechanew != "" && $justificacion != "") {
		$codigo = $ClsPro->max_reprogramacion($programacion);
		$codigo++; /// Maximo codigo de Sede
		$sql = $ClsPro->insert_reprogramacion($codigo, $programacion, $fechaold, $fechanew, $justificacion);
		//$respuesta->alert($sql);

		$rs = $ClsPro->exec_sql($sql);
		if (
			$rs == 1
		) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Orden de Trabajo Re-Porgramada satisfactoriamente!!!"
			);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..."
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);
	}
	echo json_encode($arr_respuesta);
}

function reasignar($programacion, $usuarioold, $usuarionew, $justificacion)
{
	$ClsPro = new ClsProgramacionPPM();
	//trim a cadena
	$justificacion = trim($justificacion);
	//--------
	//decodificaciones de tildes y �'s
	$justificacion = utf8_encode($justificacion);
	//--
	$justificacion = utf8_decode($justificacion);

	//$respuesta->alert("$obs,$dep,$mun,$direc,$zona,$lat,$long");
	if ($programacion != "" && $usuarioold != "" && $usuarionew != "" && $justificacion != "") {
		$codigo = $ClsPro->max_reasignacion($programacion);
		$codigo++; /// Maximo codigo de Sede
		$sql = $ClsPro->insert_reasignacion($codigo, $programacion, $usuarioold, $usuarionew, $justificacion);
		//$respuesta->alert($sql);

		$rs = $ClsPro->exec_sql($sql);
		if (
			$rs == 1
		) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Orden de Trabajo Re-Asignada satisfactoriamente!!!"
			);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..."
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);
	}
	echo json_encode($arr_respuesta);
}
function get_periodicidad($codigo)
{
	$ClsAct = new ClsActivo();
	$result = $ClsAct->get_activo($codigo);
	//var_dump($result);
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data['periodicidad'] = trim($row["act_periodicidad"]);
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
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


function tabla_reprogramacionPPM($activo, $usuario, $categoria, $area, $desde, $hasta)
{
	$ClsPro = new ClsProgramacionPPM();
	$result = $ClsPro->get_programacion('', $activo, $usuario, $categoria, '', '', $area, $desde, $hasta, '', '', '');
	if (is_array($result)) {
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_reprogramacion($activo, $usuario, $categoria, $area, $desde, $hasta),
			"message" => ""
		);
	} else {
		$arr_respuesta = array(
			"status" => false,
			"tabla" => "",
			"message" => "Aún no hay datos registrados..."
		);
	}
	echo json_encode($arr_respuesta);
}
