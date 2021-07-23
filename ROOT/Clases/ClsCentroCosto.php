<?php
require_once ("ClsConex.php");

class ClsCentroCosto extends ClsConex{
/* Situacion 1 = ACTIVO, 0 = INACTIVO */
   
    function get_centro_costo($codigo,$nombre = '',$situacion = '',$habiles = '') {
		$nombre = trim($nombre);
		
        $sql= "SELECT * ";
		$sql.= " FROM sis_centro_costo";
		$sql.= " WHERE 1 = 1";
		if(strlen($codigo)>0) { 
			$sql.= " AND cc_codigo = $codigo"; 
		}
		if(strlen($nombre)>0) { 
			$sql.= " AND cc_nombre like '%$nombre%'"; 
		}
		if(strlen($situacion)>0) { 
			$sql.= " AND cc_situacion = $situacion"; 
		}
        if(strlen($habiles)>0) { 
			$sql.= " AND cc_codigo != 0"; 
		}
		$sql.= " ORDER BY cc_codigo ASC, cc_situacion DESC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;

	}
	function count_centro_costo($codigo,$nombre = '',$situacion = '',$habiles = '') {
		$nombre = trim($nombre);
		
        $sql= "SELECT COUNT(*) as total";
		$sql.= " FROM sis_centro_costo";
		$sql.= " WHERE 1 = 1";
		if(strlen($codigo)>0) { 
			$sql.= " AND cc_codigo = $codigo"; 
		}
		if(strlen($nombre)>0) { 
			$sql.= " AND cc_nombre like '%$nombre%'"; 
		}
		if(strlen($situacion)>0) { 
			$sql.= " AND cc_situacion = $situacion"; 
		}
        if(strlen($habiles)>0) { 
			$sql.= " AND cc_codigo != 0"; 
		}
		//echo $sql;
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$total = $row['total'];
		}
		return $total;
	}
		
	function insert_centro_costo($codigo,$nombre){
		$nombre = trim($nombre);
		
		$sql = "INSERT INTO sis_centro_costo";
		$sql.= " VALUES ($codigo,'$nombre',1); ";
		//echo $sql;
		return $sql;
	}
	function modifica_centro_costo($codigo,$nombre){
		$nombre = trim($nombre);
		
		$sql = "UPDATE sis_centro_costo SET ";
		$sql.= "cc_nombre = '$nombre'"; 
		
		$sql.= " WHERE cc_codigo = $codigo; "; 	
		//echo $sql;
		return $sql;
	}
	function cambia_situacion_centro_costo($codigo,$situacion){
		
		$sql = "UPDATE sis_centro_costo SET ";
		$sql.= "cc_situacion = $situacion"; 
				
		$sql.= " WHERE cc_codigo = $codigo"; 	
		
		return $sql;
	}
	function max_centro_costo() {
		
        $sql = "SELECT max(cc_codigo) as max ";
		$sql.= " FROM sis_centro_costo";
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
