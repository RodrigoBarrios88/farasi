<?php
	include_once('html_fns_ejecucion.php');
	validate_login("../");
$id = $_SESSION["codigo"];

	//--
	$sedes = $_SESSION["sedes_in"];	
	//$_POST
	$sede = $_REQUEST["sede"];
	$sede = ($sede == "")?$sedes:$sede;
	$departamento = $_REQUEST["departamento"];
	$categoria = $_REQUEST["categoria"];
	//--
	$anio = date("Y");
	$desde = $_REQUEST["desde"];
	$desde = ($desde == "")?"01/01/".date("Y"):$desde; //valida que si no se selecciona fecha, coloque la del dia
	$hasta = $_REQUEST["hasta"];
	$hasta = ($hasta == "")?"31/12/".date("Y"):$hasta; //valida que si no se selecciona fecha, coloque la del dia
?>
<!DOCTYPE html>
<html>
<head>
<?php echo head("../"); ?>
</head>
<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("../","auditoria"); ?>
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
												<a class="nav-link" href="../CPAUDCUESTIONARIO/FRMreportes.php" >
													<h6><i class="fa fa-print"></i> Rep. Programaci&oacute;n</h6>
												</a>
											</li>
											<li class="nav-item active">
												<a class="nav-link active" href="../CPAUDEJECUCION/FRMreportes.php" >
													<h6><i class="fa fa-print"></i> Rep. Auditorias</h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="../CPAUDRESULTADOS/FRMresultados.php" >
													<h6><i class="fa fa-calendar-o"></i> Periodico Resultados</h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="../CPAUDINFORME/FRMreportesPlan.php" >
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
								<h5 class="card-title"><i class="fa fa-print"></i> Reportes de Auditorias</h5>
							</div>
							<div class="card-body all-icons">
								<form name="f1" id="f1" method="get">
								<div class="row">
									<div class="col-xs-12 col-md-12 text-right"><label class = "text-info">* Filtros de Busqueda</label> </div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Sede:</label> <span class="text-success">*</span>
										<?php echo utf8_decode(sedes_html("sede","Submit();","select2")); ?>
										<script>
											document.getElementById("sede").value = '<?php echo $sede; ?>';
										</script>
									</div>
									<div class="col-md-6">
										<label>Departamento:</label> <span class="text-success">*</span>
										<?php echo utf8_decode(departamento_org_html("departamento","","select2")); ?>
										<script>
											document.getElementById("departamento").value = '<?php echo $departamento; ?>';
										</script>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Categor&iacute;a:</label> <span class="text-success">*</span>
										<?php echo utf8_decode(categorias_auditoria_html("categoria","","select2")); ?>
										<script>
											document.getElementById("categoria").value = '<?php echo $categoria; ?>';
										</script>
									</div>
									<div class="col-md-6">
										<label>Fechas:</label> <span class="text-success">*</span>
										<div class="form-group" id="range">
											<div class="input-daterange input-group" id="datepicker">
												<input type="text" class="input-sm form-control" name="desde" id="desde" value = "<?php echo $desde; ?>" />
												<span class="input-group-addon"> &nbsp; <i class="fa fa-calendar"></i>  &nbsp; </span>
												<input type="text" class="input-sm form-control" name="hasta" id="hasta" value = "<?php echo $hasta; ?>" />
											</div>
										</div>
									</div>
								</div>
								<div class="row">
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
								<hr>
								<div class="row">
									<div class="col-md-12">
										<label>Campos o Columnas del Reporte:</label> <span class="text-success">*</span>
										<select class="dual_select"  id = "columnas" name="columnas[]" multiple style="min-height: 250px;" >
											<option value="audit_codigo">C&oacute;digo de Cuestionario</option>
											<option value="audit_nombre" selected>Nombre del Cuestionario</option>
											<option value="audit_ponderacion" selected>Ponderaci&oacute;n del Cuestionario</option>
											<option value="pro_codigo">C&oacute;digo de Programaci&oacute;n</option>
											<option value="pro_fecha" selected>Fecha Programada</option>
											<option value="pro_hora" selected>Hora Programada</option>
											<option value="pro_observaciones" selected>Observaciones del Cuestionario</option>
											<option value="eje_codigo">C&oacute;digo de Ejecuci&oacute;n</option>
											<option value="eje_fecha_inicio" selected>Fecha de Inicio</option>
											<option value="eje_fecha_final" selected>Fecha de Finalizaci&oacute;n</option>
											<option value="eje_correos">Correos de Notificaci&oacute;n</option>
											<option value="eje_responsable" selected>Responsable</option>
											<option value="eje_observaciones" selected>Observaciones</option>
											<option value="eje_situacion" selected>Situaci&oacute;n (Status)</option>
											<option value="cat_codigo">C&oacute;digo de Categor&iacute;a</option>
											<option value="cat_nombre" selected>Categor&iacute;a</option>
											<option value="cat_color">Color</option>
											<option value="dep_codigo">C&oacute;digo de Departamento</option>
											<option value="dep_nombre" selected>Departamento</option>
											<option value="sed_codigo">C&oacute;digo de Sede</option>
											<option value="sed_nombre" selected>Sede</option>
											<option value="sede_municipio" selected>Municipio</option>
											<option value="sed_direccion" selected>Direcci&oacute;n</option>
											<option value="sed_zona" selected>Zona</option>
										</select>
										<input type="hidden" name="titulo" id="titulo" value = "Reporte de Casos Abiertos" />
										<input type="hidden" name="situacion" id="situacion" value = "1" />
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
		var dual = $('select[name="columnas[]"]').bootstrapDualListbox();
		
		$(document).ready(function () {
			$('.select2').select2({ width: '100%' });	$('#range .input-daterange').datepicker({
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
				format: "dd/mm/yyyy"
            });
		});
		
		function HTML(){
			var columnas = 0;
			$('#columnas option:selected').each(function() {
				columnas++;
		    });
			if(columnas >= 1){
				myform = document.forms.f1;
				myform.method = "get";
				myform.target ="_blank";
				myform.action ="FRMreporte.php";
				myform.submit();
				myform.action ="";
				myform.target ="";
				myform.method = "get";
			}else{
				swal("Alto", "Para generar este listado en PDF debe seleccionar al menos 1 columna...", "info");
			}
		}function PDF(){
			var columnas = 0;
			$('#columnas option:selected').each(function() {
				columnas++;
		    });
			if(columnas >= 1){
				if(columnas <= 10){
					myform = document.forms.f1;
					myform.method = "get";
					myform.target ="_blank";
					myform.action ="REPreporte.php";
					myform.submit();
					myform.action ="";
					myform.target ="";
					myform.method = "get";
				}else{
					swal("Alto", "Para generar este listado en PDF no debe de exceder mas de 11 columnas, podr\u00EDan desplegarse fuera de la p\u00E1gina...", "warning");
				}
			}else{
				swal("Alto", "Para generar este listado en PDF debe seleccionar al menos 1 columna...", "info");
			}
		}
		
		function Excel(){
			var columnas = 0;
			$('#columnas option:selected').each(function() {
				columnas++;
		    });
			if(columnas >= 1){
				myform = document.forms.f1;
				myform.method = "get";
				myform.target ="_blank";
				myform.action ="EXCELreporte.php";
				myform.submit();
				myform.action ="";
				myform.target ="";
				myform.method = "get";
			}else{
				swal("Alto", "Para generar este listado en PDF debe seleccionar al menos 1 columna...", "info");
			}
		}
	</script>

</body>
</html>
