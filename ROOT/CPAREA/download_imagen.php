<?php
$codigo = $_REQUEST["codigo"];
$imagen = file_get_contents("https://chart.googleapis.com/chart?cht=qr&chl=$codigo&chs=250x250&chld=L|0");
header ("Content-Disposition: attachment; filename=QR$codigo.png");
header ("Content-type: image/png");
header ("Content-Length: ".filesize($imagen));
readfile($imagen);?>
