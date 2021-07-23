<?php
ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cache");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);
//--
include_once('html_fns.php');

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
	$ClsAct = new ClsActividad();
	$ClsRie = new ClsRiesgo();
	$ClsOpo = new ClsOportunidad();
	$ClsPla = new ClsPlan();
	//--
	$ejecutado = 0;
	$pendiente = 0;
	$vencido = 0;
	$total = 0;
	$clase = 1;
	while ($clase <= 2) {
		$aprobados = ($clase == 1) ? $ClsRie->get_riesgo() :  $ClsOpo->get_oportunidad();
		if (is_array($aprobados)) {
			foreach ($aprobados as $rowAprobado) {
				if ($clase == 1) {
					$riesgo = trim($rowAprobado["rie_codigo"]);
					$result = $ClsPla->get_plan_ryo("", $riesgo, "", "", "", "", 3);
				} else {
					$oportunidad = trim($rowAprobado["opo_codigo"]);
					$result = $ClsPla->get_plan_ryo("", "", $oportunidad, "", "", "", 3);
				}
				if (is_array($result)) {
					foreach ($result as $row) {
						$plan = trim($row["pla_codigo"]);
						// Ejecutado
						$ejecutado += intval($ClsAct->count_programacion("", $plan, "", "", "3,4,5"));
						// Pendiente
						$pendiente += intval($ClsAct->count_programacion("", $plan, "", "", "", date("d/m/Y")));
						$pendiente += intval($ClsAct->count_programacion("", $plan, "", date("d/m/Y"), "1,2"));
						// Vencido
						$vencido += intval($ClsAct->count_programacion("", $plan, "", "", "1,2", "", date("d/m/Y")));
					}
				}
			}
		}
		$clase++;
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
	$ClsRie = new ClsRiesgo();
	$ClsOpo = new ClsOportunidad();
	$ClsAct = new ClsActividad();
	$ClsFic = new ClsFicha();
	$ClsSis = new ClsSistema();
	$ClsPro = new ClsProceso();
	$ClsPla = new ClsPlan();
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
			//--
			$clase = 1;
			while ($clase <= 2) {
				$aprobados = ($clase == 1) ? $ClsRie->get_riesgo("", "", $proceso) :  $ClsOpo->get_oportunidad("", "", $proceso);
				if (is_array($aprobados)) {
					foreach ($aprobados as $rowAprobado) {
						if ($clase == 1) {
							$riesgo = trim($rowAprobado["rie_codigo"]);
							$result = $ClsPla->get_plan_ryo("", $riesgo, "", "", "", "", 3);
						} else {
							$oportunidad = trim($rowAprobado["opo_codigo"]);
							$result = $ClsPla->get_plan_ryo("", "", $oportunidad, "", "", "", 3);
						}
						if (is_array($result)) {
							foreach ($result as $row) {
								$plan = trim($row["pla_codigo"]);
								// Ejecutado
								$ejecutado += intval($ClsAct->count_programacion("", $plan, "", "", "3,4,5"));
								// Pendiente
								$pendiente += intval($ClsAct->count_programacion("", $plan, "", "", "", date("d/m/Y")));
								$pendiente += intval($ClsAct->count_programacion("", $plan, "", date("d/m/Y"), "1,2"));
								// Vencido
								$vencido += intval($ClsAct->count_programacion("", $plan, "", "", "1,2", "", date("d/m/Y")));
							}
						}
					}
				}
				$clase++;
			}
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
			$clase = 1;
			while ($clase <= 2) {
				$aprobados = ($clase == 1) ? $ClsRie->get_riesgo("", "", "", $sistema) :  $ClsOpo->get_oportunidad("", "", "", $sistema);
				if (is_array($aprobados)) {
					foreach ($aprobados as $rowAprobado) {
						if ($clase == 1) {
							$riesgo = trim($rowAprobado["rie_codigo"]);
							$result = $ClsPla->get_plan_ryo("", $riesgo, "", "", "", "", 3);
						} else {
							$oportunidad = trim($rowAprobado["opo_codigo"]);
							$result = $ClsPla->get_plan_ryo("", "", $oportunidad, "", "", "", 3);
						}
						if (is_array($result)) {
							foreach ($result as $row) {
								$plan = trim($row["pla_codigo"]);
								// Ejecutado
								$ejecutado += intval($ClsAct->count_programacion("", $plan, "", "", "3,4,5"));
								// Pendiente
								$pendiente += intval($ClsAct->count_programacion("", $plan, "", "", "", date("d/m/Y")));
								$pendiente += intval($ClsAct->count_programacion("", $plan, "", date("d/m/Y"), "1,2"));
								// Vencido
								$vencido += intval($ClsAct->count_programacion("", $plan, "", "", "1,2", "", date("d/m/Y")));
							}
						}
					}
				}
				$clase++;
			}
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
				$clase = 1;
				while ($clase <= 2) {
					$aprobados = ($clase == 1) ? $ClsRie->get_riesgo("", "", $proceso) :  $ClsOpo->get_oportunidad("", "", $proceso);
					if (is_array($aprobados)) {
						foreach ($aprobados as $rowAprobado) {
							if ($clase == 1) {
								$riesgo = trim($rowAprobado["rie_codigo"]);
								$result = $ClsPla->get_plan_ryo("", $riesgo, "", "", "", "", 3);
							} else {
								$oportunidad = trim($rowAprobado["opo_codigo"]);
								$result = $ClsPla->get_plan_ryo("", "", $oportunidad, "", "", "", 3);
							}
							if (is_array($result)) {
								foreach ($result as $row) {
									$plan = trim($row["pla_codigo"]);
									// Ejecutado
									$ejecutado += intval($ClsAct->count_programacion("", $plan, "", "", "3,4,5"));
									// Pendiente
									$pendiente += intval($ClsAct->count_programacion("", $plan, "", "", "", date("d/m/Y")));
									$pendiente += intval($ClsAct->count_programacion("", $plan, "", date("d/m/Y"), "1,2"));
									// Vencido
									$vencido += intval($ClsAct->count_programacion("", $plan, "", "", "1,2", "", date("d/m/Y")));
								}
							}
						}
					}
					$clase++;
				}
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

function barra_usuario()
{
	$ClsAct = new ClsActividad();
	$ClsRie = new ClsRiesgo();
	$ClsOpo = new ClsOportunidad();
	$ClsFic = new ClsFicha();
	$ClsPla = new ClsPlan();
	//--
	$ejecutado = 0;
	$pendiente = 0;
	$vencido = 0;
	$total = 0;
	$usuario = $_SESSION["codigo"];
	$result = $ClsFic->get_ficha("", 3);
	if (is_array($result)) {
		foreach ($result as $row) {
			$proceso = trim($row["fic_codigo"]);
			$clase = 1;
			while ($clase <= 2) {
				$aprobados = ($clase == 1) ? $ClsRie->get_riesgo("", "", $proceso) :  $ClsOpo->get_oportunidad("", "", $proceso);
				if (is_array($aprobados)) {
					foreach ($aprobados as $rowAprobado) {
						if ($clase == 1) {
							$riesgo = trim($rowAprobado["rie_codigo"]);
							$result = $ClsPla->get_plan_ryo("", $riesgo, "", "", "", "", 3);
						} else {
							$oportunidad = trim($rowAprobado["opo_codigo"]);
							$result = $ClsPla->get_plan_ryo("", "", $oportunidad, "", "", "", 3);
						}
						if (is_array($result)) {
							foreach ($result as $row) {
								$plan = trim($row["pla_codigo"]);
								// Ejecutado
								$ejecutado += intval($ClsAct->count_programacion("", $plan, "", "", "3,4,5"));
								// Pendiente
								$pendiente += intval($ClsAct->count_programacion("", $plan, "", "", "", date("d/m/Y")));
								$pendiente += intval($ClsAct->count_programacion("", $plan, "", date("d/m/Y"), "1,2"));
								// Vencido
								$vencido += intval($ClsAct->count_programacion("", $plan, "", "", "1,2", "", date("d/m/Y")));
							}
						}
					}
				}
				$clase++;
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

function cumplimiento_usuario()
{
	$ClsRie = new ClsRiesgo();
	$ClsOpo = new ClsOportunidad();
	$ClsAct = new ClsActividad();
	$ClsFic = new ClsFicha();
	$ClsSis = new ClsSistema();
	$ClsPro = new ClsProceso();
	$ClsPla = new ClsPlan();
	//-- Proceso
	$usuario = $_SESSION["codigo"];
	$result = $ClsFic->get_ficha("", 3);
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
			//--
			$clase = 1;
			while ($clase <= 2) {
				$aprobados = ($clase == 1) ? $ClsRie->get_riesgo("", "", $proceso) :  $ClsOpo->get_oportunidad("", "", $proceso);
				if (is_array($aprobados)) {
					foreach ($aprobados as $rowAprobado) {
						if ($clase == 1) {
							$riesgo = trim($rowAprobado["rie_codigo"]);
							$result = $ClsPla->get_plan_ryo("", $riesgo, "", "", "", "", 3);
						} else {
							$oportunidad = trim($rowAprobado["opo_codigo"]);
							$result = $ClsPla->get_plan_ryo("", "", $oportunidad, "", "", "", 3);
						}
						if (is_array($result)) {
							foreach ($result as $row) {
								$plan = trim($row["pla_codigo"]);
								// Ejecutado
								$ejecutado += intval($ClsAct->count_programacion("", $plan, "", "", "3,4,5"));
								// Pendiente
								$pendiente += intval($ClsAct->count_programacion("", $plan, "", "", "", date("d/m/Y")));
								$pendiente += intval($ClsAct->count_programacion("", $plan, "", date("d/m/Y"), "1,2"));
								// Vencido
								$vencido += intval($ClsAct->count_programacion("", $plan, "", "", "1,2", "", date("d/m/Y")));
							}
						}
					}
				}
				$clase++;
			}
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
			$clase = 1;
			$procesos = $ClsFic->get_ficha("", 3);
			if (is_array($procesos)) {
				foreach ($procesos as $rowProceso) {
					$proceso = trim($rowProceso["fic_codigo"]);
					while ($clase <= 2) {
						$aprobados = ($clase == 1) ? $ClsRie->get_riesgo("", "", $proceso, $sistema) :  $ClsOpo->get_oportunidad("", "", $proceso, $sistema);
						if (is_array($aprobados)) {
							foreach ($aprobados as $rowAprobado) {
								if ($clase == 1) {
									$riesgo = trim($rowAprobado["rie_codigo"]);
									$result = $ClsPla->get_plan_ryo("", $riesgo, "", "", "", "", 3);
								} else {
									$oportunidad = trim($rowAprobado["opo_codigo"]);
									$result = $ClsPla->get_plan_ryo("", "", $oportunidad, "", "", "", 3);
								}
								if (is_array($result)) {
									foreach ($result as $row) {
										$plan = trim($row["pla_codigo"]);
										// Ejecutado
										$ejecutado += intval($ClsAct->count_programacion("", $plan, "", "", "3,4,5"));
										// Pendiente
										$pendiente += intval($ClsAct->count_programacion("", $plan, "", "", "", date("d/m/Y")));
										$pendiente += intval($ClsAct->count_programacion("", $plan, "", date("d/m/Y"), "1,2"));
										// Vencido
										$vencido += intval($ClsAct->count_programacion("", $plan, "", "", "1,2", "", date("d/m/Y")));
									}
								}
							}
						}
						$clase++;
					}
				}
			}
			$arrejecutado2[$i] = intval($ejecutado);
			$arrpendientes2[$i] = intval($pendiente);
			$arrvencidos2[$i] = intval($vencido);
			//--
			$i++;
		}
	}


	$result = array(
		"proceso" => $arrProceso,
		"sistema" => $arrSistema,
		"ejecutado" => $arrejecutado,
		"pendiente" => $arrpendientes,
		"vencido" => $arrvencidos,
		"ejecutado2" => $arrejecutado2,
		"pendiente2" => $arrpendientes2,
		"vencido2" => $arrvencidos2
	);

	$arr_respuesta = array(
		"status" => true,
		"data" => $result,
		"message" => ""
	);

	echo json_encode($arr_respuesta);
}
