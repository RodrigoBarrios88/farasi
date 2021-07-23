<?php
	include_once('html_fns_perfil.php');
	$codigo = $_SESSION["codigo"];
	$nombre = utf8_decode($_SESSION["nombre"]);
	$rol = $_SESSION["rol"];
	$rol_nombre = utf8_decode($_SESSION["rol_nombre"]);
	$foto = $_SESSION["foto"];
	
if($rol != "" && $nombre != ""){	
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title><?php echo $_SESSION["cliente_nombre"]; ?></title>
	<link rel="shortcut icon" href="../../CONFIG/img/icono.png">
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
	<!-- CSS Files -->
	<link href="../assets.1.2.8/css/bootstrap.min.css" rel="stylesheet" />
	<link href="../assets.1.2.8/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />
	<!-- Personal CSS -->
    <link href="../assets.1.2.8/css/plugins/cropper/cropper.min.css" rel="stylesheet">
	<!-- Swal -->
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<!-- Estilos Utilitarios -->
	<link href="../assets.1.2.8/css/propios/formulario.css" rel="stylesheet">
	<script src="https://kit.fontawesome.com/907a027ade.js" crossorigin="anonymous"></script>
	<link href="../assets.1.2.8/css/propios/custom.fonts.css" rel="stylesheet">
</head>
<body class="">
	<div class="wrapper ">
		<div class="sidebar" data-color="brown" data-active-color="danger">
			<!-- Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow" -->
			<div class="logo">
				<a href="../menu.php" class="simple-text logo-mini">
					<div class="logo-image-small">
						<img src="../../CONFIG/img/logo2.png" />
					</div>
				</a>
				<a href="../menu.php" class="simple-text logo-normal">
					BPManagement
				</a>
			</div>
			<div class="sidebar-wrapper">
				<?php echo menu_user('../', false); ?>
				<ul class="nav">
					<li>
						<a href="../menu.php">
							<i class="nc-icon nc-layout-11"></i>
							<p>Men&uacute;</p>
						</a>
					</li>
					<?php echo menu_administracion('../'); ?>
					<?php echo menu_gestion_tecnica('../'); ?>
					<?php echo menu_herramientas('../'); ?>
					<hr>
					<li>
						<a href="../logout.php">
							<i class="fa fa-power-off"></i>
							<p>Salir</p>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="main-panel">
			<!-- Navbar -->
			<nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
				<div class="container-fluid">
					<div class="navbar-wrapper">
						<div class="navbar-minimize">
							<button id="minimizeSidebar" class="btn btn-icon btn-round">
								<i class="fas fa-bars text-center visible-on-sidebar-mini"></i>
								<i class="fas fa-bars text-center visible-on-sidebar-regular"></i>
							</button>
						</div>
						<div class="navbar-toggle">
							<button type="button" class="navbar-toggler">
								<span class="navbar-toggler-bar bar1"></span>
								<span class="navbar-toggler-bar bar2"></span>
								<span class="navbar-toggler-bar bar3"></span>
							</button>
						</div>
						<a class="navbar-brand" href="javascript:void(0);"><?php echo menu_aplicaciones('../'); ?></a>
					</div>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-bar navbar-kebab"></span>
						<span class="navbar-toggler-bar navbar-kebab"></span>
						<span class="navbar-toggler-bar navbar-kebab"></span>
					</button>
					<div class="collapse navbar-collapse justify-content-end" id="navigation">
						<ul class="navbar-nav">
							<?php echo menu_navigation_top('../'); ?>
						</ul>
					</div>
				</div>
			</nav>
			<!-- End Navbar -->
			
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-image"></i> Redimensi&oacute;n de la imagen</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-md-6 col-xs-12">
										<div class="image-crop">
											<img src="<?php echo $foto; ?>">
											<input type = "hidden" name = "codigo" id = "codigo"  value = "<?php echo $codigo; ?>" />
										</div>
									</div>
									<div class="col-md-5 col-xs-12 text-center">
										<h4>Previsualizaci&oacute;n de la Imagen</h4>
										<div class="img-preview img-preview-sm"></div>
										<br>
										<label title="Download image" id="download" class="btn btn-defult btn-block text-light">
											<i class="fa fa-save"></i> &nbsp; 
											Guardar Imagen
										</label>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-md-12 text-center">
										<h5><i class="fa fa-wrench"></i>  Herramientas de Edici&oacute;n</h5>
										<div class="btn-group">
											<button class="btn btn-white" id="zoomIn" type="button"><i class="fa fa-search-plus"></i> Zoom</button>
											<button class="btn btn-white" id="zoomOut" type="button"><i class="fa fa-search-minus"></i> Zoom</button>
											<button class="btn btn-white" id="rotateLeft" type="button"><i class="fa fa-rotate-left"></i> Rotar a la Izquierda</button>
											<button class="btn btn-white" id="rotateRight" type="button"><i class="fa fa-rotate-right"></i> Rotar a la Derecha</button>
										</div>
									</div>	
								</div>	
								<br><br>
							</div>
						</div>
					</div>
				</div>
			</div>
			<footer class="footer footer-black  footer-white ">
				<div class="container-fluid">
					<div class="row">
						<nav class="footer-nav">
							<ul>
							   <li>
								  <a href="https://www.farasi.com.gt" target="_blank" style="text-transform: none;"><strong>BPManagement</strong> 1.2.5 | Powered By <strong>Farasi Software</strong></a>
							   </li>
							</ul>
						</nav>
						<div class="credits ml-auto">
							<span class="copyright">
							   &copy; <?php echo date("Y"); ?> <strong>Copyright</strong> Farasi S.A.
							</span>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>
	
	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document"  id = "ModalDialog">
			<div class="modal-content">
				<div class="modal-header">
					<h6 class="modal-title text-left" id="myModalLabel"><img src="../../CONFIG/img/logo2.png" width = "60px;" /></h6>
				</div>
				<div class="modal-body text-center" id= "lblparrafo">
					<img src="../../CONFIG/img/img-loader.gif"/><br>
					<label align ="center">Transaccion en Proceso...</label>
					<!--div class="modal-footer"><button type="button" class="btn btn-primary" onclick="cerrar();" >Aceptar</button></div-->
				</div>
				<div class="modal-body" id= "Pcontainer">
			  
				</div>
			</div>
		</div>
	</div>
	<!-- Modal -->
	
	<!--   Core JS Files   -->
	<script src="../assets.1.2.8/js/core/jquery.min.js"></script>
	<script src="../assets.1.2.8/js/core/popper.min.js"></script>
	<script src="../assets.1.2.8/js/core/bootstrap.min.js"></script>
	<script src="../assets.1.2.8/js/plugins/perfect-scrollbar.jquery.min.js"></script>
	<script src="../assets.1.2.8/js/plugins/moment.min.js"></script>
	<!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
	<script src="../assets.1.2.8/js/plugins/bootstrap-switch.js"></script>
	<!--  Plugin for Sweet Alert -->
	<!--script src="../assets.1.2.8/js/plugins/sweetalert2.min.js"></script-->
	<!-- Forms Validations Plugin -->
	<script src="../assets.1.2.8/js/plugins/jquery.validate.min.js"></script>
	<!--  Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
	<script src="../assets.1.2.8/js/plugins/jquery.bootstrap-wizard.js"></script>
	<!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
	<script src="../assets.1.2.8/js/plugins/bootstrap-selectpicker.js"></script>
	<!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
	<script src="../assets.1.2.8/js/plugins/bootstrap-datetimepicker.js"></script>
	<!--  DataTables.net Plugin, full documentation here: https://datatables.net/    -->
	<script src="../assets.1.2.8/js/plugins/jquery.dataTables.min.js"></script>
	<!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
	<script src="../assets.1.2.8/js/plugins/bootstrap-tagsinput.js"></script>
	<!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
	<script src="../assets.1.2.8/js/plugins/jasny-bootstrap.min.js"></script>
	<!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
	<script src="../assets.1.2.8/js/plugins/fullcalendar.min.js"></script>
	<!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
	<script src="../assets.1.2.8/js/plugins/jquery-jvectormap.js"></script>
	<!--  Plugin for the Bootstrap Table -->
	<script src="../assets.1.2.8/js/plugins/nouislider.min.js"></script>
	<!-- Chart JS -->
	<script src="../assets.1.2.8/js/plugins/chartjs.min.js"></script>
	<!--  Notifications Plugin    -->
	<script src="../assets.1.2.8/js/plugins/bootstrap-notify.js"></script>
	<!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
	<script src="../assets.1.2.8/js/paper-dashboard.min.js?v=2.0.1" type="text/javascript"></script>
	
	<!-- Image cropper -->
    <script src="../assets.1.2.8/js/plugins/cropper/cropper.min.js"></script>
	
	<!-- Custom Theme JavaScript -->
    <script type="text/javascript" src="../assets.1.2.8/js/modules/perfil/foto.js"></script>
    <script type="text/javascript" src="../assets.1.2.8/js/modules/ejecutaModal.js"></script>
    <script type="text/javascript" src="../assets.1.2.8/js/modules/util.js"></script>
    
</body>
</html>
<?php
}else{
	echo "<form id='f1' name='f1' action='../logout.php' method='post'>";
	echo "<script>document.f1.submit();</script>";
	echo "</form>";
}
?>