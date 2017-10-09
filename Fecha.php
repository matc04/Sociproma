<?php

class Fecha {

function formatoFecha($fecha){ 
  list($anio,$mes,$dia)=explode("-",$fecha); 
  return $dia."/".$mes."/".$anio; 
}  

function formatoDbFecha($fecha){ 
  list($dia,$mes,$anio)=explode("/",$fecha); 
  return $anio."-".$mes."-".$dia; 
}  

}

?>
