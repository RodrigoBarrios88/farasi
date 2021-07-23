<?php

/**
*
* @title API para APP de auditoria
* @author Andy Gomez (plani-go.com)
* @comments API para el control total de la APP de auditoria
*
*/

ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cache");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);
// error_reporting(E_ALL);

//--
include_once('html_fns_api.php');
include_once('../CPAUDEJECUCION/html_fns_ejecucion.php');
require ("../xajax_core/xajax.inc.php");

header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header("Access-Control-Allow-Origin: *");
header("Set-Cookie: PHPSESSID=cjlk1o4giv3dtt3en71jcr578d; path=/");

///API REQUEST
$request = $_REQUEST["request"];
$_REQUEST = str_replace("undefined", "", $_REQUEST); ///valida campos "undefined" desde javascript

if($request != ""){
	switch($request){
		case "listas":
			$usuario = htmlspecialchars($_REQUEST["usuario"]);
			$sede = htmlspecialchars($_REQUEST["sede"]);
			$status = htmlspecialchars($_REQUEST["status"]);
			$fini = htmlspecialchars($_REQUEST["fini"]);
			$ffin = htmlspecialchars($_REQUEST["ffin"]);

			$is_asignados = htmlspecialchars($_REQUEST["asign"]);

			if ($is_asignados==1){
				echo view_tickets($usuario, ($sede == 0 ? '' : $sede), $status, $fini, $ffin);
			} else {
				view_tickets_asignados($usuario, (isset($sede) ? '' : $sede), $status, $fini, $ffin);
			}
			break;
		case "quiz":
			$codigo = $_REQUEST["codigo"];
			$usuario = $_REQUEST["seccion"];
			API_quiz($codigo,$usuario);
			break;
		case "functions":

			$function = $_REQUEST['xjxfun'];
			$arg = $_REQUEST['xjxargs'];
			if ($function == 'Salir_Usuario') {
				echo Salir_Usuario($arg[0],$arg[1]);
			} else if ($function == 'Trasladar_Usuario') {
				echo Trasladar_Usuario($arg[0],$arg[1]);
			} else if ($function == 'Agregar_Usuario') {
				echo Agregar_Usuario($arg[0],$arg[1]);
			} else if ($function == 'Cambiar_Status') {
				echo Cambiar_Status($arg[0],$arg[1]);
			} else if ($function == 'Cerrar_Ticket') {
				echo Cerrar_Ticket($arg[0]);
			} else if ($function == 'Modificar_Ticket') {
				echo Modificar_Ticket($arg[0], $arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7]);
			} else if ($function == 'Grabar_Ticket') {
				echo Grabar_Ticket($arg[0], $arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6]);
			}

			break;
		case "forms":

			// Gets
			$sede_get = $_REQUEST['sede'];
			$categoria_get = $_REQUEST['cat'];
			$prioridad_get = $_REQUEST['prio'];
			$users_get = $_REQUEST['users'];
			$situacion = $_REQUEST['sit'];

			// Get Data
			if (!$users_get) {
				$ClsSed = new ClsSede();
				$sedes = $ClsSed->get_sede('','','','',1);
			}

			if ($sede_get) {
				if (!$users_get) {
					$ClsAre = new ClsArea();
					$areas = $ClsAre->get_area('',$sede_get,'','','',1);

					$ClsDep = new ClsDepartamento();
					$departamento = $ClsDep->get_departamento("");
				}

				$ClsUsu = new ClsUsuario();
				$usuarios = $ClsUsu->get_usuario_sede('','',$sede_get,'','');

				if (!$users_get) {
					$ClsSec = new ClsSector();
					$sectores = $ClsSec->get_sector('',$sede_get,'',1);

					$ClsCat = new ClsCategoria();
					$categorias = $ClsCat->get_categoria_helpdesk('','',1);

					$ClsPri = new ClsPrioridad();
					$prioridad = $ClsPri->get_prioridad($situacion,'',1);

					if ($categoria_get){
						$ClsInc = new ClsIncidente();
						$incidentes = $ClsInc->get_incidente($codigo,$categoria_get,$prioridad_get,'',1);
					}
				}

			}

			$payload = array(
			"status" => false,
			"data" => array(
				'sedes' => $sedes,
				'areas' => $areas,
				'depart' => $departamento,
				'usuarios' => $usuarios,
				'sectores' => $sectores,
				'categorias' => $categorias,
				'prioridad' => $prioridad,
				'incidentes' => $incidentes
			),
			"message" => "Parametros invalidos...");
			echo safe_json_encode($payload);

			break;
		case "foto":
			 // Fecha del Sistema
			$tamano = $_FILES["photo"]['size'];
			$tipo = $_FILES["photo"]['type'];
			$archivo = $_FILES["photo"]['name'];
			$ticket = $_REQUEST["ticket"];
			$status = $_REQUEST["posicion"]; // posucion en el flujo del tramite del ticket (1->Foto inicial, 2->Foto Final)
			var_dump('todo es: status,' . $status . ' & ticketm,' . $ticket);
			
			$ClsSta = new ClsStatus();
			$result = $ClsSta->get_status_hd($status,'','',1);
			if(is_array($result)){
				foreach($result as $row){
					$posicion = trim($row["sta_posicion"]);
					$status_nombre = trim($row["sta_nombre"]);
				}	
			}	
			
			// Upload
			if ($archivo != "") {
				$ClsTic = new ClsTicket();
				$stringFoto = str_shuffle($ticket.uniqid());
				$codigo = $ClsTic->max_foto();
				$codigo++;
				$sql = $ClsTic->insert_foto($codigo,$ticket,$posicion,$stringFoto);
				/////// CAMBIO DE STATUS
				$sql.= $ClsTic->cambia_sit_ticket($ticket,$status);
				$sql.= $ClsTic->insert_ticket_status($ticket,$status,$status_nombre); /// Inserta Ticket
				$bitcod = $ClsTic->max_bitacora($ticket);
				$bitcod++;
				$sql.= $ClsTic->insert_bitacora($bitcod,$ticket,"Cambio de Status ($status_nombre)", ''); /// Inserta Ticket
				if($status == 100){ ///// CERRAR TICKET
					$sql.= $ClsTic->cerrar_ticket($ticket);
				}
				//echo $sql."<br>";
				$rs = $ClsTic->exec_sql($sql);
				if($rs == 1){
					// guardamos el archivo a la carpeta files
					$destino =  "../../CONFIG/Fotos/TICKET/".$stringFoto.".jpg";
					if (move_uploaded_file($_FILES['photo']['tmp_name'],$destino)) {
						//////////// -------- Convierte todas las imagenes a JPEG
						// Abrimos una Imagen PNG
						//$mime_type = mime_content_type($destino);
						//Valida si es un PNG
						if($mime_type == "image/png"){
							$imagen = imagecreatefrompng($destino); // si es, convierte a JPG
							imagejpeg($imagen,$destino,100); // Creamos la Imagen JPG a partir de la PNG u otra que venga
						}
						/// redimensionando
						$image = new ImageResize($destino);
						$image->resizeToWidth(300);
						$image->save($destino);
						///
						echo $stringFoto;
						if($posicion == 1){
							echo "1";
						}else{
							echo "1";
						}
						
					}else {
						echo "Error al subir el archivo, " . $_FILES['photo']['tmp_name']; $status = 0;
					}
				}else{
					echo "Error al registrar el archivo en la BD: " . $sql; $status = 0;
				}
				//echo $sql;
			} else {
				echo "Archivo vacio.";  $status = 0;
			}
			
			break;
		case "firma":
			API_firma();
			break;
		case 'fotos':
			$auditoria = $_REQUEST['aud'];
			$ejecucion = $_REQUEST['ejec'];
			$pregunta = $_REQUEST['preg'];
			API_getFotos($auditoria, $ejecucion, $pregunta);
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

