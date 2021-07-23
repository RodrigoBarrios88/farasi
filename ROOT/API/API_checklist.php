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
		case "listas":
			$usuario = $_REQUEST["usuario"];
			API_listas($usuario);
			break;
		case "new_revision":
			$lista = $_REQUEST["lista"];
			$programacion = $_REQUEST["programacion"];
			$usuario = $_REQUEST["usuario"];
			$fecha = $_REQUEST["fecha"];
			$hora = $_REQUEST["hora"];
			API_new_revision($lista,$programacion,$usuario,$fecha,$hora);
			break;
		case "responder":
			$revision = $_REQUEST["revision"];
			$lista = $_REQUEST["lista"];
			$pregunta = $_REQUEST["pregunta"];
			$respuesta = $_REQUEST["respuesta"];
			API_responder($revision,$lista,$pregunta,$respuesta);
			break;
		case "cerrar":
			$revision = $_REQUEST["revision"];
			$observaciones = $_REQUEST["observaciones"];
			$fecha = $_REQUEST["fecha"];
			$hora = $_REQUEST["hora"];
			API_cerrar($revision,$obs,$fecha,$hora);
			break;
		case "historiales":
			$tipo = $_REQUEST["tipo"];
			$usuario = $_REQUEST["usuario"];
			$desde = $_REQUEST["desde"];
			$hasta = $_REQUEST["hasta"];
			$sede = $_REQUEST["sede"];
			$categoria = $_REQUEST["categoria"];
			API_historiales($tipo,$usuario,$desde,$hasta,$sede,$categoria);
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

