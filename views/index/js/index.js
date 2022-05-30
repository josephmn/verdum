$(function () {});

$("#btnlogin").on("click", function () {
  var correo = $("#correo").val();
  var clave = $("#clave").val();

  if (correo == null || correo == "") {
    Swal.fire({
      icon: "info",
      title: "No ha ingresado su correo...",
      timer: 2000,
    });
  } else if (clave == null || clave == "") {
    Swal.fire({
      icon: "info",
      title: "No ha ingresado su clave...",
      timer: 2000,
    });
  } else {
    $.ajax({
      type: "POST",
      url: "/verdum/index/login",
      data: { correo: correo, clave: clave },
      success: function (res) {
        switch (res.estado) {
          case 0:
            Swal.fire({
              icon: "info",
              title: "Usuario o contraseña incorrecta",
              // text: "Si no se acuerda su contraseña, favor de ir a recuperar mi contraseña..!!",
              text: "Debe ingresar los datos Correctos!!",
              timer: 4000,
              timerProgressBar: true,
              // showCancelButton: false,
              // showConfirmButton: false,
            });
            break;
          case 1:
            window.location = res.url;
            break;
          case 2:
            Swal.fire({
              icon: "error",
              title: "Correo no existe",
              text: "Si aún no se ha registrado, favor de crear una cuenta..!!",
              timer: 4000,
              timerProgressBar: true,
              // showCancelButton: false,
              // showConfirmButton: false,
            });
            break;
        }
      },
    });
  }
});

function pulsar(e) {
  if (e.keyCode === 13 && !e.shiftKey) {
    $("#btnlogin").click();
  }
}
