<?php 
include_once('../html_fns.php');

function tabla_status_helpdesk($codigo){
	$ClsSta = new ClsStatus();
	$result = $ClsSta->get_status_hd($codigo,'','',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "10px">C&oacute;digo</th>';
		$salida.= '<th class = "text-center" width = "10px">Posici&oacute;n</th>';
		$salida.= '<th class = "text-center" width = "250px">Nombre</th>';
		$salida.= '<th class = "text-center" width = "20px">Color</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["sta_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarStatus('.$codigo.');" title = "Editar Status" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarStatus('.$codigo.');" title = "Eliminar Status" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//codigo
			$codigo = Agrega_Ceros($row["sta_codigo"]);
			$salida.= '<td class = "text-center">'.$codigo.'</td>';
			//codigo
			$posicion = trim($row["sta_posicion"]);
			$salida.= '<td class = "text-center">'.$posicion.'</td>';
			//nombre
			$nom = trim($row["sta_nombre"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//color
			$color = trim($row["sta_color"]);
			$salida.= '<td class = "text-center"><i class="fa fa-square fa-2x" style="color: '.$color.'"></i></td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}



function tabla_status_auditoria($codigo){
	$ClsSta = new ClsStatus();
	$result = $ClsSta->get_status_aud($codigo,'','',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "10px">C&oacute;digo</th>';
		$salida.= '<th class = "text-center" width = "10px">Posici&oacute;n</th>';
		$salida.= '<th class = "text-center" width = "250px">Nombre</th>';
		$salida.= '<th class = "text-center" width = "20px">Color</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["sta_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarStatus('.$codigo.');" title = "Editar Status" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarStatus('.$codigo.');" title = "Eliminar Status" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//codigo
			$codigo = Agrega_Ceros($row["sta_codigo"]);
			$salida.= '<td class = "text-center">'.$codigo.'</td>';
			//codigo
			$posicion = trim($row["sta_posicion"]);
			$salida.= '<td class = "text-center">'.$posicion.'</td>';
			//nombre
			$nom = trim($row["sta_nombre"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//color
			$color = trim($row["sta_color"]);
			$salida.= '<td class = "text-center"><i class="fa fa-square fa-2x" style="color: '.$color.'"></i></td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}
?>