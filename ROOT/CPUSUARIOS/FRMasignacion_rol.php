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
								<h5 class="card-title">
									<i class="nc-icon nc-tag-content"></i> Asignaci&oacute;n de Permisos
									<a class="btn btn-white btn-lg sin-margin pull-right" href="../CPPERMISOS/FRMrol.php"><small><i class="fas fa-cogs"></i> Gestor de Roles</small></a>
								</h5>
							</div>
							<div class="card-body all-icons" id="encabezado">

							</div>
							<div class="card-body all-icons" id="cuerpo">

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
			printTableAsignacion();
			$('.select2').select2({ width: '100%' });
		});
	</script>

</body>

</html>