<?php
require_once("ClsConex.php");

class ClsSistema extends ClsConex
{
   /* Situacion 1 = ACTIVO, 2 = INACTIVO */

   /////////////////////////////  SISTEMAS DE GESTION  //////////////////////////////////////

   function get_sistema($codigo = '', $nombre = '', $sit = '1', $usuario = "")
   {
      $nombre = trim($nombre);

      $sql = "SELECT * ";
      $sql .= " FROM pro_sistema, seg_usuarios";
      $sql .= " WHERE sis_usuario = usu_id";
      if (strlen($codigo) > 0) {
         $sql .= " AND sis_codigo = $codigo";
      }
      if (strlen($nombre) > 0) {
         $sql .= " AND sis_nombre like '%$nombre%'";
      }
      if (strlen($usuario) > 0) {
         $sql .= " AND sis_usuario = $usuario";
      }
      if (strlen($sit) > 0) {
         $sql .= " AND sis_situacion = '$sit'";
      }
      $sql .= " ORDER BY sis_codigo ASC";

      $result = $this->exec_query($sql);
      // echo $sql;
      return $result;
   }
   function count_sistema($codigo, $nombre = '', $sit = '')
   {
      $nombre = trim($nombre);

      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM pro_sistema";
      $sql .= " WHERE 1 = 1";
      if (strlen($codigo) > 0) {
         $sql .= " AND sis_codigo = $codigo";
      }
      if (strlen($nombre) > 0) {
         $sql .= " AND sis_nombre like '%$nombre%'";
      }
      if (strlen($sit) > 0) {
         $sql .= " AND sis_situacion = '$sit'";
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }

   function insert_sistema($codigo, $nombre, $color, $usuario, $politica)
   {
      $nombre = trim($nombre);
      $politica = trim($politica);

      $sql = "INSERT INTO pro_sistema";
      $sql .= " VALUES ($codigo,'$nombre','$color',$usuario,'$politica',1);";
      //echo $sql;
      return $sql;
   }
   function modifica_sistema($codigo, $nombre, $color, $usuario, $politica)
   {
      $nombre = trim($nombre);
      $politica = trim($politica);

      $sql = "UPDATE pro_sistema SET ";
      $sql .= "sis_nombre = '$nombre', ";
      $sql .= "sis_color = '$color', ";
      $sql .= "sis_usuario = $usuario, ";
      $sql .= "sis_politica = '$politica'";
      $sql .= " WHERE sis_codigo = $codigo";
      //echo $sql;
      return $sql;
   }
   function cambia_situacion_sistema($codigo, $sit)
   {

      $sql = "UPDATE pro_sistema SET sis_situacion = $sit";
      $sql .= " WHERE sis_codigo = $codigo";

      return $sql;
   }
   function max_sistema()
   {
      $sql = "SELECT max(sis_codigo) as max ";
      $sql .= " FROM pro_sistema";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }
}
