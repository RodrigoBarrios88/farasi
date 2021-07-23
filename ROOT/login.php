<?php
include_once('user_auth_fns.php');
require_once ("Clases/ClsUsuario.php");
$username = $_REQUEST['usu'];
$pass = $_REQUEST['pass'];
$seg = $_REQUEST['seg'];

///////// condicion para bloqueo de contrase�a
///-- esta rutina lee un archivo plano para saber cuantos intentos fallidos
//-- tiene para bloquear el usuario
$archivo = "../CONFIG/seguridad.txt";
$texto = file_get_contents($archivo);
$texto = explode(":",$texto);
$intentos = trim($texto[1]);
////////////////

if($username != '' && $pass != '' ){
  if ((!isset($_REQUEST['usu']))||(!isset($_REQUEST['pass']))){	
	//redirecciona por medio de $_post
	echo "<form id='f1' name='f1' action='index.php' method='post'>";
	echo "<input type='hidden' name='invalid' value='2' />";
	echo "<input type='hidden' name='seg' value='$seg' />";
	echo "<script>document.f1.submit();</script>";
	echo "</form>";
  }
	
	if($seg < $intentos){
		if(login($username,$pass)){
			$_SESSION['usu'] = $username;
			$_SESSION['pass'] = $pass;
			
			echo "<script>window.location='index.php';</script>";
		}else{
			$_SESSION['usu'] = "";	
			unset($_SESSION['usu']);
			$_SESSION['pass'] = "";	
			unset($_SESSION['pass']);
			//redirecciona por medio de $_post
			echo "<form id='f1' name='f1' action='index.php' method='post'>";
			echo "<input type='hidden' name='invalid' value='1' />";
			echo "<input type='hidden' name='seg' value='$seg' />";
			echo "<script>document.f1.submit();</script>";
			echo "</form>";
		}
	}else{
		$ClsUsu = new ClsUsuario();
		$rs = $ClsUsu->cambia_usu_seguridad($username,1);
		$pagina = "index.php";
		$msj = "Usted exedio del numero de intentos permitidos para ingresar, su usuario y contrase�a han sido bloqueados por seguridad, contacte al administrador del Sistema...";
		echo "<script type='text/javascript' >alert('$msj');window.location.href='$pagina'</script>";
	}
}else{
	//redirecciona por medio de $_post
	echo "<form id='f1' name='f1' action='index.php' method='post'>";
	echo "<input type='hidden' name='invalid' value='2' />";
	echo "<input type='hidden' name='seg' value='$seg' />";
	echo "<script>document.f1.submit();</script>";
	echo "</form>";
}	?>
