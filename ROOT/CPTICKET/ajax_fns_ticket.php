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
include_once('html_fns_ticket.php');

$request = $_REQUEST["request"];
switch ($request) {
	case "get_area":
		$area = $_REQUEST["area"];
		get_area($area);
		break;
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
		case "grabar_falla":
			$activo = $_REQUEST["activo"];
			$falla = $_REQUEST["falla"];
			$fecha = $_REQUEST["fecha"];
			$hora = $_REQUEST["hora"];
			$situacion = $_REQUEST["situacion"];
			$ticket = $_REQUEST['codigo_ticket'];
			$comentario = $_REQUEST['comentario'];
			grabar_falla($activo, $falla, $fecha, $hora, $situacion,$ticket, $comentario);
		break;
	default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

////////////////// VERSIONES /////////////////////////

function get_area($area)
{
	$ClsAre = new ClsArea();
	$result = $ClsAre->get_area($area);
	$arr_data = array();
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data["sede"] = trim($row["are_sede"]);
			$arr_data["sector"] = trim($row["are_sector"]);
			$arr_data["secNombre"] = trim(trim($row["sec_nombre"]));
			$arr_data["nivel"] = trim($row["are_nivel"]);
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

function grabar_ticket($sede, $categoria, $area, $sector, $incidente, $prioridad, $descripcion)
{
	$ClsTic = new ClsTicket();
	$ClsSta = new ClsStatus();
	$ClsInc = new ClsIncidente();
	$ClsPri = new ClsPrioridad();
	$descripcion = utf8_decode(utf8_encode(trim($descripcion)));
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
		$sql .= $ClsTic->insert_bitacora(1, $ticket, 'Apertura de Ticket', ''); /// Inserta Ticket//$respuesta->alert("$sql");
		$rs = $ClsTic->exec_sql($sql);
		if ($rs == 1) {
			$arr_data["ticket"] = $ticket;
			$arr_data["status"] = $status;
			$arr_data["sms"] = $sms;	$arr_respuesta = array(
				"status" => true,
				"data" => $arr_data,
				"pagina" => "FRMsolicitados.php",
				"message" => "Transaccion correcta !!!",
			);	mail_usuario($ticket);
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
	$desc = utf8_decode(utf8_encode(trim($descripcion)));
	//--
	if ($ticket != "" && $incidente != "" && $prioridad != "" && $sede != "") {
		$usuario = $_SESSION["codigo"];
		$status = $ClsSta->next_status_hd(0); /// obtiene el primer status activo despues de 0
		$sql = $ClsTic->insert_ticket($ticket, $desc, $incidente, $prioridad, $status, $sede, $sector, $area, $usuario); /// Inserta Ticket
		//---Bitacora
		$codBit = $ClsTic->max_bitacora($ticket);
		$codBit++;
		$sql .= $ClsTic->insert_bitacora($codBit, $ticket, 'Actualiza datos del Ticket', ''); /// Inserta Ticket//$respuesta->alert("$sql");
		$rs = $ClsTic->exec_sql($sql);
		if ($rs == 1) {	$arr_respuesta = array(
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
				$nombre_usuario = depurador_texto($row["usu_nombre"]);
			}
			$sql = $ClsTic->insert_asignacion($ticket, $usuario); /// Inserta Ticket
			$bitcod = $ClsTic->max_bitacora($ticket);
			$bitcod++;
			$sql .= $ClsTic->insert_bitacora($bitcod, $ticket, "Agrega a $nombre_usuario al caso", ''); /// Inserta Ticket
			//$respuesta->alert($sql);
			$rs = $ClsTic->exec_sql($sql);
			if ($rs == 1) {
				mail_usuario($ticket, $usuario);		$arr_respuesta = array(
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
				$nombre_usuario = trim($row["usu_nombre"]);
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
	$obs = utf8_decode(utf8_encode(trim($observacion)));
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


function grabar_falla($activo, $falla, $fecha, $hora, $situacion,$ticket,$comentario)
{
	$ClsAct = new ClsActivo();
	$ClsFal = new ClsFalla();
	$ClsTic = new ClsTicket();
	if ($activo != "") {
		$codigo = $ClsFal->max_falla($activo);
		$codigo++; /// Maximo codigo de Activo
		$fecha = "$fecha $hora";
		$sql = $ClsFal->insert_falla($codigo, $activo, $falla, $fecha);
		$sql .= $ClsAct->cambia_sit_activo($activo, $situacion);
		$bitcod = $ClsTic->max_bitacora($codigo);
		$bitcod++;
		$sql .= $ClsTic->insert_bitacora($bitcod, $ticket, 'Apertura de falla desde el ticket', $comentario); /// Inserta Ticket//$respuesta->alert("$sql");
		$rs = $ClsFal->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"hashkey" => $ClsAct->encrypt($activo,$_SESSION["codigo"]),
				"message" => "Falla reportada exitosamente, se procedera a programar un mantenimiento al activo...!"
			);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Error en la transacci\u00F3n"
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Error en la transacci\u00F3n"
		);
	}
	echo json_encode($arr_respuesta);
}
