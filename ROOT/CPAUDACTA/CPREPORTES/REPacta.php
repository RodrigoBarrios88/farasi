<?php
   //Incluir las librerias de FPDF 
   include_once('html_fns_reportes.php');
   $usuario = $_SESSION["codigo"];
   $nombre_sesion = utf8_decode($_SESSION["nombre"]);
   
   //$_POST
	$ClsAud = new ClsAuditoria();
	$ClsEje = new ClsEjecucion();
   $ClsPla = new ClsPlan();
	$ejecucion = $_REQUEST["ejecucion"];
	//--
	$result = $ClsEje->get_acta($ejecucion);
	if(is_array($result)){
		$i = 0;	
		foreach ($result as $row){
			$ejecucion = trim($row["act_ejecucion"]);
			$codigo_audit = trim($row["audit_codigo"]);
			$programacion = trim($row["pro_codigo"]);
			$sede = trim($row["sed_nombre"]);
			$sector = trim($row["sec_nombre"]);
			$area = trim($row["are_nombre"]);
			$direccion = trim($row["sed_direccion"]);
			$depmun = trim($row["sede_municipio"]);
			$departamento = trim($row["dep_nombre"]);
			$categoria = utf8_decode($row["cat_nombre"]);
			$nombre = trim($row["audit_nombre"]);
			$usuario_nombre = utf8_decode($row["usuario_nombre"]);
			//--
			$fecha_inicio = trim($row["eje_fecha_inicio"]);
			$fecha_inicio = cambia_fechaHora($fecha_inicio);
			$fecha_inicio = substr($fecha_inicio,0,16);
			//--
			$fecha_update = trim($row["eje_fecha_update"]);
			$fecha_update = cambia_fechaHora($fecha_update);
			$fecha_update = substr($fecha_update,0,16);
			//--
			$fecha_progra = trim($row["pro_fecha"]);
			$fecha_progra = cambia_fecha($fecha_progra);
			$hora_progra = substr($row["pro_hora"],0,5);
			$obs = utf8_decode($row["pro_observaciones"]);
			$obs = nl2br($obs);
			$responsable = utf8_decode($row["act_responsable"]);
			
         /// Observaciones
			$print_acta = true;
			$fini = cambia_fechaHora($row["act_fecha_inicio"]);
			$ffin = cambia_fechaHora($row["act_fecha_final"]);
			$observaciones = utf8_decode($row["act_observaciones"]);
			//--
			$fecha1 = explode(" ",$fini);
			$fini = $fecha1[0];
			$hini = substr($fecha1[1],0,5);
			$fecha2 = explode(" ",$ffin);
			$ffin = $fecha2[0];
			$hfin = substr($fecha2[1],0,5);
		}
	}$pdf=new PDF('P','mm','Legal');  // si quieren el reporte horizontal$pdf->AddPage();
	$pdf->SetMargins(5,5,5);
	$pdf->Ln(2);
	$pdf->SetFont('Arial','B',12);
   $pdf->setX(15);
	$pdf->MultiCell(0, 5, utf8_decode('ACTA DE AUDITORÍA No. ').Agrega_Ceros($ejecucion), 0 , 'L' , 0);
	$pdf->SetFont('Arial','',12);
	$pdf->setX(15);
	$pdf->MultiCell(0, 6, utf8_decode('Fecha/Hora de impresión: ').date("d/m/Y H:i"), 0 , 'L' , 0);
	$pdf->setX(15);
	$pdf->MultiCell(0, 5, 'Generado por: '.$nombre_sesion, 0 , 'L' , 0);
	$pdf->Image('../../../CONFIG/img/replogo.jpg' , 180 ,5, 30 , 30,'JPG', '');
   $pdf->Ln(10);
   $pdf->setX(15);
   $pdf->SetFont('Arial','',11);
   $texto = "        Reunidos en la sede $sede, ubicada en la $direccion de $depmun, siendo $hini horas del día $fini, ";
   $texto.= "con el objeto de realizar el cierre de la auditoría denominada $nombre para el departamento $departamento, programada para el día $fecha_progra a las $hora_progra, procediendo de la siguiente manera:";
   $texto = utf8_decode($texto);
   $pdf->MultiCell(185, 4, $texto,0, 'J', false);
   
   $pdf->Ln(5);
   $pdf->SetFont('Arial','B',12);
   $pdf->setX(15);
   $pdf->MultiCell(185, 4, 'PRIMERO:',0, 'J', false);
   $pdf->SetFont('Arial','',11);
   $pdf->setX(15);
   $pdf->MultiCell(185, 4, utf8_decode('Se procedio a listar a los participantes del equipo de auditoría, siendo los siguiente:'),0, 'J', false);
   $pdf->Ln(3);
   $pdf->SetFont('Arial','',11);
   $ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_usuario_programacion($programacion,'');
   if(is_array($result)){
      $i = 1;
		foreach($result as $row){
			$nombre = utf8_decode($row["pus_tratamiento"])." ".utf8_decode($row["usu_nombre"]);
			$rol_auditoria = utf8_decode($row["pus_rol"]);
			$asignacion = utf8_decode($row["pus_asignacion"]);
         //--
         $pdf->setX(20);
         $pdf->MultiCell(175, 4, "$i. $nombre",0, 'L', false);
         $pdf->Ln(1);
         $pdf->setX(25);
         $pdf->MultiCell(165, 4, "Quien funge como $rol_auditoria",0, 'L', false);
         if($asignacion != ""){
			$pdf->Ln(1);
         $pdf->setX(25);
         $pdf->MultiCell(165, 4, "Asignado para $asignacion",0, 'L', false);
         }
         $i++;
			$pdf->Ln(5);
		}
	}else{
      $pdf->Ln(3);
		$pdf->setX(20);
      $pdf->MultiCell(185, 4, utf8_decode('- No hay participantes agregados para esta auditoría...'),0, 'J', false);
	}
   
   $pdf->Ln(5);
   $pdf->SetFont('Arial','B',12);
   $pdf->setX(15);
   $pdf->MultiCell(185, 4, 'SEGUNDO:',0, 'J', false);
   $pdf->SetFont('Arial','',11);
   $pdf->setX(15);
   $pdf->MultiCell(185, 4, utf8_decode('Se procedio revisar el itinerario de las actividades programadas, siendo las siguientes:'),0, 'J', false);
   $pdf->Ln(3);
   $pdf->SetFont('Arial','',11);
   $ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_actividades('',$programacion,1);
   if(is_array($result)){
      $i = 1;
		foreach($result as $row){
			$fecha = cambia_fecha($row["act_fecha"]);
			$hora = substr($row["act_hora"],0,5);
         $actividad = utf8_decode($row["act_descripcion"]);
         //--
         $pdf->setX(20);
         $pdf->MultiCell(175, 4, "$i. $actividad",0, 'L', false);
         $pdf->Ln(1);
         $pdf->setX(25);
         $pdf->MultiCell(165, 4, utf8_decode("El día $fecha a las $hora hrs."),0, 'L', false);
         $i++;
			$pdf->Ln(5);
		}
	}else{
      $pdf->Ln(3);
		$pdf->setX(20);
      $pdf->MultiCell(185, 4, utf8_decode('- No hay actividades programadas...'),0, 'J', false);
	}
   
   $pdf->Ln(5);
   $pdf->SetFont('Arial','B',12);
   $pdf->setX(15);
   $pdf->MultiCell(185, 4, 'TERCER:',0, 'J', false);
   $pdf->SetFont('Arial','',11);
   $pdf->setX(15);
   $pdf->MultiCell(185, 4, utf8_decode('Se realizó la revisón de actividades durante la ejecución de la auditoría, siendo las siguientes:'),0, 'J', false);
   $pdf->Ln(3);
   $pdf->SetFont('Arial','',11);
   ///Apertura e inicio de la auditoría
   $fechor = explode(" ",$fecha_inicio);
   $fecha = $fechor[0];
   $hora = substr($fechor[1],0,5);
   $pdf->setX(20);
   $pdf->MultiCell(175, 4, utf8_decode("1. Inicio de la auditría"),0, 'L', false);
   $pdf->Ln(1);
   $pdf->setX(25);
   $pdf->MultiCell(165, 4, utf8_decode("El día $fecha a las $hora hrs."),0, 'L', false);
   $pdf->Ln(5);
   //cambios de situacion
	$result = $ClsEje->get_ejecucion_situacion($ejecucion,'1,2,3,4');
   if(is_array($result)){
      $i = 2;
		foreach($result as $row){
         $situacion = trim($row["ejest_situacion"]);
         $fechor = cambia_fechaHora($row["ejest_fecha_registro"]);
         $fechor = explode(" ",$fechor);
         $fecha = $fechor[0];
         $hora = substr($fechor[1],0,5);
			$observaciones = utf8_decode($row["ejest_observacion"]);
         switch($situacion){
            case 1: $descripcion = utf8_decode("Ejecución de la auditoría"); break;
            case 2: $descripcion = utf8_decode("Finalización de la revisión"); break;
            case 3: $descripcion = utf8_decode("Se solicitó aprobación del auditor líder"); break;
            case 4: $descripcion = utf8_decode("Se aprobó la revisión"); break;
         }
         //--
         $pdf->setX(20);
         $pdf->MultiCell(175, 4, "$i. $descripcion",0, 'L', false);
         $pdf->Ln(1);
         $pdf->setX(25);
         $pdf->MultiCell(165, 4, utf8_decode("El día $fecha a las $hora hrs."),0, 'L', false);
         $i++;
			$pdf->Ln(5);
		}
	}
   
   $pdf->Ln(5);
   $pdf->SetFont('Arial','B',12);
   $pdf->setX(15);
   $pdf->MultiCell(185, 4, 'CUARTO:',0, 'J', false);
   $pdf->SetFont('Arial','',11);
   $pdf->setX(15);
   $pdf->MultiCell(185, 4, utf8_decode("No habiendo más que hacer constar se da por terminada la presente en el mismo lugar, el día $ffin a las $hfin, firmado conformes los que en ella intervinieron."),0, 'J', false);
   $pdf->Ln(5);
   //////////////////////////////////////////// FIRMAS ///////////////////////////////////////////////////////////////
   $pdf->SetFont('Arial','',8);
   $X = 20;
   $Y = $pdf->getY();
   $columna = 1;
   $ClsAud = new ClsAuditoria();
	$result = $ClsAud->get_usuario_programacion($programacion,'');
   if(is_array($result)){
      foreach($result as $row){
         if($columna == 1){
            $X = 20;
         }else if($columna == 2){
            $X = 68;
         }else if($columna == 3){
            $X = 117;
         }else if($columna == 4){
            $X = 165;
         }
         $usuario = utf8_decode($row["pus_tratamiento"])." ".utf8_decode($row["usu_nombre"]);
         $rol_auditoria = utf8_decode($row["pus_rol"]);
         $firma = trim($row["pus_firma"]);
         if(file_exists("../../../CONFIG/Fotos/AUDFIRMAS/$firma.jpg") && $firma != ""){
            $pdf->Image('../../../CONFIG/Fotos/AUDFIRMAS/'.$firma.'.jpg', $X, $Y, 40 , 15,'JPG', '');
         }else{
            $pdf->Image('../../../CONFIG/img/imageSign.jpg', $X, $Y, 40 , 15,'JPG', '');
         }
         $pdf->SetXY($X,$Y+20);
         $pdf->Cell(40, 5, $usuario, 'T', 0, 'C');
         $pdf->SetXY($X,$Y+23);
         $pdf->Cell(40, 5, $rol_auditoria, 0, 0, 'C');
         if($columna == 4){
            $columna = 1;
            $Y+= 50;
            if($Y >= 190){
               //$pdf->SetXY($X,$Y-6);
               //$pdf->Cell(60, 6, "Aqui son $Y (1)", 1, 0, 'C');
               $pdf->AddPage();
               $pdf->SetAutoPageBreak(false,2);
               $pdf->SetMargins(5,5,5);
               $Y = 20;
            }
         }else{
            $columna++;
         }
      }
   }

   
   //Salida de PDF, en esta parte se puede definir la salida, si es a pantalla o forzar la descarga
   $ejecucion = Agrega_Ceros($ejecucion);
   $pdf->Output("Acta $ejecucion.pdf","I");
  
  
?>