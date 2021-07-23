<?php
include_once('html_fns_usuarios.php');
validate_login("../");
$id = $_SESSION["codigo"];
 //$_POST
$codigo = $_REQUEST['codigo'];
$ClsUsu = new ClsUsuario();
$result = $ClsUsu->get_usuario($codigo);
if (is_array($result)) {
	foreach ($result as $row) {
		//nombre
		$nombre = utf8_decode($row["usu_nombre"]);
		//nivel
		$rol_description = utf8_decode($row["rol_nombre"]);
		//mail
		$mail = $row["usu_mail"];
		//telefono
		$tel = $row["usu_telefono"];
		//situacion
		$sit = $row["usu_situacion"];
		$sit = ($sit == 1) ? "ACTIVO" : "INACTIVO";
		//telefono
		$fini = $row["usu_fecha_creacion"];
		$fini = $ClsUsu->cambia_fechaHora($fini);
		//telefono
		$cui = $row["usu_cui"];
		$cui = ($cui == 0) ? "N/A" : $cui;
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
				<div class="card demo-icons">
					<div class="card-header">
						<h5 class="card-title">
							<i class="fa fa-info-circle"></i> &nbsp; Informaci&oacute;n de Usuario
							<button type="button" class="btn btn-white btn-lg sin-margin pull-right" onclick="window.history.back();">
								<small><i class="fa fa-chevron-left" aria-hidden="true"></i> Atr&aacute;s</small>
							</button>
						</h5>
					</div>
					<div class="card-body all-icons">
						<div class="row">
							<div class="col-md-6">
								<label>Nombre del Usuario:</label>
								<span class="form-control"><?php echo $nombre; ?></span>
							</div>
							<div class="col-md-6">
								<label>Tel&eacute;fono:</label>
								<span class="form-control"><?php echo $rol_description; ?></span>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<label>E-mail:</label>
								<span class="form-control"><?php echo $mail; ?></span>
							</div>
							<div class="col-md-6">
								<label>Tel&eacute;fono:</label>
								<span class="form-control"><?php echo $tel; ?></span>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<label>Situaci&oacute;n:</label>
								<span class="form-control"><?php echo $sit; ?></span>
							</div>
							<div class="col-md-6">
								<label>Fecha de Creaci&oacute;n:</label>
								<span class="form-control"><?php echo $fini; ?></span>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12">
								<?php echo tabla_ver_permisos_usu($codigo); ?>
							</div>
						</div>
					</div>
				</div>
				<br>
			</div>
			<?php echo footer() ?>
		</div>
	</div>
	<?php echo modal("../"); ?>
	<?php echo scripts("../"); ?>

	<script type="text/javascript" src="../assets.1.2.8/js/modules/seguridad/usuario.js"></script>
	<script>
		$(document).ready(function() {
			$('.dual_select').bootstrapDualListbox({
				selectorMinimalHeight: 160,
			});

			$("#form").submit(function() {
				asignarCategorias($('[name="duallistbox1[]"]').val());
				return false;
			});

			$('.select2').select2({ width: '100%' });
		});
	</script>

</body>

</html>