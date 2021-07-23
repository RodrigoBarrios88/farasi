<?php
require_once ("ClsConex.php");

class ClsStatus extends ClsConex{
/* Situacion 1 = ACTIVO, 2 = INACTIVO */
    ////////////////// STATUS PROBLEM SWEEPER /////////////////////////////
    function get_status_hd($codigo,$posicion = '',$nombre = '',$sit = '') {
		$nombre = trim($nombre);
		
        $sql= "SELECT * ";
		$sql.= " FROM hd_status";
		$sql.= " WHERE 1 = 1";
        if($_SESSION["CLOSETICKET"] != 1){ /// SI tiene permisos para cerrar el ticket lo ve
            $sql.= " AND sta_codigo != 100"; 
        }
		if(strlen($codigo)>0) { 
			$sql.= " AND sta_codigo = '$codigo'"; 
		}
		if(strlen($posicion)>0) { 
			$sql.= " AND sta_posicion = '$posicion'"; 
		}
		if(strlen($nombre)>0) { 
			$sql.= " AND sta_nombre like '%$nombre%'"; 
		}
        if(strlen($sit)>0) { 
			$sql.= " AND sta_situacion = '$sit'"; 
		}
		$sql.= " ORDER BY sta_posicion ASC, sta_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql."<br><br>";
		return $result;

	}
	function count_status_hd($codigo,$posicion = '',$nombre = '',$sit = '') {
		$nombre = trim($nombre);
		
        $sql= "SELECT COUNT(*) as total";
		$sql.= " FROM hd_status";
		$sql.= " WHERE 1 = 1";
        if($_SESSION["CLOSETICKET"] != 1){ /// SI tiene permisos de gestor de sedes, ve todas las sedes. Si no, incluye esta validación 
            $sql.= " AND sta_codigo != 100"; 
        }
		if(strlen($codigo)>0) { 
			$sql.= " AND sta_codigo = '$codigo'"; 
		}
		if(strlen($posicion)>0) { 
			$sql.= " AND sta_posicion = '$posicion'"; 
		}
		if(strlen($nombre)>0) { 
			$sql.= " AND sta_nombre like '%$nombre%'"; 
		}
        if(strlen($sit)>0) { 
			$sql.= " AND sta_situacion = '$sit'"; 
		}
		//echo $sql;
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$total = $row['total'];
		}
		return $total;
	}
		function insert_status_hd($codigo,$posicion,$nombre,$color){
		$nombre = trim($nombre);
		
		$sql = "INSERT INTO hd_status";
		$sql.= " VALUES ($codigo,'$posicion','$nombre','$color',1);";
		//echo $sql;
		return $sql;
	}
	function modifica_status_hd($codigo,$posicion,$nombre,$color){
		$nombre = trim($nombre);
		
		$sql = "UPDATE hd_status SET ";
		$sql.= "sta_posicion = '$posicion',"; 
		$sql.= "sta_nombre = '$nombre',";
        $sql.= "sta_color = '$color'"; 
		
		$sql.= " WHERE sta_codigo = $codigo; "; 	
		//echo $sql;
		return $sql;
	}
	function cambia_situacion_status_hd($codigo,$sit){
		
		$sql = "UPDATE hd_status SET ";
		$sql.= "sta_situacion = $sit"; 
		$sql.= " WHERE sta_codigo = $codigo; "; 	
		
		return $sql;
	}    
	function  next_status_hd($status){
        $sql = "SELECT sta_codigo ";
		$sql.= " FROM hd_status";
		$sql.= " WHERE sta_codigo > $status";
		$sql.= " AND sta_situacion = 1";
		$sql.= " ORDER BY sta_codigo ASC LIMIT 0,1";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$next = $row["sta_codigo"];
		}
		//echo $sql;
		return $next;
	}
	function max_status_hd(){
        $sql = "SELECT max(sta_codigo) as max ";
		$sql.= " FROM hd_status";
        $sql.= " WHERE sta_codigo < 50";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}    ////////////////// STATUS AUDITORIA /////////////////////////////
    function get_status_aud($codigo,$posicion = '',$nombre = '',$sit = '') {
		$nombre = trim($nombre);
		
        $sql= "SELECT * ";
		$sql.= " FROM aud_status";
		$sql.= " WHERE 1 = 1";
        if($_SESSION["CLOSETICKET"] != 1){ /// SI tiene permisos para cerrar el ticket lo ve
            $sql.= " AND sta_codigo != 100"; 
        }
		if(strlen($codigo)>0) { 
			$sql.= " AND sta_codigo = '$codigo'"; 
		}
		if(strlen($posicion)>0) { 
			$sql.= " AND sta_posicion = '$posicion'"; 
		}
		if(strlen($nombre)>0) { 
			$sql.= " AND sta_nombre like '%$nombre%'"; 
		}
        if(strlen($sit)>0) { 
			$sql.= " AND sta_situacion = '$sit'"; 
		}
		$sql.= " ORDER BY sta_posicion ASC, sta_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;

	}
	function count_status_aud($codigo,$posicion = '',$nombre = '',$sit = '') {
		$nombre = trim($nombre);
		
        $sql= "SELECT COUNT(*) as total";
		$sql.= " FROM aud_status";
		$sql.= " WHERE 1 = 1";
        if($_SESSION["CLOSETICKET"] != 1){ /// SI tiene permisos de gestor de sedes, ve todas las sedes. Si no, incluye esta validación 
            $sql.= " AND sta_codigo != 100"; 
        }
		if(strlen($codigo)>0) { 
			$sql.= " AND sta_codigo = '$codigo'"; 
		}
		if(strlen($posicion)>0) { 
			$sql.= " AND sta_posicion = '$posicion'"; 
		}
		if(strlen($nombre)>0) { 
			$sql.= " AND sta_nombre like '%$nombre%'"; 
		}
        if(strlen($sit)>0) { 
			$sql.= " AND sta_situacion = '$sit'"; 
		}
		//echo $sql;
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$total = $row['total'];
		}
		return $total;
	}
		function insert_status_aud($codigo,$posicion,$nombre,$color){
		$nombre = trim($nombre);
		
		$sql = "INSERT INTO aud_status";
		$sql.= " VALUES ($codigo,'$posicion','$nombre','$color',1);";
		//echo $sql;
		return $sql;
	}
	function modifica_status_aud($codigo,$posicion,$nombre,$color){
		$nombre = trim($nombre);
		
		$sql = "UPDATE aud_status SET ";
		$sql.= "sta_posicion = '$posicion',"; 
		$sql.= "sta_nombre = '$nombre',";
        $sql.= "sta_color = '$color'"; 
		
		$sql.= " WHERE sta_codigo = $codigo; "; 	
		//echo $sql;
		return $sql;
	}
	function cambia_situacion_status_aud($codigo,$sit){
		
		$sql = "UPDATE aud_status SET ";
		$sql.= "sta_situacion = $sit"; 
		$sql.= " WHERE sta_codigo = $codigo; "; 	
		
		return $sql;
	}    
	function  next_status_aud($status){
        $sql = "SELECT sta_codigo ";
		$sql.= " FROM aud_status";
		$sql.= " WHERE sta_codigo > $status";
		$sql.= " AND sta_situacion = 1";
		$sql.= " ORDER BY sta_codigo ASC LIMIT 0,1";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$next = $row["sta_codigo"];
		}
		//echo $sql;
		return $next;
	}
	function max_status_aud(){
        $sql = "SELECT max(sta_codigo) as max ";
		$sql.= " FROM aud_status";
        $sql.= " WHERE sta_codigo < 50";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}
}
