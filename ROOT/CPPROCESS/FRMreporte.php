<?php
include_once('html_fns_proceso.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$tipo = $_REQUEST["tipo"];
$usuario = $_REQUEST["usuario"];
$situacion = $_REQUEST["situacion"];
//--
$fini = $_REQUEST["fini"];
$fini = ($fini == "") ? date("d/m/Y") : $fini; //valida que si no se selecciona fecha, coloque la del dia
$ffin = $_REQUEST["ffin"];
$ffin = ($ffin == "") ? date("d/m/Y") : $ffin; //valida que si no se selecciona fecha, coloque la del dia
//
$columnas = $_REQUEST["columnas"];
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("../", "process"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-bar-chart-o"></i> Reporte de Procesos</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12" id="result">
										<?php
										echo tabla_reportes($tipo, $usuario, $fini, $ffin, $situacion, $columnas);
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


	<script type="text/javascript" src="../assets.1.2.8/js/modules/checklist/revision.js"></script>
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
						title: 'Reporte de Revisiones'
					}
				]
			});
		});
	</script>

</body>

</html>