<?php
include_once('html_fns.php');
validate_login();
$id = $_SESSION["codigo"];


//--
$desde =  date("01/01/Y"); //valida que si no se selecciona fecha, coloque la del dia
$hasta =  date("d/m/Y");  //valida que si no se selecciona fecha, coloque la del dia
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
			<?php echo sidebar("","planning"); ?>
			<div class="main-panel">
				<?php echo navbar(); ?>
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title">
										<i class="fas fa-institution"></i> Descripci&oacute;n de Sistemas
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
					<!--  Grafica de Rangos -->
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-6">
							<div class="card">
								<div class="card-header ">
									<h4 class="card-title"><i class="nc-icon nc-chart-pie-36"></i> Estad&iacute;sticas de Cumplimiento de Objetivos</h4>
								</div>
								<div class="card-body ">
									<div class="row">
										<div class="col-md-6 text-center">
											<h5 class="card-category">Cumplimiento General de la Organizaci&oacute;n</h5>
											<div id="pieContainer"></div>
										</div>
										<div class="col-md-6 text-center">
											<h5 class="card-category">Cumplimiento de Procesos Estrategicos</h5>
											<div id="stocked0Container"></div>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-md-6 text-center">
											<h5 class="card-category">Cumplimiento por Tipo de Proceso</h5>
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
					<?php echo footer(); ?>
				</div>
			</div>	
			<?php echo modal() ?>
				
			<?php echo scripts() ?>
			
			
			<script type="text/javascript" src="assets.1.2.8/js/modules/menu/menu_planning.js"></script>

	</body>

	</html>
<?php
function tabla_objetivos($ficha, $sistema)
{
	$ClsObj = new ClsObjetivo();
	$result = $ClsObj->get_objetivo("", $ficha, $sistema);
	//var_dump($result);	
	$salida = '<table class="table table-borderless" >';
	if (is_array($result)) {
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			$descripcion = trim($row["obj_descripcion"]);
				//No.
				$salida .= '<td width = "5%" class = "text-center">' . $i . '.</td>';
				//Descripcion
				if($descripcion == ""){
					$salida .= '<td width = "95%" class = "text-left">No se agregado una descripcion a este objetivo</td>';
				}else{
				$salida .= '<td width = "95%" class = "text-left">'  . $descripcion . '</td>';
				}
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
	//var_dump($subtitulos); 
	//die();
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
	//var_dump($result);
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