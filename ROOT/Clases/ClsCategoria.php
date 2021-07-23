<?php
require_once ("ClsConex.php");

class ClsCategoria extends ClsConex{
/* Situacion 1 = ACTIVO, 2 = INACTIVO */

/////////////////////////////  CHECKLIST  //////////////////////////////////////
  
   function get_categoria_checklist($codigo, $nombre = '', $sit = '') {
		$nombre = trim($nombre);
		
      $sql= "SELECT * ";
      $sql.= " FROM chk_categoria";
      $sql.= " WHERE 1 = 1";
      if(strlen($codigo)>0) { 
         $sql.= " AND cat_codigo IN($codigo)"; 
      }
      if(strlen($nombre)>0) { 
         $sql.= " AND cat_nombre like '%$nombre%'"; 
      }
      if(strlen($sit)>0) { 
         $sql.= " AND cat_situacion = '$sit'"; 
      }
      $sql.= " ORDER BY cat_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
   function get_categoria_checklist_usuario($codigo, $nombre = '', $sit = '') {
		$nombre = trim($nombre);
		$usuario = $_SESSION['codigo'];
      $sql= "SELECT * ";
      $sql.= " FROM chk_categoria, chk_usuario_categoria";
      $sql.= " WHERE 1 = 1";
      $sql.= " AND cus_categoria = cat_codigo";
      $sql.= " AND cus_usuario = $usuario";
      if(strlen($codigo)>0) { 
         $sql.= " AND cat_codigo IN($codigo)"; 
      }
      if(strlen($nombre)>0) { 
         $sql.= " AND cat_nombre like '%$nombre%'"; 
      }
      if(strlen($sit)>0) { 
         $sql.= " AND cat_situacion = '$sit'"; 
      }
      $sql.= " ORDER BY cat_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function count_categoria_checklist($codigo, $nombre = '', $sit = '') {
		$nombre = trim($nombre);
		
      $sql= "SELECT COUNT(*) as total";
      $sql.= " FROM chk_categoria";
      $sql.= " WHERE 1 = 1";
      if(strlen($codigo)>0) { 
         $sql.= " AND cat_codigo IN($codigo)"; 
      }
      if(strlen($nombre)>0) { 
         $sql.= " AND cat_nombre like '%$nombre%'"; 
      }
      if(strlen($sit)>0) { 
         $sql.= " AND cat_situacion = '$sit'"; 
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach($result as $row){
         $total = $row['total'];
      }
      return $total;
	}
	
   function insert_categoria_checklist($codigo,$nombre,$color){
      $nombre = trim($nombre);
      $color = trim($color);
      
      $sql = "INSERT INTO chk_categoria";
      $sql.= " VALUES ($codigo,'$nombre','$color',1);";
      //echo $sql;
      return $sql;
   }
	function modifica_categoria_checklist($codigo,$nombre,$color){
		$nombre = trim($nombre);
		$color = trim($color);
      
		$sql = "UPDATE chk_categoria SET "; 
		$sql.= "cat_nombre = '$nombre', "; 
		$sql.= "cat_color = '$color'"; 
		$sql.= " WHERE cat_codigo = $codigo"; 	
		//echo $sql;
		return $sql;
	}
	function cambia_situacion_categoria_checklist($codigo,$sit){
		
		$sql = "UPDATE chk_categoria SET cat_situacion = $sit"; 
		$sql.= " WHERE cat_codigo = $codigo"; 	
		
		return $sql;
	}
	function max_categoria_checklist(){
      $sql = "SELECT max(cat_codigo) as max ";
      $sql.= " FROM chk_categoria";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}

/////////////////////////////  HELPDESK  //////////////////////////////////////
  
   function get_categoria_helpdesk($codigo, $nombre = '', $sit = '') {
		$nombre = trim($nombre);
		
      $sql= "SELECT * ";
      $sql.= " FROM hd_categoria";
      $sql.= " WHERE 1 = 1";
      if(strlen($codigo)>0) { 
         $sql.= " AND cat_codigo IN($codigo)"; 
      }
      if(strlen($nombre)>0) { 
         $sql.= " AND cat_nombre like '%$nombre%'"; 
      }
      if(strlen($sit)>0) { 
         $sql.= " AND cat_situacion = '$sit'"; 
      }
      $sql.= " ORDER BY cat_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function count_categoria_helpdesk($codigo, $nombre = '', $sit = '') {
		$nombre = trim($nombre);
		
      $sql= "SELECT COUNT(*) as total";
      $sql.= " FROM hd_categoria";
      $sql.= " WHERE 1 = 1";
      if(strlen($codigo)>0) { 
         $sql.= " AND cat_codigo IN($codigo)"; 
      }
      if(strlen($nombre)>0) { 
         $sql.= " AND cat_nombre like '%$nombre%'"; 
      }
      if(strlen($sit)>0) { 
         $sql.= " AND cat_situacion = '$sit'"; 
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach($result as $row){
         $total = $row['total'];
      }
      return $total;
	}
	
   function insert_categoria_helpdesk($codigo,$nombre,$color){
      $nombre = trim($nombre);
      $color = trim($color);
      
      $sql = "INSERT INTO hd_categoria";
      $sql.= " VALUES ($codigo,'$nombre','$color',1);";
      //echo $sql;
      return $sql;
   }
	function modifica_categoria_helpdesk($codigo,$nombre,$color){
		$nombre = trim($nombre);
		$color = trim($color);
      
		$sql = "UPDATE hd_categoria SET "; 
		$sql.= "cat_nombre = '$nombre', "; 
		$sql.= "cat_color = '$color'"; 
		$sql.= " WHERE cat_codigo = $codigo"; 	
		//echo $sql;
		return $sql;
	}
	function cambia_situacion_categoria_helpdesk($codigo,$sit){
		
		$sql = "UPDATE hd_categoria SET cat_situacion = $sit"; 
		$sql.= " WHERE cat_codigo = $codigo"; 	
		
		return $sql;
	}
	function max_categoria_helpdesk(){
      $sql = "SELECT max(cat_codigo) as max ";
      $sql.= " FROM hd_categoria";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}
   
/////////////////////////////  AUDITORIA  //////////////////////////////////////
  
   function get_categoria_auditoria($codigo, $nombre = '', $sit = '') {
		$nombre = trim($nombre);
		
      $sql= "SELECT * ";
      $sql.= " FROM aud_categoria";
      $sql.= " WHERE 1 = 1";
      if(strlen($codigo)>0) { 
         $sql.= " AND cat_codigo IN($codigo)"; 
      }
      if(strlen($nombre)>0) { 
         $sql.= " AND cat_nombre like '%$nombre%'"; 
      }
      if(strlen($sit)>0) { 
         $sql.= " AND cat_situacion = '$sit'"; 
      }
      $sql.= " ORDER BY cat_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function count_categoria_auditoria($codigo, $nombre = '', $sit = '') {
		$nombre = trim($nombre);
		
      $sql= "SELECT COUNT(*) as total";
      $sql.= " FROM aud_categoria";
      $sql.= " WHERE 1 = 1";
      if(strlen($codigo)>0) { 
         $sql.= " AND cat_codigo IN($codigo)"; 
      }
      if(strlen($nombre)>0) { 
         $sql.= " AND cat_nombre like '%$nombre%'"; 
      }
      if(strlen($sit)>0) { 
         $sql.= " AND cat_situacion = '$sit'"; 
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach($result as $row){
         $total = $row['total'];
      }
      return $total;
	}
	
   function insert_categoria_auditoria($codigo,$nombre,$color){
      $nombre = trim($nombre);
      $color = trim($color);
      
      $sql = "INSERT INTO aud_categoria";
      $sql.= " VALUES ($codigo,'$nombre','$color',1);";
      //echo $sql;
      return $sql;
   }
	function modifica_categoria_auditoria($codigo,$nombre,$color){
		$nombre = trim($nombre);
		$color = trim($color);
      
		$sql = "UPDATE aud_categoria SET "; 
		$sql.= "cat_nombre = '$nombre', "; 
		$sql.= "cat_color = '$color'"; 
		$sql.= " WHERE cat_codigo = $codigo"; 	
		//echo $sql;
		return $sql;
	}
	function cambia_situacion_categoria_auditoria($codigo,$sit){
		
		$sql = "UPDATE aud_categoria SET cat_situacion = $sit"; 
		$sql.= " WHERE cat_codigo = $codigo"; 	
		
		return $sql;
	}
	function max_categoria_auditoria(){
      $sql = "SELECT max(cat_codigo) as max ";
      $sql.= " FROM aud_categoria";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}

   
/////////////////////////////  PPM (MANTENIMIENTO)  //////////////////////////////////////
  
   function get_categoria_ppm($codigo, $nombre = '', $sit = '') {
		$nombre = trim($nombre);
		
      $sql= "SELECT * ";
      $sql.= " FROM ppm_categoria";
      $sql.= " WHERE 1 = 1";
      if(strlen($codigo)>0) { 
         $sql.= " AND cat_codigo IN($codigo)"; 
      }
      if(strlen($nombre)>0) { 
         $sql.= " AND cat_nombre like '%$nombre%'"; 
      }
      if(strlen($sit)>0) { 
         $sql.= " AND cat_situacion = '$sit'"; 
      }
      $sql.= " ORDER BY cat_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function count_categoria_ppm($codigo, $nombre = '', $sit = '') {
		$nombre = trim($nombre);
		
      $sql= "SELECT COUNT(*) as total";
      $sql.= " FROM ppm_categoria";
      $sql.= " WHERE 1 = 1";
      if(strlen($codigo)>0) { 
         $sql.= " AND cat_codigo IN($codigo)"; 
      }
      if(strlen($nombre)>0) { 
         $sql.= " AND cat_nombre like '%$nombre%'"; 
      }
      if(strlen($sit)>0) { 
         $sql.= " AND cat_situacion = '$sit'"; 
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach($result as $row){
         $total = $row['total'];
      }
      return $total;
	}
	
   function insert_categoria_ppm($codigo,$nombre,$color){
      $nombre = trim($nombre);
      $color = trim($color);
      
      $sql = "INSERT INTO ppm_categoria";
      $sql.= " VALUES ($codigo,'$nombre','$color',1);";
      //echo $sql;
      return $sql;
   }
	function modifica_categoria_ppm($codigo,$nombre,$color){
		$nombre = trim($nombre);
		$color = trim($color);
      
		$sql = "UPDATE ppm_categoria SET "; 
		$sql.= "cat_nombre = '$nombre', "; 
		$sql.= "cat_color = '$color'"; 
		$sql.= " WHERE cat_codigo = $codigo"; 	
		//echo $sql;
		return $sql;
	}
	function cambia_situacion_categoria_ppm($codigo,$sit){
		
		$sql = "UPDATE ppm_categoria SET cat_situacion = $sit"; 
		$sql.= " WHERE cat_codigo = $codigo"; 	
		
		return $sql;
	}
	function max_categoria_ppm(){
      $sql = "SELECT max(cat_codigo) as max ";
      $sql.= " FROM ppm_categoria";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}
   
   
/////////////////////////////  INDICADORES   //////////////////////////////////////
  
   function get_categoria_indicador($codigo, $nombre = '', $sit = '') {
		$nombre = trim($nombre);
		
      $sql= "SELECT * ";
      $sql.= " FROM ind_categoria ";
      $sql.= " WHERE 1 = 1";
      if(strlen($codigo)>0) { 
         $sql.= " AND cat_codigo IN($codigo)"; 
      }
      if(strlen($nombre)>0) { 
         $sql.= " AND cat_nombre like '%$nombre%'"; 
      }
      if(strlen($sit)>0) { 
         $sql.= " AND cat_situacion = '$sit'"; 
      }
      $sql.= " ORDER BY cat_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function count_categoria_indicador($codigo, $nombre = '', $sit = '') {
		$nombre = trim($nombre);
		
      $sql= "SELECT COUNT(*) as total";
      $sql.= " FROM ind_categoria ";
      $sql.= " WHERE 1 = 1";
      if(strlen($codigo)>0) { 
         $sql.= " AND cat_codigo IN($codigo)"; 
      }
      if(strlen($nombre)>0) { 
         $sql.= " AND cat_nombre like '%$nombre%'"; 
      }
      if(strlen($sit)>0) { 
         $sql.= " AND cat_situacion = '$sit'"; 
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach($result as $row){
         $total = $row['total'];
      }
      return $total;
	}
	
   function insert_categoria_indicador($codigo,$nombre,$color){
      $nombre = trim($nombre);
      $color = trim($color);
      
      $sql = "INSERT INTO ind_categoria ";
      $sql.= " VALUES ($codigo,'$nombre','$color',1);";
      //echo $sql;
      return $sql;
   }
	function modifica_categoria_indicador($codigo,$nombre,$color){
		$nombre = trim($nombre);
		$color = trim($color);
      
		$sql = "UPDATE ind_categoria  SET "; 
		$sql.= "cat_nombre = '$nombre', "; 
		$sql.= "cat_color = '$color'"; 
		$sql.= " WHERE cat_codigo = $codigo"; 	
		//echo $sql;
		return $sql;
	}
	function cambia_situacion_categoria_indicador($codigo,$sit){
		
		$sql = "UPDATE ind_categoria  SET cat_situacion = $sit"; 
		$sql.= " WHERE cat_codigo = $codigo"; 	
		
		return $sql;
	}
	function max_categoria_indicador(){
      $sql = "SELECT max(cat_codigo) as max ";
      $sql.= " FROM ind_categoria ";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}
   
   
}
