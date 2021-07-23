<?php
include_once('html_fns_revision.php');
validate_login("../");
$usuario = $_SESSION["codigo"];

$categoriasIn = $_SESSION["categorias_in"];
//$_POST
$ClsLis = new ClsLista();
$ClsRev = new ClsRevision();
$codigo = $_REQUEST["codigo"];
$firma = $_REQUEST["firma"];
$foto = $_REQUEST["foto"];
//--
$result = $ClsRev->get_revision($codigo);
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$revision = trim($row["rev_codigo"]);
		$codigo_lista = trim($row["list_codigo"]);
		$codigo_progra = trim($row["pro_codigo"]);
		$sede = utf8_decode($row["sed_nombre"]);
		$sector = utf8_decode($row["sec_nombre"]);
		$area = utf8_decode($row["are_nombre"]);
		$nivel = utf8_decode($row["are_nivel"]);
		$categoria = utf8_decode($row["cat_nombre"]);
		$nombre = utf8_decode($row["list_nombre"]);
		$usuario = utf8_decode($row["usuario_nombre"]);
		//--
		$requiere_firma = trim($row["list_firma"]);
		$requiere_fotos = trim($row["list_fotos"]);
		$strFirma = trim($row["rev_firma"]);
		//--
		$fecha_inicio = trim($row["rev_fecha_inicio"]);
		$fecha_inicio = cambia_fechaHora($fecha_inicio);
		$fecha_inicio = substr($fecha_inicio, 0, 16);

		$fecha_finaliza = trim($row["rev_fecha_final"]);
		$fecha_finaliza = cambia_fechaHora($fecha_finaliza);
		$fecha_finaliza = substr($fecha_finaliza, 0, 16);
		$obs = utf8_decode($row["rev_observaciones"]);
		$obs = nl2br($obs);
		//
		$situacion = trim($row["rev_situacion"]);
	}
	if (file_exists('../../CONFIG/Fotos/FIRMAS/' . $strFirma . '.jpg') && $strFirma != "") {
		$strFirma = 'Fotos/FIRMAS/' . $strFirma . '.jpg';
	} else {
		$strFirma = "img/imageSign.jpg";
	}
	/////////// PROGRAMACION /////
	$dia = date("N");
	$result = $ClsLis->get_programacion($codigo_progra, $codigo_lista);
	if (is_array($result)) {
		$i = 0;
		foreach ($result as $row) {
			$hini = trim($row["pro_hini"]);
			$hfin = trim($row["pro_hfin"]);
			$horario = "$hini - $hfin";
		}
	}
}
$result = $ClsRev->get_fotos('', $revision);
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$strFoto = trim($row["fot_foto"]);
	}
	if (file_exists('../../CONFIG/Fotos/REVISION/' . $strFoto . '.jpg') && $strFoto != "") {
		$strFoto = 'Fotos/REVISION/' . $strFoto . '.jpg';
	} else {
		$strFoto = "img/imagePhoto.jpg";
	}
} else {
	$strFoto = "img/imagePhoto.jpg";
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
					<div class="col-md-6">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="nc-icon nc-pin-3"></i> Ubicaci&oacute;n
									<button type="button" class="btn btn-white btn-lg sin-margin pull-right" onclick="window.history.back();"><small><i class="fa fa-chevron-left"></i> Atr&aacute;s</small></button>
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
												<label>Sede:</label>
												<input type="text" class="form-control" value="<?php echo $sede; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Sector:</label>
												<input type="text" class="form-control" value="<?php echo $sector; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>&Aacute;rea:</label><br>
												<input type="text" class="form-control" value="<?php echo $area; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Nivel:</label><br>
												<input type="text" class="form-control" value="<?php echo $nivel; ?>" disabled />
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
									<a class="btn btn-white btn-lg sin-margin pull-right" href="CPREPORTES/REPrevision.php?hashkey=<?php echo $hashkey; ?>" target="_blank"><small><i class="fa fa-print"></i> Imprimir</small></a>
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12" id="result">
										<div class="row">
											<div class="col-md-12">
												<label>Categor&iacute;a:</label>
												<input type="text" class="form-control" value="<?php echo $categoria; ?>" disabled />
												<input type="hidden" id="revision" name="revision" value="<?php echo $revision; ?>" />
												<input type="hidden" id="reqfoto" name="reqfoto" value="<?php echo $requiere_fotos; ?>" />
												<input type="hidden" id="reqfirma" name="reqfirma" value="<?php echo $requiere_firma; ?>" />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Lista:</label>
												<input type="text" class="form-control" value="<?php echo $nombre; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Horario de Ejecuci&oacute;n:</label><br>
												<input type="text" class="form-control" value="<?php echo $horario; ?>" disabled />
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Usuario que Ejecut&oacute;:</label><br>
												<input type="text" class="form-control" value="<?php echo $usuario; ?>" disabled />
											</div>
										</div>
									</div>
								</div>
								<br>
							</div>
						</div>
					</div>
				</div>

				<?php if ($firma == 1) { ?>
					<br>
					<div class="row">
						<div class="col-md-12 text-center">
							<div class="fileinput fileinput-new text-center" data-provides="fileinput">
								<div class="fileinput-new thumbnail">
									<img src="../../CONFIG/<?php echo $strFirma; ?>" alt="...">
								</div>
							</div>
							<p>Firma</p>
						</div>
					</div>
					<br>
				<?php } ?>

				<?php if ($foto == 1) { ?>
					<br>
					<div class="row">
						<div class="col-md-12 text-center">
							<div class="fileinput fileinput-new text-center" data-provides="fileinput">
								<div class="fileinput-new thumbnail">
									<img src="../../CONFIG/<?php echo $strFoto; ?>" alt="...">
								</div>
							</div>
							<p>Foto</p>
						</div>
					</div>
					<br>
				<?php } ?>

			</div>
			<?php echo footer() ?>
		</div>
	</div>
	<?php echo modal("../"); ?>
	<?php echo scripts("../"); ?>

	<script type="text/javascript" src="../assets.1.2.8/js/modules/checklist/revision.js"></script>
	<script>
		$(document).ready(function() {
			$('.dataTables-example').DataTable({
				pageLength: 100,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: [

				]
			});

			$('.select2').select2({ width: '100%' });
		});
	</script>

</body>

</html>