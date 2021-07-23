<?php 
include_once('../html_fns.php');

function tabla_soluciones($ejecucion,$auditoria,$usuario,$sede,$departamento,$categoria,$fini,$ffin,$status,$situacion,$orderfecha){
	$ClsPla = new ClsPlan();
	$result = $ClsPla->get_plan_solucion($ejecucion,$auditoria,$usuario,$sede,$departamento,$categoria,$fini,$ffin,$status,$situacion,$orderfecha);
	if(is_array($result)){
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "10px">No.</th>';
		$salida.= '<th class = "text-center" width = "150px">Sede</th>';
		$salida.= '<th class = "text-center" width = "150px">Departamento</th>';
		$salida.= '<th class = "text-center" width = "150px">Categor&iacute;a</th>';
		$salida.= '<th class = "text-center" width = "100px">Cuestionario</th>';
		$salida.= '<th class = "text-center" width = "100px">Fecha Reg.</th>';
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
			$fechor = trim($row["pla_fecha_registro"]);
			$fechor = cambia_fechaHora($fechor);
			$salida.= '<td class = "text-left">'.$fechor.'</td>';
			//codigo
			$codigo = $row["pla_ejecucion"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsPla->encrypt($codigo, $usu);
			$salida.= '<td class = "text-center" >';
			$salida.= '<a class="btn btn-info btn-lg" href = "FRMplan.php?hashkey='.$hashkey.'" title = "Generar Informe Final de Auditor&iacute;a" ><i class="fas fa-clipboard-list"></i></a> ';
			$salida.= '</td>';
			$salida.= '<td class = "text-center" >';
			$salida.= '<a class="btn btn-white btn-lg" href = "FRMverplan.php?plan='.$codigo.'" title = "Ver Informe Final de Auditor&iacute;a" ><i class="fa fa-search"></i></a> ';
			$salida.= '</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}




function tabla_reportes($sede,$departamento,$categoria,$fini,$ffin,$status,$situacion,$columnas){
	$ClsPla = new ClsPlan();
	$result = $ClsPla->get_plan_solucion('','','',$sede,$departamento,$categoria,$fini,$ffin,$status,$situacion);
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
			$salida.= '<th class = "text-center" width = "100px">Lista</th>';
			$salida.= '<th class = "text-center" width = "150px">Soluci&oacute;n</th>';
			$salida.= '<th class = "text-center" width = "100px">Fecha</th>';
			$salida.= '<th class = "text-center" width = "120px">Responsable</th>';
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
					}else if($col == "sol_fecha"){
						$campo = cambia_fecha($row[$campo]);
					}else if($col == "sol_fecha_registro"){
						$campo = cambia_fechaHora($row[$campo]);
					}else if($col == "sol_fecha_solucion"){
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
					}else if($col == "pre_tipo"){
						$tipo = trim($row["pre_tipo"]);
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
				//nombre
				$nom = utf8_decode($row["audit_nombre"]);
				$salida.= '<td class = "text-left">'.$nom.'</td>';
				//solucion
				$solucion = utf8_decode($row["sol_solucion"]);
				$salida.= '<td class = "text-left">'.$solucion.'</td>';
				//fecha/hora
				$fecha = trim($row["sol_fecha"]);
				$fecha = cambia_fecha($fecha);
				$salida.= '<td class = "text-center">'.$fecha.'</td>';
				//responsable
				$responsable = utf8_decode($row["sol_responsable_nombre"]);
				$salida.= '<td class = "text-left">'.$responsable.'</td>';
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
		   $respuesta["titulo"] = "C&oacute;digo Auditor&iacute;a";
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
		case "eje_situacion":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Situaci&oacute;n";
			$respuesta["campo"] = "eje_situacion";
			break;
		case "audit_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Lista";
			$respuesta["campo"] = "audit_codigo";
			break;
		case "audit_nombre":
			$respuesta["ancho"] = "110";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Nombre del cuestionario";
			$respuesta["campo"] = "audit_nombre";
			break;
		case "pro_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Prog.";
			$respuesta["campo"] = "pro_codigo";
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
		//--
		case "pre_pregunta":
			$respuesta["ancho"] = "150";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Pregunta";
			$respuesta["campo"] = "pre_pregunta";
			break;
		case "pre_tipo":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Tipo de Pregunta";
			$respuesta["campo"] = "pre_tipo";
			break;
		case "pre_peso":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Peso";
			$respuesta["campo"] = "pre_peso";
			break;
		//--
		case "sol_solucion":
			$respuesta["ancho"] = "150";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Acci&oacute;n a Tomar";
			$respuesta["campo"] = "sol_solucion";
			break;
		case "sol_fecha":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha Programada";
			$respuesta["campo"] = "sol_fecha";
			break;
		case "sol_responsable_nombre":
			$respuesta["ancho"] = "120";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Responsable";
			$respuesta["campo"] = "sol_responsable_nombre";
			break;
		case "sol_fecha_registro":
			$respuesta["ancho"] = "110";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha Registro";
			$respuesta["campo"] = "sol_fecha_registro";
			break;
		case "sol_fecha_solucion":
			$respuesta["ancho"] = "110";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha Status";
			$respuesta["campo"] = "sol_fecha_solucion";
			break;
		case "sta_nombre":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Status";
			$respuesta["campo"] = "sta_nombre";
			break;
	}	
	return $respuesta;
}


function parametrosDinamicosPDF($columna){
	switch($columna){
		case "eje_codigo":
		   $respuesta["ancho"] = "25";
		   $respuesta["alineacion"] = "C";
		   $respuesta["titulo"] = "Código";
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
		case "eje_situacion":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Situación";
			$respuesta["campo"] = "eje_situacion";
			break;
		case "audit_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Lista";
			$respuesta["campo"] = "audit_codigo";
			break;
		case "audit_nombre":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Nombre del cuestionario";
			$respuesta["campo"] = "audit_nombre";
			break;
		case "pro_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Progra.";
			$respuesta["campo"] = "pro_codigo";
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
		case "dep_nombre":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "L";
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
		//--
		case "pre_pregunta":
			$respuesta["ancho"] = "75";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Pregunta";
			$respuesta["campo"] = "pre_pregunta";
			break;
		case "pre_tipo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Tipo Pregunta";
			$respuesta["campo"] = "pre_tipo";
			break;
		case "pre_peso":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Peso";
			$respuesta["campo"] = "pre_peso";
			break;
		//--
		case "sol_solucion":
			$respuesta["ancho"] = "75";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Accion a Tomar";
			$respuesta["campo"] = "sol_solucion";
			break;
		case "sol_fecha":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Prog.";
			$respuesta["campo"] = "sol_fecha";
			break;
		case "sol_responsable_nombre":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Responsable";
			$respuesta["campo"] = "sol_responsable_nombre";
			break;
		case "sol_fecha_registro":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Registro";
			$respuesta["campo"] = "sol_fecha_registro";
			break;
		case "sol_fecha_solucion":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Status";
			$respuesta["campo"] = "sol_fecha_solucion";
			break;
		case "sta_nombre":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Status";
			$respuesta["campo"] = "sta_nombre";
			break;
	}	
	return $respuesta;
}


function parametrosDinamicosEXCEL($columna){
	switch($columna){
		case "eje_codigo":
		   $respuesta["ancho"] = "18";
		   $respuesta["alineacion"] = "C";
		   $respuesta["titulo"] = "Cod. Revision";
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
		case "eje_situacion":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Situacion";
			$respuesta["campo"] = "eje_situacion";
			break;
		case "audit_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Lista";
			$respuesta["campo"] = "audit_codigo";
			break;
		case "audit_nombre":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Nombre del cuestionario";
			$respuesta["campo"] = "audit_nombre";
			break;
		case "pro_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Programado";
			$respuesta["campo"] = "pro_codigo";
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
		case "dep_nombre":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Area";
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
		//--
		case "pre_pregunta":
			$respuesta["ancho"] = "45";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Pregunta";
			$respuesta["campo"] = "pre_pregunta";
			break;
		case "pre_tipo":
			$respuesta["ancho"] = "20";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Tipo Pregunta";
			$respuesta["campo"] = "pre_tipo";
			break;
		case "pre_peso":
			$respuesta["ancho"] = "10";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Peso";
			$respuesta["campo"] = "pre_peso";
			break;
		//--
		case "sol_solucion":
			$respuesta["ancho"] = "45";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Accion a Tomar";
			$respuesta["campo"] = "sol_solucion";
			break;
		case "sol_fecha":
			$respuesta["ancho"] = "20";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Programada";
			$respuesta["campo"] = "sol_fecha";
			break;
		case "sol_responsable_nombre":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Responsable";
			$respuesta["campo"] = "sol_responsable_nombre";
			break;
		case "sol_fecha_registro":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Registro";
			$respuesta["campo"] = "sol_fecha_registro";
			break;
		case "sol_fecha_solucion":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Status";
			$respuesta["campo"] = "sol_fecha_solucion";
			break;
		case "sta_nombre":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Status";
			$respuesta["campo"] = "sta_nombre";
			break;
	}	
	return $respuesta;
}
?>