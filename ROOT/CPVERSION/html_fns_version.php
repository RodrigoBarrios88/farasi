<?php 
include_once('../html_fns.php');

function tabla_version($codigo){
	$ClsVer = new ClsVersion();
	$result = $ClsVer->get_version($codigo,'','',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" cellspacing="0" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "50px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "20px">Código</th>';
		$salida.= '<th class = "text-center" width = "150px">Software</th>';
		$salida.= '<th class = "text-center" width = "50px">Plataforma</th>';
		$salida.= '<th class = "text-center" width = "100px">Versión</th>';
		$salida.= '<th class = "text-center" width = "100px">Última Actualización</th>';
		$salida.= '<th class = "text-center" width = "100px">Usuario</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["ver_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-sm" onclick = "seleccionarVersion('.$codigo.');" title = "Click para seleccionar" ><i class="fa fa-pencil-alt"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-sm" onclick = "eliminarVersion('.$codigo.');" title = "Eliminar Version" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//codigo
			$codigo = trim($row["ver_codigo"]);
			$salida.= '<td class = "text-center"># '.$codigo.'</td>';
			//nombre
			$software = trim($row["ver_software"]);
			$salida.= '<td class = "text-left">'.$software.'</td>';
			//plataforma
			$plataforma = trim($row["ver_plataforma"]);
			$salida.= '<td class = "text-center">'.$plataforma.'</td>';
			//version
			$version = trim($row["ver_version"]);
			$salida.= '<td class = "text-center">'.$version.'</td>';
			//fecha update
			$freg = trim($row["ver_fecha_update"]);
			$freg = cambia_fechaHora($freg);
			$salida.= '<td class = "text-center">'.$freg.'</td>';
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
}?>