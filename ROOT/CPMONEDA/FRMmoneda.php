<?php
include_once('html_fns_moneda.php');
validate_login("../");
$id = $_SESSION["codigo"];
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../", "herramientas"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-money"></i> Tasa de Cambio para Monedas</h5>
							</div>
							<div class="card-body all-icons">
								<form name="f1" id="f1">
									<div class="row">
										<div class="col-xs-6 col-md-6 text-left"><a class="btn btn-white" href="../menu.php"><i class="fa fa-chevron-left"></i> Atr&aacute;s</a> </div>
										<div class="col-xs-6 col-md-6 text-right"><label class=" text-danger">* Campos Obligatorios</label> </div>
									</div>
									<div class="row">
										<div class="col-md-4">
											<label>Moneda:</label> <span class="text-danger">*</span>
											<?php echo Moneda_html("moneda", "printTableCambio(this.value);", "select2"); ?>
										</div>
										<div class="col-md-2">
											<br>
											<button type="button" class="btn btn-success btn-outline btn-block" style="cursor:pointer" onclick="agregaMoneda();" title="Gestor de monedas">
												<i class="fa fa-cogs"></i> <i class="fa fa-plus"></i> <i class="fa fa-minus"></i>
											</button>
										</div>
										<div class="col-md-6">
											<label>Tasa de Cambio de la Empresa:</label> <span class="text-danger">*</span>
											<input type="text" class="form-control text-center" name="cambio" id="cambio" onkeyup="decimales(this)" />
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Compra:</label> <span class="text-danger">*</span>
											<input type="text" class="form-control text-center" name="compra" id="compra" onkeyup="decimales(this)" />
										</div>
										<div class="col-md-6">
											<label>Venta:</label> <span class="text-danger">*</span>
											<input type="text" class="form-control text-center" name="venta" id="venta" onkeyup="decimales(this)" />
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-md-12 text-center">
											<a class="btn btn-white" href="FRMmoneda.php"><i class="fas fa-eraser"></i> Limpiar</a>
											<button type="button" class="btn btn-primary" id="btn-tasa" onclick="GrabarTasaCambio();"><i class="fas fa-save"></i> Grabar</button>
										</div>
									</div>
								</form>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/master/moneda.js"></script>

</body>
</html>