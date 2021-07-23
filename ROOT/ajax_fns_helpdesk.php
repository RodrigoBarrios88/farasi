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
	case "conteo_tickets":
		$sede = $_REQUEST["sede"];
		$fini = $_REQUEST["fini"];
		$ffin = $_REQUEST["ffin"];
		conteo_tickets($fini,$ffin,$sede);
		break;
	case "categorias_prioridades":
		$sede = $_REQUEST["sede"];
		$fini = $_REQUEST["fini"];
		$ffin = $_REQUEST["ffin"];
		conteo_categorias_prioridades($fini,$ffin,$sede);
		break;
	case "estadistica_status":
		$sede = $_REQUEST["sede"];
		$fini = $_REQUEST["fini"];
		$ffin = $_REQUEST["ffin"];
		estadistica_status($fini,$ffin,$sede);
		break;
	case "estadistica_prioridad":
		$sede = $_REQUEST["sede"];
		$fini = $_REQUEST["fini"];
		$ffin = $_REQUEST["ffin"];
		estadistica_prioridad($fini,$ffin,$sede);
		break;
	case "estadistica_semanal":
		$sede = $_REQUEST["sede"];
		$fini = $_REQUEST["fini"];
		$ffin = $_REQUEST["ffin"];
		estadistica_semanal($fini,$ffin,$sede);
		break;
	case "tabla_tickets":
		$sede = $_REQUEST["sede"];
		$fini = $_REQUEST["fini"];
		$ffin = $_REQUEST["ffin"];
		tabla_tickets($fini,$ffin,$sede);
		break;
	default:
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Seleccione un metodo..."
		);
		echo json_encode($arr_respuesta);
}

//////////////////////////////////////// HELPDESK ///////////////////////////////////////////
function conteo_tickets($fini,$ffin,$sede){
	$sede = ($sede == "Todas")?"":$sede;
	$fini = ($fini == "")?date("01/01/Y"):$fini; //valida que si no se selecciona fecha, coloque la del dia
	$ffin = ($ffin == "")?date("31/12/Y"):$ffin; //valida que si no se selecciona fecha, coloque la del dia
	$ClsTic = new ClsTicket();
	$result = $ClsTic->get_status_chart($fini,$ffin,$sede);
	$salida = "";
	if(is_array($result)){
		$i=1;
		foreach($result as $row){
			$codigo = trim($row["sta_codigo"]);
			//status
			$status = trim($row["sta_nombre"]);
			//$status = (strlen($status) > 13)?substr($status,0,10)."...":$status;
			//color
			$color = trim($row["sta_color"]);
			//cantidad
			$cantidad = trim($row["total_tickets"]);
			//--
			$salida .= '<div class="col-lg-3 col-md-6 col-sm-6">';
			$salida.= '<div class="card card-stats" >';
			$salida.= '<div class="card-body">';
			$salida.= '<a href="javascript:void(0);" onclick="verConteoStatus('.$codigo.',\''.$fini.'\',\''.$ffin.'\',\''.$sede.'\')">';
			$salida.= '<div class="row">';
			$salida.= '<div class="col-5 col-md-4">';
			$salida.= '<div class="icon-big text-center text-info">';
			$salida.= '<i class="fa fa-desktop" style="color:'.$color.'"></i>';
			$salida.= '</div>';
			$salida.= '</div>';
			$salida.= '<div class="col-7 col-md-8">';
			$salida.= '<div class="numbers">';
			$salida.= '<small>'.$cantidad.'</small>';
			$salida.= '</div>';
			$salida.= '</div>';
			$salida.= '</div>';
			$salida.= '</div>';
			$salida.= '<div class="card-footer text-right">';
			$salida.= '<div class="stats">';
			$salida.= $status;
			$salida.= '</div>';
			$salida.= '</a>';
			$salida.= '</div>';
			$salida.= '</div>';
			$salida.= '</div>';
			//--
			$i++;
		}
	}$arr_respuesta = array(
		"status" => true,
		"data" => $salida,
		"message" => ""
	);

	echo json_encode($arr_respuesta);
}


