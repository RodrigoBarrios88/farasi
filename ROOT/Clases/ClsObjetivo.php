<?php
require_once("ClsConex.php");

class ClsObjetivo extends ClsConex
{
    function get_objetivo($codigo = "", $proceso = "", $sistema = '', $situacion = '1')
    {

        $sql = "SELECT * ";
        $sql .= " FROM pro_objetivos, pro_sistema, pro_ficha";
        $sql .= " WHERE obj_sistema = sis_codigo";
        $sql .= " AND obj_proceso = fic_codigo";
        if (strlen($codigo) > 0) {
            $sql .= " AND obj_codigo = $codigo";
        }
        if (strlen($proceso) > 0) {
            $sql .= " AND obj_proceso = $proceso";
        }
        if (strlen($sistema) > 0) {
            $sql .= " AND obj_sistema = $sistema";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND obj_situacion IN ($situacion)";
        }
        $sql .= " ORDER BY obj_proceso ASC, obj_sistema ASC;";

        $result = $this->exec_query($sql);
        //echo $sql."<br>";
        return $result;
    }    

	function  count_objetivo($codigo = "", $proceso = "", $sistema = '', $situacion = '1')
    {

        $sql = "SELECT COUNT(*) as total";
        $sql .= " FROM pro_objetivos, pro_sistema, pro_ficha";
        $sql .= " WHERE obj_sistema = sis_codigo";
        $sql .= " AND obj_proceso = fic_codigo";
        if (strlen($codigo) > 0) {
            $sql .= " AND obj_codigo = $codigo";
        }
        if (strlen($proceso) > 0) {
            $sql .= " AND obj_proceso = $proceso";
        }
        if (strlen($sistema) > 0) {
            $sql .= " AND obj_sistema = $sistema";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND obj_situacion IN ($situacion)";
        }
        //echo $sql;
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $total = $row['total'];
        }
        return $total;
    }

    function insert_objetivo($codigo, $proceso, $sistema, $descripcion, $usuario = '')
    {
        $usuario = ($usuario == '') ? $_SESSION["codigo"] : $usuario;
        $fsis = date("Y-m-d H:i:s");

        $sql = "INSERT INTO pro_objetivos";
        $sql .= " VALUES ($codigo,$proceso,$sistema,'$descripcion',$usuario,'$fsis',1)";
        $sql .= " ON DUPLICATE KEY UPDATE obj_descripcion = '$descripcion', obj_usuario = '$usuario', obj_fecha_registro = '$fsis'; ";
        // echo $sql;
        return $sql;
    }
    
    function cambia_situacion_objetivo($codigo, $situacion)
    {
        $sql = "UPDATE pro_objetivos SET obj_situacion = $situacion";
        $sql .= " WHERE obj_codigo = $codigo; ";

        return $sql;
    }

    function max_objetivo()
    {
        $sql = "SELECT max(obj_codigo) as max ";
        $sql .= " FROM pro_objetivos";
        $result = $this->exec_query($sql);
        if (is_array($result)) {
            foreach ($result as $row) {
                $max = $row["max"];
            }
        }
        //echo $sql;
        return $max;
    }

    function modifica_objetivo($codigo, $descripcion)
    {
        $descripcion = trim($descripcion);

        $sql = "UPDATE pro_objetivos SET ";
        $sql .= "obj_descripcion = '$descripcion'";
        $sql .= " WHERE obj_codigo = $codigo;";
        //echo $sql;
        return $sql;
    }

    function get_objetivo_asignado($proceso, $sistema = '', $usuario = "")
    {
        $sql = "SELECT * ";
        $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE fus_usuario = usu_id) as usuario_nombre";
        $sql .= " ,(SELECT rev_situacion FROM pla_revision_objetivo WHERE obj_codigo = rev_objetivo AND rev_usuario_asignado = obj_usuario) AS Estado";
        $sql .= " FROM pro_objetivos, pro_usuario_ficha, pro_sistema, pro_ficha";
        $sql .= " WHERE fus_ficha = obj_proceso";
        $sql .= " AND sis_codigo = obj_sistema";
        $sql .= " AND fic_codigo = fus_ficha";
        $sql .= " AND obj_situacion = 1";
        $sql .= " AND fic_situacion = 3";
        
        if (strlen($usuario) > 0) {
            $sql .= " AND fus_usuario = $usuario";
        }
        if (strlen($proceso) > 0) {
            $sql .= " AND obj_proceso = $proceso";
        }
        if (strlen($sistema) > 0) {
            $sql .= " AND obj_sistema = $sistema";
        }
        $sql .= " ORDER BY obj_proceso ASC, obj_sistema ASC;";

        $result = $this->exec_query($sql);
      //echo $sql . "<br>";
        return $result;
    }

    ///////////////////////////////// Revision de Objetivos /////////////////////////////
    function get_revision($codigo = "", $proceso = "", $sistema = "", $usuario = "", $objetivo = '',  $situacion = "")
    {
        $sql = "SELECT * ";

        $sql .= ",(SELECT usu_nombre FROM seg_usuarios WHERE usu_id = rev_usuario_asignado) as usuario_nombre";
        $sql .= " ,(SELECT sis_nombre FROM pro_sistema WHERE obj_sistema = sis_codigo) as sistema_nombre";
        $sql .= " FROM pla_revision_objetivo, pro_objetivos, pro_ficha";
        $sql .= " WHERE rev_objetivo = obj_codigo";
        $sql .= " AND obj_proceso = fic_codigo";
        if (strlen($codigo) > 0) {
            $sql .= " AND rev_codigo = $codigo";
        }
        if (strlen($proceso) > 0) {
            $sql .= " AND obj_proceso = $proceso";
        }
        if (strlen($sistema) > 0) {
            $sql .= " AND obj_sistema = $sistema";
        }
        if (strlen($objetivo) > 0) {
            $sql .= " AND rev_objetivo = $objetivo";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND rev_usuario_asignado = $usuario";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND rev_situacion IN($situacion)";
        }

        $sql .= " ORDER BY rev_codigo ASC, obj_codigo ASC;";

        $result = $this->exec_query($sql);
        //echo $sql."<br>";
        return $result;
    }
    function count_revision($codigo = "", $proceso = "", $sistema = "", $usuario = "", $objetivo = '',  $situacion = "")
    {

        $sql = "SELECT COUNT(*) as total";
        $sql .= " FROM pla_revision_objetivo, pro_objetivos, pro_ficha";
        $sql .= " WHERE rev_objetivo = obj_codigo";
        $sql .= " AND obj_proceso = fic_codigo";
        if (strlen($codigo) > 0) {
            $sql .= " AND rev_codigo = $codigo";
        }
        if (strlen($proceso) > 0) {
            $sql .= " AND obj_proceso = $proceso";
        }
        if (strlen($sistema) > 0) {
            $sql .= " AND obj_sistema = $sistema";
        }
        if (strlen($objetivo) > 0) {
            $sql .= " AND rev_objetivo = $objetivo";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND rev_usuario_asignado = $usuario";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND rev_situacion IN($situacion)";
        }
        //echo $sql;
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $total = $row['total'];
        }
        return $total;
    }

    function insert_revision($codigo, $objetivo, $asignado, $usuario, $fini, $ffin, $situacion)
    {
        $asignado = ($asignado == '') ? $_SESSION["codigo"] : $asignado;
        $fini = ($fini == '') ?  date("Y-m-d H:i:s") : $fini;
        $ffin = ($ffin == '') ?  date("Y-m-d H:i:s") : $ffin;

        $sql = "INSERT INTO pla_revision_objetivo ";
        $sql .= " VALUES ($codigo,$objetivo,$asignado,$usuario,'$fini','$ffin','',$situacion)";
        $sql .= " ON DUPLICATE KEY UPDATE rev_fecha_fin = '$ffin',  rev_situacion = $situacion, rev_usuario_revisa = $usuario; ";
        //echo $sql;
        return $sql;
    }

    function max_revision()
    {
        $sql = "SELECT max(rev_codigo) as max ";
        $sql .= " FROM pla_revision_objetivo";
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
        $sql = "UPDATE pla_revision_objetivo SET rev_situacion = $situacion, rev_observacion = '$observacion'";
        $sql .= " WHERE rev_codigo = $codigo ";
        return $sql;
    }
}
