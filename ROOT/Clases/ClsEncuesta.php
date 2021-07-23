<?php
require_once("ClsConex.php");

class ClsEncuesta extends ClsConex
{
   /* Situacion 1 = ACTIVO, 0 = INACTIVO */

   /////////////////////////////  CUESTIONARIO  //////////////////////////////////////

   function get_cuestionario($codigo, $categoria = '', $situacion = '')
   {

      $sql = "SELECT * ";
      $sql .= " FROM enc_cuestionario, enc_categoria ";
      $sql .= " WHERE cue_categoria = cat_codigo";
      if (strlen($codigo) > 0) {
         $sql .= " AND cue_codigo = $codigo";
      }
      if (strlen($categoria) > 0) {
         $sql .= " AND cue_categoria IN($categoria)";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND cue_situacion IN($situacion)";
      }
      $sql .= " ORDER BY cue_categoria ASC, cue_codigo ASC";

      $result = $this->exec_query($sql);
    //  echo $sql;
      return $result;
   }
   function count_cuestionario($codigo, $categoria = '', $situacion = '')
   {

      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM enc_cuestionario, enc_categoria";
      $sql .= " WHERE cue_categoria = cat_codigo";
      if (strlen($codigo) > 0) {
         $sql .= " AND cue_codigo = $codigo";
      }
      if (strlen($categoria) > 0) {
         $sql .= " AND cue_categoria IN($categoria)";
      }
      if (strlen($ponderacion) > 0) {
         $sql .= " AND cue_categoria = $ponderacion";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND cue_situacion IN($situacion)";
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }
   function insert_cuestionario($codigo, $categoria, $titulo, $descripcion, $objetivo)
   {
      $titulo = trim($titulo);

      $sql = "INSERT INTO enc_cuestionario";
      $sql .= " VALUES ($codigo,$categoria,'$titulo','$descripcion','$objetivo',1);";
      //echo $sql;
      return $sql;
   }
   function modifica_cuestionario($codigo, $categoria, $titulo, $descripcion, $objetivo)
   {
      $titulo = trim($titulo);

      $sql = "UPDATE enc_cuestionario SET ";
      $sql .= "cue_categoria = '$categoria',";
      $sql .= "cue_titulo = '$titulo',";
      $sql .= "cue_descripcion  = '$descripcion',";
      $sql .= "cue_objetivo = '$objetivo'";

      $sql .= " WHERE cue_codigo = $codigo; ";
      //echo $sql;
      return $sql;
   }
   function cambia_situacion_cuestionario($codigo, $situacion)
   {

      $sql = "UPDATE enc_cuestionario SET ";
      $sql .= "cue_situacion = $situacion";

      $sql .= " WHERE cue_codigo = $codigo; ";

      return $sql;
   }
   function max_cuestionario()
   {
      $sql = "SELECT max(cue_codigo) as max ";
      $sql .= " FROM enc_cuestionario";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   } /////////////////////////////  SECCIONES  //////////////////////////////////////

   function get_secciones($codigo, $encuesta, $situacion = '')
   {
      $sql = "SELECT *, CONCAT('(',sec_numero,'.) ',sec_titulo) as sec_numero_titulo";
      $sql .= " FROM enc_cuestionario, enc_secciones";
      $sql .= " WHERE sec_encuesta = cue_codigo";

      if (strlen($codigo) > 0) {
         $sql .= " AND sec_codigo = $codigo";
      }
      if (strlen($encuesta) > 0) {
         $sql .= " AND sec_encuesta = $encuesta";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND sec_situacion = $situacion";
      }
      $sql .= " ORDER BY cue_codigo ASC, sec_codigo ASC";

      $result = $this->exec_query($sql);
      //echo $sql;
      return $result;
   }
   function count_secciones($codigo, $encuesta, $situacion = '')
   {
      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM enc_cuestionario, enc_secciones";
      $sql .= " WHERE sec_encuesta = cue_codigo";

      if (strlen($codigo) > 0) {
         $sql .= " AND sec_codigo = $codigo";
      }
      if (strlen($encuesta) > 0) {
         $sql .= " AND sec_encuesta = $encuesta";
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
   function insert_secciones($codigo, $encuesta, $numero, $titulo, $proposito)
   {
      $numero = trim($numero);
      $titulo = trim($titulo);
      $proposito = trim($proposito);

      $sql = "INSERT INTO enc_secciones";
      $sql .= " VALUES ($codigo,$encuesta,'$numero','$titulo','$proposito',1);";
      //echo $sql;
      return $sql;
   }
   function modifica_secciones($codigo, $encuesta, $numero, $titulo, $proposito)
   {
      $numero = trim($numero);
      $titulo = trim($titulo);
      $proposito = trim($proposito);

      $sql = "UPDATE enc_secciones SET ";
      $sql .= "sec_numero = '$numero',";
      $sql .= "sec_titulo = '$titulo',";
      $sql .= "sec_proposito = '$proposito'";

      $sql .= " WHERE sec_codigo = $codigo ";
      $sql .= " AND sec_encuesta = $encuesta;";
      //echo $sql;
      return $sql;
   }
   function cambia_situacion_seccion($codigo, $encuesta, $situacion)
   {

      $sql = "UPDATE enc_secciones SET ";
      $sql .= "sec_situacion = $situacion";

      $sql .= " WHERE sec_codigo = $codigo ";
      $sql .= " AND sec_encuesta = $encuesta;";

      return $sql;
   }
   function max_secciones($encuesta)
   {
      $sql = "SELECT max(sec_codigo) as max ";
      $sql .= " FROM enc_secciones";
      $sql .= " WHERE sec_encuesta = $encuesta ";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }

   /////////////////////////////  PREGUNTAS  //////////////////////////////////////

   function get_pregunta($codigo, $encuesta, $seccion = '', $situacion = '')
   {

      $sql = "SELECT * ";
      $sql .= " FROM enc_cuestionario, enc_preguntas, enc_secciones";
      $sql .= " WHERE pre_encuesta = cue_codigo";
      $sql .= " AND pre_encuesta = sec_encuesta";
      $sql .= " AND pre_seccion = sec_codigo";

      if (strlen($codigo) > 0) {
         $sql .= " AND pre_codigo = $codigo";
      }
      if (strlen($encuesta) > 0) {
         $sql .= " AND pre_encuesta = $encuesta";
      }
      if (strlen($seccion) > 0) {
         $sql .= " AND pre_seccion IN($seccion)";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND pre_situacion = $situacion";
      }
      $sql .= " ORDER BY cue_codigo ASC, pre_seccion ASC, pre_codigo ASC";

      $result = $this->exec_query($sql);
      //echo $sql;
      return $result;
   }
   function count_pregunta($codigo, $encuesta, $seccion = '', $situacion = '')
   {
      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM enc_cuestionario, enc_preguntas, enc_secciones";
      $sql .= " WHERE pre_encuesta = cue_codigo";
      $sql .= " AND pre_encuesta = sec_encuesta";
      $sql .= " AND pre_seccion = sec_codigo";

      if (strlen($codigo) > 0) {
         $sql .= " AND pre_codigo = $codigo";
      }
      if (strlen($encuesta) > 0) {
         $sql .= " AND pre_encuesta = $encuesta";
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
   function insert_pregunta($codigo, $encuesta, $seccion, $pregunta, $tipo, $peso)
   {
      $pregunta = trim($pregunta);

      $sql = "INSERT INTO enc_preguntas";
      $sql .= " VALUES ($codigo,$encuesta,$seccion,'$pregunta',$tipo,$peso,1);";
      //echo $sql;
      return $sql;
   }
   function modifica_pregunta($codigo, $encuesta, $seccion, $pregunta, $tipo, $peso)
   {
      $pregunta = trim($pregunta);

      $sql = "UPDATE enc_preguntas SET ";
      $sql .= "pre_seccion = '$seccion',";
      $sql .= "pre_pregunta = '$pregunta',";
      $sql .= "pre_tipo = '$tipo',";
      $sql .= "pre_peso = '$peso'";

      $sql .= " WHERE pre_codigo = $codigo";
      $sql .= " AND pre_encuesta = $encuesta;";
      //echo $sql;
      return $sql;
   }
   function cambia_situacion_pregunta($codigo, $encuesta, $situacion)
   {

      $sql = "UPDATE enc_preguntas SET ";
      $sql .= "pre_situacion = $situacion";

      $sql .= " WHERE pre_codigo = $codigo";
      $sql .= " AND pre_encuesta = $encuesta;";

      return $sql;
   }
   function max_pregunta($encuesta)
   {
      $sql = "SELECT max(pre_codigo) as max ";
      $sql .= " FROM enc_preguntas";
      $sql .= " WHERE pre_encuesta = $encuesta ";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }    /////////////////////////////  INVITACION  //////////////////////////////////////    
   function get_invitacion($codigo, $encuesta, $categoria = '', $fini = '', $ffin = '', $situacion = '')
   {

      $sql = "SELECT *, ";
      $sql .= " (SELECT usu_nombre FROM seg_usuarios WHERE usu_id = inv_usuario) as usuario_nombre,";
      $sql .= " (SELECT eje_codigo FROM enc_ejecucion WHERE eje_invitacion = inv_codigo AND eje_situacion IN(1,2) ORDER BY eje_codigo DESC LIMIT 0,1) as ejecucion_activa";
      $sql .= " FROM enc_cuestionario, enc_invitacion, enc_categoria";
      $sql .= " WHERE inv_encuesta = cue_codigo";
      $sql .= " AND cue_categoria = cat_codigo";
      $sql .= " AND cue_situacion = 1";

      if (strlen($codigo) > 0) {
         $sql .= " AND inv_codigo = $codigo";
      }
      if (strlen($encuesta) > 0) {
         $sql .= " AND inv_encuesta = $encuesta";
      }
      if (strlen($categoria) > 0) {
         $sql .= " AND cue_categoria IN($categoria)";
      }
      if ($fini != "" && $ffin != "") {
         $fini = $this->regresa_fecha($fini);
         $ffin = $this->regresa_fecha($ffin);
         $sql .= " AND inv_fecha_registro BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND inv_situacion IN($situacion)";
      }
      $sql .= " ORDER BY cue_categoria ASC, cue_codigo ASC";

      $result = $this->exec_query($sql);
      //echo $sql."<br>";
      return $result;
   }
   function count_invitacion($codigo, $encuesta, $categoria = '', $fini = '', $ffin = '', $situacion = '')
   {

      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM enc_cuestionario, enc_invitacion, enc_categoria";
      $sql .= " WHERE inv_encuesta = cue_codigo";
      $sql .= " AND cue_categoria = cat_codigo";
      $sql .= " AND cue_situacion = 1";

      if (strlen($codigo) > 0) {
         $sql .= " AND inv_codigo = $codigo";
      }
      if (strlen($encuesta) > 0) {
         $sql .= " AND inv_encuesta = $encuesta";
      }
      if (strlen($categoria) > 0) {
         $sql .= " AND cue_categoria IN($categoria)";
      }
      if ($fini != "" && $ffin != "") {
         $fini = $this->regresa_fecha($fini);
         $ffin = $this->regresa_fecha($ffin);
         $sql .= " AND inv_fecha_registro BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND inv_situacion IN($situacion)";
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }
   function  insert_invitacion($codigo, $encuesta, $cliente, $correo, $url, $observaciones)
   {
      $observaciones = trim($observaciones);
      $url = trim($url);
      $fsis = date("Y-m-d H:i:s");
      $usuario = $_SESSION["codigo"];

      $sql = "INSERT INTO enc_invitacion";
      $sql .= " VALUES ($codigo,$encuesta,'$cliente','$correo','$url','$observaciones','$usuario','$fsis',1);";
      //echo $sql;
      return $sql;
   }
   function modifica_invitacion($codigo, $encuesta, $cliente, $correo, $url, $observaciones)
   {
      $observaciones = trim($observaciones);
      $url = trim($url);
      $fsis = date("Y-m-d H:i:s");
      $usuario = $_SESSION["codigo"];

      $sql = "UPDATE enc_invitacion SET ";
      $sql .= "inv_encuesta = '$encuesta',";
      $sql .= "inv_cliente = '$cliente',";
      $sql .= "inv_correo = '$correo',";
      $sql .= "inv_url = '$url',";
      $sql .= "inv_usuario = '$usuario',";
      $sql .= "inv_fecha_registro = '$fsis',";
      $sql .= "inv_observaciones = '$observaciones'";

      $sql .= " WHERE inv_codigo = $codigo;";
      //echo $sql;
      return $sql;
   }
   function cambia_situacion_invitacion($codigo, $situacion)
   {

      $sql = "UPDATE enc_invitacion SET ";
      $sql .= "inv_situacion = $situacion";

      $sql .= " WHERE inv_codigo = $codigo; ";

      return $sql;
   }
   function max_invitacion()
   {
      $sql = "SELECT max(inv_codigo) as max ";
      $sql .= " FROM enc_invitacion";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }


   /////////////////////////////  CATEGORIA  //////////////////////////////////////

   function get_categoria($codigo, $nombre = '', $sit = '')
   {
      $nombre = trim($nombre);

      $sql = "SELECT * ";
      $sql .= " FROM enc_categoria";
      $sql .= " WHERE 1 = 1";
      if (strlen($codigo) > 0) {
         $sql .= " AND cat_codigo = $codigo";
      }
      if (strlen($nombre) > 0) {
         $sql .= " AND cat_nombre like '%$nombre%'";
      }
      if (strlen($sit) > 0) {
         $sql .= " AND cat_situacion = '$sit'";
      }
      $sql .= " ORDER BY cat_codigo ASC";

      $result = $this->exec_query($sql);
      //echo $sql;
      return $result;
   }
   function count_categoria($codigo, $nombre = '', $sit = '')
   {
      $nombre = trim($nombre);

      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM enc_categoria";
      $sql .= " WHERE 1 = 1";
      if (strlen($codigo) > 0) {
         $sql .= " AND cat_codigo = $codigo";
      }
      if (strlen($nombre) > 0) {
         $sql .= " AND cat_nombre like '%$nombre%'";
      }
      if (strlen($sit) > 0) {
         $sql .= " AND cat_situacion = '$sit'";
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }

   function insert_categoria($codigo, $nombre, $color)
   {
      $nombre = trim($nombre);
      $color = trim($color);

      $sql = "INSERT INTO enc_categoria";
      $sql .= " VALUES ($codigo,'$nombre','$color',1);";
      //echo $sql;
      return $sql;
   }
   function modifica_categoria($codigo, $nombre, $color)
   {
      $nombre = trim($nombre);
      $color = trim($color);

      $sql = "UPDATE enc_categoria SET ";
      $sql .= "cat_nombre = '$nombre', ";
      $sql .= "cat_color = '$color'";
      $sql .= " WHERE cat_codigo = $codigo";
      //echo $sql;
      return $sql;
   }
   function cambia_situacion_categoria($codigo, $sit)
   {

      $sql = "UPDATE enc_categoria SET cat_situacion = $sit";
      $sql .= " WHERE cat_codigo = $codigo";

      return $sql;
   }
   function max_categoria()
   {
      $sql = "SELECT max(cat_codigo) as max ";
      $sql .= " FROM enc_categoria";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }
}
