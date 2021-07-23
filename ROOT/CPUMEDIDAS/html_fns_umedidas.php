<?php 
include_once('../html_fns.php');

function get_umed_class($class) {
	switch($class) {
		case "$": return 'Moneda';
		case "E": return 'Energia';
		case "M": return 'Dimensiones';
		case "P": return 'Peso';
		case "S": return 'Area';
		case "C": return 'Volumen';
		case "T": return 'Tiempo';
		case "1": return 'Otros';
		default:
		return "Otros";
	}
}

function tabla_unidades_de_medida($codigo){
	$ClsUmed = new ClsUmedida();
	$result = $ClsUmed->get_unidad('',$codigo);

	if(is_array($result)){
		$salida = '<table id="tabla" class="table table-striped dataTables-example" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "50px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "50px">C&oacute;digo</th>';
		$salida.= '<th class = "text-left" width = "100px">Unidad de Medida</th>';
		$salida.= '<th class = "text-left" width = "50px">Simbolo o Abreviatura</th>';
		$salida.= '<th class = "text-left" width = "40px">Clase</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["umed_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarUmedida('.$codigo.');" title = "Editar Unidad" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarUmedida('.$codigo.');" title = "Eliminar Unidad" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//codigo
			$codigo = Agrega_Ceros($row["umed_codigo"]);
			$salida.= '<td class = "text-center">'.$codigo.'</td>';
			//nombre
			$nom = trim($row["umed_desc_lg"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//abreviacion
			$abrv = trim($row["umed_desc_ct"]);
			$salida.= '<td class = "text-left">'.$abrv.'</td>';
			//clase
			$class = get_umed_class(trim($row["umed_clase"]));
			$salida.= '<td class = "text-left">'.$class.'</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}
	return $salida;
}


?>