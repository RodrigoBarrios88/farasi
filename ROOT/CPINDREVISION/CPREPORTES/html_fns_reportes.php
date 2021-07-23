<?php
include_once('../../html_fns.php');

function detalle_programacion($accion, $tipo)
{
	$ClsAcc = new ClsAccion();
	$result = $ClsAcc->get_programacion('', $accion);

	if (is_array($result)) {
		$salida = '<table class="table" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">No.</th>';
		$salida .= '<th class = "text-center" width = "150px">Fecha de Inicio</th>';
		$salida .= '<th class = "text-center" width = "150px">Fecha Final</th>';
		$salida .= '<th class = "text-center" width = "150px">D&iacute;a Planificado</th>';
		$salida .= '<th class = "text-center" width = "150px">&Uacute;ltimo D&iacute;a</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//No.
			$salida .= '<td class = "text-center">' . $i . '.</td>';
			// Fecha Inicial
			$fini = trim($row["pro_fecha_inicio"]);
			$salida .= '<td class = "text-left">' . $fini . '</td>';
			// Fecha Final
			$ffin = trim($row["pro_fecha_fin"]);
			$salida .= '<td class = "text-left">' . $ffin . '</td>';
			// Dia Inicial
			$dini = day_name(trim($row["pro_dia_inicio"]), $tipo);
			$salida .= '<td class = "text-left">' . $dini . '</td>';
			// Fecha Inicial
			$dfin = day_name(trim($row["pro_dia_fin"]), $tipo);
			$salida .= '<td class = "text-left">' . $dfin . '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}

	return $salida;
}

function get_archivos($numero, $codigo)
{
	// Si no existe ningun archivo me devuelve false en la posicion 0
	$ClsRev = new ClsRevision();
	$evidencia = false;
	for ($i = 1; $i <= $numero; $i++) {
		$result = $ClsRev->get_archivo('', $codigo, $i);
		if (is_array($result)) {
			foreach ($result as $row) {
				$strArchivo = trim($row["arc_archivo"]);
				if (file_exists('../../../CONFIG/Fotos/PLANNING/' . $strArchivo . '.jpg')) {
					$strArchivo = '../../../CONFIG/Fotos/PLANNING/' . $strArchivo . '.jpg';
					$evidencia = true;
				} else if (file_exists('../../../CONFIG/Archivos/PLANNING/' . $strArchivo . '.pdf')) {
					$strArchivo = "../../../CONFIG/img/document.png";
					$evidencia = true;
				} else {
					$strArchivo = "../../../CONFIG/img/imagePhoto.jpg";
				}
			}
		} else {
			$strArchivo = '../../../CONFIG/img/imagePhoto.jpg';
		}
		$arrArchivos[$i] = $strArchivo;
	}
	$arrArchivos[0] = $evidencia;
	return $arrArchivos;
}

function day_name($day, $tipo)
{
	if ($tipo == "W") {
		switch ($day) {
			case 1:
				return "Lunes";
			case 2:
				return "Martes";
			case 3:
				return "Miercoles";
			case 4:
				return "Jueves";
			case 5:
				return "Viernes";
			case 6:
				return "Sabado";
			case 7:
				return "Domingo";
			default:
				return "Domingo";
		}
	} else {
		return "D&iacute;a $day del Mes";
	}
}
