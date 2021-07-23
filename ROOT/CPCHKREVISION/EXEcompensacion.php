<?php
	include_once('html_fns_revision.php');
	validate_login("../");
$id = $_SESSION["codigo"];
	$nombre = utf8_decode($_SESSION["nombre"]);
	$rol = $_SESSION["rol"];
	$rol_nombre = utf8_decode($_SESSION["rol_nombre"]);
	$foto = $_SESSION["foto"];$fini = "07/05/2019";
	$ffin = "07/05/2019";
	$categoria = "";
	tabla_horarios($categoria,1,'','',$fini,$ffin);
	
function tabla_horarios($categoria,$sede,$sector,$area,$fini,$ffin){
	$dia = date("N");
	$ClsLis = new ClsLista();
	$ClsRev = new ClsRevision();
	$query = "SELECT *, ";
	if($fini != "" && $ffin != "") { 
		$fini = $ClsLis->regresa_fecha($fini);
		$ffin = $ClsLis->regresa_fecha($ffin);
		$query.= "(SELECT rev_codigo FROM chk_revision WHERE rev_programacion = pro_codigo AND rev_situacion = 2 AND rev_fecha_inicio BETWEEN '$fini 00:00:00' AND '$ffin 23:59:59' ORDER BY rev_codigo LIMIT 0,1) as revision_ejecutada";
	}
	$query.= " FROM chk_programacion,chk_lista";
	$query.= " WHERE list_codigo = pro_lista";
	$query.= " AND pro_sede IN($sede)";
	//$query.= " AND list_categoria IN($categoria)";
	$query.= " ORDER BY list_categoria ASC, pro_sede ASC, pro_sector ASC, pro_area ASC, list_codigo ASC, pro_hini ASC";
	//echo $query;$result = $ClsLis->exec_query($query);
	//print_r($result);
	if(is_array($result)){
		$i=1;
		$sql = "";
		$revision = $ClsRev->max_revision();
		$revision++; /// Maximo codigo de Lista
		foreach($result as $row){
			//sede
			$sede = utf8_decode($row["sed_nombre"]);
			//nombre
			$nom = utf8_decode($row["list_nombre"]);
			//categoria
			$categoria = utf8_decode($row["cat_nombre"]);
			//horarios
			$hini = substr($row["pro_hini"],0,5);
			$hfin = substr($row["pro_hfin"],0,5);
			//Obs
			$obs = utf8_decode($row["pro_observaciones"]);
			//situacion
			$fecha = regresa_fecha($ffin);
			$jecutada = $row["revision_ejecutada"];
			$situacion = ($jecutada != "")?'<strong class="text-success">Ejecutado</strong>':'<strong class="text-muted">Pendiente</strong>';
			if($jecutada != ""){
				$situacion = '<strong class="text-success">Ejecutado</strong>';
			}else{
				$codigo_lista = trim($row["list_codigo"]);
				$codigo_progra = trim($row["pro_codigo"]);
				$usuario = $_SESSION["codigo"];
				$sql = $ClsRev->insert_revision($revision,$codigo_lista,$codigo_progra,1,'');
				echo "$sql<br>";
				///PREGUNTAS
				$result_preguntas = $ClsLis->get_pregunta('',$codigo_lista,'','','','',1);
				if(is_array($result_preguntas)){
					foreach ($result_preguntas as $row_pregunta){
						$pregunta_codigo = trim($row_pregunta["pre_codigo"]);
						$sql = $ClsRev->insert_respuesta($revision,$codigo_lista,$pregunta_codigo,1);
						echo "$sql<br>";
					}
				} // inicializa las preguntas con un N
				$sql = $ClsRev->cerrar_revision($revision,'Cierre Automatico');
				echo "$sql<br><br>";
				$revision++; /// Maximo codigo de Lista
			}
			//--
			$i++;
			$rs = $ClsRev->exec_sql($sql);
			if($rs == 1){
				//echo "Ejecucion exitosa...";
			}else{
				echo "Error!";
			}
		}
		
	}
}
 
?>    