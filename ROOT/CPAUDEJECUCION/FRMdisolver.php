<?php
include_once('html_fns_ejecucion.php');
	validate_login("../");
$usuario = $_SESSION["codigo"];

$categoriasIn = $_SESSION["categorias_in"];
//$_POST
$ClsAud = new ClsAuditoria();
$ClsEje = new ClsEjecucion();
$hashkey = $_REQUEST["hashkey"];
$ejecucion = $ClsAud->decrypt($hashkey, $usuario);
//--
$result = $ClsEje->get_ejecucion($ejecucion, '', '');
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$ejecucion = trim($row["eje_codigo"]);
		$codigo_audit = trim($row["audit_codigo"]);
		$ponderacion_audit = trim($row["audit_ponderacion"]);
		$sede = utf8_decode($row["sed_nombre"]);
		$direccion = utf8_decode($row["sed_direccion"]) . ", " . utf8_decode($row["sede_municipio"]);
		$departamento = utf8_decode($row["dep_nombre"]);
		$categoria = utf8_decode($row["cat_nombre"]);
		$nombre = utf8_decode($row["audit_nombre"]);
		$usuario_nombre = utf8_decode($row["usuario_nombre"]);
		$strFirma = trim($row["eje_firma"]);
		//--
		$fecha_inicio = trim($row["eje_fecha_inicio"]);
		$fecha_inicio = cambia_fechaHora($fecha_inicio);
		$fecha_inicio = substr($fecha_inicio, 0, 16);
		//--
		$fecha_finaliza = trim($row["eje_fecha_final"]);
		$fecha_finaliza = cambia_fechaHora($fecha_finaliza);
		$fecha_finaliza = substr($fecha_finaliza, 0, 16);
		//--
		$fecha_progra = trim($row["pro_fecha"]);
		$fecha_progra = cambia_fecha($fecha_progra);
		$hora_progra = substr($row["pro_hora"], 0, 5);
		$fecha_progra = "$fecha_progra $hora_progra";
		$obs = utf8_decode($row["pro_observaciones"]);
		$responsable = utf8_decode($row["eje_responsable"]);
		$EjeObservacion = utf8_decode($row["eje_observaciones"]);
		//--
		$strFirma1 = trim($row["eje_firma_evaluador"]);
		$strFirma2 = trim($row["eje_firma_evaluado"]);
		$correos = trim(strtolower($row["eje_correos"]));
		$situacion = trim($row["eje_situacion"]);
		$nota = trim($row["eje_nota"]);
	}
}
if (file_exists('../../CONFIG/Fotos/AUDFIRMAS/' . $strFirma1 . '.jpg') && $strFirma1 != "") {
	$strFirma1 = 'Fotos/AUDFIRMAS/' . $strFirma1 . '.jpg';
} else {
	$strFirma1 = "img/imageSign.jpg";
}
if (file_exists('../../CONFIG/Fotos/AUDFIRMAS/' . $strFirma2 . '.jpg') && $strFirma2 != "") {
	$strFirma2 = 'Fotos/AUDFIRMAS/' . $strFirma2 . '.jpg';
} else {
	$strFirma2 = "img/imageSign.jpg";
}?>
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
												<label>Direcci&oacute;n:</label><br>
												<input type="text" class="form-control" value="<?php echo $direccion; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Departamento:</label>
												<input type="text" class="form-control" value="<?php echo $departamento; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Fecha y Hora de Programaci&oacute;n:</label><br>
												<input type="text" class="form-control" value="<?php echo $fecha_progra; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Responsable o Evaluado: </label>
												<input type="text" class="form-control" value="<?php echo $responsable; ?>" disabled />
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
									<a class="btn btn-white btn-lg sin-margin pull-right" href="CPREPORTES/REPrevision.php?ejecucion=<?php echo $ejecucion; ?>" target="_blank"><small><i class="fa fa-print"></i> Imprimir</small></a>
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12" id="result">
										<div class="row">
											<div class="col-md-12">
												<label>Categor&iacute;a:</label>
												<input type="text" class="form-control" value="<?php echo $categoria; ?>" disabled />
												<input type="hidden" id="ejecucion" name="ejecucion" value="<?php echo $ejecucion; ?>" />
												<input type="hidden" id="reqfoto" name="reqfoto" value="<?php echo $requiere_fotos; ?>" />
												<input type="hidden" id="reqfirma" name="reqfirma" value="<?php echo $requiere_firma; ?>" />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Cuestionario de Auditor&iacute;a:</label>
												<input type="text" class="form-control" value="<?php echo $nombre; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Fecha y Hora de Inicio:</label><br>
												<input type="text" class="form-control" value="<?php echo $fecha_inicio; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Fecha y Hora de Finalizaci&oacute;n:</label><br>
												<input type="text" class="form-control" value="<?php echo $fecha_finaliza; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Usuario que registr&oacute;:</label><br>
												<input type="text" class="form-control" value="<?php echo $usuario_nombre; ?>" disabled />
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
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-12">
										<label>Observaciones de Programaci&oacute;n:</label><br>
										<textarea class="form-control text-justify" rows="4" disabled><?php echo $obs; ?></textarea>
									</div>
								</div>
								<br>
							</div>
						</div>
					</div>
				</div>

				<?php
				$result_seccion = $ClsAud->get_secciones('', $codigo_audit, 1);
				if (is_array($result_seccion)) {
					$i = 1;
					foreach ($result_seccion as $row_seccion) {
						$seccion_codigo = $row_seccion["sec_codigo"];
						$titulo = trim($row_seccion["sec_numero"]) . ". " . utf8_decode($row_seccion["sec_titulo"]);
						$proposito = utf8_decode($row_seccion["sec_proposito"]);
						$proposito = nl2br($proposito);
				?>
						<div class="row">
							<div class="col-md-12">
								<div class="card demo-icons">
									<div class="card-header">
										<h5 class="card-title"><?php echo $titulo; ?></h5>
									</div>
									<div class="card-body all-icons">
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
												$observacion = '-';
												$aplicaActive = 'active';
												$aplica_desc = 'Aplica';
												$aplica = '';
												$result_respuesta = $ClsEje->get_respuesta($ejecucion, $codigo_audit, $pregunta_codigo);
												if (is_array($result_respuesta)) {
													foreach ($result_respuesta as $row_respuesta) {
														$aplica = utf8_decode($row_respuesta["resp_aplica"]);
														$respuesta = utf8_decode($row_respuesta["resp_respuesta"]);
														$observacion = utf8_decode($row_respuesta["resp_observacion"]);
														$observacion = nl2br($observacion);
													}
													$observacion = ($observacion == "") ? "-" : $observacion;
													$aplicaActive = ($aplica == 1) ? "active" : "";
													$aplica_desc = ($aplica == 1) ? '<i class="fa fa-check"></i> Aplica' : '<i class="fa fa-times"></i> No Aplica';
												}
												$salida = "";
												if ($pregunta_tipo == 1) {
													$salida = $respuesta;
												} else if ($pregunta_tipo == 2) {
													switch ($respuesta) {
														case 1:
															$elemento = 'SI - ' . $peso . ' pts.';
															break;
														case 2:
															$elemento = 'NO';
															break;
														default:
															$elemento = '-';
															break;
													}
													$salida = $elemento;
												} else if ($pregunta_tipo == 3) {
													switch ($respuesta) {
														case 1:
															$elemento = 'SATISFACTORIO';
															break;
														case 2:
															$elemento = 'NO SATISFACTORIO';
															break;
														default:
															$elemento = '-';
															break;
													}
													$salida = $elemento;
												}
												//---
												$usuario_disuelve = "-";
												$observacionDisolucion = "-";
												$result_disolucion = $ClsEje->get_disolucion_hallazgo($ejecucion, $codigo_audit, $pregunta_codigo);	
												if (is_array($result_disolucion)) {
													foreach ($result_disolucion as $row_disolucion) {
														$usuario_disuelve = utf8_decode($row_disolucion["dis_usuario_nombre"]);
														$observacionDisolucion = utf8_decode($row_disolucion["dis_observaciones"]);
														$fechor_disuelto = cambia_fechaHora($row_disolucion["dis_fecha_registro"]);
													}
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
																<label>Nota:</label>
																<p class="border border-dark p-2"><?php echo $salida; ?></p>
															</div>
															<div class="col-md-3 col-xs-6">
																<label>Peso:</label>
																<p class="border border-dark p-2"><?php echo $peso; ?></p>
															</div>
															<div class="col-md-3 col-xs-12 text-right">
																<label>.</label>
																<p class="border border-dark p-2 text-center"><strong><?php echo $aplica_desc; ?></strong></p>
															</div>
														</div>
														<div class="row">
															<div class="col-md-12">
																<label class="mt-2">Observaciones de disoluci&oacute;n de hallazgos:</label>
																<p class="border border-dark p-2 text-justify"><?php echo $observacion; ?></p>
															</div>
														</div>
													</div>
													<div class="col-md-5 col-xs-8 col-xs-offset-2">
														<div class="row">
															<div class="col-md-12 col-xs-12">
																
																<button type="button" class="btn btn-primary btn-block" onclick="seleccionarHallazgo(<?php echo $codigo_audit; ?>,<?php echo $pregunta_codigo; ?>,<?php echo $ejecucion; ?>);"><i class="fas fa-edit"></i> Disolver Hallazgo</button>
																
															</div>
														</div>
														<div class="row">
															<div class="col-md-6 col-xs-6">
																<label>Usuario:</label>
																<p class="border border-dark p-2" id="usuario<?php echo $pregunta_codigo; ?>"><?php echo $usuario_disuelve; ?></p>
															</div>
															<div class="col-md-6 col-xs-6">
																<label>Fecha/hora:</label>
																<p class="border border-dark p-2" id="fechor<?php echo $pregunta_codigo; ?>"><?php echo $fechor_disuelto; ?></p>
															</div>
														</div>
														<div class="row">
															<div class="col-md-12">
																<label class="mt-2">Observaciones o comentarios durante la auditor&iacute;a:</label>
																<p class="border border-dark p-2 text-justify" id="observacion<?php echo $pregunta_codigo; ?>"><?php echo $observacionDisolucion; ?></p>
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
				<?php
					}
				}
				?>

				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="nc-icon nc-briefcase-24"></i> Cierre del Formulario de Auditor&iacute;a</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-6 text-center">
										<div class="fileinput fileinput-new text-center" data-provides="fileinput">
											<div class="fileinput-new thumbnail">
												<img src="../../CONFIG/<?php echo $strFirma1; ?>" alt="...">
											</div>
										</div>
										<p>Firma Auditor(a)</p>
									</div>
									<div class="col-md-6 text-center">
										<div class="fileinput fileinput-new text-center" data-provides="fileinput">
											<div class="fileinput-new thumbnail">
												<img src="../../CONFIG/<?php echo $strFirma2; ?>" alt="...">
											</div>
										</div>
										<p>Firma Evaluado(a)</p>
									</div>
								</div>
								<div class="row">
									<div class="col-md-10 ml-auto mr-auto">
										<label>Correos de Notificaci&oacute;n: </label>
										<input type="text" class="form-control" value="<?php echo $correos; ?>" disabled />
									</div>
								</div>
								<div class="row">
									<div class="col-md-10 ml-auto mr-auto">
										<label>Responsable o Evaluado: </label>
										<input type="text" class="form-control" value="<?php echo $responsable; ?>" disabled />
									</div>
								</div>
								<div class="row">
									<div class="col-md-10 ml-auto mr-auto">
										<label>Observaciones:</label>
										<textarea class="form-control" rows="5" disabled><?php echo $EjeObservacion; ?></textarea>
									</div>
								</div>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/auditoria/disolucion.js"></script>

	<script>
		$(document).ready(function() {
			$('.select2').select2({ width: '100%' });
		});
	</script>

</body>
</html>