<?php
include_once('html_fns_reportes.php');
validate_login("../../");
$id = $_SESSION["codigo"];
$nombre_sesion = utf8_decode($_SESSION["nombre"]);

//$_POST
$hashkey = $_REQUEST["hashkey"];
$ClsFic = new ClsFicha();
$codigo = $ClsFic->decrypt($hashkey, $id);
$result = $ClsFic->get_ficha($codigo);
foreach ($result as $row) {
	$nombre = utf8_decode($row["fic_nombre"]);
	$tipo = utf8_decode($row["tit_nombre"] . " " . $row["sub_nombre"]);

	$analisis = utf8_decode($row["fic_analisis_foda"]);
	$analisis = nl2br($analisis);
	$objetivo = utf8_decode($row["fic_objetivo_general"]);
	$objetivo = nl2br($objetivo);

	$pertenece = trim($row["fic_pertenece"]);

	$desde = cambia_fecha($row["fic_fecha_inicio"]);
	$hasta = cambia_fecha($row["fic_fecha_fin"]);
}

?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../../"); ?>
	<link rel="stylesheet" href="../../assets.1.2.8/css/propios/printpre.css" type="text/css" media="screen,projection" charset="UTF-8" />
	<link rel="stylesheet" href="../../assets.1.2.8/css/propios/printpost.css" type="text/css" media="print" />
</head>

<body class="">
	<!--div align = "center" id = "print">
		<button type = "button" class = "btn btn-print btn-round" onclick = "pageprint();" ><i class="fas fa-print"></i> Imprimir</button><br /><br />
	</div-->
	<div class="m-5">
		<div class="row">
			<div class="col-md-6">
				<p class="text-justify">Ficha de Procesos No. # <?php echo Agrega_Ceros($codigo); ?></p>
				<p class="text-justify">Generado por: <?php echo $nombre_sesion; ?></p>
			</div>
			<div class="col-md-6 text-right">
				<img src="../../../CONFIG/img/replogo.jpg" alt="logo" width="150px" />
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h5 class="card-title"><i class="fas fa-clipboard-list"></i> Ficha de Procesos No. #<?php echo Agrega_Ceros($codigo); ?></h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<label>Nombre del Proceso:</label>
						<p class="text-justify"><?php echo $nombre; ?></p>
						<input type="hidden" id="codigo" name="codigo" value="<?php echo $codigo; ?>">
					</div>
					<?php
					if ($pertenece != "0") {
						$rs = $ClsFic->get_ficha($pertenece);
						foreach ($rs as $row) {
							$pertenece = utf8_decode($row["fic_nombre"]);
					?>
							<div class="col-md-6">
								<label>Proceso al que pertenece:</label>
								<p class="text-justify"><?php echo $pertenece; ?></p>
							</div>
					<?php
						}
					}
					?>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label>Tipo de Proceso:</label>
						<p class="text-justify"><?php echo $tipo; ?></p>
					</div>
					<div class="col-md-6">
						<label>Fechas de Vigencia:</label>
						<div class="form-group" id="range">
							<div class="input-daterange input-group" id="datepicker">
								<p class="text-justify"> <?php echo $desde; ?> </p>
								<p class="text-justify">&nbsp;-&nbsp;</p>
								<p class="text-justify"><?php echo $hasta; ?> </p>
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
						<label>An&aacute;lisis FODA:</label>
						<p class="text-justify"><?php echo $analisis; ?></p>
					</div>
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
						<label>Objetivo General del Proceso:</label>
						<p class="text-justify"><?php echo $objetivo; ?></p>
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
							<div class="col-md-6"><small>Objetivos de <?php echo $sistema_nombre; ?>:</small></div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div id="objetivos<?php echo $sistema; ?>" role="tablist" aria-multiselectable="true" class="card-collapse">
									<?php echo utf8_decode(objetivos_acordion_pdf($codigo, $sistema)); ?>
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
				<br>
				<div class="row">
					<div class="col-md-12" id="resultElementos"></div>
				</div>
				<div class="row">
					<div class="col-md-6"><label>Fuentes de Entradas:</label> </div>
				</div>
				<div class="row">
					<div class="col-md-12" id="resultFuenteEntrada"><?php echo utf8_decode(tabla_elemento('', $codigo, 1)); ?></div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-6"><label>Entradas:</label> </div>
				</div>
				<div class="row">
					<div class="col-md-12" id="resultEntrada"><?php echo utf8_decode(tabla_elemento('', $codigo, 2)); ?></div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-6"><label>Receptores de las Salidas:</label> </div>
				</div>
				<div class="row">
					<div class="col-md-12" id="resultFuenteSalida"><?php echo utf8_decode(tabla_elemento('', $codigo, 3)); ?></div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-6"><label>Salidas:</label> </div>
				</div>
				<div class="row">
					<div class="col-md-12" id="resultSalida"><?php echo utf8_decode(tabla_elemento('', $codigo, 4)); ?></div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-6"><label>Actividades:</label> </div>
				</div>
				<div class="row">
					<div class="col-md-12" id="resultActividad"><?php echo utf8_decode(tabla_elemento('', $codigo, 5)); ?></div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-6"><label>Medidas de Verificaci&oacute;n:</label> </div>
				</div>
				<div class="row">
					<div class="col-md-12" id="resultVerificacion"><?php echo utf8_decode(tabla_elemento('', $codigo, 6)); ?></div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-6"><label>Requisitos Legales:</label> </div>
				</div>
				<div class="row">
					<div class="col-md-12" id="resultRequisitoLegal"><?php echo utf8_decode(tabla_legal('', $codigo)); ?></div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-6"><label>Aspectos Ambientales:</label> </div>
				</div>
				<div class="row">
					<div class="col-md-12" id="resultAspectoAmbiental"><?php echo utf8_decode(tabla_ambiental('', $codigo)); ?></div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-6"><label>Responsabilidad Social:</label> </div>
				</div>
				<div class="row">
					<div class="col-md-12" id="resultResponsibilidadSocial"><?php echo utf8_decode(tabla_responsabilidad('', $codigo)); ?></div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-6"><label>Puntos de Norma que aplican al proceso:</label> </div>
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
				<?php
				$ClsRec = new ClsRecursos();
				$result = $ClsRec->get_tipo_recursos('', '', 1);
				if (is_array($result)) {
					foreach ($result as $row) {
						$tipo = trim($row["tip_codigo"]);
						$tipo_nombre = utf8_decode($row["tip_nombre"]);
						$recurso_nombre = "";
						$result_recurso = $ClsRec->get_recurso('', $codigo, $tipo);
						if (is_array($result_recurso)) {
							foreach ($result_recurso as $row_objetivos) {
								$recurso_nombre = utf8_decode($row_objetivos["rec_descripcion"]);
							}
						} else {
							$recurso_nombre = "-";
						}
				?>
						<div class="row">
							<div class="col-md-12">
								<label><?php echo $tipo_nombre; ?></label> <br>
								<ul class="text-justify">
									<li><?php echo $recurso_nombre; ?></li>
								</ul>
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
		<br>
	</div>
	<?php echo scripts("../../"); ?>
	<script type="text/javascript" src="../../assets.1.2.8/js/modules/process/elemento.js"></script>
	<script type="text/javascript" src="../../assets.1.2.8/js/modules/process/requisito_legal.js"></script>
	<script>
		$(document).ready(function() {
			mostrarDiagramaReporte();
		});

		function pageprint() {
			boton = document.getElementById("print");
			boton.style.display = "none";
			window.print();
			boton.style.display = "block";
		}
	</script>
</body>

</html>