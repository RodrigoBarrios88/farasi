<?php
include_once('html_fns_mejora.php');
validate_login("../");
$id = $_SESSION["codigo"];
//$_POST
$ClsQue = new ClsQuejas();
$hashkey = $_REQUEST["hashkey"];
///informacion auditoria
$codigo = $ClsQue->decrypt($hashkey, $id);
$info = $ClsQue->get_quejas($codigo);
if (is_array($info)) {
    foreach ($info as $row) {
        $proceso = utf8_decode($row["fic_nombre"]);
        $sistema = utf8_decode($row["sis_nombre"]);
        $descripcion = utf8_decode($row["que_descripcion"]);
        $usuario = utf8_decode($row["usu_nombre"]);
        $fecha_registro = cambia_fecha($row["que_fecha_registro"]);
        $cliente = utf8_decode($row["que_cliente"]);
        $tipo = utf8_decode($row["que_tipo"]);;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php echo head("../"); ?>
</head>

<body class="">
    <div class="wrapper ">
        <?php echo sidebar("../", "mejora"); ?>
        <div class="main-panel">
            <?php echo navbar("../"); ?>
            <div class="content">
                <fieldset disabled>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card demo-icons">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="nc-icon nc-bullet-list-67"></i> Informaci&oacute;n
                                    </h5>
                                </div>
                                <div class="card-body all-icons">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Proceso:</label>
                                                    <input type="text" class="form-control" value="<?= $proceso ?>" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Sistema:</label>
                                                    <input type="text" class="form-control" value="<?= $sistema ?>" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Fecha Registro:</label>
                                                    <input type="text" class="form-control" value="<?= $fecha_registro ?>" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Usuario:</label>
                                                    <input type="text" class="form-control" value="<?= $usuario ?>" />
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Cliente:</label>
                                                    <input type="text" class="form-control" value="<?= $cliente ?>" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Tipo:</label>
                                                    <input type="text" class="form-control" value="<?= $tipo ?>" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Descripci&oacute;n:</label>
                                                    <input type="text" class="form-control" value="<?= $descripcion ?>" />
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <?php echo footer() ?>
        </div>
    </div>
    <?php echo modal("../"); ?>
    <?php echo scripts("../"); ?>
    <script>
        $('.dual_select').bootstrapDualListbox({
            selectorMinimalHeight: 160,
        });
        $("#form").submit(function() {
            asignarUsuario($('[name="duallistbox1[]"]').val());
            return false;
        });
        $('#range .input-daterange').datepicker({
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true,
            format: "dd/mm/yyyy"
        });
    </script>
</body>

</html>