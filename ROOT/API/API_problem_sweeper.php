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
		case "get_areas":
			get_areas();
			break;
		case "get_incidentes":
			get_incidentes();
			break;
		case "get_prioridades":
			get_prioridades();
			break;
		case "get_status":
			get_status();
			break;
		case "get_usuarios":
			$sedes_IN = $_REQUEST["sedes_in"]; //viene en el login y se almacena en el localstorage (Separados por coma ',' Eje. 1,2,3)
			get_usuarios($sedes_IN);
			break;
		///-----
		case "tickets_asignados":
			$usuario = $_REQUEST["usuario"];
			tickets_asignados($usuario);
			break;
		case "tickets_reportados":
			$usuario = $_REQUEST["usuario"];
			tickets_reportados($usuario);
			break;
		///---
		case "grabar":
			$sede = $_REQUEST["sede"];
			$categoria = $_REQUEST["categoria"];
			$area = $_REQUEST["area"];
			$sector = $_REQUEST["sector"];
			$incidente = $_REQUEST["incidente"];
			$prioridad = $_REQUEST["prioridad"];
			$descripcion = $_REQUEST["descripcion"];
			grabar_ticket($sede, $categoria, $area, $sector, $incidente, $prioridad, $descripcion);
			break;
		case "modificar":
			$ticket = $_REQUEST["ticket"];
			$sede = $_REQUEST["sede"];
			$categoria = $_REQUEST["categoria"];
			$area = $_REQUEST["area"];
			$sector = $_REQUEST["sector"];
			$incidente = $_REQUEST["incidente"];
			$prioridad = $_REQUEST["prioridad"];
			$descripcion = $_REQUEST["descripcion"];
			modificar_ticket($ticket, $sede, $categoria, $area, $sector, $incidente, $prioridad, $descripcion);
			break;
		case "agregar_usuario":
			$ticket = $_REQUEST["ticket"];
			$usuario = $_REQUEST["usuario"];
			agregar_usuario($ticket, $usuario);
			break;
		case "trasladar_usuario":
			$ticket = $_REQUEST["ticket"];
			$usuario = $_REQUEST["usuario"];
			trasladar_usuario($ticket, $usuario);
			break;
		case "salir_usuario":
			$ticket = $_REQUEST["ticket"];
			$usuario = $_REQUEST["usuario"];
			salir_usuario($ticket, $usuario);
			break;
		case "cerrar_ticket":
			$ticket = $_REQUEST["ticket"];
			cerrar_ticket($ticket);
			break;
		case "cambiar_status":
			$ticket = $_REQUEST["ticket"];
			$status = $_REQUEST["status"];
			$observacion = $_REQUEST["observacion"];
			cambiar_status($ticket, $status, $observacion);
			break;
		default:
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Seleccione un metodo..."
			);
			echo json_encode($arr_respuesta);
	}
}else{
	//devuelve un mensaje de manejo de errores
	$payload = array(
		"status" => false,
		"data" => [],
		"message" => "Delimite el tipo de consulta a realizar...");
		echo json_encode($payload);
}

////////////////// BASE /////////////////////////

function get_areas(){
	$ClsAre = new ClsArea();
	$result = $ClsAre->get_area('', '', '', '', '', 1);
	$i = 0;
	$arr_data = array();
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data[$i]["sede"] = intval($row["are_sede"]);
			$arr_data[$i]["sector"] = intval($row["are_sector"]);
			$arr_data[$i]["area"] = intval($row["are_codigo"]);
			$arr_data[$i]["nombre"] = trim($row["are_nombre"]);
			$arr_data[$i]["nivel"] = trim($row["are_nivel"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"message" => ""
		);
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Aún no hay datos registrados..."
		);
	}
	echo json_encode($arr_respuesta);
}


function get_incidentes(){
	$ClsInc = new ClsIncidente();
	$i = 0;
	$arr_data = array();
	$result = $ClsInc->get_incidente('', '', '', '', 1);
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data[$i]["categoria"] = intval($row["inc_categoria"]);
			$arr_data[$i]["incidente"] = intval($row["inc_codigo"]);
			$arr_data[$i]["prioridad"] = intval($row["inc_prioridad"]);
			$arr_data[$i]["nombre"] = trim($row["inc_nombre"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"message" => ""
		);
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Aún no hay datos registrados..."
		);
	}
	echo json_encode($arr_respuesta);
}



