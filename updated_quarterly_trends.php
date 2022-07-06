<?php

$product_name = 'OnePlus 9 Pro_test';
$db = mysqli_connect('localhost', 'root', '', 'sentimental_analysisdb');
$query = "SELECT a.ch_yrqr, a.ch_sentiments, (CASE WHEN length(b.sentiment_cnt) > 0 THEN b.sentiment_cnt ELSE 0 END) as sentiments_cnt FROM column_chart a LEFT JOIN (SELECT concat(year(date), '-',(CASE WHEN QUARTER(DATE) = 1 THEN 'Q1' WHEN QUARTER(DATE) = 2 THEN 'Q2' WHEN QUARTER(DATE) = 3 THEN 'Q3' ELSE 'Q4' END)) AS yrqr, results as sentiments, COUNT(results) AS sentiment_cnt FROM amazon_data where product_name ='OnePlus 9 Pro_test' GROUP BY 1,2 ORDER BY 1,2 ASC) b ON a.ch_yrqr = b.yrqr AND a.ch_sentiments = b.sentiments WHERE a.ch_yrqr NOT LIKE '2020-%' ORDER BY a.ch_yrqr; ";

$result = mysqli_query($db, $query);
$sentiments = mysqli_fetch_all($result);
$qr = array();
$sen = array();
$value = array();
$positive = array();
$Negative = array();
$Neutral  = array();
foreach($sentiments as $sentiment)
{

if($sentiment[1]=='Positive')
{
  $positive[] = $sentiment[2];
}
else if($sentiment[1]=='Negative')
{
  $Negative[] = $sentiment[2];
}
else if($sentiment[1]=='Neutral')
{
  $Neutral[] = $sentiment[2];
}
    
}
?>
<html>
<script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>

  <figure class="highcharts-figure">
      <div id="container"></div>
      <p class="highcharts-description">
      </p>
  </figure>
  <style>
    .highcharts-figure,
.highcharts-data-table table {
    min-width: 310px;
    max-width: 800px;
    margin: 1em auto;
}

#container {
    height: 400px;
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
    </style>
<script>
  var positive =     <?php echo '["' . implode('", "', $positive) . '"]' ?>;
  var negative =      <?php echo '["' . implode('", "', $Negative) . '"]' ?>;
  var neutral =      <?php echo '["' . implode('", "', $Neutral) . '"]' ?>;
  Highcharts.chart('container0', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Quarterly Trends'
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: [
            '2021-Q1',
            '2021-Q2',
            '2021-Q3',
            '2021-Q4',
            '2022-Q1',

        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Count'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Positive',
        data: [parseInt(positive[0]), parseInt(positive[1]), parseInt(positive[2]),parseInt(positive[3]), parseInt(positive[4])]

    }, {
        name: 'Negative',
        data: [parseInt(negative[0]), parseInt(negative[1]), parseInt(negative[2]),parseInt(negative[3]), parseInt(negative[4])]

    }, {
        name: 'Neutral',
        data: [parseInt(neutral[0]), parseInt(neutral[1]), parseInt(neutral[2]),parseInt(neutral[3]), parseInt(neutral[4])]

    }]
});
</script>

  
</html>