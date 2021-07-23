<?php
include_once('html_fns_sede.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$nombre = $_REQUEST["nom"];
$zona = $_REQUEST["zona"];
$departamento = $_REQUEST["departamento"];
$departamento = ($departamento == "") ? "100" : $departamento;
$municipio = $_REQUEST["municipio"];
$municipio = ($municipio == "") ? "101" : $municipio;
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
								<h5 class="card-title"><i class="fa fa-bank"></i> Gestor de Sedes</h5>
							</div>
							<div class="card-body all-icons">
								<form name="f1" id="f1" action="" method="get">
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
											<label>Nombre de la Sede:</label> <span class="text-danger">*</span>
											<input type="text" class="form-control" name="nombre" id="nombre" onkeyup="texto(this)" />
											<input type="hidden" name="codigo" id="codigo" value="<?php echo $codigo; ?>" />
										</div>
										<div class="col-md-6">
											<label>Zona:</label> <span class="text-danger">*</span>
											<input type="text" class="form-control" name="zona" id="zona" onkeyup="enteros(this);" />
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<label>Direcci&oacute;n:</label> <span class="text-danger">*</span>
											<input type="text" class="form-control" name="direccion" id="direccion" onkeyup="texto(this);" />
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Departamento:</label> <span class="text-danger">*</span>
											<?php echo departamento_html("departamento", "getdepmun();", "select2"); ?>
										</div>
										<div class="col-md-6">
											<label>Municipio:</label> <span class="text-danger">*</span>
											<div id="divmun">
												<?php echo combos_vacios("municipio", "select2"); ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Latitud:</label> <span class="text-danger">*</span>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="fa fa-map-marker"></i></span>
												</div>
												<input type="text" class="form-control" name="latitud" id="latitud" onkeyup="decimales(this)" placeholder="Ej: 15.70202221773049" />
											</div>
										</div>
										<div class="col-md-6">
											<label>Longitud:</label> <span class="text-danger">*</span>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text"><i class="fa fa-map-marker"></i></span>
												</div>
												<input type="text" class="form-control" name="longitud" id="longitud" onkeyup="decimales(this)" placeholder="Ej: -90.29919505119324" />
											</div>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-md-3 ml-auto mr-auto text-center">
											<button type="button" class="btn btn-success btn-lg btn-block" onclick="plotMap();"><span class="fa fa-map-marker"></span> Ver Mapa <span class="fa fa-globe"></span></button>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/sedes/sede.js"></script>
</body>

</html>