function API_quiz ($codigo, $usuario) {
	$ClsTicket = new ClsTicket();
	$ticket_info = $ClsTicket->get_ticket($codigo, '', '', '', '', '','', '', $usuario);
	if(is_array($ticket_info)){
		$ClsInc = new ClsIncidente();
		$ClsPri = new ClsPrioridad();
		$i = 0; foreach ($ticket_info AS $row) {
			$get_asignacion = $ClsTicket->get_asignacion($row['tic_codigo'], $usuario);
			$ticket_info[$i]['asignacion'] = $get_asignacion;
			$get_bitacora = $ClsTicket->get_bitacora('',$row['tic_codigo']);
			$ticket_info[$i]['bitacora'] = $get_bitacora;

			$get_fotos = $ClsTicket->get_fotos('',$row['tic_codigo']);
			$get_fotos = (is_array($get_fotos) ? $get_fotos : array());
			$ticket_info[$i]['fotos'] = $get_fotos;

			$incidente = $ClsInc->get_incidente($row['tic_incidente']);
			$ticket_info[$i]['incidente'] = $incidente[0]['inc_nombre'];
			$prioridad = $ClsPri->get_prioridad($row['tic_prioridad'],'',1);
			$ticket_info[$i]['prioridad'] = $prioridad[0]['pri_nombre'];
		}
	}

	$payload = array(
			"status" => (boolean)$ticket_info,
			"data" => $ticket_info,
			"message" => '');
	echo safe_json_encode($payload);
}

