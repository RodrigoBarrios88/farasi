<?php 
include_once('../html_fns.php');
require_once("../recursos/mandrill/src/Mandrill.php"); //--correos
require_once("../../CONFIG/constructor.php"); //--correos


function mail_usuario($ticket,$usuario = ''){
	
	$ClsTic = new ClsTicket();
	$result = $ClsTic->get_ticket($ticket);
	if(is_array($result)){
		foreach($result as $row){
			//codigo
			$codigo = Agrega_Ceros($row["tic_codigo"]);
			//incidente
			$incidente = trim($row["inc_nombre"]);
			//status
			$status = trim($row["sta_nombre"]);
			//prioridad
			$prioridad = trim($row["pri_nombre"]);
			//categoria
			$categoria = trim($row["cat_nombre"]);
			//descripcion
			$desccripcion = trim($row["tic_descripcion"]);
			$desccripcion = nl2br($desccripcion);
			//ubicacion
			$sede = trim($row["sed_nombre"]);
			$sector = trim($row["sec_nombre"]);
			$area = trim($row["are_nombre"]);
		}
	}
	
	//asignados
	$result = $ClsTic->get_asignacion($ticket,$usuario);
	$i = 0;
	if(is_array($result)){
		foreach($result as $row){
			$arrcorreos["email"] = trim($row["usu_mail"]);
			$arrcorreos["name"] = "";
			$arrcorreos["type"] = "to";
			$to[$i] = $arrcorreos;
			$i++;
		}
		$arrcorreos["email"] = "soporte@farasi.com.gt";
		$arrcorreos["name"] = "";
		$arrcorreos["type"] = "to";
		$to[$i] = $arrcorreos;
	}
	
	//////////////////////// CREDENCIALES DE CLIENTE
	$ClsConf = new ClsConfig();
	$result = $ClsConf->get_credenciales();
	if(is_array($result)){
		foreach($result as $row){
			$cliente_nombre = trim($row['cliente_nombre']);
			$cliente_nombre_reporte = trim($row['cliente_nombre_reporte']);
		}
	}
	$url = url_origin( $_SERVER );
		
	$mailadmin = "soporte@farasi.com.gt";
    // Instancia el API KEY de Mandrill
	$mandrill = new Mandrill('aLGRM5YodGYp_GDBwwDilw');
	/////////////_________ Correo a admin
	$subject = $cliente_nombre_reporte;
	$texto = "Estimado Usuario,<br><br>hay una nueva solicitud reportada con el número # $codigo en la $sede, $sector, $area.<br><br>El porblema es el siguiente:<br><strong>$incidente</strong> <br>$desccripcion <br><br>";
	$texto.= "Puede accesar al sistema desde aqui:<br><br>";
	$texto.= '<a href="'.$url.'/HDAPP/" class="btn btn-warning btn-round btn-block">  Click </a>';
	$texto.= "<br><br>Gracias y saludos,<br><br>HelpDesk";
	
	$html = mail_constructor($subject,$texto); 
	
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
		$validacion =  1;
	} catch(Mandrill_Error $e) { 
		//echo "<br>";
		//print_r($e);
		//devuelve un mensaje de manejo de errores
		$validacion =  0;
	}         
		
	return $validacion;
}


?>
