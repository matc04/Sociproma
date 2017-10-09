<?php

  session_start();

  require("MysqlDB.php");
  require('SmartyIni.php');
  require('Session.php');
  require('PacienteIntervencion.php');
  require_once('FilePagoLote.php');
  require_once('ParamConf.php');

  // Include Composer autoloader if not already done.
  include 'pdfparser/vendor/autoload.php';
  
// Parse pdf file and build necessary objects.
  $parser = new \Smalot\PdfParser\Parser();

  $sumary = [];

  $miParamConf = new ParamConf;
  $miSession = new Session;
  $smarty  = new SmartyIni;
  $miconexion = new DB_mysql;
  $miPacienteIntervencion = new PacienteIntervencion;
  $myFilePagoLote = new FilePagoLote;
  $actualizados = 0;

  $miconexion->conectar("", "", "", "");

  $smarty->assign('usuario_log', $_SESSION['usuario_log']);
  $smarty->assign('nombre_log', $_SESSION['nombre_log'].", ".$_SESSION['apellido_log']);
  $smarty->assign('badministra', $_SESSION['badministra']);
  $smarty->assign('subtitulo', '');
  $smarty->assign('error_msg', '');
  $smarty->assign('titulo', '');
  $smarty->assign('direccion', $miParamConf->getLocalhost());
  $smarty->assign('fechapago', '');
  $smarty->assign('procesados', 0);
  $smarty->assign('noexiste', 0);
  $smarty->assign('actualizados', 0);

  if(!$_SESSION['usuario_log']){
    $localhost = "location: ".$miParamConf->getLocalhost()."/iniciosesion.php";
    header($localhost);
  }

  if (!empty($_POST['accion']) ) {
    if ($_POST["accion"] == "upload"){
      if (upload_file($miParamConf, $smarty)){
        $target_dir = $miParamConf->getDirUpload()."/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        parser_file($miconexion->Conexion_ID, $target_file, $parser, $miPacienteIntervencion, $myFilePagoLote);
        $resp =  array('status' => 'true', 
                       'procesados' => $sumary['procesados'],
                       'noexisten' => $sumary['noexisten'],  
                       'actualizados' => $sumary['actualizados'] );
        
        echo json_encode($resp);
      }
    }
  } else {
    $smarty->display('Upload.tpl');  
  } 

function upload_file($miParamConf, $smarty){
  $target_dir = $miParamConf->getDirUpload()."/";
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  $uploadOk = 1;
  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

// Check file size
//   if ($_FILES["fileToUpload"]["size"] > 500000) {
//     echo "Sorry, your file is too large.";
//     $uploadOk = 0;
//   }

// Allow certain file formats
   if($imageFileType != "pdf") {
     $error = "solo puede tratar archivos PDF.";
     $uploadOk = 0;
   }

// Check if $uploadOk is set to 0 by an error
   if ($uploadOk == 0) {
     $error = "Disculpe, el archivo no puede ser procesado, ".$error;
     $smarty->assign('error_msg', $error);
     return FALSE;
// if everything is ok, try to upload file
   } else {
     if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
       return TRUE;
     } else {
      $smarty->assign('error_msg', 'Disculpe, se produjo un error con el archivo seleccionado');
      return FALSE;
     }
   }
}

function parser_file($Conexion_ID, $file, $parser, $miPacienteIntervencion, $myFilePagoLote){
  $pdf = $parser->parseFile($file);
  $actualizados = 0;
  $procesados = 0;
  $noexiste = 0;
  $md5File = md5_file($file);
  global $sumary;
  global $actualizados;

  $fechaParts = explode("/", $_POST['fechapago']);
  $data['fecha_pago'] = $fechaParts[2]."-".$fechaParts[1]."-".$fechaParts[0];
  
// Retrieve all pages from the pdf file.
  $pages  = $pdf->getPages();
   
// Loop over each page to extract text.
  $linea = [];
  foreach ($pages as $page) {
    $text = $page->getText();
    $text2 = nl2br($text);
    $linea = explode("\n", $text2);

    $inicio = 0;
    foreach ($linea as $palabra){
      if (strpos($palabra, "Honorario")){
        $inicio = 1;  
      }

      if ($inicio == 1){
        if (strpos($palabra, "FHOI")){
          $posc = explode("-", $palabra);
          $patron = "/^(\d+)\s*/";
          preg_match($patron, $posc[1], $matches);
          $data['historia'] = $matches[0];
        }

        if (strpos($palabra, "Bs.")){
          $patron = "/(\d.*,\d\d)\s*/";
          preg_match($patron, $palabra, $matches);
          $data['monto'] = $matches[0];

          //print "voy a crear con   ";
          if (!pagar_lote($Conexion_ID, $data, $md5File, $miPacienteIntervencion, $myFilePagoLote) ){
            $noexiste++;
          //  print "NO SE PROCESO";
          }
          $procesados++;
        }
      }
    }
  }
  $sumary['procesados'] = $procesados;
  $sumary['noexisten'] = $noexiste;
  $sumary['actualizados'] = $actualizados;
}

/* Ejecuta un Actualizaci�n para dar el recibo como pagado */

function pagar_lote($Conexion_ID, $datos = "", $md5File, $miPacienteIntervencion, $myFilePagoLote){
    global $actualizados;

    #Se busca el recibo por el numero de historia
    $Consulta_ID = $miPacienteIntervencion->get_historia($Conexion_ID, $datos['historia']);
    if ($row = mysql_fetch_row($Consulta_ID)) {
        $idRecibo = $row[0];
        $montoTotal = $row[1];
        $montoPagado = $row[2];
    }

    if ($row){
      //Solo se procesa si el archivo PDF en tratamiento no ha sido procesado
	    
      $Where = " me.id_pacienteintervencion = $idRecibo AND me.smd5file = '$md5File' ";

      $ConsultaIdFile = $myFilePagoLote->consulta($Conexion_ID, $Where);
      if ($rowFile = mysql_fetch_row($ConsultaIdFile)) {
          $idFile = $row[0];
      }

      if (!$rowFile){
          if (strpos($datos['monto'], ",")){
              $monto_pagado = str_replace(".","",$datos['monto']);
              $monto_pagado = str_replace(",",".",$monto_pagado);
          }
          if ($montoTotal == $monto_pagado || ($monto_pagado + $montoPagado) == $montoTotal){
              $datos['estatus'] = 2;
          } else {
              $datos['estatus'] = 3;
          }
          $query = "UPDATE paciente_intervencion set monto_pagado = (monto_pagado + $monto_pagado), 
			   fecha_pago = '". $datos['fecha_pago']."', id_estatus = ".$datos['estatus'].
                  "  WHERE id = $idRecibo ";

	  $response = mysql_query($query, $Conexion_ID);

	  $queryFile = " INSERT INTO filepagolote (id_pacienteintervencion, smd5file) ".
			     " VALUES ($idRecibo, '$md5File') ";

	  $responseFile = mysql_query($queryFile, $Conexion_ID);
	  $actualizados++;
      }
      return TRUE;
    } else {
        return FALSE;
    }
}

?>
