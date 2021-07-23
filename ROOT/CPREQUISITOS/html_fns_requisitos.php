<?php
include_once('../html_fns.php');

////////////////////////// Asignaciones //////////////////////

function tabla_requisito_proceso($codigo, $ficha, $usuario, $situacion)
{
	$ClsReq = new ClsRequisito();
	$result = $ClsReq->get_requisito_procesos($codigo, $ficha, $usuario, $situacion,'',true);
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">C&oacute;digo Ficha</th>';
		$salida .= '<th class = "text-center" width = "150px">Requisito</th>';
		$salida .= '<th class = "text-center" width = "10px"></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = agrega_ceros($row["req_codigo"]);
			$salida .= '<td class = "text-center">' . $codigo . '.</td>';
			//nombre
			$nombre = utf8_decode($row["req_nomenclatura"]);
			$salida .= '<td class = "text-left">' . $nombre . '</td>';
			// Ver Usuarios
			$codigo = $row["req_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-info btn-xs" onclick = "usuarios_requisito(' . $codigo . ');" title = "Personas asignadas a este requisito" ><i class="fa fa-user"></i></button>';
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





function tabla_documentos($codigo)
{
	$ClsBib = new ClsDocumento();
	$result = $ClsBib->get_documento($codigo);
	if (is_array($result)) {
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-left" width = "10%px"><i class="fa fa-cogs"></i></th>';
		$salida .= '<th class = "text-center" width = "10%">C&oacute;digo</th>';
		$salida .= '<th class = "text-left" width = "35%">T&iacute;tulo</th>';
		$salida .= '<th class = "text-left" width = "10%">Tipo</th>';
		$salida .= '<th class = "text-left" width = "15%">Fecha</th>';
		$salida .= '<th class = "text-left" width = "15%">Entidad</th>';
		$salida .= '<th class = "text-left" width = "15%">Derogado</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = $row["doc_codigo"];
			$salida .= '<td class = "text-left" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "Seleccionar(' . $codigo . ');" title = "Editar Sistema" ><i class="fa fa-pencil"></i></button>';
			$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "Deshabilitar(' . $codigo . ');" title = "Eliminar Sistema" ><i class="fa fa-trash"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			// No. 
			$salida .= '<td class = "text-center">' . $i . '</td>';
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
			$fecha = cambia_fecha($row["doc_vigencia"]);
			$salida .= '<td class = "text-left">' . $fecha . '</td>';
			// Entidad
			$entidad  = trim($row["doc_entidad"]);
			$entidad = nl2br($entidad);
			$salida .= '<td class = "text-left">' . $entidad . '</td>';
			// Derogado

			$derogado  = trim($row["doc_derogado"]);
			$derogado = nl2br($derogado);
			$salida .= '<td class = "text-left">' . si_no($derogado) . '</td>';
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
		$salida .= '<th class = "text-left" width = "5%">N&uacute;mero</th>';
		$salida .= '<th class = "text-left" width = "15%">C&oacute;digo</th>';
		$salida .= '<th class = "text-left" width = "20%">Documento</th>';
		$salida .= '<th class = "text-left" width = "18%">Art. Punto, otro</th>';
		$salida .= '<th class = "text-left" width = "20%">Descripci&oacute;n</th>';
		$salida .= '<th class = "text-left" width = "20%">Fecha Registro</th>';
		$salida .= '<th class = "text-left" width = "20%">Clasificación</th>';
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
			//clasificacion
			$requisito = trim($row["req_tipo"]);
			$requisito = nl2br($requisito);
			
			if($requisito == "1"){
			   $salida .= '<td class = "text-left">' . "requisito legal". '</td>';
			}
			else if($requisito == "2"){
				$salida .= '<td class = "text-left">' . "requisito Normativo". '</td>';
			}
			else if($requisito == "3"){
				$salida .= '<td class = "text-left">' . "otro requisito". '</td>';
			}
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

function tabla_gestor_biblioteca($codigo){
	$ClsBib = new ClsBiblioteca();
	$result = $ClsBib->get_biblioteca($codigo,'','1,2,3,10');
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "50px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida.= '<th class = "text-left" width = "150px">Categor&iacute;a</th>';
		$salida.= '<th class = "text-left" width = "150px">T&iacute;tulo</th>';
		$salida.= '<th class = "text-center" width = "50px">Versi&oacute;n</th>';
		$salida.= '<th class = "text-center" width = "50px">Vence</th>';
		$salida.= '<th class = "text-center" width = "50px">Status</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=1;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["bib_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarBiblioteca('.$codigo.');" title = "Editar Documento" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-warning btn-xs" onclick = "obosletoBiblioteca('.$codigo.');" title = "Marcar como Obosleto" ><i class="fa fa-exclamation-circle"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarBiblioteca('.$codigo.');" title = "Eliminar Documento" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//codigo
			$codigo = Agrega_Ceros($row["bib_codigo"]);
			$salida.= '<td class = "text-center">#'.$codigo.'</td>';
			//categoria
			$categoria = trim($row["cat_nombre"]);
			$salida.= '<td class = "text-left">'.$categoria.'</td>';
			//titulo
			$titulo = trim($row["bib_titulo"]);
			$salida.= '<td class = "text-left">'.$titulo.'</td>';
			//version
			$version = trim($row["bib_version"]);
			$salida.= '<td class = "text-center">'.$version.'</td>';
			//vence
			$vence = cambia_fechaHora($row["bib_fecha_vence"]);
			$vence = substr($vence,0,10);
			$salida.= '<td class = "text-center">'.$vence.'</td>';
			//status
			$situacion = trim($row["bib_situacion"]);
			switch($situacion){
				case 0: $status = '<span class="text-danger">Eliminado</span>'; break; 
				case 1: $status = '<span class="text-muted">Edici&oacute;n</span>'; break; 
				case 2: $status = '<span class="text-info">En Aprobaci&oacute;n</span>'; break; 
				case 3: $status = '<span class="text-success">Versi&oacute;n Aprobada</span>'; break; 
				case 10: $status = '<strong class="text-warning">Obsoleto</strong>'; break; 
			}
			$salida.= '<td class = "text-center">'.$status.'</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}


function tabla_gestor_versiones($codigo){
	$ClsBib = new ClsBiblioteca();
	$result = $ClsBib->get_biblioteca($codigo,'','1,2,3,10');
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "10px">No.</th>';
		$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida.= '<th class = "text-left" width = "150px">Categor&iacute;a</th>';
		$salida.= '<th class = "text-left" width = "150px">T&iacute;tulo</th>';
		$salida.= '<th class = "text-center" width = "50px">Versi&oacute;n</th>';
		$salida.= '<th class = "text-center" width = "50px">Vence</th>';
		$salida.= '<th class = "text-center" width = "50px">Status</th>';
		$salida.= '<th class = "text-center" width = "50px"><i class="fa fa-cogs"></i></th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=1;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$salida.= '<td class = "text-center" >'.$i.'.</td>';
			//codigo
			$codigo = Agrega_Ceros($row["bib_codigo"]);
			$salida.= '<td class = "text-center">#'.$codigo.'</td>';
			//categoria
			$categoria = trim($row["cat_nombre"]);
			$salida.= '<td class = "text-left">'.$categoria.'</td>';
			//titulo
			$titulo = trim($row["bib_titulo"]);
			$salida.= '<td class = "text-left">'.$titulo.'</td>';
			//version
			$version = trim($row["bib_version"]);
			$salida.= '<td class = "text-center">'.$version.'</td>';
			//vence
			$vence = cambia_fechaHora($row["bib_fecha_vence"]);
			$vence = substr($vence,0,10);
			$salida.= '<td class = "text-center">'.$vence.'</td>';
			//status
			$situacion = trim($row["bib_situacion"]);
			switch($situacion){
				case 0: $status = '<span class="text-danger">Eliminado</span>'; break; 
				case 1: $status = '<span class="text-muted">Edici&oacute;n</span>'; break; 
				case 2: $status = '<span class="text-info">En Aprobaci&oacute;n</span>'; break; 
				case 3: $status = '<span class="text-success">Versi&oacute;n Aprobada</span>'; break; 
				case 10: $status = '<strong class="text-warning">Obsoleto</strong>'; break; 
			}
			$salida.= '<td class = "text-center">'.$status.'</td>';
			//codigo
			$codigo = trim($row["bib_codigo"]);
			$archivo = trim($row["bib_documento"]);
			$usuario = $_SESSION["codigo"];
			$hashkey = $ClsBib->encrypt($codigo,$usuario);
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group" role="group">';
				$salida.= '<button id="btn-documento-'.$codigo.'" type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-book"></i></button>';
				$salida.= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
				$salida.= '<a class="dropdown-item" onclick = "newdocumento('.$codigo.');" href="javascript:void(0);"><i class="fa fa-cloud-upload-alt"></i> Cargar Documento (Nueva Versi&oacute;n)</a>';
				if($archivo != ""){
					$salida.= '<a class="dropdown-item" onclick = "solicitarAprobacion('.$codigo.');" href="javascript:void(0);"><i class="fa fa-question-circle"></i> Solicitar Aprobaci&oacute;n</a>';
					$salida.= '<a class="dropdown-item" href="EXEverdocumento.php?hashkey='.$hashkey.'" target = "_blank"><i class="fa fa-book-open"></i> Ver Documento</a>';
					$salida.= '<a class="dropdown-item text-danger" onclick = "eliminarDocumento('.$codigo.');" href="javascript:void(0);"><i class="fa fa-trash"></i> Borrar Documento</a>';
				}else{
					$salida.= '<a class="dropdown-item text-muted" href="javascript:void(0);" ><i class="fa fa-book-open"></i> A&uacute;n no hay Documento</a>';
				}
				$salida.= '</div>';
				$salida.= '</div>';
			$salida.= '</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}


function tabla_gestor_aprobaciones($codigo){
	$ClsBib = new ClsBiblioteca();
	$result = $ClsBib->get_biblioteca($codigo,'','2');
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "10px">No.</th>';
		$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida.= '<th class = "text-left" width = "150px">Categor&iacute;a</th>';
		$salida.= '<th class = "text-left" width = "150px">T&iacute;tulo</th>';
		$salida.= '<th class = "text-center" width = "50px">Versi&oacute;n</th>';
		$salida.= '<th class = "text-center" width = "50px">Vence</th>';
		$salida.= '<th class = "text-center" width = "50px">Status</th>';
		$salida.= '<th class = "text-center" width = "50px"><i class="fa fa-cogs"></i></th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=1;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$salida.= '<td class = "text-center" >'.$i.'.</td>';
			//codigo
			$codigo = Agrega_Ceros($row["bib_codigo"]);
			$salida.= '<td class = "text-center">#'.$codigo.'</td>';
			//categoria
			$categoria = trim($row["cat_nombre"]);
			$salida.= '<td class = "text-left">'.$categoria.'</td>';
			//titulo
			$titulo = trim($row["bib_titulo"]);
			$salida.= '<td class = "text-left">'.$titulo.'</td>';
			//version
			$version = trim($row["bib_version"]);
			$salida.= '<td class = "text-center">'.$version.'</td>';
			//vence
			$vence = cambia_fechaHora($row["bib_fecha_vence"]);
			$vence = substr($vence,0,10);
			$salida.= '<td class = "text-center">'.$vence.'</td>';
			//status
			$situacion = trim($row["bib_situacion"]);
			switch($situacion){
				case 0: $status = '<span class="text-danger">Eliminado</span>'; break; 
				case 1: $status = '<span class="text-muted">Edici&oacute;n</span>'; break; 
				case 2: $status = '<span class="text-info">En Aprobaci&oacute;n</span>'; break; 
				case 3: $status = '<span class="text-success">Versi&oacute;n Aprobada</span>'; break; 
				case 10: $status = '<strong class="text-warning">Obsoleto</strong>'; break; 
			}
			$salida.= '<td class = "text-center">'.$status.'</td>';
			//codigo
			$codigo = trim($row["bib_codigo"]);
			$salida.= '<td class = "text-center" >';
				$salida.= '<button type="button" class="btn btn-success" onclick = "aprobarBiblioteca('.$codigo.');"><i class="fa fa-check-circle"></i></button>';
			$salida.= '</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}


function tabla_documentos(){
	$ClsBib = new ClsBiblioteca();
	$result = $ClsBib->get_biblioteca('','','1,2,3,10');
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "10px">No.</th>';
		$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida.= '<th class = "text-left" width = "100px">Categor&iacute;a</th>';
		$salida.= '<th class = "text-left" width = "100px">T&iacute;tulo</th>';
		$salida.= '<th class = "text-left" width = "200px">Descripci&oacute;n</th>';
		$salida.= '<th class = "text-center" width = "50px">Versi&oacute;n</th>';
		$salida.= '<th class = "text-center" width = "50px">Vence</th>';
		$salida.= '<th class = "text-center" width = "50px">Status</th>';
		$salida.= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=1;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$salida.= '<td class = "text-center" >'.$i.'.</td>';
			//codigo  
			$codigo = Agrega_Ceros($row["bib_codigo_interno"]);
			$salida.= '<td class = "text-center">#'.$codigo.'</td>';
			//categoria
			$categoria = trim($row["cat_nombre"]);
			$salida.= '<td class = "text-left">'.$categoria.'</td>';
			//nombre
			$nom = trim($row["bib_titulo"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//descripcion
			$descripcion = trim($row["bib_descripcion"]);
			$descripcion = nl2br($descripcion);
			$salida.= '<td class = "text-justify">'.$descripcion.'</td>';
			//version
			$version = trim($row["bib_version"]);
			$salida.= '<td class = "text-center">'.$version.'</td>';
			//vence
			$vence = cambia_fechaHora($row["bib_fecha_vence"]);
			$vence = substr($vence,0,10);
			$salida.= '<td class = "text-center">'.$vence.'</td>';
			//status
			$situacion = trim($row["bib_situacion"]);
			switch($situacion){
				case 0: $status = '<span class="text-danger">Eliminado</span>'; break; 
				case 1: $status = '<span class="text-muted">Edici&oacute;n</span>'; break; 
				case 2: $status = '<span class="text-info">En Aprobaci&oacute;n</span>'; break; 
				case 3: $status = '<span class="text-success">Versi&oacute;n Aprobada</span>'; break; 
				case 10: $status = '<strong class="text-warning">Obsoleto</strong>'; break; 
			}
			$salida.= '<td class = "text-center">'.$status.'</td>';
			//codigo
			$codigo = trim($row["bib_codigo"]);
			$archivo = trim($row["bib_documento"]);
			$usuario = $_SESSION["codigo"];
			$hashkey = $ClsBib->encrypt($codigo,$usuario);
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-info btn-xs" onclick = "verHistorial('.$codigo.');" title = "Ver Historial del Documento" ><i class="fa fa-info"></i></button>';
				if($archivo != ""){
					$salida.= '<a class="btn btn-primary" href="EXEverdocumento.php?hashkey='.$hashkey.'" target = "_blank"><i class="fa fa-book-open"></i></a>';
				}else{
					$salida.= '<a class="btn btn-white" href="javascript:void(0);" disabled ><i class="fa fa-ban"></i></a>';
				}
				$salida.= '</div>';
			$salida.= '</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}

////////////////////////////////// REPORTES /////////////////////////////////////////////////

function tabla_reportes($categoria,$biblioteca,$fini,$ffin,$situacion,$columnas){
	$ClsBib = new ClsBiblioteca();
	$result = $ClsBib->get_invitacion('',$biblioteca,$categoria,$fini,$ffin,$situacion);
	if(is_array($result)){
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "10px">No.</th>';
		if(is_array($columnas)){
			foreach($columnas as $col){
			   $parametros = parametrosDinamicosHTML($col);
			   $ancho = $parametros['ancho'];
			   $titulo = $parametros['titulo'];
			   $salida.= '<th class = "text-center" width = "'.$ancho.'">'.$titulo.'</th>';
			}
		}else{
			$salida.= '<th class = "text-center" width = "150px">Categor&iacute;a</th>';
			$salida.= '<th class = "text-center" width = "100px">Biblioteca</th>';
			$salida.= '<th class = "text-center" width = "100px">Cliente</th>';
			$salida.= '<th class = "text-center" width = "100px">Correo</th>';
			$salida.= '<th class = "text-center" width = "100px">Fecha Invitaci&oacute;n</th>';
		}
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i = 1;
		foreach($result as $row){
			$salida.= '<tr>';
			//--
			$salida.= '<td class = "text-center">'.$i.'.- </td>';
			//--
			if(is_array($columnas)){
				foreach($columnas as $col){
					$parametros = parametrosDinamicosHTML($col);
					$campo = $parametros['campo'];
					$alineacion = $parametros['alineacion'];
					if($col == "bib_codigo"){
						$campo = '# '.Agrega_Ceros($row[$campo]);
					}else if($col == "inv_situacion"){
						$campo = trim($row[$campo]);
						$campo = ($campo == 1)?'<strong class="text-info">Pendiente</strong>':'<strong class="text-success">Ejecutada</strong>';
					}else if($col == "inv_fecha_registro"){
						$campo = cambia_fechaHora($row["inv_fecha_registro"]);
					}else{
						$campo = trim($row[$campo]);
					}
					$j++;
					//columna
					$salida.= '<td class = "'.$alineacion.'">'.$campo.'</td>';
				}
			}else{
				//categoria
				$categoria = trim($row["cat_nombre"]);
				$salida.= '<td class = "text-left">'.$categoria.'</td>';
				//Cliente
				$cliente = trim($row["inv_cliente"]);
				$salida.= '<td class = "text-left">'.$cliente.'</td>';
				//Correo
				$correo = trim($row["inv_correo"]);
				$salida.= '<td class = "text-left">'.$correo.'</td>';
				//nombre
				$titulo = trim($row["bib_titulo"]);
				$salida.= '<td class = "text-left">'.$titulo.'</td>';
				//fecha/hora
				$fecha = cambia_fecha($row["inv_fecha_registro"]);
				$hora = substr($row["inv_hora"],0,5);
				$salida.= '<td class = "text-center">'.$fecha.' '.$hora.'</td>';
			}
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}


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
