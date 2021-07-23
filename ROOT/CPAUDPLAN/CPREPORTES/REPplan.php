<?php
   //Incluir las librerias de FPDF 
   include_once('html_fns_reportes.php');
   $usuario = $_SESSION["codigo"];
   
   //$_POST
	$ClsAud = new ClsAuditoria();
	$ClsEje = new ClsEjecucion();
   $ClsPla = new ClsPlan();
	$plan = $_REQUEST["ejecucion"];
	//--
	$result = $ClsPla->get_plan($plan,'','');
	if(is_array($result)){
		$i = 0;	
		foreach ($result as $row){
			$ejecucion = trim($row["pla_ejecucion"]);
			$codigo_audit = trim($row["audit_codigo"]);
			$sede = utf8_decode($row["sed_nombre"]);
			$direccion = utf8_decode($row["sed_direccion"]);
			$depmun = utf8_decode($row["sede_municipio"]);
			$departamento = utf8_decode($row["dep_nombre"]);
			$categoria = utf8_decode($row["cat_nombre"]);
			$nombre = utf8_decode($row["audit_nombre"]);
			$usuario_nombre = utf8_decode($row["usuario_nombre"]);
			$strFirma = trim($row["pla_firma"]);
			//--
			$fecha_inicio = trim($row["pla_fecha_registro"]);
			$fecha_inicio = cambia_fechaHora($fecha_inicio);
			$fecha_inicio = substr($fecha_inicio,0,16);
			//--
			$fecha_update = trim($row["pla_fecha_update"]);
			$fecha_update = cambia_fechaHora($fecha_update);
			$fecha_update = substr($fecha_update,0,16);
			//--
			$fecha_progra = trim($row["pro_fecha"]);
			$fecha_progra = cambia_fecha($fecha_progra);
			$hora_progra = substr($row["pro_hora"],0,5);
			$fecha_progra = "$fecha_progra $hora_progra";
			$obs = utf8_decode($row["pro_observaciones"]);
			$obs = nl2br($obs);
			$responsable = utf8_decode($row["pla_responsable"]);
			//----------
         $strFirma = trim($row["pla_firma"]);
         $tramiento_usuario = utf8_decode($row["pla_tratamiento"]);
			$nombre_usuario = utf8_decode($row["pla_nombre"]);
			$rol_usuario = utf8_decode($row["pla_rol"]);
         $observaciones = utf8_decode($row["pla_observaciones"]);
			$situacion = trim($row["pla_situacion"]);
		}
	}// INICIA ESCRITURA DE PDF 
	$pdf = new PDF('P','mm','Letter');
	$pdf->AddPage();
	$pdf->SetAutoPageBreak(false,2);
	
   $pdf->SetMargins(5,5,5);
	$pdf->Ln(2);
   
   $pdf->SetFont('Arial','',10);
   $pdf->SetX(10);
	$pdf->MultiCell(195, 6, utf8_decode('Fecha/Hora de impresión: ').date("d/m/Y H:i"), 0 , 'L' , 0);
   $pdf->SetX(10);
	$pdf->MultiCell(195, 6, 'Impreso por: '.utf8_decode($_SESSION["nombre"]), 0 , 'L' , 0);
	$pdf->Image('../../../CONFIG/img/logo.jpg', 190, 10, 15 , 15,'JPG', '');
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetFont('Arial','',14);
   $pdf->SetXY(10,30);
   $pdf->Cell(92.5, 10, utf8_decode('Informe Final de Auditoría'), 0, 0, 'C',1);
   $pdf->SetFont('Arial','B',14);
   $pdf->SetXY(112.5,30);
   $pdf->Cell(92.5, 10, utf8_decode('Código # ').Agrega_Ceros($ejecucion), 0, 0, 'C');
   
   $pdf->SetFont('Arial','B',12);
   $pdf->SetXY(10,50);
   $pdf->MultiCell(92.5,6,utf8_decode('Ubicación'),'B','C');
   
   $pdf->SetFont('Arial','B',12);
   $pdf->SetXY(112.5,50);
   $pdf->MultiCell(92.5,6,utf8_decode('Información'),'B','C');
   
   $pdf->SetTextColor(0,0,0); // LETRA COLOR NEGRO
   //----------- IZQUIERDA ------//
   $pdf->SetFont('Arial','',8);
   //---
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetFont('Arial','',8);
   $pdf->SetXY(10,56);
   $pdf->Cell(25, 6, utf8_decode('Sede:'), 0, 0, 'L');
   $pdf->SetXY(35,56);
   $pdf->Cell(67.5, 6, $sede, 0, 0, 'L', 1);
   
   $pdf->SetFillColor(245, 247, 249);
   $pdf->SetXY(10,62);
   $pdf->Cell(25, 6, utf8_decode('Dirección:'), 0, 0, 'L');
   $pdf->SetXY(35,62);
   $pdf->Cell(67.5, 6, $direccion, 0, 0, 'L', 1);
   
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetXY(10,68);
   $pdf->Cell(25, 6, utf8_decode('Departamento:'), 0, 0, 'L');
   $pdf->SetXY(35,68);
   $pdf->Cell(67.5, 6, $departamento, 0, 0, 'L', 1);
   
   $pdf->SetFillColor(245, 247, 249);
   $pdf->SetXY(10,74);
   $pdf->Cell(25, 6, utf8_decode('Programación:'), 0, 0, 'L');
   $pdf->SetXY(35,74);
   $pdf->Cell(67.5, 6, $fecha_progra, 0, 0, 'L', 1);
   
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetXY(10,80);
   $pdf->Cell(25, 6, utf8_decode('Auditoria: '), 0, 0, 'L');
   $pdf->SetXY(35,80);
   $pdf->Cell(67.5, 6, Agrega_Ceros($ejecucion), 0, 0, 'L', 1);
   
   
   
   //----------- DERECHA ------//
   $pdf->SetFont('Arial','',8);
   //---
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetFont('Arial','',8);
   $pdf->SetXY(112.5,56);
   $pdf->Cell(25, 6, utf8_decode('Categoría:'), 0, 0, 'L');
   $pdf->SetXY(137.5,56);
   $pdf->Cell(67.5, 6, $categoria, 0, 0, 'L', 1);
   
   $pdf->SetFillColor(245, 247, 249);
   $pdf->SetXY(112.5,62);
   $pdf->Cell(25, 6, utf8_decode('Cuestionario:'), 0, 0, 'L');
   $pdf->SetXY(137.5,62);
   $pdf->Cell(67.5, 6, $nombre, 0, 0, 'L', 1);
   
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetXY(112.5,68);
   $pdf->Cell(25, 6, utf8_decode('Registro:'), 0, 0, 'L');
   $pdf->SetXY(137.5,68);
   $pdf->Cell(67.5, 6, $fecha_inicio, 0, 0, 'L', 1);
   
   $pdf->SetFillColor(245, 247, 249);
   $pdf->SetXY(112.5,74);
   $pdf->Cell(25, 6, utf8_decode('Actualización:'), 0, 0, 'L');
   $pdf->SetXY(137.5,74);
   $pdf->Cell(67.5, 6, $fecha_update, 0, 0, 'L', 1);
   
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetXY(112.5,80);
   $pdf->Cell(25, 6, utf8_decode('Registró:'), 0, 0, 'L');
   $pdf->SetXY(137.5,80);
   $pdf->Cell(67.5, 6, $usuario_nombre, 0, 0, 'L', 1);
   
   $pdf->setY(95);
   $result_seccion = $ClsAud->get_secciones('',$codigo_audit,1);
   if(is_array($result_seccion)){
      $i = 1;	
      foreach ($result_seccion as $row_seccion){
         $seccion_codigo = $row_seccion["sec_codigo"];
         $numero = trim($row_seccion["sec_numero"]);
         $titulo = utf8_decode($row_seccion["sec_titulo"]);
         $proposito = utf8_decode($row_seccion["sec_proposito"]);
         
         $Y = $pdf->getY();
         $pdf->SetFont('Arial','B',12);
         $pdf->SetXY(10,$Y);
         $pdf->MultiCell(10, 6, $numero.'.', 0, 'L');
         $pdf->SetXY(20,$Y);
         $pdf->MultiCell(185, 6, $titulo, 0, 'J');
         $Y+=10;
         $pdf->setY($Y);
         //---
         $result = $ClsAud->get_pregunta('',$codigo_audit,$seccion_codigo,1) ;
         if(is_array($result)){
            $i = 1;	
            foreach ($result as $row){
               $pregunta_codigo = $row["pre_codigo"];
               $pregunta_tipo = $row["pre_tipo"];
               $pregunta = utf8_decode($row["pre_pregunta"]);
               $peso = $row["pre_peso"];
               //--
               $respuesta = '0';
               $observacion = '';
               $aplica_desc = 'Aplica';
               $aplica = '';
               $result_respuesta = $ClsEje->get_respuesta($ejecucion,$codigo_audit,$pregunta_codigo);
               if(is_array($result_respuesta)){
                  foreach ($result_respuesta as $row_respuesta){
                     $aplica = utf8_decode($row_respuesta["resp_aplica"]);
                     $respuesta = utf8_decode($row_respuesta["resp_respuesta"]);
                     $observacion = utf8_decode($row_respuesta["resp_observacion"]);
                  }
                  $aplica_desc = ($aplica == 1)?'Aplica':'No Aplica';
               }	
               //--
               $Y = $pdf->getY();
               $Ysolucion = $pdf->getY(); //almacena la coordenada Y de su similar
               $pdf->SetFont('Arial','B',8);
               $pdf->SetXY(20,$Y);
               $pdf->MultiCell(10, 6, $i.'.', 0, 'L');
               $pdf->SetXY(30,$Y);
               $pdf->MultiCell(105, 6, $pregunta, 0, 'J');
               $Y = $pdf->getY();
               //-- Tipos de ponderaciones
               if($pregunta_tipo == 1){
                  $pdf->SetFont('Arial','',8);
                  $pdf->SetFillColor(236, 240, 244);
                  $pdf->SetXY(30,$Y);
                  $pdf->Cell(58, 6, $respuesta, 0, 0, 'C', 1);
                  //--
                  $pdf->SetFont('Arial','B',10);
                  $pdf->SetXY(90,$Y);
                  $pdf->Cell(45, 6, $aplica_desc, 0, 0, 'C', 1);
               }else if($pregunta_tipo == 2){
                  switch($respuesta){
                     case 1: $elemento = 'SI - '.$peso.' pts.'; break;
                     case 2: $elemento = 'NO'; break;
                     default: $elemento = '-'; break;
                  }
                  $pdf->SetFont('Arial','',8);
                  $pdf->SetFillColor(236, 240, 244);
                  $pdf->SetXY(30,$Y);
                  $pdf->Cell(58, 6, $elemento, 0, 0, 'C', 1);
                  //--
                  $pdf->SetFont('Arial','B',8);
                  $pdf->SetXY(90,$Y);
                  $pdf->Cell(45, 6, $aplica_desc, 0, 0, 'C', 1);
               }else if($pregunta_tipo == 3){
                  switch($respuesta){
                     case 1: $elemento = 'SATISFACTORIO'; break;
                     case 2: $elemento = 'NO SATISFACTORIO'; break;
                     default: $elemento = '-'; break;
                  }
                  $pdf->SetFont('Arial','',8);
                  $pdf->SetFillColor(236, 240, 244);
                  $pdf->SetXY(30,$Y);
                  $pdf->Cell(58, 6, $elemento, 0, 0, 'C', 1);
                  //--
                  $pdf->SetFont('Arial','B',8);
                  $pdf->SetXY(90,$Y);
                  $pdf->Cell(45, 6, $aplica_desc, 0, 0, 'C', 1);
               }
               //-
               $Y+=7;
               $pdf->setY($Y);
               //-- Respuesta
               //$respuesta = "esta es una respuesta";
               $observacion = ($observacion == "")?" - ":$observacion;
               $Y = $pdf->getY();
               $pdf->SetFont('Arial','',8);
               $pdf->SetFillColor(245, 247, 249);
               $pdf->SetXY(30,$Y);
               $pdf->Cell(105, 31, '', 0, 0, 'C', 1);
               $pdf->SetXY(30,$Y);
               $pdf->MultiCell(105, 5, $observacion, 0, 'J', 1);
               //-
               //-- Solucion
               $fecha_sol = "";
               $solucion = "";
               $result_plan = $ClsPla->get_solucion($ejecucion,$codigo_audit,$pregunta_codigo);
               if(is_array($result_plan)){
                  foreach ($result_plan as $row_plan){
                     $fecha_sol = cambia_fecha($row_plan["sol_fecha"]);
                     $solucion = utf8_decode($row_plan["sol_solucion"]);
                     $responsable = utf8_decode($row_plan["sol_responsable_nombre"]);
                     $status = utf8_decode($row_plan["sta_nombre"]);
                     $solucionado = cambia_fechaHora($row_plan["sol_fecha_solucion"]);
                  }	
               }else{
                  $status = "Pendiente";
                  $responsable = "";
               }
               $Y = $Ysolucion;
               $pdf->SetFont('Arial','B',8);
               $pdf->SetFillColor(255, 255, 255);
               $pdf->SetXY(137,$Y);
               $pdf->Cell(70, 6,utf8_decode('Responsable:'), 0, 0, 'L', 1);
               $Y+=6;
               $pdf->SetFillColor(236, 240, 244);
               $pdf->SetFont('Arial','',8);
               $pdf->SetXY(137,$Y);
               $pdf->Cell(70, 6, $responsable, 0, 0, 'L', 1);
               $Y+=6;
               $pdf->SetFont('Arial','B',8);
               $pdf->SetFillColor(255, 255, 255);
               $pdf->SetXY(137,$Y);
               $pdf->Cell(70, 6,utf8_decode('Próxima Auditoría:'), 0, 0, 'L', 1);
               $Y+=6;
               $pdf->SetFillColor(236, 240, 244);
               $pdf->SetFont('Arial','',8);
               $pdf->SetXY(137,$Y);
               $pdf->Cell(70, 5, $fecha_sol, 0, 0, 'C', 1);
               $Y+=6;
               $pdf->SetFont('Arial','',8);
               $pdf->SetXY(137,$Y);
               $pdf->Cell(70, 20, '', 0, 0, 'C', 1);
               $pdf->SetXY(137,$Y);
               $pdf->MultiCell(70, 4, $solucion, 0, 'J', 1);
               /*$Y+=21;
               $pdf->SetFont('Arial','B',8);
               $pdf->SetFillColor(255, 255, 255);
               $pdf->SetXY(137,$Y);
               $pdf->Cell(35, 5,utf8_decode('Status:'), 0, 0, 'L', 1);
               $pdf->SetXY(172,$Y);
               $pdf->Cell(35, 5,utf8_decode('Solucionado (Fecha):'), 0, 0, 'L', 1);
               $Y+=5;
               $pdf->SetFillColor(236, 240, 244);
               $pdf->SetFont('Arial','',8);
               $pdf->SetXY(137,$Y);
               $pdf->Cell(34, 5, $status, 0, 0, 'L', 1);
               $pdf->SetXY(172,$Y);
               $pdf->Cell(35, 5, $solucionado, 0, 0, 'L', 1);*/
               
               
               $Y = $pdf->getY();
               $pdf->setY($Y);
               
               
               $Y = $pdf->getY();
               $Y+=7;
               $pdf->setY($Y);
               //////// IMAGENES ///////
               $result = $ClsEje->get_fotos(1,$ejecucion,$codigo_audit,$pregunta_codigo);
               if(is_array($result)){
                  foreach ($result as $row){
                     $strFoto = trim($row["fot_foto"]);
                     if(file_exists('../../../CONFIG/Fotos/AUDITORIA/'.$strFoto.'.jpg') && $strFoto != ""){
                        //$pdf->Image('../../../CONFIG/Fotos/AUDITORIA/'.$strFoto.'.jpg', 145, $Y, 50 , 35,'JPG', '');
                     }else{
                        //$pdf->Image('../../../CONFIG/img/imagePhoto.jpg', 145, $Y, 50 , 35,'JPG', '');
                     }
                  }	
               }else{
                  //$pdf->Image('../../../CONFIG/img/imagePhoto.jpg', 145, $Y, 50 , 35,'JPG', '');
               }
               
               
               ///////////////// VALIDA EL CAMBIO DE PAGINA
               $yyy = $pdf->getY();
               if($yyy <= 216){
                  $pdf->Ln(10);
               }else{
                  $pdf->AddPage();
                  $pdf->SetAutoPageBreak(false,2);
                  
                  $mleft = 0;
                  $mtop = 0;
                  $pdf->SetMargins($mleft,$mtop); //0.5 centimetro de margen izquierdo
                  
                  $pdf->setY(30);
               }
               /// AUMENTA VUELTA
               $i++;
            }
            $i--;
         }else{
            $pdf->SetFillColor(236, 240, 244);
            $pdf->SetFont('Arial','',10);
            $pdf->SetXY(10,$Y);
            $pdf->Cell(195, 6, ' - No hay preguntas -', 0, 0, 'C', true);
         }
      }
   }
					
   $Y = $pdf->getY();
   $Y+=10;
   //---
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetFont('Arial','B',11);
   $pdf->SetXY(10,$Y);
   $pdf->Cell(195, 6, 'Observaciones:', 0, 0, 'L');
   $Y+=8;
   $pdf->SetFont('Arial','',10);
   $pdf->SetXY(10,$Y);
   $pdf->Cell(195, 40, '', 0, 0, 'C', 1);
   $pdf->SetXY(12,$Y+2);
   $pdf->MultiCell(192, 4, $observaciones, 0, 'J', true);
   $Y+=42;
   $pdf->setY($Y);
   
   //////////// RESPONSABLE Y FIRMA ///////////
   $Y = $pdf->getY();
   $Y+=5;
   if($Y > 236){
      $pdf->AddPage();
      $pdf->SetAutoPageBreak(false,2);
      $Y = 0;
   }
   $Y+=5;
   if(file_exists('../../../CONFIG/Fotos/AUDFIRMAS/'.$strFirma.'.jpg') && $strFirma != ""){
      $pdf->Image('../../../CONFIG/Fotos/AUDFIRMAS/'.$strFirma.'.jpg', 97.5, $Y, 40 , 30,'JPG', '');
   }else{
      $pdf->Image('../../../CONFIG/img/imageSign.jpg', 97.5, $Y, 40 , 30,'JPG', '');
   }
   $Y+=32;
   $pdf->SetFillColor(255, 255, 255);
   $pdf->SetFont('Arial','',10);
   $pdf->SetXY(77.5,$Y);
   $pdf->MultiCell(80, 6, "$tramiento_usuario $nombre_usuario", 'T', 'C', true);
   $Y+=5;
   $pdf->SetFont('Arial','I',8);
   $pdf->SetXY(77.5,$Y);
   $pdf->MultiCell(80, 5, $rol_usuario, 0, 'C', true);
   
   //////////// PIE DE IMPRESIÓN ///////////
   $pdf->SetY(265);
   $pdf->SetFont('Arial','',10);
   $pdf->SetX(10);
	$pdf->MultiCell(195, 4, utf8_decode('Fecha/Hora de impresión: ').date("d/m/Y H:i"), 0 , 'R' , 0);
   $pdf->SetX(10);
	$pdf->MultiCell(195, 4, 'Impreso por: '.utf8_decode($_SESSION["nombre"]), 0 , 'R' , 0);
   
   //Salida de PDF, en esta parte se puede definir la salida, si es a pantalla o forzar la descarga
   $ejecucion = Agrega_Ceros($ejecucion);
   $pdf->Output("Informe Final No. $ejecucion.pdf","I");
  
  
?>