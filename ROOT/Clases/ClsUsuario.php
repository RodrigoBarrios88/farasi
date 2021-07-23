<?php
require_once ("ClsConex.php");
date_default_timezone_set('America/Guatemala');
//NOTA//
/*
 **EL CAMPO usu_habilita REGISTRA SI EL USUARIO YA INGRESO POR PRIMERAVEZ AL SISTEMA Y CAMBIO SU USUARIO Y CONTRASE�A	
*/
class ClsUsuario extends ClsConex{

	function get_login($usu,$pass) {
		$pass = $this->encrypt($pass, $usu); //encrypta el pasword
		
        $sql= "SELECT * ";
		$sql.= " FROM seg_usuarios, seg_rol";
		$sql.= " WHERE rol_id = usu_rol";
		$sql.= " AND usu_usuario = '$usu'"; 
		$sql.= " AND usu_pass = '$pass'"; 
		$sql.= " AND usu_situacion = 1"; 
		$sql.= " AND usu_seguridad = 0"; 
		
		$result = $this->exec_query($sql);
		//echo $sql."<br><br>";
		return $result;

	}
	function get_valida_pregunta_resp($id,$usu,$preg,$resp) {
		$preg = trim($preg);
		$resp = trim($resp);
		$resp = $this->encrypt($resp, $usu); //encrypta la respuesta
		
		$sql= "SELECT * ";
		$sql.= " FROM seg_usuarios";
		$sql.= " WHERE usu_id = '$id'"; 
		$sql.= " AND usu_usuario = '$usu'"; 
		$sql.= " AND usu_pregunta like '%$preg%'"; 
		$sql.= " AND usu_respuesta = '$resp'"; 
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
		
		
	function get_usuario($id = '',$nom = '',$mail = '',$rol = '',$band = '',$sit = '1',$usu = '') {
		$nom = trim($nom);
		
        $sql= "SELECT * ";
		$sql.= " FROM seg_usuarios, seg_rol";
		$sql.= " WHERE rol_id = usu_rol";
		if(strlen($id)>0) { 
			  $sql.= " AND usu_id = $id"; 
		}
		if(strlen($nom)>0) { 
			  $sql.= " AND usu_nombre like '%$nom%'"; 
		}
		if(strlen($mail)>0) { 
			  $sql.= " AND usu_mail = '$mail'"; 
		}
		if(strlen($rol)>0) { 
			  $sql.= " AND usu_rol = $rol"; 
		}
		if(strlen($band)>0) { 
			  $sql.= " AND usu_habilita = $band"; 
		}
		if(strlen($sit)>0) { 
			  $sql.= " AND usu_situacion IN($sit)"; 
		}
		if(strlen($usu)>0) { 
			  $sql.= " AND usu_usuario = '$usu'"; 
		}
		$sql.= " ORDER BY usu_rol ASC, usu_id ASC;";
		
		$result = $this->exec_query($sql);
		// echo $sql;
		return $result;

	}
	function count_usuario($id,$nom = '',$mail = '',$rol = '',$band = '',$sit = '',$usu = '') {
		$nom = trim($nom);
		
        $sql= "SELECT COUNT(*) as total";
		$sql.= " FROM seg_usuarios, seg_rol";
		$sql.= " WHERE rol_id = usu_rol";
		if(strlen($id)>0) { 
			  $sql.= " AND usu_id = $id"; 
		}
		if(strlen($nom)>0) { 
			  $sql.= " AND usu_nombre like '%$nom%'"; 
		}
		if(strlen($mail)>0) { 
			  $sql.= " AND usu_mail = '$mail'"; 
		}
		if(strlen($rol)>0) { 
			  $sql.= " AND usu_rol = $rol"; 
		}
		if(strlen($band)>0) { 
			  $sql.= " AND usu_habilita = $band"; 
		}
		if(strlen($sit)>0) { 
			  $sql.= " AND usu_situacion IN($sit)"; 
		}
	    if(strlen($usu)>0) { 
			  $sql.= " AND usu_usuario = '$usu'"; 
		}
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$cont = $row["total"];
		}
		//echo $sql;
		return $cont;
	}
	function comprueba_nusuario($id,$usu) {
        $sql= "SELECT COUNT(*) as comprueba";
		$sql.= " FROM seg_usuarios";
		$sql.= " WHERE usu_usuario = '$usu'";
		$sql.= " AND usu_id != $id";  	
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$cont = $row["comprueba"];
		}
		//echo $sql;
		return $cont;
	}	
	function insert_usuario($codigo,$nom,$mail,$tel,$rol,$usu,$pass){
		$nom = trim($nom);
		$mail = strtolower($mail);
		$fec = date("Y-m-d H:i:s");
		$pass = $this->encrypt($pass, $usu); //encrypta el pasword
		
		$sql = "INSERT INTO seg_usuarios ";
		$sql.= " VALUES($codigo,'$nom','$mail','$tel',$rol,'$usu','$pass','','',0,0,'$fec',1); ";
		//echo $sql;
		return $sql;
	}
	function modifica_usuario($id,$nom,$mail,$tel,$rol,$usu,$pass,$habil,$seg){
		$nom = trim($nom);
		$mail = strtolower($mail);
		
		$sql = "UPDATE seg_usuarios SET ";
		$sql.= "usu_nombre = '$nom',"; 
		$sql.= "usu_mail = '$mail',"; 
		$sql.= "usu_telefono = '$tel',"; 
		$sql.= "usu_rol = '$rol',"; 
		$sql.= "usu_seguridad = $seg,";
		$sql.= "usu_habilita = $habil";
		if($usu != ""){
		$sql.= ",usu_usuario = '$usu'";
		}
		if($pass != ""){
		$pass = $this->encrypt($pass, $usu); //encrypta el pasword
		$sql.= ",usu_pass = '$pass'";
		}
		$sql.= " WHERE usu_id = $id; ";
		//echo $sql;
		return $sql;
	}
	function cambia_sit_usuario($id,$sit){
		
		$sql = "UPDATE seg_usuarios SET ";
		$sql.= "usu_situacion = $sit"; 
				
		$sql.= " WHERE usu_id = $id;"; 	
		
		return $sql;
	}
	function cambia_nivel_usuario($id,$rol){
		
		$sql = "UPDATE seg_usuarios SET ";
		$sql.= "usu_rol = $rol"; 
				
		$sql.= " WHERE usu_id = $id;"; 	
		
		return $sql;
	}
	function cambia_usu_habilita($id,$habil){
		
		$sql = "UPDATE seg_usuarios SET ";
		$sql.= "usu_habilita = $habil"; 
		$sql.= " WHERE usu_id = $id; "; 	
		
		return $sql;
	}
	function cambia_pregunta($id,$usu,$preg,$clave){
		$preg = trim($preg);
		$clave = trim($clave);
		$clave = $this->encrypt($clave, $usu); //encrypta la respuesta
		
		$sql = "UPDATE seg_usuarios SET ";
		$sql.= "usu_pregunta = '$preg',";
		$sql.= "usu_respuesta = '$clave'"; 
		$sql.= " WHERE usu_id = $id; "; 	
		
		return $sql;
	}
	function modifica_perfil($id,$nom,$mail,$tel){
		$nom = trim($nom);
		
		$sql = "UPDATE seg_usuarios SET ";
		$sql.= " usu_nombre = '$nom',";
		$sql.= " usu_mail = '$mail',";
		$sql.= " usu_telefono = '$tel'"; 
		$sql.= " WHERE usu_id = $id; "; 	
		
		return $sql;
	}
	function modifica_pass($id,$usu,$pass){
		// ejecuta el cambio de usuario y contrase�a
		$pass = $this->encrypt($pass, $usu); //encrypta el pasword
		
	    $sql = "UPDATE seg_usuarios SET ";
		$sql.= " usu_usuario = '$usu',";
		$sql.= " usu_pass = '$pass'";
		$sql.= " WHERE usu_id = $id; "; 
		//echo $sql;
		return $sql;
	}
	function cambia_usu_seguridad($usu,$seg){
		$sql = "UPDATE seg_usuarios SET ";
		$sql.= "usu_seguridad = $seg"; 
		$sql.= " WHERE usu_usuario = '$usu'; "; 	
		//echo $sql;
		$result = $this->exec_sql($sql);
		return $result;
	}
	function cambia_foto($usu,$string){
		$freg = date("Y-m-d H:i:s");
		$usureg = $_SESSION["codigo"];
		
		$sql = "INSERT INTO seg_foto_usuario (fot_usuario, fot_string , fot_fecha_registro, fot_usuario_registro)";
		$sql.= " VALUES('$usu','$string','$freg','$usureg')";
		$sql.= " ON DUPLICATE KEY UPDATE";
		$sql.= " fot_string = '$string',";
		$sql.= " fot_fecha_registro = '$freg',";
		$sql.= " fot_usuario_registro = '$usureg';";
		
		return $sql;
	}
	function last_foto_usuario($usu){
	    $sql = "SELECT fot_string as last ";
		$sql.= " FROM seg_foto_usuario";
		$sql.= " WHERE fot_usuario = '$usu'"; 	
		$result = $this->exec_query($sql);
		if(is_array($result)){
			foreach($result as $row){
				$last = $row["last"];
			}
		}
		//echo $sql;
		return $last;
	}
	function max_usuario(){
        $sql = "SELECT max(usu_id) as max ";
		$sql.= " FROM seg_usuarios";
		$sql.= " WHERE 1 = 1";
		$result = $this->exec_query($sql);
		if(is_array($result)){
			foreach($result as $row){
				$max = $row["max"];
			}
		}
		//echo $sql;
		return $max;
	}
	function get_usuarios_por_rol($rol) {
		
        $sql= "SELECT *";
		$sql.= " FROM seg_usuarios";
		$sql.= " WHERE usu_rol IN($rol)";
		$sql.= " AND usu_situacion != 0";
		$sql.= " ORDER BY usu_rol ASC, usu_id ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	//////////////////// ________ ASIGNACION USUARIO - SEDE ___________ ///////////////////////
	function get_usuario_sede($codigo,$usu,$sed,$nom = '',$sit = '') {
		$nom = trim($nom);
		
        $sql= "SELECT * ";
		$sql.= " FROM sis_usuario_sede, sis_sede, seg_usuarios";
		$sql.= " WHERE sus_usuario = usu_id";
		$sql.= " AND sus_sede = sed_codigo";
		if(strlen($codigo)>0) { 
			$sql.= " AND sus_codigo = $codigo"; 
		}
		if(strlen($usu)>0) { 
			$sql.= " AND sus_usuario = $usu"; 
		}
		if(strlen($sed)>0) { 
			$sql.= " AND sus_sede = $sed"; 
		}
		if(strlen($nom)>0) { 
			$sql.= " AND sed_nombre like '%$nom%'"; 
		}
		if(strlen($sit)>0) { 
			$sql.= " AND sed_situacion = '$sit'"; 
		}
		$sql.= " ORDER BY usu_id ASC, sed_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;

	}
	function count_usuario_sede($codigo,$usu,$sed,$nom = '',$sit = '') {
		$nom = trim($nom);
		
        $sql= "SELECT COUNT(*) as total";
		$sql.= " FROM sis_usuario_sede, sis_sede, seg_usuarios";
		$sql.= " WHERE sus_usuario = usu_id";
		$sql.= " AND sus_sede = sed_codigo";
		if(strlen($codigo)>0) { 
			$sql.= " AND sus_codigo = $codigo"; 
		}
		if(strlen($usu)>0) { 
			$sql.= " AND sus_usuario = $usu"; 
		}
		if(strlen($sed)>0) { 
			$sql.= " AND sus_sede = $sed"; 
		}
		if(strlen($nom)>0) { 
			$sql.= " AND sed_nombre like '%$nom%'"; 
		}
		if(strlen($sit)>0) { 
			$sql.= " AND sed_situacion = '$sit'"; 
		}
		
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$total = $row['total'];
		}
		return $total;

	}
	function get_usuario_sede_combo($sedes) {
		
		$sql= "SELECT DISTINCT usu_id, usu_nombre, usu_mail, usu_telefono, sus_codigo, sus_sede, sus_fecha_registro";
		$sql.= " FROM sis_usuario_sede, seg_usuarios";
		$sql.= " WHERE sus_usuario = usu_id";
		$sql.= " AND sus_sede IN($sedes)";
		$sql.= " ORDER BY usu_id ASC, sus_sede ASC, usu_nombre ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;

	}
	function insert_usuario_sede($codigo,$usu,$sed){
		//--
		$usu_reg = $_SESSION["codigo"];
		$fec_reg = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO sis_usuario_sede ";
		$sql.= " VALUES ($codigo,$usu,$sed,'$fec_reg',$usu_reg);";
		//echo $sql;
		return $sql;
	}
	function delete_usuario_sede($usu){
		
		$sql = "DELETE FROM sis_usuario_sede";
		$sql.= " WHERE sus_usuario = $usu;";
		
		return $sql;
	}
	function max_usuario_sede($usu){
        $sql = "SELECT max(sus_codigo) as max ";
		$sql.= " FROM sis_usuario_sede";
		$sql.= " WHERE sus_usuario = $usu; "; 
		$result = $this->exec_query($sql);
		if(is_array($result)){
			foreach($result as $row){
				$max = $row["max"];
			}
		}
		//echo $sql;
		return $max;
	}//////////////////// ________ ASIGNACION USUARIO - CATEGORIA ___________ ///////////////////////
	function get_usuario_categoria($codigo,$usu,$categoria,$nom = '',$sit = '') {
		$nom = trim($nom);
		
        $sql= "SELECT * ";
		$sql.= " FROM chk_usuario_categoria, chk_categoria, seg_usuarios";
		$sql.= " WHERE cus_usuario = usu_id";
		$sql.= " AND cus_categoria = cat_codigo";
		if(strlen($codigo)>0) { 
			$sql.= " AND cus_codigo = $codigo"; 
		}
		if(strlen($usu)>0) { 
			$sql.= " AND cus_usuario = $usu"; 
		}
		if(strlen($categoria)>0) { 
			$sql.= " AND cus_categoria = $categoria"; 
		}
		if(strlen($nom)>0) { 
			$sql.= " AND cat_nombre like '%$nom%'"; 
		}
		if(strlen($sit)>0) { 
			$sql.= " AND cat_situacion = '$sit'"; 
		}
		$sql.= " ORDER BY usu_id ASC, cat_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql."<br><br>";
		return $result;

	}
	function count_usuario_categoria($codigo,$usu,$categoria,$nom = '',$sit = '') {
		$nom = trim($nom);
		
		$sql= "SELECT COUNT(*) as total";
		$sql.= " FROM chk_usuario_categoria, chk_categoria, seg_usuarios";
		$sql.= " WHERE cus_usuario = usu_id";
		$sql.= " AND cus_categoria = cat_codigo";
		if(strlen($codigo)>0) { 
			$sql.= " AND cus_codigo = $codigo"; 
		}
		if(strlen($usu)>0) { 
			$sql.= " AND cus_usuario = $usu"; 
		}
		if(strlen($categoria)>0) { 
			$sql.= " AND cus_categoria = $categoria"; 
		}
		if(strlen($nom)>0) { 
			$sql.= " AND cat_nombre like '%$nom%'"; 
		}
		if(strlen($sit)>0) { 
			$sql.= " AND cat_situacion = '$sit'"; 
		}
		
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$total = $row['total'];
		}
		return $total;

	}
	function insert_usuario_categoria($codigo,$usu,$categoria){
		//--
		$usu_reg = $_SESSION["codigo"];
		$fec_reg = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO chk_usuario_categoria ";
		$sql.= " VALUES ($codigo,$usu,$categoria,'$fec_reg',$usu_reg);";
		//echo $sql;
		return $sql;
	}
	function delete_usuario_categoria($usu){
		
		$sql = "DELETE FROM chk_usuario_categoria";
		$sql.= " WHERE cus_usuario = $usu;";
		
		return $sql;
	}
	function max_usuario_categoria($usu){
        $sql = "SELECT max(cus_codigo) as max ";
		$sql.= " FROM chk_usuario_categoria";
		$sql.= " WHERE cus_usuario = $usu; "; 
		$result = $this->exec_query($sql);
		if(is_array($result)){
			foreach($result as $row){
				$max = $row["max"];
			}
		}
		//echo $sql;
		return $max;
	}
