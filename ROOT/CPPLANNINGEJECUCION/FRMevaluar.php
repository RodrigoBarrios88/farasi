<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$usuario = $_SESSION["codigo"];
//$_POST
$ClsAcc = new ClsAccion();
$hashkey = $_REQUEST["hashkey"];
$codigo = $ClsAcc->decrypt($hashkey, $usuario);
//--
$result = $ClsAcc->get_programacion_aprobada($codigo);
if (is_array($result)) {
	foreach ($result as $row) {
		$proceso = utf8_decode($row["proceso_nombre"]);
		$sistema = utf8_decode($row["sistema_nombre"]);
		$usuario = utf8_decode($row["usuario_nombre"]);
		$nombre = utf8_decode($row["acc_nombre"]);
		$presupuesto = utf8_decode($row["acc_presupuesto"]);
		$ffin = cambia_fecha($row["rev_fecha_fin"]);
		$objetivo = utf8_decode($row["obj_descripcion"]);
		$accion = utf8_decode($row["acc_descripcion"]);
		$dini = cambia_fecha($row["pro_fecha_inicio"]);
		$dfin = cambia_fecha($row["pro_fecha_fin"]);
		$tipo = trim($row["acc_tipo"]);
		$programacion = trim($row["pro_codigo"]);
	}
}
$ClsEje = new ClsEjecucion();
$result = $ClsEje->get_documentos_ejecucion('', $programacion, 1);
if (is_array($result)) {
	foreach ($result as $row) {
		$docCodigo = trim($row["doc_codigo"]);
		$posicion = trim($row["doc_posicion"]);
		$strDoc = trim($row["doc_documento"]);
		if (file_exists('../../CONFIG/Archivos/ACCION/' . $strDoc . '.pdf') || $strDoc != "") {
			$strDoc = '<a href="../../CONFIG/Archivos/ACCION/' . $strDoc . '.pdf" target="_blank"><img class="img-responsive" src="../../CONFIG/img/document.png" alt="..."></a>';
		} else {
			$strDoc = '<i class="fa fa-file-o fa-8x"></i>';
		}
	}
} else {
	$strDoc = '<i class="fa fa-file-o fa-8x"></i>';
}
$result = $ClsEje->get_ejecucion_accion("", $programacion);
if (is_array($result)) {
	foreach ($result as $row) {
		$codigo = trim($row["eje_codigo"]);
		$observacion = utf8_decode($row["eje_observacion"]);
		$situacion = trim($row["eje_situacion"]);
	}
}

?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
	<link src="../assets.1.2.8/css/plugins/touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
	
</head>

<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../", "planning"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?> <div class="content">
				<div class="row">
					<div class="col-md-6">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="nc-icon nc-bullet-list-67"></i> Informaci&oacute;n
									<a class="btn btn-white btn-lg sin-margin pull-right" href="FRMevaluacion.php"><small><i class="fa fa-chevron-left"></i> Atr&aacute;s</small></a>
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
												<label>Fecha Ejecutada:</label>
												<input type="text" class="form-control" value="<?php echo $ffin; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Usuario que ejecut&oacute;:</label>
												<input type="text" class="form-control" value="<?php echo $usuario; ?>" disabled />
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
												<label>Ultimo D&iacute;a:</label>
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
												<textarea type="text" class="form-control  textarea-autosize" disabled /><?php echo $objetivo; ?></textarea>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Accion:</label>
												<textarea type="text" class="form-control  textarea-autosize"  disabled /> <?php echo $accion; ?></textarea>
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
									<div class="col-md-12 ml-auto mr-auto">
										<textarea disabled class="form-control textarea-autosize"><?php echo $observacion ?></textarea>
									</div>
								</div>
								<hr>
								<div class="row">
									<?php
									$result = $ClsEje->get_fotos_ejecucion('', $programacion);
									if (is_array($result)) {
										foreach ($result as $row) {
											$posicion = trim($row["fot_posicion"]);
											$strFoto = trim($row["fot_foto"]);
											if (file_exists('../../CONFIG/Fotos/ACCION/' . $strFoto . '.jpg') || $strFoto != "") {
												$strFoto = 'Fotos/ACCION/' . $strFoto . '.jpg';
											} else {
												$strFoto = "img/imagePhoto.jpg";
											}
									?>
											<div class="col-md-4 text-center">
												<div class="fileinput fileinput-new text-center" data-provides="fileinput">
													<div class="fileinput-new thumbnail">
														<a href="../../CONFIG/<?php echo $strFoto; ?>" target="_blank">
															<img src="../../CONFIG/<?php echo $strFoto; ?>" alt="...">
														</a>
													</div>
												</div>
											</div>
									<?php
										}
									} else {
										$strFoto = "img/imagePhoto.jpg";
									}
									?>
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
													<span class="fileinput-new" disabled><i class="fa fa-file-text"></i> Documento PDF</span>
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fas fa-clipboard-list"></i> &nbsp; Evaluar Ejecuci&oacute;n</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-12">
										<label>Observaciones:</label> <span class="text-danger">*</span>
										<textarea class="form-control textarea-autosize" name="observacion" id="observacion" onkeyup="textoLargo(this);" rows="5"></textarea>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Puntuaci&oacute;n:</label> <span class="text-danger">*</span>
										<input id="puntuacion" class="form-control input-sm" type="text" value="50" name="puntuacion">
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-lg-12 col-xs-12 text-center">
										<input type="hidden" id="codigo" name="codigo" value="<?php echo $codigo; ?>" />
										<a type="button" class="btn btn-default " href="FRMevaluacion.php"><span class="fa fa-chevron-left"></span> Regresar</a>
										<button type="button" class="btn btn-danger " id="btn-grabar" onclick="finalizarEvaluacion();"><span class="fa fa-check"></span> Finalizar</button>
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
	<!-- touchspin -->
	<script src="../assets.1.2.8/js/plugins/touchspin/jquery.bootstrap-touchspin.min.js"></script>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/planning/ejecucion.js"></script>
	<script>
		$(document).ready(function() {
			$("input[name='puntuacion']").TouchSpin({
				min: 0,
				max: 100,
				step: 1,
				boostat: 5,
				maxboostedstep: 10,
				postfix: 'Pts.'
			});
		});
	</script>
</body>

</html>