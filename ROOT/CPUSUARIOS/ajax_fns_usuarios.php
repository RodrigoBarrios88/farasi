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
include_once('html_fns_usuarios.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "tabla":
		$codigo = $_REQUEST["codigo"];
		get_tabla($codigo);
		break;
	case "get":
		$codigo = $_REQUEST["codigo"];
		get_usuario($codigo);
		break;
	case "grabar":
		$nombre = $_REQUEST["nombre"];
		$mail = $_REQUEST["mail"];
		$telefono = $_REQUEST["telefono"];
		$rol = $_REQUEST["rol"];
		$usuario = $_REQUEST["usuario"];
		$pass = $_REQUEST["pass"];
		grabar_usuario($nombre,$mail,$telefono,$rol,$usuario,$pass);
		break;
	case "modificar":
		$codigo = $_REQUEST["codigo"];
		$nombre = $_REQUEST["nombre"];
		$mail = $_REQUEST["mail"];
		$telefono = $_REQUEST["telefono"];
		$rol = $_REQUEST["rol"];
		$usuario = $_REQUEST["usuario"];
		$pass = $_REQUEST["pass"];
		$habilita = $_REQUEST["habilita"];
		$seguridad = $_REQUEST["seguridad"];
		modificar_usuario($codigo,$nombre,$mail,$telefono,$rol,$usuario,$pass,$habilita,$seguridad);
		break;
	case "situacion":
		$codigo = $_REQUEST["codigo"];
		$situacion = $_REQUEST["situacion"];
		cambio_situacion($codigo,$situacion);
		break;
	case "cambiarpass":
		$codigo = $_REQUEST["codigo"];
		$nombre = $_REQUEST["nombre"];
		$mail = $_REQUEST["mail"];
		$telefono = $_REQUEST["telefono"];
		$usuario = $_REQUEST["usuario"];
		$pass = $_REQUEST["pass"];
		cambia_contrasena($codigo,$nombre,$mail,$telefono,$usuario,$pass);
		break;////// PERMISOS ////
	case "tablaasignacion":
		get_tabla_asignacion();
		break;
	case "cuadroroles":
		$codigo = $_REQUEST["codigo"];
		get_cuadro_roles($codigo);
		break;
	case "permisosroles":
		$codigo = $_REQUEST["codigo"];
		get_permisos_roles($codigo);
		break;
	case "asignar":
		$usuario = $_REQUEST["usuario"];
		$rol = $_REQUEST["rol"];
		$permisos = $_REQUEST["permisos"];
		$grupos = $_REQUEST["grupos"];
		$cantidad = $_REQUEST["cantidad"];
		asignar_permisos($usuario,$rol,$permisos,$grupos,$cantidad);
		break;////// ORGANIZACION ////
	case "asignar_sede":
		$usuario = $_REQUEST["usuario"];
		$sedes = $_REQUEST["sedes"];
		asignar_sedes($usuario,$sedes);
		break;
	case "asignar_categoria":
		$usuario = $_REQUEST["usuario"];
		$categorias = $_REQUEST["categorias"];
		asignar_categorias($usuario,$categorias);
		break;
	case "asignar_categoria_indicador":
		$usuario = $_REQUEST["usuario"];
		$categorias = $_REQUEST["categorias"];
		asignar_categorias_indicador($usuario, $categorias);
		break;
	case "asignar_departamento":
		$usuario = $_REQUEST["usuario"];
		$departamentos = $_REQUEST["departamentos"];
		asignar_departamentos($usuario,$departamentos);
		break;default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}


