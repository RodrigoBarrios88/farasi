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

	$programacion = $_REQUEST["programacion"];
	if(!empty($_FILES)){
		if($programacion != ""){
			if (file_exists($_FILES['photo']['tmp_name']) || is_uploaded_file($_FILES['photo']['tmp_name'])) {
                $stringFirma = str_shuffle($programacion.uniqid());
				$destino =  "../../CONFIG/Fotos/PPMFIRMAS/$stringFirma.jpg";
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
					$ClsPro = new ClsProgramacionPPM();
					$result = $ClsPro->get_imagenes($programacion);
					if(is_array($result)){
						foreach ($result as $row){
							$firma_vieja = trim($row["pro_firma"]);
						}
						if(file_exists('../../CONFIG/Fotos/PPMFIRMAS/'.$firma_vieja.'.jpg')){
							unlink("../../CONFIG/Fotos/PPMFIRMAS/$firma_vieja.jpg");
						}
					}
					//carga la nueva
					$sql = $ClsPro->firma_programacion($programacion,$stringFirma);
					$rs = $ClsPro->exec_sql($sql);
					if($rs == 1){
						$arrFotos = array();
						$result = $ClsPro->get_programacion($programacion);
						if(is_array($result)){
							foreach ($result as $row){
								$strFoto1 = trim($row["pro_foto1"]);
								$strFoto2 = trim($row["pro_foto2"]);
								$strFirma = trim($row["pro_firma"]);
							}
							if(file_exists('../../CONFIG/Fotos/PPM/'.$strFoto1.'.jpg') && $strFoto1 != ""){
								$arrFotos[0]['foto_antes'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/PPM/$strFoto1.jpg";
							}else{
								$arrFotos[0]['foto_antes'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imagePhoto.jpg";
							}
							if(file_exists('../../CONFIG/Fotos/PPM/'.$strFoto2.'.jpg') && $strFoto2 != ""){
								$arrFotos[0]['foto_despues'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/PPM/$strFoto2.jpg";
							}else{
								$arrFotos[0]['foto_despues'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imagePhoto.jpg";
							}
							if(file_exists('../../CONFIG/Fotos/PPMFIRMAS/'.$strFirma.'.jpg') && $strFirma != ""){
								$arrFotos[0]['firma'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/PPMFIRMAS/$strFirma.jpg";
							}else{
								$arrFotos[0]['firma'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imageSign.jpg";
							}
						}else{
							$arrFotos[0]['foto_antes'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imagePhoto.jpg";
							$arrFotos[0]['foto_despues'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imagePhoto.jpg";
							$arrFotos[0]['firma'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imageSign.jpg";
						}
						
						$arr_data = array(
							"status" => true,
							"imagens" => $arrFotos,
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
				"message" => "El código de la programacion viene vacio..."
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