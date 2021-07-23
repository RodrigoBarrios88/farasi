<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$usuario = $_SESSION["codigo"];

$categoriasIn = $_SESSION["categorias_in"];
//$_POST
$ClsAud = new ClsAuditoria();
$ClsEje = new ClsEjecucion();
$ClsPla = new ClsPlan();
$ejecucion = $_REQUEST["plan"];
//--
$result = $ClsEje->get_ejecucion($ejecucion, '', '');
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$ejecucion = trim($row["eje_codigo"]);
		$codigo_audit = trim($row["audit_codigo"]);
		$codigo_progra = trim($row["pro_codigo"]);
		$sede = utf8_decode($row["sed_nombre"]);
		$direccion = utf8_decode($row["sed_direccion"]) . ", " . utf8_decode($row["sede_municipio"]);
		$departamento = utf8_decode($row["dep_nombre"]);
		$categoria = utf8_decode($row["cat_nombre"]);
		$nombre = utf8_decode($row["audit_nombre"]);
		$usuario_nombre = utf8_decode($row["usuario_nombre"]);
		$strFirma = trim($row["eje_firma"]);
		//--
		$fecha_inicio = trim($row["eje_fecha_inicio"]);
		$fecha_inicio = cambia_fechaHora($fecha_inicio);
		$fecha_inicio = substr($fecha_inicio, 0, 16);
		//--
		$fecha_finaliza = trim($row["eje_fecha_final"]);
		$fecha_finaliza = cambia_fechaHora($fecha_finaliza);
		$fecha_finaliza = substr($fecha_finaliza, 0, 16);
		//--
		$fecha_progra = trim($row["pro_fecha"]);
		$fecha_progra = cambia_fecha($fecha_progra);
		$hora_progra = substr($row["pro_hora"], 0, 5);
		$fecha_progra = "$fecha_progra $hora_progra";
		$obs = utf8_decode($row["pro_observaciones"]);
		//
		$ejeobs = utf8_decode($row["eje_observaciones"]);
		$responsable = utf8_decode($row["eje_responsable"]);
		///---
		$observaciones = utf8_decode($row["pla_observaciones"]);
		$situacion = trim($row["eje_situacion"]);
		$ultima_actualizacion = cambia_fechaHora($row["pla_fecha_update"]);
	}
	$situacion = ($situacion == 1) ? "En edici&oacute;n" : "Finalizado";
	$usuario = $_SESSION["codigo"];
} else {
	$alerta_completa = 'swal("Error", "Na hay un plan de acci\u00F3n desarrollado a\u00FAn...", "error").then((value)=>{ window.history.back(); });';
}
$result = $ClsPla->get_plan($ejecucion, '', '');
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$observaciones = utf8_decode($row["pla_observaciones"]);
		$situacion = trim($row["pla_situacion"]);
		$ultima_actualizacion = cambia_fechaHora($row["pla_fecha_update"]);
	}
	$situacion = ($situacion == 1) ? "En edici&oacute;n" : "Finalizado";
	$usuario = $_SESSION["codigo"];
}
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
	<script type='text/javascript'>
		function mensaje() {
			<?php echo $alerta_completa; ?>
		}
		window.setTimeout('mensaje(<?php echo $status; ?>);', 500);
	</script>
</head>

<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("../", "auditoria"); ?>
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
												<label>Direcci&oacute;n:</label><br>
												<input type="text" class="form-control" value="<?php echo $direccion; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Departamento:</label>
												<input type="text" class="form-control" value="<?php echo $departamento; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Fecha y Hora de Programaci&oacute;n:</label><br>
												<input type="text" class="form-control" value="<?php echo $fecha_progra; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Responsable o Evaluado: </label>
												<input type="text" class="form-control" value="<?php echo $responsable; ?>" disabled />
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
									<i class="nc-icon nc-bullet-list-67"></i> Auditor&iacute;a
									<a class="btn btn-white btn-lg sin-margin pull-right" href="CPREPORTES/REPrevision.php?ejecucion=<?php echo $ejecucion; ?>" target="_blank"><small><i class="fa fa-print"></i> Auditor&iacute;a</small></a>
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12" id="result">
										<div class="row">
											<div class="col-md-12">
												<label>Categor&iacute;a:</label>
												<input type="text" class="form-control" value="<?php echo $categoria; ?>" disabled />
												<input type="hidden" id="ejecucion" name="ejecucion" value="<?php echo $ejecucion; ?>" />
												<input type="hidden" id="reqfoto" name="reqfoto" value="<?php echo $requiere_fotos; ?>" />
												<input type="hidden" id="reqfirma" name="reqfirma" value="<?php echo $requiere_firma; ?>" />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Cuestionario de Auditor&iacute;a:</label>
												<input type="text" class="form-control" value="<?php echo $nombre; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Fecha y Hora de Inicio:</label><br>
												<input type="text" class="form-control" value="<?php echo $fecha_inicio; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Fecha y Hora de Finalizaci&oacute;n:</label><br>
												<input type="text" class="form-control" value="<?php echo $fecha_finaliza; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Usuario que registr&oacute;:</label><br>
												<input type="text" class="form-control" value="<?php echo $usuario_nombre; ?>" disabled />
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
									<div class="col-md-6">
										<label>Plan: </label>
										<input type="text" class="form-control" value="<?php echo $situacion; ?>" disabled />
									</div>
									<div class="col-md-6">
										<label>&Uacute;ltima actualizaci&oacute;n: </label>
										<input type="text" class="form-control" value="<?php echo $ultima_actualizacion; ?>" disabled />
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Observaciones durante la programaci&oacute;n:</label><br>
										<textarea class="form-control text-justify" rows="3" disabled><?php echo $obs; ?></textarea>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 ml-auto mr-auto">
										<label>Observaciones durante la auditor&iacute;a:</label>
										<textarea class="form-control" rows="3" disabled><?php echo $ejeobs; ?></textarea>
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
								<h5 class="card-title"><i class="nc-icon nc-briefcase-24"></i> Cierre del Plan</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-10 ml-auto mr-auto">
										<label>Observaciones:</label>
										<textarea class="form-control" name="obs" id="obs" onkeyup="textoLargo(this);" rows="5" disabled><?php $observaciones; ?></textarea>
									</div>
								</div>
								<br>
								<div class="row">
									<form action="EXEcarga_foto_solucion.php" name="f1" name="f1" method="post" enctype="multipart/form-data">
										<input id="imagen" name="imagen" type="file" multiple="false" class="hidden" onchange="uploadImage();">
										<input type="hidden" id="ejecucion" name="ejecucion" value="<?php echo $ejecucion; ?>" />
										<input type="hidden" id="auditoria" name="auditoria" value="<?php echo $codigo_audit; ?>" />
										<input type="hidden" id="programacion" name="programacion" value="<?php echo $codigo_progra; ?>" />
										<input type="hidden" id="pregunta" name="pregunta" />
									</form>
								</div>
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


	<script type="text/javascript" src="../assets.1.2.8/js/modules/auditoria/plan.js"></script>
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