<?php
include_once('html_fns_valida.php');
$ClsUsu = new ClsUsuario();
$hashkey = $_REQUEST["hashkey"];
$codigo = $ClsUsu->decrypt($hashkey, "clave");
$codigo = 1;
$result = $ClsUsu->get_usuario($codigo);
if (is_array($result)) {
	foreach ($result as $row) {
		$usu = $row["usu_usuario"];
		$nombre = utf8_decode($row["usu_nombre"]);
		$mail = $row["usu_mail"];
		$telefono = $row["usu_telefono"];
	}
}

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

			<nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
				<div class="container-fluid">
					<div class="navbar-wrapper">
						<div class="navbar-minimize">
							<button id="minimizeSidebar" class="btn btn-icon btn-round">
								<i class="fas fa-bars text-center visible-on-sidebar-mini"></i>
								<i class="fas fa-bars text-center visible-on-sidebar-regular"></i>
							</button>
						</div>
						<div class="navbar-toggle">
							<button type="button" class="navbar-toggler">
								<span class="navbar-toggler-bar bar1"></span>
								<span class="navbar-toggler-bar bar2"></span>
								<span class="navbar-toggler-bar bar3"></span>
							</button>
						</div>
						<a class="navbar-brand" href="javascript:void(0);"><?php echo menu_aplicaciones('../'); ?></a>
					</div>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-bar navbar-kebab"></span>
						<span class="navbar-toggler-bar navbar-kebab"></span>
						<span class="navbar-toggler-bar navbar-kebab"></span>
					</button>
					<div class="collapse navbar-collapse justify-content-end" id="navigation">
						<ul class="navbar-nav">

						</ul>
					</div>
				</div>
			</nav>


			<div class="content">
				<form name="f1" id="f1">
					<?php if ($hashkey != "") { ?>
						<div class="card demo-icons">
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-12 text-center">
										<img alt="image" class="img-rounded" src="../../CONFIG/img/icon.png" width="100px" />
										<h3 class="text-center">Hola, para activar su usuario, por favor agregue una contrase&ntilde;a.</h3>
									</div>
								</div>
							</div>
						</div>
						<div class="card demo-icons">
							<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
								<h6 class="m-0 font-weight-bold text-muted"><i class="fa fa-user"></i> &nbsp; Datos del Perfil</h6>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-12">
										<label>Nombre:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $nombre; ?>" onkeyup="texto(this);" />
										<input type="hidden" name="codigo" id="codigo" value="<?php echo $codigo; ?>" />
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
										<input type="text" class="form-control" name="telefono" id="telefono" value="<?php echo $telefono; ?>" onkeyup="enteros(this);" />
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
					<?php } else { ?>
						<div class="card demo-icons">
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-12 text-center">
										<img alt="image" class="img-rounded" src="../../CONFIG/img/icon.png" width="100px" />
										<h3 class="text-center">Hola, para activar su usuario, por favor agregue una contrase&ntilde;a.</h3>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 text-center"><label>
									<h3 class="alert alert-warning text-center">
										<i class="fa fa-ban fa-3x"></i><br>
										Ups! Lo sentimos, el enlace de este correo est&aacute; roto...<br>
										Si es primera vez que entras al sistema, tu usuario es el correo registrado y tu contrase&ntilde;a temporal es <b>123456</b>, puedes dar click en este enlace:
										<a href="../logout.php"> Ingresar a BPManagement App</a>.<br>
										<em>(Te pedir&aacute; cambiarla cuando entres por primera vez)</em>
									</h3>
							</div>
						</div>
					<?php } ?>
				</form>
			</div>
			<?php echo footer() ?>
		</div>
	</div>
	<?php echo modal("../"); ?>
	<?php echo scripts("../"); ?>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/perfil/perfil.js"></script>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/seguridad/activar.js"></script>
</body>

</html>