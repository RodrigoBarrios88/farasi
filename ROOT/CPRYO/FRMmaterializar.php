<?php
include_once('html_fns_ryo.php');
validate_login("../");
$id = $_SESSION["codigo"];
//$_POST
$ClsRie = new ClsRiesgo();
$hashkey = $_REQUEST["hashkey"];
$codigo = $ClsRie->decrypt($hashkey, $id);
$info = $ClsRie->get_riesgo($codigo);
$materializado = $_REQUEST['materializado'];
if (is_array($info)) {
	foreach ($info as $row) {
		$proceso = utf8_decode($row["fic_nombre"]);
		$sistema = utf8_decode($row["sis_nombre"]);
		$origen = utf8_decode($row["rie_origen"]);
		$causa = utf8_decode($row["rie_causa"]);
		$consecuencia = utf8_decode($row["rie_consecuencia"]);
		$probabilidad = utf8_decode($row["rie_probabilidad"]);
		$impacto = utf8_decode($row["rie_impacto"]);
		$severidad = intval($probabilidad) * intval($impacto);
		$condicion = get_condicion($severidad);
		$accion = trim($row["rie_accion"]);
		$accion = get_accion_riesgo($accion);
		$riesgo = utf8_decode($row["fod_descripcion"]);
		$ficha = trim($row["fic_codigo"]);
		$desde = cambia_fecha($row["rie_fecha_materializacion"]);
		$arrArchivos = get_archivos(3, $codigo);
	}
}
$desde = ($desde == "00/00/0000") ? date("d/m/Y") : $desde;
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../", "risk"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<fieldset disabled>
					<div class="row">
						<div class="col-md-6">
							<div class="card demo-icons">
								<div class="card-header">
									<h5 class="card-title">
										<i class="nc-icon nc-bullet-list-67"></i> Informaci&oacute;n
									</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-lg-12">
											<div class="row">
												<div class="col-md-12">
													<label>Proceso:</label>
													<input type="text" class="form-control" value="<?php echo $proceso; ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Sistema:</label>
													<input type="text" class="form-control" value="<?php echo $sistema; ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Probabildad:</label>
													<input type="text" class="form-control" value="<?php echo get_probabilidad($probabilidad); ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Impacto:</label>
													<input type="text" class="form-control" value="<?php echo get_impacto($impacto); ?>" />
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
										<i class="fa fa-check-square-o"></i> Estado
									</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-lg-12">
											<div class="row">
												<div class="col-md-12">
													<label>Severidad:</label>
													<input type="text" class="form-control" value="<?php echo $severidad; ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Condici&oacute;n:</label>
													<input type="text" class="form-control" value="<?php echo $condicion; ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Acci&oacute;n:</label>
													<input type="text" class="form-control" value="<?php echo $accion; ?>" />
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
										<i class="fa fa-file-text-o"></i> Descripci&oacute;n
									</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-lg-12">
											<div class="row">
												<div class="col-md-12">
													<label>Riesgo:</label>
													<textarea class="form-control textarea-autosize" onkeyup="textoLargo(this);" rows="2"><?php echo $riesgo; ?></textarea>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Origen:</label>
													<textarea class="form-control textarea-autosize" onkeyup="textoLargo(this);" rows="2"><?php echo $origen; ?></textarea>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Causa:</label>
													<textarea class="form-control textarea-autosize" onkeyup="textoLargo(this);" rows="2"><?php echo $causa; ?></textarea>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Consecuencia:</label>
													<textarea class="form-control textarea-autosize" onkeyup="textoLargo(this);" rows="2"><?php echo $consecuencia; ?></textarea>
												</div>
											</div>
										</div>
										<br>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php if ($justificacion != "") { ?>
						<div class="row">
							<div class="col-md-12">
								<div class="card demo-icons">
									<div class="card-header">
										<h5 class="card-title">
											<i class="nc-icon nc-paper"></i> Revisi&oacute;n de Gerencia
										</h5>
									</div>
									<div class="card-body all-icons">
										<div class="row">
											<div class="col-lg-12">
												<div class="row">
													<div class="col-md-12">
														<label>Justificacion:</label>
														<textarea class="form-control textarea-autosize" onkeyup="textoLargo(this);" rows="2"><?php echo $justificacion; ?></textarea>
													</div>
												</div>
											</div>
											<br>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
				</fieldset>
				<div class=" row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<?php if($materializado == 'yes'):?>
										<i class="fa fa-pencil-square-o"></i> Evidencia
										<?php elseif($materializado == 'no'):?>
											<i class="fa fa-pencil-square-o"></i> Materializar
									<?php endif;?>
								</h5>
							</div>
							<div class="col-xs-12 col-md-12 text-right"><label class="text-danger">* Campos Obligatorios</label> </div>
							<div class="card-body all-icons">
								<div class="row">
									<?php if ($materializado == 'no') : ?>
										<div class="col-md-6">
											<label>Fecha: </label> <span class="text-danger">*</span>
											<div class="input-group">
												<a class="input-group-addon"><i class="fa fa-calendar"></i></a>
												<input type="text" onchange="update(this,10);" class="form-control" name="desde" id="desde" value="<?php echo $desde; ?>" />
											</div>
										</div>
									<?php elseif ($materializado == 'yes') : ?>
										<div class="col-md-6">
											<label>Fecha: </label> <span class="text-danger">*</span>
											<div class="input-group">
												<a class="input-group-addon"><i class="fa fa-calendar"></i></a>
												<input disabled onchange="update(this,10);" class="form-control" name="desde" id="desde" value="<?php echo $desde; ?>" />
											</div>
										</div>
									<?php endif; ?>
								</div>
								<form id="form" action="#" class="wizard-big">
									<div class="row">
										<?php if ($materializado == 'no') : ?>
											<div class="col-lg-12">
												<label>Responsables: </label> <span class="text-danger">*</span>
												<?php
												////////---------- Obtiene los usuarios asignados a cada ficha
												$ClsRie = new ClsRiesgo();
												$result = $ClsRie->get_riesgo_usuario("", $codigo, "");
												if (is_array($result)) {
													$arrusuarios = array();
													$usuarios_asignados = 0;
													foreach ($result as $row) {
														$arrusuarios[$usuarios_asignados] = $row["rus_usuario"];
														$usuarios_asignados++;
													}
													//echo $sedes_asignadas;
												}
												$ClsFic = new ClsFicha();
												$result = $ClsFic->get_ficha_usuario("", $ficha);
												?>
												<select class="form-control dual_select" onchange="asignarUsuario();" name="duallistbox1[]" multiple>
													<?php
													if (is_array($result)) {
														foreach ($result as $row) {
															$cod = $row["usu_id"];
															$nom = utf8_decode($row["usu_nombre"]);
															$chk = "";
															for ($i = 0; $i < $usuarios_asignados; $i++) {
																//echo "$cod == ".$arrsedes[$i]."<br>";
																if ($cod == $arrusuarios[$i]) {
																	$chk = "selected";
																	break;
																}
															}
															echo '<option value="' . $cod . '" ' . $chk . '>' . $nom . '</option>';
														}
													} else {
														echo '<option value="">No hay usuarios registrados...</option>';
													}
													?>
												</select>
											</div>
										<?php endif; ?>
									</div>
								</form>
								<br>
								<br>
								<div class="row">
									<?php for ($i = 1; $i <= 3; $i++) { ?>
										<div class="col-md-4 text-center">
											<div class="fileinput fileinput-new text-center" data-provides="fileinput">
												<div class="fileinput fileinput-new text-center" data-provides="fileinput">
													<div class="text-center" id="archivo<?php echo $i ?>">
														<?php echo $arrArchivos[$i]; ?>
													</div>
													<?php if ($materializado == 'no') : ?>
														<span class="btn btn-rose btn-round btn-file">
															<span class="fileinput-new" onclick="openInput(<?php echo $i ?>);">
																<i class="fa fa-camera"></i> Agregar Foto
															</span>
														</span>
													<?php endif; ?>
												</div>
											</div>
										</div>
									<?php } ?>
								</div>
								<br>
								<div class="row">
									<div class="col-md-6 ml-auto mr-auto text-center">
										<input type="hidden" name="codigo" id="codigo" value="<?php echo $codigo ?>" />
										<input type="hidden" name="hoy" id="hoy" value="<?php echo date("Y-m-d") ?>" />
										<input type="hidden" name="evidencia" id="evidencia" value="<?php echo $arrArchivos[0] ?>" />
										<input id="imagen" name="imagen" type="file" multiple="false" class="hidden" onchange="upload(this,1);">
										<input type="hidden" id="posicion" name="posicion" />
										<?php if ($materializado == 'yes') : ?>
											<button type="button" class="btn btn-default" onclick="window.history.back();"><span class="fa fa-chevron-left"></span> Regresar</button>
										<?php elseif ($materializado == "no") : ?>
											<button type="button" class="btn btn-default" onclick="window.history.back();"><span class="fa fa-chevron-left"></span> Regresar</button>
											<button type="button" class="btn btn-danger" id="btn-grabar" onclick="Finalizar();"><span class="fa fa-check"></span> Finalizar</button>
										<?php endif; ?>
									</div>
								</div>
							</div>
							<div class="card-footer text-right">
								<hr>
								<div class="stats">
									<i class="fa fa-clock-o"></i> <?php echo $programacion; ?>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/ryo/riesgo.js"></script>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/ryo/materializacion.js"></script>
	<script>
		$('.dual_select').bootstrapDualListbox({
			selectorMinimalHeight: 160,
		});
		$("#form").submit(function() {
			asignarUsuario($('[name="duallistbox1[]"]').val());
			return false;
		});
		$('#desde').datepicker({
			keyboardNavigation: false,
			forceParse: false,
			autoclose: true,
			format: "dd/mm/yyyy"
		});
	</script>
</body>

</html>