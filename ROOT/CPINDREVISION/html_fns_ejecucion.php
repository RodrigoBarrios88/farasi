<?php
include_once('../html_fns.php');

function tabla_ejecucion($codigo, $proceso, $sistema, $usuario)
{
	$ClsInd = new ClsIndicador();
	$result = $ClsInd->get_programacion($codigo, "", $proceso, $sistema, date("d/m/Y"), date("H:i"), "", $usuario);

	if (is_array($result)) {
		$salida = '<table id="tabla" class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "20px">Codigo</th>';
		$salida .= '<th class = "text-center" width = "75px">Proceso</th>';
		$salida .= '<th class = "text-center" width = "75px">Sistema</th>';
		$salida .= '<th class = "text-center" width = "75px">Indicador</th>';
		$salida .= '<th class = "text-center" width = "50px">Horario</th>';
		$salida .= '<th class = "text-center" width = "40px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 0;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//No.
			$codigo = Agrega_Ceros($row["pro_codigo"]);
			$salida .= '<td class = "text-center">' . $codigo . '.</td>';
			// Proceso
			$proceso = trim($row["obj_proceso"]);
			$salida .= '<td class = "text-center">' . $proceso . '</td>';
			// Sistema
			$sistema = trim($row["obj_sistema"]);
			$salida .= '<td class = "text-center">' . $sistema . '</td>';
			// Indicador
			$name = trim($row["ind_nombre"]);
			$salida .= '<td class = "text-center">' . $name . '</td>';
			// Horario
			$horario = substr($row["pro_hini"], 0, 5) . "-" . substr($row["pro_hfin"], 0, 5);
			$salida .= '<td class = "text-center">' . $horario . '</td>';
			// Codigo
			$programacion = $row["pro_codigo"];
			$revision = $row["revision"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsInd->encrypt($programacion, $usu);
			$salida .= '<td class = "text-center" >';
			if ($revision != "") {
				$ClsRev = new ClsRevision();
				$revision = $ClsRev->get_revision_indicador($revision);
				$situacion = $revision[0]["rev_situacion"];		if ($situacion == "1") $salida .= '<a class="btn btn-success" href = "FRManotar.php?hashkey=' . $hashkey . '" title = "Seleccionar Indicador" > Continuar (Toma #' . $revision . ') <i class="fa fa-chevron-right"></i></a> &nbsp; ';
				else $salida .= '<a class="btn btn-warning"> Toma de datos Finalizada <i class="fa fa-check"></i></a> &nbsp; ';
			} else $salida .= '<a class="btn btn-info" href = "FRManotar.php?hashkey=' . $hashkey . '" title = "Seleccionar Indicador " >Iniciar Toma de Datos <i class="fa fa-chevron-right"></i></a> &nbsp; ';
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


function tabla_revisiones($codigo, $indicador, $usuario, $fini, $ffin, $proceso, $sistema)
{
	$ClsRev = new ClsRevision();
	$result = $ClsRev->get_revision_indicador($codigo, $indicador, "", $usuario, $fini, $ffin, "1,2", "", $proceso, $sistema);

	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">C&oacute;digo</th>';
		$salida .= '<th class = "text-center" width = "75px">Proceso</th>';
		$salida .= '<th class = "text-center" width = "75px">Sistema</th>';
		$salida .= '<th class = "text-center" width = "75px">Indicador</th>';
		$salida .= '<th class = "text-center" width = "75px">Usuario</th>';
		$salida .= '<th class = "text-center" width = "50px">Fecha/Hora</th>';
		$salida .= '<th class = "text-center" width = "50px">Status</th>';
		$salida .= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		foreach ($result as $row) {
			$salida .= '<tr>';
			//revision
			$revision = Agrega_Ceros($row["rev_codigo"]);
			$salida .= '<td class = "text-center">#' . $revision . '</td>';
			// Proceso
			$proceso = trim($row["obj_proceso"]);
			$salida .= '<td class = "text-center">' . $proceso . '</td>';
			// Sistema
			$sistema = trim($row["obj_sistema"]);
			$salida .= '<td class = "text-center">' . $sistema . '</td>';
			// Indicador
			$name = trim($row["ind_nombre"]);
			$salida .= '<td class = "text-center">' . $name . '</td>';
			// Usuario
			$usuario = trim($row["rev_usuario"]);
			$salida .= '<td class = "text-left">' . $usuario . '</td>';
			//fecha/hora
			$fechor = trim($row["rev_fecha_final"]);
			$fechor = cambia_fechaHora($fechor);
			$salida .= '<td class = "text-center">' . $fechor . '</td>';
			//stauts
			$situacion = trim($row["rev_situacion"]);
			$status = ($situacion == 1) ? '<strong class="text-info">En proceso</strong>' : '<strong class="text-muted">Finalizada</strong>';
			$salida .= '<td class = "text-center">' . $status . '</td>';
			//codigo
			$codigo = $row["rev_codigo"];
			$hashkey = $ClsRev->encrypt($codigo, $_SESSION["codigo"]);
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button class="btn btn-info btn-lg" onclick="verRevision(' . $codigo . ')" title = "Seleccionar Revisi&oacute;n" ><i class="fa fa-search"></i></button> ';
			$salida .= '<a class="btn btn-white btn-xs" href = "CPREPORTES/REPpdf.php?hashkey=' . $hashkey . '" target="_blank" title = "Imprimir Acciones" ><i class="fa fa-print"></i></a>';
			$salida .= '</div>';
			$salida .= '</td>';
			//--
			$salida .= '</tr>';
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}

	return $salida;
}


function tabla_reportes($usuario, $proceso, $sistema, $desde, $hasta, $columnas)
{
	$ClsRev = new ClsRevision();
	$result = $ClsRev->get_revision_indicador('', '', '', $usuario, $desde, $hasta, '', '', $proceso, $sistema);

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
			$salida .= '<th class = "text-center" width = "100px">Usuario</th>';
			$salida .= '<th class = "text-center" width = "100px">Indicador</th>';
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
					if ($col == "rev_codigo" || $col == "ind_codigo" || $col == "sis_codigo" || $col == "cla_codigo" || $col == "cla_unidad_medida" || $col == "fic_codigo") {
						$campo = '# ' . Agrega_Ceros($row[$campo]);
					} else if ($col == "rev_fecha_inicio") {
						$campo = cambia_fechaHora($row[$campo]);
					} else if ($col == "rev_fecha_final") {
						$campo = cambia_fechaHora($row[$campo]);
					} else if ($col == "rev_situacion") {
						$campo = trim($row[$campo]);
						$campo = ($campo == 1) ? '<strong class="text-success">En Proceso</strong>' : '<strong class="text-muted">Finalizado</strong>';
					} else if ($col == "pro_hini_hfin") {
						$campo = trim($row["pro_hini"]) . "-" . trim($row["pro_hfin"]);;
					} else if ($col == "pro_tipo") {
						$campo = (trim($row["pro_tipo"]) == "S") ? "Semanal" : "Mensual";
					} else {
						$campo = utf8_decode($row[$campo]);
					}
					//columna
					$salida .= '<td class = "' . $alineacion . '">' . $campo . '</td>';
				}
			} else {
				//departamento
				$departamento = utf8_decode($row["fic_nombre"]);
				$salida .= '<td class = "text-left">' . $departamento . '</td>';
				//clasficiacion
				$clasificacion = utf8_decode($row["cla_nombre"]);
				$salida .= '<td class = "text-left">' . $clasificacion . '</td>';
				//categoria
				$categoria = utf8_decode($row["sis_nombre"]);
				$salida .= '<td class = "text-left">' . $categoria . '</td>';
				//Usuario
				$usuario = utf8_decode($row["usuario_nombre"]);
				$salida .= '<td class = "text-left">' . $usuario . '</td>';
				//nombre
				$nom = utf8_decode($row["ind_nombre"]);
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
			/// Revision ///
		case "rev_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Revisi&oacute;n";
			$respuesta["campo"] = "rev_codigo";
			break;
		case "rev_lectura":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Lectura Tomada";
			$respuesta["campo"] = "rev_lectura";
			break;
		case "rev_fecha_inicio":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha y hora de Inicio";
			$respuesta["campo"] = "rev_fecha_inicio";
			break;
		case "rev_fecha_final":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha y hora de Finalizaci&oacute;n";
			$respuesta["campo"] = "rev_fecha_final";
			break;
		case "rev_observaciones":
			$respuesta["ancho"] = "200";
			$respuesta["alineacion"] = "text-justify";
			$respuesta["titulo"] = "Observaciones";
			$respuesta["campo"] = "rev_observaciones";
			break;
		case "rev_situacion":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Situaci&oacute;n";
			$respuesta["campo"] = "rev_situacion";
			break;
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
			/// Programacion ///
		case "pro_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Prog.";
			$respuesta["campo"] = "pro_codigo";
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
			break;	/// Departamento ///
		case "fic_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Proceso";
			$respuesta["campo"] = "fic_codigo";
			break;
		case "fic_nombre":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Proceso";
			$respuesta["campo"] = "obj_proceso";
			break;
			/// Clasificacion ///
		case "cla_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Clasificaci&oacute;n";
			$respuesta["campo"] = "cla_codigo";
			break;
		case "cla_nombre":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Clasificaci&oacute;n";
			$respuesta["campo"] = "cla_nombre";
			break;
			/// Unidad de Medida ///
		case "medida_nombre":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Unidad de medida";
			$respuesta["campo"] = "medida_nombre";
			break;
			/// Objetivo ///
		case "obj_descripcion":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "text_left";
			$respuesta["titulo"] = "Objetivo";
			$respuesta["campo"] = "obj_descripcion";
			break;
			/// Usuario ///
		case "usuario_nombre":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Usuario (Registr&oacute;)";
			$respuesta["campo"] = "rev_usuario";
			break;
	}
	return $respuesta;
}

function parametrosDinamicosEXCEL($columna)
{
	switch ($columna) {
			/// Revision ///
		case "rev_codigo":
			$respuesta["ancho"] = "18";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Código Revisión";
			$respuesta["campo"] = "rev_codigo";
			break;
		case "rev_lectura":
			$respuesta["ancho"] = "18";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Lectura Tomada";
			$respuesta["campo"] = "rev_lectura";
			break;
		case "rev_fecha_inicio":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha y hora de Inicio";
			$respuesta["campo"] = "rev_fecha_inicio";
			break;
		case "rev_fecha_final":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha y hora de Finalización";
			$respuesta["campo"] = "rev_fecha_final";
			break;
		case "rev_observaciones":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "J";
			$respuesta["titulo"] = "Observaciones";
			$respuesta["campo"] = "rev_observaciones";
			break;
		case "rev_situacion":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Situación";
			$respuesta["campo"] = "rev_situacion";
			break;
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
			/// Programacion ///
		case "pro_codigo":
			$respuesta["ancho"] = "18";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Código Prog.";
			$respuesta["campo"] = "pro_codigo";
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
			$respuesta["ancho"] = "30";
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
			/// Proceso ///
		case "fic_codigo":
			$respuesta["ancho"] = "18";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Código Proceso";
			$respuesta["campo"] = "fic_codigo";
			break;
		case "fic_nombre":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Proceso";
			$respuesta["campo"] = "obj_proceso";
			break;
			/// Unidad de Medida ///
		case "medida_nombre":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Unidad de medida";
			$respuesta["campo"] = "medida_nombre";
			break;
			/// Objetivo ///
		case "obj_descripcion":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Objetivo";
			$respuesta["campo"] = "obj_descripcion";
			break;
			/// Usuario ///
		case "usuario_nombre":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Usuario (Registró)";
			$respuesta["campo"] = "rev_usuario";
			break;
	}
	return $respuesta;
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
				if (file_exists('../../CONFIG/Fotos/PLANNING/' . $strArchivo . '.jpg')) {
					$strArchivo = '<a href="../../CONFIG/Fotos/PLANNING/' . $strArchivo . '.jpg" target="_blank"><img class="img-upload" src="../../CONFIG/Fotos/PLANNING/' . $strArchivo . '.jpg" alt="..."></a>';
					$evidencia = true;
				} else if (file_exists('../../CONFIG/Archivos/PLANNING/' . $strArchivo . '.pdf')) {
					$strArchivo = '<a href="../../CONFIG/Archivos/PLANNING/' . $strArchivo . '.pdf" target="_blank"><img class="img-upload" src="../../CONFIG/img/document.png" alt="..."></a>';
					$evidencia = true;
				} else {
					$strArchivo = '<i class="fa fa-file-o fa-8x"></i>';
				}
			}
		} else {
			$strArchivo = '<i class="fa fa-file-o fa-8x"></i>';
		}
		$arrArchivos[$i] = $strArchivo;
	}
	$arrArchivos[0] = $evidencia;
	return $arrArchivos;
}
