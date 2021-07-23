<?php
include_once('html_fns.php');
validate_login();
$id = $_SESSION["codigo"];

$sedes_IN = $_SESSION["sedes_in"];
//$_POST
$sede = $_REQUEST["sede"];
//$sede = ($sede == "")?$_SESSION["sede_codigo_1"]:$sede;
$sector = $_REQUEST["sector"];
$area = $_REQUEST["area"];
$activo = $_REQUEST["activo"];
$usuario = $_REQUEST["usuario"];
$categoria = $_REQUEST["categoria"];
$situacion = $_REQUEST["situacion"];
//--
$mes = date("m");
$anio = date("Y");
$desde = $_REQUEST["desde"];
$desde = ($desde == "") ? date("01/01/Y") : $desde; //valida que si no se selecciona fecha, coloque la del dia
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
			<?php echo sidebar("","ppm"); ?>
			<div class="main-panel">
				<?php echo navbar(); ?>
				<div class="content">
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-12">
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
													<label>Activo:</label> <span class="text-info">*</span>
													<div id="divactivo">
														<?php
														if ($sede != "" && $area != "") {
															echo utf8_decode(activos_html("activo", $sede, $area, "Submit()", "select2"));
														} else {
															echo combos_vacios("activo", "select2");
														}
														?>
													</div>
													<script>
														document.getElementById("activo").value = "<?php echo $activo; ?>";
													</script>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Usuario a Asignar:</label> <span class="text-info">*</span>
													<?php echo utf8_decode(usuarios_sedes_html("usuario", $sedes_IN, "Submit()", "select2")); ?>
													<script>
														document.getElementById("usuario").value = "<?php echo $usuario; ?>";
													</script>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Categor&iacute;a:</label> <span class="text-success">*</span>
													<?php echo utf8_decode(categorias_ppm_html("categoria", "Submit();", "select2")); ?>
													<script>
														document.getElementById("categoria").value = '<?php echo $categoria; ?>';
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
						<div class="col-lg-8 col-md-8 col-sm-6">
							<div class="card">
								<div class="card-header ">
									<h4 class="card-title"><i class="nc-icon nc-chart-pie-36"></i> Estad&iacute;sticas de Mantenimiento</h4>
									<h5 class="card-category">Reporte de Actividades</h5>
								</div>
								<div class="card-body ">
									<!-- Cuadros de conteo -->
									<div class="row">
										<div class="col-lg-3 col-md-6 col-sm-6">
											<div class="card card-stats">
												<div class="card-body">
													<div class="row">
														<div class="col-5 col-md-4">
															<div class="icon-big text-center text-success">
																<i class="fa fa-desktop text-success"></i>
															</div>
														</div>
														<div class="col-7 col-md-8">
															<div class="numbers">
																<small id="contadorFinal"></small>
															</div>
														</div>
													</div>
												</div>
												<div class="card-footer text-right">
													<div class="stats">Finalizado</div>
												</div>
											</div>
										</div>								<div class="col-lg-3 col-md-6 col-sm-6">
											<div class="card card-stats">
												<div class="card-body">
													<div class="row">
														<div class="col-5 col-md-4">
															<div class="icon-big text-center text-warning">
																<i class="fa fa-desktop text-warning"></i>
															</div>
														</div>
														<div class="col-7 col-md-8">
															<div class="numbers">
																<small id="contadorEspera"></small>
															</div>
														</div>
													</div>
												</div>
												<div class="card-footer text-right">
													<div class="stats">En Espera</div>
												</div>
											</div>
										</div>								<div class="col-lg-3 col-md-6 col-sm-6">
											<div class="card card-stats">
												<div class="card-body">
													<div class="row">
														<div class="col-5 col-md-4">
															<div class="icon-big text-center text-info">
																<i class="fa fa-desktop text-info"></i>
															</div>
														</div>
														<div class="col-7 col-md-8">
															<div class="numbers">
																<small id="contadorProceso"></small>
															</div>
														</div>
													</div>
												</div>
												<div class="card-footer text-right">
													<div class="stats">En Proceso</div>
												</div>
											</div>
										</div>								<div class="col-lg-3 col-md-6 col-sm-6">
											<div class="card card-stats">
												<div class="card-body">
													<div class="row">
														<div class="col-5 col-md-4">
															<div class="icon-big text-center text-muted">
																<i class="fa fa-desktop text-muted"></i>
															</div>
														</div>
														<div class="col-7 col-md-8">
															<div class="numbers">
																<small id="contadorPendiente"></small>
															</div>
														</div>
													</div>
												</div>
												<div class="card-footer text-right">
													<div class="stats">Pendiente</div>
												</div>
											</div>
										</div>
									</div>
									<!-- /. Cuadros de conteo -->
									<!-- Barra de Progreso % -->
									<div class="row">
										<div class="col-md-12 text-center">
											<h5 class="card-category">Cumplimiento de Trabajo</h5>
											<div class="row">
												<div class="col-md-12 text-center">
													<table>
														<tr>
															<td class="text-left"><strong>Finalizado:</strong> </td>
															<td class="text-left">&nbsp; <i class="fa fa-square sqr-success"></i></td>
														</tr>
														<tr>
															<td class="text-left"><strong>En Espera:</strong> </td>
															<td class="text-left">&nbsp; <i class="fa fa-square sqr-warning"></i></td>
														</tr>
														<tr>
															<td class="text-left"><strong>En Proceso:</strong> </td>
															<td class="text-left">&nbsp; <i class="fa fa-square sqr-info"></i></td>
														</tr>
														<tr>
															<td class="text-left"><strong>Pendiente:</strong> </td>
															<td class="text-left">&nbsp; <i class="fa fa-square sqr-default"></i></td>
														</tr>
													</table>
												</div>
											</div>
											<br>
											<div class="row">
												<div class="col-md-12">
													<div class="progress progress-striped active">
														<div id="progressFinal" class="progress-bar progress-bar-success">
															<span id="spanFinal"></span>
														</div>
														<div id="progressEspera" class="progress-bar progress-bar-warning">
															<span id="spanEspera"></span>
														</div>
														<div id="progressProceso" class="progress-bar progress-bar-info">
															<span id="spanProceso"></span>
														</div>
														<div id="progressPendiente" class="progress-bar">
															<span id="spanPendiente"></span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- /. Barra de Progreso % -->
									<br>
									<div class="row">
										<div class="col-md-12 text-center">
											<h5 class="card-category">Resultados por Categor&iacute;as</h5>
											<div id="resultCategorias">									</div>
										</div>
									</div>
								</div>
								<div class="card-footer text-right">
									<div class="stats">
										<i class="fa fa-clock-o"></i> <?php echo date("d/m/Y H:i"); ?>
									</div>
								</div>
							</div>
						</div>
					</div>			<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="card">
								<div class="card-body ">
									<div class="row">
										<div class="col-md-6 text-center">
											<h5 class="card-category">Distribuci&oacute;n de Trabajo</h5>
											<div class="row">
												<div class="col-md-12" id="resultTrabajo">										</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-12 text-center">
													<h5 class="card-category">Fallas Reportadas</h5>
													<div id="resultActivosOff">...</div>
												</div>
											</div>
											<hr>
											<div class="row">
												<div class="col-md-12 text-center">
													<h5 class="card-category">Activos Fuera de Servicio</h5>
													<div id="resultFallas">...</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>			<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="card">
								<div class="card-header ">
									<h4 class="card-title"><i class="nc-icon nc-calendar-60"></i> Programaci&oacute;n de Actividades</h4>
									<h5 class="card-category">Mantenimiento Preventivo</h5>
								</div>
								<div class="card-body" id="resultProgramacion">						</div>
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
									<h4 class="card-title"><i class="fa fa-money"></i> Ejecuci&oacute;n Presupuestaria</h4>
									<h5 class="card-category">Programado vs Ejecutado</h5>
								</div>
								<div class="card-body" id="resultPresupuesto">						</div>
								<div class="card-footer text-right">
									<hr>
									<div class="stats">
										<i class="fa fa-clock-o"></i> <?php echo date("d/m/Y H:i"); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">			</div>
				</div>
				<?php echo footer() ?>
			</div>
		</div>
		<?php echo modal() ?>
		<?php echo scripts() ?>
		<!-- Morris -->
		<script src="assets.1.2.8/js/plugins/morris/raphael-2.1.0.min.js"></script>
		<script src="assets.1.2.8/js/plugins/morris/morris.js"></script>
		<script type="text/javascript" src="assets.1.2.8/js/modules/menu/menu_ppm.js"></script>

	</body>

	</html>
