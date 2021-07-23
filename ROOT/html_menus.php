<?php
//-- SISTEMA --
include_once('user_auth_fns.php');

//////////////////////////////////////////////////// //////////////// ////////////////////////////////////////////////////
//////////////////////////////////////////////////// SECCION DE MENU /////////////////////////////////////////////////////
//////////////////////////////////////////////////// //////////////// ////////////////////////////////////////////////////
//////////////////////////////////// sidebar ////////////////////////////////////
function sidebar($nivel = "", $modulo)
{
	$salida = '<div class="sidebar" data-color="brown" data-active-color="danger">';
	$salida .= '<div class="logo">';
	$salida .= '<a href="' . $nivel . 'menu.php" class="simple-text logo-mini">';
	$salida .= '<div class="logo-image-small">';
	$salida .= '<img src="../../CONFIG/img/logo2.png" />';
	$salida .= '</div>';
	$salida .= '</a>';
	$salida .= '<a href="' . $nivel . 'menu.php" class="simple-text logo-normal">';
	$salida .= 'BPManagement';
	$salida .= '</a>';
	$salida .= '</div>';
	$salida .= '<div class="sidebar-wrapper">';
	$salida .= menu_user($nivel);
	$salida .= '<ul class="nav">';
	$salida .= '<li>';
	$salida .= ($nivel == "" || $modulo == "herramientas") ? '<a href="' . $nivel . 'menu.php">' : '<a href="' . $nivel . 'menu_' . $modulo . '.php">';
	$salida .= '<i class="nc-icon nc-layout-11"></i>';
	$salida .= '<p>Men&uacute;</p>';
	$salida .= '</a>';
	$salida .= '</li>';
	$salida .= menu_administracion($nivel);
	$salida .= menu_gestion_tecnica($nivel);
	switch ($modulo) {
		case "indicador":
			$salida .= menu_indicador($nivel, false);
			break;
		case "process":
			$salida .= menu_procesos($nivel, false);
			break;
		case "checklist":
			$salida .= menu_checklist($nivel, false);
			break;
		case "planning":
			$salida .= menu_planning($nivel, false);
			break;
		case "ppm":
			$salida .= menu_ppm($nivel, false);
			break;
		case "auditoria":
			$salida .= menu_auditoria($nivel, false);
			break;
		case "helpdesk":
			$salida .= menu_helpdesk($nivel, false);
			break;
		case "mejora":
			$salida .= menu_mejora($nivel, false);
			break;
		case "risk":
			$salida .= menu_risk($nivel, false);
			break;
		case "biblioteca":
			$salida .= menu_biblioteca($nivel, false);
			break;
		case "encuestas":
			$salida .= menu_encuestas($nivel, false);
			break;
		case "herramientas":
			$salida .= menu_herramientas($nivel, false);
			break;
		case "requisitos":
				$salida .= menu_requisitos($nivel, false);
		break;
	}
	$salida .= '<hr>';
	$salida .= '<li>';
	$salida .= '<a href="' . $nivel . 'logout.php">';
	$salida .= '<i class="fa fa-power-off"></i>';
	$salida .= '<p>Salir</p>';
	$salida .= '</a>';
	$salida .= '</li>';
	$salida .= '</ul>';
	$salida .= '</div>';
	$salida .= '</div>';
	return $salida;
}
//////////////////////////////////// Scripts ////////////////////////////////////
function scripts($nivel = "")
{
	$salida = '<script src="' . $nivel . 'assets.1.2.8/js/core/jquery.min.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/core/popper.min.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/core/bootstrap.min.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/textarea-autosize.min.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/perfect-scrollbar.jquery.min.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/moment.min.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/bootstrap-switch.js"></script>';
	// $salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/sweetalert2.min.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/jquery.validate.min.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/jquery.bootstrap-wizard.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/bootstrap-selectpicker.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/bootstrap-datetimepicker.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/jquery.dataTables.min.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/bootstrap-tagsinput.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/jasny-bootstrap.min.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/fullcalendar.min.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/jquery-jvectormap.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/nouislider.min.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/chartjs.min.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/bootstrap-notify.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/paper-dashboard.min.js?v=2.0.1" type="text/javascript"></script>';
	//$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/paper-dashboard.min.js" type="text/javascript"></script>';

	$salida .= '<script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/d3/d3.min.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/c3/c3.min.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/peity/jquery.peity.min.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/select2/select2.full.min.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/clockpicker/clockpicker.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/datapicker/bootstrap-datepicker.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/dualListbox/jquery.bootstrap-duallistbox.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/peity/jquery.peity.min.js"></script>';
	$salida .= '<script type="text/javascript" src="' . $nivel . 'assets.1.2.8/js/modules/ejecutaModal.js"></script>';
	$salida .= '<script type="text/javascript" src="' . $nivel . 'assets.1.2.8/js/modules/loading.js"></script>';
	$salida .= '<script type="text/javascript" src="' . $nivel . 'assets.1.2.8/js/modules/util.js"></script>';
	return $salida;
}
//////////////////////////////////// NavBar ////////////////////////////////////
function navbar($nivel = "")
{
	$salida = '<nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">';
	$salida .= '<div class="container-fluid">';
	$salida .= '<div class="navbar-wrapper">';
	$salida .= '<div class="navbar-minimize">';
	$salida .= '<button id="minimizeSidebar" class="btn btn-icon btn-round">';
	$salida .= '<i class="fas fa-bars text-center visible-on-sidebar-mini"></i>';
	$salida .= '<i class="fas fa-bars text-center visible-on-sidebar-regular"></i>';
	$salida .= '</button>';
	$salida .= '</div>';
	$salida .= '<div class="navbar-toggle">';
	$salida .= '<button type="button" class="navbar-toggler">';
	$salida .= '<span class="navbar-toggler-bar bar1"></span>';
	$salida .= '<span class="navbar-toggler-bar bar2"></span>';
	$salida .= '<span class="navbar-toggler-bar bar3"></span>';
	$salida .= '</button>';
	$salida .= '</div>';
	$salida .= '<a class="navbar-brand" href="javascript:void(0);">' . menu_aplicaciones($nivel) . '</a>';
	$salida .= '</div>';
	$salida .= '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">';
	$salida .= '<span class="navbar-toggler-bar navbar-kebab"></span>';
	$salida .= '<span class="navbar-toggler-bar navbar-kebab"></span>';
	$salida .= '<span class="navbar-toggler-bar navbar-kebab"></span>';
	$salida .= '</button>';
	$salida .= '<div class="collapse navbar-collapse justify-content-end" id="navigation">';
	$salida .= '<ul class="navbar-nav">';
	$salida .=  menu_navigation_top($nivel);
	$salida .= '</ul>';
	$salida .= '</div>';
	$salida .= '</div>';
	$salida .= '</nav>';

	return $salida;
}
//////////////////////////////////// Modal ////////////////////////////////////
function modal($nivel = "")
{
	$salida = '<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
	$salida .= '<div class="modal-dialog" role="document" id="ModalDialog">';
	$salida .= '<div class="modal-content">';
	$salida .= '<div class="modal-header">';
	$salida .= '<h6 class="modal-title text-left" id="myModalLabel"><img src="' . $nivel . '../CONFIG/img/logo2.png" width="60px;" /></h6>';
	$salida .= '</div>';
	$salida .= '<div class="modal-body text-center" id="lblparrafo">';
	$salida .= '<img src="' . $nivel . '../CONFIG/img/img-loader.gif" /><br>';
	$salida .= '<label align="center">Transaccion en Proceso...</label>';
	$salida .= '</div>';
	$salida .= '<div class="modal-body" id="Pcontainer">';
	$salida .= '</div>';
	$salida .= '</div>';
	$salida .= '</div>';
	$salida .= '</div>';
	return $salida;
}
//////////////////////////////////// Header ////////////////////////////////////
function head($nivel = '')
{
	$salida = '<meta charset="utf-8" />';
	$salida .= '<link rel="apple-touch-icon" sizes="76x76" href="' . $nivel . 'assets.1.2.8/img/apple-icon.png">';
	$salida .= '<link rel="icon" type="image/png" href="' . $nivel . 'assets.1.2.8/img/favicon.png">';
	$salida .= '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />';
	$salida .= '<title>' . $_SESSION["cliente_nombre"] . '</title>';
	$salida .= '<link rel="shortcut icon" href="' . $nivel . '../CONFIG/img/icono.png">';
	$salida .= '<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no" name="viewport" />';
	$salida .= '<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />';
	$salida .= '<link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">';
	$salida .= '<link href="' . $nivel . 'assets.1.2.8/css/bootstrap.min.css" rel="stylesheet" />';
	$salida .= '<link href="' . $nivel . 'assets.1.2.8/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />';
	$salida .= '<link href="' . $nivel . 'assets.1.2.8/css/plugins/select2/select2.min.css" rel="stylesheet">';
	$salida .= '<link href="' . $nivel . 'assets.1.2.8/css/plugins/datapicker/datepicker3.css" rel="stylesheet">';
	$salida .= '<link href="' . $nivel . 'assets.1.2.8/css/plugins/colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet">';
	$salida .= '<script src="' . $nivel . 'assets.1.2.8/js/plugins/sweetalert/sweetalert.min.js"></script>';
	$salida .= '<link href="' . $nivel . 'assets.1.2.8/css/propios/formulario.css" rel="stylesheet">';
	$salida .= '<link href="' . $nivel . 'assets.1.2.8/css/propios/calendario.css" rel="stylesheet">';
	$salida .= '<link href="' . $nivel . 'assets.1.2.8/css/propios/gridxs.css" rel="stylesheet">';
	$salida .= '<script src="https://kit.fontawesome.com/907a027ade.js" crossorigin="anonymous"></script>';
	$salida .= '<link href="' . $nivel . 'assets.1.2.8/css/propios/custom.fonts.css" rel="stylesheet">';
	$salida .= '<link href="' . $nivel . 'assets.1.2.8/css/propios/figuras.css" rel="stylesheet">';
	$salida .= '<link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">';
	$salida .= '<link href="' . $nivel . 'assets.1.2.8/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">';
	$salida .= '<link href="' . $nivel . 'assets.1.2.8/css/plugins/c3/c3.min.css" rel="stylesheet">';
	$salida .= '<link href="' . $nivel . 'assets.1.2.8/css/plugins/textarea-autosize.css" rel="stylesheet">';
	return $salida;
}

