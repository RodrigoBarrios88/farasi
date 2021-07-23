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
include_once('html_fns_moneda.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "tabla_cambio":
		$codigo = $_REQUEST["codigo"];
		get_tabla_cambio($codigo);
		break;
	case "tabla_monedas":
		get_tabla_moneda();
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get_moneda($codigo);
		break;
	case "grabar":
		$descripcion = $_REQUEST["descripcion"];
		$simbolo = $_REQUEST["simbolo"];
		$pais = $_REQUEST["pais"];
		$cambio = $_REQUEST["cambio"];
		$compra = $_REQUEST["compra"];
		$venta = $_REQUEST["venta"];
		grabar_moneda($descripcion,$simbolo,$pais,$cambio,$compra,$venta);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$descripcion = $_REQUEST["descripcion"];
		$simbolo = $_REQUEST["simbolo"];
		$pais = $_REQUEST["pais"];
		$cambio = $_REQUEST["cambio"];
		$compra = $_REQUEST["compra"];
		$venta = $_REQUEST["venta"];
		modificar_moneda($codigo,$descripcion,$simbolo,$pais,$cambio,$compra,$venta);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_moneda($codigo,$situacion);
		break;
	case "tasa_cambio":
		$moneda = $_REQUEST["moneda"];
		$cambio = $_REQUEST["cambio"];
		$compra = $_REQUEST["compra"];
		$venta = $_REQUEST["venta"];
		tasa_cambio($moneda,$cambio,$compra,$venta);
		break;
	default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

////////////////// DEPARTAMENTOS /////////////////////////

function get_tabla_cambio($codigo){ 
	$ClsMon = new ClsMoneda();
	$result = $ClsMon->get_moneda($codigo);
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_historial_cambio($codigo),
			"message" => ""
		);
	}else{
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Aún no hay datos registrados..."
		);
	}
	echo json_encode($arr_respuesta);
}

function get_tabla_moneda(){ 
	$ClsMon = new ClsMoneda();
	$result = $ClsMon->get_moneda('');
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_monedas(),
			"message" => ""
		);
	}else{
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Aún no hay datos registrados..."
		);
	}
	echo json_encode($arr_respuesta);
}


function get_moneda($codigo){ 
	$ClsMon = new ClsMoneda();
	$result = $ClsMon->get_moneda($codigo);
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["mon_codigo"]);
			$arr_data["descripcion"] = trim($row["mon_descripcion"]);
			$arr_data["simbolo"] = trim($row["mon_simbolo"]);
			$arr_data["pais"] = trim($row["mon_pais"]);
			$arr_data["cambio"] = trim($row["mon_cambio"]);
			$arr_data["compra"] = trim($row["mon_compra"]);
			$arr_data["venta"] = trim($row["mon_venta"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"message" => ""
		);
	}else{
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Aún no hay datos registrados..."
		);
	}
	echo json_encode($arr_respuesta);
}


function grabar_moneda($descripcion,$simbolo,$pais,$cambio,$compra,$venta){
	$ClsMon = new ClsMoneda();
	if($descripcion != "" && $simbolo != "" && $pais != "" && $cambio != "" && $compra != "" && $venta != ""){
		$codigo = $ClsMon->max_moneda();
		$codigo++; /// Maximo codigo de Version
		$sql = $ClsMon->insert_moneda($codigo,$descripcion,$simbolo,$pais,$cambio,$compra,$venta); /// Inserta Version
		$sql.= $ClsMon->insert_his_cambio($codigo,$cambio,$compra,$venta);
		$rs = $ClsMon->exec_sql($sql);
		if($rs == 1){
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro guardado satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		}else{
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..."
			);
			echo json_encode($arr_respuesta);
		}
	}else{
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);
		echo json_encode($arr_respuesta);
	}
}


function modificar_moneda($codigo,$descripcion,$simbolo,$pais,$cambio,$compra,$venta){
	$ClsMon = new ClsMoneda();
	if($codigo != "" && $descripcion != "" && $simbolo != "" && $pais != "" && $cambio != "" && $compra != "" && $venta != ""){
		$sql = $ClsMon->update_moneda($codigo,$descripcion,$simbolo,$pais);
		$sql.= $ClsMon->update_cambio_moneda($codigo,$cambio,$compra,$venta);
		$sql.= $ClsMon->insert_his_cambio($codigo,$cambio,$compra,$venta);
		$rs = $ClsMon->exec_sql($sql);
		if($rs == 1){
			$arr_respuesta = array(
				"status" => true,
				//"sql" => $sql,
				"data" => [],
				"message" => "Registro actualizados satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		}else{
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..."
			);
			echo json_encode($arr_respuesta);
		}
	}else{
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);
		echo json_encode($arr_respuesta);
	}
}


function situacion_moneda($codigo,$situacion){ 
	$ClsMon = new ClsMoneda();
	$sql = $ClsMon->cambia_sit_moneda($codigo,$situacion);
	$rs = $ClsMon->exec_sql($sql);
	if($rs == 1){
		$arr_respuesta = array(
			"status" => true,
			"data" => [],
			"message" => "Cambio de situación con éxito...!"
		);
		echo json_encode($arr_respuesta);
	}else{
		$arr_respuesta = array(
			"status" => false,
			"sql" => $sql,
			"data" => [],
			"message" => "Error en la ejecución.."
		);
		echo json_encode($arr_respuesta);
	}
}


function tasa_cambio($moneda,$cambio,$compra,$venta){
	$ClsMon = new ClsMoneda();
	if($moneda != "" && $cambio != "" && $compra != "" && $venta != ""){
		$sql = $ClsMon->update_cambio_moneda($moneda,$cambio,$compra,$venta);
		$sql.= $ClsMon->insert_his_cambio($moneda,$cambio,$compra,$venta);
		$rs = $ClsMon->exec_sql($sql);
		if($rs == 1){
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro guardado satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		}else{
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacción..."
			);
			echo json_encode($arr_respuesta);
		}
	}else{
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);
		echo json_encode($arr_respuesta);
	}
}?>