<?php
include_once('html_fns_planning.php');
validate_login("../");
$usuario = $_SESSION["codigo"];

$categoriasIn = $_SESSION["categorias_in"];
//$_POST
$ClsObj = new ClsObjetivo();
$hashkey = $_REQUEST["hashkey"];
$revision = $ClsObj->decrypt($hashkey, $usuario);
//--
$result = $ClsObj->get_revision($revision);
if (is_array($result)) {
	foreach ($result as $row) {
		// Obtener datos de la ficha
		$ficha = trim($row["fic_codigo"]);
		$ClsFic = new ClsFicha();
		$rs = $ClsFic->get_ficha($ficha);
		$nivel = trim($rs[0]["fic_nivel"]);
		$rs = $ClsFic->get_ficha($ficha, "", "", "", "", "", "", $nivel);
		if (is_array($rs)) {
			$tipo = utf8_decode($rs[0]["tit_nombre"] . " " . $rs[0]["sub_nombre"]);
			$proceso = utf8_decode($rs[0]["fic_nombre"]);
		}
		// Obtener datos del Objetivo
		$objetivo = trim($row["obj_codigo"]);
		$asignado_cod = trim($row["rev_usuario_asignado"]);
		$asignado = utf8_decode($row["usuario_nombre"]);
		$observaciones = utf8_decode($row["rev_observacion"]);
		$rs = $ClsObj->get_objetivo($objetivo);
		if (is_array($rs)) {
			$objetivo_nombre = utf8_decode($rs[0]["obj_descripcion"]);
			$sistema = utf8_decode($rs[0]["sis_nombre"]);
		}
	}
}

?>
	<!DOCTYPE html>
	<html>

	<head>
		<?php echo head("../"); ?>
		<style>
			.img-upload {
				width: 30%;
				margin: 1px;
				cursor: pointer;
			}

			.img-demo {
				width: 50%;
			}
		</style>
	</head>

	<body class="">
		<div class="wrapper ">
			<?php echo sidebar("../","planning"); ?>
			<div class="main-panel">
				<?php echo navbar("../"); ?> <div class="content">
					<div class="row">
						<div class="col-md-6">
							<div class="card demo-icons">
								<div class="card-header">
									<h5 class="card-title">
										<i class="fab fa-creative-commons-sampling "></i> Proceso
										<a class="btn btn-white btn-lg sin-margin pull-right" href="FRMaprobacion.php"><small><i class="fa fa-chevron-left"></i> Atr&aacute;s</small></a>
									</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-xs-6 col-md-6 text-left"> </div>
									</div>
									<div class="row">
										<div class="col-lg-12" id="result">
											<div class="row">
												<div class="col-md-12">
													<label>Tipo:</label>
													<input type="text" class="form-control" value="<?php echo $tipo; ?>" disabled />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Nombre:</label>
													<input type="text" class="form-control" value="<?php echo $proceso; ?>" disabled />
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
										<i class="nc-icon nc-bullet-list-67"></i> Informaci&oacute;n
									</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-lg-12" id="result">
											<div class="row">
												<div class="col-md-12">
													<label>Sistema:</label>
													<input type="text" class="form-control" value="<?php echo $sistema; ?>" disabled />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Usuario:</label>
													<input type="text" class="form-control" value="<?php echo $asignado; ?>" disabled />
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
										<i class="nc-icon nc-bullet-list-67"></i> Descripci&oacute;n
									</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-lg-12" id="result">
											<div class="row">
												<div class="col-md-12">
													<label>Objetivo:</label>
													<textarea type="text" class="form-control" rows="5" disabled /><?php echo $objetivo_nombre; ?></textarea>
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
									<h5 class="card-title"><i class="nc-icon nc-paper"></i> Acciones del Objetivo</h5>
								</div>
								<div class="card-body all-icons">
									<?php
									echo utf8_decode(tabla_aprobar_accion($asignado_cod, $objetivo))
									?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card demo-icons">
								<div class="card-header">
									<h5 class="card-title"><i class="fas fa-pen-nib"></i> Observaciones de la Revisi&oacute;n</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-md-10 ml-auto mr-auto">
											<label>Observaciones:</label>
											<textarea class="form-control" name="observaciones" id="observaciones" onkeyup="textoLargo(this);" onblur="saveObservacion()" rows="5"><?php echo $observaciones ?></textarea>
										</div>
									</div>
									<br>
									<div class="row">
										<input type="hidden" id="codigo" name="codigo" value="<?php echo $revision; ?>" />
									</div>
									<div class="row">
										<div class="col-md-6 ml-auto mr-auto text-center">
											<a type="button" class="btn btn-default btn-lg" href="FRMaprobacion.php"><span class="fa fa-chevron-left"></span> Regresar</a>
											<button type="button" class="btn btn-warning btn-lg" id="btncerrar" onclick="rechazarObjetivo();"><span class="fa fa-edit"></span> Correcci&oacute;n</button>
											<button type="button" class="btn btn-success btn-lg" id="btncerrar" onclick="aprobarObjetivo();"><span class="fa fa-check"></span> Aprobar</button>
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
		
		<script type="text/javascript" src="../assets.1.2.8/js/modules/planning/revision.js"></script>
		<script>
			$(document).ready(function() {
				$('.dataTables-example').DataTable({
					pageLength: 100,
					responsive: true,
					dom: '<"html5buttons"B>lTfgitp',
					buttons: []
				});
				$('.input-group.date').datepicker({
					format: 'dd/mm/yyyy',
					keyboardNavigation: false,
					forceParse: false,
					calendarWeeks: true,
					autoclose: true
				});
				$('.select2').select2({ width: '100%' });
			});
		</script>

	</body>

	</html>
