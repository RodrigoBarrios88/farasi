<?php
include_once('html_fns_cuestionario.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$auditoria = $_REQUEST["codigo"];
$ClsAud = new ClsAuditoria();
$result = $ClsAud->get_cuestionario($auditoria, '', '', 1);
if (is_array($result)) {
	foreach ($result as $row) {
		//categoria
		$categoria = utf8_decode($row["cat_nombre"]);
		//nombre
		$nombre = utf8_decode($row["audit_nombre"]);
		//tipo
		$tipo = trim($row["audit_ponderacion"]);
		switch ($tipo) {
			case 1:
				$labelponderacion = "de 1 a 10";
				$readonly = "readonly";
				break;
			case 2:
				$labelponderacion = "SI y NO (con pesos ponderados)";
				$readonly = "";
				break;
			case 3:
				$labelponderacion = "Satisfactorio y No Satisfactorio";
				$readonly = "readonly";
				break;
		}
	}
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
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-question-circle-o"></i> Gestor de Preguntas</h5>
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
										<label>Auditor&iacute;a: </label> <span class="text-danger">*</span>
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
										<label>Secci&oacute;n: </label> <span class="text-danger">*</span>
										<?php echo utf8_decode(secciones_auditoria_html("seccion", $auditoria, "", "select2")); ?>
										<input type="hidden" name="codigo" id="codigo" />
										<input type="hidden" name="auditoria" id="auditoria" value="<?php echo $auditoria; ?>" />
									</div>
									<div class="col-md-6">
										<label><?php echo $labelponderacion; ?> - Peso:</label> <span class="text-danger">*</span>
										<input type="text" name="peso" id="peso" class="form-control" <?php echo $readonly; ?> value="1" onkeyup="decimales(this);" />
										<input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo; ?>" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Pregunta:</label> <span class="text-danger">*</span>
										<textarea class="form-control" name="pregunta" id="pregunta" rows="3" onkeyup="textoLargo(this);"></textarea>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/auditoria/pregunta.js"></script>
</body>
</html>