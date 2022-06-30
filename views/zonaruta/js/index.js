


$(function () {
  //PARA CARGAR DATOS POR URL
  var queryString = window.location.search;
  var urlParams = new URLSearchParams(queryString);
  var variable1 = urlParams.get('variable1');
  var variable2 = urlParams.get('variable2');
  var variable3 = urlParams.get('variable3');



  $(document).ready(function () {
    var groupColumn = 4;
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
      // dom: "Bfrtip",
      columnDefs: [{ visible: false, targets: 4 }],
      order: [[groupColumn, "asc"]],
      // buttons: [
      //   "excel",
      //   // "pdf", 
      //   "print",
      //   {
      //     extend: 'pdfHtml5',
      //     orientation: 'landscape',
      //     pageSize: 'LEGAL'
      //   }],
      displayLength: 25,


      "drawCallback": function (settings) {
        var api = this.api();
        var rows = api.rows({ page: 'current' }).nodes();
        var last = null;

        api.column(groupColumn, { page: 'current' }).data().each(function (group, i) {
          if (last !== group) {
            $(rows).eq(i).before(
              '<tr class="group"><td colspan="5"  bgcolor="8ECB54">   ' + group + '</td></tr>'
            );
            last = group;
          }
        });
      },



    });

    $('#example1 tbody').on('click', 'tr.group', function () {
      var currentOrder = table.order()[0];
      if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
        table.order([groupColumn, 'desc']).draw();
      }
      else {
        table.order([groupColumn, 'asc']).draw();
      }
    });

    // Order by the grouping
    // $("#example1 tbody").on("click", "tr.group", function () {
    //   var currentOrder = table.order()[0];
    //   if (currentOrder[0] === groupColumn && currentOrder[1] === "asc") {
    //     table.order([groupColumn, "desc"]).draw();
    //   } else {
    //     table.order([groupColumn, "asc"]).draw();
    //   }
    // });
  }); //tabla.- tabla index



  $(document).ready(function () {
    var groupColumn = 2;
    var table = $('#example').DataTable({
      "columnDefs": [
        { "visible": false, "targets": groupColumn }
      ],
      "order": [[groupColumn, 'asc']],
      "displayLength": 25,

      "drawCallback": function (settings) {
        var api = this.api();
        var rows = api.rows({ page: 'current' }).nodes();
        var last = null;

        api.column(groupColumn, { page: 'current' }).data().each(function (group, i) {
          if (last !== group) {
            $(rows).eq(i).before(
              '<tr class="group"><td colspan="5">' + group + '</td></tr>'
            );

            last = group;
          }
        });
      }

    });
    // Order by the grouping
    $('#example tbody').on('click', 'tr.group', function () {
      var currentOrder = table.order()[0];
      if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
        table.order([groupColumn, 'desc']).draw();
      }
      else {
        table.order([groupColumn, 'asc']).draw();
      }
    });
  });







  $(document).ready(function () {
    var groupColumn = 1;
    var table = $("#example2").DataTable({
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
      columnDefs: [{ visible: false, targets: 0 }],
      order: [[groupColumn, "asc"]],
      displayLength: 10,
      drawCallback: function (settings) {
        var api = this.api();
        var rows = api.rows({ page: "current" }).nodes();
        var last = null;

        api.column(0, groupColumn, { page: "current" }).data().each(function (group, i) {
          if (last !== group) {
            $(rows).eq(i).before(
              '<tr  class="group"><td colspan="10"  bgcolor="D8F0B2">' + group + "</td></tr>"
            );
            last = group;
          }
        });
      },
    });

    // Order by the grouping
    $("#example1 tbody").on("click", "tr.group", function () {
      var currentOrder = table.order()[0];
      if (currentOrder[0] === groupColumn && currentOrder[1] === "asc") {
        table.order([groupColumn, "desc"]).draw();
      } else {
        table.order([groupColumn, "asc"]).draw();
      }
    });
  }); //tabla.- tabla index



  // function myFunc() {
  //   var now = new Date();
  //   var time = now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds();
  //   document.getElementById('hora').innerHTML = time;
  // }
  // myFunc();
  // setInterval(myFunc, 1000);

  $('#cancelar').on('click', function () {
    var id = setInterval(function () {
      location.reload();
      clearInterval(id);
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

