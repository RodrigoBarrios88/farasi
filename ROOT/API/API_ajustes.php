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
		case "get_perfil":
			$usuario = $_REQUEST["usuario"];
			API_get_perfil($usuario);
			break;
		case "set_perfil":
			$usuario = $_REQUEST["usuario"];
			$nombre = $_REQUEST["nombre"];
			$mail = $_REQUEST["mail"];
			$tel = $_REQUEST["telefono"];
			API_set_perfil($usuario,$nombre,$mail,$tel);
			break;
		case "get_pasword":
			$usuario = $_REQUEST["usuario"];
			API_get_pasword($usuario);
			break;
		case "set_pasword":
			$usuario = $_REQUEST["usuario"];
			$usu = $_REQUEST["usu"];
			$pass = $_REQUEST["pass"];
			API_set_pasword($usuario,$usu,$pass);
			break;
		case "get_ajustes":
			$usuario = $_REQUEST["usuario"];
			API_get_ajustes($usuario);
			break;
		case "set_ajustes":
			$usuario = $_REQUEST["usuario"];
			$idioma = $_REQUEST["idioma"];
			$notificaciones = $_REQUEST["notificaciones"];
			API_set_ajustes($usuario,$idioma,$notificaciones);
			break;
		case "get_foto":
			$usuario = $_REQUEST["usuario"];
			API_get_foto($usuario);
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

function API_get_perfil($usuario){
	if($usuario != ""){
		$ClsUsu = new ClsUsuario();
		$result = $ClsUsu->get_usuario($usuario);
		if (is_array($result)) {
			foreach ($result as $row){
				$arr_data['usuario'] = $usuario;
				$arr_data['nombre'] = trim($row["usu_nombre"]);
				$arr_data['mail'] = trim($row["usu_mail"]);
				$arr_data['telefono'] = trim($row["usu_telefono"]);
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
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Uno de los campos esta vacio...");
			echo json_encode($payload);
	}

}


function API_set_perfil($usuario,$nombre,$mail,$tel){
		$ClsUsu = new ClsUsuario();
		if($usuario != "" && $nombre != "" && $mail != "" && $tel != ""){
			$sql = $ClsUsu->modifica_perfil($usuario,$nombre,$mail,$tel);
			$rs = $ClsUsu->exec_sql($sql);
			if($rs == 1){
				$payload = array(
					 "status" => true,
					 "data" => array( "usuario" => $usuario),
					 "message" => "Registro actualizado satisfactoriamente!");
				echo json_encode($payload);
			}else{
					//devuelve un mensaje de manejo de errores
					$payload = array(
						 "status" => false,
						 "data" => [],
						 "message" => "Error en la transacción...");
						 echo json_encode($payload);
			}
		}else{
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => false,
				"data" => [],
				"message" => "Uno de los campos esta vacio...");
				echo json_encode($payload);
		}

}


function API_get_pasword($usuario){
	if($usuario != ""){
		$ClsUsu = new ClsUsuario();
		$result = $ClsUsu->get_usuario($usuario);
		if (is_array($result)) {
			foreach ($result as $row){
				$arr_data['usuario'] = $usuario;
				$arr_data['nombre'] = trim($row["usu_nombre"]);
				$arr_data['usu'] = trim($row["usu_usuario"]);
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
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Uno de los campos esta vacio...");
			echo json_encode($payload);
	}

}


function API_set_pasword($usuario,$usu,$pass){
		$ClsUsu = new ClsUsuario();
		if($usuario != "" && $usu != "" && $pass != ""){
			$sql = $ClsUsu->modifica_pass($usuario,$usu,$pass);
			$rs = $ClsUsu->exec_sql($sql);
			if($rs == 1){
				$payload = array(
					 "status" => true,
					 "data" => array( "usuario" => $usuario),
					 "message" => "Registro actualizado satisfactoriamente!");
				echo json_encode($payload);
			}else{
					//devuelve un mensaje de manejo de errores
					$payload = array(
						 "status" => false,
						 "data" => [],
						 "message" => "Error en la transacción...");
						 echo json_encode($payload);
			}
		}else{
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => false,
				"data" => [],
				"message" => "Uno de los campos esta vacio...");
				echo json_encode($payload);
		}

}

function API_get_ajustes($usuario){
	if($usuario != ""){
		$ClsAju = new ClsAjustes();
		$result = $ClsAju->get_ajustes($usuario);
		if (is_array($result)) {
			foreach ($result as $row){
				$arr_data['usuario'] = $usuario;
				$arr_data['nombre'] = trim($row["usu_nombre"]);
				$arr_data['idioma'] = trim($row["aju_idioma"]);
				$arr_data['notificaciones'] = trim($row["aju_notificaciones"]);
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
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Uno de los campos esta vacio...");
			echo json_encode($payload);
	}

}


function API_set_ajustes($usuario,$idioma,$notificaciones){
		$ClsAju = new ClsAjustes();
		if($usuario != "" && $idioma != "" && $notificaciones != ""){
			$sql = $ClsAju->insert_ajustes($usuario,$idioma,$notificaciones);
			$rs = $ClsAju->exec_sql($sql);
			if($rs == 1){
				$payload = array(
					"status" => true,
					"data" => array( "usuario" => $usuario ),
					"message" => "Registro actualizado satisfactoriamente!");
					echo json_encode($payload);
	    }else{
				//devuelve un mensaje de manejo de errores
			 	$payload = array(
					"status" => false,
					"data" => [],
					"message" => "Error en la transacción...");
					echo json_encode($payload);
	    }
		}else{
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => false,
				"data" => [],
				"message" => "Uno de los campos esta vacio...");
				echo json_encode($payload);
		}

}


function API_get_foto($usuario){
	if($usuario != ""){
		$ClsUsu = new ClsUsuario();
		$foto = $ClsUsu->last_foto_usuario($usuario);
		if(file_exists('../../CONFIG/Fotos/USUARIOS/'.$foto.'.jpg') && $foto != ""){
			$arr_data['url_foto'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/USUARIOS/".$foto.".jpg";
		}else{
			$arr_data['url_foto'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/nofoto.png";
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
			"message" => "Uno de los campos esta vacio...");
			echo json_encode($payload);
	}
}

