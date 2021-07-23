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
include_once('html_fns_ejecucion.php');
	validate_login("../");
$id = $_SESSION["codigo"];
	$nombre_sesion = utf8_decode($_SESSION["nombre"]);
	$rol = $_SESSION["rol"];
	$rol_nombre = utf8_decode($_SESSION["rol_nombre"]);
	$foto = $_SESSION["foto"];
	//$_POST
	$categoria = $_REQUEST["categoria"];
	$encuesta = $_REQUEST["encuesta"];
	$periodo = $_REQUEST["periodo"];
	$situacion = $_REQUEST["situacion"];
	//--
	$fini = $_REQUEST["desde"];
	$ffin = $_REQUEST["hasta"];if($periodo == "D"){
		$titulo = "Reporte dia a dia del $fini al $ffin";
	}else if($periodo == "S"){
		$titulo = "Reporte semana a semana del $fini al $ffin";
	}else if($periodo == "M"){
		$titulo = "Reporte mes a mes del $fini al $ffin";
	}$ClsEnc = new ClsEncuesta();
	$result = $ClsEnc->get_categoria($categoria,'',1);
	$categorias_nombre = "";
	if(is_array($result)){
		foreach($result as $row){
			$categorias_nombre.= utf8_decode($row["cat_nombre"]).", ";
		}
		$categorias_nombre = substr($categorias_nombre, 0, -2);
		$categorias_nombre = quita_tildes($categorias_nombre);
	}	
	
