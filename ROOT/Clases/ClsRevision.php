<?php
require_once("ClsConex.php");

class ClsRevision extends ClsConex
{
    /* Situacion 1 = Abierto, 2 = Cerrado, 0 = Anulado */

    /////////////////////////////  REVISONES CHECKLIST //////////////////////////////////////
    function get_revision_programacion($lista,$programacion, $usuario){
        $sql = "SELECT * FROM chk_revision ";
        $sql.= "WHERE";
        if (strlen($lista) > 0) {
            $sql.= " rev_lista = $lista";
        }
        if (strlen($programacion) > 0) {
            $sql.= " AND rev_programacion = $programacion"; 
        }
        if (strlen($usuario) > 0) {
            $sql.= " AND rev_usuario = $usuario"; 
        }
        $sql.= " ORDER BY rev_codigo ASC; ";
        $result = $this->exec_query($sql);
        //echo $sql;
        //echo mysqli_error($this->conn);

        return $result;   

    }
    function get_revision($codigo, $lista = '', $usuario = '', $sede = '', $sector = '', $area = '', $categoria = '', $fini = '', $ffin = '', $situacion = '', $orderfecha = 'DESC')
    {
        $sede = ($sede == "") ? $_SESSION["sedes_in"] : $sede;

        $sql = "SELECT *, ";
        //// SI
        $sql .= "(SELECT COUNT(resp_respuesta) FROM chk_revision_respuestas";
        $sql .= " WHERE resp_revision = rev_codigo AND resp_respuesta = 1 AND rev_situacion IN(1,2)) as rev_cont_si,";
        //// NO
        $sql .= "(SELECT COUNT(resp_respuesta) FROM chk_revision_respuestas";
        $sql .= " WHERE resp_revision = rev_codigo AND resp_respuesta = 2 AND rev_situacion IN(1,2)) as rev_cont_no,";
        //// NO APLICA
        $sql .= "(SELECT COUNT(resp_respuesta) FROM chk_revision_respuestas";
        $sql .= " WHERE resp_revision = rev_codigo AND resp_respuesta = 3 AND rev_situacion IN(1,2)) as rev_cont_na,";
        //--
        $sql .= " (SELECT usu_nombre FROM seg_usuarios WHERE usu_id = rev_usuario) as usuario_nombre,";
        $sql .= " (SELECT dm_desc FROM mast_mundep WHERE dm_codigo = sed_municipio) as sede_municipio";
        $sql .= " FROM chk_revision, chk_lista, chk_programacion, chk_categoria, sis_area, sis_sector, sis_sede";
        $sql .= " WHERE rev_lista = list_codigo";
        $sql .= " AND rev_programacion = pro_codigo";
        $sql .= " AND list_categoria = cat_codigo";
        $sql .= " AND pro_sede = sed_codigo";
        $sql .= " AND pro_sector = sec_codigo";
        $sql .= " AND pro_area = are_codigo";
        if (strlen($codigo) > 0) {
            $sql .= " AND rev_codigo = $codigo";
        }
        if (strlen($lista) > 0) {
            $sql .= " AND list_codigo = $lista";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND rev_usuario = $usuario";
        }
        if (strlen($sede) > 0) {
            $sql .= " AND pro_sede IN($sede)";
        }
        if (strlen($sector) > 0) {
            $sql .= " AND pro_sector = $sector";
        }
        if (strlen($area) > 0) {
            $sql .= " AND pro_area = $area";
        }
        if (strlen($categoria) > 0) {
            $sql .= " AND list_categoria IN($categoria)";
        }
        if ($fini != "" && $ffin != "") {
            $fini = $this->regresa_fecha($fini);
            $ffin = $this->regresa_fecha($ffin);
            $sql .= " AND rev_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND rev_situacion IN($situacion)";
        }
        $sql .= " ORDER BY rev_fecha_inicio $orderfecha, list_categoria ASC, pro_sede ASC, pro_sector ASC, pro_area ASC, list_codigo ASC";

        $result = $this->exec_query($sql);
        //echo $sql;
        return $result;
    }

