<?php
	include_once('html_fns_revision.php');
	validate_login("../");
$id = $_SESSION["codigo"];

	//$_POST
	$sede = $_REQUEST["sede"];
	$sector = $_REQUEST["sector"];
	$area = $_REQUEST["area"];
	$categoria = $_REQUEST["categoria"];
	$revision = $_REQUEST["revision"];
	//--
	$desde = $_REQUEST["desde"];
	$hasta = $_REQUEST["hasta"];$ClsCat = new ClsCategoria();
	$result = $ClsCat->get_categoria_checklist($categoria,'',1);
	$categorias_nombre = "";
	if(is_array($result)){
		foreach($result as $row){
			$categorias_nombre.= utf8_decode($row["cat_nombre"]).", ";
		}
		$categorias_nombre = substr($categorias_nombre, 0, -2);
	}
?>
<!DOCTYPE html>
<html>
<head>
    <?php echo head("../"); ?>
</head>
<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("../","checklist"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-question-circle"></i> Reporte de Respuestas (SI, NO y No Aplica)</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12">
										<strong class="text-muted">Reporte Conjunto, categor&iacute;as calculadas: <?php echo $categorias_nombre; ?></strong>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-lg-12">
										<?php
											echo tabla_respuestas($revision,$sede,$sector,$area,$categoria,$desde,$hasta);
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
	
    
    <script type="text/javascript" src="../assets.1.2.8/js/modules/checklist/lista.js"></script></body>
</html>
