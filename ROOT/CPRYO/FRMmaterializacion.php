<?php
include_once('html_fns_ryo.php');
validate_login("../");
$id = $_SESSION["codigo"];

//--

$tipo = $_REQUEST["tipo"];
$proceso = $_REQUEST["proceso"];
$sistema = $_REQUEST["sistema"];
$tipo = ($tipo == "") ? 'M' : $tipo;

?>

<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("../", "risk"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-check-square-o"></i> Riesgos</h5>
							</div>
							<div class="card-body all-icons">
								<div class="nav-tabs-navigation">
									<div class="nav-tabs-wrapper">
										<ul id="tabs" class="nav nav-tabs" role="tablist">
											<li class="nav-item">
												<a id="tarjetaMaterializar" class="nav-link active" onclick="cambiarTipo('M');">
													<h6><i class="fa fa-calendar-check-o"></i> Materializar</h6>
												</a>
											</li>
											<li class="nav-item">
												<a id="tarjetaMaterializado" class="nav-link" onclick="cambiarTipo('MM');">
													<h6><i class="fa fa-list-ol"></i> Materializados</h6>
												</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>				
				<!----RIESGOS SIN MATERIALIZAR---->
				<div class="row" id="materializar">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="fas fa-fire-alt"></i> Materializaci&oacute;n de Riesgos
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-xs-6 col-md-6 text-left">
										<button type="button" class="btn btn-white" onclick="window.history.back();">
											<i class="fa fa-chevron-left"></i>Atr&aacute;s
										</button>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-lg-12" id="result">
										<input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo; ?>" />
										<?php echo tabla_materializacion($proceso, $sistema, $id, 1); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!----RIESGOS MATERIALIZADOS--->
				<div class="row" id="materializado">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="fas fa-fire-alt"></i> Riesgos Materializados
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-xs-6 col-md-6 text-left">
										<button type="button" class="btn btn-white" onclick="window.history.back();">
											<i class="fa fa-chevron-left"></i>Atr&aacute;s
										</button>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-lg-12" id="result">
										<input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo; ?>" />
										<?php echo tabla_materializacion($proceso, $sistema, $id, 2); ?>
									</div>
								</div>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/ryo/accion.js"></script>
	<script>
		$(document).ready(function() {
			cambiarTipo('M');
			$('.dataTables-example').DataTable({
				pageLength: 50,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: []
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