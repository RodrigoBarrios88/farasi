<?php
require_once ("ClsConex.php");

class ClsSector extends ClsConex{
/* Situacion 1 = ACTIVO, 2 = INACTIVO */

/////////////////////////////  SECTOR  //////////////////////////////////////
  
   function get_sector($codigo, $sede = '', $nombre = '', $sit = '') {
		$nombre = trim($nombre);
      $sede = ($sede == "")?$_SESSION["sedes_in"]:$sede;
      
      $sql= "SELECT * ";
      $sql.= " FROM sis_sector, sis_sede";
      $sql.= " WHERE sed_codigo = sec_sede";
      if(strlen($codigo)>0) { 
         $sql.= " AND sec_codigo = $codigo"; 
      }
      if(strlen($sede)>0) { 
         $sql.= " AND sec_sede IN($sede)"; 
      }
      if(strlen($nombre)>0) { 
         $sql.= " AND sec_nombre like '%$nombre%'"; 
      }
      if(strlen($sit)>0) { 
         $sql.= " AND sec_situacion = '$sit'"; 
      }
      $sql.= " ORDER BY sec_sede ASC, sec_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function count_sector($codigo, $sede = '', $nombre = '', $sit = '') {
		$nombre = trim($nombre);
      $sede = ($sede == "")?$_SESSION["sedes_in"]:$sede;
		
      $sql= "SELECT COUNT(*) as total";
      $sql.= " FROM sis_sector, sis_sede";
      $sql.= " WHERE sed_codigo = sec_sede";
      if(strlen($codigo)>0) { 
         $sql.= " AND sec_codigo = $codigo"; 
      }
      if(strlen($sede)>0) { 
         $sql.= " AND sec_sede IN($sede)"; 
      }
      if(strlen($nombre)>0) { 
         $sql.= " AND sec_nombre like '%$nombre%'"; 
      }
      if(strlen($sit)>0) { 
         $sql.= " AND sec_situacion = '$sit'"; 
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach($result as $row){
         $total = $row['total'];
      }
      return $total;
	}
	
   function insert_sector($codigo,$sede,$nombre){
      $nombre = trim($nombre);
      
      $sql = "INSERT INTO sis_sector";
      $sql.= " VALUES ($codigo,$sede,'$nombre',1);";
      //echo $sql;
      return $sql;
   }
	function modifica_sector($codigo,$sede,$nombre){
		$nombre = trim($nombre);
		
		$sql = "UPDATE sis_sector SET ";
		$sql.= "sec_sede = '$sede',"; 		
		$sql.= "sec_nombre = '$nombre'"; 
		
		$sql.= " WHERE sec_codigo = $codigo"; 	
		//echo $sql;
		return $sql;
	}
	function cambia_situacion_sector($codigo,$sit){
		
		$sql = "UPDATE sis_sector SET ";
		$sql.= "sec_situacion = $sit"; 
				
		$sql.= " WHERE sec_codigo = $codigo"; 	
		
		return $sql;
	}
	function max_sector(){
      $sql = "SELECT max(sec_codigo) as max ";
      $sql.= " FROM sis_sector";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}
}
