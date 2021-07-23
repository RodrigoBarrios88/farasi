<?php
include_once('html_fns_ryo.php');
validate_login("../");
$id = $_SESSION["codigo"];

//--
$last = new DateTime();
$last->modify('last day of this month');
$ultimo = $last->format('d');
$desde = $_REQUEST["desde"];
$desde = ($desde == "") ? date("d/m/Y") : $desde; //valida que si no se selecciona fecha, coloque la del dia
$hasta = $_REQUEST["hasta"];
$hasta = ($hasta == "") ? date("$ultimo/m/Y") : $hasta; //valida que si no se selecciona fecha, coloque la del dia
//$_POST
$ClsOpo = new ClsOportunidad();
$hashkey = $_REQUEST["hashkey"];
$codigo = $ClsOpo->decrypt($hashkey, $id);
$result = $ClsOpo->get_oportunidad($codigo);
if (is_array($result)) {
	foreach ($result as $row) {
		$ficha = utf8_decode($row["opo_proceso"]);
		$justificacion = utf8_decode($row["opo_justificacion"]);
		$proceso = utf8_decode($row["fic_nombre"]);
		$sistema = utf8_decode($row["sis_nombre"]);
		$viabilidad = utf8_decode($row["opo_viabilidad"]);
		$rentabilidad = utf8_decode($row["opo_rentabilidad"]);
		$prioridad = intval($viabilidad) * intval($rentabilidad);
		$condicion = get_condicion_oportunidad($prioridad);
		$accion = trim($row["opo_accion"]);
		$accion = get_accion_oportunidad($accion);
		$oportunidad = utf8_decode($row["fod_descripcion"]);
	}
} ?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>


<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../", "risk"); ?>
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
													<label>Viabilidad:</label>
													<input type="text" class="form-control" value="<?php echo get_prioridad($viabilidad); ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Rentabilidad:</label>
													<input type="text" class="form-control" value="<?php echo get_prioridad($rentabilidad); ?>" />
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
										<i class="fa fa-check-square-o"></i> Estado
									</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-lg-12">
											<div class="row">
												<div class="col-md-12">
													<label>Prioridad:</label>
													<input type="text" class="form-control" value="<?php echo $prioridad; ?>" />
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
													<label>Oportunidad:</label>
													<textarea class="form-control" onkeyup="textoLargo(this);" rows="2"><?php echo $oportunidad; ?></textarea>
												</div>
											</div>
										</div>
										<br>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php if ($justificacion != "") { ?>
						<div class="row">
							<div class="col-md-12">
								<div class="card demo-icons">
									<div class="card-header">
										<h5 class="card-title">
											<i class="nc-icon nc-paper"></i> Revisi&oacute;n de Gerencia
										</h5>
									</div>
									<div class="card-body all-icons">
										<div class="row">
											<div class="col-lg-12">
												<div class="row">
													<div class="col-md-12">
														<label>Justificacion:</label>
														<textarea class="form-control" onkeyup="textoLargo(this);" rows="2"><?php echo $justificacion; ?></textarea>
													</div>
												</div>
											</div>
											<br>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
				</fieldset>
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-pencil-square-o"></i> Plan de Acci&oacute;n para Oportunidades</h5>
								<h6 class="card-subtitle text-muted"> Gestor de Actividades</h6><br>
							</div>
							<div class="card-body all-icons">
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
										<label>Responsable:</label> <span class="text-danger">*</span>
										<?php echo utf8_decode(usuarios_html("responsable", "", "select2", $ficha)) ?>
										<input type="hidden" name="oportunidad" id="oportunidad" value="<?php echo $codigo ?>" />
										<input type="hidden" name="codigo" id="codigo" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Periodicidad:</label> <span class="text-danger">*</span>
										<select class="form-control select2" name="periodicidad" id="periodicidad" onchange="cambiaTipo(this);">
											<option value="U">&Uacute;nica</option>
											<option value="W">Semanal</option>
											<option value="M">Mensual</option>
										</select>
									</div>
									<div class="col-md-6">
										<label>Fechas:</label> <span class="text-danger">*</span>
										<div class="form-group" id="range">
											<div class="input-daterange input-group" id="datepicker">
												<input type="text" class="input-sm form-control" id="desde" value="<?php echo $desde ?>" />
												<input hidden type="text" class="input-sm form-control" id="hoy" value="<?php echo date("d/m/Y"); ?>" />
												<span class="input-group-addon"> &nbsp; <i class="fa fa-calendar"></i> &nbsp; </span>
												<input type="text" class="input-sm form-control" id="hasta" value="<?php echo $hasta ?>" />
											</div>
										</div>
									</div>
								</div>
								<div class="row hidden" id="rangos">
									<div class="col-md-6">
										<label>Dia planificado:</label> <span class="text-danger">*</span>
										<select class="form-control select2" id="inicio">

										</select>
									</div>
									<div class="col-md-6">
										<label>Dia Limite:</label> <span class="text-danger">*</span>
										<select class="form-control select2" id="fin">

										</select>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Actividad:</label> <span class="text-danger">*</span>
										<textarea class="form-control" id="descripcion" name="descripcion" rows="5"></textarea>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-12 text-center">
										<button type="button" class="btn btn-white" id="btn-limpiar" onclick="Limpiar();"><i class="fas fa-eraser"></i> Limpiar</button>
										<button type="button" class="btn btn-primary" id="btn-grabar" onclick="Grabar(1);"><i class="fas fa-save"></i> Grabar</button>
										<button type="button" class="btn btn-primary hidden" id="btn-modificar" onclick="Modificar(1);"><i class="fas fa-save"></i> Grabar</button>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-lg-12" id="result"> </div>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/ryo/accion.js"></script>
</body>
<script>
	printTable(1);
	$('#range .input-daterange').datepicker({
		keyboardNavigation: false,
		forceParse: false,
		autoclose: true,
		format: "dd/mm/yyyy"
	});
	$('.select2').select2({ width: '100%' });
</script>

</html>