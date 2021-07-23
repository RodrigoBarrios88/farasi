<?php
require_once ("ClsConex.php");

class ClsLista extends ClsConex{
/* Situacion 1 = ACTIVO, 0 = INACTIVO */

/////////////////////////////  LISTAS DE CHEQUEO  //////////////////////////////////////
  
    function get_lista($codigo, $categoria = '',$situacion = '') {
		$usuario = $_SESSION['codigo'];
	    $sql= "SELECT * ";
		$sql.= " FROM chk_lista, chk_categoria, chk_usuario_categoria";
        $sql.= " WHERE list_categoria = cat_codigo";
		$sql.= " AND cus_categoria = cat_codigo";
		$sql.= " AND cus_categoria = list_categoria";
		$sql.= " AND cus_usuario = $usuario";

        if(strlen($codigo)>0) { 
			$sql.= " AND list_codigo = $codigo"; 
		}
        if(strlen($categoria)>0) { 
            $sql.= " AND list_categoria IN($categoria)"; 
        }
        if(strlen($situacion)>0) { 
            $sql.= " AND list_situacion IN($situacion)"; 
        }
        $sql.= " ORDER BY list_categoria ASC, list_codigo ASC;";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		//echo mysqli_error($this->conn);
		return $result;
	}
	function count_lista($codigo,$categoria = '',$situacion = '') {
		$fecha = date("Y-m-d H:i:s");
		
	    $sql= "SELECT COUNT(*) as total";
		$sql.= " FROM chk_lista, chk_categoria";
        $sql.= " WHERE list_categoria = cat_codigo";
        if(strlen($codigo)>0) { 
			$sql.= " AND list_codigo = $codigo"; 
		}
        if(strlen($categoria)>0) { 
            $sql.= " AND list_categoria IN($categoria)"; 
        }
        if(strlen($situacion)>0) { 
            $sql.= " AND list_situacion IN($situacion)"; 
        }
		//echo $sql;
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$total = $row['total'];
		}
		return $total;
	}
	function insert_lista($codigo,$categoria,$nombre,$fotos,$firma){
		$nombre = trim($nombre);
		
		$sql = "INSERT INTO chk_lista";
		$sql.= " VALUES ($codigo,$categoria,'$nombre',$fotos,$firma,1);";
		//echo $sql;
		return $sql;
	}
	function modifica_lista($codigo,$categoria,$nombre,$fotos,$firma){
		$nombre = trim($nombre);
		
        $sql = "UPDATE chk_lista SET ";
		$sql.= "list_categoria = '$categoria',"; 
		$sql.= "list_nombre = '$nombre',"; 
		$sql.= "list_fotos = '$fotos',"; 
		$sql.= "list_firma = '$firma'"; 		
		
		$sql.= " WHERE list_codigo = $codigo; "; 	
		//echo $sql;
		return $sql;
	}   
   function cambia_sit_lista($codigo,$situacion){
		
		$sql = "UPDATE chk_lista SET ";
		$sql.= "list_situacion = $situacion"; 
				
		$sql.= " WHERE list_codigo = $codigo; "; 	
		
		return $sql;
	}
	function max_lista(){
	    $sql = "SELECT max(list_codigo) as max ";
		$sql.= " FROM chk_lista";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}
	
