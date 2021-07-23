<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$usuario = $_SESSION["codigo"];

$categoriasIn = $_SESSION["categorias_in"];
//$_POST
$ClsEnc = new ClsEncuesta();
$ClsRes = new ClsEncuestaResolucion();
$hashkey = $_REQUEST["hashkey"];
$cuestionario = $ClsEnc->decrypt($hashkey, $usuario);
//--ff
$result = $ClsEnc->get_cuestionario($cuestionario);
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$codigo = trim($row["cue_codigo"]);
		$categoria = utf8_decode($row["cat_nombre"]);
		$titulo = utf8_decode($row["cue_titulo"]);
		$titulo = utf8_decode($row["cue_titulo"]);
		$titulo = utf8_decode($row["cue_titulo"]);
		$descripcion = utf8_decode($row["cue_descripcion"]);
		$descripcion = nl2br($descripcion);
		$objetivo = utf8_decode($row["cue_objetivo"]);
		$objetivo = nl2br($objetivo);
		//--
		$situacion = trim($row["eje_situacion"]);
	}
} //--
$desde = $_REQUEST["desde"];
$desde = ($desde == "") ? date("01/01/Y") : $desde; //valida que si no se selecciona fecha, coloque la del dia
$hasta = $_REQUEST["hasta"];
$hasta = ($hasta == "") ? date("31/12/Y") : $hasta; //valida que si no se selecciona fecha, coloque la del dia

?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>

</head>

