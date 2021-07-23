<?php
require_once("ClsConex.php");

class ClsAccion extends ClsConex
{

   /////////////////////////////  Accion //////////////////////////////////////

   function get_accion($codigo = "", $objetivo = "", $proceso = '', $usuario = '', $tipo = "", $sistema = "", $desde = '', $hasta = '', $situacion = '1')
   {

      $sql = "SELECT * ";
      $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE acc_usuario = usu_id) as acc_usuario";
      $sql .= " ,(SELECT fic_nombre FROM pro_ficha WHERE obj_proceso = fic_codigo) as obj_proceso";
      $sql .= " ,(SELECT sis_nombre FROM pro_sistema WHERE obj_sistema = sis_codigo) as obj_sistema";
      $sql .= " FROM pla_accion, pro_objetivos";
      $sql .= " WHERE acc_objetivo = obj_codigo";
      if (strlen($codigo) > 0) {
         $sql .= " AND acc_codigo = $codigo";
      }
      if (strlen($objetivo) > 0) {
         $sql .= " AND obj_codigo = $objetivo";
      }
      if (strlen($proceso) > 0) {
         $sql .= " AND acc_objetivo = $proceso";
      }
      if (strlen($usuario) > 0) {
         $sql .= " AND acc_usuario = $usuario";
      }
      if (strlen($tipo) > 0) {
         $sql .= " AND obj_tipo = $tipo";
      }
      if ($desde != "" && $hasta != "") {
         $desde = $this->regresa_fecha($desde);
         $hasta = $this->regresa_fecha($hasta);
         $sql .= " AND obj_fecha_registro BETWEEN '$desde' AND '$hasta'";
      }
      if (strlen($sistema) > 0) {
         $sql .= " AND obj_sistema = $sistema";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND acc_situacion = $situacion";
      }
      $sql .= " ORDER BY acc_codigo ASC, acc_objetivo ASC;";

