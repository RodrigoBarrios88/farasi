<?php
include_once('html_fns_indicador.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$proceso = $_REQUEST["proceso"];
$sistema = $_REQUEST["sistema"];
$usuario = $_REQUEST["usuario"];
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("../", "indicador"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?> <div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="fa fa-exclamation-circle"></i> Reporte de Indicadores
								</h5>
							</div>
							<div class="card-body all-icons">
								<form name="f1" id="f1" action="" method="get">
									<div class="row">
										<div class="col-xs-12 col-md-12 text-right"><label class="text-info">* Filtros de Busqueda</label> </div>
									</div>
									<div class="row">
										<div class="col-xs-6 col-md-6 text-left">
											<button type="button" class="btn btn-white" onclick="window.history.back();">
												<i class="fa fa-chevron-left"></i>Atr&aacute;s
											</button>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Proceso:</label> <span class="text-info">*</span>
											<?php echo utf8_decode(ficha_html('proceso', 'Submit();', 'select2')); ?>
											<script>
												document.getElementById("proceso").value = '<?php echo $proceso; ?>';
											</script>
										</div>
										<div class="col-md-6">
											<label>Sistema:</label> <span class="text-info">*</span>
											<?php echo utf8_decode(sistema_html('sistema', 'Submit();', 'select2')); ?>
											<script>
												document.getElementById("sistema").value = '<?php echo $sistema; ?>';
											</script>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Usuario:</label> <span class="text-info">*</span>
											<?php echo utf8_decode(usuarios_html('usuario', 'Submit();', 'select2')); ?>
											<script>
												document.getElementById("usuario").value = '<?php echo $usuario; ?>';
											</script>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12 text-center">
											<br>
											<a class="btn btn-white" href="FRMindicador.php"><i class="fa fa-eraser"></i> Limpiar</a>
											<button type="button" class="btn btn-primary" onclick="Submit();"><i class="fa fa-search"></i> Buscar</button>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12" id="result">
											<?php echo utf8_decode(tabla_indicadores($codigo, $proceso, $sistema, $usuario)); ?>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/indicador/indicador.js"></script>

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