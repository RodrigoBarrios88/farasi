<?php
include_once('html_fns_proceso.php');
validate_login("../");
$id = $_SESSION["codigo"];
//$tipo = $_REQUEST['tipo'];

?>
<!DOCTYPE html>
<html>

<head>
    <?php echo head("../"); ?>
</head>

<body class="">
    <div class="wrapper ">
        <?php echo sidebar("../", "process"); ?>
        <div class="main-panel">
            <?php echo navbar("../"); ?>
            <div class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card demo-icons">
                            <div class="card-header">
                                <h5 class="card-title"><i class="fa fa-layer-group"></i>Gestor de Necesidades y Expectativas de Partes Interesadas</h5>
                            </div>
                            <div class="card-body all-icons">
                                <div class="row">
                                    <div class="col-xs-6 col-md-6 text-left">
                                        <button type="button" class="btn btn-white" onclick="window.history.back();">
                                            <i class="fa fa-chevron-left"></i>Atr&aacute;s
                                        </button>
                                    </div>
                                    <div class="col-xs-6 col-md-6 text-right"><label class=" text-danger">* Campos Obligatorios</label> </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Necesidad o Expectativa:</label> <span class="text-danger">*</span>
                                        <input type="text" class="form-control" name="nombre" id="nombre" onkeyup="texto(this)" />
                                        <input type="hidden" name="codigo" id="codigo" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Tipo:</label> <span class="text-danger">*</span>
                                        <select name="" id="tipo" class="select2 form-control" name="tipo">
                                            <option value="">Seleccione</option>
                                            <option value="1">Necesidad</option>
                                            <option value="2">Expectativa</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label> Descripci&oacute;n:</label><span class="text-danger">*</span>
                                        <textarea class="form-control" id="descripcion" name="descripcion" rows="5"></textarea>
                                    </div>
                                    <input type="hidden" id="situacion" value="">
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-white" id="btn-limpiar" onclick="Limpiar();"><i class="fas fa-eraser"></i> Limpiar</button>
                                    <button type="button" class="btn btn-primary" id="btn-grabar" onclick="Grabar();"><i class="fas fa-save"></i> Grabar</button>
                                    <button type="button" class="btn btn-primary hidden" id="btn-modificar" onclick="modificar();"><i class="fas fa-save"></i> Modificar</button>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12" id="result">
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

    <?php echo modal("../"); ?>
    <?php echo scripts("../"); ?>

    <script type="text/javascript" src="../assets.1.2.8/js/modules/process/expectativa.js"></script>

</body>
<script>
    printTable('');
</script>

</html>