//////////////////// ______ ASIGNACION USUARIO - CATEGORIA DE INDICADORES_________ ///////////////////////
	function get_usuario_categoria_indicador($codigo,$usu,$categoria,$nom = '',$sit = '') {
		$nom = trim($nom);
		
        $sql= "SELECT * ";
		$sql.= " FROM ind_usuario_indicador, ind_categoria, seg_usuarios";
		$sql.= " WHERE ius_usuario = usu_id";
		$sql.= " AND ius_indicador = cat_codigo";
		if(strlen($codigo)>0) { 
			$sql.= " AND ius_codigo = $codigo"; 
		}
		if(strlen($usu)>0) { 
			$sql.= " AND ius_usuario = $usu"; 
		}
		if(strlen($categoria)>0) { 
			$sql.= " AND ius_indicador = $categoria"; 
		}
		if(strlen($nom)>0) { 
			$sql.= " AND cat_nombre like '%$nom%'"; 
		}
		if(strlen($sit)>0) { 
			$sql.= " AND cat_situacion = '$sit'"; 
		}
		$sql.= " ORDER BY usu_id ASC, cat_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql."<br><br>";
		return $result;

	}
	function count_usuario_categoria_indicador($codigo,$usu,$categoria,$nom = '',$sit = '') {
		$nom = trim($nom);
		
		$sql= "SELECT COUNT(*) as total";
		$sql.= " FROM ind_usuario_indicador, ind_categoria, seg_usuarios";
		$sql.= " WHERE ius_usuario = usu_id";
		$sql.= " AND ius_categoria = cat_codigo";
		if(strlen($codigo)>0) { 
			$sql.= " AND ius_codigo = $codigo"; 
		}
		if(strlen($usu)>0) { 
			$sql.= " AND ius_usuario = $usu"; 
		}
		if(strlen($categoria)>0) { 
			$sql.= " AND ius_indicador = $categoria"; 
		}
		if(strlen($nom)>0) { 
			$sql.= " AND cat_nombre like '%$nom%'"; 
		}
		if(strlen($sit)>0) { 
			$sql.= " AND cat_situacion = '$sit'"; 
		}
		
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$total = $row['total'];
		}
		return $total;

	}
	function insert_usuario_categoria_indicador($codigo,$usu,$indicador){
		//--
		$usu_reg = $_SESSION["codigo"];
		$fec_reg = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO ind_usuario_indicador ";
		$sql.= " VALUES ($codigo,$usu,$indicador,'$fec_reg',$usu_reg);";
		//echo $sql;
		return $sql;
	}
	function delete_usuario_categoria_indicador($usu){
		
		$sql = "DELETE FROM ind_usuario_indicador";
		$sql.= " WHERE ius_usuario = $usu;";
		
		return $sql;
	}
	function max_usuario_categoria_indicador($usu){
        $sql = "SELECT max(ius_codigo) as max ";
		$sql.= " FROM ind_usuario_indicador";
		$sql.= " WHERE ius_usuario = $usu; "; 
		$result = $this->exec_query($sql);
		if(is_array($result)){
			foreach($result as $row){
				$max = $row["max"];
			}
		}
		//echo $sql;
		return $max;
	}
	
