<?php
require_once ("ClsConex.php");

class ClsAjustes extends ClsConex{
   
    function get_ajustes($usuario = '') {
				
        $sql= "SELECT * ";
		$sql.= " FROM seg_ajustes";
		$sql.= " WHERE 1 = 1";
		if(strlen($usuario)>0) { 
			$sql.= " AND aju_usuario = $usuario"; 
		}
		$sql.= " ORDER BY aju_usuario ASC"; 
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;

	}
		
	function insert_ajustes($usuario,$idioma,$notificaciones){
		
		$freg = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO seg_ajustes ";
		$sql.= " VALUES ('$usuario','$idioma','$notificaciones','$freg')";
		$sql.= " ON DUPLICATE KEY UPDATE";
		$sql.= " aju_idioma = '$idioma',";
		$sql.= " aju_notificaciones = '$notificaciones',";
		$sql.= " aju_fecha_update = '$freg';";
		//echo $sql;
		return $sql;
	}
	
}
