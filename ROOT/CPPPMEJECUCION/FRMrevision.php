<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$usuario = $_SESSION["codigo"];

$categoriasIn = $_SESSION["categorias_in"];
//$_POST
$ClsCue = new ClsCuestionarioPPM();
$ClsPro = new ClsProgramacionPPM();
$hashkey = $_REQUEST["hashkey"];
$codigo = $ClsPro->decrypt($hashkey, $usuario);
//--
$result = $ClsPro->get_programacion($codigo);
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$codigo = trim($row["pro_codigo"]);
		$sede = utf8_decode($row["sed_nombre"]);
		$sector = utf8_decode($row["sec_nombre"]);
		$area = utf8_decode($row["are_nombre"]);
		$nivel = utf8_decode($row["are_nivel"]);
		$activo_codigo = utf8_decode($row["act_codigo"]);
		$activo = utf8_decode($row["act_nombre"]);
		$marca = utf8_decode($row["act_marca"]);
		$proveedor = utf8_decode($row["act_proveedor"]);
		$periodicidad = utf8_decode($row["act_periodicidad"]);
		$capacidad = utf8_decode($row["act_capacidad"]);
		$cantidad = trim($row["act_cantidad"]);
		$observaciones = utf8_decode($row["act_observaciones"]);
		//--
		$nombre_usuario = utf8_decode($row["usu_nombre"]);
		$categoria = utf8_decode($row["cat_nombre"]);
		$presupuesto = trim($row["pro_presupuesto_programado"]);
		$moneda = utf8_decode($row["mon_simbolo"]);
		$cuestionario = trim($row["pro_cuestionario"]);
		//--
		$fecha = trim($row["pro_fecha"]);
		$fecha = cambia_fecha($fecha);
		//--
		$programado = trim($row["pro_fecha"]);
		$ahora = date("Y-m-d");
		$vencimiento = comparaFechas($programado, $ahora);
		//--
		$strFoto1 = trim($row["pro_foto1"]);
		$strFoto2 = trim($row["pro_foto2"]);
		$strFirma = trim($row["pro_firma"]);
		//--
		$fecha_update = trim($row["pro_fecha_update"]);
		$fecha_update = cambia_fechaHora($fecha_update);
		$fecha_update = substr($fecha_update, 0, 16);
		$obs_progra = utf8_decode($row["pro_observaciones_programacion"]);
		$obs_progra = nl2br($obs_progra);
		$obs_ejecuta = utf8_decode($row["pro_observaciones_ejecucion"]);
		$obs_ejecuta = nl2br($obs_ejecuta);
		//
		$situacion = trim($row["pro_situacion"]);
	}
	if (file_exists('../../CONFIG/Fotos/PPMFIRMAS/' . $strFirma . '.jpg') && $strFirma != "") {
		$strFirma = 'Fotos/PPMFIRMAS/' . $strFirma . '.jpg';
	} else {
		$strFirma = "img/imageSign.jpg";
	}

	if (file_exists('../../CONFIG/Fotos/PPM/' . $strFoto1 . '.jpg') && $strFoto1 != "") {
		$strFoto1 = 'Fotos/PPM/' . $strFoto1 . '.jpg';
	} else {
		$strFoto1 = "img/imagePhoto.jpg";
	}

	if (file_exists('../../CONFIG/Fotos/PPM/' . $strFoto2 . '.jpg') && $strFoto2 != "") {
		$strFoto2 = 'Fotos/PPM/' . $strFoto2 . '.jpg';
	} else {
		$strFoto2 = "img/imagePhoto.jpg";
	}
}
$ClsAct = new ClsActivo();
$result = $ClsAct->get_fotos('', $activo_codigo, 1);
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$fotCodigo = trim($row["fot_codigo"]);
		$posicion = trim($row["fot_posicion"]);
		$actFoto1 = trim($row["fot_foto"]);
		if (file_exists('../../CONFIG/Fotos/ACTIVOS/' . $actFoto1 . '.jpg') || $actFoto1 != "") {
			$actFoto1 = '<img  src="../../CONFIG/Fotos/ACTIVOS/' . $actFoto1 . '.jpg" alt="...">';
		} else {
			$actFoto1 = '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
		}
	}
} else {
	$actFoto1 = '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
}
$result = $ClsAct->get_fotos('', $activo_codigo, 2);
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$fotCodigo = trim($row["fot_codigo"]);
		$posicion = trim($row["fot_posicion"]);
		$actFoto2 = trim($row["fot_foto"]);
		if (file_exists('../../CONFIG/Fotos/ACTIVOS/' . $actFoto2 . '.jpg') || $actFoto2 != "") {
			$actFoto2 = '<img  src="../../CONFIG/Fotos/ACTIVOS/' . $actFoto2 . '.jpg" alt="...">';
		} else {
			$actFoto2 = '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
		}
	}
} else {
	$actFoto2 = '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
}
$result = $ClsAct->get_fotos('', $activo_codigo, 3);
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$fotCodigo = trim($row["fot_codigo"]);
		$posicion = trim($row["fot_posicion"]);
		$actFoto3 = trim($row["fot_foto"]);
		if (file_exists('../../CONFIG/Fotos/ACTIVOS/' . $actFoto3 . '.jpg') || $actFoto3 != "") {
			$actFoto3 = '<img  src="../../CONFIG/Fotos/ACTIVOS/' . $actFoto3 . '.jpg" alt="...">';
		} else {
			$actFoto3 = '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
		}
	}
} else {
	$actFoto3 = '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
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
					<div class="col-md-6">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="nc-icon nc-pin-3"></i> Ubicaci&oacute;n
									<button type="button" class="btn btn-white btn-lg sin-margin pull-right" onclick="window.history.back();"><small><i class="fa fa-chevron-left"></i> Atr&aacute;s</small></button>
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-xs-6 col-md-6 text-left"> </div>
								</div>
								<div class="row">
									<div class="col-lg-12" id="result">
										<div class="row">
											<div class="col-md-12">
												<label>Sede:</label>
												<input type="text" class="form-control" value="<?php echo $sede; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Sector:</label>
												<input type="text" class="form-control" value="<?php echo $sector; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>&Aacute;rea:</label><br>
												<input type="text" class="form-control" value="<?php echo $area; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Nivel:</label><br>
												<input type="text" class="form-control" value="<?php echo $nivel; ?>" disabled />
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
									<i class="nc-icon nc-bullet-list-67"></i> Informaci&oacute;n
									<a class="btn btn-white btn-lg sin-margin pull-right" href="CPREPORTES/REPrevision.php?hashkey=<?php echo $hashkey; ?>" target="_blank"><small><i class="fa fa-print"></i> Imprimir</small></a>
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12" id="result">
										<div class="row">
											<div class="col-md-12">
												<label>Categor&iacute;a:</label>
												<input type="text" class="form-control" value="<?php echo $categoria; ?>" disabled />
												<input type="hidden" id="codigo" name="codigo" value="<?php echo $codigo; ?>" />
												<input type="hidden" id="cuestionario" name="cuestionario" value="<?php echo $cuestionario; ?>" />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Usuario que Ejecuta:</label><br>
												<input type="text" class="form-control" value="<?php echo $nombre_usuario; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Fecha Programada:</label><br>
												<input type="text" class="form-control" value="<?php echo $fecha; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Presupuesto:</label><br>
												<input type="text" class="form-control" value="<?php echo $moneda; ?>. <?php echo $presupuesto; ?>" disabled />
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
								<div class="row">
									<div class="col-md-12">
										<label>Observaciones en la Programac&oacute;n:</label>
										<textarea class="form-control" rows="3" disabled><?php echo $obs_progra; ?></textarea>
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
								<div class="row">
									<div class="col-md-6">
										<label>Nombre del Activo:</label>
										<input type="text" class="form-control" disabled value="<?php echo $activo; ?>" />
									</div>
									<div class="col-md-6">
										<label>Marca:</label>
										<input type="text" class="form-control" disabled value="<?php echo $marca; ?>" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Proveedor:</label>
										<input type="text" class="form-control" disabled value="<?php echo $proveedor; ?>" />
									</div>
									<div class="col-md-6">
										<label>Cantidad:</label>
										<input type="text" class="form-control" disabled value="<?php echo $cantidad; ?>" />
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-4 text-center">
										<div class="fileinput fileinput-new text-center" data-provides="fileinput">
											<div class="fileinput fileinput-new text-center" data-provides="fileinput">
												<div class="text-center">
													<?php echo $actFoto1; ?>
												</div>
												<label>Activo Foto 1</label>
											</div>
										</div>
									</div>
									<div class="col-md-4 text-center">
										<div class="fileinput fileinput-new text-center" data-provides="fileinput">
											<div class="fileinput fileinput-new text-center" data-provides="fileinput">
												<div class="text-center">
													<?php echo $actFoto2; ?>
												</div>
												<label>Activo Foto 2</label>
											</div>
										</div>
									</div>
									<div class="col-md-4 text-center">
										<div class="fileinput fileinput-new text-center" data-provides="fileinput">
											<div class="fileinput fileinput-new text-center" data-provides="fileinput">
												<div class="text-center" id="foto3">
													<?php echo $actFoto3; ?>
												</div>
												<label>Activo Foto 3</label>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Observaciones Especiales del Activo:</label>
										<textarea class="form-control" rows="3" disabled><?php echo $observaciones; ?></textarea>
									</div>
								</div>
								<br>
							</div>
						</div>
					</div>
				</div>
				<?php if ($situacion == 1 && $vencimiento == 2) { ?>
					<div class="row">
						<div class="col-md-12">
							<h5 class="alert alert-danger text-center">
								<i class="fa fa-warning"></i> Actividad Vencida desde <?php echo $fecha; ?>...
							</h5>
						</div>
					</div>
				<?php } else if ($situacion == 2) { ?>
					<div class="row">
						<div class="col-md-12">
							<h5 class="alert alert-warning text-center">
								<i class="fa fa-clock-o"></i> Actividad en Espera desde <?php echo $fecha_update; ?>...
							</h5>
						</div>
					</div>
				<?php } else if ($situacion == 3) { ?>
					<div class="row">
						<div class="col-md-12">
							<h5 class="alert alert-info text-center">
								<i class="fa fa-users-cog"></i> Actividad en proceso (abierta) desde <?php echo $fecha_update; ?>...
							</h5>
						</div>
					</div>
				<?php } else if ($situacion == 4) { ?>
					<div class="row">
						<div class="col-md-12">
							<h5 class="alert alert-success text-center">
								<i class="fa fa-check"></i> Actividad Finalizada desde <?php echo $fecha_update; ?>...
							</h5>
						</div>
					</div>
				<?php } ?>

				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="nc-icon nc-pin-3"></i> Programaci&oacute;n No. <?php echo Agrega_Ceros($codigo); ?>. <small>Fecha de Actualizaci&oacute;n <?php echo $fecha_update; ?></small>
								</h5>
							</div>
							<div class="card-body all-icons">
								<br>
								<?php
								$result = $ClsCue->get_pregunta('', $cuestionario, '', 1);
								if (is_array($result)) {
									$i = 1;
									foreach ($result as $row) {
										$pregunta_codigo = $row["pre_codigo"];
										$pregunta = utf8_decode($row["pre_pregunta"]);
										$pregunta = nl2br($pregunta);
										//--
										$respuesta = "";
										$result_respuesta = $ClsPro->get_respuesta($codigo, $cuestionario, $pregunta_codigo);
										if (is_array($result_respuesta)) {
											foreach ($result_respuesta as $row_respuesta) {
												$respuesta = utf8_decode($row_respuesta["resp_respuesta"]);
											}
										}
										if ($respuesta == 1) {
											$respSI = "active";
											$respNO = "";
											$respNA = "";
										} else if ($respuesta == 2) {
											$respSI = "";
											$respNO = "active";
											$respNA = "";
										} else if ($respuesta == 3) {
											$respSI = "";
											$respNO = "";
											$respNA = "active";
										} else {
											$respSI = "";
											$respNO = "";
											$respNA = "";
										}
										$salida = ""; ///limpia la cadena por cada vuelta
										$salida .= '<div class="btn-group btn-group-toggle">';
										$salida .= '<label class="btn btn-white ' . $respSI . '">';
										$salida .= ' <i class="fa fa-check"></i> Si';
										$salida .= '</label>';
										//--
										$salida .= '<label class="btn btn-white ' . $respNA . '">';
										$salida .= ' No Aplica';
										$salida .= '</label>';
										//--
										$salida .= '<label class="btn btn-white ' . $respNO . '" >';
										$salida .= ' No <i class="fa fa-times"></i>';
										$salida .= '</label>';
										$salida .= '</div>';

								?>
										<div class="row">
											<div class="col-md-1 text-right"><strong><?php echo $i; ?>.</strong></div>
											<div class="col-md-10">
												<p class="text-justify"><?php echo $pregunta; ?></p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-10 ml-auto mr-auto"><?php echo $salida; ?></div>
										</div>
										<br>
								<?php
										$i++;
									}
								} else {
								}
								?>
								<hr>
								<div class="row">
									<div class="col-md-10 ml-auto mr-auto">
										<label>Observaciones al ejecutar:</label>
										<textarea class="form-control" disabled rows="4" onkeyup="textoLargo(this)"><?php echo $obs_ejecuta; ?></textarea>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-4 text-center">
										<div class="fileinput fileinput-new text-center" data-provides="fileinput">
											<div class="fileinput-new thumbnail" id="foto1">
												<img src="../../CONFIG/<?php echo $strFoto1; ?>" alt="...">
											</div>
										</div>
										<label>Foto del Antes</label>
									</div>
									<div class="col-md-4 text-center">
										<div class="fileinput fileinput-new text-center" data-provides="fileinput">
											<div class="fileinput-new thumbnail" id="foto2">
												<img src="../../CONFIG/<?php echo $strFoto2; ?>" alt="...">
											</div>
										</div>
										<label>Foto del Despu&eacute;s</label>
									</div>
									<div class="col-md-4 text-center">
										<div class="fileinput fileinput-new text-center" data-provides="fileinput">
											<div class="fileinput-new thumbnail">
												<img src="../../CONFIG/<?php echo $strFirma; ?>" alt="...">
											</div>
										</div>
										<label>Firma del Supervisor</label>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-12 text-center">
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/ppm/ejecucion.js"></script>
	<script>
		$(document).ready(function() {
			$('.dataTables-example').DataTable({
				pageLength: 100,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: [

				]
			});

			$('.select2').select2({ width: '100%' });
		});
	</script>

</body>

</html>