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
include_once('html_fns_permiso.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "tablagrupos":
		$codigo = $_REQUEST["codigo"];
		get_tabla_grupo($codigo);
		break;
	case "getgrupo":
		$codigo = $_REQUEST["codigo"];
		get_grupo($codigo);
		break;
	case "grabargrupo":
		$nombre = $_REQUEST["nombre"];
		$clave = $_REQUEST["clave"];
		grabar_grupo($nombre,$clave);
		break;
	case "modificargrupo":
		$codigo = $_REQUEST["codigo"];
		$nombre = $_REQUEST["nombre"];
		$clave = $_REQUEST["clave"];
		modificar_grupo($codigo,$nombre,$clave);
		break;
	case "situaciongrupo":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_grupo($codigo,$situacion);
		break;
	////// PERMISOS ////
	case "tablapermisos":
		$codigo = $_REQUEST["codigo"];
		$grupo = $_REQUEST["grupo"];
		get_tabla_permiso($codigo,$grupo);
		break;
	case "getpermiso":
		$codigo = $_REQUEST["codigo"];
		$grupo = $_REQUEST["grupo"];
		get_permiso($codigo,$grupo);
		break;
	case "grabarpermiso":
		$grupo = $_REQUEST["grupo"];
		$nombre = $_REQUEST["nombre"];
		$clave = $_REQUEST["clave"];
		grabar_permiso($grupo,$nombre,$clave);
		break;
	case "modificarpermiso":
		$codigo = $_REQUEST["codigo"];
		$grupo = $_REQUEST["grupo"];
		$nombre = $_REQUEST["nombre"];
		$clave = $_REQUEST["clave"];
		modificar_permiso($codigo,$grupo,$nombre,$clave);
		break;
	////// PERMISOS ////
	case "tablaroles":
		get_tabla_roles();
		break;
	case "grabarol":
		$nombre = $_REQUEST["nombre"];
		$desc = $_REQUEST["descripcion"];
		$permisos = $_REQUEST["permisos"];
		$grupos = $_REQUEST["grupos"];
		$cantidad = $_REQUEST["cantidad"];
		grabar_rol($nombre,$desc,$permisos,$grupos,$cantidad);
		break;
	case "modificarol":
		$codigo = $_REQUEST["codigo"];
		$nombre = $_REQUEST["nombre"];
		$desc = $_REQUEST["descripcion"];
		$permisos = $_REQUEST["permisos"];
		$grupos = $_REQUEST["grupos"];
		$cantidad = $_REQUEST["cantidad"];
		modificar_rol($codigo,$nombre,$desc,$permisos,$grupos,$cantidad);
		break;
	case "situacionrol":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		situacion_rol($codigo,$situacion);
		break;default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

////////////////// GRUPOS /////////////////////////
function get_tabla_grupo($codigo){ 
	$ClsPerm = new ClsPermiso();
	$result = $ClsPerm->get_grupo($codigo,'','');
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_grupos(''),
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


function get_grupo($codigo){ 
	$ClsPerm = new ClsPermiso();
	$result = $ClsPerm->get_grupo($codigo,'','');
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["gperm_id"]);
			$arr_data["nombre"] = trim($row["gperm_desc"]);
			$arr_data["clave"] = trim($row["gperm_clave"]);
			$arr_data["situacion"] = trim($row["gperm_situacion"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_grupos($codigo),
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


function grabar_grupo($nombre,$clave){
	$ClsPerm = new ClsPermiso();
	if($nombre !="" && $clave !=""){
		$codigo = $ClsPerm->max_grupo();
		$codigo++;
		$sql = $ClsPerm->insert_grupo($codigo,$nombre,$clave);
		$rs = $ClsPerm->exec_sql($sql);
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


function modificar_grupo($codigo,$nombre,$clave){
	$ClsPerm = new ClsPermiso();
	if($nombre !="" && $clave !=""){
		$sql = $ClsPerm->modifica_grupo($codigo,$nombre,$clave);
		$rs = $ClsPerm->exec_sql($sql);
		if($rs == 1){
			$arr_respuesta = array(
				"status" => true,
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


function situacion_grupo($codigo,$situacion){ 
	$ClsPerm = new ClsPermiso();
	$sql = $ClsPerm->cambia_sit_grupo($codigo,$situacion);
	$rs = $ClsPerm->exec_sql($sql);
	if($rs == 1){
		$arr_respuesta = array(
			"status" => true,
			"data" => [],
			"message" => "Cambio de situación exitoso...!"
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




/////////////// PERMISOS //////////////////////
function get_tabla_permiso($codigo,$grupo){ 
	$ClsPerm = new ClsPermiso();
	$result = $ClsPerm->get_permisos($codigo,$grupo,'','');
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_permisos('',''),
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


function get_permiso($codigo,$grupo){ 
	$ClsPerm = new ClsPermiso();
	$result = $ClsPerm->get_permisos($codigo,$grupo,'','');
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["perm_id"]);
			$arr_data["grupo"] = trim($row["perm_grupo"]);
			$arr_data["nombre"] = trim($row["perm_desc"]);
			$arr_data["clave"] = trim($row["perm_clave"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_permisos($codigo,$grupo),
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


function grabar_permiso($grupo,$nombre,$clave){
	$ClsPerm = new ClsPermiso();
	if($grupo !="" && $nombre !="" && $clave !=""){
		$codigo = $ClsPerm->max_permiso($grupo);
		$codigo++;
		$sql = $ClsPerm->insert_permisos($codigo,$grupo,$nombre,$clave);
		$rs = $ClsPerm->exec_sql($sql);
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


function modificar_permiso($codigo,$grupo,$nombre,$clave){
	$ClsPerm = new ClsPermiso();
	if($grupo !="" && $nombre !="" && $clave !=""){
		$sql = $ClsPerm->modifica_permisos($codigo,$grupo,$nombre,$clave);
		$rs = $ClsPerm->exec_sql($sql);
		if($rs == 1){
			$arr_respuesta = array(
				"status" => true,
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



////////////// ROLES ////////////////////
function get_tabla_roles(){ 
	$ClsRol = new ClsRol();
	$result = $ClsRol->get_rol('');
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_roles(),
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


function grabar_rol($nombre,$desc,$permisos,$grupos,$cantidad){
	$ClsRol = new ClsRol();
	if($nombre !="" && $desc !="" && $cantidad !=""){
		//crea array
		$arrpermisos = explode(",",$permisos);
		$arrgrupos = explode(",",$grupos);
		//--
		$codigo = $ClsRol->max_rol();
		$codigo++;
		$sql = $ClsRol->insert_rol($codigo,$nombre,$desc);
		for($i = 0; $i < $cantidad; $i++){
			$permiso = $arrpermisos[$i];
			$grupo = $arrgrupos[$i];
			$sql.= $ClsRol->insert_det_rol($permiso,$grupo,$codigo);
		}
		$rs = $ClsRol->exec_sql($sql);
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


function modificar_rol($codigo,$nombre,$desc,$permisos,$grupos,$cantidad){
	$ClsRol = new ClsRol();
	$ClsUsu = new ClsUsuario();
	$ClsPerm = new ClsPermiso();
	if($nombre !="" && $desc !="" && $cantidad !=""){
		//crea array
		$arrpermisos = explode(",",$permisos);
		$arrgrupos = explode(",",$grupos);
		$sql = $ClsRol->modifica_rol($codigo,$nombre,$desc);
		$sql.= $ClsRol->delet_det_rol_grupo($codigo);
		for($i = 0; $i < $cantidad; $i++){
			$permiso = $arrpermisos[$i];
			$grupo = $arrgrupos[$i];
			$sql.= $ClsRol->insert_det_rol($permiso,$grupo,$codigo);
		}
		
		$result = $ClsUsu->get_usuarios_por_rol($codigo);
		if(is_array($result)){	
			foreach($result as $row){
				$usuuario = $row["usu_id"];
				$sql.= $ClsPerm->actualiza_asignacion_rol($usuuario,$codigo);
			}
		}
		$rs = $ClsRol->exec_sql($sql);
		if($rs == 1){
			$arr_respuesta = array(
				"status" => true,
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


function situacion_rol($codigo,$situacion){ 
	$ClsRol = new ClsRol();
	$sql = $ClsRol->cambia_sit_rol($codigo,$situacion);
	$rs = $ClsRol->exec_sql($sql);
	if($rs == 1){
		$arr_respuesta = array(
			"status" => true,
			//"sql" => $sql,
			"data" => [],
			"message" => "Cambio de situación exitoso...!"
		);
		echo json_encode($arr_respuesta);
	}else{
		$arr_respuesta = array(
			"status" => false,
			//"sql" => $sql,
			"data" => [],
			"message" => "Error en la ejecución"
		);
		
		echo json_encode($arr_respuesta);
	}
}
?>