<?php
   /*Programador: Manuel Sosa Azurdia
   Fecha: Enero de 2019*/

   // crea una sesi�n o reanuda la actual basada en un identificador de sesi�n pasado mediante una petici�n GET o POST, 
   //o pasado mediante una cookie. 
   session_start();
   ini_set("default_charset", "iso-8859-1");

   require_once ("Clases/ClsUsuario.php");
   require_once ("Clases/ClsConfig.php");
   // verifica si el usuario tiene permisos en la BD's
   function login($usu,$pass){
      $usu = decode($usu);
      $pass = decode($pass);
      $ClsUsu = new ClsUsuario();   
		$result = $ClsUsu->get_login($usu,$pass);
		if (is_array($result)) {
			return true;
		}else {
			return false;
		} 
   }
  
  //verifica si el usuario esta logeado o si no 	
  function check_auth_user(){
      global $_SESSION;
      if (isset($_SESSION['usu'])){
         return true;
      }else{
         return false;
      }
  }
  
  //quitar caracteres especiales para evitar sqlinjection
  function decode($string){ 
		$nopermitidos = array("'",'\\','<','>',"\"","-","%");
		$string = str_replace(" ","",$string);
      $string = str_replace($nopermitidos, "", $string);
      return $string;
	}   
  
  
  //muestra la pagina de login
function login_form($inv,$cont) {
//////////////////////// CREDENCIALES DE CLIENTE
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

$cont = ($cont == "")?0:$cont;
?>
<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8" />
   <link rel="apple-touch-icon" sizes="76x76" href="../CONFIG/img/apple-icon.png">
   <link rel="shortcut icon" href="../CONFIG/img/icono.png">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
   <title><?php echo $cliente_nombre; ?></title>
   <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
   <!-- Fonts and icons -->
   <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
   <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
   <!-- CSS Files -->
   <link href="assets.1.2.8/css/bootstrap.min.css" rel="stylesheet" />
   <link href="assets.1.2.8/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />
   <!-- CSS propios -->
   <link href="assets.1.2.8/css/propios/formulario.css" rel="stylesheet">
   <link href="assets.1.2.8/css/propios/custom.fonts.css" rel="stylesheet">
   <script src="https://kit.fontawesome.com/907a027ade.js" crossorigin="anonymous"></script>
   <link href="assets.1.2.8/css/propios/login.css" rel="stylesheet">
</head>

<body class="login-page">
   <!-- Navbar -->
   <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
      <div class="container">
         <div class="navbar-wrapper">
            <div class="navbar-toggle">
               <button type="button" class="navbar-toggler">
                  <span class="navbar-toggler-bar bar1"></span>
                  <span class="navbar-toggler-bar bar2"></span>
                  <span class="navbar-toggler-bar bar3"></span>
               </button>
            </div>
            <a class="navbar-brand" href="#"><?php echo $cliente_nombre; ?></a>
         </div>
         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
         </button>
         <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <ul class="navbar-nav">
               <li class="nav-item  active ">
                  <a href="CPAYUDA/FRMcontact_admin.php" target="_blank" class="nav-link">
                     <i class="nc-icon nc-tap-01"></i> Contactar al Administrador
                  </a>
               </li>
               <li class="nav-item ">
                  <a href="CPAYUDA/FRMpregunta_clave.php" target="_blank" class="nav-link">
                     <i class="nc-icon nc-key-25"></i> Recuperar Contrase&ntilde;a
                  </a>
               </li>
            </ul>
         </div>
      </div>
   </nav>
   <!-- End Navbar -->
   <div class="wrapper wrapper-full-page ">
      <div class="full-page section-image" filter-color="black" data-image="../CONFIG/img/background/bg-login.jpg">
         <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
         <div class="content">
            <div class="container">
               <div class="col-lg-4 col-md-6 ml-auto mr-auto">
                  <form class="form" action="login.php" method="post">
                     <div class="card card-login">
                        <div class="card-header ">
                           <div class="card-header ">
                              <h3 class="header text-center">
                                 <img src="../CONFIG/img/logo_largo.png" width="150px" />
                              </h3>
                           </div>
                        </div>
                        <div class="card-body ">
                           <?php
                              if($inv == 1){
                                 $cont++;
                           ?>
                              <div class="alert alert-danger text-center" role="alert"> Usuario o Contrase&ntilde;a invalida...</div>
                           <?php
                              }else if($inv == 2){
                                 $cont++;
                           ?>
                              <div class="alert alert-danger text-center" role="alert"> Uno o mas Campos est&aacute;n vacios...</b></div>
                           <?php
                              }	
                           ?>
                           <div class="input-group">
                              <div class="input-group-prepend">
                                 <span class="input-group-text">
                                    <i class="nc-icon nc-single-02"></i>
                                 </span>
                              </div>
                              <input type="text" class="form-control" placeholder="Usuario" name="usu" id="usu" required="">
                           </div>
                           <div class="input-group">
                              <div class="input-group-prepend">
                                 <span class="input-group-text">
                                    <i class="nc-icon nc-key-25"></i>
                                 </span>
                              </div>
                              <input type="password" class="form-control" placeholder="Password" id = "pass" name = "pass" required="">
                           </div>
                        </div>
                        <div class="card-footer ">
                           <button type="submit" class="btn btn-primary btn-round btn-block mb-3"><i class="fa fa-sign-in"></i> Iniciar Sesi&oacute;n</button>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!--   Core JS Files   -->
   <script src="assets.1.2.8/js/core/jquery.min.js"></script>
   <script src="assets.1.2.8/js/core/popper.min.js"></script>
   <script src="assets.1.2.8/js/core/bootstrap.min.js"></script>
   <script src="assets.1.2.8/js/plugins/perfect-scrollbar.jquery.min.js"></script>
   <script src="assets.1.2.8/js/plugins/moment.min.js"></script>
   <!-- Paper Dashboard DEMO methods, don't include it in your project! -->
   <script src="assets.1.2.8/template/template.js"></script>
   <script>
     $(document).ready(function() {
       demo.checkFullPageBackgroundImage();
     });
   </script>
</body>
</html>
<?php } ?>