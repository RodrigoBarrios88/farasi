<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$usuario = $_SESSION["codigo"];

$categoriasIn = $_SESSION["categorias_in"];
//$_POST
$ClsAud = new ClsAuditoria();
$ClsEje = new ClsEjecucion();
$hashkey = $_REQUEST["hashkey"];
$ejecucion = $ClsAud->decrypt($hashkey, $usuario);
//--
$result = $ClsEje->get_ejecucion($ejecucion, '', '');
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$ejecucion = trim($row["eje_codigo"]);
		$codigo_audit = trim($row["audit_codigo"]);
		$codigo_progra = trim($row["pro_codigo"]);
		$ponderacion_audit = trim($row["audit_ponderacion"]);
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
	}
	$situacion = ($situacion == 1) ? "En edici&oacute;n" : "Finalizado";
}?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
	
	<script type='text/javascript'>
		function mensaje() {
			<?php echo $alerta_completa; ?>
		}
	</script>
</head>

<body class="">
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
									<a class="btn btn-white btn-lg sin-margin pull-right" href="FRMaprobaciones.php"><small><i class="fa fa-chevron-left"></i> Atr&aacute;s</small></a>
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
										<label>Situaci&oacute;n: </label>
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
								<h5 class="card-title"><i class="nc-icon nc-paper"></i> Secciones del Plan</h5>
							</div>
							<div class="card-body all-icons">
								<?php

								$result = secciones_auditoria($ejecucion, $codigo_audit, $hashkey);
								echo $result["salida"];
								?>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fas fa-pen-nib"></i> Observaciones de la Revisi&oacute;n</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-10 ml-auto mr-auto">
										<label>Observaciones:</label>
										<textarea class="form-control" name="observaciones" id="observaciones" onkeyup="textoLargo(this);" rows="5"></textarea>
									</div>
								</div>
								<br>
								<div class="row">
									<input type="hidden" id="ejecucion" name="ejecucion" value="<?php echo $ejecucion; ?>" />
									<input type="hidden" id="auditoria" name="auditoria" value="<?php echo $codigo_audit; ?>" />
									<input type="hidden" id="programacion" name="programacion" value="<?php echo $codigo_progra; ?>" />
									<input type="hidden" id="pregunta" name="pregunta" />
								</div>
								<div class="row">
									<div class="col-md-6 ml-auto mr-auto text-center">
										<a type="button" class="btn btn-default btn-lg" href="FRMaprobaciones.php"><span class="fa fa-chevron-left"></span> Regresar</a>
										<button type="button" class="btn btn-warning btn-lg" id="btncerrar" onclick="rechazarAuditoria();"><span class="fa fa-edit"></span> Correcci&oacute;n</button>
										<button type="button" class="btn btn-success btn-lg" id="btncerrar" onclick="aprobarAuditoria();"><span class="fa fa-check"></span> Aprobar</button>
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


	<script type="text/javascript" src="../assets.1.2.8/js/modules/auditoria/aprobar.js"></script>
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
<?php



function secciones_auditoria($ejecucion, $codigo_audit, $hashkey)
{
	$ClsEje = new ClsEjecucion();
	$ClsAud = new ClsAuditoria();
	$ClsPla = new ClsPlan();
	$result = $ClsAud->get_secciones('', $codigo_audit, 1);
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "150px">Secci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "30px">Puntos Evaluados</th>';
		$salida .= '<th class = "text-center" width = "30px">Puntos Pendientes</th>';
		$salida .= '<th class = "text-center" width = "30px">Puntos Revisados</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		foreach ($result as $row) {
			$seccion_codigo = trim($row["sec_codigo"]);
			$evaluados = 0;
			$pendientes = 0;
			$revisados = 0;
			$noaplica = 0;
			$result_respuesta = $ClsEje->get_respuesta($ejecucion, $codigo_audit, '', '', $seccion_codigo);
			if (is_array($result_respuesta)) {
				foreach ($result_respuesta as $row_respuesta) {
					$evaluados++;
				}
			}
			$result_revision = $ClsEje->get_ejecucion_revision($ejecucion, $codigo_audit, '', $seccion_codigo);
			if (is_array($result_revision)) {
				foreach ($result_revision as $row_revision) {
					$revisados++;
				}
			}
			$pendientes = ($evaluados - $revisados);
			//---
			$salida .= '<tr>';
			//titulo
			$titulo = trim($row["sec_numero"]) . ". " . utf8_decode($row["sec_titulo"]);
			$salida .= '<td class = "text-left">';
			$salida .= '<a  href="FRMaprobar_secciones.php?seccion=' . $seccion_codigo . '&hashkey=' . $hashkey . '">' . $titulo . ' <i class="fa  fa-angle-right"></i></a>';
			$salida .= '</td>';
			//---
			$salida .= '<td class = "text-center">';
			$salida .= '<a class="btn btn-secondary btn-round btn-sm" href="FRMaprobar_secciones.php?seccion=' . $seccion_codigo . '&hashkey=' . $hashkey . '" title="Puntos Evaluados">' . $evaluados . '</a>';
			$salida .= '</td>';
			$salida .= '<td class = "text-center">';
			$salida .= '<a class="btn btn-white btn-round btn-sm" href="FRMaprobar_secciones.php?seccion=' . $seccion_codigo . '&hashkey=' . $hashkey . '" title="Puntos Pendientes">' . $pendientes . '</a>';
			$salida .= '</td>';
			$salida .= '<td class = "text-center">';
			$salida .= '<a class="btn btn-info btn-round btn-sm" href="FRMaprobar_secciones.php?seccion=' . $seccion_codigo . '&hashkey=' . $hashkey . '" title="Puntos Revisados">' . $revisados . '</a>';
			$salida .= '</td>';
			//---
			//--
			$salida .= '</tr>';
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	} else {
	}
	$result = array(
		"salida" => $salida,
		"preguntas" => $PREGUNTAS,
		"respuestas" => $RESPUESTAS,
		"pendientes" => $PENDIENTES
	);
	return $result;
}?>