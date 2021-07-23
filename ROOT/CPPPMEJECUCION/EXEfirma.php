<?php
include_once('html_fns_ejecucion.php');
	validate_login("../");
$id = $_SESSION["codigo"];
	$nombre = utf8_decode($_SESSION["nombre"]);
	$rol = $_SESSION["rol"];
	$rol_nombre = utf8_decode($_SESSION["rol_nombre"]);
	$foto = $_SESSION["foto"];
	
///////////////// BD ///////////////////////
	//archivo temporal en binario
	$data_temporal = $_FILES['imagen']['tmp_name'];
	$codigo = $_REQUEST['codigo'];
	$itmp = fopen($data_temporal, 'r+b');
	$data = fread($itmp, filesize($data_temporal));
	fclose($itmp);
	//escapando los caracteres
	$ClsPro = new ClsProgramacionPPM();
	//$ultimaFirma = $ClsEnc->last_firma($codigo);
	$stringFirma = str_shuffle($codigo.uniqid());
	$sql = $ClsPro->firma_programacion($codigo,$stringFirma);
	$rs = $ClsPro->exec_sql($sql);

///////////// A CARPETAS //////////////////////
// obtenemos los datos del archivo
    $tamano = $_FILES["imagen"]['size'];
    $archivo = $_FILES["imagen"]['name'];
	$codigo = $_REQUEST['codigo'];
	$nombre = $stringFirma.".jpg";
	// Upload
	if ($archivo != "") {
		// guardamos el archivo a la carpeta files
		$destino =  "../../CONFIG/Fotos/PPMFIRMAS/".$nombre;
		if (move_uploaded_file($_FILES['imagen']['tmp_name'],$destino)) {
			$msj = "Imagen $archivo subido..." ; $status = 1;
		} else {
			$msj = "Error al subir el archivo"; $status = 0;
		}
	} else {
		$msj = "Archivo vacio.";  $status = 0;
	}$arr_data = array(
		"status" => $status,
		"message" => $msj
	);
	echo json_encode($arr_data);
	
?>