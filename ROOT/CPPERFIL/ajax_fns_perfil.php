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
include_once('html_fns_perfil.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "perfil":
		$codigo = $_REQUEST["codigo"];
		$nombre = $_REQUEST["nombre"];
		$email = $_REQUEST["email"];
		$telefono = $_REQUEST["telefono"];
		update_perfil($codigo,$nombre,$email,$telefono);
		break;
	case "password":
		$codigo = $_REQUEST["codigo"];
		$usuario = $_REQUEST["usuario"];
		$pass = $_REQUEST["pass"];
		update_password($codigo,$usuario,$pass);
		break;
	case "ajustes":
		$codigo = $_REQUEST["codigo"];
		$idioma = $_REQUEST["idioma"];
		$notificaciones = $_REQUEST["notificaciones"];
		update_ajustes($codigo,$idioma,$notificaciones);
		break;
	default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}


function update_perfil($codigo,$nombre,$mail,$telefono){ 
	$ClsUsu = new ClsUsuario();
	if($codigo != ""){
		if($codigo != "" && $nombre != "" && $mail != "" && $telefono != ""){
			//$respuesta->alert("$id,$mail,$tel");
			$sql = $ClsUsu->modifica_perfil($codigo,$nombre,$mail,$telefono);
			$rs = $ClsUsu->exec_sql($sql);
			//$respuesta->alert("$sql");
			if($rs == 1){
				$_SESSION['nombre'] = $nombre;
				$arr_respuesta = array(
					"status" => true,
					"data" => [],
					"message" => "Perfil actualizado satisfactoriamente!!!"
				);
				echo json_encode($arr_respuesta);
				return;
			}else{
				$arr_respuesta = array(
					"status" => false,
					"data" => [],
					"message" => "Error en la transacción al ejecutar la sentencia en el servidor...."
				);
				echo json_encode($arr_respuesta);
				return;
			}	
		}else{
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Error en la transacción, algunos campos van vacios...."
			);
			echo json_encode($arr_respuesta);
			return;
		}
	}else{
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Error en la transacción, codigo de usuario vacio...."
		);
		echo json_encode($arr_respuesta);
		return;
	}

}

function update_password($codigo,$usuario,$pass){ 
	$ClsUsu = new ClsUsuario();
	if($codigo != ""){
		if($codigo != "" && $usuario != "" && $pass != ""){
			//$respuesta->alert("$id,$mail,$tel");
			$sql = $ClsUsu->modifica_pass($codigo,$usuario,$pass);
			$rs = $ClsUsu->exec_sql($sql);
			//$respuesta->alert("$sql");
			if($rs == 1){
				$arr_respuesta = array(
					"status" => true,
					"data" => [],
					"message" => "Contraseña actualizada satisfactoriamente!!!"
				);
				echo json_encode($arr_respuesta);
				return;
			}else{
				$arr_respuesta = array(
					"status" => false,
					"data" => [],
					"message" => "Error en la transacción al ejecutar la sentencia en el servidor...."
				);
				echo json_encode($arr_respuesta);
				return;
			}	
		}else{
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Error en la transacción, algunos campos van vacios...."
			);
			echo json_encode($arr_respuesta);
			return;
		}
	}else{
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Error en la transacción, codigo de usuario vacio...."
		);
		echo json_encode($arr_respuesta);
		return;
	}
}


function update_ajustes($codigo,$idioma,$notificaciones){ 
	$ClsAjus = new ClsAjustes();
	if($codigo != ""){
		if($codigo != "" && $idioma != "" && $notificaciones != ""){
			//$respuesta->alert("$id,$mail,$tel");
			$sql = $ClsAjus->insert_ajustes($codigo,$idioma,$notificaciones);
			$rs = $ClsAjus->exec_sql($sql);
			if($rs == 1){
				$arr_respuesta = array(
					"status" => true,
					"data" => [],
					"message" => "Configuraciones de perfil actualizadas satisfactoriamente!!!"
				);
				echo json_encode($arr_respuesta);
				return;
			}else{
				$arr_respuesta = array(
					"status" => false,
					"data" => [],
					"message" => "Error en la transacción al ejecutar la sentencia en el servidor...."
				);
				echo json_encode($arr_respuesta);
				return;
			}	
		}else{
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Error en la transacción, algunos campos van vacios...."
			);
			echo json_encode($arr_respuesta);
			return;
		}
	}else{
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Error en la transacción, codigo de usuario vacio...."
		);
		echo json_encode($arr_respuesta);
		return;
	}
}?>