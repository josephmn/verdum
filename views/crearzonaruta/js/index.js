$(function () {

  //PARA CARGAR DATOS POR URL
  var queryString = window.location.search;
  var urlParams = new URLSearchParams(queryString);
  var variable1 = urlParams.get('variable1');
  var variable2 = urlParams.get('variable2');
  var variable3 = urlParams.get('variable3');
  var ImporteTotal = 0;

  // function myFunc() {
  //   var now = new Date();
  //   var time = now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds();
  //   document.getElementById('hora').innerHTML = time;
  // }
  // myFunc();
  // setInterval(myFunc, 1000);

  $(document).ready(function () {
    var groupColumn = 1;
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
      columnDefs: [{ visible: false, targets: 1 }],
      order: [[groupColumn, "asc"]],
      displayLength: 10,
      drawCallback: function (settings) {
        var api = this.api();
        var rows = api.rows({ page: "current" }).nodes();
        var last = null;

        api.column(1, groupColumn, { page: "current" }).data().each(function (group, i) {
          if (last !== group) {
            $(rows).eq(i).before(
              '<tr  class="group"><td colspan="5"  bgcolor="D8F0B2">' + group + "</td></tr>"
            );
            last = group;
          }
        });
      },
    });

    // // Order by the grouping
    // $("#example1 tbody").on("click", "tr.group", function () {
    //   var currentOrder = table.order()[0];
    //   if (currentOrder[0] === groupColumn && currentOrder[1] === "asc") {
    //     table.order([groupColumn, "desc"]).draw();
    //   } else {
    //     table.order([groupColumn, "asc"]).draw();
    //   }
    // });
  }); //tabla.- tabla index



  document.getElementById("codvend").value = variable1
  document.getElementById("nombre").value = variable2.replace("&Ntilde;", "Ñ");

  $('#codvend,#nombre').attr('readonly', true);

  $('#asignar').on('click', function () {
    var post = 0;
    var departamento = $('#departamento option:selected').val();
    if (departamento == 0) {
      $("#departamento").focus();
      Swal.fire({
        icon: "warning",
        title: "SELECCIONE DEPARTAMENTO",
        text: "Seleccione un departamento de la lista!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }
    var codvend = $('#codvend').val();
 
 


    $.ajax({
      type: 'POST',
      url: '/verdum/crearzonaruta/guardar_zonaruta_vendedor',
      data: {
        post: post,
        departamento: departamento,
        variable3: variable3,
        codvend: codvend
      },

      beforeSend: function () {
        $("#div-00").html("");
        $("#div-00").append(
          "<div id='div-00'>\<div class='d-flex justify-content-center my-1'>\<div class='spinner-border text-success' role='status' aria-hidden='true'></div>\</div>\ </div>"
        );
      },
      success: function (res) {
        $("#div-00").html("");
        if (variable1 != null) {
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
        } else {
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

