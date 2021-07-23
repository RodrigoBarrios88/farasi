<?php
	include_once('html_fns_cuestionario.php');
	validate_login("../");
$id = $_SESSION["codigo"];

	//$_POST
	$nombre = $_REQUEST["nom"];
	$zona = $_REQUEST["zona"];
	$dep = $_REQUEST["dep"];
	$dep = ($dep =="")?"100":$dep;
	$mun = $_REQUEST["mun"];
	$mun = ($mun =="")?"101":$mun;
?>
<!DOCTYPE html>
<html>
<head>
<?php echo head("../"); ?>
</head>
<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../","encuestas"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fas fa-mail-bulk"></i> Invitaciones a Llenar Encuestas</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-xs-6 col-md-6 text-left">
										<button type="button" class="btn btn-white" onclick="window.history.back();">
											<i class="fa fa-chevron-left"></i>Atr&aacute;s
										</button>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<?php echo tabla_cuestionarios_invitacion(); ?>
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
	
	
    <script>
		$(document).ready(function(){
			$('.dataTables-example').DataTable({
                pageLength: 100,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: []
            });
			$('.select2').select2();
        });
    </script>

</body>
</html>
