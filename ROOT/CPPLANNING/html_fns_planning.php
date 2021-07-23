<?php
include_once('../html_fns.php');

function tabla_objetivos($proceso, $sistema, $usuario)
{
	$ClsObj = new ClsObjetivo();
	$result = $ClsObj->get_objetivo_asignado($proceso, $sistema, $usuario);

	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">C&oacute;digo</th>';
		$salida .= '<th class = "text-center" width = "50px">Proceso</th>';
		$salida .= '<th class = "text-center" width = "30px">Sistema</th>';
		$salida .= '<th class = "text-center" width = "250px">Objetivo</th>';
		$salida .= '<th class = "text-center" width = "100px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		foreach ($result as $row) {
			$salida .= '<tr>';
			// Codigo
			$codigo = agrega_ceros($row["obj_codigo"]);
			$salida .= '<td class = "text-center">' . $codigo . '.</td>';
			// Proceso
			$proceso = utf8_decode($row["fic_nombre"]);
			$salida .= '<td class = "text-left">' . $proceso . '</td>';
			// Sistema
			$sistema = utf8_decode($row["sis_nombre"]);
			$salida .= '<td class = "text-left">' . $sistema . '</td>';
			// Descripcion
			$descripcion = utf8_decode($row["obj_descripcion"]);
			$descripcion = nl2br($descripcion);
			$salida .= '<td class = "text-left">' . $descripcion . '</td>';
			//--
			$codigo = $row["obj_codigo"];
			$hashkey = $ClsObj->encrypt($codigo, $usuario);
			// Situacion de la Revision
			$revision = $ClsObj->get_revision("", "", "", $usuario, $codigo);
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			if (is_array($revision)) {
				$situacion = trim($revision[0]["rev_situacion"]);
				$id = trim($revision[0]["rev_codigo"]);
				switch ($situacion) {
					case 1:
						$salida .= '<a class="btn btn-white btn-xs" href = "FRMacciones.php?hashkey=' . $hashkey . '" title = "Acciones del Objetivo" ><i class="fa fa-pencil"></i></a>';
						$salida .= '<a class="btn btn-white btn-xs" href = "CPREPORTES/REPacciones.php?hashkey=' . $hashkey . '" target="_blank" title = "Imprimir Acciones" ><i class="fa fa-print"></i></a>';
						$salida .= '<button type="button" class="btn btn-info btn-outline" onclick="solicitarAprobacion(' . $id . ',' . $codigo . ');" title = "Solicitar Aprobacion" ><i class="fa fa-exclamation-circle"></i></a>';
						break;
					case 2:
						$salida .= '<a class="btn btn-white btn-xs" href = "FRMacciones.php?hashkey=' . $hashkey . '" title = "Acciones del Objetivo" disabled ><i class="fa fa-pencil"></i></a>';
						$salida .= '<a class="btn btn-white btn-xs" href = "CPREPORTES/REPacciones.php?hashkey=' . $hashkey . '" target="_blank" title = "Imprimir Acciones" ><i class="fa fa-print"></i></a>';
						$salida .= '<button type="button" class="btn btn-info btn-outline" title = "En Revisi&oacute;n.." disabled ><i class="fa fa-exclamation-circle"></i></a>';
						break;
					case 3:
						$salida .= '<a class="btn btn-white btn-xs" href = "FRMacciones.php?hashkey=' . $hashkey . '" title = "Acciones del Objetivo" disabled ><i class="fa fa-pencil"></i></a>';
						$salida .= '<a class="btn btn-white btn-xs" href = "CPREPORTES/REPacciones.php?hashkey=' . $hashkey . '" target="_blank" title = "Imprimir Acciones" ><i class="fa fa-print"></i></a>';
						$salida .= '<button type="button" class="btn btn-info btn-xs" disabled><i class="fa fa-check"></i> Acciones Aprobadas</button> ';
						break;
					default:
						$salida .= '<a class="btn btn-white btn-xs" href = "FRMacciones.php?hashkey=' . $hashkey . '" title = "Acciones del Objetivo" ><i class="fa fa-pencil"></i></a>';
						$salida .= '<a class="btn btn-white btn-xs" href = "CPREPORTES/REPacciones.php?hashkey=' . $hashkey . '" target="_blank" title = "Imprimir Acciones" ><i class="fa fa-print"></i></a>';
						$salida .= '<button type="button" class="btn btn-info btn-outline" onclick="solicitarAprobacion(' . $id . ',' . $codigo . ');" title = "Solicitar Aprobacion" ><i class="fa fa-exclamation-circle"></i></a>';
						break;
				}
			} else {
				$salida .= '<a class="btn btn-white btn-xs" href = "FRMacciones.php?hashkey=' . $hashkey . '" title = "Acciones del Objetivo" ><i class="fa fa-pencil"></i></a>';
				$salida .= '<a class="btn btn-white btn-xs" href = "CPREPORTES/REPacciones.php?hashkey=' . $hashkey . '" target="_blank" title = "Imprimir Acciones" ><i class="fa fa-print"></i></a>';
				$salida .= '<button type="button" class="btn btn-info btn-outline" onclick="solicitarAprobacion(\'\',' . $codigo . ');" title = "Solicitar Aprobacion" ><i class="fa fa-exclamation-circle"></i></a>';
			}
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

function tabla_acciones($accion, $objetivo = "", $proceso = "", $usuario = "", $tipo = "", $sistema = "", $desde = "", $hasta = "", $situacion = "", $visualiza = false)
{
	// Acciones
	$ClsAcc = new ClsAccion();
	$result = $ClsAcc->get_accion($accion, $objetivo, $proceso, $usuario, $tipo, $sistema, $desde, $hasta);
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida .= '<th class = "text-center" width = "150px">Nombre</th>';
		$salida .= '<th class = "text-left" width = "20px">Presupuesto</th>';
		$salida .= '<th class = "text-left" width = "30px">Periodicidad</th>';
		$salida .= '<th class = "text-center" width = "30px">Fechas</th>';
		$salida .= '<th class = "text-center" width = "30px">Comentario</th>';
		$salida .= '<th class = "text-center" width = "20px">Programaci&oacute;n</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$codigo = $row["acc_codigo"];
			$salida .= '<tr>';
			// Codigo
			$codigo = trim($row["acc_codigo"]);
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "Seleccionar(' . $codigo . ');" title = "Editar Acci&oacute;n" ><i class="fa fa-pencil"></i></button>';
			$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "quitarElemento(' . $codigo . ');" title = "Eliminar Acci&oacute;n" ><i class="fa fa-trash"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			// Nombre
			$nombre = trim($row["acc_nombre"]);
			$salida .= '<td class = "text-left">' . $nombre . '</td>';
			// Presupuesto
			$presupuesto = trim($row["acc_presupuesto"]);
			$salida .= '<td class = "text-left">' . $presupuesto . '</td>';
			// Tipo
			$tipo = trim($row["acc_tipo"]);
			switch ($tipo) {
				case "U":
					$tipo = "Unica";
					break;
				case "W":
					$tipo = "Semanal";
					break;
				case "M":
					$tipo = "Mensual";
					break;
			}
			$salida .= '<td class = "text-left">' . $tipo . '</td>';
			// Fechas
			$fini = cambia_fecha(trim($row["acc_fecha_inicio"]));
			$ffin = cambia_fecha(trim($row["acc_fecha_fin"]));
			$salida .= '<td class = "text-center">' . $fini . ' - ' . $ffin . '</td>';
			// Comentario
			$comentario = trim($row["acc_comentario"]);
			$salida .= '<td class = "text-left">' . $comentario . '</td>';
			// Periodicidad
			$codigo = $row["acc_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$tipo = trim($row["acc_tipo"]);
			$salida .= '<button type="button" class="btn btn-info btn-xs" onclick = "verDetalle(' . $codigo . ',\'' . $tipo . '\');" title = "Detalle de Acci&oacute;n" ><i class="fa fa-search"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
	}
	$salida .= '</tbody>';
	$salida .= '</table>';

	return $salida;
}

function tabla_aprobacion($proceso, $tipo, $sistema, $usuario)
{
	$ClsObj = new ClsObjetivo();
	$result = $ClsObj->get_revision("", $proceso, $sistema, $usuario, "", 2);
	if (is_array($result)) {
		$sisNombre = $result[0]["sistema_nombre"];
		$salida = '<hr><h6 class="card-title"><i class="fa fa-check"></i> ' . $sisNombre . '</h6>';
		$salida .= '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">C&oacute;digo</th>';
		$salida .= '<th class = "text-center" width = "50px">Proceso</th>';
		$salida .= '<th class = "text-center" width = "50px">Usuario Asignado</th>';
		$salida .= '<th class = "text-center" width = "200px">Objetivo</th>';
		$salida .= '<th class = "text-center" width = "20px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		foreach ($result as $row) {
			$salida .= '<tr>';
			// Codigo
			$codigo = agrega_ceros($row["rev_codigo"]);
			$salida .= '<td class = "text-center">' . $codigo . '.</td>';
			///codigo objetivo
			// Codigo
			//$codigoObjetivo = agrega_ceros($row["obj_codigo"]);
			//$salida .= '<td class = "text-center">' . $codigoObjetivo . '.</td>';
			// Proceso
			$proceso = utf8_decode($row["fic_nombre"]);
			$salida .= '<td class = "text-left">' . $proceso . '</td>';
			// Usuario Asignado
			$asignado = utf8_decode($row["usuario_nombre"]);
			$salida .= '<td class = "text-left">' . $asignado . '</td>';
			// Descripcion
			$descripcion = utf8_decode($row["obj_descripcion"]);
			$descripcion = nl2br($descripcion);
			$salida .= '<td class = "text-left">' . $descripcion . '</td>';
			//--
			$codigoAccion = $row["obj_codigo"];
			$user = $_SESSION["codigo"];
			$hashkeyAccion = $ClsObj->encrypt($codigoAccion, $user);
			$codigoRev = $row["rev_codigo"];
			$user = $_SESSION["codigo"];
			$hashkeyRev = $ClsObj->encrypt($codigoRev, $user);
			// Situacion 
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<a class="btn btn-white btn-xs" href = "CPREPORTES/REPacciones.php?hashkey=' . $hashkeyAccion . '" target="_blank" title = "Imprimir Acciones" ><i class="fa fa-print"></i></a>';
			$salida .= '<a class="btn btn-success btn-lg" href = "FRMaprobar.php?hashkey=' . $hashkeyRev . '" title = "Revisar Objetivo" ><i class="fas fa-clipboard-check"></i></a> ';
			$salida .= '</div>';
			$salida .= '</td>';
			//--
			$salida .= '</tr>';
		}
	}
	$salida .= '</tbody>';
	$salida .= '</table>';
	return $salida;
}

