<?php

class ParamConf {

  var $localhost;

  var $template_dir;

  var $cache_dir;

  var $compile_dir;

  var $libSmarty;

  var $classPdf;


/* Método Constructor: Cada vez que creemos una variable de esta clase, se ejecutará esta función */

  function ParamConf(){
    require_once('libs/spyc.php');

    $Param = Spyc::YAMLLoad('config.yaml'); 

    $this->localhost = $Param['controller']['localhost'];
    $this->dirupload = $Param['dir_upload'];
    $this->template_dir = $Param['smarty']['template_dir'];
    $this->cache_dir = $Param['smarty']['cache_dir'];
    $this->compile_dir = $Param['smarty']['compile_dir'];
    $this->libSmarty = $Param['smarty']['class'];
    $this->classPdf = $Param['pdf']['class'];

  }

  function getLocalhost() {
    return $this->localhost;
  }

  function getTemplateDir() {
    return $this->template_dir;
  }

  function getCacheDir() {
    return $this->cache_dir;
  }

  function getCompileDir() {
    return $this->compile_dir;
  }

  function getLibSmarty() {
    return $this->libSmarty;
  }

  function getClassPdf() {
    return $this->classPdf;
  }

  function getDirUpload() {
    return $this->dirupload;
  }
}
