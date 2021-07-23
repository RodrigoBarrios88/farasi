<?php
include_once('html_fns_requisitos.php');
validate_login("../");
$id = $_SESSION["codigo"];

$ClsReq = new ClsRequisito();
$hashkey = $_REQUEST["hashkey"];
$requisito = $ClsReq->decrypt($hashkey, $id);
// obtiene plan de riesgo del usuario
$result = $ClsReq->get_requisito($requisito);
if (is_array($result)) {
	foreach ($result as $row) {
		$codigo = trim($row["req_codigo"]);
		$nomenclatura = trim($row["req_nomenclatura"]);
		$documento = trim($row["req_documento"]);
		$tituloDocumento = trim($row['doc_titulo']);
		$descripcion = trim($row["req_descripcion"]);
		$documentoSoporte = trim($row["req_documento_soporte"]);
		$fecharegistro = trim($row["req_fecha_registro"]);
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
		<?php echo sidebar("../", "requisitos"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
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
													<label>Nomenclatura:</label>
													<input type="text" class="form-control" value="<?php echo utf8_decode($nomenclatura); ?>" />
												</div>
												<div class="col-md-6">
													<label>Documento:</label>
													<input type="text" class="form-control" value="<?php echo utf8_decode(substr($tituloDocumento,0,50)); ?>..." />
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<label>Requisito:</label>
													<input type="text" class="form-control" value="<?php echo utf8_decode($descripcion); ?>" />
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<label>Documento Soporte:</label>
													<input type="text" class="form-control" value="<?php echo utf8_decode($documentoSoporte); ?>" />
												</div>
												<div class="col-md-6">
													<label>Fecha registro:</label>
													<input type="text" class="form-control" value="<?php echo cambia_fechaHora($fecharegistro); ?>" />
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
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fab fa-wpforms"></i> Gestor de evaluaciones
									<!-----
									<a class="btn btn-white btn-lg pull-right" href="CPREPORTES/REPpdf.php?hashkey=<?php echo $hashkey ?>" target="_blank" title="Imprimir Actividades" id="pdf"><i class="fa fa-print"></i></a>
									<a class="btn btn-white btn-lg pull-right hidden" href="CPREPORTES/REPInmediatoPdf.php?hashkey=<?php echo $hashkey ?>" target="_blank" title="Imprimir Actividades" id="pdfinmediato"><i class="fa fa-print"></i></a>-->
								</h5>
							</div>
							<di class="card-body all-icons">
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
										<label>Tipo Evaluaci&oacute;n</label> <span class="text-danger">*</span>
										<input type="text" name="nombre" id="nombre" class="form-control">
									</div>
									<div class="col-md-6">
										<label>Aspecto:</label> <span class="text-danger">*</span>
										<input type="text" name="aspecto" id="aspecto" class="form-control">
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Componente:</label> <span class="text-danger">*</span>
										<input type="text" name="componente" id="componente" class="form-control">
									</div>
									<div class="col-md-6">
										<label>Frecuencia:</label> <span class="text-danger">*</span>
										<select class="form-control select2" name="frecuencia" id="frecuencia">
											<option value="">Seleccione</option>
											<option value="1">Semestral</option>
											<option value="2">Trimestral</option>
											<option value="3">Anual</option>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Fecha reevaluaci&oacute;n: </label> <span class="text-danger">*</span>
										<div class="input-group">
											<a class="input-group-addon"><i class="fa fa-calendar"></i></a>
											<input type="text" class="form-control" name="fecha" id="fecha" value="<?php echo $desde; ?>" />
										</div>
									</div>
									<div class="col-md-2">
										<div class="col-md-3 checkbox" style="padding-top: 1.5em;">
											<div class="checkbox checkbox-danger con-margin">
												<input type="checkbox" name="cumple" id="cumple" value="1" />
												<label for="checkbox2">
												Requisito Legal 
												</label>
											</div>
										</div>
									</div>
									<div class="col-md-2">
										<div class="col-md-3 checkbox" style="padding-top: 1.5em;">
											<div class="checkbox checkbox-danger con-margin">
												<input type="checkbox" name="evarequisito" id="evarequisito" value="1" />
												<label for="checkbox2">
													Requisito
												</label>
											</div>
										</div>
									</div>
							
								</div>
								<br>
								<div class="row">
									<div class="col-md-12 text-center">
										<button type="button" class="btn btn-white" id="btn-limpiar" onclick="Limpiar();"><i class="fas fa-eraser"></i> Limpiar</button>
										<button type="button" class="btn btn-primary" id="btn-grabar" onclick="Grabar(1);"><i class="fas fa-save"></i> Grabar</button>
										<button type="button" class="btn btn-primary hidden" id="btn-modificar" onclick="Modificar(1);"><i class="fas fa-save"></i> Grabar</button>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-lg-12" id="result"> </div>
								</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-xs-12 text-center">
					<input type="hidden" name="codigo" id="codigo" />
					<input type="hidden" id="requisito" name="requisito" value="<?php echo $requisito; ?>" />
				</div>
			</div>
		</div>
		<?php echo footer() ?>
	</div>
	</div>
	<?php echo modal("../"); ?>
	<?php echo scripts("../"); ?>
</body>
<script type="text/javascript" src="../assets.1.2.8/js/modules/requisitos/tipo_evaluacion.js"></script>
<script>
	printTable("");
	$('#range .input-daterange').datepicker({
		keyboardNavigation: false,
		forceParse: false,
		autoclose: true,
		format: "dd/mm/yyyy"
	});
	$("#fecha").datepicker({
		keyboardNavigation: false,
		forceParse: false,
		autoclose: true,
		format: "dd/mm/yyyy"
	});
	$('.select2').select2({ width: '100%' });
</script>

</html>