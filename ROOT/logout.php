<?php
  include_once('user_auth_fns.php');
  
	$_SESSION['usu'] = "";	
	unset($_SESSION['usu']);
	$_SESSION['pass'] = "";	
	unset($_SESSION['pass']);
  session_destroy();

  header('Location: index.php');
?>
