<?php
require_once ("ClsConex.php");

class ClsIncidente extends ClsConex{
/* Situacion 1 = ACTIVO, 2 = INACTIVO */
   
    function get_incidente($codigo,$categoria = '',$prioridad = '',$nombre = '',$situacion = '') {
		$nombre = trim($nombre);
		
        $sql= "SELECT * ";
		$sql.= " FROM hd_incidente, hd_categoria, hd_prioridad";
		$sql.= " WHERE inc_categoria = cat_codigo";
		$sql.= " AND inc_prioridad = pri_codigo";
		if(strlen($codigo)>0) { 
			$sql.= " AND inc_codigo = '$codigo'"; 
		}
		if(strlen($categoria)>0) { 
			$sql.= " AND inc_categoria = '$categoria'"; 
		}
		if(strlen($prioridad)>0) { 
			$sql.= " AND inc_prioridad = '$prioridad'"; 
		}
		if(strlen($nombre)>0) { 
			$sql.= " AND inc_nombre like '%$nombre%'"; 
		}
        if(strlen($situacion)>0) { 
			$sql.= " AND inc_situacion = '$situacion'"; 
		}
		$sql.= " ORDER BY inc_categoria ASC, inc_prioridad ASC, inc_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;

	}
	function count_incidente($codigo,$categoria = '',$prioridad = '',$nombre = '',$situacion = '') {
		$nombre = trim($nombre);
		
        $sql= "SELECT COUNT(*) as total";
		$sql.= " FROM hd_incidente, hd_categoria, hd_prioridad";
		$sql.= " WHERE inc_categoria = cat_codigo";
		$sql.= " AND inc_prioridad = pri_codigo";
		if(strlen($codigo)>0) { 
			$sql.= " AND inc_codigo = '$codigo'"; 
		}
		if(strlen($categoria)>0) { 
			$sql.= " AND inc_categoria = '$categoria'"; 
		}
		if(strlen($prioridad)>0) { 
			$sql.= " AND inc_prioridad = '$prioridad'"; 
		}
		if(strlen($nombre)>0) { 
			$sql.= " AND inc_nombre like '%$nombre%'"; 
		}
        if(strlen($situacion)>0) { 
			$sql.= " AND inc_situacion = '$situacion'"; 
		}
		//echo $sql;
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$total = $row['total'];
		}
		return $total;
	}
		function insert_incidente($codigo,$categoria,$prioridad,$nombre){
		$nombre = trim($nombre);
		
		$sql = "INSERT INTO hd_incidente";
		$sql.= " VALUES ($codigo,'$categoria','$prioridad','$nombre',1);";
		//echo $sql;
		return $sql;
	}
	function modifica_incidente($codigo,$categoria,$prioridad,$nombre){
		$nombre = trim($nombre);
		
		$sql = "UPDATE hd_incidente SET ";
		$sql.= "inc_categoria = '$categoria',"; 
		$sql.= "inc_prioridad = '$prioridad',"; 
		$sql.= "inc_nombre = '$nombre'"; 
		
		$sql.= " WHERE inc_codigo = $codigo; "; 	
		//echo $sql;
		return $sql;
	}
	function cambia_situacion_incidente($codigo,$situacion){
		
		$sql = "UPDATE hd_incidente SET ";
		$sql.= "inc_situacion = $situacion"; 
		$sql.= " WHERE inc_codigo = $codigo; "; 	
		
		return $sql;
	}
	function max_incidente(){
        $sql = "SELECT max(inc_codigo) as max ";
		$sql.= " FROM hd_incidente";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}

/////////////////// ________ ASIGNACION USUARIO - INCIDENTE ___________ /////////////////////
	function get_usuario_incidente($codigo,$incidente,$usu,$categoria = '',$prioridad = '',$situacion = '') {
		
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
		if(strlen($categoria)>0) { 
			$sql.= " AND inc_categoria = '$categoria'"; 
		}
		if(strlen($prioridad)>0) { 
			$sql.= " AND inc_prioridad = '$prioridad'"; 
		}
		if(strlen($situacion)>0) { 
			$sql.= " AND inc_situacion = '$situacion'"; 
		}
		$sql.= " ORDER BY usu_id ASC, inc_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}    
    function get_usuario_incidente_sede($incidente,$usuario,$sede) {
		
        $sql= "SELECT * ";
		$sql.= " FROM hd_usuario_incidente, hd_incidente, seg_usuarios, sis_usuario_sede";
		$sql.= " WHERE ius_usuario = usu_id";
		$sql.= " AND ius_incidente = inc_codigo";
        $sql.= " AND sus_usuario = usu_id";
		if(strlen($incidente)>0) { 
			$sql.= " AND ius_incidente = $incidente"; 
		}
		if(strlen($usuario)>0) { 
			$sql.= " AND ius_usuario = $usuario"; 
		}
		if(strlen($sede)>0) { 
			$sql.= " AND sus_sede = $sede"; 
		}
		$sql.= " ORDER BY usu_id ASC, inc_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function count_usuario_incidente($codigo,$incidente,$usu,$categoria = '',$prioridad = '',$situacion = '') {
		
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
		if(strlen($categoria)>0) { 
			$sql.= " AND inc_categoria = '$categoria'"; 
		}
		if(strlen($prioridad)>0) { 
			$sql.= " AND inc_prioridad = '$prioridad'"; 
		}
		if(strlen($situacion)>0) { 
			$sql.= " AND inc_situacion = '$situacion'"; 
		}
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$total = $row['total'];
		}
		return $total;
	}
	function insert_usuario_incidente($codigo,$incidente,$usu){
		//--
		$usu_reg = $_SESSION["codigo"];
		$fec_reg = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO hd_usuario_incidente ";
		$sql.= " VALUES ($codigo,$incidente,$usu,'$fec_reg',$usu_reg);";
		//echo $sql;
		return $sql;
	}
	function delete_usuario_incidente($incidente){
		
		$sql = "DELETE FROM hd_usuario_incidente";
		$sql.= " WHERE ius_incidente = $incidente;";
		
		return $sql;
	}
	function max_usuario_incidente($incidente){
        $sql = "SELECT max(ius_codigo) as max ";
		$sql.= " FROM hd_usuario_incidente";
		$sql.= " WHERE ius_incidente = $incidente; "; 
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
?>