<?php
include_once('html_fns_indicador.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$departamento = $_REQUEST["departamento"];
$clasificacion = $_REQUEST["clasificacion"];
$categoria = $_REQUEST["categoria"];
$dia = $_REQUEST["dia"];
$situacion = $_REQUEST["sit"];
//--
$mes = date("m");
$anio = date("Y");
$desde = $_REQUEST["desde"];
$desde = ($desde == "") ? date("1/m/Y") : $desde; //valida que si no se selecciona fecha, coloque la del dia
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
			<?php echo sidebar("../","indicador"); ?>
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
													<a class="nav-link" href="../CPINDICADOR/FRMindicadores.php">
														<h6><i class="fa fa-print"></i> Indicadores</h6>
													</a>
												</li>
												<li class="nav-item">
													<a class="nav-link active" href="../CPINDICADOR/FRMreportes.php">
														<h6><i class="fa fa-print"></i> Programaciones</h6>
													</a>
												</li>
												<li class="nav-item">
													<a class="nav-link" href="../CPINDREVISION/FRMreportes.php">
														<h6><i class="fa fa-print"></i> Revisiones</h6>
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
									<h5 class="card-title"><i class="fa fa-print"></i> Reportes de Programaciones</h5>
								</div>
								<div class="card-body all-icons">
									<form name="f1" id="f1" method="get">
										<div class="row">
											<div class="col-xs-12 col-md-12 text-right"><label class="text-info">* Filtros de Busqueda</label> </div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Proceso:</label> <span class="text-success">*</span>
												<?php echo utf8_decode(departamento_org_html("departamento", "Submit();", "select2")); ?>
												<script>
													document.getElementById("departamento").value = '<?php echo $departamento; ?>';
												</script>
											</div>
											<div class="col-md-6">
												<label>Situaci&oacute;n</label> <span class="text-success">*</span>
												<?php echo utf8_decode(combo_situacion("sit", "Submit();", "select2")); ?>
												<script>
													document.getElementById("sit").value = '<?php echo $situacion; ?>';
												</script>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Sistema:</label> <span class="text-success">*</span>
												<?php echo utf8_decode(categorias_indicador_html("categoria", "Submit();", "select2")); ?>
												<script>
													document.getElementById("categoria").value = '<?php echo $categoria; ?>';
												</script>
											</div>
											<div class="col-md-6">
												<label>Usuario:</label> <span class="text-success">*</span>
												<?php echo utf8_decode(usuarios_html("usuario", "Submit();", "select2")); ?>
												<script>
													document.getElementById("usuario").value = '<?php echo $usuario; ?>';
												</script>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Rango de Fechas:</label> <span class="text-success">*</span>
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
													<!-- Indicador -->
													<option value="ind_codigo" selected>C&oacute;digo de Indicador</option>
													<option value="ind_nombre" selected>Nombre del Indicador</option>
													<!-- Programacion -->
													<option value="pro_codigo">C&oacute;digo de Programaci&oacute;n</option>
													<option value="pro_usuario" selected>Usuario que programa</option>
													<option value="pro_fecha" selected>Fecha Programada</option>
													<option value="pro_hini_hfin" selected>Intervalo de Horarios</option>
													<option value="pro_observaciones">Observaciones del indicador</option>
													<option value="pro_tipo">Tipo de Programaci&oacute;n</option>
													<option value="obj_descripcion" selected>Objetivo</option>
													<option value="sis_nombre" selected>Sistema</option>
													<option value="fic_nombre" selected>Proceso</option>
												</select>
												<input type="hidden" name="titulo" id="titulo" value="Reporte de Casos Abiertos" />
												<input type="hidden" name="situacion" id="situacion" value="1" />
											</div>
										</div>
										<br>
										<div class="row">
											<div class="col-md-12 text-center">
												<a type="button" class="btn btn-white" href="FRMreportes.php"><i class="fa fa-eraser"></i> Limpiar</a>
												<button type="button" class="btn btn-primary" onclick="HTML();"><i class="fa fa-search"></i> Buscar</button>
												<button hidden type="button" class="btn btn-danger" onclick="PDF();"><i class="fa fa-file-pdf-o"></i> PDF</button>
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
		<!-- --- -->
		
		
		<script type="text/javascript" src="../assets.1.2.8/js/modules/indicador/indicador.js"></script>
		<script>
			var dual = $('select[name="columnas[]"]').bootstrapDualListbox();	$(document).ready(function() {
				$('.select2').select2({ width: '100%' });		$('#range .input-daterange').datepicker({
					keyboardNavigation: false,
					forceParse: false,
					autoclose: true,
					format: "dd/mm/yyyy"
				});
			});	function HTML() {
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
					swal("Alto", "Para generar este listado en HTML debe seleccionar al menos 1 columna...", "info");
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
