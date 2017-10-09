<body>

<html>

<?php

  session_start();

  require("MysqlDB.php");
  require("Responsable.php");
  require('SmartyIni.php');
  require('Session.php');
  require_once('ParamConf.php');

  $miParamConf = new ParamConf;

  $miSession = new Session;
  
  $miSession->delete_session();

  $smarty        = new SmartyIni;
  $miResponsable = new Responsable;

  if (!$_SESSION['usuario_log']){
    $direcc = "location: ".$miParamConf->getLocalhost()."/iniciosesion.php";
    header($direcc);
  }
  $smarty->assign('usuario_log', $_SESSION['usuario_log']);
  $smarty->assign('nombre_log', $_SESSION['nombre_log'].", ".$_SESSION['apellido_log']);
  $smarty->assign('badministra', $_SESSION['badministra']);
  $smarty->assign('referente', '');
  $smarty->assign('subtitulo', '');
  $smarty->assign('error_msg', '');
  $smarty->assign('sdescripcion', '');

  $ConsultaId;

  $miconexion = new DB_mysql ;

  $miconexion->conectar("", "", "", "");

  $smarty->assign('titulo','Responsables de las Intervenciones');
  $smarty->assign('direccion', $miParamConf->getLocalhost());


  if (!empty($_POST['accion']) ) {
    if ($_POST["accion"] == "actualiza" && !empty($_POST["id"]) ){

#Se muestran los datos asociados al id en tratamiento
      $Where = " id = " . $_POST["id"];
      $ConsultaId = $miResponsable->consulta($miconexion->Conexion_ID, $Where);

      $row = mysql_fetch_assoc($ConsultaId);
      if ($row){
        $smarty->assign('id',          $row["id"]);
        $smarty->assign('sdescripcion', $row["sdescripcion"]);
      }
    }

#Se hace la inserciòn de los valores de la pantalla

    if ( $_POST["accion"] == "enviar" && empty($_POST["id"])){
      if (crear( $miconexion->Conexion_ID, $miResponsable ) ){
        $smarty->assign('error_msg', 'La creación de los datos se realizó de manera exitosa');
	      if (preg_match("/CtrlPacienteIntervencion/", $_POST['referente'])) {
          $direcc = "location: ".$miParamConf->getLocalhost()."/CtrlPacienteIntervencion.php";
	        header($direcc);
	      }
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de la creación de los datos');
      }
    }
    elseif( $_POST["accion"] == "enviar" && !empty($_POST["id"]) ){
      if (actualiza( $miconexion->Conexion_ID, $miResponsable ) ){
        $smarty->assign('error_msg', 'La acualización de los datos se realizó de manera exitosa');
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de la actualización de los datos');
      }
    }
    elseif( $_POST["accion"] == "elimina" && !empty($_POST["id"]) ){
      if (elimina( $miconexion->Conexion_ID, $miResponsable ) ){
        $smarty->assign('error_msg', 'La Eliminación del registro se realizó de manera exitosa');
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de eliminar el registro');
      }
    }
    elseif( $_POST["accion"] == "buscar" ){
      if (!buscar( $smarty, $miconexion->Conexion_ID, $miResponsable )){
        $smarty->assign('error_msg', 'No existe información con el criterio indicado');
      }
    }
  }
  else{
    $smarty->assign('id',           "");
    $smarty->assign('sdescripcion', "");
  }

  if (empty($_POST['accion']) || $_POST["accion"] != "buscar" ){
    $Where = " bactivo = 1 ";
    $ConsultaID = $miResponsable->consulta($miconexion->Conexion_ID, $Where);
    verconsulta( $smarty, $ConsultaID );
  }

  if(isset($_SERVER['HTTP_REFERER'])) {
    if (preg_match("/CtrlPacienteIntervencion/", $_SERVER['HTTP_REFERER'])) {
      $smarty->assign('referente', $_SERVER['HTTP_REFERER']);
    }
  }

  $smarty->display('Responsables.tpl');



/* Realiza la insercion de los datos indicados en la forma */
function crear( $Conexion_ID, $miResponsable ){

  $datos = array( "sdescripcion" => $_POST["sdescripcion"] );

  
  $resultado = $miResponsable->create( $Conexion_ID, $datos );

  return $resultado;
}

/* Realiza la actualizacón de los datos indicados en la forma */
function actualiza( $Conexion_ID, $miResponsable ){

  $Where = " id = " . $_POST["id"];

  $datos = array( "id"             => $_POST["id"],
		  "sdescripcion"   => $_POST["sdescripcion"] );

  
  $resultado = $miResponsable->actualiza( $Conexion_ID, $Where, $datos );

  return $resultado;
}

/* Realiza la actualizacón de los datos indicados en la forma */
function elimina( $Conexion_ID, $miResponsable ){

  $Where = " id = " . $_POST["id"];

  $resultado = $miResponsable->elimina( $Conexion_ID, $Where );

  return $resultado;
}

/* Muestra los datos de una consulta */

function verconsulta( $smarty, $ConsultaID ) {

// mostrarmos los registros
  

  $clase = "fondoetiqueta";
  while ($row = mysql_fetch_row($ConsultaID)) {

    $clase  = ( $clase == "fondoetiqueta" ) ? '' : "fondoetiqueta";
    $Datos['id']           = $row[0];
    $Datos['sdescripcion'] = $row[1];
    $Datos['clase']        = $clase;

    $Responsables[$row[0]] = $Datos;
  }

  $smarty->assign('ArrResponsables', $Responsables);

}

function buscar( $smarty, $Conexion_ID, $Responsable ) {

  $Where =  array();
  $Responsables = array();
  $Where[] = " bactivo = 1 ";
  if ($_POST["sdescripcion"]){
    $Where[] = " LOWER(sdescripcion) like '%". strtolower($_POST["sdescripcion"])."%'";
  }

  $ConsultaID = $Responsable->consulta($Conexion_ID, join(" AND ", $Where));
// mostrarmos los registros
  
  $clase = "fondoetiqueta";
  while ($row = mysql_fetch_row($ConsultaID)) {

    $clase  = ( $clase == "fondoetiqueta" ) ? '' : "fondoetiqueta";
    $Datos['id']           = $row[0];
    $Datos['sdescripcion'] = $row[1];
    $Datos['clase']        = $clase;

    $Responsables[$row[0]] = $Datos;
  }

  if ( $Responsables ){
    $smarty->assign('ArrResponsables', $Responsables);
    return 1;
  }
  else{
    return 0;
  }
}

?>

</body>

</html>

