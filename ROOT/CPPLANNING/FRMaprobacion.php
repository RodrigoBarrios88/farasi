<?php
include_once('html_fns_planning.php');
validate_login("../");
$id = $_SESSION["codigo"];
//$_POST
$proceso = $_REQUEST["proceso"];
$tipo = $_REQUEST["tipo"];
$desde = $_REQUEST["desde"];
$desde = ($desde == "") ? date("01/01/Y") : $desde;  //valida que si no se selecciona fecha, coloque la del dia
$hasta = $_REQUEST["hasta"];
$hasta = ($hasta == "") ? date("d/m/Y") : $hasta;
// Sistema
$ClSis = new ClsSistema();
$sistemas = $ClSis->get_sistema("", "", "", $id);
?>
<!DOCTYPE html>
<html>

<head>
    <?php echo head("../"); ?>
</head>

<body class="sidebar-mini">
    <div class="wrapper ">
        <?php echo sidebar("../", "planning"); ?>
        <div class="main-panel">
            <?php echo navbar("../"); ?>
            <div class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card demo-icons">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fa fa-exclamation-circle"></i> Objetivos pendientes de aprobaci&oacute;n
                                    <button class="btn btn-white btn-lg sin-margin pull-right" onclick="window.history.back()"><small><i class="fa fa-chevron-left"></i> Atr&aacute;s</small></button>
                                </h5>
                            </div>
                            <div class="card-body all-icons">
                                <form name="f1" id="f1" action="" method="get">
                                    <div class="row">
                                        <div class="col-lg-12" id="result">
                                            <?php
                                            // La tabla de sistemas de los que tiene poder
                                            if (is_array($sistemas)) {
                                                foreach ($sistemas as $row) {
                                                    $sistema = trim($row["sis_codigo"]);
                                                    echo tabla_aprobacion($proceso, $tipo, $sistema, "");
                                                }
                                            }
                                            ?>
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
        $(document).ready(function() {
            $('.dataTables-example').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [

                ]
            });

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