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
		case "login":
			$usu = $_REQUEST["usu"];
			$pass = $_REQUEST["pass"];
			API_login($usu,$pass);
			break;
		case "get_perfil":
			$usuario = $_REQUEST["usuario"];
			API_get_perfil($usuario);
			break;
		case "validar_red":
			API_validar_red();
			break;
		case "versionamiento":
			$codigo = $_REQUEST["codigo"];
			API_validar_version($codigo);
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

function API_login($usu,$pass){
	$ClsUsu = new ClsUsuario();
	if($usu != "" && $pass != ""){
		$result = $ClsUsu->get_login($usu,$pass);
		if (is_array($result)) {
				foreach ($result as $row){
					$codigo = $row['usu_id'];
					$nombre = trim($row["usu_nombre"]);
					$mail = trim($row["usu_mail"]);
					$telefono = trim($row["usu_telefono"]);
					$tipo = $row['usu_tipo'];
					$rol = $row['rol_id'];
					$rol_nombre = $row['rol_nombre'];
				}
				
				$foto = $ClsUsu->last_foto_usuario($codigo);
				if(file_exists('../../CONFIG/Fotos/USUARIOS/'.$foto.'.jpg') && $foto != ""){
					$url_foto = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/USUARIOS/".$foto.".jpg";
				}else{
					$url_foto = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/nofoto.png";
				}
				
				$result2 = $ClsUsu->get_usuario_sede('',$codigo,'','',1);
				if(is_array($result2)) {
					$cont_sede = 1;
					foreach ($result2 as $row2){
					$sedes_IN.= $row2['sed_codigo'].",";
					$cont_sede++;
					}
					$cont_sede--; //quita la ultima vuelta
					$sedes_IN = substr($sedes_IN, 0, -1); // quita la ultima coma
				}
				
				/////-----------------------------------------------------
				$result2 = $ClsUsu->get_usuario_categoria('',$codigo,'','',1);
				if(is_array($result2)) {
					$cont_categoria = 1;
					foreach ($result2 as $row2){
					$categorias_IN.= $row2['cat_codigo'].",";
					$cont_categoria++;
					}
					$cont_categoria--; //quita la ultima vuelta
					$categorias_IN = substr($categorias_IN, 0, -1); // quita la ultima coma
				}
				  
				/// USUARIO
				$arr_data['codigo'] = $codigo;
				$arr_data['nombre'] = $nombre;
				$arr_data['mail'] = $mail;
				$arr_data['telefono'] = $telefono;
				$arr_data['rol'] = $rol;
				$arr_data['rol_nombre'] = $rol_nombre;
				$arr_data['url_foto'] = $url_foto;
				
				//--
				$arr_data['sedes_in'] = ($sedes_IN != "")?$sedes_IN:"";
				$arr_data['categorias_in'] = ($categorias_IN != "")?$categorias_IN:"";
				
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
				"message" => "El usuario o el password son incorrectos...");
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


function API_get_perfil($usuario){
	if($usuario != ""){
		$ClsUsu = new ClsUsuario();
		$result = $ClsUsu->get_usuario($usuario);
		if(is_array($result)) {
			foreach ($result as $row){
				$arr_data['usuario'] = $usuario;
				$arr_data['nombre'] = trim($row["usu_nombre"]);
				$arr_data['mail'] = trim($row["usu_mail"]);
				$arr_data['telefono'] = trim($row["usu_telefono"]);
				//--
				$foto = trim($row['usu_foto']);
				if(file_exists('../../CONFIG/Fotos/USUARIOS/'.$foto.'.jpg') && $foto != ""){
					$arr_data['url_foto'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/USUARIOS/".$foto.".jpg";
				}else{
					$arr_data['url_foto'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/nofoto.png";
				}
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


function API_validar_red(){
	//devuelve un mensaje de manejo de errores
	$payload = array( "status" => true );
	echo json_encode($payload);
}



function API_validar_version($codigo){
	$ClsVer = new ClsVersion();
	$codigo = (trim($codigo) == "")?1:$codigo;
	$result = $ClsVer->get_version($codigo,'','',1);
	if(is_array($result)) {
		foreach ($result as $row){
			$version = trim($row["ver_version"]);
		}
		//devuelve un mensaje de manejo de errores
		$payload = array( "version" => $version );
		echo json_encode($payload);
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "No hay version registrada para este software...");
		echo json_encode($payload);
	}

}

?>