      $result = $this->exec_query($sql);
      //echo $sql;
      return $result;
   }

   function count_accion($codigo = "", $objetivo = "", $proceso = '', $usuario = '', $tipo = "", $sistema = "",  $situacion = '1')
   {

      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM pla_accion, pro_objetivos";
      $sql .= " WHERE acc_objetivo = obj_codigo";
      if (strlen($codigo) > 0) {
         $sql .= " AND acc_codigo = $codigo";
      }
      if (strlen($objetivo) > 0) {
         $sql .= " AND acc_objetivo = $objetivo";
      }
      if (strlen($proceso) > 0) {
         $sql .= " AND obj_proceso = $proceso";
      }
      if (strlen($usuario) > 0) {
         $sql .= " AND acc_usuario = $usuario";
      }
      if (strlen($tipo) > 0) {
         $sql .= " AND obj_tipo = $tipo";
      }
      if (strlen($sistema) > 0) {
         $sql .= " AND obj_sistema = $sistema";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND acc_situacion IN($situacion)";
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }

   function insert_accion($codigo, $objetivo, $usuario = '', $descripcion = '',  $nombre = '',  $presupuesto = 0, $comentario = "", $tipo = "U", $desde, $hasta, $situacion = '')
   {
      $desde = regresa_fecha($desde);
      $hasta = regresa_fecha($hasta);
      $sql = "INSERT INTO pla_accion";
      $sql .= " VALUES ($codigo, $objetivo, $usuario,'$nombre','$descripcion',  '$comentario',$presupuesto, '$tipo', '$desde', '$hasta', $situacion);";
      //echo $sql;
      return $sql;
   }


   function modifica_accion($codigo, $objetivo = "", $usuario = "", $descripcion = "",  $nombre = "",  $presupuesto = "", $comentario = "", $tipo = "", $desde = "", $hasta = "")
   {
      $desde = regresa_fecha($desde);
      $hasta = regresa_fecha($hasta);
      $sql = "UPDATE pla_accion SET ";
      $sql .= "acc_codigo = $codigo ";
      if (strlen($descripcion) > 0) {
         $sql .= ",acc_descripcion = '$descripcion'";
      }
      if (strlen($objetivo) > 0) {
         $sql .= ",acc_objetivo = $objetivo";
      }
      if (strlen($usuario) > 0) {
         $sql .= ",acc_usuario = $usuario";
      }
      if (strlen($nombre) > 0) {
         $sql .= ",acc_nombre = '$nombre'";
      }
      if (strlen($presupuesto) > 0) {
         $sql .= ",acc_presupuesto = $presupuesto";
      }
      if (strlen($comentario) > 0) {
         $sql .= ",acc_comentario = '$comentario'";
      }
      if (strlen($tipo) > 0) {
         $sql .= ",acc_tipo = '$tipo'";
      }
      if (strlen($tipo) > 0) {
         $sql .= ",acc_fecha_inicio = '$desde'";
      }
      if (strlen($tipo) > 0) {
         $sql .= ",acc_fecha_fin = '$hasta'";
      }
      $sql .= " WHERE acc_codigo = $codigo;";
      //echo $sql;
      return $sql;
   }

   function cambia_situacion_accion($codigo, $sit)
   {
      $sql = "UPDATE pla_accion SET ";
      $sql .= "acc_situacion = $sit";
      $sql .= " WHERE acc_codigo = $codigo;";
     // echo $sql;
      return $sql;
   }

   function max_accion()
   {
      $sql = "SELECT max(acc_codigo) as max ";
      $sql .= " FROM pla_accion";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }

   /////////////////////////////  Programacion //////////////////////////////////////
   function insert_programacion($accion = '', $desde = '', $hasta = '', $situacion = '1')
   {
      $desde = $this->regresa_fecha($desde);
      $hasta = $this->regresa_fecha($hasta);
      $sql = "INSERT INTO pla_programacion (pro_accion, pro_fecha_inicio, pro_fecha_fin, pro_situacion)";
      $sql .= " VALUES ($accion,'$desde','$hasta',$situacion);";
      // echo $sql;
      return $sql;
   }

   function delete_programacion($codigo = "", $accion = '')
   {
      $sql = "DELETE FROM pla_programacion WHERE 1 = 1";
      if (strlen($codigo) > 0) {
         $sql .= " AND pro_codigo = $codigo";
      }
      if (strlen($accion) > 0) {
         $sql .= " AND pro_accion = $accion;";
      }
      // echo $sql;
      return $sql;
   }

   function get_programacion($codigo = "", $accion = "", $desde = '', $hasta = '', $inicio = "", $fin = "", $situacion = '')
   {

      $sql = "SELECT * ";
      $sql .= " FROM pla_programacion, pla_accion";
      $sql .= " WHERE acc_codigo = pro_accion";
      if (strlen($codigo) > 0) {
         $sql .= " AND pro_codigo = $codigo";
      }
      if (strlen($accion) > 0) {
         $sql .= " AND pro_accion = $accion";
      }
      if (strlen($inicio) > 0) {
         $sql .= " AND pro_dia_inicio = $inicio";
      }
      if (strlen($fin) > 0) {
         $sql .= " AND pro_dia_fin = $fin";
      }
      if ($desde != "" && $hasta != "") {
         $sql .= " AND pro_fecha_inicio > '$desde' AND pro_fecha_fin < '$hasta'";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND pro_situacion = $situacion";
      }
      $sql .= " ORDER BY pro_fecha_inicio ASC;";

      $result = $this->exec_query($sql);
      // echo $sql;
      return $result;
   }

   function cambia_situacion_programacion($codigo = "", $accion = "", $sit)
   {
      $sql = "UPDATE pla_programacion SET ";
      $sql .= "pro_situacion = $sit";
      $sql .= " WHERE 1 = 1";
      if (strlen($codigo) > 0) {
         $sql .= " AND pro_codigo = $codigo";
      }
      if (strlen($accion) > 0) {
         $sql .= " AND pro_accion = $accion";
      }
      $sql .= ";";
      //echo $sql;
      return $sql;
   }

   function count_programacion($codigo = "", $accion = "", $objetivo = "", $usuario = "", $desde = '', $hasta = '', $inicio = "", $fin = "", $situacion = '')
   {

      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM pla_programacion, pla_accion";
      $sql .= " WHERE acc_codigo = pro_accion";
      if (strlen($codigo) > 0) {
         $sql .= " AND pro_codigo = $codigo";
      }
      if (strlen($accion) > 0) {
         $sql .= " AND pro_accion = $accion";
      }
      if (strlen($objetivo) > 0) {
         $sql .= " AND acc_objetivo = $objetivo";
      }
      if (strlen($usuario) > 0) {
         $sql .= " AND acc_usuario = $usuario";
      }
      if (strlen($inicio) > 0) {
         $sql .= " AND pro_dia_inicio = $inicio";
      }
      if (strlen($fin) > 0) {
         $sql .= " AND pro_dia_fin = $fin";
      }
      if ($desde != "" && $hasta != "") {
         $sql .= " AND pro_fecha_inicio > '$desde' AND pro_fecha_fin < '$hasta'";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND pro_situacion = $situacion";
      }
      // echo $sql;
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }

   function get_programacion_aprobada($codigo = "", $usuario = "", $desde = '', $hasta = '', $objetivo = "", $sistema = "", $situacion = '1', $proceso = '')
   {
      $sql = "SELECT * ";
      $sql .= " ,(SELECT fic_nombre FROM pro_ficha WHERE obj_proceso = fic_codigo) as proceso_nombre";
      $sql .= " ,(SELECT sis_nombre FROM pro_sistema WHERE obj_sistema = sis_codigo) as sistema_nombre";
      $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE rev_usuario_asignado = usu_id) as usuario_nombre";
      $sql .= " FROM pla_revision_objetivo, pla_accion, pla_programacion, pro_objetivos ";
      $sql .= " WHERE pro_accion = acc_codigo";
      $sql .= " AND rev_objetivo = acc_objetivo";
      $sql .= " AND obj_codigo = acc_objetivo";
      $sql .= " AND pro_accion = acc_codigo";
      $sql .= " AND acc_usuario = rev_usuario_asignado";
      $sql .= " AND rev_situacion = 3";
      if (strlen($codigo) > 0) {
         $sql .= " AND pro_codigo = $codigo";
      }
      if (strlen($usuario) > 0) {
         $sql .= " AND acc_usuario = $usuario";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND pro_situacion = $situacion";
      }
      if (strlen($objetivo) > 0) {
         $sql .= " AND acc_objetivo = $objetivo";
      }
      if (strlen($sistema) > 0) {
         $sql .= " AND obj_sistema = $sistema";
      }
      if (strlen($proceso) > 0) {
         $sql .= " AND obj_proceso = $proceso";
      }
      if (strlen($desde) > 0 && strlen($hasta) > 0) {
         $desde = regresa_fecha($desde);
         $hasta = regresa_fecha($hasta);
         $sql .= " AND ((pro_fecha_inicio BETWEEN '$desde' AND '$hasta')";
         $sql .= " OR (pro_fecha_fin BETWEEN '$desde' AND '$hasta'))";
      }
      $sql .= " ORDER BY rev_codigo ASC, acc_codigo ASC;";

      $result = $this->exec_query($sql);
      // echo $sql;
      return $result;
   }

   function count_programacion_aprobada($codigo = "", $usuario = "", $desde = '', $hasta = '', $objetivo = "", $sistema = "", $situacion = '1', $proceso = '')
   {
      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM pla_revision_objetivo, pla_accion, pla_programacion, pro_objetivos ";
      $sql .= " WHERE pro_accion = acc_codigo";
      $sql .= " AND rev_objetivo = acc_objetivo";
      $sql .= " AND obj_codigo = acc_objetivo";
      $sql .= " AND pro_accion = acc_codigo";
      $sql .= " AND acc_usuario = rev_usuario_asignado";
      $sql .= " AND rev_situacion = 3";
      if (strlen($codigo) > 0) {
         $sql .= " AND pro_codigo = $codigo";
      }
      if (strlen($usuario) > 0) {
         $sql .= " AND acc_usuario = $usuario";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND pro_situacion = $situacion";
      }
      if (strlen($objetivo) > 0) {
         $sql .= " AND acc_objetivo = $objetivo";
      }
      if (strlen($sistema) > 0) {
         $sql .= " AND obj_sistema = $sistema";
      }
      if (strlen($proceso) > 0) {
         $sql .= " AND obj_proceso = $proceso";
      }
      if (strlen($desde) > 0 && strlen($hasta) > 0) {
         $desde = regresa_fecha($desde);
         $hasta = regresa_fecha($hasta);
         $sql .= " AND ((pro_fecha_inicio BETWEEN '$desde' AND '$hasta')";
         $sql .= " OR (pro_fecha_fin BETWEEN '$desde' AND '$hasta'))";
      }
      // echo $sql;
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }
}
   