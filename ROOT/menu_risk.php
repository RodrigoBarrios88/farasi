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
		<?php echo sidebar("", "risk"); ?>
		<div class="main-panel">
			<?php echo navbar(); ?>
			<div class="content">
				<!--  Reporte General de Indicadores -->
				<div class="row">
					<div class="col-lg-12" id="result">
						<div class="card">
							<div class="card-header ">
								<h4 class="card-title"><i class="fas fa-business-time"></i> Riesgos y Oportunidades de Procesos Estrategicos</h4>
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
		<script type="text/javascript" src="assets.1.2.8/js/modules/menu/menu_ryo.js"></script>

</body>

</html>
<?php
function tabla_cumplimiento()
{

	// Plan de Riesgo Aprobado
	$ClsRie = new ClsRiesgo();
	$ClsOpo = new ClsOportunidad();
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
		$salida .= '<th class = "text-center" width = "250px">Riesgo/Oportunidad</th>';
		$salida .= '<th class = "text-center" width = "20px">Cumplimiento</th>';
		$salida .= '<th class = "text-center" width = "20px">Evaluaci&oacute;n</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		foreach ($procesos as $rowProceso) {
			$ficha = trim($rowProceso["fic_codigo"]);
			$i = 1;
			$clase = 1;
			while ($clase <= 2) {
				$aprobados = ($clase == 1) ? $ClsRie->get_riesgo("", "", $ficha) :  $ClsOpo->get_oportunidad("", "", $ficha);
				if (is_array($aprobados)) {
					foreach ($aprobados as $rowAprobado) {
						$salida .= '<tr>';
						//codigo
						$salida .= '<td class = "text-center" >';
						$salida .= '<div class="btn-group">';
						$salida .= '<button type="button" class="btn btn-dark btn-xs">' . $i . '.</button>';
						if ($clase == 1) {
							$codigo = trim($rowAprobado["rie_codigo"]);
							$salida .= '<button type="button" class="btn btn-dark btn-xs" onclick = "detalle(' . $codigo . ');" title = "Ver Detalles" ><span class="fa fa-search"></span></button>';
						} else {
							$codigo = trim($rowAprobado["opo_codigo"]);
							$salida .= '<button type="button" class="btn btn-dark btn-xs" onclick = "detalle_oportunidad(' . $codigo . ');" title = "Ver Detalles" ><span class="fa fa-search"></span></button>';
						}
						$salida .= '</div>';
						$salida .= '</td>';
						// Proceso
						$proceso = trim($rowAprobado["fic_nombre"]);
						$salida .= '<td class = "text-left">' . $proceso . '</td>';
						// Sistema
						$sistema = trim($rowAprobado["sis_nombre"]);
						$salida .= '<td class = "text-left">' . $sistema . '</td>';
						// Tipo
						$tipo = trim($rowAprobado["fod_descripcion"]);
						$tipo = nl2br($tipo);
						$salida .= '<td class = "text-left">' . $tipo . '</td>';
						//--
						$promedio_objetivo = 0;
						$promedioPunteo = 0;
						$total = 0;
						if ($clase == 1) {
							$codigo = trim($rowAprobado["rie_codigo"]);
							$result = $ClsPla->get_plan_ryo("", $codigo, "", "", "", "", 3);
						} else {
							$codigo = trim($rowAprobado["opo_codigo"]);
							$result = $ClsPla->get_plan_ryo("", "", $codigo, "", "", "", 3);
						}
						if (is_array($result)) {
							foreach ($result as $row) {
								$plan = $row["pla_codigo"];
								$numero_programaciones = intval($ClsAct->count_programacion("", $plan)); // Todas
								$numero_evaluaciones = intval($ClsAct->count_programacion("", $plan, "", "", 5)); // Finalizadas
								// Cumplimiento
								if ($numero_programaciones != 0) $promedio_objetivo += round(($numero_evaluaciones / $numero_programaciones) * 100, 2);
								// Evaluacion
								$evaluaciones = $ClsAct->get_programacion("", $plan, "", "", "5");
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
				$clase++;
			}
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}

function tabla_objetivos($ficha, $sistema)
{
	$ClsObj = new ClsObjetivo();
	$result = $ClsObj->get_objetivo("", $ficha, $sistema);

	$salida = '<table class="table table-borderless" >';
	if (is_array($result)) {
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			// No.
			$salida .= '<td width = "5%" class = "text-center">' . $i . '.</td>';
			// Descripcion
			$descripcion = trim($row["obj_descripcion"]);
			$salida .= '<td width = "85%" class = "text-left">'  . $descripcion . '</td>';
			// Codigo
			$codigo = trim($row["obj_codigo"]);
			$salida .= '<td width = "10%" class = "text-left">';
			$salida .= '<button class="btn btn-dark btn-xs" onclick="detalle(' . $codigo . ')" title = "Seleccionar Indicador" ><i class="fa fa-chart-line"></i> KPI</button> ';
			$salida .= '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
	}
	$salida .= '</table>';

	return $salida;
}

function detalle_acordion($sistema)
{
	$salida = "";
	// Obtener los Subtitulos de los Procesos 
	$ClsPro = new ClsProceso();
	$ClsFic = new ClsFicha();
	$ClsObj = new ClsObjetivo();
	// 3 -> codigo solo para estrategicos
	$subtitulos = $ClsPro->get_subtitulo(3, 2);
	if (is_array($subtitulos)) {
		foreach ($subtitulos as $row) {
			$nombre = utf8_decode($row['sub_nombre']);
			$salida .= '<div class="row">';
			$salida .= '<div class="col-md-12">';
			$salida .= '<div class="card card-plain">';
			$salida .= '<div class="card-body">';
			$salida .= '<label>Objetivos de Procesos ' . $nombre . ':</label>';
			// Obtener las fichas
			$fichas = $ClsFic->get_ficha('', $row['sub_codigo'], '', '', '', '', '1,2,3');
			foreach ($fichas as $row2) {
				$ficha = trim($row2['fic_codigo']);
				$objetivos = $ClsObj->get_objetivo("", $ficha, $sistema);
				if (is_array($objetivos)) {
					$proceso = trim($row2['fic_nombre']);
					$salida .= '<div class="row">';
					$salida .= '<div class="col-md-12">';
					$salida .= '<small>' . $proceso . '</small>';
					$salida .= '</div>';
					$salida .= '</div>';
					$salida .= '<div class="row">';
					$salida .= '<div class="col-md-12">';
					$salida .= tabla_objetivos($ficha, $sistema);
					$salida .= '</div>';
					$salida .= '</div>';
				}
			}
			$salida .= '</div>';
			$salida .= '</div>';
			$salida .= '</div>';
			$salida .= '</div>';
		}
	}
	return $salida;
}

function sistemas_acordion()
{
	$ClsSis = new ClsSistema();
	$result = $ClsSis->get_sistema();
	$salida = "";
	if (is_array($result)) {
		$i = 1;
		foreach ($result as $row) {
			//codigo
			$codigo = trim($row["sis_codigo"]);
			$nombre = trim($row["sis_nombre"]);
			$politica = trim($row["sis_politica"]);
			//--
			$salida .= '<div class="card card-plain">';
			$salida .= '<div class="card-body m-2" role="tab" id="panel' . $codigo . '">';
			$salida .= '<a data-toggle="collapse" data-parent="#accordion" href="#collapse' . $codigo . '" aria-expanded="false" aria-controls="collapse' . $codigo . '">';
			$salida .= '<div class="row">';
			$salida .= '<div class="col-md-11">';
			$salida .= '<div class="row">';
			$salida .= '<div class="col-md-12">';
			$salida .= $i . '. <label> Pol&iacute;tica de ' . $nombre . ':</label>';
			$salida .= '</div>';
			$salida .= '</div>';
			$salida .= '<div class="row">';
			$salida .= '<div class="col-md-12">';
			$salida .= 	'<small> ' . $politica . '</small>';
			$salida .= '</div>';
			$salida .= '</div>';
			$salida .= '</div>';
			$salida .= '<div class="col-md-1">';
			$salida .= '<i class="nc-icon nc-minimal-down"></i>';
			$salida .= '</div>';
			$salida .= '</div>';
			$salida .= '</a>';
			$salida .= '</div>';
			//--
			$salida .= '<div id="collapse' . $codigo . '" class="collapse" role="tabpanel" aria-labelledby="panel' . $codigo . '">';
			$salida .= '<div class="card-body">';
			$salida .= detalle_acordion($codigo);
			$salida .= '</div>';
			$salida .= '</div>';
			$salida .= '</div>';
			$i++;
		}
	} else {
		$salida = '<div class="row">';
		$salida .= '<div class="col-xs-12 col-md-12">';
		$salida .= '<h5 class="alert alert-warning text-center">';
		$salida .= '<i class="fa fa-information-circle"></i> No existen sistemas...';
		$salida .= '</h5>';
		$salida .= '</div>';
		$salida .= '</div>';
	}

	return $salida;
}
?>