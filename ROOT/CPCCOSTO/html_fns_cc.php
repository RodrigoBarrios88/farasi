<?php 
include_once('../html_fns.php');

function tabla_centro_costos($codigo){
	$ClsCC = new ClsCentroCosto();
	$result = $ClsCC->get_centro_costo($codigo,'',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" cellspacing="0" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "30px"><i class="fas fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "20px">Código</th>';
		$salida.= '<th class = "text-left" width = "250px">Nombre de la Centros de Costo</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = trim($row["cc_codigo"]);
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-sm" onclick = "seleccionarCentroCosto('.$codigo.');" title = "Editar Centro de Costo" ><i class="fa fa-pencil-alt"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-sm" onclick = "deshabilitarCentroCosto('.$codigo.');" title = "Eliminar Centro de Costo" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//cliente
			$codigo = Agrega_Ceros($row["cc_codigo"]);
			$salida.= '<td class = "text-center">'.$codigo.'</td>';
			//nombre
			$nom = trim($row["cc_nombre"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}
?>