const routeChart = '/rents/json/chart';

$.getJSON(routeChart, function (data) { //ajax
		
  console.log(data)

}).then((data) => {

  let arrMonths = []
  let arrQtds = [];

  dataArray = Object.values(data);
  console.log('dataArray', dataArray)

  dataArray.forEach(function(item) {
    arrMonths.push(item.month)
    arrQtds.push(item.qtd)
  });

  loadChart(arrMonths, arrQtds);

}).catch(function () {
  console.log(`Rota não encontrada! (${routeChart})`);
});


function loadChart(arrMeses, arrQuantidades) {

  // Set new default font family and font color to mimic Bootstrap's default styling
  Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
  Chart.defaults.global.defaultFontColor = '#292b2c';

  // Area Chart Example
  var ctx = document.getElementById("graficoAlugueis");
  var myLineChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: arrMeses/*["Semana 1", "Semana 2", "Semana 3", "Semana 4", "Semana 5"]*/,
      datasets: [{
        label: "Alugueis",
        lineTension: 0.3,
        backgroundColor: "rgba(2,117,216,0.2)",
        borderColor: "rgba(2,117,216,1)",
        pointRadius: 5,
        pointBackgroundColor: "green",
        pointBorderColor: "rgba(255,255,255,0.8)",
        pointHoverRadius: 5,
        pointHoverBackgroundColor: "rgba(2,117,216,1)",
        pointHitRadius: 50,
        pointBorderWidth: 2,
        data: arrQuantidades,
      }],
    },
    options: {

      tooltips:{
        mode:'index',
        intersect:false
      },
      scales: {
        xAxes: [{
          time: {
            unit: 'date'
          },
          gridLines: {
            display: false
          },
          ticks: {
            maxTicksLimit: 7
          }
        }],
        yAxes: [{
          ticks: {
            min: 0,
            max: Math.max(...arrQuantidades),
            maxTicksLimit: 5
          },
          gridLines: {
            color: "rgba(0, 0, 0, .125)",
          }
        }],
      },
      legend: {
        display: false
      }
    }
  });

}