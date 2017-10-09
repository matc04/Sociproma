<?php

// load Smarty library
require_once('ParamConf.php');

$miParamConf = new ParamConf;

require($miParamConf->getLibSmarty());


class SmartyIni extends Smarty {

  public function SmartyIni() {
	
    parent::__construct(); 

    $miParamConf = new ParamConf;


// Class Constructor. 
// These automatically get set with each new instance.

    $this->template_dir = $miParamConf->getTemplateDir();
    $this->cache_dir = $miParamConf->getCacheDir();
    $this->compile_dir = $miParamConf->getCompileDir();

  }
}
?>
