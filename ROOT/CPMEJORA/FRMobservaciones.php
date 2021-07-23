<?php
include_once('html_fns_mejora.php');
validate_login("../");
$id = $_SESSION["codigo"];

$ClsAud = new ClsAuditoria();
$hashkey = $_REQUEST["hashkey"];
$codigo = $ClsAud->decrypt($hashkey, $id);
$result = $ClsAud->get_externa($codigo);
if (is_array($result)) {
	foreach ($result as $row) {
		$tipo = get_tipo_auditoria($row["ext_tipo"]);
		$entidad = utf8_decode($row["ext_entidad"]);
		$objetivo = utf8_decode($row["ext_objetivo"]);
		$resumen = utf8_decode($row["ext_resumen"]);
		$fecha = cambia_fecha($row["ext_fecha_auditoria"]);
		$usuario = utf8_decode($row["registra_nombre"]);
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
		<?php echo sidebar("../", "mejora"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<fieldset disabled>
					<div class="row">
						<div class="col-md-12">
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
												<div class="col-md-6">
													<label>Tipo:</label>
													<input type="text" class="form-control" value="<?php echo $tipo; ?>" />
												</div>
												<div class="col-md-6">
													<label>Entidad:</label>
													<input type="text" class="form-control" value="<?php echo $entidad; ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<label>Fecha Realizada:</label>
													<input type="text" class="form-control" value="<?php echo $fecha ?>" />
												</div>
												<div class="col-md-6">
													<label>Usuario que Registra:</label>
													<input type="text" class="form-control" value="<?php echo $usuario ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Objetivo:</label>
													<textarea type="text" class="form-control textarea-autosize"><?php echo $objetivo ?> </textarea>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Resumen:</label>
													<textarea type="text" class="form-control textarea-autosize"><?php echo $resumen ?> </textarea>
												</div>
											</div>

										</div>
									</div>
									<br>
								</div>
							</div>
						</div>
					</div>
				</fieldset>
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fab fa-wpforms"></i> Observaciones </h5>
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
									<div class="col-md-12">
										<label>Descripci&oacute;n:</label> <span class="text-danger">*</span>
										<textarea class="form-control textarea-autosize" id="descripcion" name="descripcion"></textarea>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-12 text-center">
										<input hidden name="codigo" id="codigo" />
										<button type="button" class="btn btn-white" id="btn-limpiar" onclick="Limpiar();"><i class="fas fa-eraser"></i> Limpiar</button>
										<button type="button" class="btn btn-primary" id="btn-grabar" onclick="Grabar();"><i class="fas fa-save"></i> Grabar</button>
										<button type="button" class="btn btn-primary hidden" id="btn-modificar" onclick="Modificar();"><i class="fas fa-save"></i> Grabar</button>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-lg-12" id="result"> </div>
								</div>
								<input type="hidden" id="codigo" name="codigo" />
								<input type="hidden" id="auditoria" name="auditoria" value="<?php echo $codigo; ?>" />
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/mejora/externa_detalle.js"></script>
	<script>
		$(document).ready(function() {
			$("#fecha").datepicker({
				keyboardNavigation: false,
				forceParse: false,
				autoclose: true,
				format: "dd/mm/yyyy"
			});
			printTable('');
		});
	</script>
</body>

</html>