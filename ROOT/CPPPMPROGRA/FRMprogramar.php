<?php
include_once('html_fns_programacion.php');
validate_login("../");
$id = $_SESSION["codigo"];
$hashkey = $_REQUEST['hashkey'];
$ClsAct = new ClsActivo();
///activo que viene de gestion de activos
$act  = $ClsAct->decrypt($hashkey, $id);

//$_POST
if($act == ""){
	$activo = $_REQUEST['activo'];	
}else{
	$activo = $act;
}
/*
$usuario = $_REQUEST["usuario"];
$presupuesto = $_REQUEST["presupuesto"];
$moneda = $_REQUEST["moneda"];

$categoria = $_REQUEST["categoria"];
$tipo = $_REQUEST["tipo"];
$cuestionario = $_REQUEST["cuestionario"];
$observaciones = $_REQUEST["observacion"];
*/
//--
$moneda = ($moneda == "") ? 1 : $moneda;
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
/*
if ($activo != "") {
	$ClsAct = new ClsActivo();
	$result = $ClsAct->get_activo($codigo);
	if (is_array($result)) {
		foreach ($result as $row) {
			$periodicidad = trim($row["act_periodicidad"]);
			switch ($periodicidad) {
				case "D":
					$periodicidad = "Diario";
					break;
				case "W":
					$periodicidad = "Semanal";
					break;
				case "M":
					$periodicidad = "Mensual";
					break;
				case "Y":
					$periodicidad = "Anual";
					break;
				case "V":
					$periodicidad = "Variado";
					break;
			}
		}
	}
}*/
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../", "ppm"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-users-cog"></i> Programaci&oacute;n de Ordenes de Trabajo</h5>
							</div>
							<div class="card-body all-icons">
								<form name="f1" id="f1" action="" method="get">
									<div class="row">
										<div class="col-xs-6 col-md-6 text-left">
											<a type="button" class="btn btn-white" href="FRMprogramacion.php"><i class="fa fa-chevron-left"></i> Atr&aacute;s</a>
										</div>
										<div class="col-xs-6 col-md-6 text-right"><label class=" text-danger">* Campos Obligatorios</label> </div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<label>Activo:</label> <span class="text-danger">*</span>
												<?php echo utf8_decode(activo_sedes_html("activo", "getPeriodicidad(this.value);", "select2"));?>
											<script>
												document.getElementById("activo").value = "<?php echo $activo; ?>";
											</script>
											<input type="hidden" name="sector" id="sector" value="" />
											<input type="hidden" name="hashkey" id="hashkey" value="<?=$hashkey?>" />
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Usuario a Asignar:</label> <span class="text-danger">*</span>
											<?php echo utf8_decode(usuarios_html("usuario", "", "select2")); ?>
											<script>
												document.getElementById("usuario").value = "";
											</script>
										</div>
										<div class="col-md-6">
											<label>Categor&iacute;a:</label> <span class="text-danger">*</span>

											<?php echo utf8_decode(categorias_ppm_html("categoria", "", "select2")); ?>
											<script>
												document.getElementById("categoria").value = "";
											</script>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Fechas:</label> <span class="text-danger">*</span>
											<div class="form-group" id="range">
												<div class="input-daterange input-group" id="datepicker">
													<input type="text" class="input-sm form-control" name="desde" id="desde" value="<?php echo $desde; ?>" />
													<span class="input-group-addon"> &nbsp; <i class="fa fa-calendar"></i> &nbsp; </span>
													<input type="text" class="input-sm form-control" name="hasta" id="hasta" value="<?php echo $hasta; ?>" />
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<label>Presupuesto:</label> <small class="text-muted">(moneda 0.00)</small> <span class="text-danger">*</span>
											<input type="text" class="form-control" name="presupuesto" id="presupuesto" onkeyup="decimales(this)" value="" />
										</div>
										<div class="col-md-3">
											<label>Moneda:</label> <span class="text-danger">*</span>
											<?php echo utf8_decode(moneda_simbolo_html("moneda", "", "select2")); ?>
											<script>
												document.getElementById("moneda").value = "<?=$moneda?>";
											</script>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Cuestionario:</label> <span class="text-danger">*</span>
											<?php echo utf8_decode(cuestionario_html("cuestionario", "", "select2")); ?>
											<script>
												document.getElementById("cuestionario").value = "";
											</script>
										</div>
										<div class="col-md-3">
											<label>Periodicidad del Activo:</label> <span class="text-danger">*</span>
											<input name="periodicidad" id="periodicidad" type="text" class="form-control" value="" readonly />
										</div>
										<div class="col-md-3">
											<label>Visualizacion al Programar:</label> <span class="text-danger">*</span>
											<select class="form-control select2" name="tipo" id="tipo" onchange="tipoProgramacion(this.value);">
												<option value="S">Seleccione</option>
												<option value="W" selected>Semanal</option>
												<option value="M">Mensual</option>
											</select>
											<script>
												document.getElementById("tipo").value = "";
											</script>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<label>Observaciones:</label>
											<textarea class="form-control" name="observacion" id="observacion" rows="3" onkeyup="textoLargo(this);"></textarea>
										</div>
									</div>
									<hr>
									<div id="containerSemana">
										<div class="row">
											<div class="col-md-6">
												<label>Visualizaci&oacute;n Semanal:</label> <span class="text-danger">*</span>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12 text-center">
												<div data-toggle="buttons-checkbox" class="btn-group">
													<button class="btn btn-white" id="diaW1" type="button">Lunes</button>
													<button class="btn btn-white" id="diaW2" type="button">Martes</button>
													<button class="btn btn-white" id="diaW3" type="button">Miercoles</button>
													<button class="btn btn-white" id="diaW4" type="button">Jueves</button>
													<button class="btn btn-white" id="diaW5" type="button">Viernes</button>
													<button class="btn btn-white" id="diaW6" type="button">Sabado</button>
													<button class="btn btn-white" id="diaW7" type="button">Domingo</button>
												</div>
											</div>
										</div>
									</div>
									<div id="containerMes">
										<div class="row">
											<div class="col-md-6">
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

									<br>
									<div class="row">
										<div class="col-md-12 text-center">
											<a type="button" class="btn btn-white" id="btn-limpiar" href="FRMprogramar.php"><i class="fas fa-eraser"></i> Limpiar</a>
											<button type="button" class="btn btn-primary" id="btn-grabar" onclick="Grabar();"><i class="fas fa-save"></i> Grabar</button>
										</div>
									</div>
								</form>
								<hr>
								<div class="row">
									<div class="col-md-12 text-center">
										<?php
										$mes = date("m");
										$anio = date("Y");
										$desde = "01/$mes/$anio";
										$hasta = "31/$mes/$anio";
										echo tabla_programacion($activo, '', '', '', $desde, $hasta)
										?>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/ppm/programacion.js"></script>


	<script>
		$(document).ready(function() {
			//ejecutamos el onchange del combo activo si ya viene un //////////
			//valor por POST
			$select = $('#activo');
 			$select.on('change', getPeriodicidad());
 			$select.trigger('change');

			tipoProgramacion('S');
			$('.dataTables-example').DataTable({
				pageLength: 100,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: []
			});
			$('#range .input-daterange').datepicker({
				keyboardNavigation: false,
				forceParse: false,
				autoclose: true,
				format: "dd/mm/yyyy"
			});
			$('.select2').select2({
				width: '100%'
			});
		});
	</script>

</body>

</html>