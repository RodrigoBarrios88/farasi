<?php
require_once ("ClsUtil.php");

class ClsConex extends ClsUtil{
	var $conn;
	function getConexion() {
		
		$ifxConnect = mysqli_connect("localhost", "root", "root", "planigo");
		
		if ($ifxConnect) {
			$this->conn = $ifxConnect;
			//echo "Si se Conecto";
		}else {
			//echo "No se Conecto";
			return false;
		}
	}
	function exec_query($sql){
	$this->getConexion();
	$conn = $this->conn;
	//echo $sql;
		if($conn){
			//mysql_select_db("transporte", $conn);
			$result = mysqli_query($conn,$sql);
			if($result){
				$x = 0;
				while($row = $result->fetch_assoc()){
					$result_array[$x] = $row;
					$x++;
				}
				mysqli_close($conn); //cierra el cursor
				if($x > 0){ return $result_array; }
			}
			return "!E";
		}
		
	}
	function exec_sql($sql){
		$this->getConexion();
		$conn = $this->conn; //trae el string de conexion
		if($conn){ //revisa si no viene null
			$ssql = explode(';',$sql); //separa en varios query's el sql si fuera el caso
			mysqli_autocommit($conn, FALSE); //desactiva el auto-commit en la BD
			foreach($ssql as $squery){ //ciclo para ejecucion de query's
				$squery = trim($squery); //limpia la cadena de caracteres 
				if(strlen($squery)>0){ //valida si la fila de query no va vacia
					$rs = mysqli_query($conn,$squery); //ejecuta la sentencia
					if(!$rs){ 
						mysqli_rollback($conn); //valida si existe algun error ejecuta un rollback y sale de la funcion devolviendo "0"
						mysqli_close($conn); 
						return 0; 
					}				
				}
			}
			mysqli_commit($conn); //si no encontro errores ejecuta el commit
			mysqli_close($conn); //cierra el cursor
			return 1;
		}else{ return 0;}
		
	}
	
}


//////////////////////////////////// ------- ////////////////////////////////////// ------- ////////////////////////////////

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$database = 'planigo';
$user = 'root';
$pass = 'root';
$host = 'localhost';
$dir = 'dump.sql';


$filename = "backup-" . date("d-m-Y") . ".sql";
$mime = "application/octet-stream";

header( "Content-Type: " . $mime );
header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

$cmd = "mysqldump -u $user --password=$pass $database | gzip --best";   

passthru( $cmd );

	
?>