function get_tabla($codigo){ 
	$ClsUsu = new ClsUsuario();
	$result = $ClsUsu->get_usuario($codigo);
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_usuarios($codigo),
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


function get_usuario($codigo){ 
	$ClsUsu = new ClsUsuario();
	$result = $ClsUsu->get_usuario($codigo);
	$i = 0;
	$arr_data = array();
	if(is_array($result)){
		foreach($result as $row){
			$arr_data["codigo"] = trim($row["usu_id"]);
			$arr_data["cliente"] = trim($row["usu_cliente"]);
			$arr_data["nombre"] = trim($row["usu_nombre"]);
			$arr_data["rol"] = trim($row["usu_rol"]);
			$arr_data["usuario"] = trim($row["usu_usuario"]);
			$arr_data["mail"] = trim($row["usu_mail"]);
			$arr_data["telefono"] = trim($row["usu_telefono"]);
			$arr_data["habilita"] = trim($row["usu_habilita"]);
			$arr_data["seguridad"] = trim($row["usu_seguridad"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_usuarios($codigo),
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


function grabar_usuario($nombre,$mail,$telefono,$rol,$usuario,$pass){
	$ClsUsu = new ClsUsuario();
	if($nombre !="" && $mail !=""){
		$cont = $ClsUsu->count_usuario("","","","","","",$usuario);
		if($cont == 0){
			$codigo = $ClsUsu->max_usuario();
			$codigo++;
			$sql = $ClsUsu->insert_usuario($codigo,$nombre,$mail,$telefono,$rol,$usuario,$pass);
			$rs = $ClsUsu->exec_sql($sql);
			if($rs == 1){
				$arr_respuesta = array(
					"status" => true,
					"data" => [],
					"message" => "Registro guardados satisfactoriamente...!"
				);
				echo json_encode($arr_respuesta);
			}else{
				$arr_respuesta = array(
					"status" => false,
					"data" => [],
					"message" => "Error en la transacción..."
				);
				echo json_encode($arr_respuesta);
			}
		}else{
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "El Nombre de Usuario ya esta asignado a otra persona, busque otra nombre..."
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


function modificar_usuario($codigo,$nombre,$mail,$telefono,$rol,$usuario,$pass,$habilita,$seguridad){
	$ClsUsu = new ClsUsuario();
	if($codigo !="" && $nombre !=""){
		$cont = $ClsUsu->comprueba_nusuario($codigo,$usuario);
		if($cont == 0){
			$sql = $ClsUsu->modifica_usuario($codigo,$nombre,$mail,$telefono,$rol,$usuario,$pass,$habilita,$seguridad);
			$rs = $ClsUsu->exec_sql($sql);
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
				"message" => "El Nombre de Usuario ya esta asignado a otra persona, busque otra nombre..."
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


function cambio_situacion($codigo,$situacion){ 
	$ClsUsu = new ClsUsuario();
	$sql = $ClsUsu->cambia_sit_usuario($codigo,$situacion);
	$rs = $ClsUsu->exec_sql($sql);
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
			"data" => [],
			"message" => "Error en la ejecución"
		);
		
		echo json_encode($arr_respuesta);
	}
}


function cambia_contrasena($codigo,$nombre,$mail,$telefono,$usuario,$pass){
	$ClsUsu = new ClsUsuario();
	if($codigo !="" && $nombre !="" && $usuario !=""){
		$sql = $ClsUsu->modifica_pass($codigo,$usuario,$pass);
		$sql.= $ClsUsu->modifica_perfil($codigo,$nombre,$mail,$telefono);
		$sql.= $ClsUsu->cambia_usu_habilita($codigo,1);
		$rs = $ClsUsu->exec_sql($sql);
		if($rs == 1){
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Contraseña actualizada satisfactoriamente...!"
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


/////////////// PERMISOS //////////////////////

function get_tabla_asignacion(){ 
	$arr_respuesta = array(
		"status" => true,
		"tabla" => tabla_asignacion_roles_usuarios()
	);
	echo json_encode($arr_respuesta);
}

function get_cuadro_roles($codigo){ 
	$arr_respuesta = array(
		"status" => true,
		"cuadro" => tabla_encabezado_asignacion($codigo)
	);
	echo json_encode($arr_respuesta);
}


function get_permisos_roles($codigo){if($codigo != 0){
		$contenido = tabla_permisos_roll($codigo);
	}else{
		$contenido = tabla_permisos_libre();
	}$arr_respuesta = array(
		"status" => true,
		"tabla" => $contenido
	);
	echo json_encode($arr_respuesta);
}


function asignar_permisos($usuario,$rol,$permisos,$grupos,$cantidad){
	$ClsPerm = new ClsPermiso();
	$ClsUsu = new ClsUsuario();
	if($usuario !="" && $rol !="" && $cantidad !=""){
		//crea array
		$arrpermisos = explode(",",$permisos);
		$arrgrupos = explode(",",$grupos);
		//--
		$sql = $ClsPerm->delet_perm_asignacion($usuario);
		for($i = 0; $i < $cantidad; $i++){
			$permiso = $arrpermisos[$i];
			$grupo = $arrgrupos[$i];
			$sql.= $ClsPerm->insert_perm_asignacion($usuario,$permiso,$grupo);
		}
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



/////////////// ORGANIZACION //////////////////////
function asignar_sedes($usuario,$sedes){
	$ClsUsu = new ClsUsuario();
	if($usuario !=""){
		$codigo = $ClsUsu->max_usuario_sede($usuario);
		$codigo++;
		$sql = $ClsUsu->delete_usuario_sede($usuario);
		if($sedes != ""){
			$arrsede = explode(",",$sedes);
			$count = count($arrsede); //cuenta cuantas vienen en el array
		}else{
			$count = 0;
		}
		for($i = 0; $i < $count; $i++){
		   $sede = $arrsede[$i];
		   $sql.= $ClsUsu->insert_usuario_sede($codigo,$usuario,$sede);
		   $codigo++;
		}
		$rs = $ClsUsu->exec_sql($sql);
		if($rs == 1){
			$arr_respuesta = array(
				"status" => true,
				"sql" => $sql,
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


function asignar_categorias($usuario,$categorias){
	$ClsUsu = new ClsUsuario();
	if($usuario !=""){
		$codigo = $ClsUsu->max_usuario_categoria($usuario);
		$codigo++;
		$sql = $ClsUsu->delete_usuario_categoria($usuario);
		if($categorias != ""){
			$arrsede = explode(",",$categorias);
			$count = count($arrsede); //cuenta cuantas vienen en el array
		}else{
			$count = 0;
		}
		for($i = 0; $i < $count; $i++){
		   $sede = $arrsede[$i];
		   $sql.= $ClsUsu->insert_usuario_categoria($codigo,$usuario,$sede);
		   $codigo++;
		}
		$rs = $ClsUsu->exec_sql($sql);
		if($rs == 1){
			$arr_respuesta = array(
				"status" => true,
				"sql" => $sql,
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



function asignar_categorias_indicador($usuario, $categorias){
	$ClsUsu = new ClsUsuario();
	if ($usuario != "") {
		$codigo = $ClsUsu->max_usuario_categoria_indicador($usuario);
		$codigo++;
		$sql = $ClsUsu->delete_usuario_categoria_indicador($usuario);
		if ($categorias != "") {
			$arrcat = explode(",", $categorias);
			$count = count($arrcat); //cuenta cuantas vienen en el array
		} else {
			$count = 0;
		}
		for ($i = 0; $i < $count; $i++) {
			$indicadores = $arrcat[$i];
			$sql .= $ClsUsu->insert_usuario_categoria_indicador($codigo, $usuario,$indicadores);
			$codigo++;
		}
		$rs = $ClsUsu->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"sql" => $sql,
				"data" => [],
				"message" => "Registro guardado satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		} else {
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => $sql
			);
			echo json_encode($arr_respuesta);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);echo json_encode($arr_respuesta);
	}
}


function asignar_departamentos($usuario,$departamentos){
	$ClsUsu = new ClsUsuario();
	if($usuario !=""){
		$codigo = $ClsUsu->max_usuario_departamento($usuario);
		$codigo++;
		$sql = $ClsUsu->delete_usuario_departamento($usuario);
		if($departamentos != ""){
			$arrdepartamento = explode(",",$departamentos);
			$count = count($arrdepartamento); //cuenta cuantas vienen en el array
		}else{
			$count = 0;
		}
		for($i = 0; $i < $count; $i++){
		   $departamento = $arrdepartamento[$i];
		   $sql.= $ClsUsu->insert_usuario_departamento($codigo,$usuario,$departamento);
		   $codigo++;
		}
		$rs = $ClsUsu->exec_sql($sql);
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
}?>