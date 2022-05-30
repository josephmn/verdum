$(function () {
  creardatatable("#example1", 15, 0, "desc"); //tabla.- index
  // CARGAR ARCHIVO IMAGEN
  $("#btnsubir").on("click", function () {
    var formData = new FormData();
    let documento = $("#documento")[0].files[0];
    let fecha = $("#fecha").val();
    formData.append("documento", documento);
    formData.append("fecha", fecha);


    var table = $('#example1').DataTable();
    var table_length = table.data().count();

    if (table_length > 0) {
      // $("#documento").focus();
      Swal.fire({
        icon: "error",
        title: "DATOS",
        text: "Para agregar datos, no debe tener informacion en la tabla!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }

    if (documento == "" || documento == null) {
      $("#documento").focus();
      Swal.fire({
        icon: "warning",
        title: "ARCHIVO",
        text: "Seleccione un archivo!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }
    $.ajax({
      url: "/verdum/canalcliente/cargar_datos",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
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
        // JSON.stringify(res);
        // console.log(res);
        // console.log(res);

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

      },
    });
  });



  $('#btnSistema').on('click', function () {
    var post = 0;
    var periodo = $('#periodo option:selected').val();    
    var table = $('#example1').DataTable();
    var table_length = table.data().count();

    if (table_length == 0 || table_length == null) {
      // $("#documento").focus();
      Swal.fire({
        icon: "error",
        title: "DATOS",
        text: "No existe datos para procesar!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }

    Swal.fire({
      title: "Estas seguro de procesar en el Sistema?",
      text: "Los cambios solo afectaran el periodo seleccionado!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#61C250",
      cancelButtonColor: "#ea5455",
      confirmButtonText: "Si, Procesar!", //<i class="fa fa-smile-wink"></i>
      cancelButtonText: "No", //<i class="fa fa-frown"></i>
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: 'POST',
          url: '/verdum/canalcliente/Actualizar_canal_cliente',
          data: {
            post: post,
            periodo: periodo,
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
            }, res.itimer);
          }
        });
      }
    });
  });


  $('#btnEliminar').on('click', function () {
    var post = 1;
    var periodo = "202203"
    var table = $('#example1').DataTable();
    var table_length = table.data().count();

    var table = $('#example1').DataTable();
    var table_length = table.data().count();
    if (table_length == 0 || table_length == null) {
      Swal.fire({
        icon: "error",
        title: "DATOS",
        text: "Para eliminar, debe tener informacion en la tabla!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }

    $.ajax({
      type: 'POST',
      url: '/verdum/canalcliente/Actualizar_canal_cliente',
      data: {
        post: post,
        periodo: periodo,
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
        }, res.itimer);
      }
    });


  });



});

// crear tabla
function creardatatable(nombretabla, row, orden, asde) {
  var tabla = $(nombretabla).dataTable({
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
    lengthMenu: [row],
    order: [[orden, asde]],
  });
  return tabla;
}


// padres menu
function navegacionmenu(string) {
  $.ajax({
    type: "POST",
    url: "/portaldistribuidor/dashboard/cambiarsession",
    data: { string: string },
  });
  var dato = ""; //cerrado
  $.ajax({
    type: "POST",
    url: "/portaldistribuidor/dashboard/cambiaropen",
    data: { string: dato },
  });
}

// hijos submenu
function clicksub(string) {
  $.ajax({
    type: "POST",
    url: "/portaldistribuidor/dashboard/cambiarsessionsub",
    data: { string: string },
  });
  var dato = "open"; //cerrado
  $.ajax({
    type: "POST",
    url: "/portaldistribuidor/dashboard/cambiaropen",
    data: { string: dato },
  });
}
