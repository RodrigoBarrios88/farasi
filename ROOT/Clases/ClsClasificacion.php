<?php
require_once ("ClsConex.php");

class ClsClasificacion extends ClsConex{
/* Situacion 1 = ACTIVO, 2 = INACTIVO */

/////////////////////////////  INDICADORES   //////////////////////////////////////
  
   function get_clasificacion_indicador($codigo, $nombre = '', $sit = '') {
		$nombre = trim($nombre);
		
      $sql= "SELECT * ";
      $sql.= " FROM ind_clasificacion, mast_unidad_medida ";
      $sql.= " WHERE cla_unidad_medida = umed_codigo";
      if(strlen($codigo)>0) { 
         $sql.= " AND cla_codigo = $codigo"; 
      }
      if(strlen($nombre)>0) { 
         $sql.= " AND cla_nombre like '%$nombre%'"; 
      }
      if(strlen($sit)>0) { 
         $sql.= " AND cla_situacion = '$sit'"; 
      }
      $sql.= " ORDER BY cla_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function count_clasificacion_indicador($codigo, $nombre = '', $sit = '') {
		$nombre = trim($nombre);
		
      $sql= "SELECT COUNT(*) as total";
      $sql.= " FROM ind_clasificacion, mast_unidad_medida ";
      $sql.= " WHERE cla_unidad_medida = umed_codigo";
      if(strlen($codigo)>0) { 
         $sql.= " AND cla_codigo = $codigo"; 
      }
      if(strlen($nombre)>0) { 
         $sql.= " AND cla_nombre like '%$nombre%'"; 
      }
      if(strlen($sit)>0) { 
         $sql.= " AND cla_situacion = '$sit'"; 
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach($result as $row){
         $total = $row['total'];
      }
      return $total;
	}
	
   function insert_clasificacion_indicador($codigo,$nombre,$umed){
      $nombre = trim($nombre);
      $umed = trim($umed);
      
      $sql = "INSERT INTO ind_clasificacion ";
      $sql.= " VALUES ($codigo,'$nombre','$umed',1);";
      //echo $sql;
      return $sql;
   }
	function modifica_clasificacion_indicador($codigo,$nombre,$umed){
		$nombre = trim($nombre);
		$umed = trim($umed);
      
		$sql = "UPDATE ind_clasificacion  SET "; 
		$sql.= "cla_nombre = '$nombre', "; 
		$sql.= "cla_unidad_medida = '$umed'"; 
		$sql.= " WHERE cla_codigo = $codigo"; 	
		//echo $sql;
		return $sql;
	}
	function cambia_situacion_clasificacion_indicador($codigo,$sit){
		
		$sql = "UPDATE ind_clasificacion  SET cla_situacion = $sit"; 
		$sql.= " WHERE cla_codigo = $codigo"; 	
		
		return $sql;
	}
	function max_clasificacion_indicador(){
      $sql = "SELECT max(cla_codigo) as max ";
      $sql.= " FROM ind_clasificacion ";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}
   
   
}	
?>