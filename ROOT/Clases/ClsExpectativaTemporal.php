<?php
require_once("ClsConex.php");

class ClsNecesidadxx extends ClsConex
{
    /* Situacion 1 = ACTIVO, 2 = INACTIVO */
    function get_Necesidad($codigo  = '' , $tipo, $nombre = '', $descripcion = ''){
        $nombre = trim($nombre);
        $descripcion = utf8_encode($descripcion);
        $tipo = trim($tipo);
        $sql = "SELECT * ";
        $sql .= " FROM pro_extra ";
        //$sql .= " WHERE ext_tipo IN(1,2) "; 
        if (strlen($tipo) > 0) {
            $sql .= "WHERE ext_tipo IN($tipo)";
        }
        if (strlen($codigo) > 0) {
            $sql .= " AND ext_codigo = $codigo ";
        }
        if (strlen($descripcion) > 0) {
            $sql .= " AND ext_descripcion like '%$descripcion%'";
        }
        $sql .= " ORDER BY ext_codigo ASC; ";
        $result = $this->exec_query($sql);  
  //  echo $sql;    
        return $result;
    }

    function insert_Necesidad($codigo, $tipo, $nombre, $descripcion)
    { 
        $nombre = trim($nombre);
        $descripcion = utf8_encode($descripcion);
        $tipo = trim($tipo);
        $sql = "INSERT INTO pro_extra";
        $sql .= " VALUES ($codigo, $tipo, '$nombre', '$descripcion');";
       // echo $sql;
        return $sql;
    }
    function modifica_Necesidad($codigo, $tipo, $nombre, $descripcion)
    {
        $nombre = trim($nombre);
        $descripcion = utf8_encode($descripcion);
        $tipo = trim($tipo);
        $sql = "UPDATE pro_extra SET ";
        $sql .= "ext_tipo = $tipo, ";
        $sql .= "ext_nombre = '$nombre', ";
        $sql .= "ext_descripcion = '$descripcion' ";
        $sql .= "WHERE ext_codigo = $codigo";
        //ECHO $sql;
        return $sql;
    }
    
    function cambia_situacion_Necesidad($codigo, $sit)
    {
        $sql = "UPDATE pro_extra SET ";
        $sql .= "ext_tipo = $sit";
        $sql .= " WHERE ext_codigo = $codigo";
        return $sql;
    }

    function max_Necesidad()
    {
        $sql = "SELECT max(ext_codigo) as max ";
        $sql .= " FROM pro_extra";
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $max = $row["max"];
        }
        return $max;
    }
}
