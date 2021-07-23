<?php
require_once ("ClsConex.php");

class ClsEncuestaResolucion extends ClsConex{
/* Situacion 1 = Abierto, 2 = Cerrado, 0 = Anulado */

/////////////////////////////  EJECUCION DE AUDITORIAS  //////////////////////////////////////
  
    function get_ejecucion($codigo,$encuesta = '',$categoria = '',$fini = '',$ffin = '',$situacion = '',$orderfecha = 'DESC') {
		
	    $sql= "SELECT *, ";
        $sql.= " (SELECT usu_nombre FROM seg_usuarios WHERE usu_id = inv_usuario) as usuario_nombre";
		$sql.= " FROM enc_ejecucion, enc_cuestionario, enc_invitacion, enc_categoria";
        $sql.= " WHERE eje_encuesta = cue_codigo";
        $sql.= " AND eje_invitacion = inv_codigo";
        $sql.= " AND cue_categoria = cat_codigo";
        if(strlen($codigo)>0) { 
			$sql.= " AND eje_codigo = $codigo"; 
		}
        if(strlen($encuesta)>0) { 
			$sql.= " AND cue_codigo = $encuesta"; 
		}
        if(strlen($categoria)>0) { 
            $sql.= " AND cue_categoria IN($categoria)"; 
        }
        if($fini != "" && $ffin != "") { 
			$fini = $this->regresa_fecha($fini);
			$ffin = $this->regresa_fecha($ffin);
			$sql.= " AND eje_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'"; 
		}
        if(strlen($situacion)>0) { 
            $sql.= " AND eje_situacion IN($situacion)"; 
        }
        $sql.= " ORDER BY eje_fecha_inicio $orderfecha, cue_categoria ASC, cue_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	
	function count_ejecucion($codigo,$encuesta = '',$categoria = '',$fini = '',$ffin = '',$situacion = '',$orderfecha = 'DESC') {
		
        $sql= "SELECT COUNT(*) as total";
		$sql.= " FROM enc_ejecucion, enc_cuestionario, enc_invitacion, enc_categoria";
        $sql.= " WHERE eje_encuesta = cue_codigo";
        $sql.= " AND eje_invitacion = inv_codigo";
        $sql.= " AND cue_categoria = cat_codigo";
        if(strlen($codigo)>0) { 
			$sql.= " AND eje_codigo = $codigo"; 
		}
        if(strlen($encuesta)>0) { 
			$sql.= " AND cue_codigo = $encuesta"; 
		}
        if(strlen($categoria)>0) { 
            $sql.= " AND cue_categoria IN($categoria)"; 
        }
        if($fini != "" && $ffin != "") { 
			$fini = $this->regresa_fecha($fini);
			$ffin = $this->regresa_fecha($ffin);
			$sql.= " AND eje_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'"; 
		}
        if(strlen($situacion)>0) { 
            $sql.= " AND eje_situacion IN($situacion)"; 
        }
		//echo $sql;
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$total = $row['total'];
		}
		return $total;
	}
    
    
    function comprueba_ejecucion($codigo,$encuesta = '',$invitacion = '',$fini = '',$ffin = '',$situacion = '') {
		
	    $sql= "SELECT * ";
        $sql.= " FROM enc_ejecucion, enc_cuestionario, enc_invitacion";
        $sql.= " WHERE eje_encuesta = cue_codigo";
        $sql.= " AND eje_invitacion = inv_codigo";
        if(strlen($codigo)>0) { 
			$sql.= " AND eje_codigo = $codigo"; 
		}
        if(strlen($encuesta)>0) { 
			$sql.= " AND cue_codigo = $encuesta"; 
		}
        if(strlen($invitacion)>0) { 
			$sql.= " AND inv_codigo = $invitacion"; 
		}
        if($fini != "" && $ffin != "") { 
			$fini = $this->regresa_fecha($fini);
			$ffin = $this->regresa_fecha($ffin);
			$sql.= " AND eje_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'"; 
		}
        if(strlen($situacion)>0) { 
            $sql.= " AND eje_situacion IN($situacion)"; 
        }
        $sql.= " ORDER BY eje_fecha_inicio DESC, inv_codigo, cue_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
    
	
	function insert_ejecucion($codigo,$encuesta,$invitacion,$ip,$region,$ciudad){
		$nombre = trim($nombre);
        $fsis = date("Y-m-d H:i:s");
        
		$sql = "INSERT INTO enc_ejecucion";
		$sql.= " VALUES ($codigo,$encuesta,$invitacion,'$fsis','$fsis','','','','','$ip','$region','$ciudad','$fsis',1)";
		$sql.= " ON DUPLICATE KEY UPDATE";
        $sql.= " eje_ip = '$ip', ";
        $sql.= " eje_region = '$region', ";
        $sql.= " eje_ciudad = '$ciudad', ";
        $sql.= " eje_fecha_update = '$fsis'; ";
        
		//echo $sql."<br>";
		return $sql;
	}
	
	
	function cerrar_ejecucion($codigo){
		$fsis = date("Y-m-d H:i:s");
        
        $sql = "UPDATE enc_ejecucion SET ";
		$sql.= "eje_situacion = 2,"; 
		$sql.= "eje_fecha_final = '$fsis'"; 
		$sql.= " WHERE eje_codigo = $codigo; "; 	
		//echo $sql;
		return $sql;
	}
    
    
    function update_ejecucion($codigo,$campo,$valor){
		
		$sql = "UPDATE enc_ejecucion SET $campo = '$valor'"; 
		$sql.= " WHERE eje_codigo = $codigo; "; 	
		
		return $sql;
	}
    
	function cambia_situacion_ejecucion($codigo,$situacion){
		
		$sql = "UPDATE enc_ejecucion SET eje_situacion = $situacion"; 
		$sql.= " WHERE eje_codigo = $codigo; "; 	
		
		return $sql;
	}
	
	function max_ejecucion(){
	    $sql = "SELECT max(eje_codigo) as max ";
		$sql.= " FROM enc_ejecucion";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}
    
    
	
/////////////////////////////  RESPUESTAS  //////////////////////////////////////
  
    function get_respuesta($ejecucion,$encuesta,$pregunta,$seccion = '') {
		
	    $sql= "SELECT * ";
		$sql.= " FROM enc_respuestas,enc_preguntas";
        $sql.= " WHERE resp_pregunta = pre_codigo";
        $sql.= " AND resp_encuesta = pre_encuesta";
        
		if(strlen($ejecucion)>0) { 
            $sql.= " AND resp_ejecucion = $ejecucion"; 
		}
		if(strlen($encuesta)>0) { 
			$sql.= " AND resp_encuesta = $encuesta"; 
		}
		if(strlen($pregunta)>0) { 
            $sql.= " AND resp_pregunta = $pregunta"; 
        }
        if(strlen($seccion)>0) { 
            $sql.= " AND resp_seccion = $seccion"; 
        }
        $sql.= " ORDER BY resp_ejecucion ASC, resp_pregunta ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	
	function count_respuesta($ejecucion,$encuesta,$pregunta,$seccion = '') {
		$sql= "SELECT COUNT(*) as total";
        $sql.= " FROM enc_respuestas,enc_preguntas";
        $sql.= " WHERE resp_pregunta = pre_codigo";
        $sql.= " AND resp_encuesta = pre_encuesta";
        
		if(strlen($ejecucion)>0) { 
            $sql.= " AND resp_ejecucion = $ejecucion"; 
		}
		if(strlen($encuesta)>0) { 
			$sql.= " AND resp_encuesta = $encuesta"; 
		}
		if(strlen($pregunta)>0) { 
            $sql.= " AND resp_pregunta = $pregunta"; 
        }
		if(strlen($seccion)>0) { 
            $sql.= " AND resp_seccion = $seccion"; 
        }
        //echo $sql;
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$total = $row['total'];
		}
		return $total;
	}
    
    
    function get_ejecucion_respuestas($ejecucion,$encuesta = '',$pregunta = '',$categoria = '',$fini = '',$ffin = '',$situacion = '') {
		
	    $sql= "SELECT *, ";
        $sql.= " (SELECT usu_nombre FROM seg_usuarios WHERE usu_id = inv_usuario) as usuario_nombre";
		$sql.= " FROM enc_cuestionario, enc_invitacion, enc_ejecucion, enc_categoria, enc_preguntas, enc_respuestas ";
        $sql.= " WHERE cue_codigo =  inv_encuesta";
        $sql.= " AND inv_codigo = eje_invitacion ";
        $sql.= " AND inv_encuesta = eje_encuesta ";
        $sql.= " AND cue_categoria = cat_codigo ";
        $sql.= " AND pre_encuesta = cue_codigo ";
        $sql.= " AND pre_codigo = resp_pregunta ";
        $sql.= " AND pre_encuesta = resp_encuesta";
        $sql.= " AND eje_codigo = resp_ejecucion";
         
        if(strlen($ejecucion)>0) { 
			$sql.= " AND resp_ejecucion = $ejecucion"; 
		}
        if(strlen($encuesta)>0) { 
			$sql.= " AND resp_encuesta = $encuesta"; 
		}
        if(strlen($pregunta)>0) { 
			$sql.= " AND resp_pregunta = $pregunta"; 
		}
        if(strlen($categoria)>0) { 
            $sql.= " AND cue_categoria IN($categoria)"; 
        }
        if($fini != "" && $ffin != "") { 
			$fini = $this->regresa_fecha($fini);
			$ffin = $this->regresa_fecha($ffin);
			$sql.= " AND eje_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'"; 
		}
        if(strlen($situacion)>0) { 
            $sql.= " AND eje_situacion IN($situacion)"; 
        }
        $sql.= " ORDER BY eje_fecha_inicio $orderfecha, cue_categoria ASC, cue_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql."<br><br>";
		return $result;
	}
	
	function get_estadistica_respuestas($encuesta = '',$seccion = '', $fechaInicio = '', $fechaFinal = '') {
		$fechaInicio = $this->regresa_fecha($fechaInicio);
		$fechaFinal = $this->regresa_fecha($fechaFinal);
        $sql= "SELECT pre_codigo, pre_tipo, pre_peso, pre_pregunta,";
        $sql.= " (SELECT COUNT(resp_respuesta) FROM enc_respuestas WHERE pre_codigo = resp_pregunta AND pre_encuesta = resp_encuesta AND pre_tipo = resp_tipo AND resp_respuesta = 1 AND resp_fecha_registro BETWEEN '$fechaInicio 00:00:00' AND '$fechaFinal 23:59:59') as respuesta_1,";
        $sql.= " (SELECT COUNT(resp_respuesta) FROM enc_respuestas WHERE pre_codigo = resp_pregunta AND pre_encuesta = resp_encuesta AND pre_tipo = resp_tipo AND resp_respuesta = 2 AND resp_fecha_registro BETWEEN '$fechaInicio 00:00:00' AND '$fechaFinal 23:59:59') as respuesta_2,";
        $sql.= " (SELECT COUNT(resp_respuesta) FROM enc_respuestas WHERE pre_codigo = resp_pregunta AND pre_encuesta = resp_encuesta AND pre_tipo = resp_tipo AND resp_respuesta = 3 AND resp_fecha_registro BETWEEN '$fechaInicio 00:00:00' AND '$fechaFinal 23:59:59') as respuesta_3,";
        $sql.= " (SELECT COUNT(resp_respuesta) FROM enc_respuestas WHERE pre_codigo = resp_pregunta AND pre_encuesta = resp_encuesta AND pre_tipo = resp_tipo AND resp_respuesta = 4 AND resp_fecha_registro BETWEEN '$fechaInicio 00:00:00' AND '$fechaFinal 23:59:59') as respuesta_4,";
        $sql.= " (SELECT COUNT(resp_respuesta) FROM enc_respuestas WHERE pre_codigo = resp_pregunta AND pre_encuesta = resp_encuesta AND pre_tipo = resp_tipo AND resp_respuesta = 5 AND resp_fecha_registro BETWEEN '$fechaInicio 00:00:00' AND '$fechaFinal 23:59:59') as respuesta_5,";
        $sql.= " (SELECT COUNT(resp_respuesta) FROM enc_respuestas WHERE pre_codigo = resp_pregunta AND pre_encuesta = resp_encuesta AND pre_tipo = resp_tipo AND resp_respuesta = 6 AND resp_fecha_registro BETWEEN '$fechaInicio 00:00:00' AND '$fechaFinal 23:59:59') as respuesta_6,";
        $sql.= " (SELECT COUNT(resp_respuesta) FROM enc_respuestas WHERE pre_codigo = resp_pregunta AND pre_encuesta = resp_encuesta AND pre_tipo = resp_tipo AND resp_respuesta = 7 AND resp_fecha_registro BETWEEN '$fechaInicio 00:00:00' AND '$fechaFinal 23:59:59') as respuesta_7,";
        $sql.= " (SELECT COUNT(resp_respuesta) FROM enc_respuestas WHERE pre_codigo = resp_pregunta AND pre_encuesta = resp_encuesta AND pre_tipo = resp_tipo AND resp_respuesta = 8 AND resp_fecha_registro BETWEEN '$fechaInicio 00:00:00' AND '$fechaFinal 23:59:59') as respuesta_8,";
        $sql.= " (SELECT COUNT(resp_respuesta) FROM enc_respuestas WHERE pre_codigo = resp_pregunta AND pre_encuesta = resp_encuesta AND pre_tipo = resp_tipo AND resp_respuesta = 9 AND resp_fecha_registro BETWEEN '$fechaInicio 00:00:00' AND '$fechaFinal 23:59:59') as respuesta_9,";
        $sql.= " (SELECT COUNT(resp_respuesta) FROM enc_respuestas WHERE pre_codigo = resp_pregunta AND pre_encuesta = resp_encuesta AND pre_tipo = resp_tipo AND resp_respuesta = 10 AND resp_fecha_registro BETWEEN '$fechaInicio 00:00:00' AND '$fechaFinal 23:59:59') as respuesta_10,";
        $sql.= " (SELECT resp_observacion FROM enc_respuestas WHERE pre_codigo = resp_pregunta AND pre_encuesta = resp_encuesta AND resp_observacion != '' ORDER BY resp_ejecucion DESC LIMIT 0,1) as respuesta_observacion";
        $sql.= " FROM enc_preguntas";
        $sql.= " WHERE pre_situacion = 1";
        if(strlen($encuesta)>0) {
           $sql.= " AND pre_encuesta IN($encuesta) ";
        }
        if(strlen($seccion)>0) {
           $sql.= " AND pre_seccion IN($seccion)";
        }
        $sql.= " ORDER BY pre_encuesta ASC, pre_seccion ASC, pre_codigo ASC";

        $result = $this->exec_query($sql);
       // echo $sql;
        return $result;
  	}
	
	
	function insert_respuesta($encuesta,$pregunta,$ejecucion,$seccion,$tipo,$peso,$respuesta){
        $fsis = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO enc_respuestas (resp_encuesta, resp_pregunta, resp_ejecucion, resp_seccion, resp_tipo, resp_peso, resp_respuesta, resp_fecha_registro)";
		$sql.= " VALUES ($encuesta,$pregunta,$ejecucion,$seccion,$tipo,'$peso',$respuesta,'$fsis')";
        $sql.= " ON DUPLICATE KEY UPDATE";
        $sql.= " resp_seccion = '$seccion', ";
		$sql.= " resp_tipo = '$tipo', ";
		$sql.= " resp_peso = '$peso', ";
		$sql.= " resp_respuesta = '$respuesta', ";
		$sql.= " resp_fecha_registro = '$fsis'; ";
		//echo $sql;
		return $sql;
	}
    
    function update_respuesta($encuesta,$pregunta,$ejecucion,$seccion,$observacion){
        $fsis = date("Y-m-d H:i:s");
		
		$sql = "INSERT INTO enc_respuestas (resp_encuesta, resp_pregunta, resp_ejecucion, resp_seccion, resp_observacion, resp_fecha_registro)";
		$sql.= " VALUES ($encuesta,$pregunta,$ejecucion,$seccion,'$observacion','$fsis')";
        $sql.= " ON DUPLICATE KEY UPDATE";
		$sql.= " resp_seccion = '$seccion', ";
		$sql.= " resp_observacion = '$observacion', ";
		$sql.= " resp_fecha_registro = '$fsis';";
        //echo $sql;
		return $sql;
	}
    
}

?>