    function count_revision($codigo, $lista = '', $usuario = '', $sede = '', $sector = '', $area = '', $categoria = '', $fini = '', $ffin = '', $situacion = '', $orderfecha = 'DESC')
    {
        $sede = ($sede == "") ? $_SESSION["sedes_in"] : $sede;

        $sql = "SELECT COUNT(*) as total";
        $sql .= " FROM chk_revision, chk_lista, chk_programacion, chk_categoria, sis_area, sis_sector, sis_sede";
        $sql .= " WHERE rev_lista = list_codigo";
        $sql .= " AND rev_programacion = pro_codigo";
        $sql .= " AND list_categoria = cat_codigo";
        $sql .= " AND pro_sede = sed_codigo";
        $sql .= " AND pro_sector = sec_codigo";
        $sql .= " AND pro_area = are_codigo";
        if (strlen($codigo) > 0) {
            $sql .= " AND rev_codigo = $codigo";
        }
        if (strlen($lista) > 0) {
            $sql .= " AND list_codigo = $lista";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND rev_usuario = $usuario";
        }
        if (strlen($sede) > 0) {
            $sql .= " AND pro_sede IN($sede)";
        }
        if (strlen($sector) > 0) {
            $sql .= " AND pro_sector = $sector";
        }
        if (strlen($area) > 0) {
            $sql .= " AND pro_area = $area";
        }
        if (strlen($categoria) > 0) {
            $sql .= " AND list_categoria IN($categoria)";
        }
        if ($fini != "" && $ffin != "") {
            $fini = $this->regresa_fecha($fini);
            $ffin = $this->regresa_fecha($ffin);
            $sql .= " AND rev_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'";
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

    function get_sede_compara($fini = '', $ffin = '', $hora = '', $categoria = '', $sede = '', $sector = '', $area = '')
    {
        $sede = ($sede == "") ? $_SESSION["sedes_in"] : $sede;
        $fini = $this->regresa_fecha($fini);
        $ffin = $this->regresa_fecha($ffin);
        $hora = ($hora == "") ? date("H:i:s") : $hora;

        $sql = "SELECT *, ";
        //// SI
        $sql .= "(SELECT COUNT(resp_respuesta) FROM chk_revision_respuestas,chk_preguntas,chk_revision,chk_lista,chk_programacion";
        $sql .= " WHERE resp_pregunta = pre_codigo";
        $sql .= " AND resp_revision = rev_codigo";
        $sql .= " AND resp_lista = rev_lista";
        $sql .= " AND rev_lista = list_codigo";
        $sql .= " AND rev_programacion = pro_codigo";
        $sql .= " AND pro_sede = sed_codigo";
        $sql .= " AND pre_situacion = 1";
        $sql .= " AND resp_respuesta = 1"; // SI
        if (strlen($categoria) > 0) {
            $sql .= " AND list_categoria IN($categoria)";
        }
        if (strlen($sector) > 0) {
            $sql .= " AND pro_sector = $sector";
        }
        if (strlen($area) > 0) {
            $sql .= " AND pro_area = $area";
        }
        $sql .= " AND rev_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin $hora' AND rev_situacion IN(1,2)) as respuestas_si,";
        //SI
        //// NO
        $sql .= "(SELECT COUNT(resp_respuesta) FROM chk_revision_respuestas,chk_preguntas,chk_revision,chk_lista,chk_programacion";
        $sql .= " WHERE resp_pregunta = pre_codigo";
        $sql .= " AND resp_revision = rev_codigo";
        $sql .= " AND resp_lista = rev_lista";
        $sql .= " AND rev_lista = list_codigo";
        $sql .= " AND rev_programacion = pro_codigo";
        $sql .= " AND pro_sede = sed_codigo";
        $sql .= " AND pre_situacion = 1";
        $sql .= " AND resp_respuesta = 2"; // NO
        if (strlen($categoria) > 0) {
            $sql .= " AND list_categoria IN($categoria)";
        }
        if (strlen($sector) > 0) {
            $sql .= " AND pro_sector = $sector";
        }
        if (strlen($area) > 0) {
            $sql .= " AND pro_area = $area";
        }
        $sql .= " AND rev_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin $hora' AND rev_situacion IN(1,2)) as respuestas_no,";
        //NO
        //// NA
        $sql .= "(SELECT COUNT(resp_respuesta) FROM chk_revision_respuestas,chk_preguntas,chk_revision,chk_lista,chk_programacion";
        $sql .= " WHERE resp_pregunta = pre_codigo";
        $sql .= " AND resp_revision = rev_codigo";
        $sql .= " AND resp_lista = rev_lista";
        $sql .= " AND rev_lista = list_codigo";
        $sql .= " AND rev_programacion = pro_codigo";
        $sql .= " AND pro_sede = sed_codigo";
        $sql .= " AND pre_situacion = 1";
        $sql .= " AND resp_respuesta = 3"; // NA
        if (strlen($categoria) > 0) {
            $sql .= " AND list_categoria IN($categoria)";
        }
        if (strlen($sector) > 0) {
            $sql .= " AND pro_sector = $sector";
        }
        if (strlen($area) > 0) {
            $sql .= " AND pro_area = $area";
        }
        $sql .= " AND rev_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin $hora' AND rev_situacion IN(1,2)) as respuestas_na";
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

    function get_categoria_compara($fini = '', $ffin = '', $hora = '', $categoria = '', $sede = '', $sector = '', $area = '')
    {
        $sede = ($sede == "") ? $_SESSION["sedes_in"] : $sede;
        $fini = $this->regresa_fecha($fini);
        $ffin = $this->regresa_fecha($ffin);
        $hora = ($hora == "") ? date("H:i") : $hora;

        $sql = "SELECT *, ";
        //// SI
        $sql .= "(SELECT COUNT(resp_respuesta) FROM chk_revision_respuestas,chk_preguntas,chk_revision,chk_lista,chk_programacion";
        $sql .= " WHERE resp_pregunta = pre_codigo";
        $sql .= " AND resp_revision = rev_codigo";
        $sql .= " AND resp_lista = rev_lista";
        $sql .= " AND rev_lista = list_codigo";
        $sql .= " AND rev_programacion = pro_codigo";
        $sql .= " AND list_categoria = cat_codigo";
        $sql .= " AND pre_situacion = 1";
        $sql .= " AND resp_respuesta = 1"; // SI
        if (strlen($sede) > 0) {
            $sql .= " AND pro_sede IN($sede)";
        }
        if (strlen($sector) > 0) {
            $sql .= " AND pro_sector = $sector";
        }
        if (strlen($area) > 0) {
            $sql .= " AND pro_area = $area";
        }
        $sql .= " AND rev_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin $hora' AND rev_situacion IN(1,2)) as respuestas_si,";
        //SI
        //// NO
        $sql .= "(SELECT COUNT(resp_respuesta) FROM chk_revision_respuestas,chk_preguntas,chk_revision,chk_lista,chk_programacion";
        $sql .= " WHERE resp_pregunta = pre_codigo";
        $sql .= " AND resp_revision = rev_codigo";
        $sql .= " AND resp_lista = rev_lista";
        $sql .= " AND rev_lista = list_codigo";
        $sql .= " AND rev_programacion = pro_codigo";
        $sql .= " AND list_categoria = cat_codigo";
        $sql .= " AND pre_situacion = 1";
        $sql .= " AND resp_respuesta = 2"; // NO
        if (strlen($sede) > 0) {
            $sql .= " AND pro_sede IN($sede)";
        }
        if (strlen($sector) > 0) {
            $sql .= " AND pro_sector = $sector";
        }
        if (strlen($area) > 0) {
            $sql .= " AND pro_area = $area";
        }
        $sql .= " AND rev_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin $hora' AND rev_situacion IN(1,2)) as respuestas_no,";
        //NO
        //// NA
        $sql .= "(SELECT COUNT(resp_respuesta) FROM chk_revision_respuestas,chk_preguntas,chk_revision,chk_lista,chk_programacion";
        $sql .= " WHERE resp_pregunta = pre_codigo";
        $sql .= " AND resp_revision = rev_codigo";
        $sql .= " AND resp_lista = rev_lista";
        $sql .= " AND rev_lista = list_codigo";
        $sql .= " AND rev_programacion = pro_codigo";
        $sql .= " AND list_categoria = cat_codigo";
        $sql .= " AND pre_situacion = 1";
        $sql .= " AND resp_respuesta = 3"; // NA
        if (strlen($sede) > 0) {
            $sql .= " AND pro_sede IN($sede)";
        }
        if (strlen($sector) > 0) {
            $sql .= " AND pro_sector = $sector";
        }
        if (strlen($area) > 0) {
            $sql .= " AND pro_area = $area";
        }
        $sql .= " AND rev_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin $hora' AND rev_situacion IN(1,2)) as respuestas_na";
        //NA
        $sql .= " FROM chk_categoria";
        $sql .= " WHERE cat_situacion = 1";
        if (strlen($categoria) > 0) {
            $sql .= " AND cat_codigo IN($categoria)";
        }
        $sql .= " ORDER BY cat_codigo ASC";

        $result = $this->exec_query($sql);
        //echo $sql."<br><br>";
        return $result;
    }

    function get_categoria_compara2($fini = '', $ffin = '', $hora = '', $categoria = '', $sede = '', $area = '')
    {
        $fini = $this->regresa_fecha($fini);
        $ffin = $this->regresa_fecha($ffin);
        $hora = ($hora == "") ? date("H:i") : $hora;

        $sql = "SELECT *, ";
        $sql .= "(SELECT COUNT(list_codigo) FROM chk_lista, chk_programacion";
        $sql .= " WHERE list_codigo = pro_lista";
        $sql .= " AND list_categoria = cat_codigo";
        if (strlen($sede) > 0) {
            $sql .= " AND pro_sede IN($sede)";
        }
        if (strlen($area) > 0) {
            $sql .= " AND pro_area = $area";
        }
        $sql .= " AND list_situacion = 1) as listas_totales,";
        //--
        $sql .= "(SELECT COUNT(list_codigo) FROM chk_lista, chk_programacion";
        $sql .= " WHERE list_codigo = pro_lista";
        $sql .= " AND list_categoria = cat_codigo";
        if (strlen($sede) > 0) {
            $sql .= " AND pro_sede IN($sede)";
        }
        if (strlen($area) > 0) {
            $sql .= " AND pro_area = $area";
        }
        $sql .= " AND pro_hini < '$hora' AND list_situacion = 1) as listas_ahora,";
        //-
        $sql .= "(SELECT COUNT(list_codigo) FROM chk_lista, chk_programacion";
        $sql .= " WHERE list_codigo = pro_lista";
        $sql .= " AND list_categoria = cat_codigo";
        if (strlen($sede) > 0) {
            $sql .= " AND pro_sede IN($sede)";
        }
        if (strlen($area) > 0) {
            $sql .= " AND pro_area = $area";
        }
        $sql .= " AND pro_hini > '$hora' AND list_situacion = 1) as listas_faltantes,";
        $sql .= "(SELECT COUNT(rev_codigo) FROM chk_revision,chk_lista,chk_programacion";
        $sql .= " WHERE rev_lista = list_codigo";
        $sql .= " AND rev_programacion = pro_codigo";
        $sql .= " AND list_categoria = cat_codigo";
        if (strlen($sede) > 0) {
            $sql .= " AND pro_sede IN($sede)";
        }
        if (strlen($area) > 0) {
            $sql .= " AND pro_area = $area";
        }
        $sql .= " AND rev_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59' AND rev_situacion IN(1,2)) as revisiones_totales";
        $sql .= " FROM chk_categoria";
        $sql .= " WHERE cat_situacion = 1";
        if (strlen($categoria) > 0) {
            $sql .= " AND cat_codigo IN($categoria)";
        }
        $sql .= " ORDER BY cat_codigo ASC";

        $result = $this->exec_query($sql);
        //echo $sql."<br>";
        return $result;
    }

    function insert_revision($codigo, $lista, $programacion, $usuario, $observacion, $fechor = '')
    {

        $fechor = trim($fechor);
        if ($fechor == "") {
            $fsis = date("Y-m-d H:i:s");
        } else {
            $fsis = $this->regresa_fechaHora($fechor);
        }


        $sql = "INSERT INTO chk_revision";
        $sql .= " VALUES ($codigo,$lista,$programacion,$usuario,'$fsis','$fsis','$observacion','',1)";
        $sql .= " ON DUPLICATE KEY UPDATE";
        $sql .= " rev_observaciones = '$observacion'; ";

        //echo $sql;
        return $sql;
    }


    function cerrar_revision($codigo, $observacion, $fechor = '')
    {
        $observacion = trim($observacion);
        $fechor = trim($fechor);
        if ($fechor == "") {
            $fsis = date("Y-m-d H:i:s");
        } else {
            $fsis = $this->regresa_fechaHora($fechor);
        }

        $sql = "UPDATE chk_revision SET ";
        $sql .= "rev_situacion = 2,";
        $sql .= "rev_fecha_final = '$fsis',";
        $sql .= "rev_observaciones = '$observacion'";

        $sql .= " WHERE rev_codigo = $codigo; ";
        //echo $sql."<br>";
        return $sql;
    }


    function firma_revision($codigo, $firma)
    {

        $sql = "UPDATE chk_revision SET rev_firma = '$firma'";
        $sql .= " WHERE rev_codigo = $codigo; ";

        return $sql;
    }


    function cambia_sit_revision($codigo, $situacion)
    {

        $sql = "UPDATE chk_revision SET rev_situacion = $situacion";
        $sql .= " WHERE rev_codigo = $codigo; ";

        return $sql;
    }

    function max_revision()
    {
        $sql = "SELECT max(rev_codigo) as max ";
        $sql .= " FROM chk_revision";
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $max = $row["max"];
        }
        //echo $sql;
        return $max;
    }

    /////////////////////////////  RESPUESTAS  //////////////////////////////////////

    function get_respuesta($revision, $lista, $pregunta)
    {

        $sql = "SELECT * ";
        $sql .= " FROM chk_revision_respuestas,chk_preguntas";
        $sql .= " WHERE resp_pregunta = pre_codigo";

        if (strlen($revision) > 0) {
            $sql .= " AND resp_revision = $revision";
        }
        if (strlen($lista) > 0) {
            $sql .= " AND resp_lista = $lista";
        }
        if (strlen($pregunta) > 0) {
            $sql .= " AND resp_pregunta = $pregunta";
        }
        $sql .= " ORDER BY resp_revision ASC, resp_pregunta ASC";

        $result = $this->exec_query($sql);
        //echo $sql;
        return $result;
    }

    function count_respuesta($revision, $lista, $pregunta)
    {
        $sql = "SELECT COUNT(*) as total";
        $sql .= " FROM chk_revision_respuestas,chk_preguntas";
        $sql .= " WHERE resp_pregunta = pre_codigo";

        if (strlen($revision) > 0) {
            $sql .= " AND resp_revision = $revision";
        }
        if (strlen($lista) > 0) {
            $sql .= " AND resp_lista = $lista";
        }
        if (strlen($pregunta) > 0) {
            $sql .= " AND resp_pregunta = $pregunta";
        }
        //echo $sql;
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $total = $row['total'];
        }
        return $total;
    }

    function get_resultados($codigo, $lista = '', $usuario = '', $sede = '', $sector = '', $area = '', $categoria = '', $fini = '', $ffin = '', $situacion = '', $respuesta = '')
    {
        $sede = ($sede == "") ? $_SESSION["sedes_in"] : $sede;

        $sql = " SELECT * ";
        $sql .= " FROM chk_revision_respuestas,chk_preguntas,chk_revision,chk_lista,chk_categoria,chk_programacion";
        $sql .= " WHERE resp_pregunta = pre_codigo";
        $sql .= " AND resp_revision = rev_codigo";
        $sql .= " AND resp_lista = rev_lista";
        $sql .= " AND rev_lista = list_codigo";
        $sql .= " AND cat_codigo = list_categoria";
        $sql .= " AND rev_programacion = pro_codigo";
        $sql .= " AND pre_situacion = 1";

        if (strlen($codigo) > 0) {
            $sql .= " AND rev_codigo = $codigo";
        }
        if (strlen($lista) > 0) {
            $sql .= " AND rev_lista = $lista";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND rev_usuario = $usuario";
        }
        if (strlen($sede) > 0) {
            $sql .= " AND pro_sede IN($sede)";
        }
        if (strlen($sector) > 0) {
            $sql .= " AND pro_sector = $sector";
        }
        if (strlen($area) > 0) {
            $sql .= " AND pro_area = $area";
        }
        if (strlen($categoria) > 0) {
            $sql .= " AND list_categoria IN($categoria)";
        }
        if ($fini != "" && $ffin != "") {
            $fini = $this->regresa_fecha($fini);
            $ffin = $this->regresa_fecha($ffin);
            $sql .= " AND rev_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND rev_situacion IN($situacion)";
        }
        if (strlen($respuesta) > 0) {
            $sql .= " AND resp_respuesta IN($respuesta)";
        }
        $sql .= " ORDER BY resp_revision ASC, resp_pregunta ASC";

        $result = $this->exec_query($sql);
        //echo $sql;
        return $result;
    }

    function count_resultados($codigo, $lista = '', $usuario = '', $sede = '', $sector = '', $area = '', $categoria = '', $fini = '', $ffin = '', $situacion = '', $respuesta = '')
    {
        $sede = ($sede == "") ? $_SESSION["sedes_in"] : $sede;

        $sql = " SELECT COUNT(resp_respuesta) as total";
        $sql .= " FROM chk_revision_respuestas,chk_preguntas,chk_revision,chk_lista,chk_categoria,chk_programacion";
        $sql .= " WHERE resp_pregunta = pre_codigo";
        $sql .= " AND resp_revision = rev_codigo";
        $sql .= " AND resp_lista = rev_lista";
        $sql .= " AND rev_lista = list_codigo";
        $sql .= " AND cat_codigo = list_categoria";
        $sql .= " AND rev_programacion = pro_codigo";
        $sql .= " AND pre_situacion = 1";

        if (strlen($codigo) > 0) {
            $sql .= " AND rev_codigo = $codigo";
        }
        if (strlen($lista) > 0) {
            $sql .= " AND rev_lista = $lista";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND rev_usuario = $usuario";
        }
        if (strlen($sede) > 0) {
            $sql .= " AND pro_sede IN($sede)";
        }
        if (strlen($sector) > 0) {
            $sql .= " AND pro_sector = $sector";
        }
        if (strlen($area) > 0) {
            $sql .= " AND pro_area = $area";
        }
        if (strlen($categoria) > 0) {
            $sql .= " AND list_categoria IN($categoria)";
        }
        if ($fini != "" && $ffin != "") {
            $fini = $this->regresa_fecha($fini);
            $ffin = $this->regresa_fecha($ffin);
            $sql .= " AND rev_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND rev_situacion IN($situacion)";
        }
        if (strlen($respuesta) > 0) {
            $sql .= " AND resp_respuesta IN($respuesta)";
        }

        //echo $sql."<br><br>";
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $total = $row['total'];
        }
        return $total;
    }

