<?php
require_once("ClsConex.php");

class ClsIndicador extends ClsConex
{
   /* Situacion 1 = ACTIVO, 2 = INACTIVO */


   /////////////////////////////  INDICADORES   //////////////////////////////////////

   function get_indicador($codigo ='', $objetivo = '', $proceso = '', $sistema = '', $usuario = '', $unidad = '', $nombre = '', $sit = '1,2')
   {
      $nombre = trim($nombre);

      $sql = "SELECT * ";
      $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE ind_usuario = usu_id) as ind_usuario";
      $sql .= " ,(SELECT fic_nombre FROM pro_ficha WHERE obj_proceso = fic_codigo) as obj_proceso";
      $sql .= " ,(SELECT sis_nombre FROM pro_sistema WHERE obj_sistema = sis_codigo) as obj_sistema";
      $sql .= " ,(SELECT umed_desc_lg FROM mast_unidad_medida WHERE umed_codigo = ind_unidad_medida) as medida_nombre";
      $sql .= " FROM ind_indicador, pro_objetivos";
      $sql .= " WHERE ind_objetivo = obj_codigo";
      $sql .= " AND obj_situacion = 1";
      if (strlen($codigo) > 0) {
         $sql .= " AND ind_codigo = $codigo";
      }
      if (strlen($objetivo) > 0) {
         $sql .= " AND ind_objetivo = $objetivo";
      }
      if (strlen($usuario) > 0) {
         $sql .= " AND ind_usuario = $usuario";
      }
      if (strlen($unidad) > 0) {
         $sql .= " AND ind_unidad_medida = $unidad";
      }
      if (strlen($proceso) > 0) {
         $sql .= " AND obj_proceso = $proceso";
      }
      if (strlen($sistema) > 0) {
         $sql .= " AND obj_sistema = $sistema";
      }
      if (strlen($nombre) > 0) {
         $sql .= " AND ind_nombre like '%$nombre%'";
      }
      if (strlen($sit) > 0) {
         $sql .= " AND ind_situacion IN ($sit)";
      }
      $sql .= " ORDER BY ind_codigo ASC;";

