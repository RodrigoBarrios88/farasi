<?php
require_once("ClsConex.php");

class ClsSede extends ClsConex
{
	/* Situacion 1 = ACTIVO, 2 = INACTIVO */

	function get_sede($codigo, $departamento = '', $municipio = '', $nombre = '', $situacion = '', $zona = '')
	{
		$sedes_access = $_SESSION["sedes_in"];
		$nombre = trim($nombre);
		if ($_SESSION["GESSED"] != 1) { /// SI tiene permisos de gestor de sedes, ve todas las sedes. Si no, incluye esta validación 
			$codigo = ($codigo == "") ? $_SESSION["sedes_in"] : $codigo;
		}
		$sql = "SELECT * ";
		$sql .= " FROM sis_sede,mast_mundep";
		$sql .= " WHERE sed_municipio = dm_codigo";
		if ($_SESSION["USUSED"] != 1) {
			$sql .= " AND sed_codigo IN($sedes_access)";
		}
		if (strlen($codigo) > 0) {
			$sql .= " AND sed_codigo IN($codigo)";
		}
		if (strlen($departamento) > 0) {
			$sql .= " AND sed_departamento = '$departamento'";
		}
		if (strlen($municipio) > 0) {
			$sql .= " AND sed_municipio = '$municipio'";
		}
		if (strlen($nombre) > 0) {
			$sql .= " AND sed_nombre like '%$nombre%'";
		}
		if (strlen($zona) > 0) {
			$sql .= " AND sed_zona = '$zona'";
		}
		if (strlen($situacion) > 0) {
			$sql .= " AND sed_situacion = '$situacion'";
		}
		$sql .= " ORDER BY sed_codigo ASC";

		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function count_sede($codigo, $departamento = '', $municipio = '', $nombre = '', $situacion = '', $zona = '')
	{
		$sedes_access = $_SESSION["sedes_in"];
		$nombre = trim($nombre);
		if ($_SESSION["GESSED"] != 1) { /// SI tiene permisos de gestor de sedes, ve todas las sedes. Si no, incluye esta validación 
			$codigo = ($codigo == "") ? $_SESSION["sedes_in"] : $codigo;
		}

		$sql = "SELECT COUNT(*) as total";
		$sql .= " FROM sis_sede,mast_mundep";
		$sql .= " WHERE sed_municipio = dm_codigo";
		if ($_SESSION["USUSED"] != 1) {
			$sql .= " AND sed_codigo IN($sedes_access)";
		}
		if (strlen($codigo) > 0) {
			$sql .= " AND sed_codigo IN($codigo)";
		}
		if (strlen($departamento) > 0) {
			$sql .= " AND sed_departamento = '$departamento'";
		}
		if (strlen($municipio) > 0) {
			$sql .= " AND sed_municipio = '$municipio'";
		}
		if (strlen($nombre) > 0) {
			$sql .= " AND sed_nombre like '%$nombre%'";
		}
		if (strlen($zona) > 0) {
			$sql .= " AND sed_zona = '$zona'";
		}
		if (strlen($situacion) > 0) {
			$sql .= " AND sed_situacion = '$situacion'";
		}
		//echo $sql;
		$result = $this->exec_query($sql);
		foreach ($result as $row) {
			$total = $row['total'];
		}
		return $total;
	}
	function insert_sede($codigo, $nombre, $departamento, $municipio, $direccion, $zona, $lat, $long)
	{
		$nombre = trim($nombre);
		$direccion = trim($direccion);

		$sql = "INSERT INTO sis_sede";
		$sql .= " VALUES ($codigo,'$nombre',$departamento,$municipio,'$direccion',$zona,'$lat','$long',1);";
		//echo $sql;
		return $sql;
	}
	function modifica_sede($codigo, $nombre, $departamento, $municipio, $direccion, $zona, $lat, $long)
	{
		$nombre = trim($nombre);
		$direccion = trim($direccion);

		$sql = "UPDATE sis_sede SET ";
		$sql .= "sed_nombre = '$nombre',";
		$sql .= "sed_direccion = '$direccion',";
		$sql .= "sed_departamento = '$departamento',";
		$sql .= "sed_municipio = '$municipio',";
		$sql .= "sed_zona = '$zona',";
		$sql .= "sed_latitud = '$lat',";
		$sql .= "sed_longitud = '$long'";

		$sql .= " WHERE sed_codigo = $codigo; ";
		//echo $sql;
		return $sql;
	}
	function cambia_situacion_sede($codigo, $situacion)
	{

		$sql = "UPDATE sis_sede SET ";
		$sql .= "sed_situacion = $situacion";
		$sql .= " WHERE sed_codigo = $codigo; ";

		return $sql;
	}
	function max_sede()
	{
		$sql = "SELECT max(sed_codigo) as max ";
		$sql .= " FROM sis_sede";
		$result = $this->exec_query($sql);
		foreach ($result as $row) {
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}    ///////////// LOGOS /////////////////
	function cambia_foto($sede, $string)
	{
		$freg = date("Y-m-d H:i:s");
		$usureg = $_SESSION["codigo"];

		$sql = "INSERT INTO sis_foto_sede (fot_sede, fot_string , fot_fecha_registro, fot_usuario_registro)";
		$sql .= " VALUES('$sede','$string','$freg','$usureg')";
		$sql .= " ON DUPLICATE KEY UPDATE";
		$sql .= " fot_string = '$string',";
		$sql .= " fot_fecha_registro = '$freg',";
		$sql .= " fot_usuario_registro = '$usureg';";

		return $sql;
	}
	function last_foto_sede($sede)
	{
		$sql = "SELECT fot_string as last ";
		$sql .= " FROM sis_foto_sede";
		$sql .= " WHERE fot_sede = '$sede'";
		$result = $this->exec_query($sql);
		if (is_array($result)) {
			foreach ($result as $row) {
				$last = $row["last"];
			}
		}
		//echo $sql;
		return $last;
	}
}
