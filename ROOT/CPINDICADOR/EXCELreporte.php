<?php

/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.8.0, 2014-03-02
 */

/** Error reporting */
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Guatemala');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once '../Clases/PHPExcel.php';
include_once('html_fns_indicador.php');
validate_login("../");
$id = $_SESSION["codigo"];
$nombre_sesion = trim($_SESSION["nombre"]);
$rol = $_SESSION["rol"];
$rol_nombre = trim($_SESSION["rol_nombre"]);
$foto = $_SESSION["foto"];
//$_POST
$proceso = $_REQUEST["proceso"];
$sistema = $_REQUEST["sistema"];
$usuario = $_REQUEST["usuario"];
$dia = $_REQUEST["dia"];
$situacion = $_REQUEST["sit"];
//-
$columnas = $_REQUEST["columnas"];
$titulo = "Reporte de Programaciones";

////////////////////////////////////// PARAMETROS ///////////////////////////////////////////
$anchos[1] = 6;
$alineaciones[1] = "C";
$titulos[1] = "No.";
$i = 2;
$ancho_total = 6;
if (is_array($columnas)) {
	foreach ($columnas as $col) {
		$parametros = parametrosDinamicosEXCEL($col);
		$anchos[$i] = $parametros['ancho'];
		$alineaciones_titulos[$i] = 'C';
		$alineaciones[$i] = $parametros['alineacion'];
		$titulos[$i] = trim($parametros['titulo']);
		$ancho_total += $parametros['ancho'];
		$i++;
	}
}
$i--;
$totalColumnas = $i;
//--


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator($nombre_sesion)
	->setLastModifiedBy($nombre_sesion)
	->setTitle($titulo)
	->setSubject("Nomina en Excel Office 2007 XLSX")
	->setDescription($titulo)
	->setKeywords("office 2007 openxml php")
	->setCategory("LISTADO");

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

/// Combinacion de Celdas (Merge cells)
$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
$objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
$objPHPExcel->getActiveSheet()->mergeCells('A3:F3');
$objPHPExcel->getActiveSheet()->mergeCells('A4:F4');
$objPHPExcel->getActiveSheet()->mergeCells('A5:F5');
$objPHPExcel->getActiveSheet()->mergeCells('A6:F6');

//Seteo de Titulos
$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A1', $titulo)
	->setCellValue('A3', 'Fecha/Hora de generación: ' . date("d/m/Y H:i"))
	->setCellValue('A4', 'Generado por: ' . $nombre_sesion);

$objPHPExcel->getActiveSheet()->getStyle("A1:A6")->getFont()->setName('Arial'); /// Asigna tipo de letra
$objPHPExcel->getActiveSheet()->getStyle("A1:A6")->getFont()->setSize(12); /// Asigna tamaño de letra
$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(16); /// Asigna tamaño de letra
$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true); /// Asigna negrita
$objPHPExcel->getActiveSheet()->getStyle("A1")->getFill()->getStartColor()->setARGB('C4C4C4');

/////////////////////////////        
$linea_inicial = 9;
$linea = 9;
$i = 1;

////// ENCABEZADOS /////
for ($i = 1; $i <= $totalColumnas; $i++) {
	$letra = LetrasBase($i);
	$objPHPExcel->getActiveSheet()->setCellValue($letra . "8", $titulos[$i]);
}

//--
$letra = LetrasBase($totalColumnas);
//echo "$letra - $totalColumnas <br><br>";

$objPHPExcel->getActiveSheet()->getStyle("A8:$letra" . "8")->getFont()->setName('Arial'); /// Asigna tipo de letra
$objPHPExcel->getActiveSheet()->getStyle("A8:$letra" . "8")->getFont()->setSize($fontsize * 2); /// Asigna tamaño de letra
$objPHPExcel->getActiveSheet()->getStyle("A8:$letra" . "8")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle("A8:$letra" . "8")->getFill()->getStartColor()->setARGB('C4C4C4');
$objPHPExcel->getActiveSheet()->getStyle("A8:$letra" . "8")->getFont()->setBold(true); /// Asigna negrita
$objPHPExcel->getActiveSheet()->getStyle("A8:$letra" . "8")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); /// alinea al  centro
//--
for ($i = 1; $i <= $totalColumnas; $i++) {
	$letra = LetrasBase($i);
	$objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth($anchos[$i]);
}

