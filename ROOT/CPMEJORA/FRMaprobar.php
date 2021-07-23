<?php
include_once('html_fns_mejora.php');
validate_login("../");
$id = $_SESSION["codigo"];

$ClsPla = new ClsPlan();
$hashkey = $_REQUEST["hashkey"];
$origen = $_REQUEST["origen"];
$codigo = $ClsPla->decrypt($hashkey, $id);
$result = $ClsPla->get_plan_mejora($codigo);
if (is_array($result)) {
	foreach ($result as $row) {
		$codigo = trim($row["pla_codigo"]);
		$justificacion = utf8_decode($row["pla_justificacion"]);
		$hallazgo = utf8_decode($row["pla_hallazgo"]);
		// obtiene Hallazgo
		$ClsHal = new ClsHallazgo();
		switch ($origen) {
			case 1:
				$result = $ClsHal->get_hallazgo_auditoria_interna($hallazgo);
				break;
			case 2:
				$result = $ClsHal->get_hallazgo_auditoria_externa($hallazgo);
				break;
			case 3:
				$result = $ClsHal->get_hallazgo_queja($hallazgo);
				break;
			case 4:
				$result = $ClsHal->get_hallazgo_indicador($hallazgo);
				break;
			case 5:
				$result = $ClsHal->get_hallazgo_riesgo($hallazgo);
				break;
			case 6:
				$result = $ClsHal->get_hallazgo_requisito($hallazgo);
				break;

		}
		if (is_array($result)) {
			foreach ($result as $row) {
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
				<?php if ($tipo == "No conformidad") { ?>
					<div class="row">
						<div class="col-md-12">
							<div class="card demo-icons">
								<div class="card-header">
									<h5 class="card-title"><i class="fas fa-sitemap"></i> Anal&iacute;sis de Causa Ra&iacute;z
										<a class="btn btn-white btn-lg pull-right" href="CPREPORTES/REPpdf.php?hashkey=<?php echo $hashkey ?>" target="_blank" title="Imprimir Actividades" id="pdf"><i class="fa fa-print"></i></a>
									</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-md-12">
										<label>Observacion del Analisis:</label>
											<textarea class="form-control textarea-autosize"></textarea>
										</div>
									</div>
									<div class="row">
										<?php echo utf8_decode(causas("", $codigo, 0)) ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fab fa-wpforms"></i> Plan de Acci&oacute;n
									<a class="btn btn-white btn-lg pull-right" href="CPREPORTES/REPpdf.php?hashkey=<?php echo $hashkey ?>" target="_blank" title="Imprimir Actividades" id="pdf"><i class="fa fa-print"></i></a>
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<?php echo utf8_decode(actividades("", $codigo)) ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-xs-12 text-center">
						<input type="hidden" id="codigo" name="codigo" value="<?php echo $codigo; ?>" />
						<a type="button" class="btn btn-default " href="FRMaprobacion.php"><span class="fa fa-chevron-left"></span> Regresar</a>
						<button type="button" class="btn btn-warning " onclick="solicitarCorreccion(<?php echo $codigo; ?>);"><span class="fa fa-exclamation"></span> Solicitar Correcci&oacute;n</button>
						<button type="button" class="btn btn-info " onclick="aprobar(<?php echo $codigo; ?>);"><span class="fa fa-check"></span> Aprobar</button>
					</div>
				</div>
			</div>
			<?php echo footer() ?>
		</div>
	</div>
	<?php echo modal("../"); ?>
	<?php echo scripts("../"); ?>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/mejora/plan.js"></script>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/mejora/actividad.js"></script>
</body>

</html>
<?php
function causas($codigo  = '', $plan = '', $pertenece = '')
{
	$ClsCau = new ClsCausa();
	$result = $ClsCau->get_causa($codigo, $plan, $pertenece);
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example">';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5px">No.</th>';
		$salida .= '<th class = "text-left" width = "450px">Causa</th>';
		if ($pertenece == "" || $pertenece == 0) $salida .= '<th class = "text-center" width = "10px"><i class="fas fa-network-wired"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			// No. 
			$salida .= '<td class = "text-center">' . $i . '</td>';
			// Causa
			$causa = trim($row["cau_descripcion"]);
			$salida .= '<td class = "text-left">' . $causa . '</td>';
			//codigo
			$codigo = $row["cau_codigo"];
			//--
			if ($pertenece == "" || $pertenece == 0) {
				$salida .= '<td class = "text-center" >';
				$salida .= '<div class="btn-group">';
				$salida .= '<a class="btn btn-info btn-xs" onclick="subcausa(' . $codigo . ',\'' . $causa . '\')" title = "Agregar Subcausa" ><i class="fa fa-arrow-right"></i></a>';
				$salida .= '</div>';
				$salida .= '</td>';
			}
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}
function actividades($codigo, $plan)
{
	$ClsAct = new ClsActividad();
	$result = $ClsAct->get_actividad_mejora($codigo, $plan);
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" id="tabla" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%"> No. </th>';
		$salida .= '<th class = "text-left" width = "25%">Actividad</th>';
		$salida .= '<th class = "text-center" width = "10%">Fecha de Inicio</th>';
		$salida .= '<th class = "text-center" width = "10%">Fecha Final</th>';
		$salida .= '<th class = "text-center" width = "5%">Programaci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "20%">Comentario</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			// No. 
			$salida .= '<td class = "text-center">' . $i . '</td>';
			// Descripcion
			$descripcion = trim($row["act_descripcion"]);
			$descripcion = nl2br($descripcion);
			$salida .= '<td class = "text-left">' . $descripcion . '</td>';
			// Fecha Inicio
			$fini = cambia_fecha($row["act_fecha_inicio"]);
			$salida .= '<td class = "text-left">' . $fini . '</td>';
			// Fecha Final
			$ffin = cambia_fecha($row["act_fecha_fin"]);
			$salida .= '<td class = "text-left">' . $ffin . '</td>';
			// Programacion
			$codigo = $row["act_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-info btn-xs" onclick = "verProgramacion(' . $codigo . ');" title = "Programaci&oacute;n de Actividad" ><i class="fa fa-calendar"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			// Comentario
			$comentario = trim($row["act_comentario"]);
			$codigo = trim($row["act_codigo"]);
			$salida .= '<td class = "text-left"><textarea type="text" class="form-control textarea-autosize" onblur = "update(' . $codigo . ',this,1);">' . $comentario . '</textarea></td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}
?>