<?php

$beerName = $settingsArray["beerName"];
$tempFormat = $settingsArray["tempFormat"];
$profileKey = $settingsArray["profileKey"];
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/main.css">
    <script type="text/javascript">
      // pass parameters to JavaScript
      window.tempFormat = <?php echo "'$tempFormat'" ?>;
      window.googleDocsKey = <?php echo "\"$profileKey\""?>;
      window.beerName = <?php echo "\"$beerName\""?>;
    </script>
    <script type="text/javascript" src="js/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.10.3.custom.min.js"></script>
         <script type="text/javascript" src="http://www.google.com/jsapi"></script>
              <script type="text/javascript">
                google.load('visualization', '1.0', {packages: ['charteditor']});
$(document).ready(function(){


  drawBeerChart(window.beerName, 'curr-beer-chart');


              /* Give name of the beer to display and div to draw the graph in */
              function drawBeerChart(beerName, div){
                var beerChart;
                var beerData;
               
                $.post("get_beer_files.php", {"beername": beerName}, function(answer){
                  var combinedJson;
                  var first = true;
                  var files = eval(answer);
               
                  for(i=0;i<files.length;i++){
               
                    filelocation = files[i];
                    var jsonData = $.ajax({
                        url: filelocation,
                        dataType:"json",
                          async: false
                            }).responseText;
                        var evalledJsonData = eval("("+jsonData+")");
                    if(first){
                      combinedJson = evalledJsonData;
                      first = false;
                    }
                    else{
                      combinedJson.rows  = combinedJson.rows.concat(evalledJsonData.rows);
                    }
                  }
                  var beerData = new google.visualization.DataTable(combinedJson);
                  var beerChart = new google.visualization.AnnotatedTimeLine(document.getElementById("chart_div"));
                    beerChart.draw(beerData, {
                             'displayAnnotations': true,
                             'scaleType': 'maximized',
                             'displayZoomButtons': false,
                             'allValuesSuffix': "\u00B0 C",
                             'numberFormats': "##.0",
                            'displayAnnotationsFilter' : true});
                });
              }
              });
            </script>

            
            <!-- <script type="text/javascript">
                google.setOnLoadCallback(loadEditor);
                var chartEditor = null;

                function loadEditor() {
                  // Create the chart to edit.
                  var wrapper = new google.visualization.ChartWrapper({
                     'chartType':'LineChart',
                     'dataSourceUrl':'data/Test.csv',
                     'query':'SELECT A,D WHERE D > 100 ORDER BY D',
                     'options': {'title':'Population Density (people/km^2)', 'legend':'none'}
                     });

                  chartEditor = new google.visualization.ChartEditor();
                  google.visualization.events.addListener(chartEditor, 'ok', redrawChart);
                  chartEditor.openDialog(wrapper, {});
                }

                // On "OK" save the chart to a <div> on the page.
                function redrawChart(){
                  chartEditor.getChartWrapper().draw(document.getElementById('container'));
                }

              </script> -->
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Home Brew Project</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Beer Lists <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#">Brewed Beers</a></li>
                <li><a href="#">Future Brews</a></li>
                <li><a href="#">Old and reliable Brews</a></li>
                <li class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li>
          </ul>
          <!--<form class="navbar-form navbar-right">
            <div class="form-group">
              <input type="text" placeholder="Email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Sign in</button>
          </form>  end of sign in -->
        </div><!--/.navbar-collapse -->
      </div>
    </div>

    <!-- Main jumbotron for a primary marketing message or call to action -->
   
    <div class="container" id="container">
      <!-- Example row of columns -->
      <div id="chart_div" style="width: 680px; height: 400px; margin-bottom: 30px;"></div>
      </div>

      <hr>

 
<div class="clear"></div>
      <footer>
        <p>&copy; Philip Scheid 2013</p>
      </footer>
    </div> <!-- /container -->        
        


    </body>
</html>