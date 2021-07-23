<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$id = $_SESSION["codigo"];

$categoriasIn = $_SESSION["categorias_in"];
//$_POST
$ClsRev = new ClsRevision();
$hashkey = $_REQUEST["hashkey"];
$codigo_programacion = $ClsRev->decrypt($hashkey, $id);
//--
$result = $ClsRev->get_revision_indicador('', $codigo_programacion);
if (is_array($result)) {
	foreach ($result as $row) {
		$revision = trim($row["rev_codigo"]);
		$codigo_indicador = trim($row["ind_codigo"]);
		$nombre = utf8_decode($row["ind_nombre"]);
		$usuario_nombre = trim($row["ind_usuario"]);
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
	}
} else {
	$ClsInd = new ClsIndicador();
	$result = $ClsInd->get_programacion($codigo_programacion);
	if (is_array($result)) {
		foreach ($result as $row) {
			$codigo_ind = $row["ind_codigo"];
			$nombre = utf8_decode($row["ind_nombre"]);
			$min = trim($row["ind_lectura_minima"]);
			$max = trim($row["ind_lectura_maxima"]);
			$ideal = trim($row["ind_lectura_ideal"]);
			$usuario_nombre = trim($row["ind_usuario"]);
			$unidad = utf8_decode($row["medida_nombre"]);
			$medida = utf8_decode($row["ind_unidad_medida"]);
			$sistema = utf8_decode($row["obj_sistema"]);
			$proceso = utf8_decode($row["obj_proceso"]);
			$hini = trim($row["pro_hini"]);
			$hfin = trim($row["pro_hfin"]);
			$horario = "$hini - $hfin";
			$observacion = nl2br(utf8_decode($row["pro_observaciones"]));
		}
		// --
		$codigo = $ClsRev->max_revision_indicador();
		$codigo++;
		$sql = $ClsRev->insert_revision_indicador($codigo, $codigo_programacion, 0, $id);
		$rs = $ClsRev->exec_sql($sql);
		if ($rs == 1) {
			$alerta_completa = 'swal("Apertura de Revision", "Se ha aperturado una nueva revisi\u00F3n en esta lista...", "success");';
		} else {
			$alerta_completa = 'swal("Error", "Error en la transacci\u00F3n ' . $sql . '", "error").then((value)=>{ window.history.back(); });';
		}
	} else {
		$alerta_completa = 'swal("Alto", "Este formulario de revisi\u00F3n esta fuera de horario...", "warning").then((value)=>{ window.history.back(); });';
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
			<?php echo sidebar("../", "indicador"); ?>
			<div class="main-panel">
				<?php echo navbar("../"); ?>
				<div class="content" id="result">
					<div class="row">
						<div class="col-md-6">
							<div class="card demo-icons">
								<div class="card-header">
									<h5 class="card-title">
										<i class="nc-icon nc-bullet-list-67"></i> Informaci&oacute;n
										<a class="btn btn-white btn-lg sin-margin pull-right" href="FRMindicador.php"><small><i class="fa fa-chevron-left"></i> Atr&aacute;s</small></a>
									</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-lg-12">
											<div class="row">
												<div class="col-md-12">
													<label>Usuario:</label>
													<input type="text" class="form-control" value="<?php echo $usuario_nombre; ?>" disabled />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Indicador:</label>
													<input type="text" class="form-control" value="<?php echo $nombre; ?>" disabled />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Proceso:</label>
													<input type="text" class="form-control" value="<?php echo $proceso; ?>" disabled />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Sistema:</label>
													<input type="text" class="form-control" value="<?php echo $sistema; ?>" disabled />
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
													<input type="text" class="form-control" value="<?php echo $unidad; ?>" disabled />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Lectura Minima:</label>
													<input type="text" class="form-control" value="<?php echo $min; ?>" disabled />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Lectura Ideal:</label>
													<input type="text" class="form-control" value="<?php echo $ideal; ?>" disabled />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Lectura M&aacute;xima:</label>
													<input type="text" class="form-control" value="<?php echo $max; ?>" disabled />
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
								<div class="card-body all-icons">
									<?php
									if ($observacion != "") {
									?>
										<div class="row">
											<div class="col-md-12">
												<label>Observaciones durante la programaci&oacute;n a tomar en cuenta:</label><br>
												<textarea class="form-control text-justify" rows="4" readonly><?php echo $observacion; ?></textarea>
											</div>
										</div>
										<hr>
									<?php } ?>
									<input type="hidden" name="revision" id="revision" value="<?php echo $revision ?>" />
									<div class="row">
										<div class="col-md-10 ml-auto mr-auto">
											<label>Lectura (<?php echo $unidad ?>):</label>
											<input type="number" class="form-control" id="lec" name="lec" />
										</div>
									</div>
									<div class="row">
										<div class="col-md-10 ml-auto mr-auto">
											<label>Observaciones:</label>
											<textarea class="form-control" name="obs" id="obs" onkeyup="textoLargo(this);" rows="5"></textarea>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-md-6 ml-auto mr-auto text-center">
											<button type="button" class="btn btn-default btn-lg" onclick="window.history.back();"><span class="fa fa-chevron-left"></span> Regresar</button>
											<button type="button" class="btn btn-success btn-lg" id="btn-grabar" onclick="cerrarRevision();"><span class="fa fa-folder"></span> Cerrar</button>
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
		<script type="text/javascript" src="../assets.1.2.8/js/modules/indicator/revision.js"></script>

		<script>
			$(document).ready(function() {
				$('.dataTables-example').DataTable({
					pageLength: 100,
					responsive: true,
					dom: '<"html5buttons"B>lTfgitp',
					buttons: []
				});
				$('.select2').select2({ width: '100%' });
				window.setTimeout('mensaje(<?php echo $status; ?>);', 500);
			});

			function mensaje() {
				<?php echo $alerta_completa; ?>
			}
		</script>
	</body>

	</html>
