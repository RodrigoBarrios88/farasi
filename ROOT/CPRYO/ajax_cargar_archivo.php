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
include_once('html_fns_ryo.php');

$archivo = $_FILES["archivo"]['name'];
$nombre = $_REQUEST["nombre"];
$codigo = $_REQUEST["codigo"];
$posicion = $_REQUEST["posicion"];

// Upload
if ($archivo != "") {
	$ClsRie = new ClsRiesgo();

	$stringFoto = str_shuffle($codigo . uniqid());
	$id = $ClsRie->max_archivo();
	$id++;
	$sql = $ClsRie->insert_archivo($id, $codigo, $posicion, $stringFoto);
	$rs = $ClsRie->exec_sql($sql);
	if ($rs == 1) {
		// vemos si el destino es la imagen o el documento
		$destino =  "../../CONFIG/Fotos/RYO/" . $stringFoto . ".jpg";
		if (move_uploaded_file($_FILES['archivo']['tmp_name'], $destino)) {
			$msj = "archivo $nombre subida exitosamente...!";
			$posicion = 1;
			//////////// -------- Convierte todas las archivoes a JPEG
			// Abrimos una archivo PNG
			//$mime_type = mime_content_type($destino);
			//Valida si es un PNG
			if ($mime_type == "image/png") {
				$archivo = imagecreatefrompng($destino); // si es, convierte a JPG
				imagejpeg($archivo, $destino, 100); // Creamos la archivo JPG a partir de la PNG u otra que venga
			}
			/// redimensionando
			//$image = new ImageResize($destino);
			//$image->resizeToWidth(300);
			//$image->save($destino);
			///
			if ($posicion == 1) {
				$arr_respuesta = array(
					"status" => true,
					"message" => $msj,
				);
			} else {
				$arr_respuesta = array(
					"status" => true,
					"message" => $msj,
				);
			}
		} else {
			$arr_respuesta = array(
				"status" => false,
				"message" => "Error al subir el archivo..."
			);
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"message" => "Error al registrar el archivo en la BD" . $sql
		);
	}
	//echo $sql;
} else {
	$arr_respuesta = array(
		"status" => false,
		"message" => "Archivo vacio"
	);
}
echo json_encode($arr_respuesta);
