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
include_once('html_fns_ejecucion.php');

// Obtenemos los datos del archivo// obtenemos los datos del archivo
$tamano = $_FILES["imagen"]['size'];
$archivo = $_FILES["imagen"]['name'];
$codigo = $_REQUEST["activo"];
$posicion = $_REQUEST["posicion"];
$ClsPro = new ClsProgramacionPPM();

// Upload
if ($archivo != "") {
	$stringFoto = str_shuffle($codigo . uniqid());
	if ($posicion == 1) {
		$sql = $ClsPro->foto_inicio_programacion($codigo, $stringFoto);
		$rs = $ClsPro->exec_sql($sql);
	} else if ($posicion == 2) {
		$sql = $ClsPro->foto_final_programacion($codigo, $stringFoto);
		$rs = $ClsPro->exec_sql($sql);
	} else {
		$msj = "Error en la carga de la imagen, parametro de posici\u00F3n invalido";
		$status = 0;
		$rs = 0;
	}
	if ($rs == 1) {
		// guardamos el archivo a la carpeta files
		$destino =  "../../CONFIG/Fotos/PPM/" . $stringFoto . ".jpg";
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
		}
	} else {
		$msj = "Error en la transacci\u00F3n al cargar a la Base de Datos";
		$status = 0;
	}
} else {
	$msj = "Archivo vacio. $ejecucion, $pregunta";
	$status = 0;
}

$result = $ClsPro->get_imagenes($codigo);
if (is_array($result)) {
	foreach ($result as $row) {
		if ($posicion == 1) {
			$foto = trim($row["pro_foto1"]);
		} else if ($posicion == 2) {
			$foto = trim($row["pro_foto2"]);
		} else {
			$foto = "ABC";
		}
		if (file_exists('../../CONFIG/Fotos/PPM/' . $foto . '.jpg') || $foto != "") {
			$strFoto = '<img src="../../CONFIG/Fotos/PPM/' . $foto . '.jpg" alt="..." />';
		} else {
			$strFoto = '<img src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
		}
	}
} else {
	$strFoto = '<img class="img-demo" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
}

$arr_data = array(
	"status" => $status,
	"img" => $strFoto,
	"message" => $msj
);
echo json_encode($arr_data);
