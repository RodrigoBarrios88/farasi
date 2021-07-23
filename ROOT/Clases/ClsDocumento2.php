<?php
require_once ("ClsConex.php");

class ClsDocumento extends ClsConex{
/* Situacion 1 = ACTIVO, 2 = INACTIVO */

   function get_documento($codigo, $titulo = '', $tipo = '', $entidad= '', $sistema = '', $fecha_ingreso = '',$vigencia = '', $situacion) {
		$titulo = trim($titulo);
      $entidad = trim($entidad);
		$tipo = trim($tipo);

      $sql = "SELECT * ";
      $sql.= " FROM req_documento, pro_sistema";
      $sql.= " WHERE doc_sistema = sis_codigo";
      $sql.= " AND doc_situacion > 0";
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
      if(strlen($sistema)>0) { 
         $sql.= " AND doc_sistema = $sistema "; 
      }  if(strlen($sistema)>0) { 
         $sql.= " AND doc_sistema = $sistema "; 
      }
      if(strlen($vigencia)>0) { 
         $sql.= " AND doc_vigencia  = '$vigencia'"; 
      }
      if(strlen($$situacion)>0) { 
         $sql.= " AND doc_situacion  = '$situacion'"; 
      }
      $sql.= " ORDER BY doc_codigo ASC; ";
	  $result = $this->exec_query($sql);
      // echo($sql);
      //var_dump($result);  
      //die();
	  return $result;

	}
   	
   function insert_documento($codigo, $titulo, $tipo, $entidad, $sistema, $vigencia){
      $codigo = trim($codigo);
      $usuario_crea = trim($_SESSION["codigo"]);
      $titulo = trim($titulo);
      $tipo = trim($tipo);
      $entidad = trim($entidad);
      $sistema = trim($sistema);
      $vigencia = trim($vigencia);
      $usuario_modifica = $usuario_crea;
      $sql = "INSERT INTO req_documento";
      $sql .= "(doc_codigo,doc_usuario_crea,doc_titulo, doc_tipo, doc_entidad, doc_sistema, doc_vigencia, doc_usuario_modifica)";
      $sql .= " VALUES ($codigo, $usuario_crea, '$titulo', '$tipo','$entidad', $sistema, $vigencia, $usuario_modifica, 1);";
     // echo $sql;
      return $sql;
   }

	function modifica_documento($codigo,$titulo, $tipo, $entidad, $sistema, $vigencia, $situacion){
      $titulo = trim($titulo);
      $tipo = trim($tipo);
      $entidad = trim($entidad);
      $nueva_situacion = $situacion+1;
		$sql = "UPDATE req_documento SET ";
		$sql.= "doc_titulo = '$titulo', "; 		
		$sql.= "doc_tipo = '$tipo', "; 		
		$sql.= "doc_entidad = '$entidad', "; 
      $sql.= "doc_sistema = $sistema, "; 
      $sql.= "doc_vigencia = '$vigencia'"; 
      $sql.= "doc_situacion = $nueva_situacion";
		$sql.= " WHERE doc_codigo = $codigo"; 	
		//echo $sql;
		return $sql;
	}
	function cambia_situacion_doc($codigo,$sit){
		
		$sql = "UPDATE req_documento SET ";
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
/*t as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
       
	}*/
//}
