<?php
	include_once('html_fns_sede.php');
	$nombre = utf8_decode($_SESSION["nombre"]);
	$rol = $_SESSION["rol"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"><title><?php echo $_SESSION["cliente_nombre"]; ?></title>
	<link rel="shortcut icon" href="../../CONFIG/img/icono.png">
	<!-- Bootstrap -->
	<link href="../assets.1.2.8/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://kit.fontawesome.com/907a027ade.js" crossorigin="anonymous"></script>
	<!--MAPA -->
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCTJLYv6tic6CD1KqvrXOwiQKQ7bUcR8gA&callback=initialize"></script>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/sedes/mapa_plot.js"></script>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/sedes/sede.js"></script>
	<style>
		#mapCanvas {
			height: 600px;
			float: center;
		}
	</style>
	
</head>
<body class="pace-done body-small fixed-sidebar">
   <div id="row">
		<form name="form_mapa" method="POST" enctype="multipart/form-data">
			<input type = "hidden" name = "latitud" id = "latitud" value="14.595810882805258" />
			<input type = "hidden" name = "longitud" id = "longitud" value="-90.51987960302733" />
			<input type = "hidden" name="direccion" id="direccion"/>
		</form>
		<div class="row m-1">
			<div class="col-md-12 text-center">
				<button type="button" class="btn btn-primary" onclick = "initialize();"><span class="fa fa-refresh"></span> Inicializar  <span class="fa fa-globe"></span></button> &nbsp;
				<button type="button" class="btn btn-success" onclick = "AceptarCoordenadas();"><span class="fa fa-globe"></span> Aceptar <span class="fa fa-check"></span></button>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12" id="mapCanvas"></div>
	</div>
</body>
</html>
