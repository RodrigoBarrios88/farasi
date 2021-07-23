<?php 
include_once('../html_fns.php');


function tabla_categorias($codigo){
	$ClsEnc = new ClsEncuesta();
	$result = $ClsEnc->get_categoria($codigo,'',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "50px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida.= '<th class = "text-center" width = "250px">Nombre</th>';
		$salida.= '<th class = "text-center" width = "20px">Color</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["cat_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarCategoria('.$codigo.');" title = "Editar Categoria" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarCategoria('.$codigo.');" title = "Eliminar Categoria" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//codigo
			$codigo = Agrega_Ceros($row["cat_codigo"]);
			$salida.= '<td class = "text-center">'.$codigo.'</td>';
			//nombre
			$nom = trim($row["cat_nombre"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//color
			$color = trim($row["cat_color"]);
			$salida.= '<td class = "text-center"><i class="fa fa-square fa-2x" style="color: '.$color.'"></i></td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}


function tabla_cuestionarios($codigo){
	$ClsEnc = new ClsEncuesta();
	$result = $ClsEnc->get_cuestionario($codigo,'',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida.= '<th class = "text-center" width = "150px">Categor&iacute;a</th>';
		$salida.= '<th class = "text-center" width = "150px">Cuestionario</th>';
		$salida.= '<th class = "text-center" width = "50px"><i class="fa fa-cogs"></i></th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["cue_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarCuestionario('.$codigo.');" title = "Editar Cuestionario" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarCuestionario('.$codigo.');" title = "Eliminar Cuestionario" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//codigo
			$codigo = Agrega_Ceros($row["cue_codigo"]);
			$salida.= '<td class = "text-center">#'.$codigo.'</td>';
			//categoria
			$categoria = trim($row["cat_nombre"]);
			$salida.= '<td class = "text-left">'.$categoria.'</td>';
			//nombre
			$nom = trim($row["cue_titulo"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//--
			$codigo = $row["cue_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<a class="btn btn-info btn-xs" href="FRMsecciones.php?codigo='.$codigo.'" title = "Agregar Secci&oacute;n al Cuestionario" ><i class="fa fa-columns"></i></a>';
					$salida.= '<a class="btn btn-success btn-xs" href="FRMpreguntas.php?codigo='.$codigo.'" title = "Agregar Preguntas a la Cuestionario" ><i class="fa fa-question"></i></a>';
				//	$salida.= '<a class="btn btn-white btn-xs" href="CPREPORTES/REPcuestionario.php?codigo='.$codigo.'" target="_blank" title = "Ver Previsualizaci&oacute;n del Cuestionario" ><i class="fa fa-print"></i></a>';
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

function tabla_secciones($codigo,$encuesta){
	$ClsEnc = new ClsEncuesta();
	$result = $ClsEnc->get_secciones($codigo,$encuesta,1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "10px">No.</th>';
		$salida.= '<th class = "text-center" width = "50px">N&uacute;mero</th>';
		$salida.= '<th class = "text-center" width = "150px">T&iacute;tulo</th>';
		$salida.= '<th class = "text-center" width = "250px">Prop&oacute;sito</th>';
		$salida.= '<th class = "text-center" width = "10px"><i class="fa fa-cogs"></i></th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=1;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["sec_codigo"];
			$encuesta = $row["sec_encuesta"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarSeccion('.$codigo.','.$encuesta.');" title = "Editar Secci&oacute;n" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarSeccion('.$codigo.','.$encuesta.');" title = "Eliminar Secci&oacute;n" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//No.
			$salida.= '<td class = "text-center">'.$i.'.</td>';
			//seccion
			$numero = trim($row["sec_numero"]);
			$salida.= '<td class = "text-center">'.$numero.'.</td>';
			//titulo
			$titulo = trim($row["sec_titulo"]);
			$salida.= '<td class = "text-left">'.$titulo.'</td>';
			//proposito
			$proposito = trim($row["sec_proposito"]);
			$proposito = nl2br($proposito);
			$salida.= '<td class = "text-justify">'.$proposito.'</td>';
			//--
			$codigo = $row["cue_codigo"];
			$seccion = $row["sec_codigo"];
			$salida.= '<td class = "text-center" >';
			$salida.= '<a class="btn btn-success btn-xs" href="FRMpreguntas.php?codigo='.$codigo.'&seccion='.$seccion.'" title = "Agregar Preguntas a la Cuestionario" ><i class="fa fa-question"></i></a>';
			$salida.= '</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}


function tabla_preguntas($codigo,$encuesta){
	$ClsEnc = new ClsEncuesta();
	$result = $ClsEnc->get_pregunta($codigo,$encuesta,'',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "10px">No.</th>';
		$salida.= '<th class = "text-center" width = "150px">Secci&oacute;n</th>';
		$salida.= '<th class = "text-center" width = "150px">Preguntas</th>';
		$salida.= '<th class = "text-center" width = "100px">Tipo</th>';
		$salida.= '<th class = "text-center" width = "50px">Peso Ponderado</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=1;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["pre_codigo"];
			$encuesta = $row["pre_encuesta"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarPregunta('.$codigo.','.$encuesta.');" title = "Editar Pregunta" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarPregunta('.$codigo.','.$encuesta.');" title = "Eliminar Pregunta" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//No.
			$salida.= '<td class = "text-center">'.$i.'.</td>';
			//seccion
			$seccion = trim($row["sec_numero"]).". ".trim($row["sec_titulo"]);
			$salida.= '<td class = "text-left">'.$seccion.'</td>';
			//pregunta
			$pregunta = trim($row["pre_pregunta"]);
			$pregunta = nl2br($pregunta);
			$salida.= '<td class = "text-justify">'.$pregunta.'</td>';
			//tipo
			$tipo = trim($row["pre_tipo"]);
			switch($tipo){
				case 1: $tipo = "1-10"; break;
				case 2: $tipo = "SI &oacute; NO"; break;
			}
			$salida.= '<td class = "text-left">'.$tipo.'</td>';
			//peso
			$peso = trim($row["pre_peso"]);
			$salida.= '<td class = "text-center">'.$peso.'</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}


function tabla_cuestionarios_invitacion(){
	$ClsEnc = new ClsEncuesta();
	$result = $ClsEnc->get_cuestionario('','',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "10px">No.</th>';
		$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida.= '<th class = "text-center" width = "150px">Categor&iacute;a</th>';
		$salida.= '<th class = "text-center" width = "150px">Cuestionario</th>';
		$salida.= '<th class = "text-center" width = "20px"><i class="fa fa-cogs"></i></th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=1;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = trim($row["cue_codigo"]);
			$salida.= '<td class = "text-center" >'.$i.'.</td>';
			//codigo
			$codigo = Agrega_Ceros($row["cue_codigo"]);
			$salida.= '<td class = "text-center">#'.$codigo.'</td>';
			//categoria
			$categoria = utf8_decode($row["cat_nombre"]);
			$salida.= '<td class = "text-left">'.$categoria.'</td>';
			//nombre
			$nombre = utf8_decode($row["cue_titulo"]);
			$salida.= '<td class = "text-left">'.$nombre.'</td>';
			//--
			$codigo = $row["cue_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<a type="button" class="btn btn-white btn-xs" href = "FRMinvitacion.php?codigo='.$codigo.'" title = "Invitar para resolver" ><i class="fas fa-mail-bulk"></i></a>';
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



function tabla_invitacion($codigo,$encuesta){
	$ClsEnc = new ClsEncuesta();
	$result = $ClsEnc->get_invitacion($codigo,$encuesta,'','','',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "150px">Cliente</th>';
		$salida.= '<th class = "text-center" width = "150px">Correo</th>';
		$salida.= '<th class = "text-center" width = "100px">Fecha/Hora</th>';
		$salida.= '<th class = "text-center" width = "250px">Observaciones</th>';
		$salida.= '<th class = "text-center" width = "10px"><i class="fa fa-cogs"></i></th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=1;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["inv_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarInvitacion('.$codigo.');" title = "Editar Invitaci&oacute;n" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarInvitacion('.$codigo.');" title = "Eliminar Invitaci&oacute;n" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//cliente
			$cliente = trim($row["inv_cliente"]);
			$salida.= '<td class = "text-left">'.$cliente.'</td>';
			//correo
			$correo = trim($row["inv_correo"]);
			$salida.= '<td class = "text-left">'.$correo.'</td>';
			//No.
			$fecha = cambia_fechaHora($row["inv_fecha_registro"]);
			$salida.= '<td class = "text-center">'.$fecha.'</td>';
			//observaciones
			$obs = trim($row["inv_observaciones"]);
			$salida.= '<td class = "text-left">'.$obs.'</td>';
			//--
			$codigo = $row["inv_codigo"];/////
			$usuario = $_SESSION['codigo'];
			$hashkey = $ClsEnc->encryptt($codigo);
			$url = "https://" . $_SERVER['HTTP_HOST'] . "/ROOT/ENCEJECUCION/?hashkey=".$hashkey;
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs text-primary" onclick="hashkeyInvitacion(\''.$url.'\');" title = "Compartir Enlace Hash" ><i class="fas fa-mail-bulk"></i></button>';
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

function tabla_reportes($categoria,$encuesta,$fini,$ffin,$situacion,$columnas){
	$ClsEnc = new ClsEncuesta();
	$result = $ClsEnc->get_invitacion('',$encuesta,$categoria,$fini,$ffin,$situacion);
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
			$salida.= '<th class = "text-center" width = "100px">Cuestionario</th>';
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
					if($col == "cue_codigo"){
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
				$titulo = trim($row["cue_titulo"]);
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
		case "cue_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Cuestionario";
			$respuesta["campo"] = "cue_codigo";
			break;
		case "cue_titulo":
			$respuesta["ancho"] = "110";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Nombre del cuestionario";
			$respuesta["campo"] = "cue_titulo";
			break;
		case "cue_ponderacion":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Ponderaci&oacute;n";
			$respuesta["campo"] = "cue_ponderacion";
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
			$respuesta["titulo"] = "Observaciones (Cuestionario)";
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
		case "cue_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Código";
			$respuesta["campo"] = "cue_codigo";
			break;
		case "cue_titulo":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Nombre del cuestionario";
			$respuesta["campo"] = "cue_titulo";
			break;
		case "cue_ponderacion":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Ponderación";
			$respuesta["campo"] = "cue_ponderacion";
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
			$respuesta["titulo"] = "Observaciones (Cuestionario)";
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
		case "cue_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Codigo";
			$respuesta["campo"] = "cue_codigo";
			break;
		case "cue_titulo":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Nombre del cuestionario";
			$respuesta["campo"] = "cue_titulo";
			break;
		case "cue_ponderacion":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Ponderacion";
			$respuesta["campo"] = "cue_ponderacion";
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
			$respuesta["titulo"] = "Observaciones (Cuestionario)";
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
?>