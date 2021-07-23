<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$id = $_SESSION["codigo"];
//$_POST
$ClsAct = new ClsActividad();
$ClsHal = new ClsHallazgo();
$hashkey = $_REQUEST["hashkey"];
$codigo = $ClsAct->decrypt($hashkey, $id);
$result = $ClsAct->get_programacion_mejora($codigo);
if (is_array($result)) {
	foreach ($result as $row) {
		$situacion = trim($row["pro_situacion"]);
		switch ($situacion) {
			case 3:
				$sql = $ClsAct->cambia_situacion_programacion_mejora($codigo, "", 4); // la abre
				$rs = $ClsAct->exec_sql($sql);
				if ($rs != 1) {
					$arr_respuesta = array(
						"status" => false,
						"sql" => $sql,
						"data" => [],
						"message" => "Error en la transacción..." . $sql
					);
					echo json_encode($arr_respuesta);
				}
				break;
			case 5: // cerrada
				echo "<form id='f1' name='f1' action='FRMejecucion.php' method='post'>";
				echo "<script>document.f1.submit();</script>";
				echo "</form>";
				break;
		}
		$evaluacion = utf8_decode($row["pro_evaluacion"]);
		$puntuacion = trim($row["pro_puntuacion"]);
		$observacion = utf8_decode($row["pro_ejecucion"]);
		$fini = cambia_fecha($row["pro_fecha_inicio"]);
		$ffin = cambia_fecha($row["pro_fecha_fin"]);
		$programacion = $fini . " - " . $ffin;
		$arrArchivos = get_archivos(2, $codigo);
		$fini = cambia_fecha($row["act_fecha_inicio"]);
		$ffin = cambia_fecha($row["act_fecha_fin"]);
		$actividad = utf8_decode($row["act_descripcion"]);
		$periodicidad = get_periodicidad($row["act_periodicidad"]);
		// --
		$plan = trim($row["pla_codigo"]);
		$origen = trim($row["hal_origen"]);
		$hallazgo = trim($row["pla_hallazgo"]);
		switch ($origen) {
			case 1:
				$info = $ClsHal->get_hallazgo_auditoria_interna($hallazgo, "", "", $sistema_codigo);
				break;
			case 2:
				$info = $ClsHal->get_hallazgo_auditoria_externa($hallazgo, "", "", $sistema_codigo);
				break;
			case 3:
				$info = $ClsHal->get_hallazgo_queja($hallazgo, "", "", $sistema_codigo);
				break;
			case 4:
				$info = $ClsHal->get_hallazgo_indicador($hallazgo, "", "", $sistema_codigo);
				break;
			case 5:
				$info = $ClsHal->get_hallazgo_riesgo($hallazgo, "", "", $sistema_codigo);
				break;
			case 6:
				$info = $ClsHal->get_hallazgo_requisito($hallazgo, "", "", $sistema_codigo);
				break;
		}
		if (is_array($info)) {
			foreach ($info as $row) {
				$proceso = utf8_decode($row["fic_nombre"]);
				$sistema = utf8_decode($row["sis_nombre"]);
				$hallazgo = utf8_decode($row["hal_descripcion"]);
				$tipo = get_tipo($row["hal_tipo"]);
				$origen = get_origen($row["hal_origen"]);
				$fecha = cambia_fechaHora($row["hal_fecha"]);
				$usuario = utf8_decode($row["usu_nombre"]);
			}
		}
	}
} ?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
	<link src="../assets.1.2.8/css/plugins/touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
</head>

