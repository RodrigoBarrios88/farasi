<?php
require_once ("ClsConex.php");

class ClsQuejas extends ClsConex{
/* Situacion 1 = ACTIVO, 2 = INACTIVO */

   function get_quejas($codigo = '', $proceso = '', $sistema = '', $descripcion = '', $cliente = '', $tipo = '', $situacion = '1') {
		$descripcion = trim($descripcion);
      $cliente = trim($cliente);
		$tipo = trim($tipo);

      $sql = "SELECT * ";
      $sql.= " FROM mej_queja, pro_ficha, pro_sistema, seg_usuarios";
      $sql.= " WHERE que_proceso = fic_codigo";
      $sql.= " AND que_sistema = sis_codigo";
      $sql.= " AND que_usuario_registra = usu_id";
      if(strlen($codigo)>0) { 
         $sql.= " AND que_codigo = $codigo "; 
      }
      if(strlen($proceso)>0) { 
         $sql.= " AND que_proceso IN($proceso)"; 
      }
      if(strlen($sistema)>0) {   
         $sql.= " AND que_sistema = $sistema"; 
      }
      if(strlen($descripcion)>0) { 
         $sql.= " AND que_descripcion like $descripcion"; 
      }
      if(strlen($cliente)>0) { 
         $sql.= " AND que_cliente like '%$cliente%'"; 
      }
      if(strlen($tipo)>0) {   
         $sql.= " AND que_tipo = $tipo"; 
      }
      if(strlen($situacion)>0) { 
         $sql.= " AND que_situacion = '$situacion'"; 
      }
      $sql.= " ORDER BY que_codigo ASC; ";
	  $result = $this->exec_query($sql);
   //   echo($sql);
      //var_dump($result);  
      //die();
	  return $result;

	}
   	
   function insert_quejas($codigo,$proceso,$sistema,$descripcion,$cliente, $tipo){
      $descripcion = trim($descripcion);
      $cliente = trim($cliente);
      $tipo = trim($tipo);
      $usuario = $_SESSION["codigo"];
      $sql = "INSERT INTO mej_queja";
      $sql.= " VALUES ($codigo,$proceso,$sistema,'$descripcion', $usuario , CURDATE(),'$cliente', '$tipo', 1);";
      //echo $sql;
      return $sql;
   }
	function modifica_quejas($codigo,$proceso,$sistema,$descripcion,$cliente, $tipo){
      $descripcion = trim($descripcion);
      $cliente = trim($cliente);
      $tipo = trim($tipo);
		$sql = "UPDATE mej_queja SET ";
		$sql.= "que_proceso = '$proceso', "; 		
		$sql.= "que_sistema = '$sistema', "; 		
		$sql.= "que_descripcion = '$descripcion', ";
		$sql.= "que_cliente = '$cliente', "; 
      $sql.= "que_tipo = '$tipo'"; 

		$sql.= " WHERE que_codigo = $codigo"; 	
		//echo $sql;
		return $sql;
	}
	function cambia_situacion_quejas($codigo,$sit){
		
		$sql = "UPDATE mej_queja SET ";
		$sql.= "que_situacion = $sit"; 
				
		$sql.= " WHERE que_codigo = $codigo"; 	
		
		return $sql;
	}
	function max_quejas(){
      $sql = "SELECT max(que_codigo) as max ";
      $sql.= " FROM mej_queja";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}
}
