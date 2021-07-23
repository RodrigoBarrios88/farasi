<?php
require_once("ClsConex.php");

class ClsAuditoria extends ClsConex
{
   /* Situacion 1 = ACTIVO, 0 = INACTIVO */

   /////////////////////////////  CUESTIONARIO  //////////////////////////////////////

   function get_cuestionario($codigo, $categoria = '', $ponderacion = '', $situacion = '')
   {

      $sql = "SELECT * ";
      $sql .= " FROM aud_auditoria, aud_categoria ";
      $sql .= " WHERE audit_categoria = cat_codigo";
      if (strlen($codigo) > 0) {
         $sql .= " AND audit_codigo = $codigo";
      }
      if (strlen($categoria) > 0) {
         $sql .= " AND audit_categoria IN($categoria)";
      }
      if (strlen($ponderacion) > 0) {
         $sql .= " AND audit_ponderacion = $ponderacion";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND audit_situacion IN($situacion)";
      }
      $sql .= " ORDER BY audit_categoria ASC, audit_codigo ASC";

      $result = $this->exec_query($sql);
      //echo $sql;
      return $result;
   }
   function count_cuestionario($codigo, $categoria = '', $ponderacion = '', $situacion = '')
   {

      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM aud_auditoria, aud_categoria";
      $sql .= " WHERE audit_categoria = cat_codigo";
      if (strlen($codigo) > 0) {
         $sql .= " AND audit_codigo = $codigo";
      }
      if (strlen($categoria) > 0) {
         $sql .= " AND audit_categoria IN($categoria)";
      }
      if (strlen($ponderacion) > 0) {
         $sql .= " AND audit_categoria = $ponderacion";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND audit_situacion IN($situacion)";
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }
   function insert_cuestionario($codigo, $categoria, $nombre, $ponderacion, $criterios, $objetivo, $riesgo, $alcance)
   {
      $nombre = trim($nombre);

      $sql = "INSERT INTO aud_auditoria";
      $sql .= " VALUES ($codigo,$categoria,'$nombre',$ponderacion,'$criterios','$objetivo','$riesgo','$alcance',1);";
      //echo $sql;
      return $sql;
   }
   function modifica_cuestionario($codigo, $categoria, $nombre, $ponderacion, $criterios, $objetivo, $riesgo, $alcance)
   {
      $nombre = trim($nombre);

      $sql = "UPDATE aud_auditoria SET ";
      $sql .= "audit_categoria = '$categoria',";
      $sql .= "audit_nombre = '$nombre',";
      $sql .= "audit_ponderacion = '$ponderacion',";
      $sql .= "audit_criterios = '$criterios',";
      $sql .= "audit_objetivo = '$objetivo',";
      $sql .= "audit_riesgos = '$riesgo',";
      $sql .= "audit_alcance = '$alcance'";

      $sql .= " WHERE audit_codigo = $codigo; ";
      //echo $sql;
      return $sql;
   }
   function cambia_situacion_cuestionario($codigo, $situacion)
   {

      $sql = "UPDATE aud_auditoria SET ";
      $sql .= "audit_situacion = $situacion";

      $sql .= " WHERE audit_codigo = $codigo; ";

      return $sql;
   }
   function max_cuestionario()
   {
      $sql = "SELECT max(audit_codigo) as max ";
      $sql .= " FROM aud_auditoria";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   } /////////////////////////////  SECCIONES  //////////////////////////////////////

   function get_secciones($codigo, $auditoria, $situacion = '')
   {
      $sql = "SELECT *, CONCAT('(',sec_numero,'.) ',sec_titulo) as sec_numero_titulo";
      $sql .= " FROM aud_auditoria, aud_secciones";
      $sql .= " WHERE sec_auditoria = audit_codigo";

      if (strlen($codigo) > 0) {
         $sql .= " AND sec_codigo = $codigo";
      }
      if (strlen($auditoria) > 0) {
         $sql .= " AND sec_auditoria = $auditoria";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND sec_situacion = $situacion";
      }
      $sql .= " ORDER BY audit_codigo ASC, sec_codigo ASC";

      $result = $this->exec_query($sql);
      //echo $sql;
      return $result;
   }
   function count_secciones($codigo, $auditoria, $situacion = '')
   {
      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM aud_auditoria, aud_secciones";
      $sql .= " WHERE sec_auditoria = audit_codigo";

      if (strlen($codigo) > 0) {
         $sql .= " AND sec_codigo = $codigo";
      }
      if (strlen($auditoria) > 0) {
         $sql .= " AND sec_auditoria = $auditoria";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND sec_situacion = $situacion";
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }
   function insert_secciones($codigo, $auditoria, $numero, $titulo, $proposito)
   {
      $numero = trim($numero);
      $titulo = trim($titulo);
      $proposito = trim($proposito);

      $sql = "INSERT INTO aud_secciones";
      $sql .= " VALUES ($codigo,$auditoria,'$numero','$titulo','$proposito',1);";
      //echo $sql;
      return $sql;
   }
   function modifica_secciones($codigo, $auditoria, $numero, $titulo, $proposito)
   {
      $numero = trim($numero);
      $titulo = trim($titulo);
      $proposito = trim($proposito);

      $sql = "UPDATE aud_secciones SET ";
      $sql .= "sec_numero = '$numero',";
      $sql .= "sec_titulo = '$titulo',";
      $sql .= "sec_proposito = '$proposito'";

      $sql .= " WHERE sec_codigo = $codigo ";
      $sql .= " AND sec_auditoria = $auditoria;";
      //echo $sql;
      return $sql;
   }
   function cambia_situacion_seccion($codigo, $auditoria, $situacion)
   {

      $sql = "UPDATE aud_secciones SET ";
      $sql .= "sec_situacion = $situacion";

      $sql .= " WHERE sec_codigo = $codigo ";
      $sql .= " AND sec_auditoria = $auditoria;";

      return $sql;
   }
   function max_secciones($auditoria)
   {
      $sql = "SELECT max(sec_codigo) as max ";
      $sql .= " FROM aud_secciones";
      $sql .= " WHERE sec_auditoria = $auditoria ";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }

   /////////////////////////////  PREGUNTAS  //////////////////////////////////////

   function get_pregunta($codigo, $auditoria, $seccion = '', $situacion = '')
   {

      $sql = "SELECT * ";
      $sql .= " FROM aud_auditoria, aud_preguntas, aud_secciones";
      $sql .= " WHERE pre_auditoria = audit_codigo";
      $sql .= " AND pre_auditoria = sec_auditoria";
      $sql .= " AND pre_seccion = sec_codigo";

      if (strlen($codigo) > 0) {
         $sql .= " AND pre_codigo = $codigo";
      }
      if (strlen($auditoria) > 0) {
         $sql .= " AND pre_auditoria = $auditoria";
      }
      if (strlen($seccion) > 0) {
         $sql .= " AND pre_seccion IN($seccion)";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND pre_situacion = $situacion";
      }
      $sql .= " ORDER BY audit_codigo ASC, pre_seccion ASC, pre_codigo ASC";

      $result = $this->exec_query($sql);
      //echo $sql;
      return $result;
   }
   function count_pregunta($codigo, $auditoria, $seccion = '', $situacion = '')
   {
      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM aud_auditoria, aud_preguntas, aud_secciones";
      $sql .= " WHERE pre_auditoria = audit_codigo";
      $sql .= " AND pre_auditoria = sec_auditoria";
      $sql .= " AND pre_seccion = sec_codigo";

      if (strlen($codigo) > 0) {
         $sql .= " AND pre_codigo = $codigo";
      }
      if (strlen($auditoria) > 0) {
         $sql .= " AND pre_auditoria = $auditoria";
      }
      if (strlen($seccion) > 0) {
         $sql .= " AND pre_seccion IN($seccion)";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND pre_situacion = $situacion";
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }
   function insert_pregunta($codigo, $auditoria, $seccion, $pregunta, $tipo, $peso)
   {
      $pregunta = trim($pregunta);

      $sql = "INSERT INTO aud_preguntas";
      $sql .= " VALUES ($codigo,$auditoria,$seccion,'$pregunta',$tipo,$peso,1);";
      //echo $sql;
      return $sql;
   }
   function modifica_pregunta($codigo, $auditoria, $seccion, $pregunta, $tipo, $peso)
   {
      $pregunta = trim($pregunta);

      $sql = "UPDATE aud_preguntas SET ";
      $sql .= "pre_seccion = '$seccion',";
      $sql .= "pre_pregunta = '$pregunta',";
      $sql .= "pre_tipo = '$tipo',";
      $sql .= "pre_peso = '$peso'";

      $sql .= " WHERE pre_codigo = $codigo";
      $sql .= " AND pre_auditoria = $auditoria;";
      //echo $sql;
      return $sql;
   }
   function cambia_situacion_pregunta($codigo, $auditoria, $situacion)
   {

      $sql = "UPDATE aud_preguntas SET ";
      $sql .= "pre_situacion = $situacion";

      $sql .= " WHERE pre_codigo = $codigo";
      $sql .= " AND pre_auditoria = $auditoria;";

      return $sql;
   }
   function max_pregunta($auditoria)
   {
      $sql = "SELECT max(pre_codigo) as max ";
      $sql .= " FROM aud_preguntas";
      $sql .= " WHERE pre_auditoria = $auditoria ";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }    /////////////////////////////  PROGRAMACION  //////////////////////////////////////    
   function get_programacion($codigo, $auditoria, $sede = '', $departamento = '', $categoria = '', $fini = '', $ffin = '', $hini = '', $hfin = '', $situacion = '', $usuario = '')
   {
      $sede = ($sede == "") ? $_SESSION["sedes_in"] : $sede;

      $sql = "SELECT *, ";
      $sql .= " (SELECT dm_desc FROM mast_mundep WHERE dm_codigo = sed_municipio) as sede_municipio";
      if (strlen($usuario) > 0) {
         $sql .= ", (SELECT eje_codigo FROM aud_ejecucion WHERE eje_programacion = pro_codigo AND eje_usuario = $usuario AND eje_situacion IN(1,2) ORDER BY pro_fecha DESC LIMIT 0,1) as ejecucion_activa";
      } else {
         $sql .= ", (SELECT eje_codigo FROM aud_ejecucion WHERE eje_programacion = pro_codigo AND eje_situacion IN(1,2) ORDER BY pro_fecha DESC LIMIT 0,1) as ejecucion_activa";
      }
      $sql .= " FROM aud_auditoria, aud_programacion, aud_categoria, sis_departamento, sis_sede";
      $sql .= " WHERE pro_auditoria = audit_codigo";
      $sql .= " AND audit_categoria = cat_codigo";
      $sql .= " AND pro_sede = sed_codigo";
      $sql .= " AND pro_departamento = dep_codigo";
      $sql .= " AND audit_situacion = 1";

      if (strlen($codigo) > 0) {
         $sql .= " AND pro_codigo = $codigo";
      }
      if (strlen($auditoria) > 0) {
         $sql .= " AND pro_auditoria = $auditoria";
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
         $sql .= " AND pro_fecha BETWEEN '$fini' AND '$ffin'";
      }
      if ($hini != "" && $hfin != "") {
         $sql .= " AND pro_hora BETWEEN '$hini' AND '$hfin'";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND pro_situacion IN($situacion)";
      }
      $sql .= " ORDER BY pro_fecha ASC, pro_hora ASC, audit_categoria ASC, pro_sede ASC, pro_departamento ASC, audit_codigo ASC";

      $result = $this->exec_query($sql);
      //echo $sql."<br>";
      return $result;
   }
   function  count_programacion($codigo, $auditoria, $sede = '', $departamento = '', $categoria = '', $fini = '', $ffin = '', $hini = '', $hfin = '', $situacion = '', $usuario = '')
   {
      $sede = ($sede == "") ? $_SESSION["sedes_in"] : $sede;

      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM aud_auditoria, aud_programacion, aud_categoria, sis_departamento, sis_sede";
      $sql .= " WHERE pro_auditoria = audit_codigo";
      $sql .= " AND audit_categoria = cat_codigo";
      $sql .= " AND pro_sede = sed_codigo";
      $sql .= " AND pro_departamento = dep_codigo";
      $sql .= " AND audit_situacion = 1";

      if (strlen($codigo) > 0) {
         $sql .= " AND pro_codigo = $codigo";
      }
      if (strlen($auditoria) > 0) {
         $sql .= " AND pro_auditoria = $auditoria";
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
         $sql .= " AND pro_fecha BETWEEN '$fini' AND '$ffin'";
      }
      if ($hini != "" && $hfin != "") {
         $sql .= " AND pro_hora BETWEEN '$hini' AND '$hfin'";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND pro_situacion IN($situacion)";
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }
   function  insert_programacion($codigo, $auditoria, $sede, $departamento, $fecha, $hora, $objetivo, $riesgo, $alcance, $obs)
   {
      $obs = trim($obs);
      $fecha = $this->regresa_fecha($fecha);

      $sql = "INSERT INTO aud_programacion";
      $sql .= " VALUES ($codigo,$auditoria,$sede,$departamento,'$fecha','$hora','$objetivo','$riesgo','$alcance','$obs',1);";
      //echo $sql;
      return $sql;
   }
   function modifica_programacion($codigo, $auditoria, $sede, $departamento, $fecha, $hora, $objetivo, $riesgo, $alcance, $obs)
   {
      $obs = trim($obs);
      $fecha = $this->regresa_fecha($fecha);

      $sql = "UPDATE aud_programacion SET ";
      $sql .= "pro_auditoria = '$auditoria',";
      $sql .= "pro_sede = '$sede',";
      $sql .= "pro_departamento = '$departamento',";
      $sql .= "pro_fecha = '$fecha',";
      $sql .= "pro_hora = '$hora',";
      $sql .= "pro_objetivo = '$objetivo',";
      $sql .= "pro_riesgo = '$riesgo',";
      $sql .= "pro_alcance = '$alcance',";
      $sql .= "pro_observaciones = '$obs'";

      $sql .= " WHERE pro_codigo = $codigo;";
      //echo $sql;
      return $sql;
   }
   function cambia_situacion_programacion($codigo, $situacion)
   {

      $sql = "UPDATE aud_programacion SET ";
      $sql .= "pro_situacion = $situacion";

      $sql .= " WHERE pro_codigo = $codigo; ";

      return $sql;
   }
   function max_programacion()
   {
      $sql = "SELECT max(pro_codigo) as max ";
      $sql .= " FROM aud_programacion";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }


   /////////////////////////////  CORREOS  //////////////////////////////////////

   function get_correo($codigo, $auditoria, $sede = '')
   {

      $sql = "SELECT * ";
      $sql .= " FROM aud_correos, aud_auditoria, sis_sede";
      $sql .= " WHERE cor_auditoria = audit_codigo";
      $sql .= " AND cor_sede = sed_codigo";
      $sql .= " AND sed_situacion = 1";

      if (strlen($codigo) > 0) {
         $sql .= " AND cor_codigo = $codigo";
      }
      if (strlen($auditoria) > 0) {
         $sql .= " AND cor_auditoria = $auditoria";
      }
      if (strlen($sede) > 0) {
         $sql .= " AND cor_sede = $sede";
      }
      $sql .= " ORDER BY cor_codigo ASC, cor_sede ASC;";

      $result = $this->exec_query($sql);
      // echo $sql;
      return $result;
   }
   function count_correo($codigo, $auditoria, $sede = '')
   {
      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM aud_correos, aud_auditoria, sis_sede";
      $sql .= " WHERE cor_auditoria = audit_codigo";
      $sql .= " AND cor_sede = sed_codigo";
      $sql .= " AND sed_situacion = 1";

      if (strlen($codigo) > 0) {
         $sql .= " AND cor_codigo = $codigo";
      }
      if (strlen($auditoria) > 0) {
         $sql .= " AND cor_auditoria = $auditoria";
      }
      if (strlen($sede) > 0) {
         $sql .= " AND cor_sede = $sede";
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }
   function insert_correo($codigo, $sede, $auditoria, $nombre, $correo)
   {
      $correo = trim($correo);
      $correo = strtolower($correo);

      $sql = "INSERT INTO aud_correos";
      $sql .= " VALUES ($codigo,$sede,$auditoria,'$nombre','$correo');";
      //echo $sql;
      return $sql;
   }
   function modifica_correo($codigo, $sede, $auditoria, $nombre, $correo)
   {
      $correo = trim($correo);
      $correo = strtolower($correo);

      $sql = "UPDATE aud_correos SET ";
      $sql .= "cor_sede = '$sede',";
      $sql .= "cor_nombre = '$nombre',";
      $sql .= "cor_correo = '$correo'";

      $sql .= " WHERE cor_codigo = $codigo;";
      //echo $sql;
      return $sql;
   }


   function delete_correo($codigo)
   {

      $sql = "DELETE FROM aud_correos ";
      $sql .= " WHERE cor_codigo = $codigo;";
      //echo $sql;
      return $sql;
   }
   function max_correo()
   {
      $sql = "SELECT max(cor_codigo) as max ";
      $sql .= " FROM aud_correos";
      $sql .= " WHERE 1 = 1 ";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }

   /////////////////////////////  ACTIVIDADES  //////////////////////////////////////

   function get_actividades($codigo, $programacion, $situacion = '')
   {
      $sql = "SELECT *";
      $sql .= " FROM aud_programacion, aud_actividades";
      $sql .= " WHERE act_programacion = pro_codigo";

      if (strlen($codigo) > 0) {
         $sql .= " AND act_codigo = $codigo";
      }
      if (strlen($programacion) > 0) {
         $sql .= " AND act_programacion = $programacion";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND act_situacion = $situacion";
      }
      $sql .= " ORDER BY pro_codigo ASC, act_codigo ASC";

      $result = $this->exec_query($sql);
      //echo $sql;
      return $result;
   }
   function count_actividades($codigo, $programacion, $situacion = '')
   {
      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM aud_programacion, aud_actividades";
      $sql .= " WHERE act_programacion = pro_codigo";

      if (strlen($codigo) > 0) {
         $sql .= " AND act_codigo = $codigo";
      }
      if (strlen($programacion) > 0) {
         $sql .= " AND act_programacion = $programacion";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND act_situacion = $situacion";
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }
   function insert_actividades($codigo, $programacion, $fecha, $hora, $descripcion, $obs)
   {
      $fecha = trim($fecha);
      $fecha = $this->regresa_fecha($fecha);
      $hora = trim($hora);
      $descripcion = trim($descripcion);
      $obs = trim($obs);

      $sql = "INSERT INTO aud_actividades";
      $sql .= " VALUES ($codigo,$programacion,'$fecha','$hora','$descripcion','$obs',1);";
      //echo $sql;
      return $sql;
   }
   function modifica_actividades($codigo, $programacion, $fecha, $hora, $descripcion)
   {
      $fecha = trim($fecha);
      $fecha = $this->regresa_fecha($fecha);
      $hora = trim($hora);
      $descripcion = trim($descripcion);

      $sql = "UPDATE aud_actividades SET ";
      $sql .= "act_fecha = '$fecha',";
      $sql .= "act_hora = '$hora',";
      $sql .= "act_descripcion = '$descripcion',";
      $sql .= "act_observaciones = ''";

      $sql .= " WHERE act_codigo = $codigo ";
      $sql .= " AND act_programacion = $programacion;";

      //echo $sql;
      return $sql;
   }
   function cambia_situacion_actividad($codigo, $programacion, $situacion)
   {

      $sql = "UPDATE aud_actividades SET ";
      $sql .= "act_situacion = $situacion";

      $sql .= " WHERE act_codigo = $codigo ";
      $sql .= " AND act_programacion = $programacion;";

      return $sql;
   }
   function delete_actividad($codigo, $programacion)
   {

      $sql = "DELETE FROM aud_actividades";
      $sql .= " WHERE act_codigo = $codigo ";
      $sql .= " AND act_programacion = $programacion;";

      return $sql;
   }
   function max_actividades($programacion)
   {
      $sql = "SELECT max(act_codigo) as max ";
      $sql .= " FROM aud_actividades";
      $sql .= " WHERE act_programacion = $programacion ";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }

   /////////////////////////////  Externa  //////////////////////////////////////

   function get_externa($codigo = '', $fini = '', $ffin = '', $tipo = '', $situacion = '1')
   {
      $sql = "SELECT *";
      $sql .= ",(SELECT usu_nombre FROM seg_usuarios WHERE ext_usuario_registra = usu_id) as registra_nombre";
      $sql .= " FROM aud_externa";
      $sql .= " WHERE 1 = 1";

      if (strlen($codigo) > 0) {
         $sql .= " AND ext_codigo = $codigo";
      }
      if ($fini != "" && $ffin != "") {
         $fini = $this->regresa_fecha($fini);
         $ffin = $this->regresa_fecha($ffin);
         $sql .= " AND ext_fecha BETWEEN '$fini' AND '$ffin'";
      }
      if (strlen($tipo) > 0) {
         $sql .= " AND ext_programacion = $tipo";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND ext_situacion = $situacion";
      }
      $sql .= " ORDER BY ext_codigo ASC;";

      $result = $this->exec_query($sql);
      //echo $sql;
      return $result;
   }
   function count_externa($codigo = '', $fini = '', $ffin = '', $tipo = '', $situacion = '1')
   {
      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM  aud_externa";
      $sql .= " WHERE 1 = 1";

      if (strlen($codigo) > 0) {
         $sql .= " AND ext_codigo = $codigo";
      }
      if ($fini != "" && $ffin != "") {
         $fini = $this->regresa_fecha($fini);
         $ffin = $this->regresa_fecha($ffin);
         $sql .= " AND ext_fecha BETWEEN '$fini' AND '$ffin'";
      }
      if (strlen($tipo) > 0) {
         $sql .= " AND ext_programacion = $tipo";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND ext_situacion = $situacion";
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }
   function insert_externa($codigo, $tipo, $entidad, $objetivo, $resumen, $fecha)
   {
      $fecha = regresa_fecha($fecha);
      $usuario = $_SESSION["codigo"];
      $sql = "INSERT INTO aud_externa";
      $sql .= " VALUES ($codigo,$tipo,'$entidad','$objetivo','$resumen','$fecha',$usuario,NOW(),1);";
      //echo $sql;
      return $sql;
   }
   function modifica_externa($codigo, $tipo, $entidad, $objetivo, $resumen, $fecha)
   {
      $fecha = $this->regresa_fecha($fecha);

      $sql = "UPDATE aud_externa SET ";
      $sql .= "ext_fecha_auditoria = '$fecha',";
      $sql .= "ext_tipo = $tipo,";
      $sql .= "ext_entidad = '$entidad',";
      $sql .= "ext_objetivo = '$objetivo',";
      $sql .= "ext_resumen = '$resumen'";

      $sql .= " WHERE ext_codigo = $codigo ";

      //echo $sql;
      return $sql;
   }
   function cambia_situacion_externa($codigo, $situacion)
   {

      $sql = "UPDATE aud_externa SET ";
      $sql .= "ext_situacion = $situacion";

      $sql .= " WHERE ext_codigo = $codigo ";

      return $sql;
   }
   function max_externa()
   {
      $sql = "SELECT max(ext_codigo) as max ";
      $sql .= " FROM aud_externa";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }
   /////////////////////////////  Externa  //////////////////////////////////////

   function get_externa_detalle($codigo = '', $auditoria = '', $situacion = '1')
   {
      $sql = "SELECT *";
      $sql .= " FROM aud_externa_detalle, aud_externa, seg_usuarios";
      $sql .= " WHERE dext_auditoria = ext_codigo";
      $sql .= " AND ext_usuario_registra = usu_id"; 
      if (strlen($codigo) > 0) {
         $sql .= " AND dext_codigo = $codigo";
      }
      if (strlen($auditoria) > 0) {
         $sql .= " AND dext_auditoria = $auditoria";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND dext_situacion = $situacion";
      }
      $sql .= " ORDER BY dext_codigo ASC;";

      $result = $this->exec_query($sql);
      //echo $sql;
      return $result;
   }

   function count_externa_detalle($codigo = '', $fini = '', $ffin = '', $tipo = '', $situacion = '1')
   {
      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM aud_externa_detalle, aud_externa";
      $sql .= " WHERE dext_auditoria = ext_codigo";

      if (strlen($codigo) > 0) {
         $sql .= " AND ext_codigo = $codigo";
      }
      if ($fini != "" && $ffin != "") {
         $fini = $this->regresa_fecha($fini);
         $ffin = $this->regresa_fecha($ffin);
         $sql .= " AND ext_fecha BETWEEN '$fini' AND '$ffin'";
      }
      if (strlen($tipo) > 0) {
         $sql .= " AND ext_programacion = $tipo";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND ext_situacion = $situacion";
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }
   function insert_externa_detalle($codigo, $auditoria, $descripcion)
   {
      $sql = "INSERT INTO aud_externa_detalle";
      $sql .= " VALUES ($codigo,$auditoria,'$descripcion',0,0,1);";
      //echo $sql;
      return $sql;
   }
   
   function update_externa_detalle($codigo, $campo, $valor)
   {
      $sql = "UPDATE aud_externa_detalle";
      $sql .= " SET $campo = '$valor' ";
      $sql .= " WHERE dext_codigo = $codigo; ";
      // echo $sql."<br>"lllll;
      return $sql;
   }
   
   function modifica_externa_detalle($codigo, $descripcion)
   {
      $sql = "UPDATE aud_externa_detalle SET ";
      $sql .= "dext_descripcion = '$descripcion'";

      $sql .= " WHERE dext_codigo = $codigo ";

      //echo $sql;
      return $sql;
   }
   function cambia_situacion_externa_detalle($codigo, $situacion)
   {

      $sql = "UPDATE aud_externa_detalle SET ";
      $sql .= "dext_situacion = $situacion";

      $sql .= " WHERE dext_codigo = $codigo ";

      return $sql;
   }
   function max_externa_detalle()
   {
      $sql = "SELECT max(dext_codigo) as max ";
      $sql .= " FROM aud_externa_detalle";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }
   //////////////////// ________ ASIGNACION USUARIO - PROGRAMACION ___________ ///////////////////////
   function get_usuario_programacion($programacion, $usuario = '')
   {

      $sql = "SELECT * ";
      $sql .= " FROM aud_usuario_programacion, aud_programacion, seg_usuarios";
      $sql .= " WHERE pus_usuario = usu_id";
      $sql .= " AND pus_programacion = pro_codigo";
      if (strlen($usuario) > 0) {
         $sql .= " AND pus_usuario = $usuario";
      }
      if (strlen($programacion) > 0) {
         $sql .= " AND pus_programacion = $programacion";
      }
      $sql .= " ORDER BY usu_id ASC, pus_programacion ASC";

      $result = $this->exec_query($sql);
      //echo $sql;
      return $result;
   }
   function insert_usuario_programacion($programacion, $usuario, $tratamiento, $rol, $asignacion)
   {
      //--
      $usu_reg = $_SESSION["codigo"];
      $fec_reg = date("Y-m-d H:i:s");

      $sql = "INSERT INTO aud_usuario_programacion ";
      $sql .= " VALUES ($programacion,$usuario,'$tratamiento','$rol','$asignacion','','$fec_reg',$usu_reg)";
      $sql .= " ON DUPLICATE KEY UPDATE ";
      $sql .= "pus_fecha_registro = '$fec_reg',";
      $sql .= "pus_usuario_registro = '$usu_reg',";
      $sql .= "pus_tratamiento = '$tratamiento',";
      $sql .= "pus_rol = '$rol',";
      $sql .= "pus_asignacion = '$asignacion';";
      //echo $sql;
      return $sql;
   }
   function delete_usuario_programacion($programacion, $usuario)
   {

      $sql = "DELETE FROM aud_usuario_programacion";
      $sql .= " WHERE pus_programacion = $programacion";
      $sql .= " AND pus_usuario = $usuario;";

      return $sql;
   }


   function last_firma_usuario($programacion, $usuario)
   {
      $sql = "SELECT pus_firma as last ";
      $sql .= " FROM aud_usuario_programacion";
      $sql .= " WHERE pus_programacion = '$programacion'";
      $sql .= " AND pus_usuario = '$usuario'";
      $result = $this->exec_query($sql);
      if (is_array($result)) {
         foreach ($result as $row) {
            $last = $row["last"];
         }
      }
      //echo $sql;
      return $last;
   }


   function cambia_firma_usuario($programacion, $usuario, $firma)
   {

      $sql = "UPDATE aud_usuario_programacion SET ";
      $sql .= "pus_firma = '$firma'";

      $sql .= " WHERE pus_programacion = '$programacion'";
      $sql .= " AND pus_usuario = '$usuario'";

      return $sql;
   }
}
