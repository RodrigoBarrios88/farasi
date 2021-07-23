<?php
include_once('html_fns_usuarios.php');
validate_login("../");
$id = $_SESSION["codigo"];

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
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-users"></i> &nbsp; Gestor de Usuarios</h5>
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
										<label>Nombre:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="nombre" id="nombre" onkeyup="texto(this)" />
										<input type="hidden" name="codigo" id="codigo" />
									</div>
									<div class="col-md-6 text-left">
										<label>Rol de Usuario: </label>
										<?php echo utf8_decode(rol_html('rol', '', 'select2')); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Email:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control text-libre" name="mail" id="mail" onkeyup="texto(this)" />
									</div>
									<div class="col-md-6 text-left">
										<label>Telefono: </label>
										<input type="text" class="form-control" name="telefono" id="telefono" onkeyup="texto(this)" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Usuario:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control text-libre" name="usu" id="usu" onkeyup="texto(this)" />
									</div>
									<div class="col-md-6 text-left">
										<label>Contrase&ntilde;a: </label> <span class="text-danger">*</span>
										<input type="password" class="form-control" name="pass" id="pass" />
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-6 ml-auto mr-auto text-left checkbox">
										<div class="checkbox checkbox-primary sin-margin">
											<input name="habilita" id="habilita" value="0" type="checkbox" checked="" disabled />
											<label for="checkbox2">
												- Pedir Cambio de Contrase&ntilde;a al Iniciar Sesi&oacute;n.
											</label>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6 ml-auto mr-auto text-left checkbox">
										<div class="checkbox checkbox-danger sin-margin">
											<input type="checkbox" name="seguridad" id="seguridad" value="0" disabled />
											<label for="checkbox2">
												- Constraint de Seguridad.
											</label>
										</div>
									</div>
								</div>
								<br>
								<hr>
								<div class="row">
									<div class="col-md-12 text-center">
										<button type="button" class="btn btn-white" id="btn-limpiar" onclick="Limpiar();"><i class="fas fa-eraser"></i> Limpiar</button>
										<button type="button" class="btn btn-primary" id="btn-grabar" onclick="Grabar();"><i class="fas fa-save"></i> Grabar</button>
										<button type="button" class="btn btn-primary hidden" id="btn-modificar" onclick="Modificar();"><i class="fas fa-save"></i> Grabar</button>
									</div>
								</div>
							</div>
							<div class="card-footer">
								<div class="row">
									<div class="col-md-12 text-right">
										<a type="button" class="btn btn-secondary btn-sm" href="CPREPORTES/FRMreplistado.php"><i class="fas fa-print"></i> Reporte de Usuarios</a>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-lg-12" id="result">

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php echo footer() ?>
		</div>
	</div>
	<?php echo modal("../"); ?>
	<?php echo scripts("../"); ?>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/seguridad/usuario.js"></script>
	<script>
		$(document).ready(function() {
			printTable('');
			$('.select2').select2({ width: '100%' });
		});
	</script>

</body>

</html>