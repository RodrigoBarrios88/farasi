<?php
    include_once('xajax_funct_ayuda.php');
	require_once("../recursos/mandrill/src/Mandrill.php"); //--correos
	require_once("../../CONFIG/constructor.php"); //--correos
	$tipo = $_REQUEST['tipo'];
	$mail = $_REQUEST['email'];//////////////////////// CREDENCIALES DE CLIENTE
	$ClsConf = new ClsConfig();
	$result = $ClsConf->get_credenciales();
	if(is_array($result)){
		foreach($result as $row){
			$cliente_nombre = utf8_decode($row['cliente_nombre']);
			$cliente_nombre_reporte = utf8_decode($row['cliente_nombre_reporte']);
		}
	}
	$cliente_nombre = depurador_texto($cliente_nombre);
	$cliente_nombre_reporte = depurador_texto($cliente_nombre_reporte);?>
<html>
	
<head>
   <?php echo head("../"); ?>
</head>

<body class="login-page"><nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
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
						<a href="../CPAYUDA/FRMcontact_admin.php" class="nav-link">
							<i class="nc-icon nc-tap-01"></i> Contactar al Administrador
						</a>
					</li>
					<li class="nav-item ">
						<a href="../CPAYUDA/FRMpregunta_clave.php" class="nav-link">
							<i class="nc-icon nc-key-25"></i> Recuperar Contrase&ntilde;a
						</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>	<div class="wrapper wrapper-full-page ">
		<div class="full-page section-image" filter-color="black" data-image="../../CONFIG/img/background/bg-contact.jpg">
	
<?php    ///////////
    $nombre = $_REQUEST["nom"];
    $mail = $_REQUEST["email"];
    $subj = $_REQUEST["subj"];
    $msj = $_REQUEST["msj"];

    $mailadmin = "soporte@farasi.com.gt";
    // Instancia el API KEY de Mandrill
	$mandrill = new Mandrill('aLGRM5YodGYp_GDBwwDilw');
	//--
	// Create the email and send the message
	$to = array(
		array(
            'email' => $mailadmin,
            'name' => 'Administrador',
            'type' => 'to'
		)
	);
	
  /////////////_________ Correo a admin
	$subject = utf8_encode(utf8_decode("Correo al Administrador desde BPManagement de $cliente_nombre"));
	$cuerpo = "Has recibido un nuevo mensaje desde el Sistema BPManagement de $cliente_nombre. <br><br>"."Aqui estan los detalles:<br><br>Nombre: $nombre<br>E-mail: $mail<br>Asunto: $subj<br>Mensaje: $msj<br><br>Que pases un feliz dia!!!";
	$html = mail_constructor($subject, $cuerpo);
	try{		$message = array(
			'subject' => $subject,
			'html' => $html,
			'from_email' => 'noreply@farasi.com.gt',
			'from_name' => 'BPManagement',
			'to' => $to
		 );
		 
		//print_r($message);
		$result = $mandrill->messages->send($message);
		$status = 1;
		$msj = "Correo enviado con exito!";
	} catch(Mandrill_Error $e) { 
		//echo "<br>";
		//print_r($e);
		//devuelve un mensaje de manejo de errores
		$status = 0;
		$msj = "Error en el envio de correos...";
	}         
	
?>            <script type='text/javascript' >
                function mensaje(status){
                    if(status == 1){
                        swal("Ok!", "Su mensaje ha sido enviado exitosamente, en un momento uno de nuestros agentes lo contactara...", "success").then((value)=>{ window.history.back(); });
                    }else{
                        swal("Ohoo!", "Su mensaje no ha podido ser entregado en este momento, lo sentimos...", "error").then((value)=>{ window.history.back(); });
                    }
                }
                window.setTimeout('mensaje(<?php echo $status; ?>);',1000);
            </script>            <?php echo footer() ?>
		</div>
    </div>
    
	<?php echo scripts("../") ?>
    <!-- Paper Dashboard DEMO methods, don't include it in your project! -->
	<script src="../assets.1.2.8/template/template.js"></script>
	<script>
		$(document).ready(function() {
			demo.checkFullPageBackgroundImage();
		});
	</script>
    <script type="text/javascript" src="../assets.1.2.8/js/modules/seguridad/pregunta.js"></script>
   
	
</body>
</html>