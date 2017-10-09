
sortitems = 0;
  function move(fbox,tbox) {
    for(var i=0; i<fbox.options.length; i++) {
      if(fbox.options[i].selected && fbox.options[i].value != "") {
        var no = new Option();
        no.value = fbox.options[i].value;
        no.text = fbox.options[i].text;
        tbox.options[tbox.options.length] = no;
        fbox.options[i].value = "";
        fbox.options[i].text = "";
      }
    }
    BumpUp(fbox);
    if (sortitems) SortD(tbox);
  }
  function BumpUp(box) {
    for(var i=0; i<box.options.length; i++) {
      if(box.options[i].value == "") {
        for(var j=i; j<box.options.length-1; j++) {
        box.options[j].value = box.options[j+1].value;
        box.options[j].text = box.options[j+1].text;
      }
      var ln = i;
      break;
      }
    }
    if(ln < box.options.length) {
      box.options.length -= 1;
      BumpUp(box);
    }
  }
  function SortD(box) {
    var temp_opts = new Array();
    var temp = new Object();
    for(var i=0; i<box.options.length; i++) {
      temp_opts[i] = box.options[i];
    }
    for(var x=0; x<temp_opts.length-1; x++) {
      for(var y=(x+1); y<temp_opts.length; y++) {
        if(temp_opts[x].text > temp_opts[y].text) {
          temp = temp_opts[x].text;
          temp_opts[x].text = temp_opts[y].text;
          temp_opts[y].text = temp;
          temp = temp_opts[x].value;
          temp_opts[x].value = temp_opts[y].value;
          temp_opts[y].value = temp;
        }
      }
    }
    for(var i=0; i<box.options.length; i++) {
      box.options[i].value = temp_opts[i].value;
      box.options[i].text = temp_opts[i].text;
    }
  }

  function seleccionarTodaLaLista(lista) {
    for(var i=0; i<lista.options.length; i++) {
      lista.options[i].selected = true;
    }
  }

function agregarOpcionLista(control, opcion) {
  if (document.all) {
    control.add(opcion);
  }
  else {
    control.add(opcion,null);
  }
}

function vaciarLista(control) {
  for (var q = control.options.length; q >= 0; q--){
    control.options[q] = null;
  }
  control.options[0] = new Option("-Seleccione-","");
}

function buscarValorAsociadoEnArreglo(arreglo, columnasFiltro, valoresFiltro, columnaValorBuscado) {
  var columnas = columnasFiltro.split("|");
  var valores = valoresFiltro.split("|");
  var longitud = arreglo.length;

  for (var x = 0; x < longitud; x++) {
    if (comprobarFiltro(arreglo[x],columnasFiltro, valoresFiltro)) {
      return arreglo[x][columnaValorBuscado];
    }
  }
  
  return "No Encontrado";
}

function comprobarFiltro(filaArreglo, columnasFiltro, valoresFiltro) {
  var columnas = columnasFiltro.split("|");
  var valores = valoresFiltro.split("|");

  for (var i=0; i<columnas.length; i++) {
    if (filaArreglo[columnas[i]] != valores[i]) {
      return false;
    }
  }
  
  return true;
}
