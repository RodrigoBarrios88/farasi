<?php
include_once('../html_fns.php');

function tabla_cuestionarios($codigo)
{
	$ClsCue = new ClsCuestionarioPPM();
	$result = $ClsCue->get_cuestionario($codigo, '', 1);

	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida .= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida .= '<th class = "text-center" width = "150px">Categor&iacute;a</th>';
		$salida .= '<th class = "text-center" width = "150px">Cuestionario</th>';
		$salida .= '<th class = "text-center" width = "40px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 0;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = $row["cue_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarCuestionario(' . $codigo . ');" title = "Editar Cuestionario" ><i class="fa fa-pencil"></i></button>';
			$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "Confirm_Delete_Cuestionario(' . $codigo . ');" title = "Eliminar Cuestionario" ><i class="fa fa-trash"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			//codigo
			$codigo = Agrega_Ceros($row["cue_codigo"]);
			$salida .= '<td class = "text-center">' . $codigo . '</td>';
			//categoria
			$categoria = trim($row["cat_nombre"]);
			$salida .= '<td class = "text-left">' . $categoria . '</td>';
			//nombre
			$nom = trim($row["cue_nombre"]);
			$salida .= '<td class = "text-left">' . $nom . '</td>';
			//--
			//codigo
			$codigo = $row["cue_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-success btn-xs" onclick = "preguntas(' . $codigo . ');" title = "Agregar Preguntas a la Cuestionario" ><span class="fa fa-question-circle-o"></span></button>';
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


function tabla_preguntas($codigo, $cuestionario)
{
	$ClsCue = new ClsCuestionarioPPM();
	$result = $ClsCue->get_pregunta($codigo, $cuestionario, '', 1);

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
			$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "Confirm_Delete_Pregunta(' . $codigo . ');" title = "Eliminar Pregunta" ><i class="fa fa-trash"></i></button>';
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


function tabla_programacion($activo, $usuario, $categoria, $area, $desde, $hasta)
{
	$ClsPro = new ClsProgramacionPPM();
	$result = $ClsPro->get_programacion('', $activo, $usuario, $categoria, '', '', $area, $desde, $hasta, '', '', '');

	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida .= '<th class = "text-center" width = "120px">Ubicaci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "50px">No. Activo</th>';
		$salida .= '<th class = "text-center" width = "120px">Activo</th>';
		$salida .= '<th class = "text-center" width = "120px">Usuario</th>';
		$salida .= '<th class = "text-center" width = "100px">Categor&iacute;a</th>';
		$salida .= '<th class = "text-center" width = "100px">Fecha</th>';
		$salida .= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = $row["pro_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsPro->encrypt($codigo, $usu);
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarProgramacion(\'' . $hashkey . '\');" title = "Editar Programaci&oacute;n" ><i class="fa fa-pencil"></i></button>';
			$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "Confirm_Delete_Programacion(' . $codigo . ');" title = "Eliminar Programaci&oacute;n" ><i class="fa fa-trash"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			//sede
			$sede = trim($row["sed_nombre"]) . " " . trim($row["are_nombre"]) . " " . trim($row["are_nivel"]);
			$salida .= '<td class = "text-left">' . utf8_decode($sede) . '</td>';
			//activo
			$codigoActivo = Agrega_Ceros($row["act_codigo"]);
			$salida .= '<td class = "text-center">' . $codigoActivo . '</td>';
			//activo
			$activo = utf8_decode(trim($row["act_nombre"]));
			$salida .= '<td class = "text-left">' . $activo . '</td>';
			//usuario
			$usuario = utf8_decode(trim($row["usu_nombre"]));
			$salida .= '<td class = "text-left">' . $usuario . '</td>';
			//categoria
			$categoria = utf8_decode(trim($row["cat_nombre"]));
			$salida .= '<td class = "text-left">' . $categoria . '</td>';
			//fecha.
			$fecha = cambia_fecha($row["pro_fecha"]);
			$salida .= '<td class = "text-center">' . $fecha . '</td>';
			//codigo
			$codigo = $row["pro_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsPro->encrypt($codigo, $usu);
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-success btn-xs" onclick = "verProgramacion(\'' . $hashkey . '\');" title = "Ver Orden de Trabajo" ><i class="fa fa-search"></i></button>';
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


function tabla_reprogramacion($activo, $usuario, $categoria, $area, $desde, $hasta)
{
	$ClsPro = new ClsProgramacionPPM();
	$result = $ClsPro->get_programacion('', $activo, $usuario, $categoria, '', '', $area, $desde, $hasta, '', '', '');

	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" id="tabla">';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">No.</th>';
		$salida .= '<th class = "text-center" width = "30px">Orden No.</th>';
		$salida .= '<th class = "text-center" width = "120px">Sede</th>';
		$salida .= '<th class = "text-center" width = "50px">No. Activo</th>';
		$salida .= '<th class = "text-center" width = "120px">Activo</th>';
		$salida .= '<th class = "text-center" width = "120px">Usuario</th>';
		$salida .= '<th class = "text-center" width = "100px">Categor&iacute;a</th>';
		$salida .= '<th class = "text-center" width = "100px">Fecha</th>';
		$salida .= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$salida .= '<td class = "text-center">' . $i . '.</td>';
			//codigo
			$codigo = Agrega_Ceros($row["pro_codigo"]);
			$salida .= '<td class = "text-center">#' . $codigo . '</td>';
			//sede
			$sede = nl2br(trim($row["sed_nombre"]));
			$salida .= '<td class = "text-left">' . $sede . '</td>';
			//activo
			$codigoActivo = Agrega_Ceros($row["act_codigo"]);
			$salida .= '<td class = "text-center">' . $codigoActivo . '</td>';
			//activo
			$activo = nl2br(trim($row["act_nombre"]));
			$salida .= '<td class = "text-left">' . $activo . '</td>';
			//usuario
			$usuario = nl2br(trim($row["usu_nombre"]));
			$salida .= '<td class = "text-left">' . $usuario . '</td>';
			//categoria
			$categoria = nl2br(trim($row["cat_nombre"]));
			$salida .= '<td class = "text-left">' . $categoria . '</td>';
			//fecha.
			$fecha = cambia_fecha($row["pro_fecha"]);
			$salida .= '<td class = "text-center">' . $fecha . '</td>';
			//codigo
			$codigo = $row["pro_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsPro->encrypt($codigo, $usu);
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<a class="btn btn-info btn-xs" href = "FRMreprogramar.php?hashkey=' . $hashkey . '" title = "Re-Programar Orden de Trabajo" ><i class="fa fa-calendar-o"></i></a>';
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


function tabla_reasignacion($activo, $usuario, $categoria, $area, $desde, $hasta)
{
	$ClsPro = new ClsProgramacionPPM();
	$result = $ClsPro->get_programacion('', $activo, $usuario, $categoria, '', '', $area, $desde, $hasta, '', '', '');

	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">No.</th>';
		$salida .= '<th class = "text-center" width = "30px">Orden No.</th>';
		$salida .= '<th class = "text-center" width = "120px">Sede</th>';
		$salida .= '<th class = "text-center" width = "50px">No. Activo</th>';
		$salida .= '<th class = "text-center" width = "120px">Activo</th>';
		$salida .= '<th class = "text-center" width = "120px">Usuario</th>';
		$salida .= '<th class = "text-center" width = "100px">Categor&iacute;a</th>';
		$salida .= '<th class = "text-center" width = "100px">Fecha</th>';
		$salida .= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$salida .= '<td class = "text-center">' . $i . '.</td>';
			//codigo
			$codigo = Agrega_CEros($row["pro_codigo"]);
			$salida .= '<td class = "text-center">#' . $codigo . '</td>';
			//sede
			$sede = utf8_decode(trim($row["sed_nombre"]));
			$salida .= '<td class = "text-left">' . $sede . '</td>';
			//activo
			$codigoActivo = Agrega_Ceros($row["act_codigo"]);
			$salida .= '<td class = "text-center">' . $codigoActivo . '</td>';
			//activo
			$activo = utf8_decode(trim($row["act_nombre"]));
			$salida .= '<td class = "text-left">' . $activo . '</td>';
			//usuario
			$usuario = utf8_decode(trim($row["usu_nombre"]));
			$salida .= '<td class = "text-left">' . $usuario . '</td>';
			//categoria
			$categoria = utf8_decode(trim($row["cat_nombre"]));
			$salida .= '<td class = "text-left">' . $categoria . '</td>';
			//fecha.
			$fecha = cambia_fecha($row["pro_fecha"]);
			$salida .= '<td class = "text-center">' . $fecha . '</td>';
			//codigo
			$codigo = $row["pro_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsPro->encrypt($codigo, $usu);
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<a class="btn btn-primary btn-xs" href = "FRMreasignar.php?hashkey=' . $hashkey . '" title = "Re-Asignar Orden de Trabajo" ><i class="fa fa-group"></i></a>';
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


function tabla_reprogramaciones($programacion)
{
	$ClsPro = new ClsProgramacionPPM();
	$result = $ClsPro->get_reprogramacion('', $programacion);

	if (is_array($result)) {
		$salida = '<table class="table table-striped" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">No.</th>';
		$salida .= '<th class = "text-center" width = "100px">Fecha anterior</th>';
		$salida .= '<th class = "text-center" width = "100px">Fecha Reprogramaci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "110px">Fecha Transacci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "100px">Usuario/reprogram&oacute;</th>';
		$salida .= '<th class = "text-center" width = "150px">Justificaci&oacute;n</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$salida .= '<td class = "text-center">' . $i . '.</td>';
			//fecha.
			$fecha = cambia_fecha($row["rep_fecha_anterior"]);
			$salida .= '<td class = "text-center">' . $fecha . '</td>';
			//fecha.
			$fecha = cambia_fecha($row["rep_fecha_nueva"]);
			$salida .= '<td class = "text-center">' . $fecha . '</td>';
			//fecha.
			$freg = cambia_fechaHora($row["rep_fecha_registro"]);
			$salida .= '<td class = "text-center">' . $freg . '</td>';
			//usuario
			$usuario = trim($row["usu_nombre"]);
			$salida .= '<td class = "text-left">' . $usuario . '</td>';
			//justificacion
			$justificacion = trim($row["rep_justificacion"]);
			$justificacion = nl2br($justificacion);
			$salida .= '<td class = "text-justify">' . $justificacion . '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	} else {
		$salida = '<div class="text-center">';
		$salida .= '<label class="text-muted">No hay reprogramaciones registradas...</label>';
		$salida .= '</div>';
	}

	return $salida;
}

function tabla_reasignaciones($programacion)
{
	$ClsPro = new ClsProgramacionPPM();
	$result = $ClsPro->get_reasignacion('', $programacion);

	if (is_array($result)) {
		$salida = '<table class="table table-striped" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">No.</th>';
		$salida .= '<th class = "text-center" width = "100px">Usuario anterior</th>';
		$salida .= '<th class = "text-center" width = "100px">Usuario Reasignaci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "110px">Fecha Transacci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "110px">Usuario/reasign&oacute;</th>';
		$salida .= '<th class = "text-center" width = "150px">Justificaci&oacute;n</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$salida .= '<td class = "text-center">' . $i . '.</td>';
			//usuario.
			$usuario = utf8_decode($row["nombre_usuario_anterior"]);
			$salida .= '<td class = "text-left">' . $usuario . '</td>';
			//usuario.
			$usuario = utf8_decode($row["nombre_usuario_nuevo"]);
			$salida .= '<td class = "text-left">' . $usuario . '</td>';
			//fecha.
			$freg = cambia_fechaHora($row["rea_fecha_registro"]);
			$salida .= '<td class = "text-center">' . $freg . '</td>';
			//usuario
			$usuario = utf8_decode($row["usu_nombre"]);
			$salida .= '<td class = "text-left">' . $usuario . '</td>';
			//justificacion
			$justificacion = utf8_decode($row["rea_justificacion"]);
			$justificacion = nl2br($justificacion);
			$salida .= '<td class = "text-justify">' . $justificacion . '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	} else {
		$salida = '<div class="text-center">';
		$salida .= '<label class="text-muted">No hay reasignaciones registradas...</label>';
		$salida .= '</div>';
	}

	return $salida;
}


function tabla_reportes($activo, $usuario, $categoria, $sede, $area, $desde, $hasta, $situacion, $columnas)
{
	$ClsPro = new ClsProgramacionPPM();
	$result = $ClsPro->get_programacion('', $activo, $usuario, $categoria, $sede, '', $area, $desde, $hasta, '', '', $situacion);

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
			$salida .= '<th class = "text-center" width = "100px">Activo</th>';
			$salida .= '<th class = "text-center" width = "100px">Usuario</th>';
			$salida .= '<th class = "text-center" width = "150px">Categor&iacute;a</th>';
			$salida .= '<th class = "text-center" width = "100px">Cuestionario</th>';
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
					if ($col == "cue_codigo") {
						$campo = '# ' . Agrega_Ceros($row[$campo]);
					} else if ($col == "cue_situacion") {
						$campo = trim($row[$campo]);
						$campo = ($campo == 1) ? '<strong class="text-success">Activa</strong>' : '<strong class="text-danger">Inactiva</strong>';
					} else if ($col == "cue_fotos" || $col == "cue_firma") {
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
						$campo = substr($dias, 0, -1);
					} else if ($col == "pro_hini_hfin") {
						$campo = trim($row["pro_hini"]) . "-" . trim($row["pro_hfin"]);;
					} else {
						$campo = trim($row[$campo]);
					}
					//columna
					$salida .= '<td class = "' . $alineacion . '">' . $campo . '</td>';
				}
			} else {
				//sede
				$sede = trim($row["sed_nombre"]);
				$salida .= '<td class = "text-left">' . $sede . '</td>';
				//area
				$area = trim($row["are_nombre"]);
				$salida .= '<td class = "text-left">' . $area . '</td>';
				//activo
				$activo = trim($row["act_nombre"]);
				$salida .= '<td class = "text-left">' . $activo . '</td>';
				//Usuario
				$usuario = trim($row["usuario_nombre"]);
				$salida .= '<td class = "text-left">' . $usuario . '</td>';
				//categoria
				$categoria = trim($row["cat_nombre"]);
				$salida .= '<td class = "text-left">' . $categoria . '</td>';
				//nombre
				$nom = trim($row["cue_nombre"]);
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
		case "cue_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Cuestionario";
			$respuesta["campo"] = "cue_codigo";
			break;
		case "cue_nombre":
			$respuesta["ancho"] = "110";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Nombre del cuestionario";
			$respuesta["campo"] = "cue_nombre";
			break;
		case "cue_fotos":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "&iquest;Requiere Foto?";
			$respuesta["campo"] = "cue_fotos";
			break;
		case "cue_firma":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "&iquest;Requiere Firma?";
			$respuesta["campo"] = "cue_firma";
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
		case "pro_observaciones":
			$respuesta["ancho"] = "150";
			$respuesta["alineacion"] = "text-justify";
			$respuesta["titulo"] = "Observaciones (Cuestionario)";
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
			$respuesta["titulo"] = "C&oacute;digo Sector";
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


function parametrosDinamicosPDF($columna)
{
	switch ($columna) {
		case "cue_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Cuestionario";
			$respuesta["campo"] = "cue_codigo";
			break;
		case "cue_nombre":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Nombre del cuestionario";
			$respuesta["campo"] = "cue_nombre";
			break;
		case "cue_fotos":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "¿Requiere Foto?";
			$respuesta["campo"] = "cue_fotos";
			break;
		case "cue_firma":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "¿Requiere Firma?";
			$respuesta["campo"] = "cue_firma";
			break;
		case "pro_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Progra.";
			$respuesta["campo"] = "pro_codigo";
			break;
		case "pro_dias":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Días Programados";
			$respuesta["campo"] = "pro_dias";
			break;
		case "pro_hini_hfin":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Intervalo Hor.";
			$respuesta["campo"] = "pro_hini_hfin";
			break;
		case "pro_observaciones":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "J";
			$respuesta["titulo"] = "Observaciones (Cuestionario)";
			$respuesta["campo"] = "pro_observaciones";
			break;
		case "cat_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Cate.";
			$respuesta["campo"] = "cat_codigo";
			break;
		case "cat_nombre":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Categoría";
			$respuesta["campo"] = "cat_nombre";
			break;
		case "cat_color":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Color";
			$respuesta["campo"] = "cat_color";
			break;
		case "are_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Área";
			$respuesta["campo"] = "are_codigo";
			break;
		case "are_nivel":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Nivel";
			$respuesta["campo"] = "are_nivel";
			break;
		case "are_nombre":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Área";
			$respuesta["campo"] = "are_nombre";
			break;
		case "sec_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Sector";
			$respuesta["campo"] = "sec_codigo";
			break;
		case "sec_nombre":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Sector";
			$respuesta["campo"] = "sec_nombre";
			break;
		case "sed_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Sede";
			$respuesta["campo"] = "sed_codigo";
			break;
		case "sed_nombre":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Sede";
			$respuesta["campo"] = "sed_nombre";
			break;
		case "sede_municipio":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Departamento / Municipio";
			$respuesta["campo"] = "sede_municipio";
			break;
		case "sed_direccion":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Dirección (Sede)";
			$respuesta["campo"] = "sed_direccion";
			break;
		case "sed_zona":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Zona";
			$respuesta["campo"] = "sed_zona";
			break;
	}
	return $respuesta;
}


function parametrosDinamicosEXCEL($columna)
{
	switch ($columna) {
		case "cue_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Cuestionario";
			$respuesta["campo"] = "cue_codigo";
			break;
		case "cue_nombre":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Nombre del cuestionario";
			$respuesta["campo"] = "cue_nombre";
			break;
		case "cue_fotos":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Requiere Foto?";
			$respuesta["campo"] = "cue_fotos";
			break;
		case "cue_firma":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Requiere Firma?";
			$respuesta["campo"] = "cue_firma";
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
		case "pro_observaciones":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "J";
			$respuesta["titulo"] = "Observaciones (Cuestionario)";
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
