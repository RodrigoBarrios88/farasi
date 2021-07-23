<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$usuario = $_SESSION["codigo"];

$categoriasIn = $_SESSION["categorias_in"];
//$_POST
$ClsAud = new ClsAuditoria();
$ClsEje = new ClsEjecucion();
$programacion = $_REQUEST["codigo"];
$firma = $_REQUEST["firma"];

?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
	<script>
		function snapshot() {
			loadingButton(document.getElementById("snapshot"));
			//Generate the image file
			canvas = document.getElementById('canvas');
			var dataurl = canvas.toDataURL("image/jpeg", 1.0);
			var blob = dataURLtoBlob(dataurl);
			var f1 = new FormData();
			f1.append("codigo", "<?php echo $programacion; ?>");
			f1.append("firma", "<?php echo $firma; ?>");
			f1.append("imagen", blob);
			var request = new XMLHttpRequest();
			request.open("POST", "EXEfirma.php");
			request.send(f1);

			request.onreadystatechange = function() {
				if (request.readyState != 4) return;
				//alert(request.status);
				if (request.status === 200) {
					//alert("Status: " + request.status + " | Respuesta: " + request.responseText);
					//console.log(request.responseText);
					resultado = JSON.parse(request.responseText);
					//alert(resultado.status + ", " + resultado.message + ", " + resultado.img);
					//console.log(resultado);
					if (resultado.status !== 1) {
						swal("Error en la carga", resultado.message, "error");
						return;
					}
					//regresa
					window.history.back();
				} else {
					//alert("Error: " + request.status + " " + request.responseText);
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
		<?php echo sidebar("../", "ppm"); ?>
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
	<?php echo modal("../"); ?>
	<?php echo scripts("../"); ?>
	<!-- Canvas -->
	<script src="../assets.1.2.8/js/plugins/canvas/canvas.js"></script>
	<link href="../assets.1.2.8/css/plugins/canvas/canvas.css" rel="stylesheet">
	<script type="text/javascript" src="../assets.1.2.8/js/modules/auditoria/ejecucion.js"></script>

</body>

</html>
