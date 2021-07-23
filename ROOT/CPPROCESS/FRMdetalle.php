<?php
include_once('html_fns_proceso.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$hashkey = $_REQUEST["hashkey"];
$ClsFic = new ClsFicha();
$codigo = $ClsFic->decrypt($hashkey, $id);
$codigo = intval($codigo);
$result = $ClsFic->get_ficha($codigo);
//var_dump($result);

foreach ($result as $row) {
	$nombre = utf8_decode($row["fic_nombre"]);
	$tipo = trim($row["fic_tipo"]);
	$pertenece = trim($row["fic_pertenece"]);
	$nombreTipo = utf8_decode($row["tit_nombre"]);
	$analisis = utf8_decode($row["fic_analisis_foda"]);
	$objetivo = utf8_decode($row["fic_objetivo_general"]);
	$desde = cambia_fecha($row["fic_fecha_inicio"]);
	$hasta = cambia_fecha($row["fic_fecha_fin"]);
}
//var_dump($pertenece);
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../", "process"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<h5 class="card-title">
									<i class="fas fa-clipboard-list"></i> Ficha de Procesos No. #<?php echo Agrega_Ceros($codigo); ?>
									<button type="button" class="btn btn-white btn-lg sin-margin pull-right" onclick="window.history.back();"><small><i class="nc-icon nc-minimal-left"></i> Regresar</small></button>
								</h5>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<label>Nombre del Proceso:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" id="nombre" name="nombre" onkeyup="texto(this);" onblur="updateFicha(this,4)" value="<?php echo $nombre; ?>">
										<input type="hidden" id="codigo" name="codigo" value="<?php echo $codigo; ?>">
									</div>
									<?php
									if ($pertenece != NULL) {
										$rs = $ClsFic->get_ficha($pertenece);
										foreach ($rs as $row) {
											$nombre = utf8_decode($row["fic_nombre"]);
											//var_dump($nombre);
											?>
											<div class="col-md-6">
												<label>Proceso al que pertenece:</label>
												<p class="text-justify"><?php echo $nombre; ?></p>
											</div>
									<?php
										}
									}
									?>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Tipo de Proceso:</label> <span class="text-danger">*</span>
										<?php
										if ($pertenece == "0") echo utf8_decode(subtitulos_html('tipo', 'updateFicha(this,2)', 'select2', 2));
										else echo utf8_decode(subtitulos_html('tipo', '', 'select2 disabled', 2));
										?>
										<script>
											document.getElementById("tipo").value = '<?php echo trim($tipo); ?>';
										</script>
									</div>
									<div class="col-md-6">
										<label>Fechas de Vigencia:</label> <span class="text-danger">*</span>
										<div class="form-group" id="range">
											<div class="input-daterange input-group" id="datepicker">
												<input onchange="updateFicha(this,7)" type="text" class="input-sm form-control" name="desde" id="desde" value="<?php echo $desde; ?>" />
												<span class="input-group-addon"> &nbsp; <i class="fa fa-calendar"></i> &nbsp; </span>
												<input onchange="updateFicha(this,8)" type="text" class="input-sm form-control" name="hasta" id="hasta" value="<?php echo $hasta; ?>" />
											</div>
										</div>
									</div>
								</div>
								<br>
							</div>
						</div>
						<div class="card">
							<div class="card-header">
								<h5 class="card-title">An&aacute;lisis FODA del Proceso</h5>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<label>An&aacute;lisis FODA:</label> <span class="text-danger">*</span>
										<textarea class="form-control" id="analsis" name="analsis" onkeyup="textoLargo(this);" onblur="updateFicha(this,3)"><?php echo $analisis; ?></textarea>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-6"><label>Fortalezas:</label> <span class="text-danger">*</span></div>
									<div class="col-md-6"><button type="button" class="btn btn-white sin-margin pull-right" onclick="masFoda(1,'resultFortalezas');"><small><i class="nc-icon nc-simple-add"></i> Agregar</small></button></div>
								</div>
								<div class="row">
									<div class="col-md-12" id="resultFortalezas"><?php echo utf8_decode(tabla_foda('', $codigo, 1)); ?></div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-6"><label>Oportunidades:</label> <span class="text-danger">*</span></div>
									<div class="col-md-6"><button type="button" class="btn btn-white sin-margin pull-right" onclick="masFoda(2,'resultOportunidades');"><small><i class="nc-icon nc-simple-add"></i> Agregar</small></button></div>
								</div>
								<div class="row">
									<div class="col-md-12" id="resultOportunidades"><?php echo utf8_decode(tabla_foda('', $codigo, 2)); ?></div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-6"><label>Debilidades:</label> <span class="text-danger">*</span></div>
									<div class="col-md-6"><button type="button" class="btn btn-white sin-margin pull-right" onclick="masFoda(3,'resultDebilidades');"><small><i class="nc-icon nc-simple-add"></i> Agregar</small></button></div>
								</div>
								<div class="row">
									<div class="col-md-12" id="resultDebilidades"><?php echo utf8_decode(tabla_foda('', $codigo, 3)); ?></div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-6"><label>Amenazas:</label> <span class="text-danger">*</span></div>
									<div class="col-md-6"><button type="button" class="btn btn-white sin-margin pull-right" onclick="masFoda(4,'resultAmenazas');"><small><i class="nc-icon nc-simple-add"></i> Agregar</small></button></div>
								</div>
								<div class="row">
									<div class="col-md-12" id="resultAmenazas"><?php echo utf8_decode(tabla_foda('', $codigo, 4)); ?></div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-12" id="resultFODA">
										<?php echo utf8_decode(tabla_foda_grafica($codigo)); ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3"></div>
									<div class="col-md-6">
										<label>Clave de Sistemas</label><br>
										<?php echo tabla_sistemas(); ?>
									</div>
									<div class="col-md-3"></div>
								</div>
								<br>
							</div>
						</div>
						<div class="card">
							<div class="card-header">
								<h5 class="card-title">Objetivos</h5>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<small>Objetivo General del Proceso:</small> <span class="text-danger">*</span>
										<textarea class="form-control" id="objetivo" name="objetivo" onkeyup="textoLargo(this);" onblur="updateFicha(this,4)"><?php echo $objetivo; ?></textarea>
									</div>
								</div>
								<br>
								<?php
								$ClsSis = new ClsSistema();
								$result = $ClsSis->get_sistema('', '', 1);
								if (is_array($result)) {
									foreach ($result as $row) {
										$sistema = trim($row["sis_codigo"]);
										$sistema_nombre = utf8_decode($row["sis_nombre"]);
										$objetivo_especifico = "";
										$indicador_especifico = "";
										$control_especifico = "";
								?>
										<div class="row">
											<div class="col-md-6"><small>Objetivos de <?php echo $sistema_nombre; ?>:</small> <span class="text-danger">*</span></div>
											<div class="col-md-6"><button type="button" class="btn btn-white sin-margin pull-right" onclick="masObjetivo(<?php echo $sistema; ?>);"><small><i class="nc-icon nc-simple-add"></i> Agregar</small></button></div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div id="objetivos<?php echo $sistema; ?>" role="tablist" aria-multiselectable="true" class="card-collapse">
													<?php echo utf8_decode(objetivos_acordion($codigo, $sistema)); ?>
												</div>
											</div>
										</div>
										<hr>
								<?php
									}
								}
								?>
								<br>
							</div>
						</div>
						<div class="card">
							<div class="card-header">
								<h5 class="card-title">
									Desarrollo del Proceso<br>
									<small>Elementos del Proceso</small>
								</h5>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-6"><label>Fuentes de Entrada:</label> <span class="text-danger">*</span></div>
									<div class="col-md-6"><button type="button" class="btn btn-white sin-margin pull-right" onclick="masElemento(1,'resultFuenteEntrada');"><small><i class="nc-icon nc-simple-add"></i> Agregar</small></button></div>
								</div>
								<div class="row">
									<div class="col-md-12" id="resultFuenteEntrada"><?php echo utf8_decode(tabla_elemento('', $codigo, 1)); ?></div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-6"><label>Entradas:</label> <span class="text-danger">*</span></div>
									<div class="col-md-6"><button type="button" class="btn btn-white sin-margin pull-right" onclick="masElemento(2,'resultEntrada');"><small><i class="nc-icon nc-simple-add"></i> Agregar</small></button></div>
								</div>
								<div class="row">
									<div class="col-md-12" id="resultEntrada"><?php echo utf8_decode(tabla_elemento('', $codigo, 2)); ?></div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-6"><label>Receptores de Salida:</label> <span class="text-danger">*</span></div>
									<div class="col-md-6"><button type="button" class="btn btn-white sin-margin pull-right" onclick="masElemento(3,'resultFuenteSalida');"><small><i class="nc-icon nc-simple-add"></i> Agregar</small></button></div>
								</div>
								<div class="row">
									<div class="col-md-12" id="resultFuenteSalida"><?php echo utf8_decode(tabla_elemento('', $codigo, 3)); ?></div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-6"><label>Salidas:</label> <span class="text-danger">*</span></div>
									<div class="col-md-6"><button type="button" class="btn btn-white sin-margin pull-right" onclick="masElemento(4,'resultSalida');"><small><i class="nc-icon nc-simple-add"></i> Agregar</small></button></div>
								</div>
								<div class="row">
									<div class="col-md-12" id="resultSalida"><?php echo utf8_decode(tabla_elemento('', $codigo, 4)); ?></div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-6"><label>Actividades:</label> <span class="text-danger">*</span></div>
									<div class="col-md-6"><button type="button" class="btn btn-white sin-margin pull-right" onclick="masElemento(5,'resultActividad');"><small><i class="nc-icon nc-simple-add"></i> Agregar</small></button></div>
								</div>
								<div class="row">
									<div class="col-md-12" id="resultActividad"><?php echo utf8_decode(tabla_elemento('', $codigo, 5)); ?></div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-6"><label>Medidas de Verificaci&oacute;n:</label> <span class="text-danger">*</span></div>
									<div class="col-md-6"><button type="button" class="btn btn-white sin-margin pull-right" onclick="masElemento(6,'resultVerificacion');"><small><i class="nc-icon nc-simple-add"></i> Agregar</small></button></div>
								</div>
								<div class="row">
									<div class="col-md-12" id="resultVerificacion"><?php echo utf8_decode(tabla_elemento('', $codigo, 6)); ?></div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-12" id="resultElementos"> </div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-6"><label>Requisitos Legales:</label> <span class="text-danger">*</span></div>
									<div class="col-md-6"><button type="button" class="btn btn-white sin-margin pull-right" onclick="masRquisitoLegal();"><small><i class="nc-icon nc-simple-add"></i> Agregar</small></button></div>
								</div>
								<div class="row">
									<div class="col-md-12" id="resultRequisitoLegal"><?php echo utf8_decode(tabla_legal('', $codigo)); ?></div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-6"><label>Aspectos Ambientales:</label> <span class="text-danger">*</span></div>
									<div class="col-md-6"><button type="button" class="btn btn-white sin-margin pull-right" onclick="masAspectoAmbiental();"><small><i class="nc-icon nc-simple-add"></i> Agregar</small></button></div>
								</div>
								<div class="row">
									<div class="col-md-12" id="resultAspectoAmbiental"><?php echo utf8_decode(tabla_ambiental('', $codigo)); ?></div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-6"><label>Responsabilidad Social:</label> <span class="text-danger">*</span></div>
									<div class="col-md-6"><button type="button" class="btn btn-white sin-margin pull-right" onclick="masResponsibilidadSocial();"><small><i class="nc-icon nc-simple-add"></i> Agregar</small></button></div>
								</div>
								<div class="row">
									<div class="col-md-12" id="resultResponsibilidadSocial"><?php echo utf8_decode(tabla_responsabilidad('', $codigo)); ?></div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-6"><label>Puntos de Norma que aplican al proceso:</label> <span class="text-danger">*</span></div>
									<div class="col-md-6"><button type="button" class="btn btn-white sin-margin pull-right" onclick="masPuntoNorma();"><small><i class="nc-icon nc-simple-add"></i> Agregar</small></button></div>
								</div>
								<div class="row">
									<div class="col-md-12" id="resultPuntoNorma"><?php echo utf8_decode(tabla_punto_norma('', $codigo)); ?></div>
								</div>
								<br>
							</div>
						</div>
						<div class="card">
							<div class="card-header">
								<h5 class="card-title">Riesgos y Oportunidades</h5>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<?php echo utf8_decode(tabla_riesgos_oportunidades($codigo)); ?>
									</div>
								</div>
								<br>
							</div>
						</div>
						<div class="card">
							<div class="card-header">
								<h5 class="card-title">Recursos</h5>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-6"><label>Recursos:</label> <span class="text-danger">*</span></div>
									<div class="col-md-6"><button type="button" class="btn btn-white sin-margin pull-right" onclick="masRecurso();"><small><i class="nc-icon nc-simple-add"></i> Agregar</small></button></div>
								</div>
								<div class="row">
									<div class="col-md-12" id="resultRecurso"><?php echo utf8_decode(tabla_recurso('', $codigo)); ?></div>
								</div>
								<br>
							</div>
						</div>
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-md-12 text-center">
										<button type="button" class="btn btn-lg btn-white" onclick="window.history.back();"><small><i class="nc-icon nc-minimal-left"></i> Regresar</small></button>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 text-center">
										<a class="btn btn-lg btn-white" href="CPREPORTES/REPficha.php?hashkey=<?php echo $hashkey; ?>" target="_blank"><i class="fa fa-print"></i> Revisar Ficha</a>
										<a href=""></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php echo footer(); ?>
		</div>
	</div>
	<?php echo modal("../"); ?>
	<?php echo scripts("../"); ?>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/process/ficha.js"></script>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/process/foda.js"></script>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/process/control.js"></script>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/process/indicador.js"></script>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/process/objetivos.js"></script>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/process/elemento.js"></script>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/process/requisito_legal.js"></script>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/process/aspecto_ambiental.js"></script>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/process/responsabilidad_social.js"></script>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/process/punto_norma.js"></script>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/process/recurso.js"></script>

	<script>
		$(document).ready(function() {
			mostrarDiagrama();
			$('.select2').select2({ width: '100%' });
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

///////