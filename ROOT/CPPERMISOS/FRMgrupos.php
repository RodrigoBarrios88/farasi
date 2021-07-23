<?php
include_once('html_fns_permiso.php');
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
								<h5 class="card-title">
									<i class="nc-icon nc-lock-circle-open"></i> Gestor de Grupos de Permisos
									<a class="btn btn-white btn-lg sin-margin pull-right" href="FRMrol.php"><small><i class="nc-icon nc-minimal-left"></i> Regresar</small></a>
									<a class="btn btn-white btn-lg sin-margin pull-right" href="FRMpermisos.php"><small><i class="nc-icon nc-lock-circle-open"></i> Gestor de Permisos</small></a>
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-6">
										<label>Clave: </label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="clv" id="clv" onkeyup="texto(this);" maxlength="10" />
										<input type="hidden" name="cod" id="cod" />
									</div>
									<div class="col-md-6 text-right"><label class=" text-danger">* Campos Obligatorios</label></div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Descripci&oacute;n: </label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="desc" id="desc" onkeyup="texto(this);" />
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-12 text-center">
										<button type="button" class="btn btn-white" id="btn-limpiar" onclick="Limpiar();"><i class="fas fa-eraser"></i> Limpiar</button>
										<button type="button" class="btn btn-primary" id="btn-grabar" onclick="Grabar();"><i class="fas fa-save"></i> Grabar</button>
										<button type="button" class="btn btn-primary hidden" id="btn-modificar" onclick="Modificar();"><i class="fas fa-save"></i> Grabar</button>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-lg-12" id="result">

									</div>
								</div>
								<br>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/seguridad/grupo.js"></script>
	<script>
		$(document).ready(function() {
			printTable('', '');
		});
	</script>

</body>
</html>