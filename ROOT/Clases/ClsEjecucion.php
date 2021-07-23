<?php
require_once("ClsConex.php");

class ClsEjecucion extends ClsConex
{
    /* Situacion 1 = Abierto, 2 = Cerrado, 0 = Anulado */

    /////////////////////////////  EJECUCION DE AUDITORIAS  //////////////////////////////////////

    function get_ejecucion($codigo, $auditoria = '', $usuario = '', $sede = '', $departamento = '', $categoria = '', $fini = '', $ffin = '', $situacion = '', $orderfecha = 'DESC')
    {
        $sede = ($sede == "") ? $_SESSION["sedes_in"] : $sede;

        $sql = "SELECT *, ";
        $sql .= " (SELECT usu_nombre FROM seg_usuarios WHERE usu_id = eje_usuario) as usuario_nombre,";
        $sql .= " (SELECT dm_desc FROM mast_mundep WHERE dm_codigo = sed_municipio) as sede_municipio,";
        $sql .= " (SELECT act_ejecucion FROM aud_acta WHERE act_ejecucion = eje_codigo AND act_situacion != 0) as eje_acta,";
        $sql .= " (SELECT pla_ejecucion FROM aud_plan WHERE pla_ejecucion = eje_codigo AND pla_situacion != 0) as eje_plan";
        $sql .= " FROM aud_ejecucion, aud_auditoria, aud_programacion, aud_categoria, sis_departamento, sis_sede";
        $sql .= " WHERE eje_auditoria = audit_codigo";
        $sql .= " AND eje_programacion = pro_codigo";
        $sql .= " AND audit_categoria = cat_codigo";
        $sql .= " AND pro_sede = sed_codigo";
        $sql .= " AND pro_departamento = dep_codigo";
        if (strlen($codigo) > 0) {
            $sql .= " AND eje_codigo = $codigo";
        }
        if (strlen($auditoria) > 0) {
            $sql .= " AND audit_codigo = $auditoria";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND eje_usuario = $usuario";
        }
        if (strlen($sede) > 0) {
            $sql .= " AND pro_sede IN($sede)";
        }
        if (strlen($departamento) > 0) {
            $sql .= " AND pro_departamento = $departamento";
        }
        if (strlen($categoria) > 0) {
            $sql .= " AND audit_categoria IN($categoria)";
        }
        if ($fini != "" && $ffin != "") {
            $fini = $this->regresa_fecha($fini);
            $ffin = $this->regresa_fecha($ffin);
            $sql .= " AND eje_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND eje_situacion IN($situacion)";
        }
        $sql .= " ORDER BY eje_fecha_inicio $orderfecha, audit_categoria ASC, pro_sede ASC, pro_departamento ASC, audit_codigo ASC";

        $result = $this->exec_query($sql);
        //echo $sql."<br>";
        return $result;
    }

