<?php
	include_once('html_fns_revision.php');
	validate_login("../");
$id = $_SESSION["codigo"];

	//$_POST
	$sede = $_REQUEST["sede"];
	$sector = $_REQUEST["sector"];
	$area = $_REQUEST["area"];
	$categoria = $_REQUEST["categoria"];
	$usuario = $_REQUEST["usuario"];
	//--
	$mes = date("m");
	$anio = date("Y");
	$desde = $_REQUEST["desde"];
	$desde = ($desde == "")?date("d/m/Y"):$desde; //valida que si no se selecciona fecha, coloque la del dia
	$hasta = $_REQUEST["hasta"];
	$hasta = ($hasta == "")?date("d/m/Y"):$hasta; //valida que si no se selecciona fecha, coloque la del dia
?>
<!DOCTYPE html>
<html>
<head>
<?php echo head("../"); ?>
</head>
<body class="">
	<div class="wrapper ">
		<?php echo sidebar("../","checklist"); ?>
		<div class="main-panel">
			<?php echo navbar("../"); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="nc-icon nc-paper"></i> Listado de Revisiones y Resultados</h5>
							</div>
							<div class="card-body all-icons">
								<form name = "f1" id = "f1" action="" method="get">
								<div class="row">
									<div class="col-xs-6 col-md-6 text-left">
										<button type="button" class="btn btn-white" onclick="window.history.back();">
											<i class="fa fa-chevron-left"></i>Atr&aacute;s
										</button>
									</div>
									<div class="col-xs-12 col-md-12 text-right"><label class = "text-info">* Filtros de Busqueda</label> </div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Sede:</label> <span class="text-success">*</span>
										<?php echo utf8_decode(sedes_html("sede","Submit();","select2")); ?>
										<script>
											document.getElementById("sede").value = '<?php echo $sede; ?>';
										</script>
									</div>
									<div class="col-md-6">
										<label>Sector:</label> <span class="text-success">*</span>
										<?php
											if($sede != ""){
												echo utf8_decode(sector_html("sector",$sede,"Submit();","select2"));
											}else{
												echo combos_vacios("sector",'select2');
											}
										?>
										<script>
											document.getElementById("sector").value = '<?php echo $sector; ?>';
										</script>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>&Aacute;rea:</label> <span class="text-success">*</span>
										<?php
											if($sector != ""){
												echo utf8_decode(area_html("area",$sector,"Submit();","select2"));
											}else{
												echo combos_vacios("area",'select2');
											}
										?>
										<script>
											document.getElementById("area").value = '<?php echo $area; ?>';
										</script>
									</div>
									<div class="col-md-6">
										<label>Categor&iacute;a:</label> <span class="text-success">*</span>
										<?php echo utf8_decode(categorias_chk_html("categoria","Submit();","select2")); ?>
										<script>
											document.getElementById("categoria").value = '<?php echo $categoria; ?>';
										</script>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Usuario:</label> <span class="text-success">*</span>
										<?php echo utf8_decode(usuarios_html("usuario","Submit();","select2")); ?>
										<script>
											document.getElementById("usuario").value = '<?php echo $usuario; ?>';
										</script>
									</div>
									<div class="col-md-6">
										<label>Fechas:</label> <span class="text-success">*</span>
										<div class="form-group" id="range">
											<div class="input-daterange input-group" id="datepicker">
												<input type="text" class="input-sm form-control" name="desde" id="desde" value = "<?php echo $desde; ?>" />
												<span class="input-group-addon"> &nbsp; <i class="fa fa-calendar"></i>  &nbsp; </span>
												<input type="text" class="input-sm form-control" name="hasta" id="hasta" value = "<?php echo $hasta; ?>" />
											</div>
										</div>
									</div>
								</div>
								</form>
								<br>
								<div class="row">
									<div class="col-md-12 text-center">
										<a class="btn btn-white" href = "FRMrevisiones.php"><i class="fa fa-eraser"></i> Limpiar</a>
										<button type="button" class="btn btn-primary" onclick = "Submit();"><i class="fa fa-search"></i> Buscar</button>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-lg-12" id = "result">
										<?php
											echo tabla_revisiones($codigo,$lista,$usuario,$sede,$sector,$area,$categoria,$desde,$hasta,"1,2");
										?>
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
	
    <script type="text/javascript" src="../assets.1.2.8/js/modules/checklist/lista.js"></script>
    	<script>
		$(document).ready(function(){
			$('.dataTables-example').DataTable({
                pageLength: 100,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    
                ]
            });
			
            $('.select2').select2({ width: '100%' });
			
			$('#range .input-daterange').datepicker({
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
				format: "dd/mm/yyyy"
            });
        });
    </script>

</body>
</html>
