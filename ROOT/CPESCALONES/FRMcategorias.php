<?php
include_once('html_fns_escalones.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../", "helpdesk"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-tags"></i> Categor&iacute;as de Problem Sweeper</h5>
							</div>
							<div class="card-body all-icons">
								<?php
								$ClsCat = new ClsCategoria();
								$result = $ClsCat->get_categoria_helpdesk('', '', 1);
								if (is_array($result)) {
									$i = 1;
									foreach ($result as $row) {
										$codigo = trim($row["cat_codigo"]);
										$categoria = utf8_decode($row["cat_nombre"]);
										$color = trim($row["cat_color"]);
								?>
										<div class="card card-plain p-2">
											<div class="card-body" role="tab">
												<a href="FRMescalones.php?categoria=<?php echo $codigo; ?>">
													<?php echo "$i. $categoria"; ?>
													<!--span class="fa fa-pencil"></span-->
													<button type="button" class="btn btn-white pull-right" style="color:<?php echo $color; ?>">Seleccionar <i class="nc-icon nc-minimal-right"></i></button>
												</a>
											</div>
										</div>
								<?php
										$i++;
									}
								}
								?>
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
	<!-- asny Bootstrap -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/4.0.0/js/jasny-bootstrap.min.js"></script>

	<script type="text/javascript" src="../assets.1.2.8/js/modules/helpdesk/escalon.js"></script>

</body>

</html>