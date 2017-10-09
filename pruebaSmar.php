<?php
//cargamos las librerías de smarty
require('c:\wamp\Smartylibs\Smarty.class.php');

$smarty = new Smarty;

$smarty->template_dir = 'C:\wamp\www\templates';
$smarty->config_dir = ' C:\wamp\www\Smartyconfig';
$smarty->cache_dir = 'C:\wamp\Smartycache';
$smarty->compile_dir = 'C:\wamp\Smartytemplates_c';

//asignamos los valores para personalizar plantilla, para sustituir las variables de la misma
$smarty->assign('nombre','DesarrolloWeb.com');
$smarty->assign('titulo','Título de la página que meto desde PHP para personalizar!');

$smarty->display('home.tpl');
?>