function view_tickets ($usuario, $sede = '', $status = '', $fini = '', $ffin = '') {
	$ClsTicket = new ClsTicket();

	$list_ticket = $ClsTicket->get_ticket('','',$sede,'','',$status,$fini,$ffin,$usuario, 1);

	$array_list = array();
	$count_list = array(
		1 => 0,
		2 => 0,
		3 => 0
	);
	if(is_array($list_ticket)){
		$ClsInc = new ClsIncidente();
		$ClsPri = new ClsPrioridad();

		$i = 0; foreach ($list_ticket AS $row) {
			$array_list[$i] = array();
			$array_list[$i]['extra'] = $row;
			$array_list[$i]['name'] = $row['inc_nombre'];
			$array_list[$i]['codigo'] = $row['tic_codigo'];
			$array_list[$i]['prioridad'] = $row['tic_prioridad'];
			$array_list[$i]['status'] = $row['sta_nombre'];
			$array_list[$i]['status_cod'] = $row['sta_codigo'];
			$array_list[$i]['fecha'] = cambia_fechaHora($row["tic_fecha_registro"]);
			$incidente = $ClsInc->get_incidente($row['tic_incidente']);
			$array_list[$i]['incidente'] = $incidente[0]['inc_nombre'];
			$prioridad = $ClsPri->get_prioridad($row['tic_prioridad'],'',1);
			$array_list[$i]['prioridad'] = $prioridad[0]['pri_nombre'];
			$count_list[$row['sta_codigo']] += 1;
			$i++;
		}
	} else {
		$list_ticket = array();
	}


	$message_list = $count_list;

	$ClsUsuario = new ClsUsuario();
	$result_sedes = $ClsUsuario->get_usuario_sede('',$usuario,'','',1);
	$sedes_list = $result_sedes;

	$ClsSta = new ClsStatus();
	$result_status = $ClsSta->get_status_hd('','','',1);
	$status_list = $result_status;

	$ClsPermiso = new ClsPermiso();
	$permisses_list = $ClsPermiso->get_asi_permisos($usuario);

	$payload = array(
			"status" => (boolean)$array_list,
			"data" => $array_list,
			"message" => $message_list,
			"sedes" => $sedes_list,
			"status" => $status_list,
			"permisses" => $permisses_list);
	header('Content-Type: application/json');

	// die(json_encode($payload));
	echo safe_json_encode($payload);
}

function view_tickets_asignados ($usuario, $sede = '', $status = '', $fini = '', $ffin = '') {
	$ClsTicket = new ClsTicket();

	$list_ticket = $ClsTicket->get_ticket_asignado('','',$sede,'','','',$fini,$ffin,$usuario, 1, 1);

	$array_list = array();
	$count_list = array(
		1 => 0,
		2 => 0,
		3 => 0
	);
	if(is_array($list_ticket)){
		$ClsInc = new ClsIncidente();
		$ClsPri = new ClsPrioridad();

		$i = 0; foreach ($list_ticket AS $row) {
			$array_list[$i] = array();
			$array_list[$i]['extra'] = $row;
			$array_list[$i]['name'] = $row['inc_nombre'];
			$array_list[$i]['codigo'] = $row['tic_codigo'];
			$array_list[$i]['prioridad'] = $row['tic_prioridad'];
			$array_list[$i]['status'] = $row['sta_nombre'];
			$array_list[$i]['status_cod'] = $row['sta_codigo'];
			$array_list[$i]['fecha'] = cambia_fechaHora($row["tic_fecha_registro"]);
			$incidente = $ClsInc->get_incidente($row['tic_incidente']);
			$array_list[$i]['incidente'] = $incidente[0]['inc_nombre'];
			$prioridad = $ClsPri->get_prioridad($row['tic_prioridad'],'',1);
			$array_list[$i]['prioridad'] = $prioridad[0]['pri_nombre'];
			$count_list[$row['sta_codigo']] += 1;
			$i++;
		}
	} else {
		$list_ticket = array();
	}


	$message_list = $count_list;

	$ClsUsuario = new ClsUsuario();
	$result_sedes = $ClsUsuario->get_usuario_sede('',$usuario,'','',1);
	$sedes_list = $result_sedes;

	$ClsSta = new ClsStatus();
	$result_status = $ClsSta->get_status_hd('','','',1);
	$status_list = $result_status;

	$ClsPermiso = new ClsPermiso();
	$permisses_list = $ClsPermiso->get_asi_permisos($usuario);

	$payload = array(
			"status" => (boolean)$array_list,
			"data" => is_array($array_list) ? $array_list : array(),
			"message" => $message_list,
			"sedes" => $sedes_list,
			"status" => $status_list,
			"permisses" => $permisses_list,
			"type" => 'Assign');
	echo safe_json_encode($payload);
}

