<?php
include_once('html_fns_biblioteca.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$anio = date("Y");
$anio++;
$fecha = date("d/m/$anio");?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../", "biblioteca"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-book-open"></i> Gestor de Documentos para la Biblioteca</h5>
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
										<label>Categor&iacute;a:</label> <span class="text-danger">*</span>
										<?php echo utf8_decode(categorias_biblioteca_html("categoria", "", "select2")); ?>
									</div>
									<div class="col-md-6">
										<label>C&oacute;digo Interno:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="codint" id="codint" onkeyup="texto(this)" />
										<input type="hidden" name="codigo" id="codigo" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Usuario Responsable:</label> <span class="text-danger">*</span>
										<?php echo utf8_decode(usuarios_html("usuario", "", "select2")); ?>
									</div>
									<div class="col-md-6">
										<label>Fecha de Vencimiento:</label> <span class="text-danger">*</span>
										<div class="form-group">
											<div class="input-group date">
												<input type="text" class="form-control" name="fecvence" id="fecvence" value="<?php echo $fecha; ?>" />
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>T&iacute;tulo:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="titulo" id="titulo" onkeyup="texto(this)" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Descripci&oacute;n del Documento:</label> <span class="text-muted">(opcional)</span>
										<textarea class="form-control" name="descripcion" id="descripcion" rows="2" onkeyup="textoLargo(this);"></textarea>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/biblioteca/biblioteca.js"></script>
	<script>
		$(document).ready(function() {
			printTable('');
			$('.select2').select2({ width: '100%' });

			$('.input-group.date').datepicker({
				format: 'dd/mm/yyyy',
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
			});
		});
	</script>

</body>
</html>