<?php
require_once("ClsConex.php");

class ClsHallazgo extends ClsConex
{
   /////////////////////////////  Hallazgos //////////////////////////////////////

   function get_hallazgo_riesgo($codigo = "", $riesgo = "", $proceso = "", $sistema = "", $usuario = '', $tipo = "", $fini = '', $ffin = '', $situacion = '1')
   {
      $sql = "SELECT * ";
      $sql .= " ,(SELECT fic_nombre FROM pro_ficha WHERE fod_proceso = fic_codigo) as fic_nombre";
      $sql .= " ,(SELECT sis_nombre FROM pro_sistema WHERE fod_sistema = sis_codigo) as sis_nombre";
      $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE rie_usuario = usu_id) as usu_nombre";
      $sql .= " ,(SELECT rie_fecha_materializacion FROM ryo_riesgo WHERE rie_codigo = hal_origen_codigo) as hal_fecha";
      $sql .= " ,(SELECT fod_descripcion FROM ryo_riesgo WHERE rie_codigo = hal_origen_codigo) as hal_descripcion";
      $sql .= " FROM mej_hallazgo, ryo_riesgo, pro_foda";
      $sql .= " WHERE rie_codigo = hal_origen_codigo";
      $sql .= " AND rie_foda_elemento = fod_codigo";
      $sql .= " AND rie_proceso = fod_proceso";
      $sql .= " AND hal_origen = 5";
      if (strlen($codigo) > 0) {
         $sql .= " AND hal_codigo = $codigo";
      }
      if (strlen($riesgo) > 0) {
         $sql .= " AND hal_origen_codigo = $riesgo";
      }
      if (strlen($proceso) > 0) {
         $sql .= " AND rie_proceso = $proceso";
      }
      if (strlen($sistema) > 0) {
         $sql .= " AND fod_sistema = $sistema";
      }
      if (strlen($usuario) > 0) {
         $sql .= " AND hal_usuario_registra = $usuario";
      }
      if (strlen($tipo) > 0) {
         $sql .= " AND hal_tipo = $tipo";
      }
      if ($fini != "" && $ffin != "") {
         $fini = $this->regresa_fecha($fini);
         $ffin = $this->regresa_fecha($ffin);
         $sql .= " AND hal_fecha_registro BETWEEN '$fini' AND '$ffin'";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND hal_situacion = $situacion";
      }
      $sql .= " ORDER BY hal_codigo ASC;";

