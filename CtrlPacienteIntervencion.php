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
  require('SmartyIni.php');
  require_once('ParamConf.php');

  $miParamConf = new ParamConf;

  $smarty  = new SmartyIni;

  $smarty->assign('usuario_log', $_SESSION['usuario_log']);
  $smarty->assign('nombre_log', $_SESSION['nombre_log'].", ".$_SESSION['apellido_log']);
  $smarty->assign('badministra', $_SESSION['badministra']);

  $smarty->assign('titulo','Intervención por Paciente');
  $smarty->assign('direccion', $miParamConf->getLocalhost());

  $smarty->assign('monto_total','');
  $smarty->assign('num_recibo','');
  $smarty->assign('fecha','');
  $smarty->assign('monto_sap', 0);
  $smarty->assign('monto_preva', 0);
  $smarty->assign('monto_anestesia', 0);
  $smarty->assign('sobservacion','');
  $smarty->assign('id_intervencion','');
  $smarty->assign('id_paciente', '');
  $smarty->assign('nombre_paciente','');

  if (!empty($_GET['id_paciente']) && !empty($_GET['nombre_paciente'])){
    $smarty->assign('id_paciente', $_GET['id_paciente']);
    $smarty->assign('nombre_paciente',$_GET['nombre_paciente']);
  }
  //$smarty->assign('id','');

  if(!$_SESSION['usuario_log']){
    $direcc = "location: ".$miParamConf->getLocalhost()."/iniciosesion.php";
    header($direcc);
  }

  $ConsultaId;

  $miconexion = new DB_mysql ;

  $miFecha = new Fecha ;

  $miDoctor   = new Doctor;

  $miPaciente = new Paciente;

  $miTipoOperacion = new TipoOperacion;

  $miResponsable = new Responsable;

  $miIntervencion = new Intervencion;

  $miPacienteIntervencion = new PacienteIntervencion;

  $miDetallePacienteInterven = new DetallePacienteInterven;

  $miconexion->conectar("", "", "", "");

  // $Paciente = $miPaciente->listarPaciente( $miconexion->Conexion_ID );
  // $smarty->assign('pacien_options', $Paciente);

  $TipoOperacion = $miTipoOperacion->listarTipoOperacion( $miconexion->Conexion_ID );
  $smarty->assign('tpopera_options', $TipoOperacion);

  // $DoctorCiru = $miDoctor->listarDoctores( $miconexion->Conexion_ID, " id_especialidad in ( 1, 100 ) " );
  // $smarty->assign('doctorCiru_options', $DoctorCiru);

  $DoctorAnes = $miDoctor->listarDoctores( $miconexion->Conexion_ID, " id_especialidad = 2 " );
  $smarty->assign('doctorAnes_options', $DoctorAnes);

  // $Respon = $miResponsable->listarResponsable( $miconexion->Conexion_ID );
  // $smarty->assign('respon_options', $Respon);

  // $Intervencion = $miIntervencion->listarIntervencion( $miconexion->Conexion_ID );
  // $smarty->assign('interven_options', $Intervencion);

  $smarty->assign('subtitulo', '');
  $smarty->assign('error_msg', '');
  $smarty->assign('ArrDetalle', '');
  $smarty->assign('field', '');
  $smarty->assign('clase', '');
  $smarty->assign('bvolver', '');
  $smarty->assign('bnumFila', '');
  $smarty->assign('bpagar', '');
  $smarty->assign('id', '');

  if (!empty($_GET['bcompleto']) || !empty($_POST['bcompleto'])){
    $smarty->assign('bcompleto', (!empty($_GET['bcompleto'])) ? $_GET['bcompleto'] : $_POST['bcompleto']);  

    $smarty->assign('shistoria', '');
    $smarty->assign('sapellido', ''); 
    $smarty->assign('snombre', ''); 
    $smarty->assign('edad', ''); 
  }
  else {
    $smarty->assign('bcompleto', '');    
  }

  if (!empty($_POST['accion']) && $_POST['accion'] != "buscarPacientes" && $_POST['accion'] != "buscarIntervenciones" ) {
    if ($_POST["accion"] == "actualiza" && !empty($_POST["id"]) ||
        $_POST["accion"] == "pagar" && !empty($_POST["id"]) ) {

#Se muestran los datos asociados al id en tratamiento
      $Where = " me.id = " . $_POST["id"];
      $ConsultaId = $miPacienteIntervencion->consulta($miconexion->Conexion_ID, $Where);

      $row = mysql_fetch_object($ConsultaId);
      if ($row){
        $smarty->assign('id', $row->id);
        $smarty->assign('num_recibo', $row->num_recibo);
        $smarty->assign('id_paciente', $row->id_paciente);
        $smarty->assign('fecha', $miFecha->formatoFecha($row->fecha_intervencion));
        $smarty->assign('id_tpoperacion', $row->id_tpoperacion);
        $smarty->assign('id_doctor_cirujano', $row->id_doctor_cirujano);
        $smarty->assign('id_doctor_anestesia', $row->id_doctor_anestesia);
        $smarty->assign('monto_parcial', $row->monto_total - $row->monto_sap);
        $smarty->assign('id_responsable', $row->id_responsable);
        $smarty->assign('sobservacion', $row->sobservacion);
        $smarty->assign('monto_sap', number_format ( $row->monto_sap, 2, ",", "." ));
        $smarty->assign('monto_preva',  number_format ( $row->monto_preva, 2, ",", "." ));
        $smarty->assign('monto_anestesia',  number_format ( $row->monto_anestesia, 2, ",", "." ));
        $smarty->assign('monto_total', number_format ( $row->monto_total, 2, ",", "." ));
        $smarty->assign('id_intervencion', $row->id_intervencion);
        if ($_POST["accion"] == "pagar"){
          $smarty->assign('num_recibo', $row->num_recibo);
          $smarty->assign('nombre_paciente', $row->apellidopac.", ".$row->nombrepac);
          $smarty->assign('nombre_cirujano', $row->apellidociru.", ".$row->nombreciru);
          $smarty->assign('nombre_anestesiologo', $row->apellidoanes.", ".$row->nombreanes);
          $smarty->assign('bpagar', 1);  
        } else {
          $smarty->assign('bpagar', 0);  
        }
      }
    }

#Se hace la inserciòn de los valores de la pantalla

    if ( $_POST["accion"] == "enviar" && empty($_POST["id"])){
      if (!empty($_POST['bcompleto'])){
        if (crear_completo( $miconexion->Conexion_ID, $miPaciente, $miPacienteIntervencion )){
          $smarty->assign('error_msg', 'La creación de los datos se realizó de manera exitosa');
        } else {
          if (!empty($_SESSION['error_msg'])) {
            $smarty->assign('error_msg', $_SESSION['error_msg']);
            unset($_SESSION['error_msg']);
          }
        }
      } else {
        if (crear( $miconexion->Conexion_ID, $miPacienteIntervencion )){
          $smarty->assign('error_msg', 'La creación de los datos se realizó de manera exitosa');
        } else{
          $smarty->assign('error_msg', 'Ha ocurrido un error al momento de la creación de los datos');
        }  
      }
    }
    elseif( $_POST["accion"] == "enviar" && !empty($_POST["id"]) ){
      if (actualiza( $miconexion->Conexion_ID, $miPacienteIntervencion )){
        $smarty->assign('error_msg', 'La acualización de los datos se realizó de manera exitosa');
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de la actualización de los datos');
      }
    }
    elseif( $_POST["accion"] == "elimina" && !empty($_POST["id"]) ){
      if (elimina( $miconexion->Conexion_ID, $miPacienteIntervencion )){
        $smarty->assign('error_msg', 'La Eliminación del registro se realizó de manera exitosa');
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de eliminar el registro');
      }
    }elseif( $_POST["accion"] == "pagar_recibo" && !empty($_POST["id"]) ){
      if (pagar_recibo( $miconexion->Conexion_ID, $miPacienteIntervencion )){
        $smarty->assign('error_msg', 'El recibo ha cambiado su estatus a pagado de manera exitosa');
        $direcc = "location: ".$miParamConf->getLocalhost()."/CtrlBuscarRecibo.php";
        header($direcc);
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de pagar el registro');
      }
    }
    elseif( $_POST["accion"] == "buscar" ){
      buscar( $smarty, $miconexion->Conexion_ID, $miPacienteIntervencion );
    }

    $smarty->display('Paciente_Intervencion.tpl');
  }
  else{
    $smarty->assign('num_recibo', '');
    $smarty->assign('fecha', '');
    $smarty->assign('id_tpoperacion', '');
    $smarty->assign('id_doctor_cirujano', '');
    $smarty->assign('id_doctor_anestesia', '');
    $smarty->assign('monto_parcial', '');
    $smarty->assign('id_responsable', '');
    $smarty->assign('sobservacion', '');
    $smarty->assign('monto_sap', 0);
    $smarty->assign('monto_preva', 0);
    $smarty->assign('monto_anestesia', 0);
    $smarty->assign('monto_total', 0);
    $smarty->assign('id_interven', '');
      
    $smarty->display('Paciente_Intervencion.tpl');
  }


