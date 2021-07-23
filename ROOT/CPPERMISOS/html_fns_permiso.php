<?php 
include_once('../html_fns.php');

function tabla_grupos($codigo){
	$ClsPerm = new ClsPermiso();
	$result = $ClsPerm->get_grupo($codigo,"","");
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" cellspacing="0" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "20px"><span class="fa fa-cog"></span></td>';
		$salida.= '<th class = "text-center" width = "60px">Código</td>';
		$salida.= '<th class = "text-center" width = "270px">Nombre del Grupo</td>';
		$salida.= '<th class = "text-center" width = "100px">Clave</td>';
		$salida.= '<th class = "text-center" width = "100px">Situación</td>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$i=0;	
		foreach($result as $row){
			$salida.= '<tr>';
			//--
			$grupo = $row["gperm_id"];
			$situacion = $row["gperm_situacion"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white" onclick = "seleccionarGrupo('.$grupo.');" title = "Click para seleccionar" ><i class="fa fa-pencil-alt"></i></button>';
					if($situacion == 1){
					$salida.= '<button type="button" class="btn btn-danger" onclick = "deshabilitarGrupo('.$grupo.');" title = "Deshabilitar Grupo de Permisos" ><i class="fa fa-trash"></i></button>';
					}else{
					$salida.= '<button type="button" class="btn btn-info" onclick = "deshabilitarGrupo('.$grupo.');" title = "Habilitar Grupo de Permisos" ><i class="fa fa-circle"></i></button>';
					}
				$salida.= '</div>';
			$salida.= '</td>';
			//codigo
			$salida.= '<td class = "text-center"> # '.Agrega_Ceros($grupo).'</td>';
			//nombre
			$descripcion = trim($row["gperm_desc"]);
			$salida.= '<td align = "left">'.$descripcion.'</td>';
			//clave
			$clv = $row["gperm_clave"];
			$salida.= '<td class = "text-center">'.$clv.'</td>';
			//situacion
			$situacion = ($situacion == 1)?'<strong class="text-success">ACTIVO</strong>':'<strong class="text-danger">INACTIVO</strong>';
			$salida.= '<td class = "text-center" >'.$situacion.'</td>';
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</table>';
	}return $salida;
}


function tabla_permisos($codigo,$grupo){
	$ClsPerm = new ClsPermiso();
	$result = $ClsPerm->get_permisos($codigo,$grupo,$descripcion,$clv);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" cellspacing="0" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "20px"><span class="fa fa-cog"></span></td>';
		$salida.= '<th class = "text-center" width = "20px">No.</td>';
		$salida.= '<th class = "text-center" width = "50px">Código</td>';
		$salida.= '<th class = "text-center" width = "150px">Grupo</td>';
		$salida.= '<th class = "text-center" width = "200px">Nombre del Permiso</td>';
		$salida.= '<th class = "text-center" width = "100px">Clave</td>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$i=1;	
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["perm_id"];
			$grupo = $row["perm_grupo"];
			$salida.= '<td class = "text-center" >';
			$salida.= '<button type="button" class="btn btn-white" onclick = "seleccionarPermiso('.$codigo.','.$grupo.');" title = "Click para seleccionar" ><i class="fas fa-pencil-alt"></i></button>';
			$salida.= '</td>';
			//No.
			$salida.= '<td class = "text-center">'.$i.'. </td>';
			//codigo
			$salida.= '<td class = "text-center"># '.Agrega_Ceros($codigo).'</td>';
			//grupo
			$grupo = trim($row["gperm_desc"]);
			$salida.= '<td class = "text-left">'.$grupo.'</td>';
			//nombre
			$nombre = trim($row["perm_desc"]);
			$salida.= '<td class = "text-left">'.$nombre.'</td>';
			//clave
			$clv = $row["perm_clave"];
			$salida.= '<td class = "text-center">'.$clv.'</td>';
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</table>';
	}return $salida;
}


