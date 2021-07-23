<?php
include_once('html_fns_prioridades.php');
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
		<?php echo sidebar("../", "helpdesk"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-tags"></i> Gestor de Prioridades</h5>
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
										<label>Nombre de la Prioridad:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="nombre" id="nombre" onkeyup="texto(this)" value="<?php echo $nombre; ?>" />
										<input type="hidden" name="codigo" id="codigo" value="<?php echo $codigo; ?>" />
									</div>
									<div class="col-md-6">
										<label>Notificaciones Push:</label> <span class="text-danger">*</span>
										<select class="form-control select2" name="sms" id="sms">
											<option value="0" selected=>No</option>
											<option value="1">Si</option>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Tiempo de Respuesta (horas):</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="trespuesta" id="trespuesta" placeholder="hh:mm" maxlength="6" onkeyup="tiempo(this)" />
									</div>
									<div class="col-md-6">
										<label>Tiempo de Soluci&oacute;n (horas):</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="tsolucion" id="tsolucion" placeholder="hh:mm" maxlength="6" onkeyup="tiempo(this)" />
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Recordatorio cada (horas):</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" name="trecordar" id="trecordar" placeholder="hh:mm" maxlength="6" onkeyup="tiempo(this)" />
									</div>
									<div class="col-md-5">
										<label>Color:</label> <span class="text-danger">*</span>
										<input type="text" class="form-control" id="color" name="color" value="#fff" />
									</div>
									<div class="col-md-1">
										<span>.</span><br>
										<button type="button" id="btn-color" class="btn btn-white btn-block back-change"> &nbsp;</button>
									</div>
								</div>
								<br>
								<div class="row">
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

	<!-- asny Bootstrap -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/4.0.0/js/jasny-bootstrap.min.js"></script>

	<script type="text/javascript" src="../assets.1.2.8/js/modules/helpdesk/prioridades.js"></script>

	<script>
		$(document).ready(function() {

			$('.select2').select2({ width: '100%' });

			$('#color').colorpicker();

			var divStyle = $('.back-change')[0].style;
			$('#color').colorpicker({
				color: divStyle.backgroundColor
			}).on('changeColor', function(ev) {
				divStyle.backgroundColor = ev.color.toHex();
			});
		});
	</script>

</body>

</html>