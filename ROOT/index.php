<?php
  // writer.php Is the Interface for Writers to Manage Their Stories

  include_once('user_auth_fns.php');
  include_once('validacion.php');
  $inv = $_REQUEST["invalid"];
  $seg = $_REQUEST["seg"];

  if (!check_auth_user()){
    echo login_form($inv,$seg);
	//echo "Sin loguear..";
  }else{
	consulta_log(); 
	//echo "Consulta Permisos..";
  }
  
?>
		