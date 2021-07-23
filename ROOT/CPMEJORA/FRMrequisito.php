<?php
include_once('html_fns_mejora.php');
validate_login("../");
$id = $_SESSION["codigo"];
//$_POST
$clsReq = new ClsTipoEvaluacion();
$hashkey = $_REQUEST["hashkey"];
///informacion auditoria
$codigo = $clsReq->decrypt($hashkey, $id);
$info = $clsReq->get_tipo_evaluacion($codigo, "");
if (is_array($info)) {
    foreach ($info as $row) {
        $codigoRequisito = utf8_decode($row['tip']);
        $requisito = utf8_decode($row['req_nomenclatura']);
        $descripcion_requisito = utf8_decode($row["req_descripcion"]);
        $fecha_registro_requisito = utf8_decode($row["req_fecha_registro"]);
        $titulo_documento = utf8_decode($row["doc_titulo"]);
        $tipo_documento = utf8_decode($row["doc_tipo"]);
        $fecha_documento = cambia_fecha($row["doc_fecha"]);
        $entidad_documento = utf8_decode($row["doc_entidad"]);
        $sistema_documento = utf8_decode($row["doc_sistema"]);
        $area_documento = utf8_decode($row["doc_area"]);
        $nombre_evaluacion = utf8_decode($row["eva_nombre"]);
        $cumple_evaluacion = si_no($row["eva_cumple"]);
        $aspecto_evaluacion = utf8_decode($row["eva_aspecto"]);
        $componente_evaluacion = utf8_decode($row["eva_componente"]);
        $frecuencia_evaluacion = Frecuencias($row["eva_frecuencia"]);
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
                        <div class="col-md-6">
                            <div class="card demo-icons">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fa fa-file" aria-hidden="true"></i> Documento
                                    </h5>
                                </div>
                                <div class="card-body all-icons">
                                    <div class="row">
                                        <div class="col-lg-12">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Titulo:</label>
                                                    <input type="text" class="form-control" value="<?= $titulo_documento ?>" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Tipo documento:</label>
                                                    <input type="text" class="form-control" value="<?= $tipo_documento ?>" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Fecha registro:</label>
                                                    <input type="text" class="form-control" value="<?= $fecha_documento ?>" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Entidad :</label>
                                                    <input type="text" class="form-control" value="<?= $entidad_documento ?>" />
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Sistema:</label>
                                                    <input type="text" class="form-control" value="<?= get__sistema($sistema_documento) ?>" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Area :</label>
                                                    <input type="text" class="form-control" value="<?= $area_documento ?>" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card demo-icons">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fa fa-pencil" aria-hidden="true"></i> Tipo de Evaluaci&oacute;n
                                    </h5>
                                </div>
                                <div class="card-body all-icons">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Nombre :</label>
                                            <input type="text" class="form-control" value="<?= $nombre_evaluacion ?>" />
                                        </div>
                                        <div class="col-md-6">
                                            <label>Cumple:</label>
                                            <input type="text" class="form-control" value="<?= $cumple_evaluacion ?>" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Aspecto:</label>
                                            <input type="text" class="form-control" value="<?= $aspecto_evaluacion ?>" />
                                        </div>
                                        <div class="col-md-6">
                                            <label>Componente:</label>
                                            <input type="text" class="form-control" value="<?= $componente_evaluacion ?>" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Frecuencia:</label>
                                            <input type="text" class="form-control" value="<?= $frecuencia_evaluacion ?>" />
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
                                        <i class="fa fa-sticky-note" aria-hidden="true"></i> Requisito
                                    </h5>
                                </div>
                                <div class="card-body all-icons">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Requisito :</label>
                                                    <input type="text" class="form-control" value="<?= $requisito ?>" />
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Fecha Registro:</label>
                                                    <input type="text" class="form-control" value="<?= $fecha_registro_requisito ?>" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Descripci&oacute;n:</label>
                                                    <input type="text" class="form-control" value="<?= $descripcion_requisito ?>" />
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