<?php
require_once ("ClsConex.php");

class ClsMoneda extends ClsConex{
   
    function get_moneda($codigo = '') {
        $sql= "SELECT * ";
		$sql.= " FROM fin_moneda";
		$sql.= " WHERE mon_situacion = 1";
		if(strlen($codigo)>0) { 
			  $sql.= " AND mon_codigo = $codigo"; 
		}
		$sql.= " ORDER BY mon_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function get_tipo_cambio($codigo = '') {
				
        $sql= "SELECT mon_cambio as tcambio ";
		$sql.= " FROM fin_moneda";
		$sql.= " WHERE mon_situacion = 1";
		$sql.= " AND mon_codigo = $codigo"; 
		$result = $this->exec_query($sql);
		if(is_array($result)){
			foreach($result as $row){
				$tcambio = $row["tcambio"];
			}
		}else{
			$tcambio = 0;
		}
		
		return $tcambio;
	}
		
	function insert_moneda($codigo,$descripcion,$simbolo,$pais,$cambio,$compra,$venta){
		$descripcion = trim($descripcion);
		$simbolo = trim($simbolo);
		$pais = trim($pais);
				
		$sql = "INSERT INTO fin_moneda ";
		$sql.= " VALUES ($codigo,'$descripcion','$simbolo','$pais','$cambio','$compra','$venta',1);";
		//echo $sql;
		return $sql;
	}
	function update_moneda($codigo,$descripcion,$simbolo,$pais){
		$descripcion = trim($descripcion);
		$pais = trim($pais);
		
		$sql = "UPDATE fin_moneda SET "; 
		$sql.= " mon_descripcion = '$descripcion',"; 	
		$sql.= " mon_simbolo = '$simbolo',"; 	
		$sql.= " mon_pais = '$pais'"; 	
		
		$sql.= " WHERE mon_codigo = $codigo;"; 	
		
		return $sql;
	}
	function update_cambio_moneda($codigo,$cambio,$compra,$venta){
		
		$sql = "UPDATE fin_moneda SET "; 
		$sql.= "mon_cambio = '$cambio', "; 	
		$sql.= "mon_compra = '$compra', "; 	
		$sql.= "mon_venta = '$venta' "; 	
		$sql.= "WHERE mon_codigo = $codigo; "; 	
		
		return $sql;
	}
		
	function cambia_sit_moneda($codigo,$sit){
		
		$sql = "UPDATE fin_moneda SET mon_situacion = $sit"; 
		$sql.= " WHERE mon_codigo = $codigo;"; 	
		
		return $sql;
	}
	function max_moneda() {
		
        $sql = "SELECT max(mon_codigo) as max ";
		$sql.= " FROM fin_moneda";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}
	function insert_his_cambio($moneda,$cambio,$compra,$venta){
		$fecha = date("Y-m-d H:i:s");
        $usuario = $_SESSION["codigo"];
        
		$sql = "INSERT INTO fin_moneda_cambio (cam_moneda,cam_cambio,cam_compra,cam_venta,cam_fecha,cam_usuario)";
		$sql.= " VALUES ($moneda,'$cambio','$compra','$venta','$fecha',$usuario);";
		//echo $sql;
		return $sql;
	}
	function get_his_cambio($moneda = '',$fecha = '') {
				
        $sql= "SELECT * ";
		$sql.= " FROM fin_moneda_cambio, fin_moneda, seg_usuarios";
		$sql.= " WHERE cam_moneda = mon_codigo";
        $sql.= " AND usu_id = cam_usuario";
		if(strlen($moneda)>0) { 
			$sql.= " AND cam_moneda = $moneda"; 
		}
		if(strlen($fecha)>0) { 
            $fecha = $this->regresa_fecha($fecha);
            $sql.= " AND cam_fecha = '$fecha'"; 
		}
		$sql.= " ORDER BY cam_fecha ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;

	}
				
	
}	
?>
