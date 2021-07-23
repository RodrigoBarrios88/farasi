<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$sede = $_REQUEST["sede"];
$departamento = $_REQUEST["departamento"];
$area = $_REQUEST["area"];
$categoria = $_REQUEST["categoria"];
$usuario = $_REQUEST["usuario"];
$situacion; // la situacion para esta pantalla será siempre 3 (solicitando revision)
//--
$hoy = date("Y-m-j");
$atras = strtotime('-1 year', strtotime($hoy));
$atras = date('d/m/Y', $atras);
$hoy = date("d/m/Y");
$desde = $_REQUEST["desde"];
$desde = ($desde == "") ? $atras : $desde; //valida que si no se selecciona fecha, coloque la un año atras
$hasta = $_REQUEST["hasta"];
$hasta = ($hasta == "") ? $hoy : $hasta; //valida que si no se selecciona fecha, coloque la del dia?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../", "auditoria"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fas fa-clipboard-check"></i> Revisi&oacute;n y Aprobaci&oacute;n de Auditorias</h5>
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
											<label>Sede:</label> <span class="text-info">*</span>
											<?php echo utf8_decode(sedes_html("sede", "Submit();", "select2")); ?>
											<script>
												document.getElementById("sede").value = '<?php echo $sede; ?>';
											</script>
										</div>
										<div class="col-md-6">
											<label>Departamento:</label> <span class="text-info">*</span>
											<?php echo utf8_decode(departamento_org_html("departamento", "Submit();", "select2")); ?>
											<script>
												document.getElementById("departamento").value = "<?php echo $departamento; ?>"
											</script>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Categor&iacute;a:</label> <span class="text-info">*</span>
											<?php echo utf8_decode(categorias_auditoria_html("categoria", "Submit();", "select2")); ?>
											<script>
												document.getElementById("categoria").value = '<?php echo $categoria; ?>';
											</script>
										</div>
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
									</div>
									<div class="row">
										<div class="col-md-12 text-center">
											<br>
											<a class="btn btn-white" href="FRMaprobaciones.php"><i class="fa fa-eraser"></i> Limpiar</a>
											<button type="button" class="btn btn-primary" onclick="Submit();"><i class="fa fa-search"></i> Buscar</button>
										</div>
									</div>
								</form>
								<hr>
								<div class="row">
									<div class="col-lg-12" id="result">
										<?php
										echo tabla_aprobacion($codigo, $auditoria, $usuario, $sede, $departamento, $categoria, $desde, $hasta);
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/auditoria/ejecucion.js"></script>
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