$(function () {


  // Variables Globales
  var queryString = window.location.search;
  var urlParams = new URLSearchParams(queryString);
  var variable1 = urlParams.get('variable1');

  $('#codigovendedor').attr('readonly', true);
  var customSwitch10 = document.getElementById("customSwitch10").checked;

  // alert(customSwitch10);
  if (customSwitch10 == true) {
    $('#nombrevendedor').attr('readonly', true);
  } else {
  }

  $("#customSwitch10").change(function () {

    var cambioswitch = document.getElementById("customSwitch10").checked;
    if (cambioswitch == true) {
      $('#nombrevendedor').attr('readonly', true);
      $('#documento').attr('readonly', false);
      document.getElementById("nombrevendedor").value = "";
    } else {
      $('#nombrevendedor').attr('readonly', false);
      $('#documento').attr('readonly', true);
      document.getElementById("documento").value = "";
      document.getElementById("nombrevendedor").value = "";
    }
  });



  if (variable1 !== null) {
    $('#documento').attr('readonly', true);
 
    document.getElementById('customSwitch10').disabled = true;

    $.ajax({
      type: "POST",
      url: "/verdum/registrovendedorb/consultar_vendedor",
      data: { variable1: variable1 },
      beforeSend: function () {
        $("#div-01").html("");
        $("#div-01").append(
          "<div id='div-01'>\<div class='d-flex justify-content-center my-1'>\<div class='spinner-border text-success' role='status' aria-hidden='true'></div>\</div>\ </div>"
        );
      },
      success: function (res) {
        $("#div-01").html("");
        switch (res.dato) {
          case 0:
            $('#codigovendedor').val(res.v_codvend.replace("&Ntilde;", "Ñ"));
            $('#documento').val(res.v_documento.replace("&Ntilde;", "Ñ"));
            $('#nombrevendedor').val(res.v_nombre.replace("&Ntilde;", "Ñ"));

  
            if (res.v_documento == "" || res.v_documento == null) {
              $('#nombrevendedor').attr('readonly', false);
              $('#documento').attr('readonly', false);
            }





            var perfil = res.v_cargo;
            $.ajax({
              type: "POST",
              url: "/verdum/registrovendedorb/consultar_perfilvendedor",
              data: { perfil: perfil },
              success: function (res) {
                $("#perfilvendedor").html("");
                $("#perfilvendedor").append(res.perfilvend);
              },
            });

            var supervisor = res.i_idsup;
            $.ajax({
              type: "POST",
              url: "/verdum/registrovendedorb/consultar_supervisor",
              data: { supervisor: supervisor },
              success: function (res) {
                $("#supervisor").html("");
                $("#supervisor").append(res.supervisor);
              },
            });

            var zonaventa = res.i_idzona;
            $.ajax({
              type: "POST",
              url: "/verdum/registrovendedorb/consultar_zonaventa",
              data: { zonaventa: zonaventa },
              success: function (res) {
                $("#zonaventa").html("");
                $("#zonaventa").append(res.zonaventa);
              },
            });

            var almacen = res.i_idsede;
            $.ajax({
              type: "POST",
              url: "/verdum/registrovendedorb/consultar_almacen",
              data: { almacen: almacen },
              success: function (res) {
                $("#sede").html("");
                $("#sede").append(res.sede);
              },
            });

            var jefeventa = res.i_idjefe;
            $.ajax({
              type: "POST",
              url: "/verdum/registrovendedorb/consultar_jefeventa",
              data: { jefeventa: jefeventa },
              success: function (res) {
                $("#jefeventa").html("");
                $("#jefeventa").append(res.jefeventa);
              },
            });
            break;
        }
      },
    });
  }



  var table = $("#tbvendedor").DataTable({
    lengthChange: true,
    responsive: true,
    autoWidth: false,
    language: {
      decimal: "",
      emptyTable: "No hay información",
      info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
      infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
      infoFiltered: "(Filtrado de _MAX_ total entradas)",
      infoPostFix: "",
      thousands: ",",
      lengthMenu: "Mostrar _MENU_ Entradas",
      loadingRecords: "Cargando...",
      processing: "Procesando...",
      search: "Buscar:",
      zeroRecords: "Sin resultados encontrados",
      paginate: {
        first: "Primero",
        last: "Ultimo",
        next: "Siguiente",
        previous: "Anterior",
      },
    },
    order: [
      [0, "asc"],
      // [1, "asc"],
    ],
    lengthMenu: [
      [25, 50, 100, -1],
      ["25", "50", "100", "Todo"],
    ],
  });

  $('#cancelar').on('click', function () {
    location.href = "https://verdum.com/verdum/registrovendedor/index";
  });


  // PARA VALIDAR USUARIO
  $("#documento").change(function () {
    var nombreusuario = $('#documento').val();
    var post = 3;
    $.ajax({
      type: "POST",
      url: "/verdum/registrovendedorb/validar_usuario",
      data: { post: post, nombreusuario: nombreusuario },

      beforeSend: function () {
        $("#div-01").html("");
        $("#div-01").append(
          "<div id='div-01'>\<div class='d-flex justify-content-center my-1'>\<div class='spinner-border text-success' role='status' aria-hidden='true'></div>\</div>\ </div>"
        );
      },

      success: function (res) {
        $("#div-01").html("");
        switch (res.estado) {
          case 0:
            if (res.v_apellidos == "EXISTE") {
              document.getElementById("nombrevendedor").value = "";
              Swal.fire(
                'Usuario ya registrado!',
                '' + res.usuario + '---' + res.v_nombres,
                'success',
              )
              var id = setInterval(function () {
                location.reload();
                clearInterval(id);
              }, 2000);
            } else {
              document.getElementById("nombrevendedor").value = res.v_nombres;
            }
            break;
          case 1:
            Swal.fire(
              'DOCUMENTO NO EXISTE EL BD.!',
              'No se ha encntrado el documento',
              'warning',
            )
            var id = setInterval(function () {
              location.reload();
              clearInterval(id);
            }, 1000);
            break;
        }
      },
    });
  });



  $('#procesar').on('click', function () {
    var post = 0;
    var codigo = $('#codigovendedor').val();
    var dni = $('#documento').val();
    var nombrevendedor = $('#nombrevendedor').val();
    var idcargo = $('#perfilvendedor option:selected').val();
    var idsup = $('#supervisor option:selected').val();
    var zonavta = $('#zonaventa option:selected').val();
    var almacen = $('#sede option:selected').val();
    var jefevta = $('#jefeventa option:selected').val();
    var estado = $('#estado option:selected').val();

    $.ajax({
      type: 'POST',
      url: '/verdum/registrovendedorb/guardar_vendedor',
      data: {
        post: post,
        codigo: codigo,
        dni: dni,
        nombrevendedor: nombrevendedor,
        idcargo: idcargo,
        idsup: idsup,
        zonavta: zonavta,
        almacen: almacen,
        jefevta: jefevta,
        estado: estado,
      },

      beforeSend: function () {
        $("#div-00").html("");
        $("#div-00").append(
          "<div id='div-01'>\<div class='d-flex justify-content-center my-1'>\<div class='spinner-border text-success' role='status' aria-hidden='true'></div>\</div>\ </div>"
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
          location.href = "https://verdum.com/verdum/registrovendedor/index";
        }, res.itimer);
      }
    });
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

