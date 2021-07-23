<?php
ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cache");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);
//--
include_once('html_fns_indicador.php');

header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header("Access-Control-Allow-Origin: *");

///API REQUEST
$request = $_REQUEST["request"];
$_REQUEST = str_replace("undefined", "", $_REQUEST); ///valida campos "undefined" desde javascript

if ($request != "") {
	switch ($request) {
		case "barra":
			barra();
			break;
		case "barra_usuario":
			barra_usuario();
			break;
		case "cumplimiento":
			cumplimiento();
			break;
		case "cumplimiento_usuario":
			cumplimiento_usuario();
			break;
		case "lecturas":
			lecturas();
			break;
		case "lecturas_usuario":
			lecturas_usuario();
			break;
		default:
			$payload = array(
				"status" => false,
				"data" => [],
				"message" => "Parametros invalidos..."
			);
			echo json_encode($payload);
			break;
	}
} else {
	//devuelve un mensaje de manejo de errores
	$payload = array(
		"status" => false,
		"data" => [],
		"message" => "Delimite el desde de consulta a realizar..."
	);
	echo json_encode($payload);
}


function barra()
{
	$ClsRev = new ClsRevision();
	$ClsInd = new ClsIndicador();

	//--
	$ejecutado = 0;
	$pendiente = 0;
	$vencido = 0;
	// Ejecutado
	$ejecutado = $ClsRev->count_revision_indicador();
	// Pendiente
	$programaciones = $ClsInd->get_programacion();
	if (is_array($programaciones)) {
		foreach ($programaciones as $rowProgramacion) {
			$revision = trim($rowProgramacion["revision"]);
			$fecha = strtotime(trim($rowProgramacion["pro_fecha"]));
			$fecha_hoy = strtotime(date("d-m-Y"));
			if ($fecha_hoy < $fecha) $pendiente++;
			else if ($fecha_hoy > $fecha && $revision == "") $vencido++;
			else if ($revision == "") $pendiente++;
		}
	}

	$total = $ejecutado + $pendiente + $vencido;
	if ($total > 0) {
		$porcent_ejecutado = round(($ejecutado * 100) / $total);
		$porcent_pendiente = round(($pendiente * 100) / $total);
		$porcent_vencido = round(($vencido * 100) / $total);
	} else {
		$porcent_ejecutado = 0;
		$porcent_pendiente = 0;
		$porcent_vencido = 0;
	}
	$result = array(
		"porcentEjecutado" => $porcent_ejecutado,
		"porcentPendiente" => $porcent_pendiente,
		"porcentVencido" => $porcent_vencido
	);

	$arr_respuesta = array(
		"status" => true,
		"data" => $result,
		"message" => ""
	);

	echo json_encode($arr_respuesta);
}


