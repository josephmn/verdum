
$(function () {

  var table = $("#tbfruta").DataTable({
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

  $('#busqueda').on('click', function () {
    $("#div-01").html("");
  });

  $('#buscarcliente').on('click', function () {
    var v_busqueda = $("#busqueda").val();
    if (v_busqueda == "" || v_busqueda == null) {
      $("#buscarcliente").focus();
      Swal.fire({
        icon: "warning",
        title: "Ingresar dato",
        text: "Ingrese dato a buscar!",
        timer: 2000,
        timerProgressBar: true,
      });
      return;
    }
    table2 = $("#tbfruta").DataTable({
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
        url: "/verdum/listadeclientes/cargar_table_clientes",
        data: { v_busqueda: v_busqueda },
        beforeSend: function () {
          $("#div-01").html("");
          $("#div-01").append(
            "<div id='div-01'>\<div class='d-flex justify-content-center my-1'>\<div class='spinner-border text-success' role='status' aria-hidden='true'></div>\</div>\ </div>"
          );
        },
      },
      columns: [
        { data: "v_codliente" },
        { data: "v_razonSocial" },
        { data: "v_clasificacion" },
        { data: "v_direccion" },
        { data: "v_fechaUltimaVisita" },
        { data: "v_ruta" },
        { data: "v_estado" },
        {
          defaultContent:
            "<button type='button' class='editar btn btn-relief-primary btn-sm'><i class='fa fa-edit'></i></button>",
        },
      ],
      columnDefs: [
        {
          targets: 0,
          className: "text-center",
          render: function (data, type, row) {
            if (row.v_estado == 'PENDIENTE DE VALIDAR') {
              $("#div-01").html("");
              return "<span class='badge bg-pill  badge-light-warning'>" + data + "</span>";
            }
            return "<span class='badge bg-pill  badge-light-success'>" + data + "</span>";
          },
        },
        {
          targets: 4,
          // className: "text-center",
        },
        {
          targets: 5,
          // className: "text-center",
          render: function (data, type, row) {
            if (row.v_ruta == 'FUERA DE RUTA') {
              $("#div-01").html("");
              return "<span class='badge bg-pill  badge-light-warning'>" + data + "</span>";
            }
            $("#div-01").html("");
            return data;
          },
        },
        {
          targets: 6,
          // className: "text-center",
          render: function (data, type, row) {
            if (row.v_estado == 'PENDIENTE DE VALIDAR') {
              $("#div-01").html("");
              return "<span class='badge bg-pill  badge-light-warning'>" + data + "</span>";
            }
            return "<span class='badge bg-pill  badge-light-success'>" + data + "</span>";
          },
        },
        {
          targets: 7,
          render: function (data, type, row, meta) {
            if (type === 'display') {
              data = '<span class="dtr-data"><a href="https://verdum.com/verdum/registrocliente/index?variable1=' + encodeURIComponent(row.v_codliente) + '&variable2=' + encodeURIComponent(row.v_razonSocial) + '&variable3=' + encodeURIComponent(row.v_ruta) + '" class="btn btn-relief-primary btn-sm"><i class="fas fa-paper-plane"></i></a><span class="badge bg-pill  badge-light-success">Validar</span></span>'
            }
            return data;
          }
        },
      ],
      order: [[0, "asc"]],
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