<?php

function tabla_aprobar_accion($asignado, $objetivo)
{
	$salida = '<div class="scroll-container">';
	$salida .= '<table class="table table-striped table-bordered" >';
	$salida .= '<thead>';
	$salida .= '<tr>';
	$salida .= '<th class = "text-center" width = "10px">No.</th>';
	$salida .= '<th class = "text-left" width = "250px">Acci&oacute;n</th>';
	$salida .= '<th class = "text-left" width = "30px">Presupuesto</th>';
	$salida .= '<th class = "text-left" width = "40px">Tipo</th>';
	$salida .= '<th class = "text-center" width = "40px">Fechas</th>';
	$salida .= '<th class = "text-center" width = "20px">Programaci&oacute;n</th>';
	$salida .= '<th class = "text-center" width = "100px">Comentario</th>';
	$salida .= '</tr>';
	$salida .= '</thead>';
	$salida .= '<tbody>';

	// Acciones
	$ClsAcc = new ClsAccion();
	$result = $ClsAcc->get_accion("", $objetivo, "", $asignado, "", "", "", "", 1);
	if (is_array($result)) {
		$i = 1;
		foreach ($result as $row) {
			$codigo = $row["acc_codigo"];
			$salida .= '<tr>';
			// No.
			$salida .= '<td class = "text-center">' . $i . '.</td>';
			// Nombre
			$nombre = trim($row["acc_nombre"]);
			$salida .= '<td class = "text-left">' . $nombre . '</td>';
			// Presupuesto
			$presupuesto = trim($row["acc_presupuesto"]);
			$salida .= '<td class = "text-left">' . $presupuesto . '</td>';
			// Tipo
			$tipo = trim($row["acc_tipo"]);
			switch ($tipo) {
				case "U":
					$tipo = "Unica";
					break;
				case "W":
					$tipo = "Semanal";
					break;
				case "M":
					$tipo = "Mensual";
					break;
			}
			$salida .= '<td class = "text-left">' . $tipo . '</td>';
			// Fechas
			$fini = cambia_fecha(trim($row["acc_fecha_inicio"]));
			$ffin = cambia_fecha(trim($row["acc_fecha_fin"]));
			$salida .= '<td class = "text-center">' . $fini . ' - ' . $ffin . '</td>';
			// Periodicidad
			$codigo = $row["acc_codigo"];
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$tipo = trim($row["acc_tipo"]);
			$salida .= '<button type="button" class="btn btn-info btn-xs" onclick = "verDetalle(' . $codigo . ',\'' . $tipo . '\');" title = "Ver Detalles" ><i class="fa fa-search"></i></button>';
			$salida .= '</div>';
			$salida .= '</td>';
			// Comentario
			$comentario = trim($row["acc_comentario"]);
			$salida .= '<td class = "text-left"><textarea type="text" class="form-control textarea-autosize" onblur = "saveComentario(' . $codigo . ');"  id ="comentario' . $codigo . '" name ="comentario' . $codigo . '">' . $comentario . '</textarea></td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
	}
	$salida .= '</tbody>';
	$salida .= '</table>';
	$salida .= '</div>';

	return $salida;
}?>