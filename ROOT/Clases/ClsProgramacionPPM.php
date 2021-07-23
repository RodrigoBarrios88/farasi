<?php
require_once("ClsConex.php");

class ClsProgramacionPPM extends ClsConex
{
   /* Situacion 1 = ACTIVO, 0 = INACTIVO */

   function get_programacion($codigo, $activo = '', $usuario = '', $categoria = '', $sede = '', $sector = '', $area = '', $fini = '', $ffin = '', $updateini = '', $updatefin = '', $situacion = '')
   {
      $sede = ($sede == "") ? $_SESSION["sedes_in"] : $sede;

      $sql = "SELECT *, ";
      $sql .= " (SELECT COUNT(rep_codigo) FROM ppm_reprogramacion WHERE rep_programacion = pro_codigo) as reprogramaciones,";
      $sql .= " (SELECT COUNT(rea_codigo) FROM ppm_reasignacion WHERE rea_programacion = pro_codigo) as reasignaciones,";
      $sql .= " (SELECT dm_desc FROM mast_mundep WHERE dm_codigo = sed_municipio) as sede_municipio";
      $sql .= " FROM ppm_activo, ppm_programacion, seg_usuarios, ppm_categoria, ppm_cuestionario, sis_area, sis_sector, sis_sede, fin_moneda ";
      $sql .= " WHERE pro_activo = act_codigo";
      $sql .= " AND pro_categoria = cat_codigo";
      $sql .= " AND pro_usuario = usu_id";
      $sql .= " AND pro_cuestionario = cue_codigo";
      $sql .= " AND act_sede = sed_codigo";
      $sql .= " AND act_sector = sec_codigo";
      $sql .= " AND act_area = are_codigo";
      $sql .= " AND pro_moneda = mon_codigo";
      $sql .= " AND pro_situacion != 0";
      $sql .= " AND act_situacion != 0";
      $sql .= " AND mon_situacion != 0";

      if (strlen($codigo) > 0) {
         $sql .= " AND pro_codigo = $codigo";
      }
      if (strlen($activo) > 0) {
         $sql .= " AND pro_activo = $activo";
      }
      if (strlen($categoria) > 0) {
         $sql .= " AND pro_categoria = $categoria";
      }
      if (strlen($sede) > 0) {
         $sql .= " AND act_sede IN($sede)";
      }
      if (strlen($sector) > 0) {
         $sql .= " AND act_sector = $sector";
      }
      if (strlen($area) > 0) {
         $sql .= " AND act_area = $area";
      }
      if (strlen($usuario) > 0) {
         $sql .= " AND pro_usuario = $usuario";
      }
      if ($fini != "" && $ffin != "") {
         $fini = $this->regresa_fecha($fini);
         $ffin = $this->regresa_fecha($ffin);
         $sql .= " AND pro_fecha BETWEEN '$fini' AND '$ffin'";
      }
      if ($updateini != "" && $updatefin != "") {
         $updateini = $this->regresa_fecha($updateini);
         $updatefin = $this->regresa_fecha($updatefin);
         $sql .= " AND pro_fecha_update BETWEEN '$updateini 00:00:00' AND '$updatefin 23:59:59'";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND pro_situacion = $situacion";
      }
      $sql .= " ORDER BY pro_fecha ASC, pro_usuario ASC, act_sede ASC, act_sector ASC, act_area ASC, act_codigo ASC";

      $result = $this->exec_query($sql);
      //echo $sql."<br><br>";
      return $result;
   }


