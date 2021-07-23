<?php
ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cahce");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);
//--
include_once('html_fns_api.php');
///API REQUEST

	$revision = $_REQUEST["revision"];
	if(!empty($_FILES)){
		if($revision != ""){
			if (file_exists($_FILES['photo']['tmp_name']) || is_uploaded_file($_FILES['photo']['tmp_name'])) {
                $stringFoto = str_shuffle($revision.uniqid());
				$destino =  "../../CONFIG/Fotos/FIRMAS/$stringFoto.jpg";
                if (move_uploaded_file($_FILES['photo']['tmp_name'],$destino)) {
					//////////// -------- Convierte todas las imagenes a JPEG
					// Abrimos una Imagen PNG
					$mime_type = mime_content_type($destino);
					//Valida si es un PNG
					if($mime_type == "image/png"){
						$imagen = imagecreatefrompng($destino); // si es, convierte a JPG
						imagejpeg($imagen,$destino,100); // Creamos la Imagen JPG a partir de la PNG u otra que venga
					}
					//busca al firma vieja y elimina
					$ClsRev = new ClsRevision();
					$result = $ClsRev->get_revision($revision);
					if(is_array($result)){
						foreach ($result as $row){
							$firma_vieja = trim($row["rev_firma"]);
						}
						if(file_exists('../../CONFIG/Fotos/FIRMAS/'.$firma_vieja.'.jpg')){
							unlink("../../CONFIG/Fotos/FIRMAS/$firma_vieja.jpg");
						}
						
					}
					//carga la nueva
					$sql = $ClsRev->firma_revision($revision,$stringFoto);
					$rs = $ClsRev->exec_sql($sql);
					if($rs == 1){
						$arr_data = array(
							"status" => true,
							"message" => "firma cargada exitosamente..."
						);
						echo json_encode($arr_data);
					}else{
						unlink($destino); //elimina carga si hay error...
						$arr_data = array(
							"status" => false,
							"message" => "existió un problema con el registro en base de datos..."
						);
						echo json_encode($arr_data);
					}
				} else {
					$arr_data = array(
						"status" => false,
						"message" => "existió un problema al cargar la imagen...."
					);
					echo json_encode($arr_data);
				}
				
            }else{
				$arr_data = array(
					"status" => false,
					"message" => "El archivo puede ir vacio o no es apto para cargarse..."
				);
				echo json_encode($arr_data);
			}
		}else{
			//devuelve un mensaje de manejo de errores
			$arr_data = array(
				"status" => false,
				"message" => "El código de la revision viene vacio..."
			);
			echo json_encode($arr_data);
		}
	}else{
		//devuelve un mensaje de manejo de errores
		$arr_data = array(
			"status" => false,
			"message" => "No hay imagen a cargar..."
		);
		echo json_encode($arr_data);
	}
?>