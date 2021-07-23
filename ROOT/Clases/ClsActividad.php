<?php
require_once("ClsConex.php");

class ClsActividad extends ClsConex
{
   //////////////////////////////////// RISK ///////////////////////////////////////
   function get_actividad($codigo = "", $plan = '', $tipo = '', $responsable = '',  $fecha = '', $situacion = '1', $desde = '', $hasta = '')
   {

      $sql = "SELECT * ";
      $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE pla_responsable = usu_id) as usu_nombre";
      $sql .= " FROM ryo_actividad, ryo_plan";
      $sql .= " WHERE act_plan = pla_codigo";
      if (strlen($codigo) > 0) {
         $sql .= " AND act_codigo = $codigo";
      }
      if (strlen($plan) > 0) {
         $sql .= " AND act_plan = $plan";
      }
      if (strlen($tipo) > 0) {
         $sql .= " AND act_tipo = $tipo";
      }
      if (strlen($responsable) > 0) {
         $sql .= " AND pla_responsable = $responsable";
      }
      if ($fecha != "") {
         $fecha = $this->regresa_fecha($fecha);
         $sql .= " AND '$fecha' BETWEEN act_fecha_inicio AND act_fecha_fin";
      }
      if ($desde != "" && $hasta != "") {
         $desde = $this->regresa_fecha($desde);
         $hasta = $this->regresa_fecha($hasta);
         $sql .= " AND (act_fecha_inicio BETWEEN '$desde' AND '$hasta'";
         $sql .= " OR act_fecha_fin BETWEEN '$desde' AND '$hasta')";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND act_situacion = $situacion";
      }
      $sql .= " ORDER BY act_codigo ASC;";

