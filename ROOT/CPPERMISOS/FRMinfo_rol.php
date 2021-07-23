<?php
	include_once('html_fns_permiso.php');
	validate_login("../");
$id = $_SESSION["codigo"];
	$nombre = utf8_decode($_SESSION["nombre"]);
	$rol = $_SESSION["rol"];
	$rol_nombre = utf8_decode($_SESSION["rol_nombre"]);
	$foto = $_SESSION["foto"];
	//$_POST
	$codigo = $_REQUEST["codigo"];$ClsRol = new ClsRol();
	$result = $ClsRol->get_rol($codigo);
	if(is_array($result)){
		foreach($result as $row){
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
		<?php echo sidebar("../","herramientas"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title">
									<i class="fa fa-info-circle"></i> &nbsp; Detalle de Permisos en el Rol
									<a class="btn btn-white btn-lg sin-margin pull-right" href="FRMnewrol.php"><small><i class="nc-icon nc-simple-add"></i> Nuevo Rol</small></a>
									<a class="btn btn-white btn-lg sin-margin pull-right" href="FRMpermisos.php"><small><i class="nc-icon nc-lock-circle-open"></i> Gestor de Permisos</small></a>
								</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-xs-6 col-md-6 text-left">
										<button type="button" class="btn btn-light btn-icon-split btn-sm" onclick="window.history.back();" >
											<span class="icon text-white-50"><i class="fa fa-chevron-left"></i></span>
											<span class="text">Atr&aacute;s</span>
										</button>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-lg-12 col-xs-12">
										<label>Nombre del Rol: </label>
										<input type = "text" class="form-control" name = "nom" id = "nom" value="<?php echo $nom; ?>" disabled />
										<input type = "hidden" name = "cod" id = "cod" value="<?php echo $codigo; ?>" />
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-xs-12">
										<label>Descripci&oacute;n del Rol: </label>
										<textarea class="form-control" name = "desc" id = "desc" rows="4" disabled ><?php echo $desc; ?></textarea>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-12">
										<?php
											if($codigo != ""){
												echo utf8_decode(tabla_ver_permisos($codigo));
											}else{
												echo '<h5 class="alert alert-warning text-center">';
												echo '<i class="fa fa-warning"></i> El codigo del Rol viene vacio...';
												echo '</h5>';
											}
										?>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-xs-12 text-center">
										<a class="btn btn-secondary btn-sm" href = "FRMrol.php"><i class="fa fa-chevron-left"></i> Regresar</a>
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
	
    <script type="text/javascript" src="../assets.1.2.8/js/modules/seguridad/rol.js"></script>
</body>
</html>
