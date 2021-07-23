<?php
include_once('../html_fns.php');

function tabla_fichas($codigo, $tipo, $usuario, $situacion, $pertenece)
{
	$situacion = ($situacion == "") ? "1,2,3,4" : $situacion;
	$ClsFic = new ClsFicha();
	$result = $ClsFic->get_ficha($codigo, $tipo, $usuario, "", "", $pertenece, $situacion);

	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">Codigo.</th>';
		$salida .= '<th class = "text-center" width = "150px">Tipo</th>';
		$salida .= '<th class = "text-center" width = "150px">Proceso</th>';
		$salida .= '<th class = "text-center" width = "150px">Situaci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "100px"><i class="fa fa-cogs"></i></th>';
		if ($pertenece == 0) $salida .= '<th class = "text-center" width = "100px">Ver Subprocesos</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = agrega_ceros($row["fic_codigo"]);
			$salida .= '<td class = "text-center">' . $codigo . '.</td>';
			//tipo
			$tipo = utf8_decode($row["tit_nombre"] . " " . $row["sub_nombre"]);
			$salida .= '<td class = "text-left">' . $tipo . '</td>';
			//nombre
			$nombre = utf8_decode($row["fic_nombre"]);
			$salida .= '<td class = "text-left">' . $nombre . '</td>';
			//situacion
			$situacion = trim($row["fic_situacion"]);
			switch ($situacion) {
				case 1:
					$situacion = '<span class="text-muted">En Edici&oacute;n</span>';
					break;
				case 2:
					$situacion = '<span class="text-primary">En Aprobaci&oacute;n</span>';
					break;
				case 3:
					$situacion = '<strong class="text-success">Aprobado</strong>';
					break;
				case 4:
					$situacion = '<strong class="text-info">En Actualizacion</strong>';
					break;
				case 0:
					$situacion = '<strong class="text-danger">Descartado</strong>';
					break;
			}
			$salida .= '<td class = "text-center">' . $situacion . '</td>';
			$situacion = trim($row["fic_situacion"]);
			$usu = $_SESSION["codigo"];
			$codigo = trim($row["fic_codigo"]);
			$hashkey = $ClsFic->encrypt($codigo, $usu);
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			if ($situacion  == 1) {
				$salida .= '<a class="btn btn-white btn-xs" href = "FRMdetalle.php?hashkey=' . $hashkey . '" title = "Seleccionar Ficha" ><i class="fa fa-pencil"></i></a>';
				$salida .= '<a class="btn btn-white btn-xs" href = "CPREPORTES/REPficha.php?hashkey=' . $hashkey . '" target="_blank" title = "Imprimir Ficha" ><i class="fa fa-print"></i></a>';
				$salida .= '<button type="button" class="btn btn-info btn-outline" onclick="solicitarAprobacion(' . $codigo . ');" title = "Solicitar Aprobacion" ><i class="fa fa-exclamation-circle"></i></a>';
				$salida .= '<button type="button" class="btn btn-danger" onclick="eliminarFicha(' . $codigo . ');" title = "Descartar Ficha de Procesos" ><i class="fa fa-trash"></i></a>';
			} else if ($situacion  == 2) {
				$salida .= '<a class="btn btn-white btn-xs" href = "FRMdetalle.php?hashkey=' . $hashkey . '" title = "Seleccionar Ficha" disabled><i class="fa fa-pencil"></i></a>';
				$salida .= '<a class="btn btn-white btn-xs" href = "CPREPORTES/REPficha.php?hashkey=' . $hashkey . '" target="_blank" title = "Imprimir Ficha" ><i class="fa fa-print"></i></a>';
				$salida .= '<button type="button" class="btn btn-info" title = "En Revisi&oacute;n.." disabled ><i class="fa fa-exclamation-circle"></i></a>';
				$salida .= '<button type="button" class="btn btn-danger" onclick="eliminarFicha(' . $codigo . ');" title = "Descartar Ficha de Procesos" ><i class="fa fa-trash"></i></a>';
			} else {
				$salida .= '<a class="btn btn-white btn-xs" href = "javascript:void(0)" disabled title = "Seleccionar Ficha" disabled><i class="fa fa-pencil"></i></a>';
				$salida .= '<a class="btn btn-white btn-xs" href = "CPREPORTES/REPficha.php?hashkey=' . $hashkey . '" target="_blank" title = "Imprimir Ficha" ><i class="fa fa-print"></i></a>';
				$salida .= '<button type="button" class="btn btn-success" title = "Aprobada" disabled ><i class="fa fa-check"></i></a>';
				$salida .= '<button type="button" class="btn btn-danger" href = "javascript:void(0)" disabled title = "Descartar Ficha de Procesos" ><i class="fa fa-trash"></i></a>';
			}
			$salida .= '</div>';
			$salida .= '</td>';
			if ($pertenece == 0) {
				// Subprocesos
				$salida .= '<td class = "text-center" >';
				$salida .= '<div class="btn-group">';
				$salida .= '<a class="btn btn-white btn-xs" href = "FRMsubproceso.php?hashkey=' . $hashkey . '" title = "Subprocesos" ><i class="fa fa-file-export"></i></a>';
				$salida .= '</div>';
				$salida .= '</td>';
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

function tabla_fichas_aprobacion()
{
	$ClsFic = new ClsFicha();
	$result = $ClsFic->get_ficha("", "", "", "", "", "", "2,3");

	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">No.</th>';
		$salida .= '<th class = "text-center" width = "150px">Tipo</th>';
		$salida .= '<th class = "text-center" width = "150px">Proceso</th>';
		$salida .= '<th class = "text-center" width = "150px">Situaci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "100px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		$totalFichasSinAprobar = count_fichas_sin_aprobar();
		foreach ($result as $row) {
			if (($i <= $totalFichasSinAprobar && ($i % 2) == 0)) {
				$salida .= '<tr class="files_in_aprobation_pares">';
			} else if (($i <= $totalFichasSinAprobar && ($i % 2) != 0)) {
				$salida .= '<tr class="files_in_aprobation_impares">';
			} else {
				$salida .= '<tr>';
			}
			//No.
			$salida .= '<td class = "text-center">' . $i . '.</td>';
			//tipo
			$tipo = utf8_decode($row["tit_nombre"] . " " . $row["sub_nombre"]);
			$salida .= '<td class = "text-left">' . $tipo . '</td>';
			//nombre
			$nombre = utf8_decode($row["fic_nombre"]);
			$salida .= '<td class = "text-left">' . $nombre . '</td>';
			//situacion
			$situacion = trim($row["fic_situacion"]);
			switch ($situacion) {
				case 1:
					$situacion = '<span class="text-muted">En Edici&oacute;n</span>';
					break;
				case 2:
					$situacion = '<span class="text-info">En Aprobaci&oacute;n</span>';
					break;
				case 3:
					$situacion = '<span class="text-primary">Aprobado</span>';
					break;
				case 4:
					$situacion = '<span class="text-primary">Modificar Ficha</span>';
					break;
			}
			$salida .= '<td class = "text-center">' . $situacion . '</td>';
			//codigo
			$codigo = $row["fic_codigo"];
			$situacion = trim($row["fic_situacion"]);
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsFic->encrypt($codigo, $usu);
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<a class="btn btn-white btn-xs" href = "CPREPORTES/REPficha.php?hashkey=' . $hashkey . '" target="_blank" title = "Imprimir Ficha" ><i class="fa fa-print"></i></a>';
			if ($situacion  == 2) {
				$salida .= '<button type="button" class="btn btn-info btn-outline" onclick="aprobarFicha(' . $codigo . ');" title = "Aprobar Ficha"><i class="fas fa-clipboard-check"></i></a>';
				$salida .= '<button type="button" class="btn btn-danger btn-outline" onclick="actualizarFicha(' . $codigo . ');" title = "Actualizar Ficha"><i class="fa fa-pencil-square-o"></i></a>';
			} else if ($situacion  == 3) {
				$salida .= '<button type="button" class="btn btn-white text-success btn-outline" onclick="actualizarFicha(' . $codigo . ');" title = "Actualizar Ficha"><i class="fas fa-check-double"></i>Aprobada (Solicitar Actualizaci&oacute;n)</a>';
			}
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

function tabla_mis_fichas($codigo, $usuario)
{
	$ClsFic = new ClsFicha();
	$result = $ClsFic->get_ficha_usuario($codigo, "", $usuario);

	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">Codigo.</th>';
		$salida .= '<th class = "text-center" width = "150px">Tipo</th>';
		$salida .= '<th class = "text-center" width = "150px">Nombre</th>';
		$salida .= '<th class = "text-center" width = "150px">Situaci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "100px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = agrega_ceros($row["fic_codigo"]);
			$salida .= '<td class = "text-center">' . $codigo . '.</td>';
			//tipo
			$tipo = ($row["fic_pertenece"] == 0) ? "Proceso" : "Subproceso";
			$salida .= '<td class = "text-left">' . $tipo . '</td>';
			//nombre
			$nombre = utf8_decode($row["fic_nombre"]);
			$salida .= '<td class = "text-left">' . $nombre . '</td>';
			//situacion
			$situacion = trim($row["fic_situacion"]);
			switch ($situacion) {
				case 1:
					$situacion = '<span class="text-muted">En Edici&oacute;n</span>';
					break;
				case 2:
					$situacion = '<span class="text-primary">En Aprobaci&oacute;n</span>';
					break;
				case 3:
					$situacion = '<strong class="text-success">Aprobado</strong>';
					break;
				case 4:
					$situacion = '<strong class="text-info">Actualizar Ficha</strong>';
					break;
				case 0:
					$situacion = '<strong class="text-danger">Descartado</strong>';
					break;
			}
			$salida .= '<td class = "text-center">' . $situacion . '</td>';
			$situacion = trim($row["fic_situacion"]);
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsFic->encrypt($codigo, $usu);
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$codigo = trim($row["fic_codigo"]);
			if ($situacion  == 1) {
				$salida .= '<a class="btn btn-white btn-xs" href = "FRMdetalle.php?hashkey=' . $hashkey . '" title = "Seleccionar Ficha" ><i class="fa fa-pencil"></i></a>';
				$salida .= '<a class="btn btn-white btn-xs" href = "CPREPORTES/REPficha.php?hashkey=' . $hashkey . '" target="_blank" title = "Imprimir Ficha" ><i class="fa fa-print"></i></a>';
				$salida .= '<button type="button" class="btn btn-info btn-outline" onclick="solicitarAprobacion(' . $codigo . ');" title = "Solicitar Aprobacion" ><i class="fa fa-exclamation-circle"></i></a>';
				$salida .= '<button type="button" class="btn btn-danger" onclick="eliminarFicha(' . $codigo . ');" title = "Descartar Ficha de Procesos" ><i class="fa fa-trash"></i></a>';
			} else if ($situacion  == 2) {
				$salida .= '<a disabled class="btn btn-white btn-xs" href = "FRMdetalle.php?hashkey=' . $hashkey . '" title = "Seleccionar Ficha" ><i class="fa fa-pencil"></i></a>';
				$salida .= '<a class="btn btn-white btn-xs" href = "CPREPORTES/REPficha.php?hashkey=' . $hashkey . '" target="_blank" title = "Imprimir Ficha" ><i class="fa fa-print"></i></a>';
				$salida .= '<button type="button" class="btn btn-info" title = "En Revisi&oacute;n.." disabled ><i class="fa fa-exclamation-circle"></i></a>';
				$salida .= '<button type="button" class="btn btn-danger" onclick="eliminarFicha(' . $codigo . ');" title = "Descartar Ficha de Procesos" ><i class="fa fa-trash"></i></a>';
			} else if ($situacion == 4) {
				$salida .= '<a class="btn btn-white btn-xs" href = "FRMdetalle.php?hashkey=' . $hashkey . '" title = "Seleccionar Ficha" ><i class="fa fa-pencil"></i></a>';
				$salida .= '<a class="btn btn-white btn-xs" href = "CPREPORTES/REPficha.php?hashkey=' . $hashkey . '" target="_blank" title = "Imprimir Ficha" ><i class="fa fa-print"></i></a>';
				$salida .= '<button type="button" class="btn btn-info btn-outline" onclick="solicitarAprobacion(' . $codigo . ');" title = "Solicitar Aprobacion" ><i class="fa fa-exclamation-circle"></i></a>';
				$salida .= '<button type="button" class="btn btn-danger" onclick="eliminarFicha(' . $codigo . ');" title = "Descartar Ficha de Procesos" ><i class="fa fa-trash"></i></a>';
			} else {
				$salida .= '<a disabled class="btn btn-white btn-xs" href = "javascript:void(0)" disabled title = "Seleccionar Ficha" ><i class="fa fa-pencil"></i></a>';
				$salida .= '<a class="btn btn-white btn-xs" href = "CPREPORTES/REPficha.php?hashkey=' . $hashkey . '" target="_blank" title = "Imprimir Ficha" ><i class="fa fa-print"></i></a>';
				$salida .= '<button type="button" class="btn btn-success" title = "Aprobada" disabled ><i class="fa fa-check"></i></a>';
				$salida .= '<button type="button" class="btn btn-danger" href = "javascript:void(0)" disabled title = "Descartar Ficha de Procesos" ><i class="fa fa-trash"></i></a>';
			}
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


function tabla_foda($detalle, $proceso, $tipo, $visualiza = false)
{
	$ClsFic = new ClsFicha();
	$result = $ClsFic->get_foda('', $proceso, $tipo);

	$salida = '<div class="scroll-container">';
	$salida .= '<table class="table table-striped table-bordered" >';
	$salida .= '<thead>';
	$salida .= '<tr>';
	$salida .= '<th class = "text-center" width = "10px">No.</th>';
	$salida .= '<th class = "text-left" width = "150px">Sistema</th>';
	$salida .= '<th class = "text-left" width = "250px">Descripci&oacute;n</th>';
	$salida .= '<th class = "text-left" width = "50px">Peso</th>';
	if ($visualiza != true) { //solo para CRUD
		$salida .= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
	}
	$salida .= '</tr>';
	$salida .= '</thead>';
	$salida .= '<tbody>';
	$total = 0;
	if (is_array($result)) {
		$i = 1;
		foreach ($result as $row) {
			$codigo = $row["fod_codigo"];
			if ($detalle == $codigo) {
				$salida .= '<tr>';
				//No.
				$salida .= '<td class = "text-center">' . $i . '.</td>';
				//Sistema
				$sistema = trim($row["fod_sistema"]);
				$salida .= '<td class = "text-left">' . sistema_html('sistema') . '</td>';
				//Nombre
				$descripcion = trim($row["fod_descripcion"]);
				$salida .= '<td class = "text-left"><input type="text" class="form-control input-table" id ="foda" name ="foda" width="150px" value="' . $descripcion . '" onkeyup = "texto(this);" ></td>';
				//Peso
				$peso = floatval($row["fod_peso"]);
				$total += $peso;
				$salida .= '<td class = "text-left"><input type="text" class="form-control input-table" id ="peso" name ="peso" width="50px" value="' . $peso . '" onkeyup = "texto(this);" ></td>';
				//codigo
				if ($visualiza != true) { //solo para CRUD
					$codigo = trim($row["fod_codigo"]);
					$tipo = trim($row["fod_tipo"]);
					switch ($tipo) {
						case 1:
							$contenedor = "resultFortalezas";
							break;
						case 2:
							$contenedor = "resultOportunidades";
							break;
						case 3:
							$contenedor = "resultDebilidades";
							break;
						case 4:
							$contenedor = "resultAmenazas";
							break;
					}
					$salida .= '<td class = "text-center" >';
					$salida .= '<div class="btn-group">';
					$salida .= '<button type="button" class="btn btn-primary btn-xs" onclick = "saveFoda(' . $codigo . ',' . $proceso . ',' . $tipo . ',\'' . $contenedor . '\');" title = "Grabar detalle de Foda" ><i class="fa fa-save"></i></button>';
					$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "quitarFoda(' . $codigo . ',' . $proceso . ',' . $tipo . ',\'' . $contenedor . '\');" title = "Eliminar detalle de Foda" ><i class="fa fa-trash"></i></button>';
					$salida .= '</div>';
					$salida .= '</td>';
				}
				//--
				$salida .= '</tr>';
			} else {
				$salida .= '<tr>';
				//No.
				$salida .= '<td class = "text-center">' . $i . '.</td>';
				//Sistema
				$sistema = trim($row["sis_nombre"]);
				$salida .= '<td class = "text-left">' . $sistema . '</td>';
				//Nombre
				$descripcion = trim($row["fod_descripcion"]);
				$salida .= '<td class = "text-left">' . $descripcion . '</td>';
				//Peso
				$peso = floatval($row["fod_peso"]);
				$total += $peso;
				$salida .= '<td class = "text-center">' . $peso . '</td>';
				//codigo
				if ($visualiza != true) { //solo para CRUD
					$codigo = trim($row["fod_codigo"]);
					$tipo = trim($row["fod_tipo"]);
					switch ($tipo) {
						case 1:
							$contenedor = "resultFortalezas";
							break;
						case 2:
							$contenedor = "resultOportunidades";
							break;
						case 3:
							$contenedor = "resultDebilidades";
							break;
						case 4:
							$contenedor = "resultAmenazas";
							break;
					}
					$salida .= '<td class = "text-center" >';
					$salida .= '<div class="btn-group">';
					$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarFoda(' . $codigo . ',' . $proceso . ',' . $tipo . ',\'' . $contenedor . '\');" title = "Editar detalle de Foda" ><i class="fa fa-pencil"></i></button>';
					$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "quitarFoda(' . $codigo . ',' . $proceso . ',' . $tipo . ',\'' . $contenedor . '\');" title = "Eliminar detalle de Foda" ><i class="fa fa-trash"></i></button>';
					$salida .= '</div>';
					$salida .= '</td>';
				}
				//--
				$salida .= '</tr>';
			}
			$i++;
		}
		//footer
		$salida .= '<tr>';
		$salida .= '<th class = "text-right" colspan = "3">Total </th>';
		$salida .= '<th class = "text-center" width = "50px">' . $total . '</th>';
		if ($visualiza != true) { //solo para CRUD
			$salida .= '<th class = "text-center" width = "30px"></th>';
		}
		$salida .= '</tr>';
		//---
		$salida .= '</tbody>';
	}
	$salida .= '</table>';
	$salida .= '</div>';

	return $salida;
}


function tabla_sistemas()
{
	$ClsSis = new ClsSistema();
	$result = $ClsSis->get_sistema('', '', 1);

	$salida = '<table class="table">';
	if (is_array($result)) {
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//sitema
			$sistema = utf8_decode($row["sis_nombre"]);
			$salida .= '<td width = "150px" class = "text-left">' . $sistema . '</td>';
			//color
			$color = trim($row["sis_color"]);
			$color = ($color == "") ? "#fff" : $color;
			$sistema = '<i class="fa fa-square fa-2x" style="color: ' . $color . '" aria-hidden="true"></i>';
			$salida .= '<th class="text-center" width="10px">' . $sistema . '</th>';
			//--
			$salida .= '</tr>';
			$i++;
		}
	}
	$salida .= '</table>';

	return $salida;
}

function tabla_Necesidades($codigo, $tipo)
{
	$clsNec = new ClsNecesidad();
	$result = $clsNec->get_Necesidad($codigo, $tipo);
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" id="tabla" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%"><i class="fa fa-cogs"></th>';
		$salida .= '<th class = "text-center" width = "5%">No.</th>';
		if ($tipo != '8') {
			$salida .= '<th class = "text-left" width = "10%">Tipo</th>';
		}
		$salida .= '<th class = "text-left" width = "10%">Nombre</th>';
		$salida .= '<th class = "text-left" width = "20%">Descripci&oacute;n</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = $row["ext_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "Seleccionar(' . $codigo . ');" title = "Editar Necesidad" ><i class="fa fa-pencil"></i></button>';
			$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "Deshabilitar(' . $codigo . ');" title = "Eliminar Necesidad" ><i class="fa fa-trash"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			// No. 
			$salida .= '<td class = "text-center">' . $codigo . '</td>';
			// Proceso
			if ($tipo != '8') {
				$tipo = trim($row["ext_tipo"]);
				$tipo = nl2br($tipo);
				if ($tipo == 1) {
					$tipo = 'Interno';
				} else if ($tipo == 2) {
					$tipo = 'Externo';
				}
				$salida .= '<td class = "text-left">' . $tipo . '</td>';
			}
			// sistema 
			$nombre = trim($row["ext_nombre"]);
			$nombre = nl2br($nombre);
			$salida .= '<td class = "text-left">' . $nombre . '</td>';
			// Descripcion
			$descripcion = trim($row["ext_descripcion"]);
			$descripcion = nl2br($descripcion);
			$salida .= '<td class = "text-left">' .   utf8_decode($descripcion) . '...</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}

function tabla_expectativas($codigo, $tipo)
{
	$clsNec = new ClsExpectativa();
	$result = $clsNec->get_expectativa($codigo, $tipo);
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" id="tabla" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%"><i class="fa fa-cogs"></th>';
		$salida .= '<th class = "text-center" width = "5%">No.</th>';
		if ($tipo != '8') {
			$salida .= '<th class = "text-left" width = "10%">Tipo</th>';
		}
		$salida .= '<th class = "text-left" width = "10%">Nombre</th>';
		$salida .= '<th class = "text-left" width = "20%">Descripci&oacute;n</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = $row["exp_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "Seleccionar(' . $codigo . ');" title = "Editar Necesidad/Expectativa" ><i class="fa fa-pencil"></i></button>';
			$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "Deshabilitar(' . $codigo . ');" title = "Eliminar Necesidad/Expectativa" ><i class="fa fa-trash"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			// No. 
			$salida .= '<td class = "text-center">' . $codigo . '</td>';
			// TIPO Necesidad o Expectatia
			$tipo = trim($row["exp_tipo"]);
			$tipo = nl2br($tipo);
			if ($tipo == 1) {
				$tipo = 'Necesidad';
			} else if ($tipo == 2) {
				$tipo = 'Expectativa';
			}
			$salida .= '<td class = "text-left">' . $tipo . '</td>';
			// Nombre 
			$nombre = trim($row["exp_nombre"]);
			$nombre = nl2br($nombre);
			$salida .= '<td class = "text-left">' . $nombre . '</td>';
			// Descripcion
			$descripcion = trim($row["exp_descripcion"]);
			$descripcion = nl2br($descripcion);
			$salida .= '<td class = "text-left">' .   utf8_decode($descripcion) . '...</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}

function tabla_foda_grafica($proceso)
{
	$ClsFic = new ClsFicha();
	////////////////////// FORTALEZAS / DEBILIDADES /////////////////////////
	$cont_fortaleza = $ClsFic->count_foda('', $proceso, 1);
	$cont_debilidad = $ClsFic->count_foda('', $proceso, 3);
	$contador_arriba = ($cont_fortaleza > $cont_debilidad) ? $cont_fortaleza : $cont_debilidad;
	//--
	$result_fortaleza = $ClsFic->get_foda('', $proceso, 1);
	$result_debilidad = $ClsFic->get_foda('', $proceso, 3);
	//--
	$salida = '<table class="table table-bordered">';
	$salida .= '<tr class="table-primary">';
	$salida .= '<th class="text-center" colspan="4">FORTALEZAS</th>';
	//--
	$salida .= '<th class="text-center" colspan="4">DEBILIDADES</th>';
	$salida .= '</tr>';
	$salida .= '<tr class="table-secondary">';
	$salida .= '<th class="text-center" width="5%">No.</th>';
	$salida .= '<th class="text-left" width="30%">Descripci&oacute;n</th>';
	$salida .= '<th class="text-center" width="5%">Sistema</th>';
	$salida .= '<th class="text-center" width="10%">Peso</th>';
	//--
	$salida .= '<th class="text-center" width="5%">No.</th>';
	$salida .= '<th class="text-left" width="35%">Descripci&oacute;n</th>';
	$salida .= '<th class="text-center" width="5%">Sistema</th>';
	$salida .= '<th class="text-center" width="10%">Peso</th>';
	$salida .= '</tr>';
	$totalFortalezas = 0;
	$totalDebilidades = 0;
	if (is_array($result_fortaleza) || is_array($result_debilidad)) {
		for ($i = 0; $i < $contador_arriba; $i++) {
			$salida .= '<tr>';
			/////// FORTALEZAS ///////
			$salida .= '<td class = "text-center">' . ($i + 1) . '.</td>';
			//Nombre
			$descripcion = trim($result_fortaleza[$i]["fod_descripcion"]);
			$salida .= '<td class = "text-left">' . $descripcion . '</td>';
			//Sistema
			$color = trim($result_fortaleza[$i]["sis_color"]);
			$color = ($color == "") ? "#fff" : $color;
			$sistema = '<i class="fa fa-square fa-2x" style="color: ' . $color . '" aria-hidden="true"></i>';
			$salida .= '<th class="text-center" width="5%">' . $sistema . '</th>';
			//Peso
			$peso = floatval($result_fortaleza[$i]["fod_peso"]);
			$totalFortalezas += $peso;
			$salida .= '<td class = "text-center">' . $peso . '</td>';	/////// DEBILIDADES ///////
			$salida .= '<td class = "text-center">' . ($i + 1) . '.</td>';
			//Nombre
			$descripcion = trim($result_debilidad[$i]["fod_descripcion"]);
			$salida .= '<td class = "text-left">' . $descripcion . '</td>';
			//Sistema
			$color = trim($result_debilidad[$i]["sis_color"]);
			$color = ($color == "") ? "#fff" : $color;
			$sistema = '<i class="fa fa-square fa-2x" style="color: ' . $color . '" aria-hidden="true"></i>';
			$salida .= '<th class="text-center" width="5%">' . $sistema . '</th>';
			//Peso
			$peso = floatval($result_debilidad[$i]["fod_peso"]);
			$totalDebilidades += $peso;
			$salida .= '<td class = "text-center">' . $peso . '</td>';
			//--
			$salida .= '</tr>';
		}
		/////// FORTALEZAS ///////
		$salida .= '<tr class="table-secondary">';
		$salida .= '<th class = "text-left" colspan = "3">Total </th>';
		$salida .= '<th class = "text-center" width="10%">' . $totalFortalezas . '</th>';
		/////// DEBILIDADES ///////
		$salida .= '<th class = "text-left" colspan = "3">Total </th>';
		$salida .= '<th class = "text-center" width="10%">' . $totalDebilidades . '</th>';
		$salida .= '</tr>';
		//---
	}
	$salida .= '<tr>';
	$salida .= '<th class="text-center" colspan="8">&nbsp;</th>';
	$salida .= '</tr>';
	////////////////////// OPORTUNIDAD / AMENAZA /////////////////////////
	$cont_oportunidad = $ClsFic->count_foda('', $proceso, 2);
	$cont_amenaza = $ClsFic->count_foda('', $proceso, 4);
	$contador_abajo = ($cont_oportunidad > $cont_amenaza) ? $cont_oportunidad : $cont_amenaza;
	//--
	$result_oportunidad = $ClsFic->get_foda('', $proceso, 2);
	$result_amenaza = $ClsFic->get_foda('', $proceso, 4);
	//--
	$salida .= '<tr class="table-primary">';
	$salida .= '<th class="text-center" colspan="4">OPORTUNIDADES</th>';
	//--
	$salida .= '<th class="text-center" colspan="4">AMENAZAS</th>';
	$salida .= '</tr>';
	$salida .= '<tr class="table-secondary">';
	$salida .= '<th class="text-center" width="5%">No.</th>';
	$salida .= '<th class="text-left" width="30%">Descripci&oacute;n</th>';
	$salida .= '<th class="text-center" width="5%">Sistema</th>';
	$salida .= '<th class="text-center" width="10%">Peso</th>';
	//--
	$salida .= '<th class="text-center" width="5%">No.</th>';
	$salida .= '<th class="text-left" width="35%">Descripci&oacute;n</th>';
	$salida .= '<th class="text-center" width="5%">Sistema</th>';
	$salida .= '<th class="text-center" width="10%">Peso</th>';
	$salida .= '</tr>';

	$totalOportunidad = 0;
	$totalAmenazas = 0;
	if (is_array($result_oportunidad) || is_array($result_amenaza)) {
		for ($i = 0; $i < $contador_abajo; $i++) {
			$salida .= '<tr>';
			/////// FORTALEZAS ///////
			$salida .= '<td class = "text-center">' . ($i + 1) . '.</td>';
			//Nombre
			$descripcion = trim($result_oportunidad[$i]["fod_descripcion"]);
			$salida .= '<td class = "text-left">' . $descripcion . '</td>';
			//Sistema
			$color = trim($result_oportunidad[$i]["sis_color"]);
			$color = ($color == "") ? "#fff" : $color;
			$sistema = '<i class="fa fa-square fa-2x" style="color: ' . $color . '" aria-hidden="true"></i>';
			$salida .= '<th class="text-center" width="5%">' . $sistema . '</th>';
			//Peso
			$peso = floatval($result_oportunidad[$i]["fod_peso"]);
			$totalOportunidad += $peso;
			$salida .= '<td class = "text-center">' . $peso . '</td>';	/////// DEBILIDADES ///////
			$salida .= '<td class = "text-center">' . ($i + 1) . '.</td>';
			//Nombre
			$descripcion = trim($result_amenaza[$i]["fod_descripcion"]);
			$salida .= '<td class = "text-left">' . $descripcion . '</td>';
			//Sistema
			$color = trim($result_amenaza[$i]["sis_color"]);
			$color = ($color == "") ? "#fff" : $color;
			$sistema = '<i class="fa fa-square fa-2x" style="color: ' . $color . '" aria-hidden="true"></i>';
			$salida .= '<th class="text-center" width="5%">' . $sistema . '</th>';
			//Peso
			$peso = floatval($result_amenaza[$i]["fod_peso"]);
			$totalAmenazas += $peso;
			$salida .= '<td class = "text-center">' . $peso . '</td>';
			//--
			$salida .= '</tr>';
		}
		/////// FORTALEZAS ///////
		$salida .= '<tr class="table-secondary">';
		$salida .= '<th class = "text-left" colspan = "3">Total </th>';
		$salida .= '<th class = "text-center" width="10%">' . $totalOportunidad . '</th>';
		/////// DEBILIDADES ///////
		$salida .= '<th class = "text-left" colspan = "3">Total </th>';
		$salida .= '<th class = "text-center" width="10%">' . $totalAmenazas . '</th>';
		$salida .= '</tr>';
		//---
	}

	$salida .= '</table>';

	return $salida;
}

function detalle_acordion($objetivo)
{
	$salida = '<div class="row">';
	$salida .= '<div class="col-md-12">';
	$ClsCon = new ClsControl();
	$ClsInd = new ClsIndicador();
	$result = $ClsInd->get_indicador("", $objetivo);
	if (is_array($result)) {
		foreach ($result as $row) {
			$indicador_descripcion = trim($row["ind_descripcion"]);
			$indicador_nombre = trim($row["ind_nombre"]);
			$unidad = trim($row["medida_nombre"]);
			$salida .= '<div class="row">';
			$salida .= '<div class="col-md-12">';
			$salida .= '<h6>Indicador:</h6>';
			$salida .= '</div>';
			$salida .= '</div>';
			$salida .= '<div class="row">';
			$salida .= '<div class="col-md-12">';
			$salida .= '&nbsp;Nombre:<label>&nbsp;' . $indicador_nombre . '</label>';
			$salida .= '</div>';
			$salida .= '</div>';
			$salida .= '<div class="row">';
			$salida .= '<div class="col-md-12">';
			$salida .= '&nbsp;Descripci&oacute;n:<label>&nbsp;' . $indicador_descripcion . '</label>';
			$salida .= '</div>';
			$salida .= '</div>';
			$salida .= '<div class="row">';
			$salida .= '<div class="col-md-12">';
			$salida .= '&nbsp;Unidad de Medida:<label>&nbsp;' . $unidad . '</label>';
			$salida .= '</div>';
			$salida .= '</div>';
			$salida .= '<hr>';
		}
	}
	$result = $ClsCon->get_control("", $objetivo);
	if (is_array($result)) {
		foreach ($result as $row) {
			$control = trim($row["con_descripcion"]);
			$salida .= '<div class="row">';
			$salida .= '<div class="col-md-12">';
			$salida .= '<h6>Control:</h6>';
			$salida .= '</div>';
			$salida .= '</div>';
			$salida .= '<div class="row">';
			$salida .= '<div class="col-md-12">';
			$salida .= '<label>&nbsp;' . $control . '</label>';
			$salida .= '</div>';
			$salida .= '</div>';
		}
	}
	$salida .= '</div>';
	$salida .= '</div>';
	return $salida;
}

function objetivos_acordion($proceso, $sistema)
{
	$ClsObj = new ClsObjetivo();
	$result = $ClsObj->get_objetivo('', $proceso, $sistema, 1);
	$salida = "";
	if (is_array($result)) {
		$i = 1;
		foreach ($result as $row) {
			//codigo
			$codigo = trim($row["obj_codigo"]);
			$descripcion = trim($row["obj_descripcion"]);
			//--
			$salida .= '<div class="card card-plain">';
			$salida .= '<div class="card-body m-2" role="tab" id="panel' . $codigo . '">';
			$salida .= '<a data-toggle="collapse" data-parent="#accordion" href="#collapse' . $codigo . '" aria-expanded="false" aria-controls="collapse' . $codigo . '">';
			$salida .= '<div class="row">';
			$salida .= $i . '.&nbsp;<label> ' . $descripcion . '</label>';
			$salida .= '</div>';
			$salida .= '<div class="col-md-3 text-right">';
			$salida .= '<button type="button" class="btn btn-white" title = "Editar Escalon" onclick="aperturaObjetivo(' . $codigo . ');"><span class="fa fa-pencil"></span></button>';
			$salida .= '<button type="button" class="btn btn-danger" title = "Eliminar Escalon" onclick="quitarObjetivo(' . $codigo . ',' . $proceso . ',' . $sistema . ');"><span class="fa fa-trash"></span></button>';
			$salida .= '</div>';
			$salida .= '<div class="col-md-1">';
			$salida .= '<i class="nc-icon nc-minimal-down"></i>';
			$salida .= '</div>';
			$salida .= '</div>';
			$salida .= '</a>';
			$salida .= '</div>';
			//--
			$salida .= '<div id="collapse' . $codigo . '" class="collapse" role="tabpanel" aria-labelledby="panel' . $codigo . '">';
			$salida .= '<div class="card-body">';
			$salida .= detalle_acordion($codigo);
			$salida .= '</div>';
			$salida .= '</div>';
			$i++;
		}
	} else {
		$salida = '<div class="row">';
		$salida .= '<div class="col-xs-12 col-md-12">';
		$salida .= '<h5 class="text-center">';
		$salida .= '<i class="fa fa-information-circle"></i> ...';
		$salida .= '</h5>';
		$salida .= '</div>';
		$salida .= '</div>';
	}

	return $salida;
}

function tabla_elemento($detalle, $proceso, $tipo, $visualiza = false)
{
	$ClsFic = new ClsFicha();
	$result = $ClsFic->get_elemento('', $proceso, $tipo);

	$salida = '<div class="scroll-container">';
	$salida .= '<table class="table table-striped table-bordered" >';
	$salida .= '<thead>';
	$salida .= '<tr>';
	$salida .= '<th class = "text-center" width = "10px">No.</th>';
	$salida .= '<th class = "text-left" width = "150px">Elemento</th>';
	$salida .= '<th class = "text-left" width = "250px">Descripci&oacute;n</th>';
	if ($visualiza != true) { //solo para CRUD
		$salida .= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
	}
	$salida .= '</tr>';
	$salida .= '</thead>';
	$salida .= '<tbody>';
	if (is_array($result)) {
		$i = 1;
		foreach ($result as $row) {
			$codigo = $row["ele_codigo"];
			if ($detalle == $codigo) {
				$salida .= '<tr>';
				//No.
				$salida .= '<td class = "text-center">' . $i . '.</td>';
				//Sistema
				$titulo = trim($row["ele_titulo"]);
				$salida .= '<td class = "text-left"><input type="text" class="form-control input-table" id ="titulo" name ="titulo" width="150px" value="' . $titulo . '" onkeyup = "texto(this);" ></td>';
				//Descripcion
				$descripcion = trim($row["ele_descripcion"]);
				$salida .= '<td class = "text-left"><input type="text" class="form-control input-table" id ="elemento" name ="elemento" width="150px" value="' . $descripcion . '" onkeyup = "texto(this);" ></td>';
				//codigo
				if ($visualiza != true) { //solo para CRUD
					$codigo = trim($row["ele_codigo"]);
					$tipo = trim($row["ele_tipo"]);
					switch ($tipo) {
						case 1:
							$contenedor = "resultFuenteEntrada";
							break;
						case 2:
							$contenedor = "resultEntrada";
							break;
						case 3:
							$contenedor = "resultFuenteSalida";
							break;
						case 4:
							$contenedor = "resultSalida";
							break;
						case 5:
							$contenedor = "resultActividad";
							break;
						case 6:
							$contenedor = "resultVerificacion";
							break;
					}
					$salida .= '<td class = "text-center" >';
					$salida .= '<div class="btn-group">';
					$salida .= '<button type="button" class="btn btn-primary btn-xs" onclick = "saveElemento(' . $codigo . ',' . $proceso . ',' . $tipo . ',\'' . $contenedor . '\');" title = "Grabar detalle de Elemento" ><i class="fa fa-save"></i></button>';
					$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "quitarElemento(' . $codigo . ',' . $proceso . ',' . $tipo . ',\'' . $contenedor . '\');" title = "Eliminar detalle de Elemento" ><i class="fa fa-trash"></i></button>';
					$salida .= '</div>';
					$salida .= '</td>';
				}
				//--
				$salida .= '</tr>';
			} else {
				$salida .= '<tr>';
				//No.
				$salida .= '<td class = "text-center">' . $i . '.</td>';
				//Titulo
				$titulo = trim($row["ele_titulo"]);
				$salida .= '<td class = "text-left">' . $titulo . '</td>';
				//Descripcion
				$descripcion = trim($row["ele_descripcion"]);
				$salida .= '<td class = "text-left">' . $descripcion . '</td>';
				//codigo
				if ($visualiza != true) { //solo para CRUD
					$codigo = trim($row["ele_codigo"]);
					$tipo = trim($row["ele_tipo"]);
					switch ($tipo) {
						case 1:
							$contenedor = "resultFuenteEntrada";
							break;
						case 2:
							$contenedor = "resultEntrada";
							break;
						case 3:
							$contenedor = "resultFuenteSalida";
							break;
						case 4:
							$contenedor = "resultSalida";
							break;
						case 5:
							$contenedor = "resultActividad";
							break;
						case 6:
							$contenedor = "resultVerificacion";
							break;
					}
					$salida .= '<td class = "text-center" >';
					$salida .= '<div class="btn-group">';
					$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarElemento(' . $codigo . ',' . $proceso . ',' . $tipo . ',\'' . $contenedor . '\');" title = "Editar detalle de Elemento" ><i class="fa fa-pencil"></i></button>';
					$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "quitarElemento(' . $codigo . ',' . $proceso . ',' . $tipo . ',\'' . $contenedor . '\');" title = "Eliminar detalle de Elemento" ><i class="fa fa-trash"></i></button>';
					$salida .= '</div>';
					$salida .= '</td>';
				}
				//--
				$salida .= '</tr>';
			}
			$i++;
		}
		$salida .= '</tbody>';
	}
	$salida .= '</table>';
	$salida .= '</div>';

	return $salida;
}

function tabla_legal($detalle, $proceso, $visualiza = false)
{
	$ClsFic = new ClsFicha();
	$result = $ClsFic->get_requisitos_legales('', $proceso);

	$salida = '<div class="scroll-container">';
	$salida .= '<table class="table table-striped table-bordered" >';
	$salida .= '<thead>';
	$salida .= '<tr>';
	$salida .= '<th class = "text-center" width = "10px">No.</th>';
	$salida .= '<th class = "text-left" width = "250px">Descripci&oacute;n</th>';
	if ($visualiza != true) { //solo para CRUD
		$salida .= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
	}
	$salida .= '</tr>';
	$salida .= '</thead>';
	$salida .= '<tbody>';
	if (is_array($result)) {
		$i = 1;
		foreach ($result as $row) {
			$codigo = $row["req_codigo"];
			if ($detalle == $codigo) {
				$salida .= '<tr>';
				//No.
				$salida .= '<td class = "text-center">' . $i . '.</td>';
				//Descripcion
				$descripcion = trim($row["req_descripcion"]);
				$salida .= '<td class = "text-left"><input type="text" class="form-control input-table" id ="requisito" name ="requisito" width="150px" value="' . $descripcion . '" onkeyup = "texto(this);" ></td>';
				//codigo
				if ($visualiza != true) { //solo para CRUD
					$codigo = trim($row["req_codigo"]);
					$salida .= '<td class = "text-center" >';
					$salida .= '<div class="btn-group">';
					$salida .= '<button type="button" class="btn btn-primary btn-xs" onclick = "saveRequisitoLegal(' . $codigo . ',' . $proceso . ');" title = "Grabar detalle del requisito legal" ><i class="fa fa-save"></i></button>';
					$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "quitarRequisitoLegal(' . $codigo . ',' . $proceso . ');" title = "Eliminar detalle del requisito legal" ><i class="fa fa-trash"></i></button>';
					$salida .= '</div>';
					$salida .= '</td>';
				}
				//--
				$salida .= '</tr>';
			} else {
				$salida .= '<tr>';
				//No.
				$salida .= '<td class = "text-center">' . $i . '.</td>';
				//Descripcion
				$descripcion = trim($row["req_descripcion"]);
				$salida .= '<td class = "text-left">' . $descripcion . '</td>';
				//codigo
				if ($visualiza != true) { //solo para CRUD
					$codigo = trim($row["req_codigo"]);
					$salida .= '<td class = "text-center" >';
					$salida .= '<div class="btn-group">';
					$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarRequisitoLegal(' . $codigo . ',' . $proceso . ');" title = "Editar detalle del requisito legal" ><i class="fa fa-pencil"></i></button>';
					$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "quitarRequisitoLegal(' . $codigo . ',' . $proceso . ');" title = "Eliminar detalle del requisito legal" ><i class="fa fa-trash"></i></button>';
					$salida .= '</div>';
					$salida .= '</td>';
				}
				//--
				$salida .= '</tr>';
			}
			$i++;
		}
		$salida .= '</tbody>';
	}
	$salida .= '</table>';
	$salida .= '</div>';

	return $salida;
}


function tabla_ambiental($detalle, $proceso, $visualiza = false)
{
	$ClsFic = new ClsFicha();
	$result = $ClsFic->get_aspectos_ambientales('', $proceso);

	$salida = '<div class="scroll-container">';
	$salida .= '<table class="table table-striped table-bordered" >';
	$salida .= '<thead>';
	$salida .= '<tr>';
	$salida .= '<th class = "text-center" width = "10px">No.</th>';
	$salida .= '<th class = "text-left" width = "250px">Descripci&oacute;n</th>';
	if ($visualiza != true) { //solo para CRUD
		$salida .= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
	}
	$salida .= '</tr>';
	$salida .= '</thead>';
	$salida .= '<tbody>';
	if (is_array($result)) {
		$i = 1;
		foreach ($result as $row) {
			$codigo = $row["asp_codigo"];
			if ($detalle == $codigo) {
				$salida .= '<tr>';
				//No.
				$salida .= '<td class = "text-center">' . $i . '.</td>';
				//Descripcion
				$descripcion = trim($row["asp_descripcion"]);
				$salida .= '<td class = "text-left"><input type="text" class="form-control input-table" id ="aspecto" name ="aspecto" width="150px" value="' . $descripcion . '" onkeyup = "texto(this);" ></td>';
				//codigo
				if ($visualiza != true) { //solo para CRUD
					$codigo = trim($row["asp_codigo"]);
					$salida .= '<td class = "text-center" >';
					$salida .= '<div class="btn-group">';
					$salida .= '<button type="button" class="btn btn-primary btn-xs" onclick = "saveAspectoAmbiental(' . $codigo . ',' . $proceso . ');" title = "Grabar detalle del aspecto ambiental" ><i class="fa fa-save"></i></button>';
					$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "quitarAspectoAmbiental(' . $codigo . ',' . $proceso . ');" title = "Eliminar detalle del aspecto ambiental" ><i class="fa fa-trash"></i></button>';
					$salida .= '</div>';
					$salida .= '</td>';
				}
				//--
				$salida .= '</tr>';
			} else {
				$salida .= '<tr>';
				//No.
				$salida .= '<td class = "text-center">' . $i . '.</td>';
				//Descripcion
				$descripcion = trim($row["asp_descripcion"]);
				$salida .= '<td class = "text-left">' . $descripcion . '</td>';
				//codigo
				if ($visualiza != true) { //solo para CRUD
					$codigo = trim($row["asp_codigo"]);
					$salida .= '<td class = "text-center" >';
					$salida .= '<div class="btn-group">';
					$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarAspectoAmbiental(' . $codigo . ',' . $proceso . ');" title = "Editar detalle del aspecto ambiental" ><i class="fa fa-pencil"></i></button>';
					$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "quitarAspectoAmbiental(' . $codigo . ',' . $proceso . ');" title = "Eliminar detalle del aspecto ambiental" ><i class="fa fa-trash"></i></button>';
					$salida .= '</div>';
					$salida .= '</td>';
				}
				//--
				$salida .= '</tr>';
			}
			$i++;
		}
		$salida .= '</tbody>';
	}
	$salida .= '</table>';
	$salida .= '</div>';

	return $salida;
}


function tabla_responsabilidad($detalle, $proceso, $visualiza = false)
{
	$ClsFic = new ClsFicha();
	$result = $ClsFic->get_responsabilidad_social('', $proceso);

	$salida = '<div class="scroll-container">';
	$salida .= '<table class="table table-striped table-bordered" >';
	$salida .= '<thead>';
	$salida .= '<tr>';
	$salida .= '<th class = "text-center" width = "10px">No.</th>';
	$salida .= '<th class = "text-left" width = "250px">Descripci&oacute;n</th>';
	if ($visualiza != true) { //solo para CRUD
		$salida .= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
	}
	$salida .= '</tr>';
	$salida .= '</thead>';
	$salida .= '<tbody>';
	if (is_array($result)) {
		$i = 1;
		foreach ($result as $row) {
			$codigo = $row["resp_codigo"];
			if ($detalle == $codigo) {
				$salida .= '<tr>';
				//No.
				$salida .= '<td class = "text-center">' . $i . '.</td>';
				//Descripcion
				$descripcion = trim($row["resp_descripcion"]);
				$salida .= '<td class = "text-left"><input type="text" class="form-control input-table" id ="responsabilidad" name ="responsabilidad" width="150px" value="' . $descripcion . '" onkeyup = "texto(this);" ></td>';
				//codigo
				if ($visualiza != true) { //solo para CRUD
					$codigo = trim($row["resp_codigo"]);
					$salida .= '<td class = "text-center" >';
					$salida .= '<div class="btn-group">';
					$salida .= '<button type="button" class="btn btn-primary btn-xs" onclick = "saveResponsibilidadSocial(' . $codigo . ',' . $proceso . ');" title = "Grabar detalle de la responsabilidad social" ><i class="fa fa-save"></i></button>';
					$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "quitarResponsibilidadSocial(' . $codigo . ',' . $proceso . ');" title = "Eliminar detalle de la responsabilidad social" ><i class="fa fa-trash"></i></button>';
					$salida .= '</div>';
					$salida .= '</td>';
				}
				//--
				$salida .= '</tr>';
			} else {
				$salida .= '<tr>';
				//No.
				$salida .= '<td class = "text-center">' . $i . '.</td>';
				//Descripcion
				$descripcion = trim($row["resp_descripcion"]);
				$salida .= '<td class = "text-left">' . $descripcion . '</td>';
				//codigo
				if ($visualiza != true) { //solo para CRUD
					$codigo = trim($row["resp_codigo"]);
					$salida .= '<td class = "text-center" >';
					$salida .= '<div class="btn-group">';
					$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarResponsibilidadSocial(' . $codigo . ',' . $proceso . ');" title = "Editar detalle de la responsabilidad social" ><i class="fa fa-pencil"></i></button>';
					$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "quitarResponsibilidadSocial(' . $codigo . ',' . $proceso . ');" title = "Eliminar detalle de la responsabilidad social" ><i class="fa fa-trash"></i></button>';
					$salida .= '</div>';
					$salida .= '</td>';
				}
				//--
				$salida .= '</tr>';
			}
			$i++;
		}
		$salida .= '</tbody>';
	}
	$salida .= '</table>';
	$salida .= '</div>';

	return $salida;
}


function tabla_punto_norma($detalle, $proceso, $visualiza = false)
{
	$ClsFic = new ClsFicha();
	$result = $ClsFic->get_puntos_norma('', $proceso);

	$salida = '<div class="scroll-container">';
	$salida .= '<table class="table table-striped table-bordered" >';
	$salida .= '<thead>';
	$salida .= '<tr>';
	$salida .= '<th class = "text-center" width = "10px">No.</th>';
	$salida .= '<th class = "text-left" width = "250px">Descripci&oacute;n</th>';
	if ($visualiza != true) { //solo para CRUD
		$salida .= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
	}
	$salida .= '</tr>';
	$salida .= '</thead>';
	$salida .= '<tbody>';
	if (is_array($result)) {
		$i = 1;
		foreach ($result as $row) {
			$codigo = $row["nor_codigo"];
			if ($detalle == $codigo) {
				$salida .= '<tr>';
				//No.
				$salida .= '<td class = "text-center">' . $i . '.</td>';
				//Descripcion
				$descripcion = trim($row["nor_descripcion"]);
				$salida .= '<td class = "text-left"><input type="text" class="form-control input-table" id ="norma" name ="norma" width="150px" value="' . $descripcion . '" onkeyup = "texto(this);" ></td>';
				//codigo
				if ($visualiza != true) { //solo para CRUD
					$codigo = trim($row["nor_codigo"]);
					$salida .= '<td class = "text-center" >';
					$salida .= '<div class="btn-group">';
					$salida .= '<button type="button" class="btn btn-primary btn-xs" onclick = "savePuntoNorma(' . $codigo . ',' . $proceso . ');" title = "Grabar detalle del punto de norma" ><i class="fa fa-save"></i></button>';
					$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "quitarPuntoNorma(' . $codigo . ',' . $proceso . ');" title = "Eliminar detalle del punto de norma" ><i class="fa fa-trash"></i></button>';
					$salida .= '</div>';
					$salida .= '</td>';
				}
				//--
				$salida .= '</tr>';
			} else {
				$salida .= '<tr>';
				//No.
				$salida .= '<td class = "text-center">' . $i . '.</td>';
				//Descripcion
				$descripcion = trim($row["nor_descripcion"]);
				$salida .= '<td class = "text-left">' . $descripcion . '</td>';
				//codigo
				if ($visualiza != true) { //solo para CRUD
					$codigo = trim($row["nor_codigo"]);
					$salida .= '<td class = "text-center" >';
					$salida .= '<div class="btn-group">';
					$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarPuntoNorma(' . $codigo . ',' . $proceso . ');" title = "Editar detalle del punto de norma" ><i class="fa fa-pencil"></i></button>';
					$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "quitarPuntoNorma(' . $codigo . ',' . $proceso . ');" title = "Eliminar detalle del punto de norma" ><i class="fa fa-trash"></i></button>';
					$salida .= '</div>';
					$salida .= '</td>';
				}
				//--
				$salida .= '</tr>';
			}
			$i++;
		}
		$salida .= '</tbody>';
	}
	$salida .= '</table>';
	$salida .= '</div>';

	return $salida;
}



function tabla_riesgos_oportunidades($proceso)
{
	$ClsFic = new ClsFicha();
	$result = $ClsFic->get_foda('', $proceso, '');

	$salida = '<div class="scroll-container">';
	$salida .= '<table class="table table-striped table-bordered" >';
	$salida .= '<thead>';
	$salida .= '<tr>';
	$salida .= '<th class = "text-center" width = "10px">No.</th>';
	$salida .= '<th class = "text-left" width = "150px">Sistema</th>';
	$salida .= '<th class = "text-left" width = "150px">Tipo</th>';
	$salida .= '<th class = "text-left" width = "250px">Descripci&oacute;n</th>';
	$salida .= '</tr>';
	$salida .= '</thead>';
	$salida .= '<tbody>';
	$total = 0;
	if (is_array($result)) {
		$i = 1;
		foreach ($result as $row) {
			$tipo = trim($row["fod_tipo"]);
			if ($tipo != 1) {
				$salida .= '<tr>';
				//No.
				$codigo = $row["fod_codigo"];
				$salida .= '<td class = "text-center">' . $i . '.</td>';
				//Sistema
				$sistema = trim($row["sis_nombre"]);
				$salida .= '<td class = "text-left">' . $sistema . '</td>';
				//tipo
				$tipo = trim($row["fod_tipo"]);
				switch ($tipo) {
					case 2:
						$tipo_descripcion = "Oportunidades / Proyectos de Mejora";
						break;
					case 3:
						$tipo_descripcion = "Riesgos Internos";
						break;
					case 4:
						$tipo_descripcion = "Riesgos Externos";
						break;
				}
				$salida .= '<td class = "text-left">' . $tipo_descripcion . '</td>';
				//Nombre
				$descripcion = trim($row["fod_descripcion"]);
				$salida .= '<td class = "text-left">' . $descripcion . '</td>';
				//--
				$salida .= '</tr>';
				$i++;
			}
		}
		$salida .= '</tbody>';
	}
	$salida .= '</table>';
	$salida .= '</div>';

	return $salida;
}


function tabla_recurso($detalle, $proceso, $visualiza = false)
{
	$ClsRec = new ClsRecursos();
	$result = $ClsRec->get_recurso('', $proceso);

	$salida = '<div class="scroll-container">';
	$salida .= '<table class="table table-striped table-bordered" >';
	$salida .= '<thead>';
	$salida .= '<tr>';
	$salida .= '<th class = "text-center" width = "10px">No.</th>';
	$salida .= '<th class = "text-left" width = "150px">Clasisficaci&oacute;n</th>';
	$salida .= '<th class = "text-left" width = "250px">Recurso</th>';
	if ($visualiza != true) { //solo para CRUD
		$salida .= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
	}
	$salida .= '</tr>';
	$salida .= '</thead>';
	$salida .= '<tbody>';
	if (is_array($result)) {
		$i = 1;
		foreach ($result as $row) {
			$codigo = $row["rec_codigo"];
			if ($detalle == $codigo) {
				$salida .= '<tr>';
				//No.
				$salida .= '<td class = "text-center">' . $i . '.</td>';
				//Clase de recursos
				$salida .= '<td class = "text-left">' . clase_recursos_html('clase') . '</td>';
				//Descripcion
				$descripcion = trim($row["rec_descripcion"]);
				$salida .= '<td class = "text-left"><input type="text" class="form-control input-table" id ="recurso" name ="recurso" width="150px" value="' . $descripcion . '" onkeyup = "texto(this);" ></td>';
				//codigo
				if ($visualiza != true) { //solo para CRUD
					$codigo = trim($row["rec_codigo"]);
					$salida .= '<td class = "text-center" >';
					$salida .= '<div class="btn-group">';
					$salida .= '<button type="button" class="btn btn-primary btn-xs" onclick = "saveRecurso(' . $codigo . ',' . $proceso . ');" title = "Grabar detalle del recurso" ><i class="fa fa-save"></i></button>';
					$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "quitarRecurso(' . $codigo . ',' . $proceso . ');" title = "Eliminar detalle del recurso" ><i class="fa fa-trash"></i></button>';
					$salida .= '</div>';
					$salida .= '</td>';
				}
				//--
				$salida .= '</tr>';
			} else {
				$salida .= '<tr>';
				//No.
				$salida .= '<td class = "text-center">' . $i . '.</td>';
				//Clase de recursos
				$clase = trim($row["tip_nombre"]);
				$salida .= '<td class = "text-left">' . $clase . '</td>';
				//Descripcion
				$descripcion = trim($row["rec_descripcion"]);
				$salida .= '<td class = "text-left">' . $descripcion . '</td>';
				//codigo
				if ($visualiza != true) { //solo para CRUD
					$codigo = trim($row["rec_codigo"]);
					$salida .= '<td class = "text-center" >';
					$salida .= '<div class="btn-group">';
					$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarRecurso(' . $codigo . ',' . $proceso . ');" title = "Editar detalle del recurso" ><i class="fa fa-pencil"></i></button>';
					$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "quitarRecurso(' . $codigo . ',' . $proceso . ');" title = "Eliminar detalle del recurso" ><i class="fa fa-trash"></i></button>';
					$salida .= '</div>';
					$salida .= '</td>';
				}
				//--
				$salida .= '</tr>';
			}
			$i++;
		}
		$salida .= '</tbody>';
	}
	$salida .= '</table>';
	$salida .= '</div>';

	return $salida;
}

////////////////////////// Asignaciones //////////////////////

function tabla_ficha_usuario($codigo, $ficha, $usuario, $situacion)
{
	$ClsFic = new ClsFicha();
	$result = $ClsFic->get_ficha_usuario($codigo, $ficha, $usuario, $situacion, true);

	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">C&oacute;digo Ficha</th>';
		$salida .= '<th class = "text-center" width = "150px">Ficha</th>';
		$salida .= '<th class = "text-center" width = "150px">Situaci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "10px"></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = agrega_ceros($row["fic_codigo"]);
			$salida .= '<td class = "text-center">' . $codigo . '.</td>';
			//nombre
			$nombre = utf8_decode($row["fic_nombre"]);
			$salida .= '<td class = "text-left">' . $nombre . '</td>';
			//situacion
			$situacion = trim($row["fic_situacion"]);
			switch ($situacion) {
				case 1:
					$situacion = '<span class="text-muted">En Edici&oacute;n</span>';
					break;
				case 2:
					$situacion = '<span class="text-primary">En Aprobaci&oacute;n</span>';
					break;
				case 3:
					$situacion = '<strong class="text-success">Aprobado</strong>';
					break;
				case 0:
					$situacion = '<strong class="text-danger">Descartado</strong>';
					break;
			}
			$salida .= '<td class = "text-center">' . $situacion . '</td>';
			// Ver Usuarios
			$codigo = $row["fic_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-info btn-xs" onclick = "usuariosProceso(' . $codigo . ');" title = "Personas asignadas a este proceso" ><i class="fa fa-user"></i></button>';
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



////////////////////////// REPORTES //////////////////////

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
						$campo = substr($dias, 0, -1);
					} else if ($col == "pro_hini_hfin") {
						$campo = trim($row["pro_hini"]) . "-" . trim($row["pro_hfin"]);;
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
						$campo = '<buttuon type = "button" class="btn btn-success" onclick = "verFotos(' . $codigo . ')" title = "Ver Fotos" ><i class="fa fa-search"></i></button>';
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
		case "are_pertenece":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "pertenece";
			$respuesta["campo"] = "are_pertenece";
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

function parametrosDinamicosEXCEL($columna)
{
	switch ($columna) {
		case "rev_codigo":
			$respuesta["ancho"] = "18";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Revision";
			$respuesta["campo"] = "rev_codigo";
			break;
		case "fic_nombre":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Proceso";
			$respuesta["campo"] = "fic_nombre";
			break;
		case "fic_objetivo_general":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Objetivo General";
			$respuesta["campo"] = "fic_objetivo_general";
			break;
		case "fic_usuario":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Usuario";
			$respuesta["campo"] = "usuario_nombre";
			break;
		case "fic_fecha_registro":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Registro";
			$respuesta["campo"] = "fic_fecha_registro";
			break;
		case "fic_tipo":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Tipo";
			//$respuesta["campo"] = "fic_fecha_registro";
			break;
		case "fic_fecha_update":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Actualizacion";
			$respuesta["campo"] = "fic_fecha_update";
			break;
		case "fic_fecha_aprobacion":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Aprobacion";
			$respuesta["campo"] = "fic_fecha_aprobacion";
			break;
		case "fic_situacion":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Situacion";
			//$respuesta["campo"] = "fic_situacion";
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
		case "are_pertenece":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "pertenece";
			$respuesta["campo"] = "are_pertenece";
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

	$salida  = '<select name="' . $name . '" id="' . $name . '" class = "' . $class . ' form-control">';
	$salida .= '<option value="">Seleccione</option>';
	$salida .= '</select>';

	return $salida;
}

function daysOfWeek($anio, $semana, $dia_semana)
{
	return date("Y-m-d", strtotime($anio . "-W" . $semana . '-' . $dia_semana));
}
