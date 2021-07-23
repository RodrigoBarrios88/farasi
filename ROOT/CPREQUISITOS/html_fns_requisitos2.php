<?php
include_once('../html_fns.php');

function tabla_documentos($codigo)
{
	$ClsDoc = new ClsDocumento2();
	$result = $ClsDoc->get_documento($codigo, "", "", "", "", "", 1);
	if (is_array($result)) {
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "50px"><i class="fa fa-cogs"></i></th>';
		$salida .= '<th class = "text-left" width = "10%">C&oacute;digo</th>';
		$salida .= '<th class = "text-left" width = "20%">T&iacute;tulo</th>';
		$salida .= '<th class = "text-left" width = "20%">Tipo</th>';
		$salida .= '<th class = "text-left" width = "20%">Entidad</th>';
		$salida .= '<th class = "text-left" width = "20%">Sistema</th>';
		$salida .= '<th class = "text-left" width = "30%">Vigente desde</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = $row["doc_codigo"];
		//	$situacion = $row["doc_situacion"];
			$salida .= '<td class = "text-left" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "Seleccionar(' . $codigo . ');" title = "Editar Sistema" ><i class="fa fa-pencil"></i></button>';
			$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "Deshabilitar(' . $codigo . ');" title = "Eliminar Sistema" ><i class="fa fa-trash"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			// No. 
			$salida .= '<td class = "text-center">' . $codigo . '</td>';
			// Titulo
			$titulo = trim($row["doc_titulo"]);
			$titulo = nl2br($titulo);
			if(strlen($titulo) > 50){
				$salida .= '<td class = "text-left">' . substr($titulo, 0, 40) . '...</td>';
			}else{
				$salida .= '<td class = "text-left">' . $titulo . '</td>';

			}
			// Tipo 
			$tipo = trim($row["doc_tipo"]);
			$tipo = nl2br($tipo);
			$salida .= '<td class = "text-left">' . $tipo . '</td>';
			// Fecha
		/*	$fecha = cambia_fecha($row["doc_fecha"]);
			$salida .= '<td class = "text-left">' . $fecha . '</td>';*/
			// Entidad
			$entidad  = trim($row["doc_entidad"]);
			$entidad = nl2br($entidad);
			$salida .= '<td class = "text-left">' . $entidad . '</td>';
			// Sistema
			$sistema  = trim($row["sis_nombre"]);
			$sistema = nl2br($sistema);
			$salida .= '<td class = "text-left">' . $sistema . '</td>';
			// Area
			$vigencia = cambia_fecha($row["doc_vigencia"]);  //cambiar doc_area por vigencia
			$salida .= '<td class = "text-left">' . $vigencia . '</td>';
			//--
			$salida .= '</tr>';
			$i++;;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}

function tabla_requisito($codigo)
{
	$ClsReq = new ClsRequisito();
	$result = $ClsReq->get_requisito($codigo);
	//echo $result;
	if (is_array($result)) {
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-left" width = "50px"><i class="fa fa-cogs"></i></th>';
		$salida .= '<th class = "text-left" width = "10%">C&oacute;digo</th>';
		$salida .= '<th class = "text-left" width = "15%">Nomenclatura</th>';
		$salida .= '<th class = "text-left" width = "20%">Documento</th>';
		$salida .= '<th class = "text-left" width = "18%">Documento Soporte</th>';
		$salida .= '<th class = "text-left" width = "20%">Descripci&oacute;n</th>';
		$salida .= '<th class = "text-left" width = "20%">Fecha Registro</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = trim($row["req_codigo"]);
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsReq->encrypt($codigo, $usu);
			$salida .= '<td class = "text-left" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "Seleccionar(' . $codigo . ');" title = "Editar Sistema" ><i class="fa fa-pencil"></i></button>';
			$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "Deshabilitar(' . $codigo . ');" title = "Eliminar Sistema" ><i class="fa fa-trash"></i></button>';
			$salida .= '<a class="btn btn-dark btn-xs" href = "FRMtipo.php?hashkey=' . $hashkey . '" title = "Evaluacion" ><i class="fas fa-calendar"></i></a> ';
			$salida .= '</div>';
			$salida .= '</td>';
			// No. 
			$salida .= '<td class = "text-center">' . $i . '</td>';
			// Nomenclatura
			$nomenclatura = trim($row["req_nomenclatura"]);
			$nomenclatura = nl2br($nomenclatura);
			$salida .= '<td class = "text-left">' . $nomenclatura . '</td>';
			// Documento 
			$documento = trim($row["doc_titulo"]);
			$documento = nl2br($documento);			
			if(strlen($documento) > 50){
				$salida .= '<td class = "text-left">' . substr($documento, 0, 40) . '...</td>';
			}else{
				$salida .= '<td class = "text-left">' . $documento . '</td>';

			}
			// Documento soporte
			$documentosoporte = trim($row["req_documento_soporte"]);
			$documentosoporte = nl2br($documentosoporte);
			$salida .= '<td class = "text-left">' . $documentosoporte . '</td>';
			// Descripcion
			$descripcion = trim($row["req_descripcion"]);
			$descripcion = nl2br($descripcion);
			$salida .= '<td class = "text-left">' . $descripcion . '</td>';
			// Fecha
			$fecha = cambia_fechaHora($row["req_fecha_registro"]);
			$salida .= '<td class = "text-left">' . $fecha . '</td>';
			//--
			$salida .= '</tr>';
			$i++;;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}


function tabla_tipo_evaluacion($codigo, $requisito)
{
	$ClsTipEval = new ClsTipoEvaluacion();
	$result = $ClsTipEval->get_tipo_evaluacion($codigo, $requisito);
	//var_dump($result);
	if (is_array($result)) {
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-left" width = "50px"><i class="fa fa-cogs"></i></th>';
		$salida .= '<th class = "text-left" width = "10%">C&oacute;digo</th>';
		$salida .= '<th class = "text-left" width = "20%">Tipo Evaluaci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "8%">Requisito Legal </th>';
		$salida .= '<th class = "text-left" width = "20%">Aspecto</th>';
		$salida .= '<th class = "text-left" width = "20%">Componente</th>';
		$salida .= '<th class = "text-left" width = "20%">Frecuencia</th>';
		$salida .= '<th class = "text-left" width = "20%">Fecha Reevaluaci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "8%">Requisito</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = trim($row["eva_codigo"]);
			$salida .= '<td class = "text-left" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "Seleccionar(' . $codigo . ');" title = "Editar Sistema" ><i class="fa fa-pencil"></i></button>';
			$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "Deshabilitar(' . $codigo . ');" title = "Eliminar Sistema" ><i class="fa fa-trash"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			// No. 
			$salida .= '<td class = "text-center">' . $i . '</td>';
			// nombre 
			$nombre = trim($row["eva_nombre"]);
			$nombre = nl2br($nombre);
			$salida .= '<td class = "text-left">' . $nombre . '</td>';
			// cumple
			$cumple = trim($row["eva_cumple"]);
			$cumple = si_no($cumple);
			$salida .= '<td class = "text-center">' . $cumple . '</td>';
			// aspecto
			$aspecto = trim($row["eva_aspecto"]);
			$aspecto = nl2br($aspecto);
			$salida .= '<td class = "text-left">' . $aspecto . '</td>';
			// componente
			$componente = trim($row["eva_componente"]);
			$componente = nl2br($componente);
			$salida .= '<td class = "text-left">' . $componente . '</td>';
			// frecuencia
			$frecuencia = trim($row["eva_frecuencia"]);
			$frecuencia = Frecuencias($frecuencia);
			$salida .= '<td class = "text-left">' . $frecuencia . '</td>';
			//fecha reevaluacion
			$fechaReevaluacion = trim($row["eva_fecha_reevaluacion"]);
			$fechaReevaluacion = cambia_fecha($fechaReevaluacion);
			$salida .= '<td class = "text-left">' . $fechaReevaluacion . '</td>';
			// cumple
			$evarequisito = trim($row["eva_requisto"]);
			$evarequisito = si_no($evarequisito);
			$salida .= '<td class = "text-center">' . $evarequisito . '</td>';
			//--
			$salida .= '</tr>';
			$i++;;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}

/*



function parametrosDinamicosHTML($columna){
	switch($columna){
		case "bib_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Biblioteca";
			$respuesta["campo"] = "bib_codigo";
			break;
		case "bib_titulo":
			$respuesta["ancho"] = "110";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Nombre de la Biblioteca";
			$respuesta["campo"] = "bib_titulo";
			break;
		case "bib_ponderacion":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Ponderaci&oacute;n";
			$respuesta["campo"] = "bib_ponderacion";
			break;
		case "inv_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Prog.";
			$respuesta["campo"] = "inv_codigo";
			break;
		case "inv_fecha_registro":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Fecha/hora";
			$respuesta["campo"] = "inv_fecha_registro";
			break;
		case "inv_cliente":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Cliente";
			$respuesta["campo"] = "inv_cliente";
			break;
		case "inv_correo":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Correo";
			$respuesta["campo"] = "inv_correo";
			break;
		case "inv_url":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "URL (redirecci&oacute;n)";
			$respuesta["campo"] = "inv_url";
			break;
		case "inv_observaciones":
			$respuesta["ancho"] = "150";
			$respuesta["alineacion"] = "text-justify";
			$respuesta["titulo"] = "Observaciones (Biblioteca)";
			$respuesta["campo"] = "inv_observaciones";
			break;
		case "inv_situacion":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Situaci&oacute;n";
			$respuesta["campo"] = "inv_situacion";
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
		case "usuario_nombre":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Usuario (Registr&oacute;)";
			$respuesta["campo"] = "usuario_nombre";
			break;
	}	
	return $respuesta;
}


function parametrosDinamicosPDF($columna){
	switch($columna){
		case "bib_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Código";
			$respuesta["campo"] = "bib_codigo";
			break;
		case "bib_titulo":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Nombre de la Biblioteca";
			$respuesta["campo"] = "bib_titulo";
			break;
		case "bib_ponderacion":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Ponderación";
			$respuesta["campo"] = "bib_ponderacion";
			break;
		case "inv_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Progra.";
			$respuesta["campo"] = "inv_codigo";
			break;
		case "inv_fecha_registro":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha/hora";
			$respuesta["campo"] = "inv_fecha_registro";
			break;
		case "inv_cliente":
			$respuesta["ancho"] = "45";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Cliente";
			$respuesta["campo"] = "inv_cliente";
			break;
		case "inv_correo":
			$respuesta["ancho"] = "45";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Correo";
			$respuesta["campo"] = "inv_correo";
			break;
		case "inv_url":
			$respuesta["ancho"] = "45";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "URL (redirección)";
			$respuesta["campo"] = "inv_url";
			break;
		case "inv_observaciones":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "J";
			$respuesta["titulo"] = "Observaciones (Biblioteca)";
			$respuesta["campo"] = "inv_observaciones";
			break;
		case "inv_situacion":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Situación";
			$respuesta["campo"] = "inv_situacion";
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
		case "usuario_nombre":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Usuario (Registró)";
			$respuesta["campo"] = "usuario_nombre";
			break;
	}	
	return $respuesta;
}


function parametrosDinamicosEXCEL($columna){
	switch($columna){
		case "bib_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Codigo";
			$respuesta["campo"] = "bib_codigo";
			break;
		case "bib_titulo":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Nombre de la Biblioteca";
			$respuesta["campo"] = "bib_titulo";
			break;
		case "bib_ponderacion":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Ponderacion";
			$respuesta["campo"] = "bib_ponderacion";
			break;
		case "inv_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Programado";
			$respuesta["campo"] = "inv_codigo";
			break;
		case "inv_fecha_registro":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Fecha/hora";
			$respuesta["campo"] = "inv_fecha_registro";
			break;
		case "inv_cliente":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Cliente";
			$respuesta["campo"] = "inv_cliente";
			break;
		case "inv_correo":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Correo";
			$respuesta["campo"] = "inv_correo";
			break;
		case "inv_url":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "URL (redireccion)";
			$respuesta["campo"] = "inv_url";
			break;
		case "inv_observaciones":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "J";
			$respuesta["titulo"] = "Observaciones (Biblioteca)";
			$respuesta["campo"] = "inv_observaciones";
			break;
		case "inv_situacion":
			$respuesta["ancho"] = "20";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Situacion";
			$respuesta["campo"] = "inv_situacion";
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
			$respuesta["titulo"] = "Categoria";
			$respuesta["campo"] = "cat_nombre";
			break;
		case "cat_color":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Color";
			$respuesta["campo"] = "cat_color";
			break;
		case "usuario_nombre":
			$respuesta["ancho"] = "20";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Usuario (Registro)";
			$respuesta["campo"] = "usuario_nombre";
			break;
	}	
	return $respuesta;
}
*/
