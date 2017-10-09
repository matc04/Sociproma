{include file='encabezado.tpl'}
{literal}
<script language="JavaScript" src="js/calendar_eu.js"></script> 
<script type="text/javascript" src="js/prototype/prototype.js"></script>
<script type="text/javascript" src="js/seleccionarItemList.js"></script>
<script language="javascript">

function runMode( accion, Id ){
  forma = document.getElementById('fpagar_recibo');

  forma.accion.value = accion;
  forma.action = "CtrlPagarRecibo.php";
  forma.id.value = Id;

  if (validate_fpagar_recibo(forma)){ forma.submit() };
}

//Función cargarComboPaciente: se encarga de cargar el contenido de las listas Paciente depediendo del criterio de busqueda en el campo criterio
function cargarCombo( listaLlenar ){
  var forma = document.getElementById(fpagar_recibo);
  var url = '/CtrlPagarRecibo.php';
  campo = eval('forma.'+listaLlenar);
  var funcion = eval('llenar_id_paciente');
  var accion = 'buscarPacientes';
  valorCriterio = forma.criterio.value;
  if (valorCriterio) {
    var param = "criterio="+valorCriterio+"&accion="+accion;
    var myAjax = new Ajax.Request( url, { method: 'post', parameters: param , onComplete: funcion } );
  }
  else {
    vaciarLista(campo);
  }
}

//Función llenar_id_paciente: Método encargado de hacer el llamado al método llenarListaDependiente, indicando que la lista a llenar es la de los Pacientes.
function llenar_id_paciente(originalRequest) {
  var respuesta = originalRequest.responseText;
  var forma = document.fpagar_recibo;
  vaciarLista(forma.id_paciente);
  if ( respuesta != 'undef' ){
    var primerarreglo = respuesta.split("|");
    for(i=0;i<primerarreglo.length;i++){
      var elemento  = primerarreglo[i].split(":");
      agregarOpcionLista(forma.id_paciente,new Option(elemento[1],elemento[0]));
    }
  }
}

