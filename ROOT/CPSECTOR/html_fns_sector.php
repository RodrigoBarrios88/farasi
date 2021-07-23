<?php 
include_once('../html_fns.php');

function tabla_sectores($codigo){
	$ClsSec = new ClsSector();
	$result = $ClsSec->get_sector($codigo,'','',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "50px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida.= '<th class = "text-center" width = "150px">Sede</th>';
		$salida.= '<th class = "text-center" width = "150px">Nombre</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["sec_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarSector('.$codigo.');" title = "Editar Sector" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitaSector('.$codigo.');" title = "Eliminar Sector" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//codigo
			$codigo = Agrega_Ceros($row["sec_codigo"]);
			$salida.= '<td class = "text-center">'.$codigo.'</td>';
			//sede
			$sede = trim($row["sed_nombre"]);
			$salida.= '<td class = "text-left">'.$sede.'</td>';
			//nombre
			$nom = trim($row["sec_nombre"]);
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