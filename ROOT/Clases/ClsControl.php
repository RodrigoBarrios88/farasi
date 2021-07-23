<?php
require_once("ClsConex.php");

class ClsControl extends ClsConex
{
    function get_control($codigo = "", $objetivo = "", $situacion = '')
    {

        $sql = "SELECT * ";
        $sql .= " FROM pro_control, pro_objetivos";
        $sql .= " WHERE con_objetivo = obj_codigo";
        if (strlen($codigo) > 0) {
            $sql .= " AND con_codigo = $codigo";
        }
        if (strlen($objetivo) > 0) {
            $sql .= " AND con_objetivo = $objetivo";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND con_situacion IN ($situacion)";
        }
        $sql .= " ORDER BY con_codigo ASC, con_objetivo ASC;";

        $result = $this->exec_query($sql);
        // echo $sql."<br>";
        return $result;
    }

    function get_control_asignado($proceso, $sistema = '', $usuario = "")
    {
        $sql = "SELECT * ";
        $sql .= " FROM pro_control, pro_usuario_ficha, pro_sistema, pro_ficha";
        $sql .= " WHERE fus_ficha = con_proceso";
        $sql .= " AND sis_codigo = con_sistema";
        $sql .= " AND fic_codigo = fus_ficha";
        if (strlen($usuario) > 0) {
            $sql .= " AND fus_usuario = $usuario";
        }
        if (strlen($proceso) > 0) {
            $sql .= " AND con_proceso = $proceso";
        }
        if (strlen($sistema) > 0) {
            $sql .= " AND con_sistema = $sistema";
        }
        $sql .= " ORDER BY con_proceso ASC, con_sistema ASC, con_tipo ASC;";

        $result = $this->exec_query($sql);
        // echo $sql . "<br>";
        return $result;
    }

    function insert_control($codigo, $objetivo, $descripcion)
    {
        $sql = "INSERT INTO pro_control";
        $sql .= " VALUES ($codigo,$objetivo,'$descripcion',1)";
        $sql .= " ON DUPLICATE KEY UPDATE con_descripcion = '$descripcion'; ";
        // echo $sql;
        return $sql;
    }

    function cambia_situacion_control($codigo, $situacion)
    {
        $sql = "UPDATE pro_control SET con_situacion = $situacion";
        $sql .= " WHERE con_codigo = $codigo; ";

        return $sql;
    }

    function max_control()
    {
        $sql = "SELECT max(con_codigo) as max ";
        $sql .= " FROM pro_control";
        $result = $this->exec_query($sql);
        if (is_array($result)) {
            foreach ($result as $row) {
                $max = $row["max"];
            }
        }
        //echo $sql;
        return $max;
    }
	function modifica_control($codigo,$descripcion){
		$descripcion = trim($descripcion);
		
		$sql = "UPDATE pro_control SET ";	
		$sql.= "con_descripcion = '$descripcion'"; 
		$sql.= " WHERE con_codigo = $codigo;";
        //echo $sql;
		return $sql;
    }    ////////////////////////////// Revision de Objetivos /////////////////////////////
    function get_revision($codigo, $proceso = "", $sistema = "", $usuario = "", $control = '', $desde = "", $hasta = "", $situacion = "")
    {
        $sql = "SELECT * ";

        $sql .= ",(SELECT usu_nombre FROM seg_usuarios WHERE usu_id = rev_usuario_asignado) as usuario_nombre";
        $sql .= " ,(SELECT sis_nombre FROM pro_sistema WHERE con_sistema = sis_codigo) as sistema_nombre";
        $sql .= " FROM pla_revision_control, pro_control, pro_ficha";
        $sql .= " WHERE rev_control = con_codigo";
        $sql .= " AND con_proceso = fic_codigo";
        if (strlen($codigo) > 0) {
            $sql .= " AND rev_codigo = $codigo";
        }
        if (strlen($proceso) > 0) {
            $sql .= " AND con_proceso = $proceso";
        }
        if (strlen($sistema) > 0) {
            $sql .= " AND con_sistema = $sistema";
        }
        if (strlen($control) > 0) {
            $sql .= " AND rev_control = $control";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND rev_usuario_asignado = $usuario";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND rev_situacion IN($situacion)";
        }

        $sql .= " ORDER BY rev_codigo ASC, con_codigo ASC;";

        $result = $this->exec_query($sql);
        // echo $sql."<br>";
        return $result;
    }

    function insert_revision($codigo, $control, $asignado, $usuario, $fini, $ffin, $situacion)
    {
        $asignado = ($asignado == '') ? $_SESSION["codigo"] : $asignado;
        $fini = ($fini == '') ?  date("Y-m-d H:i:s") : $fini;
        $ffin = ($ffin == '') ?  date("Y-m-d H:i:s") : $ffin;

        $sql = "INSERT INTO pla_revision_control ";
        $sql .= " VALUES ($codigo,$control,$asignado,$usuario,'$fini','$ffin','',$situacion)";
        $sql .= " ON DUPLICATE KEY UPDATE rev_fecha_fin = '$ffin',  rev_situacion = $situacion, rev_usuario_revisa = $usuario; ";
        //echo $sql;
        return $sql;
    }

    function max_revision()
    {
        $sql = "SELECT max(rev_codigo) as max ";
        $sql .= " FROM pla_revision_control";
        $result = $this->exec_query($sql);
        if (is_array($result)) {
            foreach ($result as $row) {
                $max = $row["max"];
            }
        }
        //echo $sql;
        return $max;
    }

    function cambia_situacion_revision($codigo, $observacion, $situacion)
    {
        $sql = "UPDATE pla_revision_control SET rev_situacion = $situacion, rev_observacion = '$observacion'";
        $sql .= " WHERE rev_codigo = $codigo ";
        return $sql;
    }
}
