<?php
include_once('html_fns_permiso.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$codigo = $_REQUEST["codigo"];
$ClsRol = new ClsRol();
$result = $ClsRol->get_rol($codigo);
if (is_array($result)) {
	foreach ($result as $row) {
		//nombre
		$nom = utf8_decode($row["rol_nombre"]);
		//descripcion
		$desc = utf8_decode($row["rol_desc"]);
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
		<?php echo sidebar("../", "herramientas"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="fa fa-edit"></i> Actualizaci&oacute;n de Rol de Permisos
									<a class="btn btn-white btn-lg sin-margin pull-right" href="FRMrol.php"><small><i class="nc-icon nc-minimal-left"></i> Regresar</small></a>
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-lg-12 col-xs-12">
										<label>Nombre del Rol: </label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="nombre" id="nombre" onkeyup="texto(this);" value="<?php echo $nom; ?>" />
										<input type="hidden" name="codigo" id="codigo" value="<?php echo $codigo; ?>" />
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-xs-12">
										<label>Descripci&oacute;n del Rol: </label> <span class="text-danger">*</span>
										<textarea type="text" class="form-control" name="descripcion" id="descripcion" onkeyup="textoLargo(this);" rows="5"><?php echo $desc; ?></textarea>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-12">
										<?php
										if ($codigo != "") {
											echo tabla_permisos_editar($codigo);
										} else {
											echo '<h5 class="alert alert-warning text-center">';
											echo '<i class="fa fa-warning"></i> El codigo del Rol viene vacio...';
											echo '</h5>';
										}
										?>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-xs-12 text-center">
										<a class="btn btn-white" href="FRMrol.php"><i class="fa fa-chevron-left"></i> Regresar</a>
										<button type="button" class="btn btn-primary" id="btn-modificar" onclick="Modificar();"><i class="fa fa-save"></i> Grabar</button>
									</div>
								</div>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/seguridad/rol.js"></script>
</body>
</html>