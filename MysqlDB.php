<?php

class DB_mysql {

/* variables de conexión */

  var $BaseDatos;

  var $Servidor;

  var $Usuario;

  var $Clave;

/* identificador de conexión y consulta */

  var $Conexion_ID = 0;

  var $Consulta_ID = 0;

/* número de error y texto error */

  var $Errno = 0;

  var $Error = "";

/* Método Constructor: Cada vez que creemos una variable

de esta clase, se ejecutará esta función */

function DB_mysql($bd = "anestesia", $host = "localhost", $user = "root", $pass = "") {
  $this->BaseDatos = $bd;
  $this->Servidor  = $host;
  $this->Usuario   = $user;
  $this->Clave     = $pass;
}

/*Conexión a la base de datos*/

function conectar($bd, $host, $user, $pass){

  if ($bd != "") $this->BaseDatos = $bd;

  if ($host != "") $this->Servidor = $host;
  
  if ($user != "") $this->Usuario = $user;
  
  if ($pass != "") $this->Clave = $pass;

// Conectamos al servidor

  $this->Conexion_ID = mysql_connect($this->Servidor, $this->Usuario, $this->Clave);

  if (!$this->Conexion_ID) {

    $this->Error = "Ha fallado la conexión.";

    return 0;

  }

//seleccionamos la base de datos

  if (!@mysql_select_db($this->BaseDatos, $this->Conexion_ID)) {

    $this->Error = "Imposible abrir ".$this->BaseDatos ;

    return 0;

  }

/* Si hemos tenido éxito conectando devuelve el identificador de la conexión, sino devuelve 0 */

  return $this->Conexion_ID;

}


} //fin de la Clse DB_mysql

?> 

