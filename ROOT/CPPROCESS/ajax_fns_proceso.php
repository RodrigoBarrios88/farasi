<?php
ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cache");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);
//--
include_once('html_fns_proceso.php');

header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header("Access-Control-Allow-Origin: *");

///API REQUEST
$request = $_REQUEST["request"];
$_REQUEST = str_replace("undefined", "", $_REQUEST); ///valida campos "undefined" desde javascript

if ($request != "") {
	switch ($request) {
		case "nueva_ficha":
			$nombre = $_REQUEST["nombre"];
			$desde = $_REQUEST["desde"];
			$hasta = $_REQUEST["hasta"];
			$tipo = $_REQUEST["tipo"];
			$pertenece = $_REQUEST["pertenece"];
			nueva_ficha($nombre, $tipo, $pertenece, $desde, $hasta);
			break;
		case "update_ficha":
			$codigo = $_REQUEST["codigo"];
			$campo = $_REQUEST["campo"];
			$valor = $_REQUEST["valor"];
			update_ficha($codigo, $campo, $valor);
			break;
		case "situacion_ficha":
			$codigo = $_REQUEST["codigo"];
			$situacion = $_REQUEST["situacion"];
			situacion_ficha($codigo, $situacion);
			break;
		case "asignar_proceso":
			$ficha = $_REQUEST["ficha"];
			$usuarios = $_REQUEST["usuarios"];
			asignar_proceso($ficha, $usuarios);
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


////////////////////////////////////////////////// Representante de Escalones ////////////////////////////////////////////////////
function nueva_ficha($nombre, $tipo, $pertenece, $desde, $hasta)
{
	$ClsFic = new ClsFicha();
	if ($nombre != "" && $tipo != "") {
		$codigo = $ClsFic->max_ficha();
		$codigo++;
		$tipo = trim($tipo);
		$sql = $ClsFic->insert_ficha($codigo, $nombre, $tipo, '', '', '', '', $pertenece, $desde, $hasta);
		$rs = $ClsFic->exec_sql($sql);
		if ($rs == 1) {
			$usuario = $_SESSION["codigo"];
			$hashkey = $ClsFic->encrypt($codigo, $usuario);
			$payload = array(
				"status" => true,
				"hashkey" => $hashkey,
				"message" => "Ficha aperturada satisfactoriamente..."
			);
			echo json_encode($payload);
		} else {
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => false,
				"data" => [],
				"Sql" => $sql,
				"message" => "Error en la transaccion"
			);
			echo json_encode($payload);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);echo json_encode($arr_respuesta);
	}
}


function update_ficha($codigo, $campo, $valor)
{
	$ClsFic = new ClsFicha();
	if ($codigo != "" && $campo != "" && $valor != "") {
		switch ($campo) {
			case 1:
				$db_campo = "fic_nombre";
				break;
			case 2:
				$db_campo = "fic_tipo";
				break;
			case 3:
				$db_campo = "fic_analisis_foda";
				break;
			case 4:
				$db_campo = "fic_objetivo_general";
				break;
			case 5:
				$db_campo = "fic_medidas_verificacion";
				break;
			case 6:
				$db_campo = "";
				break;
			case 7:
				$db_campo = "fic_fecha_inicio";
				$valor = regresa_fecha($valor);
				break;
			case 8:
				$db_campo = "fic_fecha_fin";
				$valor = regresa_fecha($valor);
				break;
			default:
				$db_campo = "";
				break;
		}
		if ($db_campo != "") {
			$sql = $ClsFic->update_ficha($codigo, $db_campo, $valor);
		}
		$rs = $ClsFic->exec_sql($sql);
		if ($rs == 1) {
			$payload = array(
				"status" => true,
				"message" => "Ficha actualizada satisfactoriamente..."
			);
			echo json_encode($payload);
		} else {
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => false,
				//"sql" => $sql,
				"data" => [],
				"message" => "Error en la transacci\u00F3n"
			);
			echo json_encode($payload);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Debe llenar los campos obligatorios..."
		);echo json_encode($arr_respuesta);
	}
}


function situacion_ficha($codigo, $situacion)
{
	$ClsFic = new ClsFicha();
	$sql = $ClsFic->cambia_situacion_ficha($codigo, $situacion);
	$rs = $ClsFic->exec_sql($sql);
	if ($rs == 1) {
		$payload = array(
			"status" => true,
			"message" => "Situacion actualizada satisfactoriamente..."
		);
		echo json_encode($payload);
	} else {
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			//"sql" => $sql,
			"message" => "Error en la transacci\u00F3n"
		);
		echo json_encode($payload);
	}
}

function asignar_proceso($ficha, $usuarios)
{
	$ClsFic = new ClsFicha();
	if ($ficha != "") {
		$codigo = $ClsFic->max_ficha_usuario($ficha);
		$codigo++;
		$sql = $ClsFic->delete_ficha_usuario($ficha);
		if ($usuarios != "") {
			$arrUsuarios = explode(",", $usuarios);
			$count = count($arrUsuarios); //cuenta cuantas vienen en el array
		} else {
			$count = 0;
		}
		for ($i = 0; $i < $count; $i++) {
			$usuario = $arrUsuarios[$i];
			$sql .= $ClsFic->insert_ficha_usuario($codigo, $ficha, $usuario);
			$codigo++;
		}
		$rs = $ClsFic->exec_sql($sql);
		if ($rs == 1) {
			$arr_respuesta = array(
				"status" => true,
				"sql" => $sql,
				"data" => [],
				"message" => "Registro guardado satisfactoriamente...!"
			);
			echo json_encode($arr_respuesta);
		} else {
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
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
		);echo json_encode($arr_respuesta);
	}
}
