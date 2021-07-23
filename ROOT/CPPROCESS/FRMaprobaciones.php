<?php
	include_once('html_fns_proceso.php');
	validate_login("../");
	$id = $_SESSION["codigo"];
	//var_dump($id);
	//die();
	//$_POST
	$tipo = $_REQUEST["tipo"];
	$situacion = $_REQUEST["situacion"];
	$usuario = $_REQUEST["usuario"];
	//--
	
?>
<!DOCTYPE html>
<html>
<head>
<?php echo head("../"); ?>
</head>
<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../","process"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fas fa-clipboard-check"></i> Listado de Fichas de Procesos a Aprobar</h5>
							</div>
							<div class="card-body all-icons">
								<form name = "f1" id = "f1" action="" method="get">
								<div class="row">
									<div class="col-xs-6 col-md-6 text-left">
										<button type="button" class="btn btn-white" onclick="window.history.back();">
											<i class="fa fa-chevron-left"></i>Atr&aacute;s
										</button>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12" id = "result">
										<?php
											echo tabla_fichas_aprobacion();
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
	
    <script type="text/javascript" src="../assets.1.2.8/js/modules/process/ficha.js"></script>
       
	<script>
		$(document).ready(function(){
			$('.dataTables-example').DataTable({
                pageLength: 100,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    
                ]
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
