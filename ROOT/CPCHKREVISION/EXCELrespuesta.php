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
include_once('html_fns_revision.php');
	validate_login("../");
$id = $_SESSION["codigo"];
	$nombre_sesion = trim($_SESSION["nombre"]);
	$rol = $_SESSION["rol"];
	$rol_nombre = trim($_SESSION["rol_nombre"]);
	$foto = $_SESSION["foto"];
	//$_POST
	$sede = $_REQUEST["sede"];
	$sector = $_REQUEST["sector"];
	$area = $_REQUEST["area"];
	$categoria = $_REQUEST["categoria"];
	$revision = $_REQUEST["revision"];
	//--
	$desde = $_REQUEST["desde"];
	$hasta = $_REQUEST["hasta"];$ClsCat = new ClsCategoria();
	$result = $ClsCat->get_categoria_checklist($categoria,'',1);
	$categorias_nombre = "";
	if(is_array($result)){
		foreach($result as $row){
			$categorias_nombre.= trim($row["cat_nombre"]).", ";
		}
		$categorias_nombre = substr($categorias_nombre, 0, -2);
	}$titulo = "Reporte de Respuestas";
    $file_desc = "Listado exportado a Excel";

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator($nombre_usu)
							 ->setLastModifiedBy($nombre_usu)
							 ->setTitle($titulo)
							 ->setSubject("Nomina en Excel Office 2007 XLSX")
							 ->setDescription($file_desc)
							 ->setKeywords("BPManagement")
							 ->setCategory($file_desc);
                             
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);    /// Combinacion de Celdas (Merge cells)
    $objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
    $objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
    $objPHPExcel->getActiveSheet()->mergeCells('A3:F3');
    $objPHPExcel->getActiveSheet()->mergeCells('A4:F4');
    $objPHPExcel->getActiveSheet()->mergeCells('A5:F5');
    $objPHPExcel->getActiveSheet()->mergeCells('A6:F6');    //Seteo de Titulos
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', $titulo)
            ->setCellValue('A3', 'Fecha/Hora de generación: '.date("d/m/Y H:i"))
            ->setCellValue('A4', 'Generado por: '.$nombre_sesion)
			->setCellValue('A5', 'Reporte Conjunto, categorías calculadas: '.$categorias_nombre);
            
    ///// ESTILOS        
    $objPHPExcel->getActiveSheet()->getStyle("A1:A4")->getFont()->setName('Arial'); /// Asigna tipo de letra
    $objPHPExcel->getActiveSheet()->getStyle("A1:A4")->getFont()->setSize(12); /// Asigna tamaño de letra
    $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setSize(16); /// Asigna tamaño de letra
    $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true); /// Asigna negrita
    $objPHPExcel->getActiveSheet()->getStyle("A1")->getFill()->getStartColor()->setARGB('C4C4C4');    ///// LOGO
    $gdImage = imagecreatefromjpeg('../../CONFIG/img/logo.jpg');
    // Add a drawing to the worksheet
    $objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
    $objDrawing->setName('Logo');
    $objDrawing->setDescription('Logo');
    $objDrawing->setImageResource($gdImage);
    $objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
    $objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
    $objDrawing->setHeight(100);
    $objDrawing->setCoordinates('F1');
    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
   
	////// ENCABEZADOS /////
	$objPHPExcel->getActiveSheet()->setCellValue('A8', "No.");
	$objPHPExcel->getActiveSheet()->setCellValue('B8', "#Revisión");
	$objPHPExcel->getActiveSheet()->setCellValue('C8', "Lista");
	$objPHPExcel->getActiveSheet()->setCellValue('D8', "Categoría");
	$objPHPExcel->getActiveSheet()->setCellValue('E8', "Fecha");
	$objPHPExcel->getActiveSheet()->setCellValue('F8', "Pregunta");
	$objPHPExcel->getActiveSheet()->setCellValue('H8', "Respuesta");
	$objPHPExcel->getActiveSheet()->setCellValue('H8', "Observaciones en Revisión");
	//--
	$objPHPExcel->getActiveSheet()->getStyle("A8:H8")->getFont()->setName('Arial'); /// Asigna tipo de letra
	$objPHPExcel->getActiveSheet()->getStyle("A8:H8")->getFont()->setSize($fontsize*2); /// Asigna tamaño de letra
	$objPHPExcel->getActiveSheet()->getStyle("A8:H8")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle("A8:H8")->getFill()->getStartColor()->setARGB('C4C4C4');
	$objPHPExcel->getActiveSheet()->getStyle("A8:H8")->getFont()->setBold(true); /// Asigna negrita
	$objPHPExcel->getActiveSheet()->getStyle("A8:H8")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); /// alinea al  centro
	//--
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(7);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(100);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(110);
	/////////////////////////////
	/////////////////////////////        
	$linea_inicial = 8;
	$linea = 9;$ClsRev = new ClsRevision();
	$result = $ClsRev->get_resultados($revision,'','',$sede, $sector, $area, $categoria,$desde,$hasta);
	if(is_array($result)){
		$num = 1;
		foreach($result as $row){
			//nombre
			$codigo = Agrega_Ceros($row["rev_codigo"]);
			//lista
			$lista = trim($row["list_nombre"]);
			//categoria
			$categoria = trim($row["cat_nombre"]);
			//fecha
			$fecha = cambia_fechaHora($row["resp_fecha_registro"]);
			//pregunta
			$pregunta = trim($row["pre_pregunta"]);
			//respuesta
			$resp = trim($row["resp_respuesta"]);
			if($resp == 1){
				$respuesta = 'SI';
			}else if($resp == 2){
				$respuesta = 'NO';
			}else{
				$respuesta = 'No aplica';
			}
			//observaciones
			$observaciones = trim($row["rev_observaciones"]);
			//--
			$objPHPExcel->getActiveSheet()->setCellValue("A$linea", $num);
			$objPHPExcel->getActiveSheet()->setCellValue("B$linea", "# $codigo");
			$objPHPExcel->getActiveSheet()->setCellValue("C$linea", $lista);
			$objPHPExcel->getActiveSheet()->setCellValue("D$linea", $categoria);
			$objPHPExcel->getActiveSheet()->setCellValue("E$linea", $fecha);
			$objPHPExcel->getActiveSheet()->setCellValue("F$linea", $pregunta);
			$objPHPExcel->getActiveSheet()->setCellValue("G$linea", $respuesta);
			$objPHPExcel->getActiveSheet()->setCellValue("H$linea", $observaciones);
			$num++;
			$linea++;
		}
	}
	$linea--;
	//////////////////////////////////////////////////////////////////////////////////// - ESTILOS DEL CUERPO DE LA TABLA - //
	// Alineacion
	$objPHPExcel->getActiveSheet()->getStyle("A$linea_inicial:A$linea")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle("C$linea_inicial:C$linea")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle("D$linea_inicial:D$linea")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle("E$linea_inicial:E$linea")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle("G$linea_inicial:G$linea")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	 
	// recuadro de columnas
	$styleThinBlackBorderOutline = array(
		 'borders' => array(
			  'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('argb' => 'FF000000'),
			  ),
		 ),
	);
	$objPHPExcel->getActiveSheet()->getStyle("A$linea_inicial:A$linea")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle("B$linea_inicial:B$linea")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle("C$linea_inicial:C$linea")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle("D$linea_inicial:D$linea")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle("E$linea_inicial:E$linea")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle("F$linea_inicial:F$linea")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle("G$linea_inicial:G$linea")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle("H$linea_inicial:H$linea")->applyFromArray($styleThinBlackBorderOutline);// Pone el Recuadro sobre la tabla
	$styleThinBlackBorderOutline = array(
		 'borders' => array(
			  'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THICK,
					'color' => array('argb' => 'FF000000'),
			  ),
		 ),
	);
	$celda_inicial = "A".$linea_inicial;
	$celda_final = "H".$linea;
	$objPHPExcel->getActiveSheet()->getStyle("A8:H8")->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle("$celda_inicial:$celda_final")->applyFromArray($styleThinBlackBorderOutline);


// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle("Reporte de Respuestas");

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$titulo.'.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 31 Jul 2017 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>