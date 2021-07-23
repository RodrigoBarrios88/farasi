<?php
include_once('html_fns_ejecucion.php');
	validate_login("../");
$id = $_SESSION["codigo"];
	$nombre = utf8_decode($_SESSION["nombre"]);
	$rol = $_SESSION["rol"];
	$rol_nombre = utf8_decode($_SESSION["rol_nombre"]);
	$foto = $_SESSION["foto"];///////////////// BD ///////////////////////
	//archivo temporal en binario
	$data_temporal = $_FILES['imagen']['tmp_name'];
	$ejecucion = $_REQUEST['ejecucion'];
	$itmp = fopen($data_temporal, 'r+b');
	$data = fread($itmp, filesize($data_temporal));
	fclose($itmp);
	//escapando los caracteres
	$ClsPla = new ClsPlan();
	$ultimaFirma = $ClsPla->last_firma_usuario($ejecucion);
	$stringFirma = str_shuffle($ejecucion."plan".uniqid());
	$sql = $ClsPla->cambia_firma_usuario($ejecucion,$stringFirma);
	$rs = $ClsPla->exec_sql($sql);
	if($rs == 1){
		///////////// A CARPETAS //////////////////////
		// obtenemos los datos del archivo
		$tamano = $_FILES["imagen"]['size'];
		$archivo = $_FILES["imagen"]['name'];
		$nombre = $stringFirma.".jpg";
		// Upload
		if ($archivo != "") {
			// guardamos el archivo a la carpeta files
			$destino =  "../../CONFIG/Fotos/AUDFIRMAS/".$nombre;
			if (move_uploaded_file($_FILES['imagen']['tmp_name'],$destino)) {
				$msj = "Firma $archivo subida exitosamente..." ; $status = true;
				if(file_exists('../../CONFIG/Fotos/AUDFIRMAS/'.$ultimaFirma.'.jpg') && $ultimaFirma != ""){
					unlink("../../CONFIG/Fotos/AUDFIRMAS/$ultimaFirma.jpg");
				}
			} else {
				$msj = "Error al subir el archivo..."; $status = false;
			}
		} else {
			$msj = "Archivo vacio...";  $status = false;
		}
		
		$arr_data = array(
			"status" => $status,
			"message" => $msj
		);
		echo json_encode($arr_data);
	}else{
		$arr_data = array(
			"status" => false,
			"message" => "Error de ejecución en la Base de Datos"
		);
		echo json_encode($arr_data);
	}
?>