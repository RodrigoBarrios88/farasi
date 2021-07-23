<?php 
include_once('../html_fns.php');

function tabla_prioridades($codigo){
	$ClsPri = new ClsPrioridad();
	$result = $ClsPri->get_prioridad($codigo,'',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "10px">C&oacute;digo</th>';
		$salida.= '<th class = "text-center" width = "150px">Nombre</th>';
		$salida.= '<th class = "text-center" width = "100px">Tiempo/Respuesta</th>';
		$salida.= '<th class = "text-center" width = "100px">Tiempo/Soluci&oacute;n</th>';
		$salida.= '<th class = "text-center" width = "100px">Recordatorio</th>';
		$salida.= '<th class = "text-center" width = "20px">Push</th>';
		$salida.= '<th class = "text-center" width = "20px">Color</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["pri_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarCategoria('.$codigo.');" title = "Editar Prioridad" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarPrioridad('.$codigo.');" title = "Eliminar Prioridad" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//codigo
			$codigo = Agrega_Ceros($row["pri_codigo"]);
			$salida.= '<td class = "text-center">'.$codigo.'</td>';
			//nombre
			$nom = trim($row["pri_nombre"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//tiempo respuesta
			$tiempo = trim($row["pri_respuesta"]);
			$tiempo = substr($tiempo,0,5);
			$salida.= '<td class = "text-center">'.$tiempo.'</td>';
			//tiempo solucion
			$tiempo = trim($row["pri_solucion"]);
			$tiempo = substr($tiempo,0,5);
			$salida.= '<td class = "text-center">'.$tiempo.'</td>';
			//tiempo recordatorio
			$tiempo = trim($row["pri_recordatorio"]);
			$tiempo = substr($tiempo,0,5);
			$salida.= '<td class = "text-center">'.$tiempo.'</td>';
			//sms
			$sms = trim($row["pri_sms"]);
			$sms = ($sms == 1)?'SI':'NO';
			$salida.= '<td class = "text-center">'.$sms.'</td>';
			//color
			$color = trim($row["pri_color"]);
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