<?php
include_once('html_fns_requisitos.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
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
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-sticky-note"></i> Gestor de Requisitos</h5>
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
										<label>Codigo del Requisito:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="nomenclatura" id="nomenclatura" onkeyup="texto(this)" />
									</div>
										<input type="hidden" name="codigo" id="codigo" />
										<div class="col-md-6">
											<label>Seleccione Documento: </label> <span class="text-danger">*</span>
											<div class="input-group">
												<a onclick="documentos();" title="Seleccionar Documento" class="input-group-addon"><i class="fa fa-search"></i></a>
												<input type="text" class="form-control" name="titulo-documento" id="titulo-documento" value="<?php //echo $tituloDocumento; ?>" readonly />
											</div>
											<input type="hidden" name="codigo-documento" id="codigo-documento" value="<?php //echo $documento; ?>" />
										</div>
									
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Art&iacute;culo, Punto de Norma, Otro:</label>
										<input type="text" class="form-control" name="soporte" id="soporte" onkeyup="texto(this)" />
									</div>
									<div class="col-md-6">
                                                <label>Clasificacion:</label> <span class="text-danger">*</span>
                                                <select class="form-control select2" name="requisito" id="requisito">
													<option value="">Seleccione</option>
													<option value="1">Requisito legal</option>
                                                    <option value="2"> Requisitos Normativos</option>
                                                    <option value="3">Otros Requisitos</option>
                                                </select>
                                            </div>
								</div>			
								<div class="row">
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Descripci&oacute;n:</label> <span class="text-danger">*</span>
										<textarea class="form-control textarea-autosize" id="descripcion" name="descripcion" rows="5"></textarea>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<label>Comentario:</label> 
										<textarea class="form-control textarea-autosize" id="comentario" name="comentario" rows="5"></textarea>
									</div>
								
								<br>
									<div class="col-md-12 text-center">
										<button type="button" class="btn btn-white" id="btn-limpiar" onclick="Limpiar();"><i class="fas fa-eraser"></i> Limpiar</button>
										<button type="button" class="btn btn-primary" id="btn-grabar" onclick="Grabar();"><i class="fas fa-save"></i> Grabar</button>
										<button type="button" class="btn btn-primary hidden" id="btn-modificar" onclick="Modificar();"><i class="fas fa-save"></i> Grabar</button>
									</div>
								</div>
								<hr>
								
								<div class="row">
									<div class="col-lg-12" id="result"> 

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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/requisitos/gestion_requisitos.js"></script>
	<script>
		$(document).ready(function() {
			printTable('');
			$('.select2').select2({ width: '100%' });
			$('.input-group.date').datepicker({
				format: 'dd/mm/yyyy',
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
			});
		});
	</script>

</body>

</html>