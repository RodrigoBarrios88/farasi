<?php
require_once ("ClsConex.php");

class ClsNorma extends ClsConex{
/* Situacion 1 = ACTIVO, 0 = INACTIVO */
   
    function get_norma($codigo,$nombre = '',$situacion = '',$habiles = '') {
		$nombre = trim($nombre);
		
        $sql= "SELECT * ";
		$sql.= " FROM sis_norma";
		$sql.= " WHERE 1 = 1";
		if(strlen($codigo)>0) { 
			$sql.= " AND nor_codigo = $codigo"; 
		}
		if(strlen($nombre)>0) { 
			$sql.= " AND nor_nombre like '%$nombre%'"; 
		}
		if(strlen($situacion)>0) { 
			$sql.= " AND nor_situacion = $situacion"; 
		}
        if(strlen($habiles)>0) { 
			$sql.= " AND nor_codigo != 0"; 
		}
		$sql.= " ORDER BY nor_codigo ASC, nor_situacion DESC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;

	}
	function count_norma($codigo,$nombre = '',$situacion = '',$habiles = '') {
		$nombre = trim($nombre);
		
        $sql= "SELECT COUNT(*) as total";
		$sql.= " FROM sis_norma";
		$sql.= " WHERE 1 = 1";
		if(strlen($codigo)>0) { 
			$sql.= " AND nor_codigo = $codigo"; 
		}
		if(strlen($nombre)>0) { 
			$sql.= " AND nor_nombre like '%$nombre%'"; 
		}
		if(strlen($situacion)>0) { 
			$sql.= " AND nor_situacion = $situacion"; 
		}
        if(strlen($habiles)>0) { 
			$sql.= " AND nor_codigo != 0"; 
		}
		//echo $sql;
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$total = $row['total'];
		}
		return $total;
	}
		
	function insert_norma($codigo,$nombre){
		$nombre = trim($nombre);
		
		$sql = "INSERT INTO sis_norma";
		$sql.= " VALUES ($codigo,'$nombre',1); ";
		//echo $sql;
		return $sql;
	}
	function modifica_norma($codigo,$nombre){
		$nombre = trim($nombre);
		
		$sql = "UPDATE sis_norma SET ";
		$sql.= "nor_nombre = '$nombre'"; 
		
		$sql.= " WHERE nor_codigo = $codigo; "; 	
		//echo $sql;
		return $sql;
	}
	function cambia_situacion_norma($codigo,$situacion){
		
		$sql = "UPDATE sis_norma SET ";
		$sql.= "nor_situacion = $situacion"; 
				
		$sql.= " WHERE nor_codigo = $codigo"; 	
		
		return $sql;
	}
	function max_norma() {
		
        $sql = "SELECT max(nor_codigo) as max ";
		$sql.= " FROM sis_norma";
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
