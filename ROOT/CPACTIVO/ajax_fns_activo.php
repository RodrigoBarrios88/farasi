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
include_once('html_fns_activo.php');

$request = $_REQUEST["request"];
switch ($request) {
	case "tabla":
		$codigo = $_REQUEST["codigo"];
		get_tabla($codigo);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get_activo($codigo);
		break;
	case "grabar":
		$sede = $_REQUEST["sede"];
		$sector = $_REQUEST["sector"];
		$area = $_REQUEST["area"];
		$nombre = $_REQUEST["nombre"];
		$marca = $_REQUEST["marca"];
		$serie = $_REQUEST["serie"];
		$modelo = $_REQUEST["modelo"];
		$parte = $_REQUEST["parte"];
		$proveedor = $_REQUEST["proveedor"];
		$periodicidad = $_REQUEST["periodicidad"];
		$capacidad = $_REQUEST["capacidad"];
		$cantidad = $_REQUEST["cantidad"];
		$precioNuevo = $_REQUEST["precioNuevo"];
		$precioCompra = $_REQUEST["precioCompra"];
		$precioActual = $_REQUEST["precioActual"];
		$observaciones = $_REQUEST["observaciones"];
		grabar_activo($sede, $sector, $area, $nombre, $marca, $serie, $modelo, $parte, $proveedor, $periodicidad, $capacidad, $cantidad, $precioNuevo, $precioCompra, $precioActual, $observaciones);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$sede = $_REQUEST["sede"];
		$sector = $_REQUEST["sector"];
		$area = $_REQUEST["area"];
		$nombre = $_REQUEST["nombre"];
		$marca = $_REQUEST["marca"];
		$serie = $_REQUEST["serie"];
		$modelo = $_REQUEST["modelo"];
		$parte = $_REQUEST["parte"];
		$proveedor = $_REQUEST["proveedor"];
		$periodicidad = $_REQUEST["periodicidad"];
		$capacidad = $_REQUEST["capacidad"];
		$cantidad = $_REQUEST["cantidad"];
		$precioNuevo = $_REQUEST["precioNuevo"];
		$precioCompra = $_REQUEST["precioCompra"];
		$precioActual = $_REQUEST["precioActual"];
		$observaciones = $_REQUEST["observaciones"];
		modificar_activo($codigo, $sede, $sector, $area, $nombre, $marca, $serie, $modelo, $parte, $proveedor, $periodicidad, $capacidad, $cantidad, $precioNuevo, $precioCompra, $precioActual, $observaciones);
		break;
	case "grabar_falla":
		$activo = $_REQUEST["activo"];
		$falla = $_REQUEST["falla"];
		$fecha = $_REQUEST["fecha"];
		$hora = $_REQUEST["hora"];
		$situacion = $_REQUEST["situacion"];
		grabar_falla($activo, $falla, $fecha, $hora, $situacion);
		break;
	case "modificar_falla":
		$codigo = $_REQUEST["codigo"];
		$activo = $_REQUEST["activo"];
		$falla = $_REQUEST["falla"];
		$fecha = $_REQUEST["fecha"];
		$hora = $_REQUEST["hora"];
		$situacion = $_REQUEST["situacion"];
		modificar_falla($codigo, $activo, $falla, $fecha, $hora, $situacion);
		break;
	case "solucionar_falla":
		$codigo = $_REQUEST["codigo"];
		$activo = $_REQUEST["activo"];
		$fecha = $_REQUEST["fecha"];
		$hora = $_REQUEST["hora"];
		$comentario = $_REQUEST["comentario"];
		solucionar_falla($codigo, $activo, $fecha, $hora, $comentario);
		break;
	case "getArea":
		$area = $_REQUEST["area"];
		get_area($area);
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
	$ClsAct = new ClsActivo();
	$result = $ClsAct->get_activo($codigo);
	if (is_array($result)) {
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_activos(''),
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


function get_activo($codigo)
{
	$ClsAct = new ClsActivo();
	$result = $ClsAct->get_activo($codigo);
	$arr_data = array();
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data["codigo"] = trim($row["act_codigo"]);
			$arr_data["sede"] = trim($row["act_sede"]);
			$arr_data["secNombre"] = trim($row["sec_nombre"]);
			$arr_data["sector"] = trim($row["act_sector"]);
			$arr_data["area"] = trim($row["act_area"]);
			$arr_data["nivel"] = trim($row["are_nivel"]);
			$arr_data["nombre"] = trim($row["act_nombre"]);
			$arr_data["marca"] = trim($row["act_marca"]);
			$arr_data["serie"] = trim($row["act_serie"]);
			$arr_data["modelo"] = trim($row["act_modelo"]);
			$arr_data["parte"] = trim($row["act_parte"]);
			$arr_data["proveedor"] = trim($row["act_proveedor"]);
			$arr_data["periodicidad"] = trim($row["act_periodicidad"]);
			$arr_data["capacidad"] = trim($row["act_capacidad"]);
			$arr_data["cantidad"] = trim($row["act_cantidad"]);
			$arr_data["precioNuevo"] = trim($row["act_precio_nuevo"]);
			$arr_data["precioCompra"] = trim($row["act_precio_compra"]);
			$arr_data["precioActual"] = trim($row["act_precio_actual"]);
			$arr_data["observaciones"] = trim($row["act_observaciones"]);
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => trim(tabla_activos($codigo)),
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


function grabar_activo($sede, $sector, $area, $nombre, $marca, $serie, $modelo, $parte, $proveedor, $periodicidad, $capacidad, $cantidad, $precioNuevo, $precioCompra, $precioActual, $observaciones)
{
	$ClsAct = new ClsActivo();
	//trim a cadena
	$nombre = trim($nombre);
	$marca = trim($marca);
	$serie = trim($serie);
	$modelo = trim($modelo);
	$parte = trim($parte);
	$proveedor = trim($proveedor);
	$capacidad = trim($capacidad);
	$observaciones = trim($observaciones);
	//--------
	$precioNuevo = ($precioNuevo == "") ? 0 : $precioNuevo;
	$precioCompra = ($precioCompra == "") ? 0 : $precioCompra;
	$precioActual = ($precioActual == "") ? 0 : $precioActual;
	if ($nombre != "" && $marca != "" && $sede != "" && $sector != "" && $area != "" && $cantidad != "") {
		$codigo = $ClsAct->max_activo();
		$codigo++; /// Maximo codigo de Activo
		$sql = $ClsAct->insert_activo($codigo, $sede, $sector, $area, $nombre, $marca, $serie, $modelo, $parte, $proveedor, $periodicidad, $capacidad, $cantidad, $precioNuevo, $precioCompra, $precioActual, $observaciones); /// Inserta Activo
		$rs = $ClsAct->exec_sql($sql);
		if ($rs == 1) {
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

function modificar_activo($codigo, $sede, $sector, $area, $nombre, $marca, $serie, $modelo, $parte, $proveedor, $periodicidad, $capacidad, $cantidad, $precioNuevo, $precioCompra, $precioActual, $observaciones)
{
	$ClsAct = new ClsActivo();
	//trim a cadena
	$nombre = trim($nombre);
	$marca = trim($marca);
	$serie = trim($serie);
	$modelo = trim($modelo);
	$parte = trim($parte);
	$proveedor = trim($proveedor);
	$capacidad = trim($capacidad);
	$observaciones = trim($observaciones);
	//--------
	$precioNuevo = ($precioNuevo == "") ? 0 : $precioNuevo;
	$precioCompra = ($precioCompra == "") ? 0 : $precioCompra;
	$precioActual = ($precioActual == "") ? 0 : $precioActual;

	if ($codigo != "" && $marca != "" && $sede != "" && $sector != "" && $area != "" && $cantidad != "") {
		$sql = $ClsAct->modifica_activo($codigo, $sede, $sector, $area, $nombre, $marca, $serie, $modelo, $parte, $proveedor, $periodicidad, $capacidad, $cantidad, $precioNuevo, $precioCompra, $precioActual, $observaciones);
		$rs = $ClsAct->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro modificado satisfactoriamente...!"
			);
		} else {
			$arr_respuesta = array(
				"status" => false,
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

function grabar_falla($activo, $falla, $fecha, $hora, $situacion)
{
	$ClsAct = new ClsActivo();
	$ClsFal = new ClsFalla();
	if ($activo != "") {
		$codigo = $ClsFal->max_falla($activo);
		$codigo++; /// Maximo codigo de Activo
		$fecha = "$fecha $hora";
		$sql = $ClsFal->insert_falla($codigo, $activo, $falla, $fecha);
		$sql .= $ClsAct->cambia_sit_activo($activo, $situacion);
		$rs = $ClsFal->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Falla reportada exitosamente...!"
			);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Error en la transacci\u00F3n"
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Error en la transacci\u00F3n"
		);
	}
	echo json_encode($arr_respuesta);
}

function modificar_falla($codigo, $activo, $falla, $fecha, $hora, $situacion)
{
	$ClsAct = new ClsActivo();
	$ClsFal = new ClsFalla();
	if ($activo != "") {
		$fecha = "$fecha $hora";
		$sql = $ClsFal->modifica_falla($codigo, $activo, $falla, $fecha);
		$sql .= $ClsAct->cambia_sit_activo($activo, $situacion);
		$rs = $ClsFal->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Falla modificada exitosamente...!"
			);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Error en la transacci\u00F3n"
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Error en la transacci\u00F3n"
		);
	}
	echo json_encode($arr_respuesta);
}

function solucionar_falla($codigo, $activo, $fecha, $hora, $comentario)
{
	$ClsAct = new ClsActivo();
	$ClsFal = new ClsFalla();
	if ($activo != "") {
		$fecha = "$fecha $hora";
		$sql = $ClsFal->cambia_sit_falla($codigo, $activo, 2, $fecha, $comentario);
		$sql .= $ClsAct->cambia_sit_activo($activo, 1);
		$rs = $ClsFal->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Falla solucionada exitosamente...!"
			);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Error en la transacci\u00F3n"
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Error en la transacci\u00F3n"
		);
	}
	echo json_encode($arr_respuesta);
}
//--
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