function API_listas($usuario){
	$ClsLis = new ClsLista();
	$ClsUsu = new ClsUsuario();
	if($usuario != ""){
			$categorias ="";
			$result = $ClsUsu->get_usuario_categoria("",$usuario,"");
			if(is_array($result)){
				foreach($result as $row){
					$categorias.= $row["cus_categoria"].",";
				}
				
				$categorias = substr($categorias,0,-1);
			}
			$result = $ClsUsu->get_usuario_sede("",$usuario,"");
			if(is_array($result)){
				$sedes = "";
				foreach($result as $row){
					$sedes.= trim($row["sus_sede"]).",";
				}
				$sedes = substr($sedes,0,-1);
			}
			/////////// PROGRAMACION /////
			$dia = date("N");
			$result = $ClsLis->get_programacion('', '', $sede, '', '', $categorias, $dia, date("H:i"), 1, '', date("d/m/Y"), date("d/m/Y"));
			$i = 0;
			if(is_array($result)){
				$j = 0;	
				foreach ($result as $row){
					$ejecutada = trim($row["revision_ejecutada"]);
					$activa = trim($row["revision_activa"]);
					//echo "$ejecutada, $activa <br>";
					if($ejecutada == ""){
						//codigo area
						$arr_data[$i]['codigo_area'] = Agrega_Ceros($row["are_codigo"]);
						//codigo lista
						$arr_data[$i]['codigo_lista'] = trim($row["list_codigo"]);
						//codigo programacion
						$arr_data[$i]['codigo_programacion'] = trim($row["pro_codigo"]);
						//categoria
						$arr_data[$i]['categoria'] = trim($row["cat_nombre"]);
						//lista
						$arr_data[$i]['nombre_lista'] = trim($row["list_nombre"]);
						$arr_data[$i]['requiere_firma'] = ($row["list_firma"] == 1)?true:false;
						$arr_data[$i]['requiere_foto'] = ($row["list_fotos"] == 1)?true:false;
						//programacion
						$arr_horario[0]['sede'] = trim($row["sed_nombre"]);
						$arr_horario[0]['sector'] = trim($row["sec_nombre"]);
						$arr_horario[0]['area'] = trim($row["are_nombre"]);
						$arr_horario[0]['nivel'] = trim($row["are_nivel"]);
						$arr_horario[0]['hora_ini'] = trim($row["pro_hini"]);
						$arr_horario[0]['hora_fin'] = trim($row["pro_hfin"]);
						$arr_horario[0]['observaciones'] = trim($row["pro_observaciones"]);
						//dias
						$C = 0;
						for($z = 1; $z<=7; $z++){
							if(intval($row["pro_dia_$z"]) == 1){
								$arr_dias[$C] = $z;
								$C++;
							}
						}
						$arr_horario[0]['dias'] = $arr_dias;
						//--
						$arr_data[$i]['programacion'] = $arr_horario;
						//////////////// PREGUNTAS ////////////////////
						$arr_preguntas = array();
						$lista = trim($row["list_codigo"]);
						$result_preguntas = $ClsLis->get_pregunta('',$lista,'',1);
						$j = 0;
						if(is_array($result_preguntas)){
							foreach($result_preguntas as $row_preguntas){
								//codigo
								$pregunta_codigo = trim($row_preguntas["pre_codigo"]);
								$arr_preguntas[$j]['pregunta_codigo'] = $pregunta_codigo;
								//texto de la pregunta
								$arr_preguntas[$j]['pregunta_texto'] = trim($row_preguntas["pre_pregunta"]);
								//respuesta
								$arr_preguntas[$j]['respuesta'] = "2";
								//--
								$j++;
							}
						}else{
							$arr_preguntas[0]['pregunta_codigo'] = 0;
							$arr_preguntas[0]['pregunta_texto'] = "";
							$arr_preguntas[0]['respuesta'] = "";
						}
						//--
						$arr_data[$i]['preguntas'] = $arr_preguntas;
						$i++;
					}
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


function API_scan($area,$usuario){
	$ClsLis = new ClsLista();
	$ClsRev = new ClsRevision();
	$ClsUsu = new ClsUsuario();
	if($area != "" && $usuario != ""){
	    $categorias ="";
	    $result = $ClsUsu->get_usuario_categoria("",$usuario,"");
		if(is_array($result)){
			foreach($result as $row){
				$categorias.= $row["cus_categoria"].",";
			}
			$categorias = substr($categorias,0,-1);
		}
		$dia = date("N");
		$count = $ClsLis->count_programacion('','','','',$area,$categorias,$dia,date("H:i"),1);
		if($count == 1){ ///////// SI SOLO VIENE 1 PROGRAMACION EN LA MISMA AREA
			$result = $ClsLis->get_programacion('','','','',$area,$categorias,$dia,date("H:i"),1);
			if(is_array($result)){
				$i = 0;
				foreach ($result as $row){
					//codigo
					$codigo_lista = trim($row["list_codigo"]);
					$revision = $row["revision_activa"];
					$programacion = $row["pro_codigo"];
				}
				if($revision != ""){
					$result = $ClsRev->get_revision($revision,'','','','','','',$fecha,$fecha,1);
					if(is_array($result)){
						$i = 0;	
						foreach ($result as $row){
							///codigo
							$codigo_lista = trim($row["list_codigo"]);
							$revision = trim($row["rev_codigo"]);
							$sede = trim($row["rev_sede"]);
							$arr_data[$i]['codigo_lista'] = $codigo_lista;
							$arr_data[$i]['codigo_revision'] = $revision;
							$arr_data[$i]['codigo_programacion'] = trim($row["pro_codigo"]);
							//ubicacion
							//categoria
							$arr_data[$i]['categoria'] = trim($row["cat_nombre"]);
							//lista
							$arr_data[$i]['nombre_lista'] = trim($row["list_nombre"]);
							$arr_data[$i]['requiere_firma'] = ($row["list_firma"] == 1)?true:false;
							$arr_data[$i]['requiere_foto'] = ($row["list_fotos"] == 1)?true:false;
							//--
							$i++;
						}
						$i--; //quita la ultima vuelta
						/////////// PROGRAMACION /////
						$arr_horario = array();
						$dia = date("N");
						$result = $ClsLis->get_programacion($programacion,$codigo_lista,$sede,'','','',$dia,date("H:i"));
						if(is_array($result)){
							$j = 0;	
							foreach ($result as $row){
								//ubicacion
								$arr_horario[$j]['sede'] = trim($row["sed_nombre"]);
								$arr_horario[$j]['sector'] = trim($row["sec_nombre"]);
								$arr_horario[$j]['area'] = trim($row["are_nombre"]);
								$arr_horario[$j]['nivel'] = trim($row["are_nivel"]);
								//--
								$arr_horario[$j]['hora_ini'] = trim($row["pro_hini"]);
								$arr_horario[$j]['hora_fin'] = trim($row["pro_hfin"]);
								$arr_horario[$j]['observaciones'] = trim($row["pro_observaciones"]);
								$j++;
							}
						}else{
							$arr_horario[0]['sede'] = "";
							$arr_horario[0]['sector'] = "";
							$arr_horario[0]['area'] = "";
							$arr_horario[0]['nivel'] = "";
							$arr_horario[0]['hora_ini'] = "";
							$arr_horario[0]['hora_fin'] = "";
							$arr_horario[0]['observaciones'] = "";
						}
						$arr_data[$i]['programacion'] = $arr_horario;
						
						$msj_payload = "ya esta abierta una revision con estos parametros...";
					}else{
						//devuelve un mensaje de manejo de errores
						$payload = array(
							"status" => false,
							"data" => [],
							"message" => "El horario de esta revision esta fura de rango en este momento...");
							echo json_encode($payload);
							
						return;
					}
				}else{
					$dia = date("N");
					$result = $ClsLis->get_programacion($programacion,$codigo_lista);
					if(is_array($result)){
						////////////////////////// APERTURA UNA REVISION ///////////////////////////////////
						$revision = $ClsRev->max_revision();
						$revision++; /// Maximo codigo de Lista
						$sql = "";
						$sql = $ClsRev->insert_revision($revision,$codigo_lista,$programacion,$usuario,'');
						///PREGUNTAS
						$result_preguntas = $ClsLis->get_pregunta('',$codigo_lista,'','','','',1);
						if(is_array($result_preguntas)){
							foreach ($result_preguntas as $row_pregunta){
								$pregunta_codigo = trim($row_pregunta["pre_codigo"]);
								$sql.= $ClsRev->insert_respuesta($revision,$codigo_lista,$pregunta_codigo,2);
							}
						} // inicializa las preguntas con un NO
						$rs = $ClsRev->exec_sql($sql);
						if($rs == 1){
							$i = 0;
							$arr_horario = array();
							foreach ($result as $row){
								///codigo
								$codigo_lista = trim($row["list_codigo"]);
								$arr_data[$i]['codigo_lista'] = $codigo_lista;
								$arr_data[$i]['codigo_revision'] = $revision;
								$arr_data[$i]['codigo_programacion'] = $programacion;
								//categoria
								$arr_data[$i]['categoria'] = trim($row["cat_nombre"]);
								//lista
								$arr_data[$i]['nombre_lista'] = trim($row["list_nombre"]);
								$arr_data[$i]['requiere_firma'] = ($row["list_firma"] == 1)?true:false;
								$arr_data[$i]['requiere_foto'] = ($row["list_fotos"] == 1)?true:false;
								//ubicacion
								$arr_horario[0]['sede'] = trim($row["sed_nombre"]);
								$arr_horario[0]['sector'] = trim($row["sec_nombre"]);
								$arr_horario[0]['area'] = trim($row["are_nombre"]);
								$arr_horario[0]['nivel'] = trim($row["are_nivel"]);
								//--
								$arr_horario[0]['hora_ini'] = trim($row["pro_hini"]);
								$arr_horario[0]['hora_fin'] = trim($row["pro_hfin"]);
								$arr_horario[0]['observaciones'] = trim($row["pro_observaciones"]);
								$i++;
							}
							$i--; //quita la ultima vuelta
							$arr_data[$i]['programacion'] = $arr_horario;
							$msj_payload = "Apertura de revisión existosa...";
						}else{
							//devuelve un mensaje de manejo de errores
							$payload = array(
								"status" => false,
								"data" => [],
								"message" => "Error en la apertura de la revision");
								echo json_encode($payload);
							return;
						}
					}	
				}
				$payload = array(
					"status" => true,
					"data" => $arr_data,
					"message" => $msj_payload);
	
				echo json_encode($payload);
				
			}else{
				//devuelve un mensaje de manejo de errores
				$payload = array(
					"status" => "false",
					"data" => [],
					"message" => "No hay checklist pendientes de abrir en esta área, en este horario...");
					echo json_encode($payload);
			}
		}else if($count > 1){ ///////// SI VIENEN VARIAS PROGRAMACIONES EN LA MISMA AREA
			$dia = date("N");
			$result = $ClsLis->get_programacion('','','','',$area,$categorias,$dia,date("H:i"),1,'',date("d/m/Y"),date("d/m/Y"));
			if(is_array($result)){
				$i = 0;	
				foreach ($result as $row){
					$ejecutada = trim($row["revision_ejecutada"]);
					$activa = trim($row["revision_activa"]);
					//codigo
					$codigo_lista = trim($row["list_codigo"]);
					$revision = $row["revision_activa"];
					$programacion = $row["pro_codigo"];
					if($ejecutada == ""){
						$i++;
					}	
				}
				
				if($i > 1){
					$i = 0;	
					foreach ($result as $row){
						$ejecutada = trim($row["revision_ejecutada"]);
						$activa = trim($row["revision_activa"]);
						$arr_preguntas = array();
						//echo "$ejecutada, $activa <br>";
						if($ejecutada == ""){
							//codigo area
							$arr_data[$i]['codigo_area'] = trim($row["are_codigo"]);
							//codigo lista
							$arr_data[$i]['codigo_lista'] = trim($row["list_codigo"]);
							//codigo programacion
							$arr_data[$i]['codigo_programacion'] = trim($row["pro_codigo"]);
							///revision
							$arr_data[$i]['codigo_revision'] = trim($row["revision_activa"]);
							$arr_data[$i]['revision_activa'] = trim($row["revision_activa"]);
							$arr_data[$i]['revision_ejecutada'] = trim($row["revision_ejecutada"]);
							//categoria
							$arr_data[$i]['categoria'] = trim($row["cat_nombre"]);
							//lista
							$arr_data[$i]['nombre_lista'] = trim($row["list_nombre"]);
							$arr_data[$i]['requiere_firma'] = ($row["list_firma"] == 1)?true:false;
							$arr_data[$i]['requiere_foto'] = ($row["list_fotos"] == 1)?true:false;
							//programacion
							$arr_horario[0]['sede'] = trim($row["sed_nombre"]);
							$arr_horario[0]['sector'] = trim($row["sec_nombre"]);
							$arr_horario[0]['area'] = trim($row["are_nombre"]);
							$arr_horario[0]['nivel'] = trim($row["are_nivel"]);
							$arr_horario[0]['hora_ini'] = trim($row["pro_hini"]);
							$arr_horario[0]['hora_fin'] = trim($row["pro_hfin"]);
							$arr_horario[0]['observaciones'] = trim($row["pro_observaciones"]);
							//dias
							$arr_dias[0]['dia_1'] = trim($row["pro_dia_1"]);
							$arr_dias[1]['dia_2'] = trim($row["pro_dia_2"]);
							$arr_dias[2]['dia_3'] = trim($row["pro_dia_3"]);
							$arr_dias[3]['dia_4'] = trim($row["pro_dia_4"]);
							$arr_dias[4]['dia_5'] = trim($row["pro_dia_5"]);
							$arr_dias[5]['dia_6'] = trim($row["pro_dia_6"]);
							$arr_dias[6]['dia_7'] = trim($row["pro_dia_7"]);
							//$arr_horario[0]['dias'] = $arr_dias;
							//--
							$arr_data[$i]['programacion'] = $arr_horario;
							//////////////// PREGUNTAS ////////////////////
							$lista = trim($row["list_codigo"]);
							$result_preguntas = $ClsLis->get_pregunta('',$lista,'',1);
							$j = 0;
							if(is_array($result_preguntas)){
								foreach($result_preguntas as $row_preguntas){
									//codigo
									$pregunta_codigo = trim($row_preguntas["pre_codigo"]);
									$arr_preguntas[$j]['pregunta_codigo'] = $pregunta_codigo;
									//texto de la pregunta
									$arr_preguntas[$j]['pregunta_texto'] = trim($row_preguntas["pre_pregunta"]);
									//respuesta
									$arr_preguntas[$j]['respuesta'] = "2";
									//--
									$j++;
								}
							}else{
								$arr_preguntas[0]['pregunta_codigo'] = 0;
								$arr_preguntas[0]['pregunta_texto'] = "";
								$arr_preguntas[0]['respuesta'] = "";
							}
							//--
							$arr_data[$i]['preguntas'] = $arr_preguntas;
							$i++;
						}
					}
					$payload = array(
						"status" => true,
						"data" => $arr_data,
						"message" => "");
					echo json_encode($payload);
					
				}else if($i == 1){
					if($activa == ""){
						////////////////////////// APERTURA UNA REVISION ///////////////////////////////////
						$revision = $ClsRev->max_revision();
						$revision++; /// Maximo codigo de Lista
						$sql = "";
						$sql = $ClsRev->insert_revision($revision,$codigo_lista,$programacion,$usuario,'');
						///PREGUNTAS
						$result_preguntas = $ClsLis->get_pregunta('',$codigo_lista,'','','','',1);
						if(is_array($result_preguntas)){
							foreach ($result_preguntas as $row_pregunta){
								$pregunta_codigo = trim($row_pregunta["pre_codigo"]);
								$sql.= $ClsRev->insert_respuesta($revision,$codigo_lista,$pregunta_codigo,2);
							}
						} // inicializa las preguntas con un NO
						$rs = $ClsRev->exec_sql($sql);
					}
					
					$i = 0;
					$result = $ClsLis->get_programacion($programacion);
					foreach ($result as $row){
						$ejecutada = trim($row["revision_ejecutada"]);
						$activa = trim($row["revision_activa"]);
						//codigo area
						$arr_data[$i]['codigo_area'] = trim($row["are_codigo"]);
						//codigo lista
						$arr_data[$i]['codigo_lista'] = trim($row["list_codigo"]);
						//codigo programacion
						$arr_data[$i]['codigo_programacion'] = trim($row["pro_codigo"]);
						///revision
						$arr_data[$i]['codigo_revision'] = ($revision == "")?trim($row["revision_activa"]):$revision;
						$arr_data[$i]['revision_activa'] = (trim($row["revision_activa"]) == "")?$revision:trim($row["revision_activa"]);
						$arr_data[$i]['revision_ejecutada'] = trim($row["revision_ejecutada"]);
						//categoria
						$arr_data[$i]['categoria'] = trim($row["cat_nombre"]);
						//lista
						$arr_data[$i]['nombre_lista'] = trim($row["list_nombre"]);
						$arr_data[$i]['requiere_firma'] = ($row["list_firma"] == 1)?true:false;
						$arr_data[$i]['requiere_foto'] = ($row["list_fotos"] == 1)?true:false;
						//programacion
						$arr_horario[0]['sede'] = trim($row["sed_nombre"]);
						$arr_horario[0]['sector'] = trim($row["sec_nombre"]);
						$arr_horario[0]['area'] = trim($row["are_nombre"]);
						$arr_horario[0]['nivel'] = trim($row["are_nivel"]);
						$arr_horario[0]['hora_ini'] = trim($row["pro_hini"]);
						$arr_horario[0]['hora_fin'] = trim($row["pro_hfin"]);
						$arr_horario[0]['observaciones'] = trim($row["pro_observaciones"]);
						//dias
						$arr_dias[0]['dia_1'] = trim($row["pro_dia_1"]);
						$arr_dias[1]['dia_2'] = trim($row["pro_dia_2"]);
						$arr_dias[2]['dia_3'] = trim($row["pro_dia_3"]);
						$arr_dias[3]['dia_4'] = trim($row["pro_dia_4"]);
						$arr_dias[4]['dia_5'] = trim($row["pro_dia_5"]);
						$arr_dias[5]['dia_6'] = trim($row["pro_dia_6"]);
						$arr_dias[6]['dia_7'] = trim($row["pro_dia_7"]);
						//$arr_horario[0]['dias'] = $arr_dias;
						//--
						$arr_data[$i]['programacion'] = $arr_horario;
						$i++;
					}
					
					$payload = array(
						"status" => true,
						"data" => $arr_data,
						"message" => "");
					echo json_encode($payload);
					
				}else{
					$payload = array(
						"status" => false,
						"data" => [],
						"message" => "Todas las listas programadas para este horario ya fueron ejecutadas...");
					echo json_encode($payload);
				}
				
			}else{
				$payload = array(
					"status" => false,
					"data" => [],
					"message" => "Todas las listas programadas para este horario ya fueron ejecutadas...");
				echo json_encode($payload);
			}
		}else{
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => "false",
				"data" => [],
				"message" => "No hay checklist pendientes de abrir en esta área, en este horario...");
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



function API_preguntas($revision,$lista){
	$ClsRev = new ClsRevision();
	$ClsLis = new ClsLista();
	
	if($revision != "" && $lista != ""){
		$result = $ClsLis->get_pregunta('',$lista,'',1);
		if(is_array($result)){
			$i = 0;
			foreach ($result as $row){
				//codigo
				$pregunta_codigo = trim($row["pre_codigo"]);
				$arr_data[$i]['pregunta_codigo'] = $pregunta_codigo;
				//texto de la pregunta
				$arr_data[$i]['pregunta_texto'] = trim($row["pre_pregunta"]);
				//respuesta
				$result_respuesta = $ClsRev->get_respuesta($revision,$lista,$pregunta_codigo);
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


function API_new_revision($lista,$programacion,$usuario,$fecha = '',$hora = ''){
	$ClsLis = new ClsLista();
	$ClsRev = new ClsRevision();
	$ClsUsu = new ClsUsuario();
	if($lista != "" && $programacion != "" && $usuario != ""){
		////////////////////////// APERTURA UNA REVISION ///////////////////////////////////
		$revision = $ClsRev->max_revision();
		$revision++; /// Maximo codigo de Lista
		$sql = "";
		$sql = $ClsRev->insert_revision($revision,$lista,$programacion,$usuario,'',"$fecha $hora");
		///PREGUNTAS
		$result_preguntas = $ClsLis->get_pregunta('',$lista,'','','','',1);
		if(is_array($result_preguntas)){
			foreach ($result_preguntas as $row_pregunta){
				$pregunta_codigo = trim($row_pregunta["pre_codigo"]);
				$sql.= $ClsRev->insert_respuesta($revision,$lista,$pregunta_codigo,2);
			}
		} // inicializa las preguntas con un NO
		$rs = $ClsRev->exec_sql($sql);
		if($rs == 1){
			$arr_data[0]['revison'] = $revision;
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => true,
				"data" => $arr_data,
				"message" => "Revision aperturada satisfactoriamente...");
				echo json_encode($payload);
		}else{
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => false,
				"data" => [],
				"message" => "Error en la apertura de la revision");
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



function API_responder($revision,$lista,$pregunta,$respuesta){
	$ClsRev = new ClsRevision();

	if($revision != "" && $lista != "" && $pregunta != "" && $respuesta != ""){
		$sql = $ClsRev->insert_respuesta($revision,$lista,$pregunta,$respuesta);
		$rs = $ClsRev->exec_sql($sql);
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
				 "message" => "Error en la transacción...");
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


function API_cerrar($revision,$obs,$fecha = '',$hora = ''){
	$ClsRev = new ClsRevision();

	if($revision != ""){
		$obs = trim($obs);
		$obs = utf8_encode($obs);
		$obs = trim($obs);
		
		$sql = $ClsRev->cerrar_revision($revision,$obs,"$fecha $hora");
		$rs = $ClsRev->exec_sql($sql);
		if($rs == 1){
			$payload = array(
				 "status" => true,
				 "data" => [],
				 "message" => "Revisión cerrada exitosa!");
				 echo json_encode($payload);
		}else{
			//devuelve un mensaje de manejo de errores
		 	$payload = array(
				 "status" => false,
				 "data" => [],
				 "message" => "Error en la transacción...");
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



function API_historiales($tipo,$usuario,$desde,$hasta,$sede,$categoria){
	$ClsRev = new ClsRevision();
	$ClsLis = new ClsLista();
	
	if($tipo != "" && $usuario != ""){
		$desde = ($desde == "")?date("d/m/Y"):$desde;
		$hasta = ($hasta == "")?date("d/m/Y"):$hasta;
		$usuario = ($tipo == 1)?'':$usuario;
		$result = $ClsRev->get_revision('','',$usuario,$sede,'','',$categoria,$desde,$hasta,'1,2');
		if(is_array($result)){
			$i = 0;
			foreach ($result as $row){
				$revision = trim($row["rev_codigo"]);
				$codigo_lista = trim($row["list_codigo"]);
				$sede = trim($row["rev_sede"]);
				//codigo
				$arr_data[$i]['codigo_lista'] = $codigo_lista;
				$arr_data[$i]['codigo_revision'] = $revision;
				//categoria
				$arr_data[$i]['categoria'] = trim($row["cat_nombre"]);
				//lista
				$arr_data[$i]['nombre_lista'] = trim($row["list_nombre"]);
				$arr_data[$i]['requiere_firma'] = trim($row["list_firma"]);
				$arr_data[$i]['requiere_foto'] = trim($row["list_fotos"]);
				$strFirma = trim($row["rev_firma"]);
				//--
				$fecha_inicio = trim($row["rev_fecha_inicio"]);
				$fecha_inicio = cambia_fechaHora($fecha_inicio);
				$arr_data[$i]['fecha_inicio'] = $fecha_inicio;
				$fecha_finaliza = trim($row["rev_fecha_final"]);
				$fecha_finaliza = cambia_fechaHora($fecha_finaliza);
				$arr_data[$i]['fecha_final'] = $fecha_finaliza;
				$obs = trim($row["rev_observaciones"]);
				$arr_data[$i]['observaciones'] = $obs;
				///-- Firmas --///
				if(file_exists('../../CONFIG/Fotos/FIRMAS/'.$strFirma.'.jpg') && $strFirma != ""){
					$url_firma = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/FIRMAS/".$strFirma.".jpg";
				}else{
					$url_firma = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imageSign.jpg";
				}
				$arr_data[$i]['url_firma'] = $url_firma;
				///-- Fotos --//
				$result_foto = $ClsRev->get_fotos('',$revision);
				if(is_array($result_foto)){
					foreach ($result_foto as $row_foto){
						$strFoto = trim($row_foto["fot_foto"]);
					}
					if(file_exists('../../CONFIG/Fotos/REVISION/'.$strFoto.'.jpg') && $strFoto != ""){
						$url_foto = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/Fotos/REVISION/".$strFoto.".jpg";
					}else{
					   $url_foto = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imagePhoto.jpg";
					}
				}else{
					$url_foto = "https://" . $_SERVER['HTTP_HOST'] . "/CONFIG/img/imagePhoto.jpg";
				}
				$arr_data[$i]['url_foto'] = $url_foto;
				/////////// PROGRAMACION /////
				$dia = date("N");
				$result_horas = $ClsLis->get_programacion('',$codigo_lista,$sede);
				if(is_array($result_horas)){
					$j = 0;	
					foreach ($result_horas as $row_horas){
						//ubicacion
						$arr_horario[$j]['sede'] = trim($row["sed_nombre"]);
						$arr_horario[$j]['sector'] = trim($row["sec_nombre"]);
						$arr_horario[$j]['area'] = trim($row["are_nombre"]);
						$arr_horario[$j]['nivel'] = trim($row["are_nivel"]);
						//--
						$arr_horario[$j]['hora_ini'] = trim($row_horas["pro_hini"]);
						$arr_horario[$j]['hora_fin'] = trim($row_horas["pro_hfin"]);
						$arr_horario[$j]['observaciones'] = trim($row_horas["pro_observaciones"]);
						//dias
						$arr_dias[0]['dia_1'] = trim($row["pro_dia_1"]);
						$arr_dias[1]['dia_2'] = trim($row["pro_dia_2"]);
						$arr_dias[2]['dia_3'] = trim($row["pro_dia_3"]);
						$arr_dias[3]['dia_4'] = trim($row["pro_dia_4"]);
						$arr_dias[4]['dia_5'] = trim($row["pro_dia_5"]);
						$arr_dias[5]['dia_6'] = trim($row["pro_dia_6"]);
						$arr_dias[6]['dia_7'] = trim($row["pro_dia_7"]);
						$arr_horario[$j]['dias'] = $arr_dias;
						$j++;
					}
				}else{
					$arr_horario[0]['sede'] = "";
					$arr_horario[0]['sector'] = "";
					$arr_horario[0]['area'] = "";
					$arr_horario[0]['hora_ini'] = "";
					$arr_horario[0]['hora_fin'] = "";
					$arr_horario[0]['observaciones'] = "";
					$arr_horario[$j]['dias'] = array();
				}
				$arr_data[$i]['programacion'] = $arr_horario;
				///////////////////
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