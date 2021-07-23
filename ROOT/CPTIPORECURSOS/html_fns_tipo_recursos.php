<?php 
include_once('../html_fns.php');

function tabla_tipo_recursos($codigo){
	$ClsRec = new ClsRecursos();
	$result = $ClsRec->get_tipo_recursos($codigo,'',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" cellspacing="0" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "30px"><i class="fas fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida.= '<th class = "text-center" width = "250px">Clasificaci&oacute;n del Recurso</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = trim($row["tip_codigo"]);
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-sm" onclick = "seleccionarTipo('.$codigo.');" title = "Editar Clasificaci&oacute;n del Recurso" ><i class="fa fa-pencil-alt"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-sm" onclick = "deshabilitarTipo('.$codigo.');" title = "Eliminar Clasificaci&oacute;n del Recurso" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//cliente
			$codigo = Agrega_Ceros($row["tip_codigo"]);
			$salida.= '<td class = "text-center">'.$codigo.'</td>';
			//nombre
			$nom = trim($row["tip_nombre"]);
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