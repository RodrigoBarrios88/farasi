<?php
require_once("ClsConex.php");

class ClsRiesgo extends ClsConex
{
    function get_riesgo($codigo = "", $riesgo = "", $proceso = '', $sistema = '', $tipo = "", $situacion = '', $usuario = "", $userFoda = '')
    {
        if($situacion ==  ""){
            $situacion = '1,2';
        }
        $sql = "SELECT * ";
        $sql .= " ,(SELECT fic_nombre FROM pro_ficha WHERE fod_proceso = fic_codigo) as fic_nombre";
        $sql .= " ,(SELECT sis_nombre FROM pro_sistema WHERE fod_sistema = sis_codigo) as sis_nombre";
        $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE rie_usuario = usu_id) as usu_nombre";
        $sql .= " FROM ryo_riesgo, pro_foda, pro_ficha";
        $sql .= " WHERE rie_foda_elemento = fod_codigo";
        $sql .= " AND rie_proceso = fod_proceso";
        $sql .= " AND fod_proceso = fic_codigo";
        if (strlen($codigo) > 0) {
            $sql .= " AND rie_codigo = $codigo";
        }
        if (strlen($riesgo) > 0) {
            $sql .= " AND rie_foda_elemento = $riesgo";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND rie_usuario = $usuario";
        }
        if (strlen($userFoda) > 0) {
            $sql .= " AND fod_usuario = $userFoda";
        }
        if (strlen($proceso) > 0) {
            $sql .= " AND rie_proceso = $proceso";
        }
        if (strlen($sistema) > 0) {
            $sql .= " AND fod_sistema = $sistema";
        }
        if (strlen($tipo) > 0) {
            $sql .= " AND fod_tipo = $tipo";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND rie_situacion IN ($situacion)";
        }
        $sql .= " ORDER BY rie_codigo ASC;";

