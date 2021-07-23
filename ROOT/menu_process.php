<?php
include_once('html_fns.php');
validate_login();
$id = $_SESSION["codigo"];

// Obtiene todos los titulos
$ClsPro = new ClsProceso();
$titulos = $ClsPro->get_titulo('', '', '', 1);
// Obtiene todas las fichas
$ClsFic = new ClsFicha();


// Obtiene el total de fichas sin aprobar
$totalFichasSinAprobar = count_fichas_sin_aprobar();
///Obtiene las fichas en actualizacion 
$totalFichasActualizar = count_fichas_actualizacion_usuario($id);


?>
<!DOCTYPE html>
<html>

<head>
    <?php echo head(); ?>
    <!-- Estilo especifico -->
    <link href="assets.1.2.8/css/propios/dashboard.css" rel="stylesheet">
</head>

<body class="sidebar-mini">
    <div class="wrapper ">

        <?php echo sidebar("", "process"); ?>
        <div class="main-panel">
            <?php echo navbar(); ?>
            <div class="content">
                <div class="row" id="macroproceso">
                    <!--///////////////   COLUMNA UNO   ///////////////////////////-->
                    <div class="col-md-2">
                        <div class="card text-center" style="height:100%; background:#e6e6ff">
                            <input type="hidden" id="total_fichas_sin_aprobar" name="total_fichas_sin_aprobar" value="<?=$totalFichasSinAprobar?>">
                            <input type="hidden" id="total_fichas_actualizacion" name="total_fichas_actualizacion" value="<?=$totalFichasActualizar?>">
                            <?php
                            foreach ($titulos as $row) {
                                $posicion = trim($row['tit_posicion']);
                                if ($posicion == 1) {
                                    $result = '<div class="card-header" style="padding-top:4px;">';
                                    $result .= '<strong>' . utf8_decode($row['tit_nombre']) . '</strong>';
                                    $result .= '</div>';
                                    $result .= '<div class="card-body" style="padding:4px;">';
                                    // Obtener los Subtitulos
                                    $subtitulos = $ClsPro->get_subtitulo('', $row['tit_codigo'], '', 1);
                                    foreach ($subtitulos as $row2) {
                                        $result .= '<div>';
                                        $sub_nombre = utf8_decode($row2['sub_nombre']);
                                        $result .= '<h6 style="margin-bottom:0px;">' . $sub_nombre . '</h6>';
                                        $result .= '</div>';
                                        // Obtener los resultados
                                        $fichas = $ClsFic->get_extra('', $row2['sub_codigo']);
                                        foreach ($fichas as $row3) {
                                            $nombre = utf8_decode($row3["ext_nombre"]);
                                            $codigo = trim($row3["ext_codigo"]);
                                            $hashkey = $ClsFic->encrypt($codigo, $_SESSION["codigo"]);
                                            // Imprime Ficha
                                            $result .= '<div class="btn-group">';
                                            $result .= '<a href="#' . $nombre . '" title="Ver descripcion" class="btn btn-sm btn-white btn-round btn-block font-weight-normal" style="background:#edebde; white-space: normal;line-height: 15px;font-size:11px; padding:5px;">' . $nombre . '</a>';
                                            $result .= '</div>';
                                        }
                                    }
                                    $result .= '</div>';
                                    echo $result;
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-sm-1 col-md-1" style="position: relative;  top: 150px;">
                        <img src="../CONFIG/img/right_arrow.png" />
                    </div>

                    <!--///////////////   COLUMNA DOS  CENTRAL  / ///////////////////////////////////////////////////////////////-->
                    <div class="col-md-6">
                        <div class="card text-center" style="height:100%; background:#e6e6ff"">
                                <?php
                                foreach ($titulos as $row) {
                                    $posicion = trim($row['tit_posicion']);
                                    if ($posicion == 2) {
                                        $result = '<div class="card-header" style="padding-top:4px;">';
                                        $result .= '<strong>' . utf8_decode($row['tit_nombre']) . '</strong>';
                                        $result .= '</div>';
                                        $result .= '<div class="card-body" style="padding:4px;">';
                                        // Obtener los Subtitulos
                                        $subtitulos = $ClsPro->get_subtitulo('', $row['tit_codigo'], '', 1);
                                        $count = sizeof($subtitulos);
                                        foreach ($subtitulos as $row2) {
                                            $result .= '<div>';
                                            $sub_nombre = utf8_decode($row2['sub_nombre']);
                                            $result .= '<h6 style="margin-bottom:0px;">' . $sub_nombre . '</h6>';
                                            $result .= '</div>';
                                            // Obtener las fichas
                                            $fichas = $ClsFic->get_ficha('', $row2['sub_codigo'], "", "", "", 0);
                                            foreach ($fichas as $row3) {
                                                $nombre = utf8_decode($row3["fic_nombre"]);
                                                $codigo = trim($row3["fic_codigo"]);
                                                $hashkey = $ClsFic->encrypt($codigo, $_SESSION["codigo"]);
                                                // Imprime Ficha
                                                $result .= '<div class="btn-group">';
                                                $result .= '<a href="CPPROCESS/CPREPORTES/REPficha.php?hashkey=' . $hashkey . '" target="_blank" title="Imprimir Ficha" class="btn btn-sm btn-white btn-round btn-block font-weight-normal" style="background:#edebde; white-space: normal;line-height: 15px;font-size:11px; padding:5px;">' . $nombre . '</a>';
                                                $result .= '<button type="button" style="background:#edebde;" class="btn btn-sm btn-white btn-round dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                                                $result .= '<span class="sr-only">Toggle Dropdown</span>';
                                                $result .= '</button>';
                                                // Imprime Subelemento
                                                $subfichas = $ClsFic->get_ficha('', '', '', '', '', trim($row3['fic_codigo']));
                                                $result .= '<div class="dropdown-menu dropdown-menu-right">';
                                                if (is_array($subfichas)) {
                                                    foreach ($subfichas as $row4) {
                                                        $nombre = utf8_decode($row4["fic_nombre"]);
                                                        $codigo = trim($row4["fic_codigo"]);
                                                        $hashkey = $ClsFic->encrypt($codigo, $_SESSION["codigo"]);
                                                        $result .= '<a class="dropdown-item"  target="_blank" style="white-space: normal;line-height: 15px;font-size:11px; padding:5px;" href="CPPROCESS/CPREPORTES/REPficha.php?hashkey=' . $hashkey . '">' . $nombre . '</a>';
                                                    }
                                                } else {
                                                    $result .= '<a class="dropdown-item" style="white-space: normal;line-height: 15px;font-size:11px; padding:5px;">Sin Subprocesos</a>';
                                                }
                                                $result .= '</div>';
                                                //--
                                                $result .= '</div>';
                                            }
                                            $count--;
                                            // Para no imprimir la ultima flecha 
                                            if ($count > 0) $result .= '<div class="row" ><div class="col-md-12"><img src="../CONFIG/img/double_arrow.png"  style=" max-height: 35px; width:55px;"/></div></div>';
                                        }
                                        $result .= '</div>';
                                        echo $result;
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class=" col-sm-1 col-md-1" style="position: relative;  top: 150px;">
                            <img src="../CONFIG/img/right_arrow.png" />
                        </div>

                        <!--///////////////   COLUMNA TRES   / ///////////////////////////////////////////////////////////////-->
                        <div class="col-md-2">
                            <div class="card text-center" style="height:100%; background:#e6e6ff">
                                <?php
                                foreach ($titulos as $row) {
                                    $posicion = trim($row['tit_posicion']);
                                    if ($posicion == 3) {
                                        $result = '<div class="card-header" style="padding-top:4px;">';
                                        $result .= '<strong>' . utf8_decode($row['tit_nombre']) . '</strong>';
                                        $result .= '</div>';
                                        $result .= '<div class="card-body" style="padding:4px;">';
                                        // Obtener los Subtitulos
                                        $subtitulos = $ClsPro->get_subtitulo('', $row['tit_codigo'], '', 1);
                                        foreach ($subtitulos as $row2) {
                                            $result .= '<div>';
                                            $sub_nombre = utf8_decode($row2['sub_nombre']);
                                            // $result .= '<h6 style="margin-bottom:0px;">' . $sub_nombre . '</h6>';
                                            $result .= '</div>';
                                            // Obtener los resultados
                                            $fichas = $ClsFic->get_extra('', $row2['sub_codigo']);
                                            if (is_array($fichas)) {
                                                foreach ($fichas as $row3) {
                                                    $nombre = utf8_decode($row3["ext_nombre"]);
                                                    $codigo = trim($row3["ext_codigo"]);
                                                    $hashkey = $ClsFic->encrypt($codigo, $_SESSION["codigo"]);
                                                    // Imprime Ficha
                                                    $result .= '<div class="btn-group">';
                                                    $result .= '<a href="#' . $nombre . '" title="Ver descripcion" class="btn btn-sm btn-white btn-round btn-block font-weight-normal" style="background:#edebde; white-space: normal;line-height: 15px;font-size:11px; padding:5px;">' . $nombre . '</a>';
                                                    $result .= '</div>';
                                                }
                                            }
                                        }
                                        $result .= '</div>';
                                        echo $result;
                                    }
                                }
                                ?>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="container">
                    <!--CONTENEDOR DE LOS ELEMENTOS DEL PROCESO -->
                    <?php
                    foreach ($titulos as $row) {
                        // Obtener los Subtitulos
                        $subtitulos = $ClsPro->get_subtitulo('', $row['tit_codigo'], '', 1);
                        $result = "";
                        foreach ($subtitulos as $row2) {
                            $fichas = $ClsFic->get_extra('', $row2['sub_codigo']);
                            if (is_array($fichas)) {
                                $result .= '<br>';
                                $result .= '<h4 class="text-center">' . $row['tit_nombre'] . ' ' . $row2['sub_nombre'] . '</h4>';
                                foreach ($fichas as $row3) {
                                    $nombre = utf8_decode($row3["ext_nombre"]);
                                    $result .= '<div class="card" id="' . $nombre . '">';
                                    $result .= '<div class="card-header m-2">';
                                    $result .= '<strong>' . $nombre . '</strong>';
                                    $result .= '</div>';
                                    $descripcion = utf8_decode($row3["ext_descripcion"]);
                                    $descripcion = utf8_decode($descripcion);
                                    $result .= '<div class="card-body">';
                                    $result .= '<p>' . $descripcion . '</p>';
                                    $result .= '<a type="button" href="#macroproceso" class="btn btn-outline-primary btn-round btn-sm pull-right mr-3">Volver al Macroproceso</a>';
                                    $result .= '</div>';
                                    $result .= '</div>';
                                }
                            }
                        }
                        echo $result;
                    }
                    ?>
                </div>
            </div>
            <?php echo footer() ?>
        </div>
    </div>
    <?php echo modal(); ?>
    <?php echo scripts(); ?>
	<script type="text/javascript" src="assets.1.2.8/js/modules/process/notificaciones.js"></script>
    <!-----------ALERTA ADMIN FICHAS SIN APROBAR------------------------------------>
    <?php if(isset($_SESSION['APROFICHA']) && $totalFichasSinAprobar > 0):?>
        <?php if(!isset($_SESSION['alerta_fichas_sin_aprob'])):?>
        <script>
            alertaAprobar();
        </script>
        <?php endif;?>
        <?php $_SESSION['alerta_fichas_sin_aprob'] = "";?>
    <!-----------------ALERTA USUARIOS EN GENERAL----------------------------------->
    <?php elseif(isset($_SESSION['MISFIC'])  && $totalFichasActualizar > 0):?>
        <?php if(!isset($_SESSION['alerta_fichas_sin_aprob'])):?>
        <script>
           alertaActualizar();
        </script>
        <?php endif;?>
        <?php $_SESSION['alerta_fichas_sin_aprob'] = "";?>
    <?php endif;?>
    <!----------------------------------------------------------------------------->
    <script>
        $('.dataTables-example').DataTable({
            pageLength: 100,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [

            ]
        });
    </script>

</body>

</html>