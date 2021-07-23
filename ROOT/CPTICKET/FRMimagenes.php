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
		$desc = utf8_decode($row["tic_descripcion"]);
		$desc = nl2br($desc);
		//--
		$sede = utf8_decode($row["sed_nombre"]);
		$sector = utf8_decode($row["sec_nombre"]);
		$area = utf8_decode($row["are_nombre"]);
		$nivel = utf8_decode($row["are_nivel"]);
		//codigo
		$codigo = Agrega_Ceros($row["tic_codigo"]);
		//categoria
		$categoria = utf8_decode($row["cat_nombre"]);
		//prioridad
		$prioridad = utf8_decode($row["pri_nombre"]);
		$trespuesta = substr($row["pri_respuesta"], 0, 5);
		$tsolucion = substr($row["pri_solucion"], 0, 5);
		//incidente
		$incidente = utf8_decode($row["inc_nombre"]);
		//fecha de registro
		$fechor = cambia_fechaHora($row["tic_fecha_registro"]);
		//status
		$status = utf8_decode($row["sta_nombre"]);
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
		<?php echo sidebar("../", "helpdesk"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="fa fa-users-cog"></i> Imagenes
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-6">
										<label>No. de Ticket:</label>
										<h3 class="text-danger text-left"># <?php echo Agrega_Ceros($codigo); ?></h3>
										<input type="hidden" name="ticket" id="ticket" value="<?php echo $codigo; ?>" />
									</div>
									<div class="col-md-6">
										<label>Status Actual:</label>
										<h3 class="text-left text-primary"><?php echo $status; ?></h3>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-md-12">
										<h5 class="text-center">Imagenes por Status</h5>
									</div>
								</div>
								<div class="row">
									<?php
									$result = $ClsTic->get_fotos('', $codigo);
									if (is_array($result)) {
										$i = 0;
										foreach ($result as $row) {
											$posicion = trim($row["fot_posicion"]);
											$status_nombre = utf8_decode($row["sta_nombre"]);
											$strFoto = trim($row["fot_foto"]);
											if (file_exists('../../CONFIG/Fotos/TICKET/' . $strFoto . '.jpg') || $strFoto != "") {
												$strFoto = 'Fotos/TICKET/' . $strFoto . '.jpg';
											} else {
												$strFoto = "img/imagePhoto.jpg";
											}
									?>
											<div class="col-md-4 text-center">
												<div class="fileinput fileinput-new text-center" data-provides="fileinput">
													<div class="fileinput-new thumbnail">
														<img src="../../CONFIG/<?php echo $strFoto; ?>" alt="...">
													</div>
												</div>
												<p><?php echo $status_nombre; ?></p>
											</div>
									<?php
										}
									} else {
										$strFoto = "img/imagePhoto.jpg";
									}
									?>
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
		});
	</script>

</body>

</html>