   function count_programacion($codigo, $activo = '', $usuario = '', $categoria = '', $sede = '', $sector = '', $area = '', $fini = '', $ffin = '', $updateini = '', $updatefin = '', $situacion = '')
   {
      $sede = ($sede == "") ? $_SESSION["sedes_in"] : $sede;

      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM ppm_activo, ppm_programacion, seg_usuarios, ppm_categoria, ppm_cuestionario, sis_area, sis_sector, sis_sede, fin_moneda ";
      $sql .= " WHERE pro_activo = act_codigo";
      $sql .= " AND pro_categoria = cat_codigo";
      $sql .= " AND pro_usuario = usu_id";
      $sql .= " AND pro_cuestionario = cue_codigo";
      $sql .= " AND act_sede = sed_codigo";
      $sql .= " AND act_sector = sec_codigo";
      $sql .= " AND act_area = are_codigo";
      $sql .= " AND pro_moneda = mon_codigo";
      $sql .= " AND pro_situacion != 0";
      $sql .= " AND act_situacion != 0";
      $sql .= " AND mon_situacion != 0";

      if (strlen($codigo) > 0) {
         $sql .= " AND pro_codigo = $codigo";
      }
      if (strlen($activo) > 0) {
         $sql .= " AND pro_activo = $activo";
      }
      if (strlen($categoria) > 0) {
         $sql .= " AND pro_categoria = $categoria";
      }
      if (strlen($sede) > 0) {
         $sql .= " AND act_sede IN($sede)";
      }
      if (strlen($sector) > 0) {
         $sql .= " AND act_sector = $sector";
      }
      if (strlen($area) > 0) {
         $sql .= " AND act_area = $area";
      }
      if (strlen($usuario) > 0) {
         $sql .= " AND pro_usuario = $usuario";
      }
      if ($fini != "" && $ffin != "") {
         $fini = $this->regresa_fecha($fini);
         $ffin = $this->regresa_fecha($ffin);
         $sql .= " AND pro_fecha BETWEEN '$fini' AND '$ffin'";
      }
      if ($updateini != "" && $updatefin != "") {
         $updateini = $this->regresa_fecha($updateini);
         $updatefin = $this->regresa_fecha($updatefin);
         $sql .= " AND pro_fecha_update BETWEEN '$updateini 00:00:00' AND '$updatefin 23:59:59'";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND pro_situacion = $situacion";
      }
      //echo $sql."<br><br>";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }


   function get_programacion_reporte($codigo, $activo = '', $usuario = '', $categoria = '', $sede = '', $sector = '', $area = '', $fini = '', $ffin = '', $updateini = '', $updatefin = '', $situacion = '')
   {
      $sede = ($sede == "") ? $_SESSION["sedes_in"] : $sede;

      $sql = "SELECT *, ";
      $sql .= " (SELECT dm_desc FROM mast_mundep WHERE dm_codigo = sed_municipio) as sede_municipio";
      $sql .= " FROM ppm_activo, ppm_programacion, seg_usuarios, ppm_categoria, ppm_cuestionario, sis_area, sis_sector, sis_sede, fin_moneda ";
      $sql .= " WHERE pro_activo = act_codigo";
      $sql .= " AND pro_categoria = cat_codigo";
      $sql .= " AND pro_usuario = usu_id";
      $sql .= " AND pro_cuestionario = cue_codigo";
      $sql .= " AND act_sede = sed_codigo";
      $sql .= " AND act_sector = sec_codigo";
      $sql .= " AND act_area = are_codigo";
      $sql .= " AND pro_moneda = mon_codigo";
      $sql .= " AND pro_situacion != 0";
      $sql .= " AND act_situacion != 0";
      $sql .= " AND mon_situacion != 0";

      if (strlen($codigo) > 0) {
         $sql .= " AND pro_codigo = $codigo";
      }
      if (strlen($activo) > 0) {
         $sql .= " AND pro_activo = $activo";
      }
      if (strlen($categoria) > 0) {
         $sql .= " AND pro_categoria = $categoria";
      }
      if (strlen($sede) > 0) {
         $sql .= " AND act_sede IN($sede)";
      }
      if (strlen($sector) > 0) {
         $sql .= " AND act_sector = $sector";
      }
      if (strlen($area) > 0) {
         $sql .= " AND act_area = $area";
      }
      if (strlen($usuario) > 0) {
         $sql .= " AND pro_usuario = $usuario";
      }
      if ($fini != "" && $ffin != "") {
         $fini = $this->regresa_fecha($fini);
         $ffin = $this->regresa_fecha($ffin);
         $sql .= " AND pro_fecha BETWEEN '$fini' AND '$ffin'";
      }
      if ($updateini != "" && $updatefin != "") {
         $updateini = $this->regresa_fecha($updateini);
         $updatefin = $this->regresa_fecha($updatefin);
         $sql .= " AND pro_fecha_update BETWEEN '$updateini 00:00:00' AND '$updatefin 23:59:59'";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND pro_situacion = $situacion";
      }
      $sql .= " ORDER BY act_codigo ASC, pro_fecha ASC, pro_usuario ASC, act_sede ASC, act_sector ASC, act_area ASC";

      $result = $this->exec_query($sql);
      //echo $sql."<br><br>";
      return $result;
   }


   function insert_programacion($fecha, $activo, $usuario, $categoria, $tipo, $presupuesto, $moneda, $cuestionario, $obs)
   {
      $obs = trim($obs);
      $fsis = date("Y-m-d H:i:s");
      $fecha = $this->regresa_fecha($fecha);

      $sql = "INSERT INTO ppm_programacion (pro_fecha, pro_activo, pro_usuario, pro_categoria, pro_tipo, pro_presupuesto_programado, pro_presupuesto_ejecutado, pro_moneda, pro_cuestionario, pro_observaciones_programacion, pro_observaciones_ejecucion, pro_foto1, pro_foto2, pro_firma, pro_fecha_update, pro_situacion)";
      $sql .= " VALUES ('$fecha','$activo','$usuario','$categoria','$tipo','$presupuesto','0','$moneda','$cuestionario','$obs','','','','','$fsis',1);";
      //echo $sql;
      return $sql;
   }

   function get_imagenes($codigo)
   {
      $sql = "SELECT pro_firma, pro_foto1, pro_foto2 ";
      $sql .= " FROM ppm_programacion";
      $sql .= " WHERE pro_codigo = $codigo";

      $result = $this->exec_query($sql);
      //echo $sql."<br><br>";
      return $result;
   }


   function modifica_programacion($codigo, $categoria, $fecha, $presupuesto, $moneda, $cuestionario, $obs)
   {
      $obs = trim($obs);
      $fsis = date("Y-m-d H:i:s");
      $fecha = $this->regresa_fecha($fecha);

      $sql = "UPDATE ppm_programacion SET ";
      $sql .= "pro_categoria = '$categoria',";
      $sql .= "pro_fecha = '$fecha',";
      $sql .= "pro_presupuesto_programado = '$presupuesto',";
      $sql .= "pro_moneda = '$moneda',";
      $sql .= "pro_cuestionario = '$cuestionario',";
      $sql .= "pro_fecha_update = '$fsis',";
      $sql .= "pro_observaciones_programacion = '$obs'";

      $sql .= " WHERE pro_codigo = $codigo;";
      //echo $sql;
      return $sql;
   }

   function update_programacion($codigo, $campo, $valor)
   {
      $sql = "UPDATE ppm_programacion";
      $sql .= " SET $campo = '$valor' ";
      $sql .= " WHERE pro_codigo = $codigo; ";
      //echo $sql."<br>";
      return $sql;
   }


   function ejecutar_programacion($codigo, $fechor, $obs)
   {
      $obs = trim($obs);

      $sql = "UPDATE ppm_programacion SET ";
      $sql .= "pro_fecha_update = '$fechor',";
      $sql .= "pro_situacion = 3,";
      $sql .= "pro_observaciones_ejecucion = '$obs'";

      $sql .= " WHERE pro_codigo = $codigo;";
      //echo $sql;
      return $sql;
   }

   function foto_inicio_programacion($codigo, $foto)
   {

      $sql = "UPDATE ppm_programacion SET ";
      $sql .= "pro_foto1 = '$foto'";

      $sql .= " WHERE pro_codigo = $codigo; ";

      return $sql;
   }

   function foto_final_programacion($codigo, $foto)
   {
      $sql = "UPDATE ppm_programacion SET ";
      $sql .= "pro_foto2 = '$foto'";

      $sql .= " WHERE pro_codigo = $codigo; ";

      return $sql;
   }

   function firma_programacion($codigo, $firma)
   {
      $sql = "UPDATE ppm_programacion SET ";
      $sql .= "pro_firma = '$firma'";

      $sql .= " WHERE pro_codigo = $codigo; ";

      return $sql;
   }

   function update_observaciones_ejecucion($codigo, $observaciones)
   {
      $sql = "UPDATE ppm_programacion SET ";
      $sql .= "pro_observaciones_ejecucion = '$observaciones'";

      $sql .= " WHERE pro_codigo = $codigo; ";

      return $sql;
   }


   function update_presupuesto_ejecucion($codigo, $presupuesto, $observaciones)
   {
      $sql = "UPDATE ppm_programacion SET ";
      $sql .= "pro_presupuesto_ejecutado = '$presupuesto',";
      $sql .= "pro_observaciones_ejecucion = '$observaciones'";

      $sql .= " WHERE pro_codigo = $codigo; ";

      return $sql;
   }

   function cambia_sit_programacion($codigo, $fechor, $situacion)
   {
      $fechor = trim($fechor);
      if ($fechor == "") {
         $fechor = date("Y-m-d H:i:s");
      } else {
         $fechor = $this->regresa_fechaHora($fechor);
      }

      $sql = "UPDATE ppm_programacion SET ";
      $sql .= "pro_fecha_update = '$fechor',";
      $sql .= "pro_situacion = $situacion";

      $sql .= " WHERE pro_codigo = $codigo; ";

      return $sql;
   }

   /////////////////////////////  RESPUESTAS CUESTIONARIO  //////////////////////////////////////

   function get_respuesta($programacion, $cuestionario, $pregunta)
   {

      $sql = "SELECT * ";
      $sql .= " FROM ppm_respuestas,ppm_preguntas";
      $sql .= " WHERE resp_pregunta = pre_codigo";
      $sql .= " AND resp_cuestionario = pre_cuestionario";

      if (strlen($programacion) > 0) {
         $sql .= " AND resp_programacion = $programacion";
      }
      if (strlen($cuestionario) > 0) {
         $sql .= " AND resp_cuestionario = $cuestionario";
      }
      if (strlen($pregunta) > 0) {
         $sql .= " AND resp_pregunta = $pregunta";
      }
      $sql .= " ORDER BY resp_programacion ASC, resp_pregunta ASC";

      $result = $this->exec_query($sql);
      //echo $sql;
      return $result;
   }


   function count_respuesta($programacion, $cuestionario, $pregunta)
   {
      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM ppm_respuestas,ppm_preguntas";
      $sql .= " WHERE resp_pregunta = pre_codigo";
      $sql .= " AND resp_cuestionario = pre_cuestionario";

      if (strlen($programacion) > 0) {
         $sql .= " AND resp_programacion = $programacion";
      }
      if (strlen($cuestionario) > 0) {
         $sql .= " AND resp_cuestionario = $cuestionario";
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


   function insert_respuesta($programacion, $cuestionario, $pregunta, $respuesta)
   {
      $fsis = date("Y-m-d H:i:s");

      $sql = "INSERT INTO ppm_respuestas";
      $sql .= " VALUES ($programacion,$cuestionario,$pregunta,'$respuesta','$fsis')";
      $sql .= " ON DUPLICATE KEY UPDATE";
      $sql .= " resp_respuesta = '$respuesta', ";
      $sql .= " resp_fecha_registro = '$fsis'; ";
      //echo $sql;
      return $sql;
   }


   function update_respuesta($programacion, $cuestionario, $pregunta, $respuesta)
   {
      $fsis = date("Y-m-d H:i:s");

      $sql = "UPDATE ppm_respuestas SET";
      $sql .= " resp_respuesta = '$respuesta', ";
      $sql .= " resp_fecha_registro = '$fsis' ";
      $sql .= " WHERE resp_cuestionario = $cuestionario ";
      $sql .= " AND resp_pregunta = $pregunta ";
      $sql .= " AND resp_programacion = $programacion;";
      //echo $sql;
      return $sql;
   }


   /////////////////////////////  RE-PROGRAMACION  //////////////////////////////////////

   function get_reprogramacion($codigo, $programacion = '', $activo = '', $usuario = '', $categoria = '', $sede = '', $sector = '', $area = '', $fantesini = '', $fantesfin = '', $fnuevaini = '', $fnuevafin = '', $situacion = '')
   {

      $sql = "SELECT * ";
      $sql .= " FROM ppm_reprogramacion, ppm_activo, ppm_programacion, seg_usuarios, ppm_categoria, ppm_cuestionario, sis_area, sis_sector, sis_sede, fin_moneda ";
      $sql .= " WHERE rep_programacion = pro_codigo";
      $sql .= " AND pro_activo = act_codigo";
      $sql .= " AND pro_categoria = cat_codigo";
      $sql .= " AND rep_usuario_registro = usu_id";
      $sql .= " AND pro_cuestionario = cue_codigo";
      $sql .= " AND act_sede = sed_codigo";
      $sql .= " AND act_sector = sec_codigo";
      $sql .= " AND act_area = are_codigo";
      $sql .= " AND pro_moneda = mon_codigo";
      $sql .= " AND pro_situacion != 0";
      $sql .= " AND act_situacion != 0";
      $sql .= " AND mon_situacion != 0";

      if (strlen($codigo) > 0) {
         $sql .= " AND rep_codigo = $codigo";
      }
      if (strlen($programacion) > 0) {
         $sql .= " AND rep_programacion = $programacion";
      }
      if (strlen($activo) > 0) {
         $sql .= " AND pro_activo = $activo";
      }
      if (strlen($categoria) > 0) {
         $sql .= " AND pro_categoria = $categoria";
      }
      if (strlen($sede) > 0) {
         $sql .= " AND act_sede IN($sede)";
      }
      if (strlen($sector) > 0) {
         $sql .= " AND act_sector = $sector";
      }
      if (strlen($area) > 0) {
         $sql .= " AND act_area = $area";
      }
      if (strlen($usuario) > 0) {
         $sql .= " AND rep_usuario_registro = $usuario";
      }
      if ($fantesini != "" && $fantesfin != "") {
         $fantesini = $this->regresa_fecha($fantesini);
         $fantesfin = $this->regresa_fecha($fantesfin);
         $sql .= " AND rep_fecha_anterior BETWEEN '$fantesini' AND '$fantesfin'";
      }
      if ($fnuevaini != "" && $fnuevafin != "") {
         $fnuevaini = $this->regresa_fecha($fnuevaini);
         $fnuevafin = $this->regresa_fecha($fnuevafin);
         $sql .= " AND rep_fecha_nueva BETWEEN '$fnuevaini' AND '$fnuevafin'";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND pro_situacion = $situacion";
      }
      $sql .= " ORDER BY pro_fecha ASC, pro_usuario ASC, act_sede ASC, act_sector ASC, act_area ASC, act_codigo ASC";

      $result = $this->exec_query($sql);
      //echo $sql;
      return $result;
   }


   function count_reprogramacion($codigo, $programacion = '', $activo = '', $usuario = '', $categoria = '', $sede = '', $sector = '', $area = '', $fantesini = '', $fantesfin = '', $fnuevaini = '', $fnuevafin = '', $situacion = '')
   {
      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM ppm_reprogramacion, ppm_activo, ppm_programacion, seg_usuarios, ppm_categoria, ppm_cuestionario, sis_area, sis_sector, sis_sede, fin_moneda ";
      $sql .= " WHERE rep_programacion = pro_codigo";
      $sql .= " AND pro_activo = act_codigo";
      $sql .= " AND pro_categoria = cat_codigo";
      $sql .= " AND rep_usuario_registro = usu_id";
      $sql .= " AND pro_cuestionario = cue_codigo";
      $sql .= " AND act_sede = sed_codigo";
      $sql .= " AND act_sector = sec_codigo";
      $sql .= " AND act_area = are_codigo";
      $sql .= " AND pro_moneda = mon_codigo";
      $sql .= " AND pro_situacion != 0";
      $sql .= " AND act_situacion != 0";
      $sql .= " AND mon_situacion != 0";

      if (strlen($codigo) > 0) {
         $sql .= " AND rep_codigo = $codigo";
      }
      if (strlen($programacion) > 0) {
         $sql .= " AND rep_programacion = $programacion";
      }
      if (strlen($activo) > 0) {
         $sql .= " AND pro_activo = $activo";
      }
      if (strlen($categoria) > 0) {
         $sql .= " AND pro_categoria = $categoria";
      }
      if (strlen($sede) > 0) {
         $sql .= " AND act_sede IN($sede)";
      }
      if (strlen($sector) > 0) {
         $sql .= " AND act_sector = $sector";
      }
      if (strlen($area) > 0) {
         $sql .= " AND act_area = $area";
      }
      if (strlen($usuario) > 0) {
         $sql .= " AND rep_usuario_registro = $usuario";
      }
      if ($fantesini != "" && $fantesfin != "") {
         $fantesini = $this->regresa_fecha($fantesini);
         $fantesfin = $this->regresa_fecha($fantesfin);
         $sql .= " AND rep_fecha_anterior BETWEEN '$fantesini' AND '$fantesfin'";
      }
      if ($fnuevaini != "" && $fnuevafin != "") {
         $fnuevaini = $this->regresa_fecha($fnuevaini);
         $fnuevafin = $this->regresa_fecha($fnuevafin);
         $sql .= " AND rep_fecha_nueva BETWEEN '$fnuevaini' AND '$fnuevafin'";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND pro_situacion = $situacion";
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }


   function insert_reprogramacion($codigo, $programacion, $fecantes, $fecnueva, $justificacion)
   {
      $usureg = $_SESSION["codigo"];
      $fsis = date("Y-m-d H:i:s");
      $fecantes = $this->regresa_fecha($fecantes);
      $fecnueva = $this->regresa_fecha($fecnueva);

      $sql = "INSERT INTO ppm_reprogramacion";
      $sql .= " VALUES ($codigo,$programacion,'$fecantes','$fecnueva','$justificacion','$fsis','$usureg')";
      $sql .= " ON DUPLICATE KEY UPDATE";
      $sql .= " rep_fecha_anterior = '$fecantes', ";
      $sql .= " rep_fecha_nueva = '$fecnueva', ";
      $sql .= " rep_justificacion = '$justificacion', ";
      $sql .= " rep_fecha_registro = '$fsis', ";
      $sql .= " rep_usuario_registro = '$usureg'; ";

      $sql .= "UPDATE ppm_programacion SET";
      $sql .= " pro_fecha = '$fecnueva' ";
      $sql .= " WHERE pro_codigo = $programacion; ";

      //echo $sql;
      return $sql;
   }


   function update_reprogramacion($codigo, $programacion, $fecantes, $fecnueva, $justificacion)
   {
      $usureg = $_SESSION["codigo"];
      $fsis = date("Y-m-d H:i:s");
      $fecantes = $this->regresa_fecha($fecantes);
      $fecnueva = $this->regresa_fecha($fecnueva);

      $sql = "UPDATE ppm_reprogramacion SET";
      $sql .= " rep_fecha_anterior = '$fecantes', ";
      $sql .= " rep_fecha_nueva = '$fecnueva', ";
      $sql .= " rep_justificacion = '$justificacion', ";
      $sql .= " rep_fecha_registro = '$fsis', ";
      $sql .= " rep_usuario_registro = '$usureg' ";

      $sql .= " WHERE rep_codigo = $codigo ";
      $sql .= " AND rep_programacion = $programacion;";
      //echo $sql;
      return $sql;
   }


   function delete_reprogramacion($codigo, $programacion)
   {

      $sql = "DELETE FROM ppm_reprogramacion ";
      $sql .= " WHERE rep_codigo = $codigo ";
      $sql .= " AND rep_programacion = $programacion;";

      return $sql;
   }


   function max_reprogramacion($programacion)
   {
      $sql = "SELECT max(rep_codigo) as max ";
      $sql .= " FROM ppm_reprogramacion";
      $sql .= " WHERE rep_programacion = $programacion";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }

   /////////////////////////////  RE-ASIGNACION  //////////////////////////////////////

   function get_reasignacion($codigo, $programacion = '', $activo = '', $usuario_antes = '', $usuario_nuevo = '', $categoria = '', $sede = '', $sector = '', $area = '', $fregini = '', $fregfin = '', $situacion = '')
   {

      $sql = "SELECT *, ";
      $sql .= " (SELECT usu_nombre FROM seg_usuarios WHERE rea_usuario_anterior = usu_id) as nombre_usuario_anterior,";
      $sql .= " (SELECT usu_nombre FROM seg_usuarios WHERE rea_usuario_nuevo = usu_id) as nombre_usuario_nuevo";
      $sql .= " FROM ppm_reasignacion, ppm_activo, ppm_programacion, seg_usuarios, ppm_categoria, ppm_cuestionario, sis_area, sis_sector, sis_sede, fin_moneda ";
      $sql .= " WHERE rea_programacion = pro_codigo";
      $sql .= " AND pro_activo = act_codigo";
      $sql .= " AND rea_usuario_registro = usu_id";
      $sql .= " AND pro_categoria = cat_codigo";
      $sql .= " AND pro_cuestionario = cue_codigo";
      $sql .= " AND act_sede = sed_codigo";
      $sql .= " AND act_sector = sec_codigo";
      $sql .= " AND act_area = are_codigo";
      $sql .= " AND pro_moneda = mon_codigo";
      $sql .= " AND pro_situacion != 0";
      $sql .= " AND act_situacion != 0";
      $sql .= " AND mon_situacion != 0";

      if (strlen($codigo) > 0) {
         $sql .= " AND rea_codigo = $codigo";
      }
      if (strlen($programacion) > 0) {
         $sql .= " AND rea_programacion = $programacion";
      }
      if (strlen($activo) > 0) {
         $sql .= " AND pro_activo = $activo";
      }
      if (strlen($categoria) > 0) {
         $sql .= " AND pro_categoria = $categoria";
      }
      if (strlen($sede) > 0) {
         $sql .= " AND act_sede IN($sede)";
      }
      if (strlen($sector) > 0) {
         $sql .= " AND act_sector = $sector";
      }
      if (strlen($area) > 0) {
         $sql .= " AND act_area = $area";
      }
      if (strlen($usuario_antes) > 0) {
         $sql .= " AND rea_usuario_anterior = $usuario_antes";
      }
      if (strlen($usuario_nuevo) > 0) {
         $sql .= " AND rea_usuario_nuevo = $usuario_nuevo";
      }
      if ($fregini != "" && $fregfin != "") {
         $fregini = $this->regresa_fecha($fregini);
         $fregfin = $this->regresa_fecha($fregfin);
         $sql .= " AND rea_fecha_registro BETWEEN '$fregini 00:00:00' AND '$fregfin 23:59:59'";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND pro_situacion = $situacion";
      }
      $sql .= " ORDER BY pro_fecha ASC, rea_usuario_anterior ASC, act_sede ASC, act_sector ASC, act_area ASC, act_codigo ASC";

      $result = $this->exec_query($sql);
      //echo $sql;
      return $result;
   }


   function count_reasignacion($codigo, $programacion = '', $activo = '', $usuario_antes = '', $usuario_nuevo = '', $categoria = '', $sede = '', $sector = '', $area = '', $fregini = '', $fregfin = '', $situacion = '')
   {
      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM ppm_reasignacion, ppm_activo, ppm_programacion, seg_usuarios, ppm_categoria, ppm_cuestionario, sis_area, sis_sector, sis_sede, fin_moneda ";
      $sql .= " WHERE rea_programacion = pro_codigo";
      $sql .= " AND pro_activo = act_codigo";
      $sql .= " AND rea_usuario_registro = usu_id";
      $sql .= " AND pro_categoria = cat_codigo";
      $sql .= " AND pro_cuestionario = cue_codigo";
      $sql .= " AND act_sede = sed_codigo";
      $sql .= " AND act_sector = sec_codigo";
      $sql .= " AND act_area = are_codigo";
      $sql .= " AND pro_moneda = mon_codigo";
      $sql .= " AND pro_situacion != 0";
      $sql .= " AND act_situacion != 0";
      $sql .= " AND mon_situacion != 0";

      if (strlen($codigo) > 0) {
         $sql .= " AND rea_codigo = $codigo";
      }
      if (strlen($programacion) > 0) {
         $sql .= " AND rea_programacion = $programacion";
      }
      if (strlen($activo) > 0) {
         $sql .= " AND pro_activo = $activo";
      }
      if (strlen($categoria) > 0) {
         $sql .= " AND pro_categoria = $categoria";
      }
      if (strlen($sede) > 0) {
         $sql .= " AND act_sede IN($sede)";
      }
      if (strlen($sector) > 0) {
         $sql .= " AND act_sector = $sector";
      }
      if (strlen($area) > 0) {
         $sql .= " AND act_area = $area";
      }
      if (strlen($usuario_antes) > 0) {
         $sql .= " AND rea_usuario_anterior = $usuario_antes";
      }
      if (strlen($usuario_nuevo) > 0) {
         $sql .= " AND rea_usuario_nuevo = $usuario_nuevo";
      }
      if ($fregini != "" && $fregfin != "") {
         $fregini = $this->regresa_fecha($fregini);
         $fregfin = $this->regresa_fecha($fregfin);
         $sql .= " AND rea_fecha_registro BETWEEN '$fregini 00:00:00' AND '$fregfin 23:59:59'";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND pro_situacion = $situacion";
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }


   function insert_reasignacion($codigo, $programacion, $usuario_antes, $usuario_nuevo, $justificacion)
   {
      $usureg = $_SESSION["codigo"];
      $fsis = date("Y-m-d H:i:s");

      $sql = "INSERT INTO ppm_reasignacion";
      $sql .= " VALUES ($codigo,$programacion,$usuario_antes,$usuario_nuevo,'$justificacion','$fsis','$usureg')";
      $sql .= " ON DUPLICATE KEY UPDATE";
      $sql .= " rea_usuario_anterior = '$usuario_antes', ";
      $sql .= " rea_usuario_nuevo = '$usuario_nuevo', ";
      $sql .= " rea_justificacion = '$justificacion', ";
      $sql .= " rea_fecha_registro = '$fsis', ";
      $sql .= " rea_usuario_registro = '$usureg'; ";

      $sql .= "UPDATE ppm_programacion SET";
      $sql .= " pro_usuario = '$usuario_nuevo' ";
      $sql .= " WHERE pro_codigo = $programacion; ";

      //echo $sql;
      return $sql;
   }


   function update_reasignacion($codigo, $programacion, $usuario_antes, $usuario_nuevo, $justificacion)
   {
      $usureg = $_SESSION["codigo"];
      $fsis = date("Y-m-d H:i:s");

      $sql = "UPDATE ppm_reasignacion SET";
      $sql .= " rea_usuario_anterior = '$usuario_antes', ";
      $sql .= " rea_usuario_nuevo = '$usuario_nuevo', ";
      $sql .= " rea_justificacion = '$justificacion', ";
      $sql .= " rea_fecha_registro = '$fsis', ";
      $sql .= " rea_usuario_registro = '$usureg' ";

      $sql .= " WHERE rea_codigo = $codigo ";
      $sql .= " AND rea_programacion = $programacion;";
      //echo $sql;
      return $sql;
   }


   function delete_reasignacion($codigo, $programacion)
   {

      $sql = "DELETE FROM ppm_reasignacion ";
      $sql .= " WHERE rea_codigo = $codigo ";
      $sql .= " AND rea_programacion = $programacion;";

      return $sql;
   }


   function max_reasignacion($programacion)
   {
      $sql = "SELECT max(rea_codigo) as max ";
      $sql .= " FROM ppm_reasignacion";
      $sql .= " WHERE rea_programacion = $programacion";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }
}
