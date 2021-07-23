<?php
include_once('html_fns_mejora.php');
validate_login("../");
$id = $_SESSION["codigo"];
//$_POST
$ClsRie = new ClsRiesgo();
$hashkey = $_REQUEST["hashkey"];
$codigo = $ClsRie->decrypt($hashkey, $id);
$info = $ClsRie->get_riesgo($codigo, "", "", "", "", 2); // Riesgo Materializado
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
					<div class=" row">
						<div class="col-md-12">
							<div class="card demo-icons">
								<div class="card-header">
									<h5 class="card-title">
										<i class="fa fa-pencil-square-o"></i> Materializacion
									</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-md-6">
											<label>Fecha:</label> <span class="text-danger">*</span>
											<div class="form-group" id="range">
												<div class="input-daterange" id="datepicker">
													<input type="text" onchange="update(this,10);" class="form-control" name="desde" id="desde" value="<?php echo $desde; ?>" />
												</div>
											</div>
										</div>
									</div>
									<form id="form" action="#" class="wizard-big">
										<div class="row">
											<div class="col-lg-6">
												<label>Responsables: </label> <span class="text-danger">*</span>
												<?php
												////////---------- Obtiene los usuarios asignados a cada ficha
												$ClsRie = new ClsRiesgo();
												$result = $ClsRie->get_riesgo_usuario("", $codigo, "",true);
												if (is_array($result)) {
													foreach ($result as $row) {
														echo '<input class="form-control" value="' . utf8_decode($row["usu_nombre"]) . '"></input>';
													}
												} ?>
											</div>
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
														<label>Foto #<?php echo $i ?></label>
													</div>
												</div>
											</div>
										<?php } ?>
									</div>
									<br>
								</div>
							</div>
						</div>
					</div>
				</fieldset>
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
		$('#range .input-daterange').datepicker({
			keyboardNavigation: false,
			forceParse: false,
			autoclose: true,
			format: "dd/mm/yyyy"
		});
	</script>
</body>

</html>