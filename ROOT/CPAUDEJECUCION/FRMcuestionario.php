<?php
include_once('html_fns_ejecucion.php');
	validate_login("../");
$usuario = $_SESSION["codigo"];

$categoriasIn = $_SESSION["categorias_in"];
//$_POST
$ClsAud = new ClsAuditoria();
$ClsEje = new ClsEjecucion();
$hashkey1 = $_REQUEST["hashkey1"];
$hashkey2 = $_REQUEST["hashkey2"];
$hashkey3 = $_REQUEST["hashkey3"];
$codigo_audit = $ClsAud->decrypt($hashkey1, $usuario);
$codigo_progra = $ClsAud->decrypt($hashkey2, $usuario);
$ejecucion = $ClsAud->decrypt($hashkey3, $usuario); //--
if ($ejecucion == "") {
	$fecha = date("d/m/Y");
	$result = $ClsEje->comprueba_ejecucion('', $codigo_audit, $codigo_progra, $usuario, $fecha, $fecha, 1);
	if (is_array($result)) {
		foreach ($result as $row) {
			$ejecucion = trim($row["eje_codigo"]);
		}
	}
}
if ($ejecucion != "") {
	$result = $ClsEje->get_ejecucion($ejecucion, '', '', '', '', '', '', '', '1,2,5');
	if (is_array($result)) {
		$i = 0;
		foreach ($result as $row) {
			$ejecucion = trim($row["eje_codigo"]);
			$codigo_audit = trim($row["eje_auditoria"]);
			$codigo_progra = trim($row["eje_programacion"]);
			$ponderacion_audit = trim($row["audit_ponderacion"]);
			$sedeCodigo = utf8_decode($row["sed_codigo"]);
			$sede = utf8_decode($row["sed_nombre"]);
			$direccion = utf8_decode($row["sed_direccion"]) . ", " . utf8_decode($row["sede_municipio"]);
			$departamento = utf8_decode($row["dep_nombre"]);
			$categoria = utf8_decode($row["cat_nombre"]);
			$nombre = utf8_decode($row["audit_nombre"]);
			//
			$responsable = trim($row["eje_responsable"]);
			$EjeObservacion = utf8_decode($row["eje_observaciones"]);
			//--
			$strFirma1 = trim($row["eje_firma_evaluador"]);
			$strFirma2 = trim($row["eje_firma_evaluado"]);
			$correos = trim(strtolower($row["eje_correos"]));
			$situacion = trim($row["eje_situacion"]);
			$nota = trim($row["eje_nota"]);
		}
		if (file_exists('../../CONFIG/Fotos/AUDFIRMAS/' . $strFirma1 . '.jpg') && $strFirma1 != "") {
			$strFirma1 = 'Fotos/AUDFIRMAS/' . $strFirma1 . '.jpg';
		} else {
			$strFirma1 = "img/imageSign.jpg";
		}

		if (file_exists('../../CONFIG/Fotos/AUDFIRMAS/' . $strFirma2 . '.jpg') && $strFirma2 != "") {
			$strFirma2 = 'Fotos/AUDFIRMAS/' . $strFirma2 . '.jpg';
		} else {
			$strFirma2 = "img/imageSign.jpg";
		}
		/////////// PROGRAMACION /////
		$dia = date("N");
		$dia = date("N");
		$result = $ClsAud->get_programacion($codigo_progra, '', '', '', '', '', date("d/m/Y"), date("d/m/Y"));
		if (is_array($result)) {
			$i = 0;
			foreach ($result as $row) {
				$fecha = cambia_fecha($row["pro_fecha"]);
				$hora = substr($row["pro_hora"], 0, 5);
				$programa = "$fecha $hora";
				$obs = utf8_decode($row["pro_observaciones"]);
				$obs = nl2br($obs);
			}
		} else {
			$alerta_completa = 'swal("Alto", "Este formulario de auditor\u00EDa esta fuera de fecha...", "warning").then((value)=>{ window.history.back(); });';
		}
		///// Parcha Hashkey3 ///
		if ($hashkey3 == '') {
			$usuario = $_SESSION["codigo"];
			$hashkey3 = $ClsAud->encrypt($ejecucion, $usuario);
		}
	}
} else {
	$result = $ClsAud->get_programacion($codigo_progra, '', '', '', '', $fecha, $fecha);
	if (is_array($result)) {
		$i = 0;
		foreach ($result as $row) {
			$codigo_audit = $row["audit_codigo"];
			$ponderacion_audit = trim($row["audit_ponderacion"]);
			$sedeCodigo = utf8_decode($row["sed_codigo"]);
			$sede = utf8_decode($row["sed_nombre"]);
			$direccion = utf8_decode($row["sed_direccion"]) . ", " . utf8_decode($row["sede_municipio"]);
			$departamento = utf8_decode($row["dep_nombre"]);
			$area = utf8_decode($row["are_nombre"]);
			$categoria = utf8_decode($row["cat_nombre"]);
			$nombre = utf8_decode($row["audit_nombre"]);
			//--
			$requiere_firma = trim($row["audit_firma"]);
			$requiere_fotos = trim($row["audit_fotos"]);
			//--
			$fecha = cambia_fecha($row["pro_fecha"]);
			$hora = substr($row["pro_hora"], 0, 5);
			$programa = "$fecha $hora";
			$obs = utf8_decode($row["pro_observaciones"]);
			$obs = nl2br($obs);
		}
		$ejecucion = $ClsEje->max_ejecucion();
		$ejecucion++; /// Maximo codigo de Cuestionario de Auditor&iacute;a
		$usuario = $_SESSION["codigo"];
		$sql = $ClsEje->insert_ejecucion($ejecucion, $codigo_audit, $codigo_progra, $usuario, '');
		$sql .= $ClsEje->insert_ejecucion_situacion($ejecucion, 1, '', $usuario);
		$rs = $ClsEje->exec_sql($sql);
		if ($rs == 1) {
			$usuario = $_SESSION["codigo"];
			$hashkey3 = $ClsAud->encrypt($ejecucion, $usuario);
			$alerta_completa = 'swal("Apertura de Auditor\u00EDa", "Se ha aperturado una nueva auditor\u00EDa seg\u00FAn programaci\u00F3n...", "success");';
		} else {
			$alerta_completa = 'swal("Error", "Error en la transacci\u00F3n", "error").then((value)=>{ window.history.back(); });';
		}
	} else {
		$alerta_completa = 'swal("Alto", "Esta propgramaci\u00F3n esta fuera de fecha...", "warning").then((value)=>{ window.history.back(); });';
	}

	$strFirma1 = "img/imageSign.jpg";
	$strFirma2 = "img/imageSign.jpg";
} ///////////////////// Valida que las variables esten llenas no importando de donde se accesa al formulario
$usuario = $_SESSION["codigo"];
$hashkey1 = ($hashkey1 == "") ? $ClsAud->encrypt($codigo_audit, $usuario) : $hashkey1;
$hashkey2 = ($hashkey2 == "") ? $ClsAud->encrypt($codigo_progra, $usuario) : $hashkey2;
$hashkey3 = ($hashkey3 == "") ? $ClsAud->encrypt($ejecucion, $usuario) : $hashkey3; /////////// CORREOS DE NOTIFICACION ///////////////
$correos = "";
if ($sedeCodigo != "") {
	$result = $ClsAud->get_correo('', $codigo_audit, $sedeCodigo);
	if (is_array($result)) {
		foreach ($result as $row) {
			$correo = utf8_decode($row["cor_correo"]);
			$correos .= $correo . ", ";
		}
		$correos = substr($correos, 0, -2);
	}
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
									<a class="btn btn-white btn-lg sin-margin pull-right" href="FRMejecutar.php"><small><i class="fa fa-chevron-left"></i> Atr&aacute;s</small></a>
								</h5>
							</div>
							<div class="card-body all-icons">
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
									<a class="btn btn-white btn-lg sin-margin pull-right" href="CPREPORTES/REPrevision.php?ejecucion=<?php echo $ejecucion; ?>" target="_blank"><small><i class="fa fa-print"></i> Imprimir</small></a>
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12" id="result">
										<div class="row">
											<div class="col-md-12">
												<label>Categor&iacute;a:</label>
												<input type="text" class="form-control" value="<?php echo $categoria; ?>" disabled />
												<form action="EXEcarga_foto.php" name="f1" name="f1" method="post" enctype="multipart/form-data">
													<input id="imagen" name="imagen" type="file" multiple="false" class="hidden" onchange="uploadImage();">
													<input type="hidden" id="ejecucion" name="ejecucion" value="<?php echo $ejecucion; ?>" />
													<input type="hidden" id="auditoria" name="auditoria" value="<?php echo $codigo_audit; ?>" />
													<input type="hidden" id="programacion" name="programacion" value="<?php echo $codigo_progra; ?>" />
													<input type="hidden" id="pregunta" name="pregunta" />
												</form>
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
												<label>Fecha y Hora de Programaci&oacute;n:</label><br>
												<input type="text" class="form-control" value="<?php echo $programa; ?>" disabled />
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
										<label>Observaciones de Programaci&oacute;n:</label><br>
										<textarea class="form-control text-justify" rows="3" disabled><?php echo $obs; ?></textarea>
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
								<h5 class="card-title"><i class="nc-icon nc-paper"></i> Secciones del Cuestionario</h5>
							</div>
							<div class="card-body all-icons">
								<?php
								//echo $ponderacion_audit."<br>";
								$result = secciones_auditoria($ejecucion, $codigo_audit, $ponderacion_audit, $hashkey1, $hashkey2, $hashkey3);
								echo $result["salida"];
								/////////////////////////////////////////
								$PREGUNTAS = $result["preguntas"];
								$RESPUESTAS = $result["respuestas"];
								$PENDIENTES = $result["pendientes"];
								$NOTA = calcula_nota_global($ejecucion, $codigo_audit, $ponderacion_audit);
								?>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="nc-icon nc-chart-bar-32"></i> Resumen</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-6 col-xs-6">
										<label>Total de Preguntas
									</div>
									<div class="col-md-6 col-xs-6">
										<input type="text" class="form-control text-center" name="preguntas" id="preguntas" value="<?php echo $PREGUNTAS; ?>" disabled />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6 col-xs-6">
										<label>Total de Respuestas
									</div>
									<div class="col-md-6 col-xs-6">
										<input type="text" class="form-info text-center" name="respuestas" id="respuestas" value="<?php echo $RESPUESTAS; ?>" disabled />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6 col-xs-6">
										<label>Preguntas Pendientes
									</div>
									<div class="col-md-6 col-xs-6">
										<input type="text" class="form-danger text-center" name="pendientes" id="pendientes" value="<?php echo $PENDIENTES; ?>" disabled />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6 col-xs-6">
										<label>Nota al momento
									</div>
									<div class="col-md-6 col-xs-6">
										<input type="text" class="form-control text-center" name="nota" id="nota" value="<?php echo $NOTA; ?>" readonly />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="nc-icon nc-briefcase-24"></i> Cierre del Formulario de Auditor&iacute;a</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-6 text-center">
										<a href="FRMfirma.php?ejecucion=<?php echo $ejecucion; ?>&firma=1">
											<div class="fileinput fileinput-new text-center" data-provides="fileinput">
												<div class="fileinput-new thumbnail">
													<img src="../../CONFIG/<?php echo $strFirma1; ?>" alt="...">
												</div>
											</div>
											<p>Click Firma Auditor(a)</p>
										</a>
									</div>
									<div class="col-md-6 text-center">
										<a href="FRMfirma.php?ejecucion=<?php echo $ejecucion; ?>&firma=2">
											<div class="fileinput fileinput-new text-center" data-provides="fileinput">
												<div class="fileinput-new thumbnail">
													<img src="../../CONFIG/<?php echo $strFirma2; ?>" alt="...">
												</div>
											</div>
											<p>Click Firma Evaluado(a)</p>
										</a>
									</div>
								</div>
								<div class="row">
									<div class="col-md-10 ml-auto mr-auto">
										<label>Correos de Notificaci&oacute;n: <span class="text-danger">*</span> <small>(separados por coma ",")</small></label>
										<input type="text" class="form-control" name="correos" id="correos" value="<?php echo $correos; ?>" onkeyup="texto(this);" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-10 ml-auto mr-auto">
										<label>Responsable o Evaluado: <span class="text-danger">*</span></label>
										<input type="text" class="form-control" name="responsable" id="responsable" value="<?php echo $responsable; ?>" onkeyup="texto(this);" />
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-10 ml-auto mr-auto">
										<h5>Observaciones Cr&iacute;ticas por Departamento:</h5>
									</div>
								</div>
								<?php
								$ClsDep = new ClsDepartamento();
								$result = $ClsDep->get_departamento('', '', 1, 1);
								if (is_array($result)) {
									foreach ($result as $row) {
										$departamento = $row["dep_codigo"];
										$departamento_desc = utf8_decode($row["dep_nombre"]);
										$result_obs = $ClsEje->get_observaciones_departamento($ejecucion, $departamento);
										$depObservacion = "";
										if (is_array($result_obs)) {
											foreach ($result_obs as $row_obs) {
												$depObservacion = utf8_decode($row_obs["obs_observacion"]);
											}
										}
								?>
										<div class="row">
											<div class="col-md-10 ml-auto mr-auto">
												<label><?php echo $departamento_desc; ?>:</label>
												<input type="text" class="form-control" value="<?php echo $depObservacion; ?>" onkeyup="texto(this);" onblur="depObservaciones(<?php echo $ejecucion; ?>,<?php echo $departamento; ?>,this.value);" />
											</div>
										</div>
									<?php
									}
								} else {
									?>
									<div class="row">
										<div class="col-md-10 ml-auto mr-auto">
											<label>No se registran departamentos a registrar observaciones...</label>
										</div>
									</div>
								<?php
								}
								?>
								<br>
								<div class="row">
									<div class="col-md-10 ml-auto mr-auto">
										<h5>Observaciones generales:</h5>
										<textarea class="form-control" name="obs" id="obs" onkeyup="textoLargo(this);" rows="5"><?php echo $EjeObservacion; ?></textarea>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-6 ml-auto mr-auto text-center">
										<button type="button" class="btn btn-default btn-lg" onclick="window.history.back();"><i class="fa fa-chevron-left"></i> Regresar</button>
										<button type="button" class="btn btn-success btn-lg" id="btn-cerrar" onclick="cerrarEjecucion();"><i class="fa fa-folder"></i> Cerrar</button>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/auditoria/ejecucion.js"></script>
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
<?php
function secciones_auditoria($ejecucion, $codigo_audit, $ponderacion_audit, $hashkey1, $hashkey2, $hashkey3)
{
	$ClsEje = new ClsEjecucion();
	$ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_secciones('', $codigo_audit, 1);
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "150px">Secci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "10px">Respuestas</th>';
		$salida .= '<th class = "text-center" width = "10px">Pendientes</th>';
		$salida .= '<th class = "text-center" width = "10px">N/A</th>';
		$salida .= '<th class = "text-center" width = "10px">Nota</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$NOTA = 0;
		$PREGUNTAS = 0;
		$PENDIENTES = 0;
		$RESPUESTAS = 0;
		foreach ($result as $row) {
			$seccion_codigo = trim($row["sec_codigo"]);
			$preguntas = 0;
			$pendientes = 0;
			$resueltas = 0;
			$noaplica = 0;
			$result_preguntas = $ClsAud->get_pregunta('', $codigo_audit, $seccion_codigo, 1);
			if (is_array($result_preguntas)) {
				foreach ($result_preguntas as $row_preguntas) {
					$preguntas++;
				}
			}
			$result_respuesta = $ClsEje->get_respuesta($ejecucion, $codigo_audit, '', '', $seccion_codigo);
			$validas = 0;
			$nota = 0;
			$suma = 0;
			$pesoTotal = 0;
			$si = 0;
			$no = 0;
			$na = 0;
			if (is_array($result_respuesta)) {
				foreach ($result_respuesta as $row_respuesta) {
					$aplica = trim($row_respuesta["resp_aplica"]);
					if ($aplica == 1) {
						$resueltas++;
					} else {
						$noaplica++;
					}
					//echo $ponderacion_audit."<br>";
					//---------------------------------------
					if ($aplica == 1) { //// SI LA RESPUESTA APLICA PARA ESA AUDITORIA, CALCULA NOTA
						if ($ponderacion_audit == 1) {
							$suma += intval($row_respuesta["resp_respuesta"]); // Encuestas de 1 a 10 (saca promedio)
						} else if ($ponderacion_audit == 2) {
							$respuesta = trim($row_respuesta["resp_respuesta"]);
							$peso = trim($row_respuesta["resp_peso"]);
							if ($respuesta == 1) {
								$suma += $peso; /// encuesta si y no, suma los pesos
							}
							$pesoTotal += $peso;
							//(score x weighted average / sum of weighted avg ) x 100
						} else if ($ponderacion_audit == 3) {
							$respuesta = trim($row_respuesta["resp_respuesta"]);
							if ($respuesta == 1) {
								$si++;   ///// Encuestas de SAT y NO SAT (cuentas cada respuesta y regla de 3 para porcentajes de SI)
							} else if ($respuesta == 2) {
								$no++;  ///// Encuestas de SAT y NO SAT (cuentas cada respuesta y regla de 3 para porcentajes de SI)
							}
						}
						$validas++;
					}
				}
				/////// calcula la nota
				//echo $ponderacion_audit."<br>";
				if ($ponderacion_audit == 1) { ///// Encuestas de 1 a 10 (saca promedio)
					$nota = $suma / $validas; // $validas -> total de preguntas validas
					$nota = $nota * 10; //para promediar sobre 100 puntos
				} else if ($ponderacion_audit == 2) {  ///// Encuestas de SI, NO y N/A (cuentas cada respuesta y regla de 3 para porcentajes de SI)
					$nota = round(($suma * 100) / $pesoTotal); //regla de 3 entre el peso de las respuestas positivas y el peso total de las VALIDAS
				} else if ($ponderacion_audit == 3) {  ///// Encuestas de SAT y NO SAT (cuentas cada respuesta y regla de 3 para porcentajes de SAT)
					$total_si = $si;
					$total_no = $no;
					$total_respuestas = $total_si + $total_no;
					if ($total_respuestas > 0) {
						$nota = round(($total_si * 100) / $total_respuestas);
					} else {
						$nota = 0;
					}
				} else {
					$nota = 0;
				}
			}
			$nota = number_format($nota, 2, '.', '');
			///--
			$PREGUNTAS += $preguntas;
			$RESPUESTAS += ($resueltas + $noaplica);
			$pendientes = $preguntas - ($resueltas + $noaplica);
			$pendientes = ($pendientes < 0) ? 0 : $pendientes;
			$PENDIENTES += $pendientes;
			//---
			$salida .= '<tr>';
			//titulo
			$titulo = utf8_decode($row["sec_numero"]) . ". " . utf8_decode($row["sec_titulo"]);
			$salida .= '<td class = "text-left">';
			$salida .= '<a  href="FRMseccion.php?seccion=' . $seccion_codigo . '&hashkey1=' . $hashkey1 . '&hashkey2=' . $hashkey2 . '&hashkey3=' . $hashkey3 . '">' . $titulo . ' <i class="fa  fa-angle-right"></i></a>';
			$salida .= '</td>';
			//---
			$salida .= '<td class = "text-center">';
			$salida .= '<a class="btn btn-info btn-round btn-sm" href="FRMseccion.php?seccion=' . $seccion_codigo . '&hashkey1=' . $hashkey1 . '&hashkey2=' . $hashkey2 . '&hashkey3=' . $hashkey3 . '" title="Resuletas (con respuesta)">' . $resueltas . '</a>';
			$salida .= '</td>';
			//---
			$salida .= '<td class = "text-center">';
			$salida .= '<a class="btn btn-danger btn-round btn-sm" href="FRMseccion.php?seccion=' . $seccion_codigo . '&hashkey1=' . $hashkey1 . '&hashkey2=' . $hashkey2 . '&hashkey3=' . $hashkey3 . '" title="Pendientes">' . $pendientes . '</a>';
			$salida .= '</td>';
			//---
			$salida .= '<td class = "text-center">';
			$salida .= '<a class="btn btn-default active btn-round btn-sm" href="FRMseccion.php?seccion=' . $seccion_codigo . '&hashkey1=' . $hashkey1 . '&hashkey2=' . $hashkey2 . '&hashkey3=' . $hashkey3 . '" title="No aplican">' . $noaplica . '</a>';
			$salida .= '</td>';
			//---
			$salida .= '<td class = "text-center">';
			$salida .= '<a class="btn btn-white btn-round btn-sm" href="FRMseccion.php?seccion=' . $seccion_codigo . '&hashkey1=' . $hashkey1 . '&hashkey2=' . $hashkey2 . '&hashkey3=' . $hashkey3 . '" title="Nota" >' . $nota . '</a>';
			$salida .= '</td>';
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
}


function calcula_nota_global($ejecucion, $codigo_audit, $ponderacion_audit)
{
	$ClsEje = new ClsEjecucion();
	$ClsAud = new ClsAuditoria();
	$result = $ClsEje->get_respuesta($ejecucion, $codigo_audit, '', '');
	$validas = 0;
	$nota = 0;
	$suma = 0;
	$pesoTotal = 0;
	$si = 0;
	$no = 0;
	if (is_array($result)) {
		foreach ($result as $row) {
			$aplica = trim($row["resp_aplica"]);
			if ($aplica == 1) { //// SI LA RESPUESTA APLICA PARA ESA AUDITORIA, CALCULA NOTA
				if ($ponderacion_audit == 1) {
					$suma += intval($row["resp_respuesta"]); // Encuestas de 1 a 10 (saca promedio)
				} else if ($ponderacion_audit == 2) {
					$respuesta = trim($row["resp_respuesta"]);
					$peso = trim($row["resp_peso"]);
					if ($respuesta == 1) {
						$suma += $peso; /// encuesta si y no, suma los pesos
					}
					$pesoTotal += $peso;
					//(score x weighted average / sum of weighted avg ) x 100
				} else if ($ponderacion_audit == 3) {
					$respuesta = trim($row["resp_respuesta"]);
					if ($respuesta == 1) {
						$si++;   ///// Encuestas de SAT y NO SAT (cuentas cada respuesta y regla de 3 para porcentajes de SI)
					} else if ($respuesta == 2) {
						$no++;  ///// Encuestas de SAT y NO SAT (cuentas cada respuesta y regla de 3 para porcentajes de SI)
					}
				}
				$validas++;
			}
		}

		/////// calcula la nota
		if ($ponderacion_audit == 1) { ///// Encuestas de 1 a 10 (saca promedio)
			$nota = $suma / $validas; // $validas -> total de preguntas validas
			$nota = $nota * 10; //para promediar sobre 100 puntos
		} else if ($ponderacion_audit == 2) {  ///// Encuestas de SI, NO y N/A (cuentas cada respuesta y regla de 3 para porcentajes de SI)
			$nota = round(($suma * 100) / $pesoTotal); //regla de 3 entre el peso de las respuestas positivas y el peso total de las VALIDAS
		} else if ($ponderacion_audit == 3) {  ///// Encuestas de SAT y NO SAT (cuentas cada respuesta y regla de 3 para porcentajes de SAT)
			$total_si = $si;
			$total_no = $no;
			$total_respuestas = $total_si + $total_no;
			if ($total_respuestas > 0) {
				$nota = round(($total_si * 100) / $total_respuestas);
			} else {
				$nota = 0;
			}
		} else {
			$nota = 0;
		}
	}
	return $nota = number_format($nota, 2, '.', '');
}


?>