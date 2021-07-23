<?php
require_once("ClsConex.php");

class ClsPlan extends ClsConex
{
	/* Situacion 1 = Abierto, 2 = Cerrado, 0 = Anulado */

	/////////////////////////////  EJECUCION DE AUDITORIAS  //////////////////////////////////////

	function get_plan($ejecucion, $auditoria = '', $usuario = '', $sede = '', $departamento = '', $categoria = '', $fini = '', $ffin = '', $situacion = '', $orderfecha = 'DESC')
	{
		$sede = ($sede == "") ? $_SESSION["sedes_in"] : $sede;

		$sql = "SELECT *, ";
		$sql .= " (SELECT usu_nombre FROM seg_usuarios WHERE usu_id = pla_usuario) as usuario_nombre,";
		$sql .= " (SELECT dm_desc FROM mast_mundep WHERE dm_codigo = sed_municipio) as sede_municipio";
		$sql .= " FROM aud_plan, aud_auditoria, aud_programacion, aud_categoria, sis_departamento, sis_sede";
		$sql .= " WHERE pla_auditoria = audit_codigo";
		$sql .= " AND pla_programacion = pro_codigo";
		$sql .= " AND audit_categoria = cat_codigo";
		$sql .= " AND pro_sede = sed_codigo";
		$sql .= " AND pro_departamento = dep_codigo";
		if (strlen($ejecucion) > 0) {
			$sql .= " AND pla_ejecucion = $ejecucion";
		}
		if (strlen($auditoria) > 0) {
			$sql .= " AND audit_codigo = $auditoria";
		}
		if (strlen($usuario) > 0) {
			$sql .= " AND pla_usuario = $usuario";
		}
		if (strlen($sede) > 0) {
			$sql .= " AND pro_sede IN($sede)";
		}
		if (strlen($departamento) > 0) {
			$sql .= " AND pro_departamento = $departamento";
		}
		if (strlen($categoria) > 0) {
			$sql .= " AND audit_categoria IN($categoria)";
		}
		if ($fini != "" && $ffin != "") {
			$fini = $this->regresa_fecha($fini);
			$ffin = $this->regresa_fecha($ffin);
			$sql .= " AND pla_fecha_registro BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'";
		}
		if (strlen($situacion) > 0) {
			$sql .= " AND pla_situacion IN($situacion)";
		}
		$sql .= " ORDER BY pla_fecha_registro $orderfecha, audit_categoria ASC, pro_sede ASC, pro_departamento ASC, audit_codigo ASC";

		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function count_plan($ejecucion, $auditoria = '', $usuario = '', $sede = '', $departamento = '', $categoria = '', $fini = '', $ffin = '', $situacion = '', $orderfecha = 'DESC')
	{
		$sede = ($sede == "") ? $_SESSION["sedes_in"] : $sede;

		$sql = "SELECT COUNT(*) as total";
		$sql .= " FROM aud_plan, aud_auditoria, aud_programacion, aud_categoria, sis_departamento, sis_sede";
		$sql .= " WHERE pla_auditoria = audit_codigo";
		$sql .= " AND pla_programacion = pro_codigo";
		$sql .= " AND audit_categoria = cat_codigo";
		$sql .= " AND pro_sede = sed_codigo";
		$sql .= " AND pro_departamento = dep_codigo";
		if (strlen($ejecucion) > 0) {
			$sql .= " AND pla_ejecucion = $ejecucion";
		}
		if (strlen($auditoria) > 0) {
			$sql .= " AND audit_codigo = $auditoria";
		}
		if (strlen($usuario) > 0) {
			$sql .= " AND pla_usuario = $usuario";
		}
		if (strlen($sede) > 0) {
			$sql .= " AND pro_sede IN($sede)";
		}
		if (strlen($departamento) > 0) {
			$sql .= " AND pro_departamento = $departamento";
		}
		if (strlen($categoria) > 0) {
			$sql .= " AND audit_categoria IN($categoria)";
		}
		if ($fini != "" && $ffin != "") {
			$fini = $this->regresa_fecha($fini);
			$ffin = $this->regresa_fecha($ffin);
			$sql .= " AND pla_fecha_registro BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'";
		}
		if (strlen($situacion) > 0) {
			$sql .= " AND pla_situacion IN($situacion)";
		}
		//echo $sql;
		$result = $this->exec_query($sql);
		foreach ($result as $row) {
			$total = $row['total'];
		}
		return $total;
	}
	function insert_plan($ejecucion, $auditoria, $programacion, $usuario, $tratamiento = '', $numbre_usuario = '', $rol_usuario = '', $observaciones = '')
	{
		$tratamiento = trim($tratamiento);
		$numbre_usuario = trim($numbre_usuario);
		$rol_usuario = trim($rol_usuario);
		$observaciones = trim($observaciones);
		$usuario = ($usuario == '') ? $_SESSION["codigo"] : $usuario;
		$fsis = date("Y-m-d H:i:s");

		$sql = "INSERT INTO aud_plan";
		$sql .= " VALUES ($ejecucion,$auditoria,$programacion,'$tratamiento','$numbre_usuario','$rol_usuario','',$usuario,'$fsis','$fsis','$observaciones',1)";
		$sql .= " ON DUPLICATE KEY UPDATE";
		$sql .= " pla_fecha_update = '$fsis'; ";

		//echo $sql;
		return $sql;
	}
	function update_plan($ejecucion, $tratamiento, $numbre_usuario, $rol_usuario, $observaciones)
	{
		$tratamiento = trim($tratamiento);
		$numbre_usuario = trim($numbre_usuario);
		$rol_usuario = trim($rol_usuario);
		$observaciones = trim($observaciones);
		$fsis = date("Y-m-d H:i:s");

		$sql = "UPDATE aud_plan SET ";
		$sql .= " pla_tratamiento = '$tratamiento',";
		$sql .= " pla_nombre = '$numbre_usuario', ";
		$sql .= " pla_rol = '$rol_usuario', ";
		$sql .= " pla_fecha_update = '$fsis',";
		$sql .= " pla_observaciones = '$observaciones'";

		$sql .= " WHERE pla_ejecucion = $ejecucion; ";
		//echo $sql;
		return $sql;
	}
	function cambia_situacion_plan($ejecucion, $situacion)
	{

		$sql = "UPDATE aud_plan SET pla_situacion = $situacion";
		$sql .= " WHERE pla_ejecucion = $ejecucion; ";

		return $sql;
	}
	function last_firma_usuario($ejecucion)
	{
		$sql = "SELECT pla_firma as last ";
		$sql .= " FROM aud_plan";
		$sql .= " WHERE pla_ejecucion = '$ejecucion'";
		$result = $this->exec_query($sql);
		if (is_array($result)) {
			foreach ($result as $row) {
				$last = $row["last"];
			}
		}
		//echo $sql;
		return $last;
	}


	function cambia_firma_usuario($ejecucion, $firma)
	{

		$sql = "UPDATE aud_plan SET ";
		$sql .= "pla_firma = '$firma'";

		$sql .= " WHERE pla_ejecucion = '$ejecucion';";

		return $sql;
	}
	function max_plan()
	{
		$sql = "SELECT max(pla_ejecucion) as max ";
		$sql .= " FROM aud_plan";
		$result = $this->exec_query($sql);
		foreach ($result as $row) {
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}

	///////////////////////////// RESOLUCIÓN HALLAZGOS  //////////////////////////////////////

	function get_solucion($ejecucion, $auditoria, $pregunta, $seccion = '')
	{

		$sql = "SELECT *, ";
		$sql .= " (SELECT usu_nombre FROM seg_usuarios WHERE usu_id = sol_responsable ORDER BY sol_responsable ASC LIMIT 0,1) as sol_responsable_nombre";
		$sql .= " FROM aud_plan_solucion,aud_preguntas,aud_status";
		$sql .= " WHERE sol_pregunta = pre_codigo";
		$sql .= " AND sol_auditoria = pre_auditoria";
		$sql .= " AND sol_situacion = sta_codigo";

		if (strlen($ejecucion) > 0) {
			$sql .= " AND sol_ejecucion = $ejecucion";
		}
		if (strlen($auditoria) > 0) {
			$sql .= " AND sol_auditoria = $auditoria";
		}
		if (strlen($pregunta) > 0) {
			$sql .= " AND sol_pregunta = $pregunta";
		}
		if (strlen($seccion) > 0) {
			$sql .= " AND pre_seccion = $seccion";
		}
		$sql .= " ORDER BY sol_ejecucion ASC, sol_pregunta ASC";

		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function count_solucion($ejecucion, $auditoria, $pregunta, $seccion = '')
	{
		$sql = "SELECT COUNT(*) as total";
		$sql .= " FROM aud_plan_solucion,aud_preguntas,aud_status";
		$sql .= " WHERE sol_pregunta = pre_codigo";
		$sql .= " AND sol_auditoria = pre_auditoria";
		$sql .= " AND sol_situacion = sta_codigo";

		if (strlen($ejecucion) > 0) {
			$sql .= " AND sol_ejecucion = $ejecucion";
		}
		if (strlen($auditoria) > 0) {
			$sql .= " AND sol_auditoria = $auditoria";
		}
		if (strlen($pregunta) > 0) {
			$sql .= " AND sol_pregunta = $pregunta";
		}
		if (strlen($seccion) > 0) {
			$sql .= " AND pre_seccion = $seccion";
		}
		//echo $sql;
		$result = $this->exec_query($sql);
		foreach ($result as $row) {
			$total = $row['total'];
		}
		return $total;
	}
	function get_plan_solucion($ejecucion, $auditoria = '', $usuario = '', $sede = '', $departamento = '', $categoria = '', $fini = '', $ffin = '', $status = '', $situacion = '', $orderfecha = 'DESC')
	{
		$sede = ($sede == "") ? $_SESSION["sedes_in"] : $sede;

		$sql = "SELECT *, ";
		$sql .= " (SELECT usu_nombre FROM seg_usuarios WHERE usu_id = sol_responsable) as sol_responsable_nombre, ";
		$sql .= " (SELECT dm_desc FROM mast_mundep WHERE dm_codigo = sed_municipio) as sede_municipio";
		$sql .= " FROM aud_plan, aud_auditoria, aud_programacion, aud_ejecucion, aud_categoria, aud_plan_solucion, aud_preguntas, aud_status, sis_departamento, sis_sede";
		$sql .= " WHERE pla_auditoria = audit_codigo";
		$sql .= " AND pla_programacion = pro_codigo";
		$sql .= " AND sol_ejecucion = eje_codigo";
		$sql .= " AND audit_categoria = cat_codigo";
		//--
		$sql .= " AND sol_pregunta = pre_codigo";
		$sql .= " AND sol_auditoria = pre_auditoria";
		$sql .= " AND sol_situacion = sta_codigo";
		//--
		$sql .= " AND pro_sede = sed_codigo";
		$sql .= " AND pro_departamento = dep_codigo";
		if (strlen($ejecucion) > 0) {
			$sql .= " AND pla_ejecucion = $ejecucion";
		}
		if (strlen($auditoria) > 0) {
			$sql .= " AND audit_codigo = $auditoria";
		}
		if (strlen($usuario) > 0) {
			$sql .= " AND pla_usuario = $usuario";
		}
		if (strlen($sede) > 0) {
			$sql .= " AND pro_sede IN($sede)";
		}
		if (strlen($departamento) > 0) {
			$sql .= " AND pro_departamento = $departamento";
		}
		if (strlen($categoria) > 0) {
			$sql .= " AND audit_categoria IN($categoria)";
		}
		if ($fini != "" && $ffin != "") {
			$fini = $this->regresa_fecha($fini);
			$ffin = $this->regresa_fecha($ffin);
			$sql .= " AND sol_fecha_registro BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'";
		}
		if (strlen($status) > 0) {
			$sql .= " AND sta_codigo IN($status)";
		}
		if (strlen($situacion) > 0) {
			$sql .= " AND pla_situacion IN($situacion)";
		}
		$sql .= " ORDER BY pla_fecha_registro $orderfecha, audit_categoria ASC, pro_sede ASC, pro_departamento ASC, audit_codigo ASC";

		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function insert_respuesta($auditoria, $pregunta, $ejecucion, $solucion)
	{
		$fsis = date("Y-m-d H:i:s");

		$sql = "INSERT INTO aud_plan_solucion";
		$sql .= " VALUES ($auditoria,$pregunta,$ejecucion,'$solucion','$fsis','0','$fsis','$fsis',1)";
		$sql .= " ON DUPLICATE KEY UPDATE";
		$sql .= " sol_solucion = '$solucion', ";
		$sql .= " sol_fecha_registro = '$fsis'; ";
		//echo $sql;
		return $sql;
	}
	function  insert_fecha($auditoria, $pregunta, $ejecucion, $fecha)
	{
		$fsis = date("Y-m-d H:i:s");
		$fecha = $this->regresa_fecha($fecha);

		$sql = "INSERT INTO aud_plan_solucion";
		$sql .= " VALUES ($auditoria,$pregunta,$ejecucion,'','$fecha','0','$fsis','$fsis',1)";
		$sql .= " ON DUPLICATE KEY UPDATE";
		$sql .= " sol_fecha = '$fecha', ";
		$sql .= " sol_fecha_registro = '$fsis'; ";
		//echo $sql;
		return $sql;
	}
	function  insert_responsable($auditoria, $pregunta, $ejecucion, $responsable)
	{
		$fsis = date("Y-m-d H:i:s");

		$sql = "INSERT INTO aud_plan_solucion";
		$sql .= " VALUES ($auditoria,$pregunta,$ejecucion,'','$fsis','$responsable','$fsis','$fsis',1)";
		$sql .= " ON DUPLICATE KEY UPDATE";
		$sql .= " sol_responsable = '$responsable', ";
		$sql .= " sol_fecha_registro = '$fsis'; ";
		//echo $sql;
		return $sql;
	}
	function situacion_responsable($auditoria, $pregunta, $ejecucion, $situacion)
	{
		$fsis = date("Y-m-d H:i:s");

		$sql = "UPDATE aud_plan_solucion SET ";
		$sql .= " sol_situacion = '$situacion', ";
		$sql .= " sol_fecha_solucion = '$fsis'";
		$sql .= " WHERE sol_auditoria = '$auditoria'";
		$sql .= " AND sol_pregunta = '$pregunta'";
		$sql .= " AND sol_ejecucion = '$ejecucion'; ";
		//echo $sql;
		return $sql;
	}    /////////////////////////////  PLAN - STATUS  //////////////////////////////////////    
	function get_plan_status($auditoria, $pregunta, $ejecucion, $status)
	{

		$sql = "SELECT * ";
		$sql .= " FROM aud_plan_status,aud_status";
		$sql .= " WHERE sta_codigo = pus_status";
		if (strlen($auditoria) > 0) {
			$sql .= " AND pus_auditoria = $auditoria";
		}
		if (strlen($pregunta) > 0) {
			$sql .= " AND pus_pregunta = $pregunta";
		}
		if (strlen($ejecucion) > 0) {
			$sql .= " AND pus_ejecucion = $ejecucion";
		}
		if (strlen($status) > 0) {
			$sql .= " AND pus_status = $status";
		}
		$sql .= " ORDER BY pus_auditoria ASC, pus_pregunta ASC, pus_ejecucion ASC, pus_status ASC";

		$result = $this->exec_query($sql);
		//echo $sql;
		return $result;
	}
	function  insert_plan_status($auditoria, $pregunta, $ejecucion, $status, $obs)
	{
		$obs = trim($obs);
		$fsis = date("Y-m-d H:i:s");

		$sql = "INSERT INTO aud_plan_status";
		$sql .= " VALUES ($auditoria,$pregunta,$ejecucion,$status,'$obs','$fsis',1)";
		$sql .= " ON DUPLICATE KEY UPDATE pus_observaciones = '$obs',pus_fecha_registro = '$fsis'; ";
		//echo $sql;
		return $sql;
	}
	/////////////////////////////  FOTOS  //////////////////////////////////////    
	function get_fotos($codigo, $ejecucion, $auditoria, $pregunta)
	{

		$sql = "SELECT * ";
		$sql .= " FROM aud_fotos_solucion";
		$sql .= " WHERE 1 = 1";
		if (strlen($codigo) > 0) {
			$sql .= " AND fot_codigo = $codigo";
		}
		if (strlen($ejecucion) > 0) {
			$sql .= " AND fot_ejecucion = $ejecucion";
		}
		if (strlen($auditoria) > 0) {
			$sql .= " AND fot_auditoria = $auditoria";
		}
		if (strlen($pregunta) > 0) {
			$sql .= " AND fot_pregunta = $pregunta";
		}
		$sql .= " ORDER BY fot_codigo ASC";

		$result = $this->exec_query($sql);
		//echo $sql."<br>";
		return $result;
	}
	function  insert_foto($codigo, $auditoria, $pregunta, $ejecucion, $foto)
	{
		$fsis = date("Y-m-d H:i:s");

		$sql = "INSERT INTO aud_fotos_solucion";
		$sql .= " VALUES ($codigo,$auditoria,$pregunta,$ejecucion,'$foto','$fsis')";
		$sql .= " ON DUPLICATE KEY UPDATE";
		$sql .= " fot_foto = '$foto', ";
		$sql .= " fot_fecha_registro = '$fsis'; ";
		//echo $sql;
		return $sql;
	}
	function  delete_foto($codigo, $auditoria, $pregunta, $ejecucion)
	{

		$sql = "DELETE FROM aud_fotos_solucion";
		$sql .= " WHERE fot_auditoria = $auditoria ";
		$sql .= " AND fot_pregunta = $pregunta ";
		$sql .= " AND fot_ejecucion = $ejecucion ";
		$sql .= " AND fot_codigo = $codigo;";

		return $sql;
	}
	function  max_foto($auditoria, $pregunta, $ejecucion)
	{
		$sql = "SELECT max(fot_codigo) as max ";
		$sql .= " FROM aud_fotos_solucion";
		$sql .= " WHERE fot_auditoria = $auditoria ";
		$sql .= " AND fot_pregunta = $pregunta ";
		$sql .= " AND fot_ejecucion = $ejecucion ";
		$result = $this->exec_query($sql);
		foreach ($result as $row) {
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}
	
	////////////////////////////////// RYO MANAGER ////////////////////////
	function get_plan_ryo($codigo = '', $riesgo = '', $oportunidad = '', $responsable = '', $desde = '', $hasta = '', $situacion = '')
	{
		$sql = "SELECT * ";
		$sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE usu_id = pla_responsable) as usu_nombre";
		$sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE usu_id = pla_usuario_revisa) as usu_revisa";
		$sql .= " FROM ryo_plan";
		$sql .= " WHERE 1 = 1";
		if (strlen($codigo) > 0) {
			$sql .= " AND pla_codigo = $codigo";
		}
		if (strlen($riesgo) > 0) {
			$sql .= " AND pla_riesgo = $riesgo";
		}
		if (strlen($oportunidad) > 0) {
			$sql .= " AND pla_oportunidad = $oportunidad";
		}
		if (strlen($responsable) > 0) {
			$sql .= " AND pla_responsable = $responsable";
		}
		if ($desde != "" && $hasta != "") {
			$desde = $this->regresa_fecha($desde);
			$hasta = $this->regresa_fecha($hasta);
			$sql .= " AND pla_fecha_creacion BETWEEN '$desde' AND '$hasta'";
		}
		if (strlen($situacion) > 0) {
			$sql .= " AND pla_situacion IN($situacion)";
		}
		$sql .= " ORDER BY pla_codigo ASC;";

		$result = $this->exec_query($sql);
		//echo $sql;
		//echo '<br>';
		return $result;
	}

	function count_plan_ryo($codigo = '', $responsable = '', $desde = '', $hasta = '', $situacion = '')
	{
		$sql = "SELECT COUNT(*) as total";
		$sql .= " FROM ryo_plan";
		$sql .= " WHERE 1 = 1";
		if (strlen($codigo) > 0) {
			$sql .= " AND pla_codigo = $codigo";
		}
		if (strlen($responsable) > 0) {
			$sql .= " AND pla_responsable = $responsable";
		}
		if ($desde != "" && $hasta != "") {
			$desde = $this->regresa_fecha($desde);
			$hasta = $this->regresa_fecha($hasta);
			$sql .= " AND pla_fecha_creacion '$desde' AND '$hasta'";
		}
		if (strlen($situacion) > 0) {
			$sql .= " AND pla_situacion IN($situacion)";
		}
		//echo $sql;
		$result = $this->exec_query($sql);
		foreach ($result as $row) {
			$total = $row['total'];
		}
		return $total;
	}

	function insert_plan_ryo($codigo, $riesgo, $oportunidad, $usuario)
	{
		$usuario = ($usuario == "") ? $_SESSION["codigo"] : $usuario;
		$fsis = date("Y-m-d");
		$sql = "INSERT INTO ryo_plan";
		$sql .= " VALUES ($codigo, $riesgo, $oportunidad, $usuario, '$fsis', 0, '', '',1);";
		//echo $sql;
		return $sql;
	}

	function update_plan_ryo($codigo, $justificacion)
	{
		$fsis = date("Y-m-d");
		$usuario = $_SESSION["codigo"];

		$sql = "UPDATE ryo_plan SET ";
		$sql .= " pla_justificacion = '$justificacion',";
		$sql .= " pla_fecha_revision = '$fsis', ";
		$sql .= " pla_usuario_revisa = $usuario, ";
		$sql .= " pla_situacion = 1 ";

		$sql .= " WHERE pla_codigo = $codigo; ";
		//echo $sql;
		return $sql;
	}

	function cambia_situacion_plan_ryo($codigo, $situacion)
	{
		$sql = "UPDATE ryo_plan";
        $sql .= " SET pla_situacion = $situacion ";
        $sql .= " WHERE pla_codigo = $codigo; ";
        // echo $sql."<br>";
        return $sql;
	}

	function max_plan_ryo()
	{
		$sql = "SELECT max(pla_codigo) as max ";
		$sql .= " FROM ryo_plan";
		$result = $this->exec_query($sql);
		foreach ($result as $row) {
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}
	 
	////////////////////////////////// Mejora Continua ////////////////////////
	function get_plan_mejora($codigo = '', $hallazgo = '',$responsable = '', $desde = '', $hasta = '', $situacion = '')
	{
		$sql = "SELECT * ";
		$sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE usu_id = pla_responsable) as usu_nombre";
		$sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE usu_id = pla_usuario_revisa) as usu_revisa";
		$sql .= " FROM mej_plan, mej_hallazgo";
		$sql .= " WHERE pla_hallazgo = hal_codigo";
		if (strlen($codigo) > 0) {
			$sql .= " AND pla_codigo = $codigo";
		}
		if (strlen($hallazgo) > 0) {
			$sql .= " AND pla_hallazgo = $hallazgo";
		}
		if (strlen($responsable) > 0) {
			$sql .= " AND pla_responsable = $responsable";
		}
		if ($desde != "" && $hasta != "") {
			$desde = $this->regresa_fecha($desde);
			$hasta = $this->regresa_fecha($hasta);
			$sql .= " AND pla_fecha_creacion BETWEEN '$desde' AND '$hasta'";
		}
		if (strlen($situacion) > 0) {
			$sql .= " AND pla_situacion IN($situacion)";
		}
		$sql .= " ORDER BY pla_codigo ASC;";
		
		$result = $this->exec_query($sql);
		return $result;
	}
	function reinicia_plan_mejora($codigo){
		
		// Crea los Backups
		$sql = "INSERT INTO `mej_bitacora`(`bit_codigo`, `bit_plan`, `bit_periodicidad`, `bit_fecha_inicio`, `bit_fecha_fin`, `bit_descripcion`, `bit_comentario`)";
		$sql .= " SELECT `act_codigo`, `act_plan`, `act_periodicidad`, `act_fecha_inicio`, `act_fecha_fin`, `act_descripcion`, `act_comentario`";
		$sql .= " FROM mej_actividad WHERE act_plan = $codigo AND act_situacion IN (1)";
		$sql .= " ON DUPLICATE KEY UPDATE ";
		$sql .= " bit_periodicidad = act_periodicidad";
		$sql .= " ,bit_fecha_inicio = act_fecha_inicio";
		$sql .= " ,bit_fecha_fin = act_fecha_fin";
		$sql .= " ,bit_descripcion = act_descripcion";
		$sql .= " ,bit_comentario = act_comentario;";

		$sql .= "INSERT INTO `mej_bitacora_detalle`(`dbit_codigo`, `dbit_bitacora`, `dbit_fecha_inicio`, `dbit_fecha_fin`, `dbit_ejecucion`, `dbit_fecha`, `dbit_evaluacion`, `dbit_puntuacion`, `dbit_evalua`, `dbit_fecha_evaluacion`, `dbit_situacion`)";
		$sql .= " SELECT `pro_codigo`, `pro_actividad`, `pro_fecha_inicio`, `pro_fecha_fin`, `pro_ejecucion`, `pro_fecha`, `pro_evaluacion`, `pro_puntuacion`, `pro_evalua`, `pro_fecha_evaluacion`, `pro_situacion`";
		$sql .= " FROM mej_actividad, mej_programacion WHERE pro_actividad = act_codigo AND act_plan = $codigo AND act_situacion IN (1)";
		$sql .= " ON DUPLICATE KEY UPDATE ";
		$sql .= " dbit_fecha_inicio = pro_fecha_inicio";
		$sql .= " ,dbit_fecha_fin = pro_fecha_fin";
		$sql .= " ,dbit_ejecucion = pro_ejecucion";
		$sql .= " ,dbit_fecha = pro_fecha";
		$sql .= " ,dbit_evaluacion = pro_evaluacion";
		$sql .= " ,dbit_puntuacion = pro_puntuacion";
		$sql .= " ,dbit_evalua = pro_evalua";
		$sql .= " ,dbit_fecha_evaluacion = pro_fecha_evaluacion";
		$sql .= " ,dbit_situacion = pro_situacion;";

		// Reinicia la Edicion
		$sql .= "UPDATE mej_plan";
        $sql .= " SET pla_situacion = 1 ";
        $sql .= " WHERE pla_codigo = $codigo; ";
		return $sql;
	}

	function count_plan_mejora($codigo = '', $responsable = '', $desde = '', $hasta = '', $situacion = '')
	{
		$sql = "SELECT COUNT(*) as total";
		$sql .= " FROM mej_plan";
		$sql .= " WHERE 1 = 1";
		if (strlen($codigo) > 0) {
			$sql .= " AND pla_codigo = $codigo";
		}
		if (strlen($responsable) > 0) {
			$sql .= " AND pla_responsable = $responsable";
		}
		if ($desde != "" && $hasta != "") {
			$desde = $this->regresa_fecha($desde);
			$hasta = $this->regresa_fecha($hasta);
			$sql .= " AND pla_fecha_creacion '$desde' AND '$hasta'";
		}
		if (strlen($situacion) > 0) {
			$sql .= " AND pla_situacion IN($situacion)";
		}
		//echo $sql;
		$result = $this->exec_query($sql);
		foreach ($result as $row) {
			$total = $row['total'];
		}
		return $total;
	}

	function insert_plan_mejora($codigo, $hallazgo, $usuario)
	{
		$usuario = ($usuario == "") ? $_SESSION["codigo"] : $usuario;
		$fsis = date("Y-m-d");
		$sql = "INSERT INTO mej_plan";
		$sql .= " VALUES ($codigo, $hallazgo, $usuario, '$fsis', 0, '', '',1);";
		//echo $sql;
		return $sql;
	}

	function update_plan_mejora($codigo, $justificacion)
	{
		$fsis = date("Y-m-d");
		$usuario = $_SESSION["codigo"];

		$sql = "UPDATE mej_plan SET ";
		$sql .= " pla_justificacion = '$justificacion',";
		$sql .= " pla_fecha_revision = '$fsis', ";
		$sql .= " pla_usuario_revisa = $usuario, ";
		$sql .= " pla_situacion = 1 ";

		$sql .= " WHERE pla_codigo = $codigo; ";
		//echo $sql;
		return $sql;
	}

	function cambia_situacion_plan_mejora($codigo, $situacion)
	{
		$sql = "UPDATE mej_plan";
        $sql .= " SET pla_situacion = $situacion ";
        $sql .= " WHERE pla_codigo = $codigo; ";
        // echo $sql."<br>";
        return $sql;
	}

	function max_plan_mejora()
	{
		$sql = "SELECT max(pla_codigo) as max ";
		$sql .= " FROM mej_plan";
		$result = $this->exec_query($sql);
		foreach ($result as $row) {
			$max = $row["max"];
		}
		//echo $sql;
		return $max;
	}
}
