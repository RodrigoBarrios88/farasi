<?php
include_once('../../html_fns.php');

function tabla_foda($detalle, $proceso, $tipo)
{
	$ClsFic = new ClsFicha();
	$result = $ClsFic->get_foda('', $proceso, $tipo);

	$salida = '<table class="table" >';
	$salida .= '<thead>';
	$salida .= '<tr>';
	$salida .= '<th class = "text-center" width = "10px">No.</th>';
	$salida .= '<th class = "text-left" width = "150px">Sistema</th>';
	$salida .= '<th class = "text-left" width = "250px">Descripci&oacute;n</th>';
	$salida .= '<th class = "text-left" width = "50px">Peso</th>';
	$salida .= '</tr>';
	$salida .= '</thead>';
	$salida .= '<tbody>';
	$total = 0;
	if (is_array($result)) {
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//No.
			$salida .= '<td width = "5%" class = "text-center">' . $i . '.</td>';
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
			//--
			$salida .= '</tr>';
			$i++;
		}
		//footer
		$salida .= '<tr>';
		$salida .= '<th class = "text-right" colspan = "3">Total </th>';
		$salida .= '<th class = "text-center" width = "50px">' . $total . '</th>';
		//--
		$salida .= '</tr>';
		//---
		$salida .= '</tbody>';
	}
	$salida .= '</table>';

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



function tabla_elemento($detalle, $proceso, $tipo)
{
	$ClsFic = new ClsFicha();
	$result = $ClsFic->get_elemento('', $proceso, $tipo);

	$salida = '<table class="table" >';
	if (is_array($result)) {
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//No.
			$salida .= '<td width = "5%" class = "text-center">' . $i . '.</td>';
			//Descripcion
			$titulo = trim($row["ele_titulo"]);
			$descripcion = trim($row["ele_descripcion"]);
			$salida .= '<td width = "95%" class = "text-left"><strong>' . $titulo . '</strong><br> ' . $descripcion . '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
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
			$salida .= '<br>';
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

function objetivos_acordion_pdf($proceso, $sistema)
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
			$salida .= '<div class="row">';
			$salida .= '<div class="col-md-12">';
			$salida .= $i . '. <label> ' . $descripcion . '</label>';
			$salida .= '</div>';
			$salida .= '</div>';
			$salida .= '</a>';
			$salida .= '</div>';
			//--
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
		$salida .= '<i class="fa fa-information-circle"></i> No existen objetivos...';
		$salida .= '</h5>';
		$salida .= '</div>';
		$salida .= '</div>';
	}

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
			$salida .= '<div class="col-md-12">';
			$salida .= $i . '. <label> ' . $descripcion . '</label>';
			$salida .= '</div>';
			$salida .= '</div>';
			$salida .= '</a>';
			$salida .= '</div>';
			//--
			$salida .= '<div class="card-body">';
			$salida .= detalle_acordion($codigo);
			$salida .= '</div>';
			$salida .= '</div>';
			$i++;
		}
	} else {
		$salida = '<div class="row">';
		$salida .= '<div class="col-xs-12 col-md-12">';
		$salida .= '<h5 class="alert alert-warning text-center">';
		$salida .= '<i class="fa fa-information-circle"></i> No existen objetivos...';
		$salida .= '</h5>';
		$salida .= '</div>';
		$salida .= '</div>';
	}

	return $salida;
}

function tabla_legal($detalle, $proceso)
{
	$ClsFic = new ClsFicha();
	$result = $ClsFic->get_requisitos_legales('', $proceso);

	$salida = '<table class="table" >';
	if (is_array($result)) {
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//No.
			$salida .= '<td width = "5%" class = "text-center">' . $i . '.</td>';
			//Descripcion
			$descripcion = trim($row["req_descripcion"]);
			$salida .= '<td width = "95%" class = "text-left">' . $descripcion . '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
	}
	$salida .= '</table>';

	return $salida;
}


function tabla_ambiental($detalle, $proceso)
{
	$ClsFic = new ClsFicha();
	$result = $ClsFic->get_aspectos_ambientales('', $proceso);

	$salida = '<table class="table" >';
	if (is_array($result)) {
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//No.
			$salida .= '<td width = "5%" class = "text-center">' . $i . '.</td>';
			//Descripcion
			$descripcion = trim($row["asp_descripcion"]);
			$salida .= '<td width = "95%" class = "text-left">' . $descripcion . '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
	}
	$salida .= '</table>';

	return $salida;
}


function tabla_responsabilidad($detalle, $proceso)
{
	$ClsFic = new ClsFicha();
	$result = $ClsFic->get_responsabilidad_social('', $proceso);

	$salida = '<table class="table" >';
	if (is_array($result)) {
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//No.
			$salida .= '<td width = "5%" class = "text-center">' . $i . '.</td>';
			//Descripcion
			$descripcion = trim($row["resp_descripcion"]);
			$salida .= '<td width = "95%" class = "text-left">' . $descripcion . '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
	}
	$salida .= '</table>';

	return $salida;
}


function tabla_punto_norma($detalle, $proceso)
{
	$ClsFic = new ClsFicha();
	$result = $ClsFic->get_puntos_norma('', $proceso);

	$salida = '<table class="table" >';
	if (is_array($result)) {
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//No.
			$salida .= '<td width = "5%" class = "text-center">' . $i . '.</td>';
			//Descripcion
			$descripcion = trim($row["nor_descripcion"]);
			$salida .= '<td width = "95%" class = "text-left">' . $descripcion . '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
	}
	$salida .= '</table>';

	return $salida;
}


function tabla_riesgos_oportunidades($proceso)
{
	$ClsFic = new ClsFicha();
	$result = $ClsFic->get_foda('', $proceso, '');

	$salida = '<table class="table" >';
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

	return $salida;
}


function tabla_recurso($detalle, $proceso, $tipo)
{
	$ClsRec = new ClsRecursos();
	$result = $ClsRec->get_recurso('', $proceso, $tipo);

	$salida = '<table class="table" >';
	if (is_array($result)) {
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//No.
			$salida .= '<td width = "5%" class = "text-center">' . $i . '.</td>';
			//Clase de recursos
			$clase = trim($row["tip_nombre"]);
			$salida .= '<td class = "text-left">' . $clase . '</td>';
			//Descripcion
			$descripcion = trim($row["rec_descripcion"]);
			$salida .= '<td class = "text-left">' . $descripcion . '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
	}
	$salida .= '</table>';

	return $salida;
}
