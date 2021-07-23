<?php
include_once('html_fns.php');
validate_login();
$id = $_SESSION["codigo"];

//$_POST
?>
<!DOCTYPE html>
<html>

<head>
	<?php echo head(); ?>
</head>

<body class="sidebar-mini">
	<div class="wrapper ">
		<?php echo sidebar("", "biblioteca"); ?>
		<div class="main-panel">
			<?php echo navbar(); ?>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="card demo-icons">
							<div class="card-header">
								<h5 class="card-title"><i class="fa fa-book-open"></i> Biblioteca Documental</h5>
							</div>
							<div class="card-body all-icons">
								<div class="row">
									<div class="col-xs-6 col-md-6 text-left">
										<button type="button" class="btn btn-white" onclick="window.history.back();">
											<i class="fa fa-chevron-left"></i>Atr&aacute;s
										</button>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12" id="result">
										<?php echo utf8_decode(tabla_documentos()) ?>
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
	<?php echo modal(); ?>
	<?php echo scripts(); ?>
</body>

</html>
<?php
function tabla_documentos()
{
	$ClsBib = new ClsBiblioteca();
	$result = $ClsBib->get_biblioteca('', '', '1,2,3,10');
	if (is_array($result)) {
		$salida = '<table class="table table-striped" id="tabla" width="100%" >';
		$salida .= '<thead>';
		$salida .= '<tr>';
		$salida .= '<th class = "text-center" width = "10px">No.</th>';
		$salida .= '<th class = "text-center" width = "20px">C&oacute;digo</th>';
		$salida .= '<th class = "text-left" width = "100px">Categor&iacute;a</th>';
		$salida .= '<th class = "text-left" width = "100px">T&iacute;tulo</th>';
		$salida .= '<th class = "text-left" width = "200px">Descripci&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "50px">Versi&oacute;n</th>';
		$salida .= '<th class = "text-center" width = "50px">Vence</th>';
		$salida .= '<th class = "text-center" width = "50px">Status</th>';
		$salida .= '<th class = "text-center" width = "30px"><i class="fa fa-cogs"></i></th>';
		$salida .= '</tr>';
		$salida .= '</thead>';
		$salida .= '<tbody>';
		$i = 1;
		foreach ($result as $row) {
			$salida .= '<tr>';
			//codigo
			$salida .= '<td class = "text-center" >' . $i . '.</td>';
			//codigo  
			$codigo = Agrega_Ceros($row["bib_codigo_interno"]);
			$salida .= '<td class = "text-center">#' . $codigo . '</td>';
			//categoria
			$categoria = trim($row["cat_nombre"]);
			$salida .= '<td class = "text-left">' . $categoria . '</td>';
			//nombre
			$nom = trim($row["bib_titulo"]);
			$salida .= '<td class = "text-left">' . $nom . '</td>';
			//descripcion
			$descripcion = trim($row["bib_descripcion"]);
			$descripcion = nl2br($descripcion);
			$salida .= '<td class = "text-justify">' . $descripcion . '</td>';
			//version
			$version = trim($row["bib_version"]);
			$salida .= '<td class = "text-center">' . $version . '</td>';
			//vence
			$vence = cambia_fechaHora($row["bib_fecha_vence"]);
			$vence = substr($vence, 0, 10);
			$salida .= '<td class = "text-center">' . $vence . '</td>';
			//status
			$situacion = trim($row["bib_situacion"]);
			switch ($situacion) {
				case 0:
					$status = '<span class="text-danger">Eliminado</span>';
					break;
				case 1:
					$status = '<span class="text-muted">Edici&oacute;n</span>';
					break;
				case 2:
					$status = '<span class="text-info">En Aprobaci&oacute;n</span>';
					break;
				case 3:
					$status = '<span class="text-success">Versi&oacute;n Aprobada</span>';
					break;
				case 10:
					$status = '<strong class="text-warning">Obsoleto</strong>';
					break;
			}
			$salida .= '<td class = "text-center">' . $status . '</td>';
			//codigo
			$codigo = trim($row["bib_codigo"]);
			$archivo = trim($row["bib_documento"]);
			$usuario = $_SESSION["codigo"];
			$hashkey = $ClsBib->encrypt($codigo, $usuario);
			$salida .= '<td class = "text-center" >';
			$salida .= '<div class="btn-group">';
			$salida .= '<button type="button" class="btn btn-info btn-xs" onclick = "verHistorial(' . $codigo . ');" title = "Ver Historial del Documento" ><i class="fa fa-info"></i></button>';
			if ($archivo != "") {
				$salida .= '<a class="btn btn-primary" href="CPBIBLIOTECA/EXEverdocumento.php?hashkey=' . $hashkey . '" target = "_blank"><i class="fa fa-book-open"></i></a>';
			} else {
				$salida .= '<a class="btn btn-white" href="javascript:void(0);" disabled ><i class="fa fa-ban"></i></a>';
			}
			$salida .= '</div>';
			$salida .= '</td>';
			//--
			$salida .= '</tr>';
			$i++;
		}
		$salida .= '</tbody>';
		$salida .= '</table>';
	}
	return $salida;
}
?>