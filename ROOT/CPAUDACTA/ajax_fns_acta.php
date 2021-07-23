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
include_once('html_fns_acta.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "grabar":
		$ejecucion = $_REQUEST["ejecucion"];
		$auditoria = $_REQUEST["auditoria"];
		$programacion = $_REQUEST["programacion"];
		$fini = $_REQUEST["fini"];
		$hini = $_REQUEST["hini"];
		$ffin = $_REQUEST["ffin"];
		$hfin = $_REQUEST["hfin"];
		$observaciones = $_REQUEST["observaciones"];
		grabar_acta($ejecucion,$auditoria,$programacion,$fini,$hini,$ffin,$hfin,$observaciones);
		break;
	default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

////////////////// EJECUCION /////////////////////////

function grabar_acta($ejecucion,$auditoria,$programacion,$fini,$hini,$ffin,$hfin,$observaciones){
   $ClsEje = new ClsEjecucion();
   
   if($auditoria != "" && $programacion != "" && $ejecucion != ""){
		$fechorini = "$fini $hini";
		$fechorfin = "$ffin $hfin";
		$sql = $ClsEje->insert_acta($ejecucion,$auditoria,$programacion,$observaciones,$fechorini,$fechorfin);
		$rs = $ClsEje->exec_sql($sql);
		if($rs == 1){
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsEje->encrypt($ejecucion, $usu);
			$arr_respuesta = array(
				"status" => true,
				"hashkey" => $hashkey,
				"message" => "Respuesta agregada con exito..."
			);
			echo json_encode($arr_respuesta);
		}else{
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Error en la ejecucion... $sql"
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


function situacion_ejecucion($ejecucion,$situacion,$obs){ 
	$ClsEje = new ClsEjecucion();
	$sql = $ClsEje->cambia_situacion_ejecucion($ejecucion,$situacion);
	$sql.= $ClsEje->insert_ejecucion_situacion($ejecucion,$situacion,$obs);
	$rs = $ClsEje->exec_sql($sql);
	if($rs == 1){
		$arr_respuesta = array(
			"status" => true,
			"situacion" => intval($situacion),
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