{literal}
function validate_fpacienteInterven_completo (form) {
  var alertstr = '';
  var invalid  = 0;

  // shistoria: standard text, hidden, password, or textarea box
  var shistoria = form.elements['shistoria'].value;
  if (shistoria == null || shistoria == "" ) {
    alertstr += '- Indique un valor válido para el campo "Número de Historia"\n';
    invalid++;
  }

  // snombre: standard text, hidden, password, or textarea box
  var snombre = form.elements['snombre'].value;
  if (snombre == null || snombre == "" ) {
    alertstr += '- Indique un valor válido para el campo "Nombres Paciente"\n';
    invalid++;
  }

  // sapellido: standard text, hidden, password, or textarea box
  var sapellido = form.elements['sapellido'].value;
  if (sapellido == null || sapellido == "" ) {
    alertstr += '- Indique un valor válido para el campo "Apellidos Paciente"\n';
    invalid++;
  }

// numrecibo: standard text, hidden, password, or textarea box
  var num_recibo = form.elements['num_recibo'].value;
  if (num_recibo == null || num_recibo == "" ) {
    alertstr += '- Indique un valor válido para el campo "Número de Recibo"\n';
    invalid++;
  }
  else{
    if (!num_recibo.match(/^\s*[0-9]+$/)){
      alertstr += '- Indique un valor válido para el campo "Número de Recibo"\n';
      invalid++;
    }
  }

// fecha: standard text, hidden, password, or textarea box
  var fecha = form.elements['fecha'].value;
  if (fecha == null || fecha == "" ) {
    alertstr += '- Indique un valor válido para el campo "Fecha de la Intervención"\n';
    invalid++;
  }
  else{
    var res = fecha.match(/^([0][1-9]|[12][0-9]|[3][0-1])(\/)([0][1-9]|[1][0-2])\2(\d{4})$/);
    if (!fecha.match(/^([0][1-9]|[12][0-9]|[3][0-1])(\/)([0][1-9]|[1][0-2])\2(\d{4})$/)){
      alertstr += '- Indique un valor válido para el campo "Fecha de la Intervención"\n';
      invalid++;
    }
  }

  // id_doctor_cirujano: standard text, hidden, password, or textarea box
  var id_doctor_cirujano = form.elements['id_doctor_cirujano'].value;
  if (id_doctor_cirujano == null || id_doctor_cirujano == "" ) {
    alertstr += '- Indique un valor válido para el campo "Cirujano"\n';
    invalid++;
  }

// // id_doctor_anestesia: select list, always assume it's multiple to get all values
  var id_doctor_anestesia = 0;
  var selected_id_doctor_anestesia = 0;
  for (var loop = 0; loop < form.elements['id_doctor_anestesia'].options.length; loop++) {
    if (form.elements['id_doctor_anestesia'].options[loop].selected) {
      id_doctor_anestesia = form.elements['id_doctor_anestesia'].options[loop].value;
      selected_id_doctor_anestesia++;
      if (id_doctor_anestesia == 0 || id_doctor_anestesia === 0) {
        alertstr += '- Seleccione una opción para el campo "Anestesiólogo"\n';
        invalid++;
      }
    } // if
  } // for id_doctor_anestesia

// monto_total: standard text, hidden, password, or textarea box
  var monto_total = form.elements['monto_total'].value;
  if (monto_total == null || monto_total == "" ) {
    alertstr += '- Indique un valor válido para el campo "Monto Total"\n';
    invalid++;
  }

  if (monto_total == 0 || monto_total == "0,00" ) {
    alertstr += '- Indique un valor válido para el campo "Monto Total"\n';
    invalid++;
  }

    // id_doctor_anestesia: standard text, hidden, password, or textarea box
  var id_intervencion = form.elements['id_intervencion'].value;
  if (id_intervencion == null || id_intervencion == "" ) {
    alertstr += '- Indique un valor válido para el campo "Tipo de Intervención"\n';
    invalid++;
  }

  if (invalid > 0 || alertstr != '') {
    if (! invalid) invalid = 'Los Siguiemtes';   // catch for programmer error
    alert(''+invalid+' error(es) fueron encontrados al enviar la información:'+'\n\n'
    +alertstr+'\n'+'- Por favor corrija los campos y trate de nuevo');
    return false;
  }
  return true;  // all checked ok
}
{/literal}