<?php
include_once('html_fns_dashboard.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$proceso = $_REQUEST["proceso"];
$sistema = $_REQUEST["sistema"];
$usuario = $_REQUEST["usuario"];
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
	<!-- Estilo especifico -->
	<link href="../assets.1.2.8/css/propios/dashboard.css" rel="stylesheet">
</head>

<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("../", "risk"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?> <div class="content">
				<!--  Reporte General de Riesgos y Oportunidades -->
				<div class="row">
					<div class="col-lg-12" id="result">
						<div class="card">
							<div class="card-header ">
								<h4 class="card-title"><i class="nc-icon nc-chart-pie-36"></i> Reporte de Riesgos y Oportunidades</h4>
							</div>
							<div class="card-body ">
								<div class="row">
									<div class="col-lg-12" id="result">
										<?php echo utf8_decode(tabla_cumplimiento_usuario($id)); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--  Grafica de Cumplimientos -->
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<div class="card">
							<div class="card-header ">
								<h4 class="card-title"><i class="nc-icon nc-chart-pie-36"></i> Estad&iacute;sticas de Cumplimiento</h4>
							</div>
							<div class="card-body ">
								<div class="row">
									<div class="col-md-6 text-center">
										<h5 class="card-category">Cumplimiento Porcentaje General</h5>
										<div id="pieContainer"></div>
										<br>
										<div class="progress progress-striped active">
											<div id="progressEjecutado" class="progress-bar progress-bar-striped progress-bar-animated progress-bar-success">
												<span id="spanEjecutado"></span>
											</div>
											<div id="progressPendiente" class="progress-bar progress-bar-striped progress-bar-animated progress-bar-warning">
												<span id="spanPendiente"></span>
											</div>
											<div id="progressVencido" class="progress-bar progress-bar-striped progress-bar-animated progress-bar-danger">
												<span id="spanVencido"></span>
											</div>
										</div>
									</div>
									<div class="col-md-6 text-center">
										<h5 class="card-category">Cumplimiento por Proceso</h5>
										<div id="stocked1Container"></div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6 text-center">
										<h5 class="card-category">Cumplimiento por Sistema</h5>
										<div id="stocked2Container"></div>
									</div>
									<div class="col-md-6 text-center">
										<h5 class="card-category">Cumplimiento por Tipo de Proceso</h5>
										<div id="stocked0Container"></div>
									</div>
								</div>
							</div>
							<div class="card-footer text-right">
								<hr>
								<div class="stats">
									<i class="fa fa-clock-o"></i> <?php echo $desde . ' - ' . $hasta; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--  Tabla -->
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<div class="card">
							<div class="card-header ">
								<h4 class="card-title"><i class="nc-icon nc-calendar-60"></i> Programaci&oacute;n para Hoy</h4>
								<h5 class="card-category">Areas - Horarios</h5>
							</div>
							<div class="card-body" id="tablaContainer">
								<?php echo utf8_decode(tabla_programacion($id)) ?>
							</div>
							<div class="card-footer text-right">
								<hr>
								<div class="stats">
									<i class="fa fa-clock-o"></i> <?php echo $desde . ' - ' . $hasta; ?>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/ryo/reporte.js"></script>
</body>

</html>