//////////////////////////////////// Footer ////////////////////////////////////
function footer()
{
	$salida = '<footer class="footer footer-black  footer-white ">';
	$salida .= '<div class="container-fluid">';
	$salida .= '<div class="row">';
	$salida .= '<nav class="footer-nav">';
	$salida .= '<ul>';
	$salida .= '<li>';
	$salida .= '<a href="https://www.farasi.com.gt" target="_blank" style="text-transform: none;"><strong>BPManagement</strong> 1.2.8 | Powered By <strong>Farasi Software</strong></a>';
	$salida .= '</li>';
	$salida .= '</ul>';
	$salida .= '</nav>';
	$salida .= '<div class="credits ml-auto">';
	$salida .= '<span class="copyright">';
	$salida .= '&copy; ' . date("Y") . ' <strong>Copyright</strong> Farasi S.A.';
	$salida .= '</span>';
	$salida .= '</div>';
	$salida .= '<div>';
	$salida .= '</div>';
	$salida .= '</footer>';
	return $salida;
}
//////////////////////////////////// PERFIL ////////////////////////////////////
function menu_user($nivel = '', $collapse = true)
{
	$nombre = utf8_decode($_SESSION["nombre"]);
	$foto = $_SESSION["foto"];
	$collapse = ($collapse) ? "collapse" : "";
	////----
	$salida = '';
	$salida .= '<div class="user">';
	// foto
	$salida .= '<div class="photo">';
	$salida .= '<img src="' . $nivel . '../../CONFIG/Fotos/' . $foto . '" />';
	$salida .= '</div>';
	//--
	$salida .= '<div class="info">';
	$salida .= '<a data-toggle="collapse" href="#collapseExample" class="collapsed">';
	// Poner los nombres en varias lineas para no montarlo en el sidebar
	if(strlen($nombre) > 20){
		$arrNombre = explode(" ",$nombre);
		if(is_array($arrNombre)){
			foreach($arrNombre as $nom){
				$nombre = $nom;
				break;
			}
		}
	}
	$salida .= '<span>' . $nombre . '<b class="caret"></b></span>';
	$salida .= '</a>';
	$salida .= '<div class="clearfix"></div>';
	$salida .= '<div class="' . $collapse . '" id="collapseExample">';
	$salida .= '<ul class="nav">';
	$salida .= '<li>';
	$salida .= '<a href="' . $nivel . 'CPPERFIL/FRMperfil.php">';
	$salida .= '<span class="sidebar-mini-icon"><i class="nc-icon nc-single-02"></i></span>';
	$salida .= '<span class="sidebar-normal">Perfil</span>';
	$salida .= '</a>';
	$salida .= '</li>';
	$salida .= '<li>';
	$salida .= '<a href="' . $nivel . 'CPPERFIL/FRMpassword.php">';
	$salida .= '<span class="sidebar-mini-icon"><i class="nc-icon nc-lock-circle-open"></i></span>';
	$salida .= '<span class="sidebar-normal">Contrase&ntilde;a</span>';
	$salida .= '</a>';
	$salida .= '</li>';
	$salida .= '<li>';
	$salida .= '<a href="' . $nivel . 'CPPERFIL/FRMajustes.php">';
	$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-cog"></i></span>';
	$salida .= '<span class="sidebar-normal">Ajustes</span>';
	$salida .= '</a>';
	$salida .= '</li>';
	$salida .= '</ul>';
	$salida .= '</div>';
	$salida .= '</div>';
	$salida .= '</div>';

	return $salida;
}

