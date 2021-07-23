<?php
include_once('html_fns_usuarios.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$usuario = $_REQUEST["usuario"];
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../", "herramientas"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-link"></i> Asignaci&oacute;n de Usuarios a Categor&iacute;as</h5>
							</div>
							<div class="card-body all-icons">
								<form id="f1" action="FRMusuario_categoria.php" method="get">
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
											<label>Usuarios:</label> <span class="text-danger">*</span>
											<?php echo utf8_decode(usuarios_html("usuario", "Submit();", "select2")); ?>
											<script>
												document.getElementById("usuario").value = '<?php echo $usuario; ?>';
											</script>
										</div>
										<div class="col-md-6 text-center">
											<span>&nbsp;</span>
											<a type="button" href="FRMusuario_categoria.php" class="btn btn-block btn-lg btn-primary"><span class="fa fa-refresh"></span></a>
										</div>
									</div>
									<br>
								</form>
								<br>
								<form id="form" action="#" class="wizard-big">
									<div class="row">
										<div class="col-lg-12">
											<?php
											////////---------- Obtiene las sedes asignadas a cada sede
											if ($usuario != "") {
												$ClsUsu = new ClsUsuario();
												$result = $ClsUsu->get_usuario_categoria("", $usuario, "");
												if (is_array($result)) {
													$arrcategorias = array();
													$categorias_asignadas = 0;
													foreach ($result as $row) {
														$arrcategorias[$categorias_asignadas] = $row["cus_categoria"];
														$categorias_asignadas++;
													}
													//echo $categorias_asignadas;
												}
											}
											////////----------
											if ($usuario != "") {
												$ClsCat = new ClsCategoria();
												$result = $ClsCat->get_categoria_checklist('', '', 1);
											}
											?>
											<select class="form-control dual_select" name="duallistbox1[]" multiple>
												<?php
												if (is_array($result)) {
													foreach ($result as $row) {
														$cod = $row["cat_codigo"];
														$nom = utf8_decode($row["cat_nombre"]);
														$chk = "";
														for ($i = 0; $i < $categorias_asignadas; $i++) {
															//echo "$cod == ".$arrcategorias[$i]."<br>";
															if ($cod == $arrcategorias[$i]) {
																$chk = "selected";
																break;
															}
														}
														echo '<option value="' . $cod . '" ' . $chk . '>' . $nom . '</option>';
													}
												} else {
													echo '<option value="">No hay categorias registradas...</option>';
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/seguridad/usuario.js"></script>
	<script>
		$(document).ready(function() {
			$('.dual_select').bootstrapDualListbox({
				selectorMinimalHeight: 160,
			});

			$("#form").submit(function() {
				asignarCategorias($('[name="duallistbox1[]"]').val());
				return false;
			});

			$('.select2').select2({ width: '100%' });
		});
	</script>

</body>

</html>