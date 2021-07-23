<?php
include_once('html_fns.php');
validate_login();
$id = $_SESSION["codigo"];

//--
$sedes_IN = $_SESSION["sedes_in"];

//$_POST
$sede = $_REQUEST["sede"];
//$sede = ($sede == "")?$_SESSION["sede_codigo_1"]:$sede;
$sedeJs = $sede; //llena variable con resultado para setear en los filtros
$sede = ($sede == "Todas") ? "" : $sede;
//--
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
	<style>
		.card-stats .card-footer .stats {
			font-size: 12px;
		}

		.sqr-abierto {
			color: rgb(144, 201, 143);
		}

		.sqr-cerrado {
			color: #e0e0e0;
			;
		}
	</style>
</head>

<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("", "helpdesk"); ?>
		<div class="main-panel">
			<?php echo navbar(); ?>
			<div class="content">
				<!-- Resumen por status -->
				<div class="row">
					<!-- Columna principal -->
					<div class="col-lg-9 col-md-9 col-sm-12">
						<!-- centro -->
						<div class="row" id="conteoContainer"> </div>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="card">
									<div class="card-header ">
										<h4 class="card-title">
											<i class="nc-icon nc-alert-circle-i"></i> Listado de Tickets en Tr&aacute;mite
											<a class="btn btn-white btn-lg sin-margin pull-right" href="CPTICKET/FRMnewticket.php"><small><i class="nc-icon nc-simple-add"></i> Nuevo Ticket</small></a>
										</h4>
									</div>
									<div class="card-body ">
										<div class="row">
											<div class="col-md-4 text-center">
												<h5 class="card-category">Incidentes por Criticidad</h5>
												<div id="stockedContainer"></div>
											</div>
											<div class="col-md-4 text-center">
												<h5 class="card-category">Incidentes por Status</h5>
												<div id="pieContainer"></div>
											</div>
											<div class="col-md-4 text-center">
												<h5 class="card-category">Total Semanal</h5>
												<div id="gaugeContainer"></div>
												<hr>
												<table>
													<tr>
														<td><strong>Incidentes Abiertos:</strong> </td>
														<td>&nbsp; <i class="fa fa-square sqr-abierto"></i></td>
													</tr>
													<tr>
														<td><strong>Incidentes Cerrados:</strong> </td>
														<td>&nbsp; <i class="fa fa-square sqr-cerrado"></i></td>
													</tr>
												</table>
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
						<!-- listado -->
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="card">
									<div class="card-body" id="tablaContainer"> </div>
								</div>
							</div>
						</div>
					</div>
					<!-- ./Columna principal -->
					<!-- Columna lateral izquierda -->
					<div class="col-lg-3 col-md-3 col-sm-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title"><i class="nc-icon nc-zoom-split"></i> Filtros</h4>
							</div>
							<div class="card-body ">
								<div class="table-full-width ">
									<form name="f1" id="f1" method="get">
										<div class="row">
											<div class="col-md-12">
												<label>Sede:</label> <span class="text-success">*</span>
												<?php echo utf8_decode(sedes_html("sede", "Submit();", "select2", "Todas")); ?>
												<script>
													document.getElementById("sede").value = '<?php echo $sedeJs; ?>';
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
											<a type="button" class="btn btn-white" href="menu_helpdesk.php"><i class="fa fa-eraser"></i> Limpiar</a>
											<button type="button" class="btn btn-primary" onclick="Submit();"><i class="fa fa-search"></i> Buscar</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-body" id="categoriasContainer"> </div>
						</div>
					</div>
					<!-- ./Columna lateral izquierda -->
				</div>
			</div>
			<?php echo footer() ?>
		</div>
	</div>
	<?php echo modal() ?>
	<?php echo scripts() ?>
	<script type="text/javascript" src="assets.1.2.8/js/modules/menu/menu_hd.js"></script>
	<script>
		$(document).ready(function() {
			c3.generate({
				bindto: '#stocked',
				data: {
					columns: [<?php echo $result2["data"]; ?>],
					colors: {
						<?php echo $result2["colores"]; ?>
					},
					type: 'bar'
				},
				axis: {
					x: {
						type: 'category',
						categories: ['Criticidad']
					}
				}
			});
			c3.generate({
				bindto: '#pie',
				data: {
					columns: [<?php echo $result1["data"]; ?>],
					colors: {
						<?php echo $result1["colores"]; ?>
					},
					type: 'pie'
				},
				axis: {
					x: {
						type: 'category',
						categories: ['Status']
					}
				}
			});
			c3.generate({
				bindto: '#gauge',
				data: {
					columns: [
						['abiertos', <?php echo $abiertos; ?>]
					],
					type: 'gauge'
				},
				color: {
					pattern: ['#90C98F', '#BABABA']
				}
			});
			$("span.pie").peity("pie", {
				fill: ["#f8ac59", "#363F51", "#d7d7d7"]
			});
		});
	</script>
</body>
</html>