<?php
include_once('html_fns_perfil.php');

///////////// A CARPETAS //////////////////////
// obtenemos los datos del archivo
    $tamano = $_FILES["imagen"]['size'];
    $archivo = $_FILES["imagen"]['name'];
	$usuario = $_SESSION["codigo"];// Upload
	if($archivo != "") {
		$ClsUsu = new ClsUsuario();
		$ultimaFoto = $ClsUsu->last_foto_usuario($usuario);
		$stringFoto = str_shuffle($usuario.uniqid());
		$sql = $ClsUsu->cambia_foto($usuario,$stringFoto);
		$rs = $ClsUsu->exec_sql($sql);
			
		// guardamos el archivo a la carpeta files
		$destino =  "../../CONFIG/Fotos/USUARIOS/".$stringFoto.".jpg";
		if (move_uploaded_file($_FILES['imagen']['tmp_name'],$destino)) {
			//////////// -------- Convierte todas las imagenes a JPEG
			// Abrimos una Imagen PNG
			//$mime_type = mime_content_type($destino);
			//Valida si es un PNG
			if($mime_type == "image/png"){
				$imagen = imagecreatefrompng($destino); // si es, convierte a JPG
				imagejpeg($imagen,$destino,100); // Creamos la Imagen JPG a partir de la PNG u otra que venga
			}
			$_SESSION["foto"] = $destino;
            ///eliminamos la anterior
			if(file_exists("../../CONFIG/Fotos/USUARIOS/".$ultimaFoto.".jpg")){
				unlink("../../CONFIG/Fotos/USUARIOS/".$ultimaFoto.".jpg");
			}
            $foto = '<img class="img-thumbnail" src="../../CONFIG/Fotos/'.$stringFoto.'" alt="..." >';
            ///respuesta
			$arr_respuesta = array(
				"status" => true,
				"imagen" => $foto,
				"message" => "Fotografía actualizada satisfactoriamente!!!"
			);
			echo json_encode($arr_respuesta);
			return;
		} else {
			$msj = "Error al subir el archivo"; $status = 0;
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