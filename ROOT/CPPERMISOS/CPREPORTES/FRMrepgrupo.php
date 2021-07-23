<?php
	include_once('html_fns_reportes.php');
	$nombre = $_SESSION["nombre"];
	$sucursal = $_SESSION['sucursal'];
	$nivel = $_SESSION["nivel"];
	$valida = $_SESSION["GRP_GPADMIN"];
if($nivel != "" && $nombre != "" && $sucursal != ""){ 
if($valida == 1){ 	
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> ASMS </title>
	<link rel="shortcut icon" href="../../images/icono.ico">
   <!-- CSS personalizado -->
    <link href="../../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">    <!-- Bootstrap Core CSS -->
    <link href="../../css.1.1.1/formulario.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="../../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="../../bower_components/datatables-responsive/css.1.1.1/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../../dist/css.1.1.1/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../../bower_components/font-awesome/css.1.1.1/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js.1.1.1/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">C<small>olegio</small>&nbsp;  L<small>os</small>&nbsp;  O<small>Livos del</small>&nbsp;  N<small>orte</small></a>
            </div>
            <!-- /.navbar-header -->
            <ul class="nav navbar-top-links navbar-right">
               <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> Perfil de Usuario</a></li>
                        <li class="divider"></li>
                        <li><a href="../../logout.php"><i class="glyphicon glyphicon-off fa-fw"></i> Salir</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="glyphicon glyphicon-question-sign fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="glyphicon glyphicon-question-sign fa-fw"></i> Ayuda</a></li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                       <li>
                            <a href="#"><i class="glyphicon glyphicon-list"></i> Men&uacute;<span class="glyphicon arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
									<a href="FRMrepgrupo.php"><i class="glyphicon glyphicon-briefcase"></i> Permisos<span class="glyphicon arrow"></span></a>
									<ul class="nav nav-third-level collapse">
										<li>
										<a href="../FRMgrupo.php"><i class="glyphicon glyphicon-king"></i> Grupos de Permisos</a>
										</li>
										<li>
										<a href="../FRMpermiso.php"><i class="glyphicon glyphicon-pawn"></i> Permisos</a>
										</li>
										<li>
										<a href="FRMrepgrupo.php"><i class="fa fa-print"></i> Reporte Grupo/Permisos</a>
										</li>
										<li>
										<a href="FRMreppermiso.php"><i class="fa fa-print"></i> Reporte de Permisos</a>
										</li>
									</ul>
									<!-- /.nav-second-level -->
								</li>
								<li>
									<a href="#"><i class="glyphicon glyphicon-briefcase"></i> Roles<span class="glyphicon arrow"></span></a>
									<ul class="nav nav-third-level collapse">
										<li>
										<a href="../FRMrollver.php"><i class="glyphicon glyphicon-eye-open"></i> Ver Roles</a>
										</li>
										<li>
										<a href="../FRMroll.php"><i class="glyphicon glyphicon-copy"></i> Nuevo Rol</a>
										</li>
										<li>
										<a href="../FRMrollmod.php"><i class="glyphicon glyphicon-refresh"></i> Actualizar Roles</a>
										</li>
										<li>
										<a href="../FRMrolldel.php"><i class="glyphicon glyphicon-floppy-remove"></i> Deshabilitar Roles</a>
										</li>
										 <li>
										<a href="FRMreproll.php"><i class="fa fa-print"></i> Reporte de Roles</a>
										</li>
									</ul>
									<!-- /.nav-second-level -->
								</li>
								<hr>
                                <li>
                                    <a href="../../menu.php"><i class="glyphicon glyphicon-list"></i> Men&uacute; Principal</a>
                                </li>
							</ul>
                            <!-- /.nav-second-level -->
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
		<form name = "f1" id = "f1" action="REPgrupo.php" method="post" target = "_blank">
            <br>
            <div class="panel panel-default">
				<div class="panel-heading"><label class = "etiqueta"><i class="fa fa-print"></i> Reporte de Grupos de Permiso</label></div>
                <div class="panel-body">
					<div class="row">
						<div class="col-xs-12 col-xs-12 text-right"> <label class = " text-info">* Campos de Busqueda</label></div>
					</div>
                    <div class="row">
                        <div class="col-xs-3 text-right"><label class = "etiqueta">Nombre del Grupo: <span class="text-info">*</span></div>
                        <div class="col-xs-3"><input type = "text" class="form-control" name = "desc" id = "desc" onkeyup = "texto(this)" /><input type = "hidden" name = "cod" id = "cod" /></div>
                        <div class="col-xs-2 text-right"><label class = "etiqueta">Clave: <span class="text-info">*</span></div>
                        <div class="col-xs-3 text-left"><input type = "text" class="form-control" name = "clv" id = "clv" onkeyup = "texto(this)" /></div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-6 col-xs-12 col-lg-offset-3 text-center">
                            <button type="button" class="btn btn-info" id = "busc" onclick = "Submit();"><span class="fa fa-search"></span> Buscar</button>
                            <button type="button" class="btn btn-white" id = "btn-limpiar" onclick = "Limpiar();"><i class="fas fa-eraser"></i> Limpiar</button>
                        </div>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
             <!-- /.panel-default -->
		</form>	
        </div>
        <!-- /#page-wrapper -->
        
    </div>
    <!-- /#wrapper -->    <!-- //////////////////////////////////////////////////////// -->
    <!-- //////////////////////////////////////////////////////// -->
    
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div id = "ModalDialog" class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
			<h4 class="modal-title text-left" id="myModalLabel"><img src="../../images/logo.png" width = "60px;" /></h4>
	      </div>
	      <div class="modal-body text-center" id= "lblparrafo">
			<img src="../../images/img-loader.gif"/><br>
			<label align ="center">Transaccion en Proceso...</label>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
			</div>
	      </div>
	      <div class="modal-body" id= "Pcontainer">
		
	      </div>
	    </div>
	  </div>
	</div>
        <!-- jQuery -->
    <script src="../../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../../bower_components/bootstrap/dist/js.1.1.1/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="../../bower_components/datatables/media/js.1.1.1/jquery.dataTables.min.js"></script>
    <script src="../../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
    <script src="../../dist/js.1.1.1/sb-admin-2.js"></script>    
    <script type="text/javascript" src="../../js.1.1.1/core/ejecutaModal.js"></script>
    <script type="text/javascript" src="../../js.1.1.1/modules/seguridad/permiso.js"></script>
    <script type="text/javascript" src="../../js.1.1.1/core/util.js"></script>

</body>
</html>
<?php
}else{
	echo "<form id='f1' name='f1' action='../../menu.php' method='post'>";
	echo "<script>document.f1.submit();</script>";
	echo "</form>";
} 
}else{
	echo "<form id='f1' name='f1' action='../../logout.php' method='post'>";
	echo "<script>document.f1.submit();</script>";
	echo "</form>";
}
?>