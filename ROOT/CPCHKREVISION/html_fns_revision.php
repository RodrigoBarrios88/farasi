<?php
include_once('../html_fns.php');

function tabla_listas($codigo, $sede, $sector, $area, $tipo, $categorias)
{
	$ClsLis = new ClsLista();	
	$salida = '<table class="table table-striped dataTables-example" width="100%" >';
	$salida .= '<thead>';
	$salida .= '<tr>';
	$salida .= '<th class = "text-center" width = "10px">No.</th>';
	$salida .= '<th class = "text-center" width = "20px">QR</th>';
	$salida .= '<th class = "text-center" width = "150px">Sede</th>';
	$salida .= '<th class = "text-center" width = "150px">&Aacute;rea</th>';
	$salida .= '<th class = "text-center" width = "150px">Categor&iacute;a</th>';
	$salida .= '<th class = "text-center" width = "100px">Lista</th>';
	$salida .= '<th class = "text-center" width = "100px">Horario</th>';
	$salida .= '<th class = "text-center" width = "100px">Tipo</th>';
	$salida .= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
	$salida .= '</tr>';
	$salida .= '</thead>';
	$salida .= '<tbody>';
	$ii = 1;
	for ($i = 1; $i <= 3; $i++) {
		$result = null;
		switch ($i) {
			case 1:
				$dia = date("d");
				$result = $ClsLis->get_programacion('', '', $sede, $sector, $area, $categorias , $dia,date("H:i"), 1, '', date("d/m/Y"), date("d/m/Y"), 'M');
				break;
			case 2:
				$diaSemana = date("D");
				// echo $diaSemana;
				$result = $ClsLis->get_programacion('', '', $sede, $sector, $area, $categorias, $diaSemana, date("H:i"), 1, '', date("d/m/Y"), date("d/m/Y"), 'J');
				break;
			case 3:
				$fechaHoy = date('Y-m-d');
				$result = $ClsLis->get_programacion('', '', $sede, $sector, $area, $categorias, $fechaHoy,date("H:i"), 1, '', date("d/m/Y"), date("d/m/Y"), 'U');
				break;

		}
		if (is_array($result)) {
			foreach ($result as $row) {
				$ejecutada = trim($row["revision_ejecutada"]);
				//echo "$ejecutada, $activa <br>";ss
				if ($ejecutada == "") {
					$salida .= '<tr>';
					//No.
					$salida .= '<td class = "text-center">' . $ii . '.</td>';
					///TOTAL
					$total = $row['TOTAL'];
					//QR
					$area_codigo = Agrega_Ceros($row["are_codigo"]);
					$salida .= '<td class = "text-center">' . $area_codigo . '</td>';
					//sede
					$sedeP = utf8_decode($row["sed_nombre"]);
					$salida .= '<td class = "text-left">' . $sedeP . '</td>';
					//area
					$areaP = utf8_decode($row["are_nombre"]);
					$area_codigo = trim($row["are_codigo"]);
					$salida .= '<td class = "text-left">' . $areaP . '</td>';
					//categoria
					$categoriaP = utf8_decode($row["cat_nombre"]);
					$salida .= '<td class = "text-left">' . $categoriaP . '</td>';
					//nombre
					$nom = utf8_decode($row["list_nombre"]);
					$salida .= '<td class = "text-left">' . $nom . '</td>';
					//horario
					$horario = substr($row["pro_hini"], 0, 5) . "-" . substr($row["pro_hfin"], 0, 5);
					$salida .= '<td class = "text-center">' . $horario . '</td>';
					//tipo
					$tipo = utf8_decode($row['pro_tipo']);
					//$tipo = get_tipo($tipo);
					$salida .= '<td class = "text-center">' . TipoProgramacion($tipo)  .'</td>';
					//codigo
					$codigo = $row["list_codigo"];
					$progra = $row["pro_codigo"];
					$revision = trim($row["revision_activa"]);
					$usu = $_SESSION["codigo"];
					$hashkey1 = $ClsLis->encrypt($codigo, $usu);
					$hashkey2 = $ClsLis->encrypt($progra, $usu);
					$salida .= '<td class = "text-center" >';
					$salida .= '<div class="btn-group">';
					$salida .= '<a class="btn btn-white btn-xs" href = "../CPCHKLISTA/CPREPORTES/REPlista.php?hashkey=' . $hashkey2 . '" target="_blank" title = "Imprimir Check List" ><i class="fa fa-print"></i></a>';
					if ($revision != "") {
						$hashkey3 = $ClsLis->encrypt($revision, $usu);
						$salida .= '<a class="btn btn-success" href = "FRMlista.php?hashkey1=' . $hashkey1 . '&hashkey2=' . $hashkey2 . '&hashkey3=' . $hashkey3 . '" title = "Seleccionar Check List" > Activa (Revisi&oacute;n #' . $revision . ') <i class="fa fa-chevron-right"></i></a>';
					} else {
						$salida .= '<a class="btn btn-info" href = "FRMlista.php?hashkey1=' . $hashkey1 . '&hashkey2=' . $hashkey2 . '" title = "Seleccionar Check List" > Seleccionar <i class="fa fa-chevron-right"></i></a>';
					}
					$salida .= '</div>';
					$salida .= '</td>';
					//--
					$salida .= '</tr>';
					$ii++;
				}
			}
		}
	}
	$salida .= '</tbody>';
	$salida .= '</table>';
	return ($ii != 1) ? $salida : "<p class ='text-secondary text-center'>No tienes checklist programados para el dia de hoy</p>";
}


