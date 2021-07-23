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
				$ponderacion = "de 1 a 10";
				break;
			case 2:
				$ponderacion = "SI, NO y No Aplica";
				break;
			case 3:
				$ponderacion = "Satisfactorio y No Satisfactorio";
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
								<h5 class="card-title"><i class="fa fa-columns"></i> Gestor de Secciones</h5>
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
										<label>Numeraci&oacute;n: </label> <small class="text-muted">(Paragrafatura o Romanos)</small> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="numero" id="numero" onkeyup="texto(this);" maxlength="10" />
										<input type="hidden" name="codigo" id="codigo" />
										<input type="hidden" name="auditoria" id="auditoria" value="<?php echo $auditoria; ?>" />
									</div>
									<div class="col-md-6">
										<label>T&iacute;tulo:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="titulo" id="titulo" onkeyup="texto(this);" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Prop&oacute;sito o Instrucciones:</label>
										<textarea class="form-control" name="proposito" id="proposito" rows="3" onkeyup="textoLargo(this);"></textarea>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-12 text-center">
										<button type="button" class="btn btn-white" id="btn-limpiar" onclick="Limpiar();"><i class="fas fa-eraser"></i> Limpiar</button>
										<button type="button" class="btn btn-primary" id="btn-grabar" onclick="GrabarSeccion();"><i class="fas fa-save"></i> Grabar</button>
										<button type="button" class="btn btn-primary hidden" id="btn-modificar" onclick="ModificarSeccion();"><i class="fas fa-save"></i> Grabar</button>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/auditoria/seccion.js"></script>
</body>
</html>