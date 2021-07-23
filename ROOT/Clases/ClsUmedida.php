<?php
require_once ("ClsConex.php");

class ClsUmedida extends ClsConex{
   
    function get_unidad($clase = '',$codigo = ''){
	    $sql ="SELECT * ";
	    $sql.=" FROM mast_unidad_medida";
	    $sql.=" WHERE umed_situacion = 1";
	    if(strlen($clase)>0) { 
			$sql.=" AND umed_clase = '$clase'";
	    }
		if(strlen($codigo)>0) { 
			$sql.=" AND umed_codigo = '$codigo'";
	    }
		$sql.=" ORDER BY umed_desc_lg ASC, umed_codigo ASC";
		//echo $sql;
		$result = $this->exec_query($sql);
		
		return $result;
	}

	function modifica_umedida($codigo, $desc, $abrev, $clase){
		$codigo = trim($codigo);
		$desc = trim($desc);
		$abrev = trim($abrev);
		$clase = trim($clase);
      
		$sql = "UPDATE mast_unidad_medida  SET "; 
		$sql.= "umed_desc_lg = '$desc', "; 
		$sql.= "umed_desc_ct = '$abrev', "; 
		$sql.= "umed_clase = '$clase'"; 
		$sql.= " WHERE umed_codigo = $codigo"; 	
		//echo $sql;
		return $sql;
	}

	function insert_umedida($codigo, $desc, $abrev, $clase){
		$codigo = trim($codigo);
		$desc = trim($desc);
		$abrev = trim($abrev);
		$clase = trim($clase);
		
		$sql = "INSERT INTO mast_unidad_medida ";
		$sql.= " VALUES ($codigo, '$desc', '$abrev', '$clase', 1);";
		//echo $sql;
		return $sql;
	 }

	 function cambia_situacion_umedida($codigo,$sit){
		
		$sql = "UPDATE mast_unidad_medida  SET umed_situacion = $sit"; 
		$sql.= " WHERE umed_codigo = $codigo"; 	
		//echo $sql;
		return $sql;
	}

	function max_umedida(){
		$sql = "SELECT max(umed_codigo) as max ";
		$sql.= " FROM mast_unidad_medida ";
		  $result = $this->exec_query($sql);
		  foreach($result as $row){
			  $max = $row["max"];
		  }
		  //echo $sql;
		  return $max;
	  }
}
