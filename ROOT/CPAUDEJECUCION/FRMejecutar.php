<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$nombre = $_REQUEST["nom"];?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../", "auditoria"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="nc-icon nc-paper"></i> Auditorias Programadas</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-xs-6 col-md-6 text-left"><a class="btn btn-white" href="FRMrevisiones.php"><i class="fa fa-chevron-left"></i> Atras</a> </div>
								</div>
								<div class="row">
									<div class="col-lg-12" id="result">
										<?php
										$usuario = $_SESSION["codigo"];
										$sedesIn = $_SESSION["sedes_in"];
										echo tabla_programacion("", $sedesIn, $usuario, date("d/m/Y"), date("d/m/Y"));
										?>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/auditoria/ejecucion.js"></script>
	<script>
		$(document).ready(function() {
			$('.dataTables-example').DataTable({
				pageLength: 100,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: [

				]
			});

			$('.select2').select2({ width: '100%' });
		});
	</script>

</body>
</html>