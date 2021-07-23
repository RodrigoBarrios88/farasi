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
include_once('html_fns_programacion.php');
validate_login("../");
$id = $_SESSION["codigo"];
$nombre_sesion = utf8_decode($_SESSION["nombre"]);
$rol = $_SESSION["rol"];
$rol_nombre = utf8_decode($_SESSION["rol_nombre"]);
$foto = $_SESSION["foto"];
//$_POST
$sede = $_REQUEST["sede"];
$area = $_REQUEST["area"];
$activo = $_REQUEST["activo"];
$usuario = $_REQUEST["usuario"];
$desde = $_REQUEST["desde"];
$hasta = $_REQUEST["hasta"];
//
$titulo = "REPORTE DE PROGRAMACION";
$doctitle = "REP_PROGRAMAMCION";
$W1 = date("W", strtotime(regresa_fecha($desde)));
$W2 = date("W", strtotime(regresa_fecha($hasta)));
$semanas = (intval($W2) - intval($W1)); 
////////////////////////////////////// PARAMETROS ///////////////////////////////////////////


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
$objPHPExcel->setActiveSheetIndex(0);	/// Combinacion de Celdas (Merge cells)
$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
$objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
$objPHPExcel->getActiveSheet()->mergeCells('A3:F3');
$objPHPExcel->getActiveSheet()->mergeCells('A4:F4');
$objPHPExcel->getActiveSheet()->mergeCells('A5:F5');
$objPHPExcel->getActiveSheet()->mergeCells('A6:F6'); //Seteo de Titulos
$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A1', $titulo)
	->setCellValue('A3', 'Fecha/Hora de generación: ' . date("d/m/Y H:i"))
	->setCellValue('A4', 'Generado por: ' . $nombre_sesion)
	->setCellValue('A5', 'Semanas de la  ' . $W1 . ' a la ' . $W2);

