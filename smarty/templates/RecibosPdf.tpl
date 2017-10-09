<page backtop="38mm" backbottom="15mm" pageset="new" >
<LINK HREF="css/anestesia.css" REL="stylesheet" TYPE="text/css">
<page_header>
<TABLE style="border-spacing:0px; border-collapse:0px; border:0px solid; bordercolor:#cc3300; width=750px;" celspacing="0" celpaddign="1" border="0">
<TR>
<TD style="font-family:Arial,Verdana,Bitstream Vera Sans,Sans,Sans-serif;font-weight:bold;color:black; width=300px;" bgcolor="white">
<IMG src="imagenes/soci.png" width="150" height="80" align="left" border="0">
</TD>
</TR>
</table>
<TABLE width="100%" align="center" celspacing="0" celpaddign="1" border="0">
  <tr>
    <td width="100%" align="center"><b>{$titulo}</b></td>
  </tr>
</table>
<TABLE width="100%" align="right" celspacing="0" celpaddign="1" border="0">
  <tr>
    <td width="100%" align="right">{$smarty.now|date_format:"%d/%m/%Y %H:%M:%S"}</td>
  </tr>
</table>
</page_header>
<page_footer>
<table style="width: 100%;">
<tr>
<td align="left" style="text-align: left;width: 70%;"></td>
<td align="right" style="text-align: right;width: 30%;">Página [[page_cu]]/[[page_nb]]</td>
</tr>
</table>
</page_footer>
<table align="center" width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr class="titulodonforojo">
     <th width="7%" style="font-family:Arial,Verdana,Bitstream Vera Sans,Sans,Sans-serif;font-size:10px;text-align:center; font-weight:none; text-decoration:none;height:11px; width:100px;">Recibo</th>
     <th width="10%" style="font-family:Arial,Verdana,Bitstream Vera Sans,Sans,Sans-serif;font-size:10px;text-align:center; font-weight:none; text-decoration:none;height:11px; width:100px;">Fecha</th>
     <th width="13%" style="font-family:Arial,Verdana,Bitstream Vera Sans,Sans,Sans-serif;font-size:10px;text-align:center; font-weight:none; text-decoration:none;height:11px; width:100px;">Paciente</th>
     <th width="5%" style="font-family:Arial,Verdana,Bitstream Vera Sans,Sans,Sans-serif;font-size:10px;text-align:center; font-weight:none; text-decoration:none;height:11px; width:100px;">Tipo</th>
     <th width="10%" style="font-family:Arial,Verdana,Bitstream Vera Sans,Sans,Sans-serif;font-size:10px;text-align:center; font-weight:none; text-decoration:none;height:11px; width:100px;">Cirujano</th>
     <th width="10%" style="font-family:Arial,Verdana,Bitstream Vera Sans,Sans,Sans-serif;font-size:10px;text-align:center; font-weight:none; text-decoration:none;height:11px; width:100px;">Anestesiologo</th>
     <th width="10%" style="font-family:Arial,Verdana,Bitstream Vera Sans,Sans,Sans-serif;font-size:10px;text-align:center; font-weight:none; text-decoration:none;height:11px; width:100px;">Monto</th>
     <th width="15%" style="font-family:Arial,Verdana,Bitstream Vera Sans,Sans,Sans-serif;font-size:10px;text-align:center; font-weight:none; text-decoration:none;height:11px; width:100px;">Responsable</th>
     <th width="10%" style="font-family:Arial,Verdana,Bitstream Vera Sans,Sans,Sans-serif;font-size:10px;text-align:center; font-weight:none; text-decoration:none;height:11px; width:100px;">Fecha Pago</th>
     <th width="10%" style="font-family:Arial,Verdana,Bitstream Vera Sans,Sans,Sans-serif;font-size:10px;text-align:center; font-weight:none; text-decoration:none;height:11px; width:100px;">Estatus</th>
  </tr>
  {foreach from=$ArrRecibos key=Id item=field}
    <tr class={$field.clase}>
     <TD width="7%" style="font-family:Arial,Verdana,Bitstream Vera Sans,Sans,Sans-serif;font-size:10px;text-align:center; font-weight:none; color:black;text-decoration:none;height:11px;">{$field.num_recibo}</td>
     <TD style="font-family:Arial,Verdana,Bitstream Vera Sans,Sans,Sans-serif;font-size:10px;text-align:center; font-weight:none; color:black;text-decoration:none;height:11px; ">{$field.fecha}</td>
     <TD style="font-family:Arial,Verdana,Bitstream Vera Sans,Sans,Sans-serif;font-size:10px;text-align:left; font-weight:none; color:black;text-decoration:none;height:11px; ">{$field.nombre_paciente}</td>
     <TD style="font-family:Arial,Verdana,Bitstream Vera Sans,Sans,Sans-serif;font-size:10px;text-align:center; font-weight:none; color:black;text-decoration:none;height:11px; ">{$field.desctpopera}</td>
     <TD align="left" style="font-family:Arial,Verdana,Bitstream Vera Sans,Sans,Sans-serif;font-size:10px;text-align:left; font-weight:none; color:black;text-decoration:none;height:11px; ">{$field.nombre_cirujano}</td>
     <TD style="font-family:Arial,Verdana,Bitstream Vera Sans,Sans,Sans-serif;font-size:10px;text-align:left; font-weight:none; color:black;text-decoration:none;height:11px; ">{$field.nombre_anestesia}</td>
     <TD style="font-family:Arial,Verdana,Bitstream Vera Sans,Sans,Sans-serif;font-size:10px;text-align:right; font-weight:none; color:black;text-decoration:none;height:11px; ">{$field.monto_total}</td>
     <TD style="font-family:Arial,Verdana,Bitstream Vera Sans,Sans,Sans-serif;font-size:10px;text-align:left; font-weight:none; color:black;text-decoration:none;height:11px; ">{$field.nombre_respon}</td>
     <TD style="font-family:Arial,Verdana,Bitstream Vera Sans,Sans,Sans-serif;font-size:10px;text-align:center; font-weight:none; color:black;text-decoration:none;height:11px; ">{$field.fecha_pago}</td>
     <TD style="font-family:Arial,Verdana,Bitstream Vera Sans,Sans,Sans-serif;font-size:10px;text-align:center; font-weight:none; color:black;text-decoration:none;height:11px;">{$field.descestatus}</td>
    </tr>
  {/foreach}
  <tr>
    <TD colspan="6" align="right" style="font-family:Arial,Verdana,Bitstream Vera Sans,Sans,Sans-serif;font-size:10px;text-align:center; font-weight:none; color:black;text-decoration:none;height:11px; "><b>TOTAL MONTO: </b></td>
    <TD align="left" style="font-family:Arial,Verdana,Bitstream Vera Sans,Sans,Sans-serif;font-size:10px;text-align:center; font-weight:none; color:black;text-decoration:none;height:11px; "><b>{$TotalMonto}</b></td>
  </tr>
</table>
<table align="left" width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <TD align="left" style="font-family:Arial,Verdana,Bitstream Vera Sans,Sans,Sans-serif;font-size:10px;text-align:left; font-weight:none; color:black;text-decoration:none;height:11px; "><b>TOTAL RECIBOS: {$TotalReg}</b></td>
  </tr>
</table>
</page>