function cumplimiento()
{
	$ClsRev = new ClsRevision();
	$ClsInd = new ClsIndicador();
	$ClsFic = new ClsFicha();
	$ClsSis = new ClsSistema();
	$ClsPro = new ClsProceso();
	//-- Proceso
	$result = $ClsFic->get_ficha();
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-left" width = "50px">Proceso</th>';
		$salida .= '<th class = "text-left" width = "30px">Ejecutado</th>';
		$salida .= '<th class = "text-left" width = "30px">Pendiente</th>';
		$salida .= '<th class = "text-left" width = "30px">Vencido</th>';
		$salida .= '<th class = "text-left" width = "30px">Total</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$i = 1;
		$arrProceso = array();
		$arrejecutado = array();
		$arrpendientes = array();
		$arrvencidos = array();
		//---
		$arrejecutado[0] = "Ejecutado";
		$arrpendientes[0] = "Pendiente";
		$arrvencidos[0] = "Vencido";
		foreach ($result as $row) {
			$proceso = trim($row["fic_codigo"]);
			$nombre = trim($row["fic_nombre"]);
			$arrProceso[$i - 1] = $nombre;
			//--
			$ejecutado = 0;
			$pendiente = 0;
			$vencido = 0;
			// Ejecutado
			$ejecutado = $ClsRev->count_revision_indicador("", "", "", $proceso);
			// Pendiente
			$programaciones = $ClsInd->get_programacion("", "", $proceso);
			if (is_array($programaciones)) {
				foreach ($programaciones as $rowProgramacion) {
					$revision = trim($rowProgramacion["revision"]);
					$fecha = strtotime(trim($rowProgramacion["pro_fecha"]));
					$fecha_hoy = strtotime(date("d-m-Y"));
					if ($fecha_hoy < $fecha) $pendiente++;
					else if ($fecha_hoy > $fecha && $revision == "") $vencido++;
					else if ($revision == "") $pendiente++;
				}
			}	//-----------------
			$arrejecutado[$i] = intval($ejecutado);
			$arrpendientes[$i] = intval($pendiente);
			$arrvencidos[$i] = intval($vencido);
			//--	
			$salida .= '<td width = "70%" class = "text-left">' . $nombre . '</td>';
			$salida .= '<td width = "10%" class = "text-left">' . $ejecutado . ' </td>';
			$salida .= '<td width = "10%" class = "text-left">' . $pendiente . ' </td>';
			$salida .= '<td width = "10%" class = "text-left">' . $vencido . ' </td>';
			$salida .= '<td width = "10%" class = "text-left">' . ($ejecutado + $pendiente + $vencido) . ' </td>';
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</table>';
	}

	//-- Sistema
	$result = $ClsSis->get_sistema();
	if (is_array($result)) {
		$i = 1;
		$arrSistema = array();
		$arrejecutado2 = array();
		$arrpendientes2 = array();
		$arrvencidos2 = array();
		//---
		$arrejecutado2[0] = "Ejecutado";
		$arrpendientes2[0] = "Pendiente";
		$arrvencidos2[0] = "Vencido";
		foreach ($result as $row) {
			$sistema = trim($row["sis_codigo"]);
			$nombre = trim($row["sis_nombre"]);
			$arrSistema[$i - 1] = $nombre;
			//--
			$ejecutado = 0;
			$pendiente = 0;
			$vencido = 0;
			// Ejecutado
			$ejecutado = $ClsRev->count_revision_indicador("", "", "", "", $sistema);
			// Pendiente
			$programaciones = $ClsInd->get_programacion("", "", "", $sistema);
			if (is_array($programaciones)) {
				foreach ($programaciones as $rowProgramacion) {
					$revision = trim($rowProgramacion["revision"]);
					$fecha = strtotime(trim($rowProgramacion["pro_fecha"]));
					$fecha_hoy = strtotime(date("d-m-Y"));
					if ($fecha_hoy < $fecha) $pendiente++;
					else if ($fecha_hoy > $fecha && $revision == "") $vencido++;
					else if ($revision == "") $pendiente++;
				}
			}	//-----------------
			$arrejecutado2[$i] = intval($ejecutado);
			$arrpendientes2[$i] = intval($pendiente);
			$arrvencidos2[$i] = intval($vencido);
			//--
			$i++;
		}
	}

	//-- Tipo
	$result = $ClsPro->get_subtitulo("", 2);
	if (is_array($result)) {
		$i = 1;
		$arrTipo = array();
		$arrejecutado3 = array();
		$arrpendientes3 = array();
		$arrvencidos3 = array();
		//---
		$arrejecutado3[0] = "Ejecutado";
		$arrpendientes3[0] = "Pendiente";
		$arrvencidos3[0] = "Vencido";
		foreach ($result as $row) {
			$tipo = trim($row["sub_codigo"]);
			$nombre = trim($row["sub_nombre"]);
			$arrTipo[$i - 1] = $nombre;
			//-- Proceso
			$result = $ClsFic->get_ficha("", $tipo);
			foreach ($result as $row) {
				$proceso = trim($row["fic_codigo"]);
				//--
				$ejecutado = 0;
				$pendiente = 0;
				$vencido = 0;
				// Ejecutado
				$ejecutado = $ClsRev->count_revision_indicador("", "", "", $proceso);
				// Pendiente
				$programaciones = $ClsInd->get_programacion("", "", $proceso);
				if (is_array($programaciones)) {
					foreach ($programaciones as $rowProgramacion) {
						$revision = trim($rowProgramacion["revision"]);
						$fecha = strtotime(trim($rowProgramacion["pro_fecha"]));
						$fecha_hoy = strtotime(date("d-m-Y"));
						if ($fecha_hoy < $fecha) $pendiente++;
						else if ($fecha_hoy > $fecha && $revision == "") $vencido++;
						else if ($revision == "") $pendiente++;
					}
				}		//-----------------
				$arrejecutado3[$i] += intval($ejecutado);
				$arrpendientes3[$i] += intval($pendiente);
				$arrvencidos3[$i] += intval($vencido);
			}	//--
			$i++;
		}
	}

	$result = array(
		"proceso" => $arrProceso,
		"sistema" => $arrSistema,
		"tipo" => $arrTipo,
		"tabla" => $salida,
		"ejecutado" => $arrejecutado,
		"pendiente" => $arrpendientes,
		"vencido" => $arrvencidos,
		"ejecutado2" => $arrejecutado2,
		"pendiente2" => $arrpendientes2,
		"vencido2" => $arrvencidos2,
		"ejecutado3" => $arrejecutado3,
		"pendiente3" => $arrpendientes3,
		"vencido3" => $arrvencidos3
	);

	$arr_respuesta = array(
		"status" => true,
		"data" => $result,
		"message" => ""
	);

	echo json_encode($arr_respuesta);
}