/* Realiza la insercion de los datos indicados en la forma */
function crear( $Conexion_ID, $miPacienteIntervencion ){

  $datos = datos_intervencion($_POST['id_paciente']);
                  
  $resultado = $miPacienteIntervencion->create( $Conexion_ID, $datos );

  return $resultado;
}

/* Realiza la insercion de los datos indicados en la forma */
function crear_completo( $Conexion_ID, $miPaciente, $miPacienteIntervencion ){

  $datos = datos_paciente();

  $resultado_paciente = $miPaciente->create($Conexion_ID, $datos);

  if ($resultado_paciente){
    $nuevo_paciente = mysql_insert_id();
    $datos_interven = datos_intervencion($nuevo_paciente);

    $resultado_interven = $miPacienteIntervencion->create($Conexion_ID, $datos_interven);

    return $resultado_interven;
  } else {
    $_SESSION['error_msg'] = 'Ha ocurrido un error al momento de la creación de los datos';
    if (preg_match("/Duplicate/", $miPaciente->error)) {
      $_SESSION['error_msg'] .= "<br>El número de historia ya existe";
    }
  }
  
  return $resultado_paciente;
}


/* Realiza la actualizacón de los datos indicados en la forma */
function actualiza( $Conexion_ID, $miPacienteIntervencion ){

  $Where = " id = " . $_POST["id"];

  $miFecha = new Fecha;

  $datos = datos_intervencion();
  
  $resultado = $miPacienteIntervencion->actualiza( $Conexion_ID, $Where, $datos );

  return $resultado;
}

