<?php
include_once('html_fns_proceso.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$tipo = $_REQUEST["tipo"];
$usuario = $_REQUEST["usuario"];
$fini = $_REQUEST["fini"];
$ffin = $_REQUEST["ffin"];
$situacion = $_REQUEST["situacion"];
//--
$fini = $_REQUEST["desde"];
$fini = ($fini == "") ? date("01/01/2020") : $fini; //valida que si no se selecciona fecha, coloque la del dia
$ffin = $_REQUEST["hasta"];
$ffin = ($ffin == "") ? date("d/m/Y") : $ffin; //valida que si no se selecciona fecha, coloque la del dia
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("../", "process"); ?>
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
												<a class="nav-link active" href="../CPPROCESS/FRMreportes.php">
													<h6><i class="fa fa-print"></i> Lista de Procesos (Fichas)</h6>
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
								<h5 class="card-title"><i class="fa fa-print"></i> Reportes de Procesos</h5>
							</div>
							<div class="card-body all-icons">
								<form name="f1" id="f1" method="get">
									<div class="row">
										<div class="col-xs-12 col-md-12 text-right"><label class="text-info">* Filtros de Busqueda</label> </div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>T&iacute;po Proceso:</label> <span class="text-danger">*</span>
											<?php echo combos_vacios("tipo", 'select2'); ?>
										</div>
										<div class="col-md-6">
											<label>Situaci&oacute;n:</label> <span class="text-danger">*</span>
											<select name="situacion" id="situacion" class="form-control select2">
												<option value="">Seleccione</option>
												<option value="1">En edici&oacute;n</option>
												<option value="2">En aprobaci&oacute;n</option>
												<option value="3">Aprobados</option>
											</select>
											<script>
												document.getElementById("situacion").value = '<?php echo $situacion; ?>';
											</script>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Usuario:</label> <span class="text-danger">*</span>
											<?php echo utf8_decode(usuarios_html("usuario", "Submit();", "select2")); ?>
											<script>
												document.getElementById("usuario").value = '<?php echo $usuario; ?>';
											</script>
										</div>
										<div class="col-md-6">
											<label>Fechas:</label> <span class="text-danger">*</span>
											<div class="form-group" id="range">
												<div class="input-daterange input-group" id="datepicker">
													<input type="text" class="input-sm form-control" name="fini" id="fini" value="<?php echo $fini; ?>" />
													<span class="input-group-addon"> &nbsp; <i class="fa fa-calendar"></i> &nbsp; </span>
													<input type="text" class="input-sm form-control" name="ffin" id="ffin" value="<?php echo $ffin; ?>" />
												</div>
											</div>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-md-12">
											<label>Campos o Columnas del Reporte:</label> <span class="text-success">*</span>
											<select class="dual_select" id="columnas" name="columnas[]" multiple style="min-height: 250px;">
												<option value="fic_codigo" selected>C&oacute;digo de la ficha</option>
												<option value="fic_nombre" selected>Nombre de la ficha</option>
												<option value="fic_tipo" selected>Tipo</option>
												<option value="fic_analisis_foda">An&aacute;lisis foda</option>
												<option value="fic_objetivo_general" selected>Objetivo general</option>
												<option value="fic_medidas_verificacion">Verificaci&oacute;n de medidas</option>
												<option value="fic_usuario" selected>Usuario</option>
												<option value="fic_fecha_registro" selected>Fecha de registro</option>
												<option value="fic_fecha_update" selected>Fecha de actualizaci&oacute;n</option>
												<option value="fic_fecha_aprobacion" selected>Fecha de aprobaci&oacute;n</option>
												<option value="fic_situacion" selected>Situaci&oacute;n</option>
												<option value="fic_pertenece">Proceso al que pertenece</option>
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
	<script>
		var dual = $('select[name="columnas[]"]').bootstrapDualListbox();

		$(document).ready(function() {
			$(".select2").select2();
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