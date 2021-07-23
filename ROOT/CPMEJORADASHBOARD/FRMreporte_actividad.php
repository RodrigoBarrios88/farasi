<?php
include_once('html_fns_dashboard.php');
validate_login("../");
$id = $_SESSION["codigo"];
//$_POST
$proceso = $_REQUEST["proceso"];
$sistema = $_REQUEST["sistema"];
$usuario = $_REQUEST["usuario"];
$desde = $_REQUEST["desde"];
$hasta = $_REQUEST["hasta"];
$columnas = $_REQUEST["columnas"];
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
								<h5 class="card-title"><i class="fa fa-bar-chart-o"></i> Reporte de Actividades</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12" id="result">
										<?php
										echo tabla_reporte_actividad($proceso, $sistema, $desde, $hasta, $usuario, $columnas);
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
	<script>
		$(document).ready(function() {
			$('.dataTables-example').DataTable({
				pageLength: 100,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: [{
						extend: 'copy'
					},
					{
						extend: 'csv'
					},
					{
						extend: 'print',
						customize: function(win) {
							$(win.document.body).addClass('white-bg');
							$(win.document.body).css('font-size', '10px');
							$(win.document.body).find('table')
								.addClass('compact')
								.css('font-size', 'inherit');
						},
						title: 'Reporte de Actividades'
					}
				]
			});
		});
	</script>
</body>

</html>