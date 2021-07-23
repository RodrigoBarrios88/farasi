<?php
include_once('../validacion.php');
include_once('html_fns_usuarios.php');
$nombre = utf8_decode($_SESSION["nombre"]);
$codigo = $_SESSION["codigo"];
$usu = $_SESSION["usu"];
$ClsUsu = new ClsUsuario();
$result = $ClsUsu->get_usuario($codigo);
if (is_array($result)) {
	foreach ($result as $row) {
		$usuario = $row["usu_usuario"];
		$nombres = utf8_decode($row["usu_nombre"]);
		$mail = $row["usu_mail"];
		$tel = $row["usu_telefono"];
		$preg = $row["usu_pregunta"];
		$resp = $row["usu_respuesta"];
		$resp = $ClsUsu->decrypt($resp, $usuario);
	}
} //////////////////////// CREDENCIALES DE CLIENTE
$ClsConf = new ClsConfig();
$result = $ClsConf->get_credenciales();
if (is_array($result)) {
	foreach ($result as $row) {
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
	<?php echo head("../"); ?>
</head>

<body class="sidebar-mini">
	<div class="wrapper ">
		<div class="sidebar" data-color="brown" data-active-color="danger">
			<div class="logo">
				<a href="../menu.php" class="simple-text logo-mini">
					<div class="logo-image-small">
						<img src="../../CONFIG/img/logo2.png" />
					</div>
			</div>
			<div class="sidebar-wrapper">
				<ul class="nav">
					<li>
						<a href="../logout.php">
							<i class="fa fa-power-off"></i>
							<p>Salir</p>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<form name="f1" id="f1">
					<br>
					<div class="row">
						<div class="col-md-12 text-center">
							<h6 class="alert alert-primary"><i class="fa fa-lock"></i> Por favor cambie su contrase&ntilde;a y actualice sus datos</label></h6>
						</div>
					</div>
					<div class="card demo-icons">
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h6 class="m-0 font-weight-bold text-muted"><i class="fa fa-user"></i> &nbsp; Datos del Perfil</label></h6>
						</div>
						<div class="card-body all-icons">
							<div class="row">
								<div class="col-md-12">
									<label>Nombre:</label> <span class="text-danger">*</span>
									<input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $nombres; ?>" onkeyup="texto(this);" />
									<input type="hidden" name="codigo" id="codigo" value="<?php echo $codigo; ?>" />
									<input type="hidden" name="cliente" id="cliente" value="<?php echo $cliente; ?>" />
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 text-left">
									<label>Email: </label>
									<input type="text" class="form-control" name="mail" id="mail" value="<?php echo $mail; ?>" />
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 text-left">
									<label>Tel&eacute;fono: </label>
									<input type="text" class="form-control" name="tel" id="tel" value="<?php echo $tel; ?>" onkeyup="enteros(this);" />
								</div>
							</div>
							<br>
						</div>
					</div>
					<br>
					<div class="card demo-icons">
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h6 class="m-0 font-weight-bold text-muted"><i class="fa fa-lock"></i> &nbsp; Seguridad</label></h6>
						</div>
						<div class="card-body all-icons">
							<div class="row">
								<div class="col-md-12">
									<label>Usuario:</label> <span class="text-danger">*</span>
									<input type="text" class="form-control" name="usu" id="usu" value="<?php echo $usu; ?>" onkeyup="texto(this);" />
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 text-left">
									<label>Nuevo Password: </label>
									<div class="input-group">
										<input type="password" class="form-control" name="pass1" id="pass1" onkeyup="comprueba_vacios(this,'pas1');" />
										<div class="input-group-prepend">
											<span class="input-group-text"><i id="pas1"></i></span>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 text-left">
									<label>Nuevo Password: </label>
									<div class="input-group">
										<input type="password" class="form-control" name="pass2" id="pass2" onkeyup="comprueba_iguales(this,document.f1.pass1);" />
										<div class="input-group-prepend">
											<span class="input-group-text"><i id="pas2"></i></span>
										</div>
									</div>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-12 text-left">
									<div class="progress">
										<div id="progress1" class="progress-bar progress-bar-danger progress-bar-striped" style="width: 0%"></div>
										<div id="progress2" class="progress-bar progress-bar-warning progress-bar-striped" style="width: 0%"></div>
										<div id="progress3" class="progress-bar progress-bar-success progress-bar-striped" style="width: 0%"></div>
									</div>
								</div>
							</div>
							<br>
						</div>
					</div>
					<br>
					<hr>
					<div class="row">
						<div class="col-md-12 text-center">
							<button type="button" class="btn btn-primary" id="btn-aceptar" onclick="aceptar();"><i class="fa fa-check"></i> Aceptar</button>
						</div>
					</div>
					<br>
				</form>
			</div>
			<?php echo footer() ?>
		</div>
	</div>
	<?php echo modal("../"); ?>
	<?php echo scripts("../"); ?>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/perfil/perfil.js"></script>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/seguridad/cambiapass.js"></script>
</body>

</html>