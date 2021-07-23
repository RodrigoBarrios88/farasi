<?php
include_once('html_fns_indicador.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$ClsInd = new ClsIndicador();
$hashkey = $_REQUEST["hashkey"];
$indicador = $ClsInd->decrypt($hashkey, $id);
$tipo = $_REQUEST["tipo"];
$hini = $_REQUEST["hini"];
$hini = ($hini == "") ? "8:00" : $hini;
$hfin = $_REQUEST["hfin"];
$hfin = ($hfin == "") ? "17:00" : $hfin;

//--
$last = new DateTime();
$last->modify('last day of this month');
$ultimo = $last->format('d');
//--
$mes = date("m");
$anio = date("Y");
$desde = $_REQUEST["desde"];
$desde = ($desde == "") ? date("d/m/Y") : $desde; //valida que si no se selecciona fecha, coloque la del dia
$hasta = $_REQUEST["hasta"];
$hasta = ($hasta == "") ? date("$ultimo/m/Y") : $hasta; //valida que si no se selecciona fecha, coloque la del dia
//--
$ClsInd = new ClsIndicador();
$result = $ClsInd->get_indicador($indicador);
if (is_array($result)) {
	foreach ($result as $row) {
		$nombre = utf8_decode($row["ind_nombre"]);
		$proceso = utf8_decode($row["obj_proceso"]);
		$sistema = utf8_decode($row["obj_sistema"]);
		$min = trim($row["ind_lectura_minima"]);
		$max = trim($row["ind_lectura_maxima"]);
		$ideal = trim($row["ind_lectura_ideal"]);
		$usuario = trim($row["ind_usuario"]);
		$unidad = utf8_decode($row["medida_nombre"]);
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
		<?php echo sidebar("../", "indicador"); ?>
		<div class="main-panel">

			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-6">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="nc-icon nc-bullet-list-67"></i> Informaci&oacute;n
									<a class="btn btn-white btn-lg sin-margin pull-right" href="FRMindicador.php"><small><i class="fa fa-chevron-left"></i> Atr&aacute;s</small></a>
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12">
										<div class="row">
											<div class="col-md-12">
												<label>Usuario:</label>
												<input type="text" class="form-control" value="<?php echo $usuario; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Indicador:</label>
												<input type="text" class="form-control" value="<?php echo $nombre; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Proceso:</label>
												<input type="text" class="form-control" value="<?php echo $proceso; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Sistema:</label>
												<input type="text" class="form-control" value="<?php echo $sistema; ?>" disabled />
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
									<i class="fa fa-clock"></i> Planificaci&oacute;n
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12">
										<div class="row">
											<div class="col-md-12">
												<label>Unidad de Medida:</label>
												<input type="text" class="form-control" value="<?php echo $unidad; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Lectura Minima:</label>
												<input type="text" class="form-control" value="<?php echo $min; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Lectura Ideal:</label>
												<input type="text" class="form-control" value="<?php echo $ideal; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Lectura M&aacute;xima:</label>
												<input type="text" class="form-control" value="<?php echo $max; ?>" disabled />
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
								<h5 class="card-title"><i class="fa fa-users-cog"></i> Mi programaci&oacute;n para el indicador</h5>
							</div>
							<div class="card-body all-icons">
								<form name="f1" id="f1" action="" method="get">
									<div class="row">
										<div class="col-xs-6 col-md-6 text-left">
											<button type="button" class="btn btn-white" onclick="window.history.back();">
												<i class="fa fa-chevron-left"></i>Atr&aacute;s
											</button>
										</div>
										<div class="col-xs-6 col-md-6 text-right"><label class=" text-danger">* Campos Obligatorios</label> </div>
									</div>
									<input type="text" id="indicador" class="form-control" value="<?php echo $indicador; ?>" hidden />
									<div class="row">
										<div class="col-md-6">
											<label>Rango de Fechas:</label> <span class="text-danger">*</span>
											<div class="form-group" id="range">
												<div class="input-daterange input-group" id="datepicker">
													<input type="text" class="input-sm form-control" name="desde" id="desde" value="<?php echo $desde; ?>" />
													<span class="input-group-addon"> &nbsp; <i class="fa fa-calendar"></i> &nbsp; </span>
													<input type="text" class="input-sm form-control" name="hasta" id="hasta" value="<?php echo $hasta; ?>" />
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<label>Rango de Horas:</label> <span class="text-danger">*</span>
											<div class="form-group" id="range">
												<div class="input-group">
													<input type="text" class="input-sm form-control timepicker" name="hini" id="hini" value="<?php echo $hini; ?>"">
													<span class=" input-group-addon form-control"> &nbsp; <i class="fa fa-clock"></i> &nbsp; </span>
													<input type="text" class="input-sm form-control timepicker" name="hfin" id="hfin" value="<?php echo $hfin; ?>">
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<label>Visualizacion al Programar:</label> <span class="text-danger">*</span>
											<select class="form-control select2" name="tipo" id="tipo" onchange="tipoProgramacion();">
												<option value="">Seleccione</option>
												<option value="W">D&iacute;as de la Semana</option>
												<option value="M">D&iacute;as del Mes</option>
											</select>
										</div>
									</div>
									<hr>
									<div id="containerSemana" style="display: none;">
										<div class="row">
											<div class="col-md-6">
												<label>Visualizaci&oacute;n Semanal:</label> <span class="text-danger">*</span>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 text-center">
												<div data-toggle="buttons-checkbox" class="btn-group">
													<button class="btn btn-white" id="diaL" type="button">Lunes</button>
													<button class="btn btn-white" id="diaM" type="button">Martes</button>
													<button class="btn btn-white" id="diaW" type="button">Miercoles</button>
													<button class="btn btn-white" id="diaJ" type="button">Jueves</button>
													<button class="btn btn-white" id="diaV" type="button">Viernes</button>
													<button class="btn btn-white" id="diaS" type="button">Sabado</button>
													<button class="btn btn-white" id="diaD" type="button">Domingo</button>
												</div>
											</div>
										</div>
									</div>
									<div id="containerMes" style="display: none;">
										<div class="row">
											<div class="col-md-6" id="contenedorMes">
												<label>Visualizaci&oacute;n Mensual:</label> <span class="text-danger">*</span>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 text-center">
												<div data-toggle="buttons-checkbox" class="btn-group">
													<button class="btn btn-white" id="dia1" type="button">d&iacute;a 01</button>
													<button class="btn btn-white" id="dia2" type="button">d&iacute;a 02</button>
													<button class="btn btn-white" id="dia3" type="button">d&iacute;a 03</button>
													<button class="btn btn-white" id="dia4" type="button">d&iacute;a 04</button>
													<button class="btn btn-white" id="dia5" type="button">d&iacute;a 05</button>
													<button class="btn btn-white" id="dia6" type="button">d&iacute;a 06</button>
													<button class="btn btn-white" id="dia7" type="button">d&iacute;a 07</button>
													<button class="btn btn-white" id="dia8" type="button">d&iacute;a 08</button>
													<button class="btn btn-white" id="dia9" type="button">d&iacute;a 09</button>
													<button class="btn btn-white" id="dia10" type="button">d&iacute;a 10</button>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 text-center">
												<div data-toggle="buttons-checkbox" class="btn-group">
													<button class="btn btn-white" id="dia11" type="button">d&iacute;a 11</button>
													<button class="btn btn-white" id="dia12" type="button">d&iacute;a 12</button>
													<button class="btn btn-white" id="dia13" type="button">d&iacute;a 13</button>
													<button class="btn btn-white" id="dia14" type="button">d&iacute;a 14</button>
													<button class="btn btn-white" id="dia15" type="button">d&iacute;a 15</button>
													<button class="btn btn-white" id="dia16" type="button">d&iacute;a 16</button>
													<button class="btn btn-white" id="dia17" type="button">d&iacute;a 17</button>
													<button class="btn btn-white" id="dia18" type="button">d&iacute;a 18</button>
													<button class="btn btn-white" id="dia19" type="button">d&iacute;a 19</button>
													<button class="btn btn-white" id="dia20" type="button">d&iacute;a 20</button>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 text-center">
												<div data-toggle="buttons-checkbox" class="btn-group">
													<button class="btn btn-white" id="dia21" type="button">d&iacute;a 21</button>
													<button class="btn btn-white" id="dia22" type="button">d&iacute;a 22</button>
													<button class="btn btn-white" id="dia23" type="button">d&iacute;a 23</button>
													<button class="btn btn-white" id="dia24" type="button">d&iacute;a 24</button>
													<button class="btn btn-white" id="dia25" type="button">d&iacute;a 25</button>
													<button class="btn btn-white" id="dia26" type="button">d&iacute;a 26</button>
													<button class="btn btn-white" id="dia27" type="button">d&iacute;a 27</button>
													<button class="btn btn-white" id="dia28" type="button">d&iacute;a 28</button>
													<button class="btn btn-white" id="dia29" type="button">d&iacute;a 29</button>
													<button class="btn btn-white" id="dia30" type="button">d&iacute;a 30</button>
													<button class="btn btn-white" id="dia31" type="button">d&iacute;a 31</button>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<label>Observaciones Especiales:</label>
											<textarea class="form-control" name="observacion" id="observacion" rows="3" onkeyup="textoLargo(this);"></textarea>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-md-12 text-center">
											<button type="button" class="btn btn-white" id="btn-limpiar" onclick="Limpiar();"><i class="fas fa-eraser"></i> Limpiar</button>
											<button type="button" class="btn btn-primary" id="btn-grabar" onclick="GrabarProgramacion();"><i class="fas fa-save"></i> Grabar</button>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-lg-12" id="result">
											<?php echo utf8_decode(tabla_programacion("", $indicador, $id)); ?>
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
	<!-- --- -->


	<script type="text/javascript" src="../assets.1.2.8/js/modules/indicador/programacion.js"></script>

	<script>
		$(document).ready(function() {
			$('.dataTables-example').DataTable({
				pageLength: 100,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: []
			});
			$('.timepicker').datetimepicker({
				//          format: 'H:mm',    // use this format if you want the 24hours timepicker
				format: 'H:mm', //use this format if you want the 12hours timpiecker with AM/PM toggle
				icons: {
					time: "fa fa-clock-o",
					date: "fa fa-calendar",
					up: "fa fa-chevron-up",
					down: "fa fa-chevron-down",
					previous: 'fa fa-chevron-left',
					next: 'fa fa-chevron-right',
					today: 'fa fa-screenshot',
					clear: 'fa fa-trash',
					close: 'fa fa-remove'
				}
			});
			$('#range .input-daterange').datepicker({
				keyboardNavigation: false,
				forceParse: false,
				autoclose: true,
				format: "dd/mm/yyyy"
			});
			$('.select2').select2({ width: '100%' });
		});
	</script>

</body>

</html>