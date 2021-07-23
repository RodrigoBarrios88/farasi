<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../", "encuestas"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-body all-icons">
								<div class="nav-tabs-navigation">
									<div class="nav-tabs-wrapper">
										<ul id="tabs" class="nav nav-tabs" role="tablist">
											<li class="nav-item active">
												<a class="nav-link" href="../ENCCUESTIONARIO/FRMreportes.php">
													<h6><i class="fa fa-print"></i> Invitaciones</h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="../ENCEJECUCION/FRMreportes.php">
													<h6><i class="fa fa-print"></i> Ejecuciones</h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link active" href="../ENCEJECUCION/FRMcuestionarios.php">
													<h6><i class="fas fa-chart-bar"></i> Resultados por Cuestionario</h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link " href="../ENCEJECUCION/FRMresultados.php">
													<h6><i class="fas fa-chart-pie"></i> Resultados Generales</h6>
												</a>
											</li>
										</ul>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<!-- .card -->
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="fas fa-paste"></i> Listado de Cuestionarios
									<button type="button" button class="btn btn-white btn-lg sin-margin pull-right" onclick="window.history.back();"><small><i class="nc-icon nc-minimal-left"></i> Regresar</small></button>
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12">
										<?php echo tabla_cuestionarios(); ?>
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


	<script>
		$(document).ready(function() {
			$('.dataTables-example').DataTable({
				pageLength: 100,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: []
			});
			$('.select2').select2();
		});
	</script>

</body>

</html>