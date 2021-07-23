<?php 
include_once('../html_fns.php');

function tabla_categorias_chk($codigo){
	$ClsCat = new ClsCategoria();
	$result = $ClsCat->get_categoria_checklist($codigo,'',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "50px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida.= '<th class = "text-center" width = "250px">Nombre</th>';
		$salida.= '<th class = "text-center" width = "20px">Color</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["cat_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarCategoria('.$codigo.');" title = "Editar Categoria" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarCategoria('.$codigo.');" title = "Eliminar Categoria" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//codigo
			$codigo = Agrega_Ceros($row["cat_codigo"]);
			$salida.= '<td class = "text-center">'.$codigo.'</td>';
			//nombre
			$nom = trim($row["cat_nombre"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//color
			$color = trim($row["cat_color"]);
			$salida.= '<td class = "text-center"><i class="fa fa-square fa-2x" style="color: '.$color.'"></i></td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}



function tabla_categorias_hd($codigo){
	$ClsCat = new ClsCategoria();
	$result = $ClsCat->get_categoria_helpdesk($codigo,'',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "50px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida.= '<th class = "text-center" width = "250px">Nombre</th>';
		$salida.= '<th class = "text-center" width = "20px">Color</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["cat_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarCategoria('.$codigo.');" title = "Editar Categoria" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarCategoria('.$codigo.');" title = "Eliminar Categoria" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//codigo
			$codigo = Agrega_Ceros($row["cat_codigo"]);
			$salida.= '<td class = "text-center">'.$codigo.'</td>';
			//nombre
			$nom = trim($row["cat_nombre"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//color
			$color = trim($row["cat_color"]);
			$salida.= '<td class = "text-center"><i class="fa fa-square fa-2x" style="color: '.$color.'"></i></td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}



function tabla_categorias_auditoria($codigo){
	$ClsCat = new ClsCategoria();
	$result = $ClsCat->get_categoria_auditoria($codigo,'',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "50px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida.= '<th class = "text-center" width = "250px">Nombre</th>';
		$salida.= '<th class = "text-center" width = "20px">Color</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["cat_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarCategoria('.$codigo.');" title = "Editar Categoria" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarCategoria('.$codigo.');" title = "Eliminar Categoria" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//codigo
			$codigo = Agrega_Ceros($row["cat_codigo"]);
			$salida.= '<td class = "text-center">'.$codigo.'</td>';
			//nombre
			$nom = trim($row["cat_nombre"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//color
			$color = trim($row["cat_color"]);
			$salida.= '<td class = "text-center"><i class="fa fa-square fa-2x" style="color: '.$color.'"></i></td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}



function tabla_categorias_ppm($codigo){
	$ClsCat = new ClsCategoria();
	$result = $ClsCat->get_categoria_ppm($codigo,'',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "50px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida.= '<th class = "text-center" width = "250px">Nombre</th>';
		$salida.= '<th class = "text-center" width = "20px">Color</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["cat_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarCategoria('.$codigo.');" title = "Editar Categoria" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarCategoria('.$codigo.');" title = "Eliminar Categoria" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//codigo
			$codigo = Agrega_Ceros($row["cat_codigo"]);
			$salida.= '<td class = "text-center">'.$codigo.'</td>';
			//nombre
			$nom = trim($row["cat_nombre"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//color
			$color = trim($row["cat_color"]);
			$salida.= '<td class = "text-center"><i class="fa fa-square fa-2x" style="color: '.$color.'"></i></td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}



function tabla_categorias_indicador($codigo){
	$ClsCat = new ClsCategoria();
	$result = $ClsCat->get_categoria_indicador($codigo,'',1);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "50px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida.= '<th class = "text-center" width = "250px">Nombre</th>';
		$salida.= '<th class = "text-center" width = "20px">Color</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["cat_codigo"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarCategoria('.$codigo.');" title = "Editar Categoria" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "deshabilitarCategoria('.$codigo.');" title = "Eliminar Categoria" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//codigo
			$codigo = Agrega_Ceros($row["cat_codigo"]);
			$salida.= '<td class = "text-center">'.$codigo.'</td>';
			//nombre
			$nom = trim($row["cat_nombre"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//color
			$color = trim($row["cat_color"]);
			$salida.= '<td class = "text-center"><i class="fa fa-square fa-2x" style="color: '.$color.'"></i></td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}

?>