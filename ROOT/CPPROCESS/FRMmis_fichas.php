<?php
include_once('html_fns_proceso.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$tipo = $_REQUEST["tipo"];
$situacion = $_REQUEST["situacion"];
$usuario = $_REQUEST["usuario"];
//--
$mes = date("m");
$anio = date("Y");
$desde = $_REQUEST["desde"];
$desde = ($desde == "") ? date("01/01/Y") : $desde; //valida que si no se selecciona fecha, coloque la del dia
$hasta = $_REQUEST["hasta"];
$hasta = ($hasta == "") ? date("d/m/Y") : $hasta; //valida que si no se selecciona fecha, coloque la del dia
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../", "process"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?> <div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="fas fa-clipboard-list"></i> Mis Fichas de Proceso
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12" id="result">
										<?php echo tabla_mis_fichas($codigo, $id); ?>
									</div>
								</div>
								<br>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php echo footer(); ?>
		</div>
	</div>
	<?php echo modal("../"); ?>
	<?php echo scripts("../"); ?>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/process/ficha.js"></script>
	<script>
		var tr = document.getElementsByClassName('files_in_aprobation_pares');
		console.log(tr);	
		$(document).ready(function() {
			$('.dataTables-example').DataTable({
				pageLength: 100,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: []
			});
			$('.select2').select2({ width: '100%' });
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