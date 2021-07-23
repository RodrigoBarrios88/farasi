<?php
include_once('html_fns_ticket.php');
validate_login("../");
$id = $_SESSION["codigo"];


//$_POST
$incidente = $_REQUEST["incidente"];
$status = $_REQUEST["status"];
$prioridad = $_REQUEST["prioridad"];
$categoria = $_REQUEST["categoria"];
$desde = $_REQUEST["desde"];
$desde = ($desde == "") ? date("01/01/Y") : $desde;  //valida que si no se selecciona fecha, coloque la del dia
$hasta = $_REQUEST["hasta"];
$hasta = ($hasta == "") ? date("d/m/Y") : $hasta;
?>
	<!DOCTYPE html>
	<html>

	<head>
		<?php echo head("../"); ?>
	</head>

	<body class="sidebar-mini">
		<div class="wrapper ">
			<?php echo sidebar("../","helpdesk"); ?>
			<div class="main-panel">
				<?php echo navbar("../"); ?>
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="card demo-icons">
								<div class="card-header">
									<h5 class="card-title">
										<i class="fa fa-exclamation-circle"></i> Tickets Solicitados por el Usuario
										<a class="btn btn-white btn-lg sin-margin pull-right" href="FRMnewticket.php"><small><i class="nc-icon nc-simple-add"></i> Nuevo Ticket</small></a>
									</h5>
								</div>
								<div class="card-body all-icons">
									<form name="f1" id="f1" action="" method="get">
										<div class="row">
											<div class="col-xs-12 col-md-12 text-right"><label class="text-info">* Filtros de Busqueda</label> </div>
										</div>
										<div class="row">
											<div class="col-xs-6 col-md-6 text-left">
										<button type="button" class="btn btn-white" onclick="window.history.back();">
											<i class="fa fa-chevron-left"></i>Atr&aacute;s
										</button>
									</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Incidente:</label> <span class="text-info">*</span>
												<?php echo utf8_decode(incidentes_html("incidente", '', '', "Submit();", "select2")); ?>
												<script>
													document.getElementById("incidente").value = '<?php echo $incidente; ?>';
												</script>
											</div>
											<div class="col-md-6">
												<label>Status:</label> <span class="text-info">*</span>
												<?php echo utf8_decode(status_html_helpdesk("status", "Submit();", "select2")); ?>
												<script>
													document.getElementById("status").value = "<?php echo $status; ?>"
												</script>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Prioridad:</label> <span class="text-info">*</span>
												<?php echo utf8_decode(prioridades_html("prioridad", "Submit();", "select2")); ?>
												<script>
													document.getElementById("prioridad").value = '<?php echo $prioridad; ?>';
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
											<div class="col-md-6">
												<label>Categoria:</label> <span class="text-info">*</span>
												<?php echo utf8_decode(categorias_hd_html("categoria", "Submit();", "select2")); ?>
												<script>
													document.getElementById("categoria").value = '<?php echo $categoria; ?>';
												</script>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 text-center">
												<br>
												<a class="btn btn-white" href="FRMauditoria.php"><i class="fa fa-eraser"></i> Limpiar</a>
												<button type="button" class="btn btn-primary" onclick="Submit();"><i class="fa fa-search"></i> Buscar</button>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12" id="result">
												<?php
												echo tabla_tickets_solicitados('', $categoria, $incidente, $prioridad, $status, $desde, $hasta);
												?>
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
		<!-- --- -->

		<script type="text/javascript" src="../assets.1.2.8/js/modules/helpdesk/ticket.js"></script>
		<script>
			$(document).ready(function() {
				$('.dataTables-example').DataTable({
					pageLength: 100,
					responsive: true,
					dom: '<"html5buttons"B>lTfgitp',
					buttons: []
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
