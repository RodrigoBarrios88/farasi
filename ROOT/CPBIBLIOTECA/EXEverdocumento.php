<?php
include_once("html_fns_biblioteca.php");
$usuario = $_SESSION["codigo"];
$hashkey = $_REQUEST["hashkey"];
//--
$ClsBib = new ClsBiblioteca();
$codigo = $ClsBib->decrypt($hashkey,$usuario);
//despliegue de documento
$result = $ClsBib->get_biblioteca($codigo);
if(is_array($result)){
	foreach($result as $row){
		$documentoo = trim($row["bib_documento"]);
	}
	//header('Content-Type: image/jpg');
	header("Content-type:application/pdf");
	echo $documentoo;
}
?>