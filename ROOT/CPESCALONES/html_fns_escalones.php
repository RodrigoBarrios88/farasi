<?php 
include_once('../html_fns.php');

function tabla_detalles($detalle,$escalon,$categoria){
	$ClsEsc = new ClsEscalon();
	$result = $ClsEsc->get_detalle_escalon('',$escalon,$categoria);$salida.= '<div class="row">';
	$salida.= '<div class="col-xs-6 col-md-6 text-left">';
	$salida.= '<button type="button" class="btn btn-primary" onclick="masDetalle('.$categoria.','.$escalon.');"><i class="fa fa-plus"></i> Agregar Notificaci&oacute;n</button>';
	$salida.= '</div>';
	$salida.= '</div>';
	$salida = '<table class="table table-striped width="100%" >';
	$salida.= '<thead>';
	$salida.= '<tr>';
	$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
	$salida.= '<th class = "text-center" width = "150px">Nombre</th>';
	$salida.= '<th class = "text-center" width = "120px">E-mail</th>';
	$salida.= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
	$salida.= '</tr>';
	$salida.= '</thead>';
	$salida.= '<tbody>';
	if(is_array($result)){
		$i=0;
		foreach($result as $row){
			$codigo = $row["not_codigo"];
			if($detalle == $codigo){
				$salida.= '<tr>';
				//codigo
				$codigo = Agrega_Ceros($row["not_codigo"]);
				$salida.= '<td class = "text-center"># '.$codigo.'</td>';
				//nombre
				$nombre = trim($row["not_nombre"]);
				$salida.= '<td class = "text-left"><input type="text" class="form-control" id ="detNombre" name ="detNombre" value="'.$nombre.'" onkeyup = "texto(this);" ></td>';
				//mail
				$mail = trim($row["not_mail"]);
				$salida.= '<td class = "text-left"><input type="text" class="form-control" id ="detMail" name ="detMail" value="'.$mail.'" onkeyup = "texto(this);" ></td>';
				//codigo
				$codigo = $row["not_codigo"];
				$salida.= '<td class = "text-center" >';
					$salida.= '<div class="btn-group">';
						$salida.= '<button type="button" class="btn btn-primary btn-xs" onclick = "saveDetalle('.$categoria.','.$escalon.','.$codigo.');" title = "Grabar Notificacion" ><i class="fa fa-save"></i></button>';
						$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "confirmDeleteDetalle('.$categoria.','.$escalon.','.$codigo.');" title = "Eliminar Notificacion" ><i class="fa fa-trash"></i></button>';
					$salida.= '</div>';
				$salida.= '</td>';
				//--
				$salida.= '</tr>';
			}else{
				$salida.= '<tr>';
				//codigo
				$codigo = Agrega_Ceros($row["not_codigo"]);
				$salida.= '<td class = "text-center"># '.$codigo.'</td>';
				//nombre
				$nom = trim($row["not_nombre"]);
				$salida.= '<td class = "text-left">'.$nom.'</td>';
				//mail
				$mail = trim($row["not_mail"]);
				$salida.= '<td class = "text-left">'.$mail.'</td>';
				//codigo
				$codigo = $row["not_codigo"];
				$salida.= '<td class = "text-center" >';
					$salida.= '<div class="btn-group">';
						$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "buscaDetalle('.$categoria.','.$escalon.','.$codigo.');" title = "Editar Notificacion" ><i class="fa fa-pencil"></i></button>';
						$salida.= '<button type="button" class="btn btn-danger btn-xs" onclick = "confirmDeleteDetalle('.$categoria.','.$escalon.','.$codigo.');" title = "Eliminar Notificacion" ><i class="fa fa-trash"></i></button>';
					$salida.= '</div>';
				$salida.= '</td>';
				//--
				$salida.= '</tr>';
			}
			$i++;
		}
		$salida.= '</tbody>';
	}
	$salida.= '</table>';return $salida;
}

