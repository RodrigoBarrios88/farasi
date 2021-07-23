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
		//--
		$objetivo = utf8_decode($row["audit_objetivo"]);
		$riesgo = utf8_decode($row["audit_riesgos"]);
		$alcance = utf8_decode($row["audit_alcance"]);
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
		<?php echo sidebar("../", "auditoria"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-calendar-o"></i> Programaci&oacute;n de Visitas de Auditor&iacute;a</h5>
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
										<label>Sede:</label> <span class="text-danger">*</span>
										<?php echo utf8_decode(sedes_html("sede", "", "select2")); ?>
										<script>
											document.getElementById("sede").value = "<?php echo $sede; ?>"
										</script>
										<input type="hidden" name="sector" id="sector" value="" />
									</div>
									<div class="col-md-6">
										<label>Departamento:</label> <span class="text-danger">*</span>
										<div id="divarea">
											<?php echo utf8_decode(departamento_org_html("departamento", "", "select2")); ?>
										</div>
										<script>
											document.getElementById("departamento").value = "<?php echo $departamento; ?>"
										</script>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Fecha de Inicio:</label> <span class="text-danger">*</span>
										<div class="form-group" id="simple">
											<div class="input-group date">
												<input type="text" class="form-control" name="fecha" id="fecha" value="<?php echo date("d/m/Y"); ?>">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											</div>
										</div>
										<input type="hidden" name="auditoria" id="auditoria" value="<?php echo $auditoria; ?>" />
										<input type="hidden" name="codigo" id="codigo" />
									</div>
									<div class="col-md-6">
										<label>Hora:</label> <span class="text-danger">*</span>
										<div class="form-group">
											<input type="text" class="form-control timepicker" name="hora" id="hora" value="08:00">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Objetivo de esta Auditor&iacute;a:</label> <span class="text-muted">(opcional)</span>
										<textarea class="form-control" name="objetivo" id="objetivo" rows="1" onkeyup="textoLargo(this);"><?php echo $objetivo; ?></textarea>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Riesgos de esta Auditor&iacute;a:</label> <span class="text-muted">(opcional)</span>
										<textarea class="form-control" name="riesgo" id="riesgo" rows="1" onkeyup="textoLargo(this);"><?php echo $riesgo; ?></textarea>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Alcance Espec&iacute;fico:</label> <span class="text-muted">(opcional)</span>
										<textarea class="form-control" name="alcance" id="alcance" rows="1" onkeyup="textoLargo(this);"><?php echo $alcance; ?></textarea>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Observaciones Especiales:</label>
										<textarea class="form-control" name="obs" id="obs" rows="1" onkeyup="textoLargo(this);"></textarea>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/auditoria/programacion.js"></script>
</body>
</html>