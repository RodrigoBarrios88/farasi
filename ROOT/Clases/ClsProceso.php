<?php
require_once("ClsConex.php");

class ClsProceso extends ClsConex
{

   /////////////////////////////  Titulo  //////////////////////////////////////

   function get_titulo($codigo = '', $nombre = '', $posicion = '', $situacion = '')
   {
      $sql = "SELECT * ";
      $sql .= " FROM pro_titulo";
      $sql .= " WHERE 1 = 1";
      if (strlen($codigo) > 0) {
         $sql .= " AND tit_codigo = $codigo";
      }
      if (strlen($nombre) > 0) {
         $sql .= " AND tit_nombre LIKE($nombre)";
      }
      if (strlen($posicion) > 0) {
         $sql .= " AND tit_posicion = $posicion";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND tit_situacion IN($situacion)";
      }
      $sql .= " ORDER BY tit_codigo ASC";

      $result = $this->exec_query($sql);
      //echo $sql;
      return $result;
   }

   function count_titulo($codigo, $situacion = '')
   {

      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM pro_titulo";
      if (strlen($codigo) > 0) {
         $sql .= " AND tit_codigo = $codigo";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND tit_situacion IN($situacion)";
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }

   function insert_titulo($codigo, $nombre, $posicion, $situacion)
   {
      $sql = "INSERT INTO pro_titulo";
      $sql .= " VALUES ($codigo,'$nombre',$posicion,$situacion);";
      //echo $sql;
      return $sql;
   }


   function modifica_titulo($codigo, $nombre, $posicion, $situacion)
   {

      $sql = "UPDATE pro_titulo SET ";
      $sql .= "tit_codigo = $codigo,";
      $sql .= "tit_nombre = '$nombre',";
      $sql .= "tit_posicion = $posicion,";
      $sql .= "tit_situacion = $situacion";

      $sql .= " WHERE tit_codigo = $codigo;";
      //echo $sql;
      return $sql;
   }

   function cambia_sit_titulo($codigo, $situacion)
   {
      $sql = "UPDATE pro_titulo SET ";
      $sql .= "tit_situacion = $situacion";
      $sql .= " WHERE tit_codigo = $codigo; ";

      return $sql;
   }

   function max_titulo()
   {
      $sql = "SELECT max(tit_codigo) as max ";
      $sql .= " FROM pro_titulo";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }

   /////////////////////////////  Subtitulo  //////////////////////////////////////

   function get_subtitulo($codigo = '', $titulo = '', $nombre = '', $situacion = '1', $posicion = '')
   {

      $sql = "SELECT * ";
      $sql .= " FROM pro_subtitulo, pro_titulo";
      $sql .= " WHERE sub_titulo = tit_codigo";
      if (strlen($codigo) > 0) {
         $sql .= " AND sub_codigo = $codigo";
      }
      if (strlen($posicion) > 0) {
         $sql .= " AND tit_posicion = $posicion";
      }
      if (strlen($titulo) > 0) {
         $sql .= " AND sub_titulo IN($titulo)";
      }
      if (strlen($nombre) > 0) {
         $sql .= " AND sub_nombre LIKE($nombre)";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND sub_situacion IN($situacion)";
      }
      $sql .= " ORDER BY sub_codigo ASC";

      $result = $this->exec_query($sql);
      //echo $sql;
      return $result;
   }

   function count_subtitulo($codigo, $situacion = '')
   {

      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM pro_subtitulo";
      if (strlen($codigo) > 0) {
         $sql .= " AND sub_codigo = $codigo";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND sub_situacion IN($situacion)";
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }

   function insert_subtitulo($codigo, $titulo, $nombre = '', $situacion = '')
   {
      $sql = "INSERT INTO pro_subtitulo";
      $sql .= " VALUES ($codigo,'$nombre',$titulo,1);";
      //echo $sql;
      return $sql;
   }


   function modifica_subtitulo($codigo, $titulo, $nombre = '', $situacion = '')
   {

      $sql = "UPDATE pro_subtitulo SET ";
      $sql .= "sub_codigo = $codigo,";
      $sql .= "sub_titulo = $titulo,";
      $sql .= "sub_nombre = '$nombre',";
      $sql .= "sub_situacion = $situacion";

      $sql .= " WHERE sub_codigo = $codigo;";
      //echo $sql;
      return $sql;
   }

   function cambia_sit_subtitulo($codigo, $situacion)
   {
      $sql = "UPDATE pro_subtitulo SET ";
      $sql .= "sub_situacion = $situacion";
      $sql .= " WHERE sub_codigo = $codigo; ";

      return $sql;
   }

   function max_subtitulo()
   {
      $sql = "SELECT max(sub_codigo) as max ";
      $sql .= " FROM pro_subtitulo";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }
}
