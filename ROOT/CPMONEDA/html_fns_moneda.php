<?php 
include_once('../html_fns.php');

function tabla_historial_cambio($moneda){
	$ClsMon = new ClsMoneda();
	$result = $ClsMon->get_his_cambio($moneda);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tablaCambio" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "10px">No.</th>';
		$salida.= '<th class = "text-center" width = "150px">Fecha de Actualizaci&oacute;n</th>';
		$salida.= '<th class = "text-center" width = "20px">Cambio</th>';
		$salida.= '<th class = "text-center" width = "20px">Compra</th>';
		$salida.= '<th class = "text-center" width = "20px">Venta</th>';
		$salida.= '<th class = "text-center" width = "150px">Usuario que Actualiz&oacute;</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=1;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$salida.= '<td class = "text-center">'.$i.'.</td>';
			//fecha update
			$freg = trim($row["cam_fecha"]);
			$freg = cambia_fechaHora($freg);
			$salida.= '<td class = "text-center">'.$freg.'</td>';
			//tipo de cambio
			$cambio = trim($row["cam_cambio"]);
			$salida.= '<td class = "text-center">1 X '.$cambio.'</td>';
			//tipo de cambio
			$compra = trim($row["cam_compra"]);
			$salida.= '<td class = "text-center">1 X '.$compra.'</td>';
			//tipo de cambio
			$venta = trim($row["cam_venta"]);
			$salida.= '<td class = "text-center">1 X '.$venta.'</td>';
			//usuario
			$usuario = trim($row["usu_nombre"]);
			$salida.= '<td class = "text-center">'.$usuario.'</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}


function tabla_monedas(){
	$ClsMon = new ClsMoneda();
	$result = $ClsMon->get_moneda('');
	if(is_array($result)){
		$salida = '<table class="table table-striped table-hover dataTables-promt" id="tablaMonedas" width = "100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th width = "50px"><i class="fas fa-cog"></i></th>';
		$salida.= '<th class = "text-left" width = "150px">NOMBRE DE LA MONEDA</th>';
		$salida.= '<th class = "text-center" width = "30px">SIMBOLO</th>';
		$salida.= '<th class = "text-left" width = "100px">PAIS</td>';
		$salida.= '<th class = "text-center" width = "100px">TASA/CAMBIO</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["mon_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group" >';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarMoneda('.$codigo.');" title = "Editar Datos de la Moneda" ><i class="fa fa-pencil"></i></button> ';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "quitarMoneda('.$codigo.');" title = "Quitar Moneda" ><i class="fa fa-trash"></i></button> ';
				$salida.= '</div>';
			$salida.= '</td>';
			//nombre
			$nom = trim($row["mon_descripcion"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//simbolo
			$simbolo = trim($row["mon_simbolo"]);
			$salida.= '<td class = "text-center">'.$simbolo.'</td>';
			//codigo
			$pais = trim($row["mon_pais"]);
			$salida.= '<td class = "text-left">'.$pais.'</td>';
			//cambio
			$tasa = trim($row["mon_cambio"]);
			$salida.= '<td class = "text-center">'.$tasa.'</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}?>