function get_prioridades(){
	$ClsPri = new ClsPrioridad();
	$i = 0;
	$arr_data = array();
	$result = $ClsPri->get_prioridad('', '', 1);
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data[$i]["prioridad"] = intval($row["pri_codigo"]);
			$arr_data[$i]["nombre"] = trim($row["pri_nombre"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"message" => ""
		);
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Aún no hay datos registrados..."
		);
	}
	echo json_encode($arr_respuesta);
}



function get_status(){
	$ClsSta = new ClsStatus();
	$i = 0;
	$arr_data = array();
	$result = $ClsSta->get_status_hd('', '', '', 1);
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data[$i]["status"] = intval($row["sta_codigo"]);
			$arr_data[$i]["nombre"] = trim($row["sta_nombre"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"message" => ""
		);
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Aún no hay datos registrados..."
		);
	}
	echo json_encode($arr_respuesta);
}


function get_usuarios($sedes_IN){
	$i = 0;
	$arr_data = array();
	$ClsUsu = new ClsUsuario();
	$result = $ClsUsu->get_usuario_sede_combo($sedes_IN);
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data[$i]["codigo"] = intval($row["usu_id"]);
			$arr_data[$i]["nombre"] = trim($row["usu_nombre"]);
			$i++;
		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"message" => ""
		);
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Aún no hay datos registrados..."
		);
	}
	echo json_encode($arr_respuesta);
}


////////////////// HELPDESK /////////////////////////

