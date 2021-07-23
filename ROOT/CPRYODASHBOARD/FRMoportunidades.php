<?php
include_once('html_fns_dashboard.php');
validate_login("../");
$id = $_SESSION["codigo"];
//$_POST
$usuario = $_REQUEST["usuario"];
$proceso = $_REQUEST["proceso"];
$sistema = $_REQUEST["sistema"];
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("../", "risk"); ?>
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
												<a class="nav-link" href="../CPRYODASHBOARD/FRMreportes.php">
													<h6><i class="fa fa-print"></i> Riesgos</h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link active" href="../CPRYODASHBOARD/FRMoportunidades.php">
													<h6><i class="fa fa-print"></i> Oportunidades</h6>
												</a>
											</li>
											
											<li class="nav-item">
												<a class="nav-link" href="../CPRYODASHBOARD/FRMactividades.php">
													<h6><i class="fa fa-print"></i> Actividades </h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="../CPRYODASHBOARD/FRMevaluaciones.php">
													<h6><i class="fa fa-print"></i> Evaluaciones</h6>
												</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-print"></i> Reportes de Oportunidades</h5>
							</div>
							<div class="card-body all-icons">
								<form name="f1" id="f1" method="get">
									<div class="row">
										<div class="col-xs-12 col-md-12 text-right"><label class="text-info">* Filtros de Busqueda</label> </div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Proceso:</label> <span class="text-success">*</span>
											<?php echo utf8_decode(ficha_html("proceso", "Submit();", "select2")); ?>
											<input type="hidden" name="codigo" id="codigo" />
											<script>
												document.getElementById("proceso").value = '<?php echo $proceso; ?>';
											</script>
										</div>
										<div class="col-md-6">
											<label>Usuario que Identifica:</label> <span class="text-success">*</span>
											<?php echo utf8_decode(usuarios_html("usuario", "Submit();", "select2")); ?>
											<script>
												document.getElementById("usuario").value = '<?php echo $usuario; ?>';
											</script>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Sistema:</label> <span class="text-success">*</span>
											<?php echo utf8_decode(sistema_html("sistema", "Submit();", "select2")); ?>
											<script>
												document.getElementById("sistema").value = '<?php echo $sistema; ?>';
											</script>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-md-12">
											<label>Campos o Columnas del Reporte:</label> <span class="text-success">*</span>
											<select class="dual_select" id="columnas" name="columnas[]" multiple style="min-height: 250px;">
												<option value="opo_codigo" selected>C&oacute;digo de la Oportunidad</option>
												<option value="fod_descripcion" selected>Oportunidad</option>
												<option value="fic_nombre" selected>Proceso</option>
												<option value="fod_sistema" selected>Sistema</option>
												<option value="opo_viabilidad" selected>Viabilidad</option>
												<option value="opo_rentabilidad" selected>Rentabilidad</option>
												<option value="opo_prioridad" selected>Prioridad</option>
												<option value="opo_condicion" selected>Condici&oacute;n</option>
												<option value="opo_usuario" selected>Usuario que Identifica</option>
												<option value="opo_fecha_registro" selected>Fecha de Identificaci&oacute;n</option>
												<option value="opo_accion" selected>Acci&oacute;n</option>
											</select>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-md-12 text-center">
											<a type="button" class="btn btn-white" href="FRMreportes.php"><i class="fa fa-eraser"></i> Limpiar</a>
											<button type="button" class="btn btn-primary" onclick="HTML();"><i class="fa fa-search"></i> Buscar</button>
											<button type="button" class="btn btn-success" onclick="Excel();"><i class="fa fa-file-excel-o"></i> Excel</button>
										</div>
									</div>
									<br>
								</form>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/ryo/reportes.js"></script>
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
			var columnas = 0;
			$('#columnas option:selected').each(function() {
				columnas++;
			});
			if (columnas >= 1) {
				myform = document.forms.f1;
				myform.method = "get";
				myform.target = "_blank";
				myform.action = "FRMreporte_oportunidad.php";
				myform.submit();
				myform.action = "";
				myform.target = "";
				myform.method = "get";
			} else {
				swal("Alto", "Para generar este opoluaci&oacute;ndo en PDF debe seleccionar al menos 1 columna...", "info");
			}
		}

		function PDF() {
			var columnas = 0;
			$('#columnas option:selected').each(function() {
				columnas++;
			});
			if (columnas >= 1) {
				if (columnas <= 10) {
					myform = document.forms.f1;
					myform.method = "get";
					myform.target = "_blank";
					myform.action = "REPreporte.php";
					myform.submit();
					myform.action = "";
					myform.target = "";
					myform.method = "get";
				} else {
					swal("Alto", "Para generar este opoluaci&oacute;ndo en PDF no debe de exceder mas de 11 columnas, podr\u00EDan desplegarse fuera de la p\u00E1gina...", "warning");
				}
			} else {
				swal("Alto", "Para generar este opoluaci&oacute;ndo en PDF debe seleccionar al menos 1 columna...", "info");
			}
		}

		function Excel() {
			var columnas = 0;
			$('#columnas option:selected').each(function() {
				columnas++;
			});
			if (columnas >= 1) {
				myform = document.forms.f1;
				myform.method = "get";
				myform.target = "_blank";
				myform.action = "EXCELreporte_oportunidad.php";
				myform.submit();
				myform.action = "";
				myform.target = "";
				myform.method = "get";
			} else {
				swal("Alto", "Para generar este opoluaci&oacute;ndo en PDF debe seleccionar al menos 1 columna...", "info");
			}
		}
	</script>

</body>

</html>