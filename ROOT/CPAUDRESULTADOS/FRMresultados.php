<?php
include_once('html_fns_resultados.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$sede = $_REQUEST["sede"];
$sector = $_REQUEST["sector"];
$area = $_REQUEST["area"];
$categoria = $_REQUEST["categoria"];
$usuario = $_REQUEST["usuario"];
//--
$mes = date("m");
$anio = date("Y");
$desde = $_REQUEST["desde"];
$desde = ($desde == "") ? date("d/m/Y") : $desde; //valida que si no se selecciona fecha, coloque la del dia
$hasta = $_REQUEST["hasta"];
$hasta = ($hasta == "") ? date("d/m/Y") : $hasta; //valida que si no se selecciona fecha, coloque la del dia?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("../", "auditoria"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-body all-icons">
								<div class="nav-tabs-navigation">
									<div class="nav-tabs-wrapper">
										<ul id="tabs" class="nav nav-tabs" role="tablist">
											<li class="nav-item">
												<a class="nav-link" href="../CPAUDCUESTIONARIO/FRMreportes.php">
													<h6><i class="fa fa-print"></i> Rep. Programaci&oacute;n</h6>
												</a>
											</li>
											<li class="nav-item active">
												<a class="nav-link" href="../CPAUDEJECUCION/FRMreportes.php">
													<h6><i class="fa fa-print"></i> Rep. Auditorias</h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link active" href="../CPAUDRESULTADOS/FRMresultados.php">
													<h6><i class="fa fa-calendar-o"></i> Periodico Resultados</h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="../CPAUDINFORME/FRMreportesPlan.php">
													<h6><i class="fas fa-clipboard-list"></i> Informe Final Aud.</h6>
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
								<h5 class="card-title"><i class="fa fa-calendar"></i> Reporte Peri&oacute;dico de Resultados</h5>
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
											<label>Departamento:</label> <span class="text-success">*</span>
											<?php echo utf8_decode(departamento_org_html("departamento", "", "select2")); ?>
											<script>
												document.getElementById("departamento").value = '<?php echo $departamento; ?>';
											</script>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Categor&iacute;a:</label> <span class="text-success">*</span>
											<?php echo utf8_decode(categorias_auditoria_html("categoria", "", "select2")); ?>
											<script>
												document.getElementById("categoria").value = '<?php echo $categoria; ?>';
											</script>
										</div>
										<div class="col-md-6">
											<label>Situaci&oacute;n:</label> <span class="text-success">*</span>
											<select id="sit" name="sit" class="form-control select2">
												<option value="" selected>Todas</option>
												<option value="1">En Proceso</option>
												<option value="2">Finalizadas</option>
											</select>
											<script>
												document.getElementById("sit").value = '<?php echo $sit; ?>';
											</script>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Periodo:</label> <span class="text-success">*</span>
											<select class="form-control select2" id="periodo" name="periodo">
												<option value="D" selected>D&iacute;a a d&iacute;a</option>
												<option value="S">Semana a Semana</option>
												<option value="M">Mes a mes</option>
											</select>
										</div>
										<div class="col-md-6">
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
									<br>
									<div class="row">
										<div class="col-md-12 text-center">
											<a type="button" class="btn btn-white" href="FRMresultados.php"><i class="fa fa-eraser"></i> Limpiar</a>
											<button type="button" class="btn btn-primary" onclick="HTML();"><i class="fa fa-search"></i> Buscar</button>

											<button type="button" class="btn btn-success" onclick="Excel();"><i class="fa fa-file-excel-o"></i> Excel</button>
										</div>
									</div>
									<br>
								</form>
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


	<script type="text/javascript" src="../assets.1.2.8/js/modules/auditoria/ejecucion.js"></script>


	<script>
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
			myform.action = "FRMresultado.php";
			myform.submit();
			myform.action = "";
			myform.target = "";
			myform.method = "get";
		}

		function PDF() {
			myform = document.forms.f1;
			myform.method = "get";
			myform.target = "_blank";
			myform.action = "REPresultado.php";
			myform.submit();
			myform.action = "";
			myform.target = "";
			myform.method = "get";
		}

		function Excel() {
			myform = document.forms.f1;
			myform.method = "get";
			myform.target = "_blank";
			myform.action = "EXCELresultado.php";
			myform.submit();
			myform.action = "";
			myform.target = "";
			myform.method = "get";
		}
	</script>

</body>
</html>