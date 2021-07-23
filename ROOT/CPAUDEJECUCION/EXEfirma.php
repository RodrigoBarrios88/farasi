<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$id = $_SESSION["codigo"];


///////////////// BD ///////////////////////
//archivo temporal en binario
$data_temporal = $_FILES['imagen']['tmp_name'];
$ejecucion = $_REQUEST['ejecucion'];
$firma = $_REQUEST['firma'];
$itmp = fopen($data_temporal, 'r+b');
$data = fread($itmp, filesize($data_temporal));
fclose($itmp);
//escapando los caracteres
$ClsEje = new ClsEjecucion();
//$ultimaFirma = $ClsEnc->last_firma($codigo);
$stringFirma = str_shuffle($ejecucion . uniqid());
if ($firma == 1) {
	$sql = $ClsEje->firma_evaluador_ejecucion($ejecucion, $stringFirma);
} else if ($firma == 2) {
	$sql = $ClsEje->firma_evaluado_ejecucion($ejecucion, $stringFirma);
}
$rs = $ClsEje->exec_sql($sql);

///////////// A CARPETAS //////////////////////
// obtenemos los datos del archivo
$tamano = $_FILES["imagen"]['size'];
$archivo = $_FILES["imagen"]['name'];
$ejecucion = $_REQUEST['ejecucion'];
$firma = $_REQUEST['firma'];
$nombre = $stringFirma . ".jpg";
// Upload
if ($archivo != "") {
	// guardamos el archivo a la carpeta files
	$destino =  "../../CONFIG/Fotos/AUDFIRMAS/" . $nombre;
	if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
		$msj = "Imagen $archivo subido...";
		$status = 1;
	} else {
		$msj = "Error al subir el archivo";
		$status = 0;
	}
} else {
	$msj = "Archivo vacio.";
	$status = 0;
}
$arr_data = array(
	"status" => $status,
	"message" => $msj
);
echo json_encode($arr_data);
