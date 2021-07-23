<?php
include_once('../html_fns.php');
require_once("../recursos/mandrill/src/Mandrill.php"); //--correos
require_once("../../CONFIG/constructor.php");

function tabla_tickets($codigo, $categoria, $incidente, $prioridad, $status, $fini, $ffin)
{
	$ClsTic = new ClsTicket();
	$result = $ClsTic->get_ticket_asignado($codigo, $categoria, '', $incidente, $prioridad, $status, $fini, $ffin, $_SESSION["codigo"], 1, 1);

	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px"><i class="fa fa-cogs"></i></th>';
		$salida .= '<th class = "text-center" width = "10px"># Ticket</th>';
		$salida .= '<th class = "text-center" width = "150px">Incidente</th>';
		$salida .= '<th class = "text-center" width = "150px">Status</th>';
		$salida .= '<th class = "text-center" width = "100px">Prioridad</th>';
		$salida .= '<th class = "text-center" width = "100px">Categor&iacute;a</th>';
		$salida .= '<th class = "text-center" width = "200px">Descripci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "150px">Fecha Apertura</th>';
		$salida .= '<th class = "text-center" width = "30px"></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 0;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = $row["tic_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			if ($_SESSION["CLOSETICKET"] == 1) {
				$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "Confirm_Cerrar_Ticket(' . $codigo . ');" title = "Cerrar Ticket" ><i class="fa fa-stop"></i></button>';
			} else {
				$salida .= '<button type="button" class="btn btn-danger btn-xs" disabled title = "Cerrar Ticket" ><i class="fa fa-stop"></i></button>';
			}
			$salida .= '</div>';
			$salida .= '</td>';
			//codigo
			$codigo = Agrega_Ceros($row["tic_codigo"]);
			$salida .= '<td class = "text-center">' . $codigo . '</td>';
			//incidente
			$incidente = utf8_decode($row["inc_nombre"]);
			$salida .= '<td class = "text-left">' . $incidente . '</td>';
			//status
			$status = utf8_decode($row["sta_nombre"]);
			$salida .= '<td class = "text-left">' . $status . '</td>';
			//prioridad
			$prioridad = utf8_decode($row["pri_nombre"]);
			$salida .= '<td class = "text-left">' . $prioridad . '</td>';
			//categoria
			$categoria = utf8_decode($row["cat_nombre"]);
			$salida .= '<td class = "text-left">' . $categoria . '</td>';
			//descripcion
			$desc = utf8_decode($row["tic_descripcion"]);
			$desc = nl2br($desc);
			$salida .= '<td class = "text-left">' . $desc . '</td>';
			//fecha de apertura
			$fechor = cambia_fechaHora($row["tic_fecha_registro"]);
			$salida .= '<td class = "text-left">' . $fechor . '</td>';
			//codigo
			$codigo = $row["tic_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-success btn-xs" onclick = "tramitarTicket(' . $codigo . ');" title = "Tr&aacute;mitar Ticket" ><i class="fa fa-chevron-right"></i></button>';
			//$salida .= '<button type="button" class="btn btn-warning btn-xs" onclick = "newFalla('.$codigo.');" title = "Reportar de Falla" ><i class="fa fa-exclamation-circle"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}

	return $salida;
}


