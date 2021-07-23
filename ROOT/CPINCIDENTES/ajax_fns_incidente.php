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
include_once('html_fns_incidente.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "tabla":
		$incidente = $_REQUEST["incidente"];
		get_tabla($incidente);
		break;
	case "grabar":
		$incidente = $_REQUEST["incidente"];
		$categoria = $_REQUEST["categoria"];
		$prioridad = $_REQUEST["prioridad"];
		$nombre = $_REQUEST["nombre"];
		$usuarios = $_REQUEST["usuarios"];
		grabar_incidente($incidente,$categoria,$prioridad,$nombre,$usuarios);
		break;
	case "situacion":
		$incidente = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_incidente($incidente,$situacion);
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
function get_tabla($incidente){ 
	$ClsInc = new ClsIncidente();
	$result = $ClsInc->get_incidente($incidente);
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_incidente(''),
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


function grabar_incidente($incidente,$categoria,$prioridad,$nombre,$usuarios){
	$ClsInc = new ClsIncidente();
	if($categoria != ""  && $nombre != "" && $prioridad != ""){
		if($incidente == ""){
			$incidente = $ClsInc->max_incidente();
			$incidente++; /// Maximo codigo de Incidente
			$sql = $ClsInc->insert_incidente($incidente,$categoria,$prioridad,$nombre); /// Inserta Incidente
		}else{
			$sql = $ClsInc->modifica_incidente($incidente,$categoria,$prioridad,$nombre); /// actualizar Incidente
		}
		//// asignacion ///
		$codigo = $ClsInc->max_usuario_incidente($incidente);
		$codigo++;
		$sql.= $ClsInc->delete_usuario_incidente($incidente);
		//crea array
		$arrusuarios = explode(",",$usuarios);
		$count = count($arrusuarios); //cuenta cuantas vienen en el array
		for($i = 0; $i < $count; $i++){
			$usuario = $arrusuarios[$i];
			$sql.= $ClsInc->insert_usuario_incidente($codigo,$incidente,$usuario);
			$codigo++;
		}
		$rs = $ClsInc->exec_sql($sql);
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


function situacion_incidente($incidente,$situacion){ 
	$ClsInc = new ClsIncidente();
	$sql = $ClsInc->cambia_situacion_incidente($incidente,$situacion);
	$rs = $ClsInc->exec_sql($sql);
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