<?php

class Session {

/* Método Constructor: Cada vez que creemos una variable de esta clase, se ejecutará esta función */

  function Session() {}

  function delete_session() {
    unset($_SESSION['condicion']);
    unset($_SESSION['numreciboini']);
    unset($_SESSION['numrecibofinal']);
    unset($_SESSION['id_paciente']);
    unset($_SESSION['fechainicial']);
    unset($_SESSION['fechafinal']);
    unset($_SESSION['id_tpoperacion']);
    unset($_SESSION['id_doctor_cirujano']);
    unset($_SESSION['id_doctor_anestesia']);
    unset($_SESSION['id_responsable']);
    unset($_SESSION['id_estatus']);
  }
}