function validate_fpagar_recibo (form) {
  var alertstr = '';
  var invalid  = 0;
  var ValorNum = 1;
  var ValorFec = 1;

// numrecibo: standard text, hidden, password, or textarea box
  if ( document.getElementById('num_recibo') ){
    var num_recibo = form.elements['num_recibo'].value;
    if (num_recibo == null || num_recibo == "" ) {
      ValorNum = 0;
      invalid++;
    }
  }

// id_paciente: select list, always assume it's multiple to get all values
  if ( document.getElementById('id_paciente') ){
    var id_paciente = 0;
    var selected_id_paciente = 0;
    for (var loop = 0; loop < form.elements['id_paciente'].options.length; loop++) {
      if (form.elements['id_paciente'].options[loop].selected) {
        id_paciente = form.elements['id_paciente'].options[loop].value;
        if (id_paciente != 0 ) { selected_id_paciente++};
        if (id_paciente == 0 || id_paciente === 0) {
          invalid++;
        }
      } // if
    } // for id_pacient
  }

// fecha: standard text, hidden, password, or textarea box
  if ( document.getElementById('fecha') ){
    var fecha = form.elements['fecha'].value;
    if (fecha) {
      if (!fecha.match(/^(0?[1-9]|[1-2][0-9]|3[0-1])\/?(0?[1-9]|1[0-2])\/?[0-9]{4}$/)){
        alertstr += '- Indique un valor válido para el campo "Fecha"\n';
        invalid++;
      }
    }
    else{
      if (fecha == null || fecha == "" ) {
        ValorFec = 0;
        invalid++;
      }
    }
  }

  if ( document.getElementById('monto_pagado') ){
    var monto = form.elements['monto_pagado'].value;
    if (monto == null || monto == "" || monto == 0 ) {
      alertstr += '- Indique un valor válido para el campo "Monto Pago"\n';
      invalid++;
    }
  }

  if ( document.getElementById('fecha_pago') ){
    var fecha = form.elements['fecha_pago'].value;
    if (fecha == null || fecha == "" ) {
      alertstr += '- Indique un valor válido para el campo "Fecha de Pago"\n';
      invalid++;
    }
    else{
      if (!fecha.match(/^(0?[1-9]|[1-2][0-9]|3[0-1])\/?(0?[1-9]|1[0-2])\/?[0-9]{4}$/)){
        alertstr += '- Indique un valor válido para el campo "Fecha"\n';
        invalid++;
      }
    }
  }

  if ( document.getElementById('num_recibo') &&  document.getElementById('id_paciente') ){
    if (invalid > 0 && ( ValorNum == 0 && ValorFec == 0 && selected_id_paciente == 0) ){
      alert(''+invalid+' error(es) fueron encontrados al enviar la información:'+'\n\n'
      +'Por Favor indique al menos un valor para la busqueda'+'\n'+'- Por favor corrija los campos y trate de nuevo');
      return false;
    }
  }

  if (invalid > 0 && alertstr != '') {
    if (! invalid) invalid = 'Los Siguiemtes';   // catch for programmer error
    alert(''+invalid+' error(es) fueron encontrados al enviar la información:'+'\n\n'
    +alertstr+'\n'+'- Por favor corrija los campos y trate de nuevo');
    return false;
  }
  return true;  // all checked ok
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

<FORM METHOD="post" name="fpagar_recibo" id="fpagar_recibo" ACTION="CtrlPagarRecibo.php">
<fieldset style="background-color : #e3e3e3">
<legend>Datos Pago Recibo</legend>
<table width="100%">
  <tr>
    <td width="15%"><b>Número de Recibo:</b></td>
    {if !$num_recibo}
      <td align="left"><input type="text" name="num_recibo" id="num_recibo" size="10" value="{$num_recibo}"><b>*</b></td>
    {else}
      <td>{$num_recibo}</td>
    {/if}
  </tr>
  <tr>
    <td width="15%"><b>Paciente:</b></td>
    {if !$num_recibo}
      <td align="left"><input type="text" name="criterio" id="criterio" size="15" onKeyup="cargarCombo('id_paciente');">&nbsp;
        <select name="id_paciente" id="id_paciente">
          {html_options options=$pacien_options selected=$id_apciente}
        </select><b>*</b>
    {else}
      <td>{$nombre_paciente}</td>
    {/if}
    </td>
  </tr>
  <tr>
    <td width="15%"><b>Fecha de la Intervención:</b></td>
    {if !$num_recibo}
      <td>
        <INPUT TYPE="text" NAME="fecha" id="fecha" SIZE="10" value="{$fecha}">
{literal}
        <script language="JavaScript"> 
	  new tcal ({
		  // form name
		  'formname': 'fpagar_recibo',
		  // input name
		  'controlname': 'fecha'
	  });
         </script>
{/literal}
         <b>*</b>
    {else}
      <td>{$fecha}</td>
    {/if}
    </td>
  </tr>
  {if $num_recibo}
    <tr>
      <td><b>Cirujano:</b></td>
      <td>{$nombre_cirujano}</td>
    </tr>
    <tr>
      <td><b>Anestesiologo:</b></td>
      <td>{$nombre_anestesia}</td>
    </tr>
    <tr>
      <td><b>Monto Total:</b></td>
      <td>{$monto_total}</td>
    </tr>
    <tr>
      <td><b>Responsable:</b></td>
      <td>{$nombre_responsable}</td>
    </tr>
    <tr>
      <td><b>Monto a Pagar:</b></td>
      <td><input type="text" size="15" name="monto_pagado" id="monto_pagado" value="{$monto_total}"><b>*</b></td>
    </tr>
    <tr>
      <td><b>Fecha de Pago:</b>
      <td>
        <INPUT TYPE="text" NAME="fecha_pago" id="fecha_pago" SIZE="10" value="{$fecha_pago}">
{literal}
      <script language="JavaScript"> 
	new tcal ({
		// form name
		'formname': 'fpagar_recibo',
		// input name
		'controlname': 'fecha_pago'
	});
       </script>
{/literal}
      <b>*</b>
      </td>
    </tr>
  {/if}
  <tr>
    <td colspan="2" align="left" ><font color="#0F305A" size="0" > (*) Estos campos son obligatorios</font></td>
  </tr>
</table>
</fieldset>
<br/><br/>
<table width="75%" cellpadding="1" align="center" id="tabla">
  <tr>
    <td align="center">
       <input type="reset" id="btnCancelar" value="Cancelar">
       {if !$id}
         <input type="button" id="btnBuscar" onClick=" runMode('buscar', '');" value="Buscar">
       {else}
         <input type="button" id="btnEnviar" onClick=" runMode('enviar', '{$id}');" value="Enviar">
       {/if}
    </td>
  </tr>
</table>
<input type="hidden" name="accion" id="accion">
<input type="hidden" name="id" id="id">
</FORM>
