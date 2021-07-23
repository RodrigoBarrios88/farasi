<?php
include_once('html_fns_mejora.php');
validate_login("../");
$id = $_SESSION["codigo"];
//$_POST
$ClsRie = new ClsAuditoria();
$hashkey = $_REQUEST["hashkey"];
///informacion auditoria
$codigo = $ClsRie->decrypt($hashkey, $id);
$info = $ClsRie->get_externa_detalle($codigo); 
if (is_array($info)) {
	foreach ($info as $row) {
		$tipo = get_tipo_auditoria($row["ext_tipo"]);
		$entidad = utf8_decode($row["ext_entidad"]);
		$objetivo = utf8_decode($row["ext_objetivo"]);
		$resumen = utf8_decode($row["ext_resumen"]);
		$fecha_auditoria = cambia_fecha($row["ext_fecha_auditoria"]);
		$usuario_registra = utf8_decode($row["usu_nombre"]);
		$fecha_registro = cambia_fecha($row["ext_fecha_registro"]);
		$descripcion = trim($row["dext_descripcion"]);        
		////detalle auditoria externa
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
    <?php echo sidebar("../", "mejora"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content"    >
				<fieldset disabled>
					<div class="row">
						<div class="col-md-12">
							<div class="card demo-icons">
								<div class="card-header">
									<h5 class="card-title">
										<i class="nc-icon nc-bullet-list-67"></i> Informaci&oacute;n
									</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-lg-12">
											<div class="row">
												<div class="col-md-6">
													<label>Tipo:</label>
													<input type="text" class="form-control" value="<?=$tipo?>" />
												</div>
                                                <div class="col-md-6">
													<label>Entidad:</label>
													<input type="text" class="form-control" value="<?=$entidad?>" />
												</div>
											</div>
                                            <div class="row">
												<div class="col-md-6">
													<label>Fecha Auditoria:</label>
													<input type="text" class="form-control" value="<?=$fecha_auditoria?>" />
												</div>
                                                <div class="col-md-6">
													<label>Usuario:</label>
													<input type="text" class="form-control" value="<?=$usuario_registra?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Objetivo:</label>
													<input type="text" class="form-control" value="<?=$objetivo?>" />
												</div>
                                                
											</div>
                                            <div class="row">
												<div class="col-md-12">
													<label>Resumen:</label>
													<input type="text" class="form-control" value="<?=$resumen?>" />
												</div>
											</div>
                
                                        
										</div>
									</div>
									<br>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="card demo-icons">
								<div class="card-header">
									<h5 class="card-title">
										<i class="fa fa-check-square-o"></i> Detalle Auditoria Externa
									</h5>
								</div>
								<div class="card-body all-icons">
									<div class="row">
										<div class="col-lg-12">
											
											<div class="row">
												<div class="col-md-12">
													<label>Descripci&oacute;n:</label>
													<input type="text" class="form-control" value="<?=$descripcion?>" />
												</div>
											</div>
										</div>
									</div>
									<br>
								</div>
							</div>
						</div>
					</div>
				</fieldset>
			</div>
			<?php echo footer() ?>
		</div>
	</div>
	<?php echo modal("../"); ?>
	<?php echo scripts("../"); ?>
	<script>
		$('.dual_select').bootstrapDualListbox({
			selectorMinimalHeight: 160,
		});
		$("#form").submit(function() {
			asignarUsuario($('[name="duallistbox1[]"]').val());
			return false;
		});
		$('#range .input-daterange').datepicker({
			keyboardNavigation: false,
			forceParse: false,
			autoclose: true,
			format: "dd/mm/yyyy"
		});
	</script>
</body>

</html>