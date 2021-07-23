<?php
include_once('html_fns_ticket.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$sede = $_REQUEST["sede"];
$area = $_REQUEST["area"];
$incidente = $_REQUEST["incidente"];
$titulo = $_REQUEST["titulo"];
$descripcion = $_REQUEST["descripcion"];
$categoria = $_REQUEST["categoria"];

if ($incidente != "") {
	$ClsInc = new ClsIncidente();
	$result = $ClsInc->get_incidente($incidente, '', '', '', 1);
	if (is_array($result)) {
		foreach ($result as $row) {
			$categoria_db = trim($row["cat_codigo"]);
			$prioridad = trim($row["pri_codigo"]);
		}
	}
}

?>
	<!DOCTYPE html>
	<html>

	<head>
		<?php echo head("../"); ?>

	</head>

	<body class="">
		<div class="wrapper ">
			<?php echo sidebar("../","helpdesk"); ?>
			<div class="main-panel">
				<?php echo navbar("../"); ?>	
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="card demo-icons">
								<div class="card-header">
									<h5 class="card-title"><i class="fa fa-plus"></i> Nuevo Ticket</h5>
								</div>
								<div class="card-body all-icons">
									<form id="f1" method="get">
										<div class="row">
											<div class="col-xs-6 col-md-6 text-left">
										<button type="button" class="btn btn-white" onclick="window.history.back();">
											<i class="fa fa-chevron-left"></i>Atr&aacute;s
										</button>
									</div>
											<div class="col-xs-6 col-md-6 text-right"><label class=" text-danger">* Campos Obligatorios</label> </div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Categor&iacute;a:</label> <span class="text-danger">*</span>
												<?php echo utf8_decode(categorias_hd_html("categoria", "Submit()", "select2 form-control")); ?>
												<script>
													document.getElementById("categoria").value = "<?php echo $categoria; ?>"
												</script>
											</div>
											<div class="col-md-6">
												<label>Sede:</label> <span class="text-danger">*</span>
												<?php echo utf8_decode(sedes_html("sede", "Submit()", "select2 form-control")); ?>
												<script>
													document.getElementById("sede").value = "<?php echo $sede; ?>"
												</script>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Area:</label> <span class="text-danger">*</span>
												<?php echo utf8_decode(areas_sede_html2("area", $sede, "setArea(this.value)", "select2")); ?>
												<script>
													document.getElementById("area").value = "<?php echo $area; ?>"
												</script>
											</div>
											<div class="col-md-6">
												<label>Sector:</label> <span class="text-danger">*</span>
												<input type="text" class="form-control" name="secNombre" id="secNombre" readonly />
												<input type="hidden" name="sector" id="sector" value="" />
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Incidente:</label> <span class="text-danger">*</span>
												<?php echo utf8_decode(incidentes_html('incidente', $categoria, '', "Submit()", "select2")); ?>
												<script>
													document.getElementById("incidente").value = "<?php echo $incidente; ?>"
												</script>
											</div>
											<div class="col-md-6">
												<label>Prioridad:</label> <span class="text-danger">*</span>
												<?php echo utf8_decode(prioridades_html("prioridad", "", "select2")); ?>
												<script>
													document.getElementById("prioridad").value = "<?php echo $prioridad; ?>"
												</script>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Descripci&oacute;n:</label>
												<textarea class="form-control textarea-autosize" name="descripcion" id="descripcion" onkeyup="textoLargo(this);"><?php echo $descripcion; ?></textarea>
											</div>
									</form>
									<div class="col-md-4 text-center">
										<br>
										<div class="fileinput fileinput-new text-center" data-provides="fileinput">
											<div class="fileinput-new thumbnail">
												<img src="../../CONFIG/img/imagePhoto.jpg" width="110px;" alt="...">
											</div>
											<div class="fileinput-preview fileinput-exists thumbnail"></div>
											<div>
												<span class="btn btn-rose btn-round btn-file">
													<span class="fileinput-new" onclick="FotoJs();"><i class="fa fa-camera"></i> Agregar Imagen</span>
													<input type="file" name="imagen" id="imagen" class="hidden" />
													<input type="hidden" id="ticket" name="ticket" />
													<input type="hidden" id="sms" name="sms" />
													<input type="hidden" id="posicion" name="posicion" value="1" />
													<input type="hidden" id="pagina" name="pagina" />
												</span>
												<a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Quitar</a>
											</div>
										</div>
									</div>
								</div>						<br>
								<hr>
								<div class="row">
									<div class="col-md-12 text-center">
										<a class="btn btn-white" href="FRMnewticket.php"><i class="fa fa-eraser"></i> Limpiar</a>
										<button type="button" class="btn btn-primary" id="btn-grabar" onclick="Grabar();"><i class="fa fa-save"></i> Grabar</button>
									</div>
								</div>
								<br>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php echo footer(); ?>
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
					buttons: [{
							extend: 'copy'
						},
						{
							extend: 'csv'
						},
						{
							extend: 'excel',
							title: 'Reporte de Ticket'
						},
						{
							extend: 'pdf',
							title: 'Reporte de Ticket'
						},
						{
							extend: 'print',
							customize: function(win) {
								$(win.document.body).addClass('white-bg');
								$(win.document.body).css('font-size', '10px');
								$(win.document.body).find('table')
									.addClass('compact')
									.css('font-size', 'inherit');
							},
							title: 'Reporte de Ticket'
						}
					]
				});		$('.select2').select2({ width: '100%' });		setArea(document.getElementById("area").value);
			});
		</script>

	</body>

	</html>
