<?php
require_once ("ClsConex.php");

class ClsBiblioteca extends ClsConex{
/* Situacion 1 = ACTIVO, 0 = INACTIVO */
/////////////////////////////  CATEGORIA  //////////////////////////////////////   
   function get_categoria($codigo, $nombre = '', $sit = '') {
		$nombre = trim($nombre);
		
      $sql= "SELECT * ";
      $sql.= " FROM bib_categoria";
      $sql.= " WHERE 1 = 1";
      if(strlen($codigo)>0) { 
         $sql.= " AND cat_codigo = $codigo"; 
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
	function count_categoria($codigo, $nombre = '', $sit = '') {
		$nombre = trim($nombre);
		
      $sql= "SELECT COUNT(*) as total";
      $sql.= " FROM bib_categoria";
      $sql.= " WHERE 1 = 1";
      if(strlen($codigo)>0) { 
         $sql.= " AND cat_codigo = $codigo"; 
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
	
   function insert_categoria($codigo,$nombre,$color){
      $nombre = trim($nombre);
      $color = trim($color);
      
      $sql = "INSERT INTO bib_categoria";
      $sql.= " VALUES ($codigo,'$nombre','$color',1);";
      //echo $sql;
      return $sql;
   }
	function modifica_categoria($codigo,$nombre,$color){
		$nombre = trim($nombre);
		$color = trim($color);
      
		$sql = "UPDATE bib_categoria SET "; 
		$sql.= "cat_nombre = '$nombre', "; 
		$sql.= "cat_color = '$color'"; 
		$sql.= " WHERE cat_codigo = $codigo"; 	
		//echo $sql;
		return $sql;
	}
	function cambia_situacion_categoria($codigo,$sit){
		
		$sql = "UPDATE bib_categoria SET cat_situacion = $sit"; 
		$sql.= " WHERE cat_codigo = $codigo"; 	
		
		return $sql;
	}
	function max_categoria(){
      $sql = "SELECT max(cat_codigo) as max ";
      $sql.= " FROM bib_categoria";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}



/////////////////////////////  DOCUMENTOS  //////////////////////////////////////
  
    function get_biblioteca($codigo, $categoria = '', $situacion = '') {
		
      $sql= "SELECT * ";
      $sql.= " FROM bib_blioteca, bib_categoria ";
      $sql.= " WHERE bib_categoria = cat_codigo";
      if(strlen($codigo)>0) { 
         $sql.= " AND bib_codigo = $codigo"; 
      }
      if(strlen($categoria)>0) { 
         $sql.= " AND bib_categoria IN($categoria)"; 
      }
      if(strlen($situacion)>0) { 
         $sql.= " AND bib_situacion IN($situacion)"; 
      }
      $sql.= " ORDER BY bib_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function count_biblioteca($codigo,$categoria = '', $situacion = '') {
		
      $sql= "SELECT COUNT(*) as total";
      $sql.= " FROM bib_blioteca, bib_categoria";
      $sql.= " WHERE bib_categoria = cat_codigo";
      if(strlen($codigo)>0) { 
         $sql.= " AND bib_codigo = $codigo"; 
      }
      if(strlen($categoria)>0) { 
         $sql.= " AND bib_categoria IN($categoria)"; 
      }
      if(strlen($situacion)>0) { 
         $sql.= " AND bib_situacion IN($situacion)"; 
      }
		//echo $sql;
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$total = $row['total'];
		}
		return $total;
	}
	function insert_biblioteca($codigo,$categoria,$codint,$titulo,$descripcion,$fecvence,$usuario){
		$titulo = trim($titulo);
		$fecvence = $this->regresa_fechaHora($fecvence);
      $fechor = date("Y-m-d H:i:s");
      
		$sql = "INSERT INTO bib_blioteca (bib_codigo, bib_categoria, bib_codigo_interno, bib_version, bib_titulo, bib_descripcion, bib_usuario, bib_fecha_registro, bib_fecha_update, bib_fecha_vence, bib_situacion)";
		$sql.= " VALUES ($codigo,$categoria,'$codint','-','$titulo','$descripcion','$usuario','$fechor','$fechor','$fecvence',1);";
		//echo $sql;
		return $sql;
	}
	function modifica_biblioteca($codigo,$categoria,$codint,$titulo,$descripcion,$fecvence,$usuario){
		$titulo = trim($titulo);
		$fecvence = $this->regresa_fechaHora($fecvence);
      $fechor = date("Y-m-d H:i:s");
      
      $sql = "UPDATE bib_blioteca SET ";
		$sql.= "bib_categoria = '$categoria',"; 
		$sql.= "bib_codigo_interno = '$codint',"; 
		$sql.= "bib_titulo = '$titulo',"; 
		$sql.= "bib_descripcion  = '$descripcion',"; 		
		$sql.= "bib_fecha_vence = '$fecvence',";
		$sql.= "bib_fecha_update = '$fechor',";
		$sql.= "bib_usuario = '$usuario'";
		
		$sql.= " WHERE bib_codigo = $codigo; "; 	
		//echo $sql;
		return $sql;
	}
   
   
   function actualiza_version($codigo,$version,$fecvence){
      $version = trim($version);
      $fecvence = $this->regresa_fechaHora($fecvence);
		$fechor = date("Y-m-d H:i:s");
		
		$sql = "UPDATE bib_blioteca SET";
		$sql.= " bib_version = '$version',";
		$sql.= " bib_fecha_vence = '$fecvence',";
		$sql.= " bib_fecha_update = '$fechor'";
		
		$sql.= " WHERE bib_codigo = $codigo; "; 	
		//echo $sql;
		return $sql;
	}
   
   
   function actualiza_documento($codigo,$documento){
		$usuario = $_SESSION["codigo"];
      $fechor = date("Y-m-d H:i:s");
		
		$sql = "UPDATE bib_blioteca SET";
		$sql.= " bib_documento = '$documento',"; 
		$sql.= " bib_fecha_registro = '$fechor',";
		$sql.= " bib_usuario = '$usuario'";
		
		$sql.= " WHERE bib_codigo = $codigo; "; 	
		//echo $sql;
		return $sql;
	}
   	function cambia_situacion_biblioteca($codigo,$situacion){
		
		$sql = "UPDATE bib_blioteca SET ";
		$sql.= "bib_situacion = $situacion"; 
				
		$sql.= " WHERE bib_codigo = $codigo; "; 	
		
		return $sql;
	}
	function max_biblioteca(){
      $sql = "SELECT max(bib_codigo) as max ";
      $sql.= " FROM bib_blioteca";
      $result = $this->exec_query($sql);
      foreach($result as $row){
         $max = $row["max"];
      }
		//echo $sql;
		return $max;
	}
   
   /////////////////////////////  BIBLIOTECA - VERSION  //////////////////////////////////////    
	function get_version($codigo,$biblioteca) {
        
      $sql= "SELECT *, ";
      $sql.= " (SELECT usu_nombre FROM seg_usuarios WHERE usu_id = ver_usuario) as usuario_registro";
      $sql.= " FROM bib_version_documento,bib_blioteca";
      $sql.= " WHERE bib_codigo = ver_biblioteca";
      if(strlen($codigo)>0) { 
         $sql.= " AND ver_codigo = $codigo"; 
      }
      if(strlen($biblioteca)>0) { 
         $sql.= " AND ver_biblioteca = $biblioteca"; 
      }
      $sql.= " ORDER BY ver_codigo ASC";
      
      $result = $this->exec_query($sql);
      //echo $sql;
      return $result;   }
      function insert_version($codigo,$biblioteca,$version,$descripcion,$observaciones){
      $descripcion = trim($descripcion);
      $observaciones = trim($observaciones);
      $fsis = date("Y-m-d H:i:s");
      $usuario = $_SESSION["codigo"];
      
      $sql = "INSERT INTO bib_version_documento (ver_codigo, ver_biblioteca, ver_version, ver_descripcion, ver_observaciones, ver_usuario, ver_fecha_registro, ver_situacion)";
      $sql.= " VALUES ($codigo,$biblioteca,'$version','$descripcion','$observaciones',$usuario,'$fsis',1)";
      $sql.= " ON DUPLICATE KEY UPDATE ver_version = '$version',ver_observaciones = '$observaciones',ver_usuario = '$usuario',ver_fecha_registro = '$fsis'; ";
      //echo $sql;
      return $sql;
   }
    
   function cambia_sit_version($codigo,$biblioteca,$situacion,$observaciones){
      $fsis = date("Y-m-d H:i:s");
      $usuario = $_SESSION["codigo"];
      
      $sql = "UPDATE bib_version_documento SET ver_situacion = $situacion,";
      $sql.= " ver_observaciones = '$observaciones',";
      if($situacion == 3){
         $sql.= " ver_fecha_aprobacion = '$fsis'";
      }
      $sql.= " ver_usuario = '$usuario'";
      $sql.= " WHERE ver_codigo = $codigo "; 	
      $sql.= " AND ver_biblioteca = $biblioteca; "; 	
      
      return $sql;   }
     
   function max_version($biblioteca){
      $sql = "SELECT max(ver_codigo) as max ";
      $sql.= " FROM bib_version_documento";
      $sql.= " WHERE ver_biblioteca = $biblioteca;"; 	
      $result = $this->exec_query($sql);
      foreach($result as $row){
         $max = $row["max"];
      }
		//echo $sql;
		return $max;
	}

} 
?>