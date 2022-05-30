$(function () {

  var queryString = window.location.search;
  var urlParams = new URLSearchParams(queryString);
  var variable1 = urlParams.get('variable1');
  var variable3 = urlParams.get('variable3');


  document.getElementById("loadsunat").disabled = true;

  //BEGIN_************************************************************************************************** Validamos a clientes que no esten validados en sunat*/
  if (variable1 != null) {

    $('#precio').val(variable1);
    var numero = $(this).val();
    var tipo = document.getElementById("tipodocumento").value;
    $('#documento').val(variable1);
    var CustId = $('#documento').val();

    if (CustId != '') {
      $.ajax({
        type: 'POST',
        url: '/verdum/registrocliente/validar_cliente',
        data: { CustId: CustId },

        beforeSend: function () {
          $("#div-01").html("");
          $("#div-01").append(
            "<div id='div-01'>\<div class='d-flex justify-content-center my-1'>\<div class='spinner-border text-success' role='status' aria-hidden='true'></div>\</div>\ </div>"
          );
        },
        success: function (res) {
          $("#div-01").html("");
          switch (res.estado) {
            case 0:
              Swal.fire(
                'Cliente ya registrado en Solomon!' + ' ' + res.v_Name,
                'Ya existe en nuestro Sistema!',
                'success'
              )

              document.getElementById("loadsunat").disabled = false;

              $('#documento').attr('readonly', true);
              $('#nomcliente').val(res.v_Name.replace("&Ntilde;", "Ñ"));
              $('#nomcliente').attr('readonly', true);
              // $("#tipodocumento").html("");
              // $("#tipodocumento").append(res.tipodocu);

              $('#mail').val(res.v_correo.replace("&Ntilde;", "Ñ"));

              var tipodocu = res.tipodocu;
              $.ajax({
                type: "POST",
                url: "/verdum/registrocliente/validar_tipo_documento",
                data: { tipodocu: tipodocu },
                success: function (res) {
                  $("#tipodocumento").html("");
                  $("#tipodocumento").append(res.tipodoc);
                  document.getElementById("tipodocumento").disabled = false;
                },
              });

              // document.getElementById("tipodocumento").disabled = true;

              var v_ClassId = res.tipocl;
              $.ajax({
                type: "POST",
                url: "/verdum/registrocliente/validar_tipocliente",
                data: { v_ClassId: v_ClassId },
                success: function (res) {
                  $("#tipocliente").html("");
                  $("#tipocliente").append(res.tipocli);
                  document.getElementById("tipocliente").disabled = false;
                },
              });

              var tipocli = res.tipocl;
              var subtipocli = res.subtplc;
              $.ajax({
                type: "POST",
                url: "/verdum/registrocliente/validar_subtipocliente",
                data: { tipocli: tipocli, subtipocli: subtipocli },
                success: function (res) {
                  $("#subtipocliente").html("");
                  $("#subtipocliente").append(res.subtipocli);
                  document.getElementById("subtipocliente").disabled = false;
                },
              });

              var v_Price = res.pricecl;
              $.ajax({
                type: "POST",
                url: "/verdum/registrocliente/validar_listaprecio",
                data: { v_Price: v_Price },
                success: function (res) {
                  $("#listaprecio").html("");
                  $("#listaprecio").append(res.clase);
                  document.getElementById("listaprecio").disabled = false;
                },
              });

              $("#condicionpago").html("");
              $("#condicionpago").append(res.terms);
              document.getElementById("condicionpago").disabled = true;


              var v_termsid = res.terms;
              $.ajax({
                type: "POST",
                url: "/verdum/registrocliente/validar_condicion_pago",
                data: { v_termsid: v_termsid },
                success: function (res) {
                  $("#condicionpago").html("");
                  $("#condicionpago").append(res.condicion);
                  document.getElementById("condicionpago").disabled = false;
                },
              });

              $('#direccion').val(res.direc.replace("&Ntilde;", "Ñ"));
              $('#referencia').val(res.ref.replace("&Ntilde;", "Ñ"));

              //*********************************************************************************************** */
              document.getElementById("estadocliente").disabled = true;
              var estado = res.v_baja;
              var post = 2;
              var v_id = res.v_estado;
              $.ajax({
                type: "POST",
                url: "/verdum/registrocliente/validar_estado_cliente",
                data: { post: post, v_id: v_id },
                success: function (res) {
                  $("#estadocliente").html(""); // yodo
                  $("#estadocliente").append(res.estado);
                  if ((estado == "CLIENTE DE BAJA")) {
                    document.getElementById("estadocliente").disabled = true;
                    $("#Note").html("");
                    $("#Note").append(
                      "<div id='Note' class='alert alert-danger' role='alert'>\<div class='alert-body'>\<strong>INFO: Cliente fue dado de baja, no se puede hacer cambios</strong>\</div>\</div>"
                    );
                    document.getElementById("procesar").disabled = true;
                    document.getElementById("btnbaja").disabled = true;
                  } else {
                    document.getElementById("estadocliente").disabled = false;
                    document.getElementById("procesar").disabled = false;
                    document.getElementById("btnbaja").disabled = false;
                  }
                },
              });


              var zonaruta = res.ruta
              $.ajax({
                type: "POST",
                url: "/verdum/registrocliente/validar_zonaruta",
                data: { zonaruta: zonaruta },
                success: function (res) {
                  $("#zonaruta").html(""); //Listar Zona de ruta
                  $("#zonaruta").append(res.rutas);
                },
              });

              var departamento = res.depa;
              var prov = res.prov;
              var i_iddis = res.distrito;

              $.ajax({
                type: "POST",
                url: "/verdum/registrocliente/validar_departamento_cliente",
                data: { departamento: departamento },
                success: function (res) {
                  $("#departamento").html(""); //Listar Zona de ruta
                  $("#departamento").append(res.depa);
                  document.getElementById("departamento").disabled = false;
                },
              });

              $.ajax({
                type: "POST",
                url: "/verdum/registrocliente/cargar_provincia",
                data: { departamento: departamento, prov: prov },
                success: function (res) {
                  $("#provincia").html("");
                  $("#provincia").append(res.data);
                  document.getElementById("provincia").disabled = false;
                },
              });
              $.ajax({
                type: "POST",
                url: "/verdum/registrocliente/cargar_distritos",
                data: { prov: prov, i_iddis: i_iddis },
                success: function (res) {
                  $("#distrito").html("");
                  $("#distrito").append(res.data);
                  document.getElementById("distrito").disabled = false;
                },
              });
              valsunat = res.valsunat;
              regcli = 1;
              //*********************************************************************************************** */
              $('#vendacargo').val(res.vend.replace("&Ntilde;", "Ñ"));
              $('#vendacargo').attr('readonly', true);

              $("#pais").html("");
              $("#pais").append(res.pais);
              document.getElementById("pais").disabled = true;

              $("#departamento").html("");
              $("#departamento").append(res.depa);
              document.getElementById("departamento").disabled = true;

              $("#provincia").html("");
              $("#provincia").append(res.prov);
              document.getElementById("provincia").disabled = true;

              $("#distrito").html("");
              $("#distrito").append(res.distrito);
              document.getElementById("distrito").disabled = true;

              break;
            case 1:
              valsunat = 0;
              regcli = 0;
              document.getElementById("loadsunat").disabled = false;
              break;
          }
        }
      });
    }
  }
  //END_**************************************************************************************************************************************************************************/

  var valsunat = 0;
  var regcli = 0;

  // PARA LISTAR LAS PROVINCIAS
  $("#departamento").change(function () {
    var departamento = $("#departamento").val();
    var prov = 0
    $.ajax({
      type: "POST",
      url: "/verdum/registrocliente/cargar_provincia",
      data: { departamento: departamento, prov: prov },
      success: function (res) {
        $("#provincia").html("");
        $("#provincia").append(res.data);
      },
    });

    var provincia = 0;
    var i_iddis = 0;
    $.ajax({
      type: "POST",
      url: "/verdum/registrocliente/cargar_distritos",
      data: { provincia: provincia, i_iddis: i_iddis },
      success: function (res) {
        $("#distrito").html("");
        $("#distrito").append(res.data);
      },
    });
  });

  // LIMPIAR CAMPO DOCUMENTO CUANDO ELIJAN TIPO
  $("#tipodocumento").change(function () {
    if (regcli == 0) {
      document.getElementById("documento").value = "";
      document.getElementById("nomcliente").value = "";
    } else {

      var numero = document.getElementById("documento").value;
      var tipo = document.getElementById("tipodocumento").value;
      if ((tipo == 'M')) {
        if ((numero.length != 11)) {
          $("#tipodocumento").focus();
          Swal.fire({
            icon: 'error',
            title: 'Ruc debe tener 11 digitos',
            text: 'Formato no Valido!',
          })
          return;
        }
      }
      if ((tipo == 'V')) {
        $("#tipodocumento").focus();
        if ((numero.length != 8)) {
          Swal.fire({
            icon: 'error',
            title: 'Dni debe tener 8 digitos',
            text: 'Formato no Valido!',
          })
          return;
        }
      }

    }
  });

  // PARA LISTAR LOS DISTRITOS
  $("#provincia").change(function () {
    var prov = $("#provincia").val();
    var i_iddis = 0;

    $.ajax({
      type: "POST",
      url: "/verdum/registrocliente/cargar_distritos",
      data: { prov: prov, i_iddis: i_iddis },
      success: function (res) {
        $("#distrito").html("");
        $("#distrito").append(res.data);
      },
    });
  });

  // PARA LISTAR VENDEDOR A CARGO
  $("#zonaruta").change(function () {
    var ZonaRutaID = $('#zonaruta option:selected').val();
    $.ajax({
      type: "POST",
      url: "/verdum/registrocliente/cargar_vendedor",
      data: { ZonaRutaID: ZonaRutaID },
      success: function (res) {
        $("#vendacargo").val(res.v_namevend);
      },
    });
  });

  // PARA LISTAR SUB TIPO DE CLIENTE
  $("#tipocliente").change(function () {
    var tipocliente = $("#tipocliente").val();
    $.ajax({
      type: "POST",
      url: "/verdum/registrocliente/cargar_subtipocliente",
      data: { tipocliente: tipocliente },
      success: function (res) {
        $("#subtipocliente").html("");
        $("#subtipocliente").append(res.data);
      },
    });
  });

  $('#vendacargo').attr('readonly', true);
  $('#nomcliente').attr('readonly', true);

  document.getElementById("estadocliente").disabled = true;


  //BEGIN_************************************************************************************************** Change Validamos a clientes que no esten validados en sunat*/
  $("#documento").change(function () {
    var numero = $(this).val();
    var tipo = document.getElementById("tipodocumento").value;
    if ((tipo == 'M')) {
      if ((numero.length != 11)) {
        Swal.fire({
          icon: 'error',
          title: 'Ruc debe tener 11 digitos',
          text: 'Formato no Valido!',
        })
        document.getElementById("documento").value = "";
        document.getElementById("documento").focus();
      }
    }
    if ((tipo == 'V')) {
      if ((numero.length != 8)) {
        Swal.fire({
          icon: 'error',
          title: 'Dni debe tener 8 digitos',
          text: 'Formato no Valido!',
        })
        document.getElementById("documento").value = "";
        document.getElementById("documento").focus();
      }
    }
    var CustId = $('#documento').val();
    if (CustId != '') {
      $.ajax({
        type: 'POST',
        url: '/verdum/registrocliente/validar_cliente',
        data: { CustId: CustId },

        beforeSend: function () {
          $("#div-01").html("");
          $("#div-01").append(
            "<div id='div-01'>\<div class='d-flex justify-content-center my-1'>\<div class='spinner-border text-success' role='status' aria-hidden='true'></div>\</div>\ </div>"
          );
        },
        success: function (res) {
          $("#div-01").html("");
          switch (res.estado) {
            case 0:
              Swal.fire(
                'Cliente ya registrado en Solomon!' + ' ' + res.v_Name,
                'Ya existe en nuestro Sistema!',
                'success'
              )



              document.getElementById("loadsunat").disabled = false;
              $('#documento').attr('readonly', true);



              $('#nomcliente').val(res.v_Name.replace("&Ntilde;", "Ñ"));
              $('#nomcliente').attr('readonly', true);



              $('#mail').val(res.v_correo.replace("&Ntilde;", "Ñ"));


              // $("#tipodocumento").html("");
              // $("#tipodocumento").append(res.tipodocu);
              // document.getElementById("tipodocumento").disabled = true;

              var tipodocu = res.tipodocu;
              $.ajax({
                type: "POST",
                url: "/verdum/registrocliente/validar_tipo_documento",
                data: { tipodocu: tipodocu },
                success: function (res) {
                  $("#tipodocumento").html("");
                  $("#tipodocumento").append(res.tipodoc);
                  document.getElementById("tipodocumento").disabled = false;
                },
              });


              var v_ClassId = res.tipocl;
              $.ajax({
                type: "POST",
                url: "/verdum/registrocliente/validar_tipocliente",
                data: { v_ClassId: v_ClassId },
                success: function (res) {
                  $("#tipocliente").html("");
                  $("#tipocliente").append(res.tipocli);
                  document.getElementById("tipocliente").disabled = false;
                },
              });
              var tipocli = res.tipocl;
              var subtipocli = res.subtplc;
              $.ajax({
                type: "POST",
                url: "/verdum/registrocliente/validar_subtipocliente",
                data: { tipocli: tipocli, subtipocli: subtipocli },
                success: function (res) {
                  $("#subtipocliente").html("");
                  $("#subtipocliente").append(res.subtipocli);
                  document.getElementById("subtipocliente").disabled = false;
                },
              });

              var v_Price = res.pricecl;
              $.ajax({
                type: "POST",
                url: "/verdum/registrocliente/validar_listaprecio",
                data: { v_Price: v_Price },
                success: function (res) {
                  $("#listaprecio").html(""); //Listar Zona de ruta
                  $("#listaprecio").append(res.clase);
                  document.getElementById("listaprecio").disabled = false;
                },
              });

              // $("#condicionpago").html("");
              // $("#condicionpago").append(res.terms);
              document.getElementById("condicionpago").disabled = true;


              var v_termsid = res.terms;
              $.ajax({
                type: "POST",
                url: "/verdum/registrocliente/validar_condicion_pago",
                data: { v_termsid: v_termsid },
                success: function (res) {
                  $("#condicionpago").html("");
                  $("#condicionpago").append(res.condicion);
                  document.getElementById("condicionpago").disabled = false;
                },
              });




              $('#direccion').val(res.direc.replace("&Ntilde;", "Ñ"));
              $('#referencia').val(res.ref.replace("&Ntilde;", "Ñ"));

              //*********************************************************************************************** */
              document.getElementById("estadocliente").disabled = true;
              var estado = res.v_baja;
              var post = 2;
              var v_id = res.v_estado;
              $.ajax({
                type: "POST",
                url: "/verdum/registrocliente/validar_estado_cliente",
                data: { post: post, v_id: v_id },
                success: function (res) {
                  $("#estadocliente").html(""); //yodo1
                  $("#estadocliente").append(res.estado);
                  if ((estado == "CLIENTE DE BAJA")) {
                    document.getElementById("estadocliente").disabled = true;
                    $("#Note").html("");
                    $("#Note").append(
                      "<div id='Note' class='alert alert-warning' role='alert'>\<div class='alert-body'>\<strong>INFO: Cliente fue dado de baja, no se puede hacer cambios</strong>\</div>\</div>"
                    );
                    document.getElementById("procesar").disabled = true;
                    document.getElementById("btnbaja").disabled = true;

                  } else {
                    document.getElementById("estadocliente").disabled = false;
                    document.getElementById("procesar").disabled = false;
                    document.getElementById("btnbaja").disabled = false;
                  }
                },
              });


              var zonaruta = res.ruta
              $.ajax({
                type: "POST",
                url: "/verdum/registrocliente/validar_zonaruta",
                data: { zonaruta: zonaruta },
                success: function (res) {
                  $("#zonaruta").html(""); //Listar Zona de ruta
                  $("#zonaruta").append(res.rutas);
                },
              });

              var departamento = res.depa;
              var prov = res.prov;
              var i_iddis = res.distrito;

              $.ajax({
                type: "POST",
                url: "/verdum/registrocliente/validar_departamento_cliente",
                data: { departamento: departamento },
                success: function (res) {
                  $("#departamento").html(""); //Listar Zona de ruta
                  $("#departamento").append(res.depa);
                  document.getElementById("departamento").disabled = false;
                },
              });

              $.ajax({
                type: "POST",
                url: "/verdum/registrocliente/cargar_provincia",
                data: { departamento: departamento, prov: prov },
                success: function (res) {
                  $("#provincia").html("");
                  $("#provincia").append(res.data);
                  document.getElementById("provincia").disabled = false;
                },
              });

              $.ajax({
                type: "POST",
                url: "/verdum/registrocliente/cargar_distritos",
                data: { prov: prov, i_iddis: i_iddis },
                success: function (res) {
                  $("#distrito").html("");
                  $("#distrito").append(res.data);
                  document.getElementById("distrito").disabled = false;
                },
              });

              valsunat = res.valsunat;
              regcli = 1;

              //*********************************************************************************************** */
              $('#vendacargo').val(res.vend.replace("&Ntilde;", "Ñ"));
              $('#vendacargo').attr('readonly', true);

              $("#pais").html("");
              $("#pais").append(res.pais);
              document.getElementById("pais").disabled = true;

              $("#departamento").html("");
              $("#departamento").append(res.depa);
              document.getElementById("departamento").disabled = true;

              $("#provincia").html("");
              $("#provincia").append(res.prov);
              document.getElementById("provincia").disabled = true;

              $("#distrito").html("");
              $("#distrito").append(res.distrito);
              document.getElementById("distrito").disabled = true;

              break;
            case 1:
              valsunat = 0;
              regcli = 0;
              document.getElementById("loadsunat").disabled = false;
              break;
          }
        }
      });
    }
  });
  //END_************************************************************************************************** Change Validamos a clientes que no esten validados en sunat*/

  $('#loadsunat').on('click', function () {
    var tipo = document.getElementById("tipodocumento").value;
    var CustId = $('#documento').val();
    var i = 0;
    var result = 0;

    var tipodocumento = $('#tipodocumento').val();

    if (tipodocumento == "X" || tipodocumento == null) {
      $("#tipodocumento").focus();
      Swal.fire({
        icon: "warning",
        title: "SELECCIONE TIPO DE DOCUMENTO",
        // text: "Favor de completar el campo!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }

    var numero = document.getElementById("documento").value;
    var tipo = document.getElementById("tipodocumento").value;
    if ((tipo == 'M')) {
      if ((numero.length != 11)) {
        $("#tipodocumento").focus();
        Swal.fire({
          icon: 'error',
          title: 'Ruc debe tener 11 digitos',
          text: 'Formato no Valido!',
        })
        return;
      }
    }
    if ((tipo == 'V')) {
      $("#tipodocumento").focus();
      if ((numero.length != 8)) {
        Swal.fire({
          icon: 'error',
          title: 'Dni debe tener 8 digitos',
          text: 'Formato no Valido!',
        })
        return;
      }
    }


    if (CustId == "" || CustId == null) {
      $("#documento").focus();
      Swal.fire({
        icon: "warning",
        title: "Ingresar documento cliente",
        text: "Favor de completar el campo!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }

    if (i == 0) {
      i = 1;
      var elem = document.getElementById("myBar");
      var width = 1;
      var id = setInterval(frame1, 20);
      function frame1() {
        if (width >= 50) {
          clearInterval(id);
          i = 0;

          if (CustId != '' && tipo == 'M') {
            $.ajax({
              type: 'POST',
              url: '/verdum/registrocliente/consulta_ruc_sunat',
              data: { CustId: CustId },
              success: function (res) {
                switch (res.respuestasunatruc) {
                  case 0:
                    var id2 = setInterval(frame2, 20);
                    width = 50
                    function frame2() {
                      if (width >= 100) {
                        elem.innerHTML = width + "%..completo";
                        clearInterval(id2);
                        i = 0;

                        if (res.estado_del_contribuyente == 'ACTIVO') {
                          Swal.fire(
                            'Ruc validado correctamente en Sunat!' + ' ' + res.nombre_o_razon_social,
                            'Hacer clic en el botón!',
                            'success'
                          )

                          $('#documento').attr('readonly', true);
                          $("#nomcliente").val(res.nombre_o_razon_social);


                          var dir = $('#direccion').val().toUpperCase();
                          // alert(res.direccion_ruc);
                          // alert(dir);
                          if (dir == "") {
                            $("#direccion").val(res.direccion_ruc);
                          }


                          valsunat = 1
                        } else {
                          Swal.fire(
                            'Ruc se encuentra de baja!' + ' ' + res.nombre_o_razon_social,
                            'Hacer clic en el botón!',
                            'warning'
                          )
                          document.getElementById("nomcliente").value = "";
                          document.getElementById("direccion").value = "";
                          valsunat = 0
                        }
                      } else {
                        width++;
                        elem.style.width = width + "%";
                        elem.innerHTML = width + "%..validando";
                      }
                    }
                    break;
                  case 1:
                    var id3 = setInterval(frame3, 20);
                    width = 50
                    function frame3() {
                      if (width >= 100) {
                        elem.innerHTML = width + "%..completo";
                        clearInterval(id3);
                        i = 0;
                        Swal.fire(
                          'Ruc no existe en Sunat!',
                          'Hacer clic en el botón!',
                          'warning'
                        )
                        document.getElementById("nomcliente").value = "";
                        document.getElementById("direccion").value = "";
                      } else {
                        width++;
                        elem.style.width = width + "%";
                        elem.innerHTML = width + "%..validando";
                      }
                    }
                    break;
                }
              }
            });
          }

          if (CustId != '' && tipo == 'V') {
            $.ajax({
              type: 'POST',
              url: '/verdum/registrocliente/consulta_dni_sunat',
              data: { CustId: CustId },

              // beforeSend: function () {
              //   $("#div-01").html("");
              //   $("#div-01").append(
              //     "<div id='div-01'>\<div class='d-flex justify-content-center my-1'>\<div class='spinner-border text-success' role='status' aria-hidden='true'></div>\</div>\ </div>"
              //   );
              // },

              success: function (res) {
                $("#div-01").html("");
                switch (res.respuestasunatdni) {
                  case 0:
                    var id4 = setInterval(frame4, 20);
                    width = 50
                    function frame4() {
                      if (width >= 100) {
                        elem.innerHTML = width + "%..completo";
                        clearInterval(id4);
                        i = 0;


                        Swal.fire(
                          'Dni validado correctamente en Sunat!' + ' ' + res.nombre_completo,
                          'Hacer clic en el botón!',
                          'success'
                        )

                        $('#documento').attr('readonly', true);
                        $("#nomcliente").val(res.nombre_completo);
                        valsunat = 1;
                      } else {
                        width++;
                        elem.style.width = width + "%";
                        elem.innerHTML = width + "%..validando";
                      }
                    }
                    break;
                  case 1:
                    var id5 = setInterval(frame5, 20);
                    width = 50
                    function frame5() {
                      if (width >= 100) {
                        elem.innerHTML = width + "%..completo";
                        clearInterval(id5);
                        i = 0;
                        Swal.fire(
                          'Dni no existe en Sunat!',
                          'Hacer clic en el botón!',
                          'warning'
                        )
                        valsunat = 0;
                        document.getElementById("nomcliente").value = "";

                      } else {
                        width++;
                        elem.style.width = width + "%";
                        elem.innerHTML = width + "%..validando";
                      }
                    }
                    break;
                }
              }
            });
          }
        } else {
          width++;
          elem.style.width = width + "%";
          elem.innerHTML = width + "%..validando";
        }
      }
    }
  });


  // Botones de Acciones
  $('#cancelar').on('click', function () {

    if (variable1 != null) {
      if (variable3 == "SUNAT") {
        location.reload();
        clearInterval(id);
        location.href = "https://verdum.com/verdum/listadeclientes/index";
      } else {
        location.reload();
        clearInterval(id);
        location.href = "https://verdum.com/verdum/tomarpedido/index";
      }
    } else {
      var id = setInterval(function () {
        location.reload();
        clearInterval(id);
      }, 100);

    }

  });


  $('#procesar').on('click', function () {
    var direccion1 = $('#direccion').val().toUpperCase();
    var direccion2 = '';
    var distrito = $('#distrito option:selected').val();
    var pais = $('#pais').val().toUpperCase();
    var nomcliente = $('#nomcliente').val().toUpperCase();
    var documento = $('#documento').val();
    var departamento = $('#departamento').val();
    var zonaruta = $('#zonaruta option:selected').val();
    var tipodocumento = $('#tipodocumento').val();
    var tipocliente = $('#tipocliente').val();
    var subtipocliente = $('#subtipocliente').val();
    var listaprecio = $('#listaprecio').val();
    var referencia = $('#referencia').val();
    var condicionpago = $('#condicionpago').val();
    var provincia = $('#provincia').val();
    var locationweb = '';
    var estadocliente = $('#estadocliente').val();
    var mail = $('#mail').val();

    if (tipodocumento == "X" || tipodocumento == null) {
      $("#tipodocumento").focus();
      Swal.fire({
        icon: "warning",
        title: "SELECCIONE TIPO DE DOCUMENTO",
        // text: "Favor de completar el campo!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }

    if ((tipodocumento == 'M')) {
      if ((documento.length != 11)) {
        $("#tipodocumento").focus();
        Swal.fire({
          icon: 'error',
          title: 'Ruc debe tener 11 digitos',
          text: 'Formato no Valido!',
        })
        return;
      }
    }
    if ((tipodocumento == 'V')) {
      $("#tipodocumento").focus();
      if ((documento.length != 8)) {
        Swal.fire({
          icon: 'error',
          title: 'Dni debe tener 8 digitos',
          text: 'Formato no Valido!',
        })
        return;
      }
    }


    if (documento == "" || documento == null) {
      $("#documento").focus();
      Swal.fire({
        icon: "info",
        title: "INGRESAR DOCUMENTO DEL CLIENTE",
        text: "Favor de completar el campo!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }

    if (nomcliente == "" || nomcliente == null) {
      $("#nomcliente").focus();
      Swal.fire({
        icon: "info",
        title: "INGRESAR NOMBRE DEL CLIENTE",
        text: "Favor de completar el campo!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }

    if (tipocliente == "X") {
      $("#tipocliente").focus();
      Swal.fire({
        icon: "info",
        title: "Selecion incorrecta",
        text: "Seleccionar un tipo de cliente del listado!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }

    if (subtipocliente == "00") {
      $("#subtipocliente").focus();
      Swal.fire({
        icon: "info",
        title: "Selecion incorrecta",
        text: "Seleccionar un subtipo de cliente en la lista!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }


    if (listaprecio == "X") {
      $("#listaprecio").focus();
      Swal.fire({
        icon: "info",
        title: "Selecion incorrecta",
        text: "Seleccionar una lista de preciodel listado!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }
    if (condicionpago == "X") {
      $("#condicionpago").focus();
      Swal.fire({
        icon: "info",
        title: "Selecion incorrecta",
        text: "Seleccionar condicion de pago del listado!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }
    if (nomcliente == "" || nomcliente == null) {
      $("#nomcliente").focus();
      Swal.fire({
        icon: "info",
        title: "Ingresar nombre del Cliente",
        text: "Favor de completar el campo!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }

    if (direccion1 == "" || direccion1 == null) {
      $("#direccion").focus();
      Swal.fire({
        icon: "info",
        title: "Ingresar direccion",
        text: "Favor de completar el campo!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }

    if (zonaruta == "X") {
      $("#zonaruta").focus();
      Swal.fire({
        icon: "info",
        title: "Selecion incorrecta",
        text: "Seleccionar zona de ruta del listado!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }
    if (pais == 0) {
      $("#pais").focus();
      Swal.fire({
        icon: "info",
        title: "Selecion incorrecta",
        text: "Seleccionar un pais de la lista!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }
    if (departamento == 0) {
      $("#departamento").focus();
      Swal.fire({
        icon: "info",
        title: "Selecion incorrecta",
        text: "Seleccionar un departamento de la lista!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }
    if (provincia == 0) {
      $("#provincia").focus();
      Swal.fire({
        icon: "info",
        title: "Selecion incorrecta",
        text: "Seleccionar una provincia de la lista!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }

    if (distrito == 0) {
      $("#distrito").focus();
      Swal.fire({
        icon: "info",
        title: "Selecion incorrecta",
        text: "Seleccionar un distrito de la lista!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }

    if (regcli == 1) {
      if (valsunat == 0) {
        $("#loadsunat").focus();
        Swal.fire({
          icon: "warning",
          title: "DATOS CLIENTE",
          text: "Cliente no esta validado en Sunat.Puede hacerlo desde el boton (ValidaSunat)!",
          timer: 5000,
          timerProgressBar: true,
        });
        return;
      }
    }


    if (mail == "" || mail == null) {

      Swal.fire({
        title: "Campo correo esta vacio, Esta seguro de Guardar?",
        text: "El correo sirve para el envio del pdf de la venta al cliente!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#61C250",
        cancelButtonColor: "#ea5455",
        confirmButtonText: "Si, guardar!", //<i class="fa fa-smile-wink"></i>
        cancelButtonText: "No", //<i class="fa fa-frown"></i>
      }).then((result) => {
        if (result.isConfirmed) {

          $.ajax({
            type: 'POST',
            url: '/verdum/registrocliente/guardarcliente',
            data: {
              direccion1: direccion1,
              direccion2: direccion2,
              distrito: distrito,
              pais: pais,
              nomcliente: nomcliente,
              documento: documento,
              departamento: departamento,
              zonaruta: zonaruta,
              tipodocumento: tipodocumento,
              tipocliente: tipocliente,
              subtipocliente: subtipocliente,
              listaprecio: listaprecio,
              referencia: referencia,
              condicionpago: condicionpago,
              provincia: provincia,
              locationweb: locationweb,
              valsunat: valsunat,
              estadocliente: estadocliente,
              mail: mail
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
                  if (variable3 == "SUNAT") {
                    location.reload();
                    clearInterval(id);
                    location.href = "https://verdum.com/verdum/listadeclientes/index";
                  } else {
                    location.reload();
                    clearInterval(id);
                    location.href = "https://verdum.com/verdum/tomarpedido/index";
                  }
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

        }
      });

    } else {
      $.ajax({
        type: 'POST',
        url: '/verdum/registrocliente/guardarcliente',
        data: {
          direccion1: direccion1,
          direccion2: direccion2,
          distrito: distrito,
          pais: pais,
          nomcliente: nomcliente,
          documento: documento,
          departamento: departamento,
          zonaruta: zonaruta,
          tipodocumento: tipodocumento,
          tipocliente: tipocliente,
          subtipocliente: subtipocliente,
          listaprecio: listaprecio,
          referencia: referencia,
          condicionpago: condicionpago,
          provincia: provincia,
          locationweb: locationweb,
          valsunat: valsunat,
          estadocliente: estadocliente,
          mail: mail
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
              if (variable3 == "SUNAT") {
                location.reload();
                clearInterval(id);
                location.href = "https://verdum.com/verdum/listadeclientes/index";
              } else {
                location.reload();
                clearInterval(id);
                location.href = "https://verdum.com/verdum/tomarpedido/index";
              }
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

    }










  });

  // raa
  $("#btnbaja").on("click", function () {
    var documento = $('#documento').val();
    var estadocliente = $('#estadocliente').val();

    if (documento == "" || documento == null) {
      $("#documento").focus();
      Swal.fire({
        icon: "warning",
        title: "Ingresar documento del Cliente",
        text: "Favor de completar el campo!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }

    if (estadocliente == "A") {
      $("#estadocliente").focus();
      Swal.fire({
        icon: "warning",
        title: "Estado debe estar (Inactivo)",
        text: "Favor de completar el campo!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }
    $("#myModal").modal("show");
  });


  $('#btnsibaja').on('click', function () {
    var post = 0;
    var documento = $('#documento').val();
    var estadocliente = $('#estadocliente').val();
    var motivo = document.getElementById("motivo").value;

    if (documento == "" || documento == null) {
      $("#documento").focus();
      Swal.fire({
        icon: "warning",
        title: "Ingresar documento del Cliente",
        text: "Favor de completar el campo!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }



    if (motivo == "" || motivo == null) {
      $("#motivo").focus();
      Swal.fire({
        icon: "warning",
        title: "INGRESAR MOTIVO DE ANULACION",
        text: "Favor de completar el campo!",
        timer: 3000,
        timerProgressBar: true,
      });
      return;
    }

    $.ajax({
      type: 'POST',
      url: '/verdum/registrocliente/guardarbajacliente',
      data: {
        post: post,
        documento: documento,
        estadocliente: estadocliente,
        motivo: motivo,
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
      }
    });

    $("#myModal").modal('hide');
  });

  $('#btnnobaja').on('click', function () {
    $("#myModal").modal('hide');
  });


  $('#mail').on('keyup', function () {
    var re = /([A-Z0-9a-z_-][^@])+?@[^$#<>?]+?\.[\w]{2,4}/.test(this.value);
    // var re = /^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/.test(this.value);
    if (!re) {
      $('#error').show();
      $('#success').hide();
    } else {
      $('#error').hide();
      $('#success').show();
    }
  })

});

//solo digitar letras
function valideKey(evt) {
  // code is the decimal ASCII representation of the pressed key.
  var code = (evt.which) ? evt.which : evt.keyCode;

  if (code == 8) { // backspace.
    return true;
  } else if (code >= 48 && code <= 57) { // is a number.
    return true;
  } else { // other keys.
    return false;
  }
}


$(document).ready(function () {
  $('input#documento')
    .keypress(function (event) {
      var tipox = $('#tipodocumento').val();
      if ((tipox == 'V')) {
        if (event.which < 48 || event.which > 57 || this.value.length === 8) {
          // alert(tipox);
          $("#documento").focus();
          return false;
        }
      }
      if ((tipox == 'M')) {
        if (event.which < 48 || event.which > 57 || this.value.length === 11) {
          // alert(tipox);
          $("#documento").focus();
          return false;
        }
      }
    });
});


$(document).ready(function () {
  var maxChars = $("#direccion");
  var max_length = maxChars.attr('maxlength');
  if (max_length > 0) {
    maxChars.bind('keyup', function (e) {
      length = new Number(maxChars.val().length);
      counter = max_length - length;
      $("#sessionNum_counter").text(counter);
    });
  }
});




$(document).ready(function () {
  var maxChars = $("#referencia");
  var max_length = maxChars.attr('maxlength');
  if (max_length > 0) {
    maxChars.bind('keyup', function (e) {
      length = new Number(maxChars.val().length);
      counter = max_length - length;
      $("#sessionNum_counter").text(counter);
    });
  }
});


// window.onload = function () {
//   var myInput = document.getElementById('documento');
//   myInput.onpaste = function (e) {
//     e.preventDefault();
//     // alert("esta acción está prohibida");
//   }
//   myInput.oncopy = function (e) {
//     e.preventDefault();
//     // alert("esta acción está prohibida");
//   }
// }

function validarcorreo(correo) {
  var expReg =
    /^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/;
  var valor = expReg.test(correo);
  if (valor == true) {
    return true;
  } else {
    return false;
  }
}



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
