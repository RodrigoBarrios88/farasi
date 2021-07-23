<?php
include_once('html_fns_perfil.php');
validate_login("../");
$id = $_SESSION["codigo"];

$ClsUsu = new ClsUsuario();
$result = $ClsUsu->get_usuario($id);
if (is_array($result)) {
	foreach ($result as $row) {
		$nombres = utf8_decode($row["usu_nombre"]);
		$usuario = $row["usu_usuario"];
		$dias = $row["usu_dias_pass"];
		$cambio = $row["usu_cambio"];
	}
}
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../", "herramientas"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<form name="f1" id="f1">
							<div class="card demo-icons">
								<div class="card-header">
									<h5 class="card-title"><i class="fa fa-unlock"></i> &nbsp; Seguridad y Contrase&ntilde;a</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-xs-6 col-md-6 text-left">
										<button type="button" class="btn btn-white" onclick="window.history.back();">
											<i class="fa fa-chevron-left"></i>Atr&aacute;s
										</button>
									</div>
										<div class="col-xs-6 col-md-6 text-right"><label class=" text-danger">* Campos Obligatorios</label> </div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Nombre de Usuario:</label> <span class="text-danger">*</span>
											<span class="form-control"><?php echo $nombres; ?></span>
											<input type="hidden" name="codigo" id="codigo" value="<?php echo $id; ?>" />
										</div>
										<div class="col-md-6">
											<label>Usuario:</label> <span class="text-danger">*</span>
											<input type="text" class="form-control" name="usu" id="usu" value="<?php echo $usuario; ?>" />
										</div>
									</div>
									<div class="row">
										<div class="col-md-5">
											<label>Contrase&ntilde;a:</label> <span class="text-danger">*</span>
											<input type="password" class="form-control" name="pass1" id="pass1" onkeyup="comprueba_vacios(this,'pas1')" />
										</div>
										<div class="col-md-1">
											<label>&nbsp;</label>
											<span class="form-control">&nbsp;<i id="pas1"></i></span>
										</div>
										<div class="col-md-5">
											<label>Confirme Contrase&ntilde;a:</label> <span class="text-danger">*</span>
											<input type="password" class="form-control" name="pass2" id="pass2" onkeyup="comprueba_iguales(this,document.f1.pass1)" />
										</div>
										<div class="col-md-1">
											<label>&nbsp;</label>
											<span class="form-control">&nbsp;<i id="pas2"></i></span>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-md-12">
											<small>Fortaleza de la Contrase&ntilde;a</small>
											<div class="progress">
												<div id="progress1" class="progress-bar progress-bar-danger progress-bar-striped" style="width: 0%"></div>
												<div id="progress2" class="progress-bar progress-bar-warning progress-bar-striped" style="width: 0%"></div>
												<div id="progress3" class="progress-bar progress-bar-success progress-bar-striped" style="width: 0%"></div>
											</div>
										</div>
									</div>
									<br>
									<hr>
									<div class="row">
										<div class="col-md-12 text-center">
											<button type="button" class="btn btn-white" id="btn-limpiar" onclick="Limpiar();"><i class="fas fa-eraser"></i> Limpiar</button>
											<button type="button" class="btn btn-primary" id="btn-grabar" onclick="ModificarPass();"><i class="fas fa-save"></i> Grabar</button>
										</div>
									</div>
									<br>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<?php echo footer() ?>
		</div>
	</div>
	<?php echo modal("../"); ?>
	<?php echo scripts("../"); ?>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/perfil/perfil.js"></script>
</body>
</html>