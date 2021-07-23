<?php
	include_once('html_fns_reportes.php');
	$nombre = utf8_decode($_SESSION["nombre"]);
	$rol = $_SESSION["rol"];
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Check In Control</title>
    <link rel="shortcut icon" href="../../../CONFIG/img/icon.ico">
	<!-- Bootstrap -->
    <link href="../../css.1.1.1/bootstrap.min.css" rel="stylesheet">
    <link href="../../font-awesome/css/font-awesome.css" rel="stylesheet">
    <!-- template CSS --> 
    <link href="../../css.1.1.1/animate.css" rel="stylesheet">
    <link href="../../css.1.1.1/style.css" rel="stylesheet">
	<!-- Data Table plugin CSS -->
	<link href="../../css.1.1.1/plugins/dataTables/datatables.min.css" rel="stylesheet">
    <!-- Estilos Utilitarios -->
    <link href="../../css.1.1.1/formulario.css" rel="stylesheet">
    <link href="../../css.1.1.1/custom.fonts.css" rel="stylesheet">
</head>

<body class="pace-done body-small fixed-sidebar">
<div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu font-10" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element text-center">
                            <img alt="image" class="img-rounded" src="../../../CONFIG/img/icon.png" width="60%" />
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?php echo $nombre; ?></strong>
                             </span> <span class="text-muted text-xs block"><?php echo $_SESSION["rol_description"]; ?> <b class="caret"></b></span> </span> </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a href="../../logout.php"><i class="fa fa-power-off"></i> Salir</a></li>
                            </ul>
                    </div>
                    <div class="logo-element">
                        <span class="icon-gto"></span>
                    </div>
                </li>
                <li class="active">
                    <a href="index.html">
						<i class="fa fa-list"></i>
						<span class="nav-label">Men&uacute; </span>
						<span class="fa arrow"></span>
					</a>
					<ul class="nav nav-second-level">
						<li>
							<a href="../FRMdepartamento.php">
							<i class="fa fa-building-o"></i> Gestor de Departamentos
							</a>
						</li>
						<li class="active">
							<a href="FRMreplistado.php">
							<i class="fa fa-print"></i> Reporte de Departamentos
							</a>
						</li>
						<hr class="hr-line-dashed">
						<li>
							<a href="../../menu.php">
							
							</a>
                        </li>
						<li>
							<a href="../../menu.php">
							<i class="glyphicon glyphicon-list"></i> Men&uacute; Principal
							</a>
                        </li>
					</ul>
                </li> 
            </ul>

        </div>
    </nav>

    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <a href="../../logout.php">
                            <i class="fa fa-power-off"></i> Salir
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="wrapper wrapper-content">
            <div class="panel panel-default">
				<div class="panel-heading"><i class="fa fa-print"></i> &nbsp; Reporte de Departamentos</div>
                <div class="panel-body" id = "cuerpo">
					<form name = "f1" id = "f1" action="REPlista.php" method="post" target = "_blank">
						<div class="row">
							<div class="col-md-6 col-md-offset-3 text-center">
								<a target="_blank" class="btn btn-primary" href = "REPlista.php"><span class="fa fa-print"></span> Exportar a PDF</a>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-lg-12">
								<?php
									echo tabla_departamentos("");
								?>
							</div>
						</div>
					</form>
				</div>
				<!-- /.panel-body -->
			</div>
		</div>
		<!-- /.wrapper wrapper-content -->
		
        <div class="footer">
            <div class="pull-right">
                Powered by Farasi Software <strong>Versi&oacute;n 1.1.1</strong>
            </div>
            <div>
                <strong>Copyright</strong> <i class="icon-gto"></i> &copy; <?php echo date("Y"); ?>
            </div>
        </div>

    </div>
</div><!-- //////////////////////////////////////////////////////// -->
    
		<?php echo modal("../../"); ?>

    <!-- Mainly scripts -->
    <script src="../../js.1.1.1/jquery-2.1.1.js"></script>
    <script src="../../js.1.1.1/bootstrap.min.js"></script>
    <script src="../../js.1.1.1/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../../js.1.1.1/plugins/slimscroll/jquery.slimscroll.min.js"></script><!-- Custom and plugin javascript -->
	<script src="../../js.1.1.1/inspinia.js"></script>
	<script src="../../js.1.1.1/plugins/pace/pace.min.js"></script>
	<script src="../../js.1.1.1/plugins/dataTables/datatables.min.js"></script>
    <script type="text/javascript" src="../../js.1.1.1/modules/seguridad/usuario.js"></script>
    <script type="text/javascript" src="../../js.1.1.1/modules/ejecutaModal.js"></script>
    <script type="text/javascript" src="../../js.1.1.1/modules/util.js"></script><!-- Page-Level Scripts -->
    <script>
        $(document).ready(function(){
            $('.dataTables-example').DataTable({
                pageLength: 50,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                 buttons: [
                    {extend: 'copy'},
                    {extend: 'csv', title: 'Reporte de Departamentos'},
                    {extend: 'excel', title: 'Reporte de Departamentos'},
                    
                    {extend: 'print',
						customize: function (win){
							$(win.document.body).addClass('white-bg');
							$(win.document.body).css('font-size', '10px');						$(win.document.body).find('table')
									.addClass('compact')
									.css('font-size', 'inherit');
						},
						title: 'Reporte de Departamentos'
                    }
                ]
            });
		});
    </script>
</body>
</html>
