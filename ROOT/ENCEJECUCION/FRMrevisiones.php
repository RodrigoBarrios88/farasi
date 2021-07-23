<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$correo = $_REQUEST["correo"];
$cliente = $_REQUEST["cliente"];
$area = $_REQUEST["area"];
$categoria = $_REQUEST["categoria"];
$usuario = $_REQUEST["usuario"];
$situacion = $_REQUEST["situacion"];
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
		<?php echo sidebar("../", "encuestas"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="nc-icon nc-paper"></i> Historial de Cuestionarios y Resultados</h5>
							</div>
							<div class="card-body all-icons">
								<form name="f1" id="f1" action="" method="get">
									<div class="row">
										<div class="col-xs-6 col-md-6 text-left">
										<button type="button" class="btn btn-white" onclick="window.history.back();">
											<i class="fa fa-chevron-left"></i>Atr&aacute;s
										</button>
									</div>
										<div class="col-xs-12 col-md-12 text-right"><label class="text-info">* Filtros de Busqueda</label> </div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Categor&iacute;a:</label> <span class="text-danger">*</span>
											<?php echo utf8_decode(categorias_encuesta_html("categoria", "Submit();", "select2")); ?>
											<script>
												document.getElementById("categoria").value = '<?php echo $categoria; ?>';
											</script>
										</div>
										<div class="col-md-6">
											<label>Fechas:</label> <span class="text-danger">*</span>
											<div class="form-group" id="range">
												<div class="input-daterange input-group" id="datepicker">
													<input type="text" class="input-sm form-control" name="desde" id="desde" value="<?php echo $desde; ?>" />
													<span class="input-group-addon"> &nbsp; <i class="fas fa-calendar"></i> &nbsp; </span>
													<input type="text" class="input-sm form-control" name="hasta" id="hasta" value="<?php echo $hasta; ?>" />
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Situaci&oacute;n:</label> <span class="text-info">*</span>
											<select class="form-control select2" id="situacion" name="situacion" onchange="Submit();">
												<option value="">TODOS</option>
												<option value="1">En procesos</option>
												<option value="2">Finalizados</option>
											</select>
											<script>
												document.getElementById("situacion").value = '<?php echo $situacion; ?>';
											</script>
										</div>
										<div class="col-md-6 text-center">
											<br>
											<a class="btn btn-white" href="FRMrevisiones.php"><i class="fa fa-eraser"></i> Limpiar</a>
											<button type="button" class="btn btn-primary" onclick="Submit();"><i class="fa fa-search"></i> Buscar</button>
										</div>
									</div>
								</form>
								<br>
								<div class="row">
									<div class="col-md-12 text-center">

									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-lg-12" id="result">
										<?php
										echo tabla_ejecucion($codigo, $encuesta, $categoria, $fini, $ffin, $situacion);
										?>
									</div>
								</div>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/encuestas/ejecucion.js"></script>
	<script>
		$(document).ready(function() {
			$('.dataTables-example').DataTable({
				pageLength: 100,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: [

				]
			});

			$('.select2').select2({ width: '100%' });
			$('#range .input-daterange').datepicker({
				keyboardNavigation: false,
				forceParse: false,
				autoclose: true,
				format: "dd/mm/yyyy"
			});
		});
	</script>

</body>

</html>