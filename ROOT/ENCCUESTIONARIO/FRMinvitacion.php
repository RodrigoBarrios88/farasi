<?php
include_once('html_fns_cuestionario.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$encuesta = $_REQUEST["codigo"];
$ClsEnc = new ClsEncuesta();
$result = $ClsEnc->get_cuestionario($encuesta, '', 1);
if (is_array($result)) {
	foreach ($result as $row) {
		//categoria
		$categoria = utf8_decode($row["cat_nombre"]);
		//nombre
		$nombre = utf8_decode($row["cue_titulo"]);
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
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fas fa-mail-bulk-o"></i> Invitar a Responder la Encuesta</h5>
							</div>
							<div class="card-body all-icons">
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
										<label>Cuestionario: </label> <span class="text-danger">*</span>
										<input type="text" class="form-control" value="<?php echo $nombre; ?>" readonly />
									</div>
									<div class="col-md-6">
										<label>Categor&iacute;a: </label> <span class="text-danger">*</span>
										<input type="text" class="form-control" value="<?php echo $categoria; ?>" readonly />
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-md-6">
										<label>Cliente: </label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="cliente" id="cliente" onkeyup="texto(this);" />
										<input type="hidden" name="encuesta" id="encuesta" value="<?php echo $encuesta; ?>" />
										<input type="hidden" name="codigo" id="codigo" />
									</div>
									<div class="col-md-6">
										<label>Correo: </label> <span class="text-danger">*</span>
										<input type="email" class="form-control" name="correo" id="correo" onkeyup="texto(this);" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Enlace o URL para redirigir al finalizar la encuesta: </label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="url" id="url" onkeyup="texto(this);" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Observaciones:</label> <span class="text-muted">(Internas)</span>
										<textarea class="form-control" name="obs" id="obs" rows="2" onkeyup="textoLargo(this);"></textarea>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-12 text-center">
										<button type="button" class="btn btn-white" id="btn-limpiar" onclick="Limpiar();"><i class="fas fa-eraser"></i> Limpiar</button>
										<button type="button" class="btn btn-primary" id="btn-grabar" onclick="Grabar();"><i class="fas fa-save"></i> Grabar</button>
										<button type="button" class="btn btn-primary hidden" id="btn-modificar" onclick="Modificar();"><i class="fas fa-save"></i> Grabar</button>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-lg-12" id="result">

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


	<script type="text/javascript" src="../assets.1.2.8/js/modules/encuestas/invitacion.js"></script>
</body>

</html>