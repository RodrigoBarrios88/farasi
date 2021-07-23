<?php
include_once('html_fns_proceso.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$ficha = $_REQUEST["ficha"];
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../", "process"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-link"></i> Asignaci&oacute;n de Usuarios a Procesos</h5>
							</div>
							<div class="card-body all-icons">
								<form id="f1" action="FRMficha_usuario.php" method="get">
									<div class="row">
										<div class="col-xs-6 col-md-6 text-left">
										<button type="button" class="btn btn-white" onclick="window.history.back();">
											<i class="fa fa-chevron-left"></i>Atr&aacute;s
										</button>
									</div>
										<div class="col-xs-6 col-md-6 text-right"><label class=" text-danger">* Campos Obligatorios</label> </div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Procesos del Sistema de Gesi&oacute;n:</label> <span class="text-danger">*</span>
											<?php echo utf8_decode(ficha_html("ficha", "Submit();", "select2")); ?>
											<script>
												document.getElementById("ficha").value = '<?php echo $ficha; ?>';
											</script>
										</div>
										<div class="col-md-6 text-center">
											<span>&nbsp;</span>
											<a type="button" href="FRMficha_usuario.php" class="btn btn-block btn-lg btn-primary"><span class="fa fa-refresh"></span></a>
										</div>
									</div>
									<br>
								</form>
								<br>
								<form id="form" action="#" class="wizard-big">
									<div class="row">
										<div class="col-lg-12">
											<?php
											////////---------- Obtiene los usuarios asignados a cada ficha
											if ($ficha != "") {
												$ClsFic = new ClsFicha();
												$result = $ClsFic->get_ficha_usuario("", $ficha, "");
												if (is_array($result)) {
													$arrusuarios = array();
													$usuarios_asignados = 0;
													foreach ($result as $row) {
														$arrusuarios[$usuarios_asignados] = $row["fus_usuario"];
														$usuarios_asignados++;
													}
													//echo $sedes_asignadas;
												}
											}
											////////----------
											if ($ficha = !"") {
												$ClsUsu = new ClsUsuario();
												$result = $ClsUsu->get_usuario('', '', '', '', '', 1, '');
											}
											?>
											<select class="form-control dual_select" name="duallistbox1[]" multiple>
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
									</div>
									<hr>
									<div class="row">
										<div class="col-md-12 text-center">
											<button type="submit" class="btn btn-block btn-primary" id="btn-asignar"><i class="fas fa-save"></i> Grabar</button>
										</div>
									</div>
								</form>
								<hr>
								<div class="row">
									<div class="col-lg-12" id="result">
										<?php
										echo tabla_ficha_usuario('', '', '', '', '', '', 0);
										?>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/process/ficha.js"></script>

	<script>
		$(document).ready(function() {
			$('.dual_select').bootstrapDualListbox({
				selectorMinimalHeight: 160,
			});
			$("#form").submit(function() {
				asignarUsuario($('[name="duallistbox1[]"]').val());
				return false;
			});

			$('.dataTables-example').DataTable({
				pageLength: 100,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: []
			});
			$('.select2').select2({ width: '100%' });
		});
	</script>

</body>

</html>