$file_desc = "Reporte Periodico";

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
    $objPHPExcel->getActiveSheet()->setCellValue('C8', "Cantidad de Respuestas");
    $objPHPExcel->getActiveSheet()->setCellValue('D8', "Promedio 1 a 10");
    $objPHPExcel->getActiveSheet()->setCellValue('E8', "Conteo de Si / No");
    //--
    $objPHPExcel->getActiveSheet()->getStyle("A8:E8")->getFont()->setName('Arial'); /// Asigna tipo de letra
    $objPHPExcel->getActiveSheet()->getStyle("A8:E8")->getFont()->setSize($fontsize*2); /// Asigna tamaño de letra
    $objPHPExcel->getActiveSheet()->getStyle("A8:E8")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $objPHPExcel->getActiveSheet()->getStyle("A8:E8")->getFill()->getStartColor()->setARGB('C4C4C4');
    $objPHPExcel->getActiveSheet()->getStyle("A8:E8")->getFont()->setBold(true); /// Asigna negrita
    $objPHPExcel->getActiveSheet()->getStyle("A8:E8")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); /// alinea al  centro
    //--
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(7);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    /////////////////////////////
	 /////////////////////////////        
	$linea_inicial = 8;
   $linea = 9;$ClsRes = new ClsEncuestaResolucion();
	$dia_inicio = "";
	$CANTIDAD = 0;
	$CANT = 0;
	$SUMA = 0;
	$PROMEDIO = 0;
	$SITOTAL = 0;
	$NOTOTAL = 0;
	if($periodo == "D"){
		$fechaInicio = strtotime(regresa_fecha($fini));
		$fechaFin = strtotime(regresa_fecha($ffin));
		for($i = $fechaInicio; $i <= $fechaFin; $i+=86400){
			$fecha = date("d/m/Y", $i);
			$dia = date("w", $i);
			$dia = ($dia == 0)?7:$dia;
			$dia_nombre = Dias_Letra($dia);
			$result = $ClsRes->get_ejecucion_respuestas('','','',$categoria,$fecha,$fecha,$situacion);
			$cantidad = 0;
			$cant = 0;
			$suma = 0;
			$promedio = 0;
			$siTotal = 0;
			$noTotal = 0;
			$sinoConteo = '';
			if(is_array($result)){
				foreach($result as $row){
					$tipo = trim($row["pre_tipo"]);
					if($tipo == 1){
						$nota = trim($row["resp_respuesta"]);
						$suma+= $nota;
						$cant++;
					}else if($tipo == 2){
						$respuesta = trim($row["resp_respuesta"]);
						if($respuesta == 1){
							$siTotal++;
						}else{
							$noTotal++;
						}
					}
					$cantidad++;
				}
				if($cant > 0){
					$promedio = $suma/$cant;
					$promedio = number_format($promedio,2,'.','');
				}
			}
			$promedio = ($promedio == 0 && $cant == 0)?"":$promedio;
			$sinoConteo = "SI ($siTotal), NO ($noTotal)";
			$CANTIDAD+=$cantidad;
			$CANT+= $cant;
			$SUMA+= $suma;
			$SITOTAL+= $siTotal;
			$NOTOTAL+= $noTotal;
			//--
			$objPHPExcel->getActiveSheet()->setCellValue("A$linea", $num);
			$objPHPExcel->getActiveSheet()->setCellValue("B$linea", "$dia_nombre $fecha");
			$objPHPExcel->getActiveSheet()->setCellValue("C$linea", $cantidad);
			$objPHPExcel->getActiveSheet()->setCellValue("D$linea", $promedio);
			$objPHPExcel->getActiveSheet()->setCellValue("E$linea", $sinoConteo);
			$objPHPExcel->getActiveSheet()->getStyle("C$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
			$objPHPExcel->getActiveSheet()->getStyle("D$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
			$objPHPExcel->getActiveSheet()->getStyle("E$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
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
				$result = $ClsRes->get_ejecucion_respuestas('',$encuesta,'',$categoria,$fecha_ini,$fecha_fin,$situacion);
				$cantidad = 0;
				$cant = 0;
				$suma = 0;
				$promedio = 0;
				$siTotal = 0;
				$noTotal = 0;
				$sinoConteo = '';
				if(is_array($result)){
					foreach($result as $row){
						$tipo = trim($row["pre_tipo"]);
						if($tipo == 1){
							$nota = trim($row["resp_respuesta"]);
							$suma+= $nota;
							$cant++;
						}else if($tipo == 2){
							$respuesta = trim($row["resp_respuesta"]);
							if($respuesta == 1){
								$siTotal++;
							}else{
								$noTotal++;
							}
						}
						$cantidad++;
					}
					if($cant > 0){
						$promedio = $suma/$cant;
						$promedio = number_format($promedio,2,'.','');
					}
				}
				$promedio = ($promedio == 0 && $cant == 0)?"":$promedio;
				$sinoConteo = "SI ($siTotal), NO ($noTotal)";
				$CANTIDAD+=$cantidad;
				$CANT+= $cant;
				$SUMA+= $suma;
				$SITOTAL+= $siTotal;
				$NOTOTAL+= $noTotal;
				//--
				$objPHPExcel->getActiveSheet()->setCellValue("A$linea", $num);
				$objPHPExcel->getActiveSheet()->setCellValue("B$linea", "Semana $i ($fecha_ini al $fecha_fin)");
				$objPHPExcel->getActiveSheet()->setCellValue("C$linea", $cantidad);
				$objPHPExcel->getActiveSheet()->setCellValue("D$linea", $promedio);
				$objPHPExcel->getActiveSheet()->setCellValue("E$linea", $sinoConteo);
				$objPHPExcel->getActiveSheet()->getStyle("C$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
				$objPHPExcel->getActiveSheet()->getStyle("D$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
				$objPHPExcel->getActiveSheet()->getStyle("E$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
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
				$result = $ClsRes->get_ejecucion_respuestas('',$encuesta,'',$categoria,$fecha_ini,$fecha_fin,$situacion);
				$cantidad = 0;
				$cant = 0;
				$suma = 0;
				$promedio = 0;
				$siTotal = 0;
				$noTotal = 0;
				$sinoConteo = '';
				if(is_array($result)){
					foreach($result as $row){
						$tipo = trim($row["pre_tipo"]);
						if($tipo == 1){
							$nota = trim($row["resp_respuesta"]);
							$suma+= $nota;
							$cant++;
						}else if($tipo == 2){
							$respuesta = trim($row["resp_respuesta"]);
							if($respuesta == 1){
								$siTotal++;
							}else{
								$noTotal++;
							}
						}
						$cantidad++;
					}
					if($cant > 0){
						$promedio = $suma/$cant;
						$promedio = number_format($promedio,2,'.','');
					}
				}
				$promedio = ($promedio == 0 && $cant == 0)?"":$promedio;
				$sinoConteo = "SI ($siTotal), NO ($noTotal)";
				$CANTIDAD+=$cantidad;
				$CANT+= $cant;
				$SUMA+= $suma;
				$SITOTAL+= $siTotal;
				$NOTOTAL+= $noTotal;
				//--
				$objPHPExcel->getActiveSheet()->setCellValue("A$linea", $num);
				$objPHPExcel->getActiveSheet()->setCellValue("B$linea", "$mes_nombre");
				$objPHPExcel->getActiveSheet()->setCellValue("C$linea", $cantidad);
				$objPHPExcel->getActiveSheet()->setCellValue("D$linea", $promedio);
				$objPHPExcel->getActiveSheet()->setCellValue("E$linea", $sinoConteo);
				$objPHPExcel->getActiveSheet()->getStyle("C$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
				$objPHPExcel->getActiveSheet()->getStyle("D$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
				$objPHPExcel->getActiveSheet()->getStyle("E$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
				$num++;
				$linea++;
			}
		}
	}//////////////// TOTALES DE TABLA ///////////////////
	if($CANT > 0){
		$PROMEDIO = $SUMA/$CANT;
		$PROMEDIO = number_format($PROMEDIO,2,'.','');
	}
	$SINOCONTEO = "SI ($SITOTAL), NO ($NOTOTAL)";
	$objPHPExcel->getActiveSheet()->setCellValue("A$linea", "");
	$objPHPExcel->getActiveSheet()->setCellValue("B$linea", " Totales");
	$objPHPExcel->getActiveSheet()->setCellValue("C$linea", $CANTIDAD);
	$objPHPExcel->getActiveSheet()->setCellValue("D$linea", $PROMEDIO);
	$objPHPExcel->getActiveSheet()->setCellValue("E$linea", $SINOCONTEO);
	$objPHPExcel->getActiveSheet()->getStyle("C$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
	$objPHPExcel->getActiveSheet()->getStyle("D$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
	//--
	$objPHPExcel->getActiveSheet()->getStyle("A$linea:E$linea")->getFont()->setName('Arial'); /// Asigna tipo de letra
	$objPHPExcel->getActiveSheet()->getStyle("A$linea:E$linea")->getFont()->setSize($fontsize*2); /// Asigna tamaño de letra
	$objPHPExcel->getActiveSheet()->getStyle("A$linea:E$linea")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle("A$linea:E$linea")->getFill()->getStartColor()->setARGB('C4C4C4');
	$objPHPExcel->getActiveSheet()->getStyle("A$linea:E$linea")->getFont()->setBold(true); /// Asigna negrita
	//////////////////////////////////////////////////////////////////////////////////
	   // - ESTILOS DEL CUERPO DE LA TABLA - //
    // Alineacion
    $objPHPExcel->getActiveSheet()->getStyle("A$linea_inicial:A$linea")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle("C$linea_inicial:C$linea")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle("D$linea_inicial:D$linea")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle("E$linea_inicial:E$linea")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
     
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
    $objPHPExcel->getActiveSheet()->getStyle("E$linea_inicial:E$linea")->applyFromArray($styleThinBlackBorderOutline);    // Pone el Recuadro sobre la tabla
    $styleThinBlackBorderOutline = array(
        'borders' => array(
            'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_THICK,
                'color' => array('argb' => 'FF000000'),
            ),
        ),
    );
    $celda_inicial = "A".$linea_inicial;
    $celda_final = "E".$linea;
    $objPHPExcel->getActiveSheet()->getStyle("A8:E8")->applyFromArray($styleThinBlackBorderOutline);
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