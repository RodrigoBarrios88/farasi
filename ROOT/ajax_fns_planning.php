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
	default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

function cumplimiento_general()
{
	$ClsObj = new ClsObjetivo();
	$ClsAcc = new ClsAccion();
	$ClsEva = new ClsEvaluacion();

	$cumplimiento = 0;
	// Acciones Evaluadas para cada objetivo
	$objetivos = $ClsObj->get_objetivo();
	foreach ($objetivos as $row) {
		$objetivo = trim($row["obj_codigo"]);
		$numero_programaciones = $ClsAcc->count_programacion("", "", $objetivo);
		$numero_evaluaciones = $ClsEva->count_evaluacion("", "", "", "", "", "", $objetivo);
		$promedio_objetivo = 0;
		if ($numero_programaciones != 0) $promedio_objetivo = $numero_evaluaciones / $numero_programaciones;
		$cumplimiento = $cumplimiento + $promedio_objetivo;
	}
	$numero_objetivos = $ClsObj->count_objetivo();
	if ($numero_objetivos != 0) $cumplimiento = $cumplimiento / $numero_objetivos;

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

	// Obtener todos los procesos estrategicos
	$result = $ClsFic->get_ficha("",3);
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
			foreach ($objetivos as $row2) {
				$objetivo = trim($row2["obj_codigo"]);
				$numero_programaciones = $ClsAcc->count_programacion("", "", $objetivo);
				$numero_evaluaciones = $ClsEva->count_evaluacion("", "", "", "", "", "", $objetivo);
				$promedio_objetivo = 0;
				if ($numero_programaciones != 0) $promedio_objetivo = $numero_evaluaciones / $numero_programaciones;
				$cumplimiento = $cumplimiento + $promedio_objetivo;
				// echo "Numero de programaciones: " . $numero_programaciones;
				// echo "\n";
				// echo "Numero de evaluaciones: " . $numero_evaluaciones;
				// echo "\n";
				// echo "Promedio del objetivo: " . $promedio_objetivo;
				// echo "\n";
				// echo "Cumplimiento: " . $cumplimiento;
				// echo "\n----------------------\n";
			}
			$numero_objetivos = $ClsObj->count_objetivo("", $proceso);
			// echo "\n--------- SUMA -------------\n";ed
			if ($numero_objetivos != 0) $cumplimiento = $cumplimiento / $numero_objetivos;
			//-----------------
			//--
			$i++;
			$salida .= '<td width = "90%" class = "text-left">' . $nombre . '</td>';
			$salida .= '<td width = "10%" class = "text-left">' . round($cumplimiento * 100, 2) . ' %</td>';
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
			// Acciones Evaluadas para cada objetivo
			$objetivos = $ClsObj->get_objetivo("", "", $sistema);
			foreach ($objetivos as $row2) {
				$objetivo = trim($row2["obj_codigo"]);
				$numero_programaciones = $ClsAcc->count_programacion("", "", $objetivo);
				$numero_evaluaciones = $ClsEva->count_evaluacion("", "", "", "", "", "", $objetivo);
				$promedio_objetivo = 0;
				if ($numero_programaciones != 0) $promedio_objetivo = $numero_evaluaciones / $numero_programaciones;
				$cumplimiento = $cumplimiento + $promedio_objetivo;
				// echo "Numero de programaciones: " . $numero_programaciones;
				// echo "\n";
				// echo "Numero de evaluaciones: " . $numero_evaluaciones;
				// echo "\n";
				// echo "Promedio del objetivo: " . $promedio_objetivo;
				// echo "\n";
				// echo "Cumplimiento: " . $cumplimiento;
				// echo "\n----------------------\n";
			}
			$numero_objetivos = $ClsObj->count_objetivo("", "", $sistema);
			// echo "\n--------- SUMA -------------\n";ed
			if ($numero_objetivos != 0) $cumplimiento = $cumplimiento / $numero_objetivos;
			//-----------------
			//--
			$i++;
			$salida .= '<td width = "90%" class = "text-left">' . $nombre . '</td>';
			$salida .= '<td width = "10%" class = "text-left">' . round($cumplimiento * 100, 2) . ' %</td>';
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
			$procesos = $ClsFic->get_ficha("", $tipo);
			foreach ($procesos as $rowProceso) {
				$proceso = trim($rowProceso["fic_codigo"]);
				// Acciones Evaluadas para cada objetivo
				$objetivos = $ClsObj->get_objetivo("", $proceso);
				if(is_array($objetivos)){
					foreach ($objetivos as $row2) {
						$objetivo = trim($row2["obj_codigo"]);
						$numero_programaciones = $ClsAcc->count_programacion("", "", $objetivo);
						$numero_evaluaciones = $ClsEva->count_evaluacion("", "", "", "", "", "", $objetivo);
						$promedio_objetivo = 0;
						if ($numero_programaciones != 0) $promedio_objetivo = $numero_evaluaciones / $numero_programaciones;
						$cumplimiento = $cumplimiento + $promedio_objetivo;
						// echo "Numero de programaciones: " . $numero_programaciones;
						// echo "\n";
						// echo "Numero de evaluaciones: " . $numero_evaluaciones;
						// echo "\n";
						// echo "Promedio del objetivo: " . $promedio_objetivo;
						// echo "\n";
						// echo "Cumplimiento: " . $cumplimiento;
						// echo "\n----------------------\n";
					}
				}
				$numero_objetivos = $ClsObj->count_objetivo("", $proceso);
				// echo "\n--------- SUMA -------------\n";ed
				if ($numero_objetivos != 0) $cumplimiento = $cumplimiento / $numero_objetivos;
			}
			$numero_procesos = $ClsFic->count_ficha("", $tipo);
			if ($numero_procesos != 0) $cumplimiento = $cumplimiento / $numero_procesos;	//--
			$i++;
			$salida .= '<td width = "90%" class = "text-left">' . $nombre . '</td>';
			$salida .= '<td width = "10%" class = "text-left">' . round($cumplimiento * 100, 2) . ' %</td>';
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
