<?php
ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cache");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);

require_once("../recursos/mandrill/src/Mandrill.php"); //--correos
require_once("../../CONFIG/constructor.php"); //--correos
include_once('html_fns_ejecucion.php');

// Obtenemos los datos del archivo
$tamano = $_FILES["documento"]['size'];
$archivo = $_FILES["documento"]['name'];
$ejecucion = $_REQUEST["ejecucion"];
$posicion = $_REQUEST["posicion"];
$ClsEje = new ClsEjecucion();

// Upload
if ($archivo != "") {
	$codigo = $ClsEje->max_documento_ejecucion();
	$codigo++;
	$stringDocumento = str_shuffle($codigo . $ejecucion . $posicion . uniqid());
	$sql = $ClsEje->insert_documento_ejecucion($codigo, $ejecucion, $posicion, $stringDocumento);
	$rs = $ClsEje->exec_sql($sql);
	if ($rs == 1) {
		// guardamos el archivo a la carpeta files
		$destino =  "../../CONFIG/Archivos/ACCION/" . $stringDocumento . ".pdf";
		if (move_uploaded_file($_FILES['documento']['tmp_name'], $destino)) {
			$msj = "documento $archivo subido exitosamente...!";
			$status = 1;
		} else {
			$msj = "Error al subir el archivo";
			$status = 0;
		}
	} else {
		$msj = "Error en la transacci\u00F3n al cargar a la Base de Datos".$sql;
		$status = 0;
	}
} else {
	$msj = "Archivo vacio. $ejecucion, $pregunta";
	$status = 0;
}

$result = $ClsEje->get_documentos_ejecucion($codigo, $ejecucion, $posicion);
$arrdocumento = array();
$documento = "";
$i = 0;
if (is_array($result)) {
	foreach ($result as $row) {
		$docCodigo = trim($row["doc_codigo"]);
		$posicion = trim($row["doc_posicion"]);
		$documento = trim($row["doc_documento"]);
		if (file_exists('../../CONFIG/Archivos/ACCION/' . $documento . '.pdf') || $documento != "") {
			$arrdocumento[$i]["documento"] = '<img onclick="deletedocumentoConfirm(' . $docCodigo . ',' . $posicion . ');" class="img-upload" src="../../CONFIG/Archivos/ACCION/' . $documento . '.jpg" alt="..." />';
		} else {
			$arrdocumento[$i]["documento"] = '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
		}
		$i++;
	}
} else {
	$arrdocumento[$i]["documento"] = '<img class="img-demo" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
}

$arr_data = array(
	"status" => $status,
	"img" => $arrdocumento,
	"message" => $msj
);
echo json_encode($arr_data);
