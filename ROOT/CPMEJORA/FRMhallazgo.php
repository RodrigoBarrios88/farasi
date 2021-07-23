<?php
include_once('html_fns_mejora.php');
validate_login("../");
$id = $_SESSION["codigo"];
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("../", "mejora"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?> <div class="content">
				<div class="row">
					<div class="col-md-6">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="nc-icon nc-paper"></i> Auditorias Internas
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12">
										<?php echo tabla_auditoria_interna() ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="fa fa-file-contract"></i> Auditorias Externas
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12">
										<?php echo utf8_decode(tabla_identificar_externas()) ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="fas fa-frown"></i> Salidas No Conformes
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12">
										<?php echo utf8_decode(tabla_identificar_quejas()) ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="fas fa-chart-line"></i> Incumplimiento de Indicadores
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12">
										<?php echo tabla_indicadores() ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="fas fa-fire-alt"></i> Riesgos Materializados
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12">
										<?php echo tabla_riesgos() ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="fas fa-book-reader"></i> Incumplimiento Legal
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12">
									<?=tabla_identificar_requisitos()?> 
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/mejora/hallazgo.js"></script>
	<script>
		$(document).ready(function() {
			$('.dataTables-example').DataTable({
				pageLength: 5,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: []
			});
			$('.select2').select2({ width: '100%' });
		});
	</script>

</body>

</html>