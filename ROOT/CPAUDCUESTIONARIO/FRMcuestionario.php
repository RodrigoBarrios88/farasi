<?php
include_once('html_fns_cuestionario.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$nombre = $_REQUEST["nom"];
$zona = $_REQUEST["zona"];
$dep = $_REQUEST["dep"];
$dep = ($dep == "") ? "100" : $dep;
$mun = $_REQUEST["mun"];
$mun = ($mun == "") ? "101" : $mun;?>
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
								<h5 class="card-title"><i class="fa fa-check-square-o"></i> Gestor de Cuestionarios de Auditoria</h5>
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
										<?php echo utf8_decode(categorias_auditoria_html("categoria", "", "select2")); ?>
									</div>
									<div class="col-md-6">
										<label>Criterios:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="criterio" id="criterio" onkeyup="texto(this)" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Nombre o T&iacute;tulo:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="nombre" id="nombre" onkeyup="texto(this)" />
										<input type="hidden" name="codigo" id="codigo" value="<?php echo $codigo; ?>" />
									</div>
									<div class="col-md-6">
										<label>Tipo de Ponderaci&oacute;n:</label> <span class="text-danger">*</span>
										<select class="form-control select2" name="pondera" id="pondera">
											<option value="">Seleccione</option>
											<option value="1">1 a 10</option>
											<option value="2">Si y No (con pesos ponderados)</option>
											<option value="3">Satisfactorio / No Satisfactorio</option>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Objetivo General:</label> <span class="text-muted">(opcional)</span>
										<textarea class="form-control textarea-autosize" name="objetivo" id="objetivo" rows="1" onkeyup="textoLargo(this);"></textarea>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Riesgos Recurrentes:</label> <span class="text-muted">(opcional)</span>
										<textarea class="form-control textarea-autosize" name="riesgo" id="riesgo" rows="1" onkeyup="textoLargo(this);"></textarea>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Alcance General:</label> <span class="text-muted">(opcional)</span>
										<textarea class="form-control textarea-autosize" name="alcance" id="alcance" rows="1" onkeyup="textoLargo(this);"></textarea>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/auditoria/cuestionario.js"></script>


</body>
</html>