/////////////////////////// Reportes //////////////////////
function tabla_reporte_ejecucion($proceso, $sistema, $usuario, $desde, $hasta, $columnas)
{
	$ClsEje = new ClsEjecucion();
	$result = $ClsEje->get_ejecucion_accion("", "", $desde, $hasta, "", $proceso, $sistema, $usuario);

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
			$salida .= '<th class = "text-center" width = "10px">C&oacute;digo</th>';
			$salida .= '<th class = "text-center" width = "50px">Proceso</th>';
			$salida .= '<th class = "text-center" width = "50px">Sistema</th>';
			$salida .= '<th class = "text-center" width = "150px">Objetivo</th>';
			$salida .= '<th class = "text-center" width = "150px">Acci&oacute;n</th>';
			$salida .= '<th class = "text-center" width = "50px">Fecha Inicio</th>';
			$salida .= '<th class = "text-center" width = "50px">Fecha Fin</th>';
			$salida .= '<th class = "text-center" width = "30px">D&iacute;a Planificado</th>';
			$salida .= '<th class = "text-center" width = "30px">&Uacute;ltimo D&iacute;a</th>';
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
					if ($col == "eva_codigo" || $col == "eje_codigo" || $col == "eva_codigo" || $col == "acc_codigo") {
						$campo = '# ' . Agrega_Ceros($row[$campo]);
					} else if ($col == "acc_tipo") {
						$campo = trim($row[$campo]);
						switch ($campo) {
							case "U":
								$campo = "&Uacute;nica";
								break;
							case "W":
								$campo = "Semanal";
								break;
							case "M":
								$campo = "Mensual";
								break;
						}
					} else if ($col == "pro_dia_inicio" || $col == "pro_dia_fin") {
						$tipo = trim($row["acc_tipo"]);
						$campo = trim($row[$campo]);
						$campo = day_name($campo, $tipo);
					} else {
						$campo = utf8_decode(trim($row[$campo]));
					}
					//columna
					$salida .= '<td class = "' . $alineacion . '">' . $campo . '</td>';
				}
			} else {
				// Codigo
				$codigo = agrega_ceros($row["pro_codigo"]);
				$salida .= '<td class = "text-center">' . $codigo . '.</td>';
				// Proceso
				$proceso = utf8_decode($row["proceso_nombre"]);
				$salida .= '<td class = "text-left">' . $proceso . '</td>';
				// Sistema
				$sistema = utf8_decode($row["sistema_nombre"]);
				$salida .= '<td class = "text-left">' . $sistema . '</td>';
				// Objetivo
				$objetivo = utf8_decode($row["obj_descripcion"]);
				if (strlen($objetivo) > 100) $objetivo = substr($objetivo, 0, 100) . "...";
				$objetivo = nl2br($objetivo);
				$salida .= '<td class = "text-left">' . $objetivo . '</td>';
				// Accion
				$accion = utf8_decode($row["acc_descripcion"]);
				if (strlen($accion) > 100) $accion = substr($accion, 0, 100) . "...";
				$accion = nl2br($accion);
				$salida .= '<td class = "text-left">' . $accion . '</td>';
				// Fecha Inicial
				$fini = trim($row["pro_fecha_inicio"]);
				$salida .= '<td class = "text-left">' . $fini . '</td>';
				// Fecha Final
				$ffin = trim($row["pro_fecha_fin"]);
				$salida .= '<td class = "text-left">' . $ffin . '</td>';
				// --
				$tipo = trim($row["acc_tipo"]);
				// Dia Inicial
				$diaInicio = trim($row["pro_dia_inicio"]);
				$dini = day_name($diaInicio, $tipo);
				$salida .= '<td class = "text-left">' . $dini . '</td>';
				// Dia Final
				$diaFinal = trim($row["pro_dia_fin"]);
				$dfin = day_name($diaFinal, $tipo);
				$salida .= '<td class = "text-left">' . $dfin . '</td>';
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
function tabla_reporte_accion($proceso, $sistema, $usuario, $desde, $hasta, $columnas)
{
	$ClsAcc = new ClsAccion();
	$result = $ClsAcc->get_accion("", "", $proceso, $usuario, "", $sistema);

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
			$salida .= '<th class = "text-center" width = "150px">Descripci&oacute;n</th>';
			$salida .= '<th class = "text-left" width = "20px">Presupuesto</th>';
			$salida .= '<th class = "text-left" width = "30px">Tipo</th>';
			$salida .= '<th class = "text-center" width = "20px">Programaci&oacute;n</th>';
			$salida .= '<th class = "text-center" width = "50px">Comentario</th>';
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
					if ($col == "eva_codigo" || $col == "eje_codigo" || $col == "eva_codigo" || $col == "acc_codigo") {
						$campo = '# ' . Agrega_Ceros($row[$campo]);
					} else if ($col == "acc_tipo") {
						$campo = trim($row[$campo]);
						switch ($campo) {
							case "U":
								$campo = "&Uacute;nica";
								break;
							case "W":
								$campo = "Semanal";
								break;
							case "M":
								$campo = "Mensual";
								break;
						}
					} else if ($col == "pro_dia_inicio" || $col == "pro_dia_fin") {
						$tipo = trim($row["acc_tipo"]);
						$campo = trim($row[$campo]);
						$campo = day_name($campo, $tipo);
					} else {
						$campo = utf8_decode(trim($row[$campo]));
					}
					//columna
					$salida .= '<td class = "' . $alineacion . '">' . $campo . '</td>';
				}
			} else {
				// Descripcion
				$descripcion = trim($row["acc_descripcion"]);
				$salida .= '<td class = "text-left" ><textarea type="text" class="form-control" id ="descripcionSave" name ="descripcionSave" rows="4" onkeyup = "texto(this);" >' . $descripcion . '</textarea></td>';
				// Presupuesto
				$presupuesto = trim($row["acc_presupuesto"]);
				$salida .= '<td class = "text-left"><input type="number" class="form-control" id ="presupuestoSave" name ="presupuestoSave" value="' . $presupuesto . '" ></td>';
				// Tipo
				$tipo = trim($row["acc_tipo"]);
				switch ($tipo) {
					case "U":
						$tipo = "Unica";
						break;
					case "W":
						$tipo = "Semanal";
						break;
					case "M":
						$tipo = "Mensual";
						break;
				}
				$salida .= '<td class = "text-left">' . $tipo . '</td>';
				// Periodicidad
				$codigo = $row["acc_codigo"];
				$salida .= '<td class = "text-center" >';
				$salida .= '<div class="btn-group">';
				$tipo = trim($row["acc_tipo"]);
				$salida .= '<button type="button" class="btn btn-info btn-xs" onclick = "verDetalle(' . $codigo . ',\'' . $tipo . '\');" title = "Detalle de Acci&oacute;n" ><i class="fa fa-search"></i></button>';
				$salida .= '</div>';
				$salida .= '</td>';
				// Comentario
				$comentario = trim($row["acc_comentario"]);
				$salida .= '<td class = "text-left">' . $comentario . '</td>';
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

function tabla_reportes($proceso, $sistema, $usuario, $desde, $hasta, $columnas)
{
	$ClsEva = new ClsEvaluacion();
	$result = $ClsEva->get_evaluacion("", $proceso, $sistema, "", "", $usuario, "", $desde, $hasta);

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
			$salida .= '<th class = "text-center" width = "150px">Acci&oacute;n</th>';
			$salida .= '<th class = "text-center" width = "50">Tipo</th>';
			$salida .= '<th class = "text-center" width = "50">Fecha</th>';
			$salida .= '<th class = "text-center" width = "150px">Observaci&oacute;n</th>';
			$salida .= '<th class = "text-center" width = "10">Puntuaci&oacute;n</th>';
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
					if ($col == "eva_codigo" || $col == "eje_codigo" || $col == "eva_codigo" || $col == "acc_codigo") {
						$campo = '# ' . Agrega_Ceros($row[$campo]);
					} else if ($col == "acc_tipo") {
						$campo = trim($row[$campo]);
						switch ($campo) {
							case "U":
								$campo = "&Uacute;nica";
								break;
							case "W":
								$campo = "Semanal";
								break;
							case "M":
								$campo = "Mensual";
								break;
						}
					} else if ($col == "pro_dia_inicio" || $col == "pro_dia_fin") {
						$tipo = trim($row["acc_tipo"]);
						$campo = trim($row[$campo]);
						$campo = day_name($campo, $tipo);
					} else {
						$campo = utf8_decode(trim($row[$campo]));
					}
					//columna
					$salida .= '<td class = "' . $alineacion . '">' . $campo . '</td>';
				}
			} else {
				// Accion
				$descripcion = utf8_decode($row["acc_descripcion"]);
				$salida .= '<td class = "text-left">' . $descripcion . '</td>';
				// Tipo
				$tipo = trim($row["acc_tipo"]);
				switch ($tipo) {
					case "U":
						$tipo = "&Uacute;nica";
						break;
					case "W":
						$tipo = "Semanal";
						break;
					case "M":
						$tipo = "Mensual";
						break;
				}
				$salida .= '<td class = "text-left">' . $tipo . '</td>';
				// Fecha 
				$fecha = trim($row["eva_fecha"]);
				$salida .= '<td class = "text-left">' . $fecha . '</td>';
				// Observacion
				$observacion = utf8_decode($row["eva_observacion"]);
				$salida .= '<td class = "text-left">' . $observacion . '</td>';
				// Puntuacion
				$puntuacion = trim($row["eva_puntuacion"]);
				$salida .= '<td class = "text-left">' . $puntuacion . '</td>';
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
		return "Dia $day del Mes";
	}
}


