{include file="doctype.tpl"}
<html>
<head>
<title>{$titulo}</title>
<!-- hojas de estilo generales -->
{include file="estilosgenerales.tpl"}

<!-- archivos de javascript generales (cargar en todas las páginas) -->
{include file="jsgenerales.tpl"}

<!--
En la plantilla siguiente se incluye el javascript para cargar el menu,
asociado al evento onload de la página. Si se quiere personalizar dicho evento,
debe escribirse la nueva función de javascript requerida y, dentro, invocar a
la función cargarMenu() existente, cambiando la respectiva etiqueta <body>.
-->
</head>
<body>

{include file="encabezado.tpl"}

<div align="center">
<img src="imagenes/f01.jpg" width="300" height="250" alt="{$titulo}" />
</div>

</body>
</html>
