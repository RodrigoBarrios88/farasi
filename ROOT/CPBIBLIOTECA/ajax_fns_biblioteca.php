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
include_once('html_fns_biblioteca.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "biblioteca":
		$categoria = $_REQUEST['categoria'];		
		tabla_biblioteca($categoria);
		break;
	case "tabla":
		$codigo = $_REQUEST["codigo"];
		$tipo = $_REQUEST["tipo"];
		get_tabla($codigo,$tipo);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get_biblioteca($codigo);
		break;
	case "grabar":
		$categoria = $_REQUEST["categoria"];
		$codint = $_REQUEST["codint"];
		$titulo = $_REQUEST["titulo"];
		$descripcion = $_REQUEST["descripcion"];
		$fecvence = $_REQUEST["fecvence"];
		$usuario = $_REQUEST["usuario"];
		grabar_biblioteca($categoria,$codint,$titulo,$descripcion,$fecvence,$usuario);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$categoria = $_REQUEST["categoria"];
		$codint = $_REQUEST["codint"];
		$titulo = $_REQUEST["titulo"];
		$descripcion = $_REQUEST["descripcion"];
		$fecvence = $_REQUEST["fecvence"];
		$usuario = $_REQUEST["usuario"];
		modificar_biblioteca($codigo,$categoria,$codint,$titulo,$descripcion,$fecvence,$usuario);
		break;
	case "delete_documento":
		$codigo = $_REQUEST["codigo"];
		delete_documento($codigo);
		break;	
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_biblioteca($codigo,$situacion);
		break;
	/// VERSION ///
	case "version":
		$documento = $_REQUEST['codigo'];
		$version = $_REQUEST['version'];
		$descripcion = $_REQUEST['descripcion'];
		$fecvence = $_REQUEST['fecvence'];
		version_biblioteca($documento,$version,$descripcion,$fecvence);
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

function tabla_biblioteca($categoria)
{
	$ClsBib = new ClsBiblioteca();
	$result = $ClsBib->get_biblioteca('',$categoria,'1,2,3,10');
	if (is_array($result)) {
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_documentos($categoria),
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

function get_tabla($codigo,$tipo){ 
	$ClsBib = new ClsBiblioteca();
	$result = $ClsBib->get_biblioteca($codigo);
	if(is_array($result)){
		if($tipo == "gestor"){
			$tabla = tabla_gestor_biblioteca($codigo);
		}else if($tipo == "versiones"){
			$tabla = tabla_gestor_versiones($codigo);
		}else if($tipo == "aprobaciones"){
			$tabla = tabla_gestor_aprobaciones($codigo);
		}
		$arr_respuesta = array(
			"status" => true,
			"tabla" => $tabla,
			"message" => ""
		);
	}else{
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Aún no hay datos registrados... $codigo,$tipo"
		);
	}
	echo json_encode($arr_respuesta);
}

function get_biblioteca($codigo){ 
	$ClsBib = new ClsBiblioteca();
	$result = $ClsBib->get_biblioteca($codigo);
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["bib_codigo"]);
			$arr_data["categoria"] = trim($row["bib_categoria"]);
			$arr_data["codint"] = trim($row["bib_codigo_interno"]);
			$arr_data["usuario"] = trim($row["bib_usuario"]);
			$arr_data["fecvence"] = cambia_fechaHora($row["bib_fecha_vence"]);
			$arr_data["titulo"] = trim($row["bib_titulo"]);
			$arr_data["descripcion"] = trim($row["bib_descripcion"]);
			$arr_data["objetivo"] = trim($row["bib_objetivo"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_gestor_biblioteca($codigo),
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


function grabar_biblioteca($categoria,$codint,$titulo,$descripcion,$fecvence,$usuario){
	$ClsBib = new ClsBiblioteca();
	if($categoria != "" && $codint != "" && $usuario != "" && $titulo != "" && $fecvence != ""){
		$codigo = $ClsBib->max_biblioteca();
		$codigo++; /// Maximo codigo de Biblioteca
		$sql = $ClsBib->insert_biblioteca($codigo,$categoria,$codint,$titulo,$descripcion,$fecvence,$usuario);
		$rs = $ClsBib->exec_sql($sql);
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


function modificar_biblioteca($codigo,$categoria,$codint,$titulo,$descripcion,$fecvence,$usuario){
	$ClsBib = new ClsBiblioteca();
	if($codigo != ""  && $categoria != ""  && $codint != "" && $usuario != "" && $titulo != "" && $fecvence != ""){
		$sql = $ClsBib->modifica_biblioteca($codigo,$categoria,$codint,$titulo,$descripcion,$fecvence,$usuario);
		$rs = $ClsBib->exec_sql($sql);
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



function delete_documento($codigo){ 
	$ClsBib = new ClsBiblioteca();
	$sql = $ClsBib->actualiza_documento($codigo,'');
	$rs = $ClsBib->exec_sql($sql);
	if($rs == 1){
		$arr_respuesta = array(
			"status" => true,
			"data" => [],
			"message" => "Documento eliminada con éxito...!"
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



function situacion_biblioteca($codigo,$situacion){ 
	$ClsBib = new ClsBiblioteca();
	$sql = $ClsBib->cambia_situacion_biblioteca($codigo,$situacion);
	$rs = $ClsBib->exec_sql($sql);
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
}


/////// VERSION ////////

function version_biblioteca($documento,$version,$descripcion,$fecvence){
	$ClsBib = new ClsBiblioteca();
	if($documento != "" && $version != "" && $descripcion != "" && $fecvence != ""){
		$codigo = $ClsBib->max_version($documento);
		$codigo++; /// Maximo codigo de Biblioteca
		$sql = $ClsBib->insert_version($codigo,$documento,$version,$descripcion,'');
		$sql.= $ClsBib->actualiza_version($documento,$version,$fecvence);
		$sql.= $ClsBib->cambia_situacion_biblioteca($documento,1);
		$rs = $ClsBib->exec_sql($sql);
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
				"message" => "Error al registrar la nueva versión..."
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