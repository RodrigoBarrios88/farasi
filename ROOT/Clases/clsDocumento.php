<?php
require_once ("ClsConex.php");

class ClsDocumento extends ClsConex{
/* Situacion 1 = ACTIVO, 2 = INACTIVO */

   function get_documento($codigo='', $titulo = '', $tipo = '', $entidad= '',$vigencia = '', $situacion = '1') {
		$titulo = trim($titulo);
      $entidad = trim($entidad);
		$tipo = trim($tipo);

      $sql = "SELECT * ";
      $sql.= " FROM req_documento";
      $sql.= " WHERE 1 = 1";
      if(strlen($codigo)>0) { 
         $sql.= " AND doc_codigo = $codigo "; 
      }
      if(strlen($titulo)>0) { 
         $sql.= " AND doc_titulo like '%$titulo%'"; 
      }
      if(strlen($tipo)>0) {   
         $sql.= " AND doc_tipo like '%$titulo%'"; 
      }
      if(strlen($entidad)>0) { 
         $sql.= " AND doc_entidad like '%$entidad%'"; 
      }
      if(strlen($vigencia)>0) { 
         $sql.= " AND doc_vigencia  = '$vigencia'"; 
      }
      if(strlen($situacion)>0) { 
         $sql.= " AND doc_situacion IN($situacion)"; 
      }
      $sql.= " ORDER BY doc_codigo ASC; ";
	  // echo $sql;
      $result = $this->exec_query($sql);
	   return $result;
	}
   	
   function insert_documento($codigo, $usuario_crea, $titulo, $tipo, $entidad, $vigencia){
      $titulo = trim($titulo);
      $tipo = trim($tipo);
      $entidad = trim($entidad);
      $sql = "INSERT INTO req_documento";
      $sql.= " VALUES ($codigo, '$usuario_crea', CURTIME(), '$titulo', '$tipo', '$entidad', '$vigencia','$usuario_crea', CURDATE(), 1);";
     // echo $sql;
      return $sql;
   }
	function modifica_documento($codigo,$titulo, $tipo, $entidad, $vigencia, $usuario_modifica){
      $titulo = trim($titulo);
      $tipo = trim($tipo);
      $entidad = trim($entidad);
		$sql = "UPDATE req_documento SET ";
		$sql.= "doc_titulo = '$titulo', "; 		
		$sql.= "doc_tipo = '$tipo', "; 		
		$sql.= "doc_entidad = '$entidad', "; 
      $sql.= "doc_vigencia = '$vigencia', "; 
      $sql.= "doc_usuario_modifica = '$usuario_modifica', "; 
      $sql.= "doc_fecha_modificacion = CURTIME()";
		$sql.= " WHERE doc_codigo = $codigo"; 	
		//echo $sql;
		return $sql;
	}
	function cambia_situacion_doc($codigo,$usuario_modifica, $sit){
		
		$sql = "UPDATE req_documento SET ";
      $sql.= "doc_usuario_modifica = '$usuario_modifica', "; 
      $sql.= "doc_fecha_modificacion = CURTIME(), ";
		$sql.= "doc_situacion = $sit"; 
				
		$sql.= " WHERE doc_codigo = $codigo"; 	
		
		return $sql;
	}
	function max_documento(){
      $sql = "SELECT max(doc_codigo) as max ";
      $sql.= " FROM req_documento";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
       
	}
}