function create_ticket ($data) {
	$ClsTicket = new ClsTicket();


} 

//////////////////---- INICIDENTES -----/////////////////////////////////

function Grabar_Ticket($desc,$incidente,$prioridad,$sede,$sector,$area,$imagen){
   //instanciamos el objeto para generar la respuesta con ajax
   $ClsTic = new ClsTicket();
   $ClsSta = new ClsStatus();
   $ClsInc = new ClsIncidente();
   $ClsPri = new ClsPrioridad();
   //pasa a mayusculas
	$titulo = trim($titulo);
	$desc = trim($desc);
	//--------
	//decodificaciones de tildes y Ñ's
		$titulo = utf8_encode($titulo);
		$desc = utf8_encode($desc);
		//--
		$titulo = utf8_decode($titulo);
		$desc = utf8_decode($desc);
	//--------
   if($incidente != "" && $prioridad != "" && $sede != ""){
      $ticket = $ClsTic->max_ticket();
      $ticket++; /// Maximo codigo de Ticket
      $usuario = $_REQUEST['usuario'];
      $status = $ClsSta->next_status_hd(0); /// obtiene el primer status activo despues de 0
      $sql = $ClsTic->insert_ticket($ticket,$desc,$incidente,$prioridad,$status,$sede,$sector,$area,$usuario); /// Inserta Ticket
      ///obtiene usuarios a encargarse
      $result = $ClsInc->get_usuario_incidente_sede($incidente,'',$sede);
      if(is_array($result)){
         foreach($result as $row){
            $usuario = intval($row["ius_usuario"]);
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
      $sql.= $ClsTic->insert_bitacora(1,$ticket,'Apertura de Ticket', ''); /// Inserta Ticket
		$rs = $ClsTic->exec_sql($var);
		if($rs == 1){
         mail_usuario($ticket);
         echo $ticket;
		}else{
			echo "Error en la transacci\u00F3n 1";
		}
	}else{
		echo "Error en la transacci\u00F3n";
	}
   
   return $respuesta;
}


function Modificar_Ticket($ticket,$desc,$incidente,$prioridad,$sede,$sector,$area,$imagen){
   //instanciamos el objeto para generar la respuesta con ajax
   $ClsTic = new ClsTicket();
   $ClsSta = new ClsStatus();
   $ClsInc = new ClsIncidente();
   //pasa a mayusculas
		$titulo = trim($titulo);
		$desc = trim($desc);
	//--------
	//decodificaciones de tildes y Ñ's
		$titulo = utf8_encode($titulo);
		$desc = utf8_encode($desc);
		//--
		$titulo = utf8_decode($titulo);
		$desc = utf8_decode($desc);
	//--------
   if($ticket != "" && $incidente != "" && $prioridad != "" && $sede != ""){
      $usuario = $_REQUEST['usuario'];
      $status = $ClsSta->next_status_hd(0); /// obtiene el primer status activo despues de 0
      $sql = $ClsTic->insert_ticket($ticket,$desc,$incidente,$prioridad,$status,$sede,$sector,$area,$usuario); /// Inserta Ticket
      //---Bitacora
      $codBit = $ClsTic->max_bitacora($ticket);
      $codBit++;
      $sql.= $ClsTic->insert_bitacora($codBit,$ticket,'Actualiza datos del Ticket', ''); /// Inserta Ticket
      
      //$respuesta->alert("$sql");
		$rs = $ClsTic->exec_sql($sql);
		if($rs == 1){
			if($imagen == true){
            echo "1";
         }else{
            echo "1";
         }
		}else{
			echo "Error en la transacci\u00F3n";
		}
	}else{
		echo "Error en la transacci\u00F3n";
	}
   
   return $respuesta;
}


function Cerrar_Ticket($codigo){
   $ClsTic = new ClsTicket();
	if($codigo != ""){
      ///limpia el codigo
      $codigo = intval($codigo);
      $sql = $ClsTic->cambia_sit_ticket($codigo,100);
      $sql.= $ClsTic->insert_ticket_status($codigo,100,'Cerrado'); /// Inserta Ticket
      $bitcod = $ClsTic->max_bitacora($codigo);
      $bitcod++;
      $sql.= $ClsTic->insert_bitacora($bitcod,$codigo,"Cierra el Ticket", ''); /// Inserta Ticket
      $sql.= $ClsTic->cerrar_ticket($codigo);
		//$respuesta->alert($sql);
		$rs = $ClsTic->exec_sql($sql);
      if($rs == 1){
         echo "1";
      }else{
         echo "Error en la transacci\u00F3n";
      }	
	}else{
		echo "Error en la transacci\u00F3n";
	}
   		
   return $respuesta;
}


//////////////
function Cambiar_Status($codigo,$status){
   $ClsTic = new ClsTicket();
   $ClsSta = new ClsStatus();
   
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
            $sql.= $ClsTic->insert_bitacora($bitcod,$codigo,"Cambio de Status ($status_nombre)", ''); /// Inserta Ticket
            if($status == 100){ ///// CERRAR TICKET
               $sql.= $ClsTic->cerrar_ticket($codigo);
            }
            //$respuesta->alert($sql);
            $rs = $ClsTic->exec_sql($sql);
            if($rs == 1){
               echo "1";
            }else{
               echo "Error en la transacci\u00F3n";
            }	
         }else{
            echo "Este Status no est\u00E1 identificado entre los habilitados...";
         }
      }else{
         echo "No ha seleccionado un status a cambiar...";
      }   
	}else{
		echo "Error en la transacci\u00F3n";
   }
   		
   return $respuesta;
}

