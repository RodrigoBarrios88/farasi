<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$usuario = $_SESSION["codigo"];

$categoriasIn = $_SESSION["categorias_in"];
//$_POST
$ClsAud = new ClsAuditoria();
$ClsEje = new ClsEjecucion();
$seccion = $_REQUEST["seccion"];
$hashkey1 = $_REQUEST["hashkey1"];
$hashkey2 = $_REQUEST["hashkey2"];
$hashkey3 = $_REQUEST["hashkey3"];
$codigo_audit = $ClsAud->decrypt($hashkey1, $usuario);
$codigo_progra = $ClsAud->decrypt($hashkey2, $usuario);
$ejecucion = $ClsAud->decrypt($hashkey3, $usuario);
$result = $ClsAud->get_secciones($seccion, $codigo_audit, 1);
if (is_array($result)) {
	$i = 1;
	foreach ($result as $row) {
		$seccion_codigo = $row["sec_codigo"];
		$titulo = trim($row["sec_numero"]) . ". " . utf8_decode($row["sec_titulo"]);
		$proposito = utf8_decode($row["sec_proposito"]);
		$proposito = nl2br($proposito);
	}
}
$result = $ClsEje->get_ejecucion($ejecucion);
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$ejecucion = trim($row["eje_codigo"]);
		$codigo_audit = trim($row["eje_auditoria"]);
		$codigo_progra = trim($row["eje_programacion"]);
		$sede = utf8_decode($row["sed_nombre"]);
		$direccion = utf8_decode($row["sed_direccion"]) . ", " . utf8_decode($row["sede_municipio"]);
		$departamento = utf8_decode($row["dep_nombre"]);
		$categoria = utf8_decode($row["cat_nombre"]);
		$nombre = utf8_decode($row["audit_nombre"]);
		//
		$responsable = trim($row["eje_responsable"]);
		$EjeObservacion = trim($row["eje_observaciones"]);
		$EjeObservacion = utf8_decode($row["eje_observaciones"]);
	}
}

