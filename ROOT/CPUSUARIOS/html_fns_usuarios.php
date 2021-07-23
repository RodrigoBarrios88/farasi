<?php
include_once('../html_fns.php');

function tabla_usuarios($codigo){
	$ClsUsu = new ClsUsuario();
	$result = $ClsUsu->get_usuario($codigo,'','','','','1,0');
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" cellspacing="0" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th width = "5px" class = "text-center"><i class="fa fa-cog"></i></td>';
		$salida.= '<th width = "5px" class = "text-center"><i class="fa fa-cog"></i></td>';
		$salida.= '<th width = "150px" class = "text-center">Nombre del Usuario</td>';
		$salida.= '<th width = "100px" class = "text-center">Rol</td>';
		$salida.= '<th width = "100px" class = "text-center">E-mail</td>';
		$salida.= '<th width = "100px" class = "text-center">Teléfono.</td>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;	
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["usu_id"];
			$sit = $row["usu_situacion"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-sm" onclick = "seleccionarUsuario('.$codigo.');" title = "Editar Usuario" ><i class="fa fa-pencil-alt"></i></button> ';
					$salida.= '<a class="btn btn-info btn-sm" href = "FRMinfo_usuario.php?codigo='.$codigo.'" title = "Información del Usuario" ><i class="fa fa-info-circle"></i></a> ';
				$salida.= '</div>';
			$salida.= '</td>';
			$salida.= '<td class = "text-center" >';
			if($sit == 1){
				$salida.= '<a class="text-center" href="javascript:void(0);" onclick = "deshabilitarUsuario('.$codigo.');" title = "Inactivar Usuario" ><i class="fa fa-toggle-on fa-2x text-success"></i></button> ';
			}else if($sit == 0){
				$salida.= '<a class="text-center" href="javascript:void(0);" onclick = "habilitarUsuario('.$codigo.');" title = "Activar Usuario" ><i class="fa fa-toggle-off fa-2x text-muted"></i></button> ';
			}
			$salida.= '</td>';
			//nombre
			$nom = trim($row["usu_nombre"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//nivel
			$rol_description = trim($row["rol_nombre"]);
			$salida.= '<td class = "text-left">'.$rol_description.'</td>';
			//usuario
			$mail = $row["usu_mail"];
			$salida.= '<td class = "text-left">'.$mail.'</td>';
			//telefono
			$tel = $row["usu_telefono"];
			$salida.= '<td class = "text-center">'.$tel.'</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}


function tabla_ver_permisos_usu($codigo){
	$ClsPerm = new ClsPermiso();
	$result = $ClsPerm->get_asi_permisos($codigo,'','','','');
	if(is_array($result)){
		$salida = '<table class="table table-hover" width = "100%" >';
		$salida.= '<thead>';
		$salida.= '<tr class="thead-dark">';
		$salida.= '<th width = "20px" class = "text-center" >-</td>';
		$salida.= '<th width = "250px" >Permisos Asignados</td>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$grup1 = '';
		$i = 1;		
		foreach($result as $row){
			$grup2 = trim($row["perm_grupo"]);
			if($grup1 != $grup2){
				$salida.= '<tr class = "table-active">';
				//grupo
				$grupo = utf8_decode($row["gperm_desc"]);
				$salida.= '<td class = "text-center" ><i class = "fa fa-chevron-right"></i></td>';
				$salida.= '<td class = "text-left" >'.$grupo.'</td>';
				$salida.= '</tr>';
				$grup1 = $grup2;
			}
			$salida.= '<tr>';
			//codigo
			$cod = $row["perm_id"];
			$grup = utf8_decode($row["perm_grupo"]);
			$salida.= '<td class = "text-center" width = "70px">';
			$salida.= '<button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>';
			$salida.= '</td>';
			//nombre
			$nom = utf8_decode($row["perm_desc"]);
			$salida.= '<td align = "left" width = "450px">'.$nom.'</td>';
			$salida.= '</tr>';
			$i++;
		}
		$i--; //le quita la ultima vuelta de mas...
		$salida.= '</table>';
	}return $salida;
}


function tabla_asignacion_roles_usuarios(){
	$ClsUsu = new ClsUsuario();
	$result = $ClsUsu->get_usuario('','','','','',1,'');
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" cellspacing="0" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "20px"><i class = "fa fa-cogs"></i></td>';
		$salida.= '<th width = "150px" class = "text-center">Nombre del Usuario</td>';
		$salida.= '<th width = "100px" class = "text-center">Rol</td>';
		$salida.= '<th width = "100px" class = "text-center">E-mail</td>';
		$salida.= '<th width = "100px" class = "text-center">Teléfono.</td>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$i = 1;	
		foreach($result as $row){
			$sit = $row["usu_situacion"];
			$class = ($sit == 1)?"info":"danger";
			$salida.= '<tr>';
			//codigo
			$codigo = $row["usu_id"];
			$salida.= '<td class = "text-center" >';
			$salida.= '<button type="button" class="btn btn-success btn-sm" onclick = "cuadroRoles('.$codigo.');" title = "Seleccionar Usuario" ><i class="fa fa-check-square"></i></button>';
			$salida.= '</td>';
			//nombre
			$nom = trim($row["usu_nombre"]);
			$salida.= '<td class = "text-left">'.$nom.'</td>';
			//nivel
			$rol_description = trim($row["rol_nombre"]);
			$salida.= '<td class = "text-left">'.$rol_description.'</td>';
			//usuario
			$mail = $row["usu_mail"];
			$salida.= '<td class = "text-left">'.$mail.'</td>';
			//telefono
			$tel = $row["usu_telefono"];
			$salida.= '<td class = "text-center">'.$tel.'</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</table>';
	}else{
		$salida.= '<div class="alert alert-danger tex-center">';
			$salida.= '<i class = "fa fa-info-circle"></i> No hay Usuarios en el listado...</label> <br>';	
		$salida.= '</div>';
	}return $salida;
}

function tabla_encabezado_asignacion($usuario){
	///combo rol
	$ClsRol = new ClsRol();
	$result = $ClsRol->get_rol_libre('');
	$combo='';
	if(is_array($result)){
		$combo .= '<select name="rol" id="rol" class = "form-control select2" onchange = "cuadroPermisosRol(this.value);">';
		$combo .= '<option value="">Seleccione</option>';
		if(is_array($result)){
			foreach ($result as $row) {
				$combo .= '<option value='.trim($row["rol_id"]).'>'.trim($row["rol_nombre"]).'</option>';
			}
		}
		$combo .='</select>';
	}else{
		$combo = combos_vacios("rol");
	}
	//--
	$ClsUsu = new ClsUsuario();
	$result = $ClsUsu->get_usuario($usuario);
	if(is_array($result)){
		$salida = '<table class="table table-striped table-hover" width="100%" cellspacing="0" >';
		foreach($result as $row){
			$salida.= '</tr>';
			//--
			$codigo = $row["usu_id"];
			$nom = trim($row["usu_nombre"])." ".trim($row["usu_apellido"]);
			$rol_description = trim($row["rol_nombre"]);
			$salida.= '<tr>';
			$salida.= '<th class = "text-left">Usuario:</th>';
			$salida.= '<td class = "text-left"> '.$nom;
			$salida.= '<input type = "hidden" name = "usuario" id = "usuario" value = "'.$codigo.'" />';
			$salida.= '</td>';
			$salida.= '<th class = "text-left">Rol Actual: </th>';
			$salida.= '<td class = "text-left"> '.$rol_description.'</td>';
			$salida.= '</tr>';
			//--
			$salida.= '<tr>';
			$niv = trim($row["rol_nombre"]);
			$salida.= '<th class = "text-left" >Rol: </th>';
			$salida.= '<td align = "text-left" colspan = "3"> '.$combo.'</td>';
			$salida.= '</tr>';
			//--
			$salida.= '<tr>';
			$salida.= '<td class = "text-center" colspan = "4">';
			$salida.= '<button type="button" class="btn btn-secondary btn-sm" onclick = "Limpiar();"><i class="fa fa-times"></i> Cancelar</button> ';
			$salida.= '<button type="button" class="btn btn-success btn-sm" id = "btn-asignar" onclick = "asignarPermisos();"><i class="fa fa-save"></i> Asignar</button> ';
			$salida.= '</td>';
			//--
			$salida.= '</tr>';
		}
		$salida.= '</table>';
		$salida.= '</div>';
	}return $salida;
}


function tabla_permisos_libre(){
	$ClsPerm = new ClsPermiso();
	$cont = $ClsPerm->count_permisos($id,$grupo,$desc,$clv);if($cont>0){
		$result = $ClsPerm->get_permisos($id,$grupo,$desc,$clv);
		$salida.= '<form name = "f2" id = "f2" onsubmit = "return false">';
		$salida = '<table class="table table-hover" width = "100%" >';
		$grup1 = '';
		$i = 1;//cuenta permisos en general
		$j = 1;//cuenta grupos de permisos
		$k = 1;//cuenta permisos en un grupo
		foreach($result as $row){
			$grup2 = trim($row["perm_grupo"]);
			if($grup1 != $grup2){
				//-- conteo y listado de id's de chk en un solo grupo	
				$salida.= '<tr class = "table-active">';
				$salida.= '<input type = "hidden" name = "gruplist'.($j-1).'" id = "gruplist'.($j-1).'" value = "'.($k).'-'.($i-1).'" />';
				$salida.= '</tr>';
				//--
				$salida.= '<tr class = "table-active">';
				//grupo
				$grupo = trim($row["gperm_desc"]);
				$salida.= '<th class = "text-center" width = "70px">';
				$salida.= '<input type = "checkbox" name = "chkg'.$j.'" id = "chkg'.$j.'" onclick = "checkTodoGrupo('.$j.');" title = "Click para seleccionar todo el grupo" >';
				$salida.= '</th>';
				$salida.= '<th colspan = "2" height = "20px" >'.$grupo.'</th>';
				$salida.= '</tr>';
				$grup1 = $grup2;
				$j++; //controla la cantidad de grupos
				$k = 0;
			}
			$salida.= '<tr>';
			//codigo
			$codigo = $row["perm_id"];
			$grup = $row["perm_grupo"];
			$salida.= '<td class = "text-center" width = "70px">';
			$salida.= '<input type = "checkbox" name = "chk'.$i.'" id = "chk'.$i.'" title = "Click para seleccionar" >';
			$salida.= '<input type = "hidden" name = "cod'.$i.'" id = "cod'.$i.'" value = "'.$codigo.'" />';
			$salida.= '<input type = "hidden" name = "gru'.$i.'" id = "gru'.$i.'" value = "'.$grup.'" />';
			$salida.= '</td>';
			//grupo
			//nombre
			$nom = trim($row["perm_desc"]);
			$salida.= '<td align = "left" width = "450px">'.$nom.'</td>';
			//--
			$salida.= '</tr>';
			$i++;
			$k++;
		}
		$i--; //le quita la ultima vuelta de mas...
		$salida.= '<tr>';
		$salida.= '<input type = "hidden" name = "gruplist'.($j-1).'" id = "gruplist'.($j-1).'" value = "'.($k).'-'.($i).'" />';
		$salida.= '<input type = "hidden" name = "cant" id = "cant" value = "'.$i.'" />';
		$salida.= '</tr>';
		$salida.= '</table>';
		$salida.= '</form>';
	}return $salida;
}


function tabla_permisos_roll($rol){
	$ClsRol = new ClsRol();
	$result = $ClsRol->get_det_rol_outer_edit($rol);
	if(is_array($result)){
		$salida.= '<form name = "f2" id = "f2" onsubmit = "return false">';
		$salida = '<table class="table table-hover" width = "100%" >';
		$grup1 = '';
		$i = 1;		
		$j = 0;	
		foreach($result as $row){
			$grup2 = trim($row["perm_grupo"]);
			if($grup1 != $grup2){
				$salida.= '<tr class = "table-active">';
				//grupo
				$grupo = trim($row["gperm_desc"]);
				$salida.= '<th colspan = "2" height = "20px" >'.$grupo.'</th>';
				$salida.= '</tr>';
				$grup1 = $grup2;
			}
			$salida.= '<tr>';
			//codigo
			$codigo = $row["perm_id"];
			$grup = $row["perm_grupo"];
			$activ = $row["activo"];
			$chk = ($activ > 0)?"checked":"";
			$j = ($activ > 0)?$j+1:$j;
			$img = ($activ > 0)? "check" : "minus-square";
			$color = ($activ > 0)? "info" : "secondary";
			$salida.= '<td class = "text-center" width = "70px">';
			$salida.= '<button type="button" class="btn btn-'.$color.' btn-sm"><i class="fa fa-'.$img.'"></i></button>';
			$salida.= '<input type = "checkbox" name = "chk'.$i.'" id = "chk'.$i.'" '.$chk.' style = "display:none;" >';
			$salida.= '<input type = "hidden" name = "cod'.$i.'" id = "cod'.$i.'" value = "'.$codigo.'" />';
			$salida.= '<input type = "hidden" name = "gru'.$i.'" id = "gru'.$i.'" value = "'.$grup.'" />';
			$salida.= '</td>';
			//nombre
			$nom = trim($row["perm_desc"]);
			$salida.= '<td align = "left" width = "450px">'.$nom.'</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$i--; //le quita la ultima vuelta de mas...
		$salida.= '<tr>';
		$salida.= '<input type = "hidden" name = "cant" id = "cant" value = "'.$i.'" />';
		$salida.= '</tr>';
		$salida.= '</table>';
		$salida.= '</form>';
	}return $salida;
}?>