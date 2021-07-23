<?php
require_once("ClsConex.php");

class ClsRequisito extends ClsConex
{
    function get_requisito_usuarios($codigo = "", $requisito = "", $usuario = "", $fecha_registro = "", $usuario_registro = '', $grupos = '')
    {
        $sql = "SELECT * ";
        $sql .= " FROM req_usuario_requisito, req_requisito , seg_usuarios";
        $sql .= " WHERE rus_requisito = req_codigo";
        $sql .= " AND rus_usuario = usu_id";
        if (strlen($codigo) > 0) {
            $sql .= " AND rus_codigo = $codigo";
        }
        if (strlen($requisito) > 0) {
            $sql .= " AND rus_requisito = $requisito";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND rus_usuario = $usuario";
        }
        if (strlen($fecha_registro) > 0) {
            $sql .= " AND rus_fecha_registro = $fecha_registro";
        }
        if (strlen($usuario_registro) > 0) {
            $sql .= " AND rus_usuario_registro = $usuario_registro";
        }
        if ($grupos) $sql .= " GROUP BY rus_requisito";
        $sql .= " ORDER BY rus_codigo DESC ;";
        $result = $this->exec_query($sql);
       //echo $sql;
        return $result;
    }
    function insert_requisito_usuario($codigo, $requisito, $usuario)
    {
        //--
        $usu_reg = $_SESSION["codigo"];
        $fec_reg = date("Y-m-d H:i:s");

        $sql = "INSERT INTO req_usuario_requisito";
        $sql .= " VALUES ($codigo,$requisito,$usuario,'$fec_reg',$usu_reg);";
        //echo $sql;
        return $sql;
    }

    function max_requisito_usuario($requisito)
    {
        $sql = "SELECT max(fus_codigo) as max ";
        $sql .= " FROM req_usuario_requisito";
        $sql .= " WHERE rus_requisito = $requisito; ";
        $result = $this->exec_query($sql);
        if (is_array($result)) {
            foreach ($result as $row) {
                $max = $row["max"];
            }
        }
        //echo $sql;
        return $max;
    }

    function delete_requisito_usuario($requisito)
    {
        $sql = "DELETE FROM req_usuario_requisito";
        $sql .= " WHERE rus_requisito = $requisito;";

        return $sql;
    }


    function get_requisito($codigo  = '' , $nomenclatura = '', $documento = '', $descripcion = '', $situacion = "1"){
        $nomenclatura = trim($nomenclatura);
        $descripcion = trim($descripcion);
        $sql = "SELECT * ";
        $sql .= " FROM req_requisito, req_documento";
        $sql .= " WHERE req_documento = doc_codigo";
        $sql .= " AND doc_situacion = 1";
        if (strlen($codigo) > 0) {
            $sql .= " AND req_codigo = $codigo ";
        }
        if (strlen($nomenclatura) > 0) {
            $sql .= " AND req_titulo like '%$nomenclatura%'";
        }
        if (strlen($documento) > 0) {
            $sql .= " AND req_tipo like '%$documento%'";
        }
        if (strlen($descripcion) > 0) {
            $sql .= " AND req_entidad like '%$descripcion%'";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND req_situacion IN ($situacion)";
        }
        $sql .= " ORDER BY req_codigo ASC; ";
        $result = $this->exec_query($sql);      
        // echo $sql; 
        return $result;
    }

    function insert_requisito($codigo, $nomenclatura, $documento, $descripcion, $requisito, $comentario, $documento_soporte)
    {   
        $descripcion = trim($descripcion);
        $nomenclatura = trim($nomenclatura);
        $comentario = trim($comentario);
        $documento_soporte = trim($documento_soporte);
        $sql = "INSERT INTO req_requisito";
        $sql .= " VALUES ($codigo, '$nomenclatura', $documento, '$descripcion', '$requisito', '$comentario', '$documento_soporte', NOW(), 1);";
        //echo $sql;
        return $sql;
    }
    function modifica_requisito($codigo, $nomenclatura, $documento, $descripcion,$requisito,$comentario, $documento_soporte)
    {
        $descripcion = trim($descripcion);
        $nomenclatura = trim($nomenclatura);
        $comentaio = trim($comentario);
        $documento_soporte = trim($documento_soporte);
        $sql = "UPDATE req_requisito SET ";
        $sql .= "req_nomenclatura = '$nomenclatura', ";
        $sql .= " req_documento = $documento, ";
        $sql .= " req_descripcion = '$descripcion', ";
        $sql .= " req_tipo = '$requisito',";
        $sql .= " req_comentario = '$comentario',";
        $sql .= " req_documento_soporte = '$documento_soporte' ";
        $sql .= " WHERE req_codigo = $codigo";
        return $sql;
    }
    function cambia_situacion_requisito($codigo, $sit)
    {
        $sql = "UPDATE req_requisito SET ";
        $sql .= "req_situacion = $sit";
        $sql .= " WHERE req_codigo = $codigo";
        return $sql;
    }
    function max_requisito()
    {
        $sql = "SELECT max(req_codigo) as max ";
        $sql .= " FROM req_requisito";
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $max = $row["max"];
        }
        return $max;
    }
}
