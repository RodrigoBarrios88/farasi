<?php
require_once ("ClsConex.php");

class ClsCausa extends ClsConex{
/////////////////////////////  Causa   //////////////////////////////////////
  
   function get_causa($codigo  = '', $plan = '', $pertenece = '', $situacion = '1') {
      $sql= "SELECT * ";
      $sql.= " FROM mej_causa, mej_plan ";
      $sql.= " WHERE cau_plan = pla_codigo";
      if(strlen($codigo)>0) { 
         $sql.= " AND cau_codigo = $codigo"; 
      }
      if(strlen($plan)>0) { 
         $sql.= " AND cau_plan = $plan"; 
      }
      if(strlen($pertenece)>0) { 
         $sql.= " AND cau_pertenece = $pertenece"; 
      }
      if(strlen($situacion)>0) { 
         $sql.= " AND cau_situacion = $situacion"; 
      }
      $sql.= " ORDER BY cau_codigo ASC";
		
		$result = $this->exec_query($sql);
		// echo $sql;
		return $result;
	}

	function count_causa($codigo, $nombre = '', $sit = '') {
		$nombre = trim($nombre);
		
      $sql= "SELECT COUNT(*) as total";
      $sql.= " FROM ind_clasificacion, mast_unidad_medida ";
      $sql.= " WHERE cau_unidad_medida = umed_codigo";
      if(strlen($codigo)>0) { 
         $sql.= " AND cau_codigo = $codigo"; 
      }
      if(strlen($nombre)>0) { 
         $sql.= " AND cau_nombre like '%$nombre%'"; 
      }
      if(strlen($sit)>0) { 
         $sql.= " AND cau_situacion = '$sit'"; 
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach($result as $row){
         $total = $row['total'];
      }
      return $total;
	}
	
   function insert_causa($codigo,$plan,$pertenece,$causa){
      $sql = "INSERT INTO mej_causa ";
      $sql.= " VALUES ($codigo,$plan,$pertenece,'$causa',1);";
      //echo $sql;
      return $sql;
   }
	function modifica_causa($codigo,$causa){
		$sql = "UPDATE mej_causa  SET "; 
		$sql.= "cau_descripcion = '$causa' "; 
		$sql.= " WHERE cau_codigo = $codigo"; 	
		//echo $sql;
		return $sql;
	}
	function cambia_situacion_causa($codigo,$sit){
		
		$sql = "UPDATE mej_causa  SET cau_situacion = $sit"; 
		$sql.= " WHERE cau_codigo = $codigo"; 	
		
		return $sql;
	}
	function max_causa(){
      $sql = "SELECT max(cau_codigo) as max ";
      $sql.= " FROM mej_causa ";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}
   
   
}
