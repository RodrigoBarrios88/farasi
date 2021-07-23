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
	case "estadisticas":
		$encuesta = $_REQUEST["encuesta"];
		$pregunta = $_REQUEST["pregunta"];
		$fini = $_REQUEST["desde"];
		$ffin = $_REQUEST["hasta"];
		estadistica_pregunta($encuesta,$pregunta,$fini,$ffin);
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

function estadistica_pregunta($encuesta,$pregunta,$fini,$ffin){
   $ClsRes = new ClsEncuestaResolucion();
   
   if($encuesta != "" && $pregunta != ""){
		$resul = $ClsRes->get_ejecucion_respuestas('',$encuesta,$pregunta,'',$fini,$ffin);
		
		
		if($tipo_respuesta == 1){
			$r1 = ($r1 == "")?0:$r1;
			$r2 = ($r2 == "")?0:$r2;
			$r3 = ($r3 == "")?0:$r3;
			$r4 = ($r4 == "")?0:$r4;
			$r5 = ($r5 == "")?0:$r5;
			$r6 = ($r6 == "")?0:$r6;
			$r7 = ($r7 == "")?0:$r7;
			$r8 = ($r8 == "")?0:$r8;
			$r9 = ($r9 == "")?0:$r9;
			$r10 = ($r10 == "")?0:$r10;
			
			$json.='{y: "Valor1", x: "'.$r1.'"},{y: "Valor2", x: "'.$r2.'"},{y: "Valor3", x: "'.$r3.'"},{y: "Valor4", x: "'.$r4.'"},{y: "Valor5", x: "'.$r5.'"}';
		}else if($tipo_respuesta == 2){
			$r1 = ($r1 == "")?0:$r1;
			$r5 = ($r5 == "")?0:$r5;
			$json.='{y: "Verdadero", x: "'.$r5.'"},{y: "Falso", x: "'.$r1.'"}';
		}
		
		if($rs == 1){
			$arr_respuesta = array(
				"status" => true,
				//"sql" => $sql,
				"data" => [],
				"message" => "Respuesta agregada con exito..."
			);
			echo json_encode($arr_respuesta);
		}else{
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => "Error en la ejecución..."
			);
			echo json_encode($arr_respuesta);
		}	
	}
}?>