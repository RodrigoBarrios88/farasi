<?php
include_once('html_fns_ticket.php');
validate_login("../");
$id = $_SESSION["codigo"];
$sedes_IN = $_SESSION["sedes_in"];
//$_POST
$codigo = $_REQUEST["codigo"];
$codigoTicket = $codigo;
$ClsTic = new ClsTicket();
$result = $ClsTic->get_ticket($codigo);
//var_dump($result);
if (is_array($result)) {
	foreach ($result as $row) {
		$desc = utf8_decode($row["tic_descripcion"]);
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
		//tiempo en conteo
		$respuesta = trim($row["tic_primer_status"]);
		$cierre = trim($row["tic_cierre_status"]);
		$espera = trim($row["tic_espera"]);
		//incidente
		$incidente = utf8_decode($row["inc_nombre"]);
		//fecha de registro
		$freg = trim($row["tic_fecha_registro"]);
		$fechor = cambia_fechaHora($row["tic_fecha_registro"]);
		//status
		$status = utf8_decode($row["sta_nombre"]);
	}
}

/////////// RESPUESTA /////////
if ($respuesta != "") {
	$date1 = new DateTime($freg);
	$date2 = new DateTime($respuesta);
	$interval = $date1->diff($date2);
	$Respuesta = $interval->format('%H:%I');
} else {
	$Respuesta = '- Pendiente de respuesta -';
}
/////////// SOLUCION /////////
if ($cierre != "") {
	$date1 = new DateTime($freg);
	$date2 = new DateTime($cierre);
	$interval = $date1->diff($date2);
	$Solucion = $interval->format('%H:%I');
	if ($espera != "") {
		$Solucion = date($Solucion);
		$Solucion = strtotime("-$espera minutes", strtotime($Solucion));
		$Solucion = date('H:i', $Solucion);
	}
} else {
	$Solucion = '- Pendiente de Soluci&oacute;n -';
}
/////////// Tiempo de Espera /////////
if ($espera != "") {
	$Espera = "$espera minutos";
} else {
	$Espera = ' --- ';
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
									<i class="fa fa-users-cog"></i> Tr&aacute;mite de Ticket
									<a class="btn btn-white btn-lg sin-margin pull-right" href="FRMtickets.php"><small><i class="nc-icon nc-minimal-left"></i> Regresar</small></a> &nbsp;
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-6">
										<label>No. de Ticket:</label>
										<h3 class="text-danger text-left"># <?php echo Agrega_Ceros($codigo); ?></h3>
										<input type="hidden" name="ticket" id="ticket" value="<?php echo $codigoTicket; ?>" />
									</div>
									<div class="col-md-3">
										<label>Status Actual:</label>
										<h3 class="text-left text-primary"><?php echo $status; ?></h3>
									</div>
									<div class="col-md-3">
										<button type="button" class="btn btn-primary btn-block btn-lg" onclick="status();"><i class="nc-icon nc-tag-content"></i> Cambiar de Status</button>
										<button type="button" class="btn btn-warning btn-block btn-lg" onclick = "newFalla(<?=$codigo?>);" title = "Reportar de Falla" ><i class="fa fa-exclamation-circle"></i>Agregar Falla</button>
										<input type="hidden" id="ticket" name="ticket" value="<?php echo $codigo; ?>" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Sede:</label>
										<span class="form-control text-info"><?php echo $sede; ?></span>
									</div>
									<div class="col-md-6">
										<label>Sector:</label>
										<span class="form-control text-info"><?php echo $sector; ?></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>&Aacute;rea:</label>
										<span class="form-control text-info"><?php echo $area; ?></span>
									</div>
									<div class="col-md-6">
										<label>Nivel:</label>
										<span class="form-control text-info"><?php echo $nivel; ?></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Categor&iacute;a:</label>
										<span class="form-control text-info"><?php echo $categoria; ?></span>
									</div>
									<div class="col-md-6">
										<label>Incidente:</label>
										<span class="form-control text-info"><?php echo $incidente; ?></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Prioridad:</label>
										<span class="form-control text-info"><?php echo $prioridad; ?></span>
									</div>
									<div class="col-md-3">
										<label>Respuesta Planificada:</label>
										<span class="form-control text-info"><?php echo $trespuesta; ?></span>
									</div>
									<div class="col-md-3">
										<label>Soluci&oacute;n Planificada:</label>
										<span class="form-control text-info"><?php echo $tsolucion; ?></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Tiempo en Espera o Hold On:</label>
										<span class="form-control text-success"><?php echo $Espera; ?></span>
									</div>
									<div class="col-md-3">
										<label>Tiempo Respuesta Efectiva:</label>
										<span class="form-control text-success"><?php echo $Respuesta; ?></span>
									</div>
									<div class="col-md-3">
										<label>Tiempo de Soluci&oacute;n Efectivo:</label>
										<span class="form-control text-success"><?php echo $Solucion; ?></span>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Descripci&oacute;n:</label>
										<textarea class="form-control" rows="4" readonly><?php echo $desc; ?></textarea>
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
														<a target="_blank" href="../../CONFIG/<?php echo $strFoto; ?>"><img src="../../CONFIG/<?php echo $strFoto; ?>" alt="..."></a>
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
									<input type="file" name="imagen" id="imagen" class="hidden" onchange="confirmStatusFoto()" />
									<input type="hidden" id="posicion" name="posicion" value="" />
									<input type="hidden" id="comentario" name="comentario" value="" />
								</div>
								<hr>
								<div class="row">
									<div class="col-md-9">
										<h5>Usuario(s) Asignados:</h5>
										<?php echo tabla_usuarios($codigo); ?>
									</div>
									<div class="col-md-3">
										<label>Usuarios a asignar:</label>
										<?php echo utf8_decode(usuarios_sedes_html("usuario", $sedes_IN, "", "select2")); ?>
										<button type="button" class="btn btn-primary btn-block btn-lg" onclick="agregar();"><i class="fa fa-link"></i> Agregar</button>
										<button type="button" class="btn btn-susccess btn-block btn-lg" onclick="trasladar();"><i class="fa fa-exchange"></i> Trasladar</button>
									</div>
								</div>
								<hr>
								<hr>
								<div class="row">
									<div class="col-md-12">
										<h5>Bitacora:</h5>
										<?php echo tabla_bitacora($codigo); ?>
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
				$('.select2').select2({ width: '100%' });
			});
		</script>

</body>

</html>