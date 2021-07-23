<?php
include_once('html_fns.php');
validate_login();
$id = $_SESSION["codigo"];

//--
//$_POST
$encuesta = $_REQUEST["encuesta"];
$categoria = $_REQUEST["categoria"];
$anio = date("Y");
$desde = $_REQUEST["desde"];
$desde = ($desde == "") ? "01/01/" . date("Y") : $desde; //valida que si no se selecciona fecha, coloque la del dia
$hasta = $_REQUEST["hasta"];
$hasta = ($hasta == "") ? "31/12/" . date("Y") : $hasta; //valida que si no se selecciona fecha, coloque la del dia?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head(); ?>
	<!-- Estilo especifico -->
	<link href="assets.1.2.8/css/propios/dashboard.css" rel="stylesheet">
</head>

<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("", "encuestas"); ?>
		<div class="main-panel">
			<?php echo navbar(); ?>
			<div class="content">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<div class="card">
							<div class="card-header ">
								<h4 class="card-title"><i class="fas fa-list-ol"></i> M&oacute;dulo de Encuestas</h4>
								<h5 class="card-category">Categor&iacute;as - Horarios</h5>
							</div>
							<div class="card-body">

							</div>
							<div class="card-footer text-right">
								<hr>
								<div class="stats">
									<i class="fa fa-clock-o"></i> <?php echo date("d/m/Y H:i"); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php echo footer() ?>
		</div>
	</div>
	<?php echo modal() ?>
	<?php echo scripts() ?>
	<script type="text/javascript" src="assets.1.2.8/js/modules/menu/menu_checklist.js"></script>
</body>
</html>