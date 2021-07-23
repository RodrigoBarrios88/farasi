<?php
include_once('html_fns_activo.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$ClsAct = new ClsActivo();validate_login("../");
$usuario = $_SESSION["codigo"];
$hashkey = $_REQUEST["hashkey"];
$codigo = $ClsAct->decrypt($hashkey, $usuario);
$result = $ClsAct->get_activo($codigo);
if (is_array($result)) {
	foreach ($result as $row) {
		$sede = utf8_decode($row["sed_nombre"]);
		$sector = utf8_decode($row["sec_nombre"]);
		$area = utf8_decode($row["are_nombre"]);
		$nivel = utf8_decode($row["are_nivel"]);
		$nombre = utf8_decode($row["act_nombre"]);
		$marca = utf8_decode($row["act_marca"]);
	}
}
$result = $ClsAct->get_fotos('', $codigo, 1);
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$fotCodigo = trim($row["fot_codigo"]);
		$posicion = trim($row["fot_posicion"]);
		$strFoto1 = trim($row["fot_foto"]);
		if (file_exists('../../CONFIG/Fotos/ACTIVOS/' . $strFoto1 . '.jpg') || $strFoto1 != "") {
			$strFoto1 = '<img onclick="deleteFotoConfirm(' . $fotCodigo . ',' . $posicion . ');" src="../../CONFIG/Fotos/ACTIVOS/' . $strFoto1 . '.jpg" alt="...">';
		} else {
			$strFoto1 = '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
		}
	}
} else {
	$strFoto1 = '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
}
$result = $ClsAct->get_fotos('', $codigo, 2);
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$fotCodigo = trim($row["fot_codigo"]);
		$posicion = trim($row["fot_posicion"]);
		$strFoto2 = trim($row["fot_foto"]);
		if (file_exists('../../CONFIG/Fotos/ACTIVOS/' . $strFoto2 . '.jpg') || $strFoto2 != "") {
			$strFoto2 = '<img onclick="deleteFotoConfirm(' . $fotCodigo . ',' . $posicion . ');" src="../../CONFIG/Fotos/ACTIVOS/' . $strFoto2 . '.jpg" alt="...">';
		} else {
			$strFoto2 = '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
		}
	}
} else {
	$strFoto2 = '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
}
$result = $ClsAct->get_fotos('', $codigo, 3);
if (is_array($result)) {
	$i = 0;
	foreach ($result as $row) {
		$fotCodigo = trim($row["fot_codigo"]);
		$posicion = trim($row["fot_posicion"]);
		$strFoto3 = trim($row["fot_foto"]);
		if (file_exists('../../CONFIG/Fotos/ACTIVOS/' . $strFoto3 . '.jpg') || $strFoto3 != "") {
			$strFoto3 = '<img onclick="deleteFotoConfirm(' . $fotCodigo . ',' . $posicion . ');" src="../../CONFIG/Fotos/ACTIVOS/' . $strFoto3 . '.jpg" alt="...">';
		} else {
			$strFoto3 = '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
		}
	}
} else {
	$strFoto3 = '<img class="img-upload" src="../../CONFIG/img/imagePhoto.jpg" alt="..." />';
}
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
								<h5 class="card-title"><i class="fa fa-camera"></i> &nbsp; Cargar Fotos de Activo</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-xs-6 col-md-6 text-left">
										<button type="button" class="btn btn-white" onclick="window.history.back();"><i class="fa fa-chevron-left"></i> Atr&aacute;s</button>
									</div>
									<div class="col-xs-6 col-md-6 text-left">
										<label>C&oacute;digo de Activo:</label> <br>
										<strong># <?php echo Agrega_Ceros($codigo); ?></strong>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Nombre del Activo:</label>
										<input type="text" class="form-control" value="<?php echo $nombre; ?>" />
										<form action="EXEcarga_foto.php" name="f1" name="f1" method="post" enctype="multipart/form-data">
											<input id="imagen" name="imagen" type="file" multiple="false" class="hidden" onchange="uploadImage();">
											<input type="hidden" id="codigo" name="codigo" value="<?php echo $codigo; ?>" />
											<input type="hidden" id="posicion" name="posicion" />
										</form>
									</div>
									<div class="col-md-6">
										<label>Marca:</label>
										<input type="text" class="form-control" readonly value="<?php echo $marca; ?>" />
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-md-4 text-center">
										<div class="fileinput fileinput-new text-center" data-provides="fileinput">
											<div class="fileinput fileinput-new text-center" data-provides="fileinput">
												<div class="text-center" id="foto1">
													<?php echo $strFoto1; ?>
												</div>
												<span class="btn btn-rose btn-round btn-file">
													<span class="fileinput-new" onclick="FotoJs(<?php echo $codigo; ?>,1);"><i class="fa fa-camera"></i> Agregar Foto 1</span>
												</span>
											</div>
										</div>
									</div>
									<div class="col-md-4 text-center">
										<div class="fileinput fileinput-new text-center" data-provides="fileinput">
											<div class="fileinput fileinput-new text-center" data-provides="fileinput">
												<div class="text-center" id="foto2">
													<?php echo $strFoto2; ?>
												</div>
												<span class="btn btn-rose btn-round btn-file">
													<span class="fileinput-new" onclick="FotoJs(<?php echo $codigo; ?>,2);"><i class="fa fa-camera"></i> Agregar Foto 2</span>
												</span>
											</div>
										</div>
									</div>
									<div class="col-md-4 text-center">
										<div class="fileinput fileinput-new text-center" data-provides="fileinput">
											<div class="fileinput fileinput-new text-center" data-provides="fileinput">
												<div class="text-center" id="foto3">
													<?php echo $strFoto3; ?>
												</div>
												<span class="btn btn-rose btn-round btn-file">
													<span class="fileinput-new" onclick="FotoJs(<?php echo $codigo; ?>,3);"><i class="fa fa-camera"></i> Agregar Foto 3</span>
												</span>
											</div>
										</div>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-12 text-center">
										<button type="button" class="btn btn-white" onclick="window.history.back();"><i class="fa fa-chevron-left"></i> Atr&aacute;s</button>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/ppm/activo.js"></script>
	<script>
		$(document).ready(function() {
			$('.dataTables-example').DataTable({
				pageLength: 100,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: [{
						extend: 'copy'
					},
					{
						extend: 'csv'
					},
					{
						extend: 'excel',
						title: 'Reporte de Activos'
					},
					{
						extend: 'pdf',
						title: 'Reporte de Activos'
					},
					{
						extend: 'print',
						customize: function(win) {
							$(win.document.body).addClass('white-bg');
							$(win.document.body).css('font-size', '10px');
							$(win.document.body).find('table')
								.addClass('compact')
								.css('font-size', 'inherit');
						},
						title: 'Reporte de Activos'
					}
				]
			});

			$('.select2').select2({ width: '100%' });
		});
	</script>

</body>
</html>