<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../", "mejora"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<fieldset disabled>
				<div class="row">
						<div class="col-md-12">
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
												<div class="col-md-6">
													<label>Proceso:</label>
													<input type="text" class="form-control" value="<?php echo $proceso; ?>" />
												</div>
												<div class="col-md-6">
													<label>Sistema:</label>
													<input type="text" class="form-control" value="<?php echo $sistema; ?>" />
												</div>
											</div>

											<div class="row">
												<div class="col-md-6">
													<label>Tipo:</label>
													<input type="text" class="form-control" value="<?php echo $tipo ?>" />
												</div>
												<div class="col-md-6">
													<label>Origen:</label>
													<input type="text" class="form-control" value="<?php echo $origen ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<label>Fecha del Hallazgo:</label>
													<input type="text" class="form-control" value="<?php echo $fecha ?>" />
												</div>
												<div class="col-md-6">
													<label>Usuario que Registra:</label>
													<input type="text" class="form-control" value="<?php echo $usuario ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-lg-12">
													<div class="row">
														<div class="col-md-12">
															<label>Hallazgo:</label>
															<textarea class="form-control textarea-autosize" onkeyup="textoLargo(this);" rows="2"><?php echo $hallazgo; ?></textarea>
														</div>
													</div>
													<?php if ($justificacion != ""): ?>
														<div class="row">
															<div class="col-md-12">
																<label>Justificacion:</label>
																<textarea class="form-control textarea-autosize" onkeyup="textoLargo(this);" rows="2"><?php echo $justificacion; ?></textarea>
															</div>
														</div>
													<?php endif ?>
												</div>
												<br>
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
										<i class="fa fa-clock-o"></i> Planificaci&oacute;n
									</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-lg-12">
											<div class="row">
												<div class="col-md-12">
													<label>Actividad:</label>
													<textarea class="form-control textarea-autosize"><?php echo $actividad; ?></textarea>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<label>Periodicidad:</label>
													<input type="text" class="form-control" value="<?php echo $periodicidad; ?>" />
												</div>
												<div class="col-md-6">
													<label>Programaci&oacute;n:</label>
													<input type="text" class="form-control" value="<?php echo $programacion; ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<label>Inicio de Actividad:</label>
													<input type="text" class="form-control" value="<?php echo $fini; ?>" />
												</div>
												<div class="col-md-6">
													<label>Fin de Actividad:</label>
													<input type="text" class="form-control" value="<?php echo $ffin; ?>" />
												</div>
											</div>
										</div>
									</div>
								</div>
								<br>
							</div>
						</div>
					</div>
					<div class=" row">
						<div class="col-md-12">
							<div class="card demo-icons">
								<div class="card-header">
									<h5 class="card-title">
										<i class="fa fa-pencil-square-o"></i> Ejecuci&oacute;n
									</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-md-10 ml-auto mr-auto">
											<label>Observaciones:</label>
											<textarea class="form-control textarea-autosize" onkeyup="textoLargo(this);" rows="1" onblur="update(this,1)"><?php echo $observacion; ?></textarea>
										</div>
									</div>
									<br>
									<br>
									<div class="row">
										<div class="col-md-6 text-center">
											<div class="fileinput fileinput-new text-center" data-provides="fileinput">
												<div class="fileinput fileinput-new text-center" data-provides="fileinput">
													<div class="text-center" id="archivo1">
														<?php echo $arrArchivos[1]; ?>
													</div>
												</div>
											</div>
											<label>Imagen</label>  
											
										</div>
										<div class="col-md-6 text-center">
											<div class="fileinput fileinput-new text-center" data-provides="fileinput">
												<div class="fileinput fileinput-new text-center" data-provides="fileinput">
													<div class="text-center" id="archivo2">
														<?php echo $arrArchivos[2]; ?>
													</div>
												</div>
											</div>
											<label>PDF</label>  
										</div>
									</div>
									<br>
								</div>
								<div class="card-footer text-right">
									<hr>
									<div class="stats">
										<i class="fa fa-clock-o"></i> <?php echo $programacion; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</fieldset>
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fas fa-clipboard-list"></i> &nbsp; Evaluar Ejecuci&oacute;n</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-12">
										<label>Observaciones:</label> <span class="text-danger">*</span>
										<textarea class="form-control textarea-autosize" name="observacion" id="observacion" onkeyup="textoLargo(this);" onblur="update(this,4);"><?php echo $evaluacion; ?></textarea>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Puntuaci&oacute;n:</label> <span class="text-danger">*</span>
										<input id="puntuacion" class="form-control input-sm" type="text" name="puntuacion" value="<?php echo $puntuacion; ?>" onchange="update(this,5);"/>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-lg-12 col-xs-12 text-center">
										<input type="hidden" id="codigo" name="codigo" value="<?php echo $codigo; ?>" />
										<input type="hidden" id="usuario" name="usuario" value="<?php echo $id; ?>" />
										<a type="button" class="btn btn-default " href="FRMevaluacion.php"><span class="fa fa-chevron-left"></span> Regresar</a>
										<button type="button" class="btn btn-danger " id="btn-grabar" onclick="Finalizar();"><span class="fa fa-check"></span> Finalizar</button>
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
	<script src="../assets.1.2.8/js/plugins/touchspin/jquery.bootstrap-touchspin.min.js"></script>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/mejora/evaluacion.js"></script>
	<script>
		$(document).ready(function() {
			$("input[name='puntuacion']").TouchSpin({
				min: 0,
				max: 100,
				step: 1,
				boostat: 5,
				maxboostedstep: 10,
				postfix: 'Pts.'
			});
		});
	</script>
</body>

</html>