$objPHPExcel->getActiveSheet()->getStyle("A1:A6")->getFont()->setName('Arial'); /// Asigna tipo de letra
$objPHPExcel->getActiveSheet()->getStyle("A1:A6")->getFont()->setSize(12); /// Asigna tamaño de letra
$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(16); /// Asigna tamaño de letra
$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true); /// Asigna negrita
$objPHPExcel->getActiveSheet()->getStyle("A1")->getFill()->getStartColor()->setARGB('C4C4C4'); /////////////////////////////        
$linea_inicial = 9;
$linea = 9;
$i = 1; ////// ENCABEZADOS /////
$objPHPExcel->getActiveSheet()->setCellValue("A8", "No.");
$objPHPExcel->getActiveSheet()->setCellValue("B8", "ORDERN No.");
$objPHPExcel->getActiveSheet()->setCellValue("C8", "CATEGORIA");
$objPHPExcel->getActiveSheet()->setCellValue("D8", "ACTIVO");
$objPHPExcel->getActiveSheet()->setCellValue("E8", "USUARIO RESPONSABLE");
$objPHPExcel->getActiveSheet()->setCellValue("F8", "SEDE");
$objPHPExcel->getActiveSheet()->setCellValue("G8", "AREA");
$objPHPExcel->getActiveSheet()->setCellValue("H8", "PISO");
$totalColumnas = 8;
$arrSemana = array();
$W1 = intval($W1);
$W2 = intval($W2);
for ($i = $W1; $i <= $W2; $i++) {
	$totalColumnas++;
	$letra = LetrasBase($totalColumnas);
	$objPHPExcel->getActiveSheet()->setCellValue($letra . "8", "W$i");
	$arrSemana[$i] = $letra;
}
//--
$letra = LetrasBase($totalColumnas);
$objPHPExcel->getActiveSheet()->getStyle("A8:$letra" . "8")->getFont()->setName('Arial'); /// Asigna tipo de letra
$objPHPExcel->getActiveSheet()->getStyle("A8:$letra" . "8")->getFont()->setSize($fontsize * 2); /// Asigna tamaño de letra
$objPHPExcel->getActiveSheet()->getStyle("A8:$letra" . "8")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle("A8:$letra" . "8")->getFill()->getStartColor()->setARGB('C4C4C4');
$objPHPExcel->getActiveSheet()->getStyle("A8:$letra" . "8")->getFont()->setBold(true); /// Asigna negrita
$objPHPExcel->getActiveSheet()->getStyle("A8:$letra" . "8")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); /// alinea al  centro
//-- Anchos de columnas
$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(6);
$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth(15);
for ($i = 9; $i <= $totalColumnas; $i++) {
	$letra = LetrasBase($i);
	$objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setWidth(10);
}
$situacion = ($situacion == 5) ? 1 : $situacion;
$ClsPro = new ClsProgramacionPPM();
$result = $ClsPro->get_programacion_reporte($codigo, $activo, $usuario, '', $sede, '', $area, $desde, $hasta, '', '', $situacion);
if (is_array($result)) {
	$i = 0;
	$linea--;
	$activoX = 0;
	foreach ($result as $row) {
		$activo = trim($row["act_codigo"]);
		if ($activo != $activoX) {
			$activoX = $activo;
			$linea++;
			$i++;
			//No.
			$objPHPExcel->getActiveSheet()->setCellValue("A" . $linea, $i . ".");
			//codigo
			$campo = Agrega_Ceros($row["pro_codigo"]);
			$objPHPExcel->getActiveSheet()->setCellValue("B" . $linea, trim($campo));
			//categoria
			$campo = utf8_decode($row["cat_nombre"]);
			$campo = trim($campo);
			$objPHPExcel->getActiveSheet()->setCellValue("C" . $linea, trim($campo));
			//activo
			$campo = utf8_decode($row["act_nombre"]);
			$campo = trim($campo);
			$objPHPExcel->getActiveSheet()->setCellValue("D" . $linea, trim($campo));
			//usuairo
			$campo = utf8_decode($row["usu_nombre"]);
			$campo = trim($campo);
			$objPHPExcel->getActiveSheet()->setCellValue("E" . $linea, trim($campo));
			//sede
			$campo = utf8_decode($row["sed_nombre"]);
			$campo = trim($campo);
			$objPHPExcel->getActiveSheet()->setCellValue("F" . $linea, trim($campo));
			//area
			$campo = utf8_decode($row["are_nombre"]);
			$campo = trim($campo);
			$objPHPExcel->getActiveSheet()->setCellValue("G" . $linea, trim($campo));
			//piso
			$campo = utf8_decode($row["are_nivel"]);
			$campo = trim($campo);
			$objPHPExcel->getActiveSheet()->setCellValue("H" . $linea, trim($campo));
			//periodicidad
			$periodicidad = trim($row["act_periodicidad"]);
			//fecha
			$fecha = trim($row["pro_fecha"]);
			$semana = date("W", strtotime($fecha));
			$semana = intval($semana);
			$letra = $arrSemana[$semana];
			$objPHPExcel->getActiveSheet()->setCellValue($letra . $linea, trim($periodicidad));
			//color
			switch ($periodicidad) {
				case "D":
					$color = "A9A9F5";
					break;
				case "W":
					$color = "5FB404";
					break;
				case "M":
					$color = "E0E6F8";
					break;
				case "Y":
					$color = "FF4000";
					break;
				case "V":
					$color = "F6CECE";
					break;
			}
			$objPHPExcel->getActiveSheet()->getStyle($letra . $linea)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle($letra . $linea)->getFill()->getStartColor()->setARGB($color);
			$objPHPExcel->getActiveSheet()->getStyle($letra . $linea)->getFont()->setBold(true);
		} else {
			//periodicidad
			$periodicidad = trim($row["act_periodicidad"]);
			//fecha
			$fecha = trim($row["pro_fecha"]);
			$semana = date("W", strtotime($fecha));
			$semana = intval($semana);
			$letra = $arrSemana[$semana];
			$objPHPExcel->getActiveSheet()->setCellValue($letra . $linea, trim($periodicidad));
			//color
			switch ($periodicidad) {
				case "D":
					$color = "A9A9F5";
					break;
				case "W":
					$color = "5FB404";
					break;
				case "M":
					$color = "E0E6F8";
					break;
				case "Y":
					$color = "FF4000";
					break;
				case "V":
					$color = "F6CECE";
					break;
			}
			$objPHPExcel->getActiveSheet()->getStyle($letra . $linea)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle($letra . $linea)->getFill()->getStartColor()->setARGB($color);
			$objPHPExcel->getActiveSheet()->getStyle($letra . $linea)->getFont()->setBold(true);
		}
	}
}   // - ESTILOS DEL CUERPO DE LA TABLA - //
// Alineacion
$objPHPExcel->getActiveSheet()->getStyle("A" . $linea_inicial . ":A" . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("B" . $linea_inicial . ":B" . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("H" . $linea_inicial . ":H" . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
for ($i = 9; $i <= $totalColumnas; $i++) {
	$letra = LetrasBase($i);
	$objPHPExcel->getActiveSheet()->getStyle($letra . $linea_inicial . ":" . $letra . $linea)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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
$objPHPExcel->getActiveSheet()->setTitle($doctitle);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $doctitle . '.xlsx"');
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
