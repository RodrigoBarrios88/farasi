<?php
include_once('html_fns_activo.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$sede = $_REQUEST["sede"];
$sector = $_REQUEST["sector"];
$area = $_REQUEST["area"];
//--
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
												<a class="nav-link" href="../CPACTIVO/FRMrepfichas.php">
													<h6><i class="fa fa-print"></i> Fichas</h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link active" href="../CPACTIVO/FRMrepinactivo.php">
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
								<h5 class="card-title"><i class="fa fa-print"></i> Reportes de Activos Fuera de Servicio</h5>
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
											<input type="hidden" name="situacion" id="situacion" value="2" />
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
									</div>
									<hr>
									<div class="row">
										<div class="col-md-12">
											<label>Campos o Columnas del Reporte:</label> <span class="text-success">*</span>
											<select class="dual_select" id="columnas" name="columnas[]" multiple style="min-height: 250px;">
												<option value="act_codigo" selected>C&oacute;digo de Activo</option>
												<option value="act_nombre" selected>Nombre de la Activo</option>
												<option value="act_marca" selected>Marca</option>
												<option value="act_serie" selected>No. Serie</option>
												<option value="act_modelo" selected>Modelo</option>
												<option value="act_parte" selected>No. Parte</option>
												<option value="act_proveedor" selected>Proveedor</option>
												<option value="act_periodicidad">Periodicidad</option>
												<option value="act_capacidad" selected>Capacidad</option>
												<option value="act_cantidad" selected>Cantidad</option>
												<option value="act_precio_nuevo">Precio Nuevo</option>
												<option value="act_precio_compra" selected>Precio Compra</option>
												<option value="act_precio_actual" selected>Precio Actual</option>
												<option value="act_fecha_registro">Fecha de Registro</option>
												<option value="act_fecha_update">Fecha de Actualizaci&oacute;n</option>
												<option value="usu_nombre" selected>Usuario que registr&oacute;</option>
												<option value="are_codigo">C&oacute;digo de &Aacute;rea</option>
												<option value="are_nivel">Nivel</option>
												<option value="are_nombre" selected>&Aacute;rea</option>
												<option value="sec_codigo">C&oacute;digo de Sector</option>
												<option value="sec_nombre" selected>Sector</option>
												<option value="sed_codigo">C&oacute;digo de Sede</option>
												<option value="sed_nombre" selected>Sede</option>
												<option value="sede_municipio">Municipio</option>
												<option value="sed_direccion">Direcci&oacute;n</option>
												<option value="sed_zona">Zona</option>
											</select>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-md-12 text-center">
											<a type="button" class="btn btn-white" href="FRMrepinactivo.php"><i class="fa fa-eraser"></i> Limpiar</a>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/ppm/activo.js"></script>
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