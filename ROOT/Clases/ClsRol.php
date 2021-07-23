<?php
require_once ("ClsConex.php");

class ClsRol extends ClsConex{
/* ROLL */
//////////////////////////////////////////////////////////////////
   
    function get_rol($id,$nom = '',$desc = ''){
		$nom = trim($nom);
		$desc = trim($desc);
		
        $sql= "SELECT * ";
		$sql.= " FROM seg_rol";
		$sql.= " WHERE rol_situacion = 1";
		$sql.= " AND rol_id > 0";
		if(strlen($id)>0){ 
			  $sql.= " AND rol_id = $id"; 
		}
		if(strlen($nom)>0){ 
			  $sql.= " AND UPPER(rol_nombre) like '%$nom%'"; 
		}
		if(strlen($desc)>0){ 
			  $sql.= " AND UPPER(rol_desc) like '%$desc%'"; 
		}
		$sql.= " ORDER BY rol_id ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function count_rol($id,$nom = '',$desc = ''){
		$nom = trim($nom);
		$desc = trim($desc);
		
        $sql= "SELECT COUNT(*) as total";
		$sql.= " FROM seg_rol";
		$sql.= " WHERE rol_situacion = 1";
		$sql.= " AND rol_id > 0";
		if(strlen($id)>0){ 
			  $sql.= " AND rol_id = $id"; 
		}
		if(strlen($nom)>0){ 
			  $sql.= " AND UPPER(rol_nombre) like '%$nom%'"; 
		}
		if(strlen($desc)>0){ 
			  $sql.= " AND UPPER(rol_desc) like '%$desc%'"; 
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
	function get_rol_libre($id,$nom = '',$desc = ''){
		$nom = trim($nom);
		$desc = trim($desc);
		
        $sql= "SELECT * ";
		$sql.= " FROM seg_rol";
		$sql.= " WHERE rol_situacion = 1";
		if(strlen($id)>0){ 
			  $sql.= " AND rol_id = $id"; 
		}
		if(strlen($nom)>0){ 
			  $sql.= " AND UPPER(rol_nombre) like '%$nom%'"; 
		}
		if(strlen($desc)>0){ 
			  $sql.= " AND UPPER(rol_desc) like '%$desc%'"; 
		}
		$sql.= " ORDER BY rol_id ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function insert_rol($id,$nom,$desc){
		$desc = trim($desc);
		
		$sql = "INSERT INTO seg_rol VALUES ($id,'$nom','$desc',1);";
		//echo $sql;
		return $sql;
	}
	function modifica_rol($id,$nom,$desc){
		$desc = trim($desc);
		
		$sql = "UPDATE seg_rol SET rol_nombre = '$nom', rol_desc = '$desc'"; 
		$sql.= " WHERE rol_id = $id;"; 	
		//echo $sql;
		return $sql;
	}
	function cambia_sit_rol($id,$sit){
		
		$sql = "UPDATE seg_rol SET rol_situacion = $sit"; 
		$sql.= " WHERE rol_id = $id;"; 	
		
		return $sql;
	}
	function max_rol() {
		
        $sql = "SELECT max(rol_id) as max ";
		$sql.= " FROM seg_rol";
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}
	
/* DETALLE DE ROLL*/
//////////////////////////////////////////////////////////////////
   
    function get_det_rol($perm,$grupo,$roll){
		
        $sql= "SELECT * ";
		$sql.= " FROM seg_permisos,seg_grupo_permisos,seg_rol,seg_det_rol";
		$sql.= " WHERE drol_rol = rol_id";
		$sql.= " AND drol_grupo = gperm_id";
		$sql.= " AND drol_permiso = perm_id";
		$sql.= " AND perm_grupo = gperm_id";
		if(strlen($perm)>0) { 
			  $sql.= " AND drol_permiso = $perm"; 
		}
		if(strlen($grupo)>0) { 
			  $sql.= " AND drol_grupo = $grupo"; 
		}
		if(strlen($roll)>0) { 
			  $sql.= " AND drol_rol = $roll"; 
		}
		$sql.= " ORDER BY drol_grupo ASC, drol_permiso ASC, drol_rol ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function count_det_rol($perm,$grupo,$roll){
		
        $sql= "SELECT COUNT(*) as total";
		$sql.= " FROM seg_permisos,seg_grupo_permisos,seg_rol,seg_det_rol";
		$sql.= " WHERE drol_rol = rol_id";
		$sql.= " AND drol_grupo = gperm_id";
		$sql.= " AND drol_permiso = perm_id";
		$sql.= " AND perm_grupo = gperm_id";
		if(strlen($perm)>0) { 
			  $sql.= " AND drol_permiso = $perm"; 
		}
		if(strlen($grupo)>0) { 
			  $sql.= " AND drol_grupo = $grupo"; 
		}
		if(strlen($roll)>0) { 
			  $sql.= " AND drol_rol = $roll"; 
		}
		$sql.= " ORDER BY drol_grupo ASC, drol_permiso ASC, drol_rol ASC";
		
		//echo $sql;
		$result = $this->exec_query($sql);
		foreach($result as $row){
			$total = $row['total'];
		}
		return $total;
	}
	function get_det_rol_outer_edit($rol){
		
        $sql= "SELECT *, ";
		//subquery
			$sql.= "(SELECT COUNT(*) FROM seg_det_rol ";
			$sql.= " WHERE drol_rol = $rol";
			$sql.= " AND drol_grupo = perm_grupo";
			$sql.= " AND drol_permiso = perm_id) as activo ";
		//fin subquery
		$sql.= " FROM seg_permisos,seg_grupo_permisos";
		$sql.= " WHERE perm_grupo = gperm_id";
		$sql.= " ORDER BY perm_grupo ASC, perm_id ASC";
		
		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function insert_det_rol($perm,$grupo,$roll){
		
		$sql = "INSERT INTO seg_det_rol VALUES ($perm,$grupo,$roll);";
		//echo $sql;
		return $sql;
	}
	function delet_det_rol($perm,$grupo,$roll){
		$sql = "DELETE FROM seg_det_rol"; 
		$sql.= " WHERE drol_permiso = $perm";
		$sql.= " AND drol_grupo = $grupo"; 
		$sql.= " AND drol_rol = $roll;"; 
		//echo $sql;
		return $sql;
	}
	function delet_det_rol_grupo($roll){
		$sql = "DELETE FROM seg_det_rol"; 
		$sql.= " WHERE drol_rol = $roll;"; 
		//echo $sql;
		return $sql;
	}

}
