<?php
include_once('html_fns_proceso.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$tipo = $_REQUEST["tipo"];
$situacion = $_REQUEST["situacion"];
$usuario = $_REQUEST["usuario"];
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../", "process"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="fas fa-clipboard-list"></i> Listado de Fichas de Procesos
									<button type="button" class="btn btn-white btn-lg sin-margin pull-right" onclick="aperturaFicha()"><small><i class="nc-icon nc-simple-add"></i> Nueva Ficha</small></button>
								</h5>
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
											<label>T&iacute;po de Proceso:</label> <span class="text-info">*</span>
											<?php echo utf8_decode(subtitulos_html('tipo', 'Submit()', 'select2', 2)); ?>
											<script>
												document.getElementById("tipo").value = '<?php echo $tipo; ?>';
											</script>
										</div>
										<div class="col-md-6">
											<label>Situaci&oacute;n:</label> <span class="text-info">*</span>
											<select name="situacion" id="situacion" class="form-control select2" onchange="Submit()">
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
								</form>
								<br>
								<div class="row">
									<div class="col-md-12 text-center">
										<a class="btn btn-white" href="FRMfichas.php"><i class="fa fa-eraser"></i> Limpiar</a>
										<button type="button" class="btn btn-primary" onclick="Submit();"><i class="fa fa-search"></i> Buscar</button>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-lg-12" id="result">
										<?php
										echo tabla_fichas($codigo, $tipo, $usuario, $situacion, 0);
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/process/ficha.js"></script>
	<script>
		$(document).ready(function() {
			$('.dataTables-example').DataTable({
				pageLength: 100,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: []
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