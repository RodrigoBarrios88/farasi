<?php
ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cache");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);
//--
include_once('html_fns_escalones.php');

header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header("Access-Control-Allow-Origin: *");

///API REQUEST
$request = $_REQUEST["request"];
$_REQUEST = str_replace("undefined", "", $_REQUEST); ///valida campos "undefined" desde javascript

if($request != ""){
	switch($request){
		case "nuevoEscalon":
			$categoria = $_REQUEST["categoria"];
			nuevoEscalon($categoria);
			break;
		case "buscaEscalon":
			$categoria = $_REQUEST["categoria"];
			$codigo = $_REQUEST["escalon"];
			buscaEscalon($categoria,$codigo);
			break;
		case "updateEscalon":
			$categoria = $_REQUEST["categoria"];
			$codigo = $_REQUEST["escalon"];
			$nombre = $_REQUEST["nombre"];
			$posicion = $_REQUEST["posicion"];
			updateEscalon($categoria,$codigo,$posicion,$nombre);
			break;
		case "deleteEscalon":
			$categoria = $_REQUEST["categoria"];
			$codigo = $_REQUEST["escalon"];
			deleteEscalon($categoria,$codigo);
			break;
		///////////////////////////////////////
		case "nuevoDetalle":
			$categoria = $_REQUEST["categoria"];
			$escalon = $_REQUEST["escalon"];
			nuevoDetalle($categoria,$escalon);
			break;
		case "buscaDetalle":
			$categoria = $_REQUEST["categoria"];
			$codigo = $_REQUEST["detalle"];
			$escalon = $_REQUEST["escalon"];
			buscaDetalle($categoria,$codigo,$escalon);
			break;
		case "updateDetalle":
			$categoria = $_REQUEST["categoria"];
			$codigo = $_REQUEST["detalle"];
			$escalon = $_REQUEST["escalon"];
			$nombre = $_REQUEST["nombre"];
			$mail = $_REQUEST["mail"];
			updateDetalle($categoria,$codigo,$escalon,$nombre,$mail);
			break;
		case "deleteDetalle":
			$categoria = $_REQUEST["categoria"];
			$codigo = $_REQUEST["detalle"];
			$escalon = $_REQUEST["escalon"];
			deleteDetalle($categoria,$codigo,$escalon);
			break;
		
		default:
			$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Parametros invalidos...");
			echo json_encode($payload);
			break;
	}
}else{
	//devuelve un mensaje de manejo de errores
	$payload = array(
		"status" => false,
		"data" => [],
		"message" => "Delimite el tipo de consulta a realizar...");
		echo json_encode($payload);
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////// FUNCIONES Y CONSULTAS ////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////// Escalones ////////////////////////////////////////////////////
function nuevoEscalon($categoria){
	$ClsEsc = new ClsEscalon();
	$codigo = $ClsEsc->max_escalon();
	$codigo++;
	$sql = $ClsEsc->insert_escalon($codigo,$categoria,0,"");
	$rs = $ClsEsc->exec_sql($sql);
	if($rs == 1) {
		
		$data = edit_escalon($codigo,$categoria);
		
		$payload = array(
			"status" => true,
			"data" => $data,
			"message" => "Escal\u00F3n creado satisfactoriamente...");
		echo json_encode($payload);
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Error en la transacci\u00F3n");
		echo json_encode($payload);
	}

}


function buscaEscalon($categoria,$codigo){
	$ClsEsc = new ClsEscalon();
	$result = $ClsEsc->get_escalon($codigo);
	//$respuesta->alert("$cont");
	if(is_array($result)){
		$data = edit_escalon($codigo,$categoria);
		
		$payload = array(
			"status" => true,
			"data" => $data,
			"message" => "");
		echo json_encode($payload);
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Este escalon no existe...");
		echo json_encode($payload);
	}
}


function updateEscalon($categoria,$codigo,$posicion,$nombre){
	$ClsEsc = new ClsEscalon();if($nombre != "" && $posicion != ""){
		$sql = $ClsEsc->modifica_escalon($codigo,$posicion,$nombre);
		$rs = $ClsEsc->exec_sql($sql);
		if($rs == 1) {
			$data = edit_escalon('',$categoria);
			
			$payload = array(
				"status" => true,
				"data" => $data,
				"message" => "Escal\u00F3n actualizado satisfactoriamente...");
			echo json_encode($payload);
		}else{
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => false,
				"data" => [],
				"alert" => $sql,
				"message" => "Error en la transacci\u00F3n...");
			echo json_encode($payload);
		}
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Uno de los datos está vacio...");
		echo json_encode($payload);
	}

}


