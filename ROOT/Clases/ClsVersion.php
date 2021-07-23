<?php
require_once ("ClsConex.php");

class ClsVersion extends ClsConex{

/////////////////////////////  VERSIONES  //////////////////////////////////////
  
   function get_version($codigo, $platafarma = '', $software = '') {
		$software = trim($software);
		
      $sql= "SELECT * ";
      $sql.= " FROM mast_version, seg_usuarios";
      $sql.= " WHERE usu_id = ver_usuario_update";
      if(strlen($codigo)>0) { 
         $sql.= " AND ver_codigo = $codigo"; 
      }
      if(strlen($platafarma)>0) { 
         $sql.= " AND ver_plataforma = $platafarma"; 
      }
      if(strlen($software)>0) { 
         $sql.= " AND ver_software like '%$software%'"; 
      }
      $sql.= " ORDER BY ver_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function count_version($codigo, $platafarma = '', $software = '') {
		$software = trim($software);
		
      $sql= "SELECT COUNT(*) as total";
      $sql.= " FROM mast_version, seg_usuarios";
      $sql.= " WHERE usu_id = ver_usuario_update";
      if(strlen($codigo)>0) { 
         $sql.= " AND ver_codigo = $codigo"; 
      }
      if(strlen($platafarma)>0) { 
         $sql.= " AND ver_plataforma = $platafarma"; 
      }
      if(strlen($software)>0) { 
         $sql.= " AND ver_software like '%$software%'"; 
      }
       //echo $sql;
      $result = $this->exec_query($sql);
      foreach($result as $row){
         $total = $row['total'];
      }
      return $total;
	}
	
   function insert_version($codigo,$software,$plataforma,$version){
      $software = trim($software);
      $version = trim($version);
      $fsis = date("Y-m-d H:i:s");
      $usuario = $_SESSION["codigo"];
      
      $sql = "INSERT INTO mast_version";
      $sql.= " VALUES ($codigo,'$software','$plataforma','$version','$fsis','$usuario');";
      //echo $sql;
      return $sql;
   }
	function modifica_version($codigo,$software,$plataforma,$version){
		$software = trim($software);
		$version = trim($version);
      $fsis = date("Y-m-d H:i:s");
      $usuario = $_SESSION["codigo"];
      
		$sql = "UPDATE mast_version SET "; 
		$sql.= "ver_software = '$software', "; 
		$sql.= "ver_plataforma = '$plataforma',"; 
		$sql.= "ver_version = '$version',"; 
		$sql.= "ver_fecha_update = '$fsis',"; 
		$sql.= "ver_usuario_update = '$usuario'";
      
		$sql.= " WHERE ver_codigo = $codigo"; 	
		//echo $sql;
		return $sql;
	}
   
   
   function cambia_version($codigo,$version){
		
		$sql = "UPDATE mast_version SET ver_version = '$version'"; 
		$sql.= " WHERE ver_codigo = $codigo"; 	
		
		return $sql;
	}
   function max_version(){
      $sql = "SELECT max(ver_codigo) as max ";
      $sql.= " FROM mast_version";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}
   
}
