<?php
include_once('html_fns_planning.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
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
		<?php echo head(); ?>
		<!-- Estilo especifico -->
		<link href="assets.1.2.8/css/propios/dashboard.css" rel="stylesheet">
	</head>

	<body class="sidebar-mini">
		<div class="wrapper ">
			<div class="sidebar" data-color="brown" data-active-color="danger">
				<?php echo logo() ?>
				<div class="sidebar-wrapper">
					<?php echo menu_user(''); ?>
					<ul class="nav">
						<li>
							<a href="menu.php">
								<i class="fa fa-home"></i>
								<p>Inicio</p>
							</a>
						</li>
						<?php echo menu_administracion(''); ?>
						<?php echo menu_gestion_tecnica(''); ?>
						<?php echo menu_mejora('', false); ?>
						<hr>
						<li>
							<a href="logout.php">
								<i class="fa fa-power-off"></i>
								<p>Salir</p>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="main-panel"><?php echo navbar(); ?><div class="content">
					<div class="row">
						<div class="col-lg-3 col-md-3 col-sm-6">
							<div class="card">
								<div class="card-header">
									<h4 class="card-title"><i class="nc-icon nc-zoom-split"></i> Filtros</h4>
								</div>
								<div class="card-body ">
									<div class="table-full-width ">
										<form name="f1" id="f1" method="get">
											<div class="row">
												<div class="col-md-12">
													<label>Proceso:</label> <span class="text-info">*</span>
													<div id="divactivo">
														<?php
														echo utf8_decode(departamento_org_html("departamento", "Submit()", "select2"));
														?>
													</div>
													<script>
														document.getElementById("departamento").value = "<?php echo $departamento; ?>";
													</script>
												</div>
											</div>
											
											<div class="row">
												<div class="col-md-12">
													<label>Fechas:</label> <span class="text-success">*</span>
													<div class="form-group" id="range">
														<div class="input-daterange input-group" id="datepicker">
															<input type="text" class="input-sm form-control" name="desde" id="desde" value="<?php echo $desde; ?>" />
															<span class="input-group-addon"> &nbsp; <i class="fa fa-calendar"></i> &nbsp; </span>
															<input type="text" class="input-sm form-control" name="hasta" id="hasta" value="<?php echo $hasta; ?>" />
														</div>
													</div>
												</div>
											</div>
										</form>
										<br>
										<div class="row">
											<div class="col-md-12 text-center">
												<a type="button" class="btn btn-white" href="menu_ppm.php"><i class="fa fa-eraser"></i> Limpiar</a>
												<button type="button" class="btn btn-primary" onclick="Submit();"><i class="fa fa-search"></i> Buscar</button>
											</div>
										</div>
									</div>
									<br><br><br>
								</div>
								<div class="card-footer text-right">
									<hr>
									<div class="stats">
										<i class="fa fa-filter"></i>Filtro
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-9 col-md-9 col-sm-6">
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
											<div id="stocked0Container"></div>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-md-6 text-center">
											<h5 class="card-category">Cumplimiento por Clasificacion</h5>
											<div id="stocked1Container"></div>
										</div>
										<div class="col-md-6 text-center">
											<h5 class="card-category">Cumplimiento por Sistema</h5>
											<div id="stocked2Container"></div>
										</div>
									</div>
								</div>
								<div class="card-footer text-right">
									<hr>
									<div class="stats">
										<i class="fa fa-clock-o"></i> <?php echo $desde . ' - ' . $hasta ; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--  Grafica de Rangos -->
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-6">
							<div class="card">
								<div class="card-header ">
									<h4 class="card-title"><i class="nc-icon nc-chart-pie-36"></i> Estad&iacute;sticas de Lecturas</h4>
								</div>
								<div class="card-body ">
									<div class="row">
										<div class="col-md-6 text-center">
											<h5 class="card-category"> Lecturas Porcentaje General</h5>
											<div id="pieContainer2"></div>
										</div>
										<div class="col-md-6 text-center">
											<h5 class="card-category">Lecturas por Proceso</h5>
											<div id="stocked3Container"></div>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-md-6 text-center">
											<h5 class="card-category">Lecturas por Clasificacion</h5>
											<div id="stocked4Container"></div>
										</div>
										<div class="col-md-6 text-center">
											<h5 class="card-category">Lecturas por Sistema</h5>
											<div id="stocked5Container"></div>
										</div>
									</div>
								</div>
								<div class="card-footer text-right">
									<hr>
									<div class="stats">
										<i class="fa fa-clock-o"></i> <?php echo $desde . ' - ' . $hasta ; ?>
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
								<div class="card-body" id="tablaContainer">						</div>
								<div class="card-footer text-right">
									<hr>
									<div class="stats">
										<i class="fa fa-clock-o"></i> <?php echo $desde . ' - ' . $hasta ; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php echo footer() ?>
			</div>
		</div>
		<?php echo modal() ?>
		
		<?php echo scripts() ?>
		
		<script type="text/javascript" src="assets.1.2.8/js/modules/menu/menu_indicator.js"></script>
		<script>
			$(document).ready(function() {
				$('.select2').select2({ width: '100%' });		$('#range .input-daterange').datepicker({
					keyboardNavigation: false,
					forceParse: false,
					autoclose: true,
					format: "dd/mm/yyyy"
				});
			});
		</script>
	</body>

	</html>
