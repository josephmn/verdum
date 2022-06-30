$(function () {

  //PARA CARGAR DATOS POR URL
  var queryString = window.location.search;
  var urlParams = new URLSearchParams(queryString);
  var variable1 = urlParams.get('variable1');
  var variable2 = urlParams.get('variable2');
  var variable3 = urlParams.get('variable3');
  var ImporteTotal = 0;



  $(document).ready(function () {
    var groupColumn = 14;
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
      columnDefs: [{ visible: false, targets: 14 }],
      order: [[groupColumn, "asc"]],
      displayLength: 25,

      "drawCallback": function (settings) {
        var api = this.api();
        var rows = api.rows({ page: 'current' }).nodes();
        var last = null;

        api.column(groupColumn, { page: 'current' }).data().each(function (group, i) {
          if (last !== group) {
            $(rows).eq(i).before(
              '<tr class="group"><td colspan="14"  bgcolor="8DC045">   ' + group + '</td></tr>'
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



  $("#xjefeventa").change(function () {
    var xjefeventa = $("#xjefeventa").val();


  });




  $("#example1 tbody").on("click", "a.revisar", function () {
    var idruta = $(this).attr("id");
    $('#xvendedoractual').val(variable1);
    $('#xzonaruta').val(idruta);

    $("#modal-cambio").modal("show");
  });


  $("#btnguardar").on("click", function () { //aqui ppto
    var post = 0;
    var codvendant = variable1;
    var codvendnew = $("#xjefeventa").val();
    var v_idzonaruta = $("#xzonaruta").val();
    var i_venta = document.getElementById("customSwitch10").checked;
    // var periodo = $("#xperiodo").val();
    var periodo = $('#xperiodo option:selected').text();

    var i_venta = Number(i_venta);


    if (codvendnew == variable1) {
      Swal.fire({
        title: "VENDEDOR NUEVO DEBE SER DIFERENTE AL ACTUAL",
        timer: 3000,
        timerProgressBar: true,
        showClass: {
          popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
          popup: 'animate__animated animate__fadeOutUp'
        }
      })
      return
    }

    Swal.fire({
      title: "Seguro de Guardar?",
      text: "Se procesara el cambio en el Sistema",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#61C250",
      cancelButtonColor: "#ea5455",
      confirmButtonText: "Si, Guardar!",
      cancelButtonText: "No",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: 'POST',
          url: '/verdum/zonaruta/registro_cambioruta',
          data: {
            post: post,
            codvendant: codvendant,
            codvendnew: codvendnew,
            v_idzonaruta: v_idzonaruta,
            i_venta: i_venta,
            periodo: periodo,
          },

          beforeSend: function () {
            $("#modal-insert").modal("show");
            var n = 0;
            var l = document.getElementById("number");
            window.setInterval(function () {
              l.innerHTML = n;
              n++;
            }, 2000);
          },

          success: function (res) {
            $("#modal-insert").modal("hide");
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
      }
    });



  });



  $("#btncancelar").on("click", function () { //aqui ppto
    $("#modal-cambio").modal('hide');
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

