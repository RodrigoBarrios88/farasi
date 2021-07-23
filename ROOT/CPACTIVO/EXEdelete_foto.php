<?php
	include_once('html_fns_activo.php');
	validate_login("../");
$id = $_SESSION["codigo"];
	$nombre = utf8_decode($_SESSION["nombre"]);
	$rol = $_SESSION["rol"];
	$rol_nombre = utf8_decode($_SESSION["rol_nombre"]);
	$foto = $_SESSION["foto"];
	
///////////// A CARPETAS //////////////////////
// obtenemos los datos del archivo
    $codigo = $_REQUEST["codigo"];
	$ClsAct = new ClsActivo();// Upload
	if($codigo != "") {
		$result = $ClsAct->get_fotos($codigo,'');
		$ultimaFoto = "";
		if(is_array($result)){
			foreach ($result as $row){
				$ultimaFoto = trim($row["fot_foto"]);
				$activo = trim($row["fot_activo"]);
				$posicion = trim($row["fot_posicion"]);
			}	
		}
		//--
		$sql = $ClsAct->delete_foto($codigo);
		$rs = $ClsAct->exec_sql($sql);
		if($rs == 1){
		///eliminamos la anterior
		unlink("../../CONFIG/Fotos/ACTIVOS/".$ultimaFoto.".jpg");
		$msj = "Foto eliminada con exito..."; $status = 1;
		}else{
			$msj = "Error en la transacci\u00F3n al eliminar en Base de Datos"; $status = 0;
		}	
	}else{
		$msj = "Alguno de los parametros va vacio...";  $status = 0;
	}$result = $ClsAct->get_fotos($codigo,$activo,$posicion);
	$arrFoto = array();
	$foto = "";
	$i = 0;
	if(is_array($result)){
		foreach ($result as $row){
			$fotCodigo = trim($row["fot_codigo"]);
			$posicion = trim($row["fot_posicion"]);
			$foto = trim($row["fot_foto"]);
			if(file_exists('../../CONFIG/Fotos/ACTIVOS/'.$foto.'.jpg') || $foto != ""){
				$arrFoto[$i]["foto"] = '<img onclick="deleteFotoConfirm('.$fotCodigo.','.$posicion.');" class="img-upload" src="../../CONFIG/Fotos/ACTIVOS/'.$foto.'.jpg" alt="..." />';
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