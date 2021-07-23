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
    $fotCodigo = $_REQUEST["codigo"];
	$auditoria = $_REQUEST["auditoria"];
	$ejecucion = $_REQUEST["ejecucion"];
	$pregunta = $_REQUEST["pregunta"];
	$ClsEje = new ClsEjecucion();// Upload
	if($fotCodigo != "" && $auditoria != "" && $ejecucion != "" && $pregunta) {
		$result = $ClsEje->get_fotos($fotCodigo,$ejecucion,$auditoria,$pregunta);
		$ultimaFoto = "";
		if(is_array($result)){
			foreach ($result as $row){
				$ultimaFoto = trim($row["fot_foto"]);
			}	
		}
		//--
		$sql = $ClsEje->delete_foto($fotCodigo,$auditoria,$pregunta,$ejecucion);
		$rs = $ClsEje->exec_sql($sql);
		if($rs == 1){
		///eliminamos la anterior
		unlink("../../CONFIG/Fotos/AUDITORIA/".$ultimaFoto.".jpg");
		$msj = "Foto eliminada con exito..."; $status = 1;
		}else{
			$msj = "Error en la transacci\u00F3n al eliminar en Base de Datos"; $status = 0;
		}	
	}else{
		$msj = "Alguno de los parametros va vacio...";  $status = 0;
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