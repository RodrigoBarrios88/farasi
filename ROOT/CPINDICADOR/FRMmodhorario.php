<?php
include_once('html_fns_indicador.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST

//-- Indicador
$indicador = $_REQUEST["indicador"];
$ClsInd = new ClsIndicador();
$result = $ClsInd->get_indicador($indicador);
if (is_array($result)) {
	foreach ($result as $row) {
		$categoria = utf8_decode($row["cat_nombre"]);
		$clasificacion = utf8_decode($row["cla_nombre"]);
		$nombre = utf8_decode($row["ind_nombre"]);
		$departamento = utf8_decode($row["dep_nombre"]);
	}
}

//-- Programacion
$hashkey = $_REQUEST["hashkey"];
$usuario = $_SESSION["codigo"];
$codigo = $ClsInd->decrypt($hashkey, $usuario);
$result = $ClsInd->get_programacion($codigo, $indicador);
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$hini = trim($row["pro_hini"]);
		$hfin = trim($row["pro_hfin"]);
		$observaciones = utf8_decode($row["pro_observaciones"]);
		$fecha = $row["pro_fecha"];
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
						<div class="col-md-12">
							<div class="card demo-icons">
								<div class="card-header">
									<h5 class="card-title"><i class="fa fa-users-cog"></i> Modificar Programaci&oacute;n de Horarios para Indicadores</h5>
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
										<input type="text" id="codigo" class="form-control" value="<?php echo $codigo; ?>" hidden />
										<div class="row">
											<div class="col-md-6">
												<label>Indicador:</label> <span class="text-danger">*</span>
												<input type="text" class="form-control" value="<?php echo $nombre; ?>" readonly />
												<input type="text" name="indicador" id="indicador" class="form-control" value="<?php echo $indicador; ?>" hidden />
											</div>
											<div class="col-md-6">
												<label>Sistema:</label> <span class="text-danger">*</span>
												<input type="text" class="form-control" value="<?php echo $categoria; ?>" readonly />
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<label>Clasificacion:</label> <span class="text-danger">*</span>
												<input type="text" class="form-control" value="<?php echo $clasificacion; ?>" readonly />
											</div>
											<div class="col-md-6">
												<label>Proceso:</label> <span class="text-danger">*</span>
												<input type="text" class="form-control" value="<?php echo $departamento; ?>" readonly />
											</div>
										</div>
										<hr>
										<div class="row">
											<div class="col-md-6">
												<label>Fecha:</label> <span class="text-danger">*</span>
												<div class="form-group" id="range">
													<div class="input-daterange input-group" id="datepicker">
														<input class="text-left input-sm form-control" name="fecha" id="fecha" value="<?php echo $fecha; ?>" />
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<label>Rango de Horas:</label> <span class="text-danger">*</span>
												<div class="form-group" id="range">
													<div class="input-group">
														<input class="input-sm form-control timepicker" name="hini" id="hini" value="<?php echo $hini; ?>"">
													<span class=" input-group-addon"> &nbsp; <i class="fa fa-clock"></i> &nbsp; </span>
														<input class="input-sm form-control timepicker" name="hfin" id="hfin" value="<?php echo $hfin; ?>">
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Observaciones Especiales:</label>
												<textarea class="form-control" name="observacion" id="observacion" rows="3" onkeyup="textoLargo(this);"><?php echo $observaciones; ?></textarea>
											</div>
										</div>
										<br>
										<div class="row">
											<div class="col-md-12 text-center">
												<button type="button" class="btn btn-white" id="btn-limpiar" onclick="Limpiar();"><i class="fas fa-eraser"></i> Limpiar</button>
												<button type="button" class="btn btn-primary" id="btn-modificar" onclick="ModificarProgramacion();"><i class="fas fa-save"></i> Grabar</button>
											</div>
										</div>
										<hr>
										<div class="row">
											<div class="col-lg-12" id="result"> </div>
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
			});
		</script>

	</body>

	</html>