function parametrosDinamicosHTML($columna)
{
	switch ($columna) {
			// Evaluaciones
		case "eva_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo de la Evaluaci&oacute;n";
			$respuesta["campo"] = "eva_codigo";
			break;
		case "eva_usuario":
			$respuesta["ancho"] = "110";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Usuario que Eval&uacute;a";
			$respuesta["campo"] = "eva_usuario";
			break;
		case "eva_fecha":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha de Evaluaci&oacute;n";
			$respuesta["campo"] = "eva_fecha";
			break;
		case "eva_observacion":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Observaci&oacute;n";
			$respuesta["campo"] = "eva_observacion";
			break;
		case "eva_puntuacion":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Puntuaci&oacute;n";
			$respuesta["campo"] = "eva_puntuacion";
			break;
			// Ejecuciones
		case "eje_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo de la Ejecuci&oacute;n";
			$respuesta["campo"] = "eje_codigo";
			break;
		case "eje_fecha":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha de Ejecuci&oacute;n";
			$respuesta["campo"] = "eje_fecha";
			break;
		case "eje_observacion":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Observaci&oacute;n";
			$respuesta["campo"] = "eje_observacion";
			break;
			// Accion
		case "acc_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo de la Acci&oacute;n";
			$respuesta["campo"] = "acc_codigo";
			break;
		case "acc_descripcion":
			$respuesta["ancho"] = "110";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Acci&oacute;n";
			$respuesta["campo"] = "acc_descripcion";
			break;
		case "acc_objetivo":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Objetivo";
			$respuesta["campo"] = "acc_objetivo";
			break;
		case "acc_tipo":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Tipo";
			$respuesta["campo"] = "acc_tipo";
			break;
		case "acc_presupuesto":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Presupuesto";
			$respuesta["campo"] = "acc_presupuesto";
			break;
		case "acc_usuario":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Usuario Asignado";
			$respuesta["campo"] = "acc_usuario";
			break;
			// Objetivo
		case "obj_sistema":
			$respuesta["ancho"] = "75";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Sistema";
			$respuesta["campo"] = "obj_sistema";
			break;
		case "obj_proceso":
			$respuesta["ancho"] = "75";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Proceso";
			$respuesta["campo"] = "obj_proceso";
			break;
		case "obj_descripcion":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Objetivo";
			$respuesta["campo"] = "obj_descripcion";
			break;
			// Programacion
		case "pro_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Programaci&oacute;n";
			$respuesta["campo"] = "pro_codigo";
			break;
		case "pro_fecha_inicio":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha Inicial";
			$respuesta["campo"] = "pro_fecha_inicio";
			break;
		case "pro_fecha_fin":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha Final";
			$respuesta["campo"] = "pro_fecha_fin";
			break;
		case "pro_dia_inicio":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "D&iacute;a Planificado";
			$respuesta["campo"] = "pro_dia_inicio";
			break;
		case "pro_dia_fin":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "&Uacute;ltimo D&iacute;a";
			$respuesta["campo"] = "pro_dia_fin";
			break;
	}
	return $respuesta;
}

