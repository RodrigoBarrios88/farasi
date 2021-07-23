<?php
include_once('html_fns.php');
validate_login();
$id = $_SESSION["codigo"];

//$_POST
$desde = date("01/01/Y"); //valida que si no se selecciona fecha, coloque la del dia
$hasta = date("d/m/Y"); //valida que si no se selecciona fecha, coloque la del dia
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head(); ?>
	<!-- Estilo especifico -->
	<link href="assets.1.2.8/css/propios/dashboard.css" rel="stylesheet">
</head>

<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("", "mejora"); ?>
		<div class="main-panel">
			<?php echo navbar(); ?>
			<div class="content">
				<!--  Reporte General de Indicadores -->
				<div class="row">
					<div class="col-lg-12" id="result">
						<div class="card">
							<div class="card-header ">
								<h4 class="card-title"><i class="fas fa-business-time"></i> Mejora Continua de Procesos Estrategicos</h4>
							</div>
							<div class="card-body ">
								<div class="row">
									<div class="col-lg-12" id="result">
										<?php echo utf8_decode(tabla_cumplimiento()); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--  Grafica de Cumplimientos -->
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<div class="card">
							<div class="card-header ">
								<h4 class="card-title"><i class="nc-icon nc-chart-pie-36"></i> Estad&iacute;sticas de Cumplimiento de Procesos Estrategicos</h4>
							</div>
							<div class="card-body ">
								<div class="row">
									<div class="col-md-6 text-center">
										<h5 class="card-category">Cumplimiento por Proceso</h5>
										<div id="stocked1Container"></div>
									</div>
									<div class="col-md-6 text-center">
										<h5 class="card-category">Cumplimiento por Sistema</h5>
										<div id="stocked2Container"></div>
									</div>

								</div>
								<div class="row">
									<div class="col-md-12 text-center">
										<h5 class="card-category">Cumplimiento Porcentaje General</h5>
										<div id="pieContainer"></div>
										<br>
										<div class="progress progress-striped active">
											<div id="progressEjecutado" class="progress-bar progress-bar-striped progress-bar-animated progress-bar-success">
												<span id="spanEjecutado"></span>
											</div>
											<div id="progressPendiente" class="progress-bar progress-bar-striped progress-bar-animated progress-bar-warning">
												<span id="spanPendiente"></span>
											</div>
											<div id="progressVencido" class="progress-bar progress-bar-striped progress-bar-animated progress-bar-danger">
												<span id="spanVencido"></span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card-footer text-right">
								<hr>
								<div class="stats">
									<i class="fa fa-clock-o"></i> <?php echo $desde . ' - ' . $hasta; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php echo footer() ?>
			</div>
		</div>
		<?php echo modal() ?>
		<?php echo scripts() ?>
		<script type="text/javascript" src="assets.1.2.8/js/modules/menu/menu_mejora.js"></script>

</body>

</html>
<?php
function tabla_cumplimiento()
{
	// Plan de Hallazgos
	$ClsHal = new ClsHallazgo();
	$ClsAct = new ClsActividad();
	$ClsPla = new ClsPlan();
	$ClsFic = new ClsFicha();
	$procesos = $ClsFic->get_ficha("", 3);
	if (is_array($procesos)) {
		$salida = '<table class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">No.</th>';
		$salida .= '<th class = "text-center" width = "50px">Proceso</th>';
		$salida .= '<th class = "text-center" width = "30px">Sistema</th>';
		$salida .= '<th class = "text-center" width = "250px">Hallazgo</th>';
		$salida .= '<th class = "text-center" width = "20px">Cumplimiento</th>';
		$salida .= '<th class = "text-center" width = "20px">Evaluaci&oacute;n</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($procesos as $rowProceso) {
			$ficha = trim($rowProceso["fic_codigo"]);
			for ($origenTipo = 1; $origenTipo <= 6; $origenTipo++) {
				$hallazgos = null;
				switch ($origenTipo) {
					case 1:
						//$hallazgos = $ClsHal->get_hallazgo_auditoria_interna("", "", $ficha);
						break;
					case 2:
						$hallazgos = $ClsHal->get_hallazgo_auditoria_externa("", "", $ficha);
						break;
					case 3:
						$hallazgos = $ClsHal->get_hallazgo_queja("", "", $ficha);
						break;
					case 4:
						$hallazgos = $ClsHal->get_hallazgo_indicador("", "", $ficha);
						break;
					case 5:
						$hallazgos = $ClsHal->get_hallazgo_riesgo("", "", $ficha);
						break;
				}
				if (is_array($hallazgos)) {
					foreach ($hallazgos as $rowHallazgo) {
						$salida .= '<tr>';
						//codigo
						$salida .= '<td class = "text-center" >';
						$salida .= '<div class="btn-group">';
						$salida .= '<button type="button" class="btn btn-dark btn-xs">' . $i . '.</button>';
						$codigo = trim($rowHallazgo["hal_codigo"]);
						$salida .= '<button type="button" class="btn btn-dark btn-xs" onclick = "detalle(' . $codigo . ',' . $origenTipo . ');" title = "Ver Detalles" ><span class="fa fa-search"></span></button>';
						$salida .= '</div>';
						$salida .= '</td>';
						// Proceso
						$proceso = trim($rowHallazgo["fic_nombre"]);
						$salida .= '<td class = "text-left">' . $proceso . '</td>';
						// Sistema
						$sistema = trim($rowHallazgo["sis_nombre"]);
						$salida .= '<td class = "text-left">' . $sistema . '</td>';
						// Tipo
						$tipo = trim($rowHallazgo["hal_descripcion"]);
						$tipo = nl2br($tipo);
						$salida .= '<td class = "text-left">' . $tipo . '</td>';
						//--
						$promedio_objetivo = 0;
						$promedioPunteo = 0;
						$total = 0;
						$codigo = trim($rowHallazgo["hal_codigo"]);
						$result = $ClsPla->get_plan_mejora("",  $codigo, "", "", "", 3);
						if (is_array($result)) {
							foreach ($result as $row) {
								$plan = $row["pla_codigo"];
								$numero_programaciones = intval($ClsAct->count_programacion_mejora("", $plan)); // Todas
								$numero_evaluaciones = intval($ClsAct->count_programacion_mejora("", $plan, "", "", 5)); // Finalizadas
								// Cumplimiento
								if ($numero_programaciones != 0) $promedio_objetivo += round(($numero_evaluaciones / $numero_programaciones) * 100, 2);
								// Evaluacion
								$evaluaciones = $ClsAct->get_programacion_mejora("", $plan, "", "", "5");
								$j = 0;
								if (is_array($evaluaciones)) {
									$pts = 0;
									foreach ($evaluaciones as $rowEvaluacion) {
										$puntuacion = intval($rowEvaluacion["pro_puntuacion"]);
										$j++;
										$pts += intval($puntuacion);
									}
								}
								if ($j != 0) {
									$promedioPunteo += round($pts / $j, 2);
									$total += $j;
								}
							}
						}
						if ($total != 0) {
							$promedio_objetivo =  round($promedio_objetivo / $total, 2);
							$promedioPunteo =  round($promedioPunteo / $total, 2);
						}
						$salida .= '<td class = "text-center">' .  $promedio_objetivo . ' %</td>';
						$salida .= '<td class = "text-center">' . $promedioPunteo . ' pts.</td>';
						$salida .= '</div>';
						$salida .= '</td>';
						//--
						$salida .= '</tr>';
						$i++;
					}
				}
			}
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}

?>