function conteo_categorias_prioridades($fini,$ffin,$sede){
	$sede = ($sede == "Todas")?"":$sede;
	$fini = ($fini == "")?date("01/01/Y"):$fini; //valida que si no se selecciona fecha, coloque la del dia
	$ffin = ($ffin == "")?date("31/12/Y"):$ffin; //valida que si no se selecciona fecha, coloque la del dia
	$ClsTic = new ClsTicket();
	$ClsCat = new ClsCategoria();
	$ClsPri = new ClsPrioridad();
	$result = $ClsCat->get_categoria_helpdesk('','',1);
	$salida = "";
	if(is_array($result)){
		$i=1;
		foreach($result as $row){
			$categoria = trim($row["cat_codigo"]);
			//--
			$total = 0;
			$totalvencidos = 0;
			$result_prioridades = $ClsPri->get_prioridad('','',1);
			if(is_array($result_prioridades)){
				foreach($result_prioridades as $row_prioridad){
					$prioridad = trim($row_prioridad["pri_codigo"]);
					$tsolucion = trim($row_prioridad["pri_solucion"]);
					$tsolucion = horasYdecimales($tsolucion);
					//--
					$cantidad = 0;
					$entimepo = 0;
					$vencidos = 0;
					$resul_ticket = $ClsTic->get_ticket('',$categoria,$sede,'', $prioridad,'',$fini,$ffin,'');
					if(is_array($resul_ticket)){
						foreach($resul_ticket as $row_ticket){
							$freg = trim($row_ticket["tic_fecha_registro"]);
							$ahora = date("Y-m-d H:i:s");
							$dias = restaFechas($freg, $ahora);
							$dias--;
							$horas = $dias * 24;
							///echo "$freg: $dias - $horas - $tsolucion <br><br>";
							///--
							if($horas > $tsolucion){
							   $vencidos++;
							}else{
							   $entimepo++;
							}
							$cantidad++;
						}	
					}
					$total+= $cantidad;
					$totalvencidos+= $vencidos;
				}	
			}
			//vencidos
			$dotcolor = ($totalvencidos > 0)?'red':'green';
			//status
			$nombre = trim($row["cat_nombre"]);
			$nombre = (strlen($nombre) > 13)?substr($nombre,0,10)."...":$nombre;
			//color
			$color = trim($row["cat_color"]);
			//-----------------
			$salida .= '<div class="card-body ">';
			$salida.= '<ul class="list-group">';
			$salida.= '<li class="list-group-item active" style="background:'.$color.'; border-color:'.$color.';">';
			$salida.= '<a style="color:#fff;" href="javascript:void(0)" onclick="verConteo('.$categoria.',\''.$fini.'\',\''.$ffin.'\',\''.$sede.'\');" >';
			$salida.= '<i class="fa fa-circle" style="color:'.$dotcolor.'"></i> '.$nombre;
			$salida.= '<span class="badge badge-pill pull-right" style="background:#fff; border-color:#fff; color:'.$color.';">'.$total.'</span>';
			$salida.= '</a>';
			$salida.= '</li>';
			//--
			$salida.= '</ul>';
			$salida.= '</div>';
			//--
			$i++;
		}
	}$arr_respuesta = array(
		"status" => true,
		"data" => $salida,
		"message" => ""
	);

	echo json_encode($arr_respuesta);
}



function estadistica_status($fini,$ffin,$sede){
	$sede = ($sede == "Todas")?"":$sede;
	$fini = ($fini == "")?date("01/01/Y"):$fini; //valida que si no se selecciona fecha, coloque la del dia
	$ffin = ($ffin == "")?date("31/12/Y"):$ffin; //valida que si no se selecciona fecha, coloque la del dia
	$ClsTic = new ClsTicket();
	$result = $ClsTic->get_status_chart($fini,$ffin,$sede);
	if(is_array($result)){
		$i=0;
		$total_tickets = 0;
		$arrcant = array();
		$arrcolors = array();
		foreach($result as $row){
			//status
			$codigo = trim($row["sta_codigo"]);
			$status = trim($row["sta_nombre"]);
			$color = trim($row["sta_color"]);
			//cantidad
			$cantidad = trim($row["total_tickets"]);
			//revisones
			$tickets = intval($row["total_tickets"]);
			$arrcant[$i]= array($status,$cantidad);
			$arrcolors["$status"] = $color;
			$i++;
		}
	}else{
		$arrcant = array();
		$arrcolors = array();
	}$arr_respuesta = array(
		"status" => true,
		"data" => $arrcant,
		"colores" => $arrcolors,
		"message" => ""
	);echo json_encode($arr_respuesta);
}


function estadistica_prioridad($fini,$ffin,$sede){
	$sede = ($sede == "Todas")?"":$sede;
	$fini = ($fini == "")?date("01/01/Y"):$fini; //valida que si no se selecciona fecha, coloque la del dia
	$ffin = ($ffin == "")?date("31/12/Y"):$ffin; //valida que si no se selecciona fecha, coloque la del dia
	$ClsTic = new ClsTicket();
	$result = $ClsTic->get_prioridad_chart($fini,$ffin,$sede);
	if(is_array($result)){
		$i=0;
		$total_tickets = 0;
		$arrcant = array();
		$arrcolors = array();
		foreach($result as $row){
			//status
			$codigo = trim($row["pri_codigo"]);
			$categoria = trim($row["pri_nombre"]);
			$color = trim($row["pri_color"]);
			//cantidad
			$cantidad = trim($row["total_tickets"]);
			//revisones
			$tickets = intval($row["total_tickets"]);
			$arrcant[$i]= array($categoria,$cantidad);
			$arrcolors["$categoria"] = $color;
			$i++;
		}
	}else{
		$arrcant = array();
		$arrcolors = array();
	}$arr_respuesta = array(
		"status" => true,
		"data" => $arrcant,
		"colores" => $arrcolors,
		"message" => ""
	);echo json_encode($arr_respuesta);
}



