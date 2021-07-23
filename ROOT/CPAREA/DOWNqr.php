<?php

include_once('../html_fns.php');
	validate_login("../");
$id = $_SESSION["codigo"];
	$nombre = utf8_decode($_SESSION["nombre"]);
	//post
	$codigo = $_REQUEST["codigo"];
	$ClsSed = new ClsSede();
	$ClsAre = new ClsArea();
	$result = $ClsAre->get_area($codigo,'','','','',1);
	if(is_array($result)){
		foreach($result as $row){
			$sede = trim($row["sed_codigo"]);
			$codigo = Agrega_Ceros($row["are_codigo"]);
			$sector = utf8_decode($row["sec_nombre"]);
			$nombre = utf8_decode($row["are_nombre"]);
			$nivel = utf8_decode($row["are_nivel"]);
		}	
	}$logo = $ClsSed->last_foto_sede($sede);
	if($logo != ""){
		$logo = "../../CONFIG/Fotos/SEDES/$logo.jpg";
	}else{
	    $logo = "../../CONFIG/img/logo.jpg";
	}$pdf = new PDF('P','mm',array(90,60));   // si quieren el reporte horizontal
	$pdf->AddPage();
	$pdf->SetAutoPageBreak(false,2);$pdf->SetMargins(5,5,5);
	$pdf->Ln(2);$pdf->Image($logo , 20 ,5, 20 , 20,'JPG', '');

	//QRCode
	$QRcode = crea_QR($codigo);
	$pdf->Image($QRcode , 10 ,25, 40 , 40,'JPG', '');$pdf->Ln(53);
	$pdf->SetFont('Arial','B',10);
	$pdf->MultiCell(0, 4, $nombre, 0 , 'C' , 0);
	$pdf->SetFont('Arial','',10);
	$pdf->MultiCell(0, 4, 'C�digo: '.$codigo, 0 , 'C' , 0);
	$pdf->MultiCell(0, 4, 'Sector: '.$sector, 0 , 'C' , 0);
	$pdf->MultiCell(0, 4, 'Nivel: '.$nivel, 0 , 'C' , 0);$pdf->Output("$codigo.pdf","D");?>