<?php
include_once('html_fns_activo.php');
validate_login("../");
$id = $_SESSION["codigo"];


//$_POST
$area = $_REQUEST["area"];
if ($area != "") {
	$ClsAre = new ClsArea();
	$result = $ClsAre->get_area($area, '', '', '', '', 1);
	if (is_array($result)) {
		foreach ($result as $row) {
			$sede = trim($row["sed_codigo"]);
			$sector = trim($row["sec_codigo"]);
			$secNombre = utf8_decode($row["sec_nombre"]);
			$nivel = utf8_decode($row["are_nivel"]);
		}
	}
}?>
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
								<h5 class="card-title"><i class="nc-icon nc-app"></i> &nbsp; Reporte de Fallas y Mantenimiento</h5>
							</div>
							<div class="card-body all-icons">
								<form name="f1" id="f1" action="" method="get">
									<div class="row">
										<div class="col-xs-6 col-md-6 text-left"><a class="btn btn-white" href="../menu_ppm.php"><i class="fa fa-chevron-left"></i> Atr&aacute;s</a> </div>
										<div class="col-xs-6 col-md-6 text-right"><label class=" text-success">* Campos de Busqueda</label> </div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<label>Area:</label> <span class="text-success">*</span>
											<?php echo utf8_decode(areas_sede_html("area", "Submit()", "select2")); ?>
											<input type="hidden" name="sede" id="sede" value="<?php echo $sede; ?>" />
											<input type="hidden" name="sector" id="sector" value="<?php echo $sector; ?>" />
											<script>
												document.getElementById("area").value = "<?php echo $area; ?>"
											</script>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Sector:</label> <span class="text-success">*</span>
											<input type="text" class="form-control" name="secNombre" id="secNombre" value="<?php echo $secNombre; ?>" readonly />
										</div>
										<div class="col-md-6">
											<label>Nivel:</label> <span class="text-success">*</span>
											<input type="text" class="form-control" name="nivel" id="nivel" value="<?php echo $nivel; ?>" readonly />
										</div>
									</div>
									<br>
									<div class="row">
										<div class="col-md-12" id="result">
											<?php
											echo tabla_activos_falla('', '', $area, $nivel);
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