function deleteEscalon($categoria,$codigo){
	$ClsEsc = new ClsEscalon();
	$sql = $ClsEsc->cambia_sit_escalon($codigo,0);
	$rs = $ClsEsc->exec_sql($sql);
	if($rs == 1) {
		$data = edit_escalon('',$categoria);
		
		$payload = array(
			"status" => true,
			"data" => $data,
			"alert" => $sql,
			"message" => "Escal\u00F3n eliminado satisfactoriamente...");
		echo json_encode($payload);
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Error en la transacci\u00F3n");
		echo json_encode($payload);
	}

}


////////////////////////////////////////////////// Detalle de Escalones ////////////////////////////////////////////////////
function nuevoDetalle($categoria,$escalon){
	$ClsEsc = new ClsEscalon();
	$codigo = $ClsEsc->max_detalle_escalon($escalon);
	$codigo++;
	$sql = $ClsEsc->insert_detalle_escalon($codigo,$escalon,'','');
	$rs = $ClsEsc->exec_sql($sql);
	if($rs == 1) {
		
		$data = tabla_detalles($codigo,$escalon,$categoria);
		
		$payload = array(
			"status" => true,
			"data" => $data,
			"message" => "Nitificaci\u00F3n creada satisfactoriamente...");
		echo json_encode($payload);
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Error en la transacci\u00F3n");
		echo json_encode($payload);
	}

}


function buscaDetalle($categoria,$codigo,$escalon){
	$ClsEsc = new ClsEscalon();
	$result = $ClsEsc->get_detalle_escalon($codigo,$escalon);
	if(is_array($result)){
		$data = tabla_detalles($codigo,$escalon,$categoria);
		
		$payload = array(
			"status" => true,
			"data" => $data,
			"message" => "");
		echo json_encode($payload);
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Esta nitificaci\u00F3n no existe...");
		echo json_encode($payload);
	}
}


function updateDetalle($categoria,$codigo,$escalon,$nombre,$mail){
	$ClsEsc = new ClsEscalon();if($nombre != "" && $mail != ""){
		$sql = $ClsEsc->modifica_detalle_escalon($codigo,$escalon,$nombre,$mail);
		$rs = $ClsEsc->exec_sql($sql);
		if($rs == 1) {
			$data = tabla_detalles('',$escalon,$categoria);
			
			$payload = array(
				"status" => true,
				"data" => $data,
				"message" => "Nitificaci\u00F3n actualizado satisfactoriamente...");
			echo json_encode($payload);
		}else{
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => false,
				"data" => [],
				"alert" => $sql,
				"message" => "Error en la transacci\u00F3n...");
			echo json_encode($payload);
		}
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Uno de los datos está vacio...");
		echo json_encode($payload);
	}

}


function deleteDetalle($categoria,$codigo,$escalon){
	$ClsEsc = new ClsEscalon();
	$sql = $ClsEsc->cambia_sit_detalle_escalon($codigo,$escalon,0);
	$rs = $ClsEsc->exec_sql($sql);
	if($rs == 1) {
		$data = tabla_detalles('',$escalon,$categoria);
		
		$payload = array(
			"status" => true,
			"data" => $data,
			"alert" => $sql,
			"message" => "Nitificaci\u00F3n eliminada satisfactoriamente...");
		echo json_encode($payload);
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Error en la transacci\u00F3n");
		echo json_encode($payload);
	}

}