/* Realiza la actualizacón de los datos indicados en la forma */
function elimina( $Conexion_ID, $miPacienteIntervencion ){

  $Where = " id = " . $_POST["id"];

  $resultado = $miPaienteIntervencion->elimina( $Conexion_ID, $Where );

  return $resultado;
}

/* Realiza la actualizacón de los datos para darpor pagado un recibo */
function pagar_recibo( $Conexion_ID, $miPacienteIntervencion ){

  $Where = " id = " . $_POST["id"];

  $miFecha = new Fecha;

  $datos = array( "fecha_pago"   => $miFecha->formatoDbFecha($_POST["fecha_pago"]),
                  "monto_pagado" => $_POST["monto_pagado"],
                  "id_status"    => 2
                );
  
  $resultado = $miPacienteIntervencion->pagar( $Conexion_ID, $Where, $datos );

  return $resultado;
}

function buscar( $smarty, $Conexion_ID, $PacienteIntervencion ) {

  $Where =  array();
  
  $ConsultaID = $PacienteIntervencion->consulta($Conexion_ID, join(" AND ", $Where));
// mostrarmos los registros
  

  $clase = "fondoetiqueta";
  while ($row = mysql_fetch_assoc($ConsultaID)) {

    $clase  = ( $clase == "fondoetiqueta" ) ? '' : "fondoetiqueta";
    $Datos["id"]                  = $row->id;
    $Datos['id_paciente']         = strtoupper($row->id_paciente);
    $Datos['fecha']               = strtoupper($row->fecha);
    $Datos['id_tpoperacion']      = $row->id_tpoperacion;
    $Datos['id_doctor_cirujano']  = $row->id_doctor_cirujano;
    $Datos['id_doctor_anestesia'] = $row->id_doctor_anestesia;
    $Datos['monto_total']         = $row->monto_total;
    $Datos['id_responsable']      = $row->id_responsable;
    $Datos['id_intervencion']     = $row->id_intervencion;
    $Datos['fecha_pago']          = $row->fecha_pago;
    $Datos['clase']               = $clase;

    $Recibos[$row[0]] = $Datos;
  }

  $smarty->assign('ArrRecibos', $Recibos);
}

/* Realiza la insercion de los datos indicados en la forma */
function datos_paciente(){

  $datos = array( "shistoria" => $_POST["shistoria"],
                  "sapellido" => $_POST["sapellido"],
                  "snombre"   => $_POST["snombre"],
                  "edad"      => $_POST["edad"]);
  

  return $datos;
}

/* Realiza la insercion de los datos indicados en la forma */
function datos_intervencion($id_paciente){

  $miFecha = new Fecha;

  $datos = array( "num_recibo"          => $_POST["num_recibo"],
                  "id_paciente"         => $id_paciente,
                  "fecha"               => $miFecha->formatoDbFecha($_POST["fecha"]),
                  "id_tpoperacion"      => $_POST["id_tpoperacion"],
                  "id_doctor_cirujano"  => $_POST["id_doctor_cirujano"],
                  "id_doctor_anestesia" => $_POST["id_doctor_anestesia"],
                  "monto_total"         => $_POST["monto_total"],
                  "monto_sap"           => $_POST["monto_sap"],
                  "id_responsable"      => $_POST["id_responsable"],
                  "sobservacion"        => $_POST["sobservacion"],
                  "id_intervencion"     => $_POST["id_intervencion"],
                  "monto_preva"         => $_POST["monto_preva"],
                  "monto_anestesia"     => $_POST["monto_anestesia"]);

  return $datos;
}

?>
