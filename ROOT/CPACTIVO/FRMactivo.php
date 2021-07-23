<?php
include_once('html_fns_activo.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$sede = $_REQUEST["sede"];
$area = $_REQUEST["area"];
if ($area != "") {
	$ClsAre = new ClsArea();
	$result = $ClsAre->get_area($area, '', '', '', '', 1);
	if (is_array($result)) {
		foreach ($result as $row) {
			$sede = trim($row["sed_codigo"]);
			$sector = trim($row["sec_codigo"]);
			$secNombre = utf8_decode($row["sec_nombre"]);
			$nivel = utf8_decode($row["are_nivel"]);
		}
	}
}
$sede = trim($_REQUEST["sede"]);
$sector = ($_REQUEST["sector"] == "") ? $sector : $_REQUEST["sector"];
$area = trim($_REQUEST["area"]);
$nombre = trim($_REQUEST["nombre"]);
$marca = trim($_REQUEST["marca"]);
$serie = trim($_REQUEST["serie"]);
$modelo = trim($_REQUEST["modelo"]);
$parte = trim($_REQUEST["parte"]);
$proveedor = trim($_REQUEST["proveedor"]);
$periodicidad = trim($_REQUEST["perfil"]);
$capacidad = trim($_REQUEST["capacidad"]);
$cantidad = trim($_REQUEST["cantidad"]);
$precioNuevo = trim($_REQUEST["precioNuevo"]);
$precioCompra = trim($_REQUEST["precioCompra"]);
$precioActual = trim($_REQUEST["precioActual"]);
$observaciones = trim($_REQUEST["observaciones"]);?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../", "ppm"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="nc-icon nc-app"></i> &nbsp; Gestor de Activos</h5>
							</div>
							<div class="card-body all-icons">
								<form name="f1" id="f1" action="" method="get">
									<div class="row">
										<div class="col-xs-6 col-md-6 text-left"><a class="btn btn-white" onclick="atras();"><i class="fa fa-chevron-left"></i> Atr&aacute;s</a> </div>
										<div class="col-xs-6 col-md-6 text-right"><label class=" text-danger">* Campos Obligatorios</label> </div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<label>Area:</label> <span class="text-danger">*</span>
											<?php echo utf8_decode(areas_sede_html("area", "setArea(this.value)", "select2")); ?>
											<input type="hidden" name="sede" id="sede" value="<?php echo $sede; ?>" />
											<input type="hidden" name="sector" id="sector" value="<?php echo $sector; ?>" />
											<script>
												document.getElementById("area").value = "<?php echo $area; ?>"
											</script>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Sector:</label> <span class="text-danger">*</span>
											<input type="text" class="form-control" name="secNombre" id="secNombre" value="<?php echo $secNombre; ?>" readonly />
										</div>
										<div class="col-md-6">
											<label>Nivel:</label> <span class="text-danger">*</span>
											<input type="text" class="form-control" name="nivel" id="nivel" value="<?php echo $nivel; ?>" readonly />
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Nombre del Activo:</label> <span class="text-danger">*</span>
											<input type="text" class="form-control" name="nombre" id="nombre" onkeyup="texto(this)" value="<?php echo $nombre; ?>" />
											<input type="hidden" name="codigo" id="codigo" value="<?php echo $codigo; ?>" />
										</div>
										<div class="col-md-6">
											<label>Marca:</label> <span class="text-danger">*</span>
											<input type="text" class="form-control" name="marca" id="marca" onkeyup="texto(this)" value="<?php echo $marca; ?>" />
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>No. de Serie:</label> <small>(No obligatorio)</small>
											<input type="text" class="form-control" name="serie" id="serie" onkeyup="texto(this)" value="<?php echo $serie; ?>" />
										</div>
										<div class="col-md-6">
											<label>Modelo:</label> <small>(No obligatorio)</small>
											<input type="text" class="form-control" name="modelo" id="modelo" onkeyup="texto(this)" value="<?php echo $modelo; ?>" />
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>No. de Parte:</label> <small>(No obligatorio)</small>
											<input type="text" class="form-control" name="parte" id="parte" onkeyup="texto(this)" value="<?php echo $parte; ?>" />
										</div>
										<div class="col-md-6">
											<label>Proveedor:</label> <small>(No obligatorio)</small>
											<input type="text" class="form-control" name="proveedor" id="proveedor" onkeyup="texto(this)" value="<?php echo $proveedor; ?>" />
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Capacidad:</label> <small>(No obligatorio)</small>
											<input type="text" class="form-control" name="capacidad" id="capacidad" onkeyup="texto(this)" value="<?php echo $capacidad; ?>" />
										</div>
										<div class="col-md-6">
											<label>Cantidad:</label> <span class="text-danger">*</span>
											<input type="text" class="form-control" name="cantidad" id="cantidad" onkeyup="enteros(this)" value="<?php echo $cantidad; ?>" />
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Periodicidad de Mantenimiento:</label> <span class="text-danger">*</span>
											<select class="form-control select2" name="periodicidad" id="periodicidad">
												<option value="">Seleccione</option>
												<option value="D">Diaria</option>
												<option value="W">Semanal</option>
												<option value="M">Mensual</option>
												<option value="Y">Anual</option>
												<option value="V">Variado</option>
											</select>
											<script>
												document.getElementById("periodicidad").value = "<?php echo $periodicidad; ?>"
											</script>
										</div>
										<div class="col-md-6">
											<label>Precio Original (Nuevo):</label> <span class="text-danger">*</span>
											<input type="text" class="form-control" name="precioNuevo" id="precioNuevo" onkeyup="decimales(this)" value="<?php echo $precioNuevo; ?>" />
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Precio de Adquicisi&oacute;n:</label> <span class="text-danger">*</span>
											<input type="text" class="form-control" name="precioCompra" id="precioCompra" onkeyup="decimales(this)" value="<?php echo $precioCompra; ?>" />
										</div>
										<div class="col-md-6">
											<label>Precio Actual:</label> <span class="text-danger">*</span>
											<input type="text" class="form-control" name="precioActual" id="precioActual" onkeyup="decimales(this)" value="<?php echo $precioActual; ?>" />
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<label>Observaciones Especiales:</label>
											<textarea class="form-control" name="observaciones" id="observaciones" rows="3" onkeyup="textoLargo(this);"><?php echo $observaciones; ?></textarea>
										</div>
									</div>
								</form>
								<br>
								<div class="row">
									<div class="col-md-12 text-center">
										<a class="btn btn-white" id="btn-limpiar" href="FRMactivo.php"><i class="fas fa-eraser"></i> Limpiar</a>
										<button type="button" class="btn btn-primary" id="btn-grabar" onclick="Grabar();"><i class="fas fa-save"></i> Grabar</button>
										<button type="button" class="btn btn-primary hidden" id="btn-modificar" onclick="Modificar();"><i class="fas fa-save"></i> Grabar</button>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-12" id="result">

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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/ppm/activo.js"></script>

	<script>
		$(document).ready(function() {
			printTable('');
			$('.select2').select2({ width: '100%' });
		});
	</script>

</body>
</html>