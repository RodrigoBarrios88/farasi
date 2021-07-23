<?php
	include_once('html_fns_ayuda.php');
	//////////////////////// CREDENCIALES DE CLIENTE
	$ClsConf = new ClsConfig();
	$result = $ClsConf->get_credenciales();
	if(is_array($result)){
		foreach($result as $row){
			$cliente_nombre = utf8_decode($row['cliente_nombre']);
			$cliente_nombre_reporte = utf8_decode($row['cliente_nombre_reporte']);
		}
	}
	$cliente_nombre = depurador_texto($cliente_nombre);
	$cliente_nombre_reporte = depurador_texto($cliente_nombre_reporte);
?>
<!DOCTYPE html>
<html>

<head>
    <?php echo head("../") ?>
	
</head>

<body class="register-page"><nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
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
						<a href="../index.php" class="nav-link">
							<i class="nc-icon nc-lock-circle-open"></i> LogIn
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
		<div class="full-page section-image" filter-color="black" data-image="../../CONFIG/img/background/bg-login.jpg">
			<!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
			<div class="content">
				<div class="container">
					<div class="col-lg-4 col-md-6 ml-auto mr-auto">
						<div class="card card-signup text-center up100">
							<div class="card-header ">
								<h4 class="card-title">
									<img alt="image" class="img-rounded" src="../../CONFIG/img/icon.png" width="30%" />
								</h4>
							</div>
							<div class="card-body ">
								<form name = "f1" id = "f1" method = "post" >
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">
												<i class="nc-icon nc-single-02"></i>
											</span>
										</div>
										<input type = "text" class = "form-control" name = "nom" id = "nom" placeholder="Nombre" />
									</div>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">
												<i class="nc-icon nc-email-85"></i>
											</span>
										</div>
										<input type="text" class="form-control" name = "email" id = "email" placeholder="Direcci&oacute;n de email" title= "Direcci&oacute;n de Correo Electr&oacute;" />
									</div>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">
												<i class="nc-icon nc-bookmark-2"></i>
											</span>
										</div>
										<input type = "text" class = "form-control" name = "subj" id = "subj" placeholder="Asunto" />
									</div>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"></span>
										</div>
										<textarea rows="2" class="form-control" placeholder="Su Mensaje" name="msj" id="msj" required data-validation-required-message="Por favor ingresa el mensaje o pregunta"></textarea>
									</div>
									<button type="button" id="btn-enviar" onclick = "enviar();" class="btn btn-primary btn-round btn-block mb-3"><i class="fa fa-send"></i> Enviar</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
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