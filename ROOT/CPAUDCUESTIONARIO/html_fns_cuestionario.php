<?php 
include_once('../html_fns.php');

function tabla_cuestionarios($codigo){
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_cuestionario($codigo,'','',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida.= '<th class = "text-center" width = "150px">Categor&iacute;a</th>';
		$salida.= '<th class = "text-center" width = "150px">Cuestionario</th>';
		$salida.= '<th class = "text-center" width = "100px">Ponderaci&oacute;</th>';
		$salida.= '<th class = "text-center" width = "50px"><i class="fa fa-cogs"></i></th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["audit_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarCuestionario('.$codigo.');" title = "Editar Cuestionario" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarCuestionario('.$codigo.');" title = "Eliminar Cuestionario" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//codigo
			$codigo = Agrega_Ceros($row["audit_codigo"]);
			$salida.= '<td class = "text-center">#'.$codigo.'</td>';
			//categoria
			$categoria = trim($row["cat_nombre"]);
			$salida.= '<td class = "text-left">'.$categoria.'</td>';
			//nombre
			$nom = trim($row["audit_nombre"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//tipo
			$tipo = trim($row["audit_ponderacion"]);
			switch($tipo){
				case 1: $tipo = "1-10"; break;
				case 2: $tipo = "SI, NO, N/A"; break;
				case 3: $tipo = "SAT, NO SAT"; break;
			}
			$salida.= '<td class = "text-left">'.$tipo.'</td>';
			//--
			$codigo = $row["audit_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<a class="btn btn-info btn-xs" href="FRMsecciones.php?codigo='.$codigo.'" title = "Agregar Secci&oacute;n al Cuestionario" ><i class="fa fa-columns"></i></a>';
					$salida.= '<a class="btn btn-success btn-xs" href="FRMpreguntas.php?codigo='.$codigo.'" title = "Agregar Preguntas a la Cuestionario" ><i class="fa fa-question"></i></a>';
					$salida.= '<a class="btn btn-white btn-xs" href="CPREPORTES/REPcuestionario.php?codigo='.$codigo.'" target="_blank" title = "Ver Previsualizaci&oacute;n del Cuestionario" ><i class="fa fa-print"></i></a>';
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

function tabla_secciones($codigo,$auditoria){
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_secciones($codigo,$auditoria,1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "10px">No.</th>';
		$salida.= '<th class = "text-center" width = "50px">N&uacute;mero</th>';
		$salida.= '<th class = "text-center" width = "150px">T&iacute;tulo</th>';
		$salida.= '<th class = "text-center" width = "250px">Proposito</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=1;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["sec_codigo"];
			$auditoria = $row["sec_auditoria"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarSeccion('.$codigo.','.$auditoria.');" title = "Editar Secci&oacute;n" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarSeccion('.$codigo.','.$auditoria.');" title = "Eliminar Secci&oacute;n" ><i class="fa fa-trash"></i></button>';
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
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}


function tabla_preguntas($codigo,$auditoria){
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_pregunta($codigo,$auditoria,'',1);
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
			$auditoria = $row["pre_auditoria"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarPregunta('.$codigo.','.$auditoria.');" title = "Editar Pregunta" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarPregunta('.$codigo.','.$auditoria.');" title = "Eliminar Pregunta" ><i class="fa fa-trash"></i></button>';
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
				case 2: $tipo = "SI, NO, N/A"; break;
				case 3: $tipo = "SAT, NO SAT"; break;
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


function tabla_cuestionarios_programacion(){
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_cuestionario('','','',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "10px">No.</th>';
		$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida.= '<th class = "text-center" width = "150px">Categor&iacute;a</th>';
		$salida.= '<th class = "text-center" width = "150px">Cuestionario</th>';
		$salida.= '<th class = "text-center" width = "100px">Ponderaci&oacute;</th>';
		$salida.= '<th class = "text-center" width = "20px"><i class="fa fa-cogs"></i></th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=1;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = trim($row["audit_codigo"]);
			$salida.= '<td class = "text-center" >'.$i.'.</td>';
			//codigo
			$codigo = Agrega_Ceros($row["audit_codigo"]);
			$salida.= '<td class = "text-center">#'.$codigo.'</td>';
			//categoria
			$categoria = utf8_decode($row["cat_nombre"]);
			$salida.= '<td class = "text-left">'.$categoria.'</td>';
			//nombre
			$nombre = utf8_decode($row["audit_nombre"]);
			$salida.= '<td class = "text-left">'.$nombre.'</td>';
			//tipo
			$tipo = trim($row["audit_ponderacion"]);
			switch($tipo){
				case 1: $tipo = "1-10"; break;
				case 2: $tipo = "SI, NO, N/A"; break;
				case 3: $tipo = "SAT, NO SAT"; break;
			}
			$salida.= '<td class = "text-left">'.$tipo.'</td>';
			//--
			$codigo = $row["audit_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<a type="button" class="btn btn-white btn-xs" href = "FRMprogramacion.php?codigo='.$codigo.'" title = "Agregar Horarios de Ejecuci&oacute;n" ><i class="fa fa-calendar"></i></a>';
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



function tabla_programacion($codigo,$auditoria){
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_programacion($codigo,$auditoria,'','','','','','','',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="table" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "150px">Sede</th>';
		$salida.= '<th class = "text-center" width = "150px">Departameto</th>';
		$salida.= '<th class = "text-center" width = "100px">Fecha/Hora</th>';
		$salida.= '<th class = "text-center" width = "250px">Observaciones</th>';
		$salida.= '<th class = "text-center" width = "20px"><i class="fa fa-cogs"></i></th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=1;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["pro_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarProgramacion('.$codigo.');" title = "Editar Programaci&oacute;n" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarProgramacion('.$codigo.');" title = "Eliminar Programaci&oacute;n" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//sede
			$sede = trim($row["sed_nombre"]);
			$salida.= '<td class = "text-left">'.$sede.'</td>';
			//departamento
			$departamento = trim($row["dep_nombre"]);
			$salida.= '<td class = "text-left">'.$departamento.'</td>';
			//No.
			$fecha = cambia_fecha($row["pro_fecha"]);
			$hora = substr($row["pro_hora"],0,5);
			$salida.= '<td class = "text-center">'.$fecha.' '.$hora.'</td>';
			//observaciones
			$obs = trim($row["pro_observaciones"]);
			$salida.= '<td class = "text-left">'.$obs.'</td>';
			//--
			$codigo = $row["pro_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<a type="button" class="btn btn-info btn-xs" href = "FRMparticipantes.php?codigo='.$codigo.'" title = "Agregar participantes" ><i class="fa fa-users"></i></a>';
					$salida.= '<a type="button" class="btn btn-info btn-outline btn-xs" href = "FRMactividades.php?codigo='.$codigo.'" title = "Agregar cronograma de actividades" ><i class="fa fa-clipboard-list"></i></a>';
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

function tabla_participantes($programacion,$usuario){
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_usuario_programacion($programacion,$usuario);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "10px">No.</th>';
		$salida.= '<th class = "text-center" width = "150px">Usuario</th>';
		$salida.= '<th class = "text-center" width = "150px">Rol</th>';
		$salida.= '<th class = "text-center" width = "100px">Asignaci&oacute;n</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=1;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$programacion = $row["pus_programacion"];
			$usuario = $row["pus_usuario"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarParticipante('.$programacion.','.$usuario.');" title = "Editar Participante" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarParticipante('.$programacion.','.$usuario.');" title = "Eliminar Participante" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//No.
			$salida.= '<td class = "text-center">'.$i.'.</td>';
			//Usuario
			$usuario = trim($row["pus_tratamiento"]).". ".trim($row["usu_nombre"]);
			$salida.= '<td class = "text-left">'.$usuario.'</td>';
			//Rol
			$pregunta = trim($row["pus_rol"]);
			$salida.= '<td class = "text-left">'.$pregunta.'</td>';
			//Asignacion
			$asignacion = trim($row["pus_asignacion"]);
			$salida.= '<td class = "text-left">'.$asignacion.'</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}


function tabla_actividades($codigo,$programacion){
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_actividades($codigo,$programacion);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "10px">No.</th>';
		$salida.= '<th class = "text-center" width = "100px">Programado</th>';
		$salida.= '<th class = "text-center" width = "150px">Actividad</th>';
		$salida.= '<th class = "text-center" width = "150px">Observaciones</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=1;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["act_codigo"];
			$programacion = $row["act_programacion"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarActividad('.$codigo.','.$programacion.');" title = "Editar Actividad" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarActividad('.$codigo.','.$programacion.');" title = "Eliminar Actividad" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//No.
			$salida.= '<td class = "text-center">'.$i.'.</td>';
			//programacion
			$fechor = cambia_fecha($row["act_fecha"])." ".trim($row["act_hora"]);
			$salida.= '<td class = "text-center">'.$fechor.'</td>';
			//actividad
			$actividad = trim($row["act_descripcion"]);
			$salida.= '<td class = "text-left">'.$actividad.'</td>';
			//observaciones
			$observaciones = trim($row["act_observaciones"]);
			$observaciones = nl2br($observaciones);
			$salida.= '<td class = "text-justify">'.$observaciones.'</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}



////////////////////////////////// REPORTES /////////////////////////////////////////////////

function tabla_reportes($sede,$departamento,$categoria,$fini,$ffin,$situacion,$columnas){
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_programacion('','',$sede,$departamento,$categoria,$fini,$ffin,'','',$situacion,'');
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
			$salida.= '<th class = "text-center" width = "150px">Sede</th>';
			$salida.= '<th class = "text-center" width = "150px">&Aacute;rea</th>';
			$salida.= '<th class = "text-center" width = "150px">Categor&iacute;a</th>';
			$salida.= '<th class = "text-center" width = "100px">Usuario</th>';
			$salida.= '<th class = "text-center" width = "100px">Cuestionario</th>';
			$salida.= '<th class = "text-center" width = "100px">Fecha Programaci&oacute;n</th>';
			$salida.= '<th class = "text-center" width = "100px">Ponderaci&oacute;n</th>';
		}
			$salida.= '<th class = "text-center" width = "20px"><i class="fa fa-cogs"></i></th>';
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
					if($col == "audit_codigo"){
						$campo = '# '.Agrega_Ceros($row[$campo]);
					}else if($col == "pro_situacion"){
						$campo = trim($row[$campo]);
						$campo = ($campo == 1)?'<strong class="text-info">Pendiente</strong>':'<strong class="text-success">Ejecutada</strong>';
					}else if($col == "audit_ponderacion"){
						$tipo = trim($row["audit_ponderacion"]);
						switch($tipo){
							case 1: $campo = "1-10"; break;
							case 2: $campo = "SI, NO, N/A"; break;
							case 3: $campo = "SAT, NO SAT"; break;
						}
					}else if($col == "pro_fecha"){
						$fecha = cambia_fecha($row["pro_fecha"]);
						$hora = substr($row["pro_hora"],0,5);
						$campo = "$fecha $hora";
					}else{
						$campo = trim($row[$campo]);
					}
					//columna
					$salida.= '<td class = "'.$alineacion.'">'.$campo.'</td>';
				}
			}else{
				//sede
				$sede = trim($row["sed_nombre"]);
				$salida.= '<td class = "text-left">'.$sede.'</td>';
				//departamento
				$departamento = trim($row["dep_nombre"]);
				$salida.= '<td class = "text-left">'.$departamento.'</td>';
				//categoria
				$categoria = trim($row["cat_nombre"]);
				$salida.= '<td class = "text-left">'.$categoria.'</td>';
				//Usuario
				$usuario = trim($row["usuario_nombre"]);
				$salida.= '<td class = "text-left">'.$usuario.'</td>';
				//nombre
				$nom = trim($row["audit_nombre"]);
				$salida.= '<td class = "text-left">'.$nom.'</td>';
				//fecha/hora
				$fecha = cambia_fecha($row["pro_fecha"]);
				$hora = substr($row["pro_hora"],0,5);
				$salida.= '<td class = "text-center">'.$fecha.' '.$hora.'</td>';
				//tipo
				$tipo = trim($row["audit_ponderacion"]);
				switch($tipo){
					case 1: $tipo = "1-10"; break;
					case 2: $tipo = "SI, NO, N/A"; break;
					case 3: $tipo = "SAT, NO SAT"; break;
				}
				$salida.= '<td class = "text-left">'.$tipo.'</td>';
			}
			//--
			$codigo = $row["pro_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-info btn-xs" onclick="verParticipantes('.$codigo.');" title = "Ver participantes" ><i class="fa fa-users"></i></button>';
					$salida.= '<button type="button" class="btn btn-info btn-outline btn-xs" onclick="verActividades('.$codigo.');" title = "Ver cronograma de actividades" ><i class="fa fa-clipboard-list"></i></button>';
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


function parametrosDinamicosHTML($columna){
	switch($columna){
		case "audit_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Cuestionario";
			$respuesta["campo"] = "audit_codigo";
			break;
		case "audit_nombre":
			$respuesta["ancho"] = "110";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Nombre del Cuestionario";
			$respuesta["campo"] = "audit_nombre";
			break;
		case "audit_ponderacion":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Ponderaci&oacute;n";
			$respuesta["campo"] = "audit_ponderacion";
			break;
		case "pro_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Prog.";
			$respuesta["campo"] = "pro_codigo";
			break;
		case "pro_fecha":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Fecha/hora";
			$respuesta["campo"] = "pro_fecha";
			break;
		case "pro_observaciones":
			$respuesta["ancho"] = "150";
			$respuesta["alineacion"] = "text-justify";
			$respuesta["titulo"] = "Observaciones (Cuestionario)";
			$respuesta["campo"] = "pro_observaciones";
			break;
		case "pro_situacion":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Situaci&oacute;n";
			$respuesta["campo"] = "pro_situacion";
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
		case "dep_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Departamento";
			$respuesta["campo"] = "dep_codigo";
			break;
		case "dep_nombre":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Departamento";
			$respuesta["campo"] = "dep_nombre";
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


function parametrosDinamicosPDF($columna){
	switch($columna){
		case "audit_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Código";
			$respuesta["campo"] = "audit_codigo";
			break;
		case "audit_nombre":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Nombre del cuestionario";
			$respuesta["campo"] = "audit_nombre";
			break;
		case "audit_ponderacion":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Ponderación";
			$respuesta["campo"] = "audit_ponderacion";
			break;
		case "pro_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Progra.";
			$respuesta["campo"] = "pro_codigo";
			break;
		case "pro_fecha":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha/hora";
			$respuesta["campo"] = "pro_fecha";
			break;
		case "pro_observaciones":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "J";
			$respuesta["titulo"] = "Observaciones (Cuestionario)";
			$respuesta["campo"] = "pro_observaciones";
			break;
		case "pro_situacion":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Situación";
			$respuesta["campo"] = "pro_situacion";
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
		case "dep_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Departamento";
			$respuesta["campo"] = "dep_codigo";
			break;
		case "dep_nombre":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Departamento";
			$respuesta["campo"] = "dep_nombre";
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


function parametrosDinamicosEXCEL($columna){
	switch($columna){
		case "audit_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Codigo";
			$respuesta["campo"] = "audit_codigo";
			break;
		case "audit_nombre":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Nombre del Cuestionario";
			$respuesta["campo"] = "audit_nombre";
			break;
		case "audit_ponderacion":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Ponderacion";
			$respuesta["campo"] = "audit_ponderacion";
			break;
		case "pro_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Programado";
			$respuesta["campo"] = "pro_codigo";
			break;
		case "pro_fecha":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Fecha/hora";
			$respuesta["campo"] = "pro_fecha";
			break;
		case "pro_observaciones":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "J";
			$respuesta["titulo"] = "Observaciones (Cuestionario)";
			$respuesta["campo"] = "pro_observaciones";
			break;
		case "pro_situacion":
			$respuesta["ancho"] = "20";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Situacion";
			$respuesta["campo"] = "pro_situacion";
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
		case "dep_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Departamento";
			$respuesta["campo"] = "dep_codigo";
			break;
		case "dep_nombre":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Departamento";
			$respuesta["campo"] = "dep_nombre";
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
?>