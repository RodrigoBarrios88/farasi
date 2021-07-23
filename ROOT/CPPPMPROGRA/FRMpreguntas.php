<?php
include_once('html_fns_programacion.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$cuestionario = $_REQUEST["cuestionario"];
$ClsCue = new ClsCuestionarioPPM();
$result = $ClsCue->get_cuestionario($cuestionario);
if (is_array($result)) {
	foreach ($result as $row) {
		$categoria = utf8_decode($row["cat_nombre"]);
		$nombre = utf8_decode($row["cue_nombre"]);
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
		<?php echo sidebar("../", "ppm"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-question-circle-o"></i> Gestor de Preguntas</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-xs-6 col-md-6 text-left"><button type="button" class="btn btn-white" onclick="atras();"><i class="fa fa-chevron-left"></i> Atr&aacute;s</a> </div>
									<div class="col-xs-6 col-md-6 text-right"><label class=" text-danger">* Campos Obligatorios</label> </div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Lista:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" value="<?php echo $nombre; ?>" readonly />
									</div>
									<div class="col-md-6">
										<label>Categor&iacute;a:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" value="<?php echo $categoria; ?>" readonly />
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-md-12">
										<label>Pregunta:</label> <span class="text-danger">*</span>
										<textarea class="form-control" name="pregunta" id="pregunta" rows="3" onkeyup="textoLargo(this);"></textarea>
										<input type="hidden" name="codigo" id="codigo" />
										<input type="hidden" name="cuestionario" id="cuestionario" value="<?php echo $cuestionario; ?>" />
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-12 text-center">
										<button type="button" class="btn btn-white" id="btn-limpiar" onclick="Limpiar();"><i class="fas fa-eraser"></i> Limpiar</button>
										<button type="button" class="btn btn-primary" id="btn-grabar" onclick="GrabarPregunta();"><i class="fas fa-save"></i> Grabar</button>
										<button type="button" class="btn btn-primary hidden" id="btn-modificar" onclick="ModificarPregunta();"><i class="fas fa-save"></i> Grabar</button>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/ppm/cuestionario.js"></script>

	<script>
		$(document).ready(function() {
			printTablePregunta(document.getElementById('cuestionario').value);

			$('.select2').select2({ width: '100%' });
		});
	</script>

</body>

</html>