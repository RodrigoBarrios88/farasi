<?php
require_once ("ClsConex.php");

class ClsCuestionarioPPM extends ClsConex{
/* Situacion 1 = ACTIVO, 0 = INACTIVO */

/////////////////////////////  CUESTIONARIO DE CHEQUEO  //////////////////////////////////////
  
    function get_cuestionario($codigo, $categoria = '',$situacion = '') {
		
	    $sql= "SELECT * ";
		$sql.= " FROM ppm_cuestionario, ppm_categoria";
        $sql.= " WHERE cue_categoria = cat_codigo";
        if(strlen($codigo)>0) { 
			$sql.= " AND cue_codigo = $codigo"; 
		}
        if(strlen($categoria)>0) { 
            $sql.= " AND cue_categoria IN($categoria)"; 
        }
        if(strlen($situacion)>0) { 
            $sql.= " AND cue_situacion IN($situacion)"; 
        }
        $sql.= " ORDER BY cue_categoria ASC, cue_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function count_cuestionario($codigo,$categoria = '',$situacion = '') {
		$titulo = trim($titulo);
		$target = trim($target);
		$fecha = date("Y-m-d H:i:s");
		
	    $sql= "SELECT COUNT(*) as total";
		$sql.= " FROM ppm_cuestionario, ppm_categoria";
        $sql.= " WHERE cue_categoria = cat_codigo";
        if(strlen($codigo)>0) { 
			$sql.= " AND cue_codigo = $codigo"; 
		}
        if(strlen($categoria)>0) { 
            $sql.= " AND cue_categoria IN($categoria)"; 
        }
        if(strlen($situacion)>0) { 
            $sql.= " AND cue_situacion IN($situacion)"; 
        }
		//echo $sql;
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$total = $row['total'];
		}
		return $total;
	}
	function insert_cuestionario($codigo,$categoria,$nombre){
		$nombre = trim($nombre);
		
		$sql = "INSERT INTO ppm_cuestionario";
		$sql.= " VALUES ($codigo,$categoria,'$nombre',1);";
		//echo $sql;
		return $sql;
	}
	function modifica_cuestionario($codigo,$categoria,$nombre){
		$nombre = trim($nombre);
		
        $sql = "UPDATE ppm_cuestionario SET ";
		$sql.= "cue_categoria = '$categoria',"; 
		$sql.= "cue_nombre = '$nombre'"; 
		
		$sql.= " WHERE cue_codigo = $codigo; "; 	
		//echo $sql;
		return $sql;
	}   
   function cambia_sit_cuestionario($codigo,$situacion){
		
		$sql = "UPDATE ppm_cuestionario SET ";
		$sql.= "cue_situacion = $situacion"; 
				
		$sql.= " WHERE cue_codigo = $codigo; "; 	
		
		return $sql;
	}
	function max_cuestionario(){
	    $sql = "SELECT max(cue_codigo) as max ";
		$sql.= " FROM ppm_cuestionario";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}
	
/////////////////////////////  PREGUNTAS  //////////////////////////////////////
  
    function get_pregunta($codigo,$cuestionario,$categoria = '',$situacion = '') {
		
	    $sql= "SELECT * ";
		$sql.= " FROM ppm_cuestionario, ppm_preguntas, ppm_categoria";
        $sql.= " WHERE pre_cuestionario = cue_codigo";
        $sql.= " AND cue_categoria = cat_codigo";
        
		if(strlen($codigo)>0) { 
            $sql.= " AND pre_codigo = $codigo"; 
		}
		if(strlen($cuestionario)>0) { 
			$sql.= " AND pre_cuestionario = $cuestionario"; 
		}
		if(strlen($categoria)>0) { 
            $sql.= " AND cue_categoria IN($categoria)"; 
        }
        if(strlen($situacion)>0) { 
            $sql.= " AND pre_situacion = $situacion"; 
        }
        $sql.= " ORDER BY cue_categoria ASC, cue_codigo ASC, pre_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
		function count_pregunta($codigo,$cuestionario,$categoria = '',$situacion = '') {
		$sql= "SELECT COUNT(*) as total";
        $sql.= " FROM ppm_cuestionario, ppm_preguntas, ppm_categoria";
        $sql.= " WHERE pre_cuestionario = cue_codigo";
        $sql.= " AND cue_categoria = cat_codigo";
       
		if(strlen($codigo)>0) { 
            $sql.= " AND pre_codigo = $codigo"; 
		}
		if(strlen($cuestionario)>0) { 
			$sql.= " AND pre_cuestionario = $cuestionario"; 
		}
		if(strlen($categoria)>0) { 
            $sql.= " AND cue_categoria IN($categoria)"; 
        }
        if(strlen($situacion)>0) { 
            $sql.= " AND pre_situacion = $situacion"; 
        }
		//echo $sql;
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$total = $row['total'];
		}
		return $total;
	}
	function insert_pregunta($codigo,$cuestionario,$desc){
        $desc = trim($desc);
		
		$sql = "INSERT INTO ppm_preguntas";
		$sql.= " VALUES ($codigo,$cuestionario,'$desc',1);";
		//echo $sql;
		return $sql;
	}
	function modifica_pregunta($codigo,$cuestionario,$desc){
		$desc = trim($desc);
		
		$sql = "UPDATE ppm_preguntas SET ";
		$sql.= "pre_cuestionario = '$cuestionario',"; 		
		$sql.= "pre_pregunta = '$desc'"; 
		
		$sql.= " WHERE pre_codigo = $codigo;";
        //echo $sql;
		return $sql;
	}    
    function cambia_sit_pregunta($codigo,$situacion){
		
		$sql = "UPDATE ppm_preguntas SET ";
		$sql.= "pre_situacion = $situacion"; 
				
		$sql.= " WHERE pre_codigo = $codigo; "; 	
		
		return $sql;
	}
    function max_pregunta(){
	    $sql = "SELECT max(pre_codigo) as max ";
		$sql.= " FROM ppm_preguntas";
        $result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}}?>
