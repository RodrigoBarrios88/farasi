<?php
include_once('html_fns_umedidas.php');
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
		<?php echo sidebar("../", "herramientas"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-tags"></i> Gestor de Unidades de Medida</h5>
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
									<div class="col-md-4">
										<label>S&iacute;mbolo o Abreviatura:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="abrev" id="abrev" onkeyup="texto(this)" />
									</div>
									<div class="col-md-7">
										<label>Clase:</label> <span class="text-danger">*</span>
										<select name="clase" id="clase" class="form-control select2" onchange="">
											<option value="">Seleccione</option>
											<option value="$">Moneda</option>
											<option value="E">Energia</option>
											<option value="M">Dimensiones</option>
											<option value="P">Peso</option>
											<option value="S">Area</option>
											<option value="C">Volumen</option>
											<option value="T">Tiempo</option>
											<option value="1">Otros</option>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="col-md-11">
										<label>Nombre de la unidad:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="desc" id="desc" onkeyup="texto(this)" />
										<input type="hidden" name="codigo" id="codigo" />
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/master/unidades_medida.js"></script>
</body>

</html>