function estadistica_semanal($fini,$ffin,$sede){
	$sede = ($sede == "Todas")?"":$sede;
	$fini = ($fini == "")?date("01/01/Y"):$fini; //valida que si no se selecciona fecha, coloque la del dia
	$ffin = ($ffin == "")?date("31/12/Y"):$ffin; //valida que si no se selecciona fecha, coloque la del dia
	$ClsTic = new ClsTicket();
	$abiertos = $ClsTic->count_ticket('','','','','', $sede,$fini,$ffin,1);
	$cerrados = $ClsTic->count_ticket('','','','','', $sede,$fini,$ffin,2);
	$total = ($abiertos + $cerrados);
	if($total > 0){
		$porcentaje = round(($abiertos*100)/$total);
	}else{
		$porcentaje = 0;
	}$arr_respuesta = array(
		"status" => true,
		"porcentaje" => array("abiertos", $porcentaje),
	);echo json_encode($arr_respuesta);
}


function tabla_tickets($fini,$ffin,$sede){
	$sede = ($sede == "Todas")?"":$sede;
	$fini = ($fini == "")?date("01/01/Y"):$fini; //valida que si no se selecciona fecha, coloque la del dia
	$ffin = ($ffin == "")?date("31/12/Y"):$ffin; //valida que si no se selecciona fecha, coloque la del dia
	$ClsTic = new ClsTicket();
	$result = $ClsTic->get_ticket('','',$sede,'', '','',$fini,$ffin,'');
	if(is_array($result)){
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "30px">Ticket</th>';
		$salida.= '<th class = "text-center" width = "100px">Categor&iacute;a</th>';
		$salida.= '<th class = "text-center" width = "150px">Incidente</th>';
		$salida.= '<th class = "text-center" width = "150px">Fecha Apertura</th>';
		$salida.= '<th class = "text-center" width = "100px">Prioridad</th>';
		$salida.= '<th class = "text-center" width = "100px">Status</th>';
		$salida.= '<th class = "text-center" width = "100px">Situaci&oacute;n</th>';
		$salida.= '<th class = "text-center" width = "10px"></th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=1;
		foreach($result as $row){
			$salida.= '<tr>';
			//Codigo
			$codigo = Agrega_Ceros($row["tic_codigo"]);
			$salida.= '<td class = "text-center"># '.$codigo.'</td>';
			//categoria
			$categoria = trim($row["cat_nombre"]);
			$salida.= '<td class = "text-left">'.$categoria.'</td>';
			//incidente
			$incidente = trim($row["inc_nombre"]);
			$salida.= '<td class = "text-left">'.$incidente.'</td>';
			//nombre
			$fechor = cambia_fechaHora($row["tic_fecha_registro"]);
			$salida.= '<td class = "text-left">'.$fechor.'</td>';
			//prioridad
			$prioridad = trim($row["pri_nombre"]);
			$salida.= '<td class = "text-center">'.$prioridad.'</td>';
			//status
			$status = trim($row["sta_nombre"]);
			$color = trim($row["sta_color"]);
			$salida.= '<td class="text-center"><strong style="color:'.$color.'">'.$status.'</strong></td>';
			//situacion
			$situacion = trim($row["tic_situacion"]);
			if($situacion == 1){
				$tsolucion = trim($row["pri_solucion"]);
				$tsolucion = horasYdecimales($tsolucion);
				$freg = trim($row["tic_fecha_registro"]);
				$ahora = date("Y-m-d H:i:s");
				$dias = restaFechas($freg, $ahora);
				$dias--;
				$horas = $dias * 24;
				///--
				if($horas > $tsolucion){
				   $situacion = '<span class="text-danger">Vencido</span>';
				}else{
				   $situacion = '<span class="text-info">En tiempo</span>';
				}
			}else{
				$situacion = '<span class="text-muted">Cerrado</span>';
			}
			$salida.= '<td class="text-center">'.$situacion.'</td>';
			//codigo
			$codigo = $row["tic_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-info btn-xs" onclick = "verInformacion('.$codigo.');" title = "Ver Informaci&oacute;n del Ticket" ><i class="fa fa-search"></i></button> &nbsp; ';
				$salida.= '</div>';
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
