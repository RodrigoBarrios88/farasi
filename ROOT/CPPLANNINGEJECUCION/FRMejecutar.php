<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$usuario = $_SESSION["codigo"];
//$_POST
$ClsEje = new ClsEjecucion();
$ClsAcc = new ClsAccion();
$hashkey = $_REQUEST["hashkey"];
$programacion = $ClsAcc->decrypt($hashkey, $usuario);
//--
$result = $ClsAcc->get_programacion_aprobada($programacion);
if (is_array($result)) {
	foreach ($result as $row) {
		$proceso = utf8_decode($row["proceso_nombre"]);
		$sistema = utf8_decode($row["sistema_nombre"]);
		$tipo = trim($row["acc_tipo"]);
		if ($tipo == "W") $tipo = "Semanal";
		else if ($tipo == "M") $tipo = "Mensual";
		else if ($tipo == "U") $tipo = "Unica";
		$presupuesto = utf8_decode($row["acc_presupuesto"]);
		$fini = cambia_fecha($row["acc_fecha_inicio"]);
		$ffin = cambia_fecha($row["acc_fecha_fin"]);
		$objetivo = utf8_decode($row["obj_descripcion"]);
		$accion = utf8_decode($row["acc_descripcion"]);
		$nombre = utf8_decode($row["acc_nombre"]);
		$dini = cambia_fecha($row["pro_fecha_inicio"]);
		$dfin = cambia_fecha($row["pro_fecha_fin"]);
		$codigo = trim($row["pro_codigo"]);
	}
}
// crear la ejecucion si se abre por primera vez
$result = $ClsEje->get_ejecucion_accion("", $programacion);
if (!is_array($result)) {
	$ejecucion_codigo = $ClsEje->max_ejecucion_accion();
	$ejecucion_codigo++;
	$sql = $ClsEje->insert_ejecucion_accion($ejecucion_codigo, $programacion, "");
	$rs = $ClsEje->exec_sql($sql);
} else {
	$ejecucion_codigo = $result[0]["eje_codigo"];
	$situacion = $result[0]["eje_situacion"];
	$observacion = utf8_decode($result[0]["eje_observacion"]);
}

$numeroImagenes = 3;
$evidencia = false;
for ($i = 1; $i <= $numeroImagenes; $i++) {
	$result = $ClsEje->get_fotos_ejecucion('', $codigo, $i);
	if (is_array($result)) {
		foreach ($result as $row) {
			$fotCodigo = trim($row["fot_codigo"]);
			$posicion = trim($row["fot_posicion"]);
			$strFoto = trim($row["fot_foto"]);
			if (file_exists('../../CONFIG/Fotos/ACCION/' . $strFoto . '.jpg') || $strFoto != "") {
				$strFoto = '<img  class="img-upload" src="../../CONFIG/Fotos/ACCION/' . $strFoto . '.jpg" alt="...">';
				$evidencia = true;
			} else {
				$strFoto = '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
			}
		}
	} else {
		$strFoto = '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
	}
	$imagenes[$i] = $strFoto;
}
$result = $ClsEje->get_documentos_ejecucion('', $codigo, 1);
if (is_array($result)) {
	foreach ($result as $row) {
		$docCodigo = trim($row["doc_codigo"]);
		$posicion = trim($row["doc_posicion"]);
		$strDoc = trim($row["doc_documento"]);
		if (file_exists('../../CONFIG/Archivos/ACCION/' . $strDoc . '.pdf') || $strDoc != "") {
			$strDoc = '<img class="img-responsive" src="../../CONFIG/img/document.png" alt="...">';
			$evidencia = true;
		} else {
			$strDoc = '<i class="fa fa-file-o fa-8x"></i>';
		}
	}
} else {
	$strDoc = '<i class="fa fa-file-o fa-8x"></i>';
}

if ($situacion == 2) {
	echo "<form id='f1' name='f1' action='FRMejecucion.php' method='post'>";
	echo "<script>document.f1.submit();</script>";
	echo "</form>";
}

