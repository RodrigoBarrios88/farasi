<?php
include_once('html_fns_sistema.php');
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
		<?php echo sidebar("../", "process"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-layer-group"></i> Gestor de Sistemas de Gesti&oacute;n</h5>
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
										<label>Nombre del Sistema:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="nombre" id="nombre" onkeyup="texto(this)" />
										<input type="hidden" name="codigo" id="codigo" />
									</div>
									<div class="col-md-5">
										<label>Color:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" id="color" name="color" value="#fff" />
									</div>
									<div class="col-md-1">
										<span>.</span><br>
										<button type="button" id="btn-color" class="btn btn-white btn-block back-change"> &nbsp;</button>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Jefe o Gerente del Sistema:</label> <span class="text-danger">*</span>
										<?php echo utf8_decode(usuarios_html('usuario', '', 'select2')); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Pol&iacute;tica del Sistema:</label>
										<textarea class="form-control" id="politica" name="politica" rows="5"></textarea>
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
									<div class="col-lg-12" id="result"> </div>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/process/sistema.js"></script>
</body>

</html>