<body class="">
<div class="wrapper ">
			<div class="sidebar" data-color="brown" data-active-color="danger">
				<!-- Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow" -->
				<div class="logo">
					<a href="../menu.php" class="simple-text logo-mini">
						<div class="logo-image-small">
							<img src="../../CONFIG/img/logo2.png" />
						</div>
					</a>
					<a href="../menu.php" class="simple-text logo-normal">
						BPManagement
					</a>
				</div>
				<div class="sidebar-wrapper">
					<?php echo menu_user('../'); ?>
					<ul class="nav">
						<li>
							<a href="../menu_encuestas.php">
								<i class="nc-icon nc-layout-11"></i>
								<p>Men&uacute;</p>
							</a>
						</li>
						<?php echo menu_administracion('../'); ?>
						<?php echo menu_gestion_tecnica('../'); ?>
						<?php echo menu_encuestas('../', false); ?>
						<hr>
						<li>
							<a href="../logout.php">
								<i class="fa fa-power-off"></i>
								<p>Salir</p>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="main-panel">
				<!-- Navbar -->
				<nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
					<div class="container-fluid">
						<div class="navbar-wrapper">
							<div class="navbar-minimize">
								<button id="minimizeSidebar" class="btn btn-icon btn-round">
									<i class="fas fa-bars text-center visible-on-sidebar-mini"></i>
									<i class="fas fa-bars text-center visible-on-sidebar-regular"></i>
								</button>
							</div>
							<div class="navbar-toggle">
								<button type="button" class="navbar-toggler">
									<span class="navbar-toggler-bar bar1"></span>
									<span class="navbar-toggler-bar bar2"></span>
									<span class="navbar-toggler-bar bar3"></span>
								</button>
							</div>
							<a class="navbar-brand" href="javascript:void(0);"><?php echo menu_aplicaciones('../'); ?></a>
						</div>
						<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
							<span class="navbar-toggler-bar navbar-kebab"></span>
							<span class="navbar-toggler-bar navbar-kebab"></span>
							<span class="navbar-toggler-bar navbar-kebab"></span>
						</button>
						<div class="collapse navbar-collapse justify-content-end" id="navigation">
							<ul class="navbar-nav">
								<?php echo menu_navigation_top('../'); ?>
							</ul>
						</div>
					</div>
				</nav>
				<!-- End Navbar -->

				<div class="content">

					<div class="row">
						<div class="col-md-12">
							<div class="card demo-icons">
								<div class="card-header">
									<h5 class="card-title">
										<i class="fas fa-chart-bar"></i> Estad&iacute;sticas de Respuesta
										<button type="button" button class="btn btn-white btn-lg sin-margin pull-right" onclick="window.history.back();"><small><i class="nc-icon nc-minimal-left"></i> Regresar</small></button>
									</h5>
								</div>
								<div class="card-body all-icons">
									<form name="f1" id="f1" method="get">
										<div class="row">
											<div class="col-md-6">
												<label>Fechas:</label> <span class="text-success">*</span>
												<div class="form-group" id="range">
													<div class="input-daterange input-group" id="datepicker">
														<input type="text" class="input-sm form-control" name="desde" id="desde" value="<?php echo $desde; ?>" />
														<span class="input-group-addon"> &nbsp; <i class="fa fa-calendar"></i> &nbsp; </span>
														<input type="text" class="input-sm form-control" name="hasta" id="hasta" value="<?php echo $hasta; ?>" />
													</div>
												</div>
											</div>
											<div class="col-md-6 text-center">
												<br>
												<a type="button" class="btn btn-white" href="FRMestadisticas.php?hashkey=<?php echo $hashkey; ?>"><i class="fa fa-eraser"></i> Limpiar</a>
												<button type="button" class="btn btn-primary" onclick="Submit();"><i class="fa fa-search"></i> Buscar</button>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Categor&iacute;a:</label><br>
												<input type="text" class="form-control" value="<?php echo $categoria; ?>" readonly />
												<input type="hidden" id="codigo" name="codigo" value="<?php echo $codigo; ?>" />
												<input type="hidden" id="hashkey" name="hashkey" value="<?php echo $hashkey; ?>" />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>T&iacute;tulo:</label><br>
												<input type="text" class="form-control" value="<?php echo $titulo; ?>" readonly />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Descrip&oacute;n del Cuestionario:</label><br>
												<p class="text-justify"><?php echo $descripcion; ?></p>
											</div>
										</div>
										<br>
										<div class="row">
											<div class="col-md-12">
												<label>Objetivo de la Encuesta:</label><br>
												<p class="text-justify"><?php echo $objetivo; ?></p>
											</div>
										</div>
									</form>
									<br>
								</div>
							</div>
						</div>
					</div>

					<?php
					$result_seccion = $ClsEnc->get_secciones('', $codigo, 1);
					if (is_array($result_seccion)) {
						$x = 1;	//Contador de gráficas
						$salida_js = "";
						foreach ($result_seccion as $row_seccion) {
							$seccion_codigo = $row_seccion["sec_codigo"];
							$titulo = trim($row_seccion["sec_numero"]) . ". " . utf8_decode($row_seccion["sec_titulo"]);
							$proposito = utf8_decode($row_seccion["sec_proposito"]);
							$proposito = nl2br($proposito);
					?>
							<div class="row">
								<div class="col-md-12">
									<div class="card demo-icons">
										<div class="card-header">
											<h5 class="card-title"><?php echo $titulo; ?></h5>
										</div>
										<div class="card-body all-icons">
											<h6 class="h6 text-center"><i class="fas fa-list-ol"></i>  Encuestas partes interesadas proveedores</h6>									
											<div id="container-graphics">
												<!---TABLA PARA IMPRIMIR GRAFICA CON PROMEDIOS RESPUESTAS TIPO 1--->
												<table>
														<tr>
														<?php
															$result = $ClsRes->get_estadistica_respuestas($codigo, $seccion_codigo,$desde,$hasta);
															if (is_array($result)) {
																$i = 1;
																foreach ($result as $row) {																	
																	$pregunta_tipo = $row["pre_tipo"];
																	$r1 = 0;
																	$r2 = 0;
																	$r3 = 0;
																	$r4 = 0;
																	$r5 = 0;
																	$r6 = 0;
																	$r7 = 0;
																	$r8 = 0;
																	$r9 = 0;
																	$r10 = 0;
																	//--
																	$r1 = intval($row["respuesta_1"]);
																	$r2 = intval($row["respuesta_2"]);
																	$r3 = intval($row["respuesta_3"]);
																	$r4 = intval($row["respuesta_4"]);
																	$r5 = intval($row["respuesta_5"]);
																	$r6 = intval($row["respuesta_6"]);
																	$r7 = intval($row["respuesta_7"]);
																	$r8 = intval($row["respuesta_8"]);
																	$r9 = intval($row["respuesta_9"]);
																	$r10 = intval($row["respuesta_10"]);
																	if ($pregunta_tipo == 1) :
																		$totalRespuestas = $r1 + $r2 + $r3 + $r4 + $r5 + $r6 + $r7 + $r8 + $r9 + $r10;
																		$r1 = $r1 * 1;
																		$r2 = $r2 * 2;
																		$r3 = $r3 * 3;
																		$r4 = $r4 * 4;
																		$r5 = $r5 * 5;
																		$r6 = $r6 * 6;
																		$r7 = $r7 * 7;
																		$r8 = $r8 * 8;
																		$r9 = $r9 * 9;
																		$r10 = $r10 * 10;
																		$sumaFinal = $r1 + $r2 + $r3 + $r4 + $r5 + $r6 + $r7 + $r8 + $r9 + $r10;
																		$promedio = $sumaFinal / $totalRespuestas; 
																	?>
																	<td class="text-center">
																		<div class="container-bar">
																			<div class="bar bar-pro" style="width: 25px; height:<?=$promedio * 10?>%"><?=round($promedio,2)?></div>
																		</div>
																	</td>
																	<?php 
																	$promedio = 0;
																	$totalRespuestas = 0; 
																	$sumaFinal = 0; 
																	endif;?>			
																	
															<?php }?>
																</tr>
															<?php
															} 
															?>
														<tr>
														<?php
															foreach ($result as $row) {
																$pregunta_codigo = $row["pre_codigo"];
																$pregunta_tipo = $row["pre_tipo"];
																if ($pregunta_tipo == 1) :?>
																<th class="text-center"><?=$pregunta_codigo?></th>
																<?php 																
																endif;
															}
														?>
														</tr>
												</table>
											</div>
											<br><br>
											<!---PREGUNTAS Y PROMEDIOS PARTES INTERESADAS PROVEEDORES--->
											<table class="table table-striped table-bordered">
												<thead>
													<th class="text-center">No.</th>
													<th class="text-center">Pregunta</th>
													<th class="text-center">Promedio</th>
												</thead>
												<tbody>
											<?php
											$result = $ClsRes->get_estadistica_respuestas($codigo, $seccion_codigo,$desde,$hasta);
											if (is_array($result)) {
												$i = 1;
												foreach ($result as $row) {
													$pregunta_codigo = $row["pre_codigo"];
													$pregunta_tipo = $row["pre_tipo"];
													$pregunta = utf8_decode($row["pre_pregunta"]);
													$pregunta = nl2br($pregunta);
													$r1 = 0;
													$r2 = 0;
													$r3 = 0;
													$r4 = 0;
													$r5 = 0;
													$r6 = 0;
													$r7 = 0;
													$r8 = 0;
													$r9 = 0;
													$r10 = 0;
													//--
													$r1 = intval($row["respuesta_1"]);
													$r2 = intval($row["respuesta_2"]);
													$r3 = intval($row["respuesta_3"]);
													$r4 = intval($row["respuesta_4"]);
													$r5 = intval($row["respuesta_5"]);
													$r6 = intval($row["respuesta_6"]);
													$r7 = intval($row["respuesta_7"]);
													$r8 = intval($row["respuesta_8"]);
													$r9 = intval($row["respuesta_9"]);
													$r10 = intval($row["respuesta_10"]);
													$observacion = utf8_decode($row["respuesta_observacion"]);
													$observacion = nl2br($observacion);
													if ($pregunta_tipo == 1) :
														$totalRespuestas = $r1 + $r2 + $r3 + $r4 + $r5 + $r6 + $r7 + $r8 + $r9 + $r10;
														$r1 = $r1 * 1;
														$r2 = $r2 * 2;
														$r3 = $r3 * 3;
														$r4 = $r4 * 4;
														$r5 = $r5 * 5;
														$r6 = $r6 * 6;
														$r7 = $r7 * 7;
														$r8 = $r8 * 8;
														$r9 = $r9 * 9;
														$r10 = $r10 * 10;
														$sumaFinal = $r1 + $r2 + $r3 + $r4 + $r5 + $r6 + $r7 + $r8 + $r9 + $r10;
														$promedio = $sumaFinal / $totalRespuestas; 
													?>
													<tr>
														<td class="text-center"><?=$pregunta_codigo?></td>
														<td class="text-left"><?=$pregunta; ?></p>
														<td class="text-center"><?=round($promedio,2)?></td>
													</tr>
													<?php 
													$promedio = 0;
													$totalRespuestas = 0; 
													$sumaFinal = 0; 
													endif;?>			
													
											<?php }
											?>

											<?php
											} else {
											}
											?>
												</tbody>
											</table>
										</div>



									<!----PREGUNTAS SI Y NO--->
										<div class="card-body all-icons">
											<h6 class="h6 text-center"><i class="fas fa-list-ol"></i>  Encuestas de satisfacci&oacute;n al proveedor </h6>
												<div id="container-graphics">
												<table>
													<tr>
														<?php
														$result = $ClsRes->get_estadistica_respuestas($codigo, $seccion_codigo,$desde,$hasta);
														if (is_array($result)) {
															$i = 1;
															foreach ($result as $row) {
														$pregunta_codigo = $row["pre_codigo"];
														$pregunta_tipo = $row["pre_tipo"];
														$pregunta = utf8_decode($row["pre_pregunta"]);
														$pregunta = nl2br($pregunta);

														$r1 = 0;
														$r2 = 0;
														$r3 = 0;
														$r4 = 0;
														$r5 = 0;
														$r6 = 0;
														$r7 = 0;
														$r8 = 0;
														$r9 = 0;
														$r10 = 0;
														//--
														$r1 = intval($row["respuesta_1"]);
														$r2 = intval($row["respuesta_2"]);
													
														if ($pregunta_tipo == 2) :
															$promedioSi = ($r1/($r1 + $r2)) * 100;
															$promedioNo = ($r2/($r2 + $r1)) * 100;
														?>										
															<td>
																<div class="container-bar">
																	<div class="bar" style="height: <?=round($promedioSi,2)?>%;"><?=round($promedioSi,2)?>%</div>
																</div>
															</td>
															<td>
																<div class="container-bar">
																	<div class="bar bar-no" style="height:<?=round($promedioNo,2)?>%;"><?=round($promedioNo,2)?>%</div>
																</div>
															</td>											
															<?php 
															$promedioSi = 0;
															$promedioNo = 0;
															endif;
															}?>
															<?php } else {
															}?>
															</tr>


														<tr>
														<?php
														$result = $ClsRes->get_estadistica_respuestas($codigo, $seccion_codigo,$desde,$hasta);
														if (is_array($result)) {
															foreach ($result as $row) {
																$pregunta_codigo = $row["pre_codigo"];		
																$pregunta_tipo = $row['pre_tipo'];														
																if($pregunta_tipo == 2):?>
																<th class="text-center" colspan="2">
																	<?=$pregunta_codigo?>
																</th>	
																<?php endif;?>
															<?php 
																	}
															} else {
															}?>
														</tr>
													</table>
												</div>
											<br><br>

											<table class="table table-striped table-bordered">
												<thead>
													<th class="text-center">No.</th>
													<th class="text-center">Pregunta</th>
													<th class="text-center">Si</th>
													<th class="text-center">No</th>
												</thead>
												<tbody>
											<?php
											$result = $ClsRes->get_estadistica_respuestas($codigo, $seccion_codigo,$desde,$hasta);
											if (is_array($result)) {
												$i = 1;
												foreach ($result as $row) {
													$pregunta_codigo = $row["pre_codigo"];
													$pregunta_tipo = $row["pre_tipo"];
													$peso = $row["pre_peso"];
													$pregunta = utf8_decode($row["pre_pregunta"]);
													$pregunta = nl2br($pregunta);
													//--
													$json = '';
													$textoRespuesta = '';
													$trComentarios = '';
													$observacion = '';
													$r1 = 0;
													$r2 = 0;
													$r3 = 0;
													$r4 = 0;
													$r5 = 0;
													$r6 = 0;
													$r7 = 0;
													$r8 = 0;
													$r9 = 0;
													$r10 = 0;
													//--
													$r1 = intval($row["respuesta_1"]);
													$r2 = intval($row["respuesta_2"]);
													$r3 = intval($row["respuesta_3"]);
													$r4 = intval($row["respuesta_4"]);
													$r5 = intval($row["respuesta_5"]);
													$r6 = intval($row["respuesta_6"]);
													$r7 = intval($row["respuesta_7"]);
													$r8 = intval($row["respuesta_8"]);
													$r9 = intval($row["respuesta_9"]);
													$r10 = intval($row["respuesta_10"]);
													$observacion = utf8_decode($row["respuesta_observacion"]);
													$observacion = nl2br($observacion);
													if ($pregunta_tipo == 2) : 
														$promedioSi = ($r1/($r1 + $r2)) * 100;
													 	$promedioNo = ($r2/($r2 + $r1)) * 100;
													?>
													<tr>
														<td class="text-center"><?=$pregunta_codigo?></td>
														<td class="text-left"><?=$pregunta;?></p>
														<td class="text-center"><?=round($promedioSi,2)?>%</td>
														<td class="text-center"><?=round($promedioNo,2)?>%</td>
													</tr>
													<?php 
													$promedioSi = 0;
													$promedioNo = 0;
													endif;?>			
													
											<?php
													$i++;
													$x++;
												}
											} else {
											}
											
											?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
					<?php
						}
					}
					?>
					
					<div class="row">
						<div class="col-md-12">
							<div class="card demo-icons">
								<div class="card-body all-icons">
									<br>
									<div class="row">
										<div class="col-md-6 ml-auto mr-auto text-center">
											<a class="btn btn-default btn-lg" href="FRMcuestionarios.php"><span class="fa fa-chevron-left"></span> Regresar</a>
										</div>
									</div>
									<br>
								</div>
							</div>
						</div>
					</div>
				</div>
				<footer class="footer footer-black  footer-white ">
					<div class="container-fluid">
						<div class="row">
							<nav class="footer-nav">
								<ul>
									<li>
										<a href="https://www.farasi.com.gt" target="_blank" style="text-transform: none;"><strong>BPManagement</strong> 1.2.5 | Powered By <strong>Farasi Software</strong></a>
									</li>
								</ul>
							</nav>
							<div class="credits ml-auto">
								<span class="copyright">
									&copy; <?php echo date("Y"); ?> <strong>Copyright</strong> Farasi S.A.
								</span>
							</div>
						</div>
					</div>
				</footer>
			</div>
		</div>
	<?php echo modal("../"); ?>
	<?php echo scripts("../"); ?>
	<!-- Morris Charts JavaScript -->
	<script src="../assets.1.2.8/js/plugins/morris/raphael-2.1.0.min.js"></script>
	<script src="../assets.1.2.8/js/plugins/morris/morris.js"></script>

	<script type="text/javascript" src="../assets.1.2.8/js/modules/encuestas/estadistica.js"></script>
	<script>
		$(function() {
			<?php echo $salida_js; ?>
		});
	</script>

</body>

</html>