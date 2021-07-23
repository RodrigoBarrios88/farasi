<?php
include_once('html_fns_ticket.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$codigo = $_REQUEST["codigo"];

$ClsTic = new ClsTicket();
$result = $ClsTic->get_ticket($codigo);
if (is_array($result)) {
	foreach ($result as $row) {
		$descripcion = utf8_decode($row["tic_descripcion"]);
		$descripcion = nl2br($descripcion);
		//--
		$sede = utf8_decode($row["tic_sede"]);
		$sector = utf8_decode($row["tic_sector"]);
		$area = utf8_decode($row["tic_area"]);
		//categoria
		$categoria = utf8_decode($row["cat_codigo"]);
		//prioridad
		$prioridad = utf8_decode($row["pri_codigo"]);
		$trespuesta = substr($row["pri_respuesta"], 0, 5);
		$tsolucion = substr($row["pri_solucion"], 0, 5);
		//incidente
		$incidente = utf8_decode($row["inc_codigo"]);
		//fecha de registro
		$fechor = cambia_fechaHora($row["tic_fecha_registro"]);
		//status
		$status = utf8_decode($row["sta_nombre"]);
		$catenom = trim($row["cat_nombre"]);
	}
}

$result = $ClsTic->get_fotos('', $codigo);
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$posicion = trim($row["fot_posicion"]);
		if ($posicion == 1) {
			$strFoto1 = trim($row["fot_foto"]);
		} else if ($posicion == 2) {
			$strFoto2 = trim($row["fot_foto"]);
		}
	}
	if (file_exists('../../CONFIG/Fotos/TICKET/' . $strFoto1 . '.jpg') || $strFoto1 != "") {
		//echo "entro";
		$strFoto1 = 'Fotos/TICKET/' . $strFoto1 . '.jpg';
	} else {
		//echo "no entro<br>";
		//echo '../../CONFIG/Fotos/TICKET/'.$strFoto1.'.jpg<br>';
		$strFoto1 = "img/imagePhoto.jpg";
	}
	if (file_exists('../../CONFIG/Fotos/TICKET/' . $strFoto2 . '.jpg') || $strFoto2 != "") {
		//echo "entro";
		$strFoto2 = 'Fotos/TICKET/' . $strFoto2 . '.jpg';
	} else {
		//echo "no entro<br>";
		//echo '../../CONFIG/Fotos/TICKET/'.$strFoto2.'.jpg<br>';	
		$strFoto2 = "img/imagePhoto.jpg";
	}
} else {
	$strFoto1 = "img/imagePhoto.jpg";
	$strFoto2 = "img/imagePhoto.jpg";
}

$catenom = ($catenom == "") ? "&nbsp;" : $catenom;
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
									<h5 class="card-title">
										<i class="fa fa-edit"></i> Actualizaci&oacute;n Tickets
										<a class="btn btn-white btn-lg sin-margin pull-right" href="FRMtickets.php"><small><i class="nc-icon nc-minimal-left"></i> Regresar</small></a>
									</h5>
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
												<label>Sede:</label> <span class="text-danger">*</span>
												<?php echo utf8_decode(sedes_html("sede", " ", "select2")); ?>
												<script>
													document.getElementById("sede").value = "<?php echo $sede; ?>"
												</script>
											</div>
											<div class="col-md-6">
												<label>Categor&iacute;a:</label> <span class="text-danger">*</span>
												<?php echo utf8_decode(categorias_hd_html("categoria", " ", "select2 form-control")); ?>
												<script>
													document.getElementById("categoria").value = "<?php echo $categoria; ?>"
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
												<?php echo utf8_decode(incidentes_html('incidente', $categoria, '', "", "select2")); ?>
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
									</form>
									<div class="row">
										<div class="col-md-6">
											<label>Descripci&oacute;n:</label>
											<textarea rows="4" class="form-control" name="descripcion" id="descripcion" onkeyup="textoLargo(this);"><?php echo $descripcion; ?></textarea>
										</div>
										<div class="col-md-4 text-center">
											<br>
											<div class="fileinput fileinput-new text-center" data-provides="fileinput">
												<div class="fileinput-new thumbnail">
													<img src="../../CONFIG/<?php echo $strFoto1; ?>" width="110px;" alt="...">
												</div>
												<div class="fileinput-preview fileinput-exists thumbnail"></div>
												<div>
													<span class="btn btn-rose btn-round btn-file">
														<span class="fileinput-new" onclick="FotoJs();"><i class="fa fa-camera"></i> Agregar Imagen</span>
														<input type="file" name="imagen" id="imagen" class="hidden" />
														<input type="hidden" id="ticket" name="ticket" value="<?php echo $codigo; ?>" />
														<input type="hidden" id="posicion" name="posicion" value="1" />
													</span>
													<a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Quitar</a>
												</div>
											</div>
										</div>
									</div>
									<br>
									<hr>
									<div class="row">
										<div class="col-md-12 text-center">
											<a class="btn btn-white" href="FRMmodticket.php"><i class="fa fa-eraser"></i> Limpiar</a>
											<button type="button" class="btn btn-primary" id="btn-grabar" onclick="Modificar();"><i class="fa fa-save"></i> Grabar</button>
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
				});
				$('.select2').select2({ width: '100%' });
				setArea(document.getElementById("area").value);
			});
		</script>

	</body>

	</html>
