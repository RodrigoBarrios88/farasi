<?php 
//inclu�mos las clases
require ("../xajax_core/xajax.inc.php");
include_once("html_fns_ticket.php");

//instanciamos el objeto de la clase xajax
$xajax = new xajax();
$xajax->setCharEncoding('ISO-8859-1');
date_default_timezone_set('America/Guatemala');

//////////////////---- INICIDENTES -----/////////////////////////////////
function Grabar_Ticket($desc,$incidente,$prioridad,$sede,$sector,$area,$imagen){
   //instanciamos el objeto para generar la respuesta con ajax
   $respuesta = new xajaxResponse();
   $ClsTic = new ClsTicket();
   $ClsSta = new ClsStatus();
   $ClsInc = new ClsIncidente();
   $ClsPri = new ClsPrioridad();
   //trim a cadena
		$titulo = trim($titulo);
		$desc = trim($desc);
	//--------
	//decodificaciones de tildes y �'s
		$titulo = utf8_encode($titulo);
		$desc = utf8_encode($desc);
		//--
		$titulo = utf8_decode($titulo);
		$desc = utf8_decode($desc);
	//--------
   if($incidente != "" && $prioridad != "" && $sede != ""){
      $ticket = $ClsTic->max_ticket();
      $ticket++; /// Maximo codigo de Ticket
      $usuario = $_SESSION["codigo"];
      $status = $ClsSta->next_status_hd(0); /// obtiene el primer status activo despues de 0
      $sql = $ClsTic->insert_ticket($ticket,$desc,$incidente,$prioridad,$status,$sede,$sector,$area,$usuario); /// Inserta Ticket
      ///obtiene usuarios a encargarse
      $result = $ClsInc->get_usuario_incidente_sede($incidente,'',$sede);
      if(is_array($result)){
         foreach($result as $row){
            $usuario = $row["ius_usuario"];
            $sql.= $ClsTic->insert_asignacion($ticket,$usuario);
         }
      }
      ///pregunta si debe enviar SMS
      $result = $ClsPri->get_prioridad($prioridad);
      if(is_array($result)){
         foreach($result as $row){
            $sms = trim($row["pri_sms"]);
         }
      }else{
         $sms = 0;  
      }
      $sql.= $ClsTic->insert_ticket_status($ticket,$status,'Apertura de Ticket'); /// Inserta Ticket
      $sql.= $ClsTic->insert_bitacora(1,$ticket,'Apertura de Ticket',''); /// Inserta Ticket
         
		//$respuesta->alert("$sql");
		$rs = $ClsTic->exec_sql($sql);
		if($rs == 1){
         $respuesta->assign("ticket","value",$ticket);
         $respuesta->assign("posicion","value",$status);
         $respuesta->assign("sms","value",$sms);
         mail_usuario($ticket);
         if($imagen == true){
            $respuesta->script('swal("Excelente!", "Registros guardados satisfactoriamente!!!", "success").then((value)=>{ upLoad(); });');
         }else{
            if($sms == 1){
               //$respuesta->redirect("../SMS/EXEasigna.php?ticket=$ticket",0);
               $respuesta->script('swal("Excelente!", "Registros guardados satisfactoriamente!!!", "success").then((value)=>{ window.location.href="FRMtickets.php" });');
            }else{
               $respuesta->script('swal("Excelente!", "Registros guardados satisfactoriamente!!!", "success").then((value)=>{ window.location.href="FRMtickets.php" });');
            }
         }
		}else{
			$respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
         $respuesta->script('document.getElementById("gra").className = "btn btn-primary"');
		}
	}else{
		$respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      $respuesta->script('document.getElementById("gra").className = "btn btn-primary"');
	}
   
   return $respuesta;
}


function Modificar_Ticket($ticket,$desc,$incidente,$prioridad,$sede,$sector,$area,$imagen){
   //instanciamos el objeto para generar la respuesta con ajax
   $respuesta = new xajaxResponse();
   $ClsTic = new ClsTicket();
   $ClsSta = new ClsStatus();
   $ClsInc = new ClsIncidente();
   //trim a cadena
		$titulo = trim($titulo);
		$desc = trim($desc);
	//--------
	//decodificaciones de tildes y �'s
		$titulo = utf8_encode($titulo);
		$desc = utf8_encode($desc);
		//--
		$titulo = utf8_decode($titulo);
		$desc = utf8_decode($desc);
	//--------
   if($ticket != "" && $incidente != "" && $prioridad != "" && $sede != ""){
      $usuario = $_SESSION["codigo"];
      $status = $ClsSta->next_status_hd(0); /// obtiene el primer status activo despues de 0
      $sql = $ClsTic->insert_ticket($ticket,$desc,$incidente,$prioridad,$status,$sede,$sector,$area,$usuario); /// Inserta Ticket
      //---Bitacora
      $codBit = $ClsTic->max_bitacora($ticket);
      $codBit++;
      $sql.= $ClsTic->insert_bitacora($codBit,$ticket,'Actualiza datos del Ticket',''); /// Inserta Ticket
      
      //$respuesta->alert("$sql");
		$rs = $ClsTic->exec_sql($sql);
		if($rs == 1){
			if($imagen == true){
            $respuesta->script('swal("Excelente!", "Registros actualizados satisfactoriamente!!!", "success").then((value)=>{ upLoad(); });');
         }else{
            $respuesta->script('swal("Excelente!", "Registros actualizados satisfactoriamente!!!", "success").then((value)=>{ window.location.href="FRMtickets.php"; });');
         }
		}else{
			$respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
         $respuesta->script('document.getElementById("gra").className = "btn btn-primary"');
		}
	}else{
		$respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      $respuesta->script('document.getElementById("gra").className = "btn btn-primary"');
	}
   
   return $respuesta;
}


