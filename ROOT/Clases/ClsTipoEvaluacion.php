<?php
require_once("ClsConex.php");

class ClsTipoEvaluacion extends ClsConex
{
    /* Situacion 1 = ACTIVO, 2 = INACTIVO */
    function get_tipo_evaluacion($codigo,$requisito,$nombre = '', $cumple = '',$aspecto = '', $componente='', $frecuencia= '', $situacion = 1){
        $nombre = trim($nombre);
        $aspecto = trim($aspecto);
        $componente = trim($componente);

        $sql = "SELECT * ";
        $sql .= " FROM req_evaluacion, req_requisito, req_documento ";
        $sql .= " WHERE eva_requisito = req_codigo";
        $sql .= " AND req_documento = doc_codigo";
        $sql .= " AND req_situacion = 1";
        if (strlen($codigo) > 0) {
            $sql .= " AND eva_codigo = $codigo ";
        }
        if(strlen($requisito) > 0){
            $sql .= " AND req_codigo = $requisito";
        }
        if (strlen($nombre) > 0) {
            $sql .= " AND eva_nombre like '%$nombre%'";
        }
        if (strlen($cumple) > 0) {
            $sql .= " AND eva_cumple = $cumple";
        }
        if (strlen($aspecto) > 0) {
            $sql .= " AND eva_aspecto like '%$aspecto%'";
        }
        if (strlen($componente) > 0) {
            $sql .= " AND eva_componente like '%$componente%'";
        }
        if (strlen($frecuencia) > 0) {
            $sql .= " AND eva_frecuencia = $frecuencia'";
        }
        if (strlen($situacion) > 0) {
           $sql .= " AND eva_situacion = 1";
        }
        $sql .= " ORDER BY eva_codigo ASC; ";
        $result = $this->exec_query($sql);      
      //   echo $sql; 
        //var_dump($this->conn);
        //die();
        return $result;

    }

    function insert_tipo_evaluacion($codigo,$requisito,$nombre, $cumple,$aspecto, $componente, $frecuencia,$fechaReevaluacion, $eva_requisito)
    {
        $requisito = trim($requisito);
        $nombre = trim($nombre);
        $cumple = trim($cumple);
        $aspecto = trim($aspecto);
        $componente = trim($componente);
        $fechaReevaluacion = regresa_fecha($fechaReevaluacion);
        $sql = "INSERT INTO req_evaluacion";
        $sql .= " VALUES ($codigo, $requisito, '$nombre', $cumple, '$aspecto', '$componente', $frecuencia, '$fechaReevaluacion', $eva_requisito, 1, NULL);";
       //echo $sql;
      // echo mysqli_error($this->conn);
        return $sql;
    }
    function modifica_tipo_evaluacion($codigo,$requisito,$nombre, $cumple,$aspecto, $componente, $frecuencia,$fechaReevaluacion,$eva_requisito)
    {
        $codigo = trim($codigo);
        $requisito = trim($requisito);
        $nombre = trim($nombre);
        $cumple = trim($cumple);
        $aspecto = trim($aspecto);
        $componente = trim($componente);
        $fechaReevaluacion = regresa_fecha($fechaReevaluacion);
        $sql = "UPDATE req_evaluacion SET ";
        $sql .= "eva_requisito = $requisito, ";
        $sql .= " eva_nombre = '$nombre', ";
        $sql .= " eva_cumple = $cumple, ";
        $sql .= " eva_aspecto = '$aspecto', ";
        $sql .= " eva_componente = '$componente', ";
        $sql .= " eva_frecuencia = $frecuencia, ";
        $sql .= " eva_fecha_reevaluacion = '$fechaReevaluacion', ";
        $sql .= " eva_requisto = $eva_requisito ";
        $sql .= " WHERE eva_codigo = $codigo";
     //   echo $sql;
        return $sql;
    }
    function cambia_situacion_tipo_evaluacion($codigo, $sit)
    {
        $sql = "UPDATE req_evaluacion SET ";
        $sql .= "eva_situacion = $sit";
        $sql .= " WHERE eva_codigo = $codigo";
        return $sql;
    }
    function max_tipo_evaluacion()
    {
        $sql = "SELECT max(eva_codigo) as max ";
        $sql .= " FROM req_evaluacion";
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $max = $row["max"];
        }
        return $max;
    }

    function update_tipo_evaluacion($codigo, $campo, $valor)
    {
       $sql = "UPDATE req_evaluacion";
       $sql .= " SET $campo = $valor ";
       $sql .= " WHERE eva_codigo = $codigo; ";
    //echo $sql."<br>";
       return $sql;
    }



}