/////////////////////////////  PREGUNTAS  //////////////////////////////////////
  
    function get_pregunta($codigo,$lista,$categoria = '',$situacion = '') {
		
	    $sql= "SELECT * ";
		$sql.= " FROM chk_lista, chk_preguntas, chk_categoria";
        $sql.= " WHERE pre_lista = list_codigo";
        $sql.= " AND list_categoria = cat_codigo";
        
		if(strlen($codigo)>0) { 
            $sql.= " AND pre_codigo = $codigo"; 
		}
		if(strlen($lista)>0) { 
			$sql.= " AND pre_lista = $lista"; 
		}
		if(strlen($categoria)>0) { 
            $sql.= " AND list_categoria IN($categoria)"; 
        }
        if(strlen($situacion)>0) { 
            $sql.= " AND pre_situacion = $situacion"; 
        }
        $sql.= " ORDER BY list_categoria ASC, list_codigo ASC, pre_codigo ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function count_pregunta($codigo,$lista,$categoria = '',$situacion = '') {
		$sql= "SELECT COUNT(*) as total";
        $sql.= " FROM chk_lista, chk_preguntas, chk_categoria";
        $sql.= " WHERE pre_lista = list_codigo";
        $sql.= " AND list_categoria = cat_codigo";
       
		if(strlen($codigo)>0) { 
            $sql.= " AND pre_codigo = $codigo"; 
		}
		if(strlen($lista)>0) { 
			$sql.= " AND pre_lista = $lista"; 
		}
		if(strlen($categoria)>0) { 
            $sql.= " AND list_categoria IN($categoria)"; 
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
	function insert_pregunta($codigo,$lista,$descripcion){
        $descripcion = trim($descripcion);
		
		$sql = "INSERT INTO chk_preguntas";
		$sql.= " VALUES ($codigo,$lista,'$descripcion',1);";
		//echo $sql;
		return $sql;
	}
	function modifica_pregunta($codigo,$lista,$descripcion){
		$descripcion = trim($descripcion);
		
		$sql = "UPDATE chk_preguntas SET ";
		$sql.= "pre_lista = $lista,"; 		
		$sql.= "pre_pregunta = '$descripcion'"; 
		
		$sql.= " WHERE pre_codigo = $codigo;";
        //echo $sql;
		return $sql;
	}    
    function cambia_sit_pregunta($codigo,$situacion){
		
		$sql = "UPDATE chk_preguntas SET ";
		$sql.= "pre_situacion = $situacion"; 
				
		$sql.= " WHERE pre_codigo = $codigo; "; 	
		
		return $sql;
	}
    function max_pregunta(){
	    $sql = "SELECT max(pre_codigo) as max ";
		$sql.= " FROM chk_preguntas";
        $result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}    /////////////////////////////  PROGRAMACION  //////////////////////////////////////    
	function get_programacion($codigo,$lista,$sede = '', $sector = '', $area = '', $categoria = '',$dia = '',$hora = '',$situacion = '',$usuario = '',$fini = '',$ffin = '',$tipo = 'S', $usuarioCategoria = '') {
        $sede = ($sede == "")?$_SESSION["sedes_in"]:$sede;
		$usuarioCategoria = $_SESSION['codigo'];
	    $sql= "SELECT *, ";
        $sql.= " (SELECT dm_desc FROM mast_mundep WHERE dm_codigo = sed_municipio) as sede_municipio";
        if($fini != "" && $ffin != "") { 
			$fini = $this->regresa_fecha($fini);
			$ffin = $this->regresa_fecha($ffin);
			$sql.= ", (SELECT rev_codigo FROM chk_revision WHERE rev_programacion = pro_codigo AND rev_situacion = 2 AND rev_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59' ORDER BY rev_codigo LIMIT 0,1) as revision_ejecutada";
			if(strlen($usuario)>0) { 
				$sql.= ", (SELECT rev_codigo FROM chk_revision WHERE rev_programacion = pro_codigo AND rev_usuario = $usuario AND rev_situacion = 1 AND rev_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59' ORDER BY rev_codigo DESC LIMIT 0,1) as revision_activa";
			}else{
				$sql.= ", (SELECT rev_codigo FROM chk_revision WHERE rev_programacion = pro_codigo AND rev_situacion = 1 AND rev_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59' ORDER BY rev_codigo LIMIT 0,1) as revision_activa";
			}
		}
		$sql.= " FROM chk_lista, chk_programacion, chk_categoria, sis_area, sis_sector, sis_sede, chk_usuario_categoria";
        $sql.= " WHERE pro_lista = list_codigo";
        $sql.= " AND list_categoria = cat_codigo";
        $sql.= " AND pro_sede = sed_codigo";
        $sql.= " AND pro_sector = sec_codigo";
		$sql.= " AND pro_area = are_codigo";
        $sql.= " AND list_situacion = 1";
        $sql.= " AND sed_situacion = 1";
        $sql.= " AND sec_situacion = 1";
        $sql.= " AND are_situacion = 1";
        $sql.= " AND cus_categoria = cat_codigo"; 
		$sql.= " AND cus_usuario = $usuarioCategoria"; 

		if(strlen($codigo)>0) { 
            $sql.= " AND pro_codigo = $codigo"; 
		}
		if(strlen($lista)>0) { 
			$sql.= " AND pro_lista = $lista"; 
		}
		if(strlen($sede)>0) { 
            $sql.= " AND pro_sede IN($sede)"; 
        }
        if(strlen($sector)>0) { 
            $sql.= " AND pro_sector = $sector"; 
        }
        if(strlen($area)>0) { 
            $sql.= " AND pro_area = $area"; 
        }
        if(strlen($categoria)>0) { 
            $sql.= " AND list_categoria IN($categoria)"; 
        }
        if(strlen($dia)>0 || $dia != "") {
            if($tipo == 'M'){
                $dia_sql = "AND pro_tipo = 'M' AND pro_dia_mes = $dia";
            }else if($tipo == 'J'){
                switch($dia){
                    case "Mon": $dia_sql = " AND pro_tipo = 'S' AND pro_dia_1 = 1"; break;
                    case "Tue": $dia_sql = " AND pro_tipo = 'S' AND pro_dia_2 = 1"; break;
                    case "Wed": $dia_sql = " AND pro_tipo = 'S' AND pro_dia_3 = 1"; break;
                    case "Thu": $dia_sql = " AND pro_tipo = 'S' AND pro_dia_4 = 1"; break;
                    case "Fri": $dia_sql = " AND pro_tipo = 'S' AND pro_dia_5 = 1"; break;
                    case "Sat": $dia_sql = " AND pro_tipo = 'S' AND pro_dia_6 = 1"; break;
                    case "Sun": $dia_sql = " AND pro_tipo = 'S' AND pro_dia_7 = 1"; break;
                }
            }else if($tipo == 'U'){
				$dia_sql = "AND pro_tipo = 'U' AND pro_fecha = '$dia'";
			}
			$sql.= " $dia_sql"; 
		}
        if(strlen($hora)>0) { 
            $sql.= " AND pro_hini <= '$hora' AND pro_hfin >= '$hora'"; 
        }
        if(strlen($situacion)>0) { 
            $sql.= " AND pro_situacion = $situacion"; 
        }
        $sql.= " ORDER BY list_categoria ASC, pro_sede ASC, pro_sector ASC, pro_area ASC, list_codigo ASC, pro_hini ASC";
		
		$result = $this->exec_query($sql);
    //	echo $sql."<br><br>";
		return $result;
	}    
    function count_programacion($codigo,$lista,$sede = '', $sector = '', $area = '', $categoria = '',$dia = '',$hora = '',$situacion = '') {
		$sede = ($sede == "")?$_SESSION["sedes_in"]:$sede;
        $sql= "SELECT COUNT(*) as total";
        $sql.= " FROM chk_lista, chk_programacion, chk_categoria, sis_area, sis_sector, sis_sede";
        $sql.= " WHERE pro_lista = list_codigo";
        $sql.= " AND list_categoria = cat_codigo";
        $sql.= " AND pro_sede = sed_codigo";
        $sql.= " AND pro_sector = sec_codigo";
		$sql.= " AND pro_area = are_codigo";
        $sql.= " AND list_situacion = 1";
        $sql.= " AND sed_situacion = 1";
        $sql.= " AND sec_situacion = 1";
        $sql.= " AND are_situacion = 1";
        
		if(strlen($codigo)>0) { 
            $sql.= " AND pro_codigo = $codigo"; 
		}
		if(strlen($lista)>0) { 
			$sql.= " AND pro_lista = $lista"; 
		}
		if(strlen($sede)>0) { 
            $sql.= " AND pro_sede IN($sede)"; 
        }
        if(strlen($sector)>0) { 
            $sql.= " AND pro_sector = $sector"; 
        }
        if(strlen($area)>0) { 
            $sql.= " AND pro_area = $area"; 
        }
        if(strlen($categoria)>0) { 
            $sql.= " AND list_categoria IN($categoria)"; 
        }
        if(strlen($dia)>0) {
			switch($dia){
				case 1: $dia_sql = "AND pro_dia_1 = 1"; break;
				case 2: $dia_sql = "AND pro_dia_2 = 1"; break;
				case 3: $dia_sql = "AND pro_dia_3 = 1"; break;
				case 4: $dia_sql = "AND pro_dia_4 = 1"; break;
				case 5: $dia_sql = "AND pro_dia_5 = 1"; break;
				case 6: $dia_sql = "AND pro_dia_6 = 1"; break;
				case 7: $dia_sql = "AND pro_dia_7 = 1"; break;
			}
			$sql.= " $dia_sql"; 
		}
        if(strlen($hora)>0) { 
            $sql.= " AND pro_hini <= '$hora' AND pro_hfin >= '$hora'"; 
        }
        if(strlen($situacion)>0) { 
            $sql.= " AND pro_situacion = $situacion"; 
        }
		//echo $sql."<br><br>";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$total = $row['total'];
		}
		return $total;
	}   
   function insert_programacion($codigo,$lista,$sede,$sector,$area,$tipo,$dia1,$dia2,$dia3,$dia4,$dia5,$dia6,$dia7,$diaMes,$hini,$hfin,$obs,$fecha){
        $obs = trim($obs);
		$fecha = regresa_fecha($fecha);
		$sql = "INSERT INTO chk_programacion";
		$sql.= " VALUES ($codigo,$lista,$sede,$sector,$area,'$tipo',$dia1,$dia2,$dia3,$dia4,$dia5,$dia6,$dia7,$diaMes,'$hini','$hfin','$obs','$fecha',1);";
		//echo $sql;
		return $sql;
	}
	function modifica_programacion($codigo, $lista,$sede,$sector,$area,$tipo,$dia1,$dia2,$dia3,$dia4,$dia5,$dia6,$dia7,$diaMes,$hini,$hfin,$observacion,$fecha){
		$observacion = trim($observacion);
		$fecha = regresa_fecha($fecha);
		$sql = "UPDATE chk_programacion SET ";
		$sql.= "pro_sede = '$sede',"; 		
		$sql.= "pro_sector = '$sector',"; 		
		$sql.= "pro_area = '$area',"; 		
		$sql.= "pro_tipo = '$tipo',"; 		
		$sql.= "pro_dia_1 = $dia1,"; 		
		$sql.= "pro_dia_2 = $dia2,"; 		
		$sql.= "pro_dia_3 = $dia3,"; 		
		$sql.= "pro_dia_4 = $dia4,"; 		
		$sql.= "pro_dia_5 = $dia5,"; 		
		$sql.= "pro_dia_6 = $dia6,"; 		
		$sql.= "pro_dia_7 = $dia7,"; 		
		$sql.= "pro_dia_mes = $diaMes,"; 		
		$sql.= "pro_hini = '$hini',"; 		
		$sql.= "pro_hfin = '$hfin',"; 		
		$sql.= "pro_observaciones = '$observacion',"; 
		$sql.= "pro_fecha = '$fecha'" ;
		$sql.= " WHERE pro_codigo = $codigo;";
        //echo $sql;
		return $sql;
	}    
    function cambia_sit_programacion($codigo,$situacion){
		
		$sql = "UPDATE chk_programacion SET ";
		$sql.= "pro_situacion = $situacion"; 
				
		$sql.= " WHERE pro_codigo = $codigo; "; 	
		
		return $sql;
	}
    function max_programacion(){
	    $sql = "SELECT max(pro_codigo) as max ";
		$sql.= " FROM chk_programacion";
        $result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}
	
	function count_checklist($tipo,$usuario,$fecha,$hora,$fini,$ffin){

		$sql = "SELECT * ";
		if($fini != "" && $ffin != "") { 
			$fini = $this->regresa_fecha($fini);
			$ffin = $this->regresa_fecha($ffin);
			$sql.= ", (SELECT rev_codigo FROM chk_revision WHERE rev_programacion = pro_codigo AND rev_situacion = 2 AND rev_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59' ORDER BY rev_codigo LIMIT 0,1) as revision_ejecutada";
			if(strlen($usuario)>0) { 
				$sql.= ", (SELECT rev_codigo FROM chk_revision WHERE rev_programacion = pro_codigo AND rev_usuario = $usuario AND rev_situacion = 1 AND rev_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59' ORDER BY rev_codigo DESC LIMIT 0,1) as revision_activa";
			}else{
				$sql.= ", (SELECT rev_codigo FROM chk_revision WHERE rev_programacion = pro_codigo AND rev_situacion = 1 AND rev_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59' ORDER BY rev_codigo LIMIT 0,1) as revision_activa";
			}
		}

		$sql .= " FROM chk_lista, chk_programacion, chk_categoria";
		$sql .= " ,sis_area, sis_sector, sis_sede, chk_usuario_categoria";
		$sql .= " WHERE pro_lista = list_codigo";
		$sql .= " AND list_categoria = cat_codigo";
		$sql .= " AND pro_sede = sed_codigo";
		$sql .= " AND pro_sector = sec_codigo";
		$sql .= " AND pro_area = are_codigo";
		$sql .= " AND list_situacion = 1";
		$sql .= " AND sed_situacion = 1";
		$sql .= " AND sec_situacion = 1";
		$sql .= " AND are_situacion = 1";
		$sql .= " AND cus_categoria = cat_codigo";
		switch($tipo){
			case 1:
				//mensual
				$sql .= " AND cus_usuario = $usuario AND pro_tipo = 'M'";
				$sql .= " AND pro_dia_mes = $fecha";			
			break;
			case 2:
				//semanal
				$sql .= " AND cus_usuario = $usuario";
				switch($fecha){
                    case "Mon": $sql .= " AND pro_tipo = 'S' AND pro_dia_1 = 1"; break;
                    case "Tue": $sql .= " AND pro_tipo = 'S' AND pro_dia_2 = 1"; break;
                    case "Wed": $sql .= " AND pro_tipo = 'S' AND pro_dia_3 = 1"; break;
                    case "Thu": $sql .= " AND pro_tipo = 'S' AND pro_dia_4 = 1"; break;
                    case "Fri": $sql .= " AND pro_tipo = 'S' AND pro_dia_5 = 1"; break;
                    case "Sat": $sql .= " AND pro_tipo = 'S' AND pro_dia_6 = 1"; break;
                    case "Sun": $sql .= " AND pro_tipo = 'S' AND pro_dia_7 = 1"; break;
                }
			break;
			case 3:
				//unico
				$sql .= " AND cus_usuario = $usuario AND pro_tipo = 'U'";
				$sql .= " AND pro_fecha = '$fecha'";
			break;
		}
		if(strlen($hora)>0) { 
            $sql.= " AND pro_hini <= '$hora' AND pro_hfin >= '$hora'"; 
        }

		$sql .= " AND pro_situacion = 1 ";
		$result = $this->exec_query($sql);
		// /echo $sql;
		return $result;
	}

}