    function insert_respuesta($codigo, $lista, $pregunta, $respuesta)
    {
        $fsis = date("Y-m-d H:i:s");

        $sql = "INSERT INTO chk_revision_respuestas";
        $sql .= " VALUES ($codigo,$lista,$pregunta,$respuesta,'$fsis')";
        $sql .= " ON DUPLICATE KEY UPDATE";
        $sql .= " resp_respuesta = '$respuesta', ";
        $sql .= " resp_fecha_registro = '$fsis'; ";
        //echo $sql;
        return $sql;
    }

    /////////////////////////////  FOTOS  //////////////////////////////////////

    function get_fotos($codigo, $revision)
    {

        $sql = "SELECT * ";
        $sql .= " FROM chk_fotos_revision";
        $sql .= " WHERE 1 = 1";
        if (strlen($codigo) > 0) {
            $sql .= " AND fot_codigo = $codigo";
        }
        if (strlen($revision) > 0) {
            $sql .= " AND fot_revision IN($revision)";
        }
        $sql .= " ORDER BY fot_codigo ASC";

        $result = $this->exec_query($sql);
        //echo $sql."<br>";
        return $result;
    }

    function insert_foto($codigo, $revision, $foto)
    {
        $fsis = date("Y-m-d H:i:s");

        $sql = "INSERT INTO chk_fotos_revision";
        $sql .= " VALUES ($codigo,$revision,'$foto','$fsis')";
        $sql .= " ON DUPLICATE KEY UPDATE";
        $sql .= " fot_foto = '$foto', ";
        $sql .= " fot_fecha_registro = '$fsis'; ";
        //echo $sql;
        return $sql;
    }

