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
include_once('html_fns_requisitos.php');

$request = $_REQUEST["request"];
switch ($request) {
    case "grabar_tipo_evaluacion":
        $requisito = $_REQUEST["requisito"];
        $nombre = $_REQUEST["nombre"];
        $cumple = $_REQUEST["cumple"];
        $aspecto = $_REQUEST['aspecto'];
        $componente = $_REQUEST["componente"];
        $frecuencia = $_REQUEST["frecuencia"];
		$fecha = $_REQUEST["fecha"];
		$evaRequisito = $_REQUEST['evaRequisito'];
        grabar_tipo_evaluacion($requisito, $nombre, $cumple, $aspecto, $componente, $frecuencia,$fecha,$evaRequisito);
        break;
    case "tabla_tipo_evaluacion":
		$requisito = $_REQUEST["requisito"];
		tabla($requisito);
        break;
    case "get":
        $codigo = $_REQUEST["codigo"];
        get($codigo);
        break;
    case "modificar_tipo_evaluacion":
        $codigo = $_REQUEST['codigo'];
        $requisito = $_REQUEST["requisito"];
        $nombre = $_REQUEST["nombre"];
        $cumple = $_REQUEST["cumple"];
        $aspecto = $_REQUEST['aspecto'];
        $componente = $_REQUEST["componente"];
        $frecuencia = $_REQUEST["frecuencia"];
		$fecha = $_REQUEST["fecha"];
		$evaRequisito = $_REQUEST['evaRequisito'];
        modificar_tipo_evaluacion($codigo, $requisito, $nombre, $cumple, $aspecto, $componente, $frecuencia, $fecha, $evaRequisito);
        break;
    case "situacion":
        $codigo = $_REQUEST["codigo"];
		$requisito = $_REQUEST["requisito"];
        $situacion = $_REQUEST["situacion"];
        situacion_tipo_evaluacion($codigo, $requisito, $situacion);
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



function grabar_tipo_evaluacion($requisito, $nombre, $cumple, $aspecto, $componente, $frecuencia, $fecha,$evaRequisito)
{
    $ClsTipEval = new ClsTipoEvaluacion();
	if ($requisito != '' && $nombre != ''  && $cumple != ''  && $aspecto != ''  && $componente != '' && $frecuencia != '' && $fecha !=''&& $evaRequisito != '') {
        $codigo = $ClsTipEval->max_tipo_evaluacion();
        $codigo++;
        $sql = $ClsTipEval->insert_tipo_evaluacion($codigo, $requisito, $nombre, $cumple, $aspecto, $componente, $frecuencia, $fecha, $evaRequisito);
        $rs = $ClsTipEval->exec_sql($sql);

        if ($rs == 1) {
            $arr_respuesta = array(
                "status" => true,
                "data" => tabla_tipo_evaluacion("",$requisito),
                "message" => "Registro guardados satisfactoriamente...!"
            );
            echo json_encode($arr_respuesta);
        } else {
            $arr_respuesta = array(
                "status" => false,
                "data" => [],
                "message" => "Error en la transacción..."
            );
            echo json_encode($arr_respuesta);
        }
    } else {
        $arr_respuesta = array(
            "status" => false,
            "data" => [],
            "message" => "Debe llenar los campos obligatorios..."
        );

        echo json_encode($arr_respuesta);
    }
}

function tabla($requisito)
{
	$ClsTipEval = new ClsTipoEvaluacion();
	$result = $ClsTipEval->get_tipo_evaluacion("",$requisito);
	//var_dump($result);
		//die();
	if (is_array($result)) {
		$arr_respuesta = array(
			"status" => true,
			"tabla" => tabla_tipo_evaluacion("", $requisito),
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

function modificar_tipo_evaluacion($codigo,  $requisito,$nombre,$cumple,$aspecto,$componente,$frecuencia, $fechaReevaluacion, $evaRequisito)
{
	$ClsTipEval = new ClsTipoEvaluacion();
	if ($codigo != '' && $requisito != '' && $nombre != ''  && $cumple != ''  && $aspecto != ''  && $componente != '' && $frecuencia != '' && $fechaReevaluacion !=''&& $evaRequisito != '') {
		$sql = $ClsTipEval->modifica_tipo_evaluacion($codigo,$requisito, $nombre, $cumple,$aspecto,$componente,$frecuencia,$fechaReevaluacion, $evaRequisito);
		$rs = $ClsTipEval->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"data" => tabla_tipo_evaluacion("",$requisito),
				"message" => "Registro guardado satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Error en la transacción..."
			);
			echo json_encode($arr_respuesta);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);

		echo json_encode($arr_respuesta);
	}
}
function get($codigo)
{
	$ClsTipEval = new ClsTipoEvaluacion();
	$result = $ClsTipEval->get_tipo_evaluacion($codigo,"");
	$arr_data = array();
	if (is_array($result)) {
		foreach ($result as $row) {
			$arr_data["codigo"] = trim($row["eva_codigo"]);
			$arr_data["requisito"] = trim($row["eva_requisito"]);
			$arr_data["nombre"] = trim($row["eva_nombre"]);
			$arr_data["cumple"] = trim($row["eva_cumple"]);
			$arr_data["aspecto"] = $row["eva_aspecto"];
			$arr_data["componente"] = $row["eva_componente"];
            $arr_data["frecuencia"] = $row["eva_frecuencia"];
			$arr_data["fecha"] = cambia_fecha($row["eva_fecha_reevaluacion"]);
			$arr_data["eva_requisito"] = $row["eva_requisto"];
			$arr_data["eva_situacion"] = $row["eva_situacion"];

		}
		$arr_respuesta = array(
			"status" => true,
			"data" => $arr_data,
			"tabla" => tabla_tipo_evaluacion($codigo, ""),
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
function situacion_tipo_evaluacion($codigo,$requisito, $situacion)
{
	$ClsTipEval = new ClsTipoEvaluacion();
	if ($codigo != "" && $situacion != "" && $requisito != "") {
		$sql = $ClsTipEval->cambia_situacion_tipo_evaluacion($codigo, $situacion);
		$rs = $ClsTipEval->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"message" => "Situación actualizada exitosamente...",
				"data" => tabla_tipo_evaluacion("",$requisito)
			);
		} else {
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => $sql
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar datos obligatorios"
		);
	}
	echo json_encode($arr_respuesta);
}
