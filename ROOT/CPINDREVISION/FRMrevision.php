<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$usuario = $_SESSION["codigo"];

//$_POST
$ClsInd = new ClsIndicador();
$ClsRev = new ClsRevision();
$hashkey = $_REQUEST["hashkey"];
$revision = $ClsInd->decrypt($hashkey, $usuario);
//--
$result = $ClsRev->get_revision_indicador($revision);
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$revision = trim($row["rev_codigo"]);
		$lectura = trim($row["rev_lectura"]);
		$observaciones = utf8_decode($row["rev_observaciones"]);
		$codigo_indicador = trim($row["ind_codigo"]);
		$nombre = utf8_decode($row["ind_nombre"]);
		$descripcion = utf8_decode($row["ind_descripcion"]);
		$usuario_nombre = trim($row["ind_usuario"]);
		$objetivo = utf8_decode($row["obj_descripcion"]);
		$sistema = utf8_decode($row["obj_sistema"]);
		$proceso = utf8_decode($row["obj_proceso"]);
		$min = trim($row["ind_lectura_minima"]);
		$max = trim($row["ind_lectura_maxima"]);
		$ideal = trim($row["ind_lectura_ideal"]);
		$usuario = trim($row["ind_usuario"]);
		$unidad = utf8_decode($row["medida_nombre"]);
		$hini = trim($row["pro_hini"]);
		$hfin = trim($row["pro_hfin"]);
		$horario = "$hini - $hfin";
		$observacion = nl2br(utf8_decode($row["pro_observaciones"]));
		$arrArchivos = get_archivos(2, $revision);
	}
	/////////// PROGRAMACION /////
	$dia = date("N");
	$result = $ClsInd->get_programacion($codigo_progra, $codigo_indicador);
	if (is_array($result)) {
		$i = 0;
		foreach ($result as $row) {
			$hini = trim($row["pro_hini"]);
			$hfin = trim($row["pro_hfin"]);
			$horario = "$hini - $hfin";
		}
	}
}?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../", "indicador"); ?>
		<div class="main-panel">

			<?php echo navbar("../"); ?>
			<div class="content">
				<fieldset disabled>
					<div class="row">
						<div class="col-md-6">
							<div class="card demo-icons">
								<div class="card-header">
									<h5 class="card-title">
										<i class="nc-icon nc-bullet-list-67"></i> Informaci&oacute;n
									</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-lg-12">
											<div class="row">
												<div class="col-md-12">
													<label>Usuario:</label>
													<input type="text" class="form-control" value="<?php echo $usuario_nombre; ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Indicador:</label>
													<input type="text" class="form-control" value="<?php echo $nombre; ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Proceso:</label>
													<input type="text" class="form-control" value="<?php echo $proceso; ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Sistema:</label>
													<input type="text" class="form-control" value="<?php echo $sistema; ?>" />
												</div>
											</div>
										</div>
									</div>
									<br>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="card demo-icons">
								<div class="card-header">
									<h5 class="card-title">
										<i class="fa fa-clock"></i> Planificaci&oacute;n
									</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-lg-12">
											<div class="row">
												<div class="col-md-12">
													<label>Unidad de Medida:</label>
													<input type="text" class="form-control" value="<?php echo $unidad; ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Lectura Minima:</label>
													<input type="text" class="form-control" value="<?php echo $min; ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Lectura Ideal:</label>
													<input type="text" class="form-control" value="<?php echo $ideal; ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Lectura M&aacute;xima:</label>
													<input type="text" class="form-control" value="<?php echo $max; ?>" />
												</div>
											</div>
										</div>
									</div>
									<br>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card demo-icons">
								<div class="card-header">
									<h5 class="card-title">
										<i class="fa fa-file-text-o"></i> Descripci&oacute;n
									</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-lg-12">
											<div class="row">
												<div class="col-md-12">
													<label>Objetivo:</label>
													<textarea class="form-control" onkeyup="textoLargo(this);" rows="3"><?php echo $objetivo; ?></textarea>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Indicador:</label>
													<textarea class="form-control" onkeyup="textoLargo(this);" rows="3"><?php echo $descripcion; ?></textarea>
												</div>
											</div>
											<?php
											if ($observacion != "") {
											?>
												<div class="row">
													<div class="col-md-12">
														<label>Observaciones a tomar en cuenta en esta anotaci&oacute;n:</label><br>
														<textarea class="form-control text-justify" rows="3"><?php echo $observacion; ?></textarea>
													</div>
												</div>
											<?php } ?>
										</div>
									</div>
									<br>
								</div>
							</div>
						</div>
					</div>
				</fieldset>
				<?php if ($situacion == 1) { ?>
					<div class="row">
						<div class="col-md-12">
							<h5 class="alert alert-info text-center">
								<i class="fa fa-info-circle"></i> Lista en proceso (abierta) desde <?php echo $fecha_inicio; ?>...
							</h5>
						</div>
					</div>
				<?php } ?>
				<div class="row">
					<input type="hidden" name="codigo" id="codigo" value="<?php echo $revision ?>" />
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="nc-icon nc-pin-3"></i> Revisi&oacute;n No. <?php echo Agrega_Ceros($revision); ?> de Fecha <?php echo $fecha_finaliza; ?>
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-10 ml-auto mr-auto">
										<label>Observaciones:</label>
										<textarea class="form-control" id="obs" rows="4" readonly><?php echo $obs; ?></textarea>
									</div>
								</div>
								<div class="row">
									<div class="col-md-10 ml-auto mr-auto">
										<label>Lectura (<?php echo $umed ?>):</label>
										<input type="number" class="form-control" value="<?php echo $lectura ?>" id="lec" name="lec" readonly />
									</div>
								</div>
								<br>
								<br>
								<div class="row">
									<div class="col-md-6 ml-auto mr-auto text-center">
										<button type="button" class="btn btn-default btn-lg" onclick="window.history.back();"><span class="fa fa-chevron-left"></span> Regresar</button>
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
	<!-- --- -->

	<script type="text/javascript" src="../assets.1.2.8/js/modules/indicador/indicador.js"></script>
	<script>
		$(document).ready(function() {
			$('.dataTables-example').DataTable({
				pageLength: 100,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: []
			});
			$('.select2').select2({ width: '100%' });
		});
	</script>

</body>
</html>