function tabla_roles(){
	$ClsRol = new ClsRol();
	$result = $ClsRol->get_rol($codigo);
	if(is_array($result)){
		$salida = '<table class="table table-striped" id="tabla" width="100%" cellspacing="0" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "10px">No.</td>';
		$salida.= '<th class = "text-center" width = "20px"><i class="fa fa-cogs"></i></td>';
		$salida.= '<th class = "text-center" width = "150px">Nombre del Rol</td>';
		$salida.= '<th class = "text-center" width = "400px">Descripci&oacute;n del Rol</td>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$i = 1;	
		foreach($result as $row){
			$salida.= '<tr>';
			//..
			$salida.= '<td class = "text-center">'.$i.'. </td>';
			//codigo
			$codigo = $row["rol_id"];
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<a class="btn btn-white" href="FRMeditrol.php?codigo='.$codigo.'" title = "Editar Usuario" ><i class="fa fa-pencil-alt"></i></a> ';
					$salida.= '<a class="btn btn-info" href="FRMinfo_rol.php?codigo='.$codigo.'" title = "Click para ver el detalle de permisos en el rol" ><i class="fa fa-info-circle"></i></a>';
					$salida.= '<button type="button" class="btn btn-danger" onclick = "eliminarRol('.$codigo.');" title = "Click para eliminar el rol" ><i class="fa fa-trash"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//nombre
			$nombre = trim($row["rol_nombre"]);
			$salida.= '<td class = "text-left">'.$nombre.'</td>';
			//descripcion
			//$descripcion = trim($row["rol_desc"]);
			$salida.= '<td align = "justify">'.$descripcion.'</td>';
			$salida.= '</tr>';
			$i++;
		}
			$i--;
		$salida.= '</table>';
	}return $salida;
}


function tabla_ver_permisos($rol){
	$ClsRol = new ClsRol();
	$result = $ClsRol->get_det_rol('','',$rol);
	if(is_array($result)){
		$salida = '<table class="table table-hover" width = "100%" >';
		$salida.= '<thead>';
		$salida.= '<tr class="thead-dark">';
		$salida.= '<th class = "text-center" width = "60px">No.</td>';
		$salida.= '<th class = "text-center" width = "200px">Permisos</td>';
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
			$salida.= '<button type="button" class="btn btn-info"><i class="fa fa-check"></i></button>';
			$salida.= '</td>';
			//nombre
			$nombre = utf8_decode($row["perm_desc"]);
			$salida.= '<td align = "left" width = "450px">'.$nombre.'</td>';
			$salida.= '</tr>';
			$i++;
		}
		$i--; //le quita la ultima vuelta de mas...
		$salida.= '</table>';
	}return $salida;
}


function tabla_permisos_asignacion(){
	$ClsPerm = new ClsPermiso();
	$result = $ClsPerm->get_permisos($id,$grupo,$descripcion,$clv);
	if(is_array($result)){	
		$salida = '<table class="table table-hover" width = "100%" >';
		$salida.= '<thead>';
			
		$grupo1 = '';
		$i = 1;//cuenta permisos en general
		$j = 1;//cuenta grupos de permisos
		$k = 1;//cuenta permisos en un grupo
		foreach($result as $row){
			$grupo2 = trim($row["perm_grupo"]);
			if($grupo1 != $grupo2){
				//-- conteo y listado de id's de chk en un solo grupo	
				$salida.= '<tr>';
				$salida.= '<input type = "hidden" name = "gruplist'.($j-1).'" id = "gruplist'.($j-1).'" value = "'.($k).'-'.($i-1).'" />';
				$salida.= '</tr>';
				//--
				$salida.= '<tr class = "table-active">';
				//grupo
				$grupo = utf8_decode($row["gperm_desc"]);
				$salida.= '<th class = "text-center" width = "50px">';
				$salida.= '<input type = "checkbox" name = "chkg'.$j.'" id = "chkg'.$j.'" onclick = "checkTodoGrupo('.$j.');" title = "Click para seleccionar todo el grupo" >';
				$salida.= '</th>';
				$salida.= '<th class = "text-center" colspan = "2" height = "20px" >'.$grupo.'</th>';
				$salida.= '</tr>';
				$grupo1 = $grupo2;
				$j++; //controla la cantidad de grupos
				$k = 0;
			}
			$salida.= '<tr>';
			//codigo
			$codigo = $row["perm_id"];
			$grupo = $row["perm_grupo"];
			$salida.= '<td class = "text-center" width = "70px">';
			$salida.= '<input type = "checkbox" name = "chk'.$i.'" id = "chk'.$i.'" title = "Click para seleccionar" >';
			$salida.= '<input type = "hidden" name = "codigo'.$i.'" id = "codigo'.$i.'" value = "'.$codigo.'" />';
			$salida.= '<input type = "hidden" name = "grupo'.$i.'" id = "grupo'.$i.'" value = "'.$grupo.'" />';
			$salida.= '</td>';
			//grupo
			//nombre
			$nombre = utf8_decode($row["perm_desc"]);
			$salida.= '<td align = "left" width = "450px">'.$nombre.'</td>';
			$salida.= '</tr>';
			$i++;
			$k++;
		}
		$i--; //le quita la ultima vuelta de mas...
		$salida.= '<tr>';
		$salida.= '<input type = "hidden" name = "gruplist'.($j-1).'" id = "gruplist'.($j-1).'" value = "'.($k).'-'.($i).'" />';
		$salida.= '<input type = "hidden" name = "cantidad" id = "cantidad" value = "'.$i.'" />';
		$salida.= '</tr>';
		$salida.= '</table>';
	}return $salida;
}