function lecturas()
{
	$ClsRev = new ClsRevision();
	$ClsInd = new ClsIndicador();
	$ClsFic = new ClsFicha();
	$ClsSis = new ClsSistema();
	$ClsPro = new ClsProceso();
	//-- Proceso
	$result = $ClsFic->get_ficha();
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example">';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-left" width = "50px">Proceso</th>';
		$salida .= '<th class = "text-left" width = "30px">Bajo el Minimo</th>';
		$salida .= '<th class = "text-left" width = "30px">Conveniente</th>';
		$salida .= '<th class = "text-left" width = "30px">Sobre el Maximo</th>';
		$salida .= '<th class = "text-left" width = "30px">Total</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$i = 1;
		$arrProceso = array();
		$arrBajo = array();
		$arrMedia = array();
		$arrAlto = array();
		//---
		$arrBajo[0] = "Bajo el Minimo";
		$arrMedia[0] = "En Rango";
		$arrAlto[0] = "Sobre el Maximo";
		foreach ($result as $row) {
			$proceso = trim($row["fic_codigo"]);
			$nombre = trim($row["fic_nombre"]);
			$arrProceso[$i - 1] = $nombre;
			$arrBajo[$i] = 0;
			$arrMedia[$i] = 0;
			$arrAlto[$i] = 0;
			$indicadores = $ClsInd->get_indicador("", "", $proceso);
			if (is_array($indicadores)) {
				foreach ($indicadores as $rowIndicador) {
					$indicador = trim($rowIndicador["ind_codigo"]);
					//--
					$bajo = 0;
					$conveniente = 0;
					$arriba = 0;
					$j = 0;
					$result_lectura = $ClsRev->get_revision_indicador("", $indicador);
					if (is_array($result_lectura)) {
						foreach ($result_lectura as $row_lectura) {
							$lectura = $row_lectura['rev_lectura'];
							$minima = $row_lectura['ind_lectura_minima'];
							$maxima = $row_lectura['ind_lectura_maxima'];
							if ($lectura < $minima) $bajo++;
							else if ($lectura > $maxima) $arriba++;
							else $conveniente++;
							$j++;
						}
					}
					//-----------------
					$arrBajo[$i] += intval($bajo);
					$arrMedia[$i] += intval($conveniente);
					$arrAlto[$i] += intval($arriba);
				}
			}
			$salida .= '<td width = "70%" class = "text-left">' . $nombre . '</td>';
			$salida .= '<td width = "10%" class = "text-left">' . $arrBajo[$i] . ' </td>';
			$salida .= '<td width = "10%" class = "text-left">' . $arrMedia[$i]. ' </td>';
			$salida .= '<td width = "10%" class = "text-left">' . $arrAlto[$i] . ' </td>';
			$salida .= '<td width = "10%" class = "text-left">' . ($arrBajo[$i] + $arrMedia[$i] + $arrAlto[$i]) . ' </td>';
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</table>';
	}
	//-- Tipo de Proceso
	$result = $ClsPro->get_subtitulo("", 2);
	if (is_array($result)) {
		$i = 1;
		$arrTipo = array();
		$arrBajo2 = array();
		$arrMedia2 = array();
		$arrAlto2 = array();
		//---
		$arrBajo2[0] = "Bajo el Minimo";
		$arrMedia2[0] = "En Rango";
		$arrAlto2[0] = "Sobre el Maximo";
		foreach ($result as $row) {
			$tipo = trim($row["sub_codigo"]);
			$nombre = trim($row["sub_nombre"]);
			$arrTipo[$i - 1] = $nombre;
			$arrBajo2[$i] += 0;
			$arrMedia2[$i] += 0;
			$arrAlto2[$i] += 0;
			//--
			$procesos = $ClsFic->get_ficha("", $tipo);
			foreach ($procesos as $rowProceso) {
				$proceso = trim($rowProceso["fic_codigo"]);
				$nombre = trim($rowProceso["fic_nombre"]);
				$indicadores = $ClsInd->get_indicador("", "", $proceso);
				if (is_array($indicadores)) {
					foreach ($indicadores as $rowIndicador) {
						$indicador = trim($rowIndicador["ind_codigo"]);
						//--
						$bajo = 0;
						$conveniente = 0;
						$arriba = 0;
						$j = 0;
						$result_lectura = $ClsRev->get_revision_indicador("", $indicador);
						if (is_array($result_lectura)) {
							foreach ($result_lectura as $row_lectura) {
								$lectura = $row_lectura['rev_lectura'];
								$minima = $row_lectura['ind_lectura_minima'];
								$maxima = $row_lectura['ind_lectura_maxima'];
								if ($lectura < $minima) $bajo++;
								else if ($lectura > $maxima) $arriba++;
								else $conveniente++;
								$j++;
							}
						}
						//-----------------
						$arrBajo2[$i] += intval($bajo);
						$arrMedia2[$i] += intval($conveniente);
						$arrAlto2[$i] += intval($arriba);
					}
				}
			}
			$i++;
		}
	}
	//-- Sistema
	$result = $ClsSis->get_sistema();
	if (is_array($result)) {
		$i = 1;
		$arrSistema = array();
		$arrBajo3 = array();
		$arrMedia3 = array();
		$arrAlto3 = array();
		//---
		$arrBajo3[0] = "Bajo el Minimo";
		$arrMedia3[0] = "En Rango";
		$arrAlto3[0] = "Sobre el Maximo";
		foreach ($result as $row) {
			$sistema = trim($row["sis_codigo"]);
			$nombre = trim($row["sis_nombre"]);
			$arrSistema[$i - 1] = $nombre;
			$indicadores = $ClsInd->get_indicador("", "", "", $sistema);
			foreach ($indicadores as $rowIndicador) {
				$indicador = trim($rowIndicador["ind_codigo"]);
				//--
				$bajo = 0;
				$conveniente = 0;
				$arriba = 0;
				$j = 0;
				$result_lectura = $ClsRev->get_revision_indicador("", $indicador);
				if (is_array($result_lectura)) {
					foreach ($result_lectura as $row_lectura) {
						$lectura = $row_lectura['rev_lectura'];
						$minima = $row_lectura['ind_lectura_minima'];
						$maxima = $row_lectura['ind_lectura_maxima'];
						if ($lectura < $minima) $bajo++;
						else if ($lectura > $maxima) $arriba++;
						else $conveniente++;
						$j++;
					}
				}
				//-----------------
				$arrBajo3[$i] += intval($bajo);
				$arrMedia3[$i] += intval($conveniente);
				$arrAlto3[$i] += intval($arriba);
			}
			//--
			$i++;
		}
	}

	$result = array(
		"proceso" => $arrProceso,
		"tabla" => $salida,
		"bajo" => $arrBajo,
		"media" => $arrMedia,
		"alto" => $arrAlto,
		"tipo" => $arrTipo,
		"bajo2" => $arrBajo2,
		"media2" => $arrMedia2,
		"alto2" => $arrAlto2,
		"sistema" => $arrSistema,
		"bajo3" => $arrBajo3,
		"media3" => $arrMedia3,
		"alto3" => $arrAlto3,
	);

	$arr_respuesta = array(
		"status" => true,
		"data" => $result,
		"message" => ""
	);

	echo json_encode($arr_respuesta);
}

