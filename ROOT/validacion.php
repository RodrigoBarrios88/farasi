<?php
require_once("Clases/ClsUsuario.php");
require_once ("Clases/ClsSede.php");
require_once("Clases/ClsPermiso.php");
require_once ("Clases/ClsConfig.php");
include_once('user_auth_fns.php');

function consulta_log(){
$ClsUsu = new ClsUsuario();
$ClsSed = new ClsSede();
$ClsPerm = new ClsPermiso();
//esta funcion verifica con que tipo de navegador pretende utilizar el sistema el Usuario
check_Nav();

$usu = $_SESSION['usu'];
$pass = $_SESSION['pass'];
//////////////////////// CREDENCIALES DEL CLIENTE
$ClsConf = new ClsConfig();
$result = $ClsConf->get_credenciales();
if(is_array($result)){
	foreach($result as $row){
		$cliente_nombre = utf8_decode($row['cliente_nombre']);
		$cliente_nombre_reporte = utf8_decode($row['cliente_nombre_reporte']);
		$cliente_direccion1 = utf8_decode($row['cliente_direccion1']);
		$cliente_direccion2 = utf8_decode($row['cliente_direccion2']);
		$cliente_departamento = utf8_decode($row['cliente_departamento']);
		$cliente_municipio = utf8_decode($row['cliente_municipio']);
		$cliente_telefono = utf8_decode($row['cliente_telefono']);
		$cliente_correo = utf8_decode($row['cliente_correo']);
		$cliente_website = utf8_decode($row['cliente_website']);
	}
}
$_SESSION['cliente'] = 1;	
$_SESSION["cliente_nombre"] = $cliente_nombre;
$_SESSION["cliente_nombre_reporte"] = $cliente_nombre_reporte;
$_SESSION["cliente_direccion"] = $cliente_direccion1." ".$cliente_direccion2;
$_SESSION["cliente_departamento"] = $cliente_departamento;
$_SESSION["cliente_municipio"] = $cliente_municipio;
$_SESSION["cliente_telefono"] = $cliente_telefono;
$_SESSION["cliente_correo"] = $cliente_correo;
$_SESSION["cliente_website"] = $cliente_website;
//////////////////////////- CREDENCIALES DE CLIENTE !

//////////////////////////- MODULOS HABILITADOS
$ClsConf = new ClsConfig();
$result = $ClsConf->get_modulos();
if(is_array($result)){
	foreach($result as $row){
		$codigo = $row["mod_codigo"];
		$nombre = $row["mod_nombre"];
		$modclave = $row["mod_clave"];
		$situacion = $row["mod_situacion"];
		if($situacion == 1){
			$_SESSION["MOD_$modclave"] = 1;
		}else{
			$_SESSION["MOD_$modclave"] = "";
		}
	}
}
//////////////////////////- MODULOS HABILITADOS
	
	$result = $ClsUsu->get_login($usu,$pass);
	if(is_array($result)) {
      foreach ($result as $row){
         $codigo = $row['usu_id'];
         $nombre = trim($row['usu_nombre']); 
         $rol = $row['usu_rol']; // 1 -> Administrador , 2 -> Solicitante, 3 -> Problem Sweeper
         $cliente = $row['usu_cliente'];
         $band = $row['usu_habilita'];
         $rol_nombre = utf8_decode($row['rol_nombre']);
      }
      
      $foto = $ClsUsu->last_foto_usuario($codigo);
      if(file_exists('../CONFIG/Fotos/USUARIOS/'.$foto.'.jpg') && $foto != ""){
         $foto = 'USUARIOS/'.$foto.'.jpg';
      }else{
         $foto = "nofoto.png";
      }
      
		/// USUARIO
      $_SESSION['codigo'] = $codigo;
      $_SESSION['nombre'] = $nombre;
      $_SESSION['rol'] = $rol;
      $_SESSION['rol_nombre'] = $rol_nombre;
      $_SESSION['foto'] = $foto;
      
      $result2 = $ClsUsu->get_usuario_sede('',$codigo,'','',1);
		if(is_array($result2)) {
			$cont_sede = 1;
			foreach ($result2 as $row2){
            $_SESSION["sede_nombre_$cont_sede"] = $row2['sed_nombre'];
            $_SESSION["sede_codigo_$cont_sede"] = $row2['sed_codigo'];
            $sedes_IN.= $row2['sed_codigo'].",";
            $cont_sede++;
			}
			$cont_sede--; //quita la ultima vuelta
			$sedes_IN = substr($sedes_IN, 0, -1); // quita la ultima coma
		}
		$_SESSION["sede_cantidad"] = ($cont_sede > 0)?$cont_sede:0;
		$_SESSION["sedes_in"] = ($sedes_IN != "")?$sedes_IN:"";
      
      /////-----------------------------------------------------
      $result2 = $ClsUsu->get_usuario_categoria('',$codigo,'','',1);
		if(is_array($result2)) {
			$cont_categoria = 1;
			foreach ($result2 as $row2){
            $_SESSION["categoria_nombre_$cont_categoria"] = $row2['cat_nombre'];
            $_SESSION["categoria_codigo_$cont_categoria"] = $row2['cat_codigo'];
            $categorias_IN.= $row2['cat_codigo'].",";
            $cont_categoria++;
			}
			$cont_categoria--; //quita la ultima vuelta
			$categorias_IN = substr($categorias_IN, 0, -1); // quita la ultima coma
		}
		$_SESSION["categoria_cantidad"] = ($cont_categoria > 0)?$cont_categoria:0;
		$_SESSION["categorias_in"] = ($categorias_IN != "")?$categorias_IN:"";
      
      //// PERMISOS
		$result = $ClsPerm->get_asi_permisos($codigo);
		if (is_array($result)) {
			$gpcod1 = "";
			$gpcod2 = "";
			foreach ($result as $row){
				$gpclave = trim($row['gperm_clave']); //Clave de grupo
				$clave = trim($row['perm_clave']); //clave de permiso
				$nivel = $row['roll_nombre']; //nombre del rol
				$_SESSION["GRP_$gpclave"] = 1;
				$_SESSION["$clave"] = 1;
			}
		}
		
		///////// Valida si se pide cambio de contraseña
      if($band != 1){
         //Header('Location: FRMcambia_pass.php');
         redirect('CPUSUARIOS/FRMcambia_pass.php',0);
      }else{
         redirect('menu.php',0);
      }
	}else{
		//redirecciona por medio de $_post
		echo "<form id='f1' name='f1' action='index.php' method='post'>";
		echo "<input type='hidden' name='invalid' value='1' />";
		echo "<input type='hidden' name='seg' value='0' />";
		echo "<script>document.f1.submit();</script>";
		echo "</form>";
	}
	
}

function redirect($url,$seconds){
	$ss = $seconds * 1000;
	$comando = "<script>window.setTimeout('window.location=".chr(34).$url.chr(34).";',".$ss.");</script>";
	echo ($comando);
}

function check_Nav(){
   $comando = "<script>
   var browser=navigator.appName;
   if (browser == 'Microsoft Internet Explorer'){
		if (confirm('No se puede Ingresar a este Sistema por medio de Internet Explorer, se recomienda utilizar FireFox o algun otro Navegador de Netscape. Desa Descargar FireFox?')){
			window.location.href='logout2.php';
		}else{
			window.location.href='logout.php';
		}
	}
	</script>";
		
    echo ($comando);
}?>