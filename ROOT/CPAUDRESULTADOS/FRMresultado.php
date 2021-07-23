<?php
include_once('html_fns_resultados.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$sede = $_REQUEST["sede"];
$departamento = $_REQUEST["departamento"];
$categoria = $_REQUEST["categoria"];
$periodo = $_REQUEST["periodo"];
$situacion = $_REQUEST["situacion"];
//--
$desde = $_REQUEST["desde"];
$hasta = $_REQUEST["hasta"];
if ($periodo == "D") {
	$titulo = "Reporte d&iacute;a a d&iacute;a del $desde al $hasta";
} else if ($periodo == "S") {
	$titulo = "Reporte semana a semana del $desde al $hasta";
} else if ($periodo == "M") {
	$titulo = "Reporte mes a mes del $desde al $hasta";
}
$ClsCat = new ClsCategoria();
$result = $ClsCat->get_categoria_auditoria($categoria, '', 1);
$categorias_nombre = "";
if (is_array($result)) {
	foreach ($result as $row) {
		$categorias_nombre .= utf8_decode($row["cat_nombre"]) . ", ";
	}
	$categorias_nombre = substr($categorias_nombre, 0, -2);
}?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("../", "auditoria"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-calendar"></i> Reporte Peri&oacute;dico de Resultados</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12">
										<h5 class="alert alert-success"><?php echo $titulo; ?></h5>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<strong class="text-muted">Reporte Conjunto, categor&iacute;as calculadas: <?php echo $categorias_nombre; ?></strong>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-lg-12">
										<?php
										echo tabla_resultados($periodo, $sede, $departamento, $categoria, $situacion, $desde, $hasta);
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


	<script type="text/javascript" src="../assets.1.2.8/js/modules/auditoria/ejecucion.js"></script>
</body>
</html>