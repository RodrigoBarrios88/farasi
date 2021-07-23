<?php
	include_once('html_fns_ejecucion.php');
	validate_login("../");
$id = $_SESSION["codigo"];

	//$_POST
	$sede = $_REQUEST["sede"];
	$area = $_REQUEST["area"];
	$activo = $_REQUEST["activo"];
	$usuario = $_REQUEST["usuario"];
	$situacion = $_REQUEST["situacion"];
	//--
	$mes = date("m");
	$anio = date("Y");
	$desde = $_REQUEST["desde"];
	$desde = ($desde == "")?date("01/m/Y"):$desde; //valida que si no se selecciona fecha, coloque la del dia
	$hasta = $_REQUEST["hasta"];
	$hasta = ($hasta == "")?date("d/m/Y"):$hasta; //valida que si no se selecciona fecha, coloque la del dia
?>
<!DOCTYPE html>
<html>
<head>
    <?php echo head("../"); ?>
</head>
<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../","ppm"); ?>
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
												<a class="nav-link" href="../CPPPMEJECUCION/FRMreportes.php" >
													<h6><i class="fa fa-list"></i> &Iacute;ndice</h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="../CPPPMPROGRA/FRMreportes.php" >
													<h6><i class="fa fa-print"></i> Programaci&oacute;n</h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link active" href="../CPPPMEJECUCION/FRMreptareas.php" >
													<h6><i class="fa fa-print"></i> Ordenes de Trabajo</h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="../CPPPMEJECUCION/FRMpresupuesto.php" >
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
								<h5 class="card-title"><i class="fa fa-search"></i> Reporte de Ordenes de Trabajo (Tareas)</h5>
							</div>
							<div class="card-body all-icons">
								<form name = "f1" id = "f1" action="" method="get">
								<div class="row">
									<div class="col-xs-6 col-md-6 text-left">
										<a type="button" class="btn btn-white" href="../menu_ppm.php"><i class="fa fa-chevron-left"></i> Atr&aacute;s</a>
									</div>
									<div class="col-xs-6 col-md-6 text-right"><label class = "text-info">* Filtros de Busqueda</label> </div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Area:</label> <span class="text-danger">*</span>
										<?php echo utf8_decode(areas_sede_html("area","Submit()","select2")); ?>
										<script>
											document.getElementById("area").value="<?php echo $area; ?>"
										</script>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Activo:</label> <span class="text-info">*</span>
										<?php echo utf8_decode(activos_html("activo","",$area,"Submit()","select2")); ?>
										<script>
											document.getElementById("activo").value="<?php echo $activo; ?>";
										</script>
									</div>
									<div class="col-md-6">
										<label>Usuario a Asignar:</label> <span class="text-info">*</span>
										<?php echo utf8_decode(usuarios_html("usuario","Submit()","select2")); ?>
										<script>
											document.getElementById("usuario").value="<?php echo $usuario; ?>";
										</script>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Fechas:</label> <span class="text-info">*</span>
										<div class="form-group" id="range">
											<div class="input-daterange input-group" id="datepicker">
												<input type="text" class="input-sm form-control" name="desde" id="desde" value = "<?php echo $desde; ?>" />
												<span class="input-group-addon"> &nbsp; <i class="fa fa-calendar"></i>  &nbsp; </span>
												<input type="text" class="input-sm form-control" name="hasta" id="hasta" value = "<?php echo $hasta; ?>" />
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<label>Situaci&oacute;n:</label> <span class="text-info">*</span>
										<select class="form-control select2" name="situacion" id="situacion" onchange="Submit();">
											<option value="">Todas las Actividades</option>
											<option value="1">Programadas</option>
											<option value="2">En Espera</option>
											<option value="3">En Proceso</option>
											<option value="4">En Finalizadas</option>
											<option value="5">Vencidas</option>
										</select>
										<script>
											document.getElementById("situacion").value="<?php echo $situacion; ?>";
										</script>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-md-12">
										<label>Campos o Columnas del Reporte:</label> <span class="text-success">*</span>
										<select class="dual_select"  id = "columnas" name="columnas[]" multiple style="min-height: 250px;" >
											<option value="pro_codigo">C&oacute;digo de Tarea</option>
											<option value="pro_fecha" selected>Fecha Programada</option>
											<option value="usu_nombre" selected>Usuario Asignado (Responsable)</option>
											<option value="pro_presupuesto_programado" selected>Presupuesto Asignado</option>
											<option value="pro_presupuesto_ejecutado" selected>Presupuesto Ejecutado</option>
											<option value="cat_codigo">C&oacute;digo de Categor&iacute;a</option>
											<option value="cat_nombre" selected>Categor&iacute;a</option>
											<option value="pro_observaciones_programacion" selected>Observaciones al Programar</option>
											<option value="pro_observaciones_ejecucion" selected>Observaciones al Ejecutar</option>
											<option value="pro_foto1" selected>Foto Inicial</option>
											<option value="pro_foto2" selected>Foto Final</option>
											<option value="pro_firma" selected>Firma</option>
											<option value="pro_fecha_update" selected>Fecha de Actualizaci&oacute;n</option>
											<option value="pro_situacion" selected>Status de la Tarea</option>
											<option value="act_codigo">C&oacute;digo de Activo</option>
											<option value="act_nombre" selected>Nombre del Activo</option>
											<option value="act_marca" selected>Marca</option>
											<option value="act_serie" selected>No. Serie</option>
											<option value="act_modelo" selected>Modelo</option>
											<option value="act_parte" selected>No. Parte</option>
											<option value="act_proveedor" selected>Proveedor</option>
											<option value="act_periodicidad" selected>Periodicidad</option>
											<option value="act_capacidad" selected>Capacidad</option>
											<option value="act_cantidad">Cantidad</option>
											<option value="act_precio_nuevo">Precio Nuevo</option>
											<option value="act_precio_compra">Precio Compra</option>
											<option value="act_precio_actual">Precio Actual</option>
											<option value="act_fecha_registro">Fecha de Registro</option>
											<option value="act_fecha_update">Fecha de Actualizaci&oacute;n</option>
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
