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
		$encuesta = $_REQUEST["encuesta"];
		get_seccion($codigo,$encuesta);
		break;
	case "grabar":
		$encuesta = $_REQUEST["encuesta"];
		$numero = $_REQUEST["numero"];
		$titulo = $_REQUEST["titulo"];
		$proposito = $_REQUEST["proposito"];
		grabar_seccion($encuesta,$numero,$titulo,$proposito);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$encuesta = $_REQUEST["encuesta"];
		$numero = $_REQUEST["numero"];
		$titulo = $_REQUEST["titulo"];
		$proposito = $_REQUEST["proposito"];
		modificar_seccion($codigo,$encuesta,$numero,$titulo,$proposito);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$encuesta = $_REQUEST["encuesta"];
		$situacion = $_REQUEST["situacion"];
		situacion_seccion($codigo,$encuesta,$situacion);
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
	$result = $ClsEnc->get_secciones($codigo,$encuesta);
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_secciones($codigo,$encuesta),
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

function get_seccion($codigo,$encuesta){ 
	$ClsEnc = new ClsEncuesta();
	$result = $ClsEnc->get_secciones($codigo,$encuesta);
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["sec_codigo"]);
			$arr_data["encuesta"] = trim($row["sec_encuesta"]);
			$arr_data["numero"] = trim($row["sec_numero"]);
			$arr_data["titulo"] = trim($row["sec_titulo"]);
			$arr_data["proposito"] = trim($row["sec_proposito"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_secciones($codigo,$encuesta),
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


function grabar_seccion($encuesta,$numero,$titulo,$proposito){
	$ClsEnc = new ClsEncuesta();
	if($encuesta != ""  && $numero != "" && $titulo != ""){
		$codigo = $ClsEnc->max_secciones($encuesta);
		$codigo++; /// Maximo codigo de Seccion
		$sql = $ClsEnc->insert_secciones($codigo,$encuesta,$numero,$titulo,$proposito); /// Inserta Seccion
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
				"sql" => $sql,
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


function modificar_seccion($codigo,$encuesta,$numero,$titulo,$proposito){
	$ClsEnc = new ClsEncuesta();
	if($codigo != ""  && $encuesta != ""  && $numero != "" && $titulo != ""){
		$sql = $ClsEnc->modifica_secciones($codigo,$encuesta,$numero,$titulo,$proposito);
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
				"sql" => $sql,
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


function situacion_seccion($codigo,$encuesta,$situacion){ 
	$ClsEnc = new ClsEncuesta();
	$sql = $ClsEnc->cambia_situacion_seccion($codigo,$encuesta,$situacion);
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