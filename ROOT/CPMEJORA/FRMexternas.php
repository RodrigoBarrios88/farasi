<?php
include_once('html_fns_mejora.php');
validate_login("../");
$id = $_SESSION["codigo"];
$desde = date("d/m/Y");
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("../", "mejora"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fas fa-file-signature"></i> Gestor de Auditor&iacute;as Externas</h5>
								<h6 class="card-subtitle text-muted">Ingreso de Documentos</h6>
								<br>
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
										<label>Fecha: </label> <span class="text-danger">*</span>
										<div class="input-group">
											<a class="input-group-addon"><i class="fa fa-calendar"></i></a>
											<input type="text" class="form-control" name="fecha" id="fecha" value="<?php echo $desde; ?>" />
										</div>
									</div>
									<div class="col-md-6">
										<label>Tipo:</label> <span class="text-danger">*</span>
										<?php echo combo_tipo_auditoria("tipo", "", "select2") ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Entidad:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="entidad" id="entidad" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Objetivo:</label> <span class="text-danger">*</span>
										<textarea class="form-control textarea-autosize" id="objetivo" name="objetivo"></textarea>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Resumen Ejecutivo:</label> <span class="text-danger">*</span>
										<textarea class="form-control textarea-autosize" id="resumen" name="resumen"></textarea>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-12 text-center">
										<input hidden name="codigo" id="codigo" />
										<button type="button" class="btn btn-white" id="btn-limpiar" onclick="Limpiar();"><i class="fas fa-eraser"></i> Limpiar</button>
										<button type="button" class="btn btn-primary" id="btn-grabar" onclick="Grabar(1);"><i class="fas fa-save"></i> Grabar</button>
										<button type="button" class="btn btn-primary hidden" id="btn-modificar" onclick="Modificar(1);"><i class="fas fa-save"></i> Grabar</button>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-lg-12" id="result"> </div>
								</div>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/mejora/externa.js"></script>
	<script>
		$(document).ready(function() {
			$(".select2").select2({
				width: "100%"
			});
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