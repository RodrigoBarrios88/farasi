<?php
require_once ("ClsConex.php");

class ClsArea extends ClsConex{
/* Situacion 1 = ACTIVO, 2 = INACTIVO */

/////////////////////////////  AREA //////////////////////////////////////
  
   function get_area($codigo, $sede = '', $sector = '', $nivel = '', $nombre = '', $sit = '') {
		$nombre = trim($nombre);
      $sede = ($sede == "")?$_SESSION["sedes_in"]:$sede;
		
      $sql = "SELECT * ";
      $sql.= " FROM sis_area, sis_sector, sis_sede";
      $sql.= " WHERE sec_codigo = are_sector";
      $sql.= " AND sed_codigo = are_sede";
      if(strlen($codigo)>0) { 
         $sql.= " AND are_codigo = $codigo"; 
      }
      if(strlen($sede)>0) { 
         $sql.= " AND are_sede IN($sede)"; 
      }
      if(strlen($sector)>0) { 
         $sql.= " AND are_sector = $sector"; 
      }
      if(strlen($nivel)>0) { 
         $sql.= " AND are_nivel = $nivel"; 
      }
      if(strlen($nombre)>0) { 
         $sql.= " AND are_nombre like '%$nombre%'"; 
      }
      if(strlen($sit)>0) { 
         $sql.= " AND are_situacion = '$sit'"; 
      }
      $sql.= " ORDER BY are_sede ASC, are_sector ASC, are_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;

	}
	function count_area($codigo, $sede = '', $sector = '', $nivel = '', $nombre = '', $sit = '') {
		$nombre = trim($nombre);
      $sede = ($sede == "")?$_SESSION["sedes_in"]:$sede;
		
      $sql= "SELECT COUNT(*) as total";
      $sql.= " FROM sis_area, sis_sector, sis_sede";
      $sql.= " WHERE sec_codigo = are_sector";
      $sql.= " AND sed_codigo = are_sede";
      if(strlen($codigo)>0) { 
         $sql.= " AND are_codigo = $codigo"; 
      }
      if(strlen($sede)>0) { 
         $sql.= " AND are_sede IN($sede)"; 
      }
      if(strlen($sector)>0) { 
         $sql.= " AND are_sector = $sector"; 
      }
      if(strlen($nivel)>0) { 
         $sql.= " AND are_nivel = $nivel"; 
      }
      if(strlen($nombre)>0) { 
         $sql.= " AND are_nombre like '%$nombre%'"; 
      }
      if(strlen($sit)>0) { 
         $sql.= " AND are_situacion = '$sit'"; 
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach($result as $row){
         $total = $row['total'];
      }
      return $total;
	}
	
   function insert_area($codigo,$sede,$sector,$nivel,$nombre){
      $nombre = trim($nombre);
      
      $sql = "INSERT INTO sis_area";
      $sql.= " VALUES ($codigo,$sede,$sector,'$nivel','$nombre',1);";
      //echo $sql;
      return $sql;
   }
	function modifica_area($codigo,$sede,$sector,$nivel,$nombre){
		$nombre = trim($nombre);
		
		$sql = "UPDATE sis_area SET ";
		$sql.= "are_sede = '$sede',"; 		
		$sql.= "are_sector = '$sector',"; 		
		$sql.= "are_nivel = '$nivel',";
		$sql.= "are_nombre = '$nombre'"; 
		
		$sql.= " WHERE are_codigo = $codigo"; 	
		//echo $sql;
		return $sql;
	}
	function cambia_situacion_area($codigo,$sit){
		
		$sql = "UPDATE sis_area SET ";
		$sql.= "are_situacion = $sit"; 
				
		$sql.= " WHERE are_codigo = $codigo"; 	
		
		return $sql;
	}
	function max_area(){
      $sql = "SELECT max(are_codigo) as max ";
      $sql.= " FROM sis_area";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}
}
