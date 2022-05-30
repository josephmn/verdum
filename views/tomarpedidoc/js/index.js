$(function () {
  //PARA CARGAR DATOS POR URL
  var queryString = window.location.search;
  var urlParams = new URLSearchParams(queryString);
  var variable1 = urlParams.get('variable1');
  var variable2 = urlParams.get('variable2');
  var variable3 = urlParams.get('variable3');


  function myFunc() {
    var now = new Date();
    var time = now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds();
    document.getElementById('hora').innerHTML = time;
  }
  myFunc();
  setInterval(myFunc, 1000);
  

  $('#cancelar').on('click', function () {
    var post = 0;
    var nu_correla = $('#nu_correla').val();
    var ch_codclien = $('#ch_codclien').val();


    Swal.fire({
      title: "Estas seguro de anular la venta?",
      text: "Esta acción no se puede deshacer!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#61C250",
      cancelButtonColor: "#ea5455",
      confirmButtonText: "Si, Anular!", //<i class="fa fa-smile-wink"></i>
      cancelButtonText: "No", //<i class="fa fa-frown"></i>
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "POST",
          url: "/verdum/tomarpedidoc/anular_venta",
          data: {
            post: post,
            nu_correla: nu_correla,
            ch_codclien: ch_codclien
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
              location.href = "https://verdum.com/verdum/tomarpedido/index";
            }, res.itimer);
          },
        });
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

