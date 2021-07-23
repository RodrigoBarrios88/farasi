<?php
require_once("ClsConex.php");

class ClsPermiso extends ClsConex
{
	/* GRUPO */
	//////////////////////////////////////////////////////////////////

	function get_grupo($id, $desc = '', $clv = '')
	{
		$desc = trim($desc);
		$clv = strtoupper($clv);

		$sql = "SELECT * ";
		$sql .= " FROM seg_grupo_permisos";
		$sql .= " WHERE gperm_situacion = 1";
		if (strlen($id) > 0) {
			$sql .= " AND gperm_id = $id";
		}
		if (strlen($desc) > 0) {
			$sql .= " AND UPPER(gperm_desc) like '%$desc%'";
		}
		if (strlen($desc) > 0) {
			$sql .= " AND UPPER(gperm_clave) like '%$clv%'";
		}
		$sql .= " ORDER BY gperm_id ASC";

		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function count_grupo($id, $desc = '', $clv = '')
	{
		$desc = trim($desc);
		$clv = strtoupper($clv);

		$sql = "SELECT COUNT(*) as total";
		$sql .= " FROM seg_grupo_permisos";
		$sql .= " WHERE gperm_situacion = 1";
		if (strlen($id) > 0) {
			$sql .= " AND gperm_id = $id";
		}
		if (strlen($desc) > 0) {
			$sql .= " AND UPPER(gperm_desc) like '%$desc%'";
		}
		if (strlen($desc) > 0) {
			$sql .= " AND UPPER(gperm_clave) like '%$clv%'";
		}
		//echo $sql;
		$result = $this->exec_query($sql);
		foreach ($result as $row) {
			$total = $row['total'];
		}
		return $total;
	}
	function insert_grupo($id, $desc, $clv)
	{
		$desc = trim($desc);
		$clv = strtoupper($clv);

		$sql = "INSERT INTO seg_grupo_permisos VALUES ($id,'$desc','$clv',1);";
		//echo $sql;
		return $sql;
	}
	function modifica_grupo($id, $desc, $clv)
	{
		$desc = trim($desc);
		$clv = strtoupper($clv);

		$sql = "UPDATE seg_grupo_permisos SET gperm_desc = '$desc',";
		$sql .= " gperm_clave = '$clv'";
		$sql .= " WHERE gperm_id = $id;";
		//echo $sql;
		return $sql;
	}
	function cambia_sit_grupo($id, $sit)
	{

		$sql = "UPDATE seg_grupo_permisos SET gperm_situacion = $sit";
		$sql .= " WHERE gperm_id = $id;";

		return $sql;
	}
	function max_grupo()
	{

		$sql = "SELECT max(gperm_id) as max ";
		$sql .= " FROM seg_grupo_permisos";
		$result = $this->exec_query($sql);
		foreach ($result as $row) {
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}

	/* PERMISOS */
	//////////////////////////////////////////////////////////////////

	function get_permisos($id, $grupo, $desc = '', $clv = '')
	{
		$desc = trim($desc);

		$sql = "SELECT * ";
		$sql .= " FROM seg_permisos,seg_grupo_permisos";
		$sql .= " WHERE perm_grupo = gperm_id";
		$sql .= " AND gperm_situacion = 1";
		if (strlen($id) > 0) {
			$sql .= " AND perm_id = $id";
		}
		if (strlen($grupo) > 0) {
			$sql .= " AND perm_grupo = $grupo";
		}
		if (strlen($desc) > 0) {
			$sql .= " AND perm_desc like '%$desc%'";
		}
		if (strlen($clv) > 0) {
			$sql .= " AND perm_clave = '$clv'";
		}
		$sql .= " ORDER BY perm_grupo ASC, perm_id ASC";

		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function count_permisos($id, $grupo, $desc = '', $clv = '')
	{
		$desc = trim($desc);

		$sql = "SELECT COUNT(*) as total";
		$sql .= " FROM seg_permisos,seg_grupo_permisos";
		$sql .= " WHERE perm_grupo = gperm_id";
		$sql .= " AND gperm_situacion = 1";
		if (strlen($id) > 0) {
			$sql .= " AND perm_id = $id";
		}
		if (strlen($grupo) > 0) {
			$sql .= " AND perm_grupo = $grupo";
		}
		if (strlen($desc) > 0) {
			$sql .= " AND perm_desc like '%$desc%'";
		}
		if (strlen($clv) > 0) {
			$sql .= " AND perm_clave = '$clv'";
		}
		//echo $sql;
		$result = $this->exec_query($sql);
		foreach ($result as $row) {
			$total = $row['total'];
		}
		return $total;
	}
	function insert_permisos($id, $grupo, $desc, $clv)
	{
		$desc = trim($desc);
		$clv = strtoupper($clv);

		$sql = "INSERT INTO seg_permisos VALUES ($id,$grupo,'$desc','$clv');";
		//echo $sql;
		return $sql;
	}
	function modifica_permisos($id, $grupo, $desc, $clv)
	{
		$desc = trim($desc);
		$clv = strtoupper($clv);

		$sql = "UPDATE seg_permisos SET ";
		$sql .= " perm_desc = '$desc',";
		$sql .= " perm_clave = '$clv'";

		$sql .= " WHERE perm_id = $id";
		$sql .= " AND perm_grupo = $grupo;";
		//echo $sql;
		return $sql;
	}
	function max_permiso($grupo)
	{

		$sql = "SELECT max(perm_id) as max ";
		$sql .= " FROM seg_permisos";
		$sql .= " WHERE perm_grupo = $grupo";
		$result = $this->exec_query($sql);
		foreach ($result as $row) {
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}

	/* ASIGNACION DE PERMISOS */
	//////////////////////////////////////////////////////////////////
	function insert_perm_asignacion($usu, $perm, $grupo)
	{
		$fsis = date("Y-m-d H:i:s");
		$quien = $_SESSION["codigo"];
		$sql = "INSERT INTO seg_asignacion ";
		$sql .= "VALUES ($usu,$perm,$grupo,'$fsis',$quien)";
		$sql .= " ON DUPLICATE KEY UPDATE aperm_fecha = '$fsis', aperm_quien = '$quien'; ";
		//echo $sql;
		return $sql;
	}
	function actualiza_asignacion_rol($usu, $rol)
	{
		$fsis = date("Y-m-d H:i:s");
		$quien = $_SESSION["codigo"];

		$sql = "INSERT INTO seg_asignacion";
		$sql .= " SELECT $usu, drol_permiso, drol_grupo, '$fsis', $quien";
		$sql .= " FROM seg_det_rol WHERE drol_rol = $rol";
		$sql .= " ON DUPLICATE KEY UPDATE aperm_fecha = '$fsis', aperm_quien = '$quien'; ";
		//echo $sql;
		return $sql;
	}
	function delet_perm_asignacion($usu)
	{
		$sql = "DELETE FROM seg_asignacion";
		$sql .= " WHERE aperm_usuario = $usu; ";
		//echo $sql;
		return $sql;
	}
	function get_asi_permisos($usu, $rol = '', $perm = '', $grupo = '', $quien = '')
	{

		$sql = "SELECT *, ";
		$sql .= " (SELECT usu_nombre FROM seg_usuarios WHERE usu_id = aperm_quien) as quien";
		$sql .= " FROM seg_asignacion,seg_usuarios,seg_rol,seg_permisos,seg_grupo_permisos";
		$sql .= " WHERE aperm_usuario = usu_id";
		$sql .= " AND usu_rol = rol_id";
		$sql .= " AND aperm_permiso = perm_id";
		$sql .= " AND aperm_grupo = perm_grupo";
		$sql .= " AND perm_grupo = gperm_id";
		if (strlen($usu) > 0) {
			$sql .= " AND aperm_usuario = $usu";
		}
		if (strlen($rol) > 0) {
			$sql .= " AND usu_rol = $rol";
		}
		if (strlen($perm) > 0) {
			$sql .= " AND aperm_permiso = $perm";
		}
		if (strlen($grupo) > 0) {
			$sql .= " AND aperm_grupo = $grupo";
		}
		if (strlen($quien) > 0) {
			$sql .= " AND aperm_quien = $quien";
		}
		$sql .= " ORDER BY perm_grupo ASC, perm_id ASC";

		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function count_asi_permisos($usu, $rol = '', $perm = '', $grupo = '', $quien = '')
	{

		$sql = "SELECT COUNT(*) as total";
		$sql .= " FROM seg_asignacion,seg_usuarios,seg_rol,seg_permisos,seg_grupo_permisos";
		$sql .= " WHERE aperm_usuario = usu_id";
		$sql .= " AND usu_rol = rol_id";
		$sql .= " AND aperm_permiso = perm_id";
		$sql .= " AND aperm_grupo = perm_grupo";
		$sql .= " AND perm_grupo = gperm_id";
		if (strlen($usu) > 0) {
			$sql .= " AND aperm_usuario = $usu";
		}
		if (strlen($rol) > 0) {
			$sql .= " AND usu_rol = $rol";
		}
		if (strlen($perm) > 0) {
			$sql .= " AND aperm_permiso = $perm";
		}
		if (strlen($grupo) > 0) {
			$sql .= " AND aperm_grupo = $grupo";
		}
		if (strlen($quien) > 0) {
			$sql .= " AND aperm_quien = $quien";
		}
		//echo $sql;
		$result = $this->exec_query($sql);
		foreach ($result as $row) {
			$total = $row['total'];
		}
		return $total;
	}
}
