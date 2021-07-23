<?php
require_once ("ClsConex.php");

class ClsPais extends ClsConex{
   
    function get_paises(){
	   $sql ="SELECT pai_id, pai_desc";
	   $sql.=" FROM mast_paises";
	   $sql.=" ORDER BY pai_desc ASC";
		//echo $sql;
		$result = $this->exec_query($sql);
		return $result;
	}
		
	
}	
?>
