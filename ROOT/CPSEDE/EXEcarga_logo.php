<?php
	include_once('html_fns_sede.php');
	validate_login("../");
$id = $_SESSION["codigo"];
	$nombre = utf8_decode($_SESSION["nombre"]);
	$rol = $_SESSION["rol"];
	$rol_nombre = utf8_decode($_SESSION["rol_nombre"]);
	$foto = $_SESSION["foto"];

	// obtenemos los datos del archivo
    // Fecha del Sistema
	$tamano = $_FILES["logo"]['size'];
	$tipo = $_FILES["logo"]['type'];
	$archivo = $_FILES["logo"]['name'];
	$sede = $_REQUEST["codigo"];
	// Upload
	if ($archivo != "") {
		$ClsSed = new ClsSede();
		$ultimaFoto = $ClsSed->last_foto_sede($sede);
		$stringFoto = str_shuffle($sede.uniqid());
		$sql = $ClsSed->cambia_foto($sede,$stringFoto);
		$rs = $ClsSed->exec_sql($sql);
		
		// guardamos el archivo a la carpeta files
		$destino =  "../../CONFIG/Fotos/SEDES/".$stringFoto.".jpg";
		if (move_uploaded_file($_FILES['logo']['tmp_name'],$destino)) {
			$msj = "Imagen $archivo subida exitosamente...!" ; $status = 1;
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
		}else {
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Error en la transacción, hubo un problema al subir el archivo..."
			);
			echo json_encode($arr_respuesta);
			return;
		}
	} else {
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Error en la transacción, archivo vacio..."
		);
		echo json_encode($arr_respuesta);
		return;
	}?>