<?php
require_once ("ClsConex.php");

class ClsEscalon extends ClsConex{
/* ESCALON */
//////////////////////////////////////////////////////////////////
   
    function get_escalon($codigo,$categoria = '',$posicion = '',$nombre = ''){
		$nombre = trim($nombre);
		$posicion = trim($posicion);
		
        $sql= "SELECT * ";
		$sql.= " FROM hd_escalon";
		$sql.= " WHERE esc_situacion = 1";
		$sql.= " AND esc_codigo > 0";
		if(strlen($codigo)>0){ 
			  $sql.= " AND esc_codigo = $codigo"; 
		}
		if(strlen($categoria)>0){ 
			  $sql.= " AND esc_categoria = $categoria"; 
		}
		if(strlen($posicion)>0){ 
			  $sql.= " AND esc_posicion = $posicion"; 
		}
        if(strlen($nombre)>0){ 
			  $sql.= " AND esc_nombre like '%$nombre%'"; 
		}
		$sql.= " ORDER BY esc_posicion ASC, esc_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function count_escalon($codigo,$categoria = '',$posicion = '',$nombre = ''){
		$nombre = trim($nombre);
		$posicion = trim($posicion);
		
        $sql= "SELECT COUNT(*) as total";
		$sql.= " FROM hd_escalon";
		$sql.= " WHERE esc_situacion = 1";
		$sql.= " AND esc_codigo > 0";
		if(strlen($codigo)>0){ 
			  $sql.= " AND esc_codigo = $codigo"; 
		}
		if(strlen($categoria)>0){ 
			  $sql.= " AND esc_categoria = $categoria"; 
		}
		if(strlen($posicion)>0){ 
			  $sql.= " AND esc_posicion = $posicion"; 
		}
		if(strlen($nombre)>0){ 
			  $sql.= " AND esc_nombre like '%$nombre%'"; 
		}
		//echo $sql;
		$result = $this->exec_query($sql);
		if(is_array($result)){
			foreach($result as $row){
				$total = $row['total'];
			}
		}
		return $total;
	}
	function insert_escalon($codigo,$categoria,$posicion,$nombre){
		$posicion = trim($posicion);
		
		$sql = "INSERT INTO hd_escalon VALUES ('$codigo','$categoria','$posicion','$nombre',1);";
		//echo $sql;
		return $sql;
	}
	function modifica_escalon($codigo,$posicion,$nombre){
		$posicion = trim($posicion);
		
		$sql = "UPDATE hd_escalon SET esc_nombre = '$nombre', esc_posicion = '$posicion'"; 
		$sql.= " WHERE esc_codigo = $codigo;"; 	
		//echo $sql;
		return $sql;
	}
	function cambia_sit_escalon($codigo,$situacion){
		
		$sql = "UPDATE hd_escalon SET esc_situacion = $situacion"; 
		$sql.= " WHERE esc_codigo = $codigo;"; 	
		
		return $sql;
	}
	function max_escalon() {
		
        $sql = "SELECT max(esc_codigo) as max ";
		$sql.= " FROM hd_escalon";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}
	
/* DETALLE DE ESCALON*/
//////////////////////////////////////////////////////////////////
   
    function get_detalle_escalon($codigo,$escalon,$categoria = '',$posicion = '',$nombre = ''){
		
        $sql= "SELECT * ";
		$sql.= " FROM hd_escalon,hd_escalon_notificacion";
		$sql.= " WHERE not_escalon = esc_codigo";
		$sql.= " AND esc_situacion = 1";
		$sql.= " AND not_situacion = 1";
		if(strlen($codigo)>0){ 
			  $sql.= " AND not_codigo = $codigo"; 
		}
		if(strlen($escalon)>0) { 
			  $sql.= " AND not_escalon = $escalon"; 
		}
        if(strlen($categoria)>0){ 
			  $sql.= " AND esc_categoria = $categoria"; 
		}
		if(strlen($posicion)>0){ 
			  $sql.= " AND esc_posicion = $posicion"; 
		}
        if(strlen($nombre)>0){ 
			  $sql.= " AND esc_nombre like '%$nombre%'"; 
		}
		$sql.= " ORDER BY not_codigo ASC, not_escalon ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql."<br><br>";
		return $result;
	}
	function count_detalle_escalon($codigo,$escalon,$categoria = '',$posicion = '',$nombre = ''){
		
        $sql= "SELECT COUNT(*) as total";
		$sql.= " FROM hd_escalon,hd_escalon_notificacion";
		$sql.= " WHERE not_escalon = esc_codigo";
		$sql.= " AND esc_situacion = 1";
		$sql.= " AND not_situacion = 1";
		if(strlen($codigo)>0){ 
			  $sql.= " AND not_codigo = $codigo"; 
		}
		if(strlen($escalon)>0) { 
			  $sql.= " AND not_escalon = $escalon"; 
		}
        if(strlen($categoria)>0){ 
			  $sql.= " AND esc_categoria = $categoria"; 
		}
		if(strlen($posicion)>0){ 
			  $sql.= " AND esc_posicion = $posicion"; 
		}
        if(strlen($nombre)>0){ 
			  $sql.= " AND esc_nombre like '%$nombre%'"; 
		}
		
		//echo $sql;
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$total = $row['total'];
		}
		return $total;
	}
	function insert_detalle_escalon($codigo,$escalon,$nombre,$mail){
		
		$sql = "INSERT INTO hd_escalon_notificacion VALUES ('$codigo','$escalon','$nombre','$mail',1);";
		//echo $sql;
		return $sql;
	}
	function delet_detalle_escalon($codigo,$escalon){
		$sql = "DELETE FROM hd_escalon_notificacion"; 
		$sql.= " WHERE not_escalon = $escalon";
		$sql.= " AND not_codigo = $codigo;"; 
		//echo $sql;
		return $sql;
	}
	function modifica_detalle_escalon($codigo,$escalon,$nombre,$mail){
		
		$sql = "UPDATE hd_escalon_notificacion SET not_nombre = '$nombre', not_mail = '$mail'"; 
		$sql.= " WHERE not_escalon = $escalon";
		$sql.= " AND not_codigo = $codigo;"; 
		//echo $sql;
		return $sql;
	}
	function cambia_sit_detalle_escalon($codigo,$escalon,$situacion){
		
		$sql = "UPDATE hd_escalon_notificacion SET not_situacion = $situacion"; 
		$sql.= " WHERE not_escalon = $escalon";
		$sql.= " AND not_codigo = $codigo;"; 
		
		return $sql;
	}    
    function max_detalle_escalon($escalon) {
		
        $sql = "SELECT max(not_codigo) as max ";
		$sql.= " FROM hd_escalon_notificacion";
		$sql.= " WHERE not_escalon = $escalon";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}

}	
?>