?>
	<!DOCTYPE html>
	<html>

	<head>
		<?php echo head("../"); ?>
		<style>
			.img-upload {
				width: 30%;
				margin: 1px;
				cursor: pointer;
			}

			.img-demo {
				width: 50%;
			}
		</style>
		<script type='text/javascript'>
			function mensaje() {
				<?php echo $alerta_completa; ?>
			}
		</script>
	</head>

	<body class="">
		<div class="wrapper ">
			<?php echo sidebar("../","planning"); ?>
			<div class="main-panel">
				<?php echo navbar("../"); ?> <div class="content">
					<div class="row">
						<div class="col-md-6">
							<div class="card demo-icons">
								<div class="card-header">
									<h5 class="card-title">
										<i class="nc-icon nc-bullet-list-67"></i> Informaci&oacute;n
										<a class="btn btn-white btn-lg sin-margin pull-right" href="FRMejecucion.php"><small><i class="fa fa-chevron-left"></i> Atr&aacute;s</small></a>
									</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-lg-12" id="result">
											<div class="row">
												<div class="col-md-12">
													<label>Proceso:</label>
													<input type="text" class="form-control" value="<?php echo $proceso; ?>" disabled />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Sistema:</label>
													<input type="text" class="form-control" value="<?php echo $sistema; ?>" disabled />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Accion:</label>
													<input type="text" class="form-control" value="<?php echo $nombre; ?>" disabled />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Presupuesto:</label>
													<input type="text" class="form-control" value="<?php echo $presupuesto; ?>" disabled />
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
										<i class="fa fa-clock"></i> Planificaci&oacute;n
									</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-lg-12" id="result">
											<div class="row">
												<div class="col-md-12">
													<label>Vigencia de Acci&oacute;n:</label>
													<input type="text" class="form-control" value="<?php echo $fini . " - " . $ffin; ?>" disabled />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Periodicidad:</label>
													<input type="text" class="form-control" value="<?php echo $tipo; ?>" disabled />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>D&iacute;a Planificado:</label>
													<input type="text" class="form-control" value="<?php echo $dini; ?>" disabled />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>&Uacute;ltimo D&iacute;a:</label>
													<input type="text" class="form-control" value="<?php echo $dfin; ?>" disabled />
												</div>
											</div>
										</div>
									</div>
									<br>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card demo-icons">
								<div class="card-header">
									<h5 class="card-title">
										<i class="nc-icon nc-bullet-list-67"></i> Descripci&oacute;n
									</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-lg-12" id="result">
											<div class="row">
												<div class="col-md-12">
													<label>Objetivo:</label>
													<textarea type="text" class="form-control" rows="5" disabled /><?php echo $objetivo; ?></textarea>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Accion:</label>
													<textarea type="text" class="form-control" rows="5" disabled /> <?php echo $accion; ?></textarea>
												</div>
											</div>
										</div>
									</div>
									<br>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card demo-icons">
								<div class="card-header">
									<h5 class="card-title"><i class="fas fa-pen-nib"></i> Ejecuci&oacute;n</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-xs-12 col-md-12 text-right"><label class="text-danger">* Campos Obligatorios (Al menos 1 evidencia)</label> </div>
									</div>
									<div class="row" id="imagenes">
										<?php for ($i = 1; $i <= $numeroImagenes; $i++) { ?>
											<div class="col-md-4 text-center">
												<div class="fileinput fileinput-new text-center" data-provides="fileinput">
													<div class="fileinput fileinput-new text-center" data-provides="fileinput">
														<div class="text-center" id="foto<?php echo $i ?>">
															<?php echo $imagenes[$i]; ?>
														</div>
														<span class="btn btn-rose btn-round btn-file">
															<span class="fileinput-new" onclick="FotoJs(<?php echo $codigo; ?>,<?php echo $i; ?>);">
																<i class="fa fa-camera"></i> Agregar Foto <?php echo $i; ?>
															</span>
														</span>
													</div>
												</div>
											</div>
										<?php } ?>
									</div>
									<hr>
									<div class="row">
										<div class="col-md-12 text-center">
											<div class="fileinput fileinput-new text-center" data-provides="fileinput">
												<div class="fileinput fileinput-new text-center" data-provides="fileinput">
													<div class="text-center" id="documento1">
														<?php echo $strDoc; ?>
													</div>
													<span class="btn btn-rose btn-round btn-file">
														<span class="fileinput-new" onclick="DocumentoJs(<?php echo $codigo; ?>);"><i class="fa fa-file-text"></i> Agregar Documento PDF</span>
													</span>
												</div>
											</div>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-md-10 ml-auto mr-auto">
											<label>Observaciones:</label> <span class="text-danger">*</span>
											<textarea class="form-control" name="observacion" id="observacion" onblur="Modificar();" onkeyup="textoLargo(this);" rows="5"><?php echo $observacion ?></textarea>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-md-6 ml-auto mr-auto text-center">
											<input id="documento" name="documento" type="file" multiple="false" class="hidden" onchange="uploadDocumento();">
											<input id="imagen" name="imagen" type="file" multiple="false" class="hidden" onchange="uploadImage();">
											<input type="hidden" id="codigo" name="codigo" value="<?php echo $codigo; ?>" />
											<input type="hidden" id="ejecucion" name="ejecucion" value="<?php echo $ejecucion_codigo; ?>" />
											<input type="hidden" id="posicion" name="posicion" />
											<a type="button" class="btn btn-default btn-lg" href="FRMejecucion.php"><span class="fa fa-chevron-left"></span> Regresar</a>
											<button type="button" class="btn btn-danger btn-lg" id="btn-grabar" onclick="finalizarEjecucion(<?php echo $evidencia; ?>);"><span class="fa fa-check"></span> Finalizar</button>
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

		<script type="text/javascript" src="../assets.1.2.8/js/modules/planning/ejecucion.js"></script>
		<script>
			$(document).ready(function() {
				$('.dataTables-example').DataTable({
					pageLength: 100,
					responsive: true,
					dom: '<"html5buttons"B>lTfgitp',
					buttons: []
				});
				$('.input-group.date').datepicker({
					format: 'dd/mm/yyyy',
					keyboardNavigation: false,
					forceParse: false,
					calendarWeeks: true,
					autoclose: true
				});
				$('.select2').select2({ width: '100%' });
			});
		</script>

	</body>

	</html>
