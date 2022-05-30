$(function () {

  //PARA CARGAR DATOS POR URL
  var queryString = window.location.search;
  var urlParams = new URLSearchParams(queryString);
  var variable1 = urlParams.get('variable1');
  var variable2 = urlParams.get('variable2');
  var variable3 = urlParams.get('variable3');
  var variable4 = urlParams.get('variable4');
  var variable5 = urlParams.get('variable5');
  var ImporteTotal = 0;
  // alert(variable1);
  // alert(variable2);
  // alert(variable3);caso ruta
  // alert(variable4);direccion
  // alert(variable5);id_envio

  function myFunc() {
    var now = new Date();
    var time = now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds();
    document.getElementById('hora').innerHTML = time;
  }
  myFunc();
  setInterval(myFunc, 1000);


  $.ajax({
    type: 'POST',
    url: '/verdum/tomarpedidob/valida_cliente_sunat',
    data: { variable1: variable1 },
    success: function (res) {
      if (res.vtitle == 'VALIDACION CLIENTE SUNAT') {
        document.querySelector('#cod').innerText = variable1 + ' - ' + variable2;
        $("#modalvalida").modal("show");
      }
    }
  });

  $('#btn_validar').on('click', function () {
    $("#modalvalida").modal('hide');
    window.location.href = 'https://verdum.com/verdum/registrocliente/index?variable1=' + variable1;
  });


  $('#btn_novalidar').on('click', function () {
    $("#modalvalida").modal('hide');
    window.location.href = "https://verdum.com/verdum/tomarpedido/index";
  });



  document.getElementById("documento").value = variable1
  document.getElementById("nombre").value = variable2.replace("&Ntilde;", "Ñ");
  document.getElementById("direccion").value = variable4.replace("&Ntilde;", "Ñ");

  $('#documento,#nombre,#direccion,#nombreproducto,#number').attr('readonly', true);
  //************************************Para obtener datos del producto ************************/
  $("#codproducto").change(function () {
    var codprod = $("#codproducto").val();
    var v_ruc = document.getElementById("documento").value;
    $.ajax({
      type: 'POST',
      url: '/verdum/tomarpedidob/buscar_producto',
      data: { codprod: codprod, v_ruc: v_ruc },
      success: function (res) {
        $('#unidad').val(res.v_undmedida);
      }
    });
  });
  //**********************************************************FUNCION SUCULENTA PARA CALCULO DE IMPORTE TOTAL****************************************INICIO
  $("#cantidad").change(function () {
    var cantidad = $('#cantidad').val();
    $('#tabla tr').each(function () {
      var tope = $(this).find("td").eq(0).html();
      var Comentario = $(this).find("td").eq(1).html();
      var Precio = $(this).find("td").eq(2).html();

      if (tope > 0) {
        var cadena = Comentario;
        var palabra = "mas";
        var index = cadena.indexOf(palabra);

        if (index >= 0) {
          //EXISTE
          if (parseFloat(cantidad) >= parseInt(tope)) {
            var total = (cantidad * Precio);

            $('#precio').val('');
            $('#precio').val(Precio);

            $('#importe').val('');
            $('#importe').val(parseFloat(parseFloat(total).toFixed(2)));
            return false;
          }
        } else {
          //NO EXITSE
          // alert("la palabra no existe dentro de la cadena");	
          if (parseFloat(cantidad) <= parseInt(tope)) {
            var total = (cantidad * Precio).toFixed(2);
            $('#precio').val('');
            $('#precio').val(Precio);
            $('#importe').val('');
            $('#importe').val(parseFloat(parseFloat(total).toFixed(2)));
            return false;
          }
        }
      }
    });
  });

  //CALCULAR IMPORTE
  $("#calcular").on("click", function () {
    var cantidad = $('#cantidad').val();
    $('#tabla tr').each(function () {
      var tope = $(this).find("td").eq(0).html();
      var Comentario = $(this).find("td").eq(1).html();
      var Precio = $(this).find("td").eq(2).html();
      if (tope > 0) {
        var cadena = Comentario;
        var palabra = "mas";
        var index = cadena.indexOf(palabra);

        if (index >= 0) {						//EXISTE
          if (parseFloat(cantidad) >= parseInt(tope)) {
            var total = (cantidad * Precio);
            $('#precio').val('');
            $('#precio').val(Precio);
            $('#importe').val('');
            $('#importe').val(parseFloat(parseFloat(total).toFixed(2)));
            return false;
          }
          // alert("la palabra existe dentro de la cadena y se encuentra en la posición " + index);	
        } else {
          if (parseFloat(cantidad) <= parseInt(tope)) {
            var total = (cantidad * Precio).toFixed(2);
            $('#precio').val('');
            $('#precio').val(Precio);
            $('#importe').val('');
            $('#importe').val(parseFloat(parseFloat(total).toFixed(2)));
            return false;
          }
        }
      }
    });
  });

  //******************************************************* */ CARGAR MODAL APROBAR INCIDENCIA
  $("#btn_cargar").on("click", function () {
    document.getElementById("precio").value = null;
    document.getElementById("importe").value = null;
    document.getElementById("cantidad").value = null;
    document.querySelector('#descripcion').innerText = $("#codproducto option:selected").text();

    var v_ruc = document.getElementById("documento").value;
    var v_CodProd = $("#codproducto").val();
    var direeccion = $("#direccion").val();


    var unidad = document.getElementById("unidad").value;
    var btrue = new Boolean(false);

    $('#mytable tr').each(function () {
      var codprod = $(this).find("td").eq(0).html();
      if (codprod == v_CodProd) {
        btrue = true
      }
    });

    if ((btrue == true)) {
      $("#codproducto").focus();
      Swal.fire({
        icon: "error",
        title: "YA AGREGO ESTE PRODUCTO",
        text: "Para volver a añadir quitar del detalle!",
        timer: 4000,
        timerProgressBar: true,
      })
      return;
    }

    if ((direeccion == "XXXXXXXXXXX" || direeccion == null)) {
      $("#direccion").focus();
      Swal.fire({
        icon: "warning",
        title: "SELECCIONE UNA DIRECCION",
        // text: "Seleccione una direccion del listado!",
        timer: 2300,
        timerProgressBar: true,
      });
      return;
    }
    if ((v_CodProd == "XXXXXX" || v_CodProd == null)) {
      $("#codproducto").focus();
      Swal.fire({
        icon: "warning",
        title: "SELECCIONE UN PRODUCTO",
        // text: "Seleccione un producto del listado!",
        timer: 2300,
        timerProgressBar: true,
      });
      return;
    }

    $.ajax({
      type: 'POST',
      url: '/verdum/tomarpedidob/valores_producto',
      data: { v_ruc: v_ruc, v_CodProd: v_CodProd },

      beforeSend: function () {
        $("#div-01").html("");
        $("#div-01").append(
          "<div id='div-01'>\<div class='d-flex justify-content-center my-1'>\
          <div class='spinner-border text-success' role='status' aria-hidden='true'></div>\</div>\ </div>"
        );
      },

   success: function (res) {
        $("#div-01").html("");
        if ((res.stock == 0)) {
          Swal.fire({
            icon: "warning",
            title: "PRODUCTO NO CUENTA STOCK",
            text: "En almacen la catidad es " + '..' + '(' + res.stock + ')',
            timer: 2400,
            timerProgressBar: true,
          })
          return false;
        }

        $('.tabla').html("");
        $('#th1').html(res.cabecera);
        $('#tb1').html(res.filas);

        document.getElementById("stock").value = res.stock
        document.getElementById("und").value = unidad

        $("#myModal").modal("show");
        $("#cantidad").focus();
      }
    });
  });


  //***********************************************************************TRABAJO CON MODAL***********************************INICIO
  $("#insertar").on("click", function () {

    var cod_prod = $("#codproducto option:selected").val();
    var precio = document.getElementById("precio").value;
    var cantidad = document.getElementById("cantidad").value;
    var importe = document.getElementById("importe").value;
    var stock = document.getElementById("stock").value;

    if ((cantidad == "" || cantidad == 0)) {
      $("#cantidad").focus();
      Swal.fire({
        icon: "warning",
        title: "Ingresar Cantidad",
        text: "Ingrese cantidad a llevar!",
        timer: 2300,
        timerProgressBar: true,
      });
      document.getElementById("cantidad").value = null;
      return;
    }

    if ((parseFloat(cantidad) > parseFloat(stock))) {
      $("#cantidad").focus();
      Swal.fire({
        icon: "warning",
        title: "STOCK INSUFICIENTE",
        text: "Cantidad ingresada es mayor al Stock!",
        timer: 3500,
        timerProgressBar: true,
      });
      return;
    }

    var i = 1; //contador para asignar id al boton que borrara la fila
    var fila = '<tr class="text-center" id="row' + i + '"><td width=25%  style= color:#0e2f44;font-size:90%;>' +
      cod_prod + '</td width=25%  style= color:#0e2f44;font-size:90%;><td width=25%  style= color:#0e2f44;font-size:90%;>' +
      precio + '</td><td width=25% style= color:#0e2f44;font-size:90%;>' +
      cantidad + '</td><td width=25% style= color:#0e2f44;font-size:90%;>' +
      importe + '</td><td width=25% ><button type="button" name="remove" id="' +
      i + '" class="btn btn-relief-danger btn_remove btn-block btn-sm"><i class="far fa-trash-alt"></button></td></tr>'; //esto seria lo que contendria la fila  
    i++;

    $('#mytable tr:first').after(fila);
    document.querySelector('#resumentableTotal').innerText = ''; //esta instruccion limpia el div adicioandos para que no se vayan acumulando
    var nFilas = $("#mytable tr").length;
    document.querySelector('#resumentableTotal').innerText = 'Cantidad de Items_ _ _ _ _ _ _ _ _ _ _    ' + (nFilas - 1);

    //le resto 1 para no contar la fila del header
    var importe = 0;
    $('#mytable tr').each(function () {
      var total = $(this).find("td").eq(3).html();
      if ((parseFloat(total)) > 0) {
        importe = (parseFloat(importe) + parseFloat(total));
      }
      ImporteTotal = 0;
      document.querySelector('#resumentableImporte').innerText = '';
      document.querySelector('#resumentableImporte').innerText = 'Suma Importe Total_ _ _ _ _ _ _ _ _     ' + parseFloat(importe).toFixed(2);
      ImporteTotal = parseFloat(importe).toFixed(2);

      $("#myModal").modal('hide');
    });
  });


  $(document).on('click', '.btn_remove', function () {
    var button_id = $(this).attr("id");
    //cuando da click obtenemos el id del boton
    $('#row' + button_id + '').remove(); //borra la fila
    //limpia el para que vuelva a contar las filas de la tabla
    document.querySelector('#resumentableTotal').innerText = '';
    var nFilas = $("#mytable tr").length;
    document.querySelector('#resumentableTotal').innerText = 'Cantidad de Items_ _ _ _ _ _ _ _ _ _ _    ' + (nFilas - 1);

    var importe = 0;
    $('#mytable tr').each(function () {
      var total = $(this).find("td").eq(3).html();
      if ((parseFloat(total)) > 0) {
        importe = (parseFloat(importe) + parseFloat(total));
      }
      document.querySelector('#resumentableImporte').innerText = '';
      document.querySelector('#resumentableImporte').innerText = 'Suma Importe Total_ _ _ _ _ _ _ _ _     ' + parseFloat(importe).toFixed(2);
    });
  });


  $('#procesar').on('click', function () {
    var post = 0;
    var codCliente = $('#documento').val();
    var horaInicio = $('#hora').text();
    var status = 'T';
    var total = ImporteTotal;// $('#total').val();
    var fueraruta = 0;
    var v_id_envio = variable5;
    var v_direccion  = variable4;


    if (variable3 == 'EN RUTA') {
      fueraruta = 1;
    }
    if (variable3 == 'FUERA DE RUTA') {
      fueraruta = 0;
    }

    valores = new Array();
    $('#mytable tr').each(function () {
      var separator = "/"
      var codigo = $(this).find('td').eq(0).html();
      var precio = $(this).find('td').eq(1).html();
      var cantiad = $(this).find('td').eq(2).html();
      var importe = $(this).find('td').eq(3).html();

      if (codigo === undefined) {
      }
      else {
        valor = new Array(codigo, precio, cantiad, importe, separator);
        valores.push(valor);
        console.log(valor);
      }
    });

    var data = valores.toString();
    if ((data == "")) {
      Swal.fire({
        icon: "warning",
        title: "INGRESE PRODUCTO PARA LA VENTA",
        // text: "Ingrese producto para la venta!",
        timer: 3000,
        timerProgressBar: true,
      });
      return false;
    }

    $.ajax({
      type: 'POST',
      url: '/verdum/tomarpedidob/guardar_venta',
      data: {
        post: post,
        codCliente: codCliente,
        horaInicio: horaInicio,
        status: status,
        total: total,
        fueraruta: fueraruta,
        v_id_envio : v_id_envio ,
        v_direccion: v_direccion  ,
        data: data,
      },

      beforeSend: function () {
        $("#div-00").html("");
        $("#div-00").append(
          "<div id='div-00'>\<div class='d-flex justify-content-center my-1'>\<div class='spinner-border text-success' role='status' aria-hidden='true'></div>\</div>\ </div>"
        );
      },
      success: function (res) {
        $("#div-00").html("");
        Swal.fire({
          icon: res.vicon,
          title: res.vtitle,
          text: res.vtext,
          timer: res.itimer,
          timerProgressBar: res.vprogressbar,
          showCancelButton: false,
          showConfirmButton: false,
        });
        var id = setInterval(function () {
          location.reload();
          clearInterval(id);
          location.href = "https://verdum.com/verdum/tomarpedido/index";
        }, res.itimer);
      }
    });
  });


  $('#cancelar').on('click', function () {
    var id = setInterval(function () {
      location.reload();
      clearInterval(id);
      location.href = "https://verdum.com/verdum/tomarpedido/index";
    }, 100);
  });



});


// padres menu
function navegacionmenu(string) {
  $.ajax({
    type: "POST",
    url: "/verdum/dashboard/cambiarsession",
    data: { string: string },
  });
  var dato = ""; //cerrado
  $.ajax({
    type: "POST",
    url: "/verdum/dashboard/cambiaropen",
    data: { string: dato },
  });
}

// hijos submenu
function clicksub(string) {
  $.ajax({
    type: "POST",
    url: "/verdum/dashboard/cambiarsessionsub",
    data: { string: string },
  });
  var dato = "open"; //cerrado
  $.ajax({
    type: "POST",
    url: "/verdum/dashboard/cambiaropen",
    data: { string: dato },
  });
}

