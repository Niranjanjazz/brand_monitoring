<?php
$product_name = 'iphone12';
$dataPoints = array();

$db = mysqli_connect('localhost', 'root', '', 'sentimental_analysisdb');
$selectquery = "SELECT results, count(results) from twitter_table where product_name ='$product_name' group by results";


$queryexecute = mysqli_query($db, $selectquery);
$sa_result = mysqli_fetch_all($queryexecute);
$res = array();
$cnt_res = array();
foreach($sa_result as $sa)
{
    $res[] =$sa[0];
    $cnt_res[] = $sa[1];    
}
?>

<html>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>


<style>
.highcharts-figure,
.highcharts-data-table table {
    min-width: 320px;
    max-width: 800px;
    margin: 1em auto;
}

.highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #ebebeb;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
}

.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}

.highcharts-data-table th {
    font-weight: 600;
    padding: 0.5em;
}

.highcharts-data-table td,
.highcharts-data-table th,
.highcharts-data-table caption {
    padding: 0.5em;
}

.highcharts-data-table thead tr,
.highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}

.highcharts-data-table tr:hover {
    background: #f1f7ff;
}

input[type="number"] {
    min-width: 50px;
}
</style>


<figure class="highcharts-figure">
  <div id="container"></div>
  <p class="highcharts-description">
  </p>
</figure>
<body>
<script>
var xaxis = <?php echo '["' . implode('", "', $res) . '"]' ?>;
var yaxis = <?php echo '["' . implode('", "', $cnt_res) . '"]' ?>;
// var x1 = parseInt(yaxis[0])
// var y1 = parseInt(yaxis[1])
// var z1 = parseInt(yaxis[2])
  
Highcharts.chart('container', {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie'
  },
  title: {
    text: 'Twitter Overall Ratings for <?php echo $product_name ?>'
  },
  tooltip: {
    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
 },
  accessibility: {
    point: {
      valueSuffix: '%'
    }
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: true,
        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
      }
    }
  },
  series: 
  [{
    name: 'Sentiments',
    colorByPoint: true,
    data: 
    [{
      name: xaxis[0],
      y: parseInt(yaxis[0]),
      sliced: true,
      selected: true
    }, {
      name: xaxis[1],
      y: parseInt(yaxis[1])
    }, {
      name: xaxis[2],
      y: parseInt(yaxis[2])
    }]
  }]
});


</script> 
</body> 
</html>