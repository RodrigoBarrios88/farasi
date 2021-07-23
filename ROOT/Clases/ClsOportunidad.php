<?php
require_once("ClsConex.php");

class ClsOportunidad extends ClsConex
{
    function get_oportunidad($codigo = "", $oportunidad = "", $proceso = '', $sistema = '', $tipo = "", $situacion = '1', $usuario = "",$userFoda= '')
    {
        $sql = "SELECT * ";
        $sql .= " ,(SELECT fic_nombre FROM pro_ficha WHERE fod_proceso = fic_codigo) as fic_nombre";
        $sql .= " ,(SELECT sis_nombre FROM pro_sistema WHERE fod_sistema = sis_codigo) as sis_nombre";
        $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE opo_usuario = usu_id) as usu_nombre";
        $sql .= " FROM ryo_oportunidad, pro_foda, pro_ficha";
        $sql .= " WHERE opo_foda_elemento = fod_codigo";
        $sql .= " AND opo_proceso = fod_proceso";
        $sql .= " AND fod_proceso = fic_codigo";
        if (strlen($codigo) > 0) {
            $sql .= " AND opo_codigo = $codigo";
        }
        if (strlen($oportunidad) > 0) {
            $sql .= " AND opo_foda_elemento = $oportunidad";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND opo_usuario = $usuario";
        }
        if (strlen($userFoda) > 0) {
            $sql .= " AND fod_usuario = $userFoda";
        }
        if (strlen($proceso) > 0) {
            $sql .= " AND opo_proceso = $proceso";
        }
        if (strlen($sistema) > 0) {
            $sql .= " AND fod_sistema = $sistema";
        }
        if (strlen($tipo) > 0) {
            $sql .= " AND fod_tipo = $tipo";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND opo_situacion IN ($situacion)";
        }
        $sql .= " ORDER BY opo_codigo ASC;";

        $result = $this->exec_query($sql);
        // echo $sql . "<br>";
        return $result;
    }

    function count_oportunidad($codigo = "", $oportunidad = "", $proceso = '', $sistema = '', $situacion = '1')
    {
        $sql = "SELECT COUNT(*) as total";
        $sql .= " FROM ryo_oportunidad, pro_foda, pro_ficha";
        $sql .= " WHERE opo_foda_elemento = fod_codigo";
        $sql .= " AND fod_proceso = fic_codigo";
        if (strlen($codigo) > 0) {
            $sql .= " AND opo_codigo = $codigo";
        }
        if (strlen($oportunidad) > 0) {
            $sql .= " AND opo_foda_elemento = $oportunidad";
        }
        if (strlen($proceso) > 0) {
            $sql .= " AND fod_proceso = $proceso";
        }
        if (strlen($sistema) > 0) {
            $sql .= " AND fod_sistema = $sistema";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND opo_situacion IN ($situacion)";
        }
        //echo $sql;
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $total = $row['total'];
        }
        return $total;
    }

    function insert_oportunidad($codigo, $oportunidad, $proceso,  $viabilidad = 0, $rentabilidad = 0, $accion = 0, $justificacion = "", $revisa = 0)
    {
        $usuario = $_SESSION["codigo"];
        $fecha = date("Y-m-d");
        $sql = "INSERT INTO ryo_oportunidad";
        $sql .= " VALUES ($codigo,$oportunidad,$proceso, $viabilidad, $rentabilidad,$usuario,'$fecha', $accion,1);";
        // echo $sql;
        return $sql;
    }

    function cambia_situacion_oportunidad($codigo, $situacion)
    {
        $sql = "UPDATE ryo_oportunidad SET opo_situacion = $situacion";
        $sql .= " WHERE opo_codigo = $codigo; ";

        return $sql;
    }

    function max_oportunidad()
    {
        $sql = "SELECT max(opo_codigo) as max ";
        $sql .= " FROM ryo_oportunidad";
        $result = $this->exec_query($sql);
        if (is_array($result)) {
            foreach ($result as $row) {
                $max = $row["max"];
            }
        }
        //echo $sql;
        return $max;
    }

    function modifica_oportunidad($codigo, $campo, $valor)
    {
        $sql = "UPDATE ryo_oportunidad";
        $sql .= " SET $campo = '$valor' ";
        $sql .= " WHERE opo_codigo = $codigo; ";
        // echo $sql."<br>";
        return $sql;
    }
}
