<?php
include_once('../html_fns.php');

////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////

function mail_pide_passw($id,$usu,$preg,$resp,$mail){
	$ClsUsu = new ClsUsuario();
	$resp = trim($resp);
	$result = $ClsUsu->get_valida_pregunta_resp($id,$usu,$preg,$resp);
	if(is_array($result)){
		foreach($result as $row){
			$nom = $row["usu_nombre"];
			$usu = $row["usu_usuario"];
			$pass = $row["usu_pass"];
			$pass = $ClsUsu->decrypt($pass, $usu); //desencripta el password
		}
		$mailadmin = "soporte@farasi.com.gt";
		$asunto = "Usuario y Contrase&ntilde;a del Sistema";
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		//direccion del remitente
		$headers .= "From: Administrador del Sistema <noreply@entergo.app>\r\n";
		$fec = date("d/m/Y");
		$cuerpo = Construye_Mail_Respuesta($fec,$nom,$usu,$pass,$mailadmin);
		if(mail($mail,$asunto,$cuerpo,$headers)){
			return 1;
		}else{
			return 2;
		}
	}else{
		return 0;
	}	
}


function mail_admin($suc,$mail,$nom,$subj,$msj){
	$ClsReg = new ClsRegla();
	$suc = trim($suc);
	$nom = trim($nom);
	$subj = trim($subj);
	$msj = trim($msj);
	$mail = strtolower($mail);
	//correo al administrador
	$admin = $ClsReg->get_mail_admin();
	$msj.= ". Nombre: $nom, Empresa: $suc, Correo para comunicarse: $mail";
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	//direccion del remitente
	$headers .= "From: (Para Administrador) $nom <$mail>\r\n";
	$cont = 0;
	if(mail($admin,$subj,$msj,$headers)){
		$cont++;
	}
	if($cont>0){
		$cont = 0;
		//correo de respuesta para el usuario
		$subj2 = "Respuesta del Administrador";
		$msj2 = "Gracias por comunicarse con el Administrador del Sistema, en un momento daremos tr�mite a su mensaje.";
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		//direccion del remitente
		$headers .= "From: Administrador del Sistema <$admin>\r\n";
		if(mail($mail,$subj2,$msj2,$headers)){
			$cont++;
		}
		if($cont>0){
			return 1;
		}else{
			return 2;
		}
	}else{
		return 0;
	}	
}

?>