<?php
include_once('html_fns_perfil.php');
validate_login("../");
$id = $_SESSION["codigo"];

$ClsUsu = new ClsUsuario();
$result = $ClsUsu->get_usuario($id);
if (is_array($result)) {
	foreach ($result as $row) {
		$nombres = utf8_decode($row["usu_nombre"]);
		$mail = $row["usu_mail"];
		$tel = $row["usu_telefono"];
	}
}
$foto = $ClsUsu->last_foto_usuario($id);
if (file_exists('../../CONFIG/Fotos/USUARIOS/' . $foto . '.jpg')) {
	$foto = "../../CONFIG/Fotos/USUARIOS/$foto.jpg";
} else {
	$foto = "../../CONFIG/Fotos/nofoto.jpg";
}
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../", "herramientas"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-4">
						<div class="card card-user">
							<div class="image">
								<img id="img-container" src="../../CONFIG/img/background/bg-contact.jpg" alt="...">
							</div>
							<div class="card-body">
								<div class="author">
									<a href="#">
										<img class="avatar border-gray" src="../../CONFIG/Fotos/<?php echo $foto; ?>" alt="...">
										<h5 class="title"><?php echo $nombre; ?></h5>
									</a>
									<p class="description">
									<form action="EXEcarga_foto.php" name="f1" name="f1" method="post" enctype="multipart/form-data">
										<button type="button" class="btn btn-info btn-block" id="btn-cargar" onclick="FotoJs();"><i class="fas fa-camera"></i> Cambiar Fotograf&iacute;a... </button>
										<input id="imagen" name="imagen" type="file" multiple="false" class="hidden" onchange="Cargar();">
										<input type="hidden" name="codigo" id="codigo" value="<?php echo $id; ?>" />
									</form>
									</p>
								</div>
							</div>
							<div class="card-footer">

							</div>
						</div>
					</div>
					<div class="col-md-8">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-user"></i> Perfil de Usuario</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-xs-6 col-md-6 text-left">
										<button type="button" class="btn btn-white" onclick="window.history.back();">
											<i class="fa fa-chevron-left"></i>Atr&aacute;s
										</button>
									</div>
									<div class="col-xs-6 col-md-6 text-right"><label class=" text-danger">* Campos Obligatorios</label> </div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Nombre y Apellido:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="nombre" id="nombre" onkeyup="texto(this);" value="<?php echo $nombres; ?>" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>E-Mail:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="mail" id="mail" onkeyup="texto(this);" value="<?php echo $mail; ?>" />
									</div>
									<div class="col-md-6">
										<label>Telefono:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="telefono" id="telefono" onkeyup="enteros(this);" value="<?php echo $tel; ?>" />
									</div>
								</div>
								<br>
								<hr>
								<div class="row">
									<div class="col-md-12 text-center">
										<button type="button" class="btn btn-white" id="btn-limpiar" onclick="Limpiar();"><i class="fas fa-eraser"></i> Limpiar</button>
										<button type="button" class="btn btn-primary" id="btn-grabar" onclick="ModificarPerfil();"><i class="fas fa-save"></i> Grabar</button>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/perfil/perfil.js"></script>
</body>
</html>