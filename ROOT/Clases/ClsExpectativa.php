<?php
require_once("ClsConex.php");

class ClsExpectativa extends ClsConex
{
    function insert_expectativa($codigo, $nombre, $tipo, $descripcion)
    {
        $codigo = trim($codigo);
        $nombre = utf8_encode($nombre);
        $tipo = trim($tipo);
        $descripcion = utf8_encode($descripcion);
        $usu = trim($_SESSION["codigo"]);
      
        $sql = "INSERT INTO pro_expectativa (exp_codigo, exp_nombre, exp_tipo, exp_descripcion, exp_usuario_crea, exp_usuario_modifica, exp_situacion) ";
        $sql .= " VALUES ($codigo, '$nombre', $tipo, '$descripcion' , $usu, $usu, 1)";
        // echo $sql;

        return $sql;
    }



    /* Situacion 1 = ACTIVO, 0 = INACTIVO, n-1 = numero de actualizaciones */
    function get_expectativa($codigo = "")
    {
        $codigo = trim($codigo);
              
        $sql  = "SELECT exp_codigo,  exp_nombre, exp_tipo, exp_descripcion , exp_situacion";
        $sql .= " FROM pro_expectativa ";
        $sql .= " WHERE 1=1 ";
       if (strlen($codigo) > 0) {
            $sql .= " AND exp_codigo=$codigo";
        }
        $sql .= " AND exp_situacion>0 " ;
        $sql .= " ORDER BY exp_codigo ASC; ";
        $result = $this->exec_query($sql);
     //   echo $sql;
         
        return $result;
     

    }


    function modifica_expectativa($codigo, $tipo, $nombre, $descripcion, $situacion)
    {
        $codigo = trim($codigo);
        $nombre = utf8_encode($nombre);
        $descripcion = utf8_encode($descripcion);
        $tipo = trim($tipo);
        $situacion = trim($situacion);
        $usu = trim($_SESSION["codigo"]);
        $sql = "UPDATE pro_expectativa SET ";
        $sql .= "exp_tipo = $tipo, ";
        $sql .= "exp_nombre = '$nombre', ";
        $sql .= "exp_descripcion = '$descripcion', ";
        $sql .= "exp_usuario_modifica = $usu, ";
        $sql .= "exp_situacion = $situacion ";
        $sql .= "WHERE exp_codigo = $codigo ";
        return $sql;
    }

    function cambia_situacion_expectativa($codigo, $sit)
    {
        $sql = "UPDATE pro_expectativa SET ";
        $sql .= "exp_situacion = $sit";
        $sql .= " WHERE exp_codigo = $codigo";
        return $sql;
    }

    function max_expectativa()
    {
        $sql = "SELECT max(exp_codigo) as max ";
        $sql .= " FROM pro_expectativa";
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $max = $row["max"];
        }
        return $max;
    }
}
