<?php
include_once('html_fns_ryo.php');
validate_login("../");
$id = $_SESSION["codigo"];
//$_POST
$ClsPla = new ClsPlan();
$ClsRie = new ClsRiesgo();
$hashkey = $_REQUEST["hashkey"];
$codigo = $ClsPla->decrypt($hashkey, $id);
$plan = $ClsPla->get_plan_ryo($codigo);
if (is_array($plan)) {
	foreach ($plan as $rowPlan) {
		$riesgo = trim($rowPlan["pla_riesgo"]);
		$responsable = utf8_decode($rowPlan["usu_nombre"]);
		$result = $ClsRie->get_riesgo($riesgo); // situacion 2 en aprobacion
		if (is_array($result)) {
			foreach ($result as $row) {
				$ficha = utf8_decode($row["rie_proceso"]);
				$proceso = utf8_decode($row["fic_nombre"]);
				$sistema = utf8_decode($row["sis_nombre"]);
				$origen = utf8_decode($row["rie_origen"]);
				$causa = utf8_decode($row["rie_causa"]);
				$consecuencia = utf8_decode($row["rie_consecuencia"]);
				$probabilidad = utf8_decode($row["rie_probabilidad"]);
				$impacto = utf8_decode($row["rie_impacto"]);
				$severidad = intval($probabilidad) * intval($impacto);
				$condicion = get_condicion($severidad);
				$accion = trim($row["rie_accion"]);
				$accion = get_accion_riesgo($accion);
				$descripcion = utf8_decode($row["fod_descripcion"]);
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
		<?php echo sidebar("../", "risk"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="nc-icon nc-paper"></i> Revisi&oacute;n de Planes de Riesgo
									<a class="btn btn-white btn-xs sin-margin pull-right" href="FRMaprobacion.php"><small><i class="fa fa-chevron-left"></i> Atr&aacute;s</small></a>
								</h5><br>
							</div>
						</div>
					</div>
				</div>
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
													<label>Responsable:</label>
													<input type="text" class="form-control" value="<?php echo $responsable; ?>" />
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
													<label>Probabildad:</label>
													<input type="text" class="form-control" value="<?php echo get_probabilidad($probabilidad); ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<label>Impacto:</label>
													<input type="text" class="form-control" value="<?php echo get_impacto($impacto); ?>" />
												</div>
												<div class="col-md-6">
													<label>Severidad:</label>
													<input type="text" class="form-control" value="<?php echo $severidad; ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<label>Condici&oacute;n:</label>
													<input type="text" class="form-control" value="<?php echo $condicion; ?>" />
												</div>
												<div class="col-md-6">
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
													<label>Riesgo:</label>
													<textarea class="form-control textarea-autosize"><?php echo $descripcion; ?></textarea>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Origen:</label>
													<textarea class="form-control textarea-autosize"><?php echo $origen; ?></textarea>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Causa:</label>
													<textarea class="form-control textarea-autosize"><?php echo $causa; ?></textarea>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Consecuencia:</label>
													<textarea class="form-control textarea-autosize"><?php echo $consecuencia; ?></textarea>
												</div>
											</div>
										</div>
										<br>
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
								<h5 class="card-title"><i class="fa fa-pencil-square-o"></i> Plan de Acci&oacute;n</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<?php echo utf8_decode(actividades("", $codigo,  1)) ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-hand-rock-o"></i> Plan Inmediato</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<?php echo utf8_decode(actividades("", $codigo,  2)) ?>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/ryo/accion.js"></script>
</body>

</html>
<?php
function actividades($codigo, $plan, $tipo = "")
{
	$ClsAct = new ClsActividad();
	$result = $ClsAct->get_actividad($codigo, $plan, $tipo);
	if (is_array($result)) {
		$salida = '<table class="table table-striped dataTables-example" id="tabla" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "5%"> No. </th>';
		$salida .= '<th class = "text-left" width = "25%">Actividad</th>';
		if ($tipo == 1) {
			$salida .= '<th class = "text-center" width = "10%">Fecha de Inicio</th>';
			$salida .= '<th class = "text-center" width = "10%">Fecha Final</th>';
			$salida .= '<th class = "text-center" width = "5%">Programaci&oacute;n</th>';
		}
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
			if ($tipo == 1) {
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
			}
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