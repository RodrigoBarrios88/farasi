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
									<i class="fa fa-sitemap"></i> Gestor de Roles de Permiso
									<a class="btn btn-white btn-lg sin-margin pull-right" href="FRMnewrol.php"><small><i class="nc-icon nc-simple-add"></i> Nuevo Rol</small></a>
									<a class="btn btn-white btn-lg sin-margin pull-right" href="FRMpermisos.php"><small><i class="nc-icon nc-lock-circle-open"></i> Gestor de Permisos</small></a>
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-xs-6 col-md-6 text-left">
										<button type="button" class="btn btn-white" onclick="window.history.back();">
											<i class="fa fa-chevron-left"></i>Atr&aacute;s
										</button>
									</div>
								</div>
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


	<script type="text/javascript" src="../assets.1.2.8/js/modules/seguridad/rol.js?v=1.1.10"></script>
	<script>
		$(document).ready(function() {
			printTable();
		});
	</script>

</body>

</html>