    function count_ejecucion($codigo, $auditoria = '', $usuario = '', $sede = '', $departamento = '', $categoria = '', $fini = '', $ffin = '', $situacion = '', $orderfecha = 'DESC')
    {
        $sede = ($sede == "") ? $_SESSION["sedes_in"] : $sede;

        $sql = "SELECT COUNT(*) as total";
        $sql .= " FROM aud_ejecucion, aud_auditoria, aud_programacion, aud_categoria, sis_departamento, sis_sede";
        $sql .= " WHERE eje_auditoria = audit_codigo";
        $sql .= " AND eje_programacion = pro_codigo";
        $sql .= " AND audit_categoria = cat_codigo";
        $sql .= " AND pro_sede = sed_codigo";
        $sql .= " AND pro_departamento = dep_codigo";
        if (strlen($codigo) > 0) {
            $sql .= " AND eje_codigo = $codigo";
        }
        if (strlen($auditoria) > 0) {
            $sql .= " AND audit_codigo = $auditoria";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND eje_usuario = $usuario";
        }
        if (strlen($sede) > 0) {
            $sql .= " AND pro_sede IN($sede)";
        }
        if (strlen($departamento) > 0) {
            $sql .= " AND pro_departamento = $departamento";
        }
        if (strlen($categoria) > 0) {
            $sql .= " AND audit_categoria IN($categoria)";
        }
        if ($fini != "" && $ffin != "") {
            $fini = $this->regresa_fecha($fini);
            $ffin = $this->regresa_fecha($ffin);
            $sql .= " AND eje_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND eje_situacion IN($situacion)";
        }
        //echo $sql;
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $total = $row['total'];
        }
        return $total;
    }

    function get_sede_compara($fini = '', $ffin = '', $hora = '', $categoria = '', $sede = '', $departamento = '')
    {
        $sede = ($sede == "") ? $_SESSION["sedes_in"] : $sede;
        $fini = $this->regresa_fecha($fini);
        $ffin = $this->regresa_fecha($ffin);
        $hora = ($hora == "") ? date("H:i:s") : $hora;

        $sql = "SELECT *, ";
        //// SI
        $sql .= "(SELECT COUNT(resp_observacion) FROM aud_respuestas,aud_ejecucion,aud_auditoria,aud_programacion";
        $sql .= " WHERE resp_ejecucion = eje_codigo";
        $sql .= " AND resp_auditoria = eje_auditoria";
        $sql .= " AND eje_auditoria = audit_codigo";
        $sql .= " AND eje_programacion = pro_codigo";
        $sql .= " AND pro_sede = sed_codigo";
        $sql .= " AND resp_observacion = 1"; // SI
        if (strlen($categoria) > 0) {
            $sql .= " AND audit_categoria IN($categoria)";
        }
        if (strlen($departamento) > 0) {
            $sql .= " AND pro_departamento = $departamento";
        }
        $sql .= " AND eje_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin $hora' AND eje_situacion IN(1,2)) as respuestas_si,";
        //SI
        //// NO
        $sql .= "(SELECT COUNT(resp_observacion) FROM aud_respuestas,aud_ejecucion,aud_auditoria,aud_programacion";
        $sql .= " WHERE resp_ejecucion = eje_codigo";
        $sql .= " AND resp_auditoria = eje_auditoria";
        $sql .= " AND eje_auditoria = audit_codigo";
        $sql .= " AND eje_programacion = pro_codigo";
        $sql .= " AND pro_sede = sed_codigo";
        $sql .= " AND resp_observacion = 2"; // NO
        if (strlen($categoria) > 0) {
            $sql .= " AND audit_categoria IN($categoria)";
        }
        if (strlen($departamento) > 0) {
            $sql .= " AND pro_departamento = $departamento";
        }
        $sql .= " AND eje_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin $hora' AND eje_situacion IN(1,2)) as respuestas_no,";
        //NO
        //// NA
        $sql .= "(SELECT COUNT(resp_observacion) FROM aud_respuestas,aud_ejecucion,aud_auditoria,aud_programacion";
        $sql .= " WHERE resp_ejecucion = eje_codigo";
        $sql .= " AND resp_auditoria = eje_auditoria";
        $sql .= " AND eje_auditoria = audit_codigo";
        $sql .= " AND eje_programacion = pro_codigo";
        $sql .= " AND pro_sede = sed_codigo";
        $sql .= " AND resp_observacion = 3"; // NA
        if (strlen($categoria) > 0) {
            $sql .= " AND audit_categoria IN($categoria)";
        }
        if (strlen($departamento) > 0) {
            $sql .= " AND pro_departamento = $departamento";
        }
        $sql .= " AND eje_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin $hora' AND eje_situacion IN(1,2)) as respuestas_na";
        //NA
        $sql .= " FROM sis_sede";
        $sql .= " WHERE sed_situacion = 1";
        if (strlen($sede) > 0) {
            $sql .= " AND sed_codigo IN($sede)";
        }
        $sql .= " ORDER BY sed_codigo ASC";

        $result = $this->exec_query($sql);
        //echo $sql;
        return $result;
    }

    function get_categoria_compara($fini = '', $ffin = '', $hora = '', $categoria = '', $sede = '', $departamento = '')
    {
        $sede = ($sede == "") ? $_SESSION["sedes_in"] : $sede;
        $fini = $this->regresa_fecha($fini);
        $ffin = $this->regresa_fecha($ffin);
        $hora = ($hora == "") ? date("H:i") : $hora;

        $sql = "SELECT *, ";
        //// SI
        $sql .= "(SELECT COUNT(resp_observacion) FROM aud_respuestas,aud_ejecucion,aud_auditoria,aud_programacion";
        $sql .= " WHERE resp_ejecucion = eje_codigo";
        $sql .= " AND resp_auditoria = eje_auditoria";
        $sql .= " AND eje_auditoria = audit_codigo";
        $sql .= " AND eje_programacion = pro_codigo";
        $sql .= " AND audit_categoria = cat_codigo";
        $sql .= " AND resp_observacion = 1"; // SI
        if (strlen($sede) > 0) {
            $sql .= " AND pro_sede IN($sede)";
        }
        if (strlen($departamento) > 0) {
            $sql .= " AND pro_departamento = $departamento";
        }
        $sql .= " AND eje_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin $hora' AND eje_situacion IN(1,2)) as respuestas_si,";
        //SI
        //// NO
        $sql .= "(SELECT COUNT(resp_observacion) FROM aud_respuestas,aud_ejecucion,aud_auditoria,aud_programacion";
        $sql .= " WHERE resp_ejecucion = eje_codigo";
        $sql .= " AND resp_auditoria = eje_auditoria";
        $sql .= " AND eje_auditoria = audit_codigo";
        $sql .= " AND eje_programacion = pro_codigo";
        $sql .= " AND audit_categoria = cat_codigo";
        $sql .= " AND resp_observacion = 2"; // NO
        if (strlen($sede) > 0) {
            $sql .= " AND pro_sede IN($sede)";
        }
        if (strlen($departamento) > 0) {
            $sql .= " AND pro_departamento = $departamento";
        }
        $sql .= " AND eje_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin $hora' AND eje_situacion IN(1,2)) as respuestas_no,";
        //NO
        //// NA
        $sql .= "(SELECT COUNT(resp_observacion) FROM aud_respuestas,aud_ejecucion,aud_auditoria,aud_programacion";
        $sql .= " WHERE resp_ejecucion = eje_codigo";
        $sql .= " AND resp_auditoria = eje_auditoria";
        $sql .= " AND eje_auditoria = audit_codigo";
        $sql .= " AND eje_programacion = pro_codigo";
        $sql .= " AND audit_categoria = cat_codigo";
        $sql .= " AND resp_observacion = 3"; // NA
        if (strlen($sede) > 0) {
            $sql .= " AND pro_sede IN($sede)";
        }
        if (strlen($departamento) > 0) {
            $sql .= " AND pro_departamento = $departamento";
        }

        $sql .= " AND eje_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin $hora' AND eje_situacion IN(1,2)) as respuestas_na";
        //NA
        $sql .= " FROM aud_categoria";
        $sql .= " WHERE cat_situacion = 1";
        if (strlen($categoria) > 0) {
            $sql .= " AND cat_codigo IN($categoria)";
        }
        $sql .= " ORDER BY cat_codigo ASC";

        $result = $this->exec_query($sql);
        //echo $sql."<br>";
        return $result;
    }

    function comprueba_ejecucion($codigo, $auditoria = '', $programacion = '', $usuario = '', $fini = '', $ffin = '', $situacion = '')
    {

        $sql = "SELECT * ";
        $sql .= " FROM aud_ejecucion, aud_auditoria, aud_programacion";
        $sql .= " WHERE eje_auditoria = audit_codigo";
        $sql .= " AND eje_programacion = pro_codigo";
        if (strlen($codigo) > 0) {
            $sql .= " AND eje_codigo = $codigo";
        }
        if (strlen($auditoria) > 0) {
            $sql .= " AND audit_codigo = $auditoria";
        }
        if (strlen($programacion) > 0) {
            $sql .= " AND pro_codigo = $programacion";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND eje_usuario = $usuario";
        }
        if ($fini != "" && $ffin != "") {
            $fini = $this->regresa_fecha($fini);
            $ffin = $this->regresa_fecha($ffin);
            $sql .= " AND eje_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND eje_situacion IN($situacion)";
        }
        $sql .= " ORDER BY eje_fecha_inicio DESC, pro_codigo, audit_codigo ASC";

        $result = $this->exec_query($sql);
        //echo $sql;
        return $result;
    }

    function insert_ejecucion($codigo, $auditoria, $programacion, $usuario, $obs)
    {
        $fsis = date("Y-m-d H:i:s");

        $sql = "INSERT INTO aud_ejecucion";
        $sql .= " VALUES ($codigo,$auditoria,$programacion,$usuario,'$fsis','$fsis','',0,'$obs','','','',0,0,1)";
        $sql .= " ON DUPLICATE KEY UPDATE";
        $sql .= " eje_observaciones = '$obs'; ";

        //echo $sql;
        return $sql;
    }

    function cerrar_ejecucion($codigo, $responsable, $nota, $obs)
    {
        $obs = trim($obs);
        $fsis = date("Y-m-d H:i:s");

        $sql = "UPDATE aud_ejecucion SET ";
        $sql .= "eje_situacion = 2,";
        $sql .= "eje_fecha_final = '$fsis',";
        $sql .= "eje_responsable = '$responsable',";
        $sql .= "eje_nota = '$nota',";
        $sql .= "eje_observaciones = '$obs'";

        $sql .= " WHERE eje_codigo = $codigo; ";
        //echo $sql;
        return $sql;
    }

    function correos_ejecucion($codigo, $correos)
    {

        $sql = "UPDATE aud_ejecucion SET eje_correos = '$correos'";
        $sql .= " WHERE eje_codigo = $codigo; ";

        return $sql;
    }

    function firma_evaluador_ejecucion($codigo, $firma)
    {

        $sql = "UPDATE aud_ejecucion SET eje_firma_evaluador = '$firma'";
        $sql .= " WHERE eje_codigo = $codigo; ";

        return $sql;
    }

    function firma_evaluado_ejecucion($codigo, $firma)
    {

        $sql = "UPDATE aud_ejecucion SET eje_firma_evaluado = '$firma'";
        $sql .= " WHERE eje_codigo = $codigo; ";

        return $sql;
    }

    function cambia_situacion_ejecucion($codigo, $situacion)
    {

        $sql = "UPDATE aud_ejecucion SET eje_situacion = $situacion";
        $sql .= " WHERE eje_codigo = $codigo; ";

        return $sql;
    }

    function max_ejecucion()
    {
        $sql = "SELECT max(eje_codigo) as max ";
        $sql .= " FROM aud_ejecucion";
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $max = $row["max"];
        }
        //echo $sql;
        return $max;
    }


    function update_ejecucion($codigo, $campo, $valor)
    {
       $sql = "UPDATE aud_ejecucion";
       $sql .= " SET $campo = '$valor' ";
       $sql .= " WHERE eje_codigo = $codigo; ";
       // echo $sql."<br>"lllll;
       return $sql;
    }

    /////////////////////////////  RESPUESTAS  //////////////////////////////////////

    function get_respuesta($ejecucion, $auditoria, $pregunta, $aplica = '', $seccion = '')
    {

        $sql = "SELECT * ";
        $sql .= " FROM aud_respuestas,aud_preguntas";
        $sql .= " WHERE resp_pregunta = pre_codigo";
        $sql .= " AND resp_auditoria = pre_auditoria";

        if (strlen($ejecucion) > 0) {
            $sql .= " AND resp_ejecucion = $ejecucion";
        }
        if (strlen($auditoria) > 0) {
            $sql .= " AND resp_auditoria = $auditoria";
        }
        if (strlen($pregunta) > 0) {
            $sql .= " AND resp_pregunta = $pregunta";
        }
        if (strlen($aplica) > 0) {
            $sql .= " AND resp_aplica = $aplica";
        }
        if (strlen($seccion) > 0) {
            $sql .= " AND resp_seccion = $seccion";
        }
        $sql .= " ORDER BY resp_ejecucion ASC, resp_pregunta ASC";

        $result = $this->exec_query($sql);
        //echo $sql;
        return $result;
    }

    function count_respuesta($ejecucion, $auditoria, $pregunta, $aplica = '', $seccion = '')
    {
        $sql = "SELECT COUNT(*) as total";
        $sql .= " FROM aud_respuestas,aud_preguntas";
        $sql .= " WHERE resp_pregunta = pre_codigo";
        $sql .= " AND resp_auditoria = pre_auditoria";

        if (strlen($ejecucion) > 0) {
            $sql .= " AND resp_ejecucion = $ejecucion";
        }
        if (strlen($auditoria) > 0) {
            $sql .= " AND resp_auditoria = $auditoria";
        }
        if (strlen($pregunta) > 0) {
            $sql .= " AND resp_pregunta = $pregunta";
        }
        if (strlen($aplica) > 0) {
            $sql .= " AND resp_aplica = $aplica";
        }
        if (strlen($seccion) > 0) {
            $sql .= " AND resp_seccion = $seccion";
        }
        //echo $sql;
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $total = $row['total'];
        }
        return $total;
    }


    function insert_respuesta($auditoria, $pregunta, $ejecucion, $seccion, $tipo, $peso, $aplica, $respuesta)
    {
        $fsis = date("Y-m-d H:i:s");

        $sql = "INSERT INTO aud_respuestas (resp_auditoria, resp_pregunta, resp_ejecucion, resp_seccion, resp_tipo, resp_peso, resp_aplica, resp_respuesta, resp_fecha_registro)";
        $sql .= " VALUES ($auditoria,$pregunta,$ejecucion,$seccion,$tipo,'$peso',$aplica,$respuesta,'$fsis')";
        $sql .= " ON DUPLICATE KEY UPDATE";
        $sql .= " resp_seccion = '$seccion', ";
        $sql .= " resp_tipo = '$tipo', ";
        $sql .= " resp_peso = '$peso', ";
        $sql .= " resp_aplica = '$aplica', ";
        $sql .= " resp_respuesta = '$respuesta', ";
        $sql .= " resp_fecha_registro = '$fsis'; ";
        //echo $sql;
        return $sql;
    }

    function update_respuesta($auditoria, $pregunta, $ejecucion, $seccion, $observacion)
    {
        $fsis = date("Y-m-d H:i:s");

        $sql = "INSERT INTO aud_respuestas (resp_auditoria, resp_pregunta, resp_ejecucion, resp_seccion, resp_aplica, resp_observacion, resp_fecha_registro)";
        $sql .= " VALUES ($auditoria,$pregunta,$ejecucion,$seccion,1,'$observacion','$fsis')";
        $sql .= " ON DUPLICATE KEY UPDATE";
        $sql .= " resp_seccion = '$seccion', ";
        $sql .= " resp_observacion = '$observacion', ";
        $sql .= " resp_fecha_registro = '$fsis';";
        //echo $sql;
        return $sql;
    }

    /////////////////////////////  FOTOS  //////////////////////////////////////

    function get_fotos($codigo, $ejecucion, $auditoria, $pregunta)
    {

        $sql = "SELECT * ";
        $sql .= " FROM aud_fotos_preguntas";
        $sql .= " WHERE 1 = 1";
        if (strlen($codigo) > 0) {
            $sql .= " AND fot_codigo = $codigo";
        }
        if (strlen($ejecucion) > 0) {
            $sql .= " AND fot_ejecucion = $ejecucion";
        }
        if (strlen($auditoria) > 0) {
            $sql .= " AND fot_auditoria = $auditoria";
        }
        if (strlen($pregunta) > 0) {
            $sql .= " AND fot_pregunta = $pregunta";
        }
        $sql .= " ORDER BY fot_codigo ASC";

        $result = $this->exec_query($sql);
        //echo $sql."<br>";
        return $result;
    }

    function count_fotos($codigo, $ejecucion, $auditoria, $pregunta)
    {

        $sql = "SELECT COUNT(*) as total";
        $sql .= " FROM aud_fotos_preguntas";
        $sql .= " WHERE 1 = 1";
        if (strlen($codigo) > 0) {
            $sql .= " AND fot_codigo = $codigo";
        }
        if (strlen($ejecucion) > 0) {
            $sql .= " AND fot_ejecucion = $ejecucion";
        }
        if (strlen($auditoria) > 0) {
            $sql .= " AND fot_auditoria = $auditoria";
        }
        if (strlen($pregunta) > 0) {
            $sql .= " AND fot_pregunta = $pregunta";
        }

        $result = $this->exec_query($sql);
        if (is_array($result)) {
            foreach ($result as $row) {
                $total = $row["total"];
            }
        }
        //echo $sql."<br>";
        return $total;
    }

    function insert_foto($codigo, $auditoria, $pregunta, $ejecucion, $foto)
    {
        $fsis = date("Y-m-d H:i:s");

        $sql = "INSERT INTO aud_fotos_preguntas";
        $sql .= " VALUES ($codigo,$auditoria,$pregunta,$ejecucion,'$foto','$fsis')";
        $sql .= " ON DUPLICATE KEY UPDATE";
        $sql .= " fot_foto = '$foto', ";
        $sql .= " fot_fecha_registro = '$fsis'; ";
        //echo $sql;
        return $sql;
    }

    function delete_foto($codigo, $auditoria, $pregunta, $ejecucion)
    {

        $sql = "DELETE FROM aud_fotos_preguntas";
        $sql .= " WHERE fot_auditoria = $auditoria ";
        $sql .= " AND fot_pregunta = $pregunta ";
        $sql .= " AND fot_ejecucion = $ejecucion ";
        $sql .= " AND fot_codigo = $codigo;";

        return $sql;
    }

    function max_foto($auditoria, $pregunta, $ejecucion)
    {
        $sql = "SELECT max(fot_codigo) as max ";
        $sql .= " FROM aud_fotos_preguntas";
        $sql .= " WHERE fot_auditoria = $auditoria ";
        $sql .= " AND fot_pregunta = $pregunta ";
        $sql .= " AND fot_ejecucion = $ejecucion ";
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $max = $row["max"];
        }
        //echo $sql;
        return $max;
    }

    /////////////////////////////  OBSERVACIONES POR DEPARTAMENTO  //////////////////////////////////////

    function get_observaciones_departamento($ejecucion, $departamento)
    {

        $sql = "SELECT * ";
        $sql .= " FROM aud_observaciones_departamento, sis_departamento";
        $sql .= " WHERE obs_departamento = dep_codigo";

        if (strlen($ejecucion) > 0) {
            $sql .= " AND obs_ejecucion = $ejecucion";
        }
        if (strlen($departamento) > 0) {
            $sql .= " AND obs_departamento = $departamento";
        }
        $sql .= " ORDER BY obs_ejecucion ASC, obs_departamento ASC";

        $result = $this->exec_query($sql);
        //echo $sql."<br><br>";
        return $result;
    }


    function insert_observaciones_departamento($ejecucion, $departamento, $observacion)
    {

        $sql = "INSERT INTO aud_observaciones_departamento";
        $sql .= " VALUES ($ejecucion,$departamento,'$observacion')";
        $sql .= " ON DUPLICATE KEY UPDATE";
        $sql .= " obs_observacion = '$observacion'; ";
        //echo $sql;
        return $sql;
    }

    /////////////////////////////  OBSERVACIONES POR SITUACION DE EJECUCION  //////////////////////////////////////

    function get_ejecucion_situacion($ejecucion, $situacion)
    {

        $sql = "SELECT * ";
        $sql .= " FROM aud_ejecucion_situacion, seg_usuarios";
        $sql .= " WHERE ejest_usuario = usu_id";
        if (strlen($ejecucion) > 0) {
            $sql .= " AND ejest_ejecucion = $ejecucion";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND ejest_situacion IN($situacion)";
        }
        $sql .= " ORDER BY ejest_ejecucion ASC, ejest_situacion ASC";

        $result = $this->exec_query($sql);
        //echo $sql."<br><br>";
        return $result;
    }


    function insert_ejecucion_situacion($ejecucion, $situacion, $observacion, $usuario = '')
    {
        $fsis = date("Y-m-d H:i:s");
        $usuario = ($usuario == "") ? $_SESSION["codigo"] : $usuario;

        $sql = "INSERT INTO aud_ejecucion_situacion";
        $sql .= " VALUES ($ejecucion,$situacion,'$fsis',$usuario,'$observacion')";
        $sql .= " ON DUPLICATE KEY UPDATE";
        $sql .= " ejest_usuario = '$usuario', ";
        $sql .= " ejest_observacion = '$observacion'; ";
        //echo $sql;
        return $sql;
    }

    ///////////////////////////// REVISION POR PREGUNTA DE EJECUCION  //////////////////////////////////////

    function get_ejecucion_revision($ejecucion, $auditoria, $pregunta, $seccion = '')
    {

        $sql = "SELECT *, ";
        $sql .= "(SELECT usu_nombre FROM seg_usuarios WHERE usu_id = rev_usuario) as rev_usuario_nombre";
        $sql .= " FROM aud_ejecucion_revision,aud_preguntas";
        $sql .= " WHERE rev_pregunta = pre_codigo";
        $sql .= " AND rev_auditoria = pre_auditoria";

        if (strlen($ejecucion) > 0) {
            $sql .= " AND rev_ejecucion = $ejecucion";
        }
        if (strlen($auditoria) > 0) {
            $sql .= " AND rev_auditoria = $auditoria";
        }
        if (strlen($pregunta) > 0) {
            $sql .= " AND rev_pregunta = $pregunta";
        }
        if (strlen($seccion) > 0) {
            $sql .= " AND pre_seccion = $seccion";
        }
        $sql .= " ORDER BY rev_ejecucion ASC, rev_pregunta ASC";

        $result = $this->exec_query($sql);
        //echo $sql;
        return $result;
    }


    function insert_ejecucion_revision($auditoria, $pregunta, $ejecucion, $resultado, $observaciones, $usuario = '')
    {
        $fsis = date("Y-m-d H:i:s");
        $usuario = ($usuario == "") ? $_SESSION["codigo"] : $usuario;

        $sql = "INSERT INTO aud_ejecucion_revision";
        $sql .= " VALUES ($auditoria,$pregunta,$ejecucion,'$resultado','$observaciones','$fsis','$usuario',1)";
        $sql .= " ON DUPLICATE KEY UPDATE";
        $sql .= " rev_resultado = '$resultado', ";
        $sql .= " rev_observaciones = '$observaciones', ";
        $sql .= " rev_usuario = '$usuario', ";
        $sql .= " rev_fecha_registro = '$fsis'; ";
        //echo $sql;
        return $sql;
    }


    ///////////////////////////// DISOLUCION DE HALLAZGOS DE AUDITORIA  //////////////////////////////////////

    function get_disolucion_hallazgo($ejecucion, $auditoria, $pregunta, $seccion = '')
    {

        $sql = "SELECT *, ";
        $sql .= "(SELECT usu_nombre FROM seg_usuarios WHERE usu_id = dis_usuario) as dis_usuario_nombre";
        $sql .= " FROM aud_disolucion,aud_preguntas";
        $sql .= " WHERE dis_pregunta = pre_codigo";
        $sql .= " AND dis_auditoria = pre_auditoria";

        if (strlen($ejecucion) > 0) {
            $sql .= " AND dis_ejecucion = $ejecucion";
        }
        if (strlen($auditoria) > 0) {
            $sql .= " AND dis_auditoria = $auditoria";
        }
        if (strlen($pregunta) > 0) {
            $sql .= " AND dis_pregunta = $pregunta";
        }
        if (strlen($seccion) > 0) {
            $sql .= " AND pre_seccion = $seccion";
        }
        $sql .= " ORDER BY dis_ejecucion ASC, dis_pregunta ASC";

        $result = $this->exec_query($sql);
        //echo $sql;
        return $result;
    }


    function insert_disolucion_hallazgo($auditoria, $pregunta, $ejecucion, $observaciones, $usuario = '')
    {
        $fsis = date("Y-m-d H:i:s");
        $usuario = ($usuario == "") ? $_SESSION["codigo"] : $usuario;

        $sql = "INSERT INTO aud_disolucion";
        $sql .= " VALUES ($auditoria,$pregunta,$ejecucion,'$observaciones','$fsis','$usuario')";
        $sql .= " ON DUPLICATE KEY UPDATE";
        $sql .= " dis_observaciones = '$observaciones', ";
        $sql .= " dis_usuario = '$usuario', ";
        $sql .= " dis_fecha_registro = '$fsis'; ";
        //echo $sql;
        return $sql;
    }

    ///////////////////////////// ACTA DE AUDITORIA  //////////////////////////////////////

    function get_acta($ejecucion, $auditoria = '', $usuario = '', $sede = '', $departamento = '', $categoria = '', $fini = '', $ffin = '', $situacion = '', $orderfecha = 'DESC')
    {
        $sede = ($sede == "") ? $_SESSION["sedes_in"] : $sede;

        $sql = "SELECT *, ";
        $sql .= " (SELECT usu_nombre FROM seg_usuarios WHERE usu_id = eje_usuario) as usuario_nombre,";
        $sql .= " (SELECT dm_desc FROM mast_mundep WHERE dm_codigo = sed_municipio) as sede_municipio";
        $sql .= " FROM aud_acta, aud_ejecucion, aud_auditoria, aud_programacion, aud_categoria, sis_departamento, sis_sede";
        $sql .= " WHERE act_ejecucion = eje_codigo";
        $sql .= " AND eje_auditoria = audit_codigo";
        $sql .= " AND eje_programacion = pro_codigo";
        $sql .= " AND audit_categoria = cat_codigo";
        $sql .= " AND pro_sede = sed_codigo";
        $sql .= " AND pro_departamento = dep_codigo";
        if (strlen($ejecucion) > 0) {
            $sql .= " AND act_ejecucion = $ejecucion";
        }
        if (strlen($auditoria) > 0) {
            $sql .= " AND audit_codigo = $auditoria";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND eje_usuario = $usuario";
        }
        if (strlen($sede) > 0) {
            $sql .= " AND pro_sede IN($sede)";
        }
        if (strlen($departamento) > 0) {
            $sql .= " AND pro_departamento = $departamento";
        }
        if (strlen($categoria) > 0) {
            $sql .= " AND audit_categoria IN($categoria)";
        }
        if ($fini != "" && $ffin != "") {
            $fini = $this->regresa_fecha($fini);
            $ffin = $this->regresa_fecha($ffin);
            $sql .= " AND act_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND act_situacion IN($situacion)";
        }
        $sql .= " ORDER BY act_fecha_inicio $orderfecha, audit_categoria ASC, pro_sede ASC, pro_departamento ASC, audit_codigo ASC";

        $result = $this->exec_query($sql);
        //echo $sql;
        return $result;
    }


    function insert_acta($ejecucion, $auditoria, $programacion, $observaciones, $fechorini, $fechorfin, $usuario = '')
    {
        $fini = ($fechorini == '') ? date("Y-m-d H:i:s") : $this->regresa_fechaHora($fechorini); // si manda fecha/hora, si no setea la actual
        $ffin = ($fechorfin == '') ? date("Y-m-d H:i:s") : $this->regresa_fechaHora($fechorfin); // si manda fecha/hora, si no setea la actual
        $usuario = ($usuario == "") ? $_SESSION["codigo"] : $usuario;

        $sql = "INSERT INTO aud_acta";
        $sql .= " VALUES ($ejecucion,$auditoria,$programacion,'$observaciones','$fini','$ffin','$usuario',1)";
        $sql .= " ON DUPLICATE KEY UPDATE";
        $sql .= " act_fecha_inicio = '$fini', ";
        $sql .= " act_fecha_final = '$ffin', ";
        $sql .= " act_observaciones = '$observaciones', ";
        $sql .= " act_usuario = '$usuario';";
        //echo $sql;
        return $sql;
    }

    function cambia_situacion_acta($ejecucion, $situacion)
    {

        $sql = "UPDATE aud_ejecucion SET act_situacion = $situacion";
        $sql .= " WHERE act_ejecucion = $ejecucion; ";

        return $sql;
    }

    ///////////////////////////// Ejecucion de Acciones  //////////////////////////////////////

    function modifica_ejecucion_accion($codigo, $observacion)
    {
        $observacion = trim($observacion);

        $sql = "UPDATE pla_ejecucion SET ";
        $sql .= "eje_observacion = '$observacion'";

        $sql .= " WHERE eje_codigo = $codigo; ";
        //echo $sql;
        return $sql;
    }

    function cambia_situacion_ejecucion_accion($codigo, $situacion)
    {

        $sql = "UPDATE pla_ejecucion SET eje_situacion = $situacion";
        $sql .= " WHERE eje_codigo = $codigo; ";
        // echo $sql;
        return $sql;
    }

    function get_ejecucion_accion($codigo = '', $programacion = '', $fini = '', $ffin = '', $situacion = '', $proceso = '', $sistema = '', $usuario= '')
    {

        $sql = "SELECT * ";
        $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE acc_usuario = usu_id) as acc_usuario";
        $sql .= " ,(SELECT fic_nombre FROM pro_ficha WHERE obj_proceso = fic_codigo) as obj_proceso";
        $sql .= " ,(SELECT sis_nombre FROM pro_sistema WHERE obj_sistema = sis_codigo) as obj_sistema";
        $sql .= " ,(SELECT eva_codigo FROM pla_evaluacion WHERE eva_ejecucion = eje_codigo) as evaluacion_ejecutada";
        $sql .= " FROM pla_ejecucion, pla_programacion, pla_accion, pro_objetivos";
        $sql .= " WHERE eje_programacion = pro_codigo";
        $sql .= " AND pro_accion = acc_codigo";
        $sql .= " AND acc_objetivo = obj_codigo";
        if (strlen($codigo) > 0) {
            $sql .= " AND eje_codigo = $codigo";
        }
        if (strlen($programacion) > 0) {
            $sql .= " AND eje_programacion = $programacion";
        }
        if (strlen($proceso) > 0) {
            $sql .= " AND obj_proceso = $proceso";
        }
        if (strlen($sistema) > 0) {
            $sql .= " AND obj_sistema = $sistema";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND eva_usuario = $usuario";
        }
        if ($fini != "" && $ffin != "") {
            $fini = $this->regresa_fecha($fini);
            $ffin = $this->regresa_fecha($ffin);
            $sql .= " AND eje_fecha BETWEEN '$fini' AND '$ffin'";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND eje_situacion IN($situacion)";
        }
        $sql .= " ORDER BY eje_fecha ASC, eje_codigo ASC, pro_codigo ASC;";

        $result = $this->exec_query($sql);
        // echo $sql."<br>";
        return $result;
    }

    function count_ejecucion_accion($codigo = '', $programacion = '', $usuario = '', $fini = '', $ffin = '', $situacion = '')
    {

        $sql = "SELECT COUNT(*) as total";
        $sql .= " FROM pla_ejecucion, pla_programacion, pla_accion";
        $sql .= " WHERE eje_programacion = pro_codigo";
        $sql .= " AND pro_accion = acc_codigo";
        if (strlen($codigo) > 0) {
            $sql .= " AND eje_codigo = $codigo";
        }
        if (strlen($programacion) > 0) {
            $sql .= " AND eje_programacion = $programacion";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND acc_usuario = $usuario";
        }
        if ($fini != "" && $ffin != "") {
            $fini = $this->regresa_fecha($fini);
            $ffin = $this->regresa_fecha($ffin);
            $sql .= " AND eje_fecha BETWEEN '$fini' AND '$ffin'";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND eje_situacion IN($situacion)";
        }
        // echo $sql;
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $total = $row['total'];
        }
        return $total;
    }

    function insert_ejecucion_accion($codigo, $programacion, $observacion)
    {
        $fsis = date("Y-m-d H:i:s");

        $sql = "INSERT INTO pla_ejecucion";
        $sql .= " VALUES ($codigo,$programacion,'$fsis','$observacion',1)";
        $sql .= " ON DUPLICATE KEY UPDATE";
        $sql .= " eje_observacion = '$observacion'; ";

        //echo $sql;
        return $sql;
    }

    function max_ejecucion_accion()
    {
        $sql = "SELECT max(eje_codigo) as max ";
        $sql .= " FROM pla_ejecucion";
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $max = $row["max"];
        }
        //echo $sql;
        return $max;
    }

    function get_fotos_ejecucion($codigo, $ejecucion, $posicion = '')
    {

        $sql = "SELECT * ";
        $sql .= " FROM pla_foto_ejecucion";
        $sql .= " WHERE 1 = 1";
        if (strlen($codigo) > 0) {
            $sql .= " AND fot_codigo = $codigo";
        }
        if (strlen($ejecucion) > 0) {
            $sql .= " AND fot_ejecucion IN($ejecucion)";
        }
        if (strlen($posicion) > 0) {
            $sql .= " AND fot_posicion = $posicion";
        }
        $sql .= " ORDER BY fot_ejecucion ASC, fot_posicion ASC";

        $result = $this->exec_query($sql);
        //echo $sql;
        return $result;
    }

    function insert_foto_ejecucion($codigo, $ejecucion, $posicion, $foto)
    {
        $fsis = date("Y-m-d H:i:s");

        $sql = "INSERT INTO pla_foto_ejecucion";
        $sql .= " VALUES ($codigo,$ejecucion,$posicion,'$foto','$fsis')";
        $sql .= " ON DUPLICATE KEY UPDATE";
        $sql .= " fot_foto = '$foto', ";
        $sql .= " fot_fecha_registro = '$fsis'; ";
        //echo $sql;
        return $sql;
    }

    function delete_foto_ejecucion($codigo)
    {

        $sql = "DELETE FROM pla_foto_ejecucion";
        $sql .= " WHERE fot_codigo = $codigo; ";

        return $sql;
    }

    function max_foto_ejecucion()
    {
        $sql = "SELECT max(fot_codigo) as max ";
        $sql .= " FROM pla_foto_ejecucion";
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $max = $row["max"];
        }
        //echo $sql;
        return $max;
    }

    function get_documentos_ejecucion($codigo, $ejecucion, $posicion = '')
    {

        $sql = "SELECT * ";
        $sql .= " FROM pla_documento_ejecucion";
        $sql .= " WHERE 1 = 1";
        if (strlen($codigo) > 0) {
            $sql .= " AND doc_codigo = $codigo";
        }
        if (strlen($ejecucion) > 0) {
            $sql .= " AND doc_ejecucion IN($ejecucion)";
        }
        if (strlen($posicion) > 0) {
            $sql .= " AND doc_posicion = $posicion";
        }
        $sql .= " ORDER BY doc_ejecucion ASC, doc_posicion ASC";

        $result = $this->exec_query($sql);
        //echo $sql;
        return $result;
    }

    function insert_documento_ejecucion($codigo, $ejecucion, $posicion, $documento)
    {
        $fsis = date("Y-m-d H:i:s");

        $sql = "INSERT INTO pla_documento_ejecucion";
        $sql .= " VALUES ($codigo,$ejecucion,$posicion,'$documento','$fsis')";
        $sql .= " ON DUPLICATE KEY UPDATE";
        $sql .= " doc_documento = '$documento', ";
        $sql .= " doc_fecha_registro = '$fsis'; ";
        //echo $sql;
        return $sql;
    }

    function delete_documento_ejecucion($codigo)
    {

        $sql = "DELETE FROM pla_documento_ejecucion";
        $sql .= " WHERE doc_codigo = $codigo; ";

        return $sql;
    }

    function max_documento_ejecucion()
    {
        $sql = "SELECT max(doc_codigo) as max ";
        $sql .= " FROM pla_documento_ejecucion";
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $max = $row["max"];
        }
        //echo $sql;
        return $max;
    }
}