function tabla_revisiones($codigo, $lista, $usuario, $sede, $sector, $area, $categoria, $fini, $ffin, $situacion)
{
	$ClsRev = new ClsRevision();
	$result = $ClsRev->get_revision($codigo, $lista, $usuario, $sede, $sector, $area, $categoria, $fini, $ffin, $situacion);

	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">No.</th>';
		$salida .= '<th class = "text-center" width = "20px">QR</th>';
		$salida .= '<th class = "text-center" width = "10px">Revisi&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "150px">Sede</th>';
		$salida .= '<th class = "text-center" width = "150px">&Aacute;rea</th>';
		$salida .= '<th class = "text-center" width = "150px">Categor&iacute;a</th>';
		$salida .= '<th class = "text-center" width = "100px">Lista</th>';
		$salida .= '<th class = "text-center" width = "100px">Fecha/Hora</th>';
		$salida .= '<th class = "text-center" width = "100px">Status</th>';
		$salida .= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//No.
			$salida .= '<td class = "text-center">' . $i . '.</td>';
			//QR
			$area_codigo = Agrega_Ceros($row["are_codigo"]);
			$salida .= '<td class = "text-center">' . $area_codigo . '</td>';
			//revision
			$revision = Agrega_Ceros($row["rev_codigo"]);
			$salida .= '<td class = "text-center">#' . $revision . '</td>';
			//sede
			$sede = utf8_decode($row["sed_nombre"]);
			$salida .= '<td class = "text-left">' . $sede . '</td>';
			//area
			$area = utf8_decode($row["are_nombre"]);
			$salida .= '<td class = "text-left">' . $area . '</td>';
			//categoria
			$categoria = utf8_decode($row["cat_nombre"]);
			$salida .= '<td class = "text-left">' . $categoria . '</td>';
			//nombre
			$nom = utf8_decode($row["list_nombre"]);
			$salida .= '<td class = "text-left">' . $nom . '</td>';
			//fecha/hora
			$fechor = trim($row["rev_fecha_final"]);
			$fechor = cambia_fechaHora($fechor);
			$salida .= '<td class = "text-left">' . $fechor . '</td>';
			//stauts
			$situacion = trim($row["rev_situacion"]);
			$status = ($situacion == 1) ? '<strong class="text-info">En procesos</strong>' : '<strong class="text-muted">Finalizada</strong>';
			$salida .= '<td class = "text-center">' . $status . '</td>';
			//codigo
			$codigo = $row["rev_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsRev->encrypt($codigo, $usu);
			$salida .= '<td class = "text-center" >';
			$salida .= '<a class="btn btn-info btn-lg" href = "FRMrevision.php?hashkey=' . $hashkey . '" title = "Seleccionar Revisi&oacute;n" ><i class="fa fa-search"></i></a> ';
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



function tabla_respuestas($revision, $sede, $sector, $area, $categoria, $desde, $hasta)
{
	$ClsRev = new ClsRevision();
	$result = $ClsRev->get_resultados($revision, '', '', $sede, $sector, $area, $categoria, $desde, $hasta);

	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-promt" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">No.</th>';
		$salida .= '<th class = "text-center" width = "30px">Revisi&oacute;n</th>';
		$salida .= '<th class = "text-left" width = "100px">Lista</th>';
		$salida .= '<th class = "text-left" width = "100px">Categor&iacute;a</th>';
		$salida .= '<th class = "text-center" width = "100px">Fecha</th>';
		$salida .= '<th class = "text-left" width = "120px">Pregunta</th>';
		$salida .= '<th class = "text-center" width = "150px">Respuesta</th>';
		$salida .= '<th class = "text-center" width = "150px">Observaciones en Revisi&oacute;n</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$salida .= '<td class = "text-center" >' . $i . '. </td>';
			//nombre
			$codigo = Agrega_Ceros($row["rev_codigo"]);
			$salida .= '<td class = "text-center"># ' . $codigo . '</td>';
			//lista
			$lista = utf8_decode($row["list_nombre"]);
			$salida .= '<td class = "text-left">' . $lista . '</td>';
			//categoria
			$categoria = utf8_decode($row["cat_nombre"]);
			$salida .= '<td class = "text-left">' . $categoria . '</td>';
			//fecha
			$fecha = cambia_fechaHora($row["resp_fecha_registro"]);
			$salida .= '<td class = "text-center">' . $fecha . '</td>';
			//pregunta
			$pregunta = utf8_decode($row["pre_pregunta"]);
			$salida .= '<td class = "text-left">' . $pregunta . '</td>';
			//respuesta
			$resp = trim($row["resp_respuesta"]);
			if ($resp == 1) {
				$respuesta = '<span class="text-info">SI</span>';
			} else if ($resp == 2) {
				$respuesta = '<strong class="text-danger">NO</strong>';
			} else {
				$respuesta = '<strong class="text-muted">No aplica</strong>';
			}
			$salida .= '<td class = "text-center">' . $respuesta . '</td>';
			//observaciones
			$obs = utf8_decode($row["rev_observaciones"]);
			$salida .= '<td class = "text-justify">' . $obs . '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	} else {
		$salida = '<h5 class="alert alert-info text-center" width="100%">';
		$salida .= '<i class="fa fa-ban"></i> No hay respuestas con estos parametros de busqueda...';
		$salida .= '</h5>';
	}

	return $salida;
}



function tabla_resultados($periodo, $sede, $sector, $area, $categoria, $fini, $ffin)
{
	$ClsRev = new ClsRevision();

	$salida = '<table class="table table-striped" width="100%" >';
	$salida .= '<thead>';
	$salida .= '<tr>';
	$salida .= '<th class = "text-center" width = "30px">No.</th>';
	$salida .= '<th class = "text-center" width = "150px"></th>';
	$salida .= '<th class = "text-center" width = "150px">Respuestas SI</th>';
	$salida .= '<th class = "text-center" width = "100px">Respuestas NO</th>';
	$salida .= '<th class = "text-center" width = "100px">Respuestas N/A</th>';
	$salida .= '<th class = "text-center" width = "100px">% Cumplimiento</th>';
	$salida .= '</tr>';
	$salida .= '</thead>';
	$salida .= '<tbody>';
	$num = 1;
	$dia_inicio = "";
	$SI = 0;
	$NO = 0;
	$NA = 0;
	$total = 0;
	$TOTALSI = 0;
	$TOTALNO = 0;
	$TOTALNA = 0;
	//--
	if ($periodo == "D") {
		$fechaInicio = strtotime(regresa_fecha($fini));
		$fechaFin = strtotime(regresa_fecha($ffin));
		for ($i = $fechaInicio; $i <= $fechaFin; $i += 86400) {
			$fecha = date("d/m/Y", $i);
			$dia = date("w", $i);
			$dia = ($dia == 0) ? 7 : $dia;
			$dia_nombre = Dias_Letra($dia);
			$SI = $ClsRev->count_resultados('', '', '', $sede, $sector, $area, $categoria, $fecha, $fecha, '', 1);
			$NO = $ClsRev->count_resultados('', '', '', $sede, $sector, $area, $categoria, $fecha, $fecha, '', 2);
			$NA = $ClsRev->count_resultados('', '', '', $sede, $sector, $area, $categoria, $fecha, $fecha, '', 3);
			$total_si = $SI;
			$total_no = $NO;
			$total = $total_si + $total_no;
			if ($total > 0) {
				$porcentaje = round(($total_si * 100) / $total);
			} else {
				$porcentaje = 0;
			}
			$TOTALSI += $SI;
			$TOTALNO += $NO;
			$TOTALNA += $NA;
			$salida .= '<tr>';
			//--
			$salida .= '<td class = "text-center">' . $num . '.- </td>';
			$salida .= '<td class = "text-left">' . $dia_nombre . ' ' . $fecha . '</td>';
			$salida .= '<td class = "text-center">' . $SI . '</td>';
			$salida .= '<td class = "text-center">' . $NO . '</td>';
			$salida .= '<td class = "text-center">' . $NA . '</td>';
			$salida .= '<td class = "text-center">' . $porcentaje . ' %</td>';
			//--
			$salida .= '</tr>';
			$num++;
		}
	} else if ($periodo == "S") {
		$fini = regresa_fecha($fini);
		$ffin = regresa_fecha($ffin);
		$anio1 = substr($fini, 0, 4);
		$anio2 = substr($ffin, 0, 4);
		if ($anio1 == $anio2) {
			$W1 = date("W", strtotime(date($fini)));
			$W2 = date("W", strtotime(date($ffin)));
			$dia_inicio = date("w", strtotime($fini));
			$num = 1;
			for ($i = $W1; $i <= $W2; $i++) {
				$fecha_ini = daysOfWeek($anio1, $i, 1);
				$fecha_fin = daysOfWeek($anio1, ($i + 1), 0);
				$fecha_ini = cambia_fecha($fecha_ini);
				$fecha_fin = cambia_fecha($fecha_fin);
				$SI = $ClsRev->count_resultados('', '', '', $sede, $sector, $area, $categoria, $fecha_ini, $fecha_fin, '', 1);
				$NO = $ClsRev->count_resultados('', '', '', $sede, $sector, $area, $categoria, $fecha_ini, $fecha_fin, '', 2);
				$NA = $ClsRev->count_resultados('', '', '', $sede, $sector, $area, $categoria, $fecha_ini, $fecha_fin, '', 3);
				$total_si = $SI;
				$total_no = $NO;
				$total = $total_si + $total_no;
				if ($total > 0) {
					$porcentaje = round(($total_si * 100) / $total);
				} else {
					$porcentaje = 0;
				}
				$TOTALSI += $SI;
				$TOTALNO += $NO;
				$TOTALNA += $NA;
				$salida .= '<tr>';
				//--
				$salida .= '<td class = "text-center">' . $num . '.- </td>';
				$salida .= '<td class = "text-left"> Semana ' . $i . ' (' . $fecha_ini . ' al ' . $fecha_fin . ')</td>';
				$salida .= '<td class = "text-center">' . $SI . '</td>';
				$salida .= '<td class = "text-center">' . $NO . '</td>';
				$salida .= '<td class = "text-center">' . $NA . '</td>';
				$salida .= '<td class = "text-center">' . $porcentaje . ' %</td>';
				//--
				$salida .= '</tr>';
				$num++;
			}
		} else {
			$salida .= '<tr>';
			$salida .= '<td colspan = "6" class = "text-center"><strong class="text-danger">Las fechas deben pertenecer al mismo a&ntilde;o...</strong></td>';
			$salida .= '</tr>';
		}
	} else if ($periodo == "M") {
		$fini = regresa_fecha($fini);
		$ffin = regresa_fecha($ffin);
		$mes1 = substr($fini, 5, 2);
		$mes2 = substr($ffin, 5, 2);
		//--
		$anio1 = substr($fini, 0, 4);
		$anio2 = substr($ffin, 0, 4);
		if ($anio1 == $anio2) {
			$num = 1;
			for ($i = $mes1; $i <= $mes2; $i++) {
				$mes_nombre = Meses_Letra($i);
				$fecha_ini = "01/$i/$anio1";
				$fecha_fin = "31/$i/$anio1";
				//echo "$mes_nombre: $fecha_ini - $fecha_fin<br>";
				$SI = $ClsRev->count_resultados('', '', '', $sede, $sector, $area, $categoria, $fecha_ini, $fecha_fin, '', 1);
				$NO = $ClsRev->count_resultados('', '', '', $sede, $sector, $area, $categoria, $fecha_ini, $fecha_fin, '', 2);
				$NA = $ClsRev->count_resultados('', '', '', $sede, $sector, $area, $categoria, $fecha_ini, $fecha_fin, '', 3);
				$total_si = $SI;
				$total_no = $NO;
				$total = $total_si + $total_no;
				if ($total > 0) {
					$porcentaje = round(($total_si * 100) / $total);
				} else {
					$porcentaje = 0;
				}
				$TOTALSI += $SI;
				$TOTALNO += $NO;
				$TOTALNA += $NA;
				$salida .= '<tr>';
				//--
				$salida .= '<td class = "text-center">' . $num . '.- </td>';
				$salida .= '<td class = "text-left">' . $mes_nombre . '</td>';
				$salida .= '<td class = "text-center">' . $SI . '</td>';
				$salida .= '<td class = "text-center">' . $NO . '</td>';
				$salida .= '<td class = "text-center">' . $NA . '</td>';
				$salida .= '<td class = "text-center">' . $porcentaje . ' %</td>';
				//--
				$salida .= '</tr>';
				$num++;
			}
		} else {
			$salida .= '<tr>';
			$salida .= '<td colspan = "6" class = "text-center"><strong class="text-danger">Las fechas deben pertenecer al mismo a&ntilde;o...</strong></td>';
			$salida .= '</tr>';
		}
	}
	//////////////// TOTALES DE TABLA ///////////////////
	$TOTAL = $TOTALSI + $TOTALNO;
	if ($TOTAL > 0) {
		$PORCENTAJE = round(($TOTALSI * 100) / $TOTAL);
	} else {
		$PORCENTAJE = 0;
	}
	$salida .= '<tr>';
	//--
	$salida .= '<th class = "text-center"> </th>';
	$salida .= '<th class = "text-right"> Totales &nbsp; </th>';
	$salida .= '<th class = "text-center">' . $TOTALSI . '</th>';
	$salida .= '<th class = "text-center">' . $TOTALNO . '</th>';
	$salida .= '<th class = "text-center">' . $TOTALNA . '</th>';
	$salida .= '<th class = "text-center">' . $PORCENTAJE . ' %</th>';
	//--
	$salida .= '</tr>';
	/////////---------
	$salida .= '</tbody>';
	$salida .= '</table>';

	return $salida;
}


function tabla_reportes($usuario, $sede, $sector, $area, $categoria, $fini, $ffin, $columnas)
{
	$ClsRev = new ClsRevision();
	$result = $ClsRev->get_revision('', '', $usuario, $sede, $sector, $area, $categoria, $fini, $ffin, '1,2');

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
					if ($col == "rev_codigo") {
						$campo = '# ' . Agrega_Ceros($row[$campo]);
					} else if ($col == "rev_fecha_inicio") {
						$campo = cambia_fechaHora($row[$campo]);
					} else if ($col == "rev_fecha_final") {
						$campo = cambia_fechaHora($row[$campo]);
					} else if ($col == "rev_situacion") {
						$campo = trim($row[$campo]);
						$campo = ($campo == 1) ? '<strong class="text-success">En Proceso</strong>' : '<strong class="text-muted">Finalizado</strong>';
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
					} else if ($col == "pro_hini_hfin") {
						$campo = trim($row["pro_hini"]) . "-" . trim($row["pro_hfin"]);
					} else if ($col == "pro_tipo") {
						$campo = (trim($row["pro_tipo"]) == "S") ? "Semanal" : "Mensual";
					} else if ($col == "rev_nota") {
						$si = $row['rev_cont_si'];
						$no = $row['rev_cont_no'];
						$na = $row['rev_cont_na'];
						$total_si = ($si + $na);
						$total_no = $no;
						$total_respuestas = $total_si + $total_no;
						if ($total_respuestas > 0) {
							$porcent_si = round(($total_si * 100) / $total_respuestas);
							$porcent_no = round(($total_no * 100) / $total_respuestas);
							//$total_na = round(($total_na*100)/$total_respuestas);
						} else {
							$porcent_si = 0;
							$porcent_si = 0;
							//$total_na = 0;
						}
						$campo = "$porcent_si %";
					} else if ($col == "rev_firma") {
						$codigo = trim($row["rev_codigo"]);
						$campo = '<button type = "button" class="btn btn-success" onclick = "verFirma(' . $codigo . ')" title = "Ver Firma" ><i class="fa fa-search"></i></button>';
					} else if ($col == "rev_foto") {
						$codigo = trim($row["rev_codigo"]);
						$campo = '<button type = "button" class="btn btn-success" onclick = "verFotos(' . $codigo . ')" title = "Ver Fotos" ><i class="fa fa-search"></i></button>';
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
		case "rev_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Revisi&oacute;n";
			$respuesta["campo"] = "rev_codigo";
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
		case "pro_tipo":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Tipo de Programaci&oacute;n";
			$respuesta["campo"] = "pro_tipo";
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
			//////////////////
		case "rev_firma":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Firma";
			$respuesta["campo"] = "rev_firma";
			break;
		case "rev_foto":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Foto";
			$respuesta["campo"] = "rev_foto";
			break;
		case "rev_cont_si":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Repsuestas SI";
			$respuesta["campo"] = "rev_cont_si";
			break;
		case "rev_cont_no":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "Repsuestas NO";
			$respuesta["titulo"] = "&Aacute;rea";
			$respuesta["campo"] = "rev_cont_no";
			break;
		case "rev_cont_na":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "Respuestas N/A";
			$respuesta["titulo"] = "&Aacute;rea";
			$respuesta["campo"] = "rev_cont_na";
			break;
		case "rev_nota":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "% Cumplimiento";
			$respuesta["campo"] = "rev_nota";
			break;
			/////////////////////
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
		case "usuario_nombre":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Usuario (Registr&oacute;)";
			$respuesta["campo"] = "usuario_nombre";
			break;
	}
	return $respuesta;
}


function parametrosDinamicosPDF($columna)
{
	switch ($columna) {
		case "rev_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Código";
			$respuesta["campo"] = "rev_codigo";
			break;
		case "rev_fecha_inicio":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fec/hora Inicio";
			$respuesta["campo"] = "rev_fecha_inicio";
			break;
		case "rev_fecha_final":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fec/hora Finalización";
			$respuesta["campo"] = "rev_fecha_final";
			break;
		case "rev_observaciones":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "J";
			$respuesta["titulo"] = "Observaciones";
			$respuesta["campo"] = "rev_observaciones";
			break;
		case "rev_situacion":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Situación";
			$respuesta["campo"] = "rev_situacion";
			break;
		case "list_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Lista";
			$respuesta["campo"] = "list_codigo";
			break;
		case "list_nombre":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Nombre de la Lista";
			$respuesta["campo"] = "list_nombre";
			break;
		case "list_fotos":
			$respuesta["ancho"] = "33";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "¿Requiere Foto?";
			$respuesta["campo"] = "list_fotos";
			break;
		case "list_firma":
			$respuesta["ancho"] = "33";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "¿Requiere Firma?";
			$respuesta["campo"] = "list_firma";
			break;
		case "pro_tipo":
			$respuesta["ancho"] = "33";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Tipo de Programacion";
			$respuesta["campo"] = "pro_tipo";
			break;
		case "pro_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Progra.";
			$respuesta["campo"] = "pro_codigo";
			break;
		case "pro_dias":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "L";
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
			$respuesta["titulo"] = "Observaciones (Lista)";
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
			$respuesta["alineacion"] = "L";
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
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Nivel";
			$respuesta["campo"] = "are_nivel";
			break;
		case "are_nombre":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Área";
			$respuesta["campo"] = "are_nombre";
			break;
			//////////////////
		case "rev_firma":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Firma";
			$respuesta["campo"] = "rev_firma";
			break;
		case "rev_foto":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Foto";
			$respuesta["campo"] = "rev_foto";
			break;
		case "rev_cont_si":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Respuestas SI";
			$respuesta["campo"] = "rev_cont_si";
			break;
		case "rev_cont_no":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Respuestas NO";
			$respuesta["campo"] = "rev_cont_no";
			break;
		case "rev_cont_na":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Respuestas N/A";
			$respuesta["campo"] = "rev_cont_na";
			break;
		case "rev_nota":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "% Cumplimiento";
			$respuesta["campo"] = "rev_nota";
			break;
			/////////////////////
		case "sec_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Sector";
			$respuesta["campo"] = "sec_codigo";
			break;
		case "sec_nombre":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "L";
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
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Sede";
			$respuesta["campo"] = "sed_nombre";
			break;
		case "sede_municipio":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Departamento / Municipio";
			$respuesta["campo"] = "sede_municipio";
			break;
		case "sed_direccion":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Dirección (Sede)";
			$respuesta["campo"] = "sed_direccion";
			break;
		case "sed_zona":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Zona";
			$respuesta["campo"] = "sed_zona";
			break;
		case "usuario_nombre":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Usuario (Registró)";
			$respuesta["campo"] = "usuario_nombre";
			break;
	}
	return $respuesta;
}


function parametrosDinamicosEXCEL($columna)
{
	switch ($columna) {
		case "rev_codigo":
			$respuesta["ancho"] = "18";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Revision";
			$respuesta["campo"] = "rev_codigo";
			break;
		case "rev_fecha_inicio":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha/hora Inicio";
			$respuesta["campo"] = "rev_fecha_inicio";
			break;
		case "rev_fecha_final":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha/hora Finaliza";
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
			$respuesta["titulo"] = "Situacion";
			$respuesta["campo"] = "rev_situacion";
			break;
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
		case "pro_tipo":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Tipo de Programacion";
			$respuesta["campo"] = "pro_tipo";
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
			//////////////////
		case "rev_firma":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Firma";
			$respuesta["campo"] = "rev_firma";
			break;
		case "rev_foto":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Foto";
			$respuesta["campo"] = "rev_foto";
			break;
		case "rev_cont_si":
			$respuesta["ancho"] = "20";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Respuestas SI";
			$respuesta["campo"] = "rev_cont_si";
			break;
		case "rev_cont_no":
			$respuesta["ancho"] = "20";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Respuestas NO";
			$respuesta["campo"] = "rev_cont_no";
			break;
		case "rev_cont_na":
			$respuesta["ancho"] = "20";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Respuestas N/A";
			$respuesta["campo"] = "rev_cont_na";
			break;
		case "rev_nota":
			$respuesta["ancho"] = "20";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "% Cumplimiento";
			$respuesta["campo"] = "rev_nota";
			break;
			///////////////
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
		case "usuario_nombre":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Usuario (Registro)";
			$respuesta["campo"] = "usuario_nombre";
			break;
	}
	return $respuesta;
}


function combo_semanas($name, $class = '')
{

	$salida = '<select name="' . $name . '" id="' . $name . '" class = "' . $class . ' form-control">';
	$salida .= '<option value="">Seleccione</option>';
	$salida .= '</select>';

	return $salida;
}

function daysOfWeek($anio, $semana, $dia_semana)
{
	return date("Y-m-d", strtotime($anio . "-W" . $semana . '-' . $dia_semana));
}
function TipoProgramacion($programacion){
	if($programacion == 'S'){
		$result = 'Semanal';
	}else if($programacion == 'U'){
		$result = 'Unico';
	}else if($programacion == 'M'){
		$result = 'Mensual';
	}
	return $result;
}