<?php
include_once('html_fns_perfil.php');
$codigo = $_SESSION["codigo"];

$ClsAjus = new ClsAjustes();
$result = $ClsAjus->get_ajustes($codigo);
if (is_array($result)) {
	foreach ($result as $row) {
		$idioma = $row["aju_idioma"];
		$notificaciones = $row["aju_notificaciones"];
	}
}
$ckecked = ($notificaciones == 1) ? "checked" : "";
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
								<h5 class="card-title"><i class="fa fa-cogs"></i> Ajustes</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-xs-6 col-md-6 text-left">
										<button type="button" class="btn btn-white" onclick="window.history.back();">
											<i class="fa fa-chevron-left"></i>Atr&aacute;s
										</button>
									</div>
									<div class="col-xs-6 col-md-6 text-right"><label class="text-danger">* Campos Obligatorios</label> </div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Idioma:</label> <span class="text-danger">*</span>
										<select class="form-control" name="idioma" id="idioma">
											<option value="">Seleccione</option>
											<option value="ES">Espa&ntilde;ol</option>
											<option value="EN">Ingl&eacute;s</option>
										</select>
										<script>
											document.getElementById("idioma").value = "<?php echo $idioma; ?>";
										</script>
										<input type="hidden" name="codigo" id="codigo" value="<?php echo $codigo; ?>" />
									</div>
									<div class="col-md-6">
										<label>Notificaciones:</label> <span class="text-danger">*</span>
										<br>
										<input type="checkbox" name="notificaciones" id="notificaciones" class="js-switch" <?php echo $ckecked; ?> />
									</div>
								</div>
								<br>
								<hr>
								<div class="row">
									<div class="col-md-12 text-center">
										<button type="button" class="btn btn-secondary" id="btn-limpiar" onclick="Limpiar();"><i class="fas fa-eraser"></i> Limpiar</button>
										<button type="button" class="btn btn-primary" id="btn-grabar" onclick="ModificarAjustes();"><i class="fas fa-save"></i> Grabar</button>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/perfil/perfil.js"></script>
</body>
</html>