<?php
require_once ("ClsConex.php");

class ClsFalla extends ClsConex{

/////////////////////////////  ACTIVO  //////////////////////////////////////
  
   function get_falla($codigo,$activo, $sede = '', $sector = '', $area = '', $situacion = '', $fini = '',$ffin = '') {
      $sede = ($sede == "")?$_SESSION["sedes_in"]:$sede;
      
      $sql= "SELECT * ";
      $sql.= " FROM ppm_activo, ppm_fallas, seg_usuarios, sis_area, sis_sector, sis_sede";
      $sql.= " WHERE usu_id = fall_usuario_regsitro";
      $sql.= " AND fall_activo = act_codigo";
      $sql.= " AND act_sede = sed_codigo";
      $sql.= " AND act_sector = sec_codigo";
      $sql.= " AND act_area = are_codigo";
      if(strlen($codigo)>0) { 
         $sql.= " AND fall_codigo = $codigo"; 
      }
      if(strlen($activo)>0) { 
         $sql.= " AND fall_activo = $activo"; 
      }
      if(strlen($sede)>0) { 
         $sql.= " AND act_sede IN($sede)"; 
      }
      if(strlen($sector)>0) { 
         $sql.= " AND act_sector IN($sector)"; 
      }
      if(strlen($area)>0) { 
         $sql.= " AND act_area IN($area)"; 
      }
      if(strlen($situacion)>0) { 
         $sql.= " AND fall_situacion IN($situacion)"; 
      }
      if($fini != "" && $ffin != "") { 
			$fini = $this->regresa_fecha($fini);
			$ffin = $this->regresa_fecha($ffin);
			$sql.= " AND fall_fecha_falla BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'";
		}
      
      $sql.= " ORDER BY act_sede ASC, act_sector ASC, act_area ASC, act_codigo ASC, fall_codigo ASC";
      
      $result = $this->exec_query($sql);
      //echo $sql."<br><br>";
      return $result;
   }
      
   function countfalla($codigo,$activo, $sector = '', $area = '', $situacion = '', $fini = '',$ffin = '') {
      
      $sql= "SELECT COUNT(*) as total";
      $sql.= " FROM ppm_activo, ppm_fallas, seg_usuarios, sis_area, sis_sector, sis_sede";
      $sql.= " WHERE usu_id = fall_usuario_regsitro";
      $sql.= " AND fall_activo = act_codigo";
      $sql.= " AND act_sede = sed_codigo";
      $sql.= " AND act_sector = sec_codigo";
      $sql.= " AND act_area = are_codigo";
      if(strlen($codigo)>0) { 
         $sql.= " AND fall_codigo = $codigo"; 
      }
      if(strlen($activo)>0) { 
         $sql.= " AND fall_activo = $activo"; 
      }
      if(strlen($sector)>0) { 
         $sql.= " AND act_sector IN($sector)"; 
      }
      if(strlen($area)>0) { 
         $sql.= " AND act_area IN($area)"; 
      }
      if(strlen($situacion)>0) { 
         $sql.= " AND fall_situacion IN($situacion)"; 
      }
      if($fini != "" && $ffin != "") { 
			$fini = $this->regresa_fecha($fini);
			$ffin = $this->regresa_fecha($ffin);
			$sql.= " AND fall_fecha_falla BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'";
		}
      
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach($result as $row){
         $total = $row['total'];
      }
      return $total;
   }
	
   
	function insert_falla($codigo,$activo,$falla,$fecha){
      $falla = trim($falla);
      $fecha = $this->regresa_fechaHora($fecha);
      $fsis = date("Y-m-d H:i:s");
      $usuario = $_SESSION["codigo"];
		
		$sql = "INSERT INTO ppm_fallas";
		$sql.= " VALUES ($codigo,$activo,'$falla','$fecha','$fsis','$usuario','$fsis',0,'',1);";
		//echo $sql;
		return $sql;
	}


	function modifica_falla($codigo,$activo,$falla,$fecha){
      $falla = trim($falla);
      $fecha = $this->regresa_fechaHora($fecha);
      
      $sql = "UPDATE ppm_fallas SET ";
      $sql.= "fall_falla = '$falla',"; 		
      $sql.= "fall_fecha_falla = '$fecha'"; 		
      
      $sql.= " WHERE fall_codigo = $codigo"; 	
		$sql.= " AND fall_activo = $activo; "; 	
      //echo $sql;
      return $sql;
	}    
   function cambia_sit_falla($codigo,$activo,$situacion,$fecha,$comentario){
      $fecha = $this->regresa_fechaHora($fecha);
		$usuario = $_SESSION["codigo"];
      
		$sql = "UPDATE ppm_fallas SET ";
		$sql.= "fall_situacion = $situacion,"; 
		$sql.= "fall_fecha_solucion = '$fecha',"; 		
      $sql.= "fall_comentario_solucion = '$comentario',"; 		
      $sql.= "fall_usuario_solucion = '$usuario'"; 		
      		
		$sql.= " WHERE fall_codigo = $codigo"; 	
		$sql.= " AND fall_activo = $activo; "; 	
		
		return $sql;
	}   
   function max_falla($activo){
      $sql = "SELECT max(fall_codigo) as max ";
      $sql.= " FROM ppm_fallas";
      $sql.= " WHERE fall_activo = $activo";
      $result = $this->exec_query($sql);
      foreach($result as $row){
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
	}}
?>