function edit_escalon($escalon,$categoria){
	$ClsEsc = new ClsEscalon();
	$result = $ClsEsc->get_escalon('',$categoria);
	if(is_array($result)){
		$i = 1;
		foreach($result as $row){
			//codigo
			$codigo = trim($row["esc_codigo"]);
			$nombre = trim($row["esc_nombre"]);
			$posicion = trim($row["esc_posicion"]);
			//--
			if($escalon == $codigo){
				$salida = '<div class="card card-plain">';
					$salida.= '<div class="card-body m-2" role="tab" id="panel'.$codigo.'">';
					$salida.= '<a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$codigo.'" aria-expanded="false" aria-controls="collapse'.$codigo.'">';
					$salida.= '<div class="row">';
					$salida.= '<div class="col-md-5">';
					$salida.= $i.'. <label>Escal&oacute;n:</label> <span class="text-danger">*</span> <input type="text" class="form-control" id ="nombre" name ="nombre" value="'.$nombre.'" onkeyup = "texto(this);" > ';
					$salida.= '</div>';
					$salida.= '<div class="col-md-3">';
					$salida.= '<label>Posici&oacute;n:</label> <span class="text-danger">*</span> '.combo_posicion($posicion);
					$salida.= '</div>';
					
					$salida.= '<div class="col-md-3 text-right">';
					$salida.= '<button type="button" class="btn btn-primary" title = "Editar Escalon" onclick="saveEscalon('.$categoria.','.$codigo.');"><span class="fa fa-save"></span></button>';
					$salida.= '<button type="button" class="btn btn-danger" title = "Eliminar Escalon" onclick="confirmDeleteEscalon('.$categoria.','.$codigo.');"><span class="fa fa-trash"></span></button>';
					$salida.= '</div>';
					
					
					$salida.= '<div class="col-md-1"><br>';
					$salida.= '<i class="nc-icon nc-minimal-down"></i>';
					$salida.= '</div>';
					$salida.= '</div>';
					$salida.= '</a>';
					$salida.= '</div>';
					//--
					$salida.= '<div id="collapse'.$codigo.'" class="collapse" role="tabpanel" aria-labelledby="panel'.$codigo.'">';
					$salida.= '<div class="card-body">';
						$salida.= tabla_detalles('',$codigo,$categoria);
					$salida.= '</div>';
					$salida.= '</div>';
				$salida.= '</div>';
			}else{
				$salida = '<div class="card card-plain">';
				$salida.= '<div class="card-body m-2" role="tab" id="panel'.$codigo.'">';
					$salida.= '<a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$codigo.'" aria-expanded="false" aria-controls="collapse'.$codigo.'">';
					$salida.= '<div class="row">';
					$salida.= '<div class="col-md-5">';
					$salida.= $i.'. <label>Escal&oacute;n:</label>'.$nombre;
					$salida.= '</div>';
					$salida.= '<div class="col-md-3">';
					$salida.= '<label>Posici&oacute;n:</label> '.$posicion;
					$salida.= '</div>';
					$salida.= '<div class="col-md-3 text-right">';
					$salida.= '<button type="button" class="btn btn-white" title = "Editar Escalon" onclick="buscaEscalones('.$categoria.','.$codigo.');"><span class="fa fa-pencil"></span></button>';
					$salida.= '<button type="button" class="btn btn-danger" title = "Eliminar Escalon" onclick="confirmDeleteEscalon('.$categoria.','.$codigo.');"><span class="fa fa-trash"></span></button>';
					$salida.= '</div>';
					$salida.= '<div class="col-md-1">';
					$salida.= '<i class="nc-icon nc-minimal-down"></i>';
					$salida.= '</div>';
					$salida.= '</div>';
					$salida.= '</a>';
					$salida.= '</div>';
					//--
					$salida.= '<div id="collapse'.$codigo.'" class="collapse" role="tabpanel" aria-labelledby="panel'.$codigo.'">';
					$salida.= '<div class="card-body">';
						$salida.= tabla_detalles('',$codigo,$categoria);
					$salida.= '</div>';
					$salida.= '</div>';
				$salida.= '</div>';
			}
			$i++;
		}
	}else{
		$salida = '<div class="row">';
		$salida.= '<div class="col-xs-12 col-md-12">';
		$salida.= '<h5 class="alert alert-warning text-center">';
		$salida.= '<i class="fa fa-information-circle"></i> No hay Escalones en esta categor&iacute;a...';
		$salida.= '</h5>';
		$salida.= '</div>';
		$salida.= '</div>';
	}return $salida;
}

function combo_posicion($posicion){
	$salida.= '<select class="form-control" name = "posicion" id = "posicion" >';
	$salida.= '<option value="">Seleccione</option>';
	for($i = 1; $i <= 10; $i++){
		if($posicion == $i){
			$salida.= '<option value="'.$i.'" selected >'.$i.'</option>';
		}else{
			$salida.= '<option value="'.$i.'">'.$i.'</option>';
		}
	}
	$salida.= '</select>';
	return $salida;
}
?>