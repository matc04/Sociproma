<?php

  session_start();

  require('Session.php');

  $miSession = new Session;
  
  $miSession->delete_session();

  exit;

?>