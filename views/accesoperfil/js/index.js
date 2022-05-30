$(function () {
  $("#example1").DataTable({
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
      [10, 25, 50, -1],
      ["10", "25", "50", "Todo"],
    ],
  });
});


// window.onload = function(){
//   var fecha = new Date(); //Fecha actual
//   var mes = fecha.getMonth()+1; //obteniendo mes
//   var dia = fecha.getDate(); //obteniendo dia
//   var ano = fecha.getFullYear(); //obteniendo año
//   if(dia<10)
//     dia='0'+dia; //agrega cero si el menor de 10
//   if(mes<10)
//     mes='0'+mes //agrega cero si el menor de 10
//   document.getElementById('fecha').value=ano+"-"+mes+"-"+dia;
// }

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
