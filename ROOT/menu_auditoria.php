<?php
include_once('html_fns.php');
validate_login();
$id = $_SESSION["codigo"];

//--
$sedes_IN = $_SESSION["sedes_in"];

//$_POST
$sede = $_REQUEST["sede"];
//$sede = ($sede == "")?$_SESSION["sede_codigo_1"]:$sede;
$departamento = $_REQUEST["departamento"];
$categoria = $_REQUEST["categoria"];
$hora = $_REQUEST["hora"];
$hora = ($hora == "") ? date("H:i") : $hora; //valida que si no se selecciona fecha, coloque la del dia
//--
$anio = date("Y");
$desde = $_REQUEST["desde"];
$desde = ($desde == "") ? "01/01/" . date("Y") : $desde; //valida que si no se selecciona fecha, coloque la del dia
$hasta = $_REQUEST["hasta"];
$hasta = ($hasta == "") ? "31/12/" . date("Y") : $hasta; //valida que si no se selecciona fecha, coloque la del dia
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head(); ?>
	<!-- Estilo especifico -->
	<link href="assets.1.2.8/css/propios/dashboard.css" rel="stylesheet">
</head>

<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("", "auditoria"); ?>
		<div class="main-panel">
			<?php echo navbar(); ?>
			<div class="content">
				<div class="row">
					<div class="col-lg-9 col-md-9 col-sm-12">
						<div class="card card-calendar">
							<div class="card-body" id="calendarContainer"> </div>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title"><i class="nc-icon nc-zoom-split"></i> Filtros</h4>
								<h5 class="card-category">Programaci&oacute;n de Auditor&iacute;as</h5>
							</div>
							<div class="card-body ">
								<div class="table-full-width ">
									<form name="f1" id="f1" method="get">
										<div class="row">
											<div class="col-md-12">
												<label>Sede:</label> <span class="text-success">*</span>
												<?php echo utf8_decode(sedes_html("sede", "calendarioMenu();", "select2", "Todas")); ?>
												<script>
													document.getElementById("sede").value = '<?php echo $sede; ?>';
												</script>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Departamento:</label> <span class="text-success">*</span>
												<?php echo utf8_decode(departamento_org_html("departamento", "calendarioMenu();", "select2")); ?>
												<script>
													document.getElementById("departamento").value = '<?php echo $departamento; ?>';
												</script>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Categor&iacute;a:</label> <span class="text-success">*</span>
												<?php echo utf8_decode(categorias_auditoria_html("categoria", "calendarioMenu();", "select2")); ?>
												<script>
													document.getElementById("categoria").value = '<?php echo $categoria; ?>';
												</script>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<label>Fechas:</label> <span class="text-success">*</span>
												<div class="form-group" id="range">
													<div class="input-daterange input-group" id="datepicker">
														<input type="text" class="input-sm form-control" name="desde" id="desde" value="<?php echo $desde; ?>" />
														<span class="input-group-addon"> &nbsp; <i class="fa fa-calendar"></i> &nbsp; </span>
														<input type="text" class="input-sm form-control" name="hasta" id="hasta" value="<?php echo $hasta; ?>" />
													</div>
												</div>
											</div>
										</div>
									</form>
									<br>
									<div class="row">
										<div class="col-md-12 text-center">
											<a type="button" class="btn btn-white" href="menu_auditoria.php"><i class="fa fa-eraser"></i> Limpiar</a>
											<button type="button" class="btn btn-primary" onclick="calendarioMenu();"><i class="fa fa-search"></i> Buscar</button>
										</div>
									</div>
								</div>
							</div>
							<div class="card-footer text-right">
								<hr>
								<div class="stats">
									<i class="fa fa-filter"></i>Filtro
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php echo footer() ?>
		</div>
	</div>

	<?php echo scripts(); ?>
	<!-- Peity -->
	<script src="assets.1.2.8/js/plugins/peity/jquery.peity.min.js"></script>
	<script type="text/javascript" src="assets.1.2.8/js/modules/menu/menu_auditoria.js"></script>

</body>
</html>