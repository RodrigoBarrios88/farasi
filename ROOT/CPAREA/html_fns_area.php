<?php 
include_once('../html_fns.php');

function tabla_areas($codigo){
	$ClsAre = new ClsArea();
	$result = $ClsAre->get_area($codigo,'','','','',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida.= '<th class = "text-center" width = "50px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "150px">Sede</th>';
		$salida.= '<th class = "text-center" width = "150px">Sector o Torre</th>';
		$salida.= '<th class = "text-center" width = "150px">Nombre</th>';
		$salida.= '<th class = "text-center" width = "100px">Nivel</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["are_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarArea('.$codigo.');" title = "Editar Area" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitaArea('.$codigo.');" title = "Eliminar Area" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//codigo
			$codigo = Agrega_Ceros($row["are_codigo"]);
			$salida.= '<td class = "text-center">'.$codigo.'</td>';
			//sede
			$sede = trim($row["sed_nombre"]);
			$salida.= '<td class = "text-left">'.$sede.'</td>';
			//sector
			$sector = trim($row["sec_nombre"]);
			$salida.= '<td class = "text-left">'.$sector.'</td>';
			//nombre
			$nom = trim($row["are_nombre"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//Nivel
			$nivel = trim($row["are_nivel"]);
			$salida.= '<td class = "text-left">'.$nivel.'</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}




function tabla_areasQR($codigo){
	$ClsAre = new ClsArea();
	$result = $ClsAre->get_area($codigo,'','','','',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida.= '<th class = "text-center" width = "150px">Sede</th>';
		$salida.= '<th class = "text-center" width = "150px">Sector o Torre</th>';
		$salida.= '<th class = "text-center" width = "150px">&Aacute;rea</th>';
		$salida.= '<th class = "text-center" width = "100px">Nivel</th>';
		$salida.= '<th class = "text-center" width = "20px"><i class="fa fa-cogs"></i></th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//No.
			//codigo
			$codigo = Agrega_Ceros($row["are_codigo"]);
			$salida.= '<td class = "text-center">'.$codigo.'</td>';
			//sede
			$sede = trim($row["sed_nombre"]);
			$salida.= '<td class = "text-left">'.$sede.'</td>';
			//sector
			$sector = trim($row["sec_nombre"]);
			$salida.= '<td class = "text-left">'.$sector.'</td>';
			//nombre
			$nom = trim($row["are_nombre"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//Nivel
			$nivel = trim($row["are_nivel"]);
			$salida.= '<td class = "text-left">'.$nivel.'</td>';
			//--
			$codigo = $row["are_codigo"];
			$salida.= '<td class = "text-center" >';
			$salida.= '<button type="button" class="btn btn-white btn-lg" onclick = "verQR('.$codigo.')" title = "Gererar QR" ><i class="fa fa-qrcode"></i></button>';
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