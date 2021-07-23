<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$usuario = $_SESSION["codigo"];

$categoriasIn = $_SESSION["categorias_in"];
//$_POST
$ClsAud = new ClsAuditoria();
$ClsEje = new ClsEjecucion();
$ClsPla = new ClsPlan();
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
	//--------------------------------
	$usuario = $_SESSION["codigo"];
	$sql = $ClsPla->insert_plan($ejecucion, $codigo_audit, $codigo_progra, $usuario);
	$rs = $ClsEje->exec_sql($sql);
	if ($rs == 1) {
		//--
	} else {
		//$alerta_completa = 'swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ window.history.back(); });';
	}
}
$result = $ClsPla->get_plan($ejecucion, '', '');
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$strFirma = trim($row["pla_firma"]);
		$tramiento_usuario = utf8_decode($row["pla_tratamiento"]);
		$nombre_usuario = utf8_decode($row["pla_nombre"]);
		$rol_usuario = utf8_decode($row["pla_rol"]);
		$observaciones = utf8_decode($row["pla_observaciones"]);
		$situacion = trim($row["pla_situacion"]);
		$ultima_actualizacion = cambia_fechaHora($row["pla_fecha_update"]);
	}
	$situacion = ($situacion == 1) ? "En edici&oacute;n" : "Finalizado";
	$usuario = $_SESSION["codigo"];
}
if (file_exists('../../CONFIG/Fotos/AUDFIRMAS/' . $strFirma . '.jpg') && $strFirma != "") {
	$strFirma = 'Fotos/AUDFIRMAS/' . $strFirma . '.jpg';
} else {
	$strFirma = "img/imageSign.jpg";
}
$nombre_usuario = ($nombre_usuario == '') ? $usuario_nombre : $nombre_usuario; //valida si coloca el nombre del usuario gestor o el nombre del responsable


?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
	<style>
		.img-upload {
			width: 30%;
			margin: 1px;
			cursor: pointer;
		}

		.img-demo {
			width: 50%;
		}
	</style>
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
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12">
										<a class="btn btn-white btn-lg sin-margin pull-right" href="FRMplanes.php"><small><i class="fa fa-chevron-left"></i> Atr&aacute;s</small></a>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
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
									<i class="nc-icon nc-bullet-list-67"></i> Informe Final de Auditor&iacute;a
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12">
										<a class="btn btn-white btn-lg sin-margin pull-right" href="CPREPORTES/REPplan.php?ejecucion=<?php echo $ejecucion; ?>" target="_blank"><small><i class="fa fa-print"></i> Informe Final de Auditor&iacute;a</small></a>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
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
								<h5 class="card-title"><i class="nc-icon nc-briefcase-24"></i> Cierre del Informe</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-10 ml-auto mr-auto">
										<label>Tratamiento: </label>
										<input type="text" class="form-control" name="tratamiento" id="tratamiento" value="<?php echo $tramiento_usuario; ?>" onkeyup="texto(this);" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-10 ml-auto mr-auto">
										<label>Nombre del Responsable: </label>
										<input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $nombre_usuario; ?>" onkeyup="texto(this);" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-10 ml-auto mr-auto">
										<label>Rol durante el proceso: </label>
										<input type="text" class="form-control" name="rol" id="rol" value="<?php echo $rol_usuario; ?>" onkeyup="texto(this);" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-10 ml-auto mr-auto">
										<label>Observaciones:</label>
										<textarea class="form-control" name="obs" id="obs" onkeyup="textoLargo(this);" rows="5"><?php echo $observaciones; ?></textarea>
										<input type="hidden" id="ejecucion" name="ejecucion" value="<?php echo $ejecucion; ?>" />
										<input type="hidden" id="auditoria" name="auditoria" value="<?php echo $codigo_audit; ?>" />
										<input type="hidden" id="programacion" name="programacion" value="<?php echo $codigo_progra; ?>" />
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-12 text-center">
										<a href="FRMfirma.php?hashkey=<?php echo $hashkey; ?>">
											<div class="fileinput fileinput-new text-center" data-provides="fileinput">
												<div class="fileinput-new thumbnail">
													<img src="../../CONFIG/<?php echo $strFirma; ?>" alt="...">
												</div>
											</div>
											<p>Click Firma Auditor(a)</p>
										</a>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6 ml-auto mr-auto text-center">
										<button type="button" class="btn btn-default btn-lg" onclick="window.history.back();"><span class="fa fa-chevron-left"></span> Regresar</button>
										<button type="button" class="btn btn-success btn-lg" id="btncerrar" onclick="GrabarPlan();"><i class="fas fa-save"></i> Grabar</button>
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
		$salida .= '<th class = "text-center" width = "250px">Secci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "50px">Puntos Evaluados</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		foreach ($result as $row) {
			$seccion_codigo = trim($row["sec_codigo"]);
			$calificado = 0;
			$noaplica = 0;
			$result_respuesta = $ClsEje->get_respuesta($ejecucion, $codigo_audit, '', '', $seccion_codigo);
			if (is_array($result_respuesta)) {
				foreach ($result_respuesta as $row_respuesta) {
					$calificado++;
				}
			}
			//---
			$salida .= '<tr>';
			//titulo
			$titulo = trim($row["sec_numero"]) . ". " . utf8_decode($row["sec_titulo"]);
			$salida .= '<td class = "text-left">';
			$salida .= '<a  href="FRMplan_secciones.php?seccion=' . $seccion_codigo . '&hashkey=' . $hashkey . '">' . $titulo . ' <i class="fa  fa-angle-right"></i></a>';
			$salida .= '</td>';
			//---
			$salida .= '<td class = "text-center">';
			$salida .= '<a class="btn btn-white active btn-round btn-sm" href="FRMplan_secciones.php?seccion=' . $seccion_codigo . '&hashkey=' . $hashkey . '" title="Pendientes">' . $calificado . '</a>';
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
} ?>