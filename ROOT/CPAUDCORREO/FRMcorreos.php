<?php
include_once('html_fns_correo.php');
validate_login("../");
$id = $_SESSION["codigo"];
//$_POST
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
								<h5 class="card-title"><i class="fa fa-envelope-o"></i> Configuraci&oacute;n de Correos de Notificaci&oacute;n</h5>
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
										<label>Sede:</label> <span class="text-danger">*</span>
										<?php echo utf8_decode(sedes_html("sede", "", "select2")); ?>
									</div>
									<div class="col-md-6">
										<label>Cuestionario de Auditor&iacute;a:</label> <span class="text-danger">*</span>
										<?php echo utf8_decode(auditoria_html("auditoria", "", "select2")); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Nombre:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="nombre" id="nombre" onkeyup="texto(this)" />
										<input type="hidden" name="codigo" id="codigo" />
									</div>
									<div class="col-md-6">
										<label>Correo:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="correo" id="correo" onkeyup="texto(this)" />
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/auditoria/correo.js"></script>
</body>
</html>