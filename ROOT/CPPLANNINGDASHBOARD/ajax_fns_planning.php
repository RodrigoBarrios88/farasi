<?php
ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cache");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);
//--
include_once('html_fns_planning.php');

header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header("Access-Control-Allow-Origin: *");

///API REQUESTT
$request = $_REQUEST["request"];
$_REQUEST = str_replace("undefined", "", $_REQUEST); ///valida campos "undefined" desde javascript

if ($request != "") {
	switch ($request) {
			///////////// Dashboard de Usuario ////////////
		case "tabla_usuario":
			$proceso = $_REQUEST["proceso"];
			$sistema = $_REQUEST["sistema"];
			$desde = $_REQUEST["desde"];
			$hasta = $_REQUEST["hasta"];
			tabla_usuario($proceso, $sistema, $desde, $hasta);
			break;
		case "cumplimiento_proceso":
			cumplimiento_proceso();
			break;
		case "cumplimiento_sistema":
			cumplimiento_sistema();
			break;
		case "cumplimiento_tipo":
			cumplimiento_tipo();
			break;
		case "cumplimiento_general":
			cumplimiento_general();
			break;
		case "objetivos_sistema":
			objetivos_sistema();
			break;
		case "acciones_sistema":
			acciones_sistema();
			break;
		case "objetivos_status":
			objetivos_status();
			break;
		case "acciones_status":
			acciones_status();
			break;
			///////////// Dashboard de Gerencia ////////////
		case "tabla_gerencia":
			$proceso = $_REQUEST["proceso"];
			$sistema = $_REQUEST["sistema"];
			$usuario = $_REQUEST["usuario"];
			tabla_gerencia($proceso, $sistema, $usuario);
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

////////////////////////////// Dashboard de Usuario //////////////////////////
function tabla_usuario($proceso, $sistema, $desde, $hasta)
{
	$usuario = $_SESSION["codigo"];
	$ClsObj = new ClsObjetivo();
	$result = $ClsObj->get_objetivo_asignado($proceso, $sistema, $usuario);
	if (is_array($result)) {
		$data = objetivos_dashboard($proceso, $sistema, $usuario, $desde, $hasta);
		$payload = array(
			"status" => true,
			"data" => $data,
			"message" => "Success"
		);
		echo json_encode($payload);
	} else {
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"message" => "Esta accion no existe..."
		);
		echo json_encode($payload);
	}
}

function cumplimiento_general()
{
	$ClsFic = new ClsFicha();
	$ClsObj = new ClsObjetivo();
	$ClsAcc = new ClsAccion();
	$ClsEva = new ClsEvaluacion();

	$cumplimiento = 0;
	$procesos = $ClsFic->get_ficha_usuario("", "", $_SESSION["codigo"]);
	foreach ($procesos as $rowProceso) {
		$proceso = trim($rowProceso["fic_codigo"]);
		// Acciones Evaluadas para cada objetivo
		$objetivos = $ClsObj->get_objetivo("", $proceso);
		if(is_array($objetivos)){
			foreach ($objetivos as $row) {
				$objetivo = trim($row["obj_codigo"]);
				$numero_programaciones = $ClsAcc->count_programacion("", "", $objetivo, $_SESSION["codigo"]);
				$numero_evaluaciones = $ClsEva->count_evaluacion("", "", "", "", "", "", $objetivo, $_SESSION["codigo"]);
				$promedio_objetivo = 0;
				if ($numero_programaciones != 0) $promedio_objetivo = $numero_evaluaciones / $numero_programaciones;
				$cumplimiento = $cumplimiento + $promedio_objetivo;
			}
		}
		$numero_objetivos = $ClsObj->count_objetivo("", $proceso);
		if ($numero_objetivos != 0) $cumplimiento = $cumplimiento / $numero_objetivos;
	}


	$arr_respuesta = array(
		"status" => true,
		"cumplimiento" =>  round($cumplimiento * 100, 2),
		"message" => ""
	);

	echo json_encode($arr_respuesta);
}

function cumplimiento_proceso()
{
	$ClsFic = new ClsFicha();
	$ClsObj = new ClsObjetivo();
	$ClsAcc = new ClsAccion();
	$ClsEva = new ClsEvaluacion();
	$salida = '<table class="table" >';

	// Obtener todos los procesos
	$result = $ClsFic->get_ficha_usuario("", "", $_SESSION["codigo"]);
	if (is_array($result)) {
		$i = 0;
		//---
		foreach ($result as $row) {
			$salida .= '<tr>';
			$nombre = trim($row["fic_nombre"]);
			$proceso = trim($row["fic_codigo"]);
			//--
			$cumplimiento = 0;
			// Acciones Evaluadas para cada objetivo
			$objetivos = $ClsObj->get_objetivo("", $proceso);
			if(is_array($objetivos)){
				foreach ($objetivos as $row2) {
					$objetivo = trim($row2["obj_codigo"]);
					$numero_programaciones = $ClsAcc->count_programacion("", "", $objetivo, $_SESSION["codigo"]);
					$numero_evaluaciones = $ClsEva->count_evaluacion("", "", "", "", "", "", $objetivo, $_SESSION["codigo"]);
					$promedio_objetivo = 0;
					if ($numero_programaciones != 0) $promedio_objetivo = $numero_evaluaciones / $numero_programaciones;
					$cumplimiento = $cumplimiento + $promedio_objetivo;
				}
			}
			$numero_objetivos = $ClsObj->count_objetivo("", $proceso);
			// echo "\n--------- SUMA -------------\n";ed
			if ($numero_objetivos != 0) $cumplimiento = $cumplimiento / $numero_objetivos;
			//-----------------
			//--
			$i++;
			$salida .= '<td width = "85%" class = "text-left">' . $nombre . '</td>';
			$salida .= '<td width = "15%" class = "text-left">' . round($cumplimiento * 100, 2) . ' %</td>';
			$salida .= '</tr>';
		}
	}
	$salida .= '</table>';


	$arr_respuesta = array(
		"status" => true,
		"data" => $salida,
		"message" => ""
	);

	echo json_encode($arr_respuesta);
}

function cumplimiento_sistema()
{
	$ClsFic = new ClsFicha();
	$ClsSis = new ClsSistema();
	$ClsObj = new ClsObjetivo();
	$ClsAcc = new ClsAccion();
	$ClsEva = new ClsEvaluacion();
	$salida = '<table class="table" >';

	// Obtener todos los sistemas
	$result = $ClsSis->get_sistema();
	if (is_array($result)) {
		$i = 0;
		//---
		foreach ($result as $row) {
			$salida .= '<tr>';
			$nombre = trim($row["sis_nombre"]);
			$sistema = trim($row["sis_codigo"]);
			//--
			$cumplimiento = 0;
			$procesos = $ClsFic->get_ficha_usuario("", "", $_SESSION["codigo"]);
			if(is_array($procesos)){
				foreach ($procesos as $rowProceso) {
					$proceso = trim($rowProceso["fic_codigo"]);
					// Acciones Evaluadas para cada objetivo
					$objetivos = $ClsObj->get_objetivo("", $proceso, $sistema);
					foreach ($objetivos as $row2) {
						$objetivo = trim($row2["obj_codigo"]);
						$numero_programaciones = $ClsAcc->count_programacion("", "", $objetivo, $_SESSION["codigo"]);
						$numero_evaluaciones = $ClsEva->count_evaluacion("", "", "", "", "", "", $objetivo, $_SESSION["codigo"]);
						$promedio_objetivo = 0;
						if ($numero_programaciones != 0) $promedio_objetivo = $numero_evaluaciones / $numero_programaciones;
						$cumplimiento = $cumplimiento + $promedio_objetivo;
					}
					$numero_objetivos = $ClsObj->count_objetivo("", "", $sistema);
					// echo "\n--------- SUMA -------------\n";ed
					if ($numero_objetivos != 0) $cumplimiento = $cumplimiento / $numero_objetivos;
				}
			}
			//-----------------
			//--
			$i++;
			$salida .= '<td width = "85%" class = "text-left">' . $nombre . '</td>';
			$salida .= '<td width = "15%" class = "text-left">' . round($cumplimiento * 100, 2) . ' %</td>';
			$salida .= '</tr>';
		}
	}
	$salida .= '</table>';


	$arr_respuesta = array(
		"status" => true,
		"data" => $salida,
		"message" => ""
	);

	echo json_encode($arr_respuesta);
}

function cumplimiento_tipo()
{
	$ClsPro = new ClsProceso();
	$ClsFic = new ClsFicha();
	$ClsObj = new ClsObjetivo();
	$ClsAcc = new ClsAccion();
	$ClsEva = new ClsEvaluacion();
	$salida = '<table class="table" >';

	// Obtener todos los sistemas
	$result = $ClsPro->get_subtitulo("", 2);
	if (is_array($result)) {
		$i = 0;
		//---
		foreach ($result as $row) {
			$salida .= '<tr>';
			$nombre = trim($row["sub_nombre"]);
			$tipo = trim($row["sub_codigo"]);
			//--
			$cumplimiento = 0;
			// Procesos para cada tipo
			$procesos = $ClsFic->get_ficha_usuario("", "", $_SESSION["codigo"], "", "", $tipo);
			if(is_array($procesos)){
				foreach ($procesos as $rowProceso) {
					$proceso = trim($rowProceso["fic_codigo"]);
					// Acciones Evaluadas para cada objetivo
					$objetivos = $ClsObj->get_objetivo("", $proceso);
					foreach ($objetivos as $row2) {
						$objetivo = trim($row2["obj_codigo"]);
						$numero_programaciones = $ClsAcc->count_programacion("", "", $objetivo, $_SESSION["codigo"]);
						$numero_evaluaciones = $ClsEva->count_evaluacion("", "", "", "", "", "", $objetivo, $_SESSION["codigo"]);
						$promedio_objetivo = 0;
						if ($numero_programaciones != 0) $promedio_objetivo = $numero_evaluaciones / $numero_programaciones;
						$cumplimiento = $cumplimiento + $promedio_objetivo;
					}
					$numero_objetivos = $ClsObj->count_objetivo("", $proceso);
					// echo "\n--------- SUMA -------------\n";ed
					if ($numero_objetivos != 0) $cumplimiento = $cumplimiento / $numero_objetivos;
				}
			}
			$numero_procesos = $ClsFic->count_ficha("", $tipo);
			if ($numero_procesos != 0) $cumplimiento = $cumplimiento / $numero_procesos;	//--
			$i++;
			$salida .= '<td width = "85%" class = "text-left">' . $nombre . '</td>';
			$salida .= '<td width = "15%" class = "text-left">' . round($cumplimiento * 100, 2) . ' %</td>';
			$salida .= '</tr>';
		}
	}
	$salida .= '</table>';


	$arr_respuesta = array(
		"status" => true,
		"data" => $salida,
		"message" => ""
	);

	echo json_encode($arr_respuesta);
}

function objetivos_sistema()
{
	$ClsFic = new ClsFicha();
	$ClsSis = new ClsSistema();
	$ClsObj = new ClsObjetivo();
	$salida = '<table class="table" >';

	// Obtener todos los sistemas
	$result = $ClsSis->get_sistema();
	if (is_array($result)) {
		$i = 0;
		//---
		foreach ($result as $row) {
			$salida .= '<tr>';
			$nombre = trim($row["sis_nombre"]);
			$sistema = trim($row["sis_codigo"]);
			//--
			$numero_objetivos = 0;
			$procesos = $ClsFic->get_ficha_usuario("", "", $_SESSION["codigo"]);
			if(is_array($procesos)){
				foreach ($procesos as $rowProceso) {
					$proceso = trim($rowProceso["fic_codigo"]);
					$numero_objetivos = $numero_objetivos + intval($ClsObj->count_objetivo("", $proceso, $sistema));
				}
			}
			//--
			$i++;
			$salida .= '<td width = "85%" class = "text-left">' . $nombre . '</td>';
			$salida .= '<td width = "15%" class = "text-left">' . $numero_objetivos . ' </td>';
			$salida .= '</tr>';
		}
	}
	$salida .= '</table>';


	$arr_respuesta = array(
		"status" => true,
		"data" => $salida,
		"message" => ""
	);

	echo json_encode($arr_respuesta);
}

function acciones_sistema()
{
	$ClsFic = new ClsFicha();
	$ClsSis = new ClsSistema();
	$ClsObj = new ClsObjetivo();
	$ClsAcc = new ClsAccion();
	$salida = '<table class="table" >';

	// Obtener todos los sistemas
	$result = $ClsSis->get_sistema();
	if (is_array($result)) {
		$i = 0;
		//---
		foreach ($result as $row) {
			$salida .= '<tr>';
			$nombre = trim($row["sis_nombre"]);
			$sistema = trim($row["sis_codigo"]);
			//--
			$numero_acciones = 0;
			$procesos = $ClsFic->get_ficha_usuario("", "", $_SESSION["codigo"]);
			if(is_array($procesos)){
				foreach ($procesos as $rowProceso) {
					$proceso = trim($rowProceso["fic_codigo"]);
					$objetivos = $ClsObj->get_objetivo("", $proceso, $sistema);
					if(is_array($objetivos)){
						foreach ($objetivos as $row2) {
							$objetivo = trim($row2["obj_codigo"]);
							$numero_acciones = $numero_acciones + intval($ClsAcc->count_accion("", $objetivo, $proceso, $_SESSION["codigo"]));
						}
					}
				}
			}
			//--
			$i++;
			$salida .= '<td width = "85%" class = "text-left">' . $nombre . '</td>';
			$salida .= '<td width = "15%" class = "text-left">' . $numero_acciones . ' </td>';
			$salida .= '</tr>';
		}
	}
	$salida .= '</table>';


	$arr_respuesta = array(
		"status" => true,
		"data" => $salida,
		"message" => ""
	);

	echo json_encode($arr_respuesta);
}

function objetivos_status()
{
	$ClsObj = new ClsObjetivo();
	$usuario = $_SESSION["codigo"];
	$salida = '<table class="table" >';
	$salida .= '<tr>';
	$result = $ClsObj->get_objetivo_asignado("", "", $usuario);
	$en_edicion = 0;
	foreach ($result as $row) {
		$codigo = $row["obj_codigo"];
		$revision = $ClsObj->get_revision("", "", "", $usuario, $codigo);
		if (!is_array($revision)) $en_edicion++;
	}
	$salida .= '<td width = "85%" class = "text-left"> En Edici&oacute;n </td>';
	$en_edicion += intval($ClsObj->count_revision("", "", "", $usuario, "", 1));
	$salida .= '<td width = "15%" class = "text-left">' . $en_edicion . ' </td>';
	$salida .= '</tr>';
	$salida .= '<tr>';
	$salida .= '<td width = "85%" class = "text-left"> En Aprobaci&oacute;n </td>';
	$salida .= '<td width = "15%" class = "text-left">' . $ClsObj->count_revision("", "", "", $usuario, "", 2) . ' </td>';
	$salida .= '</tr>';
	$salida .= '<tr>';
	$salida .= '<td width = "85%" class = "text-left"> Aprobado </td>';
	$salida .= '<td width = "15%" class = "text-left">' . $ClsObj->count_revision("", "", "", $usuario, "", 3) . ' </td>';
	$salida .= '</tr>';
	$salida .= '</table>';


	$arr_respuesta = array(
		"status" => true,
		"data" => $salida,
		"message" => ""
	);

	echo json_encode($arr_respuesta);
}

function acciones_status()
{
	$ClsObj = new ClsObjetivo();
	$ClsAcc = new ClsAccion();
	$usuario = $_SESSION["codigo"];
	$salida = '<table class="table" >';
	///////////// Edicion ///////////
	$salida .= '<tr>';
	$result = $ClsObj->get_objetivo_asignado("", "", $usuario);
	$acciones = 0;
	foreach ($result as $row) {
		$objetivo = $row["obj_codigo"];
		$revision = $ClsObj->get_revision("", "", "", $usuario, $objetivo);
		if (!is_array($revision)) $acciones += intval($ClsAcc->count_accion("", $objetivo));
	}
	$salida .= '<td width = "85%" class = "text-left"> En Edici&oacute;n </td>';
	$acciones += intval($ClsObj->count_revision("", "", "", $usuario, "", 1));
	$salida .= '<td width = "15%" class = "text-left">' . $acciones . ' </td>';
	$salida .= '</tr>';
	///////////// En Aprobacion ///////////
	$salida .= '<tr>';
	$salida .= '<td width = "85%" class = "text-left"> En Aprobaci&oacute;n </td>';
	$acciones = 0;
	$revisiones = $ClsObj->get_revision("", "", "", $usuario, "", 2);
	foreach ($revisiones as $rowRevision) {
		$objetivo = trim($rowRevision["rev_objetivo"]);
		$acciones += intval($ClsAcc->count_accion("", $objetivo));
	}
	$salida .= '<td width = "15%" class = "text-left">' . $acciones . ' </td>';
	$salida .= '</tr>';
	///////////// Aprobadas ///////////
	$salida .= '<tr>';
	$salida .= '<td width = "85%" class = "text-left"> Aprobadas </td>';
	$acciones = 0;
	$revisiones = $ClsObj->get_revision("", "", "", $usuario, "", 3);
	foreach ($revisiones as $rowRevision) {
		$objetivo = trim($rowRevision["rev_objetivo"]);
		$acciones += intval($ClsAcc->count_accion("", $objetivo));
	}
	$salida .= '<td width = "15%" class = "text-left">' . $acciones . ' </td>';
	$salida .= '</tr>';
	///////////// Ejecucion ///////////
	$disponibles = 0;
	$enProceso = 0;
	$Vencidas = 0;
	$Finalizadas = 0;
	$Canceladas = intval($ClsAcc->count_programacion_aprobada("", $usuario, "", "", "", "", 0));
	$ClsEje = new ClsEjecucion();
	$programaciones = $ClsAcc->get_programacion_aprobada("", $usuario);
	foreach ($programaciones as $rowProgramacion) {
		$codigo = $rowProgramacion["pro_codigo"];
		$fini = trim($rowProgramacion["pro_fecha_inicio"]);
		$ffin = trim($rowProgramacion["pro_fecha_fin"]);
		$tipo = trim($rowProgramacion["acc_tipo"]);
		$diaInicio = trim($rowProgramacion["pro_dia_inicio"]);
		$diaFinal = trim($rowProgramacion["pro_dia_fin"]);
		//--
		$Finalizadas += intval($ClsEje->count_ejecucion_accion("", $codigo, $usuario, "", "", 2));
		$rs = $ClsEje->get_ejecucion_accion("", $codigo);
		if (!is_array($rs) && $ffin >= date("Y-m-d") && $fini <=  date("Y-m-d")) {
			if (date("Y-m-d") >= $fini && date("Y-m-d") <= $ffin) {
				if ($tipo == "U") $disponibles++;
				else {
					if ($tipo == "W") {
						$index = date("w");
						$index = ($index == 0) ? 7 : $index;
					} else if ($tipo == "M") {
						$index = date("d");
						$index = intval($index);
					}
					if ($index >= $diaInicio && $index <= $diaFinal) $disponibles++;
				}
			}
		} else if (is_array($rs) && $ffin >= date("Y-m-d") && $fini <=  date("Y-m-d") && $rs[0]["eje_situacion"] == 1) {
			if (date("Y-m-d") >= $fini && date("Y-m-d") <= $ffin) {
				if ($tipo == "U") $enProceso++;
				else {
					if ($tipo == "W") {
						$index = date("w");
						$index = ($index == 0) ? 7 : $index;
					} else if ($tipo == "M") {
						$index = date("d");
						$index = intval($index);
					}
					if ($index >= $diaInicio && $index <= $diaFinal) $enProceso++;
					else $Vencidas++;
				}
			}
		}
	}
	///////////// Disponibles ///////////
	$salida .= '<tr>';
	$salida .= '<td width = "85%" class = "text-left"> Disponibles </td>';
	$salida .= '<td width = "15%" class = "text-left">' . $disponibles . ' </td>';
	$salida .= '</tr>';
	///////////// En Proceso ///////////
	$salida .= '<tr>';
	$salida .= '<td width = "85%" class = "text-left"> En Proceso </td>';
	$salida .= '<td width = "15%" class = "text-left">' . $enProceso . ' </td>';
	$salida .= '</tr>';
	///////////// Vencidas ///////////
	$salida .= '<tr>';
	$salida .= '<td width = "85%" class = "text-left"> Vencidas </td>';
	$salida .= '<td width = "15%" class = "text-left">' . $Vencidas . ' </td>';
	$salida .= '</tr>';
	///////////// Finalizadas ///////////
	$salida .= '<tr>';
	$salida .= '<td width = "85%" class = "text-left"> Finalizadas </td>';
	$salida .= '<td width = "15%" class = "text-left">' . $Finalizadas . ' </td>';
	$salida .= '</tr>';
	///////////// Canceladas ///////////
	$salida .= '<tr>';
	$salida .= '<td width = "85%" class = "text-left"> Canceladas </td>';
	$salida .= '<td width = "15%" class = "text-left">' . $Canceladas . ' </td>';
	$salida .= '</tr>';
	//-
	$salida .= '</table>';


	$arr_respuesta = array(
		"status" => true,
		"data" => $salida,
		"message" => ""
	);

	echo json_encode($arr_respuesta);
}


////////////////////////////// Dashboard de Gerencia /////////////////////////////

function tabla_gerencia($proceso, $sistema, $usuario)
{
	$ClsObj = new ClsObjetivo();
	$result = $ClsObj->get_objetivo_asignado($proceso, $sistema, $usuario);
	if (is_array($result)) {
		$data = objetivos_gerencia($proceso, $sistema, $usuario);
		$payload = array(
			"status" => true,
			"data" => $data,
			"message" => "Success"
		);
		echo json_encode($payload);
	} else {
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"message" => "Esta accion no existe..."
		);
		echo json_encode($payload);
	}
}