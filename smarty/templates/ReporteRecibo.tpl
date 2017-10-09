{include file='encabezado.tpl'}
{literal}
<script language="JavaScript" src="js/calendar_eu.js"></script> 
<script type="text/javascript" src="js/prototype/prototype.js"></script>
<script type="text/javascript" src="js/seleccionarItemList.js"></script>
<script language="javascript">

//Función cargarComboPaciente: se encarga de cargar el contenido de las listas Paciente depediendo del criterio de busqueda en el campo criterio
function cargarCombo( listaLlenar ){
  var forma = document.reporte;
  var url = '/';
  campo = eval('forma.'+listaLlenar);
  if ( listaLlenar == 'id_paciente' ){
    var funcion = eval('llenar_id_paciente');
	var accion = 'buscarPacientes';
  }
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
  var forma = document.reporte;
  vaciarLista(forma.id_paciente);
    if ( respuesta != 'undef' ){
    var primerarreglo = respuesta.split("|");
    for(i=0;i<primerarreglo.length;i++){
      var elemento  = primerarreglo[i].split(":");
      agregarOpcionLista(forma.id_paciente,new Option(elemento[1],elemento[0]));
    }
  }
}

function runMode( accion, Id ){
  forma = document.getElementById('reporte');

  forma.accion.value = accion;
  forma.action = "CtrlReporteRecibo.php";
  forma.id.value = Id;

  if ( accion == "ver" ){
    forma.accion.value = "actualiza";
    forma.action = "";
  }

  if (accion == "buscar" || accion == "generar" ){
    if ( accion == "generar" ){ forma.bgenera.value = 1; }
    if (validate_reporte(forma)){ forma.submit(); }
  }
  else{
    forma.submit();
  }
}

function validate_reporte (form) {
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

</script>
{/literal}

<LINK HREF="css/anestesia.css" REL="stylesheet" TYPE="text/css">
<link rel="stylesheet" href="calendar.css"> 
<table width="100%">
<tr>
<td class="tituloforma" align="center">{$titulo}</td>
</tr>
</table>

<FORM METHOD="post" name="reporte" id="reporte" ACTION="CtrlReporteRecibo.php">
<fieldset style="background-color : #e3e3e3">
<legend>Critério de Búsqueda</legend>
<table width="100%">
  <tr>
    <td nowrap width="25%"><b>Número de Recibo/Presupuesto Inicial:</b></td>
    <td width="25%" align="left"><input type="text" name="numreciboini" id="num_reciboini" size="10" value="{$numreciboini}"></td>
    <td width="25%" nowrap><b>Número de Recibo/Presupuesto Final:</b></td>
    <td width="25%" align="left"><input type="text" name="numrecibofin" id="num_recibofin" size="10" value="{$numrecibofin}"></td>
  </tr>
  <tr>
    <td><b>Paciente:</b></td>
    <td colspan="3" align="left"><input type="text" name="criterio" id="criterio" size="15" onKeyup="cargarCombo('id_paciente');">&nbsp;
      <select name="id_paciente" id="id_paciente">
        {html_options options=$pacien_options selected=$id_apciente}
      </select>
    </td>
  </tr>
  <tr>
    <td width="15%"><b>Fecha Inicial:</b></td>
    <td>
      <INPUT TYPE="text" NAME="fechainicial" id="fechainicial" SIZE="10" value="{$fechainicial}">
{literal}
      <script language="JavaScript"> 
	new tcal ({
		// form name
		'formname': 'reporte',
		// input name
		'controlname': 'fechainicial'
	});
       </script>
{/literal}
    </td>
    <td width="15%"><b>Fecha Final:</b></td>
    <td>
      <INPUT TYPE="text" NAME="fechafinal" id="fechafinal" SIZE="10" value="{$fechafinal}">
{literal}
      <script language="JavaScript"> 
	new tcal ({
		// form name
		'formname': 'reporte',
		// input name
		'controlname': 'fechafinal'
	});
       </script>
{/literal}
    </td>
  </tr>
  <tr>
    <td><b>Tipo de Operación:</b></td>
     <td colspan="3" align="left">
      <select name="id_tpoperacion" id="id_tpoperacion">
        {html_options options=$tpopera_options selected=$id_tpoperacion}
      </select>
    </td>
  </tr>
  <tr>
    <td><b>Cirujano:</b></td>
    <td colspan="3"><select name="id_doctor_cirujano" id="id_doctor_cirujano">
        {html_options options=$doctorCiru_options selected=$id_doctor_cirujano}
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
    <td colspan="3"><select name="id_responsable" id="id_responsable">
        {html_options options=$respon_options selected=$id_responsable}
    </td>
  </tr>
  <tr>
    <td><b>Estatus:</b></td>
    <td colspan="3"><select name="id_estatus" id="id_estatus">
        {html_options options=$estatus_options selected=$id_estatus}
    </td>
  </tr>
</table>
</fieldset>
<br/><br/>
<table width="75%" cellpadding="1" align="center" id="tabla">
  <tr>
    <td align="center">
      <input type="reset" id="btnCancelar" value="Cancelar">
      <input type="button" id="Buscar" onClick=" runMode('buscar');" value="Buscar">
      <a href="#" onclick=" runMode( 'generar' )"><img src="imagenes/pdf.jpg"></a>
    </td>
  </tr>
</table>
<br/><br/>
<table width="90%" cellpadding="1" align="center" id="tabla">
  <tr>
  </tr>
  <tr class="titulodonforojo">
     <th>Recibo</th>
     <th>Fecha</th>
     <th>Paciente</th>
     <th>Tipo</th>
     <th>Cirujano</th>
     <th>Anestesiólogo</th>
     <th>Monto</th>
     <th>Responsable</th>
     <th>Fecha Pago</th>
     <th>Estatus</th>
  </tr>
  {foreach from=$ArrRecibos key=Id item=field}
    <tr class={$field.clase}>
      <td align="center">{$field.num_recibo}</td>
      <td align="center">{$field.fecha}</td>
      <td>{$field.nombre_paciente}</td>
      <td>{$field.desctpopera}</td>
      <td>{$field.nombre_cirujano}</td>
      <td>{$field.nombre_anestesia}</td>
      <td align="right">{$field.monto_total}</td>
      <td>{$field.nombre_respon}</td>
      <td align="center">{$field.fecha_pago}</td>
      <td align="center">{$field.descestatus}</td>
    </tr>
  {/foreach}
</table>
<input type="hidden" name="accion" id="accion">
<input type="bgenera" name="bgenera" id="bgenera">
<input type="hidden" name="id" id="id">
</FORM>
