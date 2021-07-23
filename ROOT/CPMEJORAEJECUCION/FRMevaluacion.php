<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$id = $_SESSION["codigo"];
// Sistema
$ClSis = new ClsSistema();
$sistemas = $ClSis->get_sistema("", "", "", $id);
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
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="far fa-star"></i> Evaluaci&oacute;n de Actividades
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
								<br>
								<div class="row">
									<div class="col-lg-12" id="result">
										<?php echo trim(tabla_evaluacion($id)); ?>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/ryo/evaluacion.js"></script>
	<script>
		$(document).ready(function() {
			$('.dataTables-example').DataTable({
				pageLength: 50,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: []
			});
		});
	</script>
</body>

</html>