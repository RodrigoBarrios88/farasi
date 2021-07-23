<?php
ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cahce");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);
//--
include_once('html_fns_api.php');

header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header("Access-Control-Allow-Origin: *");

///API REQUEST
$request = $_REQUEST["request"];
$_REQUEST = str_replace("undefined", "", $_REQUEST); ///valida campos "undefined" desde javascript

if($request != ""){
	switch($request){
		case "semanas":
			$usuario = $_REQUEST["usuario"];
			$anio = $_REQUEST["anio"];
			API_semanas($usuario,$anio);
			break;
		case "actividades":
			$usuario = $_REQUEST["usuario"];
			$semana = $_REQUEST["semana"];
			$anio = $_REQUEST["anio"];
			$activo = $_REQUEST["activo"]; //no obligatorio
			$categoria = $_REQUEST["categoria"]; //no obligatorio
			$situacion = $_REQUEST["situacion"]; //no obligatorio
			API_actividades($usuario,$semana,$anio,$activo,$categoria,$situacion);
			break;
		case "actividad":
			$codigo = $_REQUEST["codigo"];
			API_actividad($codigo);
			break;
		case "preguntas":
			$programacion = $_REQUEST["programacion"];
			$cuestionario = $_REQUEST["cuestionario"];
			API_preguntas($programacion,$cuestionario);
			break;
		case "responder":
			$programacion = $_REQUEST["programacion"];
			$cuestionario = $_REQUEST["cuestionario"];
			$pregunta = $_REQUEST["pregunta"];
			$respuesta = $_REQUEST["respuesta"];
			API_responder($programacion,$cuestionario,$pregunta,$respuesta);
			break;
		case "status":
			$programacion = $_REQUEST["programacion"];
			$situacion = $_REQUEST["situacion"];
			$observaciones = $_REQUEST["obs"];
			$fecha = $_REQUEST["fecha"];
			$hora = $_REQUEST["hora"];
			API_Status($programacion,$situacion,$observaciones,$fecha,$hora);
			break;
		case "getimagenes":
			$codigo = $_REQUEST["codigo"];
			API_get_imagenes($codigo);
			break;
		case "valida":
			$codigo = $_REQUEST["codigo"];
			API_valida($codigo);
			break;
		case "offline":
			$usuario = $_REQUEST["usuario"];
			$anio = $_REQUEST["anio"];
			API_offline($usuario,$anio);
			break;
		default:
			$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Parametros invalidos...");
			echo json_encode($payload);
			break;
	}
}else{
	//devuelve un mensaje de manejo de errores
	$payload = array(
		"status" => false,
		"data" => [],
		"message" => "Delimite el tipo de consulta a realizar...");
		echo json_encode($payload);
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////// FUNCIONES Y CONSULTAS ////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function API_semanas($usuario,$anio){
	$ClsPro = new ClsProgramacionPPM();
	$ClsAct = new ClsActivo();
	$ClsUsu = new ClsUsuario();
	if($usuario != "" && $anio != ""){
		$result = $ClsUsu->get_usuario_sede("",$usuario,"");
		if(is_array($result)){
			$sedes = "";
			foreach($result as $row){
				$sedes.= trim($row["sus_sede"]).",";
			}
			$sedes = substr($sedes,0,-1);
		}
		$semana_actual = date("W");
		$semana_actual = ($semana_actual == 0)?7:$semana_actual;
		$i = 0;
		$arr_data = array();
		for($semana = 1; $semana <= 52; $semana++){
			if($semana < $semana_actual){
				$semana = (strlen($semana) <= 1)?"0$semana":$semana;
				$desde = date("d/m/Y", strtotime($anio."-W".$semana.'-1'));
				$hasta = date("d/m/Y", strtotime($anio."-W".$semana.'-7'));
				$rango_fechas = "$desde - $hasta";
				
				$result = $ClsPro->get_programacion($codigo,$activo,$usuario,$categoria,$sedes, '', '', $desde, $hasta);
				$normal = 0;
				$vencido = 0;
				if(is_array($result)){
					foreach ($result as $row){
						//conteo de dias
						$programado = trim($row["pro_fecha"])." 23:59:59";
						$fecha_update = trim($row["pro_fecha_update"]);
						$vencimiento = comparaFechas($programado, $fecha_update);
						$situacion = $row["pro_situacion"];
						if($vencimiento == 2){ // vencido
							$vencido++;
						}else{ // normal
							$normal++;
						}
					}
				}
				if($vencido > 0){
					$estado = 2;
				}else{
					$estado = 1;
				}
			}else if($semana == $semana_actual){
				$estado = 3;
			}else{
				$estado = 4;
			}
			$arr_data[$i]['semana'] = intval($semana);
			$arr_data[$i]['rango_fechas'] = $rango_fechas;
			$arr_data[$i]['estado'] = intval($estado);
			$i++;
		}
		
		$payload = array(
			"status" => true,
			"data" => $arr_data,
			"message" => "");

		echo json_encode($payload);
		
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Uno de los campos esta vacio...");
			echo json_encode($payload);
	}
}


