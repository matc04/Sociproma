<body>

<html>

<?php

  session_start();

  require("MysqlDB.php");
  require("Usuario.php");
  require("Doctor.php");
  require('SmartyIni.php');
  require('Session.php');
  require_once('ParamConf.php');

  $miParamConf = new ParamConf;

  $miSession = new Session;
  
  $miSession->delete_session();

  $smarty   = new SmartyIni;
  $miDoctor = new Doctor;

  if (!$_SESSION['usuario_log']){
    $direcc = "location: ".$miParamConf->getLocalhost()."/iniciosesion.php";
    header($direcc);
  }
  $smarty->assign('usuario_log', $_SESSION['usuario_log']);
  $smarty->assign('nombre_log', $_SESSION['nombre_log'].", ".$_SESSION['apellido_log']);
  $smarty->assign('badministra', $_SESSION['badministra']);

  $ConsultaId;

  $miconexion = new DB_mysql ;

  $miUsuario  = new Usuario;

  $miconexion->conectar("", "", "", "");

  $smarty->assign('titulo','Usuarios de Sitema de Control de Intervenciones');
  $smarty->assign('adm_ids', array(1,0));
  $smarty->assign('adm_names', array( 'Si', 'No' ));
  $smarty->assign('error_msg', "");
  $smarty->assign('direccion', $miParamConf->getLocalhost());
  
  $Doctores = $miDoctor->listarDoctores( $miconexion->Conexion_ID, '' );
  $smarty->assign('doctor_options', $Doctores);


  if (!empty($_POST['accion']) ) {
    if ($_POST["accion"] == "actualiza" && !empty($_POST["id"]) ){

#Se muestran los datos asociados al id en tratamiento
      $Where = " id = " . $_POST["id"];
      $ConsultaId = $miUsuario->consulta($miconexion->Conexion_ID, $Where);

      $row = mysql_fetch_assoc($ConsultaId);
      if ($row){
        $smarty->assign('id',          $row["id"]);
        $smarty->assign('snombre',     $row["snombre"]);
        $smarty->assign('sapellido',   $row["sapellido"]);
        $smarty->assign('susuario',    $row["susuario"]);
        $smarty->assign('scontrasena', $row["scontrasena"]);
        $smarty->assign('sconfirma',   $row["scontrasena"]);
        $smarty->assign('scorreo',     $row["scorreo"]);
        $smarty->assign('id_doctor',   ($row["id_doctor"] && $row["id_doctor"] != 9999) ? $row["id_doctor"] : 9999);

        $smarty->assign('adm_id', $row['badministrador']);
      }
    }

#Se hace la inserciòn de los valores de la pantalla

    if ( $_POST["accion"] == "enviar" && empty($_POST["id"])){
      if (crear( $miconexion->Conexion_ID, $miUsuario ) ){
        $smarty->assign('id',          "");
        $smarty->assign('snombre',     "");
        $smarty->assign('sapellido',   "");
        $smarty->assign('susuario',    "");
        $smarty->assign('scontrasena', '');
        $smarty->assign('sconfirma', '');
        $smarty->assign('scorreo',     "");
        $smarty->assign('id_doctor',   "");
        $smarty->assign('adm_id',   "");
        $smarty->assign('error_msg', 'La creación de los datos se realizó de manera exitosa');
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de la creación de los datos');
      }
    }
    elseif( $_POST["accion"] == "enviar" && !empty($_POST["id"]) ){
      if (actualiza( $miconexion->Conexion_ID, $miUsuario ) ){
        $smarty->assign('id',          "");
        $smarty->assign('snombre',     "");
        $smarty->assign('sapellido',   "");
        $smarty->assign('susuario',    "");
        $smarty->assign('scontrasena', '');
        $smarty->assign('sconfirma', '');
        $smarty->assign('scorreo',     "");
        $smarty->assign('id_doctor',   "");
        $smarty->assign('adm_id',   "");
        $smarty->assign('error_msg', 'La acualización de los datos se realizó de manera exitosa');
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de la actualización de los datos');
      }
    }
    elseif( $_POST["accion"] == "elimina" && !empty($_POST["id"]) ){
      if (elimina( $miconexion->Conexion_ID, $miUsuario ) ){
        $smarty->assign('error_msg', 'La Eliminación del registro se realizó de manera exitosa');
      }
      else{
        $smarty->assign('error_msg', 'Ha ocurrido un error al momento de eliminar el registro');
      }
    }
    elseif( $_POST["accion"] == "buscar" ){
      buscar( $smarty, $miconexion->Conexion_ID, $miUsuario );
    }
  }
  else{
    $smarty->assign('id',          "");
    $smarty->assign('snombre',     "");
    $smarty->assign('sapellido',   "");
    $smarty->assign('susuario',    "");
    $smarty->assign('scontrasena', '');
    $smarty->assign('sconfirma', '');
    $smarty->assign('scorreo',     "");
    $smarty->assign('id_doctor',   "");

    $smarty->assign('adm_id', '');
  }

  $Where = " bactivo = 1 ";
  $ConsultaID = $miUsuario->consulta($miconexion->Conexion_ID, $Where);

  verconsulta( $smarty, $ConsultaID );

  $smarty->assign('subtitulo', "");

  $smarty->display('Usuarios.tpl');



