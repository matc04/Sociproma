{include file='encabezado.tpl'}
{literal}
<script language="JavaScript" src="js/calendar_eu.js"></script> 
<script type="text/javascript" src="js/prototype/prototype.js"></script>
<script type="text/javascript" src="js/seleccionarItemList.js"></script>
<script language="javascript">

function runMode( accion, Id ){
  forma = document.getElementById('busca_recibo');

  forma.accion.value = accion;
  forma.action = "CtrlBuscarRecibo.php";
  forma.id.value = Id;

  if ( accion == "ver" ){
    forma.accion.value = "actualiza";
    forma.action = "CtrlPacienteIntervencion.php";
  }

  if ( accion == "pagar" ){
    forma.accion.value = "pagar";
    forma.action = "CtrlPacienteIntervencion.php";
  }

  if (accion == "generar" ){
    if (validate_busca_recibo(forma)){ 
      forma.action = "ReporteRecibo.php";
      forma.submit() 
    }
  }

  if (accion == "buscar" ){
    if (validate_busca_recibo(forma)){ forma.submit() };
  }
  else{
    forma.submit();
  }
}

function validate_busca_recibo (form) {
  var alertstr = '';
  var invalid  = 0;

// numreciboini: standard text, hidden, password, or textarea box
  var numreciboini = form.numreciboini.value;
  if (numreciboini){
    if (!numreciboini.match(/^-?\s*[0-9]+$/)){
      alertstr += '- Indique un valor válido para el campo "Número de Recibo Inicial"\n';
      invalid++;
    }
  }

// numrecibofin: standard text, hidden, password, or textarea box
  var numrecibofin = form.numrecibofin.value;
  if (numrecibofin){
    if (!numrecibofin.match(/^-?\s*[0-9]+$/)){
      alertstr += '- Indique un valor válido para el campo "Número de Recibo Final"\n';
      invalid++;
    }
  }

  var fechainicial = form.fechainicial.value;
  if (fechainicial){
    if (!fechainicial.match(/^(0?[1-9]|[1-2][0-9]|3[0-1])\/?(0?[1-9]|1[0-2])\/?[0-9]{4}$/)){
        alertstr += '- Indique un valor válido para el campo "Fecha Inicial"\n';
        invalid++;
    }
  }

  var fechafinal = form.fechafinal.value;
  if (fechafinal){
    if (!fechafinal.match(/^(0?[1-9]|[1-2][0-9]|3[0-1])\/?(0?[1-9]|1[0-2])\/?[0-9]{4}$/)){
        alertstr += '- Indique un valor válido para el campo "Fecha Final"\n';
        invalid++;
    }
  }

  if (invalid > 0 || alertstr != '') {
    if (! invalid) invalid = 'Los Siguiemtes';   // catch for programmer error
    alert(''+invalid+' error(es) fueron encontrados al enviar la información:'+'\n\n'
    +alertstr+'\n'+'- Por favor corrija los campos y trate de nuevo');
    return false;
  }
  return true;  // all checked ok
}

function muestra_difer(id){
  var estatus = document.getElementById('id_estatus');
  if (estatus.value == 2){
    if (document.getElementById){ //se obtiene el id
      var el = document.getElementById(id); //se define la variable "el" igual a nuestro div
      //el.style.display = (el.style.display == 'none') ? 'block' : 'none'; //damos un atributo display:none que oculta el div
      el.style.display = 'block'; //damos un atributo display:none que oculta el div
    }  
  } else {
    if (document.getElementById){ //se obtiene el id
      var el = document.getElementById(id); //se define la variable "el" igual a nuestro div
      el.style.display = 'none'; //damos un atributo display:none que oculta el div
    }
  }
}

window.onload = function(){/*hace que se cargue la función lo que predetermina que div estará oculto hasta llamar a la función nuevamente*/
  muestra_difer('diferencia');/* "contenido_a_mostrar" es el nombre que le dimos al DIV */
}

</script>
{/literal}

<LINK HREF="css/anestesia.css" REL="stylesheet" TYPE="text/css">
<link rel="stylesheet" href="calendar.css"> 
<table width="100%">
<tr>
<td class="tituloforma" align="center">{$titulo}</td>
</tr>
</table>

