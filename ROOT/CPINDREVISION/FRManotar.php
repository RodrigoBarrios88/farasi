<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$id = $_SESSION["codigo"];

$categoriasIn = $_SESSION["categorias_in"];
//$_POST
$ClsRev = new ClsRevision();
$hashkey = $_REQUEST["hashkey"];
$programacion = $ClsRev->decrypt($hashkey, $id);
$situacion = 0;
//--
$result = $ClsRev->get_revision_indicador('', '', $programacion);
if (is_array($result)) {
	foreach ($result as $row) {
		$revision = trim($row["rev_codigo"]);
		$lectura = trim($row["rev_lectura"]);
		$observaciones = utf8_decode($row["rev_observaciones"]);
		$situacion = trim($row["rev_situacion"]);
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
} else {
	$ClsInd = new ClsIndicador();
	$result = $ClsInd->get_programacion($programacion);
	if (is_array($result)) {
		foreach ($result as $row) {
			$codigo_ind = $row["ind_codigo"];
			$nombre = utf8_decode($row["ind_nombre"]);
			$descripcion = utf8_decode($row["ind_descripcion"]);
			$min = trim($row["ind_lectura_minima"]);
			$max = trim($row["ind_lectura_maxima"]);
			$ideal = trim($row["ind_lectura_ideal"]);
			$usuario_nombre = trim($row["ind_usuario"]);
			$objetivo = utf8_decode($row["obj_descripcion"]);
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
		$revision = $codigo;
		$sql = $ClsRev->insert_revision_indicador($codigo, $programacion, 0, $id);
		$rs = $ClsRev->exec_sql($sql);
		if ($rs == 1) {
			$alerta_completa = 'swal("Toma de datos iniciada", "Se ha aperturado una nueva toma en este indicador...", "success");';
		} else {
			$alerta_completa = 'swal("Error", "Error en la transacci\u00F3n ' . $sql . '", "error").then((value)=>{ window.history.back(); });';
		}
	} else {
		$alerta_completa = 'swal("Alto", "Este formulario de revisi\u00F3n esta fuera de horario...", "warning").then((value)=>{ window.history.back(); });';
	}
}
if ($situacion == 2) {
	echo "<form id='f1' name='f1' action='FRManotacion.php' method='post'>";
	echo "<script>document.f1.submit();</script>";
	echo "</form>";
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
				<div class=" row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="fa fa-pencil-square-o"></i> Anotaci&oacute;n
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-10 ml-auto mr-auto">
										<label>Lectura (<?php echo $unidad ?>):</label>
										<input type="number" class="form-control" id="lectura" name="lectura" onblur="modificar(this,1)" value="<?php echo $lectura; ?>" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-10 ml-auto mr-auto">
										<label>Observaciones:</label>
										<textarea class="form-control" name="observacion" id="observacion" onkeyup="textoLargo(this);" rows="5" onblur="modificar(this,2)"><?php echo $observaciones; ?></textarea>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-6 text-center">
										<div class="fileinput fileinput-new text-center" data-provides="fileinput">
											<div class="fileinput fileinput-new text-center" data-provides="fileinput">
												<div class="text-center" id="archivo1">
													<?php echo $arrArchivos[1]; ?>
												</div>
												<span class="btn btn-rose btn-round btn-file">
													<span class="fileinput-new" onclick="openInput('imagen');">
														<i class="fa fa-camera"></i> Agregar Foto
													</span>
												</span>
											</div>
										</div>
									</div>
									<div class="col-md-6 text-center">
										<div class="fileinput fileinput-new text-center" data-provides="fileinput">
											<div class="fileinput fileinput-new text-center" data-provides="fileinput">
												<div class="text-center" id="archivo2">
													<?php echo $arrArchivos[2]; ?>
												</div>
												<span class="btn btn-rose btn-round btn-file">
													<span class="fileinput-new" onclick="openInput('documento');"><i class="fa fa-file-text"></i> Agregar Documento PDF</span>
												</span>
											</div>
										</div>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-6 ml-auto mr-auto text-center">
										<input type="hidden" name="revision" id="revision" value="<?php echo $revision ?>" />
										<input type="hidden" name="evidencia" id="evidencia" value="<?php echo $arrArchivos[0] ?>" />
										<input id="documento" name="documento" type="file" multiple="false" class="hidden" onchange="upload(this,2);">
										<input id="imagen" name="imagen" type="file" multiple="false" class="hidden" onchange="upload(this,1);">
										<input type="hidden" id="posicion" name="posicion" />
										<button type="button" class="btn btn-default btn-lg" onclick="window.history.back();"><span class="fa fa-chevron-left"></span> Regresar</button>
										<button type="button" class="btn btn-success btn-lg" id="btn-grabar" onclick="cerrarRevision();"><span class="fa fa-folder"></span> Cerrar</button>
									</div>
								</div>
							</div>
							<div class="card-footer text-right">
								<hr>
								<div class="stats">
									<i class="fa fa-clock-o"></i> <?php echo "$hini - $hfin"; ?>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/indicador/revision.js"></script>
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