      $result = $this->exec_query($sql);
      // echo $sql;
      return $result;
   }

   function get_hallazgo_indicador($codigo = "", $indicador = "", $proceso = "", $sistema = "", $usuario = '', $tipo = "", $fini = '', $ffin = '', $situacion = '1')
   {

      $sql = "SELECT * ";
      $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE ind_usuario = usu_id) as usu_nombre";
      $sql .= " ,(SELECT fic_nombre FROM pro_ficha WHERE obj_proceso = fic_codigo) as fic_nombre";
      $sql .= " ,(SELECT sis_nombre FROM pro_sistema WHERE obj_sistema = sis_codigo) as sis_nombre";
      $sql .= " ,hal_fecha_registro as hal_fecha";
      $sql .= " ,(SELECT ind_descripcion FROM ind_indicador WHERE ind_codigo = hal_origen_codigo) as hal_descripcion";
      $sql .= " ,(SELECT ind_nombre FROM ind_indicador WHERE ind_codigo = hal_origen_codigo) as hal_nombre";
      $sql .= " FROM mej_hallazgo, ind_indicador, pro_objetivos";
      $sql .= " WHERE ind_codigo = hal_origen_codigo";
      $sql .= " AND ind_objetivo = obj_codigo";
      $sql .= " AND obj_situacion = 1";
      $sql .= " AND hal_origen = 4";
      if (strlen($codigo) > 0) {
         $sql .= " AND hal_codigo = $codigo";
      }
      if (strlen($indicador) > 0) {
         $sql .= " AND hal_origen_codigo = $indicador";
      }
      if (strlen($proceso) > 0) {
         $sql .= " AND obj_proceso = $proceso";
      }
      if (strlen($sistema) > 0) {
         $sql .= " AND obj_sistema = $sistema";
      }
      if (strlen($usuario) > 0) {
         $sql .= " AND hal_usuario_registra = $usuario";
      }
      if (strlen($tipo) > 0) {
         $sql .= " AND hal_tipo = $tipo";
      }
      if ($fini != "" && $ffin != "") {
         $fini = $this->regresa_fecha($fini);
         $ffin = $this->regresa_fecha($ffin);
         $sql .= " AND hal_fecha_registro BETWEEN '$fini' AND '$ffin'";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND hal_situacion = $situacion";
      }
      $sql .= " ORDER BY hal_codigo ASC;";

      $result = $this->exec_query($sql);
      // echo $sql;
      return $result;
   }
   function get_hallazgo_queja($codigo = "", $queja = "", $proceso = "", $sistema = "", $usuario = '', $tipo = "", $fini = '', $ffin = '', $situacion = '1')
   {

      $sql = "SELECT * ";
      $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE que_usuario_registra = usu_id) as usu_nombre";
      $sql .= " ,(SELECT fic_nombre FROM pro_ficha WHERE que_proceso = fic_codigo) as fic_nombre";
      $sql .= " ,(SELECT sis_nombre FROM pro_sistema WHERE que_sistema = sis_codigo) as sis_nombre";
      $sql .= " , que_fecha_registro as hal_fecha";
      $sql .= " , que_descripcion as hal_descripcion";
      $sql .= " FROM mej_hallazgo, mej_queja";
      $sql .= " WHERE que_codigo = hal_origen_codigo";
      $sql .= " AND hal_origen = 3";
      if (strlen($codigo) > 0) {
         $sql .= " AND hal_codigo = $codigo";
      }
      if (strlen($queja) > 0) {
         $sql .= " AND hal_origen_codigo = $queja";
      }
      if (strlen($proceso) > 0) {
         $sql .= " AND que_proceso = $proceso";
      }
      if (strlen($sistema) > 0) {
         $sql .= " AND que_sistema = $sistema";
      }
      if (strlen($usuario) > 0) {
         $sql .= " AND hal_usuario_registra = $usuario";
      }
      if (strlen($tipo) > 0) {
         $sql .= " AND hal_tipo = $tipo";
      }
      if ($fini != "" && $ffin != "") {
         $fini = $this->regresa_fecha($fini);
         $ffin = $this->regresa_fecha($ffin);
         $sql .= " AND hal_fecha_registro BETWEEN '$fini' AND '$ffin'";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND hal_situacion = $situacion";
      }
      $sql .= " ORDER BY hal_codigo ASC;";

      $result = $this->exec_query($sql);
      // echo $sql;
      return $result;
   }

   function get_hallazgo_requisito($codigo = "", $queja = "", $proceso = "", $sistema = "", $usuario = '', $tipo = "", $fini = '', $ffin = '', $situacion = '1')
   {

      $sql = "SELECT * ";
      $sql .= " ,(SELECT sis_nombre FROM pro_sistema WHERE doc_sistema = sis_codigo) as sis_nombre";
      $sql .= " ,(SELECT fic_nombre FROM pro_ficha WHERE eva_proceso = fic_codigo) as fic_nombre";
      $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE hal_usuario_registra = usu_id) as usu_nombre";
      $sql .= " ,(SELECT fic_codigo FROM pro_ficha WHERE eva_proceso = fic_codigo) as fic_codigo";
      $sql .= " , req_fecha_registro as hal_fecha";
      $sql .= " , req_descripcion as hal_descripcion";
      $sql .= " FROM mej_hallazgo, req_evaluacion, req_documento, req_requisito";
      $sql .= " WHERE eva_codigo = hal_origen_codigo";
      $sql .= " AND eva_requisito = req_codigo ";
      $sql .= " AND doc_codigo = req_documento";
      $sql .= " AND hal_origen = 6";
      if (strlen($codigo) > 0) {
         $sql .= " AND hal_codigo = $codigo";
      }
      if (strlen($queja) > 0) {
         $sql .= " AND hal_origen_codigo = $queja";
      }
      if (strlen($proceso) > 0) {
         $sql .= " AND que_proceso = $proceso";
      }
      if (strlen($sistema) > 0) {
         $sql .= " AND doc_sistema = $sistema";
      }
      if (strlen($usuario) > 0) {
         $sql .= " AND hal_usuario_registra = $usuario";
      }
      if (strlen($tipo) > 0) {
         $sql .= " AND hal_tipo = $tipo";
      }
      if ($fini != "" && $ffin != "") {
         $fini = $this->regresa_fecha($fini);
         $ffin = $this->regresa_fecha($ffin);
         $sql .= " AND hal_fecha_registro BETWEEN '$fini' AND '$ffin'";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND hal_situacion = $situacion";
      }
      $sql .= " ORDER BY hal_codigo ASC;";

      $result = $this->exec_query($sql);

      //echo mysqli_error($this->conn); 
      // echo $sql;
      return $result;
   }

   function get_hallazgo_auditoria_interna($codigo = "", $auditoria = '', $proceso = '', $sistema = '', $usuario = '', $tipo = "", $fini = '', $ffin = '', $situacion = '1')
   {

      $sql = "SELECT * ";
      $sql .= " ,(SELECT fic_nombre FROM pro_ficha WHERE eje_proceso = fic_codigo) as fic_nombre";
      $sql .= " ,(SELECT sis_nombre FROM pro_sistema WHERE eje_sistema = sis_codigo) as sis_nombre";
      $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE eje_usuario = usu_id) as usu_nombre";
      $sql .= " ,eje_fecha_final as hal_fecha";
      $sql .= " ,audit_nombre as hal_descripcion";
      $sql .= " FROM mej_hallazgo, aud_ejecucion, aud_auditoria";
      $sql .= " WHERE eje_codigo = hal_origen_codigo";
      $sql .= " AND eje_auditoria = audit_codigo";
      $sql .= " AND hal_origen = 1";

      if (strlen($codigo) > 0) {
         $sql .= " AND hal_codigo = $codigo";
      }
      if (strlen($auditoria) > 0) {
         $sql .= " AND hal_origen_codigo = $auditoria";
      }
      if (strlen($proceso) > 0) {
         $sql .= " AND rie_proceso = $proceso";
      }
      if (strlen($sistema) > 0) {
         $sql .= " AND fod_sistema = $sistema";
      }
      if (strlen($usuario) > 0) {
         $sql .= " AND hal_usuario_registra = $usuario";
      }
      if (strlen($tipo) > 0) {
         $sql .= " AND hal_tipo = $tipo";
      }
      if ($fini != "" && $ffin != "") {
         $fini = $this->regresa_fecha($fini);
         $ffin = $this->regresa_fecha($ffin);
         $sql .= " AND hal_fecha_registro BETWEEN '$fini' AND '$ffin'";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND hal_situacion = $situacion";
      }
      $sql .= " ORDER BY hal_codigo ASC;";

      $result = $this->exec_query($sql);
      // echo $sql;
      return $result;
   }

   function get_hallazgo_auditoria_externa($codigo = "", $auditoria = '', $proceso = '', $sistema = '', $usuario = '', $tipo = "", $fini = '', $ffin = '', $situacion = '1')
   {

      $sql = "SELECT * ";
      $sql .= " ,(SELECT fic_nombre FROM pro_ficha WHERE dext_proceso = fic_codigo) as fic_nombre";
      $sql .= " ,(SELECT sis_nombre FROM pro_sistema WHERE dext_sistema = sis_codigo) as sis_nombre";
      $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE ext_usuario_registra = usu_id) as usu_nombre";
      $sql .= " ,ext_fecha_auditoria as hal_fecha";
      $sql .= " ,dext_descripcion as hal_descripcion";
      $sql .= " FROM mej_hallazgo, aud_externa, aud_externa_detalle";
      $sql .= " WHERE ext_codigo = dext_auditoria";
      $sql .= " AND hal_origen_codigo = dext_codigo";
      $sql .= " AND hal_origen = 2";

      if (strlen($codigo) > 0) {
         $sql .= " AND hal_codigo = $codigo";
      }
      if (strlen($auditoria) > 0) {
         $sql .= " AND hal_origen_codigo = $auditoria";
      }
      if (strlen($proceso) > 0) {
         $sql .= " AND dext_proceso = $proceso";
      }
      if (strlen($sistema) > 0) {
         $sql .= " AND dext_sistema = $sistema";
      }

      if (strlen($tipo) > 0) {
         $sql .= " AND hal_tipo = $tipo";
      }
      if ($fini != "" && $ffin != "") {
         $fini = $this->regresa_fecha($fini);
         $ffin = $this->regresa_fecha($ffin);
         $sql .= " AND hal_fecha_registro BETWEEN '$fini' AND '$ffin'";
      }
      if (strlen($usuario) > 0) {
         $sql .= " AND hal_usuario_registra = $usuario";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND hal_situacion = $situacion";
      }

      $sql .= " ORDER BY hal_codigo ASC;";

      $result = $this->exec_query($sql);
      //   echo $sql;
      return $result;
   }


   function insert_hallazgo($codigo = "", $origen = "", $origen_codigo = "")
   {
      $fsis = date("Y-m-d");
      $usuario = $_SESSION["codigo"];
      $sql = "INSERT INTO mej_hallazgo";
      $sql .= " VALUES ($codigo,0,$origen,$origen_codigo,  '$fsis', $usuario, 1);";
      //echo $sql;
      return $sql;
   }

   function modifica_hallazgo($codigo, $descripcion, $presupuesto, $comentario = "")
   {
      $sql = "UPDATE pla_hallazgo SET ";
      $sql .= "hal_codigo = $codigo ";
      if (strlen($descripcion) > 0) {
         $sql .= ",hal_descripcion = '$descripcion'";
      }
      if (strlen($presupuesto) > 0) {
         $sql .= ",hal_presupuesto = $presupuesto";
      }
      if (strlen($comentario) > 0) {
         $sql .= ",hal_comentario = '$comentario'";
      }
      $sql .= " WHERE hal_codigo = $codigo;";
      //echo $sql;
      return $sql;
   }

   function update_hallazgo($codigo, $campo, $valor)
   {
      $sql = "UPDATE mej_hallazgo";
      $sql .= " SET $campo = '$valor' ";
      $sql .= " WHERE hal_codigo = $codigo; ";
      // echo $sql."<br>";
      return $sql;
   }

   function max_hallazgo()
   {
      $sql = "SELECT max(hal_codigo) as max ";
      $sql .= " FROM mej_hallazgo";
      $result = $this->exec_query($sql);
      foreach ($result as $row) {
         $max = $row["max"];
      }
      //echo $sql;
      return $max;
   }

   /////////////////////////////  Programacion //////////////////////////////////////
   function insert_programacion($hallazgo = '', $desde = '', $hasta = '', $inicio = '', $fin = '', $situacion = '')
   {
      $desde = $this->regresa_fecha($desde);
      $hasta = $this->regresa_fecha($hasta);
      $sql = "INSERT INTO pla_programacion (pro_hallazgo, pro_fecha_inicio, pro_fecha_fin, pro_dia_inicio, pro_dia_fin, pro_situacion)";
      $sql .= " VALUES ($hallazgo,'$desde','$hasta',$inicio, $fin,$situacion);";
      // echo $sql;
      return $sql;
   }

   function get_programacion($codigo = "", $hallazgo = "", $desde = '', $hasta = '', $inicio = "", $fin = "", $situacion = '')
   {

      $sql = "SELECT * ";
      $sql .= " FROM pla_programacion, pla_hallazgo";
      $sql .= " WHERE hal_codigo = pro_hallazgo";
      if (strlen($codigo) > 0) {
         $sql .= " AND pro_codigo = $codigo";
      }
      if (strlen($hallazgo) > 0) {
         $sql .= " AND pro_hallazgo = $hallazgo";
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

   function cambia_situacion_programacion($codigo = "", $hallazgo = "", $sit)
   {
      $sql = "UPDATE pla_programacion SET ";
      $sql .= "pro_situacion = $sit";
      $sql .= " WHERE 1 = 1";
      if (strlen($codigo) > 0) {
         $sql .= " AND pro_codigo = $codigo";
      }
      if (strlen($hallazgo) > 0) {
         $sql .= " AND pro_hallazgo = $hallazgo";
      }
      return $sql . ";";
   }

   function get_programacion_aprobada($codigo = "", $usuario = "", $desde = '', $hasta = '', $objetivo = "", $sistema = "", $situacion = '', $proceso = '')
   {
      $sql = "SELECT * ";
      $sql .= " ,(SELECT fic_nombre FROM pro_ficha WHERE obj_proceso = fic_codigo) as proceso_nombre";
      $sql .= " ,(SELECT sis_nombre FROM pro_sistema WHERE obj_sistema = sis_codigo) as sistema_nombre";
      $sql .= " ,(SELECT usu_nombre FROM seg_usuarios WHERE rev_usuario_asignado = usu_id) as usuario_nombre";
      $sql .= " FROM pla_revision_objetivo, pla_hallazgo, pla_programacion, pro_objetivos ";
      $sql .= " WHERE pro_hallazgo = hal_codigo";
      $sql .= " AND rev_objetivo = hal_objetivo";
      $sql .= " AND obj_codigo = hal_objetivo";
      $sql .= " AND pro_hallazgo = hal_codigo";
      $sql .= " AND hal_usuario = rev_usuario_asignado";
      $sql .= " AND rev_situacion = 3";
      if (strlen($codigo) > 0) {
         $sql .= " AND pro_codigo = $codigo";
      }
      if (strlen($usuario) > 0) {
         $sql .= " AND hal_usuario = $usuario";
      }
      if (strlen($situacion) > 0) {
         $sql .= " AND pro_situacion = $situacion";
      }
      if (strlen($objetivo) > 0) {
         $sql .= " AND hal_objetivo = $objetivo";
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
      $sql .= " ORDER BY rev_codigo ASC, hal_codigo ASC;";

      $result = $this->exec_query($sql);
      // echo $sql;
      return $result;
   }
}
