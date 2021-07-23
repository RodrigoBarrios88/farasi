<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$sede = $_REQUEST["sede"];
$area = $_REQUEST["area"];
$activo = $_REQUEST["activo"];
$usuario = $_REQUEST["usuario"];
$periodo = $_REQUEST["periodo"];
$periodo = ($periodo == "") ? "A" : $periodo; //valida que si no se selecciona fecha, coloque la del dia
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
												<a class="nav-link" href="../CPPPMPROGRA/FRMreportes.php">
													<h6><i class="fa fa-print"></i> Programaci&oacute;n</h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="../CPPPMEJECUCION/FRMreptareas.php">
													<h6><i class="fa fa-print"></i> Ordenes de Trabajo</h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link active" href="../CPPPMEJECUCION/FRMpresupuesto.php">
													<h6><i class="fa fa-print"></i> Ejecuci&oacute;n Presupuestaria</h6>
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
								<h5 class="card-title"><i class="fa fa-search"></i> Reporte de Ejecuci&oacute;n Presupuestaria</h5>
							</div>
							<div class="card-body all-icons">
								<form name="f1" id="f1" action="" method="get">
									<div class="row">
										<div class="col-xs-6 col-md-6 text-left">
											<a type="button" class="btn btn-white" href="../menu_ppm.php"><i class="fa fa-chevron-left"></i> Atr&aacute;s</a>
										</div>
										<div class="col-xs-6 col-md-6 text-right"><label class="text-info">* Filtros de Busqueda</label> </div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<label>Area:</label> <span class="text-info">*</span>
											<?php echo utf8_decode(areas_sede_html("area", "Submit()", "select2")); ?>
											<script>
												document.getElementById("area").value = "<?php echo $area; ?>"
											</script>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Activo:</label> <span class="text-info">*</span>
											<?php echo utf8_decode(activos_html("activo", "", $area, "Submit()", "select2")); ?>
											<script>
												document.getElementById("activo").value = "<?php echo $activo; ?>";
											</script>
										</div>
										<div class="col-md-6">
											<label>Usuario a Asignar:</label> <span class="text-info">*</span>
											<?php echo utf8_decode(usuarios_html("usuario", "Submit()", "select2")); ?>
											<script>
												document.getElementById("usuario").value = "<?php echo $usuario; ?>";
											</script>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Fechas:</label> <span class="text-info">*</span>
											<div class="form-group" id="range">
												<div class="input-daterange input-group" id="datepicker">
													<input type="text" class="input-sm form-control" name="desde" id="desde" value="<?php echo $desde; ?>" />
													<span class="input-group-addon"> &nbsp; <i class="fa fa-calendar"></i> &nbsp; </span>
													<input type="text" class="input-sm form-control" name="hasta" id="hasta" value="<?php echo $hasta; ?>" />
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<label>Periodo:</label> <span class="text-success">*</span>
											<select class="form-control select2" id="periodo" name="periodo">
												<option value="A" selected>Actividad por Actividad</option>
												<option value="D">D&iacute;a a d&iacute;a</option>
												<option value="S">Semana a Semana</option>
												<option value="M">Mes a mes</option>
											</select>
											<script>
												document.getElementById("periodo").value = "<?php echo $periodo; ?>";
											</script>
										</div>
									</div>
									<hr>
									<br>
									<div class="row">
										<div class="col-md-12 text-center">
											<a type="button" class="btn btn-white" href="FRMreptareas.php"><i class="fa fa-eraser"></i> Limpiar</a>
											<button type="button" class="btn btn-primary" onclick="HTML();"><i class="fa fa-search"></i> Buscar</button>

											<button type="button" class="btn btn-success" onclick="Excel();"><i class="fa fa-file-excel-o"></i> Excel</button>
										</div>
									</div>
									<br>
								</form>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/ppm/ejecucion.js"></script>
	<script>
		var dual = $('select[name="columnas[]"]').bootstrapDualListbox();

		$(document).ready(function() {
			$('.select2').select2({ width: '100%' });
			$('#range .input-daterange').datepicker({
				keyboardNavigation: false,
				forceParse: false,
				autoclose: true,
				format: "dd/mm/yyyy"
			});
		});

		function HTML() {
			myform = document.forms.f1;
			myform.method = "get";
			myform.target = "_blank";
			myform.action = "FRMreppresupuesto.php";
			myform.submit();
			myform.action = "";
			myform.target = "";
			myform.method = "get";
		}

		function PDF() {
			myform = document.forms.f1;
			myform.method = "get";
			myform.target = "_blank";
			myform.action = "REPpresupuesto.php";
			myform.submit();
			myform.action = "";
			myform.target = "";
			myform.method = "get";
		}

		function Excel() {
			myform = document.forms.f1;
			myform.method = "get";
			myform.target = "_blank";
			myform.action = "EXCELpresupuesto.php";
			myform.submit();
			myform.action = "";
			myform.target = "";
			myform.method = "get";
		}
	</script>

</body>

</html>