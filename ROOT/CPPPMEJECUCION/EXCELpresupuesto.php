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
	$nombre_sesion = trim($_SESSION["nombre"]);
	$rol = $_SESSION["rol"];
	$rol_nombre = trim($_SESSION["rol_nombre"]);
	$foto = $_SESSION["foto"];
	//$_POST
	$sede = $_REQUEST["sede"];
	$area = $_REQUEST["area"];
	$activo = $_REQUEST["activo"];
	$usuario = $_REQUEST["usuario"];
	$periodo = $_REQUEST["periodo"];
	$fini = $_REQUEST["desde"];
	$ffin = $_REQUEST["hasta"];
	//if($periodo == "A"){
		$titulo = "Reporte actividad por actividad del $fini al $ffin";
	}else if($periodo == "D"){
		$titulo = "Reporte dia a dia del $fini al $ffin";
	}else if($periodo == "S"){
		$titulo = "Reporte semana a semana del $fini al $ffin";
	}else if($periodo == "M"){
		$titulo = "Reporte mes a mes del $fini al $ffin";
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
	$objPHPExcel->setActiveSheetIndex(0);/// Combinacion de Celdas (Merge cells)
	$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
	$objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
	$objPHPExcel->getActiveSheet()->mergeCells('A3:F3');
	$objPHPExcel->getActiveSheet()->mergeCells('A4:F4');
	$objPHPExcel->getActiveSheet()->mergeCells('A5:F5');
	$objPHPExcel->getActiveSheet()->mergeCells('A6:F6');
	if($periodo == "A"){
	$objPHPExcel->getActiveSheet()->mergeCells('A7:F7');
	$objPHPExcel->getActiveSheet()->mergeCells('A7:F7');
	}	//Seteo de Titulos
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
	$objPHPExcel->getActiveSheet()->getStyle("A1")->getFill()->getStartColor()->setARGB('C4C4C4');///// LOGO
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
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());////// ENCABEZADOS /////
	if($periodo == "A"){ ///// Actividad por actividad
		$objPHPExcel->getActiveSheet()->setCellValue('A8', "No.");
		$objPHPExcel->getActiveSheet()->setCellValue('B8', "Número de Programación");
		$objPHPExcel->getActiveSheet()->setCellValue('C8', "Categoría");
		$objPHPExcel->getActiveSheet()->setCellValue('D8', "Activo");
		$objPHPExcel->getActiveSheet()->setCellValue('E8', "Presupuestado");
		$objPHPExcel->getActiveSheet()->setCellValue('F8', "Ejecutado");
		$objPHPExcel->getActiveSheet()->setCellValue('G8', "Diferencia");
		$objPHPExcel->getActiveSheet()->setCellValue('H8', "%");
		//--
		$objPHPExcel->getActiveSheet()->getStyle("A8:H8")->getFont()->setName('Arial'); /// Asigna tipo de letra
		$objPHPExcel->getActiveSheet()->getStyle("A8:H8")->getFont()->setSize($fontsize*2); /// Asigna tamaño de letra
		$objPHPExcel->getActiveSheet()->getStyle("A8:H8")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle("A8:H8")->getFill()->getStartColor()->setARGB('C4C4C4');
		$objPHPExcel->getActiveSheet()->getStyle("A8:H8")->getFont()->setBold(true); /// Asigna negrita
		$objPHPExcel->getActiveSheet()->getStyle("A8:H8")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); /// alinea al  centro
		//--
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(7);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
	}else{
		$objPHPExcel->getActiveSheet()->setCellValue('A8', "No.");
		$objPHPExcel->getActiveSheet()->setCellValue('B8', "");
		$objPHPExcel->getActiveSheet()->setCellValue('C8', "Presupuestado");
		$objPHPExcel->getActiveSheet()->setCellValue('D8', "Ejecutado");
		$objPHPExcel->getActiveSheet()->setCellValue('E8', "Diferencia");
		$objPHPExcel->getActiveSheet()->setCellValue('F8', "%");
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
	}
	

	/////////////////////////////
	/////////////////////////////        
	$linea_inicial = 8;
    $linea = 9;$ClsPro = new ClsProgramacionPPM();
	$num = 1;
	$dia_inicio = "";
	$PROGRAMADO = 0;
	$EJECUTADO = 0;
	$DIFERENCIA = 0;
	//--
	if($periodo == "A"){
		$result = $ClsPro->get_programacion('',$activo,$usuario,$categoria,$sede, '', $area, $fini, $ffin,'','',4);
		$programado = 0;
		$ejecutado = 0;
		$diferencia = 0;
		$porcentaje = 0;
		$signo = "";
		if(is_array($result)){
			$num = 1;
			foreach($result as $row){
				$programado = 0;
				$ejecutado = 0;
				$codigo = Agrega_Ceros($row["pro_codigo"]);
				$fecha = cambia_fecha($row["pro_fecha"]);
				//--
				$programado = cambioMoneda($row["mon_cambio"], 1, $row["pro_presupuesto_programado"]); //realiza conversion de tipo de cambio
				$ejecutado = cambioMoneda($row["mon_cambio"], 1, $row["pro_presupuesto_ejecutado"]); //realiza conversion de tipo de cambio
				$diferencia = $programado - $ejecutado;
				if($diferencia != 0){
					if($diferencia > 0){
						$porcentaje = round(($ejecutado*100)/$programado);
						$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecución
						$signo = "-";
					}else{
						$porcentaje = round(($programado*100)/$ejecutado);
						$diferencia = ($diferencia * -1);
						$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecución
						$signo = "+";
					}
				}else{
					$porcentaje = 0;
					$signo = "";
				}
				$PROGRAMADO+=$programado;
				$EJECUTADO+=$ejecutado;
				$DIFERENCIA+=$DIFERENCIA;
				//categoria
				$categoria_nombre = trim($row["cat_nombre"]);
				//activo
				$activo_nombre = trim($row["act_nombre"]);
				//--
				$objPHPExcel->getActiveSheet()->setCellValue("A$linea", $num);
				$objPHPExcel->getActiveSheet()->setCellValue("B$linea", "Programación #$codigo - $fecha");
				$objPHPExcel->getActiveSheet()->setCellValue("C$linea", "$categoria_nombre");
				$objPHPExcel->getActiveSheet()->setCellValue("D$linea", "$activo_nombre");
				$objPHPExcel->getActiveSheet()->setCellValue("E$linea", "Q.".number_format($programado, 2, '.', ','));
				$objPHPExcel->getActiveSheet()->setCellValue("F$linea", "Q.".number_format($ejecutado, 2, '.', ','));
				$objPHPExcel->getActiveSheet()->setCellValue("G$linea", $signo." Q.".number_format($diferencia, 2, '.', ','));
				$objPHPExcel->getActiveSheet()->setCellValue("H$linea", "$porcentaje%");
				$objPHPExcel->getActiveSheet()->getStyle("H$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
				$num++;
				$linea++;
			}
		}
		
	}else if($periodo == "D"){
		$fechaInicio = strtotime(regresa_fecha($fini));
		$fechaFin = strtotime(regresa_fecha($ffin));
		for($i = $fechaInicio; $i <= $fechaFin; $i+=86400){
			$fecha = date("d/m/Y", $i);
			$dia = date("w", $i);
			$dia = ($dia == 0)?7:$dia;
			$dia_nombre = Dias_Letra($dia);
			$result = $ClsPro->get_programacion('',$activo,$usuario,$categoria,$sede, '', $area, $fecha, $fecha,'','',4);
			$programado = 0;
			$ejecutado = 0;
			$diferencia = 0;
			$porcentaje = 0;
			$signo = "";
			if(is_array($result)){
				foreach($result as $row){
					$programado+= cambioMoneda($row["mon_cambio"], 1, $row["pro_presupuesto_programado"]); //realiza conversion de tipo de cambio
					$ejecutado+= cambioMoneda($row["mon_cambio"], 1, $row["pro_presupuesto_ejecutado"]); //realiza conversion de tipo de cambio
				}
			}
			$diferencia = $programado - $ejecutado;
			if($diferencia != 0){
				if($diferencia > 0){
					$porcentaje = round(($ejecutado*100)/$programado);
					$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecución
					$signo = "-";
				}else{
					$porcentaje = round(($programado*100)/$ejecutado);
					$diferencia = ($diferencia * -1);
					$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecución
					$signo = "+";
				}
			}else{
				$porcentaje = 0;
				$signo = "";
			}
			$PROGRAMADO+=$programado;
			$EJECUTADO+=$ejecutado;
			$DIFERENCIA+=$DIFERENCIA;
			//--
			$objPHPExcel->getActiveSheet()->setCellValue("A$linea", $num);
			$objPHPExcel->getActiveSheet()->setCellValue("B$linea", "$dia_nombre - $fecha");
			$objPHPExcel->getActiveSheet()->setCellValue("C$linea", "Q.".number_format($programado, 2, '.', ','));
			$objPHPExcel->getActiveSheet()->setCellValue("D$linea", "Q.".number_format($ejecutado, 2, '.', ','));
			$objPHPExcel->getActiveSheet()->setCellValue("E$linea", $signo." Q.".number_format($diferencia, 2, '.', ','));
			$objPHPExcel->getActiveSheet()->setCellValue("F$linea", "$porcentaje%");
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
				$result = $ClsPro->get_programacion('',$activo,$usuario,$categoria,$sede, '', $area, $fecha_ini, $fecha_fin,'','',4);
				$programado = 0;
				$ejecutado = 0;
				$diferencia = 0;
				$porcentaje = 0;
				$signo = "";
				if(is_array($result)){
					foreach($result as $row){
						$programado+= cambioMoneda($row["mon_cambio"], 1, $row["pro_presupuesto_programado"]); //realiza conversion de tipo de cambio
						$ejecutado+= cambioMoneda($row["mon_cambio"], 1, $row["pro_presupuesto_ejecutado"]); //realiza conversion de tipo de cambio
					}
				}
				$diferencia = $programado - $ejecutado;
				if($diferencia != 0){
					if($diferencia > 0){
						$porcentaje = round(($ejecutado*100)/$programado);
						$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecución
						$signo = "-";
					}else{
						$porcentaje = round(($programado*100)/$ejecutado);
						$diferencia = ($diferencia * -1);
						$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecución
						$signo = "+";
					}
				}else{
					$porcentaje = 0;
					$signo = "";
				}
				$PROGRAMADO+=$programado;
				$EJECUTADO+=$ejecutado;
				$DIFERENCIA+=$DIFERENCIA;
				//--
				$objPHPExcel->getActiveSheet()->setCellValue("A$linea", $num);
				$objPHPExcel->getActiveSheet()->setCellValue("B$linea", "Semana $i ($fecha_ini al $fecha_fin)");
				$objPHPExcel->getActiveSheet()->setCellValue("C$linea", "Q.".number_format($programado, 2, '.', ','));
				$objPHPExcel->getActiveSheet()->setCellValue("D$linea", "Q.".number_format($ejecutado, 2, '.', ','));
				$objPHPExcel->getActiveSheet()->setCellValue("E$linea", $signo." Q.".number_format($diferencia, 2, '.', ','));
				$objPHPExcel->getActiveSheet()->setCellValue("F$linea", "$porcentaje%");
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
				$result = $ClsPro->get_programacion('',$activo,$usuario,$categoria,$sede, '', $area, $fecha_ini, $fecha_fin,'','',4);
				$programado = 0;
				$ejecutado = 0;
				$diferencia = 0;
				$porcentaje = 0;
				$signo = "";
				if(is_array($result)){
					foreach($result as $row){
						$programado+= cambioMoneda($row["mon_cambio"], 1, $row["pro_presupuesto_programado"]); //realiza conversion de tipo de cambio
						$ejecutado+= cambioMoneda($row["mon_cambio"], 1, $row["pro_presupuesto_ejecutado"]); //realiza conversion de tipo de cambio
					}
				}
				$diferencia = $programado - $ejecutado;
				if($diferencia != 0){
					if($diferencia > 0){
						$porcentaje = round(($ejecutado*100)/$programado);
						$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecución
						$signo = "-";
					}else{
						$porcentaje = round(($programado*100)/$ejecutado);
						$diferencia = ($diferencia * -1);
						$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecución
						$signo = "+";
					}
				}else{
					$porcentaje = 0;
					$signo = "";
				}
				$PROGRAMADO+=$programado;
				$EJECUTADO+=$ejecutado;
				$DIFERENCIA+=$DIFERENCIA;
				//--
				$objPHPExcel->getActiveSheet()->setCellValue("A$linea", $num);
				$objPHPExcel->getActiveSheet()->setCellValue("B$linea", "$mes_nombre");
				$objPHPExcel->getActiveSheet()->setCellValue("C$linea", "Q.".number_format($programado, 2, '.', ','));
				$objPHPExcel->getActiveSheet()->setCellValue("D$linea", "Q.".number_format($ejecutado, 2, '.', ','));
				$objPHPExcel->getActiveSheet()->setCellValue("E$linea", $signo." Q.".number_format($diferencia, 2, '.', ','));
				$objPHPExcel->getActiveSheet()->setCellValue("F$linea", "$porcentaje%");
				$objPHPExcel->getActiveSheet()->getStyle("F$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
				$num++;
				$linea++;
			}
		}
	}
	//////////////// TOTALES DE TABLA ///////////////////
	$DIFERENCIA = $PROGRAMADO - $EJECUTADO;
	if($DIFERENCIA != 0){
		IF($DIFERENCIA > 0){
			$PORCENTAJE = ROUND(($EJECUTADO*100)/$PROGRAMADO);
			$PORCENTAJE = (100 - $PORCENTAJE); // SE RESTA ENTRE 100 PARA OBTENER EL PORCENTAJE DE AHORRO, NO EL DE EJECUCIÓN
			$SIGNO = "-";
		}else{
			$PORCENTAJE = ROUND(($PROGRAMADO*100)/$EJECUTADO);
			$DIFERENCIA = ($DIFERENCIA * -1);
			$PORCENTAJE = (100 - $PORCENTAJE); // SE RESTA ENTRE 100 PARA OBTENER EL PORCENTAJE DE AHORRO, NO EL DE EJECUCIÓN
			$SIGNO = "+";
		}
	}else{
		$PORCENTAJE = 0;
		$SIGNO = "";
	}if($periodo == "A"){ //// Actividad por actividad
		$objPHPExcel->getActiveSheet()->setCellValue("A$linea", "");
		$objPHPExcel->getActiveSheet()->setCellValue("B$linea", "");
		$objPHPExcel->getActiveSheet()->setCellValue("C$linea", "");
		$objPHPExcel->getActiveSheet()->setCellValue("D$linea", " Totales");
		$objPHPExcel->getActiveSheet()->setCellValue("E$linea", "Q.".number_format($PROGRAMADO, 2, '.', ','));
		$objPHPExcel->getActiveSheet()->setCellValue("F$linea", "Q.".number_format($EJECUTADO, 2, '.', ','));
		$objPHPExcel->getActiveSheet()->setCellValue("G$linea", $SIGNO." Q.".number_format($DIFERENCIA, 2, '.', ','));
		$objPHPExcel->getActiveSheet()->setCellValue("H$linea", "$SIGNO $PORCENTAJE%");
		$objPHPExcel->getActiveSheet()->getStyle("H$linea")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
		//--
		$objPHPExcel->getActiveSheet()->getStyle("A$linea:H$linea")->getFont()->setName('Arial'); /// Asigna tipo de letra
		$objPHPExcel->getActiveSheet()->getStyle("A$linea:H$linea")->getFont()->setSize($fontsize*2); /// Asigna tamaño de letra
		$objPHPExcel->getActiveSheet()->getStyle("A$linea:H$linea")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle("A$linea:H$linea")->getFill()->getStartColor()->setARGB('C4C4C4');
		$objPHPExcel->getActiveSheet()->getStyle("A$linea:H$linea")->getFont()->setBold(true); /// Asigna negrita
		//////////////////////////////////////////////////////////////////////////////////
		// - ESTILOS DEL CUERPO DE LA TABLA - //
		// Alineacion
		$objPHPExcel->getActiveSheet()->getStyle("A$linea_inicial:A$linea")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("E$linea_inicial:E$linea")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("F$linea_inicial:F$linea")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("G$linea_inicial:G$linea")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle("H$linea_inicial:H$linea")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
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
		$objPHPExcel->getActiveSheet()->getStyle("G$linea_inicial:H$linea")->applyFromArray($styleThinBlackBorderOutline);
		$objPHPExcel->getActiveSheet()->getStyle("G$linea_inicial:H$linea")->applyFromArray($styleThinBlackBorderOutline);
		
		// Pone el Recuadro sobre la tabla
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
	}else{ //// OTros reprotes
		$objPHPExcel->getActiveSheet()->setCellValue("A$linea", "");
		$objPHPExcel->getActiveSheet()->setCellValue("B$linea", " Totales");
		$objPHPExcel->getActiveSheet()->setCellValue("C$linea", "Q.".number_format($PROGRAMADO, 2, '.', ','));
		$objPHPExcel->getActiveSheet()->setCellValue("D$linea", "Q.".number_format($EJECUTADO, 2, '.', ','));
		$objPHPExcel->getActiveSheet()->setCellValue("E$linea", $SIGNO." Q.".number_format($DIFERENCIA, 2, '.', ','));
		$objPHPExcel->getActiveSheet()->setCellValue("F$linea", "$SIGNO $PORCENTAJE%");
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
		$objPHPExcel->getActiveSheet()->getStyle("F$linea_inicial:F$linea")->applyFromArray($styleThinBlackBorderOutline);
		
		// Pone el Recuadro sobre la tabla
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
	}	

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle("Reporte de Presupuesto");

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