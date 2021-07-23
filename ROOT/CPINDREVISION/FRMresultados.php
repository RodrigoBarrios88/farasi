<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$departamento = $_REQUEST["departamento"];
$clasificacion = $_REQUEST["clasificacion"];
$categoria = $_REQUEST["categoria"];
$usuario = $_REQUEST["usuario"];
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
													<a class="nav-link" href="../CPINDICADOR/FRMreportes.php">
														<h6><i class="fa fa-print"></i> Indicadores</h6>
													</a>
												</li>
												<li class="nav-item">
													<a class="nav-link" href="../CPINDREVISION/FRMreportes.php">
														<h6><i class="fa fa-print"></i> Revisiones</h6>
													</a>
												</li>
												<li class="nav-item">
													<a class="nav-link active" href="../CPINDREVISION/FRMresultados.php">
														<h6><i class="fa fa-print"></i> Peri&oacute;dico de Resultados</h6>
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
									<h5 class="card-title"><i class="fa fa-print"></i> Reportes de Resultados</h5>
								</div>
								<div class="card-body all-icons">
									<form name="f1" id="f1" method="get">
										<div class="row">
											<div class="col-xs-12 col-md-12 text-right"><label class="text-info">* Filtros de Busqueda</label> </div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Departamento:</label> <span class="text-success">*</span>
												<?php echo utf8_decode(departamento_org_html("departamento", "Submit();", "select2")); ?>
												<script>
													document.getElementById("departamento").value = '<?php echo $departamento; ?>';
												</script>
											</div>
											<div class="col-md-6">
												<label>Clasificacion:</label> <span class="text-success">*</span>
												<?php
												echo utf8_decode(clasificacion_indicador_html("clasificacion", "Submit();", "select2"));
												?>
												<script>
													document.getElementById("clasificacion").value = '<?php echo $clasificacion; ?>';
												</script>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Categor&iacute;a:</label> <span class="text-success">*</span>
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
													<option value="rev_codigo" selected>C&oacute;digo de Revisi&oacute;n</option>
													<option value="rev_fecha_inicio" selected>Fecha y hora de Inicio</option>
													<option value="rev_fecha_final" selected>Fecha y hora de Finalizaci&oacute;n</option>
													<option value="rev_observaciones" selected>Observaciones de la Revisi&oacute;n</option>
													<option value="rev_situacion" selected>Situaci&oacute;n</option>
													<option value="list_codigo">C&oacute;digo de Lista</option>
													<option value="list_nombre" selected>Nombre de la Lista</option>
													<option value="list_fotos">&iquest;Requiere Foto?</option>
													<option value="list_firma">&iquest;Requiere Firma?</option>
													<option value="pro_codigo">C&oacute;digo de Programaci&oacute;n</option>
													<option value="pro_dias">D&iacute;as Programados</option>
													<option value="pro_hini_hfin">Intervalo de Horarios</option>
													<option value="pro_observaciones">Observaciones de la Lista</option>
													<option value="cat_codigo">C&oacute;digo de Categor&iacute;a</option>
													<option value="cat_nombre">Categor&iacute;a</option>
													<option value="cat_color">Color</option>
													<option value="are_codigo">C&oacute;digo de &Aacute;rea</option>
													<option value="are_nombre" selected>&Aacute;rea</option>
													<option value="are_nivel">Nivel</option>
													<option value="rev_firma">Imagen Firma</option>
													<option value="rev_foto">Foto (Revisi&oacute;n)</option>
													<option value="rev_cont_si">Conteo de "SI"</option>
													<option value="rev_cont_no">Conteo de "NO"</option>
													<option value="rev_cont_na">Conteo de "NO APLICA"</option>
													<option value="rev_nota">% de Cumplimiento (SI/NO)</option>
													<option value="sec_codigo">C&oacute;digo de Sector</option>
													<option value="sec_nombre">Sector</option>
													<option value="sed_codigo">C&oacute;digo de Sede</option>
													<option value="sed_nombre" selected>Sede</option>
													<option value="sede_municipio">Municipio</option>
													<option value="sed_direccion">Direcci&oacute;n</option>
													<option value="sed_zona">Zona</option>
													<option value="usuario_nombre" selected>Usuario que Registra</option>
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
