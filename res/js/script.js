
/* ----------------- Início de Gráfico de Pizza (piechart)  ------------*/
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {

  var data = google.visualization.arrayToDataTable([
    ['Tipos', 'valor'],
    ['Recebidas',     110],
    ['Pendentes',      11]  
  ]);

  var options = {
    title: 'Faturas no último mês (jan/2020)'
  };

  var chart = new google.visualization.PieChart(document.getElementById('piechart'));

  chart.draw(data, options);
}
/* ----------------- FIM de Gráfico de Pizza (piechart)  ------------*/


/* ----------------- Início de Gráfico de Colunas ------------*/
google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawMaterial);

function drawMaterial() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'mês/ano');
      data.addColumn('string', 'recebidas');
      data.addColumn('string', 'pendente');

      data.addRows([
        [{v: 'jun/2019', f: 'jun/2019'}, 'R$ 2.546,33', 'R$ 301,00'],
        [{v: 'jul/2019', f: 'jul/2019'}, 'R$ 1.764,00', 'R$ 560,00'],
        [{v: 'ago/2019', f: 'ago/2019'}, 'R$ 1.500,00', 'R$ 120,00'],
        [{v: 'set/2019', f: 'set/2019'}, 'R$ 2.546,33', 'R$ 301,00'],
        [{v: 'out/2019', f: 'out/2019'}, 'R$ 1.764,00', 'R$ 560,00'],
        [{v: 'nov/2019', f: 'nov/2019'}, 'R$ 1.500,00', 'R$ 120,00'],
        [{v: 'dez/2019', f: 'dez/2019'}, 'R$ 1.589,97', 'R$ 250,00'],
        [{v: 'jan/2020', f: 'jan/2020'}, 'R$ 2.452,34', 'R$ 400,00']
       
      ]);

    

      var materialChart = new google.charts.Bar(document.getElementById('chart_div'));
      materialChart.draw(data);
    }

/* ----------------- FIM de Gráfico de Colunas ------------*/