<FORM METHOD="post" name="busca_recibo" id="busca_recibo" ACTION="CtrlBuscarRecibo.php">
<fieldset style="background-color : #e3e3e3">
<legend>Critério de Búsqueda</legend>
<table width="100%">
  <tr>
    <td nowrap width="25%"><b>Número de Recibo/Presupuesto Inicial:</b></td>
    <td width="25%" align="left"><input type="text" name="numreciboini" id="numreciboini" size="10" value="{$numreciboini}"></td>
    <td width="25%" nowrap><b>Número de Recibo/Presupuesto Final:</b></td>
    <td width="25%" align="left"><input type="text" name="numrecibofin" id="numrecibofin" size="10" value="{$numrecibofin}"></td>
  </tr>
  <tr>
    <td><b>Historia/Paciente:</b></td>
    <td colspan="3" align="left">
      <input type="text" id="paciente" name="paciente" size="50"/><input type="hidden" id="id_paciente" name="id_paciente"/>
    </td>
  </tr>
  <tr>
    <td width="15%"><b>Fecha Intervención Inicial:</b></td>
    <td>
      <INPUT TYPE="text" NAME="fechainicial" id="fechainicial" SIZE="10" value="{$fechainicial}">
{literal}
      <script language="JavaScript"> 
  new tcal ({
    // form name
    'formname': 'busca_recibo',
    // input name
    'controlname': 'fechainicial'
  });
       </script>
{/literal}
    </td>
    <td width="15%"><b>Fecha Intervención Final:</b></td>
    <td>
      <INPUT TYPE="text" NAME="fechafinal" id="fechafinal" SIZE="10" value="{$fechafinal}">
{literal}
      <script language="JavaScript"> 
  new tcal ({
    // form name
    'formname': 'busca_recibo',
    // input name
    'controlname': 'fechafinal'
  });
       </script>
{/literal}
    </td>
  </tr>
  <tr>
    <td><b>Tipo de Intervención:</b></td>
     <td colspan="3" align="left">
      <input type="text" id="intervencion" name="intervencion" size="50"/><input type="hidden" id="id_intervencion" name="id_intervencion"/>
    </td>
  </tr>
  <tr>
    <td><b>Cirujano:</b></td>
    <td colspan="3">
        <input type="text" id="cirujano" name="cirujano" size="50"/><input type="hidden" id="id_doctor_cirujano" name="id_doctor_cirujano"/>
    </td>
  </tr>
  <tr>
    <td><b>Anestesiólogo:</b></td>
    <td colspan="3"><select name="id_doctor_anestesia" id="id_doctor_anestesia">
        {html_options options=$doctorAnes_options selected=$id_doctor_anestesia}
    </td>
  </tr>
  <tr>
    <td><b>Monto Total:</b></td>
    <td colspan="3"><input type="text" id="monto_total" size="17" value="{$monto_total}"></td>
  </tr>
  <tr>
    <td><b>Responsable:</b></td>
    <td colspan="3">
      <input type="text" id="responsable" name="responsable" size="50"/><input type="hidden" id="id_responsable" name="id_responsable"/>
    </td>
  </tr>
   <tr>
    <td width="15%"><b>Fecha Creación Inicio:</b></td>
    <td>
      <INPUT TYPE="text" NAME="fecha_creacionini" id="fecha_creacionini" SIZE="10" value="{$fecha_creacionini}">
{literal}
      <script language="JavaScript"> 
  new tcal ({
    // form name
    'formname': 'busca_recibo',
    // input name
    'controlname': 'fecha_creinicio'
  });
       </script>
{/literal}
    </td>
    <td width="15%"><b>Fecha Creación Fin:</b></td>
    <td>
      <INPUT TYPE="text" NAME="fecha_creacionfin" id="fecha_creacionfin" SIZE="10" value="{$fecha_creacionfin}">
{literal}
      <script language="JavaScript"> 
  new tcal ({
    // form name
    'formname': 'busca_recibo',
    // input name
    'controlname': 'fecha_creacionfin'
  });
       </script>
{/literal}
    </td>
  </tr>
  <tr>
    <td><b>Estatus:</b></td>
    <td colspan="3"><select name="id_estatus" id="id_estatus" onchange="muestra_difer('diferencia')">
        {html_options options=$estatus_options selected=$id_estatus}
    </td>
  </tr>
</table>
<div id="diferencia">
  <table>
    <tr>
      <td><input type="checkbox" name="diferencia" id="diferencia" value="1"><b>Solo con diferencias en los montos?</b></td>
    </tr>
  </table>
</div>
</fieldset>
<br/><br/>
<table width="75%" cellpadding="1" align="center" id="tabla">
    <tr>
      <td valign="center" align="center">
        <input type="reset" id="btnCancelaConsulta" value="Cancelar">
        <input type="button" id="Buscar" onClick=" runMode('buscar');" value="Buscar">
        <a href="#" onclick=" runMode( 'generar' )"><img align="center" src="imagenes/pdf.jpg"></a>
      </td>
    </tr>
</table>
<br/><br/>
<table width="90%" cellpadding="1" align="center" id="tabla">
  <tr class="titulodonforojo">
     <th>Recibo/Presupuesto</th>
     <th>Fecha</th>
     <th>Paciente</th>
     <th>Tipo</th>
     <th>Cirujano</th>
     <th>Anestesiólogo</th>
     <th>Responsable</th>
     <th>Fecha Pago</th>
     <th>Monto</th>
     <th>Monto Pagado</th>
     <th>Diferencia</th>
     <th>Estatus</th>
     <th>Editar</th>
     <th>Pagar</th>
  </tr>
  {if $ArrRecibos}
    {foreach from=$ArrRecibos key=Id item=field}
      <tr class={$field.clase}>
        <td align="center">{$field.num_recibo}</td>
        <td align="center">{$field.fecha}</td>
        <td>{$field.nombre_paciente}</td>
        <td>{$field.desctpopera}</td>
        <td>{$field.nombre_cirujano}</td>
        <td>{$field.nombre_anestesia}</td>
        <td>{$field.nombre_respon}</td>
        <td align="center">{$field.fecha_pago}</td>
        <td align="right">{$field.monto_total}</td>
        <td align="right">{$field.monto_pagado}</td>
        <td align="right">{$field.diferencia}</td>
        <td align="center">{$field.descestatus}</td>
        <td align="center">
          {if $field.id_estatus == 1}
            <a href="#" onclick="runMode( 'ver', {$field.id} );"><img src="imagenes/b_edit.png"></a></td>
          {else}
            &nbsp;
          {/if}
        </td>
        <td align="center">
          {if $field.id_estatus != 2}
            <a href="#" onclick="runMode( 'pagar', {$field.id} );"><img width="25px" height="25px" src="imagenes/carduse_card_payment_5122.png"></a></td>
          {else}
            &nbsp;
          {/if}
        </td>
      </tr>
    {/foreach}
 {/if}
</table>
<input type="hidden" name="accion" id="accion">
<input type="hidden" name="id" id="id">
</FORM>
