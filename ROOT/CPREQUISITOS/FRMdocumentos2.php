<?php
include_once('html_fns_requisitos.php');
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
		<?php echo sidebar("../", "requisitos"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-book-open"></i> Gestor de Documentos Aplicables a Requisitos</h5>
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
										<label for="titulo">Titulo del Documento:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="titulo" id="titulo" onkeyup="texto(this)" />
									</div>
									<div class="col-md-6">
										<label for="tipo" title="Ley, reglamento, norma...">Tipo:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="tipo" id="tipo" onkeyup="texto(this)" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label for="entidad" title="Ministerio, direcci&oacute;n, ente certificador...">Entidad:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="entidad" id="entidad" onkeyup="texto(this)" />
									</div>
									<div class="col-md-6">
										<label>Fecha de Entrada en Vigencia:</label> <span class="text-danger">*</span>
										<input type="date" class="form-control" name="vigencia" id="vigencia" onkeyup="texto(this)" />

									</div>
									<!--		<div class="col-md-6">
										<label title='...Gestor de sistemas en "Process Manager->Gestor de Sistemas"'>Sistema:</label> <span class="text-danger">*</span>
										<?/*php echo utf8_decode(sistema_html("sistema", "", "select2"))*/ ?>
								
										 
									</div>-->
									<input type="hidden" name="codigo" id="codigo" />
								</div>
								<div class="row">

									<input type="hidden" id="situacion" name="situacion" value="">
								</div>
								<br>
								<div class="row">
									<div class="col-md-12 text-center">
										<button type="button" class="btn btn-white" id="btn-limpiar" onclick="Limpiar();"><i class="fas fa-eraser"></i> Limpiar</button>
										<button type="button" class="btn btn-primary" id="btn-grabar" onclick="Grabar();"><i class="fas fa-save"></i> Grabar</button>
										<button type="button" class="btn btn-primary hidden" id="btn-modificar" onclick="Modificar();"><i class="fas fa-save"></i> Modificar</button>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/requisitos/documento2.js"></script>
	<script>
		$(document).ready(function() {
			printTable('');
			$('.select2').select2({
				width: '100%'
			});
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