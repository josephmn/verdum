$(function () {
  // Grafico 8 - Ausentismo remunerado
  $.ajax({
    type: "POST",
    url: "/verdum/dashboard/VentasSede",
    success: function (res) {

      console.log(res);

      var json = JSON.parse("[" + res + "]");
      // console.log(json[0]);
      DibujarGrafico1(json[0]);
      // DibujarGrafico1()
      DibujarGrafico2()
    },
  });
});


function DibujarGrafico1(datos) {

  Highcharts.chart('container', {
    chart: {
      type: 'variablepie'
    },
    title: {
      text: 'Total de pedidos del dia en Soles (Fuerza de Venta)'
    },
    tooltip: {
      headerFormat: '',
      pointFormat: '<span style="color:{point.color}">\u25CF</span> <b> {point.name}</b><br/>' +
        'Total en Soles: <b>{point.y}</b><br/>' 
        // 'Population density (people per square km): <b>{point.z}</b><br/>'
    },
    series: [{
      minPointSize: 10,
      innerSize: '20%',
      zMin: 0,
      name: 'countries',
      data: datos,      
      // [{
      //   name: 'Spain',
      //   y: 505370,
      //   z: 92.9
      // }, {
      //   name: 'France',
      //   y: 551500,
      //   z: 118.7
      // }, {
      //   name: 'Poland',
      //   y: 312685,
      //   z: 124.6
      // }, {
      //   name: 'Czech Republic',
      //   y: 78867,
      //   z: 137.5
      // }, {
      //   name: 'Italy',
      //   y: 301340,
      //   z: 201.8
      // }, {
      //   name: 'Switzerland',
      //   y: 41277,
      //   z: 214.5
      // }, {
      //   name: 'Germany',
      //   y: 357022,
      //   z: 235.6
      // }]
    }]
  });
}

function DibujarGrafico2() {
  Highcharts.chart('container1', {

    chart: {
      polar: true
    },

    title: {
      text: 'Highcharts Polar Chart'
    },

    subtitle: {
      text: 'Also known as Radar Chart'
    },

    pane: {
      startAngle: 0,
      endAngle: 360
    },

    xAxis: {
      tickInterval: 45,
      min: 0,
      max: 360,
      labels: {
        format: '{value}°'
      }
    },

    yAxis: {
      min: 0
    },

    plotOptions: {
      series: {
        pointStart: 0,
        pointInterval: 45
      },
      column: {
        pointPadding: 0,
        groupPadding: 0
      }
    },

    series: [{
      type: 'column',
      name: 'Column',
      data: [8, 7, 6, 5, 4, 3, 2, 1],
      pointPlacement: 'between'
    }, {
      type: 'line',
      name: 'Line',
      data: [1, 2, 3, 4, 5, 6, 7, 8]
    }, {
      type: 'area',
      name: 'Area',
      data: [1, 8, 2, 7, 3, 6, 4, 5]
    }]
  });
}



// padres
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

// hijos
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