function Agregar_Usuario($codigo,$usuario){
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
         $sql.= $ClsTic->insert_bitacora($bitcod,$codigo,"Agrega a $nombre_usuario al caso", ''); /// Inserta Ticket
         //$respuesta->alert($sql);
         $rs = $ClsTic->exec_sql($sql);
         if($rs == 1){
            echo "1";
         }else{
            echo "Error en la transacci\u00F3n";
         }	
      }else{
         echo "Este usuairo no est\u00E1 activo en nuestros registros....";
      }
	}else{
		echo "Error en la transacci\u00F3n";
	}
   		
   return $respuesta;
}

function Trasladar_Usuario($codigo,$usuario){
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
         $sql.= $ClsTic->insert_bitacora($bitcod,$codigo,"Se traslada el caso y se agrega a $nombre_usuario", ''); /// Inserta Ticket
         //$respuesta->alert($sql);
         $rs = $ClsTic->exec_sql($sql);
         if($rs == 1){
            echo "1";
         }else{
            echo "Error en la transacci\u00F3n";
         }	
      }else{
         echo "Este usuairo no est\u00E1 activo en nuestros registros....";
      }
	}else{
		echo "Error en la transacci\u00F3n";
	}
   		
   return $respuesta;
}

function Salir_Usuario($codigo,$usuario){
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
         $sql.= $ClsTic->insert_bitacora($bitcod,$codigo,"El Usuario $nombre_usuario traslado (deja el caso a otro usuario)", ''); /// Inserta Ticket
         $rs = $ClsTic->exec_sql($sql);
         if($rs == 1){
            echo "Caso trasladado satisfactoriamente!!!";
         }else{
            echo "Error en la transacci\u00F3n";
         }	
      }else{
         echo "Este usuario no est\u00E1 activo en nuestros registros....";
      }
	}else{
		echo "Error en la transacci\u00F3n";
   }
   		
}

/**/
function utf8ize($mixed) {
	if (is_array($mixed)) {
	    foreach ($mixed as $key => $value) {
	        $mixed[$key] = utf8ize($value);
	    }
	} else if (is_string ($mixed)) {
	    return utf8_encode($mixed);
	}
	return $mixed;
}

function safe_json_encode($value){
	if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
	    $encoded = json_encode($value, JSON_PRETTY_PRINT);
	} else {
	    $encoded = json_encode($value);
	}
	switch (json_last_error()) {
	    case JSON_ERROR_NONE:
	        return $encoded;
	    case JSON_ERROR_DEPTH:
	        return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
	    case JSON_ERROR_STATE_MISMATCH:
	        return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
	    case JSON_ERROR_CTRL_CHAR:
	        return 'Unexpected control character found';
	    case JSON_ERROR_SYNTAX:
	        return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
	    case JSON_ERROR_UTF8:
	        $clean = utf8ize($value);
	        return safe_json_encode($clean);
	    default:
	        return 'Unknown error'; // or trigger_error() or throw new 
	Exception();
	}
}
