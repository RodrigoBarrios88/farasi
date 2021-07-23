<?php
ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cache");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);

include_once('html_fns.php');

$request = $_REQUEST["request"];
switch ($request) {
	case "ejecucion_checklist":
		$sede = $_REQUEST["sede"];
		$sector = $_REQUEST["sector"];
		$area = $_REQUEST["area"];
		$categoria = $_REQUEST["categoria"];
		$fecha = $_REQUEST["fecha"];
		$hora = $_REQUEST["hora"];
		estadisticas_ejecucion_checklist($fecha, $hora, $categoria, $sede, $sector, $area);
		break;
	case "cumplimiento_checklist":
		$sede = $_REQUEST["sede"];
		$sector = $_REQUEST["sector"];
		$area = $_REQUEST["area"];
		$categoria = $_REQUEST["categoria"];
		$fecha = $_REQUEST["fecha"];
		estadistica_clumplimiento_checklist($categoria, $sede, $sector, $area, $fecha);
		break;
	case "categorias_status_checklist":
		$sede = $_REQUEST["sede"];
		$sector = $_REQUEST["sector"];
		$area = $_REQUEST["area"];
		$categoria = $_REQUEST["categoria"];
		$fecha = $_REQUEST["fecha"];
		estadisticas_categorias_status_chk($categoria, $sede, $sector, $area, $fecha);
		break;
	case "tabla_horarios":
		$sede = $_REQUEST["sede"];
		$sector = $_REQUEST["sector"];
		$area = $_REQUEST["area"];
		$categoria = $_REQUEST["categoria"];
		$fecha = $_REQUEST["fecha"];
		tabla_horarios($categoria, $sede, $sector, $area, $fecha);
		break;
	default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

//////////////////////////////////////// CHECKLIST ///////////////////////////////////////////
function estadisticas_ejecucion_checklist($fecha, $hora, $categoria, $sede, $sector, $area)
{
	$sedesIn = $_SESSION["sedes_in"];
	$sede = ($sede == "Todas") ? "" : $sede;
	$sede = ($sede == "") ? $sedesIn : $sede;
	$categoriasIn = $_SESSION["categorias_in"];
	$categoria = ($categoria == "") ? $categoriasIn : $categoria;
	$ClsRev = new ClsRevision();
	$result = $ClsRev->get_categoria_compara($fecha, $fecha, $hora, $categoria, $sede, $sector, $area);
	if (is_array($result)) {
		$i = 1;
		$total_si = 0;
		$total_no = 0;
		$total_na = 0;
		$arrcodigos = array();
		$arrcategoria = array();
		$arrsi = array();
		$arrno = array();
		$arrnaplica = array();
		//--
		$arrsi[0] = "SI";
		$arrno[0] = "NO";
		$arrnaplica[0] = "NO APLICA";
		foreach ($result as $row) {
			//categorias
			$categoria_codigo = trim($row["cat_codigo"]);
			$nombre = trim($row["cat_nombre"]);
			$arrcodigos[$i - 1] = intval($categoria_codigo);
			$arrcategoria[$i - 1] = $nombre;
			$color = trim($row["cat_color"]);
			//SI
			$resp_si = intval($row["respuestas_si"]);
			$arrsi[$i] = $resp_si;
			$total_si += $resp_si;
			//NO
			$resp_no = intval($row["respuestas_no"]);
			$arrno[$i] = $resp_no;
			$total_no += $resp_no;
			//NO APLICA
			$resp_na = intval($row["respuestas_na"]); /// para no afectar el promedio con las "No Aplica" estas no se contarán
			$arrnaplica[$i] = $resp_si;
			$total_na += $resp_na;
			//--
			$i++;
		}
		///regla de 3 para porcentajes
		$total_respuestas = $total_si + $total_no;
		//echo "$total_respuestas = $total_si + $total_na + $total_no";
		if ($total_respuestas > 0) {
			$porcent_si = round(($total_si * 100) / $total_respuestas);
			$porcent_no = round(($total_no * 100) / $total_respuestas);
		} else {
			$porcent_si = 0;
			$porcent_no = 0;
		}
	} else {
		$arrcodigos = array();
		$arrcategoria = array();
		$arrsi = array();
		$arrno = array();
		$total_si = array();
		$total_no = array();
	}
	$result = array(
		"codigos" => $arrcodigos,
		"categorias" => $arrcategoria,
		"respsi" => $arrsi,
		"respno" => $arrno,
		"porcentSi" => array("Respuestas SI", $porcent_si),
		"porcentNo" => array("Respuestas NO", $porcent_no),
		"totalSi" => $total_si,
		"totalNo" => $total_no
	);
	$arr_respuesta = array(
		"status" => true,
		"data" => $result,
		"message" => ""
	);

	echo json_encode($arr_respuesta);
}


function estadistica_clumplimiento_checklist($categoria, $sede, $sector, $area, $fecha)
{
	$sedesIn = $_SESSION["sedes_in"];
	$sede = ($sede == "Todas") ? "" : $sede;
	$sede = ($sede == "") ? $sedesIn : $sede;
	$categoriasIn = $_SESSION["categorias_in"];
	$categoria = ($categoria == "") ? $categoriasIn : $categoria;
	$fecha_ymd = regresa_fecha($fecha);
	$dia = date("w", strtotime(date($fecha_ymd)));
	$dia = ($dia == 0) ? 7 : $dia; //valida que el domingo sea 7
	$ClsLis = new ClsLista();
	$clsChkRev = new ClsRevision();
	$ejecutado = 0;
	$pendiente = 0;
	$vencido = 0;
	for ($i = 1; $i <= 3; $i++) {
		$result = null;
		switch ($i) {
			case 1:
				$dia = date("d");
				$result = $ClsLis->get_programacion('', '', $sede, $sector, $area, $categoria, $dia, '', 1, '', $fecha, $fecha, 'M');
				break;
			case 2:
				$diaSemana = date("D");
				$result = $ClsLis->get_programacion('', '', $sede, $sector, $area, $categoria, $diaSemana, '', 1, '', $fecha, $fecha, 'J');
				break;
			case 3:
				$fechaHoy = date('Y-m-d');
				$result = $ClsLis->get_programacion('', '', $sede, $sector, $area, $categoria, $fechaHoy, '', 1, '', $fecha, $fecha, 'U');
				break;
		}
		if (is_array($result)) {
			foreach ($result as $row) {
				//situacion
				$fechaSM = regresa_fecha($fecha);
				$fecha_actual = strtotime(date("d-m-Y", time()));
				$fecha_entrada = strtotime("$fechaSM");
				if ($fecha_actual >= $fecha_entrada) {  // Si la actual es mayor o igual a la fecha de entrada (filtro de busqueda), realiza calculo de vencimiento, si no marca pendiente
					$revision = $row["revision_ejecutada"];
					$situacion = ($revision != "") ? '<strong class="text-success">Ejecutado</strong>' : '<strong class="text-muted">Pendiente</strong>';
					if ($revision != "") {
						$ejecutado++;
						$situacion = '<strong class="text-success">Ejecutado</strong>';
					} else {
						if ($fecha_entrada >= $fecha_actual) { // Si la fecha de entrada es (filtro de busqueda) es mayor o igual a la fecha actual, realiza calculo de vencimiento, si no marca vencido
							$hora1 = substr($row["pro_hfin"], 0, 5);
							$hora2 = date("H:i");
							$mayor = compara_horas($hora1, $hora2);
							if ($mayor == true) {
								$pendiente++;
								$situacion = '<strong class="text-muted">Pendiente</strong>';
							} else {
								$vencido++;
								$situacion = '<strong class="text-danger">Vencido</strong>';
							}
						} else {
							$vencido++;
							$situacion = '<strong class="text-danger">Vencido</strong>';
						}
					}
				} else {
					$pendiente++;
					$situacion = '<strong class="text-muted">Pendiente</strong>';
				}
			}
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


function estadisticas_categorias_status_chk($categoria, $sede, $sector, $area, $fecha)
{
	$ClsCat = new ClsCategoria();
	$ClsLis = new ClsLista();
	$clsChkRev = new ClsRevision();

	//--
	$sede = ($sede == "Todas") ? "" : $sede;
	$fecha_ymd = regresa_fecha($fecha);
	$dia = date("w", strtotime(date($fecha_ymd)));
	$dia = ($dia == 0) ? 7 : $dia; //valida que el domingo sea 7
	//--
	$result = $ClsCat->get_categoria_checklist($categoria, '', 1);
	////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////
	if (is_array($result)) {
		$i = 1;
		$arrcategoria = array();
		$arrejecutado = array();
		$arrpendientes = array();
		$arrvencidos = array();
		//---
		$arrejecutado[0] = "Ejecutado";
		$arrpendientes[0] = "Pendiente";
		$arrvencidos[0] = "Vencido";
		foreach ($result as $row) {
			$categoria = trim($row["cat_codigo"]);
			$nombre = trim($row["cat_nombre"]);
			$arrcategoria[$i - 1] = $nombre;
			//--
			$ejecutado = 0;
			$pendiente = 0;
			$vencido = 0;
			$j = 0;
			//$result_programacion = $ClsLis->get_programacion('','',$sede,$sector,$area,$categoria,$dia,'',1,'',$fecha,$fecha);

			for ($ii = 1; $ii <= 3; $ii++) {
				$result_programacion = null;
				switch ($ii) {
					case 1:
						$dia = date("d");
						$result_programacion = $ClsLis->get_programacion('', '', $sede, $sector, $area, $categoria, $dia, '', 1, '', $fecha, $fecha, 'M');
						break;
					case 2:
						$diaSemana = date("D");
						$result_programacion = $ClsLis->get_programacion('', '', $sede, $sector, $area, $categoria, $diaSemana, '', 1, '', $fecha, $fecha, 'J');
						break;
					case 3:
						$fechaHoy = date('Y-m-d');
						$result_programacion = $ClsLis->get_programacion('', '', $sede, $sector, $area, $categoria, $fechaHoy, '', 1, '', $fecha, $fecha, 'U');
						break;
				}
				////////////////////////////////////////////
				if (is_array($result_programacion)) {
					foreach ($result_programacion as $row) {
						//situacion
						$fechaSM = regresa_fecha($fecha);
						$fecha_actual = strtotime(date("d-m-Y", time()));
						$fecha_entrada = strtotime("$fechaSM");
						if ($fecha_actual >= $fecha_entrada) {  // Si la actual es mayor o igual a la fecha de entrada (filtro de busqueda), realiza calculo de vencimiento, si no marca pendiente
							$revision = $row["revision_ejecutada"];
							$situacion = ($revision != "") ? '<strong class="text-success">Ejecutado</strong>' : '<strong class="text-muted">Pendiente</strong>';
							if ($revision != "") {
								$ejecutado++;
								$situacion = '<strong class="text-success">Ejecutado</strong>';
							} else {
								if ($fecha_entrada >= $fecha_actual) { // Si la fecha de entrada es (filtro de busqueda) es mayor o igual a la fecha actual, realiza calculo de vencimiento, si no marca vencido
									$hora1 = substr($row["pro_hfin"], 0, 5);
									$hora2 = date("H:i");
									$mayor = compara_horas($hora1, $hora2);
									if ($mayor == true) {
										$pendiente++;
										$situacion = '<strong class="text-muted">Pendiente</strong>';
									} else {
										$vencido++;
										$situacion = '<strong class="text-danger">Vencido</strong>';
									}
								} else {
									$vencido++;
									$situacion = '<strong class="text-danger">Vencido</strong>';
								}
							}
						} else {
							$pendiente++;
							$situacion = '<strong class="text-muted">Pendiente</strong>';
						}
						$j++;
					}
				}
				///////////////////////////////////////////////////
			}


			$arrejecutado[$i] = intval($ejecutado);
			$arrpendientes[$i] = intval($pendiente);
			$arrvencidos[$i] = intval($vencido);
			$i++;
		}
	}
	////////////////////////////////////////////////////////




	$result = array(
		"categorias" => $arrcategoria,
		"ejecutado" => $arrejecutado,
		"pendiente" => $arrpendientes,
		"vencido" => $arrvencidos
	);
	$arr_respuesta = array(
		"status" => true,
		"data" => $result,
		"message" => ""
	);
	echo json_encode($arr_respuesta);
}


function tabla_horarios($categoria, $sede, $sector, $area, $fecha)
{
	$sedesIn = $_SESSION["sedes_in"];
	$sede = ($sede == "Todas") ? "" : $sede;
	$sede = ($sede == "") ? $sedesIn : $sede;
	$categoriasIn = $_SESSION["categorias_in"];
	$categoria = ($categoria == "") ? $categoriasIn : $categoria;
	$fecha_ymd = regresa_fecha($fecha);
	$dia = date("w", strtotime(date($fecha_ymd)));
	$dia = ($dia == 0) ? 7 : $dia; //valida que el domingo sea 7
	$ClsLis = new ClsLista();
	//	$result = $ClsLis->get_programacion('','',$sede,$sector,$area,$categoria,$dia,'',1,'',$fecha,$fecha);
	$salida = '<table class="table table-striped dataTables-example" width="100%" >';
	$salida .= '<thead>';
	$salida .= '<tr>';
	$salida .= '<th class = "text-center" width = "30px">No.</th>';
	$salida .= '<th class = "text-center" width = "30px">Progra.</th>';
	$salida .= '<th class = "text-center" width = "30px">QR</th>';
	$salida .= '<th class = "text-center" width = "100px">Sede</th>';
	$salida .= '<th class = "text-center" width = "100px">&Aacute;rea</th>';
	$salida .= '<th class = "text-center" width = "100px">Lista</th>';
	$salida .= '<th class = "text-center" width = "100px">Categor&iacute;a</th>';
	$salida .= '<th class = "text-center" width = "50px">Rango de Chequeo</th>';
	$salida .= '<th class = "text-center" width = "150px">Observaciones</th>';
	$salida .= '<th class = "text-center" width = "50px">Situaci&oacute;n</th>';
	$salida .= '</tr>';
	$salida .= '</thead>';
	$salida .= '<tbody>';
	$ii = 1;

	for ($i = 1; $i <= 3; $i++) {
		$result = null;
		switch ($i) {
			case 1:
				$dia = date("d");
				$result = $ClsLis->get_programacion('', '', $sede, $sector, $area, $categoria, $dia, '', 1, '', $fecha, $fecha, 'M');
				break;
			case 2:
				$diaSemana = date("D");
				$result = $ClsLis->get_programacion('', '', $sede, $sector, $area, $categoria, $diaSemana, '', 1, '', $fecha, $fecha, 'J');
				break;
			case 3:
				$fechaHoy = date('Y-m-d');
				$result = $ClsLis->get_programacion('', '', $sede, $sector, $area, $categoria, $fechaHoy, '', 1, '', $fecha, $fecha, 'U');
				break;
		}
		if (is_array($result)) {
			foreach ($result as $row) {
				$salida .= '<tr>';
				//codigo
				$salida .= '<td class = "text-center">' . $ii . '.</td>';
				//programacion
				$prograH = $row["pro_codigo"];
				$salida .= '<td class = "text-center">#' . $prograH . '</td>';
				//sede
				$sedeH = Agrega_Ceros($row["are_codigo"]);
				$salida .= '<td class = "text-left">' . $sedeH . '</td>';
				//sede
				$sedeH = trim($row["sed_nombre"]);
				$salida .= '<td class = "text-left">' . $sedeH . '</td>';
				//area
				$sedeH = trim($row["are_nombre"]);
				$salida .= '<td class = "text-left">' . $sedeH . '</td>';
				//nombre
				$nomH = trim($row["list_nombre"]);
				$salida .= '<td class = "text-left">' . $nomH . '</td>';
				//categoria
				$categoriaH = trim($row["cat_nombre"]);
				$salida .= '<td class = "text-left">' . $categoriaH . '</td>';
				//horarios
				$hiniH = substr($row["pro_hini"], 0, 5);
				$hfinH = substr($row["pro_hfin"], 0, 5);
				$salida .= '<td class = "text-center">' . $hiniH . '-' . $hfinH . '</td>';
				//Obs	
				$obsH = trim($row["pro_observaciones"]);
				$salida .= '<td class = "text-left">' . $obsH . '</td>';
				//situacion
				$fechaSM = regresa_fecha($fecha);
				$fecha_actual = strtotime(date("d-m-Y", time()));
				$fecha_entrada = strtotime("$fechaSM");
				if ($fecha_actual >= $fecha_entrada) {  // Si la actual es mayor o igual a la fecha de entrada (filtro de busqueda), realiza calculo de vencimiento, si no marca pendiente
					$revision = $row["revision_ejecutada"];
					$situacion = ($revision != "") ? '<strong class="text-success">Ejecutado</strong>' : '<strong class="text-muted">Pendiente</strong>';
					if ($revision != "") {
						$situacion = '<strong class="text-success">Ejecutado</strong>';
						//var_dump('entre');
					} else {
						if ($fecha_entrada >= $fecha_actual) { // Si la fecha de entrada es (filtro de busqueda) es mayor o igual a la fecha actual, realiza calculo de vencimiento, si no marca vencido
							//echo 'entre';
							$hora1 = substr($row["pro_hfin"], 0, 5);
							$hora2 = date("H:i");
							$mayor = compara_horas($hora1, $hora2);
							if ($mayor == true) {
								$situacion = '<strong class="text-muted">Pendiente</strong>';
							} else {
								$situacion = '<strong class="text-danger">Vencido</strong>';
							}
						} else {
							$situacion = '<strong class="text-danger">Vencido</strong>';
						}
					}
				} else {
					$situacion = '<strong class="text-muted">Pendiente</strong>';
				}
				$salida .= '<td class = "text-center">' . $situacion . '</td>';
				//--
				$salida .= '</tr>';
				$ii++;
			}
		}
	}
	$salida .= '</tbody>';
	$salida .= '</table>';

	$arr_respuesta = array(
		"status" => true,
		"tabla" => $salida,
		"message" => ""
	);
	echo json_encode($arr_respuesta);
}
