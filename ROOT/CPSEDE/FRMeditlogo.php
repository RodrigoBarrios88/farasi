<?php
include_once('html_fns_sede.php');
validate_login("../");
$id = $_SESSION["codigo"];
$nombre = utf8_decode($_SESSION["nombre"]);
$rol = $_SESSION["rol"];
$rol_nombre = utf8_decode($_SESSION["rol_nombre"]); //$_POST
$codigo = $_REQUEST["codigo"];
$ClsSed = new ClsSede();
$result = $ClsSed->get_sede($codigo);
if (is_array($result)) {
	foreach ($result as $row) {
		$codigo = Agrega_Ceros($row["sed_codigo"]);
		$nombre = utf8_decode($row["sed_nombre"]);
	}
}
$logo = $ClsSed->last_foto_sede($codigo);
if ($logo != "") {
	$logo = "../../CONFIG/Fotos/SEDES/$logo.jpg";
} else {
	$logo = "../../CONFIG/img/logo.jpg";
}
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="">
	<div class="wrapper ">
		<div class="sidebar" data-color="brown" data-active-color="danger">
			<div class="logo">
				<a href="../menu.php" class="simple-text logo-mini">
					<div class="logo-image-small">
						<img src="../../CONFIG/img/logo2.png" />
					</div>
			</div>
			<div class="sidebar-wrapper">
				<div class="user">
					<div class="photo">
						<img src="../../CONFIG/Fotos/<?php echo $foto; ?>" />
					</div>
					<div class="info">
						<a data-toggle="collapse" href="#collapseExample" class="collapsed">
							<span>
								<?php echo $nombre_sesion; ?>
								<b class="caret"></b>
							</span>
						</a>
						<div class="clearfix"></div>
						<div class="collapse" id="collapseExample">
							<ul class="nav">
								<li>
									<a href="../CPPERFIL/FRMperfil.php">
										<span class="sidebar-mini-icon"><i class="nc-icon nc-single-02"></i></span>
										<span class="sidebar-normal">Perfil</span>
									</a>
								</li>
								<li>
									<a href="../CPPERFIL/FRMpassword.php">
										<span class="sidebar-mini-icon"><i class="nc-icon nc-lock-circle-open"></i></span>
										<span class="sidebar-normal">Contrase&ntilde;a</span>
									</a>
								</li>
								<li>
									<a href="../CPPERFIL/FRMajustes.php">
										<span class="sidebar-mini-icon"><i class="fa fa-cog"></i></span>
										<span class="sidebar-normal">Ajustes</span>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<ul class="nav">
					<li>
						<a href="../menu.php">
							<i class="nc-icon nc-layout-11"></i>
							<p>Men&uacute;</p>
						</a>
					</li>
					<?php if ($_SESSION["GRP_GPADMIN"] == 1) { ?>
						<li>
							<a data-toggle="collapse" href="#administracio">
								<i class="fa fa-users-cog"></i>
								<p>
									Administraci&oacute;n
									<b class="caret"></b>
								</p>
							</a>
							<div class="collapse " id="administracio">
								<ul class="nav">
									<?php if ($_SESSION["GUSU"] == 1) { ?>
										<li>
											<a href="../CPUSUARIOS/FRMusuarios.php">
												<span class="sidebar-mini-icon"><i class="fa fa-user"></i></span>
												<span class="sidebar-normal"> Gestor de Usuarios </span>
											</a>
										</li>
									<?php } ?>
									<?php if ($_SESSION["GPERM"] == 1) { ?>
										<li>
											<a href="../CPUSUARIOS/FRMasignacion_rol.php">
												<span class="sidebar-mini-icon"><i class="fa fa-key"></i></span>
												<span class="sidebar-normal"> Administrador de Permisos </span>
											</a>
										</li>
									<?php } ?>
									<?php if ($_SESSION["USUSED"] == 1) { ?>
										<li>
											<a href="../CPUSUARIOS/FRMusuario_sede.php">
												<span class="sidebar-mini-icon"><i class="fa fa-bank"></i></span>
												<span class="sidebar-normal"> Usuarios / Sedes </span>
											</a>
										</li>
									<?php } ?>
									<?php if ($_SESSION["USUCAT"] == 1) { ?>
										<li>
											<a href="../CPUSUARIOS/FRMusuario_categoria.php">
												<span class="sidebar-mini-icon"><i class="fa fa-tags"></i></span>
												<span class="sidebar-normal"> Usuarios / Categor&iacute;as </span>
											</a>
										</li>
									<?php } ?>
									<?php if ($_SESSION["GPERM"] == 1) { ?>
										<li>
											<a href="../CPVERSION/FRMversion.php">
												<span class="sidebar-mini-icon"><i class="nc-icon nc-mobile"></i></span>
												<span class="sidebar-normal"> Admin. Versiones</span>
											</a>
										</li>
									<?php } ?>
								</ul>
							</div>
						</li>
					<?php } ?>
					<?php if ($_SESSION["GRP_GESTEC"] == 1) { ?>
						<li class="active">
							<a data-toggle="collapse" href="#gestores">
								<i class="fa fa-cogs"></i>
								<p>
									Gestores T&eacute;cnicos
									<b class="caret"></b>
								</p>
							</a>
							<div class="collapse " id="gestores">
								<ul class="nav">
									<?php if ($_SESSION["GESSED"] == 1) { ?>
										<li class="active">
											<a href="../CPSEDE/FRMsede.php">
												<span class="sidebar-mini-icon"><i class="fa fa-bank"></i></span>
												<span class="sidebar-normal"> Gestor de Sedes </span>
											</a>
										</li>
									<?php } ?>
									<?php if ($_SESSION["GESTA"] == 1) { ?>
										<li>
											<a href="../CPSECTOR/FRMsector.php">
												<span class="sidebar-mini-icon"><i class="fa fa-building-o"></i></span>
												<span class="sidebar-normal"> Gestor de Sector | Torres </span>
											</a>
										</li>
									<?php } ?>
									<?php if ($_SESSION["GETAR"] == 1) { ?>
										<li>
											<a href="../CPAREA/FRMarea.php">
												<span class="sidebar-mini-icon"><i class="fa fa-cube"></i></span>
												<span class="sidebar-normal"> Gestor de &Aacute;reas </span>
											</a>
										</li>
									<?php } ?>
									<?php if ($_SESSION["GETAR"] == 1) { ?>
										<li>
											<a href="../CPDEPARTAMENTO/FRMdepartamento.php">
												<span class="sidebar-mini-icon"><i class="fa fa-building-o"></i></span>
												<span class="sidebar-normal"> Gestor de Departamento </span>
											</a>
										</li>
									<?php } ?>
									<?php if ($_SESSION["CATCHK"] == 1) { ?>
										<li>
											<a href="../CPCATEGORIA/FRMcategoria_checklist.php">
												<span class="sidebar-mini-icon"><i class="fa fa-tags"></i></span>
												<span class="sidebar-normal"> Categor&iacute;as (Check List) </span>
											</a>
										</li>
									<?php } ?>
									<?php if ($_SESSION["CATHD"] == 1) { ?>
										<li>
											<a href="../CPCATEGORIA/FRMcategoria_helpdesk.php">
												<span class="sidebar-mini-icon"><i class="fa fa-tags"></i></span>
												<span class="sidebar-normal"> Categor&iacute;as (Sweeper) </span>
											</a>
										</li>
									<?php } ?>
									<?php if ($_SESSION["CATAUDIT"] == 1) { ?>
										<li>
											<a href="../CPCATEGORIA/FRMcategoria_auditoria.php">
												<span class="sidebar-mini-icon"><i class="fa fa-tags"></i></span>
												<span class="sidebar-normal"> Categor&iacute;as (Audit Active) </span>
											</a>
										</li>
									<?php } ?>
									<?php if ($_SESSION["CATPPM"] == 1) { ?>
										<li>
											<a href="../CPCATEGORIA/FRMcategoria_ppm.php">
												<span class="sidebar-mini-icon"><i class="fa fa-tags"></i></span>
												<span class="sidebar-normal"> Categor&iacute;as (Mant. Planner) </span>
											</a>
										</li>
									<?php } ?>
									<?php if ($_SESSION["GENQR"] == 1) { ?>
										<li>
											<a href="../CPAREA/FRMqrcode.php">
												<span class="sidebar-mini-icon"><i class="fa fa-qrcode"></i></span>
												<span class="sidebar-normal"> Impresi&oacute;n de QR </span>
											</a>
										</li>
									<?php } ?>
								</ul>
							</div>
						</li>
					<?php } ?>
					<?php if ($_SESSION["GRP_CKLIST"] == 1) { ?>
						<li>
							<a href="../menu_checklist.php">
								<i class="fa fa-check-square-o"></i>
								<p>Check List</p>
							</a>
						</li>
					<?php } ?>
					<?php if ($_SESSION["GRP_HELPDESK"] == 1) { ?>
						<li>
							<a href="../menu.php">
								<i class="fas fa-toolbox"></i>
								<p>Problem Sweeper</p>
							</a>
						</li>
					<?php } ?>
					<?php if ($_SESSION["GRP_PPM"] == 1) { ?>
						<li>
							<a href="../menu_ppm.php">
								<i class="fa fa-tools"></i>
								<p>Maintenance Planner</p>
							</a>
						</li>
					<?php } ?>
					<?php if ($_SESSION["GRP_AUDIT"] == 1) { ?>
						<li>
							<a href="../menu_auditoria.php">
								<i class="fas fa-clipboard-list"></i>
								<p>Auditor&iacute;a</p>
							</a>
						</li>
					<?php } ?>
					<hr>
					<li>
						<a href="../logout.php">
							<i class="fa fa-power-off"></i>
							<p>Salir</p>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-image"></i> Redimensi&oacute;n de la imagen</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-6 col-xs-12">
										<div class="image-crop">
											<img src="<?php echo $logo; ?>">
										</div>
									</div>
									<div class="col-md-5 col-xs-12 text-center">
										<h4>Previsualizaci&oacute;n de la Imagen</h4>
										<div class="img-preview img-preview-sm"></div>
										<br>
										<label title="Download image" id="download" class="btn btn-defult btn-block text-light">
											<i class="fa fa-save"></i> &nbsp;
											Guardar Imagen
										</label>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-md-12 text-center">
										<h5><i class="fa fa-wrench"></i> Herramientas de Edici&oacute;n</h5>
										<div class="btn-group">
											<button class="btn btn-white" id="zoomIn" type="button"><i class="fa fa-search-plus"></i> Zoom</button>
											<button class="btn btn-white" id="zoomOut" type="button"><i class="fa fa-search-minus"></i> Zoom</button>
											<button class="btn btn-white" id="rotateLeft" type="button"><i class="fa fa-rotate-left"></i> Rotar a la Izquierda</button>
											<button class="btn btn-white" id="rotateRight" type="button"><i class="fa fa-rotate-right"></i> Rotar a la Derecha</button>
										</div>
									</div>
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
	<!-- Image cropper -->
	<script src="../assets.1.2.8/js/plugins/cropper/cropper.min.js"></script>
	<script type="text/javascript" src="../assets.1.2.8/js/modules/perfil/perfil.js"></script>
	<script>
		$(document).ready(function() {
			var $image = $(".image-crop > img")
			$($image).cropper({
				aspectRatio: 1.1,
				preview: ".img-preview",
				done: function(data) {
					// Output the result data for cropping image.
				}
			});

			var $inputImage = $("#inputImage");
			if (window.FileReader) {
				$inputImage.change(function() {
					var fileReader = new FileReader(),
						files = this.files,
						file;

					if (!files.length) {
						return;
					}

					file = files[0];

					if (/^image\/\w+$/.test(file.type)) {
						fileReader.readAsDataURL(file);
						fileReader.onload = function() {
							$inputImage.val("");
							$image.cropper("reset", true).cropper("replace", this.result);
						};
					} else {
						showMessage("Please choose an image file.");
					}
				});
			} else {
				$inputImage.addClass("hide");
			}

			$("#download").click(function() {
				abrir();
				var dataurl = $image.cropper("getDataURL");
				var blob = dataURLtoBlob(dataurl);
				var f1 = new FormData();
				f1.append("persona", "<?php echo $persona; ?>");
				f1.append("cui", "<?php echo $cui; ?>");
				f1.append("imagen", blob);
				var request = new XMLHttpRequest();
				request.open("POST", "EXEeditlogo.php");
				request.send(f1);
				request.onreadystatechange = function() {
					if (request.readyState != 4) return;
					//alert(request.status);
					if (request.status === 200) {
						resultado = JSON.parse(request.responseText);
						console.log(resultado);
						if (resultado.status !== true) {
							swal("Error en la carga", resultado.message, "error");
							return;
						}
						swal("Excelente!", resultado.message, "success").then((value) => {
							window.location.href = "FRMsede.php";
						});
					} else {
						//alert("Error: " + request.status + " " + request.responseText);
						swal("Error en la carga", "Error en la carga de la imagen", "error");
						return;
					}
				};
			});

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

			function redirige() {
				cerrar();
				swal("Excelete!", "La imagen fue editada satisfactoriamente...", "success").then((value) => {
					window.location.href = 'FRMperfil.php';
				});
			}

			$("#zoomIn").click(function() {
				$image.cropper("zoom", 0.1);
			});

			$("#zoomOut").click(function() {
				$image.cropper("zoom", -0.1);
			});

			$("#rotateLeft").click(function() {
				$image.cropper("rotate", 45);
			});

			$("#rotateRight").click(function() {
				$image.cropper("rotate", -45);
			});

			$("#setDrag").click(function() {
				$image.cropper("setDragMode", "crop");
			});
		});
	</script>

</body>

</html>