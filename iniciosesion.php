<?php

  require("MysqlDB.php");
  require("Usuario.php");
  require('SmartyIni.php');
 // require_once('ParamConf.php');

  $miParamConf = new ParamConf;

  $smarty  = new SmartyIni;

  $ConsultaId;

  $miconexion = new DB_mysql ;

  $miUsuario  = new Usuario;

  $miconexion->conectar("", "", "", "");

  $smarty->assign('direccion', $miParamConf->getLocalhost());
  $smarty->assign('error_msg', "");
  $smarty->assign('titulo', "SOCIPROMA");
  $smarty->assign('subtitulo', " ");
  $usuario_log  = null;
  $nombre_log   = null;
  $apellido_log = null;
  $badministra  = null;

  if (!empty($_POST['susuario']) ) {
#Se Busca la información relacionada con el usuario
    $Where = " susuario = '" . $_POST["susuario"]."' AND bactivo = 1";
    $ConsultaId = $miUsuario->consulta($miconexion->Conexion_ID, $Where);

    if ($ConsultaId){
      $row = mysql_fetch_object($ConsultaId);
      if ($row &&  (sha1($_POST['scontrasena']) == $row->scontrasena)){
        //print $row->susuario;
	session_start();
	$usuario_log  = $_POST['susuario'];
	$nombre_log   = $row->snombre;
	$apellido_log = $row->sapellido;
	$badministra  = ($row->badministrador == 1) ? 1 : 0;
        $_SESSION['usuario_log']  = $usuario_log;
        $_SESSION['nombre_log']   = $nombre_log;
        $_SESSION['apellido_log'] = $apellido_log;
        $_SESSION['badministra']  = $badministra;

        $localhost = "location: ".$miParamConf->getLocalhost()."/Home.php";

        header($localhost) ;
      }
      else{
        $smarty->assign('error_msg','Las credenciales indicadas no corresponden con ningún usuario registrado');
      }
    }
    else{
      $smarty->assign('error_msg','Las credenciales indicadas no corresponden con ningún usuario registrado');
    }
  }

  $smarty->assign('usuario_log', $usuario_log);
  $smarty->assign('nombre_log', $nombre_log);
  $smarty->assign('apellido_log', $apellido_log);
  $smarty->assign('badministra', $badministra);

  $smarty->display('iniciosesion.tpl');

?>