function tabla_permisos_editar($rol){
	$ClsRol = new ClsRol();
	$result = $ClsRol->get_det_rol_outer_edit($rol);
	if(is_array($result)){
		$salida = '<table class="table table-hover" width = "100%" >';
		$salida.= '<thead>';
		$grupo1 = '';
		$i = 1;//cuenta permisos en general
		$j = 1;//cuenta grupos de permisos
		$k = 1;//cuenta permisos en un grupo
		foreach($result as $row){
			$grupo2 = trim($row["perm_grupo"]);
			if($grupo1 != $grupo2){
				//-- conteo y listado de id's de chk en un solo grupo	
				$salida.= '<tr>';
				$salida.= '<input type = "hidden" name = "gruplist'.($j-1).'" id = "gruplist'.($j-1).'" value = "'.($k).'-'.($i-1).'" />';
				$salida.= '</tr>';
				//--
				$salida.= '<tr class = "table-active">';
				//grupo
				$grupo = utf8_decode($row["gperm_desc"]);
				$salida.= '<th class = "text-center" width = "70px">';
				$salida.= '<input type = "checkbox" name = "chkg'.$j.'" id = "chkg'.$j.'" onclick = "checkTodoGrupo('.$j.');" title = "Click para seleccionar todo el grupo" >';
				$salida.= '</th>';
				$salida.= '<th class = "text-center" colspan = "2" height = "20px" >'.$grupo.'</th>';
				//--
				$salida.= '</tr>';
				$grupo1 = $grupo2;
				$j++; //controla la cantidad de grupos
				$k = 0;
			}
			$salida.= '<tr>';
			//codigo
			$codigo = $row["perm_id"];
			$grupo = $row["perm_grupo"];
			$activ = $row["activo"];
			$chk = ($activ > 0)?"checked":"";
			$salida.= '<td class = "text-center" width = "70px">';
			$salida.= '<input type = "checkbox" name = "chk'.$i.'" id = "chk'.$i.'" title = "Click para seleccionar" '.$chk.'>';
			$salida.= '<input type = "hidden" name = "codigo'.$i.'" id = "codigo'.$i.'" value = "'.$codigo.'" />';
			$salida.= '<input type = "hidden" name = "grupo'.$i.'" id = "grupo'.$i.'" value = "'.$grupo.'" />';
			$salida.= '</td>';
			//nombre
			$nombre = utf8_decode($row["perm_desc"]);
			$salida.= '<td align = "left" width = "450px">'.$nombre.'</td>';
			$salida.= '</tr>';
			$i++;
			$k++;
		}
			$i--; //le quita la ultima vuelta de mas...
		$salida.= '<tr>';
		$salida.= '<input type = "hidden" name = "gruplist'.($j-1).'" id = "gruplist'.($j-1).'" value = "'.($k).'-'.($i).'" />';
		$salida.= '<input type = "hidden" name = "cantidad" id = "cantidad" value = "'.$i.'" />';
		$salida.= '</tr>';
		$salida.= '</table>';
	}return $salida;
}
?>