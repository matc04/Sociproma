{literal}
function validate_fpacienteInterven_pago(form) {
  var alertstr = '';
  var invalid  = 0;

// fecha: standard text, hidden, password, or textarea box
  var fecha = form.elements['fecha_pago'].value;
  if (fecha == null || fecha == "" ) {
    alertstr += '- Indique un valor válido para el campo "Fecha de Pago"\n';
    invalid++;
  }
  else{
    if (!fecha.match(/^([0][1-9]|[12][0-9]|[3][0-1])(\/)([0][1-9]|[1][0-2])\2(\d{4})$/)){
      alertstr += '- Indique un valor válido para el campo "Fecha de Pago"\n';
      invalid++;
    }
  }

// monto_total: standard text, hidden, password, or textarea box
  var monto_pago = form.elements['monto_pagado'].value;
  if (monto_pago == null || monto_pago == "" ) {
    alertstr += '- Indique un valor válido para el campo "Monto Pagado"\n';
    invalid++;
  }

  if (monto_pago == 0 || monto_pago == "0,00" ) {
    alertstr += '- Indique un valor válido para el campo "Monto Pagado"\n';
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