<?php 
include_once('../html_fns.php');

function tabla_activos($codigo){
	$ClsAct = new ClsActivo();
	$result = $ClsAct->get_activo($codigo);
	if(is_array($result)){
		$salida = '<table id="tabla" class="table table-striped dataTables-example" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "40px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida.= '<th class = "text-center" width = "150px">Activo</th>';
		$salida.= '<th class = "text-center" width = "50px">Cantidad</th>';
		$salida.= '<th class = "text-center" width = "100px">Ubicaci&oacute;n</th>';
		$salida.= '<th class = "text-center" width = "120px">Marca</th>';
		$salida.= '<th class = "text-center" width = "120px">Proveedor</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["act_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsAct->encrypt($codigo, $usu);
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "seleccionarActivo('.$codigo.');" title = "Editar Activo" ><i class="fa fa-pencil"></i></button>';
					$salida.= '<button type="button" class="btn btn-primary btn-xs" onclick = "fotosActivo(\''.$hashkey.'\');" title = "Agregar Fotos del Activo" ><i class="fa fa-camera"></i></button>';
					$salida.= '<button type="button" class="btn btn-info btn-xs" onclick = "fichaActivo(\''.$hashkey.'\');" title = "Ver Ficha del Activo" ><i class="fa fa-search"></i></button>';
				$salida.= '</div>';
			$salida.= '</td>';
			//codigo
			$codigo = Agrega_Ceros($row["act_codigo"]);
			$salida.= '<td class = "text-center">'.$codigo.'</td>';
			//nombre
			$nombre = trim($row["act_nombre"]);
			$salida.= '<td class = "text-left">'.$nombre.'</td>';
			//cantidad
			$cantidad = trim($row["act_cantidad"]);
			$salida.= '<td class = "text-center">'.$cantidad.'</td>';
			//ubicacion
			$ubicacion = trim($row["sed_nombre"])." ".trim($row["are_nombre"]);
			$salida.= '<td class = "text-left">'.$ubicacion.'</td>';
			//marca
			$marca = trim($row["act_marca"]);
			$salida.= '<td class = "text-left">'.$marca.'</td>';
			//proveedor
			$proveedor = trim($row["act_proveedor"]);
			$salida.= '<td class = "text-left">'.$proveedor.'</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}


function tabla_activos_falla($sede,$sector,$area,$nivel){
	$ClsAct = new ClsActivo();
	$result = $ClsAct->get_activo('',$sede,$sector,$area);
	if(is_array($result)){
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "40px"><i class="fa fa-cogs"></i></th>';
		$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida.= '<th class = "text-center" width = "150px">Activo</th>';
		$salida.= '<th class = "text-center" width = "50px">Cantidad</th>';
		$salida.= '<th class = "text-center" width = "100px">Ubicaci&oacute;n</th>';
		$salida.= '<th class = "text-center" width = "120px">Marca</th>';
		$salida.= '<th class = "text-center" width = "120px">Proveedor</th>';
		$salida.= '<th class = "text-center" width = "70px">Situaci&oacute;n</th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=0;
		foreach($result as $row){
			$salida.= '<tr>';
			//codigo
			$codigo = $row["act_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsAct->encrypt($codigo, $usu);
			$nombre = utf8_decode(trim($row["act_nombre"]));
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<button type="button" class="btn btn-white btn-xs" onclick = "listFallas('.$codigo.');" title = "Historial de Fallas" ><i class="fa fa-list"></i></button>';
					$salida.= '<button type="button" class="btn btn-warning btn-xs" onclick = "newFalla('.$codigo.');" title = "Reportar de Falla" ><i class="fa fa-exclamation-circle"></i></button>';
					$salida.= '<a type="button" class="btn btn-info btn-xs" href = "../CPPPMPROGRA/FRMprogramar.php?hashkey='.$hashkey.'" target="_blank" title = "Programar mantenimiento para '.$nombre.'" ><i class="fa fa-calendar"></i></a>';
				$salida.= '</div>';
			$salida.= '</td>';
			//codigo
			$codigo = Agrega_Ceros($row["act_codigo"]);
			$salida.= '<td class = "text-center">'.$codigo.'</td>';
			//nombre
			$salida.= '<td class = "text-left">'.$nombre.'</td>';
			//cantidad
			$cantidad = trim($row["act_cantidad"]);
			$salida.= '<td class = "text-center">'.$cantidad.'</td>';
			//ubicacion
			$ubicacion = utf8_decode(trim($row["sed_nombre"])." ".trim($row["are_nombre"]));
			$salida.= '<td class = "text-left">'.$ubicacion.'</td>';
			//marca
			$marca = utf8_decode(trim($row["act_marca"]));
			$salida.= '<td class = "text-left">'.$marca.'</td>';
			//proveedor
			$proveedor = utf8_decode(trim($row["act_proveedor"]));
			$salida.= '<td class = "text-left">'.$proveedor.'</td>';
			//situacion
			$sit = trim($row["act_situacion"]);
			$situacion = ($sit == 1)?'<span class="text-success">Activo</span>':'<strong class="text-danger">Inactivo</strong>';
			$salida.= '<td class = "text-center">'.$situacion.'</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}


function tabla_fichas($sede, $sector, $area, $situacion){
	$ClsAct = new ClsActivo();
	$result = $ClsAct->get_activo('',$sede, $sector, $area, $situacion);
	if(is_array($result)){
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "10px">No.</th>';
		$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida.= '<th class = "text-center" width = "150px">Activo</th>';
		$salida.= '<th class = "text-center" width = "50px">Cantidad</th>';
		$salida.= '<th class = "text-center" width = "100px">Ubicaci&oacute;n</th>';
		$salida.= '<th class = "text-center" width = "120px">Marca</th>';
		$salida.= '<th class = "text-center" width = "120px">Proveedor</th>';
		$salida.= '<th class = "text-center" width = "10px"><i class="fa fa-cogs"></i></th>';
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i=1;
		foreach($result as $row){
			$salida.= '<tr>';
			//No.
			$salida.= '<td class = "text-center" >'.$i.'.</td>';
			//codigo
			$codigo = Agrega_Ceros($row["act_codigo"]);
			$salida.= '<td class = "text-center">'.$codigo.'</td>';
			//nombre
			$nombre = trim($row["act_nombre"]);
			$salida.= '<td class = "text-left">'.$nombre.'</td>';
			//cantidad
			$cantidad = trim($row["act_cantidad"]);
			$salida.= '<td class = "text-center">'.$cantidad.'</td>';
			//ubicacion
			$ubicacion = trim($row["sed_nombre"])." ".trim($row["are_nombre"]);
			$salida.= '<td class = "text-left">'.$ubicacion.'</td>';
			//marca
			$marca = trim($row["act_marca"]);
			$salida.= '<td class = "text-left">'.$marca.'</td>';
			//proveedor
			$proveedor = trim($row["act_proveedor"]);
			$salida.= '<td class = "text-left">'.$proveedor.'</td>';
			//codigo
			$codigo = $row["act_codigo"];
			$usu = $_SESSION["codigo"];
			$hashkey = $ClsAct->encrypt($codigo, $usu);
			$salida.= '<td class = "text-center" >';
				$salida.= '<div class="btn-group">';
					$salida.= '<a class="btn btn-info btn-xs" href = "FRMficha.php?hashkey='.$hashkey.'" title = "Ver Ficha" ><i class="fa fa-search"></i></a>';
					$salida.= '<a class="btn btn-primary btn-xs" href = "CPREPORTES/REPficha.php?hashkey='.$hashkey.'" target="_blank" title = "Imprimir Ficha" ><i class="fa fa-print"></i></a>';
				$salida.= '</div>';
			$salida.= '</td>';
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}


function tabla_reportes($sede, $sector, $area, $situacion, $columnas){
	$ClsAct = new ClsActivo();
	$result = $ClsAct->get_activo('',$sede, $sector, $area, $situacion);
	if(is_array($result)){
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida.= '<thead>';
		$salida.= '<tr>';
		$salida.= '<th class = "text-center" width = "10px">No.</th>';
		if(is_array($columnas)){
			foreach($columnas as $col){
			   $parametros = parametrosDinamicosHTML($col);
			   $ancho = $parametros['ancho'];
			   $titulo = $parametros['titulo'];
			   $salida.= '<th class = "text-center" width = "'.$ancho.'">'.$titulo.'</th>';
			}
		}else{
			$salida.= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
			$salida.= '<th class = "text-center" width = "150px">Activo</th>';
			$salida.= '<th class = "text-center" width = "120px">Marca</th>';
			$salida.= '<th class = "text-center" width = "50px">Cantidad</th>';
			$salida.= '<th class = "text-center" width = "100px">&Aacute;rea</th>';
			$salida.= '<th class = "text-center" width = "100px">Sede</th>';
		}
		$salida.= '</tr>';
		$salida.= '</thead>';
		$salida.= '<tbody>';
		$i = 1;
		foreach($result as $row){
			$salida.= '<tr>';
			//--
			$salida.= '<td class = "text-center">'.$i.'.- </td>';
			//--
			if(is_array($columnas)){
				foreach($columnas as $col){
					$parametros = parametrosDinamicosHTML($col);
					$campo = $parametros['campo'];
					$alineacion = $parametros['alineacion'];
					if($col == "act_codigo"){
						$campo = '# '.Agrega_Ceros($row[$campo]);
					}else if($col == "act_fecha_registro"){
						$campo = cambia_fechaHora($row["act_fecha_registro"]);
					}else if($col == "act_fecha_update"){
						$campo = cambia_fechaHora($row["act_fecha_update"]);
					}else if($col == "act_periodicidad"){
						$campo = $row["act_periodicidad"];
						switch($campo){
							case "D": $campo = "Diario"; break;
							case "W": $campo = "Semanal"; break;
							case "M": $campo = "Mensual"; break;
							case "Y": $campo = "Anual"; break;
							case "V": $campo = "Variado"; break;
						}
					}else{
						$campo = trim($row[$campo]);
					}
					//columna
					$salida.= '<td class = "'.$alineacion.'">'.$campo.'</td>';
				}
			}else{
				//codigo
				$codigo = Agrega_Ceros($row["act_codigo"]);
				$salida.= '<td class = "text-center">'.$codigo.'</td>';
				//nombre
				$nombre = trim($row["act_nombre"]);
				$salida.= '<td class = "text-left">'.$nombre.'</td>';
				//marca
				$marca = trim($row["act_marca"]);
				$salida.= '<td class = "text-left">'.$marca.'</td>';
				//cantidad
				$cantidad = trim($row["act_cantidad"]);
				$salida.= '<td class = "text-center">'.$cantidad.'</td>';
				//sede
				$sede = trim($row["sed_nombre"]);
				$salida.= '<td class = "text-left">'.$sede.'</td>';
				//area
				$area = trim($row["are_nombre"]);
				$salida.= '<td class = "text-left">'.$area.'</td>';
				
			}
			//--
			$salida.= '</tr>';
			$i++;
		}
		$salida.= '</tbody>';
		$salida.= '</table>';
	}return $salida;
}



function parametrosDinamicosHTML($columna){
	switch($columna){
		case "act_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Activo";
			$respuesta["campo"] = "act_codigo";
			break;
		case "act_nombre":
			$respuesta["ancho"] = "110";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Activo";
			$respuesta["campo"] = "act_nombre";
			break;
		case "act_marca":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Marca";
			$respuesta["campo"] = "act_marca";
			break;
		case "act_serie":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Serie";
			$respuesta["campo"] = "act_serie";
			break;
		case "act_modelo":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Modelo";
			$respuesta["campo"] = "act_modelo";
			break;
		case "act_parte":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "No. Parte";
			$respuesta["campo"] = "act_parte";
			break;
		case "act_proveedor":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Proveedor";
			$respuesta["campo"] = "act_proveedor";
			break;
		case "act_periodicidad":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Periodicidad";
			$respuesta["campo"] = "act_periodicidad";
			break;
		case "act_capacidad":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Capacidad";
			$respuesta["campo"] = "act_capacidad";
			break;
		case "act_cantidad":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Cantidad";
			$respuesta["campo"] = "act_cantidad";
			break;
		case "act_precio_nuevo":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Precio Original";
			$respuesta["campo"] = "act_precio_nuevo";
			break;
		case "act_precio_compra":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Precio de Adquicisi&oacute;n";
			$respuesta["campo"] = "act_precio_actual";
			break;
		case "act_precio_actual":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Precio Actual";
			$respuesta["campo"] = "act_precio_actual";
			break;
		case "act_fecha_registro":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha Registro";
			$respuesta["campo"] = "act_fecha_registro";
			break;
		case "act_fecha_update":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Fecha Actualiza";
			$respuesta["campo"] = "act_fecha_update";
			break;
		case "usu_nombre":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Usuario Registra";
			$respuesta["campo"] = "usu_nombre";
			break;
		case "are_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo &Aacute;rea";
			$respuesta["campo"] = "are_codigo";
			break;
		case "are_nivel":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Nivel";
			$respuesta["campo"] = "are_nivel";
			break;
		case "are_nombre":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "&Aacute;rea";
			$respuesta["campo"] = "are_nombre";
			break;
		case "sec_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Sector";
			$respuesta["campo"] = "sec_codigo";
			break;
		case "sec_nombre":
			$respuesta["ancho"] = "100";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Sector";
			$respuesta["campo"] = "sec_nombre";
			break;
		case "sed_codigo":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "C&oacute;digo Sector";
			$respuesta["campo"] = "sed_codigo";
			break;
		case "sed_nombre":
			$respuesta["ancho"] = "110";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Sede";
			$respuesta["campo"] = "sed_nombre";
			break;
		case "sede_municipio":
			$respuesta["ancho"] = "110";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Departamento / Municipio";
			$respuesta["campo"] = "sede_municipio";
			break;
		case "sed_direccion":
			$respuesta["ancho"] = "150";
			$respuesta["alineacion"] = "text-left";
			$respuesta["titulo"] = "Direcci&oacute;n (Sede)";
			$respuesta["campo"] = "sed_direccion";
			break;
		case "sed_zona":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "text-center";
			$respuesta["titulo"] = "Zona";
			$respuesta["campo"] = "sed_zona";
			break;
	}	
	return $respuesta;
}


function parametrosDinamicosPDF($columna){
	switch($columna){
		case "act_codigo":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Activo";
			$respuesta["campo"] = "act_codigo";
			break;
		case "act_nombre":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Activo";
			$respuesta["campo"] = "act_nombre";
			break;
		case "act_marca":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Marca";
			$respuesta["campo"] = "act_marca";
			break;
		case "act_serie":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Serie";
			$respuesta["campo"] = "act_serie";
			break;
		case "act_modelo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Modelo";
			$respuesta["campo"] = "act_modelo";
			break;
		case "act_parte":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "No. Parte";
			$respuesta["campo"] = "act_parte";
			break;
		case "act_proveedor":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Proveedor";
			$respuesta["campo"] = "act_proveedor";
			break;
		case "act_periodicidad":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Periodicidad";
			$respuesta["campo"] = "act_periodicidad";
			break;
		case "act_capacidad":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Capacidad";
			$respuesta["campo"] = "act_capacidad";
			break;
		case "act_cantidad":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cantidad";
			$respuesta["campo"] = "act_cantidad";
			break;
		case "act_precio_nuevo":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Precio Original";
			$respuesta["campo"] = "act_precio_nuevo";
			break;
		case "act_precio_compra":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Precio Compra";
			$respuesta["campo"] = "act_precio_actual";
			break;
		case "act_precio_actual":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Precio Actual";
			$respuesta["campo"] = "act_precio_actual";
			break;
		case "act_fecha_registro":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Registro";
			$respuesta["campo"] = "act_fecha_registro";
			break;
		case "act_fecha_update":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Actualiza";
			$respuesta["campo"] = "act_fecha_update";
			break;
		case "usu_nombre":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Usuario Registra";
			$respuesta["campo"] = "usu_nombre";
			break;
		case "are_codigo":
			$respuesta["ancho"] = "20";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Área";
			$respuesta["campo"] = "are_codigo";
			break;
		case "are_nivel":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Nivel";
			$respuesta["campo"] = "are_nivel";
			break;
		case "are_nombre":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Área";
			$respuesta["campo"] = "are_nombre";
			break;
		case "sec_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Sector";
			$respuesta["campo"] = "sec_codigo";
			break;
		case "sec_nombre":
			$respuesta["ancho"] = "50";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Sector";
			$respuesta["campo"] = "sec_nombre";
			break;
		case "sed_codigo":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Sede";
			$respuesta["campo"] = "sed_codigo";
			break;
		case "sed_nombre":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Sede";
			$respuesta["campo"] = "sed_nombre";
			break;
		case "sede_municipio":
			$respuesta["ancho"] = "60";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Dep/Municipio";
			$respuesta["campo"] = "sede_municipio";
			break;
		case "sed_direccion":
			$respuesta["ancho"] = "65";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Dirección (Sede)";
			$respuesta["campo"] = "sed_direccion";
			break;
		case "sed_zona":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Zona";
			$respuesta["campo"] = "sed_zona";
			break;
	}	
	return $respuesta;
}


function parametrosDinamicosEXCEL($columna){
	switch($columna){
		case "act_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Activo";
			$respuesta["campo"] = "act_codigo";
			break;
		case "act_nombre":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Activo";
			$respuesta["campo"] = "act_nombre";
			break;
		case "act_marca":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Marca";
			$respuesta["campo"] = "act_marca";
			break;
		case "act_serie":
			$respuesta["ancho"] = "20";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Serie";
			$respuesta["campo"] = "act_serie";
			break;
		case "act_modelo":
			$respuesta["ancho"] = "20";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Modelo";
			$respuesta["campo"] = "act_modelo";
			break;
		case "act_parte":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "No. Parte";
			$respuesta["campo"] = "act_parte";
			break;
		case "act_proveedor":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Proveedor";
			$respuesta["campo"] = "act_proveedor";
			break;
		case "act_periodicidad":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Periodicidad";
			$respuesta["campo"] = "act_periodicidad";
			break;
		case "act_capacidad":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Capacidad";
			$respuesta["campo"] = "act_capacidad";
			break;
		case "act_cantidad":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cantidad";
			$respuesta["campo"] = "act_cantidad";
			break;
		case "act_precio_nuevo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Precio Original";
			$respuesta["campo"] = "act_precio_nuevo";
			break;
		case "act_precio_compra":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Precio Compra";
			$respuesta["campo"] = "act_precio_actual";
			break;
		case "act_precio_actual":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Precio Actual";
			$respuesta["campo"] = "act_precio_actual";
			break;
		case "act_fecha_registro":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Registro";
			$respuesta["campo"] = "act_fecha_registro";
			break;
		case "act_fecha_update":
			$respuesta["ancho"] = "25";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Fecha Actualiza";
			$respuesta["campo"] = "act_fecha_update";
			break;
		case "usu_nombre":
			$respuesta["ancho"] = "40";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Usuario Registra";
			$respuesta["campo"] = "usu_nombre";
			break;
		case "are_codigo":
			$respuesta["ancho"] = "20";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Area";
			$respuesta["campo"] = "are_codigo";
			break;
		case "are_nivel":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Nivel";
			$respuesta["campo"] = "are_nivel";
			break;
		case "are_nombre":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Area";
			$respuesta["campo"] = "are_nombre";
			break;
		case "sec_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Sector";
			$respuesta["campo"] = "sec_codigo";
			break;
		case "sec_nombre":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Sector";
			$respuesta["campo"] = "sec_nombre";
			break;
		case "sed_codigo":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Cod. Sede";
			$respuesta["campo"] = "sed_codigo";
			break;
		case "sed_nombre":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Sede";
			$respuesta["campo"] = "sed_nombre";
			break;
		case "sede_municipio":
			$respuesta["ancho"] = "30";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Dep/Municipio";
			$respuesta["campo"] = "sede_municipio";
			break;
		case "sed_direccion":
			$respuesta["ancho"] = "35";
			$respuesta["alineacion"] = "L";
			$respuesta["titulo"] = "Direccion (Sede)";
			$respuesta["campo"] = "sed_direccion";
			break;
		case "sed_zona":
			$respuesta["ancho"] = "15";
			$respuesta["alineacion"] = "C";
			$respuesta["titulo"] = "Zona";
			$respuesta["campo"] = "sed_zona";
			break;
	}	
	return $respuesta;
}?>