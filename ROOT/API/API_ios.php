<?php
ob_start();
header("Cache-control: private, no-cache");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Pragma: no-cache");
header("Cache: no-cahce");
ini_set('max_execution_time', 90000);
ini_set("memory_limit", -1);
//--
include_once('html_fns_api.php');

header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header("Access-Control-Allow-Origin: *");

///API REQUEST
$request = $_REQUEST["request"];
$_REQUEST = str_replace("undefined", "", $_REQUEST); ///valida campos "undefined" desde javascript

if($request != ""){
	switch($request){
		case "login":
			$usu = $_REQUEST["usu"];
			$pass = $_REQUEST["pass"];
			API_login($usu,$pass);
			break;
		default:
			$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Parametros invalidos...");
			echo json_encode($payload);
			break;
	}
}else{
	//devuelve un mensaje de manejo de errores
	$payload = array(
		"status" => false,
		"data" => [],
		"message" => "Delimite el tipo de consulta a realizar...");
		echo json_encode($payload);
}



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////// FUNCIONES Y CONSULTAS ////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function API_login($usu,$pass){
	$ClsUsu = new ClsUsuario();
	if($usu != "" && $pass != ""){
		$contador = 0;
		//SANDBOX
		$sql = "SELECT usu_id, usu_nombre, usu_mail, usu_telefono, usu_tipo, rol_id, rol_nombre";
		$sql.= " FROM planigo12_sandbox.seg_usuarios, planigo12_sandbox.seg_rol";
		$sql.= " WHERE rol_id = usu_rol AND usu_usuario = '$usu' AND usu_pass = '$pass' AND usu_situacion = 1 AND usu_seguridad = 0;";
		$result = $ClsUsu->exec_query($sql);
		if (is_array($result)) {
			respuesta($result,"sandbox.planigo.app","planigo12_sandbox");
			$contador++;
			return;
		}
		//METROPROYECTOS
		$sql = "SELECT usu_id, usu_nombre, usu_mail, usu_telefono, usu_tipo, rol_id, rol_nombre";
		$sql.= " FROM planigo12_metro.seg_usuarios, planigo12_metro.seg_rol";
		$sql.= " WHERE rol_id = usu_rol AND usu_usuario = '$usu' AND usu_pass = '$pass' AND usu_situacion = 1 AND usu_seguridad = 0;";
		$result = $ClsUsu->exec_query($sql);
		if (is_array($result)) {
			respuesta($result,"metro.planigo.app","planigo12_metro");
			$contador++;
			return;
		}
		//MULTIPROYECTOS
		$sql = "SELECT usu_id, usu_nombre, usu_mail, usu_telefono, usu_tipo, rol_id, rol_nombre";
		$sql.= " FROM planigo12_multiproyectos.seg_usuarios, planigo12_multiproyectos.seg_rol";
		$sql.= " WHERE rol_id = usu_rol AND usu_usuario = '$usu' AND usu_pass = '$pass' AND usu_situacion = 1 AND usu_seguridad = 0;";
		$result = $ClsUsu->exec_query($sql);
		if (is_array($result)) {
			respuesta($result,"multiproyectos.planigo.app","planigo12_multiproyectos");
			$contador++;
			return;
		}
		//HUAWEI
		$sql = "SELECT usu_id, usu_nombre, usu_mail, usu_telefono, usu_tipo, rol_id, rol_nombre";
		$sql.= " FROM planigo12_huawei.seg_usuarios, planigo12_huawei.seg_rol";
		$sql.= " WHERE rol_id = usu_rol AND usu_usuario = '$usu' AND usu_pass = '$pass' AND usu_situacion = 1 AND usu_seguridad = 0;";
		$result = $ClsUsu->exec_query($sql);
		if (is_array($result)) {
			respuesta($result,"huawei.planigo.app","planigo12_huawei");
			$contador++;
			return;
		}
		//LIMANTO
		$sql = "SELECT usu_id, usu_nombre, usu_mail, usu_telefono, usu_tipo, rol_id, rol_nombre";
		$sql.= " FROM planigo12_limanto.seg_usuarios, planigo12_limanto.seg_rol";
		$sql.= " WHERE rol_id = usu_rol AND usu_usuario = '$usu' AND usu_pass = '$pass' AND usu_situacion = 1 AND usu_seguridad = 0;";
		$result = $ClsUsu->exec_query($sql);
		if (is_array($result)) {
			respuesta($result,"limanto.planigo.app","planigo12_limanto");
			$contador++;
			return;
		}
		//GTO
		$sql = "SELECT usu_id, usu_nombre, usu_mail, usu_telefono, usu_tipo, rol_id, rol_nombre";
		$sql.= " FROM planigo12_gto.seg_usuarios, planigo12_gto.seg_rol";
		$sql.= " WHERE rol_id = usu_rol AND usu_usuario = '$usu' AND usu_pass = '$pass' AND usu_situacion = 1 AND usu_seguridad = 0;";
		$result = $ClsUsu->exec_query($sql);
		if (is_array($result)) {
			respuesta($result,"gto.planigo.app","planigo12_gto");
			$contador++;
			return;
		}
		//MUNDO VERDE
		$sql = "SELECT usu_id, usu_nombre, usu_mail, usu_telefono, usu_tipo, rol_id, rol_nombre";
		$sql.= " FROM planigo12_mundoverde.seg_usuarios, planigo12_mundoverde.seg_rol";
		$sql.= " WHERE rol_id = usu_rol AND usu_usuario = '$usu' AND usu_pass = '$pass' AND usu_situacion = 1 AND usu_seguridad = 0;";
		$result = $ClsUsu->exec_query($sql);
		if (is_array($result)) {
			respuesta($result,"mundoverde.planigo.app","planigo12_mundoverde");
			$contador++;
			return;
		}
		//PRADERA CONCEPCION
		$sql = "SELECT usu_id, usu_nombre, usu_mail, usu_telefono, usu_tipo, rol_id, rol_nombre";
		$sql.= " FROM planigo12_praderaconcepcion.seg_usuarios, planigo12_praderaconcepcion.seg_rol";
		$sql.= " WHERE rol_id = usu_rol AND usu_usuario = '$usu' AND usu_pass = '$pass' AND usu_situacion = 1 AND usu_seguridad = 0;";
		$result = $ClsUsu->exec_query($sql);
		if (is_array($result)) {
			respuesta($result,"praderaconcepcion.planigo.app","planigo12_praderaconcepcion");
			$contador++;
			return;
		}
		
		/////////// SI NO INGRESA A NINGUN SISTEMA
		if($contador == 0){
			//devuelve un mensaje de manejo de errores
			$payload = array(
				"status" => false,
				"data" => [],
				"message" => "El usuario o el password son incorrectos...");
				echo json_encode($payload);
		}
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "Uno de los campos esta vacio...");
			echo json_encode($payload);
	}
	
}


