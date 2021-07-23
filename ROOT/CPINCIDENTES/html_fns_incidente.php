<?php
include_once('../html_fns.php');

function tabla_incidente($codigo)
{
	$ClsInc = new ClsIncidente();
	$result = $ClsInc->get_incidente($codigo, '', '', '', 1);

	if (is_array($result)) {
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida .= '<th class = "text-center" width = "10px">C&oacute;digo</th>';
		$salida .= '<th class = "text-center" width = "150px">Categor&iacute;a</th>';
		$salida .= '<th class = "text-center" width = "150px">Prioridad</th>';
		$salida .= '<th class = "text-center" width = "150px">Nombre</th>';
		$salida .= '<th class = "text-center" width = "10px"></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 0;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$codigo = $row["inc_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarIncidente(' . $codigo . ');" title = "Editar Incidente" ><i class="fa fa-pencil"></i></button>';
			$salida .= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarIncidente(' . $codigo . ');" title = "Eliminar Incidente" ><i class="fa fa-trash"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			//codigo
			$codigo = Agrega_Ceros($row["inc_codigo"]);
			$salida .= '<td class = "text-center">' . $codigo . '</td>';
			//categoria
			$categoria = trim($row["cat_nombre"]);
			$salida .= '<td class = "text-left">' . $categoria . '</td>';
			//prioridad
			$prioridad = trim($row["pri_nombre"]);
			$salida .= '<td class = "text-left">' . $prioridad . '</td>';
			//nombre
			$nom = trim($row["inc_nombre"]);
			$salida .= '<td class = "text-left">' . $nom . '</td>';
			//codigo
			$codigo = $row["inc_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-info btn-xs" onclick = "usuariosIncidente(' . $codigo . ');" title = "Personas Asignadas a este incidente" ><i class="fa fa-user"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}

	return $salida;
}
