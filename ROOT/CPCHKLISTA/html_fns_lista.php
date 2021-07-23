<?php
include_once('../html_fns.php');

function tabla_listas($codigo, $categoria)
{

	$ClsLis = new ClsLista();
	$result = $ClsLis->get_lista($codigo, $categoria, 1);

	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida .= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida .= '<th class = "text-center" width = "150px">Categor&iacute;a</th>';
		$salida .= '<th class = "text-center" width = "150px">Lista</th>';
		$salida .= '<th class = "text-center" width = "40px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 0;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = $row["list_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarLista(' . $codigo . ');" title = "Editar Lista" ><i class="fa fa-pencil"></i></button>';
			$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarLista(' . $codigo . ');" title = "Eliminar Lista" ><i class="fa fa-trash"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			//codigo
			$codigo = Agrega_Ceros($row["list_codigo"]);
			$salida .= '<td class = "text-center">' . $codigo . '</td>';
			//categoria
			$categoria = trim($row["cat_nombre"]);
			$salida .= '<td class = "text-left">' . $categoria . '</td>';
			//nombre
			$nom = trim($row["list_nombre"]);
			$salida .= '<td class = "text-left">' . $nom . '</td>';
			//--
			//codigo
			$codigo = $row["list_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-info btn-xs" onclick = "horarios(' . $codigo . ');" title = "Programaci&oacute;n de Ejecuci&oacute;n" ><span class="fa fa-calendar-o"></span></button>';
			$salida .= '<button type="button" class="btn btn-success btn-xs" onclick = "preguntas(' . $codigo . ');" title = "Agregar Preguntas a la Lista" ><span class="fa fa-question-circle-o"></span></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}


function tabla_listas_areas($lista)
{
	$ClsLis = new ClsLista();
	$result = $ClsLis->get_lista_area('', $lista);

	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "30px"><No.</th>';
		$salida .= '<th class = "text-center" width = "150px">Sede</th>';
		$salida .= '<th class = "text-center" width = "150px">Sector</th>';
		$salida .= '<th class = "text-center" width = "150px">&Aacute;rea</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//No.
			$salida .= '<td class = "text-center">' . $i . '.</td>';
			//sede
			$sede = utf8_decode($row["sed_nombre"]);
			$salida .= '<td class = "text-left">' . $sede . '</td>';
			//sede
			$categoria = utf8_decode($row["cat_nombre"]);
			$salida .= '<td class = "text-left">' . $categoria . '</td>';
			//area
			$area = utf8_decode($row["are_nombre"]);
			$salida .= '<td class = "text-left">' . $area . '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}

	return $salida;
}


function tabla_preguntas($codigo, $lista)
{
	$ClsLis = new ClsLista();
	$result = $ClsLis->get_pregunta($codigo, $lista, '', 1);

	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida .= '<th class = "text-center" width = "10px">No.</th>';
		$salida .= '<th class = "text-center" width = "150px">Preguntas</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = $row["pre_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarPregunta(' . $codigo . ');" title = "Editar Pregunta" ><i class="fa fa-pencil"></i></button>';
			$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarPregunta(' . $codigo . ');" title = "Eliminar Pregunta" ><i class="fa fa-trash"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			//No.
			$salida .= '<td class = "text-center">' . $i . '.</td>';
			//pregunta
			$pregunta = trim($row["pre_pregunta"]);
			$salida .= '<td class = "text-left">' . $pregunta . '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}

	return $salida;
}


function tabla_horarios($codigo, $lista)
{
	$ClsLis = new ClsLista();
	$result = $ClsLis->get_programacion($codigo, $lista, '', '', '', '', '', '', 1);

	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida .= '<th class = "text-center" width = "150px">Sede</th>';
		$salida .= '<th class = "text-center" width = "150px">&Aacute;rea</th>';
		$salida .= '<th class = "text-center" width = "50px">Horario</th>';
		$salida .= '<th class = "text-center" width = "50px">D&iacute;as</th>';
		$salida .= '<th class = "text-center" width = "150px">Observaciones</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = $row["pro_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarProgramacion(' . $codigo . ');" title = "Editar Programaci&oacute;n" ><i class="fa fa-pencil"></i></button>';
			$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarProgramacion(' . $codigo . ');" title = "Eliminar Programaci&oacute;n" ><i class="fa fa-trash"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			//sede
			$sede = trim($row["sed_nombre"]);
			$salida .= '<td class = "text-left">' . $sede . '</td>';
			//area
			$area = trim($row["are_nombre"]);
			$salida .= '<td class = "text-left">' . $area . '</td>';
			//No.
			$hini = trim($row["pro_hini"]);
			$hfin = trim($row["pro_hfin"]);
			$salida .= '<td class = "text-center">' . $hini . '-' . $hfin . '</td>';
			//dias
			$dias = "";
			$diaL = trim($row["pro_dia_1"]);
			$dias .= ($diaL == 1) ? "Lun," : "";
			$diaM = trim($row["pro_dia_2"]);
			$dias .= ($diaM == 1) ? "Mar," : "";
			$diaW = trim($row["pro_dia_3"]);
			$dias .= ($diaW == 1) ? "Mie," : "";
			$diaJ = trim($row["pro_dia_4"]);
			$dias .= ($diaJ == 1) ? "Jue," : "";
			$diaV = trim($row["pro_dia_5"]);
			$dias .= ($diaV == 1) ? "Vie," : "";
			$diaS = trim($row["pro_dia_6"]);
			$dias .= ($diaS == 1) ? "Sab," : "";
			$diaD = trim($row["pro_dia_7"]);
			$dias .= ($diaD == 1) ? "Dom," : "";
			$diaMes = trim($row["pro_dia_mes"]);
			$dias .= ($diaMes != 0) ? "d&iacute;a $diaMes del mes " : "";
			$dias = substr($dias, 0, -1);
			$diaFechaUnica = cambia_fecha($row["pro_fecha"]);
			///fecha del dia de hoy
			$dias .= ($diaFechaUnica > "0000-00-00" ) ? $diaFechaUnica : '';
			$salida .= '<td class = "text-center">' . $dias . '</td>';
			//observaciones
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

function tabla_reportes($sede, $sector, $area, $categoria, $dia, $situacion, $columnas)
{
	$ClsLis = new ClsLista();
	$result = $ClsLis->get_programacion('', '', $sede, $sector, $area, $categoria, $dia, '', $situacion);

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
			$salida .= '<th class = "text-center" width = "150px">Sede</th>';
			$salida .= '<th class = "text-center" width = "150px">&Aacute;rea</th>';
			$salida .= '<th class = "text-center" width = "150px">Categor&iacute;a</th>';
			$salida .= '<th class = "text-center" width = "100px">Usuario</th>';
			$salida .= '<th class = "text-center" width = "100px">Lista</th>';
			$salida .= '<th class = "text-center" width = "100px">Inici&oacute;</th>';
			$salida .= '<th class = "text-center" width = "100px">Finaliz&oacute;</th>';
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
					if ($col == "list_codigo") {
						$campo = '# ' . Agrega_Ceros($row[$campo]);
					} else if ($col == "list_situacion") {
						$campo = trim($row[$campo]);
						$campo = ($campo == 1) ? '<strong class="text-success">Activa</strong>' : '<strong class="text-danger">Inactiva</strong>';
					} else if ($col == "list_fotos" || $col == "list_firma") {
						$campo = trim($row[$campo]);
						$campo = ($campo == 1) ? 'Si' : 'No';
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
						$dias .= ($diaMes != 0) ? "d&iacute;a $diaMes del mes " : "";
						$campo = substr($dias, 0, -1);
					} else if ($col == "pro_tipo") {
						$campo = (trim($row["pro_tipo"]) == "S") ? "Semanal" : "Mensual";
					} else if ($col == "pro_hini_hfin") {
						$campo = trim($row["pro_hini"]) . "-" . trim($row["pro_hfin"]);
					} else {
						$campo = utf8_decode($row[$campo]);
					}
					//columna
					$salida .= '<td class = "' . $alineacion . '">' . $campo . '</td>';
				}
			} else {
				//sede
				$sede = utf8_decode($row["sed_nombre"]);
				$salida .= '<td class = "text-left">' . $sede . '</td>';
				//area
				$area = utf8_decode($row["are_nombre"]);
				$salida .= '<td class = "text-left">' . $area . '</td>';
				//categoria
				$categoria = utf8_decode($row["cat_nombre"]);
				$salida .= '<td class = "text-left">' . $categoria . '</td>';
				//Usuario
				$usuario = utf8_decode($row["usuario_nombre"]);
				$salida .= '<td class = "text-left">' . $usuario . '</td>';
				//nombre
				$nom = utf8_decode($row["list_nombre"]);
				$salida .= '<td class = "text-left">' . $nom . '</td>';
				//fecha/hora
				$fechor = trim($row["rev_fecha_inicio"]);
				$fechor = cambia_fechaHora($fechor);
				$salida .= '<td class = "text-left">' . $fechor . '</td>';
				//fecha/hora
				$fechor = trim($row["rev_fecha_final"]);
				$fechor = cambia_fechaHora($fechor);
				$salida .= '<td class = "text-left">' . $fechor . '</td>';
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
		case "list_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Lista";
			$respuesta["campo"] = "list_codigo";
			break;
		case "list_nombre":
			$respuesta["ancho"] = "110";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Nombre de la Lista";
			$respuesta["campo"] = "list_nombre";
			break;
		case "list_fotos":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "&iquest;Requiere Foto?";
			$respuesta["campo"] = "list_fotos";
			break;
		case "list_firma":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "&iquest;Requiere Firma?";
			$respuesta["campo"] = "list_firma";
			break;
		case "pro_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Prog.";
			$respuesta["campo"] = "pro_codigo";
			break;
		case "pro_dias":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "D&iacute;as Programados";
			$respuesta["campo"] = "pro_dias";
			break;
		case "pro_hini_hfin":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Intervalo de Horarios";
			$respuesta["campo"] = "pro_hini_hfin";
			break;
		case "pro_tipo":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Tipo de Programaci&oacute;n";
			$respuesta["campo"] = "pro_tipo";
			break;
		case "pro_observaciones":
			$respuesta["ancho"] = "150";
			$respuesta["alineacion"] = "text-justify";
			$respuesta["titulo"] = "Observaciones (Lista)";
			$respuesta["campo"] = "pro_observaciones";
			break;
		case "cat_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Cate";
			$respuesta["campo"] = "cat_codigo";
			break;
		case "cat_nombre":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Categor&iacute;a";
			$respuesta["campo"] = "cat_nombre";
			break;
		case "cat_color":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Color";
			$respuesta["campo"] = "cat_color";
			break;
		case "are_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo &Aacute;rea";
			$respuesta["campo"] = "are_codigo";
			break;
		case "are_nivel":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Nivel";
			$respuesta["campo"] = "are_nivel";
			break;
		case "are_nombre":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "&Aacute;rea";
			$respuesta["campo"] = "are_nombre";
			break;
		case "sec_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Sector";
			$respuesta["campo"] = "sec_codigo";
			break;
		case "sec_nombre":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Sector";
			$respuesta["campo"] = "sec_nombre";
			break;
		case "sed_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Sede";
			$respuesta["campo"] = "sed_codigo";
			break;
		case "sed_nombre":
			$respuesta["ancho"] = "110";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Sede";
			$respuesta["campo"] = "sed_nombre";
			break;
		case "sede_municipio":
			$respuesta["ancho"] = "110";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Departamento / Municipio";
			$respuesta["campo"] = "sede_municipio";
			break;
		case "sed_direccion":
			$respuesta["ancho"] = "150";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Direcci&oacute;n (Sede)";
			$respuesta["campo"] = "sed_direccion";
			break;
		case "sed_zona":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Zona";
			$respuesta["campo"] = "sed_zona";
			break;
	}
	return $respuesta;
}

function parametrosDinamicosEXCEL($columna)
{
	switch ($columna) {
		case "list_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Lista";
			$respuesta["campo"] = "list_codigo";
			break;
		case "list_nombre":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Nombre de la Lista";
			$respuesta["campo"] = "list_nombre";
			break;
		case "list_fotos":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Requiere Foto?";
			$respuesta["campo"] = "list_fotos";
			break;
		case "list_firma":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Requiere Firma?";
			$respuesta["campo"] = "list_firma";
			break;
		case "pro_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Programado";
			$respuesta["campo"] = "pro_codigo";
			break;
		case "pro_dias":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Dias Programados";
			$respuesta["campo"] = "pro_dias";
			break;
		case "pro_hini_hfin":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Intervalo Hor.";
			$respuesta["campo"] = "pro_hini_hfin";
			break;
		case "pro_tipo":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Tipo de Programacion";
			$respuesta["campo"] = "pro_tipo";
			break;
		case "pro_observaciones":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "J";
			$respuesta["titulo"] = "Observaciones (Lista)";
			$respuesta["campo"] = "pro_observaciones";
			break;
		case "cat_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Cate.";
			$respuesta["campo"] = "cat_codigo";
			break;
		case "cat_nombre":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Categoría";
			$respuesta["campo"] = "cat_nombre";
			break;
		case "cat_color":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Color";
			$respuesta["campo"] = "cat_color";
			break;
		case "are_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Area";
			$respuesta["campo"] = "are_codigo";
			break;
		case "are_nivel":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Nivel";
			$respuesta["campo"] = "are_nivel";
			break;
		case "are_nombre":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Area";
			$respuesta["campo"] = "are_nombre";
			break;
		case "sec_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Sector";
			$respuesta["campo"] = "sec_codigo";
			break;
		case "sec_nombre":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Sector";
			$respuesta["campo"] = "sec_nombre";
			break;
		case "sed_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Sede";
			$respuesta["campo"] = "sed_codigo";
			break;
		case "sed_nombre":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Sede";
			$respuesta["campo"] = "sed_nombre";
			break;
		case "sede_municipio":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Departamento / Municipio";
			$respuesta["campo"] = "sede_municipio";
			break;
		case "sed_direccion":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Direccion (Sede)";
			$respuesta["campo"] = "sed_direccion";
			break;
		case "sed_zona":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Zona";
			$respuesta["campo"] = "sed_zona";
			break;
	}
	return $respuesta;
}