      $result = $this->exec_query($sql);
   // echo $sql;  
      return $result;
   }

   function count_actividad($codigo = "", $plan = '', $tipo = '', $responsable = '',  $desde = '', $hasta = '', $proceso = "", $sistema = "", $situacion = '1')
   {

      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM ryo_actividad, ryo_riesgo, pro_foda";
      $sql .= " WHERE act_riesgo = rie_codigo";
      $sql .= " AND rie_foda_elemento = fod_codigo";
      $sql .= " AND rie_proceso = fod_proceso";
      if (strlen($codigo) > 0) {
         $sql .= " AND act_codigo = $codigo";
      }
      if (strlen($plan) > 0) {
         $sql .= " AND act_plan = $plan";
      }
      if (strlen($tipo) > 0) {
         $sql .= " AND act_tipo = $tipo";
      }
      if (strlen($responsable) > 0) {
         $sql .= " AND act_responsable = $responsable";
      }
      if ($desde != "" && $hasta != "") {
         $desde = $this->regresa_fecha($desde);
         $hasta = $this->regresa_fecha($hasta);
         $sql .= " AND act_fecha_inicio BETWEEN '$desde' AND '$hasta'";
      }
      if (strlen($proceso) > 0) {
         $sql .= " AND rie_proceso = $proceso";
      }
      if (strlen($sistema) > 0) {
         $sql .= " AND fod_sistema = $sistema";
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

   function insert_actividad($codigo, $plan, $tipo, $periodicidad, $fini, $ffin, $descripcion)
   {
      $desde = regresa_fecha($fini);
      $hasta = regresa_fecha($ffin);
      $sql = "INSERT INTO ryo_actividad";
      $sql .= " VALUES ($codigo, $plan, $tipo,'$periodicidad','$desde','$hasta','$descripcion','', 1);";
      // echo $sql;<
      return $sql;
   }

   function modifica_actividad($codigo, $plan, $tipo, $periodicidad, $fini, $ffin, $descripcion)
   {
      $desde = regresa_fecha($fini);
      $hasta = regresa_fecha($ffin);
      $sql = "UPDATE ryo_actividad SET ";
      $sql .= "act_codigo = $codigo ";
      if (strlen($plan) > 0) {
         $sql .= ",act_plan = $plan";
      }
      if (strlen($tipo) > 0) {
         $sql .= ",act_tipo = $tipo";
      }
      if (strlen($periodicidad) > 0) {
         $sql .= ",act_periodicidad = '$periodicidad'";
      }
      if (strlen($desde) > 0) {
         $sql .= ",act_fecha_inicio = '$desde'";
      }
      if (strlen($hasta) > 0) {
         $sql .= ",act_fecha_fin = '$hasta'";
      }
      if (strlen($descripcion) > 0) {
         $sql .= ",act_descripcion = '$descripcion'";
      }
      $sql .= " WHERE act_codigo = $codigo;";
      //echo $sql;
      return $sql;
   }

   function cambia_situacion_actividad($codigo, $sit)
   {
      $sql = "UPDATE ryo_actividad SET ";
      $sql .= "act_situacion = $sit";
      $sql .= " WHERE act_codigo = $codigo;";

      return $sql;
   }
   function update_actividad($codigo, $campo, $valor)
   {
      $sql = "UPDATE ryo_actividad";
      $sql .= " SET $campo = '$valor' ";
      $sql .= " WHERE act_codigo = $codigo; ";
      // echo $sql."<br>";
      return $sql;
   }
   function max_actividad()
   {
      $sql = "SELECT max(act_codigo) as max ";
      $sql .= " FROM ryo_actividad";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }

   /////////////////////////////  Programacion //////////////////////////////////////

   function insert_programacion($actividad = '', $desde = '', $hasta = '', $situacion = '1')
   {
      $desde = $this->regresa_fecha($desde);
      $hasta = $this->regresa_fecha($hasta);
      $sql = "INSERT INTO ryo_programacion (pro_actividad, pro_fecha_inicio, pro_fecha_fin, pro_ejecucion, pro_fecha, pro_evaluacion, pro_puntuacion, pro_evalua, pro_fecha_evaluacion, pro_situacion)";
      $sql .= " VALUES ($actividad,'$desde','$hasta','','','',0,0,'',$situacion);";
      // echo $sql;
      return $sql;
   }

   function delete_programacion($codigo = "", $actividad = '')
   {
      $sql = "DELETE FROM ryo_programacion WHERE 1 = 1";
      if (strlen($codigo) > 0) {
         $sql .= " AND pro_codigo = $codigo";
      }
      if (strlen($actividad) > 0) {
         $sql .= " AND pro_actividad = $actividad;";
      }
      // echo $sql;
      return $sql;
   }

   function get_programacion($codigo = "", $plan = "", $actividad = "", $fecha = '', $situacion = '1,2,3,4,5', $desde = '', $hasta = '')
   {

      $sql = "SELECT * ";
      $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE pla_responsable = usu_id) as usu_nombre";
      $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE pro_evalua = usu_id) as usu_evalua";
      $sql .= " FROM ryo_programacion, ryo_actividad, ryo_plan";
      $sql .= " WHERE act_codigo = pro_actividad";
      $sql .= " AND pla_codigo = act_plan";
      if (strlen($codigo) > 0) {
         $sql .= " AND pro_codigo = $codigo";
      }
      if (strlen($actividad) > 0) {
         $sql .= " AND pro_actividad = $actividad";
      }
      if (strlen($plan) > 0) {
         $sql .= " AND act_plan = $plan";
      }
      if ($fecha != "") {
         $fecha = $this->regresa_fecha($fecha);
         $sql .= " AND '$fecha' BETWEEN pro_fecha_inicio AND pro_fecha_fin";
      }
      if ($desde != "" && $hasta != "") {
         $desde = $this->regresa_fecha($desde);
         $hasta = $this->regresa_fecha($hasta);
         $sql .= " AND (pro_fecha_inicio BETWEEN '$desde' AND '$hasta'";
         $sql .= " OR pro_fecha_fin BETWEEN '$desde' AND '$hasta')";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND pro_situacion IN($situacion)";
      }
      $sql .= " ORDER BY pro_fecha_inicio ASC;";

      $result = $this->exec_query($sql);
      // echo $sql;
      return $result;
   }

   function cambia_situacion_programacion($codigo = "", $actividad = "", $sit)
   {
      $sql = "UPDATE ryo_programacion SET ";
      $sql .= "pro_situacion = $sit";
      $sql .= " WHERE 1 = 1";
      if (strlen($codigo) > 0) {
         $sql .= " AND pro_codigo = $codigo";
      }
      if (strlen($actividad) > 0) {
         $sql .= " AND pro_actividad = $actividad";
      }
      return $sql . ";";
   }

   function count_programacion($codigo = "", $plan = '', $actividad = "", $fecha = '', $situacion = '1,2,3,4,5', $pendiente = '', $vencimiento = '', $usuario = '')
   {

      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM ryo_programacion, ryo_actividad, ryo_plan";
      $sql .= " WHERE act_codigo = pro_actividad";
      $sql .= " AND act_plan = pla_codigo";
      if (strlen($codigo) > 0) {
         $sql .= " AND pro_codigo = $codigo";
      }
      if (strlen($plan) > 0) {
         $sql .= " AND act_plan = $plan";
      }
      if (strlen($actividad) > 0) {
         $sql .= " AND pro_actividad = $actividad";
      }
      if ($fecha != "") {
         $fecha = $this->regresa_fecha($fecha);
         $sql .= " AND '$fecha' BETWEEN pro_fecha_inicio AND pro_fecha_fin";
      }
      if ($pendiente != "") {
         $pendiente = $this->regresa_fecha($pendiente);
         $sql .= " AND '$pendiente' < pro_fecha_inicio";
      }
      if ($vencimiento != "") {
         $vencimiento = $this->regresa_fecha($vencimiento);
         $sql .= " AND '$vencimiento' > pro_fecha_fin";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND pro_situacion IN($situacion)";
      }
      if (strlen($usuario) > 0) {
         $sql .= " AND act_responsable = $usuario";
      }
      // echo $sql;
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }

   function modifica_programacion($codigo, $campo, $valor)
   {
      $sql = "UPDATE ryo_programacion";
      $sql .= " SET $campo = '$valor' ";
      $sql .= " WHERE pro_codigo = $codigo; ";
      // echo $sql."<br>";
      return $sql;
   }
   /////////////////////////////////////////// Archivos ////////////////////////////////////////

   function get_archivo($codigo, $revision, $posicion = '')
   {

      $sql = "SELECT * ";
      $sql .= " FROM ryo_archivo_programacion";
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

      $sql = "INSERT INTO ryo_archivo_programacion";
      $sql .= " VALUES ($codigo,$revision,$posicion,'$archivo',1)";
      $sql .= " ON DUPLICATE KEY UPDATE";
      $sql .= " arc_archivo = '$archivo'; ";
      //echo $sql;
      return $sql;
   }

   function delete_archivo($codigo)
   {

      $sql = "DELETE FROM ryo_archivo_programacion";
      $sql .= " WHERE arc_codigo = $codigo; ";

      return $sql;
   }

   function max_archivo()
   {
      $sql = "SELECT max(arc_codigo) as max ";
      $sql .= " FROM ryo_archivo_programacion";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }

   //////////////////////////////////// Mejora ///////////////////////////////////////
   function get_actividad_mejora($codigo = "", $plan = '', $responsable = '',  $fecha = '', $situacion = '1', $desde = '', $hasta = '')
   {

      $sql = "SELECT * ";
      $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE pla_responsable = usu_id) as usu_nombre";
      $sql .= " FROM mej_actividad, mej_plan";
      $sql .= " WHERE act_plan = pla_codigo";
      if (strlen($codigo) > 0) {
         $sql .= " AND act_codigo = $codigo";
      }
      if (strlen($plan) > 0) {
         $sql .= " AND act_plan = $plan";
      }
      if (strlen($responsable) > 0) {
         $sql .= " AND pla_responsable = $responsable";
      }
      if ($fecha != "") {
         $fecha = $this->regresa_fecha($fecha);
         $sql .= " AND '$fecha' BETWEEN act_fecha_inicio AND act_fecha_fin";
      }
      if ($desde != "" && $hasta != "") {
         $desde = $this->regresa_fecha($desde);
         $hasta = $this->regresa_fecha($hasta);
         $sql .= " AND (act_fecha_inicio BETWEEN '$desde' AND '$hasta'";
         $sql .= " OR act_fecha_fin BETWEEN '$desde' AND '$hasta')";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND act_situacion = $situacion";
      }
      $sql .= " ORDER BY act_codigo ASC;";

      $result = $this->exec_query($sql);
      // echo $sql;  
      return $result;
   }

   function count_actividad_mejora($codigo = "", $plan = '', $responsable = '',  $desde = '', $hasta = '', $proceso = "", $sistema = "", $situacion = '1')
   {

      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM mej_actividad, mej_riesgo, pro_foda";
      $sql .= " WHERE act_riesgo = rie_codigo";
      $sql .= " AND rie_foda_elemento = fod_codigo";
      $sql .= " AND rie_proceso = fod_proceso";
      if (strlen($codigo) > 0) {
         $sql .= " AND act_codigo = $codigo";
      }
      if (strlen($plan) > 0) {
         $sql .= " AND act_plan = $plan";
      }
      if (strlen($responsable) > 0) {
         $sql .= " AND act_responsable = $responsable";
      }
      if ($desde != "" && $hasta != "") {
         $desde = $this->regresa_fecha($desde);
         $hasta = $this->regresa_fecha($hasta);
         $sql .= " AND act_fecha_inicio BETWEEN '$desde' AND '$hasta'";
      }
      if (strlen($proceso) > 0) {
         $sql .= " AND rie_proceso = $proceso";
      }
      if (strlen($sistema) > 0) {
         $sql .= " AND fod_sistema = $sistema";
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

   function insert_actividad_mejora($codigo, $plan, $periodicidad, $fini, $ffin, $descripcion)
   {
      $desde = regresa_fecha($fini);
      $hasta = regresa_fecha($ffin);
      $sql = "INSERT INTO mej_actividad";
      $sql .= " VALUES ($codigo, $plan, '$periodicidad','$desde','$hasta','$descripcion','', 1);";
      // echo $sql;<
      return $sql;
   }

   function modifica_actividad_mejora($codigo, $plan, $periodicidad, $fini, $ffin, $descripcion)
   {
      $desde = regresa_fecha($fini);
      $hasta = regresa_fecha($ffin);
      $sql = "UPDATE mej_actividad SET ";
      $sql .= "act_codigo = $codigo ";
      if (strlen($plan) > 0) {
         $sql .= ",act_plan = $plan";
      }
      if (strlen($periodicidad) > 0) {
         $sql .= ",act_periodicidad = '$periodicidad'";
      }
      if (strlen($desde) > 0) {
         $sql .= ",act_fecha_inicio = '$desde'";
      }
      if (strlen($hasta) > 0) {
         $sql .= ",act_fecha_fin = '$hasta'";
      }
      if (strlen($descripcion) > 0) {
         $sql .= ",act_descripcion = '$descripcion'";
      }
      $sql .= " WHERE act_codigo = $codigo;";
      //echo $sql;
      return $sql;
   }

   function cambia_situacion_actividad_mejora($codigo, $sit)
   {
      $sql = "UPDATE mej_actividad SET ";
      $sql .= "act_situacion = $sit";
      $sql .= " WHERE act_codigo = $codigo;";

      return $sql;
   }
   function update_actividad_mejora($codigo, $campo, $valor)
   {
      $sql = "UPDATE mej_actividad";
      $sql .= " SET $campo = '$valor' ";
      $sql .= " WHERE act_codigo = $codigo; ";
      // echo $sql."<br>";
      return $sql;
   }
   function max_actividad_mejora()
   {
      $sql = "SELECT max(act_codigo) as max ";
      $sql .= " FROM mej_actividad";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }

   /////////////////////////////  Programacion //////////////////////////////////////

   function insert_programacion_mejora($actividad = '', $desde = '', $hasta = '', $situacion = '1')
   {
      $desde = $this->regresa_fecha($desde);
      $hasta = $this->regresa_fecha($hasta);
      $sql = "INSERT INTO mej_programacion (pro_actividad, pro_fecha_inicio, pro_fecha_fin, pro_ejecucion, pro_fecha, pro_evaluacion, pro_puntuacion, pro_evalua, pro_fecha_evaluacion, pro_situacion)";
      $sql .= " VALUES ($actividad,'$desde','$hasta','','','',0,0,'',$situacion);";
      // echo $sql;
      return $sql;
   }

   function delete_programacion_mejora($codigo = "", $actividad = '')
   {
      $sql = "DELETE FROM mej_programacion WHERE 1 = 1";
      if (strlen($codigo) > 0) {
         $sql .= " AND pro_codigo = $codigo";
      }
      if (strlen($actividad) > 0) {
         $sql .= " AND pro_actividad = $actividad;";
      }
      // echo $sql;
      return $sql;
   }

   function get_programacion_mejora($codigo = "", $plan = "", $actividad = "", $fecha = '', $situacion = '1,2,3,4,5', $desde = '', $hasta = '')
   {

      $sql = "SELECT * ";
      $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE pla_responsable = usu_id) as usu_nombre";
      $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE pro_evalua = usu_id) as usu_evalua";
      $sql .= " FROM mej_programacion, mej_actividad, mej_plan, mej_hallazgo";
      $sql .= " WHERE act_codigo = pro_actividad";
      $sql .= " AND pla_codigo = act_plan";
      $sql .= " AND hal_codigo = pla_hallazgo";
      if (strlen($codigo) > 0) {
         $sql .= " AND pro_codigo = $codigo";
      }
      if (strlen($actividad) > 0) {
         $sql .= " AND pro_actividad = $actividad";
      }
      if (strlen($plan) > 0) {
         $sql .= " AND act_plan = $plan";
      }
      if ($fecha != "") {
         $fecha = $this->regresa_fecha($fecha);
         $sql .= " AND '$fecha' BETWEEN pro_fecha_inicio AND pro_fecha_fin";
      }
      if ($desde != "" && $hasta != "") {
         $desde = $this->regresa_fecha($desde);
         $hasta = $this->regresa_fecha($hasta);
         $sql .= " AND (pro_fecha_inicio BETWEEN '$desde' AND '$hasta'";
         $sql .= " OR pro_fecha_fin BETWEEN '$desde' AND '$hasta')";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND pro_situacion IN($situacion)";
      }
      $sql .= " ORDER BY pro_fecha_inicio ASC;";

      $result = $this->exec_query($sql);
      // echo $sql;
      return $result;
   }

   function cambia_situacion_programacion_mejora($codigo = "", $actividad = "", $sit)
   {
      $sql = "UPDATE mej_programacion SET ";
      $sql .= "pro_situacion = $sit";
      $sql .= " WHERE 1 = 1";
      if (strlen($codigo) > 0) {
         $sql .= " AND pro_codigo = $codigo";
      }
      if (strlen($actividad) > 0) {
         $sql .= " AND pro_actividad = $actividad";
      }
      return $sql . ";";
   }

   function count_programacion_mejora($codigo = "", $plan = '', $actividad = "", $fecha = '', $situacion = '1,2,3,4,5', $pendiente = '', $vencimiento = '', $usuario = '')
   {

      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM mej_programacion, mej_actividad, mej_plan";
      $sql .= " WHERE act_codigo = pro_actividad";
      $sql .= " AND act_plan = pla_codigo";
      if (strlen($codigo) > 0) {
         $sql .= " AND pro_codigo = $codigo";
      }
      if (strlen($plan) > 0) {
         $sql .= " AND act_plan = $plan";
      }
      if (strlen($actividad) > 0) {
         $sql .= " AND pro_actividad = $actividad";
      }
      if ($fecha != "") {
         $fecha = $this->regresa_fecha($fecha);
         $sql .= " AND '$fecha' BETWEEN pro_fecha_inicio AND pro_fecha_fin";
      }
      if ($pendiente != "") {
         $pendiente = $this->regresa_fecha($pendiente);
         $sql .= " AND '$pendiente' < pro_fecha_inicio";
      }
      if ($vencimiento != "") {
         $vencimiento = $this->regresa_fecha($vencimiento);
         $sql .= " AND '$vencimiento' > pro_fecha_fin";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND pro_situacion IN($situacion)";
      }
      if (strlen($usuario) > 0) {
         $sql .= " AND act_responsable = $usuario";
      }
      // echo $sql;
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }

   function modifica_programacion_mejora($codigo, $campo, $valor)
   {
      $sql = "UPDATE mej_programacion";
      $sql .= " SET $campo = '$valor' ";
      $sql .= " WHERE pro_codigo = $codigo; ";
      // echo $sql."<br>";
      return $sql;
   }
   /////////////////////////////////////////// Archivos ////////////////////////////////////////

   function get_archivo_mejora($codigo, $revision, $posicion = '')
   {

      $sql = "SELECT * ";
      $sql .= " FROM mej_archivo_programacion";
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

   function insert_archivo_mejora($codigo, $revision, $posicion, $archivo)
   {

      $sql = "INSERT INTO mej_archivo_programacion";
      $sql .= " VALUES ($codigo,$revision,$posicion,'$archivo',1)";
      $sql .= " ON DUPLICATE KEY UPDATE";
      $sql .= " arc_archivo = '$archivo'; ";
      //echo $sql;
      return $sql;
   }

   function delete_archivo_mejora($codigo)
   {

      $sql = "DELETE FROM mej_archivo_programacion";
      $sql .= " WHERE arc_codigo = $codigo; ";

      return $sql;
   }

   function max_archivo_mejora()
   {
      $sql = "SELECT max(arc_codigo) as max ";
      $sql .= " FROM mej_archivo_programacion";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }
}
