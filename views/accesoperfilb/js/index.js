$(function () {

  var queryString = window.location.search;
  var urlParams = new URLSearchParams(queryString);
  var variable1 = urlParams.get('variable1');
  var variable2 = urlParams.get('variable2');


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
      url: "/verdum/accesoperfilb/cargar_menu_perfil",
      data: { variable1: variable1 },
    },
    columns: [
      { data: "i_id" },
      { data: "Nombre" },
      { data: "submenu" },
      { data: "status" },
      {
        defaultContent:
          "<span class='badge badge-warning'>" + variable2 + "</span>"
      },
    ],
    columnDefs: [
      {
        targets: 0,
        checkboxes: {
          attr: { id: "cbx1" },
        },
      },
      {
        targets: 3,
        className: "text-center",
        render: function (data, type, row) {
          if (row.status == 'Pendiente') {
            return "<span class='badge badge-danger'>" + data + "</span>";
          }
          return "<span class='badge badge-success'>" + data + "</span>";
        },
      },
      { className: "text-center", targets: 4 },
    ],
    order: [[0, "asc"]],
  });




  
  $("#AsignarMenu").on("click", function () {
    let post = 0;
    let ids = [];
    let rows_selected = table.column(0).checkboxes.selected();
    let prearray = rows_selected.join(",");
    ids = prearray.split(",");

    if (prearray != "") {
      $.ajax({
        type: "POST",
        url: "/verdum/accesoperfilb/guardar_acceso_menu",
        data: {
          post: post,
          variable1: variable1,
          ids: ids,
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
      url: "/verdum/accesoperfilb/carga_menu_perfil_acceso",
      data: { variable1: variable1 },
    },
    columns: [
      { data: "i_id" },
      { data: "Nombre" },
      { data: "submenu" },    
      { data: "status" },
      { data: "perfil" },
    ],
    columnDefs: [
      {
        targets: 0,
        checkboxes: {
          attr: { id: "cbx1" },
        },
      },
      {
        targets: 3,
        className: "text-center",
        render: function (data, type, row) {
          if (row.status == 'Asignado') {
            return "<span class='badge badge-success'>" + data + "</span>";
          }
          return data;
        },
      }],
    order: [[0, "asc"]],
  });



  
  $("#QuitarMenu").on("click", function () {
    let post = 1;
    let ids = [];
    let rows_selected = table1.column(0).checkboxes.selected();
    let prearray = rows_selected.join(",");
    ids = prearray.split(",");

    if (prearray != "") {
      $.ajax({
        type: "POST",
        url: "/verdum/accesoperfilb/guardar_acceso_menu",
        data: {
          post: post,
          variable1: variable1,
          ids: ids,
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

  // ********************************************************************* desde aqui accesos para submenus
  var table2 = $("#example2").DataTable({
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
      url: "/verdum/accesoperfilb/cargar_submenu_perfil",
      data: { variable1: variable1 },
    },
    columns: [
      { data: "i_id" },
      { data: "Nombre" },
      { data: "v_menu" },
      { data: "status" },
      {
        defaultContent:
          "<span class='badge badge-warning'>" + variable2 + "</span>"
      },
    ],
    columnDefs: [
      {
        targets: 0,
        checkboxes: {
          attr: { id: "cbx1" },
        },
      },
      {
        targets: 3,
        className: "text-center",
        render: function (data, type, row) {
          if (row.status == 'Pendiente') {
            return "<span class='badge badge-danger'>" + data + "</span>";
          }
          return "<span class='badge badge-success'>" + data + "</span>";
        },
      },
      { className: "text-center", targets: 4 },
    ],
    order: [[0, "asc"]],
  });

  
  $("#AsignarSubMenu").on("click", function () {
    let post = 0;
    let ids = [];
    let rows_selected = table2.column(0).checkboxes.selected();
    let prearray = rows_selected.join(",");
    ids = prearray.split(",");


    if (prearray != "") {
      $.ajax({
        type: "POST",
        url: "/verdum/accesoperfilb/guardar_acceso_submenu",
        data: {
          post: post,
          variable1: variable1,
          ids: ids,
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
        title: "No ha seleccionado ningun submenu...",
        text: "Favor de seleccionado uno!",
        timer: 3000,
        timerProgressBar: true,
      });
    }
  });

  

  var table3 = $("#example3").DataTable({
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
      url: "/verdum/accesoperfilb/cargar_submenu_perfil_acceso",
      data: { variable1: variable1 },
    },
    columns: [
      { data: "i_id" },
      { data: "Nombre" },
      { data: "v_menu" },
      { data: "status" },
      {
        defaultContent:
          variable2
      },
    ],
    columnDefs: [
      {
        targets: 0,
        checkboxes: {
          attr: { id: "cbx1" },
        },
      },
      {
        targets: 3,
        className: "text-center",
        render: function (data, type, row) {
          if (row.status == 'Asignado') {
            return "<span class='badge badge-success'>" + data + "</span>";
          }
          return data;
        },
      }
    ],
    order: [[0, "asc"]],
  });

  
$("#QuitarSubMenu").on("click", function () {
  let post = 1;
  let ids = [];
  let rows_selected = table3.column(0).checkboxes.selected();
  let prearray = rows_selected.join(",");
  ids = prearray.split(",");


  if (prearray != "") {
    $.ajax({
      type: "POST",
      url: "/verdum/accesoperfilb/guardar_acceso_submenu",
      data: {
        post: post,
        variable1: variable1,
        ids: ids,
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
      title: "No ha seleccionado ningun submenu...",
      text: "Favor de seleccionado uno!",
      timer: 3000,
      timerProgressBar: true,
    });
  }
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
