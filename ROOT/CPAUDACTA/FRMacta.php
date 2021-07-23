<?php
include_once('html_fns_acta.php');
validate_login("../");
$usuario = $_SESSION["codigo"];

$categoriasIn = $_SESSION["categorias_in"];

//$_POST
$ClsAud = new ClsAuditoria();
$ClsEje = new ClsEjecucion();
$hashkey = $_REQUEST["hashkey"];
$ejecucion = $ClsAud->decrypt($hashkey, $usuario);
$result = $ClsEje->get_acta($ejecucion);
if (is_array($result)) {
	foreach ($result as $row) {
		$ejecucion = trim($row["eje_codigo"]);
		$auditoria = trim($row["audit_codigo"]);
		$programacion = trim($row["pro_codigo"]);
		$sede = utf8_decode($row["sed_nombre"]);
		$departamento = utf8_decode($row["dep_nombre"]);
		$categoria = utf8_decode($row["cat_nombre"]);
		$titulo = utf8_decode($row["audit_nombre"]);
		/// Observaciones
		$print_acta = true;
		$fini = cambia_fechaHora($row["act_fecha_inicio"]);
		$ffin = cambia_fechaHora($row["act_fecha_final"]);
		$observaciones = utf8_decode($row["act_observaciones"]);
		//--
		$fecha1 = explode(" ", $fini);
		$fini = $fecha1[0];
		$hini = $fecha1[1];
		$fecha2 = explode(" ", $ffin);
		$ffin = $fecha2[0];
		$hfin = $fecha2[1];
	}
} else {
	$result = $ClsEje->get_ejecucion($ejecucion);
	if (is_array($result)) {
		foreach ($result as $row) {
			$ejecucion = trim($row["eje_codigo"]);
			$auditoria = trim($row["audit_codigo"]);
			$programacion = trim($row["pro_codigo"]);
			$sede = utf8_decode($row["sed_nombre"]);
			$departamento = utf8_decode($row["dep_nombre"]);
			$categoria = utf8_decode($row["cat_nombre"]);
			$titulo = utf8_decode($row["audit_nombre"]);
			/// Observaciones
			$print_acta = false;
			$observaciones = "";
			//--
			$fini = date("d/m/Y");
			$hini = "08:00";
			$ffin = date("d/m/Y");
			$hfin = date("H:i");
		}
	}
}?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../", "auditoria"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-check-square-o"></i> Gestor de Cuestionarios de Auditoria</h5>
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
										<label>Sede:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" value="<?php echo $sede; ?>" disabled />
										<input type="hidden" name="ejecucion" id="ejecucion" value="<?php echo $ejecucion; ?>" />
										<input type="hidden" name="auditoria" id="auditoria" value="<?php echo $auditoria; ?>" />
										<input type="hidden" name="programacion" id="programacion" value="<?php echo $programacion; ?>" />
									</div>
									<div class="col-md-6">
										<label>Departamento:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" value="<?php echo $departamento; ?>" disabled />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Cuestionario:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" value="<?php echo $titulo; ?>" disabled />
									</div>
									<div class="col-md-6">
										<label>Fecha de Auditor&iacute;a:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" value="<?php echo $titulo; ?>" disabled />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Fecha de inicio para el acta:</label> <span class="text-danger">*</span>
										<div class="form-group" id="simple">
											<div class="input-group date">
												<input type="text" class="form-control" name="fini" id="fini" value="<?php echo $fini; ?>">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<label>Hora de inicio:</label> <span class="text-danger">*</span>
										<div class="form-group">
											<input type="text" class="form-control timepicker" name="hini" id="hini" value="<?php echo $hini; ?>">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Fecha de cierre del acta:</label> <span class="text-danger">*</span>
										<div class="form-group" id="simple">
											<div class="input-group date">
												<input type="text" class="form-control" name="ffin" id="ffin" value="<?php echo $ffin; ?>">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<label>Hora de cierre:</label> <span class="text-danger">*</span>
										<div class="form-group">
											<input type="text" class="form-control timepicker" name="hfin" id="hfin" value="<?php echo $hfin; ?>">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Observaciones Especiales:</label> <span class="text-muted">(opcional)</span>
										<textarea class="form-control" name="observaciones" id="observaciones" rows="3" onkeyup="textoLargo(this);"><?php echo $observaciones; ?></textarea>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 text-center">
										<br>
										<a class="btn btn-white" href="FRMacta.php"><i class="fa fa-eraser"></i> Limpiar</a>
										<button type="button" class="btn btn-primary" id="btn-grabar" onclick="grabarActa();"><i class="fa fa-save"></i> Grabar</button>
										<?php if ($print_acta == true) { ?>
											<a class="btn btn-white" target="_blank" href="CPREPORTES/REPacta.php?ejecucion=<?php echo $ejecucion; ?>"><i class="fa fa-print"></i> Imprimir</a>
										<?php } else { ?>
											<a class="btn btn-white" disabled><i class="fa fa-print"></i> Imprimir</a>
										<?php } ?>
									</div>
								</div>
								<br>
								<hr>
								<br>
								<div class="row">
									<div class="col-md-12 text-center">
										<?php echo utf8_decode(tabla_participantes($programacion)); ?>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/auditoria/acta.js"></script>

	<script>
		$(document).ready(function() {
			$('.select2').select2({ width: '100%' });
		});
	</script>

</body>
</html>