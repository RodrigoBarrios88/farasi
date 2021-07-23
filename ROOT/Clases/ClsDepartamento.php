<?php
require_once ("ClsConex.php");

class ClsDepartamento extends ClsConex{
/* Situacion 1 = ACTIVO, 0 = INACTIVO */
   
    function get_departamento($codigo,$nombre = '',$sit = '',$habiles = '') {
		$nombre = trim($nombre);
		
        $sql= "SELECT * ";
		$sql.= " FROM sis_departamento";
		$sql.= " WHERE 1 = 1";
		if(strlen($codigo)>0) { 
			  $sql.= " AND dep_codigo = $codigo"; 
		}
		if(strlen($nombre)>0) { 
			  $sql.= " AND dep_nombre like '%$nombre%'"; 
		}
		if(strlen($sit)>0) { 
			  $sql.= " AND dep_situacion = $sit"; 
		}
        if(strlen($habiles)>0) { 
			  $sql.= " AND dep_codigo != 0"; 
		}
		$sql.= " ORDER BY dep_codigo ASC, dep_situacion DESC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;

	}
	function count_departamento($codigo,$nombre = '',$sit = '',$habiles = '') {
		$nombre = trim($nombre);
		
        $sql= "SELECT COUNT(*) as total";
		$sql.= " FROM sis_departamento";
		$sql.= " WHERE 1 = 1";
		if(strlen($codigo)>0) { 
			  $sql.= " AND dep_codigo = $codigo"; 
		}
		if(strlen($nombre)>0) { 
			  $sql.= " AND dep_nombre like '%$nombre%'"; 
		}
		if(strlen($sit)>0) { 
			  $sql.= " AND dep_situacion = $sit"; 
		}
        if(strlen($habiles)>0) { 
			  $sql.= " AND dep_codigo != 0"; 
		}
		//echo $sql;
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$total = $row['total'];
		}
		return $total;
	}
		
	function insert_departamento($codigo,$nombre){
		$nombre = trim($nombre);
		
		$sql = "INSERT INTO sis_departamento";
		$sql.= " VALUES ($codigo,'$nombre',1); ";
		//echo $sql;
		return $sql;
	}
	function modifica_departamento($codigo,$nombre){
		$nombre = trim($nombre);
		
		$sql = "UPDATE sis_departamento SET ";
		$sql.= "dep_nombre = '$nombre'"; 
		
		$sql.= " WHERE dep_codigo = $codigo; "; 	
		//echo $sql;
		return $sql;
	}
	function cambia_situacion_departamento($codigo,$sit){
		
		$sql = "UPDATE sis_departamento SET ";
		$sql.= "dep_situacion = $sit"; 
				
		$sql.= " WHERE dep_codigo = $codigo"; 	
		
		return $sql;
	}
	function max_departamento() {
		
        $sql = "SELECT max(dep_codigo) as max ";
		$sql.= " FROM sis_departamento";
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
