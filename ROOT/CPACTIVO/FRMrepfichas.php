<?php
include_once('html_fns_activo.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$sede = $_REQUEST["sede"];
$sector = $_REQUEST["sector"];
$area = $_REQUEST["area"];
$situacion = $_REQUEST["situacion"];
//--?>
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
									<i class="fa fa-print"></i> &Iacute;ndice de Reportes
									<a class="btn btn-white btn-lg sin-margin pull-right" href="../CPPPMEJECUCION/FRMreportes.php"><small><i class="nc-icon nc-minimal-left"></i> Regresar</small></a>
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="nav-tabs-navigation">
									<div class="nav-tabs-wrapper">
										<ul id="tabs" class="nav nav-tabs" role="tablist">
											<li class="nav-item">
												<a class="nav-link" href="../CPPPMEJECUCION/FRMreportes.php">
													<h6><i class="fa fa-list"></i> &Iacute;ndice</h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="../CPACTIVO/FRMrepactivo.php">
													<h6><i class="fa fa-print"></i> Activos</h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link active" href="../CPACTIVO/FRMrepfichas.php">
													<h6><i class="fa fa-print"></i> Fichas</h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="../CPACTIVO/FRMrepinactivo.php">
													<h6><i class="fa fa-print"></i> Activos Fuera de L&iacute;nea</h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="../CPACTIVO/FRMrepfalla.php">
													<h6><i class="fa fa-print"></i> Fallas</h6>
												</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>

						<!-- .card -->
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-copy"></i> Fichas de Activos</h5>
							</div>
							<div class="card-body all-icons">
								<form name="f1" id="f1" method="get">
									<div class="row">
										<div class="col-xs-12 col-md-12 text-right"><label class="text-info">* Filtros de Busqueda</label> </div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Sede:</label> <span class="text-success">*</span>
											<?php echo utf8_decode(sedes_html("sede", "Submit();", "select2")); ?>
											<script>
												document.getElementById("sede").value = '<?php echo $sede; ?>';
											</script>
										</div>
										<div class="col-md-6">
											<label>Sector:</label> <span class="text-success">*</span>
											<?php
											if ($sede != "") {
												echo utf8_decode(sector_html("sector", $sede, "Submit();", "select2"));
											} else {
												echo combos_vacios("sector", 'select2');
											}
											?>
											<script>
												document.getElementById("sector").value = '<?php echo $sector; ?>';
											</script>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>&Aacute;rea:</label> <span class="text-success">*</span>
											<?php
											if ($sector != "") {
												echo utf8_decode(area_html("area", $sector, "Submit();", "select2"));
											} else {
												echo combos_vacios("area", 'select2');
											}
											?>
											<script>
												document.getElementById("area").value = '<?php echo $area; ?>';
											</script>
										</div>
										<div class="col-md-6">
											<label>Situaci&oacute;n:</label> <span class="text-success">*</span>
											<select class="form-control select2" name="situacion" id="situacion">
												<option value="">Todas las situaciones</option>
												<option value="1">Activo</option>
												<option value="2">Inactivo</option>
											</select>
											<script>
												document.getElementById("situacion").value = '<?php echo $situacion; ?>';
											</script>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-md-12 text-center">
											<a type="button" class="btn btn-white" href="FRMrepfichas.php"><i class="fa fa-eraser"></i> Limpiar</a>
											<button type="button" class="btn btn-info" onclick="Submit();"><i class="fa fa-search"></i> Buscar Activos</button>
											<button hidden type="button" class="btn btn-primary" onclick="Fichas();"><i class="fa fa-file-pdf-o"></i> Imprimir Fichas</button>
										</div>
									</div>
									<br>
								</form>
								<hr>
								<div class="row">
									<div class="col-md-12">
										<?php echo utf8_decode(tabla_fichas($sede, $sector, $area, $situacion)); ?>
									</div>
								</div>
							</div>
						</div>
						<!-- /card -->
					</div>
				</div>
			</div>
			<?php echo footer() ?>
		</div>
	</div>
	<?php echo modal("../"); ?>
	<?php echo scripts("../"); ?>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/ppm/activo.js"></script>
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
						extend: 'excel',
						title: 'Listado de Activos'
					},
					{
						extend: 'pdf',
						title: 'Listado de Activos'
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
						title: 'Listado de Activos'
					}
				]
			});

			$('.select2').select2({ width: '100%' });
		});

		function Fichas() {
			myform = document.forms.f1;
			myform.method = "get";
			myform.target = "_blank";
			myform.action = "CPREPORTES/REPfichas.php";
			myform.submit();
			myform.action = "";
			myform.target = "";
			myform.method = "get";
		}
	</script>

</body>
</html>