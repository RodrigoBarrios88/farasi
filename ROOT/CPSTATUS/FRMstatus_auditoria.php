<?php
include_once('html_fns_status.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
	<style>
		.btn-spiner {
			margin: 0px;
			padding-top: 3px;
			padding-left: 10px;
			padding-right: 10px;
			padding-bottom: 3px;

			display: inline-block;
			font-weight: 400;
			text-align: center;
			white-space: nowrap;
			border: 1px solid transparent;
			font-size: 1rem;
			line-height: 1.5;
			border-radius: .25rem;
		}

		.touchspin {
			text-align: center;
		}
	</style>

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
								<h5 class="card-title"><i class="fa fa-tags"></i> Gestor de Status de Hallazgos (Audit Active)</h5>
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
										<label>Nombre del Status:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="nombre" id="nombre" onkeyup="texto(this)" />
										<input type="hidden" name="codigo" id="codigo" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Posici&oacute;n en el Listado:</label> <span class="text-danger">*</span>
										<input type="text" class="touchspin" name="posicion" id="posicion" onkeyup="enteros(this);" value="0" />
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
	<!-- TouchSpin -->
	<script src="../assets.1.2.8/js/plugins/touchspin/jquery.bootstrap-touchspin.min.js"></script>

	<script type="text/javascript" src="../assets.1.2.8/js/modules/auditoria/status.js"></script>
</body>

</html>