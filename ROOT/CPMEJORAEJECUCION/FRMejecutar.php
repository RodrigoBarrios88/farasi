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
			case 1:
				$sql = $ClsAct->cambia_situacion_programacion_mejora($codigo, "", 2); // la abre
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
			case 3: // cerrada
				echo "<form id='f1' name='f1' action='FRMejecucion.php' method='post'>";
				echo "<script>document.f1.submit();</script>";
				echo "</form>";
				break;
		}
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
				$fecha = cambia_fecha($row["hal_fecha"]);
				$usuario = utf8_decode($row["usu_nombre"]);
			}
		}
	}
} ?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
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
													<input type="text" class="form-control" value="<?php echo $usuario?>" />
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
													<?php if ($justificacion != "") { ?>
														<div class="row">
															<div class="col-md-12">
																<label>Justificacion:</label>
																<textarea class="form-control textarea-autosize" onkeyup="textoLargo(this);" rows="2"><?php echo $justificacion; ?></textarea>
															</div>
														</div>
													<?php } ?>
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
				</fieldset>
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
										<label>Observaciones:</label> <span class="text-danger">*</span>
										<textarea class="form-control textarea-autosize" name="observacion" id="observacion" onkeyup="textoLargo(this);" rows="1" onblur="update(this,1)"><?php echo $observacion; ?></textarea>
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
										<input type="hidden" name="codigo" id="codigo" value="<?php echo $codigo ?>" />
										<input type="hidden" name="hoy" id="hoy" value="<?php echo date("Y-m-d") ?>" />
										<input type="hidden" name="evidencia" id="evidencia" value="<?php echo $arrArchivos[0] ?>" />
										<input id="documento" name="documento" type="file" multiple="false" class="hidden" onchange="upload(this,2);">
										<input id="imagen" name="imagen" type="file" multiple="false" class="hidden" onchange="upload(this,1);">
										<input type="hidden" id="posicion" name="posicion" />
										<button type="button" class="btn btn-default" onclick="window.history.back();"><span class="fa fa-chevron-left"></span> Regresar</button>
										<button type="button" class="btn btn-danger" id="btn-grabar" onclick="Finalizar();"><span class="fa fa-check"></span> Finalizar</button>
									</div>
								</div>
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
			</div>
			<?php echo footer() ?>
		</div>
	</div>
	<?php echo modal("../"); ?>
	<?php echo scripts("../"); ?>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/mejora/ejecucion.js"></script>
</body>

</html>