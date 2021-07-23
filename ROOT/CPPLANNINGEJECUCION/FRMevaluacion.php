<?php
include_once('html_fns_ejecucion.php');
validate_login("../");
$id = $_SESSION["codigo"];

//$_POST
$proceso = $_REQUEST["proceso"];
$usuario = $_REQUEST["usuario"];
// Sistema
$ClSis = new ClsSistema();
$sistemas = $ClSis->get_sistema("", "", "", $id);


//--
$mes = date("m");
$anio = date("Y");
$desde = $_REQUEST["desde"];
$desde = ($desde == "") ? date("1/m/Y") : $desde; //valida que si no se selecciona fecha, coloque la del dia
$hasta = $_REQUEST["hasta"];
$hasta = ($hasta == "") ? date("d/m/Y") : $hasta; //valida que si no se selecciona fecha, coloque la del dia

?>
<!DOCTYPE html>
<html>

<head>
    <?php echo head("../"); ?>
</head>

<body class="">
    <div class="wrapper ">
        <?php echo sidebar("../", "planning"); ?>
        <div class="main-panel">
            <?php echo navbar("../"); ?>
            <div class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card demo-icons">
                            <div class="card-header">
                                <h5 class="card-title"><i class="nc-icon nc-paper"></i> Acciones pendientes de evaluaci&oacute;n</h5>
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
                                    </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Proceso:</label> <span class="text-success">*</span>
                                    <?php echo utf8_decode(ficha_html("proceso", "Submit();", "select2")); ?>
                                    <input type="hidden" name="codigo" id="codigo" />
                                    <script>
                                        document.getElementById("proceso").value = '<?php echo $proceso; ?>';
                                    </script>
                                </div>
                                <div class="col-md-6">
                                    <label>Usuario:</label> <span class="text-success">*</span>
                                    <?php echo utf8_decode(usuarios_html("usuario", "Submit();", "select2")); ?>
                                    <script>
                                        document.getElementById("usuario").value = '<?php echo $usuario; ?>';
                                    </script>
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
                            <div class="row">
                                <div class="col-lg-12" id="result">
                                    <?php
                                    // La tabla de sistemas de los que tiene poder
                                    if (is_array($sistemas)) {
                                        foreach ($sistemas as $row) echo utf8_decode(tabla_evaluacion($proceso, $tipo, trim($row["sis_codigo"]), $usuario));
                                    }
                                    ?>
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

    <script type="text/javascript" src="../assets.1.2.8/js/modules/planning/ejecucion.js"></script>

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