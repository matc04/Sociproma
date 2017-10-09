<?php

  session_start();

  require("MysqlDB.php");
  require("Doctor.php");
  require("Paciente.php");
  require("TipoOperacion.php");
  require("Responsable.php");
  require("Intervencion.php");
  require("PacienteIntervencion.php");
  require("DetallePacienteInterven.php");
  require("Fecha.php");
  require("Estatus.php");
  require("SmartyIni.php");
  require_once('ParamConf.php');

  $miParamConf = new ParamConf;

  $smarty  = new SmartyIni;

  $smarty->assign('usuario_log', $_SESSION['usuario_log']);
  $smarty->assign('nombre_log', $_SESSION['nombre_log'].", ".$_SESSION['apellido_log']);
  $smarty->assign('badministra', $_SESSION['badministra']);
  $smarty->assign('subtitulo', '');
  $smarty->assign('ArrRecibos', '');
  $smarty->assign('error_msg', '');
  $smarty->assign('monto_total', '');

  $smarty->assign('titulo','Consulta de Recibos');
  $smarty->assign('direccion', $miParamConf->getLocalhost());


  if(!$_SESSION['usuario_log']){
    $localhost = "location: ".$miParamConf->getLocalhost()."/iniciosesion.php";
    header($localhost);
  }

  $ConsultaId;


  $miconexion = new DB_mysql ;

  $miDoctor   = new Doctor;

  $miPaciente = new Paciente;

  $miTipoOperacion = new TipoOperacion;

  $miResponsable = new Responsable;

  $miIntervencion = new Intervencion;

  $miPacienteIntervencion = new PacienteIntervencion;

  $miDetallePacienteInterven = new DetallePacienteInterven;

  $miEstatus = new Estatus;

  $miconexion->conectar("", "", "", "");


  $TipoOperacion = $miTipoOperacion->listarTipoOperacion( $miconexion->Conexion_ID );
  $smarty->assign('tpopera_options', $TipoOperacion);

  $Estatus = $miEstatus->listarEstatus( $miconexion->Conexion_ID );
  $smarty->assign('estatus_options', $Estatus);

  $DoctorAnes = $miDoctor->listarDoctores( $miconexion->Conexion_ID, " id_especialidad = 2 " );
  $smarty->assign('doctorAnes_options', $DoctorAnes);

  if (!empty($_POST['accion']) && $_POST['accion'] != "buscarPacientes" ) {
    if ($_POST["accion"] == "buscar"){
      $_SESSION['condicion'] = '';
      buscar( $smarty, $miconexion->Conexion_ID, $miPacienteIntervencion );

      $smarty->assign('numreciboini',        $_POST["numreciboini"] );
      $smarty->assign('numrecibofin',        $_POST["numrecibofin"] );
      $smarty->assign('id_paciente',         $_POST["id_paciente"] );
      $smarty->assign('fechainicial',        $_POST["fechainicial"] );
      $smarty->assign('fechafinal',          $_POST["fechafinal"] );
      $smarty->assign('id_intervencion',     $_POST["id_intervencion"] );
      $smarty->assign('id_doctor_cirujano',  $_POST["id_doctor_cirujano"] );
      $smarty->assign('id_doctor_anestesia', $_POST["id_doctor_anestesia"] );
      $smarty->assign('id_responsable',      $_POST["id_responsable"] );
      $smarty->assign('id_estatus',          $_POST["id_estatus"] );
      $smarty->assign('fecha_creacionini',   $_POST["fecha_creacionini"] );
      $smarty->assign('fecha_creacionfin',   $_POST["fecha_creacionfin"] );
    }
  }
  else{

    if (!empty($_POST["accion"]) && ($_POST["accion"] == "buscarPacientes")  && !empty($_POST["criterio"]) ){
      if ($_POST["accion"] == "buscarPacientes"){
        buscarPacientes( $miconexion->Conexion_ID, $miPaciente );
      }
      elseif ($_POST["accion"] == "buscarIntervenciones"){
        buscarIntervenciones( $miconexion->Conexion_ID, $miIntervencion );
      }
      exit;
    }

    $smarty->assign('numreciboini', (!empty($_SESSION['numreciboini'])) ? $_SESSION['numreciboini'] : '');	
    $smarty->assign('numrecibofin', (!empty($_SESSION['numrecibofin'])) ? $_SESSION['numrecibofin'] : '');
    $smarty->assign('id_paciente', (!empty($_SESSION['id_paciente'])) ? $_SESSION['id_paciente'] : '');
    $smarty->assign('fechainicial', (!empty($_SESSION['id_fechainicial'])) ? $_SESSION['id_fechainicial'] : '');	
    $smarty->assign('fechafinal', (!empty($_SESSION['id_fechafinal'])) ? $_SESSION['id_fechafinal'] : '');
    $smarty->assign('id_intervencion', (!empty($_SESSION['id_intervencion'])) ? $_SESSION['id_intervencion'] : '');
    $smarty->assign('id_doctor_cirujano', (!empty($_SESSION['id_doctor_cirujano'])) ? $_SESSION['id_doctor_cirujano'] : '');
    $smarty->assign('id_doctor_anestesia', (!empty($_SESSION['id_doctor_anestesia'])) ? $_SESSION['id_doctor_anestesia'] : '');
    $smarty->assign('id_responsable', (!empty($_SESSION['id_responsable'])) ? $_SESSION['id_responsable'] : '');
    $smarty->assign('id_estatus', (!empty($_SESSION['id_estatus'])) ? $_SESSION['id_estatus'] : '');
    $smarty->assign('fecha_creacionini', (!empty($_SESSION['fecha_creacionini'])) ? $_SESSION['fecha_creacionini'] : ''); 
    $smarty->assign('fecha_creacionfin', (!empty($_SESSION['fecha_creacionfin'])) ? $_SESSION['fecha_creacionfin'] : '');

    if (empty($_POST["accion"]) && !empty($_SESSION['condicion'])){
      buscar( $smarty, $miconexion->Conexion_ID, $miPacienteIntervencion );
    } 
  }
      
  $smarty->display('BuscarRecibos.tpl');

