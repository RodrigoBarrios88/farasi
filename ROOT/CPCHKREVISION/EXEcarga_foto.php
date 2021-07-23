<?php
include_once('html_fns_revision.php');
validate_login("../");
$id = $_SESSION["codigo"];

	
// obtenemos los datos del archivo
$tamano = $_FILES["foto"]['size'];
$tipo = $_FILES["foto"]['type'];
$archivo = $_FILES["foto"]['name'];
$revision = $_REQUEST["revision"];
// Upload
if ($archivo != "") {
	$ClsLis = new ClsLista();
	$ClsRev = new ClsRevision();
	$stringFoto = str_shuffle($revision.uniqid());
	$codigo = $ClsRev->max_foto();
	$codigo++;
	$sql = $ClsRev->insert_foto($codigo,$revision,$stringFoto);
	$rs = $ClsRev->exec_sql($sql);
	if($rs == 1){
		// guardamos el archivo a la carpeta files
		$destino =  "../../CONFIG/Fotos/REVISION/".$stringFoto.".jpg";
		if (move_uploaded_file($_FILES['foto']['tmp_name'],$destino)) {
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
			//$image = new ImageResize($destino);
			//$image->resizeToWidth(300);
			//$image->save($destino);
			///
			$arr_respuesta = array(
				"status" => true,
				"imagen" => '<img src="'.$destino.'" alt="...">',
				"message" => "Imagen cargado satisfactoriamente!!!"
			);
			echo json_encode($arr_respuesta);
			die;
		}else {
			$arr_respuesta = array(
				"status" => false,
				"data" => [],
				"message" => "Error al cargar el archivo..."
			);
			echo json_encode($arr_respuesta);
			die;
		}
	}else{
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Error en la transacción al registrar en la BD...."
		);
		echo json_encode($arr_respuesta);
		die;
	}
} else {
	$arr_respuesta = array(
		"status" => false,
		"data" => [],
		"message" => "Error en la transacción, documento vacio..."
	);
	echo json_encode($arr_respuesta);
	die;
}
?>