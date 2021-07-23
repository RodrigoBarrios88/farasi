<?php
include_once('html_fns_revision.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$nombre = $_REQUEST["nom"];

//$_POST
$sede = $_REQUEST["sede"];
$sector = $_REQUEST["sector"];
$area = $_REQUEST["area"];
$categoria = $_REQUEST["categoria"];
$tipo = $_REQUEST["tipo"];
$tipo = ($tipo == "") ? 'S' : $tipo; ?>
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
<!---
				<div class="row">
					<div class="col-md-12">ssssss
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-check-square-o"></i> Tipo de Programaci&oacute;n</h5>
							</div>
							<div class="card-body all-icons">
								<div class="nav-tabs-navigation">
									<div class="nav-tabs-wrapper">
										<ul id="tabs" class="nav nav-tabs" role="tablist">
											<?php
											//if ($tipo == 'M') {
											?>
												<li class="nav-item">
													<a id="tarjetaMes" class="nav-link active" onclick="cambiarTipo('M');">
														<h6><i class="fa fa-calendar-check-o"></i> Mensual</h6>
													</a>
												</li>
												<li class="nav-item">
													<a id="tarjetaSemana" class="nav-link" onclick="cambiarTipo('S');">
														<h6><i class="fa fa-list-ol"></i> Semanal</h6>
													</a>
												</li>
											<?php
											//} else {
											?>
												<li class="nav-item">
													<a id="tarjetaMes" class="nav-link" onclick="cambiarTipo('M');">
														<h6><i class="fa fa-calendar-check-o"></i> Mensual</h6>
													</a>
												</li>
												<li class="nav-item">
													<a id="tarjetaSemana" class="nav-link active" onclick="cambiarTipo('S');">
														<h6><i class="fa fa-list-ol"></i> Semanal</h6>
													</a>
												</li>
											<?php
											//}
											?>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

-->

				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="nc-icon nc-paper"></i> Listas Programadas</h5>
							</div>
							<div class="card-body all-icons">
								<form name="f1" id="f1" action="" method="get">
									<div class="row">
										<div class="col-xs-6 col-md-6 text-left">
											<button type="button" class="btn btn-white" onclick="window.history.back();">
												<i class="fa fa-chevron-left"></i>Atr&aacute;s
											</button>
										</div>
										<div class="col-xs-6 col-md-6 text-right"><label class=" text-success">* Campos de Busqueda</label> </div>
									</div>
									<br>
									<div class="row">
										<div class="col-md-6">
											<input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo; ?>" />
											<label>Sede:</label> <span class="text-success">*</span>
											<?php echo utf8_decode(sedes_html("sede", "Submit();", "select2")); ?>
											<script>
												document.getElementById("sede").value = '<?php echo $sede; ?>';
											</script>
										</div>
										<div class="col-md-6">
											<label>Sector:</label> <span class="text-success">*</span>
											<?php
											if ($sede != "") {
												echo utf8_decode(sector_html("sector", $sede, "Submit();", "select2"));
											} else {
												echo combos_vacios("sector", 'select2');
											}
											?>
											<script>
												document.getElementById("sector").value = '<?php echo $sector; ?>';
											</script>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>&Aacute;rea:</label> <span class="text-success">*</span>
											<?php
											if ($sector != "") {
												echo utf8_decode(area_html("area", $sector, "Submit();", "select2"));
											} else {
												echo combos_vacios("area", 'select2');
											}
											?>
											<script>
												document.getElementById("area").value = '<?php echo $area; ?>';
											</script>
										</div>
										<div class="col-md-6">
											<label>Categor&iacute;a:</label> <span class="text-success">*</span>
											<?php echo utf8_decode(categorias_chk_html("categoria", "Submit();", "select2")); ?>
											<script>
												document.getElementById("categoria").value = '<?php echo $categoria; ?>';
											</script>
										</div>
									</div>
								</form>
								<br>
								<div class="row">
									<div class="col-lg-12" id="result">
										<?php
										echo tabla_listas("", $sede, $sector, $area, $tipo, $categoria);
										?>
									</div>
								</div>
								<br>
							</div>
						</div>
					</div>
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
					buttons: []
				});
				$('.select2').select2({ width: '100%' });
			});
		</script>

</body>

</html>