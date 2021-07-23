<?php
include_once('html_fns_programacion.php');
validate_login("../");
$id = $_SESSION["codigo"];

$ClsUsu = new ClsUsuario();
$ClsPro = new ClsProgramacionPPM();
//POST
$hashkey = $_REQUEST["hashkey"];
$codigo = $ClsPro->decrypt($hashkey, $id);
$result = $ClsUsu->get_usuario($codigo, '', '', '', '', 1);
if (is_array($result)) {
	foreach ($result as $row) {
		$nombre = utf8_decode($row["usu_nombre"]);
	}
}
$desde = $_REQUEST["desde"];
$hasta = $_REQUEST["hasta"];
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
								<h5 class="card-title">
									<i class="fa fa-users-cog"></i> Programaci&oacute;n de Actividades Para el Usuario <?php echo $nombre; ?>
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-xs-6 col-md-6 text-left">
										<a type="button" class="btn btn-white" href="../menu_ppm.php"><i class="fa fa-chevron-left"></i> Atr&aacute;s</a>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12 col-md-12">
										<?php echo tabla_programacion('', $codigo, '', '', $desde, $hasta); ?>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/ppm/programacion.js"></script>
	<script>
		$(document).ready(function() {
			$('.dataTables-example').DataTable({
				pageLength: 100,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: [

				]
			});

			$('#range .input-daterange').datepicker({
				keyboardNavigation: false,
				forceParse: false,
				autoclose: true,
				format: "dd/mm/yyyy"
			});

			$('.select2').select2({ width: '100%' });
		});
	</script>

</body>

</html>