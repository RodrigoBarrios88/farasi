<?php
	include_once('html_fns_biblioteca.php');
	$usuario = $_SESSION["codigo"];
	$ClsBib = new ClsBiblioteca();
	//le dimos click al boton grabar?
	$nombre = $_FILES['documento']['name'];
	$temporal = $_FILES['documento']['tmp_name'];
	$type = $_FILES['documento']['type'];
	$archivo = $_FILES["documento"]['name'];
	$codigo = $_REQUEST['codigo'];//echo "$cliente,$docnom,$observaciones | $archivo | <br><br>";
	if ($archivo != "") {
		$ClsBib->getConexion();
		$conn = $ClsBib->conn;
		//archivo temporal en binario
		$itmp = fopen($temporal, 'r+b');
		$archivo = fread($itmp, filesize($temporal));
		fclose($itmp);
		//escapando los caracteres
		$archivo = mysqli_real_escape_string($conn,$archivo);
		//--
		$sql = $ClsBib->actualiza_documento($codigo,$archivo);
		$rs = mysqli_query($conn,$sql); //ejecuta la sentencia
		if(!$rs){
			$arr_respuesta = array(
				"status" => false,
				//"sql" => $sql,
				"message" => "Error en la transacción, hubo un problema al subir el documento..."
			);
			echo json_encode($arr_respuesta);
			return;
		}else{
			$arr_respuesta = array(
				"status" => true,
				//"sql" => $sql,
				"message" => "Documento cargado satisfactoriamente!!!"
			);
			echo json_encode($arr_respuesta);
			return;
		}
	}else{
		$arr_respuesta = array(
			"status" => false,
			"data" => [],
			"message" => "Error en la transacción, documento vacio..."
		);
		echo json_encode($arr_respuesta);
		return;
	}
