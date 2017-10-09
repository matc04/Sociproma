<?php

  session_start();

  require('SmartyIni.php');
  //require('ParamConf.php');

  $miParamConf = new ParamConf;

  $smarty  = new SmartyIni;

  $smarty->assign('titulo', 'SOCIPROMA');
  $smarty->assign('subtitulo', '');
  $smarty->assign('direccion', $miParamConf->getLocalhost());
  $smarty->assign('error_msg', '');

  if (!$_SESSION['usuario_log']){
    $direcc = "location: "."/iniciosesion.php";
    header($direcc);
  }
  else{
    $smarty->assign('usuario_log', $_SESSION['usuario_log']);
    $smarty->assign('nombre_log', $_SESSION['nombre_log'].", ".$_SESSION["apellido_log"]);
    $smarty->assign('badministra', $_SESSION['badministra']);
  }

  $smarty->display('Home.tpl');

?>
