
$(function () {

  var table = $("#tbusuarios").DataTable({
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
      [1, "asc"],
    ],
    lengthMenu: [
      [25, 50, 100, -1],
      ["25", "50", "100", "Todo"],
    ],
  });

  // // PARA COMBO DE TIPOS DE DATOS 
  //   $("#tipo").change(function () {
  //     var tipo = $("#tipo").val();
  //     document.getElementById("username").value = tipo;  
  //     $.ajax({
  //       type: "POST",
  //       url: "/verdum/accesousuariob/cargar_filtros_Faq",
  //       data: { tipo: tipo },
  //       success: function (res) {
  //         $("#Faq1").html("");
  //         $("#Faq1").append(res.Faq1);

  //         $("#Faq2").html("");
  //         $("#Faq2").append(res.Faq2);
  //       },
  //     });
  //   });



  // PARA VALIDAR USUARIO
  $("#nombreusuario").change(function () {
    var nombreusuario = $('#nombreusuario').val();
    $.ajax({
      type: "POST",
      url: "/verdum/registrousuario/validar_usuario",
      data: { nombreusuario: nombreusuario },
      success: function (res) {
        switch (res.estado) {
          case 0:
            Swal.fire(
              'Usuario ya registrado!',
              '' + res.usuario + '---' + res.v_nombres,
              'success',
            )
            var id = setInterval(function () {
              location.reload();
              clearInterval(id);
            }, 800);
            break;
          case 1:
            document.getElementById("nombreusuario").value = nombreusuario;
            break;
        }
      },
    });
  });



  $('#btnregistrar').on('click', function () {
    var nombre = $('#nombre').val();
    var apellidos = $('#apellidos').val();
    var nombreusuario = $('#nombreusuario').val();
    var contraseña = $('#contraseña').val();
    var perfil = $('#perfil option:selected').val();

    if (nombre == "" || nombre == null) {
      $("#nombre").focus();
      Swal.fire({
        icon: "info",
        title: "Ingresar nombre de usuario",
        text: "Favor de completar el campo!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }

    if (apellidos == "" || apellidos == null) {
      $("#apellidos").focus();
      Swal.fire({
        icon: "info",
        title: "Ingresar apellidos de usuario",
        text: "Favor de completar el campo!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }
    if (contraseña == "" || contraseña == null) {
      $("#contraseña").focus();
      Swal.fire({
        icon: "info",
        title: "Ingresar contraseña de usuario",
        text: "Favor de completar el campo!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }

    if (perfil == 0) {
      $("#perfil").focus();
      Swal.fire({
        icon: "info",
        title: "Selecion incorrecta",
        text: "Seleccionar perfil del listado!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }

    $.ajax({
      type: 'POST',
      url: '/verdum/registrousuario/guardar_usuario',
      data: {
        nombre: nombre,
        apellidos: apellidos,
        nombreusuario: nombreusuario,
        contraseña: contraseña,
        perfil: perfil,
      },
      success: function (res) {
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
        }, res.itimer);
      }
    });
  });
});


// window.onload = function(){
//   var fecha = new Date(); //Fecha actual
//   var mes = fecha.getMonth()+1; //obteniendo mes
//   var dia = fecha.getDate(); //obteniendo dia
//   var ano = fecha.getFullYear(); //obteniendo año
//   if(dia<10)
//     dia='0'+dia; //agrega cero si el menor de 10
//   if(mes<10)
//     mes='0'+mes //agrega cero si el menor de 10
//   document.getElementById('fecha').value=ano+"-"+mes+"-"+dia;
// }

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
