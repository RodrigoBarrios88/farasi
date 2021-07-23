<?php
include_once('html_fns_acta.php');
validate_login("../");
$id = $_SESSION["codigo"];

$categoriasIn = $_SESSION["categorias_in"];
if ($id == "") {
	echo "<form id='f1' name='f1' action='../logout.php' method='post'>";
	echo "<script>document.f1.submit();</script>";
	echo "</form>";
}
//$_POST
$ClsAud = new ClsAuditoria();
$hashkey1 = $_REQUEST["hashkey1"];
$programacion = $ClsAud->decrypt($hashkey1, $id);
$hashkey2 = $_REQUEST["hashkey2"];
$usuario = $ClsAud->decrypt($hashkey2, $id);
$ClsAud = new ClsAuditoria();
$result = $ClsAud->get_usuario_programacion($programacion, $usuario);
if (is_array($result)) {
	foreach ($result as $row) {
		//Usuario
		$usuario_nombre = utf8_decode($row["pus_tratamiento"]) . " " . utf8_decode($row["usu_nombre"]);
		//Rol
		$rol_usuario = utf8_decode($row["pus_rol"]);
		//Asignacion
		$asignacion = utf8_decode($row["pus_asignacion"]);
	}
}?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
	<script>
		function snapshot() {
			var boton = document.getElementById("snapshot");
			loadingBtn(boton);
			//Generate the image file
			canvas = document.getElementById('canvas');
			var dataurl = canvas.toDataURL("image/jpeg", 1.0);
			var blob = dataURLtoBlob(dataurl);
			var http = new FormData();
			http.append("programacion", "<?php echo $programacion; ?>");
			http.append("usuario", "<?php echo $usuario; ?>");
			http.append("imagen", blob);
			var request = new XMLHttpRequest();
			request.open("POST", "EXEfirma.php");
			request.send(http);

			request.onreadystatechange = function() {
				if (request.readyState != 4) return;
				if (request.status === 200) {
					//console.log(request.responseText);
					resultado = JSON.parse(request.responseText);
					//console.log(resultado);
					if (resultado.status !== true) {
						swal("Error en la carga", resultado.message, "error").then((value) => {
							deloadingBtn(boton, '<i class="fas fa-save"></i> Grabar');
						});
						return;
					}
					//regresa
					window.history.back();
				} else {
					swal("Error en la carga", "Error en la carga de la imagen...", "error");
					return;
				}
			};

		}

		function dataURLtoBlob(dataurl) {
			var arr = dataurl.split(','),
				mime = arr[0].match(/:(.*?);/)[1],
				bstr = atob(arr[1]),
				n = bstr.length,
				u8arr = new Uint8Array(n);
			while (n--) {
				u8arr[n] = bstr.charCodeAt(n);
			}
			return new Blob([u8arr], {
				type: mime
			});
		}
	</script>
</head>

<body onload="startup();" class="">
	<div class="wrapper ">
		<?php echo sidebar("../", "auditoria"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-body all-icons text-center">
								<br>
								<button type="button" class="btn btn-default btn-lg" onclick="window.history.back();"><span class="fa fa-chevron-left"></span> Regresar</button>
								<br>
								<div class="row">
									<div class="col-md-4 ml-auto mr-auto text-center">
										<strong><?php echo $usuario_nombre; ?></strong>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 ml-auto mr-auto text-center">
										<label><?php echo $rol_usuario; ?></label>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 ml-auto mr-auto text-center">
										<canvas id="canvas">
											Nescesita usar otro navegador web...
										</canvas>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4 ml-auto mr-auto text-center">
										<button type="button" class="btn btn-primary btn-block btn-lg" id="snapshot" onclick="snapshot();"><i class="fas fa-save"></i> Grabar</button>
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
	<?php echo modal() ?>
	<?php echo scripts("../"); ?>

	<!-- Canvas -->
	<script src="../assets.1.2.8/js/plugins/canvas/canvas.js"></script>
	<link href="../assets.1.2.8/css/plugins/canvas/canvas.css" rel="stylesheet">
	<script type="text/javascript" src="../assets.1.2.8/js/modules/auditoria/acta.js"></script>

</body>
</html>