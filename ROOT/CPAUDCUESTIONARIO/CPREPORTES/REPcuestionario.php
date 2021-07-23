<?php
   //Incluir las librerias de FPDF 
   include_once('html_fns_reportes.php');
   $usuario = $_SESSION["codigo"];
   
   //$_POST
	$ClsAud = new ClsAuditoria();
	$cuestionario = $_REQUEST["codigo"];
	//--
	$result = $ClsAud->get_cuestionario($cuestionario,'','',1);
	if(is_array($result)){
		$i = 0;	
		foreach ($result as $row){
			$codigo_audit = trim($row["audit_codigo"]);
         $ponderacion_audit = trim($row["audit_ponderacion"]);
			$categoria_codigo = trim($row["cat_codigo"]);
         $categoria = utf8_decode($row["cat_nombre"]);
			$nombre = utf8_decode($row["audit_nombre"]);
		}
	}

   ///////////// ------------- ///////////////////// INICIA ESCRITURA DE PDF 
	$pdf = new PDF('P','mm','Letter');
	$pdf->AddPage();
	$pdf->SetAutoPageBreak(false,2);
	
   $pdf->SetMargins(5,5,5);
	$pdf->Ln(2);
   
   $pdf->SetFont('Arial','',10);
   $pdf->SetX(10);
	$pdf->MultiCell(195, 5, utf8_decode('Fecha/Hora de impresión: ').date("d/m/Y H:i"), 0 , 'L' , 0);
   $pdf->SetX(10);
	$pdf->MultiCell(195, 5, 'Impreso por: '.utf8_decode($_SESSION["nombre"]), 0 , 'L' , 0);
	$pdf->Image('../../../CONFIG/img/logo.jpg', 180, 5, 25 , 25,'JPG', '');
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetFont('Arial','',14);
   $pdf->SetXY(10,30);
   $pdf->Cell(92.5, 10, utf8_decode('Previsualización de Cuestionario'), 0, 0, 'C',1);
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
   $pdf->Cell(25, 6, utf8_decode('Evaluado: '), 0, 0, 'L');
   $pdf->SetXY(35,80);
   $pdf->Cell(67.5, 6, $responsable, 0, 0, 'L', 1);
   
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetXY(10,86);
   $pdf->Cell(25, 6, utf8_decode('Auditoría Anterior: '), 0, 0, 'L');
   $pdf->SetXY(35,86);
   $pdf->Cell(67.5, 6, $ultima_fecha, 0, 0, 'L', 1);
   
   
   
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
   $pdf->Cell(25, 6, utf8_decode('Inicio:'), 0, 0, 'L');
   $pdf->SetXY(137.5,68);
   $pdf->Cell(67.5, 6, $fecha_inicio, 0, 0, 'L', 1);
   
   $pdf->SetFillColor(245, 247, 249);
   $pdf->SetXY(112.5,74);
   $pdf->Cell(25, 6, utf8_decode('Finalización:'), 0, 0, 'L');
   $pdf->SetXY(137.5,74);
   $pdf->Cell(67.5, 6, $fecha_finaliza, 0, 0, 'L', 1);
   
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetXY(112.5,80);
   $pdf->Cell(25, 6, utf8_decode('Registró:'), 0, 0, 'L');
   $pdf->SetXY(137.5,80);
   $pdf->Cell(67.5, 6, $usuario_nombre, 0, 0, 'L', 1);
   
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetXY(112.5,86);
   $pdf->Cell(25, 6, utf8_decode('Última Nota:'), 0, 0, 'L');
   $pdf->SetXY(137.5,86);
   $pdf->Cell(67.5, 6, $ultima_nota, 0, 0, 'L', 1);
   
   $pdf->setY(101);
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
               

               $Y = $pdf->getY();
               $pdf->SetFont('Arial','B',10);
               $pdf->SetXY(20,$Y);
               $pdf->MultiCell(10, 6, $i.'.', 0, 'L');
               $pdf->SetXY(30,$Y);
               $pdf->MultiCell(185, 6, $pregunta, 0, 'J');
               $Y = $pdf->getY();
               $Yimg = $pdf->getY();
               //-- Tipos de ponderaciones
               if($pregunta_tipo == 1){
                  $pdf->SetFont('Arial','',10);
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
                  $pdf->SetFont('Arial','',10);
                  $pdf->SetFillColor(236, 240, 244);
                  $pdf->SetXY(30,$Y);
                  $pdf->Cell(58, 6, $elemento, 0, 0, 'C', 1);
                  //--
                  $pdf->SetFont('Arial','B',10);
                  $pdf->SetXY(90,$Y);
                  $pdf->Cell(45, 6, $aplica_desc, 0, 0, 'C', 1);
               }else if($pregunta_tipo == 3){
                  switch($respuesta){
                     case 1: $elemento = 'SATISFACTORIO'; break;
                     case 2: $elemento = 'NO SATISFACTORIO'; break;
                     default: $elemento = '-'; break;
                  }
                  $pdf->SetFont('Arial','',10);
                  $pdf->SetFillColor(236, 240, 244);
                  $pdf->SetXY(30,$Y);
                  $pdf->Cell(58, 6, $elemento, 0, 0, 'C', 1);
                  //--
                  $pdf->SetFont('Arial','B',10);
                  $pdf->SetXY(90,$Y);
                  $pdf->Cell(45, 6, $aplica_desc, 0, 0, 'C', 1);
               }
               $Y = $pdf->getY();
               $Y+=7;
               $pdf->setY($Y);
               //-- Respuesta
               $observacion = utf8_decode("- Observaciones o descripción del hallazgo -");
               $Y = $pdf->getY();
               $pdf->SetFont('Arial','',10);
               $pdf->SetFillColor(245, 247, 249);
               $pdf->SetXY(30,$Y);
               $pdf->Cell(105, 28, '', 0, 0, 'C', 1);
               $pdf->SetTextColor(156, 156, 156);
               $pdf->SetXY(30,$Y);
               $pdf->MultiCell(105, 5, $observacion, 0, 'J', 1);
               $pdf->SetTextColor(0, 0, 0);
               
               //////// IMAGENES ///////
               $pdf->Image('../../../CONFIG/img/imagePhoto.jpg', 145, $Yimg, 50 , 35,'JPG', '');
               $Yimg+=34;
               $pdf->setY($Yimg);
               
               
               ///////////////// VALIDA EL CAMBIO DE PAGINA
               $yyy = $pdf->getY();
               if($yyy <= 218){
                  $pdf->Ln(10);
               }else{
                  $pdf->AddPage();
                  $pdf->SetAutoPageBreak(false,2);
                  
                  $mleft = 0;
                  $mtop = 0;
                  $pdf->SetMargins($mleft,$mtop); //0.5 centimetro de margen izquierdo
                  
                  $pdf->setY(10);
               }
               /// AUMENTA VUELTA
               $i++;
            }
            $i--;
         }
      }
   }
   
   ////////////////////////////////////////////////////////////////// NOTAS /////////////////////////////////////////////////////////////				
   /////////////////////// NOTAS POR SECCIÓN ////////////////////////
   $Y = $pdf->getY();
   //---
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetXY(10,$Y);
   $pdf->SetFont('Arial','B',10);
   $pdf->MultiCell(195, 10, utf8_decode("Notas por sección"), 0, 'C', true);
   $Y+=11;
   $result = $ClsAud->get_secciones('',$codigo_audit,1);
	if(is_array($result)){
		foreach ($result as $row){
			$validas = 0;
			$nota = 0;
			$suma = 0;
         $pesoTotal = 0;
			$si = 0;
			$no = 0;
			$na = 0;
			//nota
			$nota = number_format($nota,2,'.','');
			//seccion
			$seccion = trim($row["sec_numero"]).". ".utf8_decode($row["sec_titulo"]);
         //--
         $pdf->SetFont('Arial','',10);
         $pdf->SetFillColor(245, 247, 249);
         $pdf->SetXY(10,$Y);
         $pdf->Cell(138, 6, $seccion, 0, 0, 'L', 1);
         //--
         $pdf->SetFont('Arial','B',10);
         $pdf->SetXY(150,$Y);
         $pdf->Cell(55, 6, $nota, 0, 0, 'C', 1);
         $Y+=7;
         ///////////////// VALIDA EL CAMBIO DE PAGINA
         $yyy = $Y;
         if($yyy <= 266){
            $pdf->Ln(10);
         }else{
            $pdf->AddPage();
            $pdf->SetAutoPageBreak(false,2);
            
            $mleft = 0;
            $mtop = 0;
            $pdf->SetMargins($mleft,$mtop); //0.5 centimetro de margen izquierdo
            
            $pdf->setY(10);
            $Y = 10;
         }
		}
	}
   //---
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetXY(10,$Y);
   $pdf->SetFont('Arial','B',12);
   $pdf->MultiCell(195, 10, utf8_decode("Nota Total $NOTA"), 0, 'C', true);
   
   $Y = $pdf->getY();
   if($Y > 236){
      $pdf->AddPage();
      $pdf->SetAutoPageBreak(false,2);
      $Y = 0;
   }
   $Y+=5;
   $pdf->SetXY(38.75,$Y);
   $pdf->Cell(40, 30, '', 1, 0, 'C', false);
   $pdf->SetXY(136.25,$Y);
   $pdf->Cell(40, 30, '', 1, 0, 'C', false);
   //$pdf->Image('../../../CONFIG/img/imageSign.jpg', 38.75, $Y, 40 , 30,'JPG', '');
   //$pdf->Image('../../../CONFIG/img/imageSign.jpg', 136.25, $Y, 40 , 30,'JPG', '');
   $Y+=30;
   $pdf->SetFillColor(255, 255, 255);
   $pdf->SetXY(20,$Y);
   $pdf->SetFont('Arial','',10);
   $pdf->MultiCell(80, 6, utf8_decode("Firma Auditor(a)"), 0, 'C', true);
   //---
   $pdf->SetFillColor(255, 255, 255);
   $pdf->SetXY(117,$Y);
   $pdf->SetFont('Arial','',10);
   $pdf->MultiCell(80, 6, utf8_decode("Firma Evaluado(a)"), 0, 'C', true);
   $Y+=10;
   ///////////////// VALIDA EL CAMBIO DE PAGINA
   $yyy = $Y;
   if($yyy <= 236){
      $pdf->Ln(10);
   }else{
      $pdf->AddPage();
      $pdf->SetAutoPageBreak(false,2);
      
      $mleft = 0;
      $mtop = 0;
      $pdf->SetMargins($mleft,$mtop); //0.5 centimetro de margen izquierdo
      
      $pdf->setY(10);
      $Y = 10;
   }
   //---
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetXY(10,$Y);
   $pdf->SetFont('Arial','B',10);
   $pdf->MultiCell(195, 10, utf8_decode("Cierre de Auditoría"), 0, 'C', true);
   ///////////////// VALIDA EL CAMBIO DE PAGINA
   $yyy = $Y;
   if($yyy <= 236){
      $pdf->Ln(10);
   }else{
      $pdf->AddPage();
      $pdf->SetAutoPageBreak(false,2);
      
      $mleft = 0;
      $mtop = 0;
      $pdf->SetMargins($mleft,$mtop); //0.5 centimetro de margen izquierdo
      
      $pdf->setY(10);
      $Y = 10;
   }
   //---
   $Y+=12;
   $pdf->SetFont('Arial','B',11);
   $pdf->SetFillColor(245, 247, 249);
   $pdf->SetXY(10,$Y);
   $pdf->Cell(195, 6, utf8_decode('Correos de notificación:'), 0, 0, 'L');
   $Y+=6;
   $pdf->SetFont('Arial','',11);
   $pdf->SetXY(10,$Y);
   $pdf->MultiCell(195, 6, $correos, 0, 'J', true);
   $Y+=7;
   ///////////////// VALIDA EL CAMBIO DE PAGINA
   $yyy = $Y;
   if($yyy <= 236){
      $pdf->Ln(10);
   }else{
      $pdf->AddPage();
      $pdf->SetAutoPageBreak(false,2);
      
      $mleft = 0;
      $mtop = 0;
      $pdf->SetMargins($mleft,$mtop); //0.5 centimetro de margen izquierdo
      
      $pdf->setY(10);
      $Y = 10;
   }
   //---
   $pdf->SetFont('Arial','B',11);
   $pdf->SetFillColor(245, 247, 249);
   $pdf->SetXY(10,$Y);
   $pdf->Cell(195, 6, 'Responsable o Evaluado:', 0, 0, 'L');
   $Y+=6;
   $pdf->SetFont('Arial','',11);
   $pdf->SetXY(10,$Y);
   $pdf->MultiCell(195, 6, $responsable, 0, 'J', true);
   $Y+=10;
   ///////////////// VALIDA EL CAMBIO DE PAGINA
   $yyy = $Y;
   if($yyy <= 266){
      $pdf->Ln(10);
   }else{
      $pdf->AddPage();
      $pdf->SetAutoPageBreak(false,2);
      
      $mleft = 0;
      $mtop = 0;
      $pdf->SetMargins($mleft,$mtop); //0.5 centimetro de margen izquierdo
      
      $pdf->setY(10);
      $Y = 10;
   }
   //---
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetXY(10,$Y);
   $pdf->SetFont('Arial','B',10);
   $pdf->MultiCell(195, 10, utf8_decode("Observaciones"), 0, 'C', true);
   ///////////////// VALIDA EL CAMBIO DE PAGINA
   $yyy = $Y;
   if($yyy <= 236){
      $pdf->Ln(10);
   }else{
      $pdf->AddPage();
      $pdf->SetAutoPageBreak(false,2);
      
      $mleft = 0;
      $mtop = 0;
      $pdf->SetMargins($mleft,$mtop); //0.5 centimetro de margen izquierdo
      
      $pdf->setY(10);
      $Y = 10;
   }
   //---
   $Y+=12;
   $ClsDep = new ClsDepartamento();
   $result = $ClsDep->get_departamento('','',1,1);
   if(is_array($result)){
      foreach($result as $row){
         $departamento = $row["dep_codigo"];
         $departamento_desc = utf8_decode($row["dep_nombre"]);
         ////--
         $pdf->SetFillColor(245, 247, 249);
         $pdf->SetFont('Arial','B',11);
         $pdf->SetXY(10,$Y);
         $pdf->Cell(195, 6, $departamento_desc.":", 0, 0, 'L');
         $Y+=6;
         $pdf->SetFont('Arial','',11);
         $pdf->SetXY(10,$Y);
         $pdf->MultiCell(195, 6, $depObservacion, 0, 'J', true);
         if($Y > 236){
            $pdf->AddPage();
            $pdf->SetAutoPageBreak(false,2);
            $Y = 0;
         }
         $Y+=7;
      }
   }
   ////--
   $pdf->SetFillColor(236, 240, 244);
   $pdf->SetFont('Arial','B',11);
   $pdf->SetXY(10,$Y);
   $pdf->Cell(195, 6, 'Observaciones generales:', 0, 0, 'L');
   $Y+=6;
   $pdf->SetFont('Arial','',11);
   $pdf->Rect(10, $Y, 195, 24, 'F');
   $pdf->SetXY(10.5,$Y+0.5);
   $pdf->MultiCell(194, 6, $EjeObservacion, 0, 'J', true);
   $Y+=10;
   
   $Y = $pdf->getY();
   if($Y > 236){
      $pdf->AddPage();
      $pdf->SetAutoPageBreak(false,2);
      $Y = 0;
   }
   $Y+=10;
   
   
   //Salida de PDF, en esta parte se puede definir la salida, si es a pantalla o forzar la descarga
   $ejecucion = Agrega_Ceros($ejecucion);
   $pdf->Output("Revisión $ejecucion.pdf","I");
  
  
?>