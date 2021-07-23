<?php
include_once('html_fns_programacion.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$ClsPro = new ClsProgramacionPPM();
$hashkey = $_REQUEST["hashkey"];
$usuario = $_SESSION["codigo"];
$codigo = $ClsPro->decrypt($hashkey, $usuario);
//--
$result = $ClsPro->get_programacion($codigo);
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$sede = utf8_decode($row["sed_nombre"]);
		$area = utf8_decode($row["are_nombre"]);
		$activo = utf8_decode($row["act_nombre"]);
		$usuario = utf8_decode($row["usu_nombre"]);
		$fecha = cambia_fecha($row["pro_fecha"]);
		$presupuesto = trim($row["pro_presupuesto_programado"]);
		$moneda = trim($row["pro_moneda"]);
		$categoria = trim($row["pro_categoria"]);
		$cuestionario = trim($row["pro_cuestionario"]);
		$observaciones = utf8_decode($row["pro_observaciones_programacion"]);
	}
}
?>
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
								<h5 class="card-title"><i class="fa fa-users-cog"></i> Programaci&oacute;n de Ordenes de Trabajo (Actualizaci&oacute;n)</h5>
							</div>
							<div class="card-body all-icons">
								<form name="f1" id="f1" action="" method="get">
									<div class="row">
										<div class="col-xs-6 col-md-6 text-left">
											<button type="button" class="btn btn-white" onclick="window.history.back();"><i class="fa fa-chevron-left"></i> Atr&aacute;s</button>
										</div>
										<div class="col-xs-6 col-md-6 text-right"><label class=" text-danger">* Campos Obligatorios</label> </div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Sede:</label> <span class="text-danger">*</span>
											<input type="text" class="form-info" name="sede" id="sede" value="<?php echo $sede; ?>" disabled />
											<input type="hidden" name="codigo" id="codigo" value="<?php echo $codigo; ?>" />
										</div>
										<div class="col-md-6">
											<label>Area:</label> <span class="text-danger">*</span>
											<input type="text" class="form-info" name="area" id="area" value="<?php echo $area; ?>" disabled />
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Activo:</label> <span class="text-danger">*</span>
											<input type="text" class="form-info" name="activo" id="activo" value="<?php echo $activo; ?>" disabled />
										</div>
										<div class="col-md-6">
											<label>Usuario a Asignar:</label> <span class="text-danger">*</span>
											<input type="text" class="form-info" name="usuario" id="usuario" value="<?php echo $usuario; ?>" disabled />
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-md-6">
											<label>Categor&iacute;a:</label> <span class="text-danger">*</span>
											<?php echo utf8_decode(categorias_ppm_html("categoria", "", "select2")); ?>
											<script>
												document.getElementById("categoria").value = "<?php echo $categoria; ?>";
											</script>
										</div>
										<div class="col-md-6">
											<label>Cuestionario:</label> <span class="text-danger">*</span>
											<?php echo utf8_decode(cuestionario_html("cuestionario", "", "select2")); ?>
											<script>
												document.getElementById("cuestionario").value = "<?php echo $cuestionario; ?>";
											</script>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
											<label>Presupuesto:</label> <small class="text-muted">(moneda 0.00)</small> <span class="text-danger">*</span>
											<input type="text" class="form-control" name="presupuesto" id="presupuesto" onkeyup="decimales(this)" value="<?php echo $presupuesto; ?>" />
										</div>
										<div class="col-md-3">
											<label>Moneda:</label> <span class="text-danger">*</span>
											<?php echo utf8_decode(moneda_simbolo_html("moneda", "", "select2")); ?>
											<script>
												document.getElementById("moneda").value = "<?php echo $moneda; ?>";
											</script>
										</div>
										<div class="col-md-6">
											<label>Fechas:</label> <span class="text-danger">*</span>
											<div class="form-group">
												<div class="input-group date">
													<input type="text" class="form-control" name="fecha" id="fecha" value="<?php echo $fecha; ?>">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<label>Observaciones:</label>
											<textarea class="form-control" name="observacion" id="observacion" rows="3" onkeyup="textoLargo(this);"><?php echo $observaciones; ?></textarea>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-md-12 text-center">
											<button type="button" class="btn btn-white" id="btn-limpiar" onclick="window.history.back();"><i class="fas fa-eraser"></i> Limpiar</button>
											<button type="button" class="btn btn-primary" id="btn-grabar" onclick="Modificar();"><i class="fas fa-save"></i> Grabar</button>
										</div>
									</div>
								</form>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/ppm/programacion.js"></script>
	<script>
		$(document).ready(function() {
			$('.dataTables-example').DataTable({
				pageLength: 100,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: [

				]
			});

			$('.input-group.date').datepicker({
				format: 'dd/mm/yyyy',
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
			});

			$('.select2').select2({ width: '100%' });
		});
	</script>

</body>

</html>