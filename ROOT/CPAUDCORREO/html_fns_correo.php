<?php 
include_once('../html_fns.php');

function tabla_correos($codigo){
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_correo($codigo,'');
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "50px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida.= '<th class = "text-center" width = "150px">Sede</th>';
		$salida.= '<th class = "text-center" width = "150px">Cuestionario de Auditor&iacute;a</th>';
		$salida.= '<th class = "text-center" width = "150px">Nombre</th>';
		$salida.= '<th class = "text-center" width = "150px">Correo</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["cor_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarCorreo('.$codigo.');" title = "Editar Correo" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "eliminarCorreo('.$codigo.');" title = "Eliminar Correo" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//codigo
			$codigo = Agrega_Ceros($row["cor_codigo"]);
			$salida.= '<td class = "text-center">'.$codigo.'</td>';
			//sede
			$sede = trim($row["sed_nombre"]);
			$salida.= '<td class = "text-left">'.$sede.'</td>';
			//auditoria
			$auditoria = trim($row["audit_nombre"]);
			$salida.= '<td class = "text-left">'.$auditoria.'</td>';
			//nombre
			$nom = trim($row["cor_nombre"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//nombre
			$correo = trim($row["cor_correo"]);
			$salida.= '<td class = "text-left">'.$correo.'</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}
?>