function respuesta($result,$DOMINIO,$BD){
	$ClsUsu = new ClsUsuario();
	if (is_array($result)) {
		foreach ($result as $row){
			$codigo = $row['usu_id'];
			$nombre = trim($row["usu_nombre"]);
			$mail = trim($row["usu_mail"]);
			$telefono = trim($row["usu_telefono"]);
			$tipo = $row['usu_tipo'];
			$rol = $row['rol_id'];
			$rol_nombre = $row['rol_nombre'];
		}
		////////////// FOTO /////////////////////
		$sql = "SELECT fot_string as last ";
		$sql.= " FROM $BD.seg_foto_usuario";
		$sql.= " WHERE fot_usuario = '$usu'"; 	
		$result = $ClsUsu->exec_query($sql);
		if(is_array($result)){
			foreach($result as $row){
				$foto = $row["last"];
			}
		}
		if(file_exists('https://' . $DOMINIO . 'CONFIG/Fotos/USUARIOS/'.$foto.'.jpg') && $foto != ""){
			$url_foto = "https://" . $DOMINIO . "/CONFIG/Fotos/USUARIOS/".$foto.".jpg";
		}else{
			$url_foto = "https://" . $DOMINIO . "/CONFIG/Fotos/nofoto.png";
		}
		///////////// SEDES ASIGNADAS /////////////
		$sql = "SELECT sed_codigo ";
		$sql.= " FROM $BD.sis_usuario_sede, $BD.sis_sede, $BD.seg_usuarios";
		$sql.= " WHERE sus_usuario = usu_id";
		$sql.= " AND sus_sede = sed_codigo";
		$sql.= " AND sus_codigo = $codigo"; 
		$sql.= " AND sed_situacion = 1"; 
		$sql.= " ORDER BY usu_id ASC, sed_codigo ASC";
		$result2 = $ClsUsu->exec_query($sql);
		if(is_array($result2)) {
			$cont_sede = 1;
			foreach ($result2 as $row2){
			$sedes_IN.= $row2['sed_codigo'].",";
			$cont_sede++;
			}
			$cont_sede--; //quita la ultima vuelta
			$sedes_IN = substr($sedes_IN, 0, -1); // quita la ultima coma
		}
		///////////// SEDES CATEGORIAS /////////////
		$sql = "SELECT cat_codigo";
		$sql.= " FROM $BD.chk_usuario_categoria, $BD.chk_categoria, $BD.seg_usuarios";
		$sql.= " WHERE cus_usuario = usu_id";
		$sql.= " AND cus_categoria = cat_codigo";
		$sql.= " AND cus_codigo = $codigo"; 
		$sql.= " AND cat_situacion = 1"; 
		$sql.= " ORDER BY usu_id ASC, cat_codigo ASC";
		$result2 = $ClsUsu->exec_query($sql);
		if(is_array($result2)) {
			$cont_categoria = 1;
			foreach ($result2 as $row2){
			$categorias_IN.= $row2['cat_codigo'].",";
			$cont_categoria++;
			}
			$cont_categoria--; //quita la ultima vuelta
			$categorias_IN = substr($categorias_IN, 0, -1); // quita la ultima coma
		}
		  
		/// USUARIO
		$arr_data['codigo'] = $codigo;
		$arr_data['nombre'] = $nombre;
		$arr_data['mail'] = $mail;
		$arr_data['telefono'] = $telefono;
		$arr_data['rol'] = $rol;
		$arr_data['rol_nombre'] = $rol_nombre;
		$arr_data['url_foto'] = $url_foto;
		//--
		$arr_data['sedes_in'] = ($sedes_IN != "")?$sedes_IN:"";
		$arr_data['categorias_in'] = ($categorias_IN != "")?$categorias_IN:"";
		//--
		$arr_data['dominio'] = $DOMINIO;
		
		$payload = array(
			"status" => true,
			"data" => $arr_data,
			"message" => "");
				
		echo json_encode($payload);
	}else{
		//devuelve un mensaje de manejo de errores
		$payload = array(
			"status" => false,
			"data" => [],
			"message" => "El usuario o el password son incorrectos...");
			echo json_encode($payload);
	}
	
}

?>