/* Realiza la insercion de los datos indicados en la forma */
function crear( $Conexion_ID, $miUsuario ){

  $id_doc = '9999';
  if ($_POST["id_doctor"] != 0){
	  $id_doc = $_POST["id_doctor"];
  }

  $datos = array( "snombre"        => $_POST["snombre"],
	                "sapellido"      => $_POST["sapellido"],
		              "susuario"       => $_POST["susuario"],
		              "scontrasena"    => $_POST["scontrasena"],
		              "scorreo"        => $_POST["scorreo"] || '',
		              "badministrador" => $_POST["badministrador"][0],
	                "id_doctor"      => $id_doc );

  
  $resultado = $miUsuario->create( $Conexion_ID, $datos );

  return $resultado;
}

/* Realiza la actualizacón de los datos indicados en la forma */
function actualiza( $Conexion_ID, $miUsuario ){

  $Where = " id = " . $_POST["id"];

  $datos = array( "id"             => $_POST["id"],
		              "snombre"        => $_POST["snombre"],
		              "sapellido"      => $_POST["sapellido"],
		              "susuario"       => $_POST["susuario"],
		              "scontrasena"    => $_POST["scontrasena"],
		              "scorreo"        => $_POST["scorreo"],
	                "badministrador" => $_POST["badministrador"],
	                "id_doctor"      => $_POST["id_doctor"] );

  
  $resultado = $miUsuario->actualiza( $Conexion_ID, $Where, $datos );

  return $resultado;
}

/* Realiza la actualizacón de los datos indicados en la forma */
function elimina( $Conexion_ID, $miUsuario ){

  $Where = " id = " . $_POST["id"];

  $resultado = $miUsuario->elimina( $Conexion_ID, $Where );

  return $resultado;
}

/* Muestra los datos de una consulta */

function verconsulta( $smarty, $ConsultaID ) {

// mostrarmos los registros
  

  $clase = "fondoetiqueta";
  while ($row = mysql_fetch_row($ConsultaID)) {

    $clase  = ( $clase == "fondoetiqueta" ) ? '' : "fondoetiqueta";
    $Datos['id']         = $row[0];
    $Datos['snombre']    = $row[1];
    $Datos['sapellido']  = $row[2];
    $Datos['susuario']   = $row[3];
    $Datos['scorreo']    = $row[5];
    $Datos['clase']      = $clase;

    $Usuarios[$row[0]] = $Datos;
  }

  $smarty->assign('ArrUsuarios', $Usuarios);

}

function buscar( $smarty, $Conexion_ID, $Usuario ) {

  $Where =  array();
  if ($_POST["id_doctor"]){
    $Where[] = " id_doctor = ". $_POST["id_doctor"];
  }
  if ($_POST["snombre"]){
    $Where[] = " LOWER(snombre) like '%". strtolower($_POST["snombre"])."%'";
  }
  if ($_POST["sapellido"]){
    $Where[] = " LOWER(sapellido) like '%". strtolower($_POST["sapellido"])."%'";
  }
  if ($_POST["scorreo"]){
    $Where[] = " LOWER(scorreo) like '%". strtolower($_POST["scorreo"])."%'";
  }
  if ($_POST["badministrador"]){
    $Where[] = " badministrador =". $_POST["badministrador"];
  }

  $ConsultaID = $Usuario->consulta($Conexion_ID, join(" AND ", $Where));
// mostrarmos los registros
  

  $clase = "fondoetiqueta";
  while ($row = mysql_fetch_row($ConsultaID)) {

    $clase  = ( $clase == "fondoetiqueta" ) ? '' : "fondoetiqueta";
    $Datos['id']         = $row[0];
    $Datos['snombre']    = $row[1];
    $Datos['sapellido']  = $row[2];
    $Datos['susuario']   = $row[3];
    $Datos['scorreo']    = $row[5];
    $Datos['clase']      = $clase;

    $Usuarios[$row[0]] = $Datos;
  }

  $smarty->assign('ArrUsuarios', $Usuarios);

}

?>

</body>

</html>

