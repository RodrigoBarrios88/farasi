<?php
include_once('html_fns_ryo.php');
validate_login("../");
$id = $_SESSION["codigo"];
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
							<div class="card-body all-icons">
								<div class="nav-tabs-navigation">
									<div class="nav-tabs-wrapper">
										<ul id="tabs" class="nav nav-tabs" role="tablist">
											<li class="nav-item">
												<a class="nav-link active" href="../CPRYO/FRMidentificacion.php">
													<h6><i class="fa fa-exclamation-triangle"></i> Riesgos Internos</h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="../CPRYO/FRMexternos.php">
													<h6><i class="fa fa-exclamation-triangle"></i> Riesgos Externos</h6>
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="../CPRYO/FRMoportunidades.php">
													<h6><i class="fa fa-bolt"></i> Oportunidades</h6>
												</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="fa fa-search"></i> Identificaci&oacute;n de Riesgos Internos
								</h5>
							</div>
							<div class="card-body all-icons">
								<form name="f1" id="f1" action="" method="get">
									<div class="col-xs-6 col-md-6 text-left">
										<button type="button" class="btn btn-white" onclick="window.history.back();">
											<i class="fa fa-chevron-left"></i> Atr&aacute;s
										</button>
									</div>
									<div class="row">
										<div class="col-lg-12" id="result">
											<?php echo tabla_foda_asignados($proceso, $sistema, $id, 3); ?>
										</div>
									</div>
								</form>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/ryo/riesgo.js"></script>
	<script>
		$(document).ready(function() {
			$('.dataTables-example').DataTable({
				pageLength: 100,
				responsive: true,
				dom: '<"html5buttons"B>lTfgitp',
				buttons: []
			});
		});
	</script>
</body>

</html>