function menu_navigation_top($nivel = '')
{
	$salida = '<li class="nav-item btn-rotate dropdown">';
	$salida .= '<a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
	$salida .= '<i class="nc-icon nc-circle-10"></i>';
	$salida .= '<p>';
	$salida .= '<span class="d-lg-none d-md-block">Perfil</span>';
	$salida .= '</p>';
	$salida .= '</a>';
	$salida .= '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">';
	$salida .= '<a class="dropdown-item" href="' . $nivel . 'CPPERFIL/FRMperfil.php"><i class="fa fa-user"></i> Datos del Perfil</a>';
	$salida .= '<a class="dropdown-item" href="' . $nivel . 'CPPERFIL/FRMpassword.php"><i class="fa fa-key"></i> Usuario y Contrase&ntilde;a</a>';
	$salida .= '<a class="dropdown-item" href="' . $nivel . 'CPPERFIL/FRMajustes.php"><i class="fa fa-cog"></i> Ajustes</a>';
	$salida .= '</div>';
	$salida .= '</li>';
	$salida .= '<li class="nav-item">';
	$salida .= '<a class="nav-link btn-rotate" href="' . $nivel . 'ayuda.php">';
	$salida .= '<i class="fa fa-question-circle"></i>';
	$salida .= '<p>';
	$salida .= '<span class="d-lg-none d-md-block">Ayuda</span>';
	$salida .= '</p>';
	$salida .= '</a>';
	$salida .= '</li>';
	$salida .= '<li class="nav-item">';
	$salida .= '<a class="nav-link btn-rotate" href="' . $nivel . 'logout.php">';
	$salida .= '<i class="fa fa-power-off"></i>';
	$salida .= '<p>';
	$salida .= '<span class="d-lg-none d-md-block">Salir</span>';
	$salida .= '</p>';
	$salida .= '</a>';
	$salida .= '</li>';

	return $salida;
}

function menu_aplicaciones($nivel = '')
{
	$salida = '<select name="menuapps" id="menuapps" class = "form-control select2" onchange="window.location.href=this.value">';
	$salida .= '<option value="#">M&oacute;dulos del Sistema</option>';
	$salida .= '<option value="' . $nivel . 'menu.php">Men&uacute; de Inicio</option>';
	if (isset($_SESSION["GRP_ENCUESTA"])) {
		$salida .= '<option value="' . $nivel . 'menu_encuestas.php">Encuestas</option>';
	}
	if (isset($_SESSION["GRP_BIBLIOTECA"])) {
		$salida .= '<option value="' . $nivel . 'CPBIBLIOTECA/FRMbiblioteca.php">Library</option>';
	}
	if (isset($_SESSION["GRP_CKLIST"])) {
		$salida .= '<option value="' . $nivel . 'menu_checklist.php">Check List</option>';
	}
	if (isset($_SESSION["GRP_HELPDESK"])) {
		$salida .= '<option value="' . $nivel . 'menu_helpdesk.php">Support</option>';
	}
	if (isset($_SESSION["GRP_PPM"])) {
		$salida .= '<option value="' . $nivel . 'menu_ppm.php">Maintenance Planner</option>';
	}
	if (isset($_SESSION["GRP_AUDIT"])) {
		$salida .= '<option value="' . $nivel . 'menu_auditoria.php">Audit Active</option>';
	}
	if (isset($_SESSION["GRP_INDICATOR"])) {
		$salida .= '<option value="' . $nivel . 'menu_indicador.php">KPI&apos;s</option>';
	}
	if (isset($_SESSION["GRP_AUDIT"])) {
		$salida .= '<option value="' . $nivel . 'menu_mejora.php">Improvement</option>';
	}
	if (isset($_SESSION["GRP_PROCESS"])) {
		$salida .= '<option value="' . $nivel . 'menu_process.php">Process Manager</option>';
	}
	if (isset($_SESSION["GRP_PLANNING"])) {
		$salida .= '<option value="' . $nivel . 'menu_planning.php">Planning Targets</option>';
	}
	if (isset($_SESSION["GRP_AUDIT"])) {
		$salida .= '<option value="' . $nivel . 'menu_risk.php">Risk &amp; O</option>';
	}
	$salida .= '</select>';

	return $salida;
}

