<?php
include_once('xajax_funct_ticket.php');
validate_login("../");
$id = $_SESSION["codigo"];
$nombre = utf8_decode($_SESSION["nombre"]);
$rol = $_SESSION["rol"];
$rol_nombre = utf8_decode($_SESSION["rol_nombre"]);
$foto = $_SESSION["foto"];
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../../"); ?>
</head>

<body class="">
	<div class="wrapper ">

		<?php
		// obtenemos los datos del archivo
		// Fecha del Sistema
		$tamano = $_FILES["doc"]['size'];
		$tipo = $_FILES["doc"]['type'];
		$archivo = $_FILES["doc"]['name'];
		$ticket = $_REQUEST["ticket"];
		$status = $_REQUEST["posicion"]; // posucion en el flujo del tramite del ticket (1->Foto inicial, 2->Foto Final)
		$comentario = $_REQUEST["comentario"];
		$comentario = depurador_texto($comentario);
		$sms = $_REQUEST["sms"];
		$ClsSta = new ClsStatus();
		$result = $ClsSta->get_status_hd($status, '', '', 1);
		if (is_array($result)) {
			foreach ($result as $row) {
				$posicion = trim($row["sta_posicion"]);
				$status_nombre = trim($row["sta_nombre"]);
			}
		}	// Upload
		if ($archivo != "") {
			$ClsTic = new ClsTicket();
			$stringFoto = str_shuffle($ticket . uniqid());
			$codigo = $ClsTic->max_foto();
			$codigo++;
			$sql = $ClsTic->insert_foto($codigo, $ticket, $posicion, $stringFoto);
			/////// CAMBIO DE STATUS
			$sql .= $ClsTic->cambia_sit_ticket($ticket, $status);
			$sql .= $ClsTic->insert_ticket_status($ticket, $status, $status_nombre); /// Inserta Ticket
			$bitcod = $ClsTic->max_bitacora($ticket);
			$bitcod++;
			$sql .= $ClsTic->insert_bitacora($bitcod, $ticket, "Cambio de Status ($status_nombre)", $comentario); /// Inserta Ticket
			if ($status == 100) { ///// CERRAR TICKET
				$sql .= $ClsTic->cerrar_ticket($ticket);
			}
			//echo $sql."<br>";
			$rs = $ClsTic->exec_sql($sql);
			//echo $rs;
			if ($rs == 1) {
				// guardamos el archivo a la carpeta files
				$destino =  "../../CONFIG/Fotos/TICKET/" . $stringFoto . ".jpg";
				if (move_uploaded_file($_FILES['doc']['tmp_name'], $destino)) {
					$msj = "Imagen $archivo subida exitosamente...!";
					$status = 1;
					//////////// -------- Convierte todas las imagenes a JPEG
					// Abrimos una Imagen PNG
					//$mime_type = mime_content_type($destino);
					//Valida si es un PNG
					if ($mime_type == "image/png") {
						$imagen = imagecreatefrompng($destino); // si es, convierte a JPG
						imagejpeg($imagen, $destino, 100); // Creamos la Imagen JPG a partir de la PNG u otra que venga
					}
					/// redimensionando
					$image = new ImageResize($destino);
					$image->resizeToWidth(300);
					$image->save($destino);
					///
					if ($posicion == 1) {
						if ($sms == 1) {
							$pagina = "../SMS/EXEasigna.php?ticket=$ticket";
						} else {
							$pagina = "FRMtickets.php";
						}
					} else {
						$pagina = "FRMtramite.php?codigo=$ticket";
					}
				} else {
					$msj = "Error al subir el archivo";
					$status = 0;
				}
			} else {
				$msj = "Error al registrar el archivo en la BD";
				$status = 0;
			}
			//echo $sql;
		} else {
			$msj = "Archivo vacio.";
			$status = 0;
		}

		?>

		<script type='text/javascript'>
			function mensaje(status) {
				var msj = '<?php echo $msj; ?>';
				//-----
				if (status === 1) {
					swal("Excelete!", "<?php echo $msj; ?>", "success").then((value) => {
						window.location.href = "<?php echo $pagina; ?>";
					});
				} else {
					errorFoto("<?php echo $msj; ?>");
				}
			}

			window.setTimeout('mensaje(<?php echo $status; ?>);', 500);
		</script>
	</div>


	<?php echo scripts("../") ?>



	<script type="text/javascript" src="../assets.1.2.8/js/modules/helpdesk/ticket.js?v1=1"></script>
</body>

</html>