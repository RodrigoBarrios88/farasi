<?php
require_once("ClsConex.php");

class ClsActivo extends ClsConex
{

   /////////////////////////////  ACTIVO  //////////////////////////////////////

   function get_activo($codigo, $sede = '', $sector = '', $area = '', $situacion = '')
   {
      $sede = ($sede == "") ? $_SESSION["sedes_in"] : $sede;

      $sql = "SELECT *, ";
      $sql .= " (SELECT dm_desc FROM mast_mundep WHERE dm_codigo = sed_municipio) as sede_municipio,";
      $sql .= " (SELECT usu_nombre FROM seg_usuarios WHERE usu_id = act_usuario) as usu_nombre";
      $sql .= " FROM ppm_activo, sis_area, sis_sector, sis_sede";
      $sql .= " WHERE act_sede = sed_codigo";
      $sql .= " AND act_sector = sec_codigo";
      $sql .= " AND act_area = are_codigo";
      if (strlen($codigo) > 0) {
         $sql .= " AND act_codigo = $codigo";
      }
      if (strlen($sede) > 0) {
         $sql .= " AND act_sede IN($sede)";
      }
      if (strlen($sector) > 0) {
         $sql .= " AND act_sector IN($sector)";
      }
      if (strlen($area) > 0) {
         $sql .= " AND act_area IN($area)";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND act_situacion IN($situacion)";
      }
      $sql .= " ORDER BY act_sede ASC, act_sector ASC, act_area ASC, act_codigo ASC";

      $result = $this->exec_query($sql);
      //echo $sql;
      return $result;
   }

   function count_activo($codigo, $sede = '', $sector = '', $area = '', $situacion = '')
   {
      $sede = ($sede == "") ? $_SESSION["sedes_in"] : $sede;

      $sql = "SELECT COUNT(*) as total";
      $sql .= " FROM ppm_activo, sis_area, sis_sector, sis_sede";
      $sql .= " WHERE act_sede = sed_codigo";
      $sql .= " AND act_sector = sec_codigo";
      $sql .= " AND act_area = are_codigo";
      if (strlen($codigo) > 0) {
         $sql .= " AND act_codigo = $codigo";
      }
      if (strlen($sede) > 0) {
         $sql .= " AND act_sede IN($sede)";
      }
      if (strlen($sector) > 0) {
         $sql .= " AND act_sector IN($sector)";
      }
      if (strlen($area) > 0) {
         $sql .= " AND act_area IN($area)";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND act_situacion IN($situacion)";
      }
      //echo $sql;
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $total = $row['total'];
      }
      return $total;
   }
   function insert_activo($codigo, $sede, $sector, $area, $nombre, $marca, $serie, $modelo, $parte, $proveedor, $periodicidad, $capacidad, $cantidad, $precio_nuevo, $precio_compra, $precio_actual, $obs)
   {
      $obs = trim($obs);
      $fsis = date("Y-m-d H:i:s");
      $usuario = $_SESSION["codigo"];

      $sql = "INSERT INTO ppm_activo";
      $sql .= " VALUES ($codigo,$sede,$sector,$area,'$nombre','$marca','$serie','$modelo','$parte','$proveedor','$periodicidad','$capacidad','$cantidad','$precio_nuevo','$precio_compra','$precio_actual','$obs','$fsis','$fsis','$usuario',1);";
      //echo $sql;
      return $sql;
   }
   function modifica_activo($codigo, $sede, $sector, $area, $nombre, $marca, $serie, $modelo, $parte, $proveedor, $periodicidad, $capacidad, $cantidad, $precio_nuevo, $precio_compra, $precio_actual, $obs)
   {
      $obs = trim($obs);
      $fsis = date("Y-m-d H:i:s");
      $usuario = $_SESSION["codigo"];

      $sql = "UPDATE ppm_activo SET ";
      $sql .= "act_sede = '$sede',";
      $sql .= "act_sector = '$sector',";
      $sql .= "act_area = '$area',";
      $sql .= "act_nombre = '$nombre',";
      $sql .= "act_marca = '$marca',";
      $sql .= "act_serie = '$serie',";
      $sql .= "act_modelo = '$modelo',";
      $sql .= "act_parte = '$parte',";
      $sql .= "act_proveedor = '$proveedor',";
      $sql .= "act_periodicidad = '$periodicidad',";
      $sql .= "act_capacidad = '$capacidad',";
      $sql .= "act_cantidad = '$cantidad',";
      $sql .= "act_precio_nuevo = '$precio_nuevo',";
      $sql .= "act_precio_compra = '$precio_compra',";
      $sql .= "act_precio_actual = '$precio_actual',";
      $sql .= "act_observaciones = '$obs',";
      $sql .= "act_fecha_update = '$fsis',";
      $sql .= "act_usuario = '$usuario'";

      $sql .= " WHERE act_codigo = $codigo;";
      //echo $sql;
      return $sql;
   }
   function  cambia_sit_activo($codigo, $situacion)
   {

      $sql = "UPDATE ppm_activo SET ";
      $sql .= "act_situacion = $situacion";

      $sql .= " WHERE act_codigo = $codigo; ";

      return $sql;
   }
   function max_activo()
   {
      $sql = "SELECT max(act_codigo) as max ";
      $sql .= " FROM ppm_activo";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   } /////////////////////////////  FOTOS  //////////////////////////////////////    
   function get_fotos($codigo, $activo, $posicion = '')
   {

      $sql = "SELECT * ";
      $sql .= " FROM ppm_foto_activo";
      $sql .= " WHERE 1 = 1";
      if (strlen($codigo) > 0) {
         $sql .= " AND fot_codigo = $codigo";
      }
      if (strlen($activo) > 0) {
         $sql .= " AND fot_activo IN($activo)";
      }
      if (strlen($posicion) > 0) {
         $sql .= " AND fot_posicion = $posicion";
      }
      $sql .= " ORDER BY fot_activo ASC, fot_posicion ASC";

      $result = $this->exec_query($sql);
      //echo $sql;
      return $result;
   }

   function insert_foto($codigo, $activo, $posicion, $foto)
   {
      $fsis = date("Y-m-d H:i:s");

      $sql = "INSERT INTO ppm_foto_activo";
      $sql .= " VALUES ($codigo,$activo,$posicion,'$foto','$fsis')";
      $sql .= " ON DUPLICATE KEY UPDATE";
      $sql .= " fot_foto = '$foto', ";
      $sql .= " fot_fecha_registro = '$fsis'; ";
      //echo $sql;
      return $sql;
   }
   function  delete_foto($codigo)
   {
      $sql = "DELETE FROM ppm_foto_activo";
      $sql .= " WHERE fot_codigo = $codigo; ";

      return $sql;
   }
   function  max_foto()
   {
      $sql = "SELECT max(fot_codigo) as max ";
      $sql .= " FROM ppm_foto_activo";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }
}
