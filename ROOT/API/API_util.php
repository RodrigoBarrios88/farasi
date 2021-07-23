<?php
ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cahce");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);
//--
include_once('html_fns_api.php');

header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header("Access-Control-Allow-Origin: *");

///API REQUEST
$request = $_REQUEST["request"];
$_REQUEST = str_replace("undefined", "", $_REQUEST); ///valida campos "undefined" desde javascript

if($request != ""){
	switch($request){
		case "sedes":
			$usuario = $_REQUEST["usuario"];
			API_get_sedes($usuario);
			break;
		case "categorias":
			$usuario = $_REQUEST["usuario"];
			API_get_categorias($usuario);
			break;
		case "categoriasppm":
			$usuario = $_REQUEST["usuario"];
			API_get_categorias_ppm($usuario);
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

function API_get_sedes($usuario){
	$ClsUsu = new ClsUsuario();
	$result = $ClsUsu->get_usuario_sede("",$usuario,"");
	if(is_array($result)) {
		$i = 0;
		foreach ($result as $row){
			$arr_data[$i]['codigo'] = trim($row["sed_codigo"]);
			$arr_data[$i]['nombre'] = trim($row["sed_nombre"]);
			$i++;
		}
		$payload = array(
			"status" => true,
			"data" => $arr_data,
			"message" => "");
		echo json_encode($payload);
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "No se registran datos...");
		echo json_encode($payload);
	}

}


function API_get_categorias($usuario){
	$ClsUsu = new ClsUsuario();
	$result = $ClsUsu->get_usuario_categoria("",$usuario,"");
	if(is_array($result)) {
		$i = 0;
		foreach ($result as $row){
			$arr_data[$i]['codigo'] = trim($row["cat_codigo"]);
			$arr_data[$i]['nombre'] = trim($row["cat_nombre"]);
			$i++;
		}
		$payload = array(
			"status" => true,
			"data" => $arr_data,
			"message" => "");
		echo json_encode($payload);
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "No se registran datos...");
		echo json_encode($payload);
	}

}


function API_get_categorias_ppm(){
	$ClsCat = new ClsCategoria();
	$result = $ClsCat->get_categoria_ppm('','',1);
	if(is_array($result)) {
		$i = 0;
		foreach ($result as $row){
			$arr_data[$i]['codigo'] = trim($row["cat_codigo"]);
			$arr_data[$i]['nombre'] = trim($row["cat_nombre"]);
			$i++;
		}
		$payload = array(
			"status" => true,
			"data" => $arr_data,
			"message" => "");
		echo json_encode($payload);
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "No se registran datos...");
		echo json_encode($payload);
	}

}
