<?php
require_once("ClsConex.php");

class ClsEvaluacion extends ClsConex
{
    ///////////////////////////// Evaluacion de Acciones  //////////////////////////////////////
    function get_evaluacion($codigo = '', $proceso = '', $sistema = '', $ejecucion = '', $accion = '', $asignado = '', $objetivo = "", $fini = '', $ffin = '', $usuario = "", $situacion = '')
    {

        $sql = "SELECT * ";
        $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE acc_usuario = usu_id) as acc_usuario";
        $sql .= " ,(SELECT fic_nombre FROM pro_ficha WHERE obj_proceso = fic_codigo) as obj_proceso";
        $sql .= " ,(SELECT sis_nombre FROM pro_sistema WHERE obj_sistema = sis_codigo) as obj_sistema";
        $sql .= " FROM pla_evaluacion, pla_ejecucion, pla_programacion, pla_accion, pro_objetivos";
        $sql .= " WHERE eva_ejecucion = eje_codigo";
        $sql .= " AND eje_programacion = pro_codigo";
        $sql .= " AND pro_accion = acc_codigo";
        $sql .= " AND acc_objetivo = obj_codigo";
        $sql .= " AND acc_situacion != 0";
        if (strlen($codigo) > 0) {
            $sql .= " AND eva_codigo = $codigo";
        }
        if (strlen($proceso) > 0) {
            $sql .= " AND obj_proceso = $proceso";
        }
        if (strlen($sistema) > 0) {
            $sql .= " AND obj_sistema = $sistema";
        }
        if (strlen($ejecucion) > 0) {
            $sql .= " AND eva_ejecucion = $ejecucion";
        }
        if (strlen($accion) > 0) {
            $sql .= " AND acc_codigo = $accion";
        }
        if (strlen($asignado) > 0) {
            $sql .= " AND acc_usuario = $asignado";
        }
        if (strlen($objetivo) > 0) {
            $sql .= " AND acc_objetivo = $objetivo";
        }
        if ($fini != "" && $ffin != "") {
            $fini = $this->regresa_fecha($fini);
            $ffin = $this->regresa_fecha($ffin);
            $sql .= " AND eva_fecha BETWEEN '$fini' AND '$ffin'";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND eva_usuario = $usuario";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND eva_situacion IN($situacion)";
        }
        $sql .= " ORDER BY eva_codigo ASC, eje_codigo ASC;";

        $result = $this->exec_query($sql);
        
       // echo mysqli_error($this->conn);

        //echo $sql."<br>";
        return $result;
    }

    function count_evaluacion($codigo = '', $ejecucion = '', $fini = '', $ffin = '', $usuario = "", $accion = "", $objetivo = "", $asignado = "", $situacion = '1')
    {

        $sql = "SELECT COUNT(*) as total";
        $sql .= " FROM pla_evaluacion, pla_ejecucion, pla_programacion, pla_accion";
        $sql .= " WHERE eva_ejecucion = eje_codigo";
        $sql .= " AND eje_programacion = pro_codigo";
        $sql .= " AND pro_accion = acc_codigo";
        $sql .= " AND acc_situacion != 0";
        if (strlen($codigo) > 0) {
            $sql .= " AND eva_codigo = $codigo";
        }
        if (strlen($ejecucion) > 0) {
            $sql .= " AND eva_ejecucion = $ejecucion";
        }
        if (strlen($accion) > 0) {
            $sql .= " AND pro_accion = $accion";
        }
        if (strlen($objetivo) > 0) {
            $sql .= " AND acc_objetivo = $objetivo";
        }
        if (strlen($asignado) > 0) {
            $sql .= " AND acc_usuario = $asignado";
        }
        if ($fini != "" && $ffin != "") {
            $fini = $this->regresa_fecha($fini);
            $ffin = $this->regresa_fecha($ffin);
            $sql .= " AND eva_fecha BETWEEN '$fini' AND '$ffin'";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND eva_usuario = $usuario";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND eva_situacion IN($situacion)";
        }
        // echo $sql;
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $total = $row['total'];
        }
        return $total;
    }

    function insert_evaluacion($codigo, $ejecucion, $observacion, $puntuacion)
    {
        $fsis = date("Y-m-d H:i:s");
        $usuario = $_SESSION["codigo"];

        $sql = "INSERT INTO pla_evaluacion";
        $sql .= " VALUES ($codigo,$ejecucion,$usuario,'$fsis','$observacion',$puntuacion,1)";
        $sql .= " ON DUPLICATE KEY UPDATE";
        $sql .= " eva_observacion = '$observacion'; ";

        //echo $sql;
        return $sql;
    }

    function max_evaluacion()
    {
        $sql = "SELECT max(eva_codigo) as max ";
        $sql .= " FROM pla_evaluacion";
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $max = $row["max"];
        }
        //echo $sql;
        return $max;
    }
}
