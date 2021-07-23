<?php
include_once('html_fns.php');
validate_login();
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head(); ?>
	<style>
		.card-stats .card-body .numbers {
			font-size: 14px;
		}
	</style>
</head>

<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("", "herramientas"); ?>
		<div class="main-panel">
			<?php echo navbar(); ?> 
			<div class="content">
				<div class="row">
					<?php echo get_card("GRP_PROCESS", "menu_process.php", "Process Manager", "fab fa-creative-commons-sampling","ProcessManager"); ?>
					<?php echo get_card("GRP_PLANNING", "menu_planning.php", "Planning Targets", "fa fa-crosshairs", ''); ?>
					<?php echo get_card("GRP_RYO", "menu_risk.php", "Risk & O", "fas fa-business-time", ""); ?>
					<?php echo get_card("GRP_PPM", "menu_ppm.php", "Maintenance Planner", "fa fa-tools", ""); ?>
				</div>
				<div class="row">
					<?php echo get_card("GRP_CKLIST", "menu_checklist.php", "Check List", "fa fa-check-square-o",""); ?>
					<?php echo get_card("GRP_HELPDESK", "menu_helpdesk.php", "Support", "fa fa-toolbox" ,""); ?>
					<?php echo get_card("GRP_INDICATOR", "menu_indicador.php", "Kpi's", "fa fa-chart-line",""); ?>
					<?php echo get_card("GRP_AUDIT", "menu_auditoria.php", "Audit Active", "fa fa-clipboard-list",""); ?>
				</div>
				<div class="row">
					<?php echo get_card("GRP_MEJORA", "menu_mejora.php", "Improvement", "fa fa-sync-alt",""); ?>
					<?php echo get_card("GRP_BIBLIOTECA", "CPBIBLIOTECA/FRMbiblioteca.php", "Library", "fas fa-book-open",""); ?>
					<?php echo get_card("GRP_REQ", "CPREQUISITOS/FRMrequisito.php", "Compliance", "fa fa-sticky-note",""); ?>
					<?php echo get_card("GRP_ENCUESTA", "menu_encuestas.php", "Encuestas", "fas fa-list-ol",""); ?>
				</div>
				<div class="row">
					<div class="col-md-12 ml-auto mr-auto">
						<div class="card card-calendar">
							<div class="card-body" id="calendarContainer"> </div>
						</div>
					</div>
				</div>
			</div>
			<?php echo footer() ?>
		</div>
	</div>
	<?php echo scripts(); ?>
	<script type="text/javascript" src="assets.1.2.8/js/modules/menu/menu.js"></script>
</body>

</html>
<?php
function get_card($perm, $menu, $titulo, $icono, $modulo)
{
	$salida = '<div class="col-lg-3 col-md-6 col-sm-6">';
	$class = "";
	if (!isset($_SESSION[$perm])) {
		$class = "text-muted";
		$icono = $icono . " text-muted";
		$menu = "";
	} else {
		$icono = $icono . " text-primary";
	}
	$salida .= '<div class="card card-stats">';
	$salida .= '<div class="card-body ">';
	$salida .= '<a href="' . $menu . '" target="_blank">';
	$salida .= '<div class="row">';
	$salida .= '<div class="col-5 col-md-4">';
	$salida .= '<div class="icon-big text-center icon-primary">';
	$salida .= '<i class="' . $icono . '"></i>';
	$salida .= '</div></div>';
	$salida .= '<div class="col-7 col-md-8">';
	$salida .= '<div class="numbers">';
	$salida .= '<p class="card-category ' . $class . '">M&oacute;dulo</p>';
	$salida .= '<p class="card-title ' . $class . '">' . $titulo . '</p>';
	$salida .= '</div></div></div></a></div><div>';
	$salida .= '<div class="card-footer text-right">';
	$salida .= '<hr>';
	$salida .= '<a href="' . $menu . '" target="_blank">';
	$salida .= '<div class="stats ' . $class . '">';
	//if($modulo == "ProcessManager"){
		//$totalFichasSinAprobar = count_fichas_sin_aprobar();
		//if($totalFichasSinAprobar){
		//	$salida .= '<span class="count_aprobation_menu">'.$totalFichasSinAprobar.'</span> Ir al M&oacute;dulo<i class="fa fa-chevron-right"></i>';
		//}else{
			$salida .= 'Ir al M&oacute;dulo <i class="fa fa-chevron-right"></i>';	
		//}
	//}else{
	//	$salida .= 'Ir al M&oacute;dulo <i class="fa fa-chevron-right"></i>';
	//}
	$salida .= '</div></a></div></div></div></div>';
	return $salida;
}
?>