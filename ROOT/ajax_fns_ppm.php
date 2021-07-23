<?php
ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cache");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);

include_once('html_fns.php');

$request = $_REQUEST["request"]; 
switch($request){
	////////////////// COMBOS /////////////////////////
	case "activo":
		$sede = $_REQUEST["sede"];
		$area = $_REQUEST["area"];
		get_activos($sede,$area);
		break;
	////////////////// DASHBOARD /////////////////////////
	case "conteo_status":
		$activo = $_REQUEST["activo"];
		$usuario = $_REQUEST["usuario"];
		$sede = $_REQUEST["sede"];
		$sector = $_REQUEST["sector"];
		$area = $_REQUEST["area"];
		$desde = $_REQUEST["desde"];
		$hasta = $_REQUEST["hasta"];
		conteo_status($activo,$usuario,$sede,$sector,$area,$desde,$hasta);
		break;
	case "categorias_status":
		$activo = $_REQUEST["activo"];
		$usuario = $_REQUEST["usuario"];
		$sede = $_REQUEST["sede"];
		$sector = $_REQUEST["sector"];
		$area = $_REQUEST["area"];
		$desde = $_REQUEST["desde"];
		$hasta = $_REQUEST["hasta"];
		tabla_categorias_status($activo,$usuario,$sede,$sector,$area,$desde,$hasta);
		break;
	case "usuarios_trabajo":
		$activo = $_REQUEST["activo"];
		$usuario = $_REQUEST["usuario"];
		$categoria = $_REQUEST["categoria"];
		$sede = $_REQUEST["sede"];
		$sector = $_REQUEST["sector"];
		$area = $_REQUEST["area"];
		$desde = $_REQUEST["desde"];
		$hasta = $_REQUEST["hasta"];
		estadisticas_usuarios_trabajo($activo,$usuario,$categoria,$sede,$sector,$area,$desde,$hasta);
		break;
	case "activos_off":
		$sede = $_REQUEST["sede"];
		$sector = $_REQUEST["sector"];
		$area = $_REQUEST["area"];
		tabla_activos_off($sede,$sector,$area);
		break;
	case "tabla_fallas":
		$sede = $_REQUEST["sede"];
		$sector = $_REQUEST["sector"];
		$area = $_REQUEST["area"];
		$desde = $_REQUEST["desde"];
		$hasta = $_REQUEST["hasta"];
		tabla_fallas($sede,$sector,$area,$desde,$hasta);
		break;
	case "tabla_programacion":
		$activo = $_REQUEST["activo"];
		$usuario = $_REQUEST["usuario"];
		$categoria = $_REQUEST["categoria"];
		$sede = $_REQUEST["sede"];
		$sector = $_REQUEST["sector"];
		$area = $_REQUEST["area"];
		$desde = $_REQUEST["desde"];
		$hasta = $_REQUEST["hasta"];
		tabla_programacion($activo,$usuario,$categoria,$sede,$sector,$area,$desde,$hasta,$situacion);
		break;
	case "tabla_presupuestos":
		$activo = $_REQUEST["activo"];
		$usuario = $_REQUEST["usuario"];
		$categoria = $_REQUEST["categoria"];
		$sede = $_REQUEST["sede"];
		$area = $_REQUEST["area"];
		$desde = $_REQUEST["desde"];
		$hasta = $_REQUEST["hasta"];
		tabla_presupuestos($activo,$usuario,$categoria,$sede,$area,$desde,$hasta);
		break;
	default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

////////////////// COMBOS /////////////////////////
function get_activos($sede,$area){
	if($sede != ""){
		$sede = ($sede == "Todas")?"":$sede;
		$combo = activos_html("activo",$sede,$area,"Submit();","select2");
	}else{
		$combo = combos_vacios("activo","select2");
	}
	$arr_respuesta = array(
		"status" => true,
		"combo" => $combo
	);
	echo json_encode($arr_respuesta);
}


//////////////////////////////////////// PPM ///////////////////////////////////////////

function conteo_status($activo, $usuario, $sede, $sectores, $area, $desde, $hasta){
	$sedesIn = $_SESSION["sedes_in"];
	$sede = ($sede == "Todas")?"":$sede;
	$sede = ($sede == "")?$sedesIn:$sede;
	$ClsPro = new ClsProgramacionPPM();
	$result = $ClsPro->get_programacion($codigo,$activo,$usuario,$categoria,$sede, $sectores, $area, $desde, $hasta,'','','');
	if(is_array($result)){
		$i=0;
		$pendiente_normal = 0;
		$pendiente_vencido = 0;
		$espera_normal = 0;
		$espera_vencido = 0;
		$proceso_normal = 0;
		$proceso_vencido = 0;
		$finalizado_normal = 0;
		$finalizado_vencido = 0;
		foreach($result as $row){
			//conteo de dias
			$programado = trim($row["pro_fecha"])." 23:59:59";
			$fecha_update = trim($row["pro_fecha_update"]);
			$vencimiento = comparaFechas($programado, $fecha_update);
			$situacion = $row["pro_situacion"];
			if($situacion == 1){
				if($vencimiento == 2){ // vencido
					$pendiente_vencido++;
				}else{ // normal
					$pendiente_normal++;
				}
			}else if($situacion == 2){
				if($vencimiento == 2){ // vencido
					$espera_vencido++;
				}else{ // normal
					$espera_normal++;
				}
			}else if($situacion == 3){
				if($vencimiento == 2){ // vencido
					$proceso_vencido++;
				}else{ // normal
					$proceso_normal++;
				}
			}else if($situacion == 4){
				if($vencimiento == 2){ // vencido
					$finalizado_vencido++;
				}else{ // normal
					$finalizado_normal++;
				}
			}
			$i++;
		}
	}if($i > 0){
		$porcent_finalizado = round((($finalizado_normal+$finalizado_vencido)*100)/$i);
		$porcent_espera = round((($espera_normal+$espera_vencido)*100)/$i);
		$porcent_proceso = round((($proceso_normal+$proceso_vencido)*100)/$i);
		$porcent_pendiente = round((($pendiente_normal+$pendiente_vencido)*100)/$i);
	}else{
		$porcent_finalizado = 0;
		$porcent_espera = 0;
		$porcent_proceso = 0;
		$porcent_pendiente = 0;
	}$arr_respuesta = array(
		"status" => true,
		"pendiente1" => $pendiente_normal,
		"pendiente2" => $pendiente_vencido,
		"espera1" => $espera_normal,
		"espera2" => $espera_vencido,
		"proceso1" => $proceso_normal,
		"proceso2" => $proceso_vencido,
		"final1" => $finalizado_normal,
		"final2" => $finalizado_vencido,
		"porcentFinalizado" => $porcent_finalizado,
		"porcentEspera" => $porcent_espera,
		"porcentProceso" => $porcent_proceso,
		"porcentPendiente" => $porcent_pendiente,
	);

	echo json_encode($arr_respuesta);
}


function tabla_categorias_status($activo, $usuario, $sede, $sectores, $area, $desde, $hasta){
	$sedesIn = $_SESSION["sedes_in"];
	$sede = ($sede == "Todas")?"":$sede;
	$sede = ($sede == "")?$sedesIn:$sede;
	$ClsCat = new ClsCategoria();
	$ClsPro = new ClsProgramacionPPM();
	$result = $ClsCat->get_categoria_ppm($codigo,'',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="dataTables-categorias" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "150px">Categor&iacute;a</th>';
		$salida.= '<th class = "text-center" width = "30px">Total</th>';
		$salida.= '<th class = "text-center" width = "30px">Pendientes</th>';
		$salida.= '<th class = "text-center" width = "30px">Proceso</th>';
		$salida.= '<th class = "text-center" width = "30px">Espera</th>';
		$salida.= '<th class = "text-center" width = "30px">Finalizados</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=1;
		foreach($result as $row){
			$categoria = trim($row["cat_codigo"]);
			//--
			$totalpendientes = 0;
			$totalproceso = 0;
			$totalfinalizados = 0;
			$totalespera = 0;
			$result_programacion = $ClsPro->get_programacion($codigo,$activo,$usuario,$categoria,$sede, $sectores, $area, $desde, $hasta,'','','');
			if(is_array($result_programacion)){
				foreach($result_programacion as $row_programacion){
					//conteo de dias
					$programado = trim($row_programacion["pro_fecha"]);
					$ahora = date("Y-m-d");
					$vencimiento = comparaFechas($programado, $ahora);
					$situacion = $row_programacion["pro_situacion"];
					if($situacion == 1){
						$totalpendientes++;
					}else if($situacion == 2){
						$totalespera++;
					}else if($situacion == 3){
						$totalpendientes++;
					}else if($situacion == 4){
						$totalfinalizados++;
					}
				}	
			}
			//status
			$nombre = trim($row["cat_nombre"]);
			$total_eventos = $totalfinalizados + $totalpendientes;
			if($total_eventos > 0){
				$porcentaje = round(($totalfinalizados*100)/$total_eventos);
			}else{
				$porcentaje = 0;
			}
			//-----------------
			$salida.= '<tr>';
			//activo
			$salida.= '<td class = "text-left">'.$nombre.'</td>';
			$salida.= '<td class = "text-center">'.$total_eventos.'</td>';
			$salida.= '<td class = "text-center">'.$totalpendientes.'</td>';
			$salida.= '<td class = "text-center">'.$totalproceso.'</td>';
			$salida.= '<td class = "text-center">'.$totalespera.'</td>';
			$salida.= '<td class = "text-center">'.$totalfinalizados.'</td>';
			//--
			$salida.= '</tr>';
			//--
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
		
		$arr_respuesta = array(
			"status" => true,
			"tabla" => $salida,
			"message" => ""
		);
	}else{
		$arr_respuesta = array(
			"status" => false,
			"tabla" => "",
			"message" => "No se registran datos con estos parametros..."
		);
	}echo json_encode($arr_respuesta);
}


function estadisticas_usuarios_trabajo($activo, $usuario, $categoria, $sede, $sectores, $area, $desde, $hasta){
	$sedesIn = $_SESSION["sedes_in"];
	$sede = ($sede == "Todas")?"":$sede;
	$sede = ($sede == "")?$sedesIn:$sede;
	$ClsUsu = new ClsUsuario();
	$ClsPro = new ClsProgramacionPPM();
	$result = $ClsUsu->get_usuario($usuario,'','','','',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="dataTables-usuarios" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th width = "10px" class = "text-center">No.</td>';
		$salida.= '<th width = "150px" class = "text-center">Nombre del Usuario</td>';
		$salida.= '<th width = "30px" class = "text-center">Ordenes de Trabajo</td>';
		$salida.= '<th width = "10px" class = "text-center"></td>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=1;
		foreach($result as $row){
			$usuario = trim($row["usu_id"]);
			//--
			$ordenes = 0;
			$result_programacion = $ClsPro->get_programacion('',$activo,$usuario,$categoria,$sede, $sectores, $area, $desde, $hasta,'','','');
			if(is_array($result_programacion)){
				foreach($result_programacion as $row_programacion){
					$ordenes++;
				}	
			}
			//-----------------
			$salida.= '<tr>';
			//i
			$salida.= '<td class = "text-center">'.$i.'</td>';
			//nombre
			$nom = trim($row["usu_nombre"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//nivel
			$salida.= '<td class = "text-center">'.$ordenes.'</td>';
			//codigo
			$codigo = $row["usu_id"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsUsu->encrypt($codigo, $usu);
			$salida.= '<td class = "text-center" >';
				$salida.= '<a class="btn btn-success" href = "CPPPMPROGRA/FRMprogramacion_usuario.php?hashkey='.$hashkey.'&desde='.$desde.'&hasta='.$hasta.'" title = "Ver Ficha del Activo" ><i class="fa fa-search"></i></q>';
			$salida.= '</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
		
		$arr_respuesta = array(
			"status" => true,
			"tabla" => $salida,
			"message" => ""
		);
		
	}else{
		$arr_respuesta = array(
			"status" => false,
			"tabla" => "",
			"message" => "No se registran datos con estos parametros..."
		);
	}echo json_encode($arr_respuesta);
}


function tabla_activos_off($sede,$sector,$area){
	$sedesIn = $_SESSION["sedes_in"];
	$sede = ($sede == "Todas")?"":$sede;
	$sede = ($sede == "")?$sedesIn:$sede;
	$ClsAct = new ClsActivo();
	$result = $ClsAct->get_activo('',$sede,$sector,$area,2);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="dataTables-activos" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida.= '<th class = "text-center" width = "100px">Activo</th>';
		$salida.= '<th class = "text-center" width = "10px"><i class="fa fa-cogs"></i></th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = Agrega_Ceros($row["act_codigo"]);
			$salida.= '<td class = "text-center">#'.$codigo.'</td>';
			//nombre
			$nombre = trim($row["act_nombre"]);
			$salida.= '<td class = "text-left">'.$nombre.'</td>';
			//codigo
			$codigo = $row["act_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsAct->encrypt($codigo, $usu);
			$salida.= '<td class = "text-center" >';
				$salida.= '<a class="btn btn-success" href = "CPACTIVO/FRMficha.php?hashkey='.$hashkey.'" title = "Ver Ficha del Activo" ><i class="fa fa-search"></i></q>';
			$salida.= '</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
		
		$arr_respuesta = array(
			"status" => true,
			"tabla" => $salida,
			"message" => ""
		);
	}else{
		$arr_respuesta = array(
			"status" => false,
			"tabla" => "",
			"message" => "No se registran datos con estos parametros... $sede,$sector,$area"
		);
	}echo json_encode($arr_respuesta);
}




function tabla_fallas($sede, $sector, $area, $desde, $hasta){
	$sedesIn = $_SESSION["sedes_in"];
	$sede = ($sede == "Todas")?"":$sede;
	$sede = ($sede == "")?$sedesIn:$sede;
	$ClsFal = new ClsFalla();
	$result = $ClsFal->get_falla('','', $sede, $sector, $area, '', $desde, $hasta);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="dataTables-fallas" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "20px">No.</th>';
		$salida.= '<th class = "text-center" width = "120px">Activo</th>';
		$salida.= '<th class = "text-center" width = "150px">Falla</th>';
		$salida.= '<th class = "text-center" width = "20px"><i class="fa fa-cogs"></i></th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=1;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$salida.= '<td class = "text-center" >'.$i.'.</td>';
			//nombre
			$nombre = trim($row["act_nombre"]);
			$salida.= '<td class = "text-left">'.$nombre.'</td>';
			//falla
			$falla = trim($row["fall_falla"]);
			$salida.= '<td class = "text-left">'.$falla.'</td>';
			//codigo
			$activo = $row["act_codigo"];
			$falla = $row["fall_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<button type="button" class="btn btn-success" onclick="listFallas('.$activo.','.$falla.');" title="Ver hisrorial de fallas del activo"><i class="fa fa-search"></i></button>';
			$salida.= '</td>';
			
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
		
		$arr_respuesta = array(
			"status" => true,
			"tabla" => $salida,
			"message" => ""
		);
	}else{
		$arr_respuesta = array(
			"status" => false,
			"tabla" => "",
			"message" => "No se registran datos con estos parametros..."
		);
	}echo json_encode($arr_respuesta);
}


function tabla_programacion($activo, $usuario, $categoria, $sede, $sectores, $area, $desde, $hasta, $situacion){
	$sedesIn = $_SESSION["sedes_in"];
	$sede = ($sede == "Todas")?"":$sede;
	$sede = ($sede == "")?$sedesIn:$sede;
	$situacion = ($situacion == 5)?1:$situacion;
	$ClsPro = new ClsProgramacionPPM();
	$result = $ClsPro->get_programacion($codigo,$activo,$usuario,$categoria,$sede, $sectores, $area, $desde, $hasta,'','',$situacion);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="dataTables-programacion" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "10px">No.</th>';
		$salida.= '<th class = "text-center" width = "120px">Activo (Cantidad)</th>';
		$salida.= '<th class = "text-center" width = "120px">Ubicaci&oacute;n</th>';
		$salida.= '<th class = "text-center" width = "100px">Situaci&oacute;n</th>';
		$salida.= '<th class = "text-center" width = "100px">Categor&iacute;a</th>';
		$salida.= '<th class = "text-center" width = "120px">Responsable</th>';
		$salida.= '<th class = "text-center" width = "100px">Fecha Programada</th>';
		$salida.= '<th class = "text-center" width = "100px">Fecha Ejecutada</th>';
		$salida.= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=1;
		foreach($result as $row){
			//conteo de dias
			$programado = trim($row["pro_fecha"]);
			$ahora = date("Y-m-d");
			$vencimiento = comparaFechas($programado, $ahora);
			$situacion = $row["pro_situacion"];
			if($situacion == 1){
				if($vencimiento == 1){ // Falta para que se cumpla
					$class = '';
					$texto = 'Programado';
				}else if($vencimiento == 2){ // ya se vencio
					$class = 'text-danger';
					$texto = 'Vencido';
				}else{ // hoy corresponde
					$class = '';
					$texto = 'Para Hoy';
				}
			}else if($situacion == 2){
				$class = 'text-warning';
				$texto = 'En Espera';
			}else if($situacion == 3){
				$class = 'text-info';
				$texto = 'En Proceso';
			}else if($situacion == 4){
				$class = 'text-success';
				$texto = 'Finalizado';
			}
			//reprogramaciones
			$reprogramaciones = intval($row["reprogramaciones"]);
			if($reprogramaciones > 0){
				$texto.= ' <small>reprogramado ('.$reprogramaciones.')</small>';
			}
			//--
			$salida.= '<tr>';
			//No.
			$salida.= '<td class = "text-center">'.$i.'</td>';
			//activo
			$activo = trim($row["act_nombre"]);
			$cantidad = trim($row["act_cantidad"]); 	
			$salida.= '<td class = "text-left">'.$activo.' ('.$cantidad.')</td>';
			//ubicacion
			$sede = trim($row["sed_nombre"]);
			$area = trim($row["are_nombre"]);
			$salida.= '<td class = "text-left">'.$sede.', '.$area.'</td>';
			//situacion.
			$salida.= '<td class = "text-center"><strong class="'.$class.'">'.$texto.'</strong></td>';
			//categoria
			$categoria = trim($row["cat_nombre"]);
			$salida.= '<td class = "text-left">'.$categoria.'</td>';
			//usuario
			$reasignaciones = intval($row["reasignaciones"]);
			$usuario = trim($row["usu_nombre"]);
			$usuario = ($reasignaciones > 0)?$usuario.' <small>(reasignado)</small>':$usuario;
			$salida.= '<td class = "text-left">'.$usuario.'</td>';
			//fecha.
			$fecha = cambia_fecha($row["pro_fecha"]);
			$salida.= '<td class = "text-center">'.$fecha.'</td>';
			//fecha.
			$fecha = cambia_fechaHora($row["pro_fecha_update"]);
			$salida.= '<td class = "text-center">'.$fecha.'</td>';
			//codigo
			$codigo = $row["pro_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsPro->encrypt($codigo, $usu);
			$salida.= '<td class = "text-center" >';
				$salida.= '<a class="btn btn-success" href = "CPPPMPROGRA/FRMorden.php?hashkey='.$hashkey.'" title = "Ver Orden de Trabajo" ><i class="fa fa-search"></i></q>';
			$salida.= '</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
		
		$arr_respuesta = array(
			"status" => true,
			"tabla" => $salida,
			"message" => ""
		);
	}else{
		$arr_respuesta = array(
			"status" => false,
			"tabla" => "",
			"message" => "No se registran datos con estos parametros..."
		);
	}echo json_encode($arr_respuesta);
}



function tabla_presupuestos($activo, $usuario, $categoria, $sede, $area, $desde, $hasta){
	$sedesIn = $_SESSION["sedes_in"];
	$sede = ($sede == "Todas")?"":$sede;
	$sede = ($sede == "")?$sedesIn:$sede;
	$ClsPro = new ClsProgramacionPPM();
	$salida = '<table class="table table-striped" id="dataTables-presupuesto" width="100%" >';
	$salida.= '<thead>';
	$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "30px">No.</th>';
		$salida.= '<th class = "text-center" width = "150px"></th>';
		$salida.= '<th class = "text-center" width = "100px">Categor&iacute;a</th>';
		$salida.= '<th class = "text-center" width = "100px">Activo</th>';
		$salida.= '<th class = "text-center" width = "150px">Programado</th>';
		$salida.= '<th class = "text-center" width = "100px">Ejecutado</th>';
		$salida.= '<th class = "text-center" width = "100px">Diferencia</th>';
		$salida.= '<th class = "text-center" width = "100px">%</th>';
	$salida.= '</tr>';
	$salida.= '</thead>';
	$salida.= '<tbody>';
	$num = 1;
	$dia_inicio = "";
	$PROGRAMADO = 0;
	$EJECUTADO = 0;
	$DIFERENCIA = 0;
	//--
	$result = $ClsPro->get_programacion('',$activo,$usuario,$categoria,$sede, '', $area, $desde, $hasta,'','',4);
	$programado = 0;
	$ejecutado = 0;
	$diferencia = 0;
	$porcentaje = 0;
	$signo = "";
	if(is_array($result)){
		$num = 1;
		foreach($result as $row){
			$programado = 0;
			$ejecutado = 0;
			$codigo = Agrega_Ceros($row["pro_codigo"]);
			$fecha = cambia_fecha($row["pro_fecha"]);
			//--
			$programado = cambioMoneda($row["mon_cambio"], 1, $row["pro_presupuesto_programado"]); //realiza conversion de tipo de cambio
			$ejecutado = cambioMoneda($row["mon_cambio"], 1, $row["pro_presupuesto_ejecutado"]); //realiza conversion de tipo de cambio
			$diferencia = $programado - $ejecutado;
			if($diferencia != 0){
				if($diferencia > 0){
					$porcentaje = round(($ejecutado*100)/$programado);
					$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecución
					$signo = "-";
				}else{
					$porcentaje = round(($programado*100)/$ejecutado);
					$diferencia = ($diferencia * -1);
					$porcentaje = (100 - $porcentaje); // se resta entre 100 para obtener el porcentaje de ahorro, no el de ejecución
					$signo = "+";
				}
			}else{
				$porcentaje = 0;
				$signo = "";
			}
			$PROGRAMADO+=$programado;
			$EJECUTADO+=$ejecutado;
			$DIFERENCIA+=$DIFERENCIA;
			//categoria
			$categoria_nombre = trim($row["cat_nombre"]);
			//activo
			$activo_nombre = trim($row["act_nombre"]);
			//--
			$salida.= '<tr>';
			//--
			$salida.= '<td class = "text-center">'.$num.'.- </td>';
			$salida.= '<td class = "text-left"> Programaci&oacute;n #'.$codigo.' '.$fecha.'</td>';
			$salida.= '<td class = "text-left">'.$categoria_nombre.'</td>';
			$salida.= '<td class = "text-left">'.$activo_nombre.'</td>';
			$salida.= '<td class = "text-center">Q. '.number_format($programado, 2, '.', ',').'</td>';
			$salida.= '<td class = "text-center">Q. '.number_format($ejecutado, 2, '.', ',').'</td>';
			$salida.= '<td class = "text-center">'.$signo.' Q.'.number_format($diferencia, 2, '.', ',').'</td>';
			$salida.= '<td class = "text-center">'.$signo.' '.number_format($porcentaje, 0, '.', '').' %</td>';
			//--
			$salida.= '</tr>';
			$num++;
		}
	}
		//////////////// TOTALES DE TABLA ///////////////////
	$DIFERENCIA = $PROGRAMADO - $EJECUTADO;
	if($DIFERENCIA != 0){
		IF($DIFERENCIA > 0){
			$PORCENTAJE = ROUND(($EJECUTADO*100)/$PROGRAMADO);
			$PORCENTAJE = (100 - $PORCENTAJE); // SE RESTA ENTRE 100 PARA OBTENER EL PORCENTAJE DE AHORRO, NO EL DE EJECUCIÓN
			$SIGNO = "-";
		}else{
			$PORCENTAJE = ROUND(($PROGRAMADO*100)/$EJECUTADO);
			$DIFERENCIA = ($DIFERENCIA * -1);
			$PORCENTAJE = (100 - $PORCENTAJE); // SE RESTA ENTRE 100 PARA OBTENER EL PORCENTAJE DE AHORRO, NO EL DE EJECUCIÓN
			$SIGNO = "+";
		}
	}else{
		$PORCENTAJE = 0;
		$SIGNO = "";
	}
	$salida.= '<tr>';
	//--
	$salida.= '<th class = "text-center"> </th>';
	$salida.= '<th class = "text-center"> </th>';
	$salida.= '<th class = "text-center"> </th>';
	$salida.= '<th class = "text-right"> Totales &nbsp; </th>';
	$salida.= '<th class = "text-center">Q. '.number_format($PROGRAMADO, 2, '.', ',').'</th>';
	$salida.= '<th class = "text-center">Q. '.number_format($EJECUTADO, 2, '.', ',').'</th>';
	$salida.= '<th class = "text-center">'.$SIGNO.' Q.'.number_format($DIFERENCIA, 2, '.', ',').'</th>';
	$salida.= '<th class = "text-center">'.$SIGNO.' '.number_format($PORCENTAJE, 0, '.', '').' %</th>';
	//--
	$salida.= '</tr>';
	/////////---------
	$salida.= '</tbody>';
	$salida.= '</table>';$arr_respuesta = array(
		"status" => true,
		"tabla" => $salida,
		"message" => ""
	);
	echo json_encode($arr_respuesta);
}?>