///////////////////////////// Reporte de Usuario //////////////////////
function barra_usuario()
{
	$ClsRev = new ClsRevision();
	$ClsInd = new ClsIndicador();
	$usuario = $_SESSION["codigo"];
	//--
	$ejecutado = 0;
	$pendiente = 0;
	$vencido = 0;
	// Ejecutado
	$ejecutado = $ClsRev->count_revision_indicador("", "", "", "", "", $usuario);
	// Pendiente
	$programaciones = $ClsInd->get_programacion("", "", "", "", "", "", "", $usuario);
	if (is_array($programaciones)) {
		foreach ($programaciones as $rowProgramacion) {
			$revision = trim($rowProgramacion["revision"]);
			$fecha = strtotime(trim($rowProgramacion["pro_fecha"]));
			$fecha_hoy = strtotime(date("d-m-Y"));
			if ($fecha_hoy < $fecha) $pendiente++;
			else if ($fecha_hoy > $fecha && $revision == "") $vencido++;
			else if ($revision == "") $pendiente++;
		}
	}

	$total = $ejecutado + $pendiente + $vencido;
	if ($total > 0) {
		$porcent_ejecutado = round(($ejecutado * 100) / $total);
		$porcent_pendiente = round(($pendiente * 100) / $total);
		$porcent_vencido = round(($vencido * 100) / $total);
	} else {
		$porcent_ejecutado = 0;
		$porcent_pendiente = 0;
		$porcent_vencido = 0;
	}
	$result = array(
		"porcentEjecutado" => $porcent_ejecutado,
		"porcentPendiente" => $porcent_pendiente,
		"porcentVencido" => $porcent_vencido
	);

	$arr_respuesta = array(
		"status" => true,
		"data" => $result,
		"message" => ""
	);

	echo json_encode($arr_respuesta);
}

