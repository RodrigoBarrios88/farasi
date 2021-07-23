<?php 
include_once('../html_fns.php');

function tabla_sedes($codigo){
	$ClsSed = new ClsSede();
	$result = $ClsSed->get_sede($codigo,'','','',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" cellspacing="0" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "50px"><i class="fas fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "20px">Código</th>';
		$salida.= '<th class = "text-center" width = "150px">Nombre de la Sede</th>';
		$salida.= '<th class = "text-center" width = "250px">dirección</th>';
		$salida.= '<th class = "text-center" width = "20px"><i class="fas fa-globe"></i></th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$cod = $row["sed_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-sm" onclick = "seleccionarSede('.$cod.');" title = "Editar Sede" ><i class="fa fa-pencil-alt"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-sm" onclick = "deshabilitaSede('.$cod.');" title = "Deshabilita Sede" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//codigo
			$codigo = Agrega_Ceros($row["sed_codigo"]);
			$salida.= '<td class = "text-center">'.$codigo.'</td>';
			//nombre
			$nom = trim($row["sed_nombre"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//Direccion
			$dir = trim($row["sed_direccion"]);
			$salida.= '<td class = "text-justify">'.$dir.'</td>';
			//mapa
			$lat = $row["sed_latitud"];
			$long = $row["sed_longitud"];
			$salida.= '<td class = "text-center">';
			$salida.= '<button type="button" class="btn btn-info btn-sm" onclick = "viewMap(\''.$lat.'\',\''.$long.'\')" title = "Ver Ubicación" > &nbsp; <i class="fa fa-map-marker"></i> &nbsp; </button> &nbsp; ';
			$salida.= '</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}
?>