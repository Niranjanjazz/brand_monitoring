<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<style>
body {
  font-family: Arial, Helvetica, sans-serif;
  margin: 0;
}

.navbar {
  overflow: hidden;
  background-color: #333; 
}

.navbar a {
  float: left;
  font-size: 16px;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}

.subnav {
  float: left;
  overflow: hidden;
}

.subnav .subnavbtn {
  font-size: 16px;  
  border: none;
  outline: none;
  color: white;
  padding: 14px 16px;
  background-color: inherit;
  font-family: inherit;
  margin: 0;
}

.navbar a:hover, .subnav:hover .subnavbtn {
  /*background-color: red;*/
  background: -webkit-linear-gradient(bottom, #7579ff, #b224ef);
}

.subnav-content {
  display: none;
  position: absolute;
  left: 0;
 background: -webkit-linear-gradient(bottom, #7579ff, #b224ef);
  width: 100%;
  z-index: 1;
}

.subnav-content a {
  float: left;
  color: white;
  text-decoration: none;
}

.subnav-content a:hover {
  background-color: #eee;
  color: black;
}

.subnav:hover .subnav-content {
  display: block;
}
body {
  font-family: "Lato", sans-serif;
}

/* Fixed sidenav, full height */
.sidenav {
  height: 100%;
  width: 200px;
  position: fixed;
  z-index: 1;
  left: 0;
  background-color: #111;
  overflow-x: hidden;
  padding-top: 20px;
}

/* Style the sidenav links and the dropdown button */
.sidenav a, .dropdown-btn {
  padding: 6px 8px 6px 16px;
  text-decoration: none;
  font-size: 20px;
  color: #818181;
  display: block;
  border: none;
  background: none;
  width: 100%;
  text-align: left;
  cursor: pointer;
  outline: none;
}

/* On mouse-over */
.sidenav a:hover, .dropdown-btn:hover {
  color: #f1f1f1;
}

/* Main content */
.main {
  margin-left: 200px; /* Same as the width of the sidenav */
  font-size: 20px; /* Increased text to enable scrolling */
  padding: 0px 10px;
}

/* Add an active class to the active dropdown button */
.active {
  background-color: green;
  color: white;
}

/* Dropdown container (hidden by default). Optional: add a lighter background color and some left padding to change the design of the dropdown content */
.dropdown-container {
  display: none;
  background-color: #262626;
  padding-left: 8px;
}

/* Optional: Style the caret down icon */
.fa-caret-down {
  float: right;
  padding-right: 8px;
}

/* Some media queries for responsiveness */
@media screen and (max-height: 450px) {
  .sidenav {padding-top: 15px;}
  .sidenav a {font-size: 18px;}
}
#logout
{
	position: absolute;
    right: 0;
}
/* Create two equal columns that floats next to each other */
.column {
  float: left;
  width: 50%;
  padding: 10px;
  height: 300px; /* Should be removed. Only for demonstration */
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
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
.collapsible {
  background-color: #777;
  color: white;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
}

.active, .collapsible:hover {
  background-color: #555;
}

.content {
  padding: 0 18px;
  display: none;
  overflow: hidden;
  background-color: #f1f1f1;
}
</style>
</head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<?php
// $product_name = 'OnePlus 9 Pro_test';
$dataPoints = array();

$odb = mysqli_connect('localhost', 'root', '', 'sentimental_analysisdb');

$product_name_query = "select a.Product_name as Product_name from (select id, Product_name from products where id like (select max(id) as currDT from products))a";
$product_name_result = mysqli_query($odb, $product_name_query);
$product_name = mysqli_fetch_all($product_name_result);
$product_name = $product_name[0][0];

$oquery = "SELECT results, count(results) from amazon_data where product_name ='$product_name' group by results";

$oresult = mysqli_query($odb, $oquery);
$osentiments = mysqli_fetch_all($oresult);

$key = array();
$value = array();
foreach($osentiments as $sentiment)
{
    $key[] =$sentiment[0];
    $value[] = $sentiment[1];
    
}
// print_r($key);
// print_r($value);
?>

<?php
// $product_name = 'OnePlus 9 Pro_test';
$dataPoints = array();

$db = mysqli_connect('localhost', 'root', '', 'sentimental_analysisdb');
$review_title = "SELECT review_title, positive FROM `amazon_data` where product_name ='$product_name' order by positive DESC LIMIT 5";

$review_title_result = mysqli_query($db, $review_title);
$positive_review_titles = mysqli_fetch_all($review_title_result);
$key1 = array();
$value1 = array();
foreach($positive_review_titles as $positive_review_title)
{
    $key1[] =$positive_review_title[0];
    $value1[] = $positive_review_title[1]*100;
    
}
$Negative_review_title = "SELECT review_title, negative FROM `amazon_data` where product_name ='$product_name' order by negative DESC LIMIT 5";

$Negative_review_title_result = mysqli_query($db, $Negative_review_title);
$Negative_review_titles = mysqli_fetch_all($Negative_review_title_result);
$key2 = array();
$value2 = array();
foreach($Negative_review_titles as $Neg)
{
    $key2[] =$Neg[0];
    $value2[] = $Neg[1]*100;
    
}
?>
<?php

// $product_name = 'OnePlus 9 Pro_test';
$db = mysqli_connect('localhost', 'root', '', 'sentimental_analysisdb');
$o_query = "SELECT a.ch_yrqr, a.ch_sentiments, (CASE WHEN length(b.sentiment_cnt) > 0 THEN b.sentiment_cnt ELSE 0 END) as sentiments_cnt FROM column_chart a LEFT JOIN (SELECT concat(year(date), '-',(CASE WHEN QUARTER(DATE) = 1 THEN 'Q1' WHEN QUARTER(DATE) = 2 THEN 'Q2' WHEN QUARTER(DATE) = 3 THEN 'Q3' ELSE 'Q4' END)) AS yrqr, results as sentiments, COUNT(results) AS sentiment_cnt FROM amazon_data where product_name ='$product_name' GROUP BY 1,2 ORDER BY 1,2 ASC) b ON a.ch_yrqr = b.yrqr AND a.ch_sentiments = b.sentiments WHERE a.ch_yrqr NOT LIKE '2020-%' ORDER BY a.ch_yrqr; ";

$overall_result = mysqli_query($db, $o_query);
$o_sentiments = mysqli_fetch_all($overall_result);
//$qr = array();
//$sen = array();
$positive = array();
$Negative = array();
$Neutral  = array();
foreach($o_sentiments as $sentiment)
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
<?php
// $product_name = 'iphone12';
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
<?php
$db = mysqli_connect('localhost', 'root', '', 'sentimental_analysisdb');
$top_query = "select reviews, positive from amazon_data where product_name = '$product_name' order by positive DESC LIMIT 5;";


$top_execute = mysqli_query($db, $top_query);
$positive_results = mysqli_fetch_all($top_execute);
$positive_score = array();
$positive_review = array();
foreach($positive_results as $positive_result)
{
    $positive_score[] =$positive_result[1];
    $positive_review[] = $positive_result[0];    
}
$top_negative = "select reviews, negative from amazon_data where product_name = '$product_name' order by negative DESC LIMIT 5;";


$top_neg = mysqli_query($db, $top_negative);
$negative_results = mysqli_fetch_all($top_neg);
$negative_score = array();
$negative_review = array();
foreach($negative_results as $negative_result)
{
    $negative_score[] =$negative_result[1];
    $negative_review[] = $negative_result[0];    
}
?>



<body>

<div class="navbar">
  <a href="index.php">Analytics360</a>
  <div class="subnav" style="padding-left: 500px;">
  <a href="index.php" id="Dashboard">Dashboard</a>
</div>
 <div class="subnav">
  <a href="home.php" id="logout">logout</a>
</div>
</div>

<div class="sidenav">
  <a href="#about">Product List</a>
  <form action="productregister.php" method="POST">
<button class="login100-form-btn"  type="submit" name="gotoproductspage">Add Product</button> 
</form>
</div>
<div>
<div class="row">
  <div class="column">
<figure class="highcharts-figure">
  <div id="container" style="overflow: unset;padding-left: 250px;"></div>
  <p class="highcharts-description">
  </p>
</figure>
</div>
<div class="column">
<figure class="highcharts-figure">
      <div id="container0"></div>
      <p class="highcharts-description">
      </p>
  </figure>
</div>
</div>
<div class="row" style="padding-top:150px;">
  <div class="column">
  <figure class="highcharts-figure">
  <div id="container2" style="padding-left: 250px;"></div>
  <p class="highcharts-description">
  </p>
</figure>
</div>
<div class="column">
<figure class="highcharts-figure">
    <div id="container1"></div>
</figure></div>
</div>
</div>


<div id="formcard" style="padding-top: 320px; padding-left: 250px;">
<h2><b><i>Top 5 reviews </i></b></h2>
<button type="button" class="collapsible">Positive Reviews</button>
<div class="content">
  <p id="positive_rev">
  <script>
    var positive_score =     <?php echo '["' . implode('", "', $positive_score) . '"]' ?>;
    var positive_review =      <?php echo '["' . implode('", "', $positive_review ) . '"]' ?>;
    document.write(positive_review[0]); 
    document.write("<br />");
    document.write("<br />");
    document.write(positive_review[1]);
    document.write("<br />");
    document.write("<br />");
    document.write(positive_review[2]);
    document.write("<br />");
    document.write("<br />");
    document.write(positive_review[3]);
    document.write("<br />");
    document.write("<br />");
    document.write(positive_review[4]);
    
  </script> 
  
  
 </p>
</div>
<!-- <button type="button" class="collapsible">Negative Reviews</button>
<div class="content">
  <p id="negative_rev">
  <script>
    var negative_score =     <?php echo '["' . implode('", "', $negative_score) . '"]' ?>;
    var negative_review =      <?php echo '["' . implode('", "', $negative_review ) . '"]' ?>;
    document.write(negative_review[0]); 
    document.write("<br />");
    document.write("<br />");
    document.write(neagtive_review[1]);
    document.write("<br />");
    document.write("<br />");
    document.write(negative_review[2]);
    document.write("<br />");
    document.write("<br />");
    document.write(negative_review[3]);
    document.write("<br />");
    document.write("<br />");
    document.write(negative_review[4]);
  </script> 
  </p>
</div> -->

</div>
</body>
<script>
var xValues =     <?php echo '["' . implode('", "', $key) . '"]' ?>;
var yValues =      <?php echo '["' . implode('", "', $value) . '"]' ?>;
var barColors = ["red", "green","blue"];

new Chart("myChart", {
  type: "bar",
  data: {
    labels: xValues,
    datasets: [{
      backgroundColor: barColors,
      data: yValues
    }]
  },
  options: {
    legend: {display: false},
    title: {
      display: true,
      text: "Overall Rating"
    }
  }
});

//viz3
var x = <?php echo '["' . implode('", "', $key1) . '"]' ?>;
             var   y = <?php echo '["' . implode('", "', $value1) . '"]' ?>;
             var x1 = <?php echo '["' . implode('", "', $key2) . '"]' ?>;
             var   y1 = <?php echo '["' . implode('", "', $value2) . '"]' ?>;
                
       Highcharts.chart('container1', {
    chart: {
        type: 'packedbubble',
        height: '100%'
    },
    title: {
        text: 'Top 5 <?php echo $product_name ?> Review Title'
    },
    tooltip: {
        useHTML: true,
        pointFormat: '<b>{point.name}:</b> {point.value}%'
    },
    plotOptions: {
        packedbubble: {
            minSize: '30%',
            maxSize: '120%',
            zMin: 0,
            zMax: 1000,
            layoutAlgorithm: {
                splitSeries: false,
                gravitationalConstant: 0.02
            },
            dataLabels: {
                enabled: true,
                format: '{point.name}',
                // filter: {
                //     property: 'y',
                //     operator: '>',
                //     value: 250
                // },
                style: {
                    color: 'black',
                    textOutline: 'none',
                    fontWeight: 'normal'
                }
            }
        }
    },
    series: [{
        name: 'Positive',
        data: [{
            name: x[0],
            value: parseFloat(y[0])
        }, {
            name: x[1],
            value: parseFloat(y[1])
        },
        {
            name: x[2],
            value: parseFloat(y[2])
        },
        {
            name: x[3],
            value: parseFloat(y[3])
        },
        {
            name: x[4],
            value: parseFloat(y[4])
        }]
    }, {
        name: 'Negative',
        data: [{
            name: x1[0],
            value: parseFloat(y1[0])
        },
        {
            name: x1[1],
            value: parseFloat(y1[1])
        },
        {
            name: x1[2],
            value: parseFloat(y1[2])
        },
        {
            name: x1[3],
            value: parseFloat(y1[3])
        },
        {
            name: x1[4],
            value: parseFloat(y1[4])
        }]
    }]
});
//viz high chart
var xValues =     <?php echo '["' . implode('", "', $key) . '"]' ?>;
var yValues =      <?php echo '["' . implode('", "', $value) . '"]' ?>;
var x = parseInt(yValues[0])
var y = parseInt(yValues[1])
var z = parseInt(yValues[2])
  Highcharts.chart('container', {
  chart: {
    type: 'column'
  },
  title: {
    text: 'Amazon Overall Ratings for <?php echo $product_name ?>'
  },
  subtitle: {
    text: ''
  },
  xAxis: {
    categories: [
      'Negative',
      'Neutral',
      'Positive',
    ],
    crosshair: true
  },
  yAxis: {
    min: 0,
    title: {
      text: 'count'
    }
  },
  tooltip: {
    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
    // pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
    //   '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
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
    name: 'Sentiments',
    data : [x,y,z]
   }, 
  // {
  //   name: 'Negative',
  //   data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3]

  // }, {
  //   name: 'Neutral',
  //   data: [48.9, 38.8, 39.3, 41.4, 47.0, 48.3, 59.0, 59.6, 52.4, 65.2, 59.3, 51.2]

  // }
]
});
//quaterly
  var positive =     <?php echo '["' . implode('", "', $positive) . '"]' ?>;
  var negative =      <?php echo '["' . implode('", "', $Negative) . '"]' ?>;
  var neutral =      <?php echo '["' . implode('", "', $Neutral) . '"]' ?>;
  Highcharts.chart('container0', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Quarterly Trends for <?php echo $product_name ?>'
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
//overall twitter_table
var xaxis = <?php echo '["' . implode('", "', $res) . '"]' ?>;
var yaxis = <?php echo '["' . implode('", "', $cnt_res) . '"]' ?>;
// var x1 = parseInt(yaxis[0])
// var y1 = parseInt(yaxis[1])
// var z1 = parseInt(yaxis[2])
  
Highcharts.chart('container2', {
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
//collapsible and expandable form
var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.display === "block") {
      content.style.display = "none";
    } else {
      content.style.display = "block";
    }
  });
}
</script>
</html>