function cumplimiento_usuario()
{
	$ClsRev = new ClsRevision();
	$ClsInd = new ClsIndicador();
	$ClsFic = new ClsFicha();
	$ClsSis = new ClsSistema();
	$ClsPro = new ClsProceso();
	$usuario = $_SESSION["codigo"];
	//-- Proceso
	$result = $ClsFic->get_ficha_usuario("", "", $usuario);
	if (is_array($result)) {
		$i = 1;
		$arrProceso = array();
		$arrejecutado = array();
		$arrpendientes = array();
		$arrvencidos = array();
		//---
		$arrejecutado[0] = "Ejecutado";
		$arrpendientes[0] = "Pendiente";
		$arrvencidos[0] = "Vencido";
		foreach ($result as $row) {
			$proceso = trim($row["fic_codigo"]);
			$nombre = trim($row["fic_nombre"]);
			$arrProceso[$i - 1] = $nombre;
			//--
			$ejecutado = 0;
			$pendiente = 0;
			$vencido = 0;
			// Ejecutado
			$ejecutado = $ClsRev->count_revision_indicador("", "", "", $proceso);
			// Pendiente
			$programaciones = $ClsInd->get_programacion("", "", $proceso);
			if (is_array($programaciones)) {
				foreach ($programaciones as $rowProgramacion) {
					$revision = trim($rowProgramacion["revision"]);
					$fecha = strtotime(trim($rowProgramacion["pro_fecha"]));
					$fecha_hoy = strtotime(date("d-m-Y"));
					if ($fecha_hoy < $fecha) $pendiente++;
					else if ($fecha_hoy > $fecha && $revision == "") $vencido++;
					else if ($revision == "") $pendiente++;
				}
			}	//-----------------
			$arrejecutado[$i] = intval($ejecutado);
			$arrpendientes[$i] = intval($pendiente);
			$arrvencidos[$i] = intval($vencido);
			//--
			$i++;
		}
	}

	//-- Sistema
	$result = $ClsSis->get_sistema();
	if (is_array($result)) {
		$i = 1;
		$arrSistema = array();
		$arrejecutado2 = array();
		$arrpendientes2 = array();
		$arrvencidos2 = array();
		//---
		$arrejecutado2[0] = "Ejecutado";
		$arrpendientes2[0] = "Pendiente";
		$arrvencidos2[0] = "Vencido";
		foreach ($result as $row) {
			$sistema = trim($row["sis_codigo"]);
			$nombre = trim($row["sis_nombre"]);
			$arrSistema[$i - 1] = $nombre;
			//--
			$ejecutado = 0;
			$pendiente = 0;
			$vencido = 0;
			// Ejecutado
			$ejecutado = $ClsRev->count_revision_indicador("", "", "", "", $sistema, $usuario);
			// Pendiente
			$programaciones = $ClsInd->get_programacion("", "", "", $sistema, "", "", "", $usuario);
			if (is_array($programaciones)) {
				foreach ($programaciones as $rowProgramacion) {
					$revision = trim($rowProgramacion["revision"]);
					$fecha = strtotime(trim($rowProgramacion["pro_fecha"]));
					$fecha_hoy = strtotime(date("d-m-Y"));
					if ($fecha_hoy < $fecha) $pendiente++;
					else if ($fecha_hoy > $fecha && $revision == "") $vencido++;
					else if ($revision == "") $pendiente++;
				}
			}	//-----------------
			$arrejecutado2[$i] = intval($ejecutado);
			$arrpendientes2[$i] = intval($pendiente);
			$arrvencidos2[$i] = intval($vencido);
			//--
			$i++;
		}
	}

	//-- Tipo
	$result = $ClsPro->get_subtitulo("", 2);
	if (is_array($result)) {
		$i = 1;
		$arrTipo = array();
		$arrejecutado3 = array();
		$arrpendientes3 = array();
		$arrvencidos3 = array();
		//---
		$arrejecutado3[0] = "Ejecutado";
		$arrpendientes3[0] = "Pendiente";
		$arrvencidos3[0] = "Vencido";
		foreach ($result as $row) {
			$tipo = trim($row["sub_codigo"]);
			$nombre = trim($row["sub_nombre"]);
			$arrTipo[$i - 1] = $nombre;
			//-- Proceso
			$result = $ClsFic->get_ficha_usuario("", "", $usuario, "", "", $tipo);
			foreach ($result as $row) {
				$proceso = trim($row["fic_codigo"]);
				//--
				$ejecutado = 0;
				$pendiente = 0;
				$vencido = 0;
				// Ejecutado
				$ejecutado = $ClsRev->count_revision_indicador("", "", "", $proceso);
				// Pendiente
				$programaciones = $ClsInd->get_programacion("", "", $proceso);
				if (is_array($programaciones)) {
					foreach ($programaciones as $rowProgramacion) {
						$revision = trim($rowProgramacion["revision"]);
						$fecha = strtotime(trim($rowProgramacion["pro_fecha"]));
						$fecha_hoy = strtotime(date("d-m-Y"));
						if ($fecha_hoy < $fecha) $pendiente++;
						else if ($fecha_hoy > $fecha && $revision == "") $vencido++;
						else if ($revision == "") $pendiente++;
					}
				}		//-----------------
				$arrejecutado3[$i] += intval($ejecutado);
				$arrpendientes3[$i] += intval($pendiente);
				$arrvencidos3[$i] += intval($vencido);
			}	//--
			$i++;
		}
	}

	$result = array(
		"proceso" => $arrProceso,
		"sistema" => $arrSistema,
		"tipo" => $arrTipo,
		"ejecutado" => $arrejecutado,
		"pendiente" => $arrpendientes,
		"vencido" => $arrvencidos,
		"ejecutado2" => $arrejecutado2,
		"pendiente2" => $arrpendientes2,
		"vencido2" => $arrvencidos2,
		"ejecutado3" => $arrejecutado3,
		"pendiente3" => $arrpendientes3,
		"vencido3" => $arrvencidos3
	);

	$arr_respuesta = array(
		"status" => true,
		"data" => $result,
		"message" => ""
	);

	echo json_encode($arr_respuesta);
}