$ClsInd = new ClsIndicador();
	$result = $ClsInd->get_programacion('', '', $proceso, $sistema, '', '', $situacion, '', $fini, $ffin);
if (is_array($result)) {
	$i = 1;
	foreach ($result as $row) {
		$objPHPExcel->getActiveSheet()->setCellValue("A" . $linea, $i . ".");
		$j = 2;
		//--
		if (is_array($columnas)) {
			foreach ($columnas as $col) {
				$parametros = parametrosDinamicosEXCEL($col);
				$campo = $parametros['campo'];
				$letra = LetrasBase($j);
				if ($col == "ind_codigo" || $col == "cat_codigo" || $col == "cla_codigo" || $col == "dep_codigo") {
					$campo = '# ' . Agrega_Ceros($row[$campo]);
					$objPHPExcel->getActiveSheet()->setCellValue($letra . $linea, $campo);
				} else if ($col == "pro_dias") {
					$dias = "";
					$dia1 = trim($row["pro_dia_1"]);
					$dias .= ($dia1 == 1) ? "Lun," : "";
					$dia2 = trim($row["pro_dia_2"]);
					$dias .= ($dia2 == 1) ? "Mar," : "";
					$dia3 = trim($row["pro_dia_3"]);
					$dias .= ($dia3 == 1) ? "Mie," : "";
					$dia4 = trim($row["pro_dia_4"]);
					$dias .= ($dia4 == 1) ? "Jue," : "";
					$dia5 = trim($row["pro_dia_5"]);
					$dias .= ($dia5 == 1) ? "Vie," : "";
					$dia6 = trim($row["pro_dia_6"]);
					$dias .= ($dia6 == 1) ? "Sab," : "";
					$dia7 = trim($row["pro_dia_7"]);
					$dias .= ($dia7 == 1) ? "Dom," : "";
					$diaMes = trim($row["pro_dia_mes"]);
					$dias .= ($diaMes != 0) ? "día $diaMes del mes " : ""; 
					$campo = substr($dias, 0, -1);
					$objPHPExcel->getActiveSheet()->setCellValue($letra . $linea, trim($campo));
				} else if($col == "pro_tipo"){
					$campo = (trim($row["pro_tipo"]) == "S") ? "Semanal" : "Mensual";
					$objPHPExcel->getActiveSheet()->setCellValue($letra.$linea, trim($campo));
				}else if ($col == "pro_hini_hfin") {
					$campo = trim($row["pro_hini"]) . "-" . trim($row["pro_hfin"]);
					$objPHPExcel->getActiveSheet()->setCellValue($letra . $linea, trim($campo));
				}  else if ($col == "revision_activa") {
					if ($row[$campo]) $campo = "Activa (Rev #" . Agrega_Ceros($row[$campo]) . ")";
					else $campo = "Inactiva";
					$objPHPExcel->getActiveSheet()->setCellValue($letra . $linea, trim($campo));
				} else {
					$campo = trim($row[$campo]);
					$campo = quita_tildes($campo);
					$objPHPExcel->getActiveSheet()->setCellValue($letra . $linea, trim($campo));
				}
				$j++;
			}
		}
		$linea++;
		$i++;
	}
	$linea--;
}

// - ESTILOS DEL CUERPO DE LA TABLA - //
// Alineacion
for ($i = 1; $i <= $totalColumnas; $i++) {
	$letra = LetrasBase($i);
	$alinea = $alineaciones[$i];
	if ($alinea == "C") { /// CENTRA LA COLUMNA DE LA PRIMERA A LA ULTIMA FILA
		$objPHPExcel->getActiveSheet()->getStyle($letra . $linea_inicial . ":" . $letra . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	}
}

// recuadro de columnas
$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);


// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle($titulo);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $titulo . '.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 31 Jul 2017 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
