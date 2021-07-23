<?php 
include_once('../../html_fns.php');


function tabla_departamentos($codigo){
	$ClsDep = new ClsDepartamento();
	$result = $ClsDep->get_departamento($codigo,'',1);if(is_array($result)){
			$salida.= '<div class="panel-body">';
            $salida.= '<div class="table-responsive">';
            $salida = '<table class="table table-striped table-bordered table-hover dataTables-example" >';
			$salida.= '<thead>';
			$salida.= '<tr>';
			$salida.= '<th class = "text-center" width = "10px">No.</th>'; 
			$salida.= '<th class = "text-center" width = "200px">NOMBRE DEL DEPARTAMENTO</th>';
			$salida.= '</tr>';
			$salida.= '</thead>';
			$salida.= '<tbody>';
		$i= 1;
		foreach($result as $row){
			$salida.= '<tr>';
			//i
			$salida.= '<td class = "text-center">'.$i.'.</td>';
			//nombre
			$nom = utf8_decode($row["dep_nombre"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
			$salida.= '</tbody>';
			$salida.= '</table>';
			$salida.= '</div>';
			$salida.= '</div>';
	}return $salida;
}?>
