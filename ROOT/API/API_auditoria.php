<?php

/**
*
* @title API para APP de auditoria
* @author Andy Gomez (plani-go.com)
* @comments API para el control total de la APP de auditoria
*
*/

ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cache");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);
// error_reporting(E_ALL);

//--
include_once('html_fns_api.php');
include_once('../CPAUDEJECUCION/html_fns_ejecucion.php');
// require ("../xajax_core/xajax.inc.php");

header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header("Access-Control-Allow-Origin: *");
header("Set-Cookie: PHPSESSID=cjlk1o4giv3dtt3en71jcr578d; path=/");

///API REQUEST
$request = $_REQUEST["request"];
$_REQUEST = str_replace("undefined", "", $_REQUEST); ///valida campos "undefined" desde javascript

if($request != ""){
	switch($request){
		case "listas":
			$categorias = htmlspecialchars($_REQUEST["categoria"]);
			$usuario = htmlspecialchars($_REQUEST["usuario"]);
			$departamento = htmlspecialchars($_REQUEST['departamento']);
			$situacion = htmlspecialchars($_REQUEST['situacion']);
			API_aud($categorias,$usuario, $departamento, $situacion);
			break;
		case "aud":
			$codigo = $_REQUEST["codigo"];
			$usuario = $_REQUEST["usuario"];
			$ejecucion = $_REQUEST['ejec'];
			$prog = $_REQUEST['prog'];
			API_audt($codigo,$usuario, $ejecucion, $prog, '');
			break;
		case "listas_offline":
			$usuario = $_REQUEST["usuario"];
			API_listas_offline($usuario);
			break;
		case "quiz":
			$codigo = $_REQUEST["codigo"];
			$seccion = $_REQUEST["seccion"];
			$ejecucion = $_REQUEST['ejecucion'];
			API_quiz($codigo,$seccion, $ejecucion);
			break;
		case "foto":
			API_fotos();
			break;
		case "firma":
			API_firma();
			break;
		case 'fotos':
			$auditoria = $_REQUEST['aud'];
			$ejecucion = $_REQUEST['ejec'];
			$pregunta = $_REQUEST['preg'];
			API_getFotos($auditoria, $ejecucion, $pregunta);
			break;
		case "answers":
			//instanciamos el objeto de la clase xajax

			$function = $_REQUEST['xjxfun'];
			$arg = $_REQUEST['xjxargs'];
			if ($function == 'Responder_Ponderacion') {
				echo Responder_Ponderacion($arg[0],$arg[1], $arg[2], $arg[3], $arg[4], $arg[5], $arg[6], $arg[7], $arg[8]);
			} else if ($function == 'Responder_Texto') {
				echo Responder_Texto($arg[0],$arg[1], $arg[2], $arg[3], $arg[4]);
			} else if ($function == 'Observacion_Departamento'){
				echo Observacion_Departamento($arg[0],$arg[1], $arg[2]);
			} else if ($function == 'Cerrar_Ejecucion') {
				echo Cerrar_Ejecucion($arg[0],$arg[1], $arg[2], $arg[3], $arg[4]);
			}

			//El objeto xajax tiene que procesar cualquier petici&oacute;n
			// $xajax->processRequest();

			break;
		case 'data';
			$ejecucion = $_REQUEST['data'];
			$audit = $_REQUEST['audit'];
			$pregunta = $_REQUEST['pregunta'];
			view_data($ejecucion, $audit, $pregunta);
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

function view_data($ejecucion, $auditoria, $pregunta) {
	$ClsEje = new ClsEjecucion();
	//
	echo 'Response:';
	return $ClsEje->get_respuesta($ejecucion, $auditoria, $pregunta);
}

function API_getFotos ($auditoria, $ejecucion, $pregunta) {
	$ClsEje = new ClsEjecucion();
	$getFotos = $ClsEje->get_fotos('', $ejecucion, $auditoria, $pregunta);
	$fotos = array();
	if(is_array($getFotos)){
	    foreach ($getFotos as $row){
			$foto = trim($row["fot_foto"]);
	    	$fotos[] = '/CONFIG/Fotos/AUDITORIA/'.$foto.'.jpg';
	    }
	}
	$payload = array(
			"status" => false,
			"data" => $fotos,
			"message" => "");
	echo json_encode($payload);
}

function API_aud($categorias,$usuario, $departamento, $situacion){
	$ClsAud = new ClsAuditoria();
	$ClsUsu = new ClsUsuario();
	$ClsEje = new ClsEjecucion();
	if($usuario != ""){
		$hora_actual = date("H:i");
		$dia = date("N");
		
		$result = $ClsUsu->get_usuario_sede("",$usuario,"");
		if(is_array($result)){
			$sedes = "";
			foreach($result as $row){
				$sedes.= trim($row["sus_sede"]).",";
			}
			$sedes = substr($sedes,0,-1);
		}
		
		// '','',$sedes,'','',$categorias,$dia,$hora_actual,1,'',date("d/m/Y"),date("d/m/Y")
		$fecha = date("d/m/Y");
		$result = $ClsAud->get_programacion('', NULL, $sedes, NULL, $categorias, $fecha, $fecha, NULL, NULL, 1, $situacion, $usuario);
		if(is_array($result)){
			$i = 0;
			foreach ($result as $row){
				// $result = $ClsEje->get_ejecucion($row['eje_codigo'], $row['audit_codigo'], $usuario,$sedes, '', $categorias, '', '', $situacion, 'DESC');
				// $arr_data[$i]['ejecuciones'] = $result;
				// $field_fecha = date(strtotime($row['pro_fecha']), 'd/m/Y');
				$datetime = strtotime($row['pro_fecha'] . ' ' . $row['pro_hora']);
				$arr_data[$i]['sed_nombre'] = trim($row['sed_nombre']);
				$arr_data[$i]['dep_nombre'] = trim($row['dep_nombre']);
				$arr_data[$i]['cat_nombre'] = trim($row['cat_nombre']);
				$arr_data[$i]['audit_nombre'] = trim($row['audit_nombre']);
				$arr_data[$i]['pro_date'] = trim(date('d M Y', $datetime));
				$arr_data[$i]['pro_hour'] = trim(date('g:i', $datetime));
				$arr_data[$i]['pro_hourtwo'] = trim(date('A', $datetime));
				$arr_data[$i]['codigo'] = trim($row['audit_codigo']);
				$arr_data[$i]['ejecucion'] = trim($row['ejecucion_activa']);
				$arr_data[$i]['programacion'] = trim($row['pro_codigo']);
				$arr_data[$i]['ejecodigo'] = trim($row['eje_codigo']);
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

function API_quiz($codigo_audit,$seccion_codigo, $ejecucion){
	$ClsAud = new ClsAuditoria();
	$ClsEje = new ClsEjecucion();
	if($codigo_audit != "" && $seccion_codigo != ""){
		
		$fecha = date("d/m/Y");
		$arr_data = array();
		// $result = $ClsAud->get_programacion('', NULL, $sedes, NULL, NULL, $fecha, $fecha, NULL, NULL, 1, $usuario);
		$result = $ClsAud->get_pregunta('',$codigo_audit,$seccion_codigo,1);
		if(is_array($result)){
			$i = 0;
			foreach ($result as $row){
				$arr_data[$i]['pre_codigo'] = trim($row['pre_codigo']);
                $arr_data[$i]['pre_auditoria'] = trim($row['pre_auditoria']);
                $arr_data[$i]['pre_seccion'] = trim($row['pre_seccion']);
                $arr_data[$i]['pre_pregunta'] = trim($row['pre_pregunta']);
                $arr_data[$i]['pre_tipo'] = trim($row['pre_tipo']);
                $arr_data[$i]['pre_peso'] = trim($row['pre_peso']);
                $arr_data[$i]['pre_situacion'] = trim($row['pre_situacion']);
				$arr_data[$i]['codigo'] = $row["pre_codigo"];
				$arr_data[$i]['tipo'] = $row["pre_tipo"];
				$arr_data[$i]['peso'] = $row["pre_peso"];
				$arr_data[$i]['pregunta'] = nl2br($row["pre_pregunta"]);
				$arr_data[$i]['respuesta'] = $ClsEje->get_respuesta($ejecucion, $codigo_audit, $row['pre_codigo'],'',$row['']);
				$arr_data[$i]['respuesta'] = $ClsEje->get_respuesta($ejecucion, $codigo_audit, $row['pre_codigo'],'',$row['']);
				$count_photos = $ClsEje->get_fotos('', $ejecucion,$codigo_audit, $row['pre_codigo']);
				$arr_data[$i]['photos'] = (is_array($count_photos)) ? intval(count($count_photos)) : 0;
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

function API_audt($codigo,$usuario, $ejecucion, $programacion){
	$ClsAud = new ClsAuditoria();
	$ClsUsu = new ClsUsuario();
	$ClsEje = new ClsEjecucion();
	$ClsDep = new ClsDepartamento();
	if($codigo != "" && $usuario != ""){
		$arr_data = array();
		/////////// PROGRAMACION /////
		$departamento = array();
		$result = $ClsDep->get_departamento('','',1,1);
		if(is_array($result)){
			$j = 0; foreach($result as $row){
				$result_obs = $ClsEje->get_observaciones_departamento($ejecucion,$row['dep_codigo']);
				$depObservacion = "";
				if(is_array($result_obs)){
					foreach($result_obs as $row_obs){
						$depObservacion = $row_obs["obs_observacion"];
					}	
				}
				$departamento[] = array(
					'nombre' => utf8_decode($row['dep_nombre']),
					'codigo' => $row['dep_codigo'],
					'obs' => $depObservacion,
					'index' => $j
				);
				$j++;
			}
		}

		if (!$ejecucion) {
			$ejecucion = $ClsEje->max_ejecucion();
			$ejecucion++;
			$sql_new_eje = $ClsEje->insert_ejecucion($ejecucion,$codigo,$programacion,$usuario, '');
			$rs = $ClsEje->exec_sql($sql_new_eje);
		}
		// if ($prog) {
			// ejecucion, $codigo,$usuario
			$result = $ClsEje->get_ejecucion($ejecucion,$codigo, $usuario,'','','','','','1,2');
			if(is_array($result)){
				$j = 0;	
				foreach ($result as $row){
					$arr_data[$j]['sed_nombre'] = trim($row['sed_nombre']);
					$arr_data[$j]['dep_nombre'] = trim($row['dep_nombre']);
					$arr_data[$j]['cat_nombre'] = trim($row['cat_nombre']);
					$arr_data[$j]['cat_color'] = trim($row['cat_color']);
					$arr_data[$j]['nombre'] = trim($row['audit_nombre']);
					$arr_data[$j]['pro_date'] = trim($row['pro_fecha'] . ' ' . $row['pro_hora']);
					$arr_data[$j]['dep'] = trim($row['dep_nombre'] . ' ' . $row['pro_hora']);
					$arr_data[$j]['codigo'] = trim($row['audit_codigo']);
					$arr_data[$j]['sede'] = array(
						'nombre' => $row['sed_nombre'],
						'municipio_numero' => $row['sed_municipio'],
						'municipio' => $row['sede_municipio'],
						'departamento' => $row['sed_departamento'],
						'direccion' => $row['sed_direccion'],
						'zona' => $row['sed_zona'],
					);
					$arr_data[$j]['ejecucion'] = $row['eje_codigo'];
					$arr_data[$j]['correos'] = $row['eje_correos'];
					$arr_data[$j]['observaciones'] = $row['pro_observaciones'];
					$arr_data[$j]['departamentos'] = $departamento;
					$arr_data[$j]['firma'] = $row['eje_firma_evaluado'];

					$result_sections = $ClsAud->get_secciones('',$codigo);
					if (is_array($result_sections)) {
						if (is_array($result_sections)) {
							$t = 0; foreach ($result_sections AS $data) {
								$result_respuesta = $ClsEje->get_respuesta($row['eje_codigo'] , $row['audit_codigo'],'','',$data['sec_codigo']);
								$preguntas_total = 0;
								$result_preguntas = $ClsAud->get_pregunta('',$row['audit_codigo'],$data['sec_codigo'],1);
								if(is_array($result_preguntas)){
									foreach ($result_preguntas as $row_preguntas){
										$preguntas_total++;
									}	
								}
								// $count_photos = $ClsEje->get_fotos('', $row['ejecucion_activa'],$row['audit_codigo'], '');
								$result_respuesta_arr = array();
								$pend_section = array();
								$no_section = array();
								$si_section = array();
								$noaplica = array();
								$at = 0;
								$preguntas[$j]['secciones'][$t] = array();
								if (is_array($result_respuesta)) {
									foreach ($result_respuesta AS $answersto) {
										$preguntas[$j]['secciones'][$t][$answersto['resp_pregunta']]++;
										// if ($answersto['resp_aplica'] == 2) {
										// 	$pend_section[$j]['secciones'][$t][$answersto['resp_pregunta']] += 1;
										// }

										if ($answersto['resp_aplica'] == 1) {
											$si_section[$j]['secciones'][$t][$answersto['resp_pregunta']] += 1;
											$ok_section[$j]['secciones'][$t][$answersto['resp_pregunta']] = $answersto['resp_respuesta'];
										} else if ($answersto['resp_aplica'] == 2) {
											$noaplica[$j]['secciones'][$t][$answersto['resp_pregunta']] += 1;
										}

										// if ($answersto['resp_tipo'] == 1) {
										// 	$pend_section[$j]['secciones'][$t][$answersto['resp_pregunta']] += 1;
										// } else if ($answersto['resp_tipo'] == 2) {
										// 	$no_section[$j]['secciones'][$t][$answersto['resp_pregunta']] += 1;
										// } else if ($answersto['resp_tipo'] == 3) {
										// 	$si_section[$j]['secciones'][$t][$answersto['resp_pregunta']] += 1;
										// }
										$result_respuesta_arr[$at]['resp_respuesta'] = $answersto['resp_respuesta'];
										$result_respuesta_arr[$at]['resp_peso'] = $answersto['resp_peso'];
										$at++;
									}
								}
								$preguntasTotal = count($preguntas[$j]['secciones'][$t]);
								$resueltas = count($si_section[$j]['secciones'][$t]);
								$noaplicaCount = count($noaplica[$j]['secciones'][$t]);
								$sitotal = count($si_section[$j]['secciones'][$t]);
								$pendientes_number = $preguntas_total - ($resueltas + $noaplicaCount);
								$arr_data[$j]['secciones'][		$t] = $data;
								// $arr_data[$j]['secciones'][$t]['photos'] = $count_photos;
								$arr_data[$j]['secciones'][$t]['count_allanswers'] = array(
									'preguntas' => $preguntas[$j]['secciones'][$t],
									'respuestas' => array(
										'pend' => ($pendientes_number<=0 ? 0 : $pendientes_number),
										'no' => $noaplicaCount,
										'si' => $sitotal,
									)
								);
								$arr_data[$j]['secciones'][$t]['respuestas'] = array(
									'no' => 0,
									'pend' => 0,
									'na' => 0
								);
								$arr_data[$j]['secciones'][$t]['datos'] = $result_respuesta_arr;
								$arr_data[$j]['secciones'][$t]['num_datos'] = count($result_respuesta);
								// $arr_data[$j]['secciones'][$t]['count'] = $result_respuesta;
								$t++;
							}
						}
					}
					$j++;
				}
			}else{
				// nothing
			}
		// }
		
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

function API_firma () {
	///////////// A CARPETAS //////////////////////
	// obtenemos los datos del archivo
    $tamano = $_FILES["photo"]['size'];
    $archivo = $_FILES["photo"]['name'];
	$ejecucion = $_REQUEST["ejecucion"];
	$firma = $_REQUEST['firma'];
	$ClsEje = new ClsEjecucion();
	
	// Upload
	if($archivo != "") {
		$stringFirma = str_shuffle($ejecucion.uniqid());

		if($firma == 1){
			$sql = $ClsEje->firma_evaluador_ejecucion($ejecucion,$stringFirma);
		}else if($firma == 2){
			$sql = $ClsEje->firma_evaluado_ejecucion($ejecucion,$stringFirma);
		}
		$rs = $ClsEje->exec_sql($sql);
		if($rs == 1){
			// guardamos el archivo a la carpeta files
			$destino =  "../../CONFIG/Fotos/AUDFIRMAS/".$stringFirma.".jpg";
			if (move_uploaded_file($_FILES['photo']['tmp_name'],$destino)) {
				$msj = "Imagen $archivo subida exitosamente...!"; 
				$status = 1;
				//////////// -------- Convierte todas las imagenes a JPEG
				// Abrimos una Imagen PNG
				$mime_type = mime_content_type($destino);
				//Valida si es un PNG
				if($mime_type == "image/png"){
					$imagen = imagecreatefrompng($destino); // si es, convierte a JPG
					imagejpeg($imagen,$destino,100); // Creamos la Imagen JPG a partir de la PNG u otra que venga
				}
				/// redimensionando
				$image = new ImageResize($destino);
				$image->resizeToWidth(300);
				$image->save($destino);
			} else {
				$msj = "Error al subir el archivo"; $status = 0;
			}
		}else{
			$msj = "Error en la transacci\u00F3n al cargar a la Base de Datos"; $status = 0;
		}	
	}else{
		$msj = "Archivo vacio. $ejecucion, $pregunta";  $status = 0;
	}
	
	$arr_data = array(
		"status" => $status,
		"img" => $arrFoto,
		"message" => $msj
	);
	echo json_encode($arr_data);
}

function API_fotos () {

	///////////// A CARPETAS //////////////////////
	// obtenemos los datos del archivo
    $tamano = $_FILES["photo"]['size'];
    $archivo = $_FILES["photo"]['name'];
	$auditoria = $_REQUEST["auditoria"];
	$ejecucion = $_REQUEST["ejecucion"];
	$pregunta = $_REQUEST["pregunta"];
	$ClsEje = new ClsEjecucion();
	
	// Upload
	if($archivo != "") {
		$fotCodigo = $ClsEje->max_foto($auditoria,$pregunta,$ejecucion);
		$fotCodigo++;
		$stringFoto = str_shuffle($auditoria.$pregunta.$ejecucion.uniqid());
		$sql = $ClsEje->insert_foto($fotCodigo,$auditoria,$pregunta,$ejecucion,$stringFoto);
		$rs = $ClsEje->exec_sql($sql);
		if($rs == 1){
			// guardamos el archivo a la carpeta files
			$destino =  "../../CONFIG/Fotos/AUDITORIA/".$stringFoto.".jpg";
			if (move_uploaded_file($_FILES['photo']['tmp_name'],$destino)) {
				$msj = "Imagen $archivo subida exitosamente...!"; 
				$status = 1;
				//////////// -------- Convierte todas las imagenes a JPEG
				// Abrimos una Imagen PNG
				$mime_type = mime_content_type($destino);
				//Valida si es un PNG
				if($mime_type == "image/png"){
					$imagen = imagecreatefrompng($destino); // si es, convierte a JPG
					imagejpeg($imagen,$destino,100); // Creamos la Imagen JPG a partir de la PNG u otra que venga
				}
				/// redimensionando
				$image = new ImageResize($destino);
				$image->resizeToWidth(300);
				$image->save($destino);
			} else {
				$msj = "Error al subir el archivo"; $status = 0;
			}
		}else{
			$msj = "Error en la transacci\u00F3n al cargar a la Base de Datos"; $status = 0;
		}	
	}else{
		$msj = "Archivo vacio. $ejecucion, $pregunta";  $status = 0;
	}
	
	$arr_data = array(
		"status" => $status,
		"img" => $arrFoto,
		"message" => $msj
	);
	echo json_encode($arr_data);
} 

// AJAX SAVE THE INFO

function Responder_Ponderacion($auditoria,$pregunta,$ejecucion,$seccion,$tipo,$peso,$aplica,$ponderacion, $observacion = ''){
   // $respuesta = new xajaxResponse();
   $ClsEje = new ClsEjecucion();
   
   if($auditoria != "" && $pregunta != "" && $ejecucion != "" && $tipo != ""){
      	$sql = $ClsEje->insert_respuesta($auditoria,$pregunta,$ejecucion,$seccion,$tipo,$peso,$aplica,$ponderacion, $observacion);
		$rs = $ClsEje->exec_sql($sql);
      if($rs == 1){
      	return 'Correcto';
      }else{
  		return 'Error en la transaccion';
      }	
	} else {
		return 'Hace falta un dato';
	}

	return $respuesta;
}

function Responder_Texto($auditoria,$pregunta,$ejecucion,$observacion){
   // $respuesta = new xajaxResponse();
   $ClsEje = new ClsEjecucion();
   
   if($auditoria != "" && $pregunta != "" && $ejecucion != ""){
      $sql = $ClsEje->update_respuesta($auditoria,$pregunta,$ejecucion,$seccion,$observacion);
		$rs = $ClsEje->exec_sql($sql);
      //$observacion->alert("$sql");
      if($rs == 1){
      	return 'Correcto';
         // return $respuesta;
      }else{
      	$respuesta = 'Error en la transaccion';
         // $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      }	
	}

	return $respuesta;
}

function Observacion_Departamento($ejecucion,$departamento,$observacion){
   // $respuesta = new xajaxResponse();
   $ClsEje = new ClsEjecucion();
   //$respuesta->alert("$ejecucion,$departamento,$observacion");
   if($ejecucion != "" && $departamento != ""){
      $sql = $ClsEje->insert_observaciones_departamento($ejecucion,$departamento,$observacion);
		$rs = $ClsEje->exec_sql($sql);
      //$respuesta->alert("$sql");
      if($rs == 1){
      	return 'Correcto';
         // return $respuesta;
      }else{
      	$respuesta = 'Error en la transaccion';
         // $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      }	
	}

	return $respuesta;
}

function Cerrar_Ejecucion($ejecucion,$nota,$correos,$responsable,$obs){
   // $respuesta = new xajaxResponse();
   $ClsEje = new ClsEjecucion();
   $ClsAud = new ClsAuditoria();
   //--
   $responsable = trim($responsable);
   $obs = trim($obs);
   $responsable = $responsable;
   $obs = utf8_encode($obs);
   $responsable = utf8_decode($responsable);
   $obs = utf8_decode($obs);
   /////// Informacion de la ejecucion
   $result = $ClsEje->get_ejecucion($ejecucion);
   if(is_array($result)){
      foreach ($result as $row){
         $codigo_audit = trim($row["eje_auditoria"]);
			$codigo_progra = trim($row["eje_programacion"]);
         $tipo = trim($row["audit_ponderacion"]);
      }	
   }
   
	if($ejecucion != "" && $codigo_progra != ""){
      $sql = $ClsEje->cerrar_ejecucion($ejecucion,$responsable,$nota,$obs);
      $sql.= $ClsEje->correos_ejecucion($ejecucion,$correos);
      $sql.= $ClsAud->cambia_situacion_programacion($codigo_progra,2);
		$rs = $ClsEje->exec_sql($sql);
      //$respuesta->alert("$ejecucion");
      if($rs == 1){
		 mail_usuario($ejecucion);
      	return 'Correcto';
         // $respuesta->script('swal("Excelente!", "Auditor\u00EDa cerrada satisfactoriamente...", "success").then((value)=>{ window.location.href="FRMejecutar.php" });');
      }else{
      	$respuesta = 'Error en la transaccion';
         // $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      }	
	}

	return $respuesta;
}

/////////////////////////// INFORME FINAL DE AUDITORIA ///////////////////////////////////
function Responder_Fecha($auditoria,$pregunta,$solucion,$fecha){
   // $respuesta = new xajaxResponse();
   $ClsPla = new ClsPlan();
   
   if($auditoria != "" && $pregunta != "" && $solucion != "" && $fecha != ""){
      $sql = $ClsPla->insert_fecha($auditoria,$pregunta,$solucion,$fecha);
		$rs = $ClsPla->exec_sql($sql);
      //$respuesta->alert("$sql");
      if($rs == 1){
      	return 'Correcto';
         // return $respuesta;
      }else{
      	$respuesta = 'Error en la transaccion';
         // $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      }	
	}

	return $respuesta;
}

function Responder_Solucion($auditoria,$pregunta,$solucion,$observacion){
   // $respuesta = new xajaxResponse();
   $ClsPla = new ClsPlan();
   
   if($auditoria != "" && $pregunta != "" && $solucion != ""){
      $sql = $ClsPla->insert_respuesta($auditoria,$pregunta,$solucion,$observacion);
		$rs = $ClsPla->exec_sql($sql);
      //$respuesta->alert("$sql");
      if($rs == 1){
      	return 'Correcto';
         // return $respuesta;
      }else{
      	$respuesta = 'Error en la transaccion';
         // $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      }	
	}

	return $respuesta;
}

function Responder_Responsable($auditoria,$pregunta,$solucion,$responsable){
   // $respuesta = new xajaxResponse();
   $ClsPla = new ClsPlan();
   
   if($auditoria != "" && $pregunta != "" && $solucion != ""){
      $sql = $ClsPla->insert_responsable($auditoria,$pregunta,$solucion,$responsable);
		$rs = $ClsPla->exec_sql($sql);
      //$respuesta->alert("$sql");
      if($rs == 1){
      	return 'Correcto';
         // return $respuesta;
      }else{
      	$respuesta = 'Error en la transaccion';
         // $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      }	
	}

	return $respuesta;
}

function Responder_Situacion($auditoria,$pregunta,$solucion){
   // $respuesta = new xajaxResponse();
   $ClsPla = new ClsPlan();
   
   if($auditoria != "" && $pregunta != "" && $solucion != ""){
      $sql = $ClsPla->situacion_responsable($auditoria,$pregunta,$solucion,2);
		$rs = $ClsPla->exec_sql($sql);
      //$respuesta->alert("$sql");
      if($rs == 1){
         $fsis = date("d/m/Y H:i:s");
         $respuesta->assign("status$pregunta","value","Solucionado");
         $respuesta->assign("solucionado$pregunta","value",$fsis);
      	return 'Correcto';
         // return $respuesta;
      }else{
      	$respuesta = 'Error en la transaccion';
         // $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      }	
	}

	return $respuesta;
}

function Cerrar_Plan($plan,$obs){
   // $respuesta = new xajaxResponse();
   $ClsPla = new ClsPlan();
   //--
   $obs = trim($obs);
   $obs = utf8_encode($obs);
   $obs = utf8_decode($obs);
   /////// Informacion de la ejecucion
	if($plan != ""){
      $sql = $ClsPla->update_plan($plan,$obs);
      $sql.= $ClsPla->cambia_situacion_plan($plan,2);
      $rs = $ClsPla->exec_sql($sql);
      //$respuesta->alert("$sql");
      if($rs == 1){
      	return 'Correcto';
         // $respuesta->script('swal("Excelente!", "Plan de Auditor\u00EDa guardado satisfactoriamente...", "success").then((value)=>{ window.location.href="FRMplanes.php" });');
      }else{
      	$respuesta = 'Error en la transaccion';
         // $respuesta->script('swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ cerrar(); });');
      }	
	}

	return $respuesta;
}