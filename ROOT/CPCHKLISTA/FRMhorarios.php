<?php
include_once('html_fns_lista.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$lista = $_REQUEST["lista"];


$ClsLis = new ClsLista();
$result = $ClsLis->get_lista($lista);
//var_dump($result);
//die();
if (is_array($result)) {
	foreach ($result as $row) {
		$categoria = utf8_decode($row["cat_nombre"]);
		$nombre = utf8_decode($row["list_nombre"]);
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
		<?php echo sidebar("../", "checklist"); ?>
		<div class="main-panel">

			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-users-cog"></i> Programaci&oacute;n de Horarios para Revisi&oacute;n</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-xs-6 col-md-6 text-left"><button type="button" class="btn btn-white" onclick="atras();"><i class="fa fa-chevron-left"></i> Atr&aacute;s</button> </div>
									<div class="col-xs-6 col-md-6 text-right"><label class=" text-danger">* Campos Obligatorios</label> </div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Lista:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" value="<?php echo $nombre; ?>" readonly />
									</div>
									<div class="col-md-6">
										<label>Categor&iacute;a:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" value="<?php echo $categoria; ?>" readonly />
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-md-6">
										<label>Area:</label> <span class="text-danger">*</span>
										<?php echo utf8_decode(areas_sede_html("area", "setArea(this.value)", "select2")); ?>
										<input type="hidden" name="sede" id="sede" value="" />
										<input type="hidden" name="sector" id="sector" value="" />
										<script>
											document.getElementById("area").value = "<?php echo $area; ?>"
										</script>
									</div>
									<div class="col-md-6">
										<label>Sector:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="secNombre" id="secNombre" readonly />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Nivel:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="nivel" id="nivel" readonly />
									</div>
									<div class="col-md-6">
										<label>Tipo de Programaci&oacute;n:</label> <span class="text-danger">*</span>
										<select class="form-control select2" name="tipo" id="tipo" onchange="tipoProgramacion(this.value)">
											<option value="S" selected>Semanal (Lunes a Domingo)</option>
											<option value="U">Unica</option>
											<option value="M">Mensual (d&iacute;a del mes)</option>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>entre:</label> <span class="text-danger">*</span>
										<div class="form-group">
											<input type="text" class="form-control timepicker" name="hini" id="hini" value="08:00">
										</div>
										<input type="hidden" name="codigo" id="codigo" />
										<input type="hidden" name="lista" id="lista" value="<?php echo $lista; ?>" />
									</div>
									<div class="col-md-6">
										<label>y (rangos):</label> <span class="text-danger">*</span>
										<div class="form-group">
											<input type="text" class="form-control timepicker" name="hfin" id="hfin" value="17:00">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-10 col-md-offset-1">
										<label>D&iacute;as a programar:</label> <span class="text-danger">*</span> <br>
										<div id="containerUnico">
											<div class="row">
												<div class="col-md-6">
													<div class="input-group">
														<a class="input-group-addon"><i class="fa fa-calendar"></i></a>
														<input type="text" class="form-control" name="fecha" id="fecha" value="<?=date('d/m/Y')?>" />
													</div>
												</div>
											</div>
										</div>
										<div id="containerSemana">
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
										<div id="containerMes">
											<div class="row">
												<div class="col-md-12 text-center">
													<div class="btn-group btn-group-toggle" id="quincena1" data-toggle="buttons">
														<span class="btn btn-white" id="dia1"><input name="dia" type="radio" autocomplete="off" /> 01</span>
														<span class="btn btn-white" id="dia2"><input name="dia" type="radio" autocomplete="off" /> 02</span>
														<span class="btn btn-white" id="dia3"><input name="dia" type="radio" autocomplete="off" /> 03</span>
														<span class="btn btn-white" id="dia4"><input name="dia" type="radio" autocomplete="off" /> 04</span>
														<span class="btn btn-white" id="dia5"><input name="dia" type="radio" autocomplete="off" /> 05</span>
														<span class="btn btn-white" id="dia6"><input name="dia" type="radio" autocomplete="off" /> 06</span>
														<span class="btn btn-white" id="dia7"><input name="dia" type="radio" autocomplete="off" /> 07</span>
														<span class="btn btn-white" id="dia8"><input name="dia" type="radio" autocomplete="off" /> 08</span>
														<span class="btn btn-white" id="dia9"><input name="dia" type="radio" autocomplete="off" /> 09</span>
														<span class="btn btn-white" id="dia10"><input name="dia" type="radio" autocomplete="off" /> 10</span>
														<span class="btn btn-white" id="dia11"><input name="dia" type="radio" autocomplete="off" /> 11</span>
														<span class="btn btn-white" id="dia12"><input name="dia" type="radio" autocomplete="off" /> 12</span>
														<span class="btn btn-white" id="dia13"><input name="dia" type="radio" autocomplete="off" /> 13</span>
														<span class="btn btn-white" id="dia14"><input name="dia" type="radio" autocomplete="off" /> 14</span>
														<span class="btn btn-white" id="dia15"><input name="dia" type="radio" autocomplete="off" /> 15</span>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12 text-center">
													<div class="btn-group btn-group-toggle" id="quincena2" data-toggle="buttons">
														<span class="btn btn-white" id="dia16"><input name="dia" type="radio" autocomplete="off" /> 16</span>
														<span class="btn btn-white" id="dia17"><input name="dia" type="radio" autocomplete="off" /> 17</span>
														<span class="btn btn-white" id="dia18"><input name="dia" type="radio" autocomplete="off" /> 18</span>
														<span class="btn btn-white" id="dia19"><input name="dia" type="radio" autocomplete="off" /> 19</span>
														<span class="btn btn-white" id="dia20"><input name="dia" type="radio" autocomplete="off" /> 20</span>
														<span class="btn btn-white" id="dia21"><input name="dia" type="radio" autocomplete="off" /> 21</span>
														<span class="btn btn-white" id="dia22"><input name="dia" type="radio" autocomplete="off" /> 22</span>
														<span class="btn btn-white" id="dia23"><input name="dia" type="radio" autocomplete="off" /> 23</span>
														<span class="btn btn-white" id="dia24"><input name="dia" type="radio" autocomplete="off" /> 24</span>
														<span class="btn btn-white" id="dia25"><input name="dia" type="radio" autocomplete="off" /> 25</span>
														<span class="btn btn-white" id="dia26"><input name="dia" type="radio" autocomplete="off" /> 26</span>
														<span class="btn btn-white" id="dia27"><input name="dia" type="radio" autocomplete="off" /> 27</span>
														<span class="btn btn-white" id="dia28"><input name="dia" type="radio" autocomplete="off" /> 28</span>
														<span class="btn btn-white" id="dia29"><input name="dia" type="radio" autocomplete="off" /> 29</span>
														<span class="btn btn-white" id="dia30"><input name="dia" type="radio" autocomplete="off" /> 30</span>
														<span class="btn btn-white" id="dia31"><input name="dia" type="radio" autocomplete="off" /> 31</span>
													</div>
												</div>
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
										<button type="button" class="btn btn-primary hidden" id="btn-modificar" onclick="ModificarProgramacion();"><i class="fas fa-save"></i> Grabar</button>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-lg-12" id="result">
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

	<script type="text/javascript" src="../assets.1.2.8/js/modules/checklist/lista.js"></script>

	<script>
		$(document).ready(function() {
			$("#fecha").datepicker({
				keyboardNavigation: false,
				forceParse: false,
				autoclose: true,
				format: "dd/mm/yyyy"
			});
			printTableProgramacion('', document.getElementById('lista').value);
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
			$('.select2').select2({
				width: '100%'
			});
			tipoProgramacion('S'); ////////// Limpia quincenas diferentes ////////////////////
			quincena1.addEventListener('click', function() {
				//alert('quincena 1');
				for (var i = 16; i <= 31; i++) {
					document.getElementById('dia' + i).className = 'btn btn-white';
				}
			});
			quincena2.addEventListener('click', function() {
				//alert('quincena 2');
				for (var i = 1; i <= 15; i++) {
					document.getElementById('dia' + i).className = 'btn btn-white';
				}
			});
		});
	</script>

</body>

</html>