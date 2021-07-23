<?php
require_once ("ClsConex.php");

class ClsMundep extends ClsConex{
   
   function get_departamentos(){
      $cod=0;
      for($i = 1; $i <= 22; $i++){
         $cod+=100; $tsrcod.= $cod.",";
      }
      $tsrcod = substr($tsrcod, 0, -1);
      $sql ="SELECT dm_codigo, dm_desc";
      $sql.=" FROM mast_mundep";
      $sql.=" WHERE dm_codigo in($tsrcod)";
      $sql.=" ORDER BY dm_codigo ASC";
      //echo $sql;
      $result = $this->exec_query($sql);
      return $result;
   }
   
   
   function get_municipios($dep){
      $mun1 = $dep + 1;
      $mun2 = $dep + 99;
      $sql ="SELECT dm_codigo, dm_desc";
      $sql.=" FROM mast_mundep";
      $sql.=" WHERE dm_codigo BETWEEN $mun1 AND $mun2";
      $sql.=" ORDER BY dm_desc ASC";
      //echo $sql;
      $result = $this->exec_query($sql);
      return $result;
   }
   
   function get_municipios_todos(){
      $sql ="SELECT dm_codigo, dm_desc";
      $sql.=" FROM mast_mundep";
      $sql.=" WHERE 1 = 1";
      $sql.=" ORDER BY dm_desc ASC";
      //echo $sql;
      $result = $this->exec_query($sql);
      return $result;
   }
		
	
}	
?>
