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
include_once('html_fns_cuestionario.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "tabla":
		$codigo = $_REQUEST["codigo"];
		$encuesta = $_REQUEST["encuesta"];
		get_tabla($codigo,$encuesta);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get_invitacion($codigo);
		break;
	case "grabar":
		$encuesta = $_REQUEST["encuesta"];
		$cliente = $_REQUEST["cliente"];
		$correo = $_REQUEST["correo"];
		$url = $_REQUEST["url"];
		$observaciones = $_REQUEST["obs"];
		grabar_invitacion($encuesta,$cliente,$correo,$url,$observaciones);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$encuesta = $_REQUEST["encuesta"];
		$cliente = $_REQUEST["cliente"];
		$correo = $_REQUEST["correo"];
		$url = $_REQUEST["url"];
		$observaciones = $_REQUEST["obs"];
		modificar_invitacion($codigo,$encuesta,$cliente,$correo,$url,$observaciones);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_invitacion($codigo,$situacion);
		break;
	default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

////////////////// STATUS /////////////////////////
function get_tabla($codigo,$encuesta){ 
	$ClsEnc = new ClsEncuesta();
	$result = $ClsEnc->get_invitacion($codigo,$encuesta);
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_invitacion($codigo,$encuesta),
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

function get_invitacion($codigo){ 
	$ClsEnc = new ClsEncuesta();
	$result = $ClsEnc->get_invitacion($codigo,'');
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["inv_codigo"]);
			$arr_data["encuesta"] = trim($row["inv_encuesta"]);
			$arr_data["cliente"] = trim($row["inv_cliente"]);
			$arr_data["correo"] = trim($row["inv_correo"]);
			$arr_data["url"] = trim($row["inv_url"]);
			$arr_data["obs"] = trim($row["inv_observaciones"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_invitacion($codigo,''),
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


function grabar_invitacion($encuesta,$cliente,$correo,$url,$observaciones){
	$ClsEnc = new ClsEncuesta();
	if($encuesta != ""  && $cliente != "" && $correo != "" && $url != ""){
		$codigo = $ClsEnc->max_invitacion();
		$codigo++; /// Maximo codigo de Cuestionario
		$sql = $ClsEnc->insert_invitacion($codigo,$encuesta,$cliente,$correo,$url,$observaciones);
		$rs = $ClsEnc->exec_sql($sql);
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


function modificar_invitacion($codigo,$encuesta,$cliente,$correo,$url,$observaciones){
	$ClsEnc = new ClsEncuesta();
	if($codigo != ""  && $encuesta != ""  && $cliente != "" && $correo != "" && $url != ""){
		$sql = $ClsEnc->modifica_invitacion($codigo,$encuesta,$cliente,$correo,$url,$observaciones);
		$rs = $ClsEnc->exec_sql($sql);
		if($rs == 1){
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Registro actualizado satisfactoriamente...!"
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


function situacion_invitacion($codigo,$situacion){ 
	$ClsEnc = new ClsEncuesta();
	$sql = $ClsEnc->cambia_situacion_invitacion($codigo,$situacion);
	$rs = $ClsEnc->exec_sql($sql);
	if($rs == 1){
		$arr_respuesta = array(
			"status" => true,
			"data" => [],
			"message" => "Situación actualizada satisfactoriamente...!"
		);
		
		echo json_encode($arr_respuesta);
	}else{
		$arr_respuesta = array(
			"status" => false,
			"sql" => $sql,
			"data" => [],
			"message" => "Error en la ejecución"
		);
		
		echo json_encode($arr_respuesta);
	}
}?>