//////////////////// ________ ASIGNACION USUARIO - DEPARTAMENTO ___________ /////////////////////
	function get_usuario_departamento($codigo,$usuario,$departamento,$nombre = '',$situacion = '') {
		$nombre = trim($nombre);
		
        $sql= "SELECT * ";
		$sql.= " FROM sis_usuario_departamento, sis_departamento, seg_usuarios";
		$sql.= " WHERE dus_usuario = usu_id";
		$sql.= " AND dus_departamento = dep_codigo";
		if(strlen($codigo)>0) { 
			$sql.= " AND dus_codigo = $codigo"; 
		}
		if(strlen($usuario)>0) { 
			$sql.= " AND dus_usuario = $usuario"; 
		}
		if(strlen($departamento)>0) { 
			$sql.= " AND dus_departamento = $departamento"; 
		}
		if(strlen($nombre)>0) { 
			$sql.= " AND dep_nombre like '%$nombre%'"; 
		}
		if(strlen($situacion)>0) { 
			$sql.= " AND dep_situacion = '$situacion'"; 
		}
		$sql.= " ORDER BY usu_id ASC, dep_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql."<br><br>";
		return $result;

	}
	function count_usuario_departamento($codigo,$usuario,$departamento,$nombre = '',$situacion = '') {
		$nombre = trim($nombre);
		
		$sql= "SELECT COUNT(*) as total";
		$sql.= " FROM sis_usuario_departamento, sis_departamento, seg_usuarios";
		$sql.= " WHERE dus_usuario = usu_id";
		$sql.= " AND dus_departamento = dep_codigo";
		if(strlen($codigo)>0) { 
			$sql.= " AND dus_codigo = $codigo"; 
		}
		if(strlen($usuario)>0) { 
			$sql.= " AND dus_usuario = $usuario"; 
		}
		if(strlen($departamento)>0) { 
			$sql.= " AND dus_departamento = $departamento"; 
		}
		if(strlen($nombre)>0) { 
			$sql.= " AND dep_nombre like '%$nombre%'"; 
		}
		if(strlen($situacion)>0) { 
			$sql.= " AND dep_situacion = '$situacion'"; 
		}
		
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$total = $row['total'];
		}
		return $total;

	}
	function insert_usuario_departamento($codigo,$usuario,$departamento){
		//--
		$usu_reg = $_SESSION["codigo"];
		$fec_reg = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO sis_usuario_departamento ";
		$sql.= " VALUES ($codigo,$usuario,$departamento,'$fec_reg',$usu_reg);";
		//echo $sql;
		return $sql;
	}
	function delete_usuario_departamento($usuario){
		
		$sql = "DELETE FROM sis_usuario_departamento";
		$sql.= " WHERE dus_usuario = $usuario;";
		
		return $sql;
	}
	function max_usuario_departamento($usuario){
        $sql = "SELECT max(dus_codigo) as max ";
		$sql.= " FROM sis_usuario_departamento";
		$sql.= " WHERE dus_usuario = $usuario; "; 
		$result = $this->exec_query($sql);
		if(is_array($result)){
			foreach($result as $row){
				$max = $row["max"];
			}
		}
		//echo $sql;
		return $max;
	}

