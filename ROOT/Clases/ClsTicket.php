<?php
require_once ("ClsConex.php");

class ClsTicket extends ClsConex{
/* Situacion 1 = Abierto, 2 = Cerrado, 0 = Anulado */

/////////////////////////////  TICKET  //////////////////////////////////
  
   function get_ticket($codigo,$categoria = '',$sede = '',$incidente = '', $prioridad = '', $status = '', $fini = '',$ffin = '',$usuario = '',$situacion = '',$orderfecha = 'DESC') {
		$sede = ($sede == "")?$_SESSION["sedes_in"]:$sede;
		
        $sql= "SELECT *, ";
        $sql.= " (SELECT usu_nombre FROM seg_usuarios WHERE usu_id = tic_usuario) as usuario_registro,";
        $sql.= " (SELECT dm_desc FROM mast_mundep WHERE dm_codigo = sed_municipio) as sede_municipio,";
        $sql.= " (SELECT tus_fecha_registro FROM hd_ticket_status WHERE tus_status != 1 AND tus_ticket = tic_codigo ORDER BY tus_fecha_registro ASC LIMIT 0,1) as tic_primer_status,";
        $sql.= " (SELECT tus_fecha_registro FROM hd_ticket_status WHERE tus_status = 100 AND tus_ticket = tic_codigo ORDER BY tus_fecha_registro ASC LIMIT 0,1) as tic_cierre_status,";
        $sql.= " (SELECT esp_tiempo FROM hd_ticket_espera WHERE esp_ticket = tic_codigo) as tic_espera,";
        $sql.= " (SELECT bit_observaciones FROM hd_ticket_bitacora WHERE bit_ticket = tic_codigo AND bit_descripcion LIKE 'Cambio de Status%' ORDER BY bit_codigo DESC LIMIT 0,1) as tic_status_observaciones";
        $sql.= " FROM hd_ticket, hd_incidente, hd_status, hd_prioridad, hd_categoria, sis_area, sis_sector, sis_sede";
        $sql.= " WHERE tic_incidente = inc_codigo";
        $sql.= " AND tic_status = sta_codigo";
        $sql.= " AND tic_prioridad = pri_codigo";
        $sql.= " AND inc_categoria = cat_codigo";
        $sql.= " AND tic_sede = sed_codigo";
        $sql.= " AND tic_sector = sec_codigo";
        $sql.= " AND tic_area = are_codigo";
        if(strlen($codigo)>0) { 
            $sql.= " AND tic_codigo = $codigo"; 
        }
        if(strlen($categoria)>0) { 
            $sql.= " AND inc_categoria IN($categoria)"; 
        }
        if(strlen($sede)>0) { 
            $sql.= " AND tic_sede IN($sede)"; 
        }
        if(strlen($incidente)>0) { 
            $sql.= " AND tic_incidente = $incidente"; 
        }
        if(strlen($prioridad)>0) { 
            $sql.= " AND tic_prioridad = $prioridad"; 
        }
        if(strlen($status)>0) { 
            $sql.= " AND tic_status = $status"; 
        }
        if($fini != "" && $ffin != "") { 
            $fini = $this->regresa_fecha($fini);
            $ffin = $this->regresa_fecha($ffin);
            $sql.= " AND tic_fecha_registro BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'"; 
        }
        if(strlen($usuario)>0) { 
         $sql.= " AND tic_usuario IN($usuario)"; 
        }
        if(strlen($situacion)>0) { 
         $sql.= " AND tic_situacion IN($situacion)"; 
        }
        $sql.= " ORDER BY tic_fecha_registro $orderfecha, tic_sede ASC, tic_prioridad ASC, inc_categoria ASC, tic_status ASC, tic_incidente ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql."<br><br>";
		return $result;
	}
	function count_ticket($codigo,$categoria = '',$incidente = '', $prioridad = '', $status = '', $sede = '',$fini = '',$ffin = '',$situacion = '',$orderfecha = 'DESC') {
		$sede = ($sede == "")?$_SESSION["sedes_in"]:$sede;
		
      $sql= "SELECT COUNT(*) as total";
		$sql.= " FROM hd_ticket, hd_incidente, hd_status, hd_prioridad, hd_categoria, sis_sede";
      $sql.= " WHERE tic_incidente = inc_codigo";
      $sql.= " AND tic_status = sta_codigo";
      $sql.= " AND tic_prioridad = pri_codigo";
      $sql.= " AND inc_categoria = cat_codigo";
      $sql.= " AND tic_sede = sed_codigo";
      if(strlen($codigo)>0) { 
         $sql.= " AND tic_codigo = $codigo"; 
      }
      if(strlen($categoria)>0) { 
         $sql.= " AND inc_categoria IN($categoria)"; 
      }
      if(strlen($sede)>0) { 
         $sql.= " AND tic_sede IN($sede)"; 
      }
      if(strlen($incidente)>0) { 
         $sql.= " AND tic_incidente = $incidente"; 
      }
      if(strlen($prioridad)>0) { 
         $sql.= " AND tic_prioridad = $prioridad"; 
      }
      if(strlen($status)>0) { 
         $sql.= " AND tic_status = $status"; 
      }
      if($fini != "" && $ffin != "") { 
         $fini = $this->regresa_fecha($fini);
         $ffin = $this->regresa_fecha($ffin);
         $sql.= " AND tic_fecha_registro BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'"; 
      }
      if(strlen($situacion)>0) { 
         $sql.= " AND tic_situacion IN($situacion)"; 
      }
		//echo $sql;
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$total = $row['total'];
		}
		return $total;
	}
   
   function get_ticket_asignado($codigo,$categoria = '',$sede = '',$incidente = '', $prioridad = '', $status = '', $fini = '',$ffin = '',$usuario = '',$sitasignacion = '',$situacion = '',$orderfecha = 'DESC') {
		$sede = ($sede == "")?$_SESSION["sedes_in"]:$sede;
		
      $sql= "SELECT *, ";
      $sql.= " (SELECT usu_nombre FROM seg_usuarios WHERE usu_id = tic_usuario) as usuario_registro,";
      $sql.= " (SELECT dm_desc FROM mast_mundep WHERE dm_codigo = sed_municipio) as sede_municipio";
      $sql.= " FROM hd_ticket, hd_asignacion, hd_incidente, hd_status, hd_prioridad, hd_categoria, sis_sede, sis_area, sis_sector";
      $sql.= " WHERE tic_codigo = asi_ticket";
      $sql.= " AND tic_incidente = inc_codigo";
      $sql.= " AND tic_status = sta_codigo";
      $sql.= " AND tic_prioridad = pri_codigo";
      $sql.= " AND inc_categoria = cat_codigo";
      $sql.= " AND tic_sede = sed_codigo";
      $sql.= " AND tic_sector = sec_codigo";
		$sql.= " AND tic_area = are_codigo";
      if(strlen($codigo)>0) { 
         $sql.= " AND tic_codigo = $codigo"; 
      }
      if(strlen($categoria)>0) { 
         $sql.= " AND inc_categoria IN($categoria)"; 
      }
      if(strlen($sede)>0) { 
         $sql.= " AND tic_sede IN($sede)"; 
      }
      if(strlen($incidente)>0) { 
         $sql.= " AND tic_incidente = $incidente"; 
      }
      if(strlen($prioridad)>0) { 
         $sql.= " AND tic_prioridad = $prioridad"; 
      }
      if(strlen($status)>0) { 
         $sql.= " AND tic_status = $status"; 
      }
      if($fini != "" && $ffin != "") { 
         $fini = $this->regresa_fecha($fini);
         $ffin = $this->regresa_fecha($ffin);
         $sql.= " AND tic_fecha_registro BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'"; 
      }
      if(strlen($usuario)>0) { 
         $sql.= " AND asi_usuario IN($usuario)"; 
      }
      if(strlen($sitasignacion)>0) { 
         $sql.= " AND asi_situacion IN($sitasignacion)"; 
      }
      if(strlen($situacion)>0) { 
         $sql.= " AND tic_situacion IN($situacion)"; 
      }
      $sql.= " ORDER BY tic_fecha_registro $orderfecha, tic_sede ASC, tic_prioridad ASC, inc_categoria ASC, tic_status ASC, tic_incidente ASC";
		
		$result = $this->exec_query($sql);
		// echo $sql;
		return $result;
	}
   
   function get_categoria_chart($fini = '',$ffin = '',$sede = '') {
		$sede = ($sede == "")?$_SESSION["sedes_in"]:$sede;
		
      $sql= "SELECT *, ";
         $sql.= "(SELECT COUNT(tic_codigo) FROM hd_ticket,hd_incidente";
         $sql.= " WHERE tic_incidente = inc_codigo";
         $sql.= " AND inc_categoria = cat_codigo";
         if(strlen($sede)>0) { 
            $sql.= " AND tic_sede IN($sede)"; 
         }
         if($fini != "" && $ffin != "") { 
            $fini = $this->regresa_fecha($fini);
            $ffin = $this->regresa_fecha($ffin);
            $sql.= " AND tic_fecha_registro BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'"; 
         }
         $sql.= " ) as total_tickets";
		$sql.= " FROM hd_categoria";
      $sql.= " WHERE cat_situacion = 1";
      $sql.= " ORDER BY cat_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
   
   function get_status_chart($fini = '',$ffin = '',$sede = '') {
        $sede = ($sede == "")?$_SESSION["sedes_in"]:$sede;
        
        $sql= "SELECT *, ";
        $sql.= "(SELECT COUNT(tic_codigo) FROM hd_ticket";
        $sql.= " WHERE tic_status = sta_codigo";
        if(strlen($sede)>0) { 
            $sql.= " AND tic_sede IN($sede)"; 
        }
        if($fini != "" && $ffin != "") { 
            $fini = $this->regresa_fecha($fini);
            $ffin = $this->regresa_fecha($ffin);
            $sql.= " AND tic_fecha_registro BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'"; 
        }
        $sql.= " ) as total_tickets";
        $sql.= " FROM hd_status";
        $sql.= " WHERE sta_situacion = 1";
        $sql.= " ORDER BY sta_codigo ASC";
        
        $result = $this->exec_query($sql);
        //echo $sql;
        return $result;
	}
   
   function get_prioridad_chart($fini = '',$ffin = '',$sede = '',$categoria = '') {
		$sede = ($sede == "")?$_SESSION["sedes_in"]:$sede;
		
      $sql= "SELECT *, ";
         $sql.= "(SELECT COUNT(tic_codigo) FROM hd_ticket, hd_incidente ";
         $sql.= " WHERE tic_incidente = inc_codigo";
         $sql.= " AND tic_prioridad = pri_codigo";
         if(strlen($sede)>0) { 
            $sql.= " AND tic_sede IN($sede)"; 
         }
         if(strlen($categoria)>0) { 
            $sql.= " AND inc_categoria IN($categoria)"; 
         }
         if($fini != "" && $ffin != "") { 
            $fini = $this->regresa_fecha($fini);
            $ffin = $this->regresa_fecha($ffin);
            $sql.= " AND tic_fecha_registro BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'"; 
         }
         $sql.= " ) as total_tickets";
		$sql.= " FROM hd_prioridad";
      $sql.= " WHERE pri_situacion = 1";
      $sql.= " ORDER BY pri_solucion ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql."<br><br>";
		return $result;
	}
	function insert_ticket($codigo,$desc,$incidente,$prioridad,$status,$sede,$sector,$area,$usuario){
      $desc = trim($desc);
      $fsis = date("Y-m-d H:i:s");
      
      $sql = "INSERT INTO hd_ticket";
      $sql.= " VALUES ($codigo,'$desc',$incidente,$prioridad,$status,$sede,$sector,$area,'$fsis','$fsis',$usuario,1,1)";
      $sql.= " ON DUPLICATE KEY UPDATE";
      $sql.= " tic_descripcion = '$desc', ";
      $sql.= " tic_incidente = '$incidente', ";
      $sql.= " tic_prioridad = '$prioridad', ";
      $sql.= " tic_sede = '$sede', ";
      $sql.= " tic_sector = '$sector', ";
      $sql.= " tic_area = '$area'; ";
      
      //echo $sql;
      return $sql;
	}
	function cambia_sit_ticket($codigo,$status){
      
		$sql = "UPDATE hd_ticket SET tic_status = $status";
		$sql.= " WHERE tic_codigo = $codigo; "; 	
		
		return $sql;
	}
   
   function cambia_escalon_ticket($codigo,$escalon){
      
		$sql = "UPDATE hd_ticket SET tic_escalon = $escalon";
		$sql.= " WHERE tic_codigo = $codigo; "; 	
		
		return $sql;
	}
   
   function cerrar_ticket($codigo){
		$fsis = date("Y-m-d H:i:s");
		$sql = "UPDATE hd_ticket SET tic_situacion = 2, tic_fecha_fin = '$fsis'";
		$sql.= " WHERE tic_codigo = $codigo; "; 	
		
		return $sql;
	}
	function max_ticket(){
	   $sql = "SELECT max(tic_codigo) as max ";
		$sql.= " FROM hd_ticket";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}    
/////////////////////////////  ASIGNACION - USUARIOS  //////////////////////////////////////    
	function get_asignacion($ticket,$usuario,$situacion = '') {
		
      $sql= "SELECT * ";
      $sql.= " FROM hd_asignacion, seg_usuarios, hd_ticket, hd_prioridad";
      $sql.= " WHERE usu_id = asi_usuario";
      $sql.= " AND tic_codigo = asi_ticket";
      $sql.= " AND tic_prioridad = pri_codigo";
      if(strlen($ticket)>0) { 
         $sql.= " AND asi_ticket = $ticket"; 
      }
      if(strlen($usuario)>0) { 
         $sql.= " AND asi_usuario = $usuario"; 
      }
      if(strlen($situacion)>0) { 
         $sql.= " AND asi_situacion = $situacion"; 
      }
      $sql.= " ORDER BY asi_ticket ASC";
      
      $result = $this->exec_query($sql);
      //echo $sql;
      return $result;
	}
	
    function insert_asignacion($ticket,$usuario){
        $fsis = date("Y-m-d H:i:s");
        
        $sql = "INSERT INTO hd_asignacion";
        $sql.= " VALUES ($ticket,$usuario,'$fsis',1)";
        $sql.= " ON DUPLICATE KEY UPDATE asi_fecha_registro = '$fsis', asi_situacion = 1; ";
        //echo $sql;
        return $sql;
    }   
    function cambia_sit_asignacion($ticket,$usuario,$situacion){
		
		$sql = "UPDATE hd_asignacion SET asi_situacion = $situacion"; 
		$sql.= " WHERE asi_ticket = $ticket "; 	
		$sql.= " AND asi_usuario = $usuario; "; 	
		
		return $sql;
	}/////////////////////////////  TICKET - STATUS  //////////////////////////////////////    
	function get_ticket_status($ticket,$status) {
        
      $sql= "SELECT * ";
      $sql.= " FROM hd_ticket_status,hd_status";
      $sql.= " WHERE sta_codigo = tus_status";
      if(strlen($ticket)>0) { 
         $sql.= " AND tus_ticket = $ticket"; 
      }
      if(strlen($status)>0) { 
         $sql.= " AND tus_status = $status"; 
      }
      $sql.= " ORDER BY tus_ticket ASC";
      
      $result = $this->exec_query($sql);
      //echo $sql;
      return $result;   }   
   function insert_ticket_status($ticket,$status,$obs){
      $obs = trim($obs);
      $fsis = date("Y-m-d H:i:s");
      
      $sql = "INSERT INTO hd_ticket_status";
      $sql.= " VALUES ($ticket,$status,'$obs','$fsis',1)";
      $sql.= " ON DUPLICATE KEY UPDATE tus_observaciones = '$obs',tus_fecha_registro = '$fsis'; ";
      //echo $sql;
      return $sql;
   }
   
   
   function get_ultimo_status($ticket) {
        
      $sql = "SELECT tus_status, tus_fecha_registro";
      $sql.= " FROM hd_ticket_status";
      $sql.= " WHERE tus_ticket = $ticket";
      $sql.= " ORDER BY tus_fecha_registro DESC LIMIT 0,1";
      //echo $sql;
		$result = $this->exec_query($sql);
      
      if(is_array($result)){
         foreach($result as $row){
            $status = $row['tus_status'];
            $fecha = $row['tus_fecha_registro'];
         }
      }
      
      $arrstatus = array(
			"status" => $status,
			"fecha" => $fecha
		);return $arrstatus;   }    
   function cambia_sit_ticket_status($ticket,$status,$situacion){      $sql = "UPDATE hd_ticket_status SET tus_situacion = $situacion"; 
      $sql.= " WHERE tus_ticket = $ticket "; 	
      $sql.= " AND tus_status = $status; "; 	
      
      return $sql;   }
     
/////////////////////////////  TICKET - BITACORA  //////////////////////////////////////    
	function get_bitacora($codigo,$ticket) {
   
      $sql= "SELECT * ";
      $sql.= " FROM hd_ticket_bitacora, seg_usuarios";
      $sql.= " WHERE usu_id = bit_usuario";
      if(strlen($codigo)>0) { 
         $sql.= " AND bit_codigo = $codigo"; 
      }
      if(strlen($ticket)>0) { 
         $sql.= " AND bit_ticket = $ticket"; 
      }
      $sql.= " ORDER BY bit_codigo ASC";
      
      $result = $this->exec_query($sql);
      //echo $sql;
      return $result;
   
   }
   
   function insert_bitacora($codigo,$ticket,$desc,$obs = ''){
      $desc = trim($desc);
      $obs = trim($obs);
      $fsis = date("Y-m-d H:i:s");
      $usuario = $_SESSION["codigo"];
      $sql = "INSERT INTO hd_ticket_bitacora";
      $sql.= " VALUES ($codigo,$ticket,'$desc','$obs','$fsis',$usuario)";
      $sql.= " ON DUPLICATE KEY UPDATE bit_descripcion = '$desc', bit_observaciones = '$obs', bit_fecha_registro = '$fsis'; ";
      //echo $sql;
      return $sql;
   }
   
   
   function max_bitacora($codigo){
      $sql = "SELECT max(bit_codigo) as max ";
      $sql.= " FROM hd_ticket_bitacora";
      $sql.= " WHERE bit_ticket = $codigo;"; 	
      $result = $this->exec_query($sql);
      foreach($result as $row){
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
	}
   
   
   function cambia_sit_bitacora($codigo,$situacion){
   
      $sql = "UPDATE hd_ticket_bitacora SET bit_situacion = $situacion"; 
      $sql.= " WHERE bit_codigo = $codigo "; 	
   
      return $sql;
   
   }
   
   /////////////////////////////  TICKET - ESPERA  //////////////////////////////////////    
	function get_espera($ticket) {
   
      $sql= "SELECT * ";
      $sql.= " FROM hd_ticket_espera";
      $sql.= " WHERE usu_id = esp_usuario";
      $sql.= " AND esp_ticket = $ticket"; 
      $sql.= " ORDER BY esp_ticket ASC";
      
      $result = $this->exec_query($sql);
      //echo $sql;
      return $result;
   
   }
   
   function insert_espera($ticket,$tiempo){
      $sql = "INSERT INTO hd_ticket_espera";
      $sql.= " VALUES ($ticket,$tiempo)";
      $sql.= " ON DUPLICATE KEY UPDATE esp_tiempo = esp_tiempo + $tiempo;";
      //echo $sql;
      return $sql;
   }
     
   /////////////////////////////  FOTOS  //////////////////////////////////////    
	function get_fotos($codigo,$ticket,$posicion = '') {
		
      $sql= "SELECT * ";
      $sql.= " FROM hd_foto_ticket, hd_status";
      $sql.= " WHERE sta_posicion = fot_posicion";
      if(strlen($codigo)>0) { 
         $sql.= " AND fot_codigo = $codigo"; 
      }
      if(strlen($ticket)>0) { 
         $sql.= " AND fot_ticket IN($ticket)"; 
      }
      if(strlen($posicion)>0) { 
         $sql.= " AND fot_posicion = $posicion"; 
      }
      $sql.= " ORDER BY fot_ticket ASC, fot_posicion ASC";
      
      $result = $this->exec_query($sql);
      //echo $sql;
      return $result;
	}
	function insert_foto($codigo,$ticket,$posicion,$foto){
      $fsis = date("Y-m-d H:i:s");
      
      $sql = "INSERT INTO hd_foto_ticket";
      $sql.= " VALUES ($codigo,$ticket,$posicion,'$foto','$fsis')";
      $sql.= " ON DUPLICATE KEY UPDATE";
      $sql.= " fot_foto = '$foto', ";
      $sql.= " fot_fecha_registro = '$fsis'; ";
      //echo $sql;
      return $sql;
	}   
   function delete_foto($codigo){
		
		$sql = "DELETE FROM hd_foto_ticket"; 
		$sql.= " WHERE fot_codigo = $codigo; "; 	
		
		return $sql;
	}   
   function max_foto(){
      $sql = "SELECT max(fot_codigo) as max ";
      $sql.= " FROM hd_foto_ticket";
      $result = $this->exec_query($sql);
      foreach($result as $row){
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
	}    

}
