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
	$ticket = $_REQUEST["ticket"];
	$status = $_REQUEST["status"];
	$comentario = $_REQUEST["comentario"];
	if(!empty($_FILES)){
		if($ticket != "" && $status != ""){
			if (file_exists($_FILES['photo']['tmp_name']) || is_uploaded_file($_FILES['photo']['tmp_name'])) {
                $stringFoto = str_shuffle($ticket.uniqid());
				$destino =  "../../CONFIG/Fotos/TICKET/$stringFoto.jpg";
                if(move_uploaded_file($_FILES['photo']['tmp_name'],$destino)){
					//////////// -------- Convierte todas las imagenes a JPEG
					// Abrimos una Imagen PNG
					$mime_type = mime_content_type($destino);
					//Valida si es un PNG
					if($mime_type == "image/png"){
						$imagen = imagecreatefrompng($destino); // si es, convierte a JPG
						imagejpeg($imagen,$destino,100); // Creamos la Imagen JPG a partir de la PNG u otra que venga
					}
					/// redimensionando 
					$image = new ImageResize($destino);
					$image->resizeToWidth(300);
					$image->save($destino);
					///--------- TRANSACCIONES ----------///
					$ClsSta = new ClsStatus();
					$result = $ClsSta->get_status_hd($status,'','',1);
					if(is_array($result)){
						foreach($result as $row){
							$posicion = trim($row["sta_posicion"]);
							$status_nombre = trim($row["sta_nombre"]);
						}	
					}
					/////// REGISTRO DE IMAGEN 
					$ClsTic = new ClsTicket();
					$stringFoto = str_shuffle($ticket.uniqid());
					$codigo = $ClsTic->max_foto();
					$codigo++;
					$sql = $ClsTic->insert_foto($codigo,$ticket,$posicion,$stringFoto);
					/////// CAMBIO DE STATUS
					$sql.= $ClsTic->cambia_sit_ticket($ticket,$status);
					$sql.= $ClsTic->insert_ticket_status($ticket,$status,$status_nombre); /// Inserta Ticket
					$bitcod = $ClsTic->max_bitacora($ticket);
					$bitcod++;
					$sql.= $ClsTic->insert_bitacora($bitcod,$ticket,"Cambio de Status ($status_nombre)",$comentario); /// Inserta Ticket
					if($status == 100){ ///// CERRAR TICKET
						$sql.= $ClsTic->cerrar_ticket($ticket);
					}
					//---- 
					$rs = $ClsTic->exec_sql($sql);
					if($rs == 1){
						$arr_data = array(
							"status" => true,
							"message" => "imagen cargada exitosamente..."
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
				}else{
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
				"message" => "El código del ticket o status viene vacio..."
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