//////////////////////////////////// ADMINISTRACION ////////////////////////////////////
function menu_administracion($nivel = '', $collapse = true)
{
	$collapse = ($collapse == true) ? "collapse" : "";
	$activemenu = ($collapse == true) ? "" : "active";
	////----
	$salida = '';
	if (isset($_SESSION["GRP_GPADMIN"])) {
		$salida .= '<li class="' . $activemenu . '">';
		$salida .= '<a data-toggle="collapse" href="#administracio">';
		$salida .= '<i class="fa fa-users-cog"></i>';
		$salida .= '<p>';
		$salida .= 'Administraci&oacute;n';
		$salida .= '<b class="caret"></b>';
		$salida .= '</p>';
		$salida .= '</a>';
		$salida .= '<div class="' . $collapse . '" id="administracio">';
		$salida .= '<ul class="nav">';
		if (isset($_SESSION["GUSU"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPUSUARIOS/FRMusuarios.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-user"></i></span>';
			$salida .= '<span class="sidebar-normal"> Gestor de Usuarios </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GPERM"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPUSUARIOS/FRMasignacion_rol.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-key"></i></span>';
			$salida .= '<span class="sidebar-normal"> Administrador de Permisos </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["USUSED"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPUSUARIOS/FRMusuario_sede.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-bank"></i></span>';
			$salida .= '<span class="sidebar-normal"> Usuarios / Sedes </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["USUCAT"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPUSUARIOS/FRMusuario_categoria.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-user-plus"></i></span>';
			$salida .= '<span class="sidebar-normal"> Usuarios / Categor&iacute;as Chk. </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["USUCAT"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPUSUARIOS/FRMusuario_categoria_indicador.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-user-plus"></i></span>';
			$salida .= '<span class="sidebar-normal"> Usuarios / Categor&iacute;as Ind.</span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GPERM"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPMONEDA/FRMmoneda.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-money"></i></span>';
			$salida .= '<span class="sidebar-normal"> Gestor de Monedas</span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GPERM"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPVERSION/FRMversion.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="nc-icon nc-mobile"></i></span>';
			$salida .= '<span class="sidebar-normal"> Admin. Versiones</span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		$salida .= '</ul>';
		$salida .= '</div>';
		$salida .= '</li>';
	}
	return $salida;
}

function menu_gestion_tecnica($nivel = '', $collapse = true)
{
	$collapse = ($collapse == true) ? "collapse" : "";
	$activemenu = ($collapse == true) ? "" : "active";
	////----
	$salida = '';
	if (isset($_SESSION["GRP_GESTEC"])) {
		$salida .= '<li class="' . $activemenu . '">';
		$salida .= '<a data-toggle="collapse" href="#gestores">';
		$salida .= '<i class="fa fa-cogs"></i>';
		$salida .= '<p>';
		$salida .= 'Gestores T&eacute;cnicos';
		$salida .= '<b class="caret"></b>';
		$salida .= '</p>';
		$salida .= '</a>';
		$salida .= '<div class="' . $collapse . '" id="gestores">';
		$salida .= '<ul class="nav">';
		if (isset($_SESSION["GESSED"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPSEDE/FRMsede.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-bank"></i></span>';
			$salida .= '<span class="sidebar-normal"> Gestor de Sedes </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GESSEC"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPSECTOR/FRMsector.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-building-o"></i></span>';
			$salida .= '<span class="sidebar-normal"> Gestor de Sector | Torres </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GESARE"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPAREA/FRMarea.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-cube"></i></span>';
			$salida .= '<span class="sidebar-normal"> Gestor de &Aacute;reas </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GESDEP"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPDEPARTAMENTO/FRMdepartamento.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-building-o"></i></span>';
			$salida .= '<span class="sidebar-normal"> Gestor de Departamento </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GESCC"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPCCOSTO/FRMcentrocosto.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fab fa-creative-commons"></i></span>';
			$salida .= '<span class="sidebar-normal"> Gestor de Centros de Costo </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GENQR"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPAREA/FRMqrcode.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-qrcode"></i></span>';
			$salida .= '<span class="sidebar-normal"> Impresi&oacute;n de QR </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GESNOR"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPNORMA/FRMnorma.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-passport"></i></span>';
			$salida .= '<span class="sidebar-normal"> Gestor de Normas </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GESNOR"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPUMEDIDAS/FRMumedidas.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-ruler-combined"></i></span>';
			$salida .= '<span class="sidebar-normal"> Unidad de Medida </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		$salida .= '</ul>';
		$salida .= '</div>';
		$salida .= '</li>';
	}

	return $salida;
}

function menu_herramientas($nivel = '', $collapse = true)
{
	$collapse = ($collapse == true) ? "collapse" : "";
	////----
	$salida = '';
	if (isset($_SESSION["GRP_BIBLIOTECA"])) {
		$salida .= '<li>';
		$salida .= '<a href="' . $nivel . 'CPBIBLIOTECA/FRMbiblioteca.php">';
		$salida .= '<i class="fas fa-book-open"></i>';
		$salida .= '<p>Library</p>';
		$salida .= '</a>';
		$salida .= '</li>';
	}
	if (isset($_SESSION["GRP_ENCUESTA"])) {
		$salida .= '<li>';
		$salida .= '<a href="' . $nivel . 'menu_encuestas.php">';
		$salida .= '<i class="fas fa-list-ol"></i>';
		$salida .= '<p>Encuestas</p>';
		$salida .= '</a>';
		$salida .= '</li>';
	}
	if (isset($_SESSION["GRP_REQ"])) {
		$salida .= '<li>';
		$salida .= '<a href="' . $nivel . 'CPREQUISITOS/FRMrequisitos.php">';
		$salida .= '<i class="fa fa-sticky-note"></i>';
		$salida .= '<p>Compliance</p>';
		$salida .= '</a>';
		$salida .= '</li>';
	}

	if (isset($_SESSION["GRP_CKLIST"])) {
		$salida .= '<li>';
		$salida .= '<a href="' . $nivel . 'menu_checklist.php">';
		$salida .= '<i class="fa fa-check-square-o"></i>';
		$salida .= '<p>Check List</p>';
		$salida .= '</a>';
		$salida .= '</li>';
	}
	if (isset($_SESSION["GRP_HELPDESK"])) {
		$salida .= '<li>';
		$salida .= '<a href="' . $nivel . 'menu_helpdesk.php">';
		$salida .= '<i class="fas fa-toolbox"></i>';
		$salida .= '<p>Support </p>';
		$salida .= '</a>';
		$salida .= '</li>';
	}
	if (isset($_SESSION["GRP_PPM"])) {
		$salida .= '<li>';
		$salida .= '<a href="' . $nivel . 'menu_ppm.php">';
		$salida .= '<i class="fa fa-tools"></i>';
		$salida .= '<p>Maintenance Planner</p>';
		$salida .= '</a>';
		$salida .= '</li>';
	}
	if (isset($_SESSION["GRP_AUDIT"])) {
		$salida .= '<li>';
		$salida .= '<a href="' . $nivel . 'menu_auditoria.php">';
		$salida .= '<i class="fas fa-clipboard-list"></i>';
		$salida .= '<p>Audit Active </p>';
		$salida .= '</a>';
		$salida .= '</li>';
	}
	if (isset($_SESSION["GRP_INDICATOR"])) {
		$salida .= '<li>';
		$salida .= '<a href="' . $nivel . 'menu_indicador.php">';
		$salida .= '<i class="fas fa-chart-line"></i>';
		$salida .= "<p>Kpi's</p>";
		$salida .= '</a>';
		$salida .= '</li>';
	}
	if (isset($_SESSION["GRP_MEJORA"])) {
		$salida .= '<li>';
		$salida .= '<a href="' . $nivel . 'menu_mejora.php">';
		$salida .= '<i class="fas fa-sync-alt"></i>';
		$salida .= '<p>Improvement </p>';
		$salida .= '</a>';
		$salida .= '</li>';
	}
	if (isset($_SESSION["GRP_PROCESS"])) {
		$salida .= '<li>';
		$salida .= '<a href="' . $nivel . 'menu_process.php">';
		$salida .= '<i class="fab fa-creative-commons-sampling"></i>';
		$salida .= '<p>Process Manager</p>';
		$salida .= '</a>';
		$salida .= '</li>';
	}
	if (isset($_SESSION["GRP_PLANNING"])) {
		$salida .= '<li>';
		$salida .= '<a href="' . $nivel . 'menu_planning.php">';
		$salida .= '<i class="fa fa-crosshairs"></i>';
		$salida .= '<p>Planning Targets</p>';
		$salida .= '</a>';
		$salida .= '</li>';
	}
	if (isset($_SESSION["GRP_RYO"])) {
		$salida .= '<li>';
		$salida .= '<a href="' . $nivel . 'menu_risk.php">';
		$salida .= '<i class="fas fa-business-time"></i>';
		$salida .= '<p>Risk & O</p>';
		$salida .= '</a>';
		$salida .= '</li>';
	}
	return $salida;
}


//////////////////////////////////// HERRAMIENTAS ////////////////////////////////////

function menu_planning($nivel = '', $collapse = true)
{
	$salida = '';
	if (isset($_SESSION["GRP_PLANNING"])) {
		$salida .= '<li class="' . (($collapse) ? "" : "active") . '">';
		$salida .= '<a data-toggle="collapse" href="#gppm">';
		$salida .= '<i class="fa fa-crosshairs"></i>';
		$salida .= '<p>';
		$salida .= 'Planning Targets';
		$salida .= '<b class="caret"></b>';
		$salida .= '</p>';
		$salida .= '</a>';
		$salida .= '<div class="' . (($collapse) ? "collapse" : "") . '" id="gppm">';
		$salida .= '<ul class="nav">';
		if (isset($_SESSION["REPOBJ"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPPLANNINGDASHBOARD/FRMusuario.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-calendar"></i></span>';
			$salida .= '<span class="sidebar-normal"> Reporte de Objetivos </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["ACCOBJ"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPPLANNING/FRMacciones_objetivo.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-check"></i></span>';
			$salida .= '<span class="sidebar-normal"> Acciones de Objetivos </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["APPACC"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPPLANNING/FRMaprobacion.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-calendar-o"></i></span>';
			$salida .= '<span class="sidebar-normal"> Aprobaci&oacute;n de Acciones</span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["EJEACC"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPPLANNINGEJECUCION/FRMejecucion.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-pencil-square-o"></i></span>';
			$salida .= '<span class="sidebar-normal"> Ejecuci&oacute;n de Acciones</span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["EVAOBJ"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPPLANNINGEJECUCION/FRMevaluacion.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-search"></i></span>';
			$salida .= '<span class="sidebar-normal"> Evaluaci&oacute;n de Objetivos </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["CONOBJ"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPPLANNINGDASHBOARD/FRMgerencia.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-bar-chart-o"></i></span>';
			$salida .= '<span class="sidebar-normal"> Consolidado de Objetivos </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["REPPLA"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPPLANNING/FRMreportes.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-bar-chart-o"></i></span>';
			$salida .= '<span class="sidebar-normal"> Reportes </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		$salida .= '</ul>';
		$salida .= '</div>';
		$salida .= '</li>';
	}

	return $salida;
}

function menu_checklist($nivel = '', $collapse = true)
{
	$collapse = ($collapse == true) ? "collapse" : "";
	$activemenu = ($collapse == true) ? "" : "active";
	////----
	$salida = '';
	if (isset($_SESSION["GRP_CKLIST"])) {
		$salida .= '<li class="' . $activemenu . '">';
		$salida .= '<a data-toggle="collapse" href="#glistas">';
		$salida .= '<i class="fas fa-clipboard-list"></i>';
		$salida .= '<p>';
		$salida .= 'Checklist';
		$salida .= '<b class="caret"></b>';
		$salida .= '</p>';
		$salida .= '</a>';
		$salida .= '<div class="' . $collapse . '" id="glistas">';
		$salida .= '<ul class="nav">';
		if (isset($_SESSION["CATCHK"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPCATEGORIA/FRMcategoria_checklist.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-tags"></i></span>';
			$salida .= '<span class="sidebar-normal"> Categor&iacute;as (Check List) </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GESCHK"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPCHKLISTA/FRMlista.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-clipboard-list"></i></span>';
			$salida .= '<span class="sidebar-normal"> Gestor de Checklist </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["REGREVWEB"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPCHKREVISION/FRMejecutar.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-edit"></i></span>';
			$checklist_count = count_checklist();
			$salida .= '<span class="sidebar-normal"> Registrar Revisi&oacute;n <span class="badge badge-pill badge-danger">' .$checklist_count. '</span></span>	';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["REVRESULT"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPCHKREVISION/FRMrevisiones.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="nc-icon nc-paper"></i></span>';
			$salida .= '<span class="sidebar-normal"> Revisiones y Resultados </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["REPCHK"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPCHKREVISION/FRMreportes.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-bar-chart-o"></i></span>';
			$salida .= '<span class="sidebar-normal"> Reportes </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		$salida .= '</ul>';
		$salida .= '</div>';
		$salida .= '</li>';
	}

	return $salida;
}

function menu_helpdesk($nivel = '', $collapse = true)
{
	$collapse = ($collapse == true) ? "collapse" : "";
	$activemenu = ($collapse == true) ? "" : "active";
	////----
	$salida = '';
	if (isset($_SESSION["GRP_HELPDESK"])) {
		$salida .= '<li class="' . $activemenu . '">';
		$salida .= '<a data-toggle="collapse" href="#ghelpdesk">';
		$salida .= '<i class="fas fa-toolbox"></i>';
		$salida .= '<p>';
		$salida .= 'Problem Sweeper';
		$salida .= '<b class="caret"></b>';
		$salida .= '</p>';
		$salida .= '</a>';
		$salida .= '<div class="' . $collapse . '" id="ghelpdesk">';
		$salida .= '<ul class="nav">';
		if (isset($_SESSION["CATHD"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPCATEGORIA/FRMcategoria_helpdesk.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-tags"></i></span>';
			$salida .= '<span class="sidebar-normal"> Categor&iacute;as (Sweeper) </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GESTPRIO"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPPRIORIDADES/FRMprioridades.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-tag"></i></span>';
			$salida .= '<span class="sidebar-normal"> Gestor de Prioridades </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GESTPRIO"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPESCALONES/FRMcategorias.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-tags"></i></span>';
			$salida .= '<span class="sidebar-normal"> Gestor de Escalones </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GESTSTATUS"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPSTATUS/FRMstatus_helpdesk.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="nc-icon nc-sound-wave"></i></span>';
			$salida .= '<span class="sidebar-normal"> Gestor de Status (Sweeper)</span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GESTIPOINC"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPINCIDENTES/FRMincidentes.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-bell"></i></span>';
			$salida .= '<span class="sidebar-normal"> Gestor de Tipos de Incidentes </span>';
			$salida .= '</a>';
		}
		if (isset($_SESSION["NEWTICKET"])) {
			$salida .= '</li>';
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPTICKET/FRMnewticket.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-edit"></i></span>';
			$salida .= '<span class="sidebar-normal"> Nuevo Ticket </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		///--- Lo ven todos
		$salida .= '<li>';
		$salida .= '<a href="' . $nivel . 'CPTICKET/FRMsolicitados.php">';
		$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-info-circle"></i></span>';
		$salida .= '<span class="sidebar-normal"> Mis Tickets </span>';
		$salida .= '</a>';
		$salida .= '</li>';
		///---
		if (isset($_SESSION["GESTTICKET"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPTICKET/FRMtickets.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-exclamation-circle"></i></span>';
			$salida .= '<span class="sidebar-normal"> Tickets Asignados </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["REPHD"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPTICKET/FRMreportes.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-bar-chart-o"></i></span>';
			$salida .= '<span class="sidebar-normal"> Reportes </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		$salida .= '</ul>';
		$salida .= '</div>';
		$salida .= '</li>';
	}

	return $salida;
}

function menu_ppm($nivel = '', $collapse = true)
{
	$collapse = ($collapse == true) ? "collapse" : "";
	$activemenu = ($collapse == true) ? "" : "active";
	////----
	$salida = '';
	if (isset($_SESSION["GRP_PPM"])) {
		$salida .= '<li class="' . $activemenu . '">';
		$salida .= '<a data-toggle="collapse" href="#gppm">';
		$salida .= '<i class="fa fa-tools"></i>';
		$salida .= '<p>';
		$salida .= 'Maintenance Planner';
		$salida .= '<b class="caret"></b>';
		$salida .= '</p>';
		$salida .= '</a>';
		$salida .= '<div class="' . $collapse . '" id="gppm">';
		$salida .= '<ul class="nav">';
		if (isset($_SESSION["GACTIVOS"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPACTIVO/FRMactivo.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="nc-icon nc-tv-2"></i></span>';
			$salida .= '<span class="sidebar-normal"> Gestor de Activos </span>';
			$salida .= '</a>';
			$salida .= '</li>';
			$salida .= '<li>';
		}
		if (isset($_SESSION["FALLACTIVOS"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPACTIVO/FRMfalla.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-exclamation-circle"></i></span>';
			$salida .= '<span class="sidebar-normal"> Reporte de Fallas (Activo) </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["CATPPM"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPCATEGORIA/FRMcategoria_ppm.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-tags"></i></span>';
			$salida .= '<span class="sidebar-normal"> Categor&iacute;as (Mant. Planner) </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GCUEPPM"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPPPMPROGRA/FRMcuestionario.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-check-circle-o"></i></span>';
			$salida .= '<span class="sidebar-normal"> Gestor de Cuestionarios </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["PROGPPM"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPPPMPROGRA/FRMprogramacion.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-calendar"></i></span>';
			$salida .= '<span class="sidebar-normal"> Programaci&oacute;n </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["REPROGPPM"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPPPMPROGRA/FRMreprogramacion.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-calendar-o"></i></span>';
			$salida .= '<span class="sidebar-normal"> Re-Programaci&oacute;n </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["REASIGPPM"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPPPMPROGRA/FRMreasignacion.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-group"></i></span>';
			$salida .= '<span class="sidebar-normal"> Re-Asignaci&oacute;n </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["EJECUTAPPM"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPPPMEJECUCION/FRMejecucion.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-users-cog"></i></span>';
			$salida .= '<span class="sidebar-normal"> Ejecuci&oacute;n de Programa </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["REVPPM"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPPPMEJECUCION/FRMrevisiones.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-search"></i></span>';
			$salida .= '<span class="sidebar-normal"> Revisi&oacute;n Detallada </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["REPPPM"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPPPMEJECUCION/FRMreportes.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-bar-chart-o"></i></span>';
			$salida .= '<span class="sidebar-normal"> Reportes </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		$salida .= '</ul>';
		$salida .= '</div>';
		$salida .= '</li>';
	}

	return $salida;
}

function menu_auditoria($nivel = '', $collapse = true)
{
	$collapse = ($collapse == true) ? "collapse" : "";
	$activemenu = ($collapse == true) ? "" : "active";
	////----
	$salida = '';
	if (isset($_SESSION["GRP_AUDIT"])) {
		$salida .= '<li class="' . $activemenu . '">';
		$salida .= '<a data-toggle="collapse" href="#gaudit" aria-expanded="true">';
		$salida .= '<i class="fas fa-clipboard-list"></i>';
		$salida .= '<p>';
		$salida .= 'Auditor&iacute;a';
		$salida .= '<b class="caret"></b>';
		$salida .= '</p>';
		$salida .= '</a>';
		$salida .= '<div class="' . $collapse . '" id="gaudit">';
		$salida .= '<ul class="nav">';
		if (isset($_SESSION["CATAUDIT"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPCATEGORIA/FRMcategoria_auditoria.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-tags"></i></span>';
			$salida .= '<span class="sidebar-normal"> Categor&iacute;as (Audit Active) </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GCUEAUDIT"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPAUDCUESTIONARIO/FRMcuestionario.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-list-ol"></i></span>';
			$salida .= '<span class="sidebar-normal"> Gestor de Cuestionarios </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GCORREOAUDIT"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPAUDCORREO/FRMcorreos.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-envelope"></i></span>';
			$salida .= '<span class="sidebar-normal"> Configurar Correos </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GESTSTATUSAUDIT"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPSTATUS/FRMstatus_auditoria.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="nc-icon nc-sound-wave"></i></span>';
			$salida .= '<span class="sidebar-normal"> Gestor de Status</span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["PROGAUDIT"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPAUDCUESTIONARIO/FRMprogramar.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-calendar-plus"></i></span>';
			$salida .= '<span class="sidebar-normal"> Programaci&oacute;n de Auditor&iacute;a </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["EJEAUDT"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPAUDEJECUCION/FRMejecutar.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-edit"></i></span>';
			$salida .= '<span class="sidebar-normal"> Realizar Auditoria</span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["REVAUDIT"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPAUDEJECUCION/FRMrevisiones.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="nc-icon nc-paper"></i></span>';
			$salida .= '<span class="sidebar-normal"> Auditorias y Resultados </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["APROBAUDIT"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPAUDEJECUCION/FRMaprobaciones.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-clipboard-list"></i></span>';
			$salida .= '<span class="sidebar-normal"> Revisi&oacute;n y Aprobaci&oacute;n </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["DISOLUTAUDIT"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPAUDEJECUCION/FRMdisolucion.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fab fa-buromobelexperte"></i></span>';
			$salida .= '<span class="sidebar-normal"> Disoluci&oacute;n de Hallazgos </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["ACTAAUDIT"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPAUDACTA/FRMauditorias.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-file-contract"></i></span>';
			$salida .= '<span class="sidebar-normal"> Actas de Auditor&iacute;a </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["REPAUDIT"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPAUDEJECUCION/FRMreportes.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-bar-chart-o"></i></span>';
			$salida .= '<span class="sidebar-normal"> Reportes </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		$salida .= '</ul>';
		$salida .= '</div>';
		$salida .= '</li>';
	}

	return $salida;
}

function menu_mejora($nivel = '', $collapse = true)
{
	$collapse = ($collapse == true) ? "collapse" : "";
	$activemenu = ($collapse == true) ? "" : "active";
	////----
	$salida = '';
	if (isset($_SESSION["GRP_MEJORA"])) {
		$salida .= '<li class="' . $activemenu . '">';
		$salida .= '<a data-toggle="collapse" href="#gmejora" aria-expanded="true">';
		$salida .= '<i class="fas fa-sync-alt"></i>';
		$salida .= '<p>';
		$salida .= 'Continuous Improver';
		$salida .= '<b class="caret"></b>';
		$salida .= '</p>';
		$salida .= '</a>';
		$salida .= '<div class="' . $collapse . '" id="gmejora">';
		$salida .= '<ul class="nav">';
		if (isset($_SESSION["GINFOAUDIT"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPMEJORA/FRMquejas.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-comment-alt"></i></span>';
			$salida .= '<span class="sidebar-normal"> Gestor de Quejas </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GINFOAUDIT"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPMEJORA/FRMexternas.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-file-signature"></i></span>';
			$salida .= '<span class="sidebar-normal"> Gestor de Auditor&iacute;as Externas </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GINFOAUDIT"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPMEJORA/FRMhallazgo.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-search"></i></span>';
			$salida .= '<span class="sidebar-normal"> Identificaci&oacute;n de Hallazgos </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GINFOAUDIT"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPMEJORA/FRMplanes.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-scroll"></i></span>';
			$salida .= '<span class="sidebar-normal"> Analisis y Planes de Acci&oacute;n </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["APRRYO"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPMEJORA/FRMaprobacion.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-check-double"></i></span>';
			$salida .= '<span class="sidebar-normal"> Aprobaci&oacute;n y Correci&oacute;n </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["EJERYO"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPMEJORAEJECUCION/FRMejecucion.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fab fa-wpforms"></i></span>';
			$salida .= '<span class="sidebar-normal"> Ejecuci&oacute;n de Actividades </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["EVARYO"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPMEJORAEJECUCION/FRMevaluacion.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="far fa-star"></i></span>';
			$salida .= '<span class="sidebar-normal"> Evaluaci&oacute;n de Actividades </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["EVARYO"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPMEJORAEJECUCION/FRMverificacion.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-retweet"></i></span>';
			$salida .= '<span class="sidebar-normal"> Verificar Eficacia </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["REPMEJORA"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPMEJORADASHBOARD/FRMreportes.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-bar-chart-o"></i></span>';
			$salida .= '<span class="sidebar-normal"> Reportes </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		$salida .= '</ul>';
		$salida .= '</div>';
		$salida .= '</li>';
	}

	return $salida;
}

function menu_indicador($nivel = '', $collapse = true)
{
	$collapse = ($collapse == true) ? "collapse" : "";
	$activemenu = ($collapse == true) ? "" : "active";
	////----
	$salida = '';
	if (isset($_SESSION["GRP_INDICATOR"])) {
		$salida .= '<li class="' . $activemenu . '">';
		$salida .= '<a data-toggle="collapse" href="#gindicador">';
		$salida .= '<i class="fas fa-chart-line"></i>';
		$salida .= '<p>';
		$salida .= 'Indicadores';
		$salida .= '<b class="caret"></b>';
		$salida .= '</p>';
		$salida .= '</a>';
		$salida .= '<div class="' . $collapse . '" id="gindicador">';
		$salida .= '<ul class="nav">';
		// if (isset($_SESSION["CATINDICA"])) {
		// 	$salida .= '<li>';
		// 	$salida .= '<a href="' . $nivel . 'CPCATEGORIA/FRMcategoria_indicador.php">';
		// 	$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-tags"></i></span>';
		// 	$salida .= '<span class="sidebar-normal"> Categor&iacute;a</span>';
		// 	$salida .= '</a>';
		// 	$salida .= '</li>';
		// }
		// if (isset($_SESSION["CLASINDICA"])) {
		// 	$salida .= '<li>';
		// 	$salida .= '<a href="' . $nivel . 'CPCLASIFICA/FRMclasificacion_indicador.php">';
		// 	$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-tag"></i></span>';
		// 	$salida .= '<span class="sidebar-normal"> Clasificaci&oacute;n</span>';
		// 	$salida .= '</a>';
		// 	$salida .= '</li>';
		// }
		if (isset($_SESSION["REPINDICA"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPINDICADORDASHBOARD/FRMreporte.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-clipboard-list"></i></span>';
			$salida .= '<span class="sidebar-normal"> Mi Reporte</span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GESINDICA"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPINDICADOR/FRMmis_indicadores.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-user"></i></span>';
			$salida .= '<span class="sidebar-normal"> Mis Indicadores </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["EJEINDICA"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPINDREVISION/FRManotacion.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-edit"></i></span>';
			$salida .= '<span class="sidebar-normal"> Registrar Anotaci&oacute;n </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["REVINDICA"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPINDREVISION/FRMrevisiones.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="nc-icon nc-paper"></i></span>';
			$salida .= '<span class="sidebar-normal"> Anotaciones y Resultados </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["CONSINDICA"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPINDICADORDASHBOARD/FRMconsolidado.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-line-chart"></i></span>';
			$salida .= '<span class="sidebar-normal"> Consolidado y Estadisticas</span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["REPINDICA"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPINDREVISION/FRMreportes.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-bar-chart-o"></i></span>';
			$salida .= '<span class="sidebar-normal"> Reportes </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		$salida .= '</ul>';
		$salida .= '</div>';
		$salida .= '</li>';
	}

	return $salida;
}

function menu_procesos($nivel = '', $collapse = true)
{
	$collapse = ($collapse == true) ? "collapse" : "";
	$activemenu = ($collapse == true) ? "" : "active";
	////----
	$salida = '';
	if (isset($_SESSION["GRP_PROCESS"])) {
		$salida .= '<li class="' . $activemenu . '">';
		$salida .= '<a data-toggle="collapse" href="#gproceso">';
		$salida .= '<i class="fab fa-creative-commons-sampling"></i>';
		$salida .= '<p>';
		$salida .= 'Process Manager';
		$salida .= '<b class="caret"></b>';
		$salida .= '</p>';
		$salida .= '</a>';
		$salida .= '<div class="' . $collapse . '" id="gproceso">';
		$salida .= '<ul class="nav">';
	
		if (isset($_SESSION["GSISTEM"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPPROCESS/FRMNecExtInt.php?tipo=resultado">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-layer-group"></i></span>';
			$salida .= '<span class="sidebar-normal">Resultados Esperados</span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GSISTEM"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPPROCESS/FRMNecExtInt.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-layer-group"></i></span>';
			$salida .= '<span class="sidebar-normal">Partes Interesadas</span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GSISTEM"])) { //QAP CREAR PERMISO PROPIO
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPPROCESS/FRMexpectativas.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-layer-group"></i></span>';
			$salida .= '<span class="sidebar-normal">Necesidades y Expectativas</span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GSISTEM"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPSISTEMA/FRMsistema.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-layer-group"></i></span>';
			$salida .= '<span class="sidebar-normal"> Gestor de Sistemas </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GTRECURSOS"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPTIPORECURSOS/FRMtipo.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-tags"></i></span>';
			$salida .= '<span class="sidebar-normal"> Clasificaci&oacute;n de Recursos </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GFICHAPRO"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPPROCESS/FRMfichas.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-clipboard-list"></i></span>';
			$salida .= '<span class="sidebar-normal"> Fichas de Procesos </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}



		if (isset($_SESSION["MISFIC"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPPROCESS/FRMmis_fichas.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-user"></i></span>';
			//////	//////////////////////////////////////////////////////////
			$usuario = $_SESSION['codigo'];
			$totalFichasActualizacion = count_fichas_actualizacion_usuario($usuario);
			if($totalFichasActualizacion){
				$salida .= '<span class="sidebar-normal"> Mis Fichas de Procesos <span class="badge badge-pill badge-danger">'.$totalFichasActualizacion.'</span>';
			}else{
				$salida .= '<span class="sidebar-normal"> Mis Fichas de Procesos </span>';
			}
			/////////////////////////////////////////////////////////////////
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["APROFICHA"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPPROCESS/FRMaprobaciones.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-clipboard-check"></i></span>';
			///////////////////////////////////////////////////////////////////////
			$totalFichasSinAprobar = count_fichas_sin_aprobar();
			if($totalFichasSinAprobar){
				$salida .= '<span class="sidebar-normal"> Aprobaci&oacute;n de Fichas   <span class="badge badge-pill badge-danger">'. $totalFichasSinAprobar .'</span> </span>';
			}else{
				$salida .= '<span class="sidebar-normal"> Aprobaci&oacute;n de Fichas</span>';
			}
			//////////////////////////////////////////////////////////////////////
			$salida .= '</a>';
			$salida .= '</li>';
		}



		if (isset($_SESSION["ASIFICHA"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPPROCESS/FRMficha_usuario.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-group"></i></span>';
			$salida .= '<span class="sidebar-normal"> Asignaci&oacute;n de Procesos </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["APROFICHA"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPPROCESS/FRMreportes.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-bar-chart-o"></i></span>';
			$salida .= '<span class="sidebar-normal"> Reportes </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		$salida .= '</ul>';
		$salida .= '</div>';
		$salida .= '</li>';
	}

	return $salida;
}

function menu_risk($nivel = '', $collapse = true)
{
	$collapse = ($collapse == true) ? "collapse" : "";
	$activemenu = ($collapse == true) ? "" : "active";
	/////////////////////----
	$salida = '';
	if (isset($_SESSION["GRP_RYO"])) {
		$salida .= '<li class="' . $activemenu . '">';
		$salida .= '<a data-toggle="collapse" href="#gproceso">';
		$salida .= '<i class="fas fa-business-time"></i>';
		$salida .= '<p>';
		$salida .= 'R&O Manager';
		$salida .= '<b class="caret"></b>';
		$salida .= '</p>';
		$salida .= '</a>';
		$salida .= '<div class="' . $collapse . '" id="gproceso">';
		$salida .= '<ul class="nav">';
		if (isset($_SESSION["MIRRYO"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPRYODASHBOARD/FRMmireporte.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-clipboard-list"></i></span>';
			$salida .= '<span class="sidebar-normal"> Mi Reporte</span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["IDERYO"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPRYO/FRMmis_riesgos.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-exclamation-triangle"></i></span>';
			$salida .= '<span class="sidebar-normal">Mis Planes Aprobados</span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["IDERYO"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPRYO/FRMidentificacion.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-search"></i></span>';
			$salida .= '<span class="sidebar-normal"> Identificaci&oacute;n</span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["ANARYO"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPRYO/FRManalisis.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-percent"></i></span>';
			$salida .= '<span class="sidebar-normal"> An&aacute;lisis </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["VALRYO"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPRYO/FRMvalorizacion.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-balance-scale"></i></span>';
			$salida .= '<span class="sidebar-normal"> Valorizaci&oacute;n </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["TRARYO"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPRYO/FRMtratamiento.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-gavel"></i></span>';
			$salida .= '<span class="sidebar-normal"> Tratamiento </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["APRRYO"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPRYO/FRMaprobacion.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-check-double"></i></span>';
			$salida .= '<span class="sidebar-normal"> Aprobaci&oacute;n de Planes </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["EJERYO"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPRYOEJECUCION/FRMejecucion.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fab fa-wpforms"></i></span>';
			$salida .= '<span class="sidebar-normal"> Ejecuci&oacute;n de Actividades </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["EVARYO"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPRYOEJECUCION/FRMevaluacion.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="far fa-star"></i></span>';
			$salida .= '<span class="sidebar-normal"> Evaluaci&oacute;n de Actividades </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["MATRYO"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPRYO/FRMmaterializacion.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-fire-alt"></i></span>';
			$salida .= '<span class="sidebar-normal"> Materializar Riesgos </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["CONRYO"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPRYODASHBOARD/FRMconsolidado.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-line-chart"></i></span>';
			$salida .= '<span class="sidebar-normal"> Consolidado y Estadisticas</span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["REPRYO"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPRYODASHBOARD/FRMreportes.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-file-excel"></i></span>';
			$salida .= '<span class="sidebar-normal"> Reportes </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		$salida .= '</ul>';
		$salida .= '</div>';
		$salida .= '</li>';
	}

	return $salida;
}

function menu_encuestas($nivel = '', $collapse = true)
{
	$collapse = ($collapse == true) ? "collapse" : "";
	$activemenu = ($collapse == true) ? "" : "active";
	////----
	$salida = '';
	if (isset($_SESSION["GRP_ENCUESTA"])) {
		$salida .= '<li class="' . $activemenu . '">';
		$salida .= '<a data-toggle="collapse" href="#gencuestas">';
		$salida .= '<i class="fas fa-tasks"></i>';
		$salida .= '<p>';
		$salida .= 'Encuestas';
		$salida .= '<b class="caret"></b>';
		$salida .= '</p>';
		$salida .= '</a>';
		$salida .= '<div class="' . $collapse . '" id="gencuestas">';
		$salida .= '<ul class="nav">';
		if (isset($_SESSION["CATENC"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'ENCCUESTIONARIO/FRMcategoria.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-tags"></i></span>';
			$salida .= '<span class="sidebar-normal"> Categor&iacute;as (Encuestas) </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GCUEENC"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'ENCCUESTIONARIO/FRMcuestionario.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-clipboard-list"></i></span>';
			$salida .= '<span class="sidebar-normal"> Gestor de Cuestionarios </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["INVITARENC"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'ENCCUESTIONARIO/FRMinvitar.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-mail-bulk"></i></span>';
			$salida .= '<span class="sidebar-normal"> Invitaciones a Clientes </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["RESULTENC"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'ENCEJECUCION/FRMrevisiones.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="nc-icon nc-paper"></i></span>';
			$salida .= '<span class="sidebar-normal"> Revisi&oacute;n </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["REPENC"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'ENCEJECUCION/FRMresultados.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-bar-chart-o"></i></span>';
			$salida .= '<span class="sidebar-normal"> Reportes </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		$salida .= '</ul>';
		$salida .= '</div>';
		$salida .= '</li>';
	}

	return $salida;
}

function menu_biblioteca($nivel = '', $collapse = true)
{
	$collapse = ($collapse == true) ? "collapse" : "";
	$activemenu = ($collapse == true) ? "" : "active";
	////----
	$salida = '';
	if (isset($_SESSION["GRP_BIBLIOTECA"])) {
		$salida .= '<li class="' . $activemenu . '">';
		$salida .= '<a data-toggle="collapse" href="#gbiblioteca">';
		$salida .= '<i class="fas fa-book-open"></i>';
		$salida .= '<p>';
		$salida .= 'Biblioteca';
		$salida .= '<b class="caret"></b>';
		$salida .= '</p>';
		$salida .= '</a>';
		$salida .= '<div class="' . $collapse . '" id="gbiblioteca">';
		$salida .= '<ul class="nav">';
		if (isset($_SESSION["CATBIBLIO"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPBIBLIOTECA/FRMcategoria.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-tags"></i></span>';
			$salida .= '<span class="sidebar-normal"> Categor&iacute;as (Biblioteca) </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GBIBLIO"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPBIBLIOTECA/FRMdocumento.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-clipboard-list"></i></span>';
			$salida .= '<span class="sidebar-normal"> Gestor de Documentos </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["VERBIBLIO"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPBIBLIOTECA/FRMversion.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-code-branch"></i></span>';
			$salida .= '<span class="sidebar-normal"> Versionamiento </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["APROBIBLIO"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPBIBLIOTECA/FRMaprobacion.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-check-circle"></i></span>';
			$salida .= '<span class="sidebar-normal"> Aprobaciones </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["BIBLIOTECA"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPBIBLIOTECA/FRMbiblioteca.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-book-open"></i></span>';
			$salida .= '<span class="sidebar-normal"> Biblioteca </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		$salida .= '</ul>';
		$salida .= '</div>';
		$salida .= '</li>';
	}

	return $salida;
}

function menu_requisitos($nivel = '', $collapse = true)
{
	$collapse = ($collapse == true) ? "collapse" : "";
	$activemenu = ($collapse == true) ? "" : "active";
	////----
	$salida = '';
	if (isset($_SESSION["GRP_REQ"])) {
		$salida .= '<li class="' . $activemenu . '">';
		$salida .= '<a data-toggle="collapse" href="#gbiblioteca">';
		$salida .= '<i class="fa fa-sticky-note"></i>';
		$salida .= '<p>';
		$salida .= 'Requisitos';
		$salida .= '<b class="caret"></b>';
		$salida .= '</p>';
		$salida .= '</a>';
		$salida .= '<div class="' . $collapse . '" id="gbiblioteca">';
		$salida .= '<ul class="nav">';
		if (isset($_SESSION["GEDOC"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPREQUISITOS/FRMdocumentos.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-book-open"></i></span>';
			$salida .= '<span class="sidebar-normal"> Gestor de Documentos </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GESREQ"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPREQUISITOS/FRMrequisito.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fa fa-sticky-note"></i></span>';
			$salida .= '<span class="sidebar-normal"> Gestor de requisitos </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GESREQ"])) {
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPREQUISITOS/FRMrequisito_procesos.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-group"></i></span>';
			$salida .= '<span class="sidebar-normal"> Asignacion de requisitos </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GESREQ"])) {  //QAP CREAR PERMISO
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPREQUISITOS/FRMrequisito_plan.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-calendar-alt"></i></span>';
			$salida .= '<span class="sidebar-normal"> Plan de Evaluaci&oacuten </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}
		if (isset($_SESSION["GESREQ"])) {  //QAP CREAR PERMISO
			$salida .= '<li>';
			$salida .= '<a href="' . $nivel . 'CPREQUISITOS/FRMrequisito_evaluar.php">';
			$salida .= '<span class="sidebar-mini-icon"><i class="fas fa-tasks"></i></span>';
			$salida .= '<span class="sidebar-normal"> Evaluar Requisitos </span>';
			$salida .= '</a>';
			$salida .= '</li>';
		}


		$salida .= '</ul>';
		$salida .= '</div>';
		$salida .= '</li>';
	}

	return $salida;
}