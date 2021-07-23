<?php
include_once('html_fns_revision.php');validate_login("../");
$usuario = $_SESSION["codigo"];

$categoriasIn = $_SESSION["categorias_in"];
//$_POST
$ClsLis = new ClsLista();
$ClsRev = new ClsRevision();
$hashkey = $_REQUEST["hashkey"];
$revision = $ClsLis->decrypt($hashkey, $usuario);
//--
$result = $ClsRev->get_revision($revision);
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$revision = trim($row["rev_codigo"]);
		$codigo_lista = trim($row["list_codigo"]);
		$codigo_progra = trim($row["pro_codigo"]);
		$sede = utf8_decode($row["sed_nombre"]);
		$sector = utf8_decode($row["sec_nombre"]);
		$area = utf8_decode($row["are_nombre"]);
		$nivel = utf8_decode($row["are_nivel"]);
		$categoria = utf8_decode($row["cat_nombre"]);
		$nombre = utf8_decode($row["list_nombre"]);
		$nombre_usuario = utf8_decode($row["usuario_nombre"]);
		//--
		$requiere_firma = trim($row["list_firma"]);
		$requiere_fotos = trim($row["list_fotos"]);
		$strFirma = trim($row["rev_firma"]);
		//--
		$fecha_inicio = trim($row["rev_fecha_inicio"]);
		$fecha_inicio = cambia_fechaHora($fecha_inicio);
		$fecha_inicio = substr($fecha_inicio, 0, 16);

		$fecha_finaliza = trim($row["rev_fecha_final"]);
		$fecha_finaliza = cambia_fechaHora($fecha_finaliza);
		$fecha_finaliza = substr($fecha_finaliza, 0, 16);
		$obs = utf8_decode($row["rev_observaciones"]);
		$obs = nl2br($obs);
		//
		$situacion = trim($row["rev_situacion"]);
	}
	if (file_exists('../../CONFIG/Fotos/FIRMAS/' . $strFirma . '.jpg') && $strFirma != "") {
		$strFirma = 'Fotos/FIRMAS/' . $strFirma . '.jpg';
	} else {
		$strFirma = "img/imageSign.jpg";
	}
	/////////// PROGRAMACION /////
	$dia = date("N");
	$result = $ClsLis->get_programacion($codigo_progra, $codigo_lista);
	if (is_array($result)) {
		$i = 0;
		foreach ($result as $row) {
			$hini = trim($row["pro_hini"]);
			$hfin = trim($row["pro_hfin"]);
			$horario = "$hini - $hfin";
		}
	}
}
$result = $ClsRev->get_fotos('', $revision);
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$strFoto = trim($row["fot_foto"]);
	}
	if (file_exists('../../CONFIG/Fotos/REVISION/' . $strFoto . '.jpg') && $strFoto != "") {
		$strFoto = 'Fotos/REVISION/' . $strFoto . '.jpg';
	} else {
		$strFoto = "img/imagePhoto.jpg";
	}
} else {
	$strFoto = "img/imagePhoto.jpg";
}
?>
	<!DOCTYPE html>
	<html>

	<head>
		<?php echo head("../"); ?>
	</head>

	<body class="">
		<div class="wrapper ">
			<?php echo sidebar("../", "checklist"); ?>
			<div class="main-panel">
				<?php echo navbar("../"); ?>
				<div class="content">
					<div class="row">
						<div class="col-md-6">
							<div class="card demo-icons">
								<div class="card-header">
									<h5 class="card-title">
										<i class="nc-icon nc-pin-3"></i> Ubicaci&oacute;n
										<button type="button" class="btn btn-white btn-lg sin-margin pull-right" onclick="window.history.back();"><small><i class="fa fa-chevron-left"></i> Atr&aacute;s</small></button>
									</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-xs-6 col-md-6 text-left"> </div>
									</div>
									<div class="row">
										<div class="col-lg-12" id="result">
											<div class="row">
												<div class="col-md-12">
													<label>Sede:</label>
													<input type="text" class="form-control" value="<?php echo $sede; ?>" disabled />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Sector:</label>
													<input type="text" class="form-control" value="<?php echo $sector; ?>" disabled />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>&Aacute;rea:</label><br>
													<input type="text" class="form-control" value="<?php echo $area; ?>" disabled />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Nivel:</label><br>
													<input type="text" class="form-control" value="<?php echo $nivel; ?>" disabled />
												</div>
											</div>
										</div>
									</div>
									<br>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="card demo-icons">
								<div class="card-header">
									<h5 class="card-title">
										<i class="nc-icon nc-bullet-list-67"></i> Informaci&oacute;n
										<a class="btn btn-white btn-lg sin-margin pull-right" href="CPREPORTES/REPrevision.php?hashkey=<?php echo $hashkey; ?>" target="_blank"><small><i class="fa fa-print"></i> Imprimir</small></a>
									</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-lg-12" id="result">
											<div class="row">
												<div class="col-md-12">
													<label>Categor&iacute;a:</label>
													<input type="text" class="form-control" value="<?php echo $categoria; ?>" disabled />
													<input type="hidden" id="revision" name="revision" value="<?php echo $revision; ?>" />
													<input type="hidden" id="reqfoto" name="reqfoto" value="<?php echo $requiere_fotos; ?>" />
													<input type="hidden" id="reqfirma" name="reqfirma" value="<?php echo $requiere_firma; ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Lista:</label>
													<input type="text" class="form-control" value="<?php echo $nombre; ?>" disabled />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Horario de Ejecuci&oacute;n:</label><br>
													<input type="text" class="form-control" value="<?php echo $horario; ?>" disabled />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Usuario que Ejecut&oacute;:</label><br>
													<input type="text" class="form-control" value="<?php echo $nombre_usuario; ?>" disabled />
												</div>
											</div>
										</div>
									</div>
									<br>
								</div>
							</div>
						</div>
					</div>

					<?php if ($situacion == 1) { ?>
						<div class="row">
							<div class="col-md-12">
								<h5 class="alert alert-info text-center">
									<i class="fa fa-info-circle"></i> Lista en proceso (abierta) desde <?php echo $fecha_inicio; ?>...
								</h5>
							</div>
						</div>
					<?php } ?>

					<div class="row">
						<div class="col-md-12">
							<div class="card demo-icons">
								<div class="card-header">
									<h5 class="card-title">
										<i class="nc-icon nc-pin-3"></i> Revisi&oacute;n No. <?php echo Agrega_Ceros($revision); ?> de Fecha <?php echo $fecha_finaliza; ?>
									</h5>
								</div>
								<div class="card-body all-icons">
									<br>
									<?php
									$result = $ClsLis->get_pregunta('', $codigo_lista, '', 1);
									if (is_array($result)) {
										$i = 1;
										foreach ($result as $row) {
											$pregunta_codigo = $row["pre_codigo"];
											$pregunta = utf8_decode($row["pre_pregunta"]);
											$pregunta = nl2br($pregunta);
											//--
											$respuesta = "";
											$result_respuesta = $ClsRev->get_respuesta($revision, $codigo_lista, $pregunta_codigo);
											if (is_array($result_respuesta)) {
												foreach ($result_respuesta as $row_respuesta) {
													$respuesta = utf8_decode($row_respuesta["resp_respuesta"]);
												}
											}
											if ($respuesta == 1) {
												$respSI = "active";
												$respNO = "";
												$respNA = "";
											} else if ($respuesta == 2) {
												$respSI = "";
												$respNO = "active";
												$respNA = "";
											} else if ($respuesta == 3) {
												$respSI = "";
												$respNO = "";
												$respNA = "active";
											} else {
												$respSI = "";
												$respNO = "";
												$respNA = "";
											}
											$salida = ""; ///limpia la cadena por cada vuelta
											$salida .= '<div class="btn-group btn-group-toggle">';
											$salida .= '<label class="btn btn-white ' . $respSI . '">';
											$salida .= ' <i class="fa fa-check"></i> Si';
											$salida .= '</label>';
											//--
											$salida .= '<label class="btn btn-white ' . $respNA . '" onclick="responder(' . $revision . ',' . $codigo_lista . ',' . $pregunta_codigo . ',3);">';
											$salida .= '<input type="radio" name="options" id="optNA' . $i . '" autocomplete="off"> No Aplica';
											$salida .= '</label>';
											//--
											$salida .= '<label class="btn btn-white ' . $respNO . '">';
											$salida .= ' No <i class="fa fa-times"></i>';
											$salida .= '</label>';
											$salida .= '</div>';

									?>
											<div class="row">
												<div class="col-md-1 text-right"><strong><?php echo $i; ?>.</strong></div>
												<div class="col-md-10">
													<p class="text-justify"><?php echo $pregunta; ?></p>
												</div>
											</div>
											<div class="row">
												<div class="col-md-10 ml-auto mr-auto"><?php echo $salida; ?></div>
											</div>
											<br>
									<?php
											$i++;
										}
									} else {
									}
									?>
									<hr>
									<div class="row">
										<div class="col-md-10 ml-auto mr-auto">
											<label>Observaciones:</label>
											<textarea class="form-control" rows="4" readonly><?php echo $obs; ?></textarea>
										</div>
									</div>
									<br>
									<?php
									if ($requiere_firma > 0 ||  $requiere_fotos > 0) {
									?>
										<div class="row">
											<?php
											if ($requiere_firma > 0) {
											?>
												<div class="col-md-6 text-center">
													<div class="fileinput fileinput-new text-center" data-provides="fileinput">
														<div class="fileinput-new thumbnail">
															<img src="../../CONFIG/<?php echo $strFirma; ?>" alt="...">
														</div>
													</div>
													<p>Firma</p>
												</div>
											<?php
											} else {
												echo '<div class="col-md-6"></div>';
											}
											?>
											<?php
											if ($requiere_fotos > 0) {
											?>
												<div class="col-md-6 text-center">
													<div class="fileinput fileinput-new text-center" data-provides="fileinput">
														<div class="fileinput-new thumbnail">
															<img src="../../CONFIG/<?php echo $strFoto; ?>" alt="...">
														</div>
													</div>
													<p>Foto</p>
												</div>
											<?php
											} else {
												echo '<div class="col-md-6"></div>';
											}
											?>
										</div>
									<?php
									}
									?>
									<br>
									<div class="row">
										<div class="col-md-6 ml-auto mr-auto text-center">
											<button type="button" class="btn btn-default btn-lg" onclick="window.history.back();"><span class="fa fa-chevron-left"></span> Regresar</button>
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

		<script type="text/javascript" src="../assets.1.2.8/js/modules/checklist/revision.js"></script>
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
			});
		</script>

	</body>

	</html>