function tabla_tickets_solicitados($codigo, $categoria, $incidente, $prioridad, $status, $fini, $ffin)
{
	$ClsTic = new ClsTicket();
	$result = $ClsTic->get_ticket($codigo, $categoria, '', $incidente, $prioridad, $status, $fini, $ffin, $_SESSION["codigo"], 1);

	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">No.</th>';
		$salida .= '<th class = "text-center" width = "10px"><i class="fa fa-cogs"></i></th>';
		$salida .= '<th class = "text-center" width = "10px"># Ticket</th>';
		$salida .= '<th class = "text-center" width = "150px">Incidente</th>';
		$salida .= '<th class = "text-center" width = "150px">Status</th>';
		$salida .= '<th class = "text-center" width = "100px">Prioridad</th>';
		$salida .= '<th class = "text-center" width = "100px">Categor&iacute;a</th>';
		$salida .= '<th class = "text-center" width = "200px">Descripci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "150px">Fecha Apertura</th>';
		$salida .= '<th class = "text-center" width = "30px"></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = $row["tic_codigo"];
			$salida .= '<td class = "text-center" >' . $i . '.</td>';
			//codigo
			$codigo = $row["tic_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarTicket(' . $codigo . ');" title = "Editar Ticket" ><i class="fa fa-pencil"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			//codigo
			$codigo = Agrega_Ceros($row["tic_codigo"]);
			$salida .= '<td class = "text-center">' . $codigo . '</td>';
			//incidente
			$incidente = utf8_decode($row["inc_nombre"]);
			$salida .= '<td class = "text-left">' . $incidente . '</td>';
			//status
			$status = utf8_decode($row["sta_nombre"]);
			$salida .= '<td class = "text-left">' . $status . '</td>';
			//prioridad
			$prioridad = utf8_decode($row["pri_nombre"]);
			$salida .= '<td class = "text-left">' . $prioridad . '</td>';
			//categoria
			$categoria = utf8_decode($row["cat_nombre"]);
			$salida .= '<td class = "text-left">' . $categoria . '</td>';
			//descripcion
			$desc = utf8_decode($row["tic_descripcion"]);
			$desc = nl2br($desc);
			$salida .= '<td class = "text-left">' . $desc . '</td>';
			//fecha de apertura
			$fechor = cambia_fechaHora($row["tic_fecha_registro"]);
			$salida .= '<td class = "text-left">' . $fechor . '</td>';
			//codigo
			$codigo = $row["tic_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-info btn-xs" onclick = "verInformacion(' . $codigo . ');" title = "Ver Informaci&oacute;n del Ticket" ><i class="fa fa-info-circle"></i></button> &nbsp; ';
			$salida .= '</div>';
			$salida .= '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}

	return $salida;
}


function tabla_usuarios($ticket)
{
	$ClsTic = new ClsTicket();
	$result = $ClsTic->get_asignacion($ticket, '', 1);

	if (is_array($result)) {
		$salida = '<table class="table table-striped" width="100%" >';
		$salida .= '<tbody>';
		$i = 0;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//titulo
			$usuario = trim($row["usu_id"]);
			$desc = utf8_decode($row["usu_nombre"]);
			$salida .= '<td class = "text-left">' . $desc . '</td>';
			//fecha registro
			$fechor = cambia_fechaHora($row["asi_fecha_registro"]);
			$salida .= '<td class = "text-left">' . $fechor . '</td>';
			//codigo
			$codigo = $row["asi_ticket"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-warning btn-xs" onclick = "salirUsuario(' . $codigo . ',' . $usuario . ');" title = "Dejar el Caso" ><i class="fa fa-sign-out"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}

	return $salida;
}



function tabla_bitacora($ticket)
{
	$ClsTic = new ClsTicket();
	$result = $ClsTic->get_bitacora('', $ticket);
	//var_dump($result);
	if (is_array($result)) {
		$salida = '<table class="table table-striped" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-left" width = "150px">Acci&oacute;n</th>';
		$salida .= '<th class = "text-left" width = "150px">Fecha/Hora</th>';
		$salida .= '<th class = "text-left" width = "200px">Observaciones o Comentarios</th>';
		$salida .= '<th class = "text-left" width = "100px">Responsable</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 0;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//titulo
			$desc = utf8_decode($row["bit_descripcion"]);
			$salida .= '<td class = "text-left">' . $desc . '</td>';
			//fecha registro
			$fechor = cambia_fechaHora($row["bit_fecha_registro"]);
			$salida .= '<td class = "text-left">' . $fechor . '</td>';
			//Comentario
			$obs = utf8_decode($row["bit_observaciones"]);
			$salida .= '<td class = "text-justify">' . $obs . '</td>';
			//usuario
			$usuario = utf8_decode($row["usu_nombre"]);
			$salida .= '<td class = "text-justify">' . $usuario . '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}

	return $salida;
}


function tabla_reportes($categoria, $sede, $incidente, $prioridad, $status, $fini, $ffin, $columnas)
{
	$ClsTic = new ClsTicket();
	$result = $ClsTic->get_ticket('', $categoria, $sede, $incidente, $prioridad, $status, $fini, $ffin, '', '1,2');

	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">No.</th>';
		$salida .= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		if (is_array($columnas)) {
			foreach ($columnas as $col) {
				$parametros = parametrosDinamicosHTML($col);
				$ancho = $parametros['ancho'];
				$titulo = $parametros['titulo'];
				$salida .= '<th class = "text-center" width = "' . $ancho . '">' . $titulo . '</th>';
			}
		} else {
			$salida .= '<th class = "text-center" width = "10px"># Ticket</th>';
			$salida .= '<th class = "text-center" width = "150px">Incidente</th>';
			$salida .= '<th class = "text-center" width = "150px">Status</th>';
			$salida .= '<th class = "text-center" width = "100px">Prioridad</th>';
			$salida .= '<th class = "text-center" width = "100px">Categor&iacute;a</th>';
			$salida .= '<th class = "text-center" width = "200px">Descripci&oacute;n</th>';
			$salida .= '<th class = "text-center" width = "150px">Fecha Apertura</th>';
		}
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//--
			$salida .= '<td class = "text-center">' . $i . '.- </td>';
			//codigo
			$codigo = $row["tic_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<button type="button" class="btn btn-info btn-xs" onclick = "verInformacion(' . $codigo . ');" title = "Ver Informaci&oacute;n del Ticket" ><i class="fa fa-info-circle"></i></button> &nbsp; ';
			$salida .= '</td>';
			//--
			if (is_array($columnas)) {
				foreach ($columnas as $col) {
					$parametros = parametrosDinamicosHTML($col);
					$campo = $parametros['campo'];
					$alineacion = $parametros['alineacion'];
					if ($col == "tic_codigo") {
						$campo = '# ' . Agrega_Ceros($row[$campo]);
					} else if ($col == "tic_fecha_registro") {
						$campo = cambia_fechaHora($row[$campo]);
					} else if ($col == "tic_fecha_fin") {
						$campo = cambia_fechaHora($row[$campo]);
					} else if ($col == "tic_respuesta") {
						$freg = trim($row["tic_fecha_registro"]);
						$respuesta = trim($row["tic_primer_status"]);
						if ($respuesta != "") {
							$date1 = new DateTime($freg);
							$date2 = new DateTime($respuesta);
							$interval = $date1->diff($date2);
							$campo = $interval->format('%H:%I:%S');
						} else {
							$campo = '- Pendiente de respuesta -';
						}
					} else if ($col == "tic_solucion") {
						$freg = trim($row["tic_fecha_registro"]);
						$cierre = trim($row["tic_cierre_status"]);
						$espera = trim($row["tic_espera"]);
						if ($cierre != "") {
							$date1 = new DateTime($freg);
							$date2 = new DateTime($cierre);
							$interval = $date1->diff($date2);
							$campo = $interval->format('%H:%I:%S');
							if ($espera != "") {
								$campo = date($campo);
								$campo = strtotime("-$espera minutes", strtotime($campo));
								$campo = date('H:i:s', $campo);
							}
						} else {
							$campo = '- Pendiente de Soluci&oacute;n -';
						}
					} else if ($col == "tic_espera") {
						$espera = trim($row["tic_espera"]);
						if ($espera != "") {
							$campo = "$espera minutos";
						} else {
							$campo = ' --- ';
						}
					} else if ($col == "tic_situacion") {
						$campo = trim($row[$campo]);
						$campo = ($campo == 1) ? '<strong class="text-success">En Proceso</strong>' : '<strong class="text-muted">Finalizado</strong>';
					} else if ($col == "tic_imagenes") {
						$codigo = trim($row["tic_codigo"]);
						$campo = '<button type = "button" class="btn btn-success" onclick = "imagenStatus(' . $codigo . ');" title = "Ver Imagenes" ><i class="fa fa-search"></i></button>';
					} else {
						$campo = utf8_decode($row[$campo]);
					}
					//columna
					$salida .= '<td class = "' . $alineacion . '">' . $campo . '</td>';
				}
			} else {
				//codigo
				$codigo = Agrega_Ceros($row["tic_codigo"]);
				$salida .= '<td class = "text-center">' . $codigo . '</td>';
				//incidente
				$incidente = utf8_decode($row["inc_nombre"]);
				$salida .= '<td class = "text-left">' . $incidente . '</td>';
				//status
				$status = utf8_decode($row["sta_nombre"]);
				$salida .= '<td class = "text-left">' . $status . '</td>';
				//prioridad
				$prioridad = utf8_decode($row["pri_nombre"]);
				$salida .= '<td class = "text-left">' . $prioridad . '</td>';
				//categoria
				$categoria = utf8_decode($row["cat_nombre"]);
				$salida .= '<td class = "text-left">' . $categoria . '</td>';
				//descripcion
				$desc = utf8_decode($row["tic_descripcion"]);
				$desc = nl2br($desc);
				$salida .= '<td class = "text-left">' . $desc . '</td>';
				//fecha de apertura
				$fechor = cambia_fechaHora($row["tic_fecha_registro"]);
				$salida .= '<td class = "text-left">' . $fechor . '</td>';
			}
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}

	return $salida;
}


function parametrosDinamicosHTML($columna)
{
	switch ($columna) {
		case "tic_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "N&uacute;mero de Ticket";
			$respuesta["campo"] = "tic_codigo";
			break;
		case "tic_fecha_registro":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha y hora de Inicio";
			$respuesta["campo"] = "tic_fecha_registro";
			break;
		case "tic_fecha_fin":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha y hora de Finalizaci&oacute;n";
			$respuesta["campo"] = "tic_fecha_fin";
			break;
		case "tic_situacion":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Situaci&oacute;n";
			$respuesta["campo"] = "tic_situacion";
			break;
		case "tic_imagenes":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Imagenes";
			$respuesta["campo"] = "tic_imagenes";
			break;
		case "inc_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo de Incidente";
			$respuesta["campo"] = "inc_codigo";
			break;
		case "inc_nombre":
			$respuesta["ancho"] = "110";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Incidente";
			$respuesta["campo"] = "inc_nombre";
			break;
		case "sta_codigo":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo de Status";
			$respuesta["campo"] = "sta_codigo";
			break;
		case "sta_nombre":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Status";
			$respuesta["campo"] = "sta_nombre";
			break;
		case "sta_color":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Color (Status)";
			$respuesta["campo"] = "sta_color";
			break;
		case "tic_status_observaciones":
			$respuesta["ancho"] = "150";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Observaciones del Status";
			$respuesta["campo"] = "tic_status_observaciones";
			break;
		case "pri_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Prioridad";
			$respuesta["campo"] = "pri_codigo";
			break;
		case "pri_nombre":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Prioridad";
			$respuesta["campo"] = "pri_nombre";
			break;
		case "pri_respuesta":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Tiempo Respuesta Planificada";
			$respuesta["campo"] = "pri_respuesta";
			break;
		case "pri_solucion":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Tiempo Soluci&oacute;n Planificada";
			$respuesta["campo"] = "pri_solucion";
			break;
		case "tic_respuesta":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Tiempo Respuesta Efectiva";
			$respuesta["campo"] = "tic_respuesta";
			break;
		case "tic_solucion":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Tiempo Soluci&oacute;n Efectiva";
			$respuesta["campo"] = "tic_solucion";
			break;
		case "tic_espera":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Tiempo Espera";
			$respuesta["campo"] = "tic_espera";
			break;
		case "pri_recordatorio":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Recordatorio";
			$respuesta["campo"] = "pri_recordatorio";
			break;
		case "pri_color":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Color (Prioridad)";
			$respuesta["campo"] = "pri_color";
			break;
		case "cat_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Cate.";
			$respuesta["campo"] = "cat_codigo";
			break;
		case "cat_nombre":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Categor&iacute;a";
			$respuesta["campo"] = "cat_nombre";
			break;
		case "cat_color":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Color (Cat.)";
			$respuesta["campo"] = "cat_color";
			break;
		case "tic_descripcion":
			$respuesta["ancho"] = "200";
			$respuesta["alineacion"] = "text-justify";
			$respuesta["titulo"] = "Descripci&oacute;n del Incidente";
			$respuesta["campo"] = "tic_descripcion";
			break;
		case "are_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo &Aacute;rea";
			$respuesta["campo"] = "are_codigo";
			break;
		case "are_nivel":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Nivel";
			$respuesta["campo"] = "are_nivel";
			break;
		case "are_nombre":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "&Aacute;rea";
			$respuesta["campo"] = "are_nombre";
			break;
		case "sec_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Sector";
			$respuesta["campo"] = "sec_codigo";
			break;
		case "sec_nombre":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Sector";
			$respuesta["campo"] = "sec_nombre";
			break;
		case "sed_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Sector";
			$respuesta["campo"] = "sed_codigo";
			break;
		case "sed_nombre":
			$respuesta["ancho"] = "110";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Sede";
			$respuesta["campo"] = "sed_nombre";
			break;
		case "sede_municipio":
			$respuesta["ancho"] = "110";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Departamento / Municipio";
			$respuesta["campo"] = "sede_municipio";
			break;
		case "sed_direccion":
			$respuesta["ancho"] = "150";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Direcci&oacute;n (Sede)";
			$respuesta["campo"] = "sed_direccion";
			break;
		case "sed_zona":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Zona";
			$respuesta["campo"] = "sed_zona";
			break;
		case "usuario_registro":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Usuario (Registr&oacute;)";
			$respuesta["campo"] = "usuario_registro";
			break;
	}
	return $respuesta;
}


function parametrosDinamicosPDF($columna)
{
	switch ($columna) {
		case "tic_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Ticket";
			$respuesta["campo"] = "tic_codigo";
			break;
		case "tic_fecha_registro":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fec/hora Inicio";
			$respuesta["campo"] = "tic_fecha_registro";
			break;
		case "tic_fecha_fin":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fec/hora Finalización";
			$respuesta["campo"] = "tic_fecha_fin";
			break;
		case "tic_situacion":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Situación";
			$respuesta["campo"] = "tic_situacion";
			break;
		case "tic_imagenes":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Imagenes";
			$respuesta["campo"] = "tic_imagenes";
			break;
		case "inc_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Lista";
			$respuesta["campo"] = "inc_codigo";
			break;
		case "inc_nombre":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Incidente";
			$respuesta["campo"] = "inc_nombre";
			break;
		case "sta_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Status";
			$respuesta["campo"] = "sta_codigo";
			break;
		case "sta_nombre":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Status";
			$respuesta["campo"] = "sta_nombre";
			break;
		case "sta_color":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Color (Status)";
			$respuesta["campo"] = "sta_color";
			break;
		case "tic_status_observaciones":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Observaciones del Status";
			$respuesta["campo"] = "tic_status_observaciones";
			break;
		case "pri_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Prioridad";
			$respuesta["campo"] = "pri_codigo";
			break;
		case "pri_nombre":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Prioridad";
			$respuesta["campo"] = "pri_nombre";
			break;
		case "pri_respuesta":
			$respuesta["ancho"] = "33";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "T. Resp. Plan.";
			$respuesta["campo"] = "pri_respuesta";
			break;
		case "pri_solucion":
			$respuesta["ancho"] = "33";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "T. Solución Plan.";
			$respuesta["campo"] = "pri_solucion";
			break;
		case "tic_respuesta":
			$respuesta["ancho"] = "33";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "T. Resp. Efec.";
			$respuesta["campo"] = "tic_respuesta";
			break;
		case "tic_solucion":
			$respuesta["ancho"] = "33";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "T. Solución Efec.";
			$respuesta["campo"] = "tic_solucion";
			break;
		case "tic_espera":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "T. Espera";
			$respuesta["campo"] = "tic_espera";
			break;
		case "pri_recordatorio":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Recordatorio";
			$respuesta["campo"] = "pri_recordatorio";
			break;
		case "pri_color":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Color (Prio)";
			$respuesta["campo"] = "pri_color";
			break;
		case "cat_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Cate.";
			$respuesta["campo"] = "cat_codigo";
			break;
		case "cat_nombre":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Categoría";
			$respuesta["campo"] = "cat_nombre";
			break;
		case "cat_color":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Color (Cat.)";
			$respuesta["campo"] = "cat_color";
			break;
		case "tic_descripcion":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "J";
			$respuesta["titulo"] = "Descripción del Incidente";
			$respuesta["campo"] = "tic_descripcion";
			break;
		case "are_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Área";
			$respuesta["campo"] = "are_codigo";
			break;
		case "are_nivel":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Nivel";
			$respuesta["campo"] = "are_nivel";
			break;
		case "are_nombre":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Área";
			$respuesta["campo"] = "are_nombre";
			break;
		case "sec_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Sector";
			$respuesta["campo"] = "sec_codigo";
			break;
		case "sec_nombre":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Sector";
			$respuesta["campo"] = "sec_nombre";
			break;
		case "sed_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Sede";
			$respuesta["campo"] = "sed_codigo";
			break;
		case "sed_nombre":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Sede";
			$respuesta["campo"] = "sed_nombre";
			break;
		case "sede_municipio":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Departamento / Municipio";
			$respuesta["campo"] = "sede_municipio";
			break;
		case "sed_direccion":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Dirección (Sede)";
			$respuesta["campo"] = "sed_direccion";
			break;
		case "sed_zona":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Zona";
			$respuesta["campo"] = "sed_zona";
			break;
		case "usuario_registro":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Usuario (Registró)";
			$respuesta["campo"] = "usuario_registro";
			break;
	}
	return $respuesta;
}


function parametrosDinamicosEXCEL($columna)
{
	switch ($columna) {
		case "tic_codigo":
			$respuesta["ancho"] = "18";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Ticket";
			$respuesta["campo"] = "tic_codigo";
			break;
		case "tic_fecha_registro":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha/hora Inicio";
			$respuesta["campo"] = "tic_fecha_registro";
			break;
		case "tic_fecha_fin":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha/hora Finaliza";
			$respuesta["campo"] = "tic_fecha_fin";
			break;
		case "tic_situacion":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Situacion";
			$respuesta["campo"] = "tic_situacion";
			break;
		case "tic_imagenes":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Imagenes";
			$respuesta["campo"] = "tic_imagenes";
			break;
		case "inc_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Incidente";
			$respuesta["campo"] = "inc_codigo";
			break;
		case "inc_nombre":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Incidente";
			$respuesta["campo"] = "inc_nombre";
			break;
		case "sta_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Status";
			$respuesta["campo"] = "sta_codigo";
			break;
		case "sta_nombre":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Status";
			$respuesta["campo"] = "sta_nombre";
			break;
		case "sta_color":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Color (Status)";
			$respuesta["campo"] = "sta_color";
			break;
		case "tic_status_observaciones":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Observaciones del Status";
			$respuesta["campo"] = "tic_status_observaciones";
			break;
		case "pri_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Prioridad";
			$respuesta["campo"] = "pri_codigo";
			break;
		case "pri_nombre":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Prioridad";
			$respuesta["campo"] = "pri_nombre";
			break;
		case "pri_respuesta":
			$respuesta["ancho"] = "23";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Tiempo Respuesta Planificada";
			$respuesta["campo"] = "pri_respuesta";
			break;
		case "pri_solucion":
			$respuesta["ancho"] = "23";
			$respuesta["alineacion"] = "J";
			$respuesta["titulo"] = "Tiempo Solucion Planificada";
			$respuesta["campo"] = "pri_solucion";
			break;
		case "tic_respuesta":
			$respuesta["ancho"] = "23";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Tiempo Respuesta Efectiva";
			$respuesta["campo"] = "tic_respuesta";
			break;
		case "tic_solucion":
			$respuesta["ancho"] = "23";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Tiempo Solucion Efectiva";
			$respuesta["campo"] = "tic_solucion";
			break;
		case "tic_espera":
			$respuesta["ancho"] = "20";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Tiempo Espera";
			$respuesta["campo"] = "tic_espera";
			break;
		case "pri_recordatorio":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Recordatorio";
			$respuesta["campo"] = "pri_recordatorio";
			break;
		case "pri_color":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Color (Prio)";
			$respuesta["campo"] = "pri_color";
			break;
		case "cat_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Cate.";
			$respuesta["campo"] = "cat_codigo";
			break;
		case "cat_nombre":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Categoria";
			$respuesta["campo"] = "cat_nombre";
			break;
		case "cat_color":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Color (Cat.)";
			$respuesta["campo"] = "cat_color";
			break;
		case "tic_descripcion":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "J";
			$respuesta["titulo"] = "Descripcion del Incidente";
			$respuesta["campo"] = "tic_descripcion";
			break;
		case "are_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Area";
			$respuesta["campo"] = "are_codigo";
			break;
		case "are_nivel":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Nivel";
			$respuesta["campo"] = "are_nivel";
			break;
		case "are_nombre":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Area";
			$respuesta["campo"] = "are_nombre";
			break;
		case "sec_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Sector";
			$respuesta["campo"] = "sec_codigo";
			break;
		case "sec_nombre":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Sector";
			$respuesta["campo"] = "sec_nombre";
			break;
		case "sed_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Sede";
			$respuesta["campo"] = "sed_codigo";
			break;
		case "sed_nombre":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Sede";
			$respuesta["campo"] = "sed_nombre";
			break;
		case "sede_municipio":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Departamento / Municipio";
			$respuesta["campo"] = "sede_municipio";
			break;
		case "sed_direccion":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Direccion (Sede)";
			$respuesta["campo"] = "sed_direccion";
			break;
		case "sed_zona":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Zona";
			$respuesta["campo"] = "sed_zona";
			break;
		case "usuario_registro":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Usuario (Registro)";
			$respuesta["campo"] = "usuario_registro";
			break;
	}
	return $respuesta;
}


function mail_usuario($ticket, $usuario = '')
{

	$ClsTic = new ClsTicket();
	$result = $ClsTic->get_ticket($ticket);
	if (is_array($result)) {
		foreach ($result as $row) {
			//codigo
			$codigo = Agrega_Ceros($row["tic_codigo"]);
			//incidente
			$incidente = depurador_texto(utf8_decode($row["inc_nombre"]));
			//status
			$status = depurador_texto(utf8_decode($row["sta_nombre"]));
			//prioridad
			$prioridad = depurador_texto(utf8_decode($row["pri_nombre"]));
			//categoria
			$categoria = depurador_texto(utf8_decode($row["cat_nombre"]));
			//descripcion
			$desc = utf8_decode($row["tic_descripcion"]);
			$desc = nl2br($desc);
			$desc = depurador_texto($desc);
			//ubicacion
			$sede = depurador_texto(utf8_decode($row["sed_nombre"]));
			$sector = depurador_texto(utf8_decode($row["sec_nombre"]));
			$area = depurador_texto(utf8_decode($row["are_nombre"]));
		}
	}

	//asignados
	$result = $ClsTic->get_asignacion($ticket, $usuario);
	$i = 0;
	if (is_array($result)) {
		foreach ($result as $row) {
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
	if (is_array($result)) {
		foreach ($result as $row) {
			$cliente_nombre = utf8_decode($row['cliente_nombre']);
			$cliente_nombre_reporte = utf8_decode($row['cliente_nombre_reporte']);
		}
	}
	$cliente_nombre = depurador_texto($cliente_nombre);
	$cliente_nombre_reporte = depurador_texto($cliente_nombre_reporte);
	$url = url_origin($_SERVER);

	$mailadmin = "soporte@farasi.com.gt";
	// Instancia el API KEY de Mandrill
	$mandrill = new Mandrill('aLGRM5YodGYp_GDBwwDilw');
	/////////////_________ Correo a admin
	$subject = $cliente_nombre_reporte;
	$texto = "Estimado Usuario,<br><br>hay una nueva solicitud reportada con el numero # $codigo en la $sede, $sector, $area.<br><br>El problema es el siguiente:<br><strong>$incidente</strong> <br>$desc <br><br>";
	$texto .= "Puede accesar al sistema desde aqui:<br><br>";
	$texto .= '<a href="' . $url . '/HDAPP/" class="btn btn-warning btn-round btn-block">  Click </a>';
	$texto .= "<br><br>Gracias y saludos,<br><br>HelpDesk";

	$html = mail_constructor($subject, $texto);

	try {
		$message = array(
			'subject' => $subject,
			'html' => $html,
			'from_email' => 'noreply@farasi.com.gt',
			'from_name' => 'BPManagement',
			'to' => $to
		); //print_r($message);
		//echo "<br>";
		$result = $mandrill->messages->send($message);
		$validacion =  1;
	} catch (Mandrill_Error $e) {
		//echo "<br>";
		//print_r($e);
		//devuelve un mensaje de manejo de errores
		$validacion =  0;
	}

	return $validacion;
}