function API_actividades($usuario,$semana,$anio,$activo='',$categoria='',$situacion=''){
	$ClsPro = new ClsProgramacionPPM();
	$ClsAct = new ClsActivo();
	$ClsUsu = new ClsUsuario();
	if($usuario != "" && $semana != "" && $anio != ""){
		
		$result = $ClsUsu->get_usuario_sede("",$usuario,"");
		if(is_array($result)){
			$sedes = "";
			foreach($result as $row){
				$sedes.= trim($row["sus_sede"]).",";
			}
			$sedes = substr($sedes,0,-1);
		}
		$semana = (strlen($semana) <= 1)?"0$semana":$semana;
		$desde = date("d/m/Y", strtotime($anio."-W".$semana.'-1'));
		$hasta = date("d/m/Y", strtotime($anio."-W".$semana.'-7'));
		
		$result = $ClsPro->get_programacion($codigo,$activo,$usuario,$categoria,$sedes, '', '', $desde, $hasta,'','',$situacion);
		if(is_array($result)){
			$i = 0;
			foreach ($result as $row){
				//situacion
				$programado = trim($row["pro_fecha"]);
				$ahora = date("Y-m-d");
				$vencimiento = comparaFechas($programado, $ahora);
				$situacion = $row["pro_situacion"];
				if($situacion == 1){
					if($vencimiento == 1){ // Falta para que se cumpla
						$situacion_desc = 'Programado';
					}else if($vencimiento == 2){ // ya se vencio
						$situacion_desc = 'Vencido';
						$situacion = 5;
					}else{ // hoy corresponde
						$situacion_desc = 'Para Hoy';
					}
				}else if($situacion == 2){
					$situacion_desc = 'En Espera';
				}else if($situacion == 3){
					$situacion_desc = 'En Proceso';
				}else if($situacion == 4){
					$situacion_desc = 'Finalizado';
				}
				//codigo
				$arr_data[$i]['codigo'] = trim($row["pro_codigo"]);
				$fecha = trim($row["pro_fecha"]);
				$arr_data[$i]['fecha'] = cambia_fecha($fecha);
				//ubicacion
				$arr_data[$i]['sede'] = trim($row["sed_nombre"]);
				$arr_data[$i]['sector'] = trim($row["sec_nombre"]);
				$arr_data[$i]['area'] = trim($row["are_nombre"]);
				$arr_data[$i]['nivel'] = trim($row["are_nivel"]);
				//activo
				$activo_codigo = trim($row["act_codigo"]);
				$arr_data[$i]['activo_codigo'] = $activo_codigo;
				$arr_data[$i]['activo'] = trim($row["act_nombre"]);
				$arr_data[$i]['marca'] = trim($row["act_marca"]); 		
				$arr_data[$i]['proveedor'] = trim($row["act_proveedor"]); 		
				$arr_data[$i]['periodicidad'] = trim($row["act_periodicidad"]); 		
				$arr_data[$i]['capacidad'] = trim($row["act_capacidad"]); 		
				$arr_data[$i]['cantidad'] = trim($row["act_cantidad"]); 		
				$arr_data[$i]['observaciones'] = trim($row["act_observaciones"]);
				$actFotos = array();
				$result_activo = $ClsAct->get_fotos('',$activo_codigo);
				if(is_array($result_activo)){
					$j = 0;	
					foreach ($result_activo as $row_activo){
						$actFoto1 = trim($row_activo["fot_foto"]);
						if(file_exists('../../CONFIG/Fotos/ACTIVOS/'.$actFoto1.'.jpg') || $actFoto1 != ""){
							$actFotos[$j]['foto_activo'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/ACTIVOS/$actFoto1.jpg";
						}else{
							$actFotos[$j]['foto_activo'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imagePhoto.jpg";
						}
						$j++;
					}
				}else{
					$actFotos[0]['foto_activo'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imagePhoto.jpg";
				}
				$arr_data[$i]['fotos_activos'] =  $actFotos;
				//--
				$arr_data[$i]['nombre_usuario'] = trim($row["usu_nombre"]);
				$arr_data[$i]['categoria'] = trim($row["cat_nombre"]);
				$arr_data[$i]['presupuesto'] = trim($row["mon_simbolo"]).'. '.trim($row["pro_presupuesto_programado"]);
				$arr_data[$i]['cuestionario'] = trim($row["pro_cuestionario"]);
				//--
				$strFoto1 = trim($row["pro_foto1"]);
				$strFoto2 = trim($row["pro_foto2"]);
				$strFirma = trim($row["pro_firma"]);
				//--
				$fecha_update = trim($row["pro_fecha_update"]);
				$fecha_update = cambia_fechaHora($fecha_update);
				$arr_data[$i]['fecha_actualizacion'] = substr($fecha_update,0,16);
				$arr_data[$i]['observaciones_programacion'] = trim($row["pro_observaciones_programacion"]);
				$arr_data[$i]['observaciones_ejecucion'] = trim($row["pro_observaciones_ejecucion"]);
				$arr_data[$i]['situacion'] = $situacion;
				$arr_data[$i]['situacion_descripcion'] = $situacion_desc;
				//--
				$arrFotos = array();
				if(file_exists('../../CONFIG/Fotos/PPM/'.$strFoto1.'.jpg') && $strFoto1 != ""){
					$arrFotos[0]['foto_antes'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/PPM/$strFoto1.jpg";
				}else{
					$arrFotos[0]['foto_antes'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imagePhoto.jpg";
				}
				if(file_exists('../../CONFIG/Fotos/PPM/'.$strFoto2.'.jpg') && $strFoto2 != ""){
					$arrFotos[0]['foto_despues'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/PPM/$strFoto2.jpg";
				}else{
					$arrFotos[0]['foto_despues'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imagePhoto.jpg";
				}
				if(file_exists('../../CONFIG/Fotos/PPMFIRMAS/'.$strFirma.'.jpg') && $strFirma != ""){
					$arrFotos[0]['firma'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/PPMFIRMAS/$strFirma.jpg";
				}else{
					$arrFotos[0]['firma'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imageSign.jpg";
				}
				$arr_data[$i]['fotos_firmas'] = $arrFotos;
				//
				$i++;
			}
			
			$payload = array(
				"status" => true,
				"data" => $arr_data,
				"message" => "");

			echo json_encode($payload);
		}else{
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => false,
				"data" => [],
				"message" => "No se registran datos...");
				echo json_encode($payload);
		}
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Uno de los campos esta vacio...");
			echo json_encode($payload);
	}
}


function API_actividad($codigo){
	$ClsPro = new ClsProgramacionPPM();
	$ClsAct = new ClsActivo();
	if($codigo != ""){
		$result = $ClsPro->get_programacion($codigo);
		if(is_array($result)){
			$i = 0;
			foreach ($result as $row){
				//situacion
				$programado = trim($row["pro_fecha"]);
				$ahora = date("Y-m-d");
				$vencimiento = comparaFechas($programado, $ahora);
				$situacion = $row["pro_situacion"];
				if($situacion == 1){
					if($vencimiento == 1){ // Falta para que se cumpla
						$situacion_desc = 'Programado';
					}else if($vencimiento == 2){ // ya se vencio
						$situacion_desc = 'Vencido';
						$situacion = 5;
					}else{ // hoy corresponde
						$situacion_desc = 'Para Hoy';
					}
				}else if($situacion == 2){
					$situacion_desc = 'En Espera';
				}else if($situacion == 3){
					$situacion_desc = 'En Proceso';
				}else if($situacion == 4){
					$situacion_desc = 'Finalizado';
				}
				//codigo
				$arr_data[$i]['codigo'] = trim($row["pro_codigo"]);
				$fecha = trim($row["pro_fecha"]);
				$arr_data[$i]['fecha'] = cambia_fecha($fecha);
				//ubicacion
				$arr_data[$i]['sede'] = trim($row["sed_nombre"]);
				$arr_data[$i]['sector'] = trim($row["sec_nombre"]);
				$arr_data[$i]['area'] = trim($row["are_nombre"]);
				$arr_data[$i]['nivel'] = trim($row["are_nivel"]);
				//activo
				$activo_codigo = trim($row["act_codigo"]);
				$arr_data[$i]['activo_codigo'] = $activo_codigo;
				$arr_data[$i]['activo'] = trim($row["act_nombre"]);
				$arr_data[$i]['marca'] = trim($row["act_marca"]); 		
				$arr_data[$i]['proveedor'] = trim($row["act_proveedor"]); 		
				$arr_data[$i]['periodicidad'] = trim($row["act_periodicidad"]); 		
				$arr_data[$i]['capacidad'] = trim($row["act_capacidad"]); 		
				$arr_data[$i]['cantidad'] = trim($row["act_cantidad"]); 		
				$arr_data[$i]['observaciones'] = trim($row["act_observaciones"]);
				$actFotos = array();
				$result_activo = $ClsAct->get_fotos('',$activo_codigo);
				if(is_array($result_activo)){
					$j = 0;	
					foreach ($result_activo as $row_activo){
						$actFoto1 = trim($row_activo["fot_foto"]);
						if(file_exists('../../CONFIG/Fotos/ACTIVOS/'.$actFoto1.'.jpg') || $actFoto1 != ""){
							$actFotos[$j]['foto_activo'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/ACTIVOS/$actFoto1.jpg";
						}else{
							$actFotos[$j]['foto_activo'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imagePhoto.jpg";
						}
						$j++;
					}
				}else{
					$actFotos[0]['foto_activo'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imagePhoto.jpg";
				}
				$arr_data[$i]['fotos_activos'] =  $actFotos;
				//--
				$arr_data[$i]['nombre_usuario'] = trim($row["usu_nombre"]);
				$arr_data[$i]['categoria'] = trim($row["cat_nombre"]);
				$arr_data[$i]['presupuesto'] = trim($row["mon_simbolo"]).'. '.trim($row["pro_presupuesto_programado"]);
				$arr_data[$i]['cuestionario'] = trim($row["pro_cuestionario"]);
				//--
				$strFoto1 = trim($row["pro_foto1"]);
				$strFoto2 = trim($row["pro_foto2"]);
				$strFirma = trim($row["pro_firma"]);
				//--
				$fecha_update = trim($row["pro_fecha_update"]);
				$fecha_update = cambia_fechaHora($fecha_update);
				$arr_data[$i]['fecha_actualizacion'] = substr($fecha_update,0,16);
				$arr_data[$i]['observaciones_programacion'] = trim($row["pro_observaciones_programacion"]);
				$arr_data[$i]['observaciones_ejecucion'] = trim($row["pro_observaciones_ejecucion"]);
				$arr_data[$i]['situacion'] = $situacion;
				$arr_data[$i]['situacion_descripcion'] = $situacion_desc;
				//--
				$arrFotos = array();
				if(file_exists('../../CONFIG/Fotos/PPM/'.$strFoto1.'.jpg') && $strFoto1 != ""){
					$arrFotos[0]['foto_antes'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/PPM/$strFoto1.jpg";
				}else{
					$arrFotos[0]['foto_antes'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imagePhoto.jpg";
				}
				if(file_exists('../../CONFIG/Fotos/PPM/'.$strFoto2.'.jpg') && $strFoto2 != ""){
					$arrFotos[0]['foto_despues'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/PPM/$strFoto2.jpg";
				}else{
					$arrFotos[0]['foto_despues'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imagePhoto.jpg";
				}
				if(file_exists('../../CONFIG/Fotos/PPMFIRMAS/'.$strFirma.'.jpg') && $strFirma != ""){
					$arrFotos[0]['firma'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/PPMFIRMAS/$strFirma.jpg";
				}else{
					$arrFotos[0]['firma'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imageSign.jpg";
				}
				$arr_data[$i]['fotos_firmas'] = $arrFotos;
				//
				$i++;
			}
			$i--;//quita una vuelta
			
			$payload = array(
				"status" => true,
				"data" => $arr_data,
				"message" => "");

			echo json_encode($payload);
		}else{
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => false,
				"data" => [],
				"message" => "No se registran datos...");
				echo json_encode($payload);
		}
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Uno de los campos esta vacio...");
			echo json_encode($payload);
	}
}



function API_preguntas($programacion,$cuestionario){
	$ClsCue = new ClsCuestionarioPPM();
	$ClsPro = new ClsProgramacionPPM();
	
	if($programacion != "" && $cuestionario != ""){
		$result = $ClsCue->get_pregunta('',$cuestionario,'',1);
		if(is_array($result)){
			$i = 0;
			foreach ($result as $row){
				//codigo
				$pregunta_codigo = trim($row["pre_codigo"]);
				$arr_data[$i]['pregunta_codigo'] = $pregunta_codigo;
				//texto de la pregunta
				$arr_data[$i]['pregunta_texto'] = trim($row["pre_pregunta"]);
				//respuesta
				$respuesta = "";
				$result_respuesta = $ClsPro->get_respuesta($programacion,$cuestionario,$pregunta_codigo);
				if(is_array($result_respuesta)){
					foreach ($result_respuesta as $row_respuesta){
						$respuesta = trim($row_respuesta["resp_respuesta"]);
					}
					$arr_data[$i]['respuesta'] = $respuesta;
				}else{
					$arr_data[$i]['respuesta'] = "";
				}	
				//--
				$i++;
			}
			$payload = array(
				"status" => true,
				"data" => $arr_data,
				"message" => "");

			echo json_encode($payload);
		}else{
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => false,
				"data" => [],
				"message" => "No se registran datos...");
				echo json_encode($payload);
		}
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Uno de los campos esta vacio...");
			echo json_encode($payload);
	}
}


function API_responder($programacion,$cuestionario,$pregunta,$respuesta){
	$ClsPro = new ClsProgramacionPPM();

	if($programacion != "" && $cuestionario != "" && $pregunta != "" && $respuesta != ""){
		$sql = $ClsPro->insert_respuesta($programacion,$cuestionario,$pregunta,$respuesta);
		$rs = $ClsPro->exec_sql($sql);
		if($rs == 1){
			$payload = array(
				 "status" => true,
				 "data" => [],
				 "message" => "respuesta exitosa!");
				 echo json_encode($payload);
		}else{
			//devuelve un mensaje de manejo de errores
		 	$payload = array(
				 "status" => false,
				 "data" => [],
				 "message" => "Error en la transacci贸n...");
				 echo json_encode($payload);
		}
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Uno de los campos esta vacio...");
			echo json_encode($payload);
	}
}


function API_Status($programacion,$situacion,$observaciones,$fecha = '',$hora = ''){
	$ClsPro = new ClsProgramacionPPM();

	if($programacion != ""){
		$observaciones = trim($observaciones);
		$sql = $ClsPro->update_observaciones_ejecucion($programacion,$observaciones);
		$sql.= $ClsPro->cambia_sit_programacion($programacion,"$fecha $hora",$situacion);
		$rs = $ClsPro->exec_sql($sql);
		if($rs == 1){
			$payload = array(
				 "status" => true,
				 "data" => [],
				 "message" => "Situaci贸n actualizada exitosamente!");
				 echo json_encode($payload);
		}else{
			//devuelve un mensaje de manejo de errores
		 	$payload = array(
				 "status" => false,
				 "data" => [],
				 "message" => "Error en la transacci贸n...");
				 echo json_encode($payload);
		}
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Uno de los campos esta vacio...");
			echo json_encode($payload);
	}
}



function API_get_imagenes($programacion){
	$ClsPro = new ClsProgramacionPPM();
	
	if($programacion != ""){
		$arrFotos = array();
		$result = $ClsPro->get_programacion($programacion);
		if(is_array($result)){
			foreach ($result as $row){
				$strFoto1 = trim($row["pro_foto1"]);
				$strFoto2 = trim($row["pro_foto2"]);
				$strFirma = trim($row["pro_firma"]);
			}
			if(file_exists('../../CONFIG/Fotos/PPM/'.$strFoto1.'.jpg') && $strFoto1 != ""){
				$arrFotos[0]['foto_antes'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/PPM/$strFoto1.jpg";
			}else{
				$arrFotos[0]['foto_antes'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imagePhoto.jpg";
			}
			if(file_exists('../../CONFIG/Fotos/PPM/'.$strFoto2.'.jpg') && $strFoto2 != ""){
				$arrFotos[0]['foto_despues'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/PPM/$strFoto2.jpg";
			}else{
				$arrFotos[0]['foto_despues'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imagePhoto.jpg";
			}
			if(file_exists('../../CONFIG/Fotos/PPMFIRMAS/'.$strFirma.'.jpg') && $strFirma != ""){
				$arrFotos[0]['firma'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/PPMFIRMAS/$strFirma.jpg";
			}else{
				$arrFotos[0]['firma'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imageSign.jpg";
			}
		}else{
			$arrFotos[0]['foto_antes'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imagePhoto.jpg";
			$arrFotos[0]['foto_despues'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imagePhoto.jpg";
			$arrFotos[0]['firma'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imageSign.jpg";
		}
		$arr_data = array(
			"status" => true,
			"imagens" => $arrFotos,
			"message" => ""
		);
		echo json_encode($arr_data);
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Uno de los campos esta vacio...");
			echo json_encode($payload);
	}
}



function API_valida($programacion){
	$ClsCue = new ClsCuestionarioPPM();
	$ClsPro = new ClsProgramacionPPM();
	
	if($programacion != ""){
		$result = $ClsPro->get_programacion($programacion);
		if(is_array($result)){
			//////// PROGRAMACION /////////
			foreach ($result as $row){
				//codigo
				$codigo = trim($row["pro_codigo"]);
				$situacion = trim($row["pro_situacion"]);
				//--
				$strFoto1 = trim($row["pro_foto1"]);
				$strFoto2 = trim($row["pro_foto2"]);
				$strFirma = trim($row["pro_firma"]);
				//
				$cuestionario = trim($row["pro_cuestionario"]);
			}
			$strFoto1 = ($strFoto1 != "")?true:false;
			$strFoto2 = ($strFoto2 != "")?true:false;
			$strFirma = ($strFirma != "")?true:false;
			
			///////// CUESTIONARIO //////////
			$result = $ClsCue->get_pregunta('',$cuestionario,'',1);
			if(is_array($result)){
				$preguntas = 0;
				$respuestas = 0;
				foreach ($result as $row){
					//codigo
					$pregunta_codigo = trim($row["pre_codigo"]);
					//respuesta
					$respuesta = "";
					$result_respuesta = $ClsPro->get_respuesta($programacion,$cuestionario,$pregunta_codigo);
					if(is_array($result_respuesta)){
						foreach ($result_respuesta as $row_respuesta){
							$respuestas++;
						}
					}
					$preguntas++;
				}
				if($preguntas <= $respuestas){
					$cuestionario = true;
				}else{
					$cuestionario = false;
				}
			}else{
				$cuestionario = true;
			}
			
			$arr_data = array(
				"programacion"  =>  $programacion,
				"status"  =>  $situacion,
				"foto1"  =>  $strFoto1,
				"foto2" => $strFoto2,
				"firma" => $strFirma,
				"cuestionario" => $cuestionario
			);
			
			$payload = array(
				"status" => true,
				"data" => $arr_data,
				"message" => "");

			echo json_encode($payload);
		}else{
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => false,
				"data" => [],
				"message" => "No se registran datos...");
				echo json_encode($payload);
		}
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Uno de los campos esta vacio...");
			echo json_encode($payload);
	}
}



///////////// OFFLINE//////////////////

function API_offline($usuario,$anio){
	$ClsPro = new ClsProgramacionPPM();
	$ClsCue = new ClsCuestionarioPPM();
	$ClsAct = new ClsActivo();
	$ClsUsu = new ClsUsuario();
	if($usuario != "" && $anio != ""){
		
		$result = $ClsUsu->get_usuario_sede("",$usuario,"");
		if(is_array($result)){
			$sedes = "";
			foreach($result as $row){
				$sedes.= trim($row["sus_sede"]).",";
			}
			$sedes = substr($sedes,0,-1);
		}
		$semana_actual = date("W");
		$semana_actual = ($semana_actual == 0)?7:$semana_actual;
		$x = 0;
		$arr_semana = array();
		for($semana = 1; $semana <= 52; $semana++){
			//////////// VALIDACION DE ESTADO EN LA SEMANA
			if($semana <= $semana_actual){
				$semana = (strlen($semana) <= 1)?"0$semana":$semana;
				$desde = date("d/m/Y", strtotime($anio."-W".$semana.'-1'));
				$hasta = date("d/m/Y", strtotime($anio."-W".$semana.'-7'));
				//------
				if($semana == $semana_actual){ /// Solo la semana actual
					$result = $ClsPro->get_programacion('','',$usuario,'',$sedes, '', '', $desde, $hasta);
					$normal = 0;
					$vencido = 0;
					if(is_array($result)){
						$i = 0;
						foreach ($result as $row){
							//situacion
							$programado = trim($row["pro_fecha"]);
							$ahora = date("Y-m-d");
							$vencimiento = comparaFechas($programado, $ahora);
							$situacion = $row["pro_situacion"];
							if($situacion == 1){
								if($vencimiento == 1){ // Falta para que se cumpla
									$situacion_desc = 'Programado';
								}else if($vencimiento == 2){ // ya se vencio
									$situacion_desc = 'Vencido';
									$situacion = 5;
								}else{ // hoy corresponde
									$situacion_desc = 'Para Hoy';
								}
							}else if($situacion == 2){
								$situacion_desc = 'En Espera';
							}else if($situacion == 3){
								$situacion_desc = 'En Proceso';
							}else if($situacion == 4){
								$situacion_desc = 'Finalizado';
							}
							//codigo
							$arr_data[$i]['codigo'] = trim($row["pro_codigo"]);
							$fecha = trim($row["pro_fecha"]);
							$arr_data[$i]['fecha'] = cambia_fecha($fecha);
							//ubicacion
							$arr_data[$i]['sede'] = trim($row["sed_nombre"]);
							$arr_data[$i]['sector'] = trim($row["sec_nombre"]);
							$arr_data[$i]['area'] = trim($row["are_nombre"]);
							$arr_data[$i]['nivel'] = trim($row["are_nivel"]);
							//activo
							$activo_codigo = trim($row["act_codigo"]);
							$arr_data[$i]['activo_codigo'] = $activo_codigo;
							$arr_data[$i]['activo'] = trim($row["act_nombre"]);
							$arr_data[$i]['marca'] = trim($row["act_marca"]); 		
							$arr_data[$i]['proveedor'] = trim($row["act_proveedor"]); 		
							$arr_data[$i]['periodicidad'] = trim($row["act_periodicidad"]); 		
							$arr_data[$i]['capacidad'] = trim($row["act_capacidad"]); 		
							$arr_data[$i]['cantidad'] = trim($row["act_cantidad"]); 		
							$arr_data[$i]['observaciones'] = trim($row["act_observaciones"]);
							$actFotos = array();
							$result_activo = $ClsAct->get_fotos('',$activo_codigo);
							$j = 0;	
							if(is_array($result_activo)){
								foreach ($result_activo as $row_activo){
									$actFoto1 = trim($row_activo["fot_foto"]);
									if(file_exists('../../CONFIG/Fotos/ACTIVOS/'.$actFoto1.'.jpg') || $actFoto1 != ""){
										$actFotos[$j]['foto_activo'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/ACTIVOS/$actFoto1.jpg";
									}else{
										$actFotos[$j]['foto_activo'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imagePhoto.jpg";
									}
									$j++;
								}
							}else{
								$actFotos[0]['foto_activo'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imagePhoto.jpg";
							}
							$arr_data[$i]['fotos_activos'] =  $actFotos;
							//--
							$arr_data[$i]['nombre_usuario'] = trim($row["usu_nombre"]);
							$arr_data[$i]['categoria'] = trim($row["cat_nombre"]);
							$arr_data[$i]['presupuesto'] = trim($row["mon_simbolo"]).'. '.trim($row["pro_presupuesto_programado"]);
							//--
							$strFoto1 = trim($row["pro_foto1"]);
							$strFoto2 = trim($row["pro_foto2"]);
							$strFirma = trim($row["pro_firma"]);
							//--
							$fecha_update = trim($row["pro_fecha_update"]);
							$fecha_update = cambia_fechaHora($fecha_update);
							$arr_data[$i]['fecha_actualizacion'] = substr($fecha_update,0,16);
							$arr_data[$i]['observaciones_programacion'] = trim($row["pro_observaciones_programacion"]);
							$arr_data[$i]['observaciones_ejecucion'] = trim($row["pro_observaciones_ejecucion"]);
							$arr_data[$i]['situacion'] = $situacion;
							$arr_data[$i]['situacion_descripcion'] = $situacion_desc;
							//--
							$arrFotos = array();
							if(file_exists('../../CONFIG/Fotos/PPM/'.$strFoto1.'.jpg') && $strFoto1 != ""){
								$arrFotos[0]['foto_antes'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/PPM/$strFoto1.jpg";
							}else{
								$arrFotos[0]['foto_antes'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imagePhoto.jpg";
							}
							if(file_exists('../../CONFIG/Fotos/PPM/'.$strFoto2.'.jpg') && $strFoto2 != ""){
								$arrFotos[0]['foto_despues'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/PPM/$strFoto2.jpg";
							}else{
								$arrFotos[0]['foto_despues'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imagePhoto.jpg";
							}
							if(file_exists('../../CONFIG/Fotos/PPMFIRMAS/'.$strFirma.'.jpg') && $strFirma != ""){
								$arrFotos[0]['firma'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/PPMFIRMAS/$strFirma.jpg";
							}else{
								$arrFotos[0]['firma'] = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imageSign.jpg";
							}
							$arr_data[$i]['fotos_firmas'] = $arrFotos;
							///////////// CUESTIONARIO ///////////////////
							$arr_preguntas = array();
							$cuestionario = trim($row["pro_cuestionario"]);
							$result_preguntas = $ClsCue->get_pregunta('',$cuestionario,'',1);
							$j = 0;
							if(is_array($result_preguntas)){
								foreach ($result_preguntas as $row_preguntas){
									//codigo
									$pregunta_codigo = trim($row_preguntas["pre_codigo"]);
									$arr_preguntas[$j]['pregunta_codigo'] = $pregunta_codigo;
									//texto de la pregunta
									$arr_preguntas[$j]['pregunta_texto'] = trim($row_preguntas["pre_pregunta"]);
									//respuesta
									$respuesta = "";
									$result_respuesta = $ClsPro->get_respuesta($programacion,$cuestionario,$pregunta_codigo);
									if(is_array($result_respuesta)){
										foreach ($result_respuesta as $row_respuesta){
											$respuesta = trim($row_respuesta["resp_respuesta"]);
										}
										$arr_preguntas[$j]['respuesta'] = $respuesta;
									}else{
										$arr_preguntas[$j]['respuesta'] = "";
									}
									$j++;
								
								}
							}
							$arr_data[$i]['cuestionario'] = trim($row["pro_cuestionario"]);
							$arr_data[$i]['preguntas'] = $arr_preguntas;
							//////////// VALIDACION DE ESTADO EN LA SEMANA
							//conteo de dias
							$programado = trim($row["pro_fecha"])." 23:59:59";
							$fecha_update = trim($row["pro_fecha_update"]);
							$vencimiento = comparaFechas($programado, $fecha_update);
							$situacion = $row["pro_situacion"];
							if($vencimiento == 2){ // vencido
								$vencido++;
							}else{ // normal
								$normal++;
							}
							///
							$i++;
						}
					}else{
						$estado = 1;
						$arr_data = array();
					}
				}else{
					$arr_data = array();
				}
				if($vencido > 0){
					$estado = 2;
				}else{
					$estado = 1;
				}
				if($semana == $semana_actual){
				    $estado = 3;
				}    
			}else{
				$estado = 4;
				$arr_data = array();
			}	
			
			//echo "Semana $semana ($estado) : Desde $desde - Hasta $hasta -- $arr_data<br>";
			
			$arr_semana[$x]['semana'] = intval($semana);
			$arr_semana[$x]['estado'] = intval($estado);
			$arr_semana[$x]['programacion'] = $arr_data;
			$x++;
		}
		
		$payload = array(
				"status" => true,
				"data" => $arr_semana,
				"message" => "");

			echo json_encode($payload);
		
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Uno de los campos esta vacio...");
			echo json_encode($payload);
	}
}

?>