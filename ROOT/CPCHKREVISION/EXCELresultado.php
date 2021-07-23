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
	$nombre_sesion = utf8_decode($_SESSION["nombre"]);
	$rol = $_SESSION["rol"];
	$rol_nombre = utf8_decode($_SESSION["rol_nombre"]);
	$foto = $_SESSION["foto"];
	//$_POST
	$sede = $_REQUEST["sede"];
	$sector = $_REQUEST["sector"];
	$area = $_REQUEST["area"];
	$categoria = $_REQUEST["categoria"];
	$periodo = $_REQUEST["periodo"];
	$situacion = "1,2";
	//--
	$fini = $_REQUEST["desde"];
	$ffin = $_REQUEST["hasta"];if($periodo == "D"){
		$titulo = "Reporte dia a dia del $fini al $ffin";
	}else if($periodo == "S"){
		$titulo = "Reporte semana a semana del $fini al $ffin";
	}else if($periodo == "M"){
		$titulo = "Reporte mes a mes del $fini al $ffin";
	}$ClsCat = new ClsCategoria();
	$result = $ClsCat->get_categoria_checklist($categoria,'',1);
	$categorias_nombre = "";
	if(is_array($result)){
		foreach($result as $row){
			$categorias_nombre.= trim($row["cat_nombre"]).", ";
		}
		$categorias_nombre = substr($categorias_nombre, 0, -2);
	}	
	
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
    $objPHPExcel->getActiveSheet()->setCellValue('B8', "");
    $objPHPExcel->getActiveSheet()->setCellValue('C8', "Respuestas SI");
    $objPHPExcel->getActiveSheet()->setCellValue('D8', "Respuestas NO");
    $objPHPExcel->getActiveSheet()->setCellValue('E8', "Respuestas N/A");
    $objPHPExcel->getActiveSheet()->setCellValue('F8', "% Cumplimient");
    //--
    $objPHPExcel->getActiveSheet()->getStyle("A8:F8")->getFont()->setName('Arial'); /// Asigna tipo de letra
    $objPHPExcel->getActiveSheet()->getStyle("A8:F8")->getFont()->setSize($fontsize*2); /// Asigna tamaño de letra
    $objPHPExcel->getActiveSheet()->getStyle("A8:F8")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $objPHPExcel->getActiveSheet()->getStyle("A8:F8")->getFill()->getStartColor()->setARGB('C4C4C4');
    $objPHPExcel->getActiveSheet()->getStyle("A8:F8")->getFont()->setBold(true); /// Asigna negrita
    $objPHPExcel->getActiveSheet()->getStyle("A8:F8")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); /// alinea al  centro
    //--
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(7);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
    /////////////////////////////
	 /////////////////////////////        
	$linea_inicial = 8;
   $linea = 9;$ClsRev = new ClsRevision();
	$dia_inicio = "";
	$SI = 0;
	$NO = 0;
	$NA = 0;
	$total = 0;
	$num = 1;
	$TOTALSI = 0;
	$TOTALNO = 0;
	$TOTALNA = 0;
	if($periodo == "D"){
		$fechaInicio = strtotime(regresa_fecha($fini));
		$fechaFin = strtotime(regresa_fecha($ffin));
		for($i = $fechaInicio; $i <= $fechaFin; $i+=86400){
			$fecha = date("d/m/Y", $i);
			$dia = date("w", $i);
			$dia = ($dia == 0)?7:$dia;
			$dia_nombre = Dias_Letra($dia);
			$SI = $ClsRev->count_resultados('','','',$sede,$sector,$area,$categoria,$fecha,$fecha,$situacion,1);
			$NO = $ClsRev->count_resultados('','','',$sede,$sector,$area,$categoria,$fecha,$fecha,$situacion,2);
			$NA = $ClsRev->count_resultados('','','',$sede,$sector,$area,$categoria,$fecha,$fecha,$situacion,3);
			$total_si = $SI;
			$total_no = $NO;
			$total = $total_si + $total_no;
			if($total > 0){
				$porcentaje = round(($total_si*100)/$total);
			}else{
				$porcentaje = 0;
			}
			$TOTALSI+= $SI;
			$TOTALNO+= $NO;
			$TOTALNA+= $NA;
			//--
			$objPHPExcel->getActiveSheet()->setCellValue("A$linea", $num);
			$objPHPExcel->getActiveSheet()->setCellValue("B$linea", "$dia_nombre $fecha");
			$objPHPExcel->getActiveSheet()->setCellValue("C$linea", $SI);
			$objPHPExcel->getActiveSheet()->setCellValue("D$linea", $NO);
			$objPHPExcel->getActiveSheet()->setCellValue("E$linea", $NA);
			$objPHPExcel->getActiveSheet()->setCellValue("F$linea", "$porcentaje%");
			$objPHPExcel->getActiveSheet()->getStyle("C$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
			$objPHPExcel->getActiveSheet()->getStyle("D$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
			$objPHPExcel->getActiveSheet()->getStyle("E$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
			$objPHPExcel->getActiveSheet()->getStyle("F$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
			$num++;
			$linea++;
		}
	}else if($periodo == "S"){
		$fini = regresa_fecha($fini);
		$ffin = regresa_fecha($ffin);
		$anio1 = substr($fini, 0, 4);
		$anio2 = substr($ffin, 0, 4);
		if($anio1 == $anio2){
			$W1 = date("W", strtotime( date($fini) ));
			$W2 = date("W", strtotime( date($ffin) ));
			$dia_inicio = date("w", strtotime($fini));
			$num = 1;
			for($i = $W1; $i <= $W2; $i++){
				$fecha_ini = daysOfWeek($anio1,$i,1);
				$fecha_fin = daysOfWeek($anio1,($i+1),0);
				$fecha_ini = cambia_fecha($fecha_ini);
				$fecha_fin = cambia_fecha($fecha_fin);
				$SI = $ClsRev->count_resultados('','','',$sede,$sector,$area,$categoria,$fecha_ini,$fecha_fin,$situacion,1);
				$NO = $ClsRev->count_resultados('','','',$sede,$sector,$area,$categoria,$fecha_ini,$fecha_fin,$situacion,2);
				$NA = $ClsRev->count_resultados('','','',$sede,$sector,$area,$categoria,$fecha_ini,$fecha_fin,$situacion,3);
				$total_si = $SI;
				$total_no = $NO;
				$total = $total_si + $total_no;
				if($total > 0){
					$porcentaje = round(($total_si*100)/$total);
				}else{
					$porcentaje = 0;
				}
				$TOTALSI+= $SI;
				$TOTALNO+= $NO;
				$TOTALNA+= $NA;
				//--
				$objPHPExcel->getActiveSheet()->setCellValue("A$linea", $num);
				$objPHPExcel->getActiveSheet()->setCellValue("B$linea", "Semana $i ($fecha_ini al $fecha_fin)");
				$objPHPExcel->getActiveSheet()->setCellValue("C$linea", $SI);
				$objPHPExcel->getActiveSheet()->setCellValue("D$linea", $NO);
				$objPHPExcel->getActiveSheet()->setCellValue("E$linea", $NA);
				$objPHPExcel->getActiveSheet()->setCellValue("F$linea", "$porcentaje%");
            $objPHPExcel->getActiveSheet()->getStyle("C$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
				$objPHPExcel->getActiveSheet()->getStyle("D$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
				$objPHPExcel->getActiveSheet()->getStyle("E$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
				$objPHPExcel->getActiveSheet()->getStyle("F$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
				$num++;
				$linea++;
			}
		}
	}else if($periodo == "M"){
		$fini = regresa_fecha($fini);
		$ffin = regresa_fecha($ffin);
		$mes1 = substr($fini, 5, 2);
		$mes2 = substr($ffin, 5, 2);
		//--
		$anio1 = substr($fini, 0, 4);
		$anio2 = substr($ffin, 0, 4);
		if($anio1 == $anio2){
			$num = 1;
			for($i = $mes1; $i <= $mes2; $i++){
				$mes_nombre = Meses_Letra($i);
				$fecha_ini = "01/$i/$anio1";
				$fecha_fin = "31/$i/$anio1";
				//echo "$mes_nombre: $fecha_ini - $fecha_fin<br>";
				$SI = $ClsRev->count_resultados('','','',$sede,$sector,$area,$categoria,$fecha_ini,$fecha_fin,$situacion,1);
				$NO = $ClsRev->count_resultados('','','',$sede,$sector,$area,$categoria,$fecha_ini,$fecha_fin,$situacion,2);
				$NA = $ClsRev->count_resultados('','','',$sede,$sector,$area,$categoria,$fecha_ini,$fecha_fin,$situacion,3);
				$total_si = $SI;
				$total_no = $NO;
				$total = $total_si + $total_no;
				if($total > 0){
					$porcentaje = round(($total_si*100)/$total);
				}else{
					$porcentaje = 0;
				}
				$TOTALSI+= $SI;
				$TOTALNO+= $NO;
				$TOTALNA+= $NA;
				//--
				$objPHPExcel->getActiveSheet()->setCellValue("A$linea", $num);
				$objPHPExcel->getActiveSheet()->setCellValue("B$linea", "$mes_nombre");
				$objPHPExcel->getActiveSheet()->setCellValue("C$linea", $SI);
				$objPHPExcel->getActiveSheet()->setCellValue("D$linea", $NO);
				$objPHPExcel->getActiveSheet()->setCellValue("E$linea", $NA);
				$objPHPExcel->getActiveSheet()->setCellValue("F$linea", "$porcentaje%");
            $objPHPExcel->getActiveSheet()->getStyle("C$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
				$objPHPExcel->getActiveSheet()->getStyle("D$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
				$objPHPExcel->getActiveSheet()->getStyle("E$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
				$objPHPExcel->getActiveSheet()->getStyle("F$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
				$num++;
				$linea++;
			}
		}
	}//////////////// TOTALES DE TABLA /////////////////////////////////////////
	$TOTAL = $TOTALSI + $TOTALNO;
	if($TOTAL > 0){
		$PORCENTAJE = round(($TOTALSI*100)/$TOTAL);
	}else{
		$PORCENTAJE = 0;
	}
	$objPHPExcel->getActiveSheet()->setCellValue("A$linea", "");
	$objPHPExcel->getActiveSheet()->setCellValue("B$linea", " Totales");
	$objPHPExcel->getActiveSheet()->setCellValue("C$linea", $TOTALSI);
	$objPHPExcel->getActiveSheet()->setCellValue("D$linea", $TOTALNO);
	$objPHPExcel->getActiveSheet()->setCellValue("E$linea", $TOTALNA);
	$objPHPExcel->getActiveSheet()->setCellValue("F$linea", "$PORCENTAJE%");
	$objPHPExcel->getActiveSheet()->getStyle("C$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
	$objPHPExcel->getActiveSheet()->getStyle("D$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
	$objPHPExcel->getActiveSheet()->getStyle("E$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
	$objPHPExcel->getActiveSheet()->getStyle("F$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
	//--
	$objPHPExcel->getActiveSheet()->getStyle("A$linea:F$linea")->getFont()->setName('Arial'); /// Asigna tipo de letra
	$objPHPExcel->getActiveSheet()->getStyle("A$linea:F$linea")->getFont()->setSize($fontsize*2); /// Asigna tamaño de letra
	$objPHPExcel->getActiveSheet()->getStyle("A$linea:F$linea")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle("A$linea:F$linea")->getFill()->getStartColor()->setARGB('C4C4C4');
	$objPHPExcel->getActiveSheet()->getStyle("A$linea:F$linea")->getFont()->setBold(true); /// Asigna negrita
	//////////////////////////////////////////////////////////////////////////////////
	   // - ESTILOS DEL CUERPO DE LA TABLA - //
    // Alineacion
    $objPHPExcel->getActiveSheet()->getStyle("A$linea_inicial:A$linea")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle("C$linea_inicial:C$linea")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle("D$linea_inicial:D$linea")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle("E$linea_inicial:E$linea")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle("F$linea_inicial:F$linea")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
     
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
    $objPHPExcel->getActiveSheet()->getStyle("F$linea_inicial:F$linea")->applyFromArray($styleThinBlackBorderOutline);    // Pone el Recuadro sobre la tabla
    $styleThinBlackBorderOutline = array(
        'borders' => array(
            'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_THICK,
                'color' => array('argb' => 'FF000000'),
            ),
        ),
    );
    $celda_inicial = "A".$linea_inicial;
    $celda_final = "F".$linea;
    $objPHPExcel->getActiveSheet()->getStyle("A8:F8")->applyFromArray($styleThinBlackBorderOutline);
    $objPHPExcel->getActiveSheet()->getStyle("$celda_inicial:$celda_final")->applyFromArray($styleThinBlackBorderOutline);


// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle("Reporte de Resultados");

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