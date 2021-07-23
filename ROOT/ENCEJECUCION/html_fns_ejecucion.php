<?php 
include_once('../html_fns.php');
require_once("../recursos/mandrill/src/Mandrill.php"); //--correos
require_once("../../CONFIG/constructor.php");
include_once('GETIP.php');


function tabla_ejecucion($codigo,$encuesta,$categoria,$fini,$ffin,$situacion){
	$ClsRes = new ClsEncuestaResolucion();
	$result = $ClsRes->get_ejecucion($codigo,$encuesta,$categoria,$fini,$ffin,$situacion);
	if(is_array($result)){
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "10px">No.</th>';
		$salida.= '<th class = "text-center" width = "150px">Categor&iacute;a</th>';
		$salida.= '<th class = "text-center" width = "100px">Cuestionario</th>';
		$salida.= '<th class = "text-center" width = "100px">Cliente</th>';
		$salida.= '<th class = "text-center" width = "100px">Fecha/Hora</th>';
		$salida.= '<th class = "text-center" width = "100px">Status</th>';
		$salida.= '<th class = "text-center" width = "10px"><i class="fa fa-cogs"></i></th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i = 1;
		foreach($result as $row){
			$salida.= '<tr>';
			//No.
			$salida.= '<td class = "text-center">'.$i.'.</td>';
			//categoria
			$categoria = utf8_decode($row["cat_nombre"]);
			$salida.= '<td class = "text-left">'.$categoria.'</td>';
			//nombre
			$titulo = utf8_decode($row["cue_titulo"]);
			$salida.= '<td class = "text-left">'.$titulo.'</td>';
			//cliente
			$cliente = utf8_decode($row["inv_cliente"]);
			$salida.= '<td class = "text-left">'.$cliente.'</td>';
			//fecha/hora
			$fechor = trim($row["eje_fecha_inicio"]);
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
			//codigo
			$codigo = $row["eje_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsRes->encrypt($codigo, $usu);
			$situacion = trim($row["eje_situacion"]);
			$salida.= '<td class = "text-center" >';
			$salida.= '<a class="btn btn-info" href = "FRMrevision.php?hashkey='.$hashkey.'" title = "Seleccionar Cuestionario" ><i class="fa fa-search"></i></a> ';
			$salida.= '</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}


function tabla_cuestionarios(){
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
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsEnc->encrypt($codigo, $usu);
			$salida.= '<td class = "text-center" >';
				$salida.= '<a type="button" class="btn btn-primary btn-xs" href = "FRMestadisticas.php?hashkey='.$hashkey.'" title = "Ver estad&iacute;sticas" ><i class="fas fa-chart-bar"></i></a>';
				$salida.= '<a type="button" class="btn btn-info btn-xs" href = "FRMestadisticasPorPregunta.php?hashkey='.$hashkey.'" title = "Promedios por pregunta" ><i class="fa fa-check-square-o"></i></a>';

			$salida.= '</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}
	return $salida;
}



function tabla_reportes($encuesta,$categoria,$fini,$ffin,$columnas){
	$ClsRes = new ClsEncuestaResolucion();
	$result = $ClsRes->get_ejecucion($codigo,$encuesta,$categoria,$fini,$ffin,$situacion);
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
			$salida.= '<th class = "text-center" width = "100px">Usuario</th>';
			$salida.= '<th class = "text-center" width = "100px">Cuestionario</th>';
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
					}else if($col == "inv_fecha_registro"){
						$campo = cambia_fechaHora($row[$campo]);
					}else if($col == "eje_situacion"){
						$campo = trim($row[$campo]);
						$campo = ($campo == 1)?'<strong class="text-success">En Proceso</strong>':'<strong class="text-muted">Finalizado</strong>';
					}else if($col == "cue_ponderacion"){
						$tipo = trim($row["cue_ponderacion"]);
						switch($tipo){
							case 1: $campo = "1-10"; break;
							case 2: $campo = "SI o NO"; break;
						}
					}else{
						$campo = utf8_decode($row[$campo]);
					}
					$j++;
					//columna
					$salida.= '<td class = "'.$alineacion.'">'.$campo.'</td>';
				}
			}else{
				//categoria
				$categoria = utf8_decode($row["cat_nombre"]);
				$salida.= '<td class = "text-left">'.$categoria.'</td>';
				//Usuario
				$usuario = utf8_decode($row["usuario_nombre"]);
				$salida.= '<td class = "text-left">'.$usuario.'</td>';
				//nombre
				$cuestionario = utf8_decode($row["cue_titulo"]);
				$salida.= '<td class = "text-left">'.$cuestionario.'</td>';
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
		   $respuesta["titulo"] = "C&oacute;digo Cuestionario";
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
		case "eje_respondio":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Responde Encuesta";
			$respuesta["campo"] = "eje_respondio";
			break;
		case "eje_correo":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Correo Responde";
			$respuesta["campo"] = "eje_correo";
			break;
		case "eje_telefono":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Tel. Responde";
			$respuesta["campo"] = "eje_telefono";
			break;
		case "eje_situacion":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Situaci&oacute;n";
			$respuesta["campo"] = "eje_situacion";
			break;
		case "cue_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Lista";
			$respuesta["campo"] = "cue_codigo";
			break;
		case "cue_titulo":
			$respuesta["ancho"] = "110";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Nombre de la Lista";
			$respuesta["campo"] = "cue_titulo";
			break;
		case "inv_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Prog.";
			$respuesta["campo"] = "inv_codigo";
			break;
		case "inv_fecha_registro":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha de Invitaci&oacute;n";
			$respuesta["campo"] = "inv_fecha_registro";
			break;
		case "inv_observaciones":
			$respuesta["ancho"] = "150";
			$respuesta["alineacion"] = "text-justify";
			$respuesta["titulo"] = "Observaciones (Lista)";
			$respuesta["campo"] = "inv_observaciones";
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
		case "eje_respondio":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Responde Encuesta";
			$respuesta["campo"] = "eje_respondio";
			break;
		case "eje_correo":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Correo Responde";
			$respuesta["campo"] = "eje_correo";
			break;
		case "eje_telefono":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Tel. Responde";
			$respuesta["campo"] = "eje_telefono";
			break;
		case "eje_situacion":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Situación";
			$respuesta["campo"] = "eje_situacion";
			break;
		case "cue_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Lista";
			$respuesta["campo"] = "cue_codigo";
			break;
		case "cue_titulo":
			$respuesta["ancho"] = "70";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Nombre de la Lista";
			$respuesta["campo"] = "cue_titulo";
			break;
		case "inv_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Progra.";
			$respuesta["campo"] = "inv_codigo";
			break;
		case "inv_fecha_registro":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha de Invitación";
			$respuesta["campo"] = "inv_fecha_registro";
			break;
		case "inv_observaciones":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "J";
			$respuesta["titulo"] = "Observaciones (Lista)";
			$respuesta["campo"] = "inv_observaciones";
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
		case "eje_respondio":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Responde Encuesta";
			$respuesta["campo"] = "eje_respondio";
			break;
		case "eje_correo":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Correo Responde";
			$respuesta["campo"] = "eje_correo";
			break;
		case "eje_telefono":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Tel. Responde";
			$respuesta["campo"] = "eje_telefono";
			break;
		case "eje_situacion":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Situacion";
			$respuesta["campo"] = "eje_situacion";
			break;
		case "cue_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Lista";
			$respuesta["campo"] = "cue_codigo";
			break;
		case "cue_titulo":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Nombre de la Lista";
			$respuesta["campo"] = "cue_titulo";
			break;
		case "inv_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Programado";
			$respuesta["campo"] = "inv_codigo";
			break;
		case "inv_fecha_registro":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha de Invitacion";
			$respuesta["campo"] = "inv_fecha_registro";
			break;
		case "inv_observaciones":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "J";
			$respuesta["titulo"] = "Observaciones (Lista)";
			$respuesta["campo"] = "inv_observaciones";
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
		case "usuario_nombre":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Usuario (Registro)";
			$respuesta["campo"] = "usuario_nombre";
			break;
	}	
	return $respuesta;
}


function mail_usuario($ejecucion){$ClsRes = new ClsEncuestaResolucion();
	$result = $ClsRes->get_ejecucion($ejecucion);
	if(is_array($result)){
		foreach($result as $row){
			//codigo
			$codigo = Agrega_Ceros($row["eje_codigo"]);
			$codigo_cuestionario = trim($row["cue_codigo"]);
			//ubicacion
			$correo = depurador_texto(utf8_decode($row["sed_nombre"]));
			$sector = depurador_texto(utf8_decode($row["sec_nombre"]));
			$area = depurador_texto(utf8_decode($row["are_nombre"]));
			$correos = trim(strtolower($row["eje_correo"]));
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
	$texto = "Estimado Usuario,<br><br>se gener&oacute; un reporte de auditor&iacute;a con el n&uacute;mero # $codigo en la $correo.<br> <br><br>";
	$texto.= "Puede acceder al reporte desde aqu&iacute;:<br><br>";
	$texto.= '<a href="'.$url.'/ROOT/CPAUDEJECUCION/CPREPORTES/REPrevision.php?ejecucion='.$codigo.'" class="btn btn-correo btn-round btn-block">  Ver Reporte de Cuestionario </a>';
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




function tabla_resultados($periodo,$categoria,$encuesta,$situacion,$fini,$ffin){
	$ClsRes = new ClsEncuestaResolucion();$salida = '<table class="table table-striped" width="100%" >';
	$salida.= '<thead>';
	$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "30px">No.</th>';
		$salida.= '<th class = "text-center" width = "150px"></th>';
		$salida.= '<th class = "text-center" width = "150px">Cantidad de Respuestas</th>';
		$salida.= '<th class = "text-center" width = "100px">Promedio 1 a 10</th>';
		$salida.= '<th class = "text-center" width = "100px">Conteo de Si / No</th>';
	$salida.= '</tr>';
	$salida.= '</thead>';
	$salida.= '<tbody>';
	$num = 1;
	$dia_inicio = "";
	$CANTIDAD = 0;
	$CANT = 0;
	$SUMA = 0;
	$PROMEDIO = 0;
	$SITOTAL = 0;
	$NOTOTAL = 0;
	//--
	if($periodo == "D"){
		$fechaInicio = strtotime(regresa_fecha($fini));
		$fechaFin = strtotime(regresa_fecha($ffin));
		for($i = $fechaInicio; $i <= $fechaFin; $i+=86400){
			$fecha = date("d/m/Y", $i);
			$dia = date("w", $i);
			$dia = ($dia == 0)?7:$dia;
			$dia_nombre = Dias_Letra($dia);
			$result = $ClsRes->get_ejecucion_respuestas('',$encuesta,'',$categoria,$fecha,$fecha,$situacion);
			$cantidad = 0;
			$cant = 0;
			$suma = 0;
			$promedio = 0;
			$siTotal = 0;
			$noTotal = 0;
			$sinoConteo = '';
			if(is_array($result)){
				foreach($result as $row){
					$tipo = trim($row["pre_tipo"]);
					if($tipo == 1){
						$nota = trim($row["resp_respuesta"]);
						$suma+= $nota;
						$cant++;
					}else if($tipo == 2){
						$respuesta = trim($row["resp_respuesta"]);
						if($respuesta == 1){
							$siTotal++;
						}else{
							$noTotal++;
						}
					}
					$cantidad++;
				}
				$promedio = $suma/$cant;
				$promedio = number_format($promedio,2,'.','');
			}
			$promedio = ($promedio == 0 && $cant == 0)?"":$promedio;
			$sinoConteo = "SI ($siTotal), NO ($noTotal)";
			$CANTIDAD+=$cantidad;
			$CANT+= $cant;
			$SUMA+= $suma;
			$SITOTAL+= $siTotal;
			$NOTOTAL+= $noTotal;
			$salida.= '<tr>';
			//--
			$salida.= '<td class = "text-center">'.$num.'.- </td>';
			$salida.= '<td class = "text-left">'.$dia_nombre.' '.$fecha.'</td>';
			$salida.= '<td class = "text-center">'.$cantidad.'</td>';
			$salida.= '<td class = "text-center">'.$promedio.'</td>';
			$salida.= '<td class = "text-center">'.$sinoConteo.'</td>';
			//--
			$salida.= '</tr>';
			$num++;
		}
	}else if($periodo == "S"){
		$fini = regresa_fecha($fini);
		$ffin = regresa_fecha($ffin);
		$anio1 = substr($fini, 0, 4);
		$anio2 = substr($ffin, 0, 4);
		if($anio1 == $anio2){
			$W1 = date("W", strtotime( date($fini) ));
			$W2 = date("W", strtotime( date($ffin) ));
			$dia_inicio = date("w", strtotime($fini));
			$num = 1;
			for($i = $W1; $i <= $W2; $i++){
				$fecha_ini = daysOfWeek($anio1,$i,1);
				$fecha_fin = daysOfWeek($anio1,($i+1),0);
				$fecha_ini = cambia_fecha($fecha_ini);
				$fecha_fin = cambia_fecha($fecha_fin);
				$result = $ClsRes->get_ejecucion_respuestas('',$encuesta,'',$categoria,$fecha_ini,$fecha_fin,$situacion);
				$cantidad = 0;
				$cant = 0;
				$suma = 0;
				$promedio = 0;
				$siTotal = 0;
				$noTotal = 0;
				$sinoConteo = '';
				if(is_array($result)){
					foreach($result as $row){
						$tipo = trim($row["pre_tipo"]);
						if($tipo == 1){
							$nota = trim($row["resp_respuesta"]);
							$suma+= $nota;
							$cant++;
						}else if($tipo == 2){
							$respuesta = trim($row["resp_respuesta"]);
							if($respuesta == 1){
								$siTotal++;
							}else{
								$noTotal++;
							}
						}
						$cantidad++;
					}
					$promedio = $suma/$cant;
					$promedio = number_format($promedio,2,'.','');
				}
				$promedio = ($promedio == 0 && $cant == 0)?"":$promedio;
				$sinoConteo = "SI ($siTotal), NO ($noTotal)";
				$CANTIDAD+=$cantidad;
				$CANT+= $cant;
				$SUMA+= $suma;
				$SITOTAL+= $siTotal;
				$NOTOTAL+= $noTotal;
				$salida.= '<tr>';
				//--
				$salida.= '<td class = "text-center">'.$num.'.- </td>';
				$salida.= '<td class = "text-left">Semana '.$i.' ('.$fecha_ini.' al '.$fecha_fin.')</td>';
				$salida.= '<td class = "text-center">'.$cantidad.'</td>';
				$salida.= '<td class = "text-center">'.$promedio.'</td>';
				$salida.= '<td class = "text-center">'.$sinoConteo.'</td>';
				//--
				$salida.= '</tr>';
				$num++;
			}
		}else{
			$salida.= '<tr>';
			$salida.= '<td colspan = "6" class = "text-center"><strong class="text-danger">Las fechas deben pertenecer al mismo a&ntilde;o...</strong></td>';
			$salida.= '</tr>';
		}
	}else if($periodo == "M"){
		$fini = regresa_fecha($fini);
		$ffin = regresa_fecha($ffin);
		$mes1 = substr($fini, 5, 2);
		$mes2 = substr($ffin, 5, 2);
		//--
		$anio1 = substr($fini, 0, 4);
		$anio2 = substr($ffin, 0, 4);
		if($anio1 == $anio2){
			$num = 1;
			for($i = $mes1; $i <= $mes2; $i++){
				$mes_nombre = Meses_Letra($i);
				$fecha_ini = "01/$i/$anio1";
				$fecha_fin = "31/$i/$anio1";
				//echo "$mes_nombre: $fecha_ini - $fecha_fin<br>";
				$result = $ClsRes->get_ejecucion_respuestas('',$encuesta,'',$categoria,$fecha_ini,$fecha_fin,$situacion);
				$cantidad = 0;
				$cant = 0;
				$suma = 0;
				$promedio = 0;
				$siTotal = 0;
				$noTotal = 0;
				$sinoConteo = '';
				if(is_array($result)){
					foreach($result as $row){
						$tipo = trim($row["pre_tipo"]);
						if($tipo == 1){
							$nota = trim($row["resp_respuesta"]);
							$suma+= $nota;
							$cant++;
						}else if($tipo == 2){
							$respuesta = trim($row["resp_respuesta"]);
							if($respuesta == 1){
								$siTotal++;
							}else{
								$noTotal++;
							}
						}
						$cantidad++;
					}
					$promedio = $suma/$cant;
					$promedio = number_format($promedio,2,'.','');
				}
				$promedio = ($promedio == 0 && $cant == 0)?"":$promedio;
				$sinoConteo = "SI ($siTotal), NO ($noTotal)";
				$CANTIDAD+=$cantidad;
				$CANT+= $cant;
				$SUMA+= $suma;
				$SITOTAL+= $siTotal;
				$NOTOTAL+= $noTotal;
				$salida.= '<tr>';
				//--
				$salida.= '<td class = "text-center">'.$num.'.- </td>';
				$salida.= '<td class = "text-left">'.$mes_nombre.'</td>';
				$salida.= '<td class = "text-center">'.$cantidad.'</td>';
				$salida.= '<td class = "text-center">'.$promedio.'</td>';
				$salida.= '<td class = "text-center">'.$sinoConteo.'</td>';
				//--
				$salida.= '</tr>';
				$num++;
			}
		}else{
			$salida.= '<tr>';
			$salida.= '<td colspan = "6" class = "text-center"><strong class="text-danger">Las fechas deben pertenecer al mismo a&ntilde;o...</strong></td>';
			$salida.= '</tr>';
		}
	}
	//////////////// TOTALES DE TABLA ///////////////////
	if($CANT > 0){
		$PROMEDIO = $SUMA/$CANT;
		$PROMEDIO = number_format($PROMEDIO,2,'.','');
	}
	$SINOCONTEO = "SI ($SITOTAL), NO ($NOTOTAL)";
	$salida.= '<tr>';
	//--
	$salida.= '<th class = "text-center"> </th>';
	$salida.= '<th class = "text-right"> Totales &nbsp; </th>';
	$salida.= '<th class = "text-center">'.$CANTIDAD.'</th>';
	$salida.= '<th class = "text-center">'.$PROMEDIO.'</th>';
	$salida.= '<th class = "text-center">'.$SINOCONTEO.'</th>';
	//--
	$salida.= '</tr>';
	/////////---------
	$salida.= '</tbody>';
	$salida.= '</table>';return $salida;
}


function combo_semanas($name,$class='') {

	$salida .= '<select name="'.$name.'" id="'.$name.'" class = "'.$class.' form-control">';
	$salida .= '<option value="">Seleccione</option>';
	$salida .='</select>';return $salida;
}

function daysOfWeek($anio,$semana,$dia_semana){
	return date("Y-m-d", strtotime($anio."-W".$semana.'-'.$dia_semana));
}

?>