?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
	<script type='text/javascript'>
		function mensaje() {
			<?php echo $alerta_completa; ?>
		}
		window.setTimeout('mensaje(<?php echo $status; ?>);', 500);
	</script>
	
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
								<h5 class="card-title"><?php echo $titulo; ?></h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-6 ml-auto mr-auto text-center">
										<a class="btn btn-white btn-lg" href="FRMcuestionario.php?hashkey1=<?php echo $hashkey1; ?>&hashkey2=<?php echo $hashkey2; ?>&hashkey3=<?php echo $hashkey3; ?>"><i class="fa fa-chevron-left"></i> Regresar</a>
									</div>
								</div>
								<br>
								<?php
								$result = $ClsAud->get_pregunta('', $codigo_audit, $seccion_codigo, 1);
								if (is_array($result)) {
									$i = 1;
									foreach ($result as $row) {
										$pregunta_codigo = $row["pre_codigo"];
										$pregunta_tipo = $row["pre_tipo"];
										$peso = $row["pre_peso"];
										$pregunta = utf8_decode($row["pre_pregunta"]);
										$pregunta = nl2br($pregunta);
										//--
										$respuesta = '0';
										$observacion = '';
										$aplicaActive = 'active';
										$aplica_desc = 'Aplica';
										$aplica = '';
										$disabled = '';
										$result_respuesta = $ClsEje->get_respuesta($ejecucion, $codigo_audit, $pregunta_codigo);
										if (is_array($result_respuesta)) {
											foreach ($result_respuesta as $row_respuesta) {
												$aplica = utf8_decode($row_respuesta["resp_aplica"]);
												$respuesta = utf8_decode($row_respuesta["resp_respuesta"]);
												$observacion = utf8_decode($row_respuesta["resp_observacion"]);
											}
											$aplicaActive = ($aplica == 1) ? "active" : "";
											$disabled = ($aplica == 1) ? "" : "disabled";
											$aplica_desc = ($aplica == 1) ? "Aplica" : "No Aplica";
										}
										$salida = "";
										if ($pregunta_tipo == 1) {
											$salida .= '<div class="form-group">';
											$salida .= '<select class ="form-control" name ="combo' . $pregunta_codigo . '" id ="combo' . $pregunta_codigo . '" onchange = "responderPonderacion(\'' . $codigo_audit . '\',\'' . $pregunta_codigo . '\',\'' . $ejecucion . '\',\'' . $seccion_codigo . '\',1,this.value);" >';
											$salida .= '<option value="0">Seleccione</option>';
											$salida .= '<option value="1">1</option>';
											$salida .= '<option value="2">2</option>';
											$salida .= '<option value="3">3</option>';
											$salida .= '<option value="4">4</option>';
											$salida .= '<option value="5">5</option>';
											$salida .= '<option value="6">6</option>';
											$salida .= '<option value="7">7</option>';
											$salida .= '<option value="8">8</option>';
											$salida .= '<option value="9">9</option>';
											$salida .= '<option value="10">10</option>';
											$salida .= '</select>';
											$salida .= '</div>';
											$salida .= '<script>';
											$salida .= 'document.getElementById("combo' . $pregunta_codigo . '").value = "' . $respuesta . '";';
											if ($aplica == 2) { // si no aplica deshabilita
												$salida .= 'document.getElementById("combo' . $pregunta_codigo . '").setAttribute("disabled", "disabled");';
											}
											$salida .= '</script>';
										} else if ($pregunta_tipo == 2) {
											if ($respuesta == 1) {
												$respSI = "active";
												$respNO = "";
											} else if ($respuesta == 2) {
												$respSI = "";
												$respNO = "active";
											} else {
												$respSI = "";
												$respNO = "";
											}

											$salida = ""; ///limpia la cadena por cada vuelta
											$salida .= '<div class="btn-group btn-group-toggle" data-toggle="buttons" >';
											$salida .= '<label class="btn btn-white ' . $respSI . '" id="labelSI' . $pregunta_codigo . '" ' . $disabled . ' onclick="responderPonderacion(\'' . $codigo_audit . '\',\'' . $pregunta_codigo . '\',\'' . $ejecucion . '\',\'' . $seccion_codigo . '\',2,1);">';
											$salida .= '<input type="radio" name="options" id="optSI' . $pregunta_codigo . '" autocomplete="off"> <i class="fa fa-check"></i> Si';
											$salida .= '</label>';
											//--
											$salida .= '<label class="btn btn-white ' . $respNO . '" id="labelNO' . $pregunta_codigo . '" ' . $disabled . ' onclick="responderPonderacion(\'' . $codigo_audit . '\',\'' . $pregunta_codigo . '\',\'' . $ejecucion . '\',\'' . $seccion_codigo . '\',2,2);">';
											$salida .= '<input type="radio" name="options" id="optNO' . $pregunta_codigo . '" autocomplete="off"> No <i class="fa fa-times"></i>';
											$salida .= '</label>';
											$salida .= '</div>';
										} else if ($pregunta_tipo == 3) {
											if ($respuesta == 1) {
												$respSI = "active";
												$respNO = "";
											} else if ($respuesta == 2) {
												$respSI = "";
												$respNO = "active";
											} else {
												$respSI = "";
												$respNO = "";
											}
											$salida = ""; ///limpia la cadena por cada vuelta
											$salida .= '<div class="btn-group btn-group-toggle" data-toggle="buttons" >';
											$salida .= '<label class="btn btn-white ' . $respSI . '" id="labelSI' . $pregunta_codigo . '" ' . $disabled . ' onclick="responderPonderacion(\'' . $codigo_audit . '\',\'' . $pregunta_codigo . '\',\'' . $ejecucion . '\',\'' . $seccion_codigo . '\',3,1);">';
											$salida .= '<input type="radio" name="options" id="optSI' . $pregunta_codigo . '" autocomplete="off"> <i class="fa fa-check"></i> SAT';
											$salida .= '</label>';
											//--
											$salida .= '<label class="btn btn-white ' . $respNO . '" id="labelNO' . $pregunta_codigo . '" ' . $disabled . ' onclick="responderPonderacion(\'' . $codigo_audit . '\',\'' . $pregunta_codigo . '\',\'' . $ejecucion . '\',\'' . $seccion_codigo . '\',3,2);">';
											$salida .= '<input type="radio" name="options" id="optNO' . $pregunta_codigo . '" autocomplete="off"> NO SAT <i class="fa fa-times"></i>';
											$salida .= '</label>';
											$salida .= '</div>';
										}

										$result = $ClsEje->get_fotos('', $ejecucion, $codigo_audit, $pregunta_codigo);
										$strFoto = "";
										$foto = "";
										if (is_array($result)) {
											foreach ($result as $row) {
												$fotCodigo = trim($row["fot_codigo"]);
												$foto = trim($row["fot_foto"]);
												if (file_exists('../../CONFIG/Fotos/AUDITORIA/' . $foto . '.jpg') || $foto != "") {
													$strFoto .= '<img onclick="menuFoto(' . $fotCodigo . ',' . $codigo_audit . ',' . $pregunta_codigo . ',' . $ejecucion . ');" class="img-upload" src="../../CONFIG/Fotos/AUDITORIA/' . $foto . '.jpg" alt="...">';
												} else {
													$strFoto .= '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="...">';
												}
											}
										} else {
											$strFoto = '<img class="img-demo" src="../../CONFIG/img/imagePhoto.jpg" alt="...">';
										}
								?>
										<div class="row">
											<div class="col-xs-2 col-md-1 text-center"><strong><?php echo $i; ?>.</strong></div>
											<div class="col-xs-10 col-md-10">
												<p class="text-justify"><?php echo $pregunta . ""; ?></p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-1 col-xs-1"></div>
											<div class="col-md-6 col-xs-11 text-left">
												<div class="row">
													<div class="col-md-6 col-xs-6">
														<?php echo $salida; ?>
														<input type="hidden" name="respuesta<?php echo $pregunta_codigo; ?>" id="respuesta<?php echo $pregunta_codigo; ?>" value="<?php echo $respuesta; ?>" />
														<input type="hidden" name="peso<?php echo $pregunta_codigo; ?>" id="peso<?php echo $pregunta_codigo; ?>" value="<?php echo $peso; ?>" />
													</div>
													<div class="col-md-3 col-xs-3"><label>Peso:</label> <strong><?php echo $peso; ?></strong></div>
													<div class="col-md-3 col-xs-3 text-right">
														<div data-toggle="buttons-checkbox" class="btn-group">
															<button class="btn btn-warning <?php echo $aplicaActive; ?>" id="aplica<?php echo $pregunta_codigo; ?>" onkeyup="texto(this)" onclick="responderAplica('<?php echo $codigo_audit; ?>','<?php echo $pregunta_codigo; ?>','<?php echo $ejecucion; ?>','<?php echo $seccion_codigo; ?>','<?php echo $pregunta_tipo; ?>');" type="button">
																<?php echo $aplica_desc; ?>
															</button>
														</div>
													</div>
												</div>
												<br>
												<div class="row">
													<div class="col-md-12 col-xs-12">
														<textarea class="form-control" name="observacion<?php echo $pregunta_codigo; ?>" id="observacion<?php echo $pregunta_codigo; ?>" onkeyup="texto(this)" onblur="responderTexto('<?php echo $codigo_audit; ?>','<?php echo $pregunta_codigo; ?>','<?php echo $ejecucion; ?>','<?php echo $seccion_codigo; ?>',this.value);" <?php echo $disabled; ?>><?php echo $observacion; ?></textarea>
														<br>
													</div>
												</div>
											</div>
											<div class="col-md-5 col-xs-8 col-xs-offset-2 text-center">
												<div class="fileinput fileinput-new text-center" data-provides="fileinput">
													<div class="fileinput fileinput-new text-center" data-provides="fileinput">
														<div class="text-center" id="foto<?php echo $pregunta_codigo; ?>">
															<?php echo $strFoto; ?>
														</div>
														<span class="btn btn-rose btn-round btn-file">
															<span class="fileinput-new" onclick="FotoJs(<?php echo $pregunta_codigo; ?>);"><i class="fa fa-camera"></i> Agregar Imagen</span>
														</span>
													</div>
												</div>
											</div>
										</div>
										<br>
								<?php
										$i++;
									}
								} else {
								}
								?>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-body all-icons">
								<div class="row">
									<form action="EXEcarga_foto.php" name="f1" name="f1" method="post" enctype="multipart/form-data">
										<input id="imagen" name="imagen" type="file" multiple="false" class="hidden" onchange="uploadImage();">
										<input type="hidden" id="ejecucion" name="ejecucion" value="<?php echo $ejecucion; ?>" />
										<input type="hidden" id="auditoria" name="auditoria" value="<?php echo $codigo_audit; ?>" />
										<input type="hidden" id="programacion" name="programacion" value="<?php echo $codigo_progra; ?>" />
										<input type="hidden" id="pregunta" name="pregunta" />
									</form>
								</div>
								<div class="row">
									<div class="col-md-6 ml-auto mr-auto text-center">
										<a class="btn btn-white btn-lg" href="FRMcuestionario.php?hashkey1=<?php echo $hashkey1; ?>&hashkey2=<?php echo $hashkey2; ?>&hashkey3=<?php echo $hashkey3; ?>"><i class="fa fa-chevron-left"></i> Regresar</a>
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
		});
	</script>

</body>
</html>