<?php
include_once('html_fns_reportes.php');
validate_login("../../");
$id = $_SESSION["codigo"];
$nombre_sesion = utf8_decode($_SESSION["nombre"]);

//$_POST
$ClsRev = new ClsRevision();
$hashkey = $_REQUEST["hashkey"];
$codigo = $ClsRev->decrypt($hashkey, $id);
//--
$result = $ClsRev->get_revision_indicador($codigo, "", "", "", "", "", 2);
if (is_array($result)) {
	foreach ($result as $row) {
		$lectura = trim($row["rev_lectura"]);
		$observaciones = utf8_decode($row["rev_observaciones"]);
		$fecha = trim($row["rev_fecha_final"]);
		$fecha = cambia_fechaHora($fecha);
		$codigo_indicador = trim($row["ind_codigo"]);
		$nombre = utf8_decode($row["ind_nombre"]);
		$descripcion = utf8_decode($row["ind_descripcion"]);
		$descripcion = ($descripcion == "") ? "N/A" : $descripcion;
		$usuario_nombre = utf8_decode($row["ind_usuario"]);
		$usuario_anota = utf8_decode($row["rev_usuario"]);
		$objetivo = utf8_decode($row["obj_descripcion"]);
		$sistema = utf8_decode($row["obj_sistema"]);
		$proceso = utf8_decode($row["obj_proceso"]);
		$min = trim($row["ind_lectura_minima"]);
		$max = trim($row["ind_lectura_maxima"]);
		$ideal = trim($row["ind_lectura_ideal"]);
		$usuario = trim($row["ind_usuario"]);
		$unidad = utf8_decode($row["medida_nombre"]);
		$observacion = nl2br(utf8_decode($row["pro_observaciones"]));
		$arrArchivos = get_archivos(2, $codigo);
	}
} ?>
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
				<p class="text-justify">Anotaci&oacute;n No. # <?php echo Agrega_Ceros($codigo); ?></p>
				<p class="text-justify">Generado por: <?php echo $nombre_sesion; ?></p>
			</div>
			<div class="col-md-6 text-right">
				<img src="../../../CONFIG/img/replogo.jpg" alt="logo" width="150px" />
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h5 class="card-title"><i class="fas fa-clipboard-list"></i> Anotaci&oacute;n No. #<?php echo Agrega_Ceros($codigo); ?></h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12">
						<div class="row">
							<div class="col-md-12">
								<label>Objetivo:</label>
								<p><?php echo $objetivo; ?></p>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label>Descripci&oacute;n del Indicador:</label>
								<p><?php echo $descripcion; ?></p>
							</div>
						</div>
						<?php
						if ($observacion != "") {
						?>
							<div class="row">
								<div class="col-md-12">
									<label>Observaciones Especiales:</label><br>
									<p><?php echo $observacion; ?></p>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="row">
							<div class="col-lg-12">
								<div class="row">
									<div class="col-md-12">
										<label>Usuario:</label>
										<p><?php echo $usuario_nombre; ?></p>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Indicador:</label>
										<p><?php echo $nombre; ?></p>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Proceso:</label>
										<p><?php echo $proceso; ?></p>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Sistema:</label>
										<p><?php echo $sistema; ?></p>
									</div>
								</div>
							</div>
						</div>
						<br>
					</div>
					<div class="col-md-6">
						<div class="row">
							<div class="col-lg-12">
								<div class="row">
									<div class="col-md-12">
										<label>Unidad de Medida:</label>
										<p><?php echo $unidad; ?></p>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Lectura Minima:</label>
										<p><?php echo $min; ?></p>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Lectura Ideal:</label>
										<p><?php echo $ideal; ?></p>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Lectura M&aacute;xima:</label>
										<p><?php echo $max; ?></p>
									</div>
								</div>
								<br>
							</div>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6 ml-auto mr-auto">
						<label>Lectura (<?php echo $unidad ?>):</label>
						<p><?php echo $lectura; ?></p>
					</div>
					<div class="col-md-6 ml-auto mr-auto">
						<label>Fecha/Hora:</label>
						<p><?php echo $fecha; ?></p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 ml-auto mr-auto">
						<label>Observaciones:</label>
						<p><?php echo $observaciones; ?></p>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-6 text-center">
						<div class="fileinput fileinput-new text-center" data-provides="fileinput">
							<div class="fileinput fileinput-new text-center" data-provides="fileinput">
								<div class="text-center">
									<?php echo $arrArchivos[1]; ?>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 text-center">
						<div class="fileinput fileinput-new text-center" data-provides="fileinput">
							<div class="fileinput fileinput-new text-center" data-provides="fileinput">
								<div class="text-center">
									<?php echo $arrArchivos[2]; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php echo scripts("../../"); ?>
		<script type="text/javascript" src="../../assets.1.2.8/js/modules/planning/accion.js"></script>
		<script>
			function pageprint() {
				boton = document.getElementById("print");
				boton.style.display = "none";
				window.print();
				boton.style.display = "block";
			}
		</script>



</body>

</html>