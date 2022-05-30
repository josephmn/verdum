$(function () {
  var contador = 0;
  var table2 = '';
  $("#tbruta").DataTable({
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
      [6, "asc"],
    ],
    lengthMenu: [
      [10, 25, 50, -1],
      ["10", "25", "50", "Todo"],
    ],
  });

  // ******************************************************************************PARA RECARAR CLIENTES EN RUTA//  PASO1 
  $('#enruta').on('click', function () {
    // if (contador > 0) {
    //   table2.destroy();
    //   contador = 0;
    // }
    var v_busqueda = '';
    var table1 = $("#tbruta").DataTable({
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
        url: "/verdum/tomarpedido/cargar_table_enruta",
        data: { v_busqueda: v_busqueda },

        beforeSend: function () {
          $("#div-01").html("");
          $("#div-01").append(
            "<div id='div-01'>\<div class='d-flex justify-content-center my-1'>\<div class='spinner-border text-danger' role='status' aria-hidden='true'></div>\</div>\ </div>"
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
            return "<span class='badge bg-pill  badge-light-danger'>" + data + "</span>";
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
            if (row.v_ruta == 'EN RUTA') {
              $("#div-01").html("");
              return "<span class='badge bg-pill  badge-light-success'>" + data + "</span>";
            }
            return data;
          },
        },
        {
          targets: 6,
          // className: "text-center",
          render: function (data, type, row) {
            return "<span class='badge bg-pill  badge-light-danger'>" + data + "</span>";
          },
        },
        {
          targets: 7,
          render: function (data, type, row, meta) {
            if (type === 'display') {
              data = '<span class="dtr-data"><a href="https://verdum.com/verdum/tomarpedidob/index?variable1=' + encodeURIComponent(row.v_codliente) + '&variable2=' + encodeURIComponent(row.v_razonSocial) + '&variable3=' + encodeURIComponent("EN RUTA") + '&variable4=' + encodeURIComponent(row.v_direccion) + '&variable5=' + encodeURIComponent(row.v_ruta) + '" class="btn btn-relief-primary btn-sm"><i class="fas fa-cart-plus"></i></a><span class="badge bg-pill  badge-light-success">Vender</span></span>'
            }
            return data;
          }
        },
      ],
      order: [[0, "asc"]],
    });
  });


  // ******************************************************************************PARA IR A CLIENTES FUERA DE RUTA** PASO2
  $('#fueraruta').on('click', function () {
    var v_busqueda = '*';
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
        url: "/verdum/tomarpedido/cargar_table_fuera_ruta",
        data: { v_busqueda: v_busqueda },
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
            return "<span class='badge bg-pill  badge-light-danger'>" + data + "</span>";
          },
        },
        {
          targets: 4,
          className: "text-center",
        },
        {
          targets: 5,
          className: "text-center",
          render: function (data, type, row) {
            if (row.v_ruta == 'FUERA DE RUTA') {
              return "<span class='badge bg-pill  badge-light-danger'>" + data + "</span>";
            }
            return data;
          },
        },
        {
          targets: 6,
          className: "text-center",
          render: function (data, type, row) {
            return "<span class='badge bg-pill  badge-light-danger'>" + data + "</span>";
          },
        },
        {
          targets: 7,
          render: function (data, type, row, meta) {
            if (type === 'display') {
              data = '<span class="dtr-data"><a href="https://verdum.com/verdum/tomarpedidob/index?variable1=' + encodeURIComponent(row.v_codliente) + '&variable2=' + encodeURIComponent(row.v_razonSocial) + '&variable3=' + encodeURIComponent("FUERA DE RUTA") + '&variable4=' + encodeURIComponent(row.v_direccion) + '&variable5=' + encodeURIComponent(row.v_ruta) + '" class="btn btn-relief-primary btn-sm"><i class="fas fa-cart-plus"></i></a><span class="badge bg-pill  badge-light-success">Vender</span></span>'
            }
            return data;
          }
        },
      ],
      order: [[0, "asc"]],
    });

    $("#div-01").html("");

  });
  // ******************************************************************************PARA BUSCAR CLIENTE FUERA DE RUTA

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
        url: "/verdum/tomarpedido/cargar_table_fuera_ruta",
        data: { v_busqueda: v_busqueda },

        beforeSend: function () {
          $("#div-01").html("");
          $("#div-01").append(
            "<div id='div-01'>\<div class='d-flex justify-content-center my-1'>\<div class='spinner-border text-danger' role='status' aria-hidden='true'></div>\</div>\ </div>"
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
            return "<span class='badge bg-pill  badge-light-danger'>" + data + "</span>";
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
            return "<span class='badge bg-pill  badge-light-danger'>" + data + "</span>";
          },
        },
        {
          targets: 7,
          render: function (data, type, row, meta) {
            if (type === 'display') {
              // data = '<span class="dtr-data"><a href="http://localhost:8080/verdum/tomarpedidob/index?variable1=' + encodeURIComponent(row.v_codliente) + '&variable2=' + encodeURIComponent(row.v_razonSocial) + '&variable3=' + encodeURIComponent(row.v_ruta) + '" class="btn btn-relief-primary btn-sm"><i class="fas fa-cart-plus"></i></a><span class="badge bg-pill  badge-light-success">Vender</span></span>'
              data = '<span class="dtr-data"><a href="https://verdum.com/verdum/tomarpedidob/index?variable1=' + encodeURIComponent(row.v_codliente) + '&variable2=' + encodeURIComponent(row.v_razonSocial) + '&variable3=' + encodeURIComponent("FUERA DE RUTA") + '&variable4=' + encodeURIComponent(row.v_direccion) + '&variable5=' + encodeURIComponent(row.v_ruta) + '" class="btn btn-relief-primary btn-sm"><i class="fas fa-cart-plus"></i></a><span class="badge bg-pill  badge-light-success">Vender</span></span>'
            }
            return data;
          }
        },
      ],
      order: [[0, "asc"]],
    });

  });




  $('#atendido').on('click', function () {
    var v_busqueda = ''
    var tbAtnd = $("#tbAtnd").DataTable({
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
        url: "/verdum/tomarpedido/cargar_table_atendidos",
        data: { v_busqueda: v_busqueda },
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
            "<button type='button' class='btn btn-relief-info btn-sm'><i class='fa fa-edit'></i></button>",
        },
      ],
      columnDefs: [
        {
          targets: 0,
          className: "text-center",
          render: function (data, type, row) {
            if (row.v_ruta == 'FUERA DE RUTA') {
              $("#div-01").html("");
              return "<span class='badge bg-pill  badge-light-warning'>" + data + "</span>";
            }
            return "<span class='badge bg-pill  badge-light-success'>" + data + "</span>";
          },
        },
        {
          targets: 5,
          // className: "text-center",
          render: function (data, type, row) {
            if (row.v_ruta == 'FUERA DE RUTA') {
              $("#div-01").html("");
              return "<span class='badge bg-pill  badge-light-danger'>" + data + "</span>";
            }
            return "<span class='badge bg-pill  badge-light-success'>" + data + "</span>";
          },
        },
        {
          targets: 6,
          // className: "text-center",
          render: function (data, type, row) {
            return "<span class='badge bg-pill  badge-light-success'>" + data + "</span>";
          },
        },
        {
          targets: 7,
          render: function (data, type, row, meta) {
            if (type === 'display') {
              data = '<span class="dtr-data"><a href="https://verdum.com/verdum/tomarpedidoc/index?variable1=' + encodeURIComponent(row.v_codliente) + '&variable2=' + encodeURIComponent(row.v_razonSocial) + '&variable3=' + encodeURIComponent(row.v_ruta) + '" class="btn btn-relief-info btn-sm"><i class="fa fa-edit"></i></a><span class="badge bg-pill  badge-light-success">Ver</span></span>'
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
