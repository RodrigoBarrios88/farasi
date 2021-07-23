<?php
include_once('../html_fns.php');

function tabla_indicadores($codigo_filtro, $proceso, $sistema_filtro, $usuario)
{
	$ClsInd = new ClsIndicador();
	$ClsFic = new ClsFicha();
	$asignadas = $ClsFic->get_ficha_usuario("", $proceso, $usuario);
	if (is_array($asignadas)) {
		$salida = '<table id="tabla" class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		// $salida .= '<th class = "text-center" width = "100px"><i class="fa fa-cogs"></i></th>';
		$salida .= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida .= '<th class = "text-left" width = "75px">Nombre</th>';
		$salida .= '<th class = "text-left" width = "75px">Proceso</th>';
		$salida .= '<th class = "text-left" width = "75px">Sistema</th>';
		$salida .= '<th class = "text-left" width = "75px">Unidad de Medida</th>';
		$salida .= '<th class = "text-center" width = "40px">Programaci&oacute;n/Detalle</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 0;
		foreach ($asignadas as $rowFicha) {
			$proceso = $rowFicha["fic_codigo"];
			$result = $ClsInd->get_indicador($codigo_filtro, "", $proceso, $sistema_filtro, "", "", "", 1);
			foreach ($result as $row) {
				$salida .= '<tr>';
				//codigo
				$codigo = $row["ind_codigo"];
				// $salida .= '<td class = "text-center" >';
				// $salida .= '<div class="btn-group">';
				// $salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarIndicador(' . $codigo . ');" title = "Editar Indicador" ><i class="fa fa-pencil"></i></button>';
				// $salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarIndicador(' . $codigo . ');" title = "Eliminar Indicador" ><i class="fa fa-trash"></i></button>';
				// $salida .= '</div>';
				// $salida .= '</td>';
				//codigo
				$codigo = Agrega_Ceros($row["ind_codigo"]);
				$salida .= '<td class = "text-center">' . $codigo . '</td>';
				//nombre
				$nombre = trim($row["ind_nombre"]);
				$salida .= '<td class = "text-left">' . $nombre . '</td>';
				//proceso
				$proceso = trim($row["obj_proceso"]);
				$salida .= '<td class = "text-left">' . $proceso . '</td>';
				//sistema
				$sistema = trim($row["obj_sistema"]);
				$salida .= '<td class = "text-left">' . $sistema . '</td>';
				//umed
				$umed = trim($row["medida_nombre"]);
				$salida .= '<td class = "text-left">' . $umed . '</td>';
				//codigo
				$codigo = $row["ind_codigo"];
				validate_login("../");
				$id = $_SESSION["codigo"];
				$hashkey = $ClsInd->encrypt($codigo, $id);
				$salida .= '<td class = "text-center" >';
				$salida .= '<div class="btn-group">';
				$salida .= '<a type="button" class="btn btn-info btn-xs" href = "FRMhorarios.php?hashkey=' . $hashkey . '" title = "Ver Programaci&oacute;n" ><span class="fa fa-calendar"></span></a>';
				$salida .= '<button type="button" class="btn btn-info btn-xs" onclick = "detalle(' . $codigo . ');" title = "Ver Detalles" ><span class="fa fa-search"></span></button>';
				$salida .= '</div>';
				$salida .= '</td>';
				//--
				$salida .= '</tr>';
				$i++;
			}
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}

	return $salida;
}

function get_dias($dia1, $dia2, $dia3, $dia4, $dia5, $dia6, $dia7)
{
	$salida = "";
	if ($dia1) $salida .= "Lunes, ";
	if ($dia2) $salida .= "Martes, ";
	if ($dia3) $salida .= "Miercoles, ";
	if ($dia4) $salida .= "Jueves, ";
	if ($dia5) $salida .= "Viernes, ";
	if ($dia6) $salida .= "Sabado, ";
	if ($dia7) $salida .= "Domingo, ";
	return substr($salida, 0, strlen($salida) - 2);
}

function tabla_programacion($codigo, $indicador, $usuario)
{
	$ClsInd = new ClsIndicador();
	$result = $ClsInd->get_programacion($codigo, $indicador, "", "", "", "", "", $usuario);

	if (is_array($result)) {
		$salida = '<table id="tabla" class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px"><i class="fa fa-cogs"></i></th>';
		$salida .= '<th class = "text-left" width = "50px">Horario</th>';
		$salida .= '<th class = "text-left" width = "50px">Fecha</th>';
		$salida .= '<th class = "text-left" width = "50px">Tipo</th>';
		$salida .= '<th class = "text-left" width = "100px">Observaci&oacute;n</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 0;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = $row["pro_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsInd->encrypt($codigo, $usu);
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			// $salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarProgramacion(\'' . $hashkey . '\');" title = "Editar Programacion" ><i class="fa fa-pencil"></i></button>';
			$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarProgramacion(' . $codigo . ');" title = "Eliminar Programacion" ><i class="fa fa-trash"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			//horario
			$lide = trim($row["pro_hini"]) . '-' . trim($row["pro_hfin"]);
			$salida .= '<td class = "text-left">' . $lide . '</td>';
			//fecha.
			$fecha = cambia_fecha($row["pro_fecha"]);
			$salida .= '<td class = "text-left">' . $fecha . '</td>';
			// Tipo
			$tipo = trim($row["pro_tipo"]);
			$tipo = ($tipo == "S") ? "Semanal" : "Mensual";
			$salida .= '<td class = "text-left">' . $tipo . '</td>';
			//observacion
			$obs = trim($row["pro_observaciones"]);
			$salida .= '<td class = "text-left">' . $obs . '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}


function tabla_reportes($proceso, $sistema, $categoria, $situacion, $fini, $ffin, $columnas)
{
	$ClsInd = new ClsIndicador();
	$result = $ClsInd->get_programacion('', '', $proceso, $sistema, '', '', $situacion, '', $fini, $ffin);

	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">No.</th>';
		if (is_array($columnas)) {
			foreach ($columnas as $col) {
				$parametros = parametrosDinamicosHTML($col);
				$ancho = $parametros['ancho'];
				$titulo = $parametros['titulo'];
				$salida .= '<th class = "text-center" width = "' . $ancho . '">' . $titulo . '</th>';
			}
		} else {
			$salida .= '<th class = "text-center" width = "150px">Proceso</th>';
			$salida .= '<th class = "text-center" width = "150px">Sistema</th>';
			$salida .= '<th class = "text-center" width = "100px">Indicador</th>';
		}
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//--
			$salida .= '<td class = "text-center">' . $i . '.- </td>';
			//--
			if (is_array($columnas)) {
				foreach ($columnas as $col) {
					$parametros = parametrosDinamicosHTML($col);
					$campo = $parametros['campo'];
					$alineacion = $parametros['alineacion'];
					if ($col == "pro_hini_hfin") {
						$campo = trim($row["pro_hini"]) . "-" . trim($row["pro_hfin"]);;
					} else if ($col == "ind_codigo" || $col == "sis_codigo" || $col == "cla_codigo" || $col == "pro_codigo") {
						$campo = '# ' . Agrega_Ceros($row[$campo]);
					} else if ($col == "revision_activa") {
						if ($row[$campo]) $campo = "Activa (Rev #" . Agrega_Ceros($row[$campo]) . ")";
						else $campo = "Inactiva";
					} else {
						$campo = utf8_decode($row[$campo]);
					}
					//columna
					$salida .= '<td class = "' . $alineacion . '">' . $campo . '</td>';
				}
			} else {
				//proceso
				$proceso = utf8_decode($row["pro_nombre"]);
				$salida .= '<td class = "text-left">' . $proceso . '</td>';
				//clasficiacion
				$sistema = utf8_decode($row["cla_nombre"]);
				$salida .= '<td class = "text-left">' . $sistema . '</td>';
				//categoria
				$categoria = utf8_decode($row["sis_nombre"]);
				$salida .= '<td class = "text-left">' . $categoria . '</td>';
				//nombre
				$nom = utf8_decode($row["ind_nombre"]);
				$salida .= '<td class = "text-left">' . $nom . '</td>';
			}
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}

	return $salida;
}

function parametrosDinamicosHTML($columna)
{
	switch ($columna) {
			/// Indicador ///
		case "ind_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Indicador";
			$respuesta["campo"] = "ind_codigo";
			break;
		case "ind_nombre":
			$respuesta["ancho"] = "110";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Nombre del Indicador";
			$respuesta["campo"] = "ind_nombre";
			break;
		case "revision_activa":
			$respuesta["ancho"] = "110";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Situaci&oacute;n";
			$respuesta["campo"] = "revision_activa";
			break;
			/// Programacion ///
		case "pro_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Prog.";
			$respuesta["campo"] = "pro_codigo";
			break;
		case "pro_usuario":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Usuario que programa";
			$respuesta["campo"] = "pro_usuario";
			break;
		case "pro_fecha":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Fecha Programada";
			$respuesta["campo"] = "pro_fecha";
			break;
		case "pro_hini_hfin":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Intervalo de Horarios";
			$respuesta["campo"] = "pro_hini_hfin";
			break;
		case "pro_observaciones":
			$respuesta["ancho"] = "150";
			$respuesta["alineacion"] = "text-justify";
			$respuesta["titulo"] = "Observaciones (Indicador)";
			$respuesta["campo"] = "pro_observaciones";
			break;
			/// Categoria ///
		case "sis_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Sistema";
			$respuesta["campo"] = "sis_codigo";
			break;
		case "sis_nombre":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Sistema";
			$respuesta["campo"] = "obj_sistema";
			break;
			/// Objetivo ///
		case "obj_descripcion":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Objetivo";
			$respuesta["campo"] = "obj_descripcion";
			break;
			/// proceso ///
		case "pro_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Proceso";
			$respuesta["campo"] = "pro_codigo";
			break;
		case "fic_nombre":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Proceso";
			$respuesta["campo"] = "obj_proceso";
			break;
	}
	return $respuesta;
}

function parametrosDinamicosEXCEL($columna)
{
	switch ($columna) {
			/// Indicador ///
		case "ind_codigo":
			$respuesta["ancho"] = "18";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Código Indicador";
			$respuesta["campo"] = "ind_codigo";
			break;
		case "ind_nombre":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Nombre del Indicador";
			$respuesta["campo"] = "ind_nombre";
			break;
		case "revision_activa":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Situación";
			$respuesta["campo"] = "revision_activa";
			break;
			/// Programacion ///
		case "pro_codigo":
			$respuesta["ancho"] = "18";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Código Prog.";
			$respuesta["campo"] = "pro_codigo";
			break;
		case "pro_usuario":
			$respuesta["ancho"] = "18";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Usuario que programa";
			$respuesta["campo"] = "pro_usuario";
			break;
		case "pro_fecha":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Fecha Programada";
			$respuesta["campo"] = "pro_fecha";
			break;
		case "pro_hini_hfin":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Intervalo de Horarios";
			$respuesta["campo"] = "pro_hini_hfin";
			break;
		case "pro_observaciones":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "J";
			$respuesta["titulo"] = "Observaciones (Indicador)";
			$respuesta["campo"] = "pro_observaciones";
			break;
		case "pro_tipo":
			$respuesta["ancho"] = "18";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Tipo de Programacion";
			$respuesta["campo"] = "pro_tipo";
			break;
			/// Categoria ///
		case "sis_codigo":
			$respuesta["ancho"] = "18";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Código Sistema";
			$respuesta["campo"] = "sis_codigo";
			break;
		case "sis_nombre":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Sistema";
			$respuesta["campo"] = "obj_sistema";
			break;
			/// Objetivo ///
		case "obj_descripcion":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Objetivo";
			$respuesta["campo"] = "obj_descripcion";
			break;
			/// proceso ///
		case "pro_codigo":
			$respuesta["ancho"] = "18";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Código Proceso";
			$respuesta["campo"] = "pro_codigo";
			break;
		case "fic_nombre":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Proceso";
			$respuesta["campo"] = "obj_proceso";
			break;
	}
	return $respuesta;
}

function combo_dias($name, $instruc = '', $class = '')
{
	$salida  = '<select name="' . $name . '" id="' . $name . '" onchange="' . $instruc . '" class = "' . $class . ' form-control">';
	$salida .= '<option value="">Seleccione</option>';
	for ($i = 1; $i <= 7; $i++) $salida .= '<option value="' . $i . '">' . Dias_Letra($i) . '</option>';
	$salida .= '</select>';
	return $salida;
}

function combo_situacion($name, $instruc = '', $class = '')
{
	$salida  = '<select name="' . $name . '" id="' . $name . '" onchange="' . $instruc . '" class = "' . $class . ' form-control">';
	$salida .= '<option value="">Seleccione</option>';
	$salida .= '<option value="1">Activas</option>';
	$salida .= '<option value="2">Inactivas</option>';
	$salida .= '</select>';
	return $salida;
}