//////////////////// ________ ASIGNACION USUARIO - INCIDENTE ___________ ///////////////////////
	function get_usuario_incidente($codigo,$usu,$incidente,$nom = '',$sit = '') {
		$nom = trim($nom);
		
        $sql= "SELECT * ";
		$sql.= " FROM hd_usuario_incidente, hd_incidente, seg_usuarios";
		$sql.= " WHERE ius_usuario = usu_id";
		$sql.= " AND ius_incidente = inc_codigo";
		if(strlen($codigo)>0) { 
			$sql.= " AND ius_codigo = $codigo"; 
		}
		if(strlen($usu)>0) { 
			$sql.= " AND ius_usuario = $usu"; 
		}
		if(strlen($incidente)>0) { 
			$sql.= " AND ius_incidente = $incidente"; 
		}
		if(strlen($nom)>0) { 
			$sql.= " AND inc_nombre like '%$nom%'"; 
		}
		if(strlen($sit)>0) { 
			$sql.= " AND inc_situacion = '$sit'"; 
		}
		$sql.= " ORDER BY usu_id ASC, inc_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;

	}
	function count_usuario_incidente($codigo,$usu,$incidente,$nom = '',$sit = '') {
		$nom = trim($nom);
		
		$sql= "SELECT COUNT(*) as total";
		$sql.= " FROM hd_usuario_incidente, hd_incidente, seg_usuarios";
		$sql.= " WHERE ius_usuario = usu_id";
		$sql.= " AND ius_incidente = inc_codigo";
		if(strlen($codigo)>0) { 
			$sql.= " AND ius_codigo = $codigo"; 
		}
		if(strlen($usu)>0) { 
			$sql.= " AND ius_usuario = $usu"; 
		}
		if(strlen($incidente)>0) { 
			$sql.= " AND ius_incidente = $incidente"; 
		}
		if(strlen($nom)>0) { 
			$sql.= " AND inc_nombre like '%$nom%'"; 
		}
		if(strlen($sit)>0) { 
			$sql.= " AND inc_situacion = '$sit'"; 
		}
		
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$total = $row['total'];
		}
		return $total;

	}
	function insert_usuario_incidente($codigo,$usu,$incidente){
		//--
		$usu_reg = $_SESSION["codigo"];
		$fec_reg = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO hd_usuario_incidente ";
		$sql.= " VALUES ($codigo,$usu,$incidente,'$fec_reg',$usu_reg);";
		//echo $sql;
		return $sql;
	}
	function delete_usuario_incidente($usu){
		
		$sql = "DELETE FROM hd_usuario_incidente";
		$sql.= " WHERE ius_usuario = $usu;";
		
		return $sql;
	}
	function max_usuario_incidente($usu){
        $sql = "SELECT max(ius_codigo) as max ";
		$sql.= " FROM hd_usuario_incidente";
		$sql.= " WHERE ius_usuario = $usu; "; 
		$result = $this->exec_query($sql);
		if(is_array($result)){
			foreach($result as $row){
				$max = $row["max"];
			}
		}
		//echo $sql;
		return $max;
	}

}
