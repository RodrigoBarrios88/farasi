<?php
	include_once('html_fns_sede.php');
	$nombre = utf8_decode($_SESSION["nombre"]);
	$rol = $_SESSION["rol"];
	/// $_POST
	$lat = $_REQUEST["latitud"];
	$long = $_REQUEST["longitud"];
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $_SESSION["cliente_nombre"]; ?></title>
	<meta charset="utf-8">
	<style>
		html, body {
			height: 100%;
			margin: 0;
			padding: 0;
		}
		#map {
			height: 100%;
		}
	</style>
</head>
<body class="pace-done body-small fixed-sidebar">
	<div id="map"></div>
	<script>
		function initMap() {
			var ploteo = {lat: <?php echo $lat; ?>, lng: <?php echo $long; ?>};
			
			var map = new google.maps.Map(document.getElementById('map'), {
				scaleControl: true,
				center: ploteo,
				zoom: 15
			});
			
			var infowindow = new google.maps.InfoWindow;
			
			var marker = new google.maps.Marker({map: map, position: ploteo});
			marker.addListener('click', function() {
				infowindow.open(map, marker);
			});
		}
	</script>
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCTJLYv6tic6CD1KqvrXOwiQKQ7bUcR8gA&callback=initMap"></script>
	</body>
</html>
