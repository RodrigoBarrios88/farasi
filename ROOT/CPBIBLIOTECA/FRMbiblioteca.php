<?php
include_once('html_fns_biblioteca.php');
validate_login("../");
$id = $_SESSION["codigo"];
/*
$documento = $_REQUEST['documento'];
$categoria = $_REQUEST['categoria'];*/
//$_POST
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head("../"); ?>
</head>

<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("../", "biblioteca"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-book-open"></i> Biblioteca Documental</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-xs-6 col-md-6 text-left">
										<button type="button" class="btn btn-white" onclick="window.history.back();">
											<i class="fa fa-chevron-left"></i>Atr&aacute;s
										</button>
									</div>
								</div>
								<div class="row">
									<form class="col-lg-12" name="f1" id="f1" action="" method="get">
										<div class="col-lg-12">
											<Label>Categoria</Label>
											<?php echo utf8_decode(categorias_biblioteca_html("categoria", "tablaDocumento()", "select2")); ?>
											<script>
												document.getElementById("categoria").value = "<?php echo $categoria; ?>";
											</script>
										</div>
										<div class="col-md-12 text-center">
											<button type="button" class="btn btn-success" onclick="Excel();"><i class="fa fa-file-excel-o"></i> Excel</button>
										</div>
										<div class="col-lg-12">										
											<?php //if($categoria != ""):?>
												<?php //tabla_documentos($categoria) ?>
											<?php //endif;?>
											<div id="result">
												
											</div>
										</div>
										<select hidden class="dual_select" id="columnas" name="columnas[]" multiple style="min-height: 250px;">
											<option value="bib_codigo" selected>C&oacute;digo </option>
											<option value="cat_nombre" selected>Categoria</option>
											<option value="bib_titulo" selected>Titulo Documento</option>
											<option value="bib_descripcion" selected>Descripcion</option>
											<option value="bib_version" selected>Version</option>
											<option value="bib_fecha_vence" selected>Vence</option>
										</select>
									</form>
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
	<script type="text/javascript" src="../assets.1.2.8/js/modules/biblioteca/biblioteca.js"></script>
	<script>
		$('.select2').select2({
			width: '100%'
		});

		function Excel() {
			var columnas = 0;
			$('#tabla th').each(function() {
				columnas++;
			});
			if (columnas >= 1) {
				myform = document.forms.f1;
				myform.method = "get";
				myform.target = "_blank";
				myform.action = "EXCELreporte.php";
				myform.submit();
				myform.action = "";
				myform.target = "";
				myform.method = "get";
			} else {
				swal("Alto", "Para generar este listado en PDF debe seleccionar al menos 1 columna...", "info");
			}
		}

		$(document).ready(function() {
			//printBiblioteca(categoria);			
		});
	</script>
</body>

</html>