    function delete_foto($codigo)
    {

        $sql = "DELETE FROM chk_fotos_revision";
        $sql .= " WHERE fot_codigo = $codigo; ";

        return $sql;
    }

    function max_foto()
    {
        $sql = "SELECT max(fot_codigo) as max ";
        $sql .= " FROM chk_fotos_revision";
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $max = $row["max"];
        }
        //echo $sql;
        return $max;
    }

    /////////////////////////////  REVISONES INDICADOR //////////////////////////////////////

    function get_revision_indicador($codigo = '', $indicador = '',  $programacion = '', $usuario = '', $fini = '', $ffin = '', $situacion = '1,2', $orderfecha = 'DESC', $proceso = '', $sistema = '')
    {

        $sql = "SELECT * ";
        $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE ind_usuario = usu_id) as ind_usuario";
        $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE pro_usuario = usu_id) as pro_usuario";
        $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE rev_usuario = usu_id) as rev_usuario";
        $sql .= " ,(SELECT fic_nombre FROM pro_ficha WHERE obj_proceso = fic_codigo) as obj_proceso";
        $sql .= " ,(SELECT sis_nombre FROM pro_sistema WHERE obj_sistema = sis_codigo) as obj_sistema";
        $sql .= " ,(SELECT umed_desc_lg FROM mast_unidad_medida WHERE umed_codigo = ind_unidad_medida) as medida_nombre";
        $sql .= " FROM ind_revision, ind_indicador, ind_programacion, pro_objetivos";
        $sql .= " WHERE rev_programacion = pro_codigo";
        $sql .= " AND pro_indicador = ind_codigo";
        $sql .= " AND ind_objetivo = obj_codigo";

        if (strlen($codigo) > 0) {
            $sql .= " AND rev_codigo = $codigo";
        }
        if (strlen($programacion) > 0) {
            $sql .= " AND rev_programacion = $programacion";
        }
        if (strlen($proceso) > 0) {
            $sql .= " AND obj_proceso = $proceso";
        }
        if (strlen($sistema) > 0) {
            $sql .= " AND obj_sistema = $sistema";
        }
        if (strlen($indicador) > 0) {
            $sql .= " AND pro_indicador = $indicador";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND rev_usuario = $usuario";
        }
        if ($fini != "" && $ffin != "") {
            $fini = $this->regresa_fecha($fini);
            $ffin = $this->regresa_fecha($ffin);
            $sql .= " AND rev_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND rev_situacion IN($situacion)";
        }
        $sql .= " ORDER BY rev_fecha_inicio $orderfecha, ind_codigo ASC;";

        $result = $this->exec_query($sql);
        // echo ($sql.'<br>');
        return $result;
    }

    function count_revision_indicador($codigo = '', $indicador = '', $programacion = '', $proceso = '', $sistema = '', $usuario = '', $fini = '', $ffin = '', $situacion = '1,2')
    {
        $sql = "SELECT COUNT(*) as total";
        $sql .= " FROM ind_revision, ind_indicador, ind_programacion, pro_objetivos";
        $sql .= " WHERE rev_programacion = pro_codigo";
        $sql .= " AND pro_indicador = ind_codigo";
        $sql .= " AND ind_objetivo = obj_codigo";

        if (strlen($codigo) > 0) {
            $sql .= " AND rev_codigo = $codigo";
        }
        if (strlen($programacion) > 0) {
            $sql .= " AND rev_programacion = $programacion";
        }
        if (strlen($proceso) > 0) {
            $sql .= " AND obj_proceso = $proceso";
        }
        if (strlen($sistema) > 0) {
            $sql .= " AND obj_sistema = $sistema";
        }
        if (strlen($indicador) > 0) {
            $sql .= " AND pro_indicador = $indicador";
        }
        if (strlen($usuario) > 0) {
            $sql .= " AND rev_usuario = $usuario";
        }
        if ($fini != "" && $ffin != "") {
            $fini = $this->regresa_fecha($fini);
            $ffin = $this->regresa_fecha($ffin);
            $sql .= " AND rev_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'";
        }
        if (strlen($situacion) > 0) {
            $sql .= " AND rev_situacion IN($situacion)";
        }
        // echo $sql;
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $total = $row['total'];
        }
        return $total;
    }

    function max_revision_indicador()
    {
        $sql = "SELECT max(rev_codigo) as max ";
        $sql .= " FROM ind_revision";
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $max = $row["max"];
        }
        //echo $sql;
        return $max;
    }

    function insert_revision_indicador($codigo, $programacion, $lectura, $usuario, $observacion = '', $fechor = '')
    {

        $fechor = trim($fechor);
        if ($fechor == "") {
            $fsis = date("Y-m-d H:i:s");
        } else {
            $fsis = $this->regresa_fechaHora($fechor);
        }

        $sql = "INSERT INTO ind_revision";
        $sql .= " VALUES ($codigo,$programacion,$lectura,'$fsis','$fsis','$observacion',$usuario,1)";
        $sql .= " ON DUPLICATE KEY UPDATE";
        $sql .= " rev_lectura = $lectura, ";
        $sql .= " rev_usuario = $usuario, ";
        $sql .= " rev_observaciones = '$observacion', ";
        $sql .= " rev_fecha_final = '$fsis'; ";

        //echo $sql;
        return $sql;
    }

    function situacion_revision_indicador($codigo, $situacion)
    {
        $sql = "UPDATE ind_revision";
        $sql .= " SET rev_situacion = $situacion ";
        $sql .= " WHERE rev_codigo = $codigo; ";
        //echo $sql."<br>";
        return $sql;
    }

    function modifica_revision_indicador($codigo, $campo, $valor)
    {
        $sql = "UPDATE ind_revision";
        $sql .= " SET $campo = '$valor' ";
        $sql .= " WHERE rev_codigo = $codigo; ";
        //echo $sql."<br>";
        return $sql;
    }

    function get_archivo($codigo, $revision, $posicion = '')
    {

        $sql = "SELECT * ";
        $sql .= " FROM ind_archivo_revision";
        $sql .= " WHERE 1 = 1";
        if (strlen($codigo) > 0) {
            $sql .= " AND arc_codigo = $codigo";
        }
        if (strlen($revision) > 0) {
            $sql .= " AND arc_revision IN($revision)";
        }
        if (strlen($posicion) > 0) {
            $sql .= " AND arc_posicion = $posicion";
        }
        $sql .= " ORDER BY arc_revision ASC, arc_posicion ASC;";

        $result = $this->exec_query($sql);
        // echo $sql;
        return $result;
    }

    function insert_archivo($codigo, $revision, $posicion, $archivo)
    {

        $sql = "INSERT INTO ind_archivo_revision";
        $sql .= " VALUES ($codigo,$revision,$posicion,'$archivo',1)";
        $sql .= " ON DUPLICATE KEY UPDATE";
        $sql .= " arc_archivo = '$archivo'; ";
        //echo $sql;
        return $sql;
    }

    function delete_archivo($codigo)
    {

        $sql = "DELETE FROM ind_archivo_revision";
        $sql .= " WHERE arc_codigo = $codigo; ";

        return $sql;
    }

    function max_archivo()
    {
        $sql = "SELECT max(arc_codigo) as max ";
        $sql .= " FROM ind_archivo_revision";
        $result = $this->exec_query($sql);
        foreach ($result as $row) {
            $max = $row["max"];
        }
        //echo $sql;
        return $max;
    }
    /////////////////////////////  REVISONES Objetivo //////////////////////////////////////


    function modifica_revision_objetivo($codigo, $observacion)
    {
        $observacion = trim($observacion);

        $sql = "UPDATE pla_revision_objetivo SET ";
        $sql .= "rev_observacion = '$observacion'";

        $sql .= " WHERE rev_codigo = $codigo; ";
        //echo $sql;
        return $sql;
    }
}