function Cerrar_Ticket($codigo){
   $respuesta = new xajaxResponse();
   $ClsTic = new ClsTicket();
	if($codigo != ""){
      ///limpia el codigo
      $codigo = intval($codigo);
      $sql = $ClsTic->cambia_sit_ticket($codigo,100);
      $sql.= $ClsTic->insert_ticket_status($codigo,100,'Cerrado'); /// Inserta Ticket
      $bitcod = $ClsTic->max_bitacora($codigo);
      $bitcod++;
      $sql.= $ClsTic->insert_bitacora($bitcod,$codigo,"Cierra el Ticket",''); /// Inserta Ticket
      $sql.= $ClsTic->cerrar_ticket($codigo);
		//$respuesta->alert($sql);
		$rs = $ClsTic->exec_sql($sql);
      if($rs == 1){
         $respuesta->script('swal("Ok!", "Ticket cerrado satisfactoriamente!!!", "success").then((value)=>{ window.location.reload(); });');
      }else{
         $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
         $respuesta->script('document.getElementById("mod").className = "btn btn-primary"');
      }	
	}else{
		$respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      $respuesta->script('document.getElementById("mod").className = "btn btn-primary"');
	}
   		
   return $respuesta;
}


//////////////
function Cambiar_Status($codigo,$status,$obs){
   $respuesta = new xajaxResponse();
   $ClsTic = new ClsTicket();
   $ClsSta = new ClsStatus();
   //trim a cadena
		$obs = trim($obs);
	//--------
	//decodificaciones de tildes y �'s
		$obs = utf8_encode($obs);
		//--
		$obs = utf8_decode($obs);
	//--------
   if($codigo != ""){
      if($status != ""){
         $result = $ClsSta->get_status_hd($status);
         if(is_array($result)){
            foreach($result as $row){
               $status_nombre = trim($row["sta_nombre"]);
            }
            ///limpia el codigo
            $codigo = intval($codigo);
            $sql = $ClsTic->cambia_sit_ticket($codigo,$status);
            $sql.= $ClsTic->insert_ticket_status($codigo,$status,$status_nombre); /// Inserta Ticket
            $bitcod = $ClsTic->max_bitacora($codigo);
            $bitcod++;
            $sql.= $ClsTic->insert_bitacora($bitcod,$codigo,"Cambio de Status ($status_nombre)",$obs); /// Inserta Ticket
            ///calcula tiempo de espera
            $arrstatus = $ClsTic->get_ultimo_status($codigo);
            $ultimo_status = $arrstatus["status"];
            $fecha = $arrstatus["fecha"];
            //$respuesta->alert("$ultimo_status $fecha");
            if($ultimo_status == 50){ ///// Cuenta tiempo de espera
               $ahora = date('Y-m-d H:i:s');
               //$respuesta->alert("$fecha    $ahora");
               $date1 = new DateTime($fecha);
               $date2 = new DateTime($ahora);
               $interval = $date1->diff($date2);
               $minutos = $interval->format('%i');
               //$respuesta->alert("$minutos");
               $sql.= $ClsTic->insert_espera($codigo,$minutos);
            }
            if($status == 100){ ///// CERRAR TICKET
               $sql.= $ClsTic->cerrar_ticket($codigo);
            }
            //$respuesta->alert($sql);
            $rs = $ClsTic->exec_sql($sql);
            if($rs == 1){
               $respuesta->script('swal("Ok!", "Cambio de Status satisfactoriamente!!!", "success").then((value)=>{ window.location.reload(); });');
            }else{
               $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
            }	
         }else{
            $respuesta->script('swal("Error", "Este Status no est\u00E1 identificado entre los habilitados...", "error").then((value)=>{ cerrar(); });');
         }
      }else{
         $respuesta->script('swal("Error", "No ha seleccionado un status a cambiar...", "error").then((value)=>{ cerrar(); });');
      }   
	}else{
		$respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
   }
   		
   return $respuesta;
}



