<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$id = $_SESSION["codigo"];
//$_POST
$ClsAct = new ClsActividad();
$ClsOpo = new ClsOportunidad();
$ClsRie = new ClsRiesgo();
$hashkey = $_REQUEST["hashkey"];
$codigo = $ClsAct->decrypt($hashkey, $id);
////parametro para reutilizar este formulario 
//y solamente visualizar la evidencia
$evidencia = $_REQUEST['evidencia'];
////en caso de venir true nos mostrara la evidencia 
///pero no dejara poner una calificacion
$result = $ClsAct->get_programacion($codigo);
if (is_array($result)) {
	foreach ($result as $row) {
		$situacion = trim($row["pro_situacion"]);
		if($evidencia != 'true'){
			switch ($situacion) {
				case 3:
					$sql = $ClsAct->cambia_situacion_programacion($codigo, "", 4); // la abre
					$rs = $ClsOpo->exec_sql($sql);
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
					echo "<form id='f1' name='f1' action='FRMevaluacion.php' method='post'>";
					echo "<script>document.f1.submit();</script>";
					echo "</form>";
					break;
			}
		}

		$actividad = trim($row["pro_actividad"]);
		$observacion = utf8_decode($row["pro_ejecucion"]);
		$fini = cambia_fecha($row["pro_fecha_inicio"]);
		$ffin = cambia_fecha($row["pro_fecha_fin"]);
		$ejecutada = cambia_fecha($row["pro_fecha"]);
		$evaluacion = utf8_decode($row["pro_evaluacion"]);
		$puntuacion = trim($row["pro_puntuacion"]);
		$programacion = $fini . " - " . $ffin;
		$arrArchivos = get_archivos(2, $codigo);
		$fini = cambia_fecha($row["act_fecha_inicio"]);
		$ffin = cambia_fecha($row["act_fecha_fin"]);
		$actividad = utf8_decode($row["act_descripcion"]);
		$responsable = utf8_decode($row["usu_nombre"]);
		$periodicidad = get_periodicidad($row["act_periodicidad"]);
		// Enlazar con su riesgo o su oportunidad para la informacion
		$riesgo = trim($row["pla_riesgo"]);
		$oportunidad = trim($row["pla_oportunidad"]);
		$ClsRie = new ClsRiesgo();
		$ClsOpo = new ClsOportunidad();
		if ($riesgo != 0) $info = $ClsRie->get_riesgo($riesgo);
		else $info = $ClsOpo->get_oportunidad($oportunidad);
		if (is_array($info)) {
			foreach ($info as $row) {
				$proceso = utf8_decode($row["fic_nombre"]);
				$sistema = utf8_decode($row["sis_nombre"]);
				$descripcion = utf8_decode($row["fod_descripcion"]);
				if ($riesgo != 0) {
					$probabilidad = utf8_decode($row["rie_probabilidad"]);
					$impacto = utf8_decode($row["rie_impacto"]);
					$severidad = intval($probabilidad) * intval($impacto);
					$condicion = get_condicion($severidad);
					$accion = trim($row["rie_accion"]);
					$accion = get_accion_riesgo($accion);
				} else {
					$viabilidad = utf8_decode($row["opo_viabilidad"]);
					$rentabilidad = utf8_decode($row["opo_rentabilidad"]);
					$prioridad = intval($viabilidad) * intval($rentabilidad);
					$condicion = get_condicion_oportunidad($prioridad);
					$accion = trim($row["opo_accion"]);
					$accion = get_accion_oportunidad($accion);
				}
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
		<?php echo sidebar("../", "risk"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<fieldset disabled>
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
													<label><?php echo ($riesgo == 0) ? "Oportunidad" : "Riesgo"; ?>:</label>
													<textarea class="form-control textarea-autosize"><?php echo $descripcion; ?></textarea>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Actividad:</label>
													<textarea class="form-control textarea-autosize"><?php echo $actividad; ?></textarea>
												</div>
											</div>
										</div>
										<br>
									</div>
								</div>
							</div>
						</div>
					</div>
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
											<div class="row">
												<div class="col-md-12">
													<label>Condici&oacute;n:</label>
													<input type="text" class="form-control" value="<?php echo $condicion; ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Acci&oacute;n:</label>
													<input type="text" class="form-control" value="<?php echo $accion; ?>" />
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
										<i class="fa fa-clock-o"></i> Planificaci&oacute;n
									</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-lg-12">
											<div class="row">
												<div class="col-md-12">
													<label>Periodicidad:</label>
													<input type="text" class="form-control" value="<?php echo $periodicidad; ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Programaci&oacute;n:</label>
													<input type="text" class="form-control" value="<?php echo $programacion; ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Responsable:</label>
													<input type="text" class="form-control" value="<?php echo $responsable; ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Fecha de Ejecuci&oacute;n:</label>
													<input type="text" class="form-control" value="<?php echo $ejecutada; ?>" />
												</div>
											</div>
										</div>
									</div>
									<br>
								</div>
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
							<!--EN CASO DE SOLICITAR VISUALIZAR LA EVIDENCIA-->
							<?php if($evidencia != 'true'):?>
								<h5 class="card-title"><i class="fas fa-clipboard-list"></i> &nbsp; Evaluar Ejecuci&oacute;n</h5>
							<?php else: ?>
								<h5 class="card-title"><i class="fas fa-clipboard-list"></i> &nbsp; Resultados Evaluaci&oacute;n</h5>
							<?php endif ?>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-12">
										<label>Observaciones:</label> <span class="text-danger">*</span>
										<!--EN CASO DE SOLICITAR VISUALIZAR LA EVIDENCIA-->
										<?php if($evidencia != 'true'):?>
											<textarea class="form-control textarea-autosize" name="observacion" id="observacion" onkeyup="textoLargo(this);" onblur="update(this,4);"><?php echo $evaluacion; ?></textarea>
										<?php else:?>
											<textarea disabled class="form-control textarea-autosize" name="observacion" id="observacion" onkeyup="textoLargo(this);" onblur="update(this,4);"><?php echo $evaluacion; ?></textarea>
											<label>Puntuaci&oacute;n:</label> <span class="text-danger">*</span>
											<input id="" disabled class="form-control" type="text" name="" value="<?php echo $puntuacion; ?>" onchange="update(this,5);"/>
										<?php endif;?>
									</div>
								</div>
								<?php if($evidencia != 'true'):?>
								<div class="row">
									<div class="col-md-6">
										<label>Puntuaci&oacute;n:</label> <span class="text-danger">*</span>
										<input id="puntuacion" class="form-control input-sm" type="text" name="puntuacion" value="<?php echo $puntuacion; ?>" onchange="update(this,5);"/>
									</div>
								</div>
								<?php endif;?>
								<hr>
								<div class="row">
									<div class="col-lg-12 col-xs-12 text-center">
										<input type="hidden" id="codigo" name="codigo" value="<?php echo $codigo; ?>" />
										<input type="hidden" id="usuario" name="usuario" value="<?php echo $id; ?>" />
										<?php if($evidencia != 'true'):?>
											<a type="button" class="btn btn-default " href="FRMevaluacion.php"><span class="fa fa-chevron-left"></span> Regresar</a>
											<button type="button" class="btn btn-danger " id="btn-grabar" onclick="Finalizar();"><span class="fa fa-check"></span> Finalizar</button>
										<?php endif;?>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/ryo/evaluacion.js"></script>
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