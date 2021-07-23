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
		<?php echo sidebar("", "indicador"); ?>
		<div class="main-panel">
			<?php echo navbar(); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<h5 class="card-title">
									<i class="fas fa-institution"></i> <b>KPI'S</b> Key Process Indicators: Indicadores de Procesos Estrategicos
								</h5>
							</div>
							<div class="card-body">
								<div id="sistemas" role="tablist" aria-multiselectable="true" class="card-collapse">
									<?php echo utf8_decode(sistemas_acordion()); ?>
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
								<h4 class="card-title"><i class="nc-icon nc-chart-pie-36"></i> Estad&iacute;sticas de Cumplimiento</h4>
							</div>
							<div class="card-body ">
								<div class="row">
									<div class="col-md-6 text-center">
										<h5 class="card-category">Cumplimiento General de la Organizaci&oacute;n</h5>
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
									<div class="col-md-6 text-center">
										<h5 class="card-category">Cumplimiento por Tipo de Proceso</h5>
										<div id="stocked0Container"></div>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-6 text-center">
										<h5 class="card-category">Cumplimiento de Procesos Estrategicos</h5>
										<div id="stocked1Container"></div>
									</div>
									<div class="col-md-6 text-center">
										<h5 class="card-category">Cumplimiento por Sistema</h5>
										<div id="stocked2Container"></div>
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
				<!--  Grafica de Lecturas -->
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-6">
						<div class="card">
							<div class="card-header ">
								<h4 class="card-title"><i class="nc-icon nc-chart-pie-36"></i> Estad&iacute;sticas de Lecturas</h4>
							</div>
							<div class="card-body ">
								<div class="row">
									<div class="col-md-6 text-center">
										<h5 class="card-category">Lecturas porcentaje general</h5>
										<div id="pieContainer2"></div>
									</div>
									<div class="col-md-6 text-center">
										<h5 class="card-category">Lecturas por Tipo de Proceso</h5>
										<div id="stocked3Container"></div>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-6 text-center">
										<h5 class="card-category">Lecturas de Procesos Estrategicos</h5>
										<div id="stocked4Container"></div>
									</div>
									<div class="col-md-6 text-center">
										<h5 class="card-category">Lecturas por Sistema</h5>
										<div id="stocked5Container"></div>
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
				<!--  Reporte General de Indicadores -->
				<div class="row">
					<div class="col-lg-12" id="result">
						<div class="card">
							<div class="card-header ">
								<h4 class="card-title"><i class="nc-icon nc-chart-pie-36"></i> KPI'S</h4>
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
				<?php echo footer() ?>
			</div>
		</div>
		<?php echo modal() ?>
		<?php echo scripts() ?>
		<script type="text/javascript" src="assets.1.2.8/js/modules/menu/menu_indicador.js"></script>

</body>
</html>
<?php
function tabla_cumplimiento()
{
	$ClsInd = new ClsIndicador();
	$ClsRev = new ClsRevision();
	$ClsFic = new ClsFicha();
	$procesos = $ClsFic->get_ficha("", 3);

	if (is_array($procesos)) {
		$salida = '<table id="tabla" class="table table-striped dataTables-example" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida .= '<th class = "text-left" width = "75px">Nombre</th>';
		$salida .= '<th class = "text-left" width = "75px">Proceso</th>';
		$salida .= '<th class = "text-left" width = "75px">Sistema</th>';
		$salida .= '<th class = "text-center" width = "40px">Cumplimiento</th>';
		$salida .= '<th class = "text-center" width = "40px">Promedio</th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 0;
		if(is_array($procesos)){
			foreach ($procesos as $rowProceso) {
				$proceso = trim($rowProceso["fic_codigo"]);
				$result = $ClsInd->get_indicador("", "", $proceso, "", "", "", "", 1);
				if(is_array($result)){
					foreach ($result as $row) {
						$salida .= '<tr>';
						//codigo
						$codigo = $row["ind_codigo"];
						$salida .= '<td class = "text-center" >';
						$salida .= '<div class="btn-group">';
						$salida .= '<button type="button" class="btn btn-dark btn-xs">' . Agrega_Ceros($codigo) . '</button>';
						$salida .= '<button type="button" class="btn btn-dark btn-xs" onclick = "detalle(' . $codigo . ');" title = "Ver Detalles" ><span class="fa fa-search"></span></button>';
						$salida .= '</div>';
						$salida .= '</td>';
						//nombre
						$nombre = trim($row["ind_nombre"]);
						$salida .= '<td class = "text-left">' . $nombre . '</td>';
						//proceso
						$proceso = trim($row["obj_proceso"]);
						$salida .= '<td class = "text-left">' . $proceso . '</td>';
						//sistema
						$sistema = trim($row["obj_sistema"]);
						$salida .= '<td class = "text-left">' . $sistema . '</td>';
						// Cumplimiento
						$indicador = trim($row["ind_codigo"]);
						$numero_programaciones = $ClsInd->count_programacion("", $indicador);
						$numero_revisiones = $ClsRev->count_revision_indicador("", $indicador);
						$promedio_indicador = 0;
						if ($numero_programaciones != 0) $promedio_indicador = $numero_revisiones / $numero_programaciones;
						$salida .= '<td class = "text-center">' . round($promedio_indicador * 100, 2) . ' %</td>';
						// Evaluacion
						$umed = trim($row["medida_nombre"]);
						$revisiones = $ClsRev->get_revision_indicador("", $indicador);
						$promedio_indicador = 0;
						$j = 0;
						foreach ($revisiones as $rowRevision) {
							$lectura = trim($rowRevision["rev_lectura"]);
							$j++;
							$promedio_indicador += intval($lectura);
						}
						if ($j != 0) $promedio_indicador = round($promedio_indicador / $j, 2);
						$salida .= '<td class = "text-center">' . $promedio_indicador  . ' ' . $umed . '</td>';
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