      $result = $this->exec_query($sql);
     // echo $sql;
      return $result;
   }

   function count_indicador($codigo, $objetivo = '', $proceso = '', $sistema = '', $usuario = '', $unidad = '', $nombre = '', $sit = '1,2')
   {
      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM ind_indicador, pro_objetivos";
      $sql .= " WHERE ind_objetivo = obj_codigo";
      if (strlen($codigo) > 0) {
         $sql .= " AND ind_codigo = $codigo";
      }
      if (strlen($objetivo) > 0) {
         $sql .= " AND ind_objetivo = $objetivo";
      }
      if (strlen($usuario) > 0) {
         $sql .= " AND ind_usuario = $usuario";
      }
      if (strlen($unidad) > 0) {
         $sql .= " AND ind_unidad_medida = $unidad";
      }
      if (strlen($proceso) > 0) {
         $sql .= " AND obj_proceso = $proceso";
      }
      if (strlen($sistema) > 0) {
         $sql .= " AND obj_sistema = $sistema";
      }
      if (strlen($nombre) > 0) {
         $sql .= " AND ind_nombre like '%$nombre%'";
      }
      if (strlen($sit) > 0) {
         $sql .= " AND ind_situacion IN ($sit)";
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }

   function insert_indicador($codigo, $objetivo, $unidad, $nombre, $descripcion, $ideal, $max, $min, $situacion)
   {
      $nombre = trim($nombre);
      $descripcion = trim($descripcion);
      $usuario = $_SESSION["codigo"];
      $sql = "INSERT INTO ind_indicador ";
      $sql .= " VALUES ($codigo, $objetivo, $usuario, $unidad, '$nombre', '$descripcion', $ideal, $max, $min, $situacion);";
      // echo $sql;
      return $sql;
   }

   function modifica_indicador($codigo, $nombre, $unidad, $ideal, $max, $min, $descripcion)
   {
      $nombre = trim($nombre);
      $descripcion = trim($descripcion);

      $sql = "UPDATE ind_indicador SET";
      $sql .= " ind_unidad_medida = $unidad,";
      $sql .= " ind_nombre = '$nombre',";
      $sql .= " ind_descripcion = '$descripcion',";
      $sql .= " ind_lectura_ideal = $ideal,";
      $sql .= " ind_lectura_maxima = $max,";
      $sql .= " ind_lectura_minima = $min,";
      $sql .= " ind_situacion = 1";
      $sql .= " WHERE ind_codigo = $codigo";
      // echo $sql;
      return $sql;
   }

   function cambia_situacion_indicador($codigo, $sit)
   {

      $sql = "UPDATE ind_indicador  SET ind_situacion = $sit";
      $sql .= " WHERE ind_codigo = $codigo";

      return $sql;
   }

   function max_indicador()
   {
      $sql = "SELECT max(ind_codigo) as max ";
      $sql .= " FROM ind_indicador ";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }


   /////////////////////////////  PROGRAMACION  //////////////////////////////////////
   function get_programacion($codigo = '', $indicador = '', $proceso = '', $sistema = '', $dia = '', $hora = '', $situacion = '1', $usuario = '', $fini = '', $ffin = '')
   {

      $sql = "SELECT * ";
      $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE ind_usuario = usu_id) as ind_usuario";
      $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE pro_usuario = usu_id) as pro_usuario";
      $sql .= " ,(SELECT fic_nombre FROM pro_ficha WHERE obj_proceso = fic_codigo) as obj_proceso";
      $sql .= " ,(SELECT sis_nombre FROM pro_sistema WHERE obj_sistema = sis_codigo) as obj_sistema";
      $sql .= " ,(SELECT umed_desc_lg FROM mast_unidad_medida WHERE umed_codigo = ind_unidad_medida) as medida_nombre";
      $sql .= " ,(SELECT rev_codigo FROM ind_revision WHERE rev_programacion = pro_codigo AND rev_situacion IN(1,2) ORDER BY rev_codigo LIMIT 0,1) as revision";

      $sql .= " FROM ind_programacion, ind_indicador, pro_objetivos";
      $sql .= " WHERE ind_codigo = pro_indicador";
      $sql .= " AND ind_objetivo = obj_codigo";

      if (strlen($codigo) > 0) {
         $sql .= " AND pro_codigo = $codigo";
      }
      if (strlen($indicador) > 0) {
         $sql .= " AND pro_indicador = $indicador";
      }
      if (strlen($proceso) > 0) {
         $sql .= " AND obj_proceso = $proceso";
      }
      if (strlen($sistema) > 0) {
         $sql .= " AND obj_sistema = $sistema";
      }
      if (strlen($usuario) > 0) {
         $sql .= " AND pro_usuario = $usuario";
      }
      if (strlen($dia) > 0) {
         $dia = regresa_fecha($dia);
         $sql .= " AND pro_fecha = '$dia'";
      }
      if (strlen($hora) > 0) {
         $sql .= " AND pro_hini <= '$hora' AND pro_hfin >= '$hora'";
      }
      if ($fini != "" && $ffin != "") {
         $fini = regresa_fecha($fini);
         $ffin = regresa_fecha($ffin);
         $sql .= " AND pro_fecha BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND pro_situacion = $situacion";
      }
      $sql .= " ORDER BY ind_codigo ASC, pro_codigo ASC;";

      $result = $this->exec_query($sql);
      // echo $sql . "<br><br>";
      return $result;
   }

   function count_programacion($codigo = '', $indicador = '', $proceso = '', $sistema = '', $dia = '', $hora = '', $situacion = '1,2', $usuario = '', $fini = '', $ffin = '')
   {
      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM ind_programacion, ind_indicador, pro_objetivos";
      $sql .= " WHERE ind_codigo = pro_indicador";
      $sql .= " AND ind_objetivo = obj_codigo";

      if (strlen($codigo) > 0) {
         $sql .= " AND pro_codigo = $codigo";
      }
      if (strlen($indicador) > 0) {
         $sql .= " AND pro_indicador = $indicador";
      }
      if (strlen($proceso) > 0) {
         $sql .= " AND obj_proceso = $proceso";
      }
      if (strlen($sistema) > 0) {
         $sql .= " AND obj_sistema = $sistema";
      }
      if (strlen($usuario) > 0) {
         $sql .= " AND ind_usuario = $usuario";
      }
      if (strlen($dia) > 0) {
         $dia = regresa_fecha($dia);
         $sql .= " AND pro_fecha = '$dia'";
      }
      if (strlen($hora) > 0) {
         $sql .= " AND pro_hini <= '$hora' AND pro_hfin >= '$hora'";
      }
      if ($fini != "" && $ffin != "") {
         $sql .= " AND pro_fecha BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59'";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND pro_situacion IN($situacion);";
      }
      // echo $sql;
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }

   function insert_programacion($indicador, $tipo, $fecha, $hini, $hfin, $observacion, $usuario)
   {
      $fecha = $this->regresa_fecha($fecha);

      $sql = "INSERT INTO ind_programacion (pro_indicador, pro_tipo, pro_fecha, pro_hini, pro_hfin, pro_observaciones, pro_usuario, pro_situacion)";
      $sql .= " VALUES ($indicador,'$tipo','$fecha','$hini','$hfin','$observacion',$usuario,1);";
      //echo $sql;
      return $sql;
   }


   function modifica_programacion($codigo, $observacion, $hini, $hfin, $fecha)
   {
      $observacion = trim($observacion);

      $sql = "UPDATE ind_programacion SET ";
      $sql .= "pro_fecha = '$fecha', ";
      $sql .= "pro_hini = '$hini', ";
      $sql .= "pro_hfin = '$hfin', ";
      $sql .= "pro_observaciones = '$observacion' ";

      $sql .= " WHERE pro_codigo = $codigo;";
      //echo $sql;
      return $sql;
   }


   function cambia_sit_programacion($codigo, $situacion)
   {

      $sql = "UPDATE ind_programacion SET ";
      $sql .= "pro_situacion = $situacion";

      $sql .= " WHERE pro_codigo = $codigo; ";

      return $sql;
   }


   function max_programacion()
   {
      $sql = "SELECT max(pro_codigo) as max ";
      $sql .= " FROM ind_programacion";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }
}