function tickets_asignados($usuario){
	$ClsTic = new ClsTicket();
	if($usuario != ""){
		$i=0;
		$result = $ClsTic->get_ticket_asignado('','','','','','','','',$usuario,1,1);
		if(is_array($result)){
			foreach($result as $row){
			    $ticket = trim($row["tic_codigo"]);
				$arr_data[$i]['codigo'] = intval($row["tic_codigo"]);
				$arr_data[$i]['incidente'] = trim($row["inc_nombre"]);
				$arr_data[$i]['descripcion'] = trim($row["tic_descripcion"]);
				//--
				$arr_data[$i]['sede'] = trim($row["sed_nombre"]);
				$arr_data[$i]['sector'] = trim($row["sec_nombre"]);
				$arr_data[$i]['area'] = trim($row["are_nombre"]);
				$arr_data[$i]['nivel'] = trim($row["are_nivel"]);
				//prioridad
				$arr_data[$i]['categoria'] = trim($row["cat_nombre"]);
				$arr_data[$i]['prioridad'] = trim($row["pri_nombre"]);
				$arr_data[$i]['tiempo_respuesta'] = substr($row["pri_respuesta"],0,5);
				$arr_data[$i]['tiempo_solucion'] = substr($row["pri_solucion"],0,5);
				//fecha de registro
				$arr_data[$i]['fecha_registro'] = cambia_fechaHora($row["tic_fecha_registro"]);
				//status
				$arr_data[$i]['status'] = trim($row["sta_nombre"]);
				//tiempo en conteo
				$respuesta = trim($row["tic_primer_status"]);
				$cierre = trim($row["tic_cierre_status"]);
				$espera = trim($row["tic_espera"]);
				/////////// RESPUESTA /////////
				if($respuesta != ""){
					$freg = trim($row["tic_fecha_registro"]);
					$date1 = new DateTime($freg);
					$date2 = new DateTime($respuesta);
					$interval = $date1->diff($date2);
					$arr_data[$i]['primera_respuesta'] = $interval->format('%H:%I');
				}else{
					$arr_data[$i]['primera_respuesta'] = '- Pendiente de respuesta -';
				}
				/////////// SOLUCION /////////
				if($cierre != ""){
					$freg = trim($row["tic_fecha_registro"]);
					$date1 = new DateTime($freg);
					$date2 = new DateTime($cierre);
					$interval = $date1->diff($date2);
					$Solucion = $interval->format('%H:%I');
					if($espera != ""){
						$Solucion = date($Solucion);
						$Solucion = strtotime ( "-$espera minutes" , strtotime ( $Solucion ) ) ;
						$arr_data[$i]['status_solucion'] = date ( 'H:i' , $Solucion );
					}else{
						$arr_data[$i]['status_solucion'] = $Solucion;
					}
				}else{
					$arr_data[$i]['status_solucion'] = '- Pendiente de Solución -';
				}
				/////////// Tiempo de Espera /////////
				if($espera != ""){
					$arr_data[$i]['tiempo_espera'] = "$espera minutos";
				}else{
					$arr_data[$i]['tiempo_espera'] = ' --- ';
				}
				/////////// Usuarios /////////
				$j = 0;	
				$arr_usuarios = array();
				$result_usuarios = $ClsTic->get_asignacion($ticket,'',1);
				if(is_array($result_usuarios)){
					foreach ($result_usuarios as $row_usuarios){
						$arr_usuarios[$j]['codigo'] = trim($row_usuarios["usu_id"]);
						$arr_usuarios[$j]['nombre'] = trim($row_usuarios["usu_nombre"]);
						$arr_usuarios[$j]['fecha_asignacion'] = cambia_fechaHora($row_usuarios["asi_fecha_registro"]);
						$j++;
					}
				}
				$arr_data[$i]['usuarios_asignados'] = $arr_usuarios;
				/////////// Bitacora /////////
				$j = 0;	
				$arr_bitacora = array();
				$result_bitacora = $ClsTic->get_bitacora('',$ticket);
				if(is_array($result_bitacora)){
					foreach ($result_bitacora as $row_bitacora){
						$arr_bitacora[$j]['actividad'] = trim($row_bitacora["bit_descripcion"]);
						$arr_bitacora[$j]['observaciones'] = trim($row_bitacora["bit_observaciones"]);
						$arr_bitacora[$j]['fecha_registro'] = cambia_fechaHora($row_bitacora["bit_fecha_registro"]);
						$j++;
					}
				}
				$arr_data[$i]['bitacora'] = $arr_bitacora;
				/////////// Imagenes /////////
				$j = 0;	
				$arr_imagenes = array();
				$result_img = $ClsTic->get_fotos('',$ticket);
				if(is_array($result_img)){
					foreach ($result_img as $row_img){
						$arr_imagenes[$j]['posicion'] = trim($row_img["fot_posicion"]);
						$arr_imagenes[$j]['status'] = trim($row_img["sta_nombre"]);
						$strFoto = trim($row_img["fot_foto"]);
						if(file_exists('../../CONFIG/Fotos/TICKET/'.$strFoto.'.jpg') || $strFoto != ""){ /// valida que tenga foto registrada
							$arr_imagenes[$i]['url_imagen'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/TICKET/$strFoto.jpg";
						}else{
							$arr_imagenes[$i]['url_imagen'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imagePhoto.jpg";
						}
						$j++;
					}
				}
				$arr_data[$i]['imagenes'] = $arr_imagenes;
				$i++;
			}
			//print_r($arr_data);
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


function tickets_reportados($usuario){
	$ClsTic = new ClsTicket();
	if($usuario != ""){
		$i=0;
		$result = $ClsTic->get_ticket('','','','','','','','',$usuario,1);
		if(is_array($result)){
			foreach($result as $row){
			    $ticket = trim($row["tic_codigo"]);
				$arr_data[$i]['codigo'] = intval($row["tic_codigo"]);
				$arr_data[$i]['incidente'] = trim($row["inc_nombre"]);
				$arr_data[$i]['descripcion'] = trim($row["tic_descripcion"]);
				//--
				$arr_data[$i]['sede'] = trim($row["sed_nombre"]);
				$arr_data[$i]['sector'] = trim($row["sec_nombre"]);
				$arr_data[$i]['area'] = trim($row["are_nombre"]);
				$arr_data[$i]['nivel'] = trim($row["are_nivel"]);
				//prioridad
				$arr_data[$i]['categoria'] = trim($row["cat_nombre"]);
				$arr_data[$i]['prioridad'] = trim($row["pri_nombre"]);
				$arr_data[$i]['tiempo_respuesta'] = substr($row["pri_respuesta"],0,5);
				$arr_data[$i]['tiempo_solucion'] = substr($row["pri_solucion"],0,5);
				//fecha de registro
				$arr_data[$i]['fecha_registro'] = cambia_fechaHora($row["tic_fecha_registro"]);
				//status
				$arr_data[$i]['status'] = trim($row["sta_nombre"]);
				//tiempo en conteo
				$respuesta = trim($row["tic_primer_status"]);
				$cierre = trim($row["tic_cierre_status"]);
				$espera = trim($row["tic_espera"]);
				/////////// RESPUESTA /////////
				if($respuesta != ""){
					$freg = trim($row["tic_fecha_registro"]);
					$date1 = new DateTime($freg);
					$date2 = new DateTime($respuesta);
					$interval = $date1->diff($date2);
					$arr_data[$i]['primera_respuesta'] = $interval->format('%H:%I');
				}else{
					$arr_data[$i]['primera_respuesta'] = '- Pendiente de respuesta -';
				}
				/////////// SOLUCION /////////
				if($cierre != ""){
					$freg = trim($row["tic_fecha_registro"]);
					$date1 = new DateTime($freg);
					$date2 = new DateTime($cierre);
					$interval = $date1->diff($date2);
					$Solucion = $interval->format('%H:%I');
					if($espera != ""){
						$Solucion = date($Solucion);
						$Solucion = strtotime ( "-$espera minutes" , strtotime ( $Solucion ) ) ;
						$arr_data[$i]['status_solucion'] = date ( 'H:i' , $Solucion );
					}else{
						$arr_data[$i]['status_solucion'] = $Solucion;
					}
				}else{
					$arr_data[$i]['status_solucion'] = '- Pendiente de Solución -';
				}
				/////////// Tiempo de Espera /////////
				if($espera != ""){
					$arr_data[$i]['tiempo_espera'] = "$espera minutos";
				}else{
					$arr_data[$i]['tiempo_espera'] = ' --- ';
				}
				/////////// Usuarios /////////
				$j = 0;	
				$arr_usuarios = array();
				$result_usuarios = $ClsTic->get_asignacion($ticket,'',1);
				if(is_array($result_usuarios)){
					foreach ($result_usuarios as $row_usuarios){
						$arr_usuarios[$j]['codigo'] = trim($row_usuarios["usu_id"]);
						$arr_usuarios[$j]['nombre'] = trim($row_usuarios["usu_nombre"]);
						$arr_usuarios[$j]['fecha_asignacion'] = cambia_fechaHora($row_usuarios["asi_fecha_registro"]);
						$j++;
					}
				}
				$arr_data[$i]['usuarios_asignados'] = $arr_usuarios;
				/////////// Bitacora /////////
				$j = 0;	
				$arr_bitacora = array();
				$result_bitacora = $ClsTic->get_bitacora('',$ticket);
				if(is_array($result_bitacora)){
					foreach ($result_bitacora as $row_bitacora){
						$arr_bitacora[$j]['actividad'] = trim($row_bitacora["bit_descripcion"]);
						$arr_bitacora[$j]['observaciones'] = trim($row_bitacora["bit_observaciones"]);
						$arr_bitacora[$j]['fecha_registro'] = cambia_fechaHora($row_bitacora["bit_fecha_registro"]);
						$j++;
					}
				}
				$arr_data[$i]['bitacora'] = $arr_bitacora;
				/////////// Imagenes /////////
				$j = 0;	
				$arr_imagenes = array();
				$result_img = $ClsTic->get_fotos('',$ticket);
				if(is_array($result_img)){
					foreach ($result_img as $row_img){
						$arr_imagenes[$j]['posicion'] = trim($row_img["fot_posicion"]);
						$arr_imagenes[$j]['status'] = trim($row_img["sta_nombre"]);
						$strFoto = trim($row_img["fot_foto"]);
						if(file_exists('../../CONFIG/Fotos/TICKET/'.$strFoto.'.jpg') || $strFoto != ""){ /// valida que tenga foto registrada
							$arr_imagenes[$i]['url_imagen'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/TICKET/$strFoto.jpg";
						}else{
							$arr_imagenes[$i]['url_imagen'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imagePhoto.jpg";
						}
						$j++;
					}
				}
				$arr_data[$i]['imagenes'] = $arr_imagenes;
				$i++;
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



function grabar_ticket($sede, $categoria, $area, $sector, $incidente, $prioridad, $descripcion)
{
	$ClsTic = new ClsTicket();
	$ClsSta = new ClsStatus();
	$ClsInc = new ClsIncidente();
	$ClsPri = new ClsPrioridad();
	$descripcion = trim(utf8_encode(trim($descripcion)));
	//--------
	if ($incidente != "" && $prioridad != "" && $sede != "") {
		$ticket = $ClsTic->max_ticket();
		$ticket++; /// Maximo ticket de Ticket
		$usuario = $_SESSION["codigo"];
		$status = $ClsSta->next_status_hd(0); /// obtiene el primer status activo despues de 0
		$sql = $ClsTic->insert_ticket($ticket, $descripcion, $incidente, $prioridad, $status, $sede, $sector, $area, $usuario); /// Inserta Ticket
		///obtiene usuarios a encargarse
		$result = $ClsInc->get_usuario_incidente_sede($incidente, '', $sede);
		if (is_array($result)) {
			foreach ($result as $row) {
				$usuario = $row["ius_usuario"];
				$sql .= $ClsTic->insert_asignacion($ticket, $usuario);
			}
		}
		///pregunta si debe enviar SMS
		$result = $ClsPri->get_prioridad($prioridad);
		if (is_array($result)) {
			foreach ($result as $row) {
				$sms = trim($row["pri_sms"]);
			}
		} else {
			$sms = 0;
		}
		$sql .= $ClsTic->insert_ticket_status($ticket, $status, 'Apertura de Ticket'); /// Inserta Ticket
		$sql .= $ClsTic->insert_bitacora(1, $ticket, 'Apertura de Ticket', ''); /// Inserta Ticket

		//$respuesta->alert("$sql");
		$rs = $ClsTic->exec_sql($sql);

		if ($rs == 1) {
			$arr_data["ticket"] = $ticket;
			$arr_data["status"] = $status;
			$arr_data["sms"] = $sms;

			$arr_respuesta = array(
				"status" => true,
				"data" => $arr_data,
				"pagina" => "FRMsolicitados.php",
				"message" => "Transaccion correcta !!!",
			);

			mail_usuario($ticket);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"message" => "Error en la transaccion..."
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los Campos Obligatorios..."
		);
	}
	echo json_encode($arr_respuesta);
}


function modificar_ticket($ticket, $sede, $categoria, $area, $sector, $incidente, $prioridad, $descripcion)
{
	$ClsTic = new ClsTicket();
	$ClsSta = new ClsStatus();
	$ClsInc = new ClsIncidente();
	//trim a cadena
	$desc = trim(utf8_encode(trim($descripcion)));
	//--
	if ($ticket != "" && $incidente != "" && $prioridad != "" && $sede != "") {
		$usuario = $_SESSION["codigo"];
		$status = $ClsSta->next_status_hd(0); /// obtiene el primer status activo despues de 0
		$sql = $ClsTic->insert_ticket($ticket, $desc, $incidente, $prioridad, $status, $sede, $sector, $area, $usuario); /// Inserta Ticket
		//---Bitacora
		$codBit = $ClsTic->max_bitacora($ticket);
		$codBit++;
		$sql .= $ClsTic->insert_bitacora($codBit, $ticket, 'Actualiza datos del Ticket', ''); /// Inserta Ticket

		//$respuesta->alert("$sql");
		$rs = $ClsTic->exec_sql($sql);
		if ($rs == 1) {

			$arr_respuesta = array(
				"status" => true,
				"message" => "Transaccion correcta !!!",
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"message" => "Error en la transaccion..."
		);
	}
	echo json_encode($arr_respuesta);
}

function agregar_usuario($ticket, $usuario)
{
	$ClsTic = new ClsTicket();
	$ClsUsu = new ClsUsuario();

	if ($ticket != "") {
		$result = $ClsUsu->get_usuario($usuario);
		if (is_array($result)) {
			foreach ($result as $row) {
				$nombre_usuario = trim($row["usu_nombre"]);
			}
			$sql = $ClsTic->insert_asignacion($ticket, $usuario); /// Inserta Ticket
			$bitcod = $ClsTic->max_bitacora($ticket);
			$bitcod++;
			$sql .= $ClsTic->insert_bitacora($bitcod, $ticket, "Agrega a $nombre_usuario al caso", ''); /// Inserta Ticket
			//$respuesta->alert($sql);
			$rs = $ClsTic->exec_sql($sql);
			if ($rs == 1) {
				mail_usuario($ticket, $usuario);

				$arr_respuesta = array(
					"status" => true,
					"message" => "Transaccion correcta !!!"
				);
			} else {
				$arr_respuesta = array(
					"status" => false,
					"message" => "Error en la transacci\u00F3n"
				);
			}
		} else {
			$arr_respuesta = array(
				"status" => false,
				"message" => "Este usuairo no est\u00E1 activo en nuestros registros...."
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"message" => "Error en la transacci\u00F3n"
		);
	}
	echo json_encode($arr_respuesta);
}

function salir_usuario($ticket, $usuario)
{
	$ClsTic = new ClsTicket();
	$ClsUsu = new ClsUsuario();

	if ($ticket != "" && $usuario != "") {
		$result = $ClsUsu->get_usuario($usuario);
		if (is_array($result)) {
			foreach ($result as $row) {
				$nombre_usuario = trim($row["usu_nombre"]);
			}
			$sql = $ClsTic->cambia_sit_asignacion($ticket, $usuario, 2); /// cambia situacion 
			$bitcod = $ClsTic->max_bitacora($ticket);
			$bitcod++;
			$sql .= $ClsTic->insert_bitacora($bitcod, $ticket, "El Usuario $nombre_usuario traslado (deja el caso a otro usuario)", ''); /// Inserta Ticket
			$rs = $ClsTic->exec_sql($sql);
			//$respuesta->alert($rs);
			if ($rs == 1) {
				$arr_respuesta = array(
					"status" => true,
					"message" => "Caso trasladado satisfactoriamente!!!"
				);
			} else {
				$arr_respuesta = array(
					"status" => false,
					"message" => "Error en la transacci\u00F3n"
				);
			}
		} else {
			$arr_respuesta = array(
				"status" => false,
				"message" => "Este usuairo no est\u00E1 activo en nuestros registros...."
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"message" => "Error en la transacci\u00F3n"
		);
	}

	echo json_encode($arr_respuesta);
}

function cerrar_ticket($ticket)
{
	$ClsTic = new ClsTicket();
	if ($ticket != "") {
		///limpia el codigo
		$codigo = intval($ticket);
		$sql = $ClsTic->cambia_sit_ticket($codigo, 100);
		$sql .= $ClsTic->insert_ticket_status($codigo, 100, 'Cerrado'); /// Inserta Ticket
		$bitcod = $ClsTic->max_bitacora($codigo);
		$bitcod++;
		$sql .= $ClsTic->insert_bitacora($bitcod, $codigo, "Cierra el Ticket", ''); /// Inserta Ticket
		$sql .= $ClsTic->cerrar_ticket($codigo);
		//$respuesta->alert($sql);
		$rs = $ClsTic->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"message" => "Ticket cerrado satisfactoriamente!!!"
			);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"message" => "Error en la transacci\u00F3n"
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"message" => "Error en la transacci\u00F3n"
		);
	}
	echo json_encode($arr_respuesta);
}

function trasladar_usuario($ticket, $usuario)
{
	$ClsTic = new ClsTicket();
	$ClsUsu = new ClsUsuario();

	if ($ticket != "") {
		$sql = "";
		///////// deshabilita a usuarios activos para el traslado
		$result = $ClsTic->get_asignacion($ticket, '', 1);
		if (is_array($result)) {
			foreach ($result as $row) {
				$usuario_actual = trim($row["asi_usuario"]);
				$sql .= $ClsTic->cambia_sit_asignacion($ticket, $usuario_actual, 2); /// Inserta Ticket
			}
		}
		////// asigna al nuevo usaurio
		$result = $ClsUsu->get_usuario($usuario);
		if (is_array($result)) {
			foreach ($result as $row) {
				$nombre_usuario = depurador_texto($row["usu_nombre"]);
			}
			$sql .= $ClsTic->insert_asignacion($ticket, $usuario); /// Inserta Ticket
			$bitcod = $ClsTic->max_bitacora($ticket);
			$bitcod++;
			$sql .= $ClsTic->insert_bitacora($bitcod, $ticket, "Se traslada el caso y se agrega a $nombre_usuario", ''); /// Inserta Ticket
			//$respuesta->alert($sql);
			$rs = $ClsTic->exec_sql($sql);
			if ($rs == 1) {
				mail_usuario($ticket, $usuario);
				$arr_respuesta = array(
					"status" => true,
					"message" => "Caso trasladado satisfactoriamente!!!"
				);
			} else {
				$arr_respuesta = array(
					"status" => false,
					"message" => "Error en la transacci\u00F3n"
				);
			}
		} else {
			$arr_respuesta = array(
				"status" => false,
				"message" => "Error en la transacci\u00F3n"
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"message" => "Error en la transacci\u00F3n"
		);
	}

	echo json_encode($arr_respuesta);
}


function cambiar_status($ticket, $status, $observacion)
{
	$ClsTic = new ClsTicket();
	$ClsSta = new ClsStatus();
	//trim a cadena modificando las tildes
	$obs = trim(utf8_encode(trim($observacion)));
	//--------
	if ($ticket != "") {
		if ($status != "") {
			$result = $ClsSta->get_status_hd($status);
			if (is_array($result)) {
				foreach ($result as $row) {
					$status_nombre = trim($row["sta_nombre"]);
				}
				///limpia el ticket
				$ticket = intval($ticket);
				$sql = $ClsTic->cambia_sit_ticket($ticket, $status);
				$sql .= $ClsTic->insert_ticket_status($ticket, $status, $status_nombre); /// Inserta Ticket
				$bitcod = $ClsTic->max_bitacora($ticket);
				$bitcod++;
				$sql .= $ClsTic->insert_bitacora($bitcod, $ticket, "Cambio de Status ($status_nombre)", $obs); /// Inserta Ticket
				///calcula tiempo de espera
				$arrstatus = $ClsTic->get_ultimo_status($ticket);
				$ultimo_status = $arrstatus["status"];
				$fecha = $arrstatus["fecha"];
				//$respuesta->alert("$ultimo_status $fecha");
				if ($ultimo_status == 50) { ///// Cuenta tiempo de espera
					$ahora = date('Y-m-d H:i:s');
					//$respuesta->alert("$fecha    $ahora");
					$date1 = new DateTime($fecha);
					$date2 = new DateTime($ahora);
					$interval = $date1->diff($date2);
					$minutos = $interval->format('%i');
					//$respuesta->alert("$minutos");
					$sql .= $ClsTic->insert_espera($ticket, $minutos);
				}
				if ($status == 100) { ///// CERRAR TICKET
					$sql .= $ClsTic->cerrar_ticket($ticket);
				}
				//$respuesta->alert($sql);
				$rs = $ClsTic->exec_sql($sql);
				if ($rs == 1) {
					$arr_respuesta = array(
						"status" => true,
						"message" => "Cambio de Status satisfactoriamente!!!"
					);
				} else {
					$arr_respuesta = array(
						"status" => false,
						"message" => "Error en la transacci\u00F3n"
					);
				}
			} else {
				$arr_respuesta = array(
					"status" => false,
					"message" => "Este Status no est\u00E1 identificado entre los habilitados..."
				);
			}
		} else {
			$arr_respuesta = array(
				"status" => false,
				"message" => "No ha seleccionado un status a cambiar..."
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"message" => "Error en la transacci\u00F3n"
		);
	}
	echo json_encode($arr_respuesta);
}
