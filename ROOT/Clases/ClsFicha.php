<?php
require_once("ClsConex.php");

class ClsFicha extends ClsConex
{
    /////////////////////////////  Asignacion de Fichas  //////////////////////////////////////

    function get_ficha_usuario($codigo = "", $ficha = "", $usuario = "", $situacion = "", $grupos = false, $tipo = "")
    {
        $sql = "SELECT * ";
        $sql .= " FROM pro_usuario_ficha, pro_ficha, pro_subtitulo, pro_titulo, seg_usuarios";
        $sql .= " WHERE fus_ficha = fic_codigo";
        $sql .= " AND fus_usuario = usu_id";
        $sql .= " AND fic_tipo = sub_codigo";
        $sql .= " AND sub_titulo = tit_codigo";
        if (strlen($codigo) > 0) {
            $sql .= " AND fus_codigo = $codigo";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND fus_usuario = $usuario";
        }
        if (strlen($ficha) > 0) {
            $sql .= " AND fus_ficha = $ficha";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND fic_situacion IN ($situacion)";
        }
        if (strlen($tipo) > 0) {
            $sql .= " AND fic_tipo = $tipo";
        }
        if ($grupos) $sql .= " GROUP BY fus_ficha";
        $sql .= " ORDER BY fic_situacion DESC ;";

        $result = $this->exec_query($sql);
    //    echo $sql;
        return $result;
    }

    function insert_ficha_usuario($codigo, $ficha, $usuario)
    {
        //--
        $usu_reg = $_SESSION["codigo"];
        $fec_reg = date("Y-m-d H:i:s");

        $sql = "INSERT INTO pro_usuario_ficha ";
        $sql .= " VALUES ($codigo,$ficha,$usuario,'$fec_reg',$usu_reg);";
        //echo $sql;
        return $sql;
    }

    function max_ficha_usuario($ficha)
    {
        $sql = "SELECT max(fus_codigo) as max ";
        $sql .= " FROM pro_usuario_ficha";
        $sql .= " WHERE fus_ficha = $ficha; ";
        $result = $this->exec_query($sql);
        if (is_array($result)) {
            foreach ($result as $row) {
                $max = $row["max"];
            }
        }
        //echo $sql;
        return $max;
    }

    function delete_ficha_usuario($ficha)
    {
        $sql = "DELETE FROM pro_usuario_ficha";
        $sql .= " WHERE fus_ficha = $ficha;";

        return $sql;
    }

    /////////////////////////////  NECESIDADES Y RESULTADOS  //////////////////////////////////////

    function get_extra($codigo, $tipo = '')
    {

        $sql = "SELECT * ";
        $sql .= " FROM pro_extra, pro_subtitulo, pro_titulo";
        $sql .= " WHERE ext_tipo = sub_codigo";
        $sql .= " AND sub_titulo = tit_codigo";
        if (strlen($codigo) > 0) {
            $sql .= " AND ext_codigo = $codigo";
        }
        if (strlen($tipo) > 0) {
            $sql .= " AND ext_tipo = $tipo";
        }
        $sql .= " ORDER BY tit_codigo ASC;";

        $result = $this->exec_query($sql);
        //echo $sql;
        return $result;
    }

    /* Situacion 1 = Edicion, 2 = Solicitando Aprobacion, 3 = Aprobado, 0 = Anulado */
    //////////////////////////////////  FICHA DE PROCESO  //////////////////////////////////////

