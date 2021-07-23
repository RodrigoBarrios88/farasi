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
include_once('html_fns_ejecucion.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "responderAprobacion":
		$auditoria = $_REQUEST["auditoria"];
		$pregunta = $_REQUEST["pregunta"];
		$ejecucion = $_REQUEST["ejecucion"];
		$resultado = $_REQUEST["resultado"];
		$observacion = $_REQUEST["observacion"];
		responder_aprobacion($auditoria,$pregunta,$ejecucion,$resultado,$observacion);
		break;
	case "disolverHallazgo":
		$auditoria = $_REQUEST["auditoria"];
		$pregunta = $_REQUEST["pregunta"];
		$ejecucion = $_REQUEST["ejecucion"];
		$seccion = $_REQUEST["seccion"];
		$tipo = $_REQUEST["tipo"];
		$peso = $_REQUEST["peso"];
		$respuesta = $_REQUEST["respuesta"];
		$observacion = $_REQUEST["observacion"];
		$justificacion = $_REQUEST["justificacion"];
		disolver_hallazgo($auditoria,$pregunta,$ejecucion,$seccion,$tipo,$peso,$respuesta,$observacion,$justificacion);
		break;
	default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

////////////////// APROBAR /////////////////////////
function responder_aprobacion($auditoria,$pregunta,$ejecucion,$resultado,$observacion){
   $ClsEje = new ClsEjecucion();
   
   if($auditoria != "" && $pregunta != "" && $ejecucion != ""){
      $usuario = $_SESSION["codigo"];
      $sql = $ClsEje->insert_ejecucion_revision($auditoria,$pregunta,$ejecucion,$resultado,$observacion,$usuario);
		$rs = $ClsEje->exec_sql($sql);
      if($rs == 1){
         $arr_respuesta = array(
				"status" => true,
				"data" => [],
				"resultado" => $resultado,
				"message" => "Respuesta agregada con éxito..."
			);
			echo json_encode($arr_respuesta);
      }else{
         $arr_respuesta = array(
				"status" => false,
				"sql" => $sql,
				"data" => [],
				"message" => "Error en la ejecución..."
			);
			echo json_encode($arr_respuesta);
      }	
	}
}


function disolver_hallazgo($auditoria,$pregunta,$ejecucion,$seccion,$tipo,$peso,$ponderacion,$observacion,$justificacion){
   $ClsEje = new ClsEjecucion();
   
	if($auditoria != "" && $pregunta != "" && $ejecucion != "" && $tipo != ""){
      $sql = $ClsEje->insert_respuesta($auditoria,$pregunta,$ejecucion,$seccion,$tipo,$peso,1,$ponderacion,$justificacion);
      $sql.= $ClsEje->update_respuesta($auditoria,$pregunta,$ejecucion,$seccion,$observacion);
      $sql.= $ClsEje->insert_disolucion_hallazgo($auditoria,$pregunta,$ejecucion,$justificacion);
		$rs = $ClsEje->exec_sql($sql);
      if($rs == 1){
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"resultado" => $resultado,
				"message" => "Disolución ejecutada con éxito..."
			);
			echo json_encode($arr_respuesta);
      }else{
         $arr_respuesta = array(
				"status" => false,
				"sql" => $sql,
				"data" => [],
				"message" => "Error en la ejecución..."
			);
      }	
	}
}?>