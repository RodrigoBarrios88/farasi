<?php
include_once('html_fns_mejora.php');
validate_login("../");
$id = $_SESSION["codigo"];

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
								<h5 class="card-title"><i class="fa fa-exclamation"></i> Gestor de Quejas</h5>
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
										<label>Proceso:</label> <span class="text-danger">*</span>
										<?php echo utf8_decode(ficha_html("proceso", "", "select2")) ?>
										<input type="hidden" name="codigo" id="codigo" />
									</div>
									<div class="col-md-6">
										<label>Sistema:</label> <span class="text-danger">*</span>
										<?php echo utf8_decode(sistema_html("sistema", "", "select2")) ?>
										<input type="hidden" name="codigo" id="codigo" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Cliente:</label> <span class="text-danger">*</span>
										<input type="text" name="cliente" id="cliente" class="form-control">
									</div>
									<div class="col-md-6">
										<label>Tipo:</label> <span class="text-danger">*</span>
										<input type="text" name="tipo" id="tipo" class="form-control">

									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Descripcion:</label> <span class="text-danger">*</span>
										<textarea class="form-control textarea-autosize" id="descripcion" name="descripcion" rows="5"></textarea>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-12 text-center">
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/mejora/quejas.js"></script>
	<script>
		$(document).ready(function() {
			$('.select2').select2({ width: '100%' });
			printTable('');
			$('.dataTables-example').DataTable({
				pageLength: 50,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: []
			});
		});
	</script>
</body>

</html>