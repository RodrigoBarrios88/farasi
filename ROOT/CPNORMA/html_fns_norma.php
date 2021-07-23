<?php 
include_once('../html_fns.php');

function tabla_normas($codigo){
	$ClsNor = new ClsNorma();
	$result = $ClsNor->get_norma($codigo,'',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" cellspacing="0" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "30px"><i class="fas fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "20px">Código</th>';
		$salida.= '<th class = "text-center" width = "250px">Nombre de la Norma</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = trim($row["nor_codigo"]);
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-sm" onclick = "seleccionarNorma('.$codigo.');" title = "Editar Norma" ><i class="fa fa-pencil-alt"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-sm" onclick = "deshabilitarNorma('.$codigo.');" title = "Eliminar Norma" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//cliente
			$codigo = Agrega_Ceros($row["nor_codigo"]);
			$salida.= '<td class = "text-center">'.$codigo.'</td>';
			//nombre
			$nom = trim($row["nor_nombre"]);
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