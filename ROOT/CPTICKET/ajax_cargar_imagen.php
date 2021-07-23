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

$archivo = $_FILES["imagen"]['name'];
$nombre = $_REQUEST["nombre"];
$ticket = $_REQUEST["ticket"];
$status = $_REQUEST["posicion"];
$sms = $_REQUEST["sms"];


// Upload
if ($archivo != "") {
	$ClsTic = new ClsTicket();
	$ClsSta = new ClsStatus();
	// la posicion del status
	foreach ($ClsSta->get_status_hd($status) as $row)
		$posicion = trim($row["sta_posicion"]);

	$stringFoto = str_shuffle($ticket . uniqid());
	$codigo = $ClsTic->max_foto();
	$codigo++;
	$sql = $ClsTic->insert_foto($codigo, $ticket, $posicion, $stringFoto);
	$rs = $ClsTic->exec_sql($sql);
	if ($rs == 1) {
		// guardamos el archivo a la carpeta files
		$destino =  "../../CONFIG/Fotos/TICKET/" . $stringFoto . ".jpg";
		if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
			$msj = "Imagen $nombre subida exitosamente...!";
			$posicion = 1;
			//////////// -------- Convierte todas las imagenes a JPEG
			// Abrimos una Imagen PNG
			//$mime_type = mime_content_type($destino);
			//Valida si es un PNG
			if ($mime_type == "image/png") {
				$imagen = imagecreatefrompng($destino); // si es, convierte a JPG
				imagejpeg($imagen, $destino, 100); // Creamos la Imagen JPG a partir de la PNG u otra que venga
			}
			/// redimensionando
			//$image = new ImageResize($destino);
			//$image->resizeToWidth(300);
			//$image->save($destino);
			///
			if ($posicion == 1) {
				if ($sms == 1) {
					$arr_respuesta = array(
						"status" => true,
						"message" => $msj,
						"pagina" => "../SMS/EXEasigna.php?ticket=$ticket"
					);
				} else {
					$arr_respuesta = array(
						"status" => true,
						"message" => $msj,
						"pagina" => "FRMsolicitados.php"
					);
				}
			} else {
				$arr_respuesta = array(
					"status" => true,
					"message" => $msj,
					"pagina" => "FRMtramite.php?codigo=$ticket"
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
