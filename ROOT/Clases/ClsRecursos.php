<?php
require_once ("ClsConex.php");

class ClsRecursos extends ClsConex{
/////////////////////////////////// TIPO DE RECURSOS  //////////////////////////////////////////
   
    function get_tipo_recursos($codigo,$nombre = '',$sit = '',$habiles = '') {
		$nombre = trim($nombre);
		
        $sql= "SELECT * ";
		$sql.= " FROM pro_tipo_recursos";
		$sql.= " WHERE 1 = 1";
		if(strlen($codigo)>0) { 
			  $sql.= " AND tip_codigo = $codigo"; 
		}
		if(strlen($nombre)>0) { 
			  $sql.= " AND tip_nombre like '%$nombre%'"; 
		}
		if(strlen($sit)>0) { 
			  $sql.= " AND tip_situacion = $sit"; 
		}
        if(strlen($habiles)>0) { 
			  $sql.= " AND tip_codigo != 0"; 
		}
		$sql.= " ORDER BY tip_codigo ASC, tip_situacion DESC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;

	}
	function count_tipo_recursos($codigo,$nombre = '',$sit = '',$habiles = '') {
		$nombre = trim($nombre);
		
        $sql= "SELECT COUNT(*) as total";
		$sql.= " FROM pro_tipo_recursos";
		$sql.= " WHERE 1 = 1";
		if(strlen($codigo)>0) { 
			  $sql.= " AND tip_codigo = $codigo"; 
		}
		if(strlen($nombre)>0) { 
			  $sql.= " AND tip_nombre like '%$nombre%'"; 
		}
		if(strlen($sit)>0) { 
			  $sql.= " AND tip_situacion = $sit"; 
		}
        if(strlen($habiles)>0) { 
			  $sql.= " AND tip_codigo != 0"; 
		}
		//echo $sql;
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$total = $row['total'];
		}
		return $total;
	}
		
	function insert_tipo_recursos($codigo,$nombre){
		$nombre = trim($nombre);
		
		$sql = "INSERT INTO pro_tipo_recursos";
		$sql.= " VALUES ($codigo,'$nombre',1); ";
		//echo $sql;
		return $sql;
	}
	function modifica_tipo_recursos($codigo,$nombre){
		$nombre = trim($nombre);
		
		$sql = "UPDATE pro_tipo_recursos SET ";
		$sql.= "tip_nombre = '$nombre'"; 
		
		$sql.= " WHERE tip_codigo = $codigo; "; 	
		//echo $sql;
		return $sql;
	}
	function cambia_situacion_tipo_recursos($codigo,$sit){
		
		$sql = "UPDATE pro_tipo_recursos SET ";
		$sql.= "tip_situacion = $sit"; 
				
		$sql.= " WHERE tip_codigo = $codigo"; 	
		
		return $sql;
	}
	function max_tipo_recursos() {
		
        $sql = "SELECT max(tip_codigo) as max ";
		$sql.= " FROM pro_tipo_recursos";
		$result = $this->exec_query($sql);
		if(is_array($result)){
			foreach($result as $row){
				$max = $row["max"];
			}
		}
		//echo $sql;
		return $max;
	}		
	/////////////////////////////  RECURSOS  //////////////////////////////////////
    function get_recurso($codigo,$proceso,$tipo = '') {        $sql= "SELECT * ";
        $sql.= " FROM pro_recursos, pro_tipo_recursos";
        $sql.= " WHERE rec_tipo_recurso = tip_codigo";
        $sql.= " AND rec_situacion = 1";
        if(strlen($codigo)>0) { 
            $sql.= " AND rec_codigo = $codigo"; 
        }
        if(strlen($proceso)>0) { 
            $sql.= " AND rec_proceso = $proceso"; 
        }
        if(strlen($tipo)>0) { 
            $sql.= " AND rec_tipo_recurso = $tipo"; 
        }
        $sql.= " ORDER BY rec_proceso ASC, rec_tipo_recurso ASC, rec_codigo ASC";
        
        $result = $this->exec_query($sql);
        //echo $sql;
        return $result;    }    
	function  insert_recurso($codigo,$proceso,$tipo,$descripcion,$usuario = ''){
        $descripcion = trim($descripcion);
        $tipo = trim($tipo);
        $usuario = ($usuario == '')?$_SESSION["codigo"]:$usuario;
        $fsis = date("Y-m-d H:i:s");
        
        $sql = "INSERT INTO pro_recursos";
        $sql.= " VALUES ($codigo,$proceso,$tipo,'$descripcion','$usuario','$fsis',1)";
        $sql.= " ON DUPLICATE KEY UPDATE rec_tipo_recurso = '$tipo', rec_descripcion = '$descripcion', rec_usuario = '$usuario', rec_fecha_registro = '$fsis'; ";
        //echo $sql;
        return $sql;
    }    
	function  cambia_situacion_recurso($codigo,$proceso, $situacion){        $sql = "UPDATE pro_recursos SET rec_situacion = $situacion"; 
        $sql.= " WHERE rec_codigo = $codigo "; 	
        $sql.= " AND rec_proceso = $proceso; "; 	
        
        return $sql;    }    
	function  max_recurso($proceso){
        $sql = "SELECT max(rec_codigo) as max ";
        $sql.= " FROM pro_recursos";
        $sql.= " WHERE rec_proceso = $proceso;"; 	
        $result = $this->exec_query($sql);
        foreach($result as $row){
            $max = $row["max"];
        }
        //echo $sql;
        return $max;
    }
}
