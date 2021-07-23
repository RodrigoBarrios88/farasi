<?php
   //Incluir las librerias de FPDF 
   include_once('html_fns_reportes.php');
   $usuario = $_SESSION["codigo"];
   
//$_POST
$sede = $_REQUEST["sede"];
$sector = $_REQUEST["sector"];
$area = $_REQUEST["area"];
$situacion = $_REQUEST["situacion"];

$pdf = new PDF('P','mm','Legal');
      
$ClsAct = new ClsActivo();
$result = $ClsAct->get_activo('', $sede, $sector, $area, $situacion);
if(is_array($result)){
   foreach($result as $row){
      $codigo = trim($row["act_codigo"]);
      //--
      $sede = utf8_decode($row["sed_nombre"]); 		
      $sector = utf8_decode($row["sec_nombre"]); 		
      $area = utf8_decode($row["are_nombre"]);
      $nivel = utf8_decode($row["are_nivel"]); 		
      $nombre = utf8_decode($row["act_nombre"]); 		
      $marca = utf8_decode($row["act_marca"]); 		
      $serie = utf8_decode($row["act_serie"]); 		
      $modelo = utf8_decode($row["act_modelo"]); 		
      $parte = utf8_decode($row["act_parte"]); 		
      $proveedor = utf8_decode($row["act_proveedor"]); 		
      $periodicidad = utf8_decode($row["act_periodicidad"]); 		
      $capacidad = utf8_decode($row["act_capacidad"]); 		
      $cantidad = trim($row["act_cantidad"]); 		
      $precio1 = trim($row["act_precio_nuevo"]); 		
      $precio2 = trim($row["act_precio_compra"]); 		
      $precio3 = trim($row["act_precio_actual"]);
      $observaciones = utf8_decode($row["act_observaciones"]);
      //--
      $sit = trim($row["act_situacion"]);
      $situacion = ($sit == 1)?'Activo':'Inactivo';
      if($sit == 1){
         $rsit = 81;
         $gsit = 188;
         $bsit = 218;
      }else{
         $rsit = 239;
         $gsit = 129;
         $bsit = 87;
      }
      
      switch($periodicidad){
         case "D": $periodicidad = "Diario"; break;
         case "W": $periodicidad = "Semanal"; break;
         case "M": $periodicidad = "Mensual"; break;
         case "Y": $periodicidad = "Anual"; break;
         case "V": $periodicidad = "Variado"; break;
      }
      
      //////////////// IMAGENES /////////////////////
      $result = $ClsAct->get_fotos('',$codigo,1);
      if(is_array($result)){
         $i = 0;	
         foreach ($result as $row){
            $fotCodigo = trim($row["fot_codigo"]);
            $posicion = trim($row["fot_posicion"]);
            $strFoto1 = trim($row["fot_foto"]);
            if(file_exists('../../../CONFIG/Fotos/ACTIVOS/'.$strFoto1.'.jpg') || $strFoto1 != ""){
               $strFoto1 = '../../../CONFIG/Fotos/ACTIVOS/'.$strFoto1.'.jpg';
            }else{
               $strFoto1 = '../../../CONFIG/img/imagePhoto.jpg';
            }
         }
      }else{
         $strFoto1 = '../../../CONFIG/img/imagePhoto.jpg';
      }
      
      $result = $ClsAct->get_fotos('',$codigo,2);
      if(is_array($result)){
         $i = 0;	
         foreach ($result as $row){
            $fotCodigo = trim($row["fot_codigo"]);
            $posicion = trim($row["fot_posicion"]);
            $strFoto2 = trim($row["fot_foto"]);
            if(file_exists('../../../CONFIG/Fotos/ACTIVOS/'.$strFoto2.'.jpg') || $strFoto2 != ""){
               $strFoto2 = '../../../CONFIG/Fotos/ACTIVOS/'.$strFoto2.'.jpg';
            }else{
               $strFoto2 = '../../../CONFIG/img/imagePhoto.jpg';
            }
         }
      }else{
         $strFoto2 = '../../../CONFIG/img/imagePhoto.jpg';
      }
      
      $result = $ClsAct->get_fotos('',$codigo,3);
      if(is_array($result)){
         $i = 0;	
         foreach ($result as $row){
            $fotCodigo = trim($row["fot_codigo"]);
            $posicion = trim($row["fot_posicion"]);
            $strFoto3 = trim($row["fot_foto"]);
            if(file_exists('../../../CONFIG/Fotos/ACTIVOS/'.$strFoto3.'.jpg') || $strFoto3 != ""){
               $strFoto3 = '../../../CONFIG/Fotos/ACTIVOS/'.$strFoto3.'.jpg';
            }else{
               $strFoto3 = '../../../CONFIG/img/imagePhoto.jpg';
            }
         }
      }else{
         $strFoto3 = '../../../CONFIG/img/imagePhoto.jpg';
      }
   
	
      $pdf->AddPage();
      $pdf->SetAutoPageBreak(false,2);
      $pdf->SetMargins(5,5);
      
      $pdf->SetFont('Arial','',8);
      $pdf->SetXY(10,5);
      $pdf->MultiCell(195, 3, utf8_decode('Fecha/Hora de impresión: ').date("d/m/Y H:i"), 0 , 'L' , 0);
      $pdf->SetXY(10,8);
      $pdf->MultiCell(195, 3, 'Impreso por: '.utf8_decode($_SESSION["nombre"]), 0 , 'L' , 0);
      
      $pdf->SetDrawColor(145, 145, 145);
      $pdf->SetTextColor(74, 74, 74); // gris
      $pdf->SetFont('Arial','B',14);
      $pdf->SetXY(10,20);
      $pdf->Cell(195, 8, $nombre, 'B', 0, 'L', 0);
      //logo
      $pdf->Image('../../../CONFIG/img/logo.jpg', 175, 5, 20 , 20,'JPG', '');
      
      //--
      $pdf->SetFillColor(236, 240, 244);
      $pdf->SetFont('Arial','B',14);
      $pdf->SetXY(10,30);
      $pdf->Cell(92.5, 10, utf8_decode('Código # ').Agrega_Ceros($codigo), 0, 0, 'C',1);
      $pdf->SetFont('Arial','B',12);
      $pdf->SetXY(112.5,30);
      $pdf->SetTextColor($rsit, $gsit, $bsit); // color segun las situacion
      $pdf->Cell(92.5, 10, utf8_decode( 'Situación: ' . $situacion ), 0, 0, 'C',1);
      
      $pdf->SetTextColor(74, 74, 74); // gris
      
      $pdf->SetFont('Arial','B',12);
      $pdf->SetXY(10,45);
      $pdf->MultiCell(92.5,6,utf8_decode('Ubicación'),'B','C');
      
      $pdf->SetFont('Arial','B',12);
      $pdf->SetXY(112.5,45);
      $pdf->MultiCell(92.5,6,utf8_decode('Información'),'B','C');
      
      //----------- IZQUIERDA ------//
      $pdf->SetFont('Arial','',8);
      //---
      $pdf->SetFillColor(236, 240, 244);
      $pdf->SetXY(10,56);
      $pdf->Cell(20, 6, utf8_decode('Sede:'), 0, 0, 'L');
      $pdf->SetXY(35,56);
      $pdf->Cell(67.5, 6, $sede, 0, 0, 'L', 1);
      
      $pdf->SetFillColor(245, 247, 249);
      $pdf->SetXY(10,62);
      $pdf->Cell(20, 6, utf8_decode('Sector:'), 0, 0, 'L');
      $pdf->SetXY(35,62);
      $pdf->Cell(67.5, 6, $sector, 0, 0, 'L', 1);
      
      $pdf->SetFillColor(236, 240, 244);
      $pdf->SetXY(10,68);
      $pdf->Cell(20, 6, utf8_decode('Área:'), 0, 0, 'L');
      $pdf->SetXY(35,68);
      $pdf->Cell(67.5, 6, $area, 0, 0, 'L', 1);
      
      $pdf->SetFillColor(245, 247, 249);
      $pdf->SetXY(10,74);
      $pdf->Cell(20, 6, utf8_decode('Nivel:'), 0, 0, 'L');
      $pdf->SetXY(35,74);
      $pdf->Cell(67.5, 6, $nivel, 0, 0, 'L', 1);
      
      $pdf->SetFillColor(236, 240, 244);
      $pdf->SetXY(10,80);
      $pdf->Cell(20, 6, utf8_decode('Proveedor:'), 0, 0, 'L');
      $pdf->SetXY(35,80);
      $pdf->Cell(67.5, 6, $proveedor, 0, 0, 'L', 1);
      
      $pdf->SetFont('Arial','B',12);
      $pdf->SetXY(10,91);
      $pdf->MultiCell(92.5,6,utf8_decode('Precios'),'B','C');
      
      $pdf->SetFont('Arial','',8);
      $pdf->SetFillColor(245, 247, 249);
      $pdf->SetXY(10,98);
      $pdf->Cell(20, 6, utf8_decode('Original (Nuevo):'), 0, 0, 'L');
      $pdf->SetXY(35,98);
      $pdf->Cell(67.5, 6, $precio1, 0, 0, 'L', 1);
      
      $pdf->SetFillColor(236, 240, 244);
      $pdf->SetXY(10,104);
      $pdf->Cell(20, 6, utf8_decode('Adquicisión:'), 0, 0, 'L');
      $pdf->SetXY(35,104);
      $pdf->Cell(67.5, 6, $precio2, 0, 0, 'L', 1);
      
      
      //----------- DERECHA ------//
      $pdf->SetFont('Arial','',8);
      //---
      $pdf->SetFillColor(236, 240, 244);
      $pdf->SetXY(112.5,56);
      $pdf->Cell(20, 6, utf8_decode('Nombre:'), 0, 0, 'L');
      $pdf->SetXY(132.5,56);
      $pdf->Cell(72.5, 6, $nombre, 0, 0, 'L', 1);
      
      $pdf->SetFillColor(245, 247, 249);
      $pdf->SetXY(112.5,62);
      $pdf->Cell(20, 6, utf8_decode('Marca:'), 0, 0, 'L');
      $pdf->SetXY(132.5,62);
      $pdf->Cell(72.5, 6, $marca, 0, 0, 'L', 1);
      
      $pdf->SetFillColor(236, 240, 244);
      $pdf->SetXY(112.5,68);
      $pdf->Cell(20, 6, utf8_decode('No. Serie:'), 0, 0, 'L');
      $pdf->SetXY(132.5,68);
      $pdf->Cell(72.5, 6, $serie, 0, 0, 'L', 1);
      
      $pdf->SetFillColor(245, 247, 249);
      $pdf->SetXY(112.5,74);
      $pdf->Cell(20, 6, utf8_decode('Modelo:'), 0, 0, 'L');
      $pdf->SetXY(132.5,74);
      $pdf->Cell(72.5, 6, $modelo, 0, 0, 'L', 1);
      
      $pdf->SetFillColor(245, 247, 249);
      $pdf->SetXY(112.5,80);
      $pdf->Cell(20, 6, utf8_decode('No. Parte:'), 0, 0, 'L');
      $pdf->SetXY(132.5,80);
      $pdf->Cell(72.5, 6, $parte, 0, 0, 'L', 1);
      
      $pdf->SetFillColor(245, 247, 249);
      $pdf->SetXY(112.5,86);
      $pdf->Cell(20, 6, utf8_decode('Capacidad:'), 0, 0, 'L');
      $pdf->SetXY(132.5,86);
      $pdf->Cell(72.5, 6, $capacidad, 0, 0, 'L', 1);
      
      $pdf->SetFillColor(245, 247, 249);
      $pdf->SetXY(112.5,92);
      $pdf->Cell(20, 6, utf8_decode('Cantidad:'), 0, 0, 'L');
      $pdf->SetXY(132.5,92);
      $pdf->Cell(72.5, 6, $cantidad, 0, 0, 'L', 1);
      
      $pdf->SetFillColor(245, 247, 249);
      $pdf->SetXY(112.5,104);
      $pdf->Cell(20, 6, utf8_decode('Precio Actual:'), 0, 0, 'L');
      $pdf->SetXY(132.5,104);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(72.5, 6, $precio3, 0, 0, 'L', 1);
      
     ////////// OBSERVACIONES ///////////
      $pdf->SetFont('Arial','',8);
      $pdf->SetFillColor(236, 240, 244);
      $pdf->SetXY(10,115);
      $pdf->Cell(20, 6, utf8_decode('Observaciones Especiales:'), 0, 0, 'L');
      $pdf->SetXY(10,121);
      $pdf->Cell(195, 50, '', 0, 0, 'L',1);
      $pdf->SetXY(10,121);
      $pdf->MultiCell(195, 4, $observaciones, 0, 'J', 0);
      
      // Fotos
      $pdf->Image($strFoto1, 10, 175, 60 , 40, 'JPG', '');
      $pdf->Image($strFoto2, 77, 175, 60 , 40, 'JPG', '');
      $pdf->Image($strFoto3, 145, 175, 60 , 40, 'JPG', '');
      
      
      // INICIA ESCRITURA DE PDF 
      $pdf->AddPage();
      $pdf->SetAutoPageBreak(false,2);
      
      $pdf->SetMargins(5,5);
      
      //////////// REPORTE DE FALLAS ////////////////
      $pdf->SetFont('Arial','B',12);
      $pdf->SetXY(10,20);
      $pdf->MultiCell(195,6,utf8_decode('Historial de Fallas'),'B','C');
      
      $pdf->SetY(30);
      // encabezados
      $pdf->SetWidths(array(7, 90, 30, 30, 20, 30));  // AQUÍ LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;
      $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C'));  // AQUÍ LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;
      
      $pdf->SetFont('Arial','B',6);  // AQUI LE ASIGNO EL TIPO DE LETRA Y TAMAÑO
      $pdf->SetFillColor(236, 240, 244);
      $pdf->SetDrawColor(145, 145, 145);
      $pdf->SetTextColor(74, 74, 74); // gris
   
      for($i=0;$i<1;$i++){  // ESTE ES EL ENCABEZADO DE LA TABLA, 
         $pdf->Row(array('No.', 'Falla Reportada', 'Fecha de la Falla','Fecha de Registro',utf8_decode('Situación'),utf8_decode('Fecha de Solución')));
      }
   
      ////////////////////////////////////// CUERPO ///////////////////////////////////////////
      $pdf->SetWidths(array(7, 90, 30, 30, 20, 30));  // AQUÍ LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;
      $pdf->SetAligns(array('C', 'L', 'C', 'C', 'C', 'C'));  // AQUÍ LE DOY ANDHO A CADA UNA DE LAS COLUMNAS;
      
      $ClsFal = new ClsFalla();
      $result = $ClsFal->get_falla('',$codigo);
      
      $i=1;
      if(is_array($result)){
         foreach($result as $row){
            //nombre
            $nombre = utf8_decode($row["act_nombre"]);
            //falla
            $falla = utf8_decode($row["fall_falla"]);
            //fecha
            $fecha_falla = cambia_fechaHora($row["fall_fecha_falla"]);
            //fecha registro
            $fecha_registro = cambia_fechaHora($row["fall_fecha_registro"]);
            //situacion
            $sitfalla = trim($row["fall_situacion"]);
            $situacion = ($sitfalla == 1)?'Reportado':'Solucionado';
            //fecha solucion
            $fecha_solucion = cambia_fechaHora($row["fall_fecha_registro"]);
            $fecha_solucion = ($sitfalla == 2)?$fecha_solucion:'-';
            //---
            $pdf->SetFont('Arial','',6);   // ASIGNO EL TIPO Y TAMAÑO DE LA LETRA
            $pdf->SetFillColor(255,255,255);	// AQUI LE DOY EL COLOR DE FONDO DE LAS CELDAS
            $pdf->SetTextColor(0);  // LE ASIGNO EL COLOR AL TEXTO
            $no = $i.".";
            $pdf->Row(array($no,$falla,$fecha_falla,$fecha_registro,$situacion,$fecha_solucion)); // AGREGO LOS DATOS A LA FILA, VIENE REPERESENTADO POR UN ARRAY 
            $i++;															// IGUAL QUE EL ENCABEZADO, Y ESTO SE HACE POR CADA REGISTRO
         }
         $i--;
         ////////////////////////////////////// PIE DE REPORTE ///////////////////////////////////////////
         $pdf->SetFont('Arial','B',6);  	// ASIGNO EL TIPO Y TAMAÑO DE LA LETRA
         $pdf->SetFillColor(236, 240, 244);
         $pdf->Cell(207,5,$i.' Registro(s).',1,'','R',true);	// AQUI ASIGNO UNA CELDA DEL ANCHO DE LA TABLA PARA PONER LA CANTIDAD DE REGISTROS
            
      }else{
         $pdf->SetFont('Arial','',6);  	// ASIGNO EL TIPO Y TAMAO DE LA LETRA
         $pdf->SetFillColor(255, 255, 255);
         $pdf->Cell(207,5,'No se Reportan Datos.',1,'','C',true);	// AQUI ASIGNO UNA CELDA DEL ANCHO DE LA TABLA PARA PONER LA CANTIDAD DE REGISTROS
         
         $y=$pdf->GetY();
         $y+=5;
         // Put the position to the right of the cell
         $pdf->SetXY(5,$y);
         //footer
         $pdf->SetFont('Arial','B',6);  	// ASIGNO EL TIPO Y TAMAÑO DE LA LETRA
         $pdf->SetFillColor(236, 240, 244);
         $pdf->Cell(207,5,'0 Registro(s).',1,'','R',true);	// AQUI ASIGNO UNA CELDA DEL ANCHO DE LA TABLA PARA PONER LA CANTIDAD DE REGISTROS
      } 
   
   }
}
   
//Salida de PDF, en esta parte se puede definir la salida, si es a pantalla o forzar la descarga
$pdf->Output("Fichas Activos.pdf","I");
  
  
?>