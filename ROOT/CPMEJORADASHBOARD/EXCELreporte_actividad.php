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
include_once('html_fns_dashboard.php');
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
$tipo = $_REQUEST["tipo"];
$desde = $_REQUEST["desde"];
$hasta = $_REQUEST["hasta"];
$columnas = $_REQUEST["columnas"];

$titulo = "Reporte de Actividades";

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
// Plan de Riesgo Aprobado
$ClsPla = new ClsPlan();
$ClsAct = new ClsActividad();
$ClsHal = new ClsHallazgo();
$planes = $ClsPla->get_plan_mejora("", "", $usuario);
if (is_array($planes)) {
	$i = 1;
	foreach ($planes as $rowPlan) {
		$plan = trim($rowPlan["pla_codigo"]);
		$origen = trim($rowPlan["hal_origen"]);
		$hallazgo = trim($rowPlan["pla_hallazgo"]);
		switch ($origen) {
			case 1:
				$info = $ClsHal->get_hallazgo_auditoria_interna($hallazgo, "", $procesoFiltro, $sistemaFiltro);
				break;
			case 2:
				$info = $ClsHal->get_hallazgo_auditoria_externa($hallazgo, "", $procesoFiltro, $sistemaFiltro);
				break;
			case 3:
				$info = $ClsHal->get_hallazgo_queja($hallazgo, "", $procesoFiltro, $sistemaFiltro);
				break;
			case 4:
				$info = $ClsHal->get_hallazgo_indicador($hallazgo, "", $procesoFiltro, $sistemaFiltro);
				break;
			case 5:
				$info = $ClsHal->get_hallazgo_riesgo($hallazgo, "", $procesoFiltro, $sistemaFiltro);
				break;
			case 6:
				//$result = $ClsHal->get_hallazgo_queja();
				break;
		}
		if (is_array($info)) {
			foreach ($info as $row) {
				$proceso_nombre = trim($row["fic_nombre"]);
				$sistema_nombre = trim($row["sis_nombre"]);
				$descripcion = trim($row["hal_descripcion"]);
				$actividades = $ClsAct->get_actividad_mejora("", $plan, $tipo, "", "", "", $desde, $hasta);
				if (is_array($actividades)) {
					foreach ($actividades as $row) {
						$objPHPExcel->getActiveSheet()->setCellValue("A" . $linea, $i . ".");
						$j = 2;
						//--
						if (is_array($columnas)) {
							foreach ($columnas as $col) {
								$parametros = parametrosDinamicosEXCEL($col);
								$campo = $parametros['campo'];
								$letra = LetrasBase($j);
								if ($col == "act_codigo") {
									$campo = '# ' . Agrega_Ceros($row[$campo]);
									$objPHPExcel->getActiveSheet()->setCellValue($letra . $linea, trim($campo));
								} else if ($col == "act_tipo") {
									$campo = trim($row[$campo]);
									switch ($campo) {
										case 1:
											$campo = "Plan de Acción";
											break;
										case 2:
											$campo = "Plan Inmediato";
											break;
									}
									$objPHPExcel->getActiveSheet()->setCellValue($letra . $linea, trim($campo));
								} else if ($col == "act_fecha_inicio" || $col == "act_fecha_fin" || $col == "pla_fecha_creacion"  || $col == "pla_fecha_revision") {
									if ($row[$campo] == "0000-00-00") $campo = "Sin Fecha";
									else $campo = cambia_fecha($row[$campo]);
									$objPHPExcel->getActiveSheet()->setCellValue($letra . $linea, trim($campo));
								} else if ($col == "pla_situacion") {
									switch ($row[$campo]) {
										case 1:
											$campo = "En edición";
											break;
										case 2:
											$campo = "En Aprobación";
											break;
										case 3:
											$campo = "Aprobado";
											break;
									}
									$objPHPExcel->getActiveSheet()->setCellValue($letra . $linea, trim($campo));
								} else if ($col == "act_periodicidad") {
									$campo = trim($row[$campo]);
									switch ($campo) {
										case "W":
											$campo = "Semanal";
											break;
										case "M":
											$campo = "Mensual";
											break;
										case "U":
											$campo = "Única";
											break;
									}
									$objPHPExcel->getActiveSheet()->setCellValue($letra . $linea, trim($campo));
								} else if ($col == "pro_situacion") {
									$campo = trim($row[$campo]);
									switch ($campo) {
										case 0:
											$campo = "Cancelada";
											break;
										case 1:
											$campo = "Pendiente";
											break;
										case 2:
											$campo = "En Proceso";
											break;
										case 3:
											$campo = "Ejecutada";
											break;
										case 4:
											$campo = "En Evaluación";
											break;
										case 5:
											$campo = "Finalizada";
											break;
									}
									$objPHPExcel->getActiveSheet()->setCellValue($letra . $linea, trim($campo));
								} else if ($col == "pro_puntuacion") {
									$campo = (trim($row[$campo]) == 0) ? "Sin Puntuar" : trim($row[$campo]);
									$objPHPExcel->getActiveSheet()->setCellValue($letra . $linea, trim($campo));
								} else if ($col == "pro_fecha" || $col == "pro_fecha_evaluacion") {
									$campo = ($row[$campo] == 0) ? "Sin Fecha" : cambia_fecha($row[$campo]);
									$objPHPExcel->getActiveSheet()->setCellValue($letra . $linea, trim($campo));
								} else if ($col == "pro_fecha_inicio" || $col == "pro_fecha_fin") {
									$campo = cambia_fecha($row[$campo]);
									$objPHPExcel->getActiveSheet()->setCellValue($letra . $linea, trim($campo));
								} else if ($col == "fic_nombre") {
									$campo = $proceso_nombre;
									$objPHPExcel->getActiveSheet()->setCellValue($letra . $linea, trim($campo));
								} else if ($col == "sis_nombre") {
									$campo = $sistema_nombre;
									$objPHPExcel->getActiveSheet()->setCellValue($letra . $linea, trim($campo));
								} else if ($col == "hal_descripcion") {
									$campo = $descripcion;
									$objPHPExcel->getActiveSheet()->setCellValue($letra . $linea, trim($campo));
								} else {
									$campo = trim($row[$campo]);
									$objPHPExcel->getActiveSheet()->setCellValue($letra . $linea, trim($campo));
								}
								$j++;
							}
						}
						$linea++;
						$i++;
					}
				}
			}
		}
	}
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