function parametrosDinamicosExcel($columna)
{
	switch ($columna) {
			// Evaluaciones
		case "eva_codigo":
			$respuesta["ancho"] = "18";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Código de la Evaluación";
			$respuesta["campo"] = "eva_codigo";
			break;
		case "eva_usuario":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Usuario que Evalúa";
			$respuesta["campo"] = "eva_usuario";
			break;
		case "eva_fecha":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha de Evaluación";
			$respuesta["campo"] = "eva_fecha";
			break;
		case "eva_observacion":
			$respuesta["ancho"] = "75";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Observación";
			$respuesta["campo"] = "eva_observacion";
			break;
		case "eva_puntuacion":
			$respuesta["ancho"] = "18";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Puntuación";
			$respuesta["campo"] = "eva_puntuacion";
			break;
			// Ejecuciones
		case "eje_codigo":
			$respuesta["ancho"] = "18";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Código de la Ejecución";
			$respuesta["campo"] = "eje_codigo";
			break;
		case "eje_fecha":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha de Ejecución";
			$respuesta["campo"] = "eje_fecha";
			break;
		case "eje_observacion":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Observación";
			$respuesta["campo"] = "eje_observacion";
			break;
			// Accion
		case "acc_codigo":
			$respuesta["ancho"] = "18";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Código de la Acción";
			$respuesta["campo"] = "acc_codigo";
			break;
		case "acc_descripcion":
			$respuesta["ancho"] = "75";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Acción";
			$respuesta["campo"] = "acc_descripcion";
			break;
		case "acc_objetivo":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Objetivo";
			$respuesta["campo"] = "acc_objetivo";
			break;
		case "acc_tipo":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Tipo";
			$respuesta["campo"] = "acc_tipo";
			break;
		case "acc_presupuesto":
			$respuesta["ancho"] = "18";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Presupuesto";
			$respuesta["campo"] = "acc_presupuesto";
			break;
		case "acc_usuario":
			$respuesta["ancho"] = "18";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Usuario Asignado";
			$respuesta["campo"] = "acc_usuario";
			break;
			// Objetivo
		case "obj_sistema":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Sistema";
			$respuesta["campo"] = "obj_sistema";
			break;
		case "obj_proceso":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Proceso";
			$respuesta["campo"] = "obj_proceso";
			break;
		case "obj_descripcion":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Objetivo";
			$respuesta["campo"] = "obj_descripcion";
			break;
			// Programacion
		case "pro_codigo":
			$respuesta["ancho"] = "18";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Código Programación";
			$respuesta["campo"] = "pro_codigo";
			break;
		case "pro_fecha_inicio":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Inicial";
			$respuesta["campo"] = "pro_fecha_inicio";
			break;
		case "pro_fecha_fin":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Final";
			$respuesta["campo"] = "pro_fecha_fin";
			break;
		case "pro_dia_inicio":
			$respuesta["ancho"] = "18";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Día Planificado";
			$respuesta["campo"] = "pro_dia_inicio";
			break;
		case "pro_dia_fin":
			$respuesta["ancho"] = "18";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Último Día";
			$respuesta["campo"] = "pro_dia_fin";
			break;
	}
	return $respuesta;
}
