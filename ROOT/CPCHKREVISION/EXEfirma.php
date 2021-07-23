<?php
include_once('html_fns_revision.php');
	validate_login("../");
$id = $_SESSION["codigo"];
	$nombre = utf8_decode($_SESSION["nombre"]);
	$rol = $_SESSION["rol"];
	$rol_nombre = utf8_decode($_SESSION["rol_nombre"]);
	$foto = $_SESSION["foto"];
	
///////////////// BD ///////////////////////
	//archivo temporal en binario
	$data_temporal = $_FILES['imagen']['tmp_name'];
	$revision = $_REQUEST['revision'];
	$itmp = fopen($data_temporal, 'r+b');
	$data = fread($itmp, filesize($data_temporal));
	fclose($itmp);
	//escapando los caracteres
	$stringFoto = str_shuffle($revision.uniqid());
///////////// A CARPETAS //////////////////////
// obtenemos los datos del archivo
    $tamano = $_FILES["imagen"]['size'];
    $archivo = $_FILES["imagen"]['name'];
	$revision = $_REQUEST['revision'];
	$nombre = $stringFirma.".jpg";
	// Upload
	if ($archivo != "") {
		// guardamos el archivo a la carpeta files
		$destino =  "../../CONFIG/Fotos/FIRMAS/$stringFoto.jpg";
		if (move_uploaded_file($_FILES['imagen']['tmp_name'],$destino)) {
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
				$msj = "firma cargada exitosamente..." ; $status = 1;
			}else{
				unlink($destino); //elimina carga si hay error...
				$msj = "Existió un problema con el registro en base de datos..."; $status = 0;
			}
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