<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$sede = $_REQUEST["sede"];
$area = $_REQUEST["area"];
$activo = $_REQUEST["activo"];
$usuario = $_REQUEST["usuario"];
$situacion = $_REQUEST["situacion"];
//--
$mes = date("m");
$anio = date("Y");
$desde = $_REQUEST["desde"];
$desde = ($desde == "") ? date("d/m/Y") : $desde; //valida que si no se selecciona fecha, coloque la del dia
$hasta = $_REQUEST["hasta"];
$hasta = ($hasta == "") ? date("d/m/Y") : $hasta; //valida que si no se selecciona fecha, coloque la del dia
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../", "ppm"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="fa fa-list"></i> &Iacute;ndice de Reportes
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-6">
										<label>Reportes de Activos</label>
										<hr>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<a class="text-muted" href="../CPACTIVO/FRMrepactivo.php">
											<h5><i class="fa fa-print"></i> Reporte de Activos</h5>
										</a>
									</div>
									<div class="col-md-6">
										<a class="text-muted" href="../CPACTIVO/FRMrepfichas.php">
											<h5><i class="fa fa-copy"></i> Fichas de Activos</h5>
										</a>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<a class="text-muted" href="../CPACTIVO/FRMrepinactivo.php">
											<h5><i class="fa fa-print"></i> Reporte de Activos Fuera de L&iacute;nea</h5>
										</a>
									</div>
									<div class="col-md-6">
										<a class="text-muted" href="../CPACTIVO/FRMrepfalla.php">
											<h5><i class="fa fa-print"></i> Reporte de Fallas</h5>
										</a>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-6">
										<label>Reportes de Ordenes de Trabajo</label>
										<hr>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<a class="text-muted" href="../CPPPMPROGRA/FRMreportes.php">
											<h5><i class="fa fa-print"></i> Reporte de Programaci&oacute;n</h5>
										</a>
									</div>
									<div class="col-md-6">
										<a class="text-muted" href="../CPPPMEJECUCION/FRMreptareas.php">
											<h5><i class="fa fa-print"></i> Reporte de Tareas</h5>
										</a>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<a class="text-muted" href="../CPPPMEJECUCION/FRMpresupuesto.php">
											<h5><i class="fa fa-print"></i> Reporte de Ejecuci&oacute;n Presupuestaria</h5>
										</a>
									</div>
									<div class="col-md-6">

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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/ppm/ejecucion.js"></script>
	<script>
		$(document).ready(function() {
			$('.dataTables-example').DataTable({
				pageLength: 100,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: [

				]
			});

			$('#range .input-daterange').datepicker({
				keyboardNavigation: false,
				forceParse: false,
				autoclose: true,
				format: "dd/mm/yyyy"
			});

			$('.select2').select2({ width: '100%' });
		});
	</script>

</body>

</html>