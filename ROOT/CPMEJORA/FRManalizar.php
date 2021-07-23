<?php
include_once('html_fns_mejora.php');
validate_login("../");
$id = $_SESSION["codigo"];

$ClsPla = new ClsPlan();
$hashkey = $_REQUEST["hashkey"];
$origen = $_REQUEST["origen"];
$hallazgo = $ClsPla->decrypt($hashkey, $id);
// obtiene plan de hallazgo del usuario
$result = $ClsPla->get_plan_mejora("", $hallazgo);
if (is_array($result)) {
	foreach ($result as $row) {
		$codigo = trim($row["pla_codigo"]);
		$justificacion = utf8_decode($row["pla_justificacion"]);
	}
} else {
	$codigo = $ClsPla->max_plan_mejora();
	$codigo++;
	$sql = $ClsPla->insert_plan_mejora($codigo, $hallazgo, $id);
	$rs = $ClsPla->exec_sql($sql);
}
//--
$last = new DateTime();
$last->modify('last day of this month');
$ultimo = $last->format('d');
$desde = $_REQUEST["desde"];
$desde = ($desde == "") ? date("d/m/Y") : $desde; //valida que si no se selecciona fecha, coloque la del dia
$hasta = $_REQUEST["hasta"];
$hasta = ($hasta == "") ? date("$ultimo/m/Y") : $hasta; //valida que si no se selecciona fecha, coloque la del dia
// obtiene Hallazgo
$ClsHal = new ClsHallazgo();
switch ($origen) {
	case 5:
		$result = $ClsHal->get_hallazgo_riesgo($hallazgo);
		break;
}
if (is_array($result)) {
	foreach ($result as $row) {
		$proceso = utf8_decode($row["fic_nombre"]);
		$sistema = utf8_decode($row["sis_nombre"]);
		$hallazgo = utf8_decode($row["hal_descripcion"]);
		$tipo = get_tipo($row["hal_tipo"]);
		$origen = get_origen($row["hal_origen"]);
		$fecha = cambia_fecha($row["hal_fecha"]);
		$usuario = utf8_decode($row["usu_nombre"]);
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
				</fieldset>
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fab fa-wpforms"></i> Analisis de Causa Ra&iacute;z
									<a class="btn btn-white btn-lg pull-right" href="CPREPORTES/REPpdf.php?hashkey=<?php echo $hashkey ?>" target="_blank" title="Imprimir Diagrama" id="pdf"><i class="fa fa-print"></i></a>
								</h5>
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
									<div class="col-md-12">
										<label>Causa:</label> <span class="text-danger">*</span>
										<textarea class="form-control textarea-autosize" id="causa" name="causa"></textarea>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-12 text-center">
										<button type="button" class="btn btn-white" id="btn-limpiar" onclick="Limpiar();"><i class="fas fa-eraser"></i> Limpiar</button>
										<button type="button" class="btn btn-primary" id="btn-grabar" onclick="Grabar();"><i class="fas fa-save"></i> Grabar</button>
										<button type="button" class="btn btn-primary hidden" id="btn-modificar" onclick="Modificar();"><i class="fas fa-save"></i> Grabar</button>
									</div>
								</div>
								<hr>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-xs-12 text-center">
						<input type="hidden" name="codigo" id="codigo" />
						<input type="hidden" id="plan" name="plan" value="<?php echo $codigo; ?>" />
						<a type="button" class="btn btn-default " href="FRMaprobacion.php"><span class="fa fa-chevron-left"></span> Regresar</a>
						<button type="button" class="btn btn-info " onclick="solicitar(<?php echo $codigo; ?>);"><span class="fa fa-check"></span> Solicitar Aprobacion</button>
					</div>
				</div>
			</div>
			<?php echo footer() ?>
		</div>
	</div>
	<?php echo modal("../"); ?>
	<?php echo scripts("../"); ?>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/mejora/plan.js"></script>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/mejora/analisis.js"></script>
</body>
<script>
	$('#range .input-daterange').datepicker({
		keyboardNavigation: false,
		forceParse: false,
		autoclose: true,
		format: "dd/mm/yyyy"
	});
	$('.select2').select2({ width: '100%' });
</script>

</html>