<?php
require_once ("ClsConex.php");

class ClsPrioridad extends ClsConex{
/* Situacion 1 = ACTIVO, 2 = INACTIVO */
   
    function get_prioridad($codigo,$nombre = '',$situacion = '') {
		$nombre = trim($nombre);
		
        $sql= "SELECT * ";
		$sql.= " FROM hd_prioridad";
		$sql.= " WHERE 1 = 1";
		if(strlen($codigo)>0) { 
			$sql.= " AND pri_codigo = '$codigo'"; 
		}
		if(strlen($nombre)>0) { 
			$sql.= " AND pri_nombre like '%$nombre%'"; 
		}
        if(strlen($situacion)>0) { 
			$sql.= " AND pri_situacion = '$situacion'"; 
		}
		$sql.= " ORDER BY pri_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;	}
	function count_prioridad($codigo,$nombre = '',$situacion = '') {
		$nombre = trim($nombre);
		
        $sql= "SELECT COUNT(*) as total";
		$sql.= " FROM hd_prioridad";
		$sql.= " WHERE 1 = 1";
		if(strlen($codigo)>0) { 
			$sql.= " AND pri_codigo = '$codigo'"; 
		}
		if(strlen($nombre)>0) { 
			$sql.= " AND pri_nombre like '%$nombre%'"; 
		}
        if(strlen($situacion)>0) { 
			$sql.= " AND pri_situacion = '$situacion'"; 
		}
		//echo $sql;
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$total = $row['total'];
		}
		return $total;
	}
		function insert_prioridad($codigo,$nombre,$trespuesta,$tsolucion,$trecordar,$color,$sms){
		$nombre = trim($nombre);
		
		$sql = "INSERT INTO hd_prioridad";
		$sql.= " VALUES ($codigo,'$nombre','$trespuesta','$tsolucion','$trecordar','$color',$sms,1);";
		//echo $sql;
		return $sql;
	}
	function modifica_prioridad($codigo,$nombre,$trespuesta,$tsolucion,$trecordar,$color,$sms){
		$nombre = trim($nombre);
        $color = trim($color);
		
		$sql = "UPDATE hd_prioridad SET ";
		$sql.= "pri_nombre = '$nombre',"; 
		$sql.= "pri_respuesta = '$trespuesta',"; 
		$sql.= "pri_solucion = '$tsolucion',"; 
		$sql.= "pri_recordatorio = '$trecordar',"; 
		$sql.= "pri_color = '$color',"; 
		$sql.= "pri_sms = '$sms'"; 
		
		$sql.= " WHERE pri_codigo = $codigo; "; 	
		//echo $sql;
		return $sql;
	}
	function cambia_situacion_prioridad($codigo,$situacion){
		
		$sql = "UPDATE hd_prioridad SET ";
		$sql.= "pri_situacion = $situacion"; 
		$sql.= " WHERE pri_codigo = $codigo; "; 	
		
		return $sql;
	}
	function max_prioridad(){
        $sql = "SELECT max(pri_codigo) as max ";
		$sql.= " FROM hd_prioridad";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}
	
}
