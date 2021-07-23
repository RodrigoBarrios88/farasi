<?php
include_once('html_fns.php');
validate_login();
$id = $_SESSION["codigo"];

//--
$sedes_IN = $_SESSION["sedes_in"];

//$_POST
$sede = $_REQUEST["sede"];
//$sede = ($sede == "")?$_SESSION["sede_codigo_1"]:$sede;
$sector = $_REQUEST["sector"];
$area = $_REQUEST["area"];
$categoria = $_REQUEST["categoria"];
$hora = $_REQUEST["hora"];
$hora = ($hora == "") ? "23:59" : $hora; //valida que si no se selecciona fecha, coloque la del dia
//--
$fecha = $_REQUEST["fecha"];
$fecha = ($fecha == "") ? date("d/m/Y") : $fecha; //valida que si no se selecciona fecha, coloque la del dia?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head(); ?>
	<!-- Estilo especifico -->
	<link href="assets.1.2.8/css/propios/dashboard.css" rel="stylesheet">
</head>

<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("", "checklist"); ?>
		<div class="main-panel">
			<?php echo navbar(); ?>
			<div class="content">
				<div class="row">
					<div class="col-lg-3 col-md-3 col-sm-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title"><i class="nc-icon nc-zoom-split"></i> Filtros</h4>
								<h5 class="card-category">Resumen de Ejecuci&oacute;n</h5>
							</div>
							<div class="card-body ">
								<div class="table-full-width ">
									<form name="f1" id="f1" method="get">
										<div class="row">
											<div class="col-md-12">
												<label>Sede:</label> <span class="text-success">*</span>
												<?php echo utf8_decode(sedes_html("sede", "Submit();comboSector();", "select2", "Todas")); ?>
												<script>
													document.getElementById("sede").value = '<?php echo $sede; ?>';
												</script>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Sector:</label> <span class="text-success">*</span>
												<div id="divsector">
													<?php
													if ($sede != "") {
														echo utf8_decode(sector_html("sector", $sede, "Submit();comboArea();", "select2"));
													} else {
														echo combos_vacios("sector", 'select2');
													}
													?>
												</div>
												<script>
													document.getElementById("sector").value = '<?php echo $sector; ?>';
												</script>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>&Aacute;rea:</label> <span class="text-success">*</span>
												<div id="divarea">
													<?php
													if ($sector != "") {
														echo utf8_decode(area_html("area", $sector, "Submit();", "select2"));
													} else {
														echo combos_vacios("area", 'select2');
													}
													?>
												</div>
												<script>
													document.getElementById("area").value = '<?php echo $area; ?>';
												</script>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Categor&iacute;a:</label> <span class="text-success">*</span>
												<?php echo utf8_decode(categorias_chk_html("categoria", "Submit();", "select2")); ?>
												<script>
													document.getElementById("categoria").value = '<?php echo $categoria; ?>';
												</script>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Hora L&iacute;mite:</label> <span class="text-success">*</span>
												<div class="form-group">
													<input type="text" class="form-control timepicker" name="hora" id="hora" value="<?php echo $hora; ?>">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Fecha:</label> <span class="text-success">*</span>
												<div class="form-group">
													<div class="input-group date">
														<input type="text" class="form-control" name="fecha" id="fecha" value="<?php echo $fecha; ?>">
														<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													</div>
												</div>
											</div>
										</div>
									</form>
									<br>
									<div class="row">
										<div class="col-md-12 text-center">
											<a type="button" class="btn btn-white" href="menu_checklist.php"><i class="fa fa-eraser"></i> Limpiar</a>
											<button type="button" class="btn btn-primary" onclick="Submit();"><i class="fa fa-search"></i> Buscar</button>
										</div>
									</div>
								</div>
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
								<h5 class="card-category">Reporte de Revisiones</h5>
							</div>
							<div class="card-body ">
								<div class="row">
									<div class="col-md-6 text-center">
										<h5 class="card-category">Cumplimiento de Hoy (Escaneos hoy)</h5>
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
										<h5 class="card-category">Cumplimiento por Categor&iacute;a</h5>
										<div id="stocked2Container"></div>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-6 text-center">
										<h5 class="card-category">Resultados por Categor&iacute;a</h5>
										<div id="stocked1Container"></div>
									</div>
									<div class="col-md-6 text-center">
										<h5 class="card-category">Resultados en General</h5>
										<div id="gaugeContainer"></div>
									</div>
								</div>
							</div>
							<div class="card-footer text-right">
								<hr>
								<div class="stats">
									<i class="fa fa-clock-o"></i> <?php echo date("d/m/Y H:i"); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<div class="card">
							<div class="card-header ">
								<h4 class="card-title"><i class="nc-icon nc-calendar-60"></i> Programaci&oacute;n para Hoy</h4>
								<h5 class="card-category">Areas - Horarios</h5>
							</div>
							<div class="card-body" id="tablaContainer"> </div>
							<div class="card-footer text-right">
								<hr>
								<div class="stats">
									<i class="fa fa-clock-o"></i> <?php echo date("d/m/Y H:i"); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row"> </div>
				<div class="row"> </div>
			</div>
			<?php echo footer() ?>
		</div>
	</div>
	<?php echo modal() ?>
	<?php echo scripts() ?>
	<script type="text/javascript" src="assets.1.2.8/js/modules/menu/menu_checklist.js"></script>

</body>
</html>