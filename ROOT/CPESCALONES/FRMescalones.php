<?php
	include_once('html_fns_escalones.php');
	validate_login("../");
$id = $_SESSION["codigo"];

	//$_POST
	$categoria = $_REQUEST["categoria"];
	$ClsCat = new ClsCategoria();
	$result = $ClsCat->get_categoria_helpdesk($categoria,'',1);
	if(is_array($result)){
		$i = 1;
		foreach($result as $row){
			$categoria = trim($row["cat_codigo"]);
			$categoria_nombre = utf8_decode($row["cat_nombre"]);
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
   <?php echo head("../"); ?>
</head>
<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../","helpdesk"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-tags"></i> Categor&iacute;a: <?php echo $categoria_nombre; ?></h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-xs-6 col-md-6 text-left">
										<button type="button" class="btn btn-white" onclick="window.history.back();"><i class="fa fa-chevron-left"></i> Atr&aacute;s</button>
										<button type="button" class="btn btn-white" onclick="Limpiar();"><i class="fa fa-eraser"></i> Limpiar</button>
										<button type="button" class="btn btn-primary" onclick="masEscalones(<?php echo $categoria; ?>);"><i class="fa fa-plus"></i> Agregar</button>
									</div>
									<div class="col-xs-6 col-md-6 text-right"><label class = " text-danger">* Campos Obligatorios</label> </div>
								</div>
								<div id="accordion" role="tablist" aria-multiselectable="true" class="card-collapse">
									<?php echo utf8_decode(edit_escalon($escalon,$categoria)); ?>
								</div>
								<br><br>
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
