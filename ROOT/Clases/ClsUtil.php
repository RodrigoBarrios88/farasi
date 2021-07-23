<?php

class ClsUtil {
	//////////////////////////////////////////////////////////////////////////////////////// 
	/////////////////////// AUXILIAR //////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////
	// Manejo de Fechas
	function cambia_fecha($Fecha){ 
		if ($Fecha<>""){ 
		   $trozos=explode("-",$Fecha,3); 
		   return $trozos[2]."/".$trozos[1]."/".$trozos[0]; 
		}else{return $Fecha;} 
	}   
   function regresa_fecha($Fecha){ 
		if ($Fecha<>""){ 
		   $trozos=explode("/",$Fecha); 
		   return $trozos[2]."-".$trozos[1]."-".$trozos[0]; 
		}else{return $Fecha;} 
	}   
   function regresa_fechaHora($Fecha) { 
		if ($Fecha<>""){ 
		   $trozos=explode("/",$Fecha); 
		   $trozos2=explode(" ",$trozos[2]);
		   $fecha = $trozos2[0]."-".$trozos[1]."-".$trozos[0]; 
		   $hora = $trozos2[1];
		   return "$fecha $hora";
		}else 
		   {return $Fecha;} 
	}
	function cambia_fechaHora($Fecha) { 
		if ($Fecha<>""){ 
		   $trozos=explode("-",$Fecha); 
		   $trozos2=explode(" ",$trozos[2]);
		   $fecha = $trozos2[0]."/".$trozos[1]."/".$trozos[0]; 
		   $hora = $trozos2[1];
		   return "$fecha $hora";
		}else 
		   {return $Fecha;} 
	}
	function Fecha_suma_dias($Fecha, $dias){
		$fec = explode("/",$Fecha);
		$day = $fec[2];
		$mon = $fec[1];
		$year = $fec[0];
		
		$fecha_cambiada = mktime(0,0,0,$mon,$day+$dias,$year);
		$fecha = date("d/m/Y", $fecha_cambiada);
		return $fecha; //devuelve dd/mm/yyyy 
	}
	////////// Encripci�n y Desencripci�n ------------
	function encrypt($string, $key) {
		$result = '';
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$result.=$char;
		}
		return base64_encode($result);
	}
	function decrypt($string, $key) {
		$result = '';
		$string = base64_decode($string);
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$result.=$char;
		}
		return $result;
	}
	function encryptt($string) {
		$result = '';
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$char = chr(ord($char));
			$result.=$char;
		}
		return base64_encode($result);
	}
	function decryptt($string) {
		$result = '';
		$string = base64_decode($string);
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$char = chr(ord($char));
			$result.=$char;
		}
		return $result;
	}
			
}
