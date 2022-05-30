
$(function () {

  $("#idupdate").attr("readonly", true);
  $('#nombre').attr('readonly', true);
  $('#apellidos').attr('readonly', true);
  $('#nombreupdate').attr('readonly', true);
  
  var table = $("#example1").DataTable({
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
    ajax: {
      type: 'POST',
      url: "/verdum/registrousuario/cargar_tabla_usuarios",
    },
    columns: [
      { data: "i_id" },
      { data: "v_nombres" },
      { data: "v_apellidos" },
      { data: "v_correo" },
      { data: "v_perfil" },
      { data: "v_carnombre" },
      { data: "v_codvend" },
      { data: "v_sede" },
      { data: "d_fecharegistro" },
      {
        defaultContent:
          "<button type='button' class='editar btn btn-relief-primary btn-sm'><i class='fa fa-edit'></i></button>",
      },
    ],
    columnDefs: [
      {
        targets: 3,
        className: "text-center",
      },
      {
        targets: 4,
        className: "text-center",
      },
      {
        targets: 5,
        className: "text-center",
      },
      {
        targets: 6,
        className: "text-center",
      },
      {
        targets: 9,
        className: "text-center",
      },
    ],

    columnDefs: [
      {
        targets: 0,
        className: "text-center",
        render: function (data, type, row) {
          return "<span class='badge bg-pill  badge-light-danger'>" + data + "</span>";
        },
      },
    ],

    order: [
      [0, "asc"],
      [1, "asc"],
    ],

    lengthMenu: [
      [10, 25, 50, -1],
      ["10", "25", "50", "Todo"],
    ],
  });




  var obtener_data_editar = function (tbody, table) {
    $(tbody).on("click", "button.editar", function () {
      var data = table.row($(this).parents("tr")).data();
      console.log(data);
      $("#idupdate").val("");
      $("#idupdate").val(data.v_correo);
      $("#nombreupdate").val("");
      $("#nombreupdate").val(data.v_nombres);
      var nombreusuario = data.v_correo
      var post = 2

      $.ajax({
        type: "POST",
        url: "/verdum/registrousuario/lista_usuario_update",
        data: { post: post, nombreusuario: nombreusuario },
        success: function (res) {
          switch (res.estado) {
            case 0:
              var idperfil = res.idperfil
              var idcargo = res.idcargo
              var idvend = res.idvend
              var idsede = res.idsede

              $.ajax({
                type: "POST",
                url: "/verdum/registrousuario/validar_perfil",
                data: { idperfil: idperfil },
                success: function (res) {
                  $("#perfilupdate").html("");
                  $("#perfilupdate").append(res.perfiles);
                },
              });

              $.ajax({
                type: "POST",
                url: "/verdum/registrousuario/validar_cargo",
                data: { idcargo: idcargo },
                success: function (res) {
                  $("#cargoupdate").html("");
                  $("#cargoupdate").append(res.cargos);
                },
              });

              $.ajax({
                type: "POST",
                url: "/verdum/registrousuario/validar_vendedor",
                data: { idvend: idvend },
                success: function (res) {
                  $("#vendedorupdate").html("");
                  $("#vendedorupdate").append(res.vendedor);
                },
              });

              $.ajax({
                type: "POST",
                url: "/verdum/registrousuario/validar_sede",
                data: { idsede: idsede },
                success: function (res) {
                  $("#sedeupdate").html("");
                  $("#sedeupdate").append(res.sede);
                },
              });

              break;
            case 1:
              break;
          }
        },
      });

      // $("#nombreusuarioupdate").val("");
      // $("#nombreusuarioupdate").val(data.v_correo);
      $("#modal-editar").modal("show");
    });
  };

  obtener_data_editar("#example1", table);

  $("#btnagregar").on("click", function () {
    $("#modal-agregar").modal("show");
  });

  // Modal para agregar nuevo usuario
  $("#btncancelar").on("click", function () {
    $("#modal-agregar").modal("hide");
  });

  // Modal para actualizar
  $("#btncancelupdate").on("click", function () {
    $("#modal-editar").modal("hide");
  });


  // PARA VALIDAR USUARIO
  $("#nombreusuario").change(function () {
    var nombreusuario = $('#nombreusuario').val();
    var post = 2;
    $.ajax({
      type: "POST",
      url: "/verdum/registrousuario/validar_usuario",
      data: { post: post, nombreusuario: nombreusuario },
      success: function (res) { 
        switch (res.estado) {
          case 0:
            if (res.v_sede == "NOEXISTE") {
              $('#nombre').val(res.v_nombres.replace("&Ntilde;", "Ñ"));
              $('#apellidos').val(res.v_apellidos.replace("&Ntilde;", "Ñ"));
            } else {
              Swal.fire(
                'Usuario ya registrado!',
                '' + res.usuario + '---' + res.v_nombres,
                'success',
              )
              var id = setInterval(function () {
                location.reload();
                clearInterval(id);
              }, 1500);
            }

            break;
          case 1:
            // document.getElementById("nombreusuario").value = nombreusuario;
            Swal.fire(
              'DOCUMENTO NO EXISTE EL BD.!',
              'No se ha encntrado el documento',
              'warning',
            )
            var id = setInterval(function () {
              location.reload();
              clearInterval(id);
            }, 1500);
            break;


        }
      },
    });
  });



  $('#btnregistrar').on('click', function () {
    var post = 0;
    var nombre = $('#nombre').val();
    var apellidos = $('#apellidos').val();
    var nombreusuario = $('#nombreusuario').val();
    var contraseña = $('#contraseña').val();
    var perfil = $('#perfil option:selected').val();
    var idcargo = $('#cargo option:selected').val();
    var idvend = $('#vendedor option:selected').val();
    var idsede = $('#sede option:selected').val();

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
    if (idcargo == 0) {
      $("#cargo").focus();
      Swal.fire({
        icon: "info",
        title: "Selecion incorrecta",
        text: "Seleccionar cargo del listado!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }
    if (nombreusuario == "" || nombreusuario == null) {
      $("#nombreusuario").focus();
      Swal.fire({
        icon: "info",
        title: "Ingresar username de usuario",
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

    if (idvend == 0) {
      $("#vendedor").focus();
      Swal.fire({
        icon: "info",
        title: "Selecion incorrecta",
        text: "Seleccionar vendedor del listado!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }

    if (idsede == 0) {
      $("#sede").focus();
      Swal.fire({
        icon: "info",
        title: "Selecion incorrecta",
        text: "Seleccionar sede del listado!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }

    $.ajax({
      type: 'POST',
      url: '/verdum/registrousuario/guardar_usuario',
      data: {
        post: post,
        nombre: nombre,
        apellidos: apellidos,
        nombreusuario: nombreusuario,
        contraseña: contraseña,
        perfil: perfil,
        idcargo: idcargo,
        idvend: idvend,
        idsede: idsede,
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


  $('#btnactualizar').on('click', function () {
    var post = 1;
    var nombre = $('#nombreupdate').val();
    var apellidos = '';
    var nombreusuario = $('#idupdate').val();;
    var contraseña = $('#contraseñaupdate').val();
    var perfil = $('#perfilupdate option:selected').val();
    var idcargo = $('#cargoupdate option:selected').val();
    var idvend = $('#vendedorupdate option:selected').val();
    var idsede = $('#sedeupdate option:selected').val();
    //Solo validacion        
    var customSwitch10 = document.getElementById("customSwitch10").checked;

    if (nombreusuario == "" || nombreusuario == null) {
      $("#idupdate").focus();
      Swal.fire({
        icon: "info",
        title: "Ingresar un codigo para actualizar",
        text: "Favor de completar el campo!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }

    if (nombre == "" || nombre == null) {
      $("#nombreupdate").focus();
      Swal.fire({
        icon: "info",
        title: "Ingresar nombre de usuario",
        text: "Favor de completar el campo!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }
    if (perfil == 0) {
      $("#perfilupdate").focus();
      Swal.fire({
        icon: "info",
        title: "Selecion incorrecta",
        text: "Seleccionar perfil del listado!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }
    if (idcargo == 0) {
      $("#cargoupdate").focus();
      Swal.fire({
        icon: "info",
        title: "Selecion incorrecta",
        text: "Seleccionar cargo del listado!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }
    if ((customSwitch10 == true) && (contraseña == '')) {
      $("#contraseñaupdate").focus();
      Swal.fire({
        icon: "info",
        title: "Ingresar nueva contraseña",
        text: "Favor de completar el campo!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    } else {
      contraseña = '';
    }

    if (idsede == 0) {
      $("#sedeupdate").focus();
      Swal.fire({
        icon: "info",
        title: "Selecion incorrecta",
        text: "Seleccionar sede del listado!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }
    $.ajax({
      type: 'POST',
      url: '/verdum/registrousuario/guardar_usuario',
      data: {
        post: post,
        nombre: nombre,
        apellidos: apellidos,
        nombreusuario: nombreusuario,
        contraseña: contraseña,
        perfil: perfil,
        idcargo: idcargo,
        idvend: idvend,
        idsede: idsede,
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


// window.onload = function () {
//   var fecha = new Date(); //Fecha actual
//   var mes = fecha.getMonth() + 1; //obteniendo mes
//   var dia = fecha.getDate(); //obteniendo dia
//   var ano = fecha.getFullYear(); //obteniendo año
//   if (dia < 10)
//     dia = '0' + dia; //agrega cero si el menor de 10
//   if (mes < 10)
//     mes = '0' + mes //agrega cero si el menor de 10
//   document.getElementById('fecha').value = ano + "-" + mes + "-" + dia;
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





