<?php
include_once('html_fns_planning.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
//--
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
			<?php echo sidebar("../","planning"); ?>
			<div class="main-panel">
				<?php echo navbar("../"); ?> <div class="content">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="card">
								<div class="card-header">
									<h4 class="card-title"><i class="nc-icon nc-zoom-split"></i> Filtros</h4>
								</div>
								<div class="card-body ">
									<div class="table-full-width ">
										<form name="f1" id="f1" method="get">
											<div class="row">
												<div class="col-md-6">
													<label>Proceso:</label> <span class="text-info">*</span>
													<div>
														<?php echo utf8_decode(ficha_html("proceso", "Submit()", "select2", $id)); ?>
													</div>
													<script>
														document.getElementById("proceso").value = "<?php echo $departamento; ?>";
													</script>
												</div>
												<div class="col-md-6">
													<label>Sistema:</label> <span class="text-info">*</span>
													<div>
														<?php echo utf8_decode(sistema_html("sistema", "Submit()", "select2")); ?>
													</div>
													<script>
														document.getElementById("sistema").value = "<?php echo $sistema; ?>";
													</script>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<label>Usuario:</label> <span class="text-info">*</span>
													<div>
														<?php echo utf8_decode(ficha_html("usuario", "Submit()", "select2")); ?>
													</div>
													<script>
														document.getElementById("usuario").value = "<?php echo $usuario; ?>";
													</script>
												</div>
											</div>
										</form>
										<br>
										<div class="row">
											<div class="col-md-12 text-center">
												<a type="button" class="btn btn-white" href="FRMusuario_dashboard.php"><i class="fa fa-eraser"></i> Limpiar</a>
												<button type="button" class="btn btn-primary" onclick="Submit();"><i class="fa fa-search"></i> Buscar</button>
											</div>
										</div>
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
									<h4 class="card-title"><i class="nc-icon nc-calendar-60"></i> Detalle de Procesos</h4>
									<h5 class="card-category">Objetivos - Cumplimiento</h5>
								</div>
								<div class="card-body" id="tablaContainer"> </div>
								<!-- <div class="card-footer text-right">
									<hr>
									<div class="stats">
										<i class="fa fa-clock-o"></i> <?php echo $desde . ' - ' . $hasta; ?>
									</div>
								</div> -->
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-6">
							<div class="card">
								<div class="card-header ">
									<h4 class="card-title"><i class="nc-icon nc-chart-pie-36"></i> Estad&iacute;sticas de Cumplimiento de Objetivos</h4>
								</div>
								<div class="card-body ">
									<div class="row">
										<div class="col-md-6 text-center">
											<h5 class="card-category"> Cumplimiento General del Usuario</h5>
											<div id="pieContainer"></div>
										</div>
										<div class="col-md-6 text-center">
											<h5 class="card-category">Cumplimiento por Proceso</h5>
											<div id="stocked0Container"></div>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-md-6 text-center">
											<h5 class="card-category">Cumplimiento por Tipo de Proceso</h5>
											<div id="stocked1Container"></div>
										</div>
										<div class="col-md-6 text-center">
											<h5 class="card-category">Cumplimiento por Sistema</h5>
											<div id="stocked2Container"></div>
										</div>
									</div>
								</div>
								<!-- <div class="card-footer text-right">
									<hr>
									<div class="stats">
										<i class="fa fa-clock-o"></i> <?php echo $desde . ' - ' . $hasta; ?>
									</div>
								</div> -->
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="card">
								<div class="card-header ">
									<h4 class="card-title"><i class="nc-icon nc-chart-pie-36"></i> Estad&iacute;sticas Generales</h4>
								</div>
								<div class="card-body ">
									<div class="row">
										<div class="col-md-6 text-center">
											<h5 class="card-category">Objetivos por Status</h5>
											<div id="generalContainer0"></div>
										</div>
										<div class="col-md-6 text-center">
											<h5 class="card-category">Objetivos por Sistema</h5>
											<div id="generalContainer1"></div>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-md-6 text-center">
											<h5 class="card-category">Acciones por Status</h5>
											<div id="generalContainer2"></div>
										</div>
										<div class="col-md-6 text-center">
											<h5 class="card-category">Acciones por Sistema</h5>
											<div id="generalContainer3"></div>
										</div>
									</div>
								</div>
								<!-- <div class="card-footer text-right">
									<hr>
									<div class="stats">
										<i class="fa fa-clock-o"></i> <?php echo $desde . ' - ' . $hasta; ?>
									</div>
								</div> -->
							</div>
						</div>
					</div>

				</div>
				<?php echo footer() ?>
			</div>
		</div>
		<?php echo modal("../"); ?>
		<?php echo scripts("../"); ?>

		<script type="text/javascript" src="../assets.1.2.8/js/modules/planning/menu_gerencia.js"></script>
		<script>
			$(document).ready(function() {
				tablaObjetivos();
				$('.select2').select2({ width: '100%' });
				$('#range .input-daterange').datepicker({
					keyboardNavigation: false,
					forceParse: false,
					autoclose: true,
					format: "dd/mm/yyyy"
				});
			});
		</script>
	</body>

	</html>
