require('../../node_modules/materialize-css/dist/js/materialize.js');
require('../../node_modules/materialize-css/dist/css/materialize.css');
require('../css/app.css');
const $ = require('jquery');
import {GoogleCharts} from 'google-charts';


$(document).ready(function(){
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd'
    });
    $('select').formSelect();


});

GoogleCharts.load(drawChart);

function drawChart() {
  $.ajax({
    type: 'GET',
    url: '/app_dev.php/stats',
    success: function(response){
        let dataArray = [
          ['Date', 'Amount']
        ];
        response = Object.values(response).map(function(arrayResponse) {
          dataArray.push(Object.values(arrayResponse));
        })
      const data = GoogleCharts.api.visualization.arrayToDataTable(dataArray);
      const pie_1_chart = new GoogleCharts.api.visualization.LineChart(document.getElementById('chart_div'));
      pie_1_chart.draw(data);
    }
  })

}