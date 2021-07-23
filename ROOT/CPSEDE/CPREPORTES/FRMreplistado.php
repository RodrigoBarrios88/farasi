<?php
include_once('html_fns_reportes.php');
validate_login("../../");
$nombre = utf8_decode($_SESSION["nombre"]);
$rol = $_SESSION["rol"];
?>
<!DOCTYPE html>
<html>

<head>
    <?php echo head("../../"); ?>
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
                                <a href="../FRMsede.php">
                                    <i class="fa fa-building"></i> Gestor de Sedes (Clientes)
                                </a>
                            </li>
                            <li class="active">
                                <a href="FRMreplistado.php">
                                    <i class="fa fa-print"></i> Reporte de Sedes
                                </a>
                            </li>
                            <hr class="hr-line-dashed">
                            <li>
                                <a href="../../menu_mensajeria.php">
                                    <i class="fa fa-bars"></i> Men&uacute; de Mensajer&iacute;a
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
                    <div class="panel-heading"><label><i class="fa fa-print"></i> &nbsp; Reporte de Sedes</label></div>
                    <div class="panel-body" id="cuerpo">
                        <form name="f1" id="f1" action="REPlista.php" method="post" target="_blank">
                            <div class="row">
                                <div class="col-xs-12 col-md-12 text-right"><label class=" text-info">* Campos de Busqueda</label></div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1"><label>Cliente:</label> <span class="text-danger">*</span></div>
                                <div class="col-md-5"><label>Nombre de la Sede:</label> <span class="text-danger">*</span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1">
                                    <?php echo clientes_html("cli"); ?>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="nom" id="nom" onkeyup="texto(this)" />
                                    <input type="hidden" name="cod" id="cod" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1"><label>Situaci&oacute;n:</label> <span class="text-danger">*</span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1">
                                    <select class="form-control" name="sit" id="sit">
                                        <option value="1">ACTIVAS</option>
                                        <option value="0">INACTIVAS</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3 text-center">
                                    <button type="button" class="btn btn-primary" id="busc" onclick="Submit();"><span class="fa fa-print"></span> Imprimir</button>
                                    <button type="button" class="btn btn-white" id="btn-limpiar" onclick="Limpiar();"><i class="fas fa-eraser"></i> Limpiar</button>
                                </div>
                            </div>
                            <br>
                        </form>
                    </div>
                    <!-- /.panel-body -->
                </div>
            </div>
            <!-- /.wrapper wrapper-content -->

            <div class="footer">
                <div class="pull-right">
                    <small>Powered by Farasi Software</small>
                </div>
                <div>
                    <strong>Copyright</strong> <i class="icon-gto"></i> &copy; <?php echo date("Y"); ?>
                </div>
            </div>

        </div>
    </div><!-- //////////////////////////////////////////////////////// -->

    <?php echo modal("../../"); ?>

    <!-- Mainly scripts -->
    <script src="../../js.1.9.2/jquery-2.1.1.js"></script>
    <script src="../../js.1.9.2/bootstrap.min.js"></script>
    <script src="../../assets.1.2.8/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../../assets.1.2.8/js/plugins/slimscroll/jquery.slimscroll.min.js"></script><!-- Custom and plugin javascript -->
    <script src="../../js.1.9.2/inspinia.js"></script>
    <script src="../../assets.1.2.8/js/plugins/pace/pace.min.js"></script>
    <script type="text/javascript" src="../../assets.1.2.8/js/modules/seguridad/usuario.js"></script>
    <script type="text/javascript" src="../../assets.1.2.8/js/modules/ejecutaModal.js"></script>
    <script type="text/javascript" src="../../assets.1.2.8/js/modules/util.js"></script>
</body>

</html>