<?php
ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cache");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);

include_once('html_fns_valida.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "activar":
		$codigo = $_REQUEST["codigo"];
		$nombre = $_REQUEST["nombre"];
		$mail = $_REQUEST["mail"];
		$telefono = $_REQUEST["telefono"];
		$usuario = $_REQUEST["usuario"];
		$pass = $_REQUEST["pass"];
		cambia_contrasena($codigo,$nombre,$mail,$telefono,$usuario,$pass);
		break;default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

////////////////// ACTIVAR /////////////////////////
function cambia_contrasena($codigo,$nombre,$mail,$telefono,$usuario,$pass){
	$ClsUsu = new ClsUsuario();
	if($codigo !="" && $nombre !="" && $usuario !=""){
		$sql = $ClsUsu->modifica_pass($codigo,$usuario,$pass);
		$sql.= $ClsUsu->modifica_perfil($codigo,$nombre,$mail,$telefono);
		$sql.= $ClsUsu->cambia_usu_habilita($codigo,1);
		$rs = $ClsUsu->exec_sql($sql);
		if($rs == 1){
			$_SESSION['codigo'] = $codigo;
			$_SESSION['nombre'] = $nombre;
			$_SESSION['usu'] = $usuario;
			$_SESSION['pass'] = $pass;
			
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
?>