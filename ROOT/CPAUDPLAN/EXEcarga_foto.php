<?php
	include_once('html_fns_ejecucion.php');
	validate_login("../");
$id = $_SESSION["codigo"];
	$nombre = utf8_decode($_SESSION["nombre"]);
	$rol = $_SESSION["rol"];
	$rol_nombre = utf8_decode($_SESSION["rol_nombre"]);
	$foto = $_SESSION["foto"];
	
///////////// A CARPETAS //////////////////////
// obtenemos los datos del archivo
    $tamano = $_FILES["imagen"]['size'];
    $archivo = $_FILES["imagen"]['name'];
	$auditoria = $_REQUEST["auditoria"];
	$ejecucion = $_REQUEST["ejecucion"];
	$pregunta = $_REQUEST["pregunta"];
	$ClsEje = new ClsEjecucion();// Upload
	if($archivo != "") {
		$fotCodigo = $ClsEje->max_foto($auditoria,$pregunta,$ejecucion);
		$fotCodigo++;
		$stringFoto = str_shuffle($auditoria.$pregunta.$ejecucion.uniqid());
		$sql = $ClsEje->insert_foto($fotCodigo,$auditoria,$pregunta,$ejecucion,$stringFoto);
		$rs = $ClsEje->exec_sql($sql);
		if($rs == 1){
			// guardamos el archivo a la carpeta files
			$destino =  "../../CONFIG/Fotos/AUDITORIA/".$stringFoto.".jpg";
			if (move_uploaded_file($_FILES['imagen']['tmp_name'],$destino)) {
				$msj = "Imagen $archivo subida exitosamente...!" ; $status = 1;
				//////////// -------- Convierte todas las imagenes a JPEG
				// Abrimos una Imagen PNG
				//$mime_type = mime_content_type($destino);
				//Valida si es un PNG
				if($mime_type == "image/png"){
					$imagen = imagecreatefrompng($destino); // si es, convierte a JPG
					imagejpeg($imagen,$destino,100); // Creamos la Imagen JPG a partir de la PNG u otra que venga
				}
				/// redimensionando
				$image = new ImageResize($destino);
				$image->resizeToWidth(300);
				$image->save($destino);
			} else {
				$msj = "Error al subir el archivo"; $status = 0;
			}
		}else{
			$msj = "Error en la transacci\u00F3n al cargar a la Base de Datos"; $status = 0;
		}	
	}else{
		$msj = "Archivo vacio. $ejecucion, $pregunta";  $status = 0;
	}$result = $ClsEje->get_fotos('',$ejecucion,$auditoria,$pregunta);
	$arrFoto = array();
	$foto = "";
	$i = 0;
	if(is_array($result)){
		foreach ($result as $row){
			$fotCodigo = trim($row["fot_codigo"]);
			$foto = trim($row["fot_foto"]);
			if(file_exists('../../CONFIG/Fotos/AUDITORIA/'.$foto.'.jpg') || $foto != ""){
				$arrFoto[$i]["foto"] = '<img onclick="menuFoto('.$fotCodigo.','.$auditoria.','.$pregunta.','.$ejecucion.');" class="img-upload" src="../../CONFIG/Fotos/AUDITORIA/'.$foto.'.jpg" alt="..." />';
			}else{
				$arrFoto[$i]["foto"] = '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
			}
			$i++;
		}	
	}else{
		$arrFoto[$i]["foto"] = '<img class="img-demo" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
	}$arr_data = array(
		"status" => $status,
		"img" => $arrFoto,
		"message" => $msj
	);
	echo json_encode($arr_data);
?>