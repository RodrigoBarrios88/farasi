<?php
include_once('html_fns_planning.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$proceso = $_REQUEST["proceso"];
$sistema = $_REQUEST["sistema"];

//--
$mes = date("m");
$anio = date("Y");
$desde = $_REQUEST["desde"];
$desde = ($desde == "") ? date("1/m/Y") : $desde; //valida que si no se selecciona fecha, coloque la del dia
$hasta = $_REQUEST["hasta"];
$hasta = ($hasta == "") ? date("d/m/Y") : $hasta; //valida que si no se selecciona fecha, coloque la del dia?>
    <!DOCTYPE html>
    <html>

    <head>
        <?php echo head("../"); ?>
    </head>

    <body class="">
        <div class="wrapper ">
            <?php echo sidebar("../","planning"); ?>
            <div class="main-panel">
               <?php echo navbar("../"); ?>
                <div class="content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card demo-icons">
                                <div class="card-header">
                                    <h5 class="card-title"><i class="nc-icon nc-paper"></i> Objetivos a evaluar</h5>
                                </div>
                                <div class="card-body all-icons">
                                    <form name="f1" id="f1" method="get">
                                        <div class="row">
                                            <div class="col-xs-12 col-md-12 text-right"><label class="text-info">* Filtros de Busqueda</label> </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-6 col-md-6 text-left">
										<button type="button" class="btn btn-white" onclick="window.history.back();">
											<i class="fa fa-chevron-left"></i>Atr&aacute;s
										</button>
									</div>
                                                    Atr&aacute;s</button> </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Proceso:</label> <span class="text-success">*</span>
                                                <?php echo utf8_decode(departamento_org_html("departamento", "Submit();", "select2")); ?>
                                                <input type="hidden" name="codigo" id="codigo" />
                                                <script>
                                                    document.getElementById("departamento").value = '<?php echo $departamento; ?>';
                                                </script>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Sistema:</label> <span class="text-success">*</span>
                                                <?php echo utf8_decode(categorias_indicador_html("categoria", "Submit();", "select2")); ?>
                                            </div>
                                            <script>
                                                document.getElementById("categoria").value = '<?php echo $categoria; ?>';
                                            </script>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
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
                                        <br>
                                    </form>
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <a class="btn btn-white" href="FRMrevisiones.php"><i class="fa fa-eraser"></i> Limpiar</a>
                                            <button type="button" class="btn btn-primary" onclick="Submit();"><i class="fa fa-search"></i> Buscar</button>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-12" id="result">
                                            <?php echo utf8_decode(tabla_evaluacion($proceso, $tipo, $sistema, $id, "", "", "", "3,4")); ?>
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
