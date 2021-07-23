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
$categoria = $_REQUEST['categoria'];
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
								<h5 class="card-title"><i class="fa fa-search"></i> Visualizaci&oacute;n de Informaci&oacute;n Programada</h5>
							</div>
							<div class="card-body all-icons">
								<form name="f1" id="f1" action="" method="get">
									<div class="row">
										<div class="col-xs-6 col-md-6 text-left">
											<a type="button" class="btn btn-white" href="../menu_ppm.php"><i class="fa fa-chevron-left"></i> Atr&aacute;s</a>
										</div>
										<div class="col-xs-6 col-md-6 text-right"><label class="text-info">* Filtros de Busqueda</label> </div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<label>Area:</label> <span class="text-info">*</span>
											<?php echo utf8_decode(areas_sede_html("area", "Submit()", "select2")); ?>
											<script>
												document.getElementById("area").value = "<?php echo $area; ?>"
											</script>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Activo:</label> <span class="text-info">*</span>
											<?php echo utf8_decode(activos_html("activo", "", $area, "Submit()", "select2")); ?>
											<script>
												document.getElementById("activo").value = "<?php echo $activo; ?>";
											</script>
										</div>
										<div class="col-md-6">
											<label>Usuario a Asignar:</label> <span class="text-info">*</span>
											<?php echo utf8_decode(usuarios_html("usuario", "Submit()", "select2")); ?>
											<script>
												document.getElementById("usuario").value = "<?php echo $usuario; ?>";
											</script>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Fechas:</label> <span class="text-info">*</span>
											<div class="form-group" id="range">
												<div class="input-daterange input-group" id="datepicker">
													<input type="text" class="input-sm form-control" name="desde" id="desde" value="<?php echo $desde; ?>" />
													<span class="input-group-addon"> &nbsp; <i class="fa fa-calendar"></i> &nbsp; </span>
													<input type="text" class="input-sm form-control" name="hasta" id="hasta" value="<?php echo $hasta; ?>" />
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
												document.getElementById("situacion").value = "<?php echo $situacion; ?>";
											</script>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Categoria: <span class="text-info">*</span> </label>
											<?php echo utf8_decode(categorias_ppm_html("categoria", "Submit()", "select2")); ?>
										</div>
										<script>
												document.getElementById("categoria").value = "<?php echo $categoria; ?>";
										</script>
									</div>
									<hr>
									<br>
									<div class="row">
										<div class="col-md-12 text-center">
											<a type="button" class="btn btn-white" id="btn-limpiar" href="FRMrevisiones.php"><i class="fas fa-eraser"></i> Limpiar</a>
											<button type="button" class="btn btn-primary" id="busca" onclick="Submit();"><span class="fa fa-search"></span> Buscar</button>
										</div>
									</div>
								</form>
								<br>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-xs-12 col-md-12">
										<?php echo tabla_revisiones($activo, $usuario, $categoria, '', $area, $desde, $hasta, $situacion); ?>
									</div>
								</div>
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
		$(document).ready(function() {
			$('.dataTables-example').DataTable({
				pageLength: 100,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: [

				]
			});

			$('#range .input-daterange').datepicker({
				keyboardNavigation: false,
				forceParse: false,
				autoclose: true,
				format: "dd/mm/yyyy"
			});

			$('.select2').select2({ width: '100%' });
		});
	</script>

</body>

</html>