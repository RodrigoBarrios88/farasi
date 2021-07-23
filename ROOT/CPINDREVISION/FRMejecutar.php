<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$id = $_SESSION["codigo"];
//$_POST
$proceso = $_REQUEST["proceso"];
$sistema = $_REQUEST["sistema"];
?>
    <!DOCTYPE html>
    <html>

    <head>
        <?php echo head("../"); ?>
    </head>

    <body class="">
        <div class="wrapper ">
            <?php echo sidebar("../", "indicador"); ?>
            <div class="main-panel">
                <?php echo navbar("../"); ?>
                <div class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card demo-icons">
                                <div class="card-header">
                                    <h5 class="card-title"><i class="fa fa-edit"></i> Anotaciones Programadas</h5>
                                </div>
                                <div class="card-body all-icons">
                                    <input type="hidden" name="codigo" id="codigo" />
                                    <form name="f1" id="f1" method="get">
                                        <div class="row">
                                            <div class="col-xs-12 col-md-12 text-right"><label class="text-info">* Filtros de Busqueda</label> </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-6 col-md-6 text-left"><button type="button" class="btn btn-white" onclick="window.history.back();"><i class="fa fa-chevron-left"></i>
                                                    Atr&aacute;s</button> </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo; ?>" />
                                                <label>Proceso:</label> <span class="text-success">*</span>
                                                <?php echo utf8_decode(ficha_html("proceso", "Submit();", "select2")); ?>
                                                <script>
                                                    document.getElementById("proceso").value = '<?php echo $proceso; ?>';
                                                </script>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Sistema:</label> <span class="text-success">*</span>
                                                <?php echo utf8_decode(sistema_html("categoria", "Submit();", "select2")); ?>
                                            </div>
                                            <script>
                                                document.getElementById("sistema").value = '<?php echo $sistema; ?>';
                                            </script>
                                        </div>
                                        <br>
                                    </form>
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <button type="button" class="btn btn-white" id="btn-limpiar" onclick="Limpiar();"><i class="fas fa-eraser"></i> Limpiar</button>
                                            <button type="button" class="btn btn-primary" id="btn-grabar" onclick="Grabar();"><i class="fas fa-save"></i> Grabar</button>
                                            <button type="button" class="btn btn-primary hidden" id="btn-modificar" onclick="Modificar();"><i class="fas fa-save"></i> Grabar</button>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-12" id="result">
                                            <?php echo utf8_decode(tabla_ejecucion('', $proceso, $sistema)); ?>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo footer(); ?>
            </div>
        </div>
        <?php echo modal("../") ?>
        <?php echo scripts("../"); ?>
        <script type="text/javascript" src="../assets.1.2.8/js/modules/indicator/revision.js"></script>
        <script>
            $(document).ready(function() {
                $('.dataTables-example').DataTable({
                    pageLength: 100,
                    responsive: true,
                    dom: '<"html5buttons"B>lTfgitp',
                    buttons: [

                    ]
                });

                $('.select2').select2({ width: '100%' });
            });
        </script>
    </body>

    </html>
