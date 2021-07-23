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
include_once('html_fns_ayuda.php');

$request = $_REQUEST["request"]; 
switch($request){
	case "password":
		$mail = $_REQUEST["email"];
		get_password($mail);
		break;
	case "mailadmin":
		$nombre = $_REQUEST["nombre"];
		$email = $_REQUEST["email"];
		$subject = $_REQUEST["subject"];
		$msj = $_REQUEST["msj"];
		send_mail_admin($nombre,$email,$subject,$msj);
		break;
	default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}


function get_password($mail){ 
	$ClsUsu = new ClsUsuario();if($mail != ""){
		$result = $ClsUsu->get_usuario('','',$mail);
		if(is_array($result)){
			foreach($result as $row){
				$nombre = $row["usu_nombre"];
				$seguridad = $row["usu_seguridad"];
				$situacion = $row["usu_situacion"];
				//--
				$usu = $row["usu_usuario"];
				$pass = $row["usu_pass"];
				$pass = $ClsUsu->decrypt($pass, $usu); //desencripta el password
			}
			if($situacion == 1){
				if($seguridad == 0){
					$status = 1;
				}else{
					$arr_respuesta = array(
						"status" => false,
						"data" => [],
						"message" => "Su usuario se encuentra bloqueado, por favor contacte al administrador..."
					);
					echo json_encode($arr_respuesta);
					return;
				}
			}else{
				$arr_respuesta = array(
					"status" => false,
					"data" => [],
					"message" => "Su usuario se encuentra inactivo..."
				);
				echo json_encode($arr_respuesta);
				return;
			}
			//////////////////////// CREDENCIALES DE CLIENTE
			$ClsConf = new ClsConfig();
			$result = $ClsConf->get_credenciales();
			if(is_array($result)){
				foreach($result as $row){
					$cliente_nombre = utf8_decode($row['cliente_nombre']);
					$cliente_nombre_reporte = utf8_decode($row['cliente_nombre_reporte']);
				}
			}
			$cliente_nombre = depurador_texto($cliente_nombre);
			$cliente_nombre_reporte = depurador_texto($cliente_nombre_reporte);
			$mailadmin = "manuelsa@farasi.com.gt";
			// Instancia el API KEY de Mandrill
			$mandrill = new Mandrill('aLGRM5YodGYp_GDBwwDilw');
			//--
			// Create the email and send the message
			$to = array(
				array(
					'email' => "$mail",
					'name' => "$nombre",
					'type' => 'to'
				)
			);
			/////////////_________ Correo a admin
			$subject = "Tu Password";
			$cuerpo = "Has recibido un nuevo mensaje desde el Sistema BPManagement de $cliente_nombre. <br><br>"."Aqu&iacute; est&aacute;n los detalles:<br><br>Estimado(a) $nombre<br> $mensaje_error<br>E-mail: $mail<br>Usuario: $usu<br>Password: $pass<br><br>Que pases un feliz d&iacute;a!!!";
			$html = mail_constructor($subject, $cuerpo);
			try{
				$message = array(
					'subject' => $subject,
					'html' => $html,
					'from_email' => 'noreply@farasi.com.gt',
					'from_name' => 'BPManagement',
					'to' => $to
				);
				//print_r($message);
				//echo "<br>";
				$result = $mandrill->messages->send($message);
				$arr_respuesta = array(
					"status" => true,
					"data" => [],
					"message" => "Su solicitud esta siendo procesada, en unos minutos recibira un e-mail con su Usuario y Contraseña al correo registrado..."
				);
				echo json_encode($arr_respuesta);
				return;
					
			} catch(Mandrill_Error $e) { 
				//echo "<br>";
				//print_r($e);
				$arr_respuesta = array(
					"status" => false,
					"data" => [],
					"message" => "Su mensaje no ha podido ser entregado en este momento, lo sentimos..."
				);
				echo json_encode($arr_respuesta);
				return;
			}         
		}else{
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Este correo no esta registrado en el sistema, por favor contacte al administrador..."
			);
			echo json_encode($arr_respuesta);
			return;
		}
	}else{
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "El correo está vacio..."
		);
		echo json_encode($arr_respuesta);
		return;
	}

}


function send_mail_admin($nombre,$email,$subject,$msj){
	$ClsUsu = new ClsUsuario();
	if($nombre !="" && $email !="" && $subject !="" && $msj !=""){
	    //////////////////////// CREDENCIALES DE CLIENTE
		$ClsConf = new ClsConfig();
		$result = $ClsConf->get_credenciales();
		if(is_array($result)){
			foreach($result as $row){
				$cliente_nombre = utf8_decode($row['cliente_nombre']);
				$cliente_nombre_reporte = utf8_decode($row['cliente_nombre_reporte']);
			}
		}
		$cliente_nombre = depurador_texto($cliente_nombre);
		$cliente_nombre_reporte = depurador_texto($cliente_nombre_reporte);
		//--
		$mailadmin = "manuelsa@farasi.com.gt";
		// Instancia el API KEY de Mandrill
		$mandrill = new Mandrill('aLGRM5YodGYp_GDBwwDilw');
		//--
		// Create the email and send the message
		$to = array(
			array(
				'email' => $mailadmin,
				'name' => 'Administrador',
				'type' => 'to'
			)
		);
		/////////////_________ Correo a admin
		$subject = trim("Correo al Administrador desde BPManagement de $cliente_nombre");
		$cuerpo = trim("Has recibido un nuevo mensaje desde el Sistema BPManagement de $cliente_nombre. <br><br>"."Aqu&iacute; estan los detalles:<br><br>Nombre: $nombre<br>E-mail: $mail<br>Asunto: $subject<br>Mensaje: $msj<br><br>Que pases un fel&iacute;z d&iacute;a!!!");
		$html = mail_constructor($subject, $cuerpo);
		try{
			$message = array(
				'subject' => $subject,
				'html' => $html,
				'from_email' => 'noreply@farasi.com.gt',
				'from_name' => 'BPManagement',
				'to' => $to
			);
			//print_r($message);
			$result = $mandrill->messages->send($message);
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Su mensaje fue enviado exitosamente al administrador...!"
			);
			echo json_encode($arr_respuesta);
			return;
		} catch(Mandrill_Error $e) { 
			//echo "<br>";
			//print_r($e);
			//devuelve un mensaje de manejo de errores
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Error en el envio de correos..."
			);
			echo json_encode($arr_respuesta);
			return;
		}         
	}else{
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);
		echo json_encode($arr_respuesta);
		return;
	}
}?>