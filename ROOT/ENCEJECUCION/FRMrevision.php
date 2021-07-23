<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$usuario = $_SESSION["codigo"];

$categoriasIn = $_SESSION["categorias_in"];
//$_POST
$ClsEnc = new ClsEncuesta();
$ClsRes = new ClsEncuestaResolucion();
$hashkey = $_REQUEST["hashkey"];
$ejecucion = $ClsEnc->decrypt($hashkey, $usuario);
//--
$result = $ClsRes->get_ejecucion($ejecucion, '', '');
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$ejecucion = trim($row["eje_codigo"]);
		$codigo_cuestionario = trim($row["eje_encuesta"]);
		$codigo_invitacion = trim($row["eje_invitacion"]);
		$cliente = utf8_decode($row["inv_cliente"]);
		$correo = utf8_decode($row["inv_correo"]);
		$categoria = utf8_decode($row["cat_nombre"]);
		$titulo = utf8_decode($row["cue_titulo"]);
		$descripcion = utf8_decode($row["cue_descripcion"]);
		$descripcion = nl2br($descripcion);
		$ip = trim($row["eje_ip"]);
		$region = trim($row["eje_region"]);
		$ciudad = trim($row["eje_ciudad"]);
		//--
		$fecha_inicio = trim($row["eje_fecha_inicio"]);
		$fecha_inicio = cambia_fechaHora($fecha_inicio);
		$fecha_inicio = substr($fecha_inicio, 0, 16);
		//--
		$fecha_finaliza = trim($row["eje_fecha_final"]);
		$fecha_finaliza = cambia_fechaHora($fecha_finaliza);
		$fecha_finaliza = substr($fecha_finaliza, 0, 16);
		//--
		$fecha_invitacion = trim($row["inv_fecha_registro"]);
		$fecha_invitacion = cambia_fechaHora($fecha_invitacion);
		$fecha_invitacion = substr($fecha_invitacion, 0, 16);
		$obs = utf8_decode($row["inv_observaciones"]);
		$usuario_nombre = utf8_decode($row["usuario_nombre"]);
		//--
		$respondio = trim($row["eje_respondio"]);
		$EjeCorreo = trim($row["eje_correo"]);
		$EjeTelefono = trim($row["eje_telefono"]);
		$EjeObservacion = utf8_decode($row["eje_observaciones"]);
		$EjeObservacion = nl2br($EjeObservacion);
		$EjeObservacion = ($EjeObservacion == "") ? "<small>No hay comentarios...</small>" : $EjeObservacion;
		//--
		$situacion = trim($row["eje_situacion"]);
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
		<?php echo sidebar("../", "encuestas"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<?php if ($situacion == 1) { ?>
					<div class="row">
						<div class="col-md-12">
							<h5 class="alert alert-info text-center">
								<i class="fa fa-info-circle"></i> Cuestionario en proceso (abierto) desde <?php echo $fecha_inicio; ?>...
							</h5>
						</div>
					</div>
				<?php } else { ?>
					<div class="row">
						<div class="col-md-12">
							<h5 class="alert alert-success text-center">
								<i class="fa fa-check-circle"></i> Cuestionario Finalizado desde <?php echo $fecha_finaliza; ?>
							</h5>
						</div>
					</div>
				<?php } ?>

				<div class="row">
					<div class="col-md-6">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fas fa-clipboard-list"></i> <?php echo $titulo; ?></h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-xs-6 col-md-6 text-left"> </div>
								</div>
								<div class="row">
									<div class="col-lg-12" id="result">
										<div class="row">
											<div class="col-md-12">
												<label>Cuestionario #:</label>
												<input type="text" class="form-control" value="<?php echo Agrega_Ceros($codigo_cuestionario); ?>" readonly />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Invitaci&oacute;n #:</label>
												<input type="text" class="form-control" value="<?php echo Agrega_Ceros($codigo_invitacion); ?>" readonly />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Cliente:</label>
												<input type="text" class="form-control" value="<?php echo $cliente; ?>" readonly />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Correo:</label><br>
												<input type="text" class="form-control" value="<?php echo $correo; ?>" readonly />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Fecha y Hora de generaci&oacute;n:</label><br>
												<input type="text" class="form-control" value="<?php echo $fecha_invitacion; ?>" readonly />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Generada por:</label>
												<input type="text" class="form-control" value="<?php echo $usuario_nombre; ?>" readonly />
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
								<h5 class="card-title"><i class="fas fa-tags"></i> <?php echo $categoria; ?></h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12" id="result">
										<div class="row">
											<div class="col-md-12">
												<label>Persona que respondi&oacute;:</label><br>
												<input type="text" class="form-control" value="<?php echo $respondio; ?>" readonly />
												<input type="hidden" id="ejecucion" name="ejecucion" value="<?php echo $ejecucion; ?>" />
												<input type="hidden" id="reqfoto" name="reqfoto" value="<?php echo $requiere_fotos; ?>" />
												<input type="hidden" id="reqfirma" name="reqfirma" value="<?php echo $requiere_firma; ?>" />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Correo / Tel&eacute;fono:</label><br>
												<input type="text" class="form-control" value="<?php echo "$EjeCorreo / $EjeTelefono"; ?>" readonly />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Fecha y Hora de Inicio:</label><br>
												<input type="text" class="form-control" value="<?php echo $fecha_inicio; ?>" readonly />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Fecha y Hora de Finalizaci&oacute;n:</label><br>
												<input type="text" class="form-control" value="<?php echo $fecha_finaliza; ?>" readonly />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Direcci&oacute;n IP:</label><br>
												<input type="text" class="form-control" value="<?php echo $ip; ?>" readonly />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Regi&oacute;n / Ciudad:</label><br>
												<input type="text" class="form-control" value="<?php echo "$region, $ciudad"; ?>" readonly />
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
										<label>Descrip&oacute;n del Cuestionario:</label><br>
										<p class="text-justify"><?php echo $descripcion; ?></p>
									</div>
								</div>
								<br>
							</div>
						</div>
					</div>
				</div>

				<?php
				$result_seccion = $ClsEnc->get_secciones('', $codigo_cuestionario, 1);
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
										$result = $ClsEnc->get_pregunta('', $codigo_cuestionario, $seccion_codigo, 1);
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
												$result_respuesta = $ClsRes->get_respuesta($ejecucion, $codigo_cuestionario, $pregunta_codigo);
												if (is_array($result_respuesta)) {
													foreach ($result_respuesta as $row_respuesta) {
														$respuesta = utf8_decode($row_respuesta["resp_respuesta"]);
														$observacion = utf8_decode($row_respuesta["resp_observacion"]);
														$observacion = nl2br($observacion);
													}
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
													<div class="col-md-10 col-xs-11 text-left">
														<div class="row">
															<div class="col-md-12 col-xs-12">
																<label>Respuesta:</label>
																<p class="border border-dark p-2"><?php echo $salida; ?></p>
															</div>
														</div>
														<div class="row">
															<div class="col-md-12">
																<label>Amplienos su respuesta si desea:</label>
																<p class="border border-dark p-2 text-justify"><?php echo $observacion; ?></p>
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
								<h5 class="card-title"><i class="nc-icon nc-briefcase-24"></i> Ya terminamos! gracias por tus respuestas...</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-10 ml-auto mr-auto">
										<label>Nombre de qui&eacute;n contesta: </label>
										<input type="text" class="form-control" value="<?php echo $respondio; ?>" readonly />
									</div>
								</div>
								<div class="row">
									<div class="col-md-1"></div>
									<div class="col-md-5">
										<label>Correo electr&oacute;nico: <span class="text-danger">*</span> </label>
										<input type="text" class="form-control" value="<?php echo $EjeCorreo; ?>" readonly />
									</div>
									<div class="col-md-5">
										<label>T&eacute;lefono: </label>
										<input type="text" class="form-control" value="<?php echo $EjeTelefono; ?>" readonly />
									</div>
									<div class="col-md-1"></div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-10 ml-auto mr-auto">
										<h5>&iquest;Tienes alg&uacute;n comentario o sugerencia general?:</h5>
										<p class="text-justify"><?php echo $EjeObservacion; ?></p>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/encuestas/ejecucion.js"></script>
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