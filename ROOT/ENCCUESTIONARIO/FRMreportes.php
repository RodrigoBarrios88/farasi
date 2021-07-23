<?php
include_once('html_fns_cuestionario.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$encuesta = $_REQUEST["encuesta"];
$categoria = $_REQUEST["categoria"];
$situacion = $_REQUEST["situacion"];
//--
$anio = date("Y");
$desde = $_REQUEST["desde"];
$desde = ($desde == "") ? "01/01/" . date("Y") : $desde; //valida que si no se selecciona fecha, coloque la del dia
$hasta = $_REQUEST["hasta"];
$hasta = ($hasta == "") ? "31/12/" . date("Y") : $hasta; //valida que si no se selecciona fecha, coloque la del dia
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("../", "encuestas"); ?>
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
											<li class="nav-item active">
												<a class="nav-link active" href="../ENCCUESTIONARIO/FRMreportes.php">
													<h6><i class="fa fa-print"></i> Invitaciones</h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="../ENCEJECUCION/FRMreportes.php">
													<h6><i class="fa fa-print"></i> Ejecuciones</h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="../ENCEJECUCION/FRMcuestionarios.php">
													<h6><i class="fas fa-chart-bar"></i> Resultados por Cuestionario</h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="../ENCEJECUCION/FRMresultados.php">
													<h6><i class="fas fa-chart-pie"></i> Resultados Generales</h6>
												</a>
											</li>
										</ul>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<!-- .card -->
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-print"></i> Reportes de Invitaciones</h5>
							</div>
							<div class="card-body all-icons">
								<form name="f1" id="f1" method="get">
									<div class="row">
										<div class="col-xs-12 col-md-12 text-right"><label class="text-info">* Filtros de Busqueda</label> </div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Categor&iacute;a:</label> <span class="text-success">*</span>
											<?php echo utf8_decode(categorias_encuesta_html("categoria", "", "select2")); ?>
											<script>
												document.getElementById("categoria").value = '<?php echo $categoria; ?>';
											</script>
										</div>
										<div class="col-md-6">
											<label>Cuestionario (Encuesta):</label> <span class="text-success">*</span>
											<?php echo utf8_decode(cuestionario_encuesta_html("encuesta", $categoria, "", "select2")); ?>
											<script>
												document.getElementById("encuesta").value = '<?php echo $encuesta; ?>';
											</script>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Situaci&oacute;n:</label> <span class="text-success">*</span>
											<select id="situacion" name="situacion" class="form-control select2">
												<option value="" selected>Todas</option>
												<option value="1">Programadas</option>
												<option value="2">Ejecutadas</option>
											</select>
											<script>
												document.getElementById("sit").value = '<?php echo $situacion; ?>';
											</script>
										</div>
										<div class="col-md-6">
											<label>Fechas:</label> <span class="text-success">*</span>
											<div class="form-group" id="range">
												<div class="input-daterange input-group" id="datepicker">
													<input type="text" class="input-sm form-control" name="desde" id="desde" value="<?php echo $desde; ?>" />
													<span class="input-group-addon"> &nbsp; <i class="fas fa-calendar"></i> &nbsp; </span>
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
												<option value="cue_codigo" selected>C&oacute;digo de Cuestionario</option>
												<option value="cue_titulo" selected>Nombre del cuestionario</option>
												<option value="cue_ponderacion">Ponderaci&oacute;n del Cuestionario</option>
												<option value="inv_codigo">C&oacute;digo de Invitaci&oacute;n</option>
												<option value="inv_fecha_registro" selected>Fecha de Invitaci&oacute;n</option>
												<option value="inv_cliente" selected>Cliente a qui&eacute;n se env&iacute;a</option>
												<option value="inv_correo" selected>Correo al que se env&iacute;a</option>
												<option value="inv_url">URL de redirecci&oacute;n</option>
												<option value="inv_observaciones">Observaciones del Cuestionario</option>
												<option value="inv_situacion" selected>Situaci&oacute;n (Status)</option>
												<option value="cat_codigo">C&oacute;digo de Categor&iacute;a</option>
												<option value="cat_nombre">Categor&iacute;a</option>
												<option value="cat_color">Color</option>
												<option value="usuario_nombre">Usuario que Env&iacute;a la Invitaci&oacute;n</option>
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


	<script type="text/javascript" src="../assets.1.2.8/js/modules/encuestas/cuestionario.js"></script>


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
				myform.action = "FRMreporte.php";
				myform.submit();
				myform.action = "";
				myform.target = "";
				myform.method = "get";
			} else {
				swal("Alto", "Para generar este listado en PDF debe seleccionar al menos 1 columna...", "info");
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
					swal("Alto", "Para generar este listado en PDF no debe de exceder mas de 11 columnas, podr\u00EDan desplegarse fuera de la p\u00E1gina...", "warning");
				}
			} else {
				swal("Alto", "Para generar este listado en PDF debe seleccionar al menos 1 columna...", "info");
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
				myform.action = "EXCELreporte.php";
				myform.submit();
				myform.action = "";
				myform.target = "";
				myform.method = "get";
			} else {
				swal("Alto", "Para generar este listado en PDF debe seleccionar al menos 1 columna...", "info");
			}
		}
	</script>

</body>

</html>