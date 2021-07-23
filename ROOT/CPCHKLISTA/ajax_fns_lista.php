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
include_once('html_fns_lista.php');

$request = $_REQUEST["request"];
switch ($request) {
	case "tabla":
		$codigo = $_REQUEST["codigo"];
		$categoria = $_REQUEST["categoria"];
		get_tabla($codigo, $categoria);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get_lista($codigo);
		break;
	case "grabar":
		$nombre = $_REQUEST["nombre"];
		$categoria = $_REQUEST["categoria"];
		$foto = $_REQUEST["foto"];
		$firma = $_REQUEST["firma"];
		grabar_checklist($nombre, $categoria, $foto, $firma);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$nombre = $_REQUEST["nombre"];
		$categoria = $_REQUEST["categoria"];
		$foto = $_REQUEST["foto"];
		$firma = $_REQUEST["firma"];
		modificar_lista($codigo, $nombre, $categoria, $foto, $firma);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_lista($codigo, $situacion);
		break;
		///////////////////// HORARIOS ///////////////////
	case "tabla_programacion":
		$codigo = $_REQUEST["codigo"];
		$lista = $_REQUEST["lista"];
		get_tabla_programacion($codigo, $lista);
		break;
	case "get_area":
		$area = $_REQUEST["area"];
		get_area($area);
		break;
	case "get_programacion":
		$codigo = $_REQUEST["codigo"];
		$lista = $_REQUEST["lista"];
		get_programacion($codigo, $lista);
		break;
	case "grabar_programacion":
		$lista = $_REQUEST["lista"];
		$sede = $_REQUEST["sede"];
		$sector = $_REQUEST["sector"];
		$area = $_REQUEST["area"];
		$tipo = $_REQUEST["tipo"];
		$dia1 = $_REQUEST["dia1"];
		$dia2 = $_REQUEST["dia2"];
		$dia3 = $_REQUEST["dia3"];
		$dia4 = $_REQUEST["dia4"];
		$dia5 = $_REQUEST["dia5"];
		$dia6 = $_REQUEST["dia6"];
		$dia7 = $_REQUEST["dia7"];
		$diaMes = $_REQUEST["diaMes"];
		$hini = $_REQUEST["hini"];
		$hfin = $_REQUEST["hfin"];
		$observacion = $_REQUEST["observacion"];
		$fecha = $_REQUEST['fechaUnica'];
		grabar_programacion($lista,$sede,$sector,$area,$tipo,$dia1,$dia2,$dia3,$dia4,$dia5,$dia6,$dia7,$diaMes,$hini,$hfin,$observacion, $fecha);
		break;
	case "modificar_programacion":
		$codigo = $_REQUEST["codigo"];
		$lista = $_REQUEST["lista"];
		$sede = $_REQUEST["sede"];
		$sector = $_REQUEST["sector"];
		$area = $_REQUEST["area"];
		$tipo = $_REQUEST["tipo"];
		$dia1 = $_REQUEST["dia1"];
		$dia2 = $_REQUEST["dia2"];
		$dia3 = $_REQUEST["dia3"];
		$dia4 = $_REQUEST["dia4"];
		$dia5 = $_REQUEST["dia5"];
		$dia6 = $_REQUEST["dia6"];
		$dia7 = $_REQUEST["dia7"];
		$diaMes = $_REQUEST["diaMes"];
		$hini = $_REQUEST["hini"];
		$hfin = $_REQUEST["hfin"];
		$observacion = $_REQUEST["observacion"];
		$fecha = $_REQUEST['fechaUnica'];

		modificar_programacion($codigo, $lista,$sede,$sector,$area,$tipo,$dia1,$dia2,$dia3,$dia4,$dia5,$dia6,$dia7,$diaMes,$hini,$hfin,$observacion,$fecha);
		break;
	case "situacion_programacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_programacion($codigo, $situacion);
		break;
		///////////////////// HORARIOS ///////////////////
	case "tabla_preguntas":
		$codigo = $_REQUEST["codigo"];
		$lista = $_REQUEST["lista"];
		get_tabla_preguntas($codigo, $lista);
		break;
	case "grabar_pregunta":
		$lista = $_REQUEST["lista"];
		$pregunta = $_REQUEST["pregunta"];
		grabar_pregunta($lista, $pregunta);
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
	case "modificar_pregunta":
		$codigo = $_REQUEST["codigo"];
		$lista = $_REQUEST["lista"];
		$pregunta = $_REQUEST["pregunta"];
		modificar_pregunta($codigo,$lista,$pregunta);
		break;
	default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

////////////////// VERSIONES /////////////////////////lllll
function get_tabla($codigo, $categoria)
{
	$ClsLis = new ClsLista();
	$result = $ClsLis->get_lista($codigo, $categoria, 1);
	if (is_array($result)) {
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_listas($codigo, $categoria),
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


function get_lista($codigo)
{
	$ClsLis = new ClsLista();
	$result = $ClsLis->get_lista($codigo);
	$i = 0;
	$arr_data = array();
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data["codigo"] = trim($row["list_codigo"]);
			$arr_data["nombre"] = trim($row["list_nombre"]);
			$arr_data["categoria"] = trim($row["list_categoria"]);
			$arr_data["foto"] = trim($row["list_fotos"]);
			$arr_data["firma"] = trim($row["list_firma"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_listas($codigo, ''),
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


function grabar_checklist($nombre, $categoria, $foto, $firma)
{
	$ClsLis = new ClsLista();
	if ($nombre != "" && $categoria != "") {
		$codigo = $ClsLis->max_lista();
		$codigo++; /// Maximo codigo de Version
		$sql = $ClsLis->insert_lista($codigo, $categoria, $nombre, $foto, $firma); /// Inserta Version
		$rs = $ClsLis->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro guardado satisfactoriamente...!",
				"codigo" => $codigo
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


function modificar_lista($codigo, $nombre, $categoria, $foto, $firma)
{
	$ClsLis = new ClsLista();
	if ($codigo != "" && $categoria != "" && $nombre != "") {
		$sql = $ClsLis->modifica_lista($codigo, $categoria, $nombre, $foto, $firma);
		$rs = $ClsLis->exec_sql($sql);
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


function situacion_lista($codigo, $situacion)
{
	$ClsLis = new ClsLista();
	$sql = $ClsLis->cambia_sit_lista($codigo, $situacion);
	$rs = $ClsLis->exec_sql($sql);
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

///////////////////// HORARIOS ///////////////////

function get_area($area)
{
	$ClsAre = new ClsArea();
	$result = $ClsAre->get_area($area);
	$arr_data = array();
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data["sede"] = trim($row["are_sede"]);
			$arr_data["sector"] = trim($row["are_sector"]);
			$arr_data["secNombre"] = trim(trim($row["sec_nombre"]));
			$arr_data["nivel"] = trim($row["are_nivel"]);
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

function get_tabla_programacion($codigo, $lista)
{
	$ClsLis = new ClsLista();
	$result = $ClsLis->get_programacion($codigo, $lista, '', '', '', '', '', '', 1);
	if (is_array($result)) {
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_horarios($codigo, $lista),
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

function grabar_programacion($lista,$sede,$sector,$area,$tipo,$dia1,$dia2,$dia3,$dia4,$dia5,$dia6,$dia7,$diaMes,$hini,$hfin,$observacion, $fecha)
{
	$ClsLis = new ClsLista();
	if ($lista != "" && $sede != "" && $sector != "" && $area != "" && $hini != "" && $hfin != "") {
		$codigo = $ClsLis->max_programacion();
		$codigo++; /// Maximo codigo dtabla_programacione Version
		$sql = $ClsLis->insert_programacion($codigo,$lista,$sede,$sector,$area,$tipo,$dia1,$dia2,$dia3,$dia4,$dia5,$dia6,$dia7,$diaMes,$hini,$hfin,$observacion,$fecha);
		$rs = $ClsLis->exec_sql($sql);
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

function get_programacion($codigo, $lista)
{
	$ClsLis = new ClsLista();
	$result = $ClsLis->get_programacion($codigo, $lista);
	$arr_data = array();
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data['codigo'] = $row["pro_codigo"];
			$arr_data['sede'] = trim($row["pro_sede"]);
			$arr_data['sector'] = trim($row["pro_sector"]);
			$arr_data['sector_nombre'] = trim($row["sec_nombre"]);
			$arr_data['nivel'] = trim($row["are_nivel"]);
			$arr_data['area'] = trim($row["pro_area"]);
			$arr_data['tipo'] = trim($row["pro_tipo"]);
			$arr_data['hini'] = substr($row["pro_hini"], 0, -3);
			$arr_data['hfin'] = substr($row["pro_hfin"], 0, -3);
			$arr_data['observacion'] = trim($row["pro_observaciones"]);
			$arr_data['dia1'] = trim($row["pro_dia_1"]);
			$arr_data['dia2'] = trim($row["pro_dia_2"]);
			$arr_data['dia3'] = trim($row["pro_dia_3"]);
			$arr_data['dia4'] = trim($row["pro_dia_4"]);
			$arr_data['dia5'] = trim($row["pro_dia_5"]);
			$arr_data['dia6'] = trim($row["pro_dia_6"]);
			$arr_data['dia7'] = trim($row["pro_dia_7"]);
			$arr_data['diaMes'] = trim($row["pro_dia_mes"]);
			$arr_data['fechaUnica'] = cambia_fecha($row['pro_fecha']);
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_horarios($codigo, $lista),
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

function modificar_programacion($codigo, $lista,$sede,$sector,$area,$tipo,$dia1,$dia2,$dia3,$dia4,$dia5,$dia6,$dia7,$diaMes,$hini,$hfin,$observacion,$fecha)
{
	$ClsLis = new ClsLista();
	if ($codigo != "" &&  $lista != "" && $sede != "" && $sector != "" && $area != "" && $tipo != "" && $hini != "" && $hfin != "") {
		$sql = $ClsLis->modifica_programacion($codigo, $lista,$sede,$sector,$area,$tipo,$dia1,$dia2,$dia3,$dia4,$dia5,$dia6,$dia7,$diaMes,$hini,$hfin,$observacion,$fecha);
		$rs = $ClsLis->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro modificado satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		} else {
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => $sql
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
	$ClsLis = new ClsLista();
	$sql = $ClsLis->cambia_sit_programacion($codigo, $situacion);
	$rs = $ClsLis->exec_sql($sql);
	if ($rs == 1) {
		$arr_respuesta = array(
			"status" => true,
			"data" => [],
			"message" => "Programación eliminada exitosamente...!"
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

///////////////////// PREGUNTAS ///////////////////
function get_tabla_preguntas($codigo, $lista)
{
	$ClsLis = new ClsLista();
	$result = $ClsLis->get_pregunta($codigo, $lista, '', 1);
	if (is_array($result)) {
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_preguntas($codigo, $lista),
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

function grabar_pregunta($lista, $pregunta)
{
	$ClsLis = new ClsLista();
	if ($lista != "" && $pregunta != "") {
		$codigo = $ClsLis->max_pregunta();
		$codigo++; /// Maximo codigo de Version
		$sql = $ClsLis->insert_pregunta($codigo, $lista, $pregunta); /// Inserta Version
		$rs = $ClsLis->exec_sql($sql);
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

function situacion_pregunta($codigo, $situacion)
{
	$ClsLis = new ClsLista();
	$sql = $ClsLis->cambia_sit_pregunta($codigo, $situacion);
	$rs = $ClsLis->exec_sql($sql);
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

function get_pregunta($codigo)
{
	$ClsLis = new ClsLista();
	$result = $ClsLis->get_pregunta($codigo, '');
	$i = 0;
	$arr_data = array();
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data["codigo"] = trim($row["pre_codigo"]);
			$arr_data["pregunta"] = trim($row["pre_pregunta"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_listas($codigo, ''),
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

function modificar_pregunta($codigo,$lista,$pregunta)
{
	$ClsLis = new ClsLista();
	if ($codigo != "" && $pregunta != "") {
		$sql = $ClsLis->modifica_pregunta($codigo,$lista,$pregunta);
		$rs = $ClsLis->exec_sql($sql);
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
