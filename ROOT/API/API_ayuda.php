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
		case "contact":
			$nombre = $_REQUEST["nombre"];
			$mail = $_REQUEST["mail"];
			$subj = $_REQUEST["subject"];
			$msj = $_REQUEST["msj"];
			API_contactanos($nombre,$mail,$subj,$msj);
			break;
		case "password":
			$mail = $_REQUEST["mail"];
			API_tu_pasword($mail);
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

function API_contactanos($nombre,$mail,$subj,$msj){
	if($nombre != "" && $mail != "" && $subj != "" && $msj != ""){
		$mailadmin = "soporte@farasi.com.gt";
		// Instancia el API KEY de Mandrill
		$mandrill = new Mandrill('aLGRM5YodGYp_GDBwwDilw');
		//--
		// Create the email and send the message
		$to = array(
			array(
				'email' => "$mailadmin",
				'name' => "Admin",
				'type' => 'to'
			)
		);
		/////////////_________ Correo a admin
		$subject = "Mensaje de un usuario de Chron Billing";
		$cuerpo = "Has recibido un nuevo mensaje de Chrone Billing. Aqui estan los detalles: <br> Nombre: <b>$nombre</b> <br> E-mail: <b>$mail</b> <br> Asunto: <b>$subj</b> <br> Mensaje: <b>$msj</b> <br><br>Que pases un feliz dia!!!";
		$html = mail_constructor($cuerpo);
		try{
			$message = array(
				'subject' => $subject,
				'html' => $html,
				'from_email' => 'noreply@farasi.com.gt',
				'to' => $to
			 );  
			//print_r($message);
			//echo "<br>";
			$result = $mandrill->messages->send($message);
			$payload = array(
				"status" => true,
				"data" => [],
				"message" => "Tu mensaje ha sido enviado, gracias!");
			echo json_encode($payload);
				
		} catch(Mandrill_Error $e) { 
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => false,
				"data" => [],
				"message" => "Por el momento no es posible enviar este mensaje, por favor intenta más tarde...");
			echo json_encode($payload);
		}         	
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Uno de los campos esta vacio, todos son obligatorios...");
		echo json_encode($payload);
	}
}


function API_tu_pasword($mail){
	if($mail != ""){
		$ClsUsu = new ClsUsuario();
		$result = $ClsUsu->get_usuario('','','',$mail);
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
					$mensaje_error = "";
					$status = 1;
				}else{
					$mensaje_error = "Su usuario se encuentra bloqueado, por favor contacte al administrador.";
					$status = 0;
				}
			}else{
				$mensaje_error = "Su usuario se encuentra inactivo.";
				$status = 0;
			}
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
			$cuerpo = "Has recibido un nuevo mensaje de Chrone Billing. Aqui estan los detalles de tu usuario: <br> Nombre: <b>$nombre</b> <br> E-mail: <b>$mail</b> <br> Usuario: <b>$usu</b> <br> Password: <b>$pass</b> <br><br>Que pases un feliz dia!!!";
			$html = mail_constructor($cuerpo);
			try{
				$message = array(
					'subject' => $subject,
					'html' => $html,
					'from_email' => 'noreply@farasi.com.gt',
					'from_name' => 'BPManagement',
					'to' => $to
				 );		 
				$result = $mandrill->messages->send($message);
				$status = 1;
					
			} catch(Mandrill_Error $e) { 
				$mensaje_error = "Su mensaje no ha podido ser entregado en este momento, lo sentimos...";
				$status = 0;
			}
		}else{
			$mensaje_error = "Este correo no esta registrado en el sistema, por favor contacte al administrador...";
			$status = 0;
		}
		if($status == 1){
			$result = $mandrill->messages->send($message);
			$payload = array(
				"status" => true,
				"data" => [],
				"message" => "Tu solicitud esta siendo procesada, en unos minutos recibiras un e-mail con tu Usuario y Contraseña al correo registrado...");
			echo json_encode($payload);
		}else{
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => false,
				"data" => [],
				"message" => $mensaje_error);
			echo json_encode($payload);
		}
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "El correo electrónico es obligatorio...");
		echo json_encode($payload);
	}
}