function Agregar_Usuario($codigo,$usuario){
   $respuesta = new xajaxResponse();
   $ClsTic = new ClsTicket();
   $ClsUsu = new ClsUsuario();
   
	if($codigo != ""){
      $result = $ClsUsu->get_usuario($usuario);
      if(is_array($result)){
         foreach($result as $row){
            $nombre_usuario = depurador_texto($row["usu_nombre"]);
         }
         $sql.= $ClsTic->insert_asignacion($codigo,$usuario); /// Inserta Ticket
         $bitcod = $ClsTic->max_bitacora($codigo);
         $bitcod++;
         $sql.= $ClsTic->insert_bitacora($bitcod,$codigo,"Agrega a $nombre_usuario al caso",''); /// Inserta Ticket
         //$respuesta->alert($sql);
         $rs = $ClsTic->exec_sql($sql);
         if($rs == 1){
            mail_usuario($codigo,$usuario);
            $respuesta->script('swal("Ok!", "Usuario asignado satisfactoriamente!!!", "success").then((value)=>{ window.location.reload(); });');
         }else{
            $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
         }	
      }else{
         $respuesta->script('swal("Error", "Este usuairo no est\u00E1 activo en nuestros registros....", "error").then((value)=>{ cerrar(); });');
      }
	}else{
		$respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      $respuesta->script('document.getElementById("mod").className = "btn btn-primary"');
	}
   		
   return $respuesta;
}


function Trasladar_Usuario($codigo,$usuario){
   $respuesta = new xajaxResponse();
   $ClsTic = new ClsTicket();
   $ClsUsu = new ClsUsuario();
   
	if($codigo != ""){
      $sql = "";
      ///////// deshabilita a usuarios activos para el traslado
      $result = $ClsTic->get_asignacion($codigo,'',1);
      if(is_array($result)){
         foreach($result as $row){
            $usuario_actual = trim($row["asi_usuario"]);
            $sql.= $ClsTic->cambia_sit_asignacion($codigo,$usuario_actual,2); /// Inserta Ticket
         }
      }
      ////// asigna al nuevo usaurio
      $result = $ClsUsu->get_usuario($usuario);
      if(is_array($result)){
         foreach($result as $row){
            $nombre_usuario = depurador_texto($row["usu_nombre"]);
         }
         $sql.= $ClsTic->insert_asignacion($codigo,$usuario); /// Inserta Ticket
         $bitcod = $ClsTic->max_bitacora($codigo);
         $bitcod++;
         $sql.= $ClsTic->insert_bitacora($bitcod,$codigo,"Se traslada el caso y se agrega a $nombre_usuario",''); /// Inserta Ticket
         //$respuesta->alert($sql);
         $rs = $ClsTic->exec_sql($sql);
         if($rs == 1){
            mail_usuario($codigo,$usuario);
            $respuesta->script('swal("Ok!", "Caso trasladado satisfactoriamente!!!", "success").then((value)=>{ window.location.reload(); });');
         }else{
            $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
         }	
      }else{
         $respuesta->script('swal("Error", "Este usuairo no est\u00E1 activo en nuestros registros....", "error").then((value)=>{ cerrar(); });');
      }
	}else{
		$respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      $respuesta->script('document.getElementById("mod").className = "btn btn-primary"');
	}
   		
   return $respuesta;
}


function Salir_Usuario($codigo,$usuario){
   $respuesta = new xajaxResponse();
   $ClsTic = new ClsTicket();
   $ClsUsu = new ClsUsuario();
   
	if($codigo != "" && $usuario != ""){
      $result = $ClsUsu->get_usuario($usuario);
      if(is_array($result)){
         foreach($result as $row){
            $nombre_usuario = utf8_decode($row["usu_nombre"]);
         }
         $sql.= $ClsTic->cambia_sit_asignacion($codigo,$usuario,2); /// cambia situacion 
         $bitcod = $ClsTic->max_bitacora($codigo);
         $bitcod++;
         $sql.= $ClsTic->insert_bitacora($bitcod,$codigo,"El Usuario $nombre_usuario traslado (deja el caso a otro usuario)",''); /// Inserta Ticket
         $rs = $ClsTic->exec_sql($sql);
         //$respuesta->alert($rs);
         if($rs == 1){
            $respuesta->script('swal("Ok!", "Caso trasladado satisfactoriamente!!!", "success").then((value)=>{ window.location.reload(); });');
         }else{
            $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
         }	
      }else{
         $respuesta->script('swal("Error", "Este usuairo no est\u00E1 activo en nuestros registros....", "error").then((value)=>{ cerrar(); });');
      }
	}else{
		$respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
   }
   		
   return $respuesta;
}

//////////////////---- TICKET -----/////////////////////////////////////////////
$xajax->register(XAJAX_FUNCTION, "Grabar_Ticket");
$xajax->register(XAJAX_FUNCTION, "Modificar_Ticket");
$xajax->register(XAJAX_FUNCTION, "Cerrar_Ticket");
//////////////---
$xajax->register(XAJAX_FUNCTION, "Cambiar_Status");
$xajax->register(XAJAX_FUNCTION, "Agregar_Usuario");
$xajax->register(XAJAX_FUNCTION, "Trasladar_Usuario");
$xajax->register(XAJAX_FUNCTION, "Salir_Usuario");

//El objeto xajax tiene que procesar cualquier petici&oacute;n
$xajax->processRequest();?>  