<?php
include_once('html_fns_planning.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$tipo = $_REQUEST["tipo"];
$presupuesto = $_REQUEST["presupuesto"];
$hashkey = $_REQUEST["hashkey"];
$ClsFic = new ClsFicha();
$codigo = $ClsFic->decrypt($hashkey, $id);

$ClsObj = new ClsObjetivo();
$rs = $ClsObj->get_objetivo($codigo);
foreach ($rs as $row) {
    $objetivo = utf8_decode($row["obj_descripcion"]);
    $proceso = utf8_decode($row["fic_nombre"]);
    $sistema = utf8_decode($row["sis_nombre"]);
}
// Revision
$rs = $ClsObj->get_revision("", "", "", $id, $codigo);
$revision = 0;
foreach ($rs as $row) {
    $observacion = utf8_decode($row["rev_observacion"]);
    $revision = trim($row["rev_codigo"]);
}
//--
$last = new DateTime();
$last->modify('last day of this month');
$ultimo = $last->format('d');
//--
$desde = date("d/m/Y"); //valida que si no se selecciona fecha, coloque la del dia
$hasta = date("$ultimo/m/Y"); //valida que si no se selecciona fecha, coloque la del dia
?>
    <!DOCTYPE html>
    <html>

    <head>
        <?php echo head("../"); ?>

    </head>

    <body class="sidebar-mini">
        <div class="wrapper ">
           <?php echo sidebar("../","planning"); ?>
            <div class="main-panel">
                <?php echo navbar("../"); ?>
                <div class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card demo-icons">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fa fa-dot-circle-o "></i> Informaci&oacute;n del objetivo
                                    </h5>
                                </div>
                                <div class="card-body all-icons">
                                    <div class="row">
                                        <div class="col-xs-6 col-md-6 text-left"> </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Proceso:</label>
                                                    <input type="text" class="form-control" value="<?php echo $proceso; ?>" disabled />
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Sistema:</label>
                                                    <input type="text" class="form-control" value="<?php echo $sistema; ?>" disabled />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Descripcion:</label>
                                                    <textarea rows="2" class="form-control" disabled><?php echo $objetivo; ?></textarea>
                                                </div>
                                            </div>
                                            <?php if ($observacion != "") { ?>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Observacion de la Revision:</label>
                                                        <textarea rows="2" class="form-control" disabled><?php echo $observacion; ?></textarea>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card demo-icons">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fa fa-exclamation-circle"></i> Acciones
                                        <a class="btn btn-white btn-lg sin-margin pull-right" href="FRMacciones_objetivo.php"><small><i class="fa fa-chevron-left"></i> Atr&aacute;s</small></a>
                                    </h5>
                                </div>
                                <div class="card-body all-icons">
                                    <form name="f1" id="f1" action="" method="get">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Nombre:</label> <span class="text-danger">*</span>
                                                <input type="text" class="form-control" id="nombre" value="<?php echo $nombre; ?>" />
                                            </div>
                                            <div class="col-md-6">
                                                <label>Presupuesto:</label> <span class="text-danger">*</span>
                                                <input type="number" class="form-control" id="presupuesto" value="<?php echo $presupuesto; ?>" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Periodicidad:</label> <span class="text-danger">*</span>
                                                <select class="form-control select2" name="tipo" id="tipo" onchange="cambiaTipo();">
                                                    <option value="">Seleccione</option>
                                                    <option value="U">&Uacute;nica</option>
                                                    <option value="W">Semanal</option>
                                                    <option value="M">Mensual</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Fechas:</label> <span class="text-danger">*</span>
                                                <div class="form-group" id="range">
                                                    <div class="input-daterange input-group" id="datepicker">
                                                        <input type="text" class="input-sm form-control" id="desde" value="<?php echo $desde ?>" />
                                                        <input hidden type="text" class="input-sm form-control" id="hoy" value="<?php echo date("d/m/Y"); ?>" />
                                                        <span class="input-group-addon"> &nbsp; <i class="fa fa-calendar"></i> &nbsp; </span>
                                                        <input type="text" class="input-sm form-control" id="hasta" value="<?php echo $hasta ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row hidden" id="rangos">
                                            <div class="col-md-6">
                                                <label>Dia planificado:</label> <span class="text-danger">*</span>
                                                <select class="form-control select2" id="inicio">

                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Dia Limite:</label> <span class="text-danger">*</span>
                                                <select class="form-control select2" id="fin">

                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label>Descripci&oacute;n:</label> <span class="text-danger">*</span>
                                                <textarea rows="4" class="form-control" id="descripcion" onkeyup="textoLargo(this);"><?php echo $descripcion; ?></textarea>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <button class="btn btn-white" onclick="window.location.reload()"><i class="fa fa-eraser"></i> Limpiar</button>
                                                <button type="button" class="btn btn-primary" id="btn-grabar" onclick="Grabar();"><i class="fas fa-save"></i> Grabar</button>
                                                <button type="button" class="btn btn-primary hidden" id="btn-modificar" onclick="Modificar();"><i class="fas fa-save"></i> Grabar</button>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <input type="text" class="input-sm form-control" id="objetivo" value="<?php echo $codigo; ?>" hidden />
                                            <input type="text" class="input-sm form-control" id="revision" value="<?php echo $revision; ?>" hidden />
                                            <input type="text" class="input-sm form-control" name="hashkey" id="hashkey" value="<?php echo $hashkey; ?>" hidden />
                                            <div class="col-md-12" id="result"><?php echo utf8_decode(tabla_acciones("", $codigo, "", $id)); ?></div>
                                        </div>
                                    </form>
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-12 col-xs-12 text-center">
                                            <input type="hidden" id="codigo" name="codigo" value="<?php echo $codigo; ?>" />
                                            <a type="button" class="btn btn-default " href="FRMacciones_objetivo.php"><span class="fa fa-chevron-left"></span> Regresar</a>
                                            <button type="button" class="btn btn-info " id="btn-grabar" onclick="solicitarAprobacion(<?php echo $revision; ?>,<?php echo $codigo; ?>);"><span class="fa fa-check"></span> Solicitar Aprobaci&oacute;n de Acciones</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo footer() ?>
            </div>
        </div>
        <?php echo modal("../") ?>
        <?php echo scripts("../"); ?>

        <script type="text/javascript" src="../assets.1.2.8/js/modules/planning/accion.js"></script>
        <script>
            $('#range .input-daterange').datepicker({
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                format: "dd/mm/yyyy"
            });
        </script>
    </body>

    </html>
