<?php
include_once('html_fns_dashboard.php');
validate_login("../");
$id = $_SESSION["codigo"];
//$_POST
$usuario = $_REQUEST["usuario"];
$proceso = $_REQUEST["proceso"];
$sistema = $_REQUEST["sistema"];
$situacion = $_REQUEST["situacion"];
$desde = $_REQUEST["desde"];
$desde = ($desde == "") ? date("01/01/Y") : $desde; //valida que si no se selecciona fecha, coloque la del dia
$hasta = $_REQUEST["hasta"];
$hasta = ($hasta == "") ? date("d/m/Y") : $hasta; //valida que si no se selecciona fecha, coloque la del dia
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("../", "mejora"); ?>
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
												<a class="nav-link" href="../CPMEJORADASHBOARD/FRMreportes.php">
													<h6><i class="fa fa-print"></i> Hallazgos</h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link active" href="../CPMEJORADASHBOARD/FRMactividades.php">
													<h6><i class="fa fa-print"></i> Actividades </h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="../CPMEJORADASHBOARD/FRMevaluaciones.php">
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
								<h5 class="card-title"><i class="fa fa-print"></i> Reporte de Actividades</h5>
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
											<label>Responsable:</label> <span class="text-success">*</span>
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
									
									<hr>
									<div class="row">
										<div class="col-md-12">
											<label>Campos o Columnas del Reporte:</label> <span class="text-success">*</span>
											<select class="dual_select" id="columnas" name="columnas[]" multiple style="min-height: 250px;">
												<option value="act_codigo" selected>C&oacute;digo</option>
												<option value="hal_descripcion" selected>Hallazgo</option>
												<option value="fic_nombre" selected>Proceso</option>
												<option value="sis_nombre" selected>Sistema</option>
												<option value="pla_responsable" selected>Responsable</option>
												<option value="act_periodicidad" selected>Periodicidad</option>
												<option value="act_fecha_inicio" selected>Fecha de Inicio</option>
												<option value="act_fecha_fin" selected>Fecha Fin</option>
												<option value="act_descripcion" selected>Actividad</option>
												<option value="act_comentario" selected>Comentario</option>
												<option value="pla_fecha_creacion" selected>Fecha de Creaci&oacute;n</option>
												<option value="pla_justificacion">Justificaci&oacute;n de la Revisi&oacute;n</option>
												<option value="pla_fecha_revision">Fecha de la Revisi&oacute;n</option>
												<option value="pla_situacion">Situaci&oacute;n</option>
											</select>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-md-12 text-center">
											<a type="button" class="btn btn-white" href="FRMplanes.php"><i class="fa fa-eraser"></i> Limpiar</a>
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
			$('.select2').select2({
				width: '100%'
			});
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
				myform.action = "FRMreporte_actividad.php";
				myform.submit();
				myform.action = "";
				myform.target = "";
				myform.method = "get";
			} else {
				swal("Alto", "Para generar este reporte en PDF debe seleccionar al menos 1 columna...", "info");
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
					swal("Alto", "Para generar este reporte en PDF no debe de exceder mas de 11 columnas, podr\u00EDan desplegarse fuera de la p\u00E1gina...", "warning");
				}
			} else {
				swal("Alto", "Para generar este reporte en PDF debe seleccionar al menos 1 columna...", "info");
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
				myform.action = "EXCELreporte_actividad.php";
				myform.submit();
				myform.action = "";
				myform.target = "";
				myform.method = "get";
			} else {
				swal("Alto", "Para generar este reporte en PDF debe seleccionar al menos 1 columna...", "info");
			}
		}
	</script>

</body>

</html>