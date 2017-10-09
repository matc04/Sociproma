<?php
 //load Smarty library
 //require('/usr/local/lib/php/Smarty/Smarty.class.php');
 //$smarty = new Smarty;
  require('../siscopra/SmartyIni.php');

 $smarty  = new SmartyIni;

 $smarty->template_dir = '/opt/lampp/htdocs/smarty/templates';
 $smarty->config_dir = '/opt/lamppp/htdocs/smarty/config';
 $smarty->cache_dir = '/opt/lampp/smarty/cache';
 $smarty->compile_dir = '/opt/lampp/smarty/templates_c';
 $smarty->assign('nombre','Penguin !!');
 $smarty->assign('titulo','Penguin !!');
 $smarty->display('encabezado.tpl');
?>