function buscar( $smarty, $Conexion_ID, $PacienteIntervencion ) {

  $miFecha = new Fecha;

  $Where =  array();

  if ($_POST["numreciboini"]){
    $Where[] = " num_recibo >= ". $_POST["numreciboini"];
  }
  if ($_POST["numrecibofin"]){
    $Where[] = " num_recibo <= ". $_POST["numrecibofin"];
  }
  if ($_POST["id_paciente"]){
    $Where[] = " id_paciente = ". $_POST["id_paciente"];
  }
  if ($_POST["fechainicial"]){
    $Where[] = " fecha_intervencion >= '". $miFecha->formatoDbFecha($_POST["fechainicial"])."'";
  }
  if ($_POST["fechafinal"]){
    $Where[] = " fecha_intervencion <= '". $miFecha->formatoDbFecha($_POST["fechafinal"])."'";
  }
  if ($_POST["id_intervencion"]){
    $Where[] = " id_intervencion = ". $_POST["id_intervencion"];
  }
  if ($_POST["id_doctor_cirujano"]){
    $Where[] = " id_doctor_cirujano = ". $_POST["id_doctor_cirujano"];
  }
  if ($_POST["id_doctor_anestesia"]){
    $Where[] = " id_doctor_anestesia = ". $_POST["id_doctor_anestesia"];
  }
  if ($_POST["id_responsable"]){
    $Where[] = " id_responsable = ". $_POST["id_responsable"];
  }
  if ($_POST["id_estatus"]){
    $Where[] = " id_estatus = ". $_POST["id_estatus"];
  }
  if (!empty($_POST["diferencia"])){
    $Where[] = " (monto_total - monto_pagado) != 0";
  }
  if (!empty($_POST["fecha_creacionini"])){
    $Where[] = " fecha_creacion >= " . $_POST["fecha_creacionini"];
  }
  if (!empty($_POST["fecha_creacionfin"])){
    $Where[] = " fecha_creacion <= " . $_POST["fecha_creacionfin"];
  }

  $ConsultaID = $PacienteIntervencion->consulta($Conexion_ID, join(" AND ", $Where));
// mostrarmos los registros
  
  $Recibos = array(); 
  $clase = "fondoetiqueta";
  while ($row = mysql_fetch_object($ConsultaID)) {

    $clase  = ( $clase == "fondoetiqueta" ) ? '' : "fondoetiqueta";
    $Datos["id"]               = $row->id;
    $Datos['num_recibo']       = $row->num_recibo;
    $Datos['nombre_paciente']  = "<b>".$row->historia."</b>/".strtoupper(utf8_encode($row->apellidopac.", ".$row->nombrepac));
    $Datos['fecha']            = $miFecha->formatoFecha($row->fecha_intervencion);
    $Datos['desctpopera']      = $row->desctpopera;
    $Datos['nombre_cirujano']  = strtoupper($row->apellidociru.", ".$row->nombreciru);
    $Datos['nombre_anestesia'] = strtoupper($row->apellidoanes.", ".$row->nombreanes);
    $Datos['monto_total']      = number_format($row->monto_total, 2, ",", ".");
    $Datos['nombre_respon']    = strtoupper($row->descrespon);
    $Datos['fecha_pago']       = ($row->id_estatus == 1) ? "&nbsp;" : $miFecha->formatoFecha($row->fecha_pago);
    $Datos['monto_pagado']     = ($row->id_estatus == 1) ? "&nbsp;" : number_format($row->monto_pagado, 2, ",", ".");
    $Datos['descestatus']      = strtoupper($row->descestatus);
    $Datos['id_estatus']       = strtoupper($row->id_estatus);
    $Datos['diferencia']       = (!empty($row->monto_pagado)) ? number_format(($row->monto_total - $row->monto_pagado),2, ",", ".") : 0;
    $Datos['clase']            = $clase;

    $Recibos[$row->id] = $Datos;
  }

  if ($Recibos){
    $smarty->assign('ArrRecibos', $Recibos);
  }
  else{
    $smarty->assign('error_msg', 'No hay información para el criterio de búsqueda indicado');
  }

}

?>
