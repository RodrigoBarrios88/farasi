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

	$usuario = $_REQUEST["usuario"];
	//echo "Usuario: $usuario <br>";
	if(!empty($_FILES)){
		if($usuario != ""){
			if (file_exists($_FILES['photo']['tmp_name']) || is_uploaded_file($_FILES['photo']['tmp_name'])) {
                $stringFoto = str_shuffle($usuario.uniqid());
				$destino =  "../../CONFIG/Fotos/USUARIOS/$stringFoto.jpg";
				//echo "Destino: $destino <br>";
                if (move_uploaded_file($_FILES['photo']['tmp_name'],$destino)) {
					$ClsUsu = new ClsUsuario();
					$ultimaFoto = $ClsUsu->last_foto_usuario($usuario);
					//echo "Ultima Foto: ../../CONFIG/Fotos/USUARIOS/$ultimaFoto.jpg <br>";
					if(file_exists('../../CONFIG/Fotos/USUARIOS/'.$ultimaFoto.'.jpg')){
						unlink("../../CONFIG/Fotos/USUARIOS/$ultimaFoto.jpg");
						//echo "borro <br>";
					}
					$sql = $ClsUsu->cambia_foto($usuario,$stringFoto);
					//echo "Inserto: $sql <br>";
					$rs = $ClsUsu->exec_sql($sql);
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
				"message" => "El código de usuario viene vacio..."
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