    function get_ficha($codigo = '', $tipo = '', $usuario = '', $fini = '', $ffin =
    '', $pertenece = "", $situacion = '', $orderfecha = 'DESC')
    {
        $situacion = ($situacion == "") ? "1,2,3,4" : $situacion;
        $sql = "SELECT * ";
        $sql .= " ,(SELECT f2.fic_nombre FROM pro_ficha AS f2 WHERE f1.fic_pertenece = f2.fic_codigo) as pertenece_nombre";
        $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE usu_id = fic_usuario) as usuario_nombre";
        $sql .= " FROM pro_ficha f1, pro_subtitulo, pro_titulo";
        $sql .= " WHERE fic_tipo = sub_codigo";
        $sql .= " AND sub_titulo = tit_codigo";
        if (strlen($codigo) > 0) {
            $sql .= " AND fic_codigo = $codigo";
        }
        if (strlen($tipo) > 0) {
            $sql .= " AND fic_tipo = $tipo";
        }
        if (strlen($pertenece) > 0) {
            $sql .= " AND fic_pertenece IN($pertenece)";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND fic_usuario = $usuario";
        }
        if ($fini != "" && $ffin != "") {
            $fini = $this->regresa_fecha($fini);
            $ffin = $this->regresa_fecha($ffin);
            $sql .= " AND fic_fecha_registro BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND fic_situacion IN($situacion)";
        }
      //$sql .= " ORDER BY fic_codigo ASC, fic_tipo ASC, fic_fecha_registro $orderfecha;";
      $sql .= " ORDER BY fic_situacion ASC, fic_fecha_registro $orderfecha;";
      $result = $this->exec_query($sql);
      //echo "<br><br>".$sql;
        return $result;
    }

    function count_ficha($codigo = '', $tipo = '', $usuario = '', $fini = '', $ffin =
    '', $situacion = '1,2,3', $nivel = '0')
    {

        $sql = "SELECT COUNT(*) as total";
        $sql .= " FROM pro_ficha";
        $sql .= " WHERE 1 = 1";
        if (strlen($codigo) > 0) {
            $sql .= " AND fic_codigo = $codigo";
        }
        if (strlen($tipo) > 0) {
            $sql .= " AND fic_tipo = $tipo";
        }
        if (strlen($nivel) > 0) {
            $sql .= " AND fic_nivel = $nivel";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND fic_usuario = $usuario";
        }
        if ($fini != "" && $ffin != "") {
            $fini = $this->regresa_fecha($fini);
            $ffin = $this->regresa_fecha($ffin);
            $sql .= " AND fic_fecha_registro BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND fic_situacion IN($situacion)";
        }
        //echo $sql;
        $result = $this->exec_query($sql);
        if(is_array($result)){
            foreach ($result as $row) {
                $total = $row['total'];
            }
        }
        return $total;
    }

    function insert_ficha($codigo, $nombre, $tipo, $analisis, $objetivo, $medida, $usuario = '', $pertenece = '', $desde, $hasta)
    {
        $pertenece = ($pertenece == "") ? 0 : $pertenece;
        $usuario = ($usuario == '') ? $_SESSION["codigo"] : $usuario;
        $fsis = date("Y-m-d H:i:s");
        $desde = regresa_fecha($desde);
        $hasta = regresa_fecha($hasta);

        $sql = "INSERT INTO pro_ficha";
        $sql .= " VALUES ($codigo,'$nombre','$tipo','$analisis','$objetivo','$medida','$usuario','$fsis','$fsis',0,'$fsis',$pertenece,'$desde','$hasta',1);";
        //echo $sql;
        return $sql;
    }

    function update_ficha($codigo, $campo, $valor, $usuario = '')
    {
        $usuario = ($usuario == '') ? $_SESSION["codigo"] : $usuario;
        $fsis = date("Y-m-d H:i:s");

        $sql = "UPDATE pro_ficha SET $campo = '$valor', ";
        $sql .= "fic_fecha_aprobacion = '$fsis', ";
        $sql .= "fic_aprueba = '$usuario'";
        $sql .= " WHERE fic_codigo = $codigo; ";

        return $sql;
    }

    function aprobacion_ficha($codigo, $usuario = '')
    {
        $usuario = ($usuario == '') ? $_SESSION["codigo"] : $usuario;
        $fsis = date("Y-m-d H:i:s");

        $sql = "UPDATE pro_ficha SET ";
        $sql .= "fic_situacion = 3,";
        $sql .= "fic_fecha_aprobacion = '$fsis',";
        $sql .= "fic_aprueba = '$usuario'";

        $sql .= " WHERE fic_codigo = $codigo; ";
        //echo $sql;
        return $sql;
    }

    function cambia_situacion_ficha($codigo, $situacion)
    {

        $sql = "UPDATE pro_ficha SET fic_situacion = $situacion";
        $sql .= " WHERE fic_codigo = $codigo; ";

        return $sql;
    }

    function max_ficha()
    {
        $sql = "SELECT max(fic_codigo) as max ";
        $sql .= " FROM pro_ficha";
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $max = $row["max"];
        }
        //echo $sql;
        return $max;
    }


    /////////////////////////////  FICHA DE SUBPROCESO  //////////////////////////////////////

    function get_subproceso($codigo, $tipo = '', $usuario = '', $fini = '', $ffin = '', $situacion = '', $orderfecha = 'DESC')
    {

        $sql = "SELECT *, ";
        $sql .= " (SELECT usu_nombre FROM seg_usuarios WHERE usu_id = fic_usuario) as usuario_nombre";
        $sql .= " FROM pro_ficha";
        $sql .= " WHERE 1 = 1";
        if (strlen($codigo) > 0) {
            $sql .= " AND fic_codigo = $codigo";
        }
        if (strlen($tipo) > 0) {
            $sql .= " AND fic_tipo = '$tipo'";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND fic_usuario = $usuario";
        }
        if ($fini != "" && $ffin != "") {
            $fini = $this->regresa_fecha($fini);
            $ffin = $this->regresa_fecha($ffin);
            $sql .= " AND fic_fecha_registro BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND fic_situacion IN($situacion)";
        }
        $sql .= " ORDER BY fic_tipo ASC, fic_situacion ASC, fic_fecha_registro $orderfecha";

        $result = $this->exec_query($sql);
        //echo $sql;
        return $result;
    }

    function count_subproceso($codigo, $usuario = '', $fini = '', $ffin = '', $situacion = '', $orderfecha = 'DESC')
    {

        $sql = "SELECT COUNT(*) as total";
        $sql .= " FROM pro_ficha";
        $sql .= " WHERE 1 = 1";
        if (strlen($codigo) > 0) {
            $sql .= " AND fic_codigo = $codigo";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND fic_usuario = $usuario";
        }
        if ($fini != "" && $ffin != "") {
            $fini = $this->regresa_fecha($fini);
            $ffin = $this->regresa_fecha($ffin);
            $sql .= " AND fic_fecha_registro BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND fic_situacion IN($situacion)";
        }
        //echo $sql;
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $total = $row['total'];
        }
        return $total;
    }

    function insert_subproceso($codigo, $nombre, $tipo, $analisis, $objetivo, $medida, $usuario = '')
    {
        $usuario = ($usuario == '') ? $_SESSION["codigo"] : $usuario;
        $fsis = date("Y-m-d H:i:s");

        $sql = "INSERT INTO pro_ficha";
        $sql .= " VALUES ($codigo,'$nombre','$tipo','$analisis','$objetivo','$medida','$usuario','$fsis','$fsis',0,'$fsis',1);";
        //echo $sql;
        return $sql;
    }

    function update_subproceso($codigo, $campo, $valor, $usuario = '')
    {
        $usuario = ($usuario == '') ? $_SESSION["codigo"] : $usuario;
        $fsis = date("Y-m-d H:i:s");

        $sql = "UPDATE pro_ficha SET $campo = '$valor', ";
        $sql .= "fic_fecha_aprobacion = '$fsis', ";
        $sql .= "fic_aprueba = '$usuario'";
        $sql .= " WHERE fic_codigo = $codigo; ";

        return $sql;
    }

    function aprobacion_subproceso($codigo, $usuario = '')
    {
        $usuario = ($usuario == '') ? $_SESSION["codigo"] : $usuario;
        $fsis = date("Y-m-d H:i:s");

        $sql = "UPDATE pro_ficha SET ";
        $sql .= "fic_situacion = 3,";
        $sql .= "fic_fecha_aprobacion = '$fsis',";
        $sql .= "fic_aprueba = '$usuario'";

        $sql .= " WHERE fic_codigo = $codigo; ";
        //echo $sql;
        return $sql;
    }

    function cambia_situacion_subproceso($codigo, $situacion)
    {

        $sql = "UPDATE pro_ficha SET fic_situacion = $situacion";
        $sql .= " WHERE fic_codigo = $codigo; ";

        return $sql;
    }

    function max_proceso()
    {
        $sql = "SELECT max(fic_codigo) as max ";
        $sql .= " FROM pro_ficha";
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $max = $row["max"];
        }
        //echo $sql;
        return $max;
    }

    /////////////////////////////  FODA  //////////////////////////////////////
    function get_foda($codigo, $proceso, $tipo = '', $sistema = '')
    {

        $sql = "SELECT * ";
        $sql .= " FROM pro_foda, pro_sistema ";
        $sql .= " WHERE fod_sistema = sis_codigo";
        $sql .= " AND fod_situacion = 1";
        if (strlen($codigo) > 0) {
            $sql .= " AND fod_codigo = $codigo";
        }
        if (strlen($proceso) > 0) {
            $sql .= " AND fod_proceso = $proceso";
        }
        if (strlen($tipo) > 0) {
            $sql .= " AND fod_tipo = $tipo";
        }
        if (strlen($sistema) > 0) {
            $sql .= " AND fod_sistema = $sistema";
        }
        $sql .= " ORDER BY fod_proceso ASC, fod_tipo ASC, fod_codigo ASC";

        $result = $this->exec_query($sql);
        // echo $sql;
        return $result;
    }

    function count_foda($codigo, $proceso, $tipo = '', $sistema = '')
    {

        $sql = "SELECT COUNT(*) as total";
        $sql .= " FROM pro_foda, pro_sistema ";
        $sql .= " WHERE fod_sistema = sis_codigo";
        $sql .= " AND fod_situacion = 1";
        if (strlen($codigo) > 0) {
            $sql .= " AND fod_codigo = $codigo";
        }
        if (strlen($proceso) > 0) {
            $sql .= " AND fod_proceso = $proceso";
        }
        if (strlen($tipo) > 0) {
            $sql .= " AND fod_tipo = $tipo";
        }
        if (strlen($sistema) > 0) {
            $sql .= " AND fod_sistema = $sistema";
        }
        $sql .= " ORDER BY fod_proceso ASC, fod_tipo ASC, fod_codigo ASC";

        //echo $sql;
        $total = 0;
        $result = $this->exec_query($sql);
        if (is_array($result)) {
            foreach ($result as $row) {
                $total = $row['total'];
            }
        }

        return $total;
    }

    function insert_foda($codigo, $proceso, $tipo, $sistema, $descripcion, $peso = '0')
    {
        $descripcion = trim($descripcion);
        $tipo = trim($tipo);
        $sistema = trim($sistema);
        $usuario = $_SESSION["codigo"];
        $fsis = date("Y-m-d H:i:s");

        $sql = "INSERT INTO pro_foda";
        $sql .= " VALUES ($codigo,$proceso,$tipo,$sistema,'$descripcion','$peso','$usuario','$fsis',1)";
        $sql .= " ON DUPLICATE KEY UPDATE fod_sistema = '$sistema', fod_descripcion = '$descripcion', fod_peso = '$peso', fod_usuario = '$usuario', fod_fecha_registro = '$fsis'; ";
        //echo $sql;
        return $sql;
    }

    function cambia_situacion_foda($codigo, $proceso, $situacion)
    {

        $sql = "UPDATE pro_foda SET fod_situacion = $situacion";
        $sql .= " WHERE fod_codigo = $codigo ";
        $sql .= " AND fod_proceso = $proceso; ";

        return $sql;
    }

    function max_foda($proceso)
    {
        $sql = "SELECT max(fod_codigo) as max ";
        $sql .= " FROM pro_foda";
        $sql .= " WHERE fod_proceso = $proceso;";
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $max = $row["max"];
        }
        //echo $sql;
        return $max;
    }


    /////////////////////////////  ELEMENTOS  //////////////////////////////////////
    function get_elemento($codigo, $proceso, $tipo = '')
    {

        $sql = "SELECT * ";
        $sql .= " FROM pro_elementos";
        $sql .= " WHERE ele_situacion = 1";
        if (strlen($codigo) > 0) {
            $sql .= " AND ele_codigo = $codigo";
        }
        if (strlen($proceso) > 0) {
            $sql .= " AND ele_proceso = $proceso";
        }
        if (strlen($tipo) > 0) {
            $sql .= " AND ele_tipo = $tipo";
        }
        $sql .= " ORDER BY ele_proceso ASC, ele_tipo ASC, ele_codigo ASC";

        $result = $this->exec_query($sql);
        //echo $sql;
        return $result;
    }

    function insert_elemento($codigo, $proceso, $tipo, $titulo, $descripcion, $usuario = '')
    {
        $descripcion = trim($descripcion);
        $tipo = trim($tipo);
        $titulo = trim($titulo);
        $usuario = ($usuario == '') ? $_SESSION["codigo"] : $usuario;
        $fsis = date("Y-m-d H:i:s");

        $sql = "INSERT INTO pro_elementos";
        $sql .= " VALUES ($codigo,$proceso,$tipo,'$titulo','$descripcion','$usuario','$fsis',1)";
        $sql .= " ON DUPLICATE KEY UPDATE ele_titulo = '$titulo', ele_descripcion = '$descripcion', ele_usuario = '$usuario', ele_fecha_registro = '$fsis'; ";
        //echo $sql;
        return $sql;
    }

    function cambia_situacion_elemento($codigo, $proceso, $situacion)
    {

        $sql = "UPDATE pro_elementos SET ele_situacion = $situacion";
        $sql .= " WHERE ele_codigo = $codigo ";
        $sql .= " AND ele_proceso = $proceso; ";

        return $sql;
    }

    function max_elemento($proceso)
    {
        $sql = "SELECT max(ele_codigo) as max ";
        $sql .= " FROM pro_elementos";
        $sql .= " WHERE ele_proceso = $proceso;";
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $max = $row["max"];
        }
        //echo $sql;
        return $max;
    }


    /////////////////////////////  REQUISITOS LEGALES  //////////////////////////////////////
    function get_requisitos_legales($codigo, $proceso)
    {

        $sql = "SELECT * ";
        $sql .= " FROM pro_requisitos_legales";
        $sql .= " WHERE req_situacion = 1";
        if (strlen($codigo) > 0) {
            $sql .= " AND req_codigo = $codigo";
        }
        if (strlen($proceso) > 0) {
            $sql .= " AND req_proceso = $proceso";
        }
        $sql .= " ORDER BY req_proceso ASC, req_codigo ASC";

        $result = $this->exec_query($sql);
        //echo $sql;
        return $result;
    }


    function insert_requisitos_legales($codigo, $proceso, $descripcion, $usuario = '')
    {
        $descripcion = trim($descripcion);
        $usuario = ($usuario == '') ? $_SESSION["codigo"] : $usuario;
        $fsis = date("Y-m-d H:i:s");

        $sql = "INSERT INTO pro_requisitos_legales";
        $sql .= " VALUES ($codigo,$proceso,'$descripcion','$usuario','$fsis',1)";
        $sql .= " ON DUPLICATE KEY UPDATE req_descripcion = '$descripcion', req_usuario = '$usuario', req_fecha_registro = '$fsis'; ";
        //echo $sql;
        return $sql;
    }


    function cambia_situacion_requisitos_legales($codigo, $proceso, $situacion)
    {

        $sql = "UPDATE pro_requisitos_legales SET req_situacion = $situacion";
        $sql .= " WHERE req_codigo = $codigo ";
        $sql .= " AND req_proceso = $proceso; ";

        return $sql;
    }

    function max_requisitos_legales($proceso)
    {
        $sql = "SELECT max(req_codigo) as max ";
        $sql .= " FROM pro_requisitos_legales";
        $sql .= " WHERE req_proceso = $proceso;";
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $max = $row["max"];
        }
        //echo $sql;
        return $max;
    }


    /////////////////////////////  ASPECTOS AMBIENTALES  //////////////////////////////////////
    function get_aspectos_ambientales($codigo, $proceso)
    {

        $sql = "SELECT * ";
        $sql .= " FROM pro_aspectos_ambientales";
        $sql .= " WHERE asp_situacion = 1";
        if (strlen($codigo) > 0) {
            $sql .= " AND asp_codigo = $codigo";
        }
        if (strlen($proceso) > 0) {
            $sql .= " AND asp_proceso = $proceso";
        }
        $sql .= " ORDER BY asp_proceso ASC, asp_codigo ASC";

        $result = $this->exec_query($sql);
        //echo $sql;
        return $result;
    }


    function insert_aspectos_ambientales($codigo, $proceso, $descripcion, $usuario = '')
    {
        $descripcion = trim($descripcion);
        $usuario = ($usuario == '') ? $_SESSION["codigo"] : $usuario;
        $fsis = date("Y-m-d H:i:s");

        $sql = "INSERT INTO pro_aspectos_ambientales";
        $sql .= " VALUES ($codigo,$proceso,'$descripcion','$usuario','$fsis',1)";
        $sql .= " ON DUPLICATE KEY UPDATE asp_descripcion = '$descripcion', asp_usuario = '$usuario', asp_fecha_registro = '$fsis'; ";
        //echo $sql;
        return $sql;
    }


    function cambia_situacion_aspectos_ambientales($codigo, $proceso, $situacion)
    {

        $sql = "UPDATE pro_aspectos_ambientales SET asp_situacion = $situacion";
        $sql .= " WHERE asp_codigo = $codigo ";
        $sql .= " AND asp_proceso = $proceso; ";

        return $sql;
    }

    function max_aspectos_ambientales($proceso)
    {
        $sql = "SELECT max(asp_codigo) as max ";
        $sql .= " FROM pro_aspectos_ambientales";
        $sql .= " WHERE asp_proceso = $proceso;";
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $max = $row["max"];
        }
        //echo $sql;
        return $max;
    }


    /////////////////////////////  RESPONSABILIDAD SOCIAL  //////////////////////////////////////
    function get_responsabilidad_social($codigo, $proceso)
    {

        $sql = "SELECT * ";
        $sql .= " FROM pro_responsabilidad_social";
        $sql .= " WHERE resp_situacion = 1";
        if (strlen($codigo) > 0) {
            $sql .= " AND resp_codigo = $codigo";
        }
        if (strlen($proceso) > 0) {
            $sql .= " AND resp_proceso = $proceso";
        }
        $sql .= " ORDER BY resp_proceso ASC, resp_codigo ASC";

        $result = $this->exec_query($sql);
        //echo $sql;
        return $result;
    }


    function insert_responsabilidad_social($codigo, $proceso, $descripcion, $usuario = '')
    {
        $descripcion = trim($descripcion);
        $usuario = ($usuario == '') ? $_SESSION["codigo"] : $usuario;
        $fsis = date("Y-m-d H:i:s");

        $sql = "INSERT INTO pro_responsabilidad_social";
        $sql .= " VALUES ($codigo,$proceso,'$descripcion','$usuario','$fsis',1)";
        $sql .= " ON DUPLICATE KEY UPDATE resp_descripcion = '$descripcion', resp_usuario = '$usuario', resp_fecha_registro = '$fsis'; ";
        //echo $sql;
        return $sql;
    }


    function cambia_situacion_responsabilidad_social($codigo, $proceso, $situacion)
    {

        $sql = "UPDATE pro_responsabilidad_social SET resp_situacion = $situacion";
        $sql .= " WHERE resp_codigo = $codigo ";
        $sql .= " AND resp_proceso = $proceso; ";

        return $sql;
    }

    function max_responsabilidad_social($proceso)
    {
        $sql = "SELECT max(resp_codigo) as max ";
        $sql .= " FROM pro_responsabilidad_social";
        $sql .= " WHERE resp_proceso = $proceso;";
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $max = $row["max"];
        }
        //echo $sql;
        return $max;
    }


    /////////////////////////////  PUNTOS DE NORMA  //////////////////////////////////////
    function get_puntos_norma($codigo, $proceso)
    {

        $sql = "SELECT * ";
        $sql .= " FROM pro_puntos_norma";
        $sql .= " WHERE nor_situacion = 1";
        if (strlen($codigo) > 0) {
            $sql .= " AND nor_codigo = $codigo";
        }
        if (strlen($proceso) > 0) {
            $sql .= " AND nor_proceso = $proceso";
        }
        $sql .= " ORDER BY nor_proceso ASC, nor_codigo ASC";

        $result = $this->exec_query($sql);
        //echo $sql;
        return $result;
    }


    function insert_puntos_norma($codigo, $proceso, $descripcion, $usuario = '')
    {
        $descripcion = trim($descripcion);
        $usuario = ($usuario == '') ? $_SESSION["codigo"] : $usuario;
        $fsis = date("Y-m-d H:i:s");

        $sql = "INSERT INTO pro_puntos_norma";
        $sql .= " VALUES ($codigo,$proceso,'$descripcion','$usuario','$fsis',1)";
        $sql .= " ON DUPLICATE KEY UPDATE nor_descripcion = '$descripcion', nor_usuario = '$usuario', nor_fecha_registro = '$fsis'; ";
        //echo $sql;
        return $sql;
    }


    function cambia_situacion_puntos_norma($codigo, $proceso, $situacion)
    {

        $sql = "UPDATE pro_puntos_norma SET nor_situacion = $situacion";
        $sql .= " WHERE nor_codigo = $codigo ";
        $sql .= " AND nor_proceso = $proceso; ";

        return $sql;
    }

    function max_puntos_norma($proceso)
    {
        $sql = "SELECT max(nor_codigo) as max ";
        $sql .= " FROM pro_puntos_norma";
        $sql .= " WHERE nor_proceso = $proceso;";
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $max = $row["max"];
        }
        //echo $sql;
        return $max;
    }
    function fichas_sin_aprobar(){
        $sql = "SELECT COUNT(1) AS 'Fichas_Sin_Aprobar' ";
        $sql.= "FROM pro_ficha WHERE fic_situacion = 2;";
        $result = $this->exec_query($sql);
        return $result;
    }

    function fichas_actualizacion($usuario){    
        $sql = " SELECT";
        $sql .= " COUNT(*) AS 'Fichas_Actualizacion'";
        $sql .= " FROM pro_usuario_ficha AS USUFIC";
        $sql .= " INNER JOIN pro_ficha FIC";
        $sql .= " ON USUFIC.fus_ficha = FIC.fic_codigo";
        $sql .= " WHERE USUFIC.fus_usuario = $usuario";
        $sql .= " AND FIC.fic_situacion = 4";
        $result = $this->exec_query($sql);
        return $result;
    }
}
