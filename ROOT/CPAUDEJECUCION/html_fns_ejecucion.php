<?php 
include_once('../html_fns.php');
require_once("../recursos/mandrill/src/Mandrill.php"); //--correos
require_once("../../CONFIG/constructor.php");

function tabla_programacion($codigo,$sedes,$usuario,$fini,$ffin){
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_programacion('','',$sedes,'','',$fini,$ffin,'','',1,$usuario);
	if(is_array($result)){
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "10px">No.</th>';
		$salida.= '<th class = "text-center" width = "150px">Sede</th>';
		$salida.= '<th class = "text-center" width = "150px">Departamento</th>';
		$salida.= '<th class = "text-center" width = "150px">Categor&iacute;a</th>';
		$salida.= '<th class = "text-center" width = "150px">Auditor&iacute;a</th>';
		$salida.= '<th class = "text-center" width = "100px">Fecha/Hora</th>';
		$salida.= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i = 1;
		foreach($result as $row){
			$salida.= '<tr>';
			//No.
			$salida.= '<td class = "text-center">'.$i.'.</td>';
			//sede
			$sede = utf8_decode($row["sed_nombre"]);
			$salida.= '<td class = "text-left">'.$sede.'</td>';
			//departamento
			$departamento = utf8_decode($row["dep_nombre"]);
			$salida.= '<td class = "text-left">'.$departamento.'</td>';
			//categoria
			$categoria = utf8_decode($row["cat_nombre"]);
			$salida.= '<td class = "text-left">'.$categoria.'</td>';
			//nombre
			$nom = utf8_decode($row["audit_nombre"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//programacion
			$fecha = cambia_fecha($row["pro_fecha"]);
			$hora = substr($row["pro_hora"],0,5);
			$salida.= '<td class = "text-center">'.$fecha.' '.$hora.'</td>';
			//codigo
			$codigo = $row["audit_codigo"];
			$progra = $row["pro_codigo"];
			$ejecucion = $row["ejecucion_activa"];
			$usu = $_SESSION["codigo"];
			$hashkey1 = $ClsAud->encrypt($codigo, $usu);
			$hashkey2 = $ClsAud->encrypt($progra, $usu);
			$salida.= '<td class = "text-center" >';
			if($ejecucion != ""){
				$hashkey3 = $ClsAud->encrypt($ejecucion, $usu);
				$salida.= '<a class="btn btn-success" href = "FRMcuestionario.php?hashkey1='.$hashkey1.'&hashkey2='.$hashkey2.'&hashkey3='.$hashkey3.'" title = "Seleccionar Cuestionario" > En proceso <i class="fa fa-angle-double-right"></i></a> &nbsp; ';				
			}else{
				$salida.= '<a class="btn btn-info" href = "FRMcuestionario.php?hashkey1='.$hashkey1.'&hashkey2='.$hashkey2.'" title = "Seleccionar Cuestioario" > Seleccionar <i class="fa fa-chevron-right"></i></a> &nbsp; ';
			}
			$salida.= '</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}


function tabla_ejecucion($codigo,$auditoria,$usuario,$sede,$departamento,$categoria,$fini,$ffin,$situacion){
	$ClsEje = new ClsEjecucion();
	$result = $ClsEje->get_ejecucion($codigo,$auditoria,$usuario,$sede,$departamento,$categoria,$fini,$ffin,$situacion);
	if(is_array($result)){
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "10px">No.</th>';
		$salida.= '<th class = "text-center" width = "150px">Sede</th>';
		$salida.= '<th class = "text-center" width = "150px">Departamento</th>';
		$salida.= '<th class = "text-center" width = "150px">Categor&iacute;a</th>';
		$salida.= '<th class = "text-center" width = "100px">Cuestionario</th>';
		$salida.= '<th class = "text-center" width = "100px">Fecha/Hora de Finalizaci&oacute;n</th>';
		$salida.= '<th class = "text-center" width = "100px">Status</th>';
		$salida.= '<th class = "text-center" width = "100px">Nota</th>';
		$salida.= '<th class = "text-center" width = "10px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "10px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "10px"><i class="fa fa-cogs"></i></th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i = 1;
		foreach($result as $row){
			$salida.= '<tr>';
			//No.
			$salida.= '<td class = "text-center">'.$i.'.</td>';
			//sede
			$sede = utf8_decode($row["sed_nombre"]);
			$salida.= '<td class = "text-left">'.$sede.'</td>';
			//departamento
			$departamento = utf8_decode($row["dep_nombre"]);
			$salida.= '<td class = "text-left">'.$departamento.'</td>';
			//categoria
			$categoria = utf8_decode($row["cat_nombre"]);
			$salida.= '<td class = "text-left">'.$categoria.'</td>';
			//nombre
			$nom = utf8_decode($row["audit_nombre"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//fecha/hora
			$fechor = trim($row["eje_fecha_final"]);
			$fechor = cambia_fechaHora($fechor);
			$salida.= '<td class = "text-left">'.$fechor.'</td>';
			//stauts
			$situacion = trim($row["eje_situacion"]);
			switch($situacion){
				case 1: $status = '<strong class="text-gray">En procesos</strong>'; break;
				case 2: $status = '<strong class="text-muted">Finalizada</strong>'; break;
				case 3: $status = '<strong class="text-info">Solicitando Aprobaci&oacute;n</strong>'; break;
				case 4: $status = '<strong class="text-success">Aprobado</strong>'; break;
				case 5: $status = '<strong class="text-danger">Corregir</strong>'; break;
				case 0: $status = '<strong class="text-warning">Anualado</strong>'; break;
			}
			$salida.= '<td class = "text-center">'.$status.'</td>';
			//nombre
			$nota = trim($row["eje_nota"]);
			$salida.= '<td class = "text-center">'.$nota.'</td>';
			//codigo
			$codigo = $row["eje_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsEje->encrypt($codigo, $usu);
			$situacion = trim($row["eje_situacion"]);
			$salida.= '<td class = "text-center" >';
			$salida.= '<a class="btn btn-info" href = "FRMrevision.php?hashkey='.$hashkey.'" title = "Seleccionar Auditor&iacute;a" ><i class="fa fa-search"></i></a> ';
			$salida.= '</td>';
			if($situacion == 1){ //En edicion
				$salida.= '<td class = "text-center" >';
				$salida.= '<a class="btn btn-white" href = "CPREPORTES/REPrevision.php?ejecucion='.$codigo.'" target="_blank" title = "Imprimir Reporte" ><i class="fas fa-print"></i></a> ';
				$salida.= '</td>';
				$salida.= '<td class = "text-center" >';
				$salida.= '<a class="btn btn-white" href = "FRMcuestionario.php?hashkey3='.$hashkey.'" title = "Seleccionar Auditor&iacute;a" ><i class="fa fa-pencil-alt"></i></a> ';
				$salida.= '</td>';
			}else if($situacion == 2){ //Solicitando Aprobacion
				$salida.= '<td class = "text-center" >';
				$salida.= '<a class="btn btn-white" href = "CPREPORTES/REPrevision.php?ejecucion='.$codigo.'" target="_blank" title = "Imprimir Reporte" ><i class="fas fa-print"></i></a> ';
				$salida.= '</td>';
				$salida.= '<td class = "text-center" >';
				$salida.= '<button class="btn btn-info btn-outline" onclick="solicitarAprobacion('.$codigo.');" title = "Solicitar Aprobaci&oacute;n" ><i class="fa fa-exclamation-circle"></i></a> ';
				$salida.= '</td>';
			}else if($situacion == 3){ //Solicitando Aprobacion
				$salida.= '<td class = "text-center" >';
				$salida.= '<a class="btn btn-white" href = "CPREPORTES/REPrevision.php?ejecucion='.$codigo.'" target="_blank" title = "Imprimir Reporte" ><i class="fas fa-print"></i></a> ';
				$salida.= '</td>';
				$salida.= '<td class = "text-center" >';
				$salida.= '<button class="btn btn-info btn-outline" title = "Solicitando Aprobaci&oacute;n" disabled ><i class="fa fa-minus text-gray"></i></a> ';
				$salida.= '</td>';
			}else if($situacion == 4){ //Aprobada
				$salida.= '<td class = "text-center" >';
				$salida.= '<a class="btn btn-white" href = "CPREPORTES/REPrevision.php?ejecucion='.$codigo.'" target="_blank" title = "Imprimir Reporte" ><i class="fas fa-print"></i></a> ';
				$salida.= '</td>';
				$salida.= '<td class = "text-center" >';
				$salida.= '<a class="btn btn-success btn-outline" href = "FRMveraprobacion.php?hashkey='.$hashkey.'" title = "Ver revisi&oacute;n" ><i class="fas fa-search"></i></a> ';
				$salida.= '</td>';
			}else if($situacion == 5){ //Rechazada
				$salida.= '<td class = "text-center" >';
				$salida.= '<a class="btn btn-danger btn-outline" href = "FRMcuestionario.php?hashkey3='.$hashkey.'" title = "Seleccionar Auditor&iacute;a para modificar" ><i class="fa fa-pencil-alt"></i></a> ';
				$salida.= '</td>';
				$salida.= '<td class = "text-center" >';
				$salida.= '<a class="btn btn-danger btn-outline" href = "FRMveraprobacion.php?hashkey='.$hashkey.'" title = "Ver revisi&oacute;n" ><i class="fas fa-search"></i></a> ';
				$salida.= '</td>';
			}else{
				$salida.= '<td class = "text-center" >';
				$salida.= '<button class="btn btn-white" title = "..." disabled ><i class="fa fa-times"></i></a> ';
				$salida.= '</td>';
			}
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}


function tabla_aprobacion($codigo,$auditoria,$usuario,$sede,$departamento,$categoria,$fini,$ffin){
	$ClsEje = new ClsEjecucion();
	$result = $ClsEje->get_ejecucion($codigo,$auditoria,$usuario,$sede,$departamento,$categoria,$fini,$ffin,"3");
	if(is_array($result)){
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "10px">No.</th>';
		$salida.= '<th class = "text-center" width = "150px">Sede</th>';
		$salida.= '<th class = "text-center" width = "150px">Departamento</th>';
		$salida.= '<th class = "text-center" width = "150px">Categor&iacute;a</th>';
		$salida.= '<th class = "text-center" width = "100px">Cuestionario</th>';
		$salida.= '<th class = "text-center" width = "10px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "10px"><i class="fa fa-cogs"></i></th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i = 1;
		foreach($result as $row){
			$salida.= '<tr>';
			//No.
			$salida.= '<td class = "text-center">'.$i.'.</td>';
			//sede
			$sede = utf8_decode($row["sed_nombre"]);
			$salida.= '<td class = "text-left">'.$sede.'</td>';
			//departamento
			$departamento = utf8_decode($row["dep_nombre"]);
			$salida.= '<td class = "text-left">'.$departamento.'</td>';
			//categoria
			$categoria = utf8_decode($row["cat_nombre"]);
			$salida.= '<td class = "text-left">'.$categoria.'</td>';
			//nombre
			$nom = utf8_decode($row["audit_nombre"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//codigo
			$codigo = $row["eje_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsEje->encrypt($codigo, $usu);
			$salida.= '<td class = "text-center" >';
			$salida.= '<a class="btn btn-white btn-lg" href = "FRMveraprobacion.php?hashkey='.$hashkey.'" title = "Ver Revisi&oacute; de Auditor&iacute;a y Comentarios" ><i class="fa fa-search"></i></a> ';
			$salida.= '</td>';
			$salida.= '<td class = "text-center" >';
			$salida.= '<a class="btn btn-success btn-lg" href = "FRMaprobar.php?hashkey='.$hashkey.'" title = "Aprobar Auditor&iacute;a" ><i class="fas fa-clipboard-check"></i></a> ';
			$salida.= '</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}


function tabla_disolucion($codigo,$auditoria,$usuario,$sede,$departamento,$categoria,$fini,$ffin){
	$ClsEje = new ClsEjecucion();
	$result = $ClsEje->get_ejecucion($codigo,$auditoria,$usuario,$sede,$departamento,$categoria,$fini,$ffin,"4");
	if(is_array($result)){
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "10px">No.</th>';
		$salida.= '<th class = "text-center" width = "150px">Sede</th>';
		$salida.= '<th class = "text-center" width = "150px">Departamento</th>';
		$salida.= '<th class = "text-center" width = "150px">Categor&iacute;a</th>';
		$salida.= '<th class = "text-center" width = "100px">Cuestionario</th>';
		$salida.= '<th class = "text-center" width = "10px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "10px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "10px"><i class="fa fa-cogs"></i></th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i = 1;
		foreach($result as $row){
			$salida.= '<tr>';
			//No.
			$salida.= '<td class = "text-center">'.$i.'.</td>';
			//sede
			$sede = utf8_decode($row["sed_nombre"]);
			$salida.= '<td class = "text-left">'.$sede.'</td>';
			//departamento
			$departamento = utf8_decode($row["dep_nombre"]);
			$salida.= '<td class = "text-left">'.$departamento.'</td>';
			//categoria
			$categoria = utf8_decode($row["cat_nombre"]);
			$salida.= '<td class = "text-left">'.$categoria.'</td>';
			//nombre
			$nom = utf8_decode($row["audit_nombre"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//codigo
			$codigo = $row["eje_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsEje->encrypt($codigo, $usu);
			$salida.= '<td class = "text-center" >';
			$salida.= '<a class="btn btn-info" href = "FRMrevision.php?hashkey='.$hashkey.'" title = "Seleccionar Auditor&iacute;a" ><i class="fa fa-search"></i></a> ';
			$salida.= '</td>';
			$salida.= '<td class = "text-center" >';
			$salida.= '<a class="btn btn-white" href = "CPREPORTES/REPrevision.php?ejecucion='.$codigo.'" target="_blank" title = "Imprimir Reporte" ><i class="fas fa-print"></i></a> ';
			$salida.= '</td>';
			$salida.= '<td class = "text-center" >';
			$salida.= '<a class="btn btn-primary" href = "FRMdisolver.php?hashkey='.$hashkey.'" title = "Disolver hallazgos de Auditor&iacute;a" ><i class="fas fa-edit"></i></a> ';
			$salida.= '</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}


function tabla_reportes($sede,$departamento,$categoria,$fini,$ffin,$columnas){
	$ClsEje = new ClsEjecucion();
	$result = $ClsEje->get_ejecucion('','','',$sede,$departamento,$categoria,$fini,$ffin,'');
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
			$salida.= '<th class = "text-center" width = "150px">Departamento</th>';
			$salida.= '<th class = "text-center" width = "150px">Categor&iacute;a</th>';
			$salida.= '<th class = "text-center" width = "100px">Usuario</th>';
			$salida.= '<th class = "text-center" width = "100px">Lista</th>';
			$salida.= '<th class = "text-center" width = "100px">Inici&oacute;</th>';
			$salida.= '<th class = "text-center" width = "100px">Finaliz&oacute;</th>';
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
					if($col == "eje_codigo"){
						$campo = '# '.Agrega_Ceros($row[$campo]);
					}else if($col == "eje_fecha_inicio"){
						$campo = cambia_fechaHora($row[$campo]);
					}else if($col == "eje_fecha_final"){
						$campo = cambia_fechaHora($row[$campo]);
					}else if($col == "eje_situacion"){
						$campo = trim($row[$campo]);
						$campo = ($campo == 1)?'<strong class="text-success">En Proceso</strong>':'<strong class="text-muted">Finalizado</strong>';
					}else if($col == "audit_ponderacion"){
						$tipo = trim($row["audit_ponderacion"]);
						switch($tipo){
							case 1: $campo = "1-10"; break;
							case 2: $campo = "SI, NO, N/A"; break;
							case 3: $campo = "SAT, NO SAT"; break;
						}
					}else{
						$campo = utf8_decode($row[$campo]);
					}
					//columna
					$salida.= '<td class = "'.$alineacion.'">'.$campo.'</td>';
				}
			}else{
				//sede
				$sede = utf8_decode($row["sed_nombre"]);
				$salida.= '<td class = "text-left">'.$sede.'</td>';
				//departamento
				$departamento = utf8_decode($row["dep_nombre"]);
				$salida.= '<td class = "text-left">'.$departamento.'</td>';
				//categoria
				$categoria = utf8_decode($row["cat_nombre"]);
				$salida.= '<td class = "text-left">'.$categoria.'</td>';
				//Usuario
				$usuario = utf8_decode($row["usuario_nombre"]);
				$salida.= '<td class = "text-left">'.$usuario.'</td>';
				//nombre
				$nom = utf8_decode($row["audit_nombre"]);
				$salida.= '<td class = "text-left">'.$nom.'</td>';
				//fecha/hora
				$fechor = trim($row["eje_fecha_inicio"]);
				$fechor = cambia_fechaHora($fechor);
				$salida.= '<td class = "text-left">'.$fechor.'</td>';
				//fecha/hora
				$fechor = trim($row["eje_fecha_final"]);
				$fechor = cambia_fechaHora($fechor);
				$salida.= '<td class = "text-left">'.$fechor.'</td>';
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
		case "eje_codigo":
		   $respuesta["ancho"] = "40";
		   $respuesta["alineacion"] = "text-center";
		   $respuesta["titulo"] = "C&oacute;digo Ejecuci&oacute;n";
		   $respuesta["campo"] = "eje_codigo";
		   break;
		case "eje_fecha_inicio":
		   $respuesta["ancho"] = "100";
		   $respuesta["alineacion"] = "text-center";
		   $respuesta["titulo"] = "Fecha y hora de Inicio";
		   $respuesta["campo"] = "eje_fecha_inicio";
		   break;
		case "eje_fecha_final":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha y hora de Finalizaci&oacute;n";
			$respuesta["campo"] = "eje_fecha_final";
			break;
		case "eje_observaciones":
			$respuesta["ancho"] = "200";
			$respuesta["alineacion"] = "text-justify";
			$respuesta["titulo"] = "Observaciones";
			$respuesta["campo"] = "eje_observaciones";
			break;
		case "eje_responsable":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Responsable";
			$respuesta["campo"] = "eje_responsable";
			break;
		case "eje_correos":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Correos";
			$respuesta["campo"] = "eje_correos";
			break;
		case "eje_situacion":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Situaci&oacute;n";
			$respuesta["campo"] = "eje_situacion";
			break;
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
		case "audit_fotos":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "&iquest;Requiere Foto?";
			$respuesta["campo"] = "audit_fotos";
			break;
		case "audit_firma":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "&iquest;Requiere Firma?";
			$respuesta["campo"] = "audit_firma";
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
			$respuesta["titulo"] = "C&oacute;digo Departamento";
			$respuesta["campo"] = "are_codigo";
			break;
		case "are_nivel":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Nivel";
			$respuesta["campo"] = "are_nivel";
			break;
		case "dep_nombre":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Departamento";
			$respuesta["campo"] = "dep_nombre";
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
		case "eje_codigo":
		   $respuesta["ancho"] = "35";
		   $respuesta["alineacion"] = "C";
		   $respuesta["titulo"] = "Cod. Ejecución";
		   $respuesta["campo"] = "eje_codigo";
		   break;
		case "eje_fecha_inicio":
		   $respuesta["ancho"] = "40";
		   $respuesta["alineacion"] = "C";
		   $respuesta["titulo"] = "Fec/hora Inicio";
		   $respuesta["campo"] = "eje_fecha_inicio";
		   break;
		case "eje_fecha_final":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fec/hora Finalización";
			$respuesta["campo"] = "eje_fecha_final";
			break;
		case "eje_observaciones":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "J";
			$respuesta["titulo"] = "Observaciones";
			$respuesta["campo"] = "eje_observaciones";
			break;
		case "eje_responsable":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Responsable";
			$respuesta["campo"] = "eje_responsable";
			break;
		case "eje_correos":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Correos";
			$respuesta["campo"] = "eje_correos";
			break;
		case "eje_situacion":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Situación";
			$respuesta["campo"] = "eje_situacion";
			break;
		case "audit_codigo":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Cuestionario";
			$respuesta["campo"] = "audit_codigo";
			break;
		case "audit_nombre":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Nombre del Cuestionario";
			$respuesta["campo"] = "audit_nombre";
			break;
		case "audit_fotos":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "¿Requiere Foto?";
			$respuesta["campo"] = "audit_fotos";
			break;
		case "audit_firma":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "¿Requiere Firma?";
			$respuesta["campo"] = "audit_firma";
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
		case "dep_nombre":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Área";
			$respuesta["campo"] = "dep_nombre";
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
		case "usuario_nombre":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Usuario (Registró)";
			$respuesta["campo"] = "usuario_nombre";
			break;
	}	
	return $respuesta;
}


function parametrosDinamicosEXCEL($columna){
	switch($columna){
		case "eje_codigo":
		   $respuesta["ancho"] = "18";
		   $respuesta["alineacion"] = "C";
		   $respuesta["titulo"] = "Cod. Ejecución";
		   $respuesta["campo"] = "eje_codigo";
		   break;
		case "eje_fecha_inicio":
		   $respuesta["ancho"] = "30";
		   $respuesta["alineacion"] = "C";
		   $respuesta["titulo"] = "Fecha/hora Inicio";
		   $respuesta["campo"] = "eje_fecha_inicio";
		   break;
		case "eje_fecha_final":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha/hora Finaliza";
			$respuesta["campo"] = "eje_fecha_final";
			break;
		case "eje_observaciones":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "J";
			$respuesta["titulo"] = "Observaciones";
			$respuesta["campo"] = "eje_observaciones";
			break;
		case "eje_responsable":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Responsable";
			$respuesta["campo"] = "eje_responsable";
			break;
		case "eje_correos":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Correos";
			$respuesta["campo"] = "eje_correos";
			break;
		case "eje_situacion":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Situación";
			$respuesta["campo"] = "eje_situacion";
			break;
		case "audit_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Cuestionario";
			$respuesta["campo"] = "audit_codigo";
			break;
		case "audit_nombre":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Nombre del Cuestionario";
			$respuesta["campo"] = "audit_nombre";
			break;
		case "audit_fotos":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Requiere Foto?";
			$respuesta["campo"] = "audit_fotos";
			break;
		case "audit_firma":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Requiere Firma?";
			$respuesta["campo"] = "audit_firma";
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
			$respuesta["titulo"] = "Cod. Área";
			$respuesta["campo"] = "are_codigo";
			break;
		case "are_nivel":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Nivel";
			$respuesta["campo"] = "are_nivel";
			break;
		case "dep_nombre":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Área";
			$respuesta["campo"] = "dep_nombre";
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
			$respuesta["titulo"] = "Dirección (Sede)";
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
			$respuesta["titulo"] = "Usuario (Registró)";
			$respuesta["campo"] = "usuario_nombre";
			break;
	}	
	return $respuesta;
}


function mail_usuario($ejecucion){$ClsEje = new ClsEjecucion();
	$result = $ClsEje->get_ejecucion($ejecucion);
	if(is_array($result)){
		foreach($result as $row){
			//codigo
			$codigo = Agrega_Ceros($row["eje_codigo"]);
			$codigo_audit = trim($row["audit_codigo"]);
			//ubicacion
			$sede = depurador_texto(utf8_decode($row["sed_nombre"]));
			$sector = depurador_texto(utf8_decode($row["sec_nombre"]));
			$area = depurador_texto(utf8_decode($row["are_nombre"]));
			$correos = trim(strtolower($row["eje_correos"]));
		}
	}
	if($correos != ""){
		$arrmail = explode(",", $correos);
		$lencorreos = count($arrmail);
	}//asignados
	if(is_array($arrmail)){
		for($i = 0; $i< $lencorreos; $i++){
			$arrcorreos["email"] = trim($arrmail[$i]);
			$arrcorreos["name"] = "";
			$arrcorreos["type"] = "to";
			$to[$i] = $arrcorreos;
		}
		$i++;
		$arrcorreos["email"] = "soporte@farasi.com.gt";
		$arrcorreos["name"] = "";
		$arrcorreos["type"] = "to";
		$to[$i] = $arrcorreos;
	}//////////////////////// CREDENCIALES DE CLIENTE
	$ClsConf = new ClsConfig();
	$result = $ClsConf->get_credenciales();
	if(is_array($result)){
		foreach($result as $row){
			$cliente_nombre = utf8_decode($row['cliente_nombre']);
			$cliente_nombre_reporte = utf8_decode($row['cliente_nombre_reporte']);
		}
	}
	$cliente_nombre = depurador_texto($cliente_nombre);
	$cliente_nombre_reporte = depurador_texto($cliente_nombre_reporte);
	$url = url_origin( $_SERVER );
		
	$mailadmin = "soporte@farasi.com.gt";
    // Instancia el API KEY de Mandrill
	$mandrill = new Mandrill('aLGRM5YodGYp_GDBwwDilw');
	/////////////_________ Correo a admin
	$subject = $cliente_nombre_reporte;
	$texto = "Estimado Usuario,<br><br>se gener&oacute; un reporte de auditor&iacute;a con el n&uacute;mero # $codigo en la $sede.<br> <br><br>";
	$texto.= "Puede acceder al reporte desde aqu&iacute;:<br><br>";
	$texto.= '<a href="'.$url.'/ROOT/CPAUDEJECUCION/CPREPORTES/REPrevision.php?ejecucion='.$codigo.'" class="btn btn-correo btn-round btn-block">  Ver Reporte de Auditor&iacute;a </a>';
	$texto.= "<br><br>Gracias y saludos,<br><br>BPManagement";$html = mail_constructor($subject,$texto); try{
		$message = array(
			'subject' => $subject,
			'html' => $html,
			'from_email' => 'noreply@farasi.com.gt',
			'from_name' => 'BPManagement',
			'to' => $to
		);
		 
		//print_r($message);
		//echo "<br>";
		$result = $mandrill->messages->send($message);
		$validacion =  1;
	} catch(Mandrill_Error $e) { 
		//echo "<br>";
		//print_r($e);
		//devuelve un mensaje de manejo de errores
		$validacion =  0;
	}         
		
	return $validacion;
}
?>