        $result = $this->exec_query($sql);
     // echo $sql."<br>";
        return $result;
    }

    function count_riesgo($codigo = "", $riesgo = "", $proceso = '', $sistema = '', $situacion = '1')
    {

        $sql = "SELECT COUNT(*) as total";
        $sql .= " FROM ryo_riesgo, pro_foda, pro_ficha";
        $sql .= " WHERE rie_foda_elemento = fod_codigo";
        $sql .= " AND fod_proceso = fic_codigo";
        if (strlen($codigo) > 0) {
            $sql .= " AND rie_codigo = $codigo";
        }
        if (strlen($riesgo) > 0) {
            $sql .= " AND rie_foda_elemento = $riesgo";
        }
        if (strlen($proceso) > 0) {
            $sql .= " AND fod_proceso = $proceso";
        }
        if (strlen($sistema) > 0) {
            $sql .= " AND fod_sistema = $sistema";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND rie_situacion IN ($situacion)";
        }
        //echo $sql;
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $total = $row['total'];
        }
        return $total;
    }

    function insert_riesgo($codigo, $riesgo, $proceso, $origen = "", $causa = "", $consecuencia = "", $probabilidad = 0, $impacto = 0, $usuario = "", $evaluacion = 0, $accion = 0)
    {
        $usuario = ($usuario == "") ? $_SESSION["codigo"] : $usuario;
        $fecha = date("Y-m-d");
        $sql = "INSERT INTO ryo_riesgo";
        $sql .= " VALUES ($codigo,$riesgo,$proceso,'$origen','$causa','$consecuencia',$probabilidad, $impacto, $usuario, '$fecha', $evaluacion,$accion,'',0,1);";
        // echo $sql;
        return $sql;
    }

    function cambia_situacion_riesgo($codigo, $situacion)
    {
        $sql = "UPDATE ryo_riesgo SET rie_situacion = $situacion";
        $sql .= " WHERE rie_codigo = $codigo; ";

        return $sql;
    }

    function max_riesgo()
    {
        $sql = "SELECT max(rie_codigo) as max ";
        $sql .= " FROM ryo_riesgo";
        $result = $this->exec_query($sql);
        if (is_array($result)) {
            foreach ($result as $row) {
                $max = $row["max"];
            }
        }
        //echo $sql;
        return $max;
    }

    function modifica_riesgo($codigo, $campo, $valor)
    {
        $sql = "UPDATE ryo_riesgo";
        $sql .= " SET $campo = '$valor' ";
        $sql .= " WHERE rie_codigo = $codigo; ";
        // echo $sql."<br>";
        return $sql;
    }

    /////////////////////////////  Asignacion de Riesgos  //////////////////////////////////////

    function get_riesgo_usuario($codigo = "", $riesgo = "", $usuario = "", $grupos = false)
    {
        $sql = "SELECT * ";
        $sql .= " FROM ryo_usuario_riesgo, ryo_riesgo, pro_foda, seg_usuarios";
        $sql .= " WHERE rus_riesgo = rie_codigo";
        $sql .= " AND rus_usuario = usu_id";
        $sql .= " AND rie_foda_elemento = fod_codigo";
        $sql .= " AND rie_situacion IN (1,2)";
        if (strlen($codigo) > 0) {
            $sql .= " AND rus_codigo = $codigo";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND rus_usuario = $usuario";
        }
        if (strlen($riesgo) > 0) {
            $sql .= " AND rus_riesgo = $riesgo";
        }
        if ($grupos) $sql .= " GROUP BY rus_riesgo";
        $sql .= " ORDER BY rie_codigo ASC, usu_id ASC;";

        $result = $this->exec_query($sql);
        // echo $sql;
        return $result;
    }

    function insert_riesgo_usuario($codigo, $riesgo, $usuario)
    {
        $usu_reg = $_SESSION["codigo"];
        $fec_reg = date("Y-m-d H:i:s");

        $sql = "INSERT INTO ryo_usuario_riesgo ";
        $sql .= " VALUES ($codigo,$riesgo,$usuario,'$fec_reg',$usu_reg);";
        //echo $sql;
        return $sql;
    }

    function max_riesgo_usuario($riesgo)
    {
        $sql = "SELECT max(rus_codigo) as max ";
        $sql .= " FROM ryo_usuario_riesgo";
        $sql .= " WHERE rus_riesgo = $riesgo; ";
        $result = $this->exec_query($sql);
        if (is_array($result)) {
            foreach ($result as $row) {
                $max = $row["max"];
            }
        }
        //echo $sql;
        return $max;
    }

    function delete_riesgo_usuario($riesgo)
    {
        $sql = "DELETE FROM ryo_usuario_riesgo";
        $sql .= " WHERE rus_riesgo = $riesgo;";

        return $sql;
    }

    /////////////////////////////////////////// Archivos ////////////////////////////////////////

    function get_archivo($codigo, $riesgo, $posicion = '')
    {
        $sql = "SELECT * ";
        $sql .= " FROM ryo_archivo_riesgo";
        $sql .= " WHERE 1 = 1";
        if (strlen($codigo) > 0) {
            $sql .= " AND arc_codigo = $codigo";
        }
        if (strlen($riesgo) > 0) {
            $sql .= " AND arc_riesgo IN($riesgo)";
        }
        if (strlen($posicion) > 0) {
            $sql .= " AND arc_posicion = $posicion";
        }
        $sql .= " ORDER BY arc_riesgo ASC, arc_posicion ASC;";

        $result = $this->exec_query($sql);
        // echo $sql;
        return $result;
    }

    function insert_archivo($codigo, $riesgo, $posicion, $archivo)
    {
        $sql = "INSERT INTO ryo_archivo_riesgo";
        $sql .= " VALUES ($codigo,$riesgo,$posicion,'$archivo',1)";
        $sql .= " ON DUPLICATE KEY UPDATE";
        $sql .= " arc_archivo = '$archivo'; ";
        //echo $sql;
        return $sql;
    }

    function delete_archivo($codigo)
    {
        $sql = "DELETE FROM ryo_archivo_riesgo";
        $sql .= " WHERE arc_codigo = $codigo; ";

        return $sql;
    }

    function max_archivo()
    {
        $sql = "SELECT max(arc_codigo) as max ";
        $sql .= " FROM ryo_archivo_riesgo";
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $max = $row["max"];
        }
        //echo $sql;
        return $max;
    }
}