function lecturas_usuario()
{
	$ClsRev = new ClsRevision();
	$ClsInd = new ClsIndicador();
	$ClsFic = new ClsFicha();
	$ClsSis = new ClsSistema();
	$ClsPro = new ClsProceso();
	$usuario = $_SESSION["codigo"];
	//-- Proceso
	$result = $ClsFic->get_ficha_usuario("", "", $usuario);
	if (is_array($result)) {
		$i = 1;
		$arrProceso = array();
		$arrBajo = array();
		$arrMedia = array();
		$arrAlto = array();
		//---
		$arrBajo[0] = "Bajo el Minimo";
		$arrMedia[0] = "En Rango";
		$arrAlto[0] = "Sobre el Maximo";
		foreach ($result as $row) {
			$proceso = trim($row["fic_codigo"]);
			$nombre = trim($row["fic_nombre"]);
			$arrProceso[$i - 1] = $nombre;
			$arrBajo[$i] = 0;
			$arrMedia[$i] = 0;
			$arrAlto[$i] = 0;
			$indicadores = $ClsInd->get_indicador("", "", $proceso);
			if (is_array($indicadores)) {
				foreach ($indicadores as $rowIndicador) {
					$indicador = trim($rowIndicador["ind_codigo"]);
					//--
					$bajo = 0;
					$conveniente = 0;
					$arriba = 0;
					$j = 0;
					$result_lectura = $ClsRev->get_revision_indicador("", $indicador);
					if (is_array($result_lectura)) {
						foreach ($result_lectura as $row_lectura) {
							$lectura = $row_lectura['rev_lectura'];
							$minima = $row_lectura['ind_lectura_minima'];
							$maxima = $row_lectura['ind_lectura_maxima'];
							if ($lectura < $minima) $bajo++;
							else if ($lectura > $maxima) $arriba++;
							else $conveniente++;
							$j++;
						}
					}
					//-----------------
					$arrBajo[$i] += intval($bajo);
					$arrMedia[$i] += intval($conveniente);
					$arrAlto[$i] += intval($arriba);
				}
			}
			$i++;
		}
	}
	//-- Tipo de Proceso
	$result = $ClsPro->get_subtitulo("", 2);
	if (is_array($result)) {
		$i = 1;
		$arrTipo = array();
		$arrBajo2 = array();
		$arrMedia2 = array();
		$arrAlto2 = array();
		//---
		$arrBajo2[0] = "Bajo el Minimo";
		$arrMedia2[0] = "En Rango";
		$arrAlto2[0] = "Sobre el Maximo";
		foreach ($result as $row) {
			$tipo = trim($row["sub_codigo"]);
			$nombre = trim($row["sub_nombre"]);
			$arrTipo[$i - 1] = $nombre;
			$arrBajo2[$i] += 0;
			$arrMedia2[$i] += 0;
			$arrAlto2[$i] += 0;
			//--
			$procesos = $ClsFic->get_ficha_usuario("", $tipo, $usuario);
			foreach ($procesos as $rowProceso) {
				$proceso = trim($rowProceso["fic_codigo"]);
				$nombre = trim($rowProceso["fic_nombre"]);
				$indicadores = $ClsInd->get_indicador("", "", $proceso);
				if (is_array($indicadores)) {
					foreach ($indicadores as $rowIndicador) {
						$indicador = trim($rowIndicador["ind_codigo"]);
						//--
						$bajo = 0;
						$conveniente = 0;
						$arriba = 0;
						$j = 0;
						$result_lectura = $ClsRev->get_revision_indicador("", $indicador);
						if (is_array($result_lectura)) {
							foreach ($result_lectura as $row_lectura) {
								$lectura = $row_lectura['rev_lectura'];
								$minima = $row_lectura['ind_lectura_minima'];
								$maxima = $row_lectura['ind_lectura_maxima'];
								if ($lectura < $minima) $bajo++;
								else if ($lectura > $maxima) $arriba++;
								else $conveniente++;
								$j++;
							}
						}
						//-----------------
						$arrBajo2[$i] += intval($bajo);
						$arrMedia2[$i] += intval($conveniente);
						$arrAlto2[$i] += intval($arriba);
					}
				}
			}
			$i++;
		}
	}
	//-- Sistema
	$result = $ClsSis->get_sistema();
	if (is_array($result)) {
		$i = 1;
		$arrSistema = array();
		$arrBajo3 = array();
		$arrMedia3 = array();
		$arrAlto3 = array();
		//---
		$arrBajo3[0] = "Bajo el Minimo";
		$arrMedia3[0] = "En Rango";
		$arrAlto3[0] = "Sobre el Maximo";
		foreach ($result as $row) {
			$sistema = trim($row["sis_codigo"]);
			$nombre = trim($row["sis_nombre"]);
			$arrSistema[$i - 1] = $nombre;
			$indicadores = $ClsInd->get_indicador("", "", "", $sistema);
			foreach ($indicadores as $rowIndicador) {
				$indicador = trim($rowIndicador["ind_codigo"]);
				//--
				$bajo = 0;
				$conveniente = 0;
				$arriba = 0;
				$j = 0;
				$result_lectura = $ClsRev->get_revision_indicador("", $indicador, "", $usuario);
				if (is_array($result_lectura)) {
					foreach ($result_lectura as $row_lectura) {
						$lectura = $row_lectura['rev_lectura'];
						$minima = $row_lectura['ind_lectura_minima'];
						$maxima = $row_lectura['ind_lectura_maxima'];
						if ($lectura < $minima) $bajo++;
						else if ($lectura > $maxima) $arriba++;
						else $conveniente++;
						$j++;
					}
				}
				//-----------------
				$arrBajo3[$i] += intval($bajo);
				$arrMedia3[$i] += intval($conveniente);
				$arrAlto3[$i] += intval($arriba);
			}
			//--
			$i++;
		}
	}

	$result = array(
		"proceso" => $arrProceso,
		"bajo" => $arrBajo,
		"media" => $arrMedia,
		"alto" => $arrAlto,
		"tipo" => $arrTipo,
		"bajo2" => $arrBajo2,
		"media2" => $arrMedia2,
		"alto2" => $arrAlto2,
		"sistema" => $arrSistema,
		"bajo3" => $arrBajo3,
		"media3" => $arrMedia3,
		"alto3" => $arrAlto3,
	);

	$arr_respuesta = array(
		"status" => true,
		"data" => $result,
		"message" => ""
	);

	echo json_encode($arr_respuesta);
}
