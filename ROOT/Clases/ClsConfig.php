<?php
require_once ("ClsConex.php");

class ClsConfig extends ClsConex{    ////////////////////////// CREDENCIALES //////////////////////////
    function get_credenciales() {
				
        $sql= "SELECT * ";
		$sql.= " FROM conf_credenciales";
        $sql.= " WHERE 1 = 1";
				
		$result = $this->exec_query($sql);
		
		return $result;
	}    
	function update_credenciales($nombre,$nombre_reporte,$direccion1,$direccion2,$departamento,$municipio,$telefono,$correo,$website) {
		
		$sql = "UPDATE conf_credenciales SET ";
		$sql.= "cliente_nombre = '$nombre',";
		$sql.= "cliente_nombre_reporte = '$nombre_reporte',";
		$sql.= "cliente_direccion1 = '$direccion1',";
		$sql.= "cliente_direccion2 = '$direccion2',";
		$sql.= "cliente_departamento = '$departamento',";
		$sql.= "cliente_municipio = '$municipio',";
		$sql.= "cliente_telefono = '$telefono',";
		$sql.= "cliente_correo = '$correo',";
		$sql.= "cliente_website  = '$website'";
		
		$sql.= "WHERE cliente_codigo = 1;";	return $sql;
	}    ////////////////////////// MODULOS //////////////////////////    
	function get_modulos() {
				
        $sql= "SELECT * ";
		$sql.= " FROM conf_modulos";
        $sql.= " WHERE 1 = 1";
				
		$result = $this->exec_query($sql);
		
		return $result;
	}    
	function  update_situacion_modulos($codigo,$situacion) {
		$sql = "UPDATE conf_modulos SET mod_situacion = '$situacion' WHERE mod_codigo = $codigo;";
		return $sql;
	}
		
}
