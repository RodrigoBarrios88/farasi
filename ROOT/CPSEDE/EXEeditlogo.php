<?php
include_once('html_fns_sede.php');

///////////// A CARPETAS //////////////////////
// obtenemos los datos del archivo
    $tamano = $_FILES["imagen"]['size'];
    $archivo = $_FILES["imagen"]['name'];
	$sede = $_REQUEST["codigo"];// Upload
	if($archivo != "") {
		$ClsSed = new ClsSede();
		$ultimaFoto = $ClsSed->last_foto_sede($sede);
		$stringFoto = str_shuffle($sede.uniqid());
		$sql = $ClsSed->cambia_foto($sede,$stringFoto);
		$rs = $ClsSed->exec_sql($sql);
			
		// guardamos el archivo a la carpeta files
		$destino =  "../../CONFIG/Fotos/SEDES/".$stringFoto.".jpg";
		if (move_uploaded_file($_FILES['imagen']['tmp_name'],$destino)) {
			$msj = "Imagen <b>$archivo</b> subido como $nombre<br> Carga Exitosa...!" ; $status = 1;
			//////////// -------- Convierte todas las imagenes a JPEG
			// Abrimos una Imagen PNG
			//$mime_type = mime_content_type($destino);
			//Valida si es un PNG
			if($mime_type == "image/png"){
				$imagen = imagecreatefrompng($destino); // si es, convierte a JPG
				imagejpeg($imagen,$destino,100); // Creamos la Imagen JPG a partir de la PNG u otra que venga
			}
			///eliminamos la anterior
			if($ultimaFoto != ""){
				unlink("../../CONFIG/Fotos/SEDES/".$ultimaFoto.".jpg");
			}
			///respuesta
			$arr_respuesta = array(
				"status" => true,
				"data" => [],
				"message" => "Logo actualizado satisfactoriamente!!!"
			);
			echo json_encode($arr_respuesta);
			return;
		} else {
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Error en la transacción, hubo un problema al subir el archivo..."
			);
			echo json_encode($arr_respuesta);
			return;
		}
	}else{
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Error en la transacción, archivo vacio..."
		);
		echo json_encode($arr_respuesta);
		return;
	}
	
?>