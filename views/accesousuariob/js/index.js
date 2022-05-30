
$(function () {
  $("#username").attr("readonly", true);
  $("#tipodato").attr("readonly", true);
  $("#faq1dato").attr("readonly", true);
  $("#faq2dato").attr("readonly", true);

  // Variables Globales
  var queryString = window.location.search;
  var urlParams = new URLSearchParams(queryString);
  var variable1 = urlParams.get('variable1');
  var variable2 = urlParams.get('variable2');
  var usuario = variable1 + ' * ' + variable2;
  var v_token = $("#v_token").val();
  var post0 = 1;
  var post1 = 1;
  var post2 = 2;
  $("#username").val(usuario);

  // Ajax calato para listado de datos temporales.
  $.ajax({
    type: "POST",
    url: "/verdum/accesousuariob/listado_dato_temporal",
    data: { post: post0, variable1: variable1 },
    success: function (res) {
      $("#tipodato").val(res.faq1);
      $("#faq1dato").val(res.faq2);
      $("#faq2dato").val(res.faq3);
    },
  });

  var table = $("#example").DataTable({
    destroy: true,
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
      url: "/verdum/accesousuariob/table1_restriccion_user_dato",
      data: { post: post1, variable1: variable1 },
    },
    columns: [
      { data: "i_id_row" },
      { data: "v_tipo" },
      { data: "i_iD" },
      { data: "v_descripcion" },
      { data: "v_faq1" },
      { data: "v_faq2" },
      { data: "v_estado" },
    ],
    columnDefs: [
      {
        targets: 0,
        checkboxes: {
          attr: { id: "cbx1" },
        },
      },
      {
        targets: 6,
        className: "text-center",
        render: function (data, type, row) {
          if (row.v_estado == 'Pendiente') {
            return "<span class='badge badge-danger'>" + data + "</span>";
          }
          return "<span class='badge badge-success'>" + data + "</span>";
        },
      },
    ],
    aLengthMenu: [
      [25, 50, 100, 200, -1],
      [25, 50, 100, 200, "All"]
    ],
    order: [[0, "asc"]],
  });

  $("#AsignarDato").on("click", function () {

    var tipo = $('#tipodato').val();

    if (tipo == '') {
      $("#tipo").focus();
      Swal.fire({
        icon: "warning",
        title: "Selecion incorrecta",
        text: "Seleccionar un tipo para restringir!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }

    let post = 0;
    let ids = [];
    let rows_selected = table.column(0).checkboxes.selected();
    let prearray = rows_selected.join(",");
    ids = prearray.split(",");

    console.log(ids);

    if (prearray != "") {
      $.ajax({
        type: "POST",
        url: "/verdum/accesousuariob/guardar_acceso_datos_usuario",
        data: {
          post: post,
          ids: ids,
          tipo: tipo,
          variable1: variable1
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
        },
      });
    } else {
      Swal.fire({
        icon: "info",
        title: "No ha seleccionado ningun menu...",
        text: "Favor de seleccionado uno!",
        timer: 3000,
        timerProgressBar: true,
      });
    }

  });

  // ********************************************************************************** DESDE AQUI DATOS PARA LA SEGUNDA TABLA


  var table1 = $("#example1").DataTable({
    destroy: true,
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
      url: "/verdum/accesousuariob/table2_restriccion_user_dato",
      data: { post: post2, variable1: variable1 },
    },
    columns: [
      { data: "i_id_row" },
      { data: "v_tipo" },
      { data: "i_iD" },
      { data: "v_descripcion" },
      { data: "v_faq1" },
      { data: "v_faq2" },
      { data: "v_estado" },
    ],
    columnDefs: [
      {
        targets: 0,
        checkboxes: {
          attr: { id: "cbx1" },
        },
      },
      {
        targets: 6,
        className: "text-center",
        render: function (data, type, row) {
          if (row.v_estado == 'Pendiente') {
            return "<span class='badge badge-danger'>" + data + "</span>";
          }
          return "<span class='badge badge-success'>" + data + "</span>";
        },
      },
    ],
    aLengthMenu: [
      [25, 50, 100, 200, -1],
      [25, 50, 100, 200, "All"]
    ],
    order: [[0, "asc"]],
  });


  $("#QuitarDato").on("click", function () {

    var tipo = $('#tipodato').val();

    if (tipo == '') {
      $("#tipo").focus();
      Swal.fire({
        icon: "warning",
        title: "Selecion incorrecta",
        text: "Seleccionar un tipo para restringir!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }


    let post = 1;
    let ids = [];
    let rows_selected = table1.column(0).checkboxes.selected();
    let prearray = rows_selected.join(",");
    ids = prearray.split(",");

    if (prearray != "") {
      $.ajax({
        type: "POST",
        url: "/verdum/accesousuariob/guardar_acceso_datos_usuario",
        data: {
          post: post,
          ids: ids,
          tipo: tipo,
          variable1: variable1
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
        },
      });
    } else {
      Swal.fire({
        icon: "info",
        title: "No ha seleccionado ningun menu...",
        text: "Favor de seleccionado uno!",
        timer: 3000,
        timerProgressBar: true,
      });
    }
  });



  $("#tipo").change(function () {
    var post = 0;
    var tipo = $("#tipo").val();
    var faq1 = '';
    var faq2 = '';

    $.ajax({
      type: "POST",
      url: "/verdum/accesousuariob/cargar_filtros_Faq",
      data: { tipo: tipo, variable1: variable1 },
      success: function (res) {
        $("#Faq1").html("");
        $("#Faq1").append(res.Faq1);
        $("#Faq2").html("");
        $("#Faq2").append(res.Faq2);
      },
    });


    $.ajax({
      type: "POST",
      url: "/verdum/accesousuariob/guardar_dato_restriccion",
      data: {
        post: post,
        variable1: variable1,
        tipo: tipo,
        faq1: faq1,
        faq2: faq2
      },
      success: function (res) {
      },
    });

  });


  $("#Faq1").change(function () {
    var post = 0;
    var tipo = $("#tipo").val();
    var faq1 = $("#Faq1").val();
    var faq2 = '';
    $.ajax({
      type: "POST",
      url: "/verdum/accesousuariob/guardar_dato_restriccion",
      data: {
        post: post,
        variable1: variable1,
        tipo: tipo,
        faq1: faq1,
        faq2: faq2
      },
      success: function (res) {
      },
    });

    $.ajax({
      type: "POST",
      url: "/verdum/accesousuariob/cargar_filtros_Faq2",
      data: { tipo: tipo, faq1: faq1, variable1: variable1 },
      success: function (res) {
        $("#Faq2").html("");
        $("#Faq2").append(res.Faq2);
      },
    });
  });


  $("#Faq2").change(function () {
    var post = 0;
    var tipo = $("#tipo").val();
    var faq1 = $("#Faq1").val();
    var faq2 = $("#Faq2").val();
    $.ajax({
      type: "POST",
      url: "/verdum/accesousuariob/guardar_dato_restriccion",
      data: {
        post: post,
        variable1: variable1,
        tipo: tipo,
        faq1: faq1,
        faq2: faq2
      },
      success: function (res) {
      },
    });
  });


  // FILTRO

  $('#filtrar').on('click', function () {

    location.reload();

    // $("#example").dataTable().fnDestroy();
    // $("#example1").dataTable().fnDestroy();

    // var tipo = $('#tipo option:selected').val();
    // var Faq1 = $('#Faq1 option:selected').val();
    // var Faq2 = $('#Faq2 option:selected').val();

    // if (tipo == "XXXXXXXXXX") {
    //   $("#tipo").focus();
    //   Swal.fire({
    //     icon: "warning",
    //     title: "Selecion incorrecta",
    //     text: "Seleccionar un tipo para restringir!",
    //     timer: 3000,
    //     timerProgressBar: true,
    //   });
    //   return;
    // }

    // if (Faq1 == "XXXXXXXXXX") {
    //   Faq1 = '';
    // }
    // if (Faq2 == "XXXXXXXXXX") {
    //   Faq2 = '';
    // }

    // // var table = $("#example").DataTable({
    // //   destroy: true,
    // //   lengthChange: true,
    // //   responsive: true,
    // //   autoWidth: false,
    // //   language: {
    // //     decimal: "",
    // //     emptyTable: "No hay información",
    // //     info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
    // //     infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
    // //     infoFiltered: "(Filtrado de _MAX_ total entradas)",
    // //     infoPostFix: "",
    // //     thousands: ",",
    // //     lengthMenu: "Mostrar _MENU_ Entradas",
    // //     loadingRecords: "Cargando...",
    // //     processing: "Procesando...",
    // //     search: "Buscar:",
    // //     zeroRecords: "Sin resultados encontrados",
    // //     paginate: {
    // //       first: "Primero",
    // //       last: "Ultimo",
    // //       next: "Siguiente",
    // //       previous: "Anterior",
    // //     },
    // //   },
    // //   ajax: {
    // //     type: 'POST',
    // //     url: "/verdum/accesousuariob/table1_restriccion_user_dato",
    // //     data: { tipo: tipo, Faq1: Faq1, Faq2: Faq2, variable1: variable1 },
    // //   },
    // //   columns: [
    // //     { data: "i_id_row" },
    // //     { data: "v_tipo" },
    // //     { data: "i_iD" },
    // //     { data: "v_descripcion" },
    // //     { data: "v_faq1" },
    // //     { data: "v_faq2" },
    // //     { data: "v_estado" },
    // //   ],
    // //   columnDefs: [
    // //     {
    // //       targets: 0,
    // //       checkboxes: {
    // //         attr: { id: "cbx1" },
    // //       },
    // //     },
    // //     {
    // //       targets: 6,
    // //       className: "text-center",
    // //       render: function (data, type, row) {
    // //         if (row.v_estado == 'Pendiente') {
    // //           return "<span class='badge badge-danger'>" + data + "</span>";
    // //         }
    // //         return "<span class='badge badge-success'>" + data + "</span>";
    // //       },
    // //     },
    // //   ],
    // //   aLengthMenu: [
    // //     [25, 50, 100, 200, -1],
    // //     [25, 50, 100, 200, "All"]
    // //   ],
    // //   order: [[0, "asc"]],
    // // });



    // $("#AsignarDato").on("click", function () {

    //   var tipo = $('#tipo option:selected').val();

    //   if (tipo == "XXXXXXXXXX") {
    //     $("#tipo").focus();
    //     Swal.fire({
    //       icon: "warning",
    //       title: "Selecion incorrecta",
    //       text: "Seleccionar un tipo para restringir!",
    //       timer: 3000,
    //       timerProgressBar: true,
    //     });
    //     return;
    //   }

    //   let post = 0;
    //   let ids = [];
    //   let rows_selected = table.column(0).checkboxes.selected();
    //   let prearray = rows_selected.join(",");
    //   ids = prearray.split(",");

    //   alert(ids);

    //   // if (prearray != "") {
    //   //   $.ajax({
    //   //     type: "POST",
    //   //     url: "/verdum/accesousuariob/guardar_acceso_datos_usuario",
    //   //     data: {
    //   //       post: post,
    //   //       ids: ids,
    //   //       tipo: tipo,
    //   //       variable1: variable1
    //   //     },
    //   //     success: function (res) {
    //   //       Swal.fire({
    //   //         icon: res.vicon,
    //   //         title: res.vtitle,
    //   //         text: res.vtext,
    //   //         timer: res.itimer,
    //   //         timerProgressBar: res.vprogressbar,
    //   //         showCancelButton: false,
    //   //         showConfirmButton: false,
    //   //       });
    //   //       var id = setInterval(function () {
    //   //         location.reload();
    //   //         clearInterval(id);
    //   //       }, res.itimer);
    //   //     },
    //   //   });
    //   // } else {
    //   //   Swal.fire({
    //   //     icon: "info",
    //   //     title: "No ha seleccionado ningun menu...",
    //   //     text: "Favor de seleccionado uno!",
    //   //     timer: 3000,
    //   //     timerProgressBar: true,
    //   //   });
    //   // }

    // });


    // // Desde aquin carga tabla 2  con accesos ya asignados
    // var table1 = $("#example1").DataTable({
    //   destroy: true,
    //   lengthChange: true,
    //   responsive: true,
    //   autoWidth: false,
    //   language: {
    //     decimal: "",
    //     emptyTable: "No hay información",
    //     info: "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
    //     infoEmpty: "Mostrando 0 to 0 of 0 Entradas",
    //     infoFiltered: "(Filtrado de _MAX_ total entradas)",
    //     infoPostFix: "",
    //     thousands: ",",
    //     lengthMenu: "Mostrar _MENU_ Entradas",
    //     loadingRecords: "Cargando...",
    //     processing: "Procesando...",
    //     search: "Buscar:",
    //     zeroRecords: "Sin resultados encontrados",
    //     paginate: {
    //       first: "Primero",
    //       last: "Ultimo",
    //       next: "Siguiente",
    //       previous: "Anterior",
    //     },
    //   },
    //   ajax: {
    //     type: 'POST',
    //     url: "/verdum/accesousuariob/table2_restriccion_user_dato",
    //     data: { tipo: tipo, Faq1: Faq1, Faq2: Faq2, variable1: variable1 },
    //   },
    //   columns: [
    //     { data: "i_id_row" },
    //     { data: "v_tipo" },
    //     { data: "i_iD" },
    //     { data: "v_descripcion" },
    //     { data: "v_faq1" },
    //     { data: "v_faq2" },
    //     { data: "v_estado" },
    //   ],
    //   columnDefs: [
    //     {
    //       targets: 0,
    //       checkboxes: {
    //         attr: { id: "cbx1" },
    //       },
    //     },
    //     {
    //       targets: 6,
    //       className: "text-center",
    //       render: function (data, type, row) {
    //         if (row.v_estado == 'Pendiente') {
    //           return "<span class='badge badge-danger'>" + data + "</span>";
    //         }
    //         return "<span class='badge badge-success'>" + data + "</span>";
    //       },
    //     },
    //   ],
    //   aLengthMenu: [
    //     [25, 50, 100, 200, -1],
    //     [25, 50, 100, 200, "All"]
    //   ],

    //   order: [[0, "asc"]],
    // });


    // // $("#QuitarDato").on("click", function () {
    // //   var tipo = $('#tipo option:selected').val();
    // //   if (tipo == "XXXXXXXXXX") {
    // //     $("#tipo").focus();
    // //     Swal.fire({
    // //       icon: "warning",
    // //       title: "Selecion incorrecta",
    // //       text: "Seleccionar un tipo para restringir!",
    // //       timer: 3000,
    // //       timerProgressBar: true,
    // //     });
    // //     return;
    // //   }

    // //   let post = 1;
    // //   let ids = [];
    // //   let rows_selected = table1.column(0).checkboxes.selected();
    // //   let prearray = rows_selected.join(",");
    // //   ids = prearray.split(",");

    // //   if (prearray != "") {
    // //     $.ajax({
    // //       type: "POST",
    // //       url: "/verdum/accesousuariob/guardar_acceso_datos_usuario",
    // //       data: {
    // //         post: post,
    // //         ids: ids,
    // //         tipo: tipo,
    // //         variable1: variable1
    // //       },
    // //       success: function (res) {
    // //         Swal.fire({
    // //           icon: res.vicon,
    // //           title: res.vtitle,
    // //           text: res.vtext,
    // //           timer: res.itimer,
    // //           timerProgressBar: res.vprogressbar,
    // //           showCancelButton: false,
    // //           showConfirmButton: false,
    // //         });
    // //         var id = setInterval(function () {
    // //           location.reload();
    // //           clearInterval(id);
    // //         }, res.itimer);
    // //       },
    // //     });
    // //   } else {
    // //     Swal.fire({
    // //       icon: "info",
    // //       title: "No ha seleccionado ningun menu...",
    // //       text: "Favor de seleccionado uno!",
    // //       timer: 3000,
    // //       timerProgressBar: true,
    // //     });
    // //   }
    // // });



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
