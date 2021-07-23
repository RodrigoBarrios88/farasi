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
include_once('html_fns_activo.php');

// Obtenemos los datos del archivo
$tamano = $_FILES["imagen"]['size'];
$archivo = $_FILES["imagen"]['name'];
$activo = $_REQUEST["activo"];
$posicion = $_REQUEST["posicion"];
$ClsAct = new ClsActivo();

// Upload
if ($archivo != "") {
	$codigo = $ClsAct->max_foto();
	$codigo++;
	$stringFoto = str_shuffle($codigo . $activo . $posicion . uniqid());
	$sql = $ClsAct->insert_foto($codigo, $activo, $posicion, $stringFoto);
	$rs = $ClsAct->exec_sql($sql);
	if ($rs == 1) {
		// guardamos el archivo a la carpeta files
		$destino =  "../../CONFIG/Fotos/ACTIVOS/" . $stringFoto . ".jpg";
		if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
			$msj = "Imagen $archivo subida exitosamente...!";
			$status = 1;
			//////////// -------- Convierte todas las imagenes a JPEG
			// Abrimos una Imagen PNG
			//$mime_type = mime_content_type($destino);
			//Valida si es un PNG
			if ($mime_type == "image/png") {
				$imagen = imagecreatefrompng($destino); // si es, convierte a JPG
				imagejpeg($imagen, $destino, 100); // Creamos la Imagen JPG a partir de la PNG u otra que venga
			}
			/// redimensionando
			$image = new ImageResize($destino);
			$image->resizeToWidth(300);
			$image->save($destino);
		} else {
			$msj = "Error al subir el archivo";
			$status = 0;
		}
	} else {
		$msj = "Error en la transacci\u00F3n al cargar a la Base de Datos";
		$status = 0;
	}
} else {
	$msj = "Archivo vacio. $ejecucion, $pregunta";
	$status = 0;
}

$result = $ClsAct->get_fotos($codigo, $activo, $posicion);
$arrFoto = array();
$foto = "";
$i = 0;
if (is_array($result)) {
	foreach ($result as $row) {
		$fotCodigo = trim($row["fot_codigo"]);
		$posicion = trim($row["fot_posicion"]);
		$foto = trim($row["fot_foto"]);
		if (file_exists('../../CONFIG/Fotos/ACTIVOS/' . $foto . '.jpg') || $foto != "") {
			$arrFoto[$i]["foto"] = '<img onclick="deleteFotoConfirm(' . $fotCodigo . ',' . $posicion . ');" class="img-upload" src="../../CONFIG/Fotos/ACTIVOS/' . $foto . '.jpg" alt="..." />';
		} else {
			$arrFoto[$i]["foto"] = '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
		}
		$i++;
	}
} else {
	$arrFoto[$i]["foto"] = '<img class="img-demo" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
}

$arr_data = array(
	"status" => $status,
	"img" => $arrFoto,
	"message" => $msj
);
echo json_encode($arr_data);
