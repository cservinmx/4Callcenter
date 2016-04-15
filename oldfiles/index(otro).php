
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Monitoreo Nimbus</title>

    <!-- Bootstrap Core CSS -->
    <link href="http://10.255.243.8/ccs/monitor/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="http://10.255.243.8/ccs/monitor/css/sb-admin.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="http://10.255.243.8/ccs/monitor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- jQuery -->
    <script src="http://10.255.243.8/ccs/monitor/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="http://10.255.243.8/ccs/monitor/js/bootstrap.min.js"></script>

    <!-- Charts JavaScript -->
      <script type="text/javascript" src="http://10.255.243.8/ccs/monitor/js/charts_loader.js"></script>

    <script>
    
    $(document).ready(function () {

    	$('#myCarousel').carousel({
    	interval: 5000
		});
	});

    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawcharts);
    
    
    function drawcharts() {
       		var string_active = "<?php echo $string_active; ?>";
           //Para los clientes activos  
          var options_active = {title:'Clientes Activos', width: 1500,height:1000, titleTextStyle: {bold: true,fontSize: 18,}};
          var json_active = $.ajax({ url: "json_chart_active.php", dataType: "json", async: false }).responseText;                    
          var data_active = new google.visualization.DataTable(json_active);
          var chart_active = new google.visualization.BarChart(document.getElementById('client_active_div'));
          chart_active.draw(data_active, options_active);
          
          //para los clientes demo
          var options_demo = {title:'Clientes Demo', width: 1500,height:1000, titleTextStyle: {bold: true,fontSize: 18,}};
          var json_demo = $.ajax({url: "json_chart_demo.php",dataType: "json", async: false }).responseText;
          var data_demo = new google.visualization.DataTable(json_demo);
          var chart_demo= new google.visualization.BarChart(document.getElementById('client_demo_div'));
          chart_demo.draw(data_demo, options_demo);
          
          //para los clientes inactivos
          var options_inactive = {title:'Clientes Inactivos', width: 1500,height:1000, titleTextStyle: {bold: true,fontSize: 18,}};
          var json_inactive = $.ajax({url: "json_chart_inactive.php",dataType: "json",async: false}).responseText;
          var data_inactive = new google.visualization.DataTable(json_inactive);
          var chart_inactive= new google.visualization.BarChart(document.getElementById('client_inactive_div'));
          chart_inactive.draw(data_inactive, options_inactive);
        }
    </script>

</head>

<body>

 
        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">Nimbus Contact Center</a>
            </div>
           
           
        </nav>
    <style>
  .carousel-inner > .item > div,
  .carousel-inner > .item > a > div {
      width: 70%;
      margin: auto;
  }

.carousel-controls{
	border: solid red 1px;
 position:relative; 
  width:300px;
  margin:0 auto;
  color: #000000;
   z-index: -1000000;
}

.carousel-indicators{
   top:0px; 
}
</style>
        <div id="page-wrapper">

            <div class="container-fluid" style="min-height: 1200px;">
	
    			  		
        		
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
              <!-- Indicators -->
              <ol class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#myCarousel" data-slide-to="1"></li>
                <li data-target="#myCarousel" data-slide-to="2"></li>
              </ol>
            
              <!-- Wrapper for slides -->
              <div class="carousel-inner" role="listbox">
                <div class="item active">
                  <div id="client_active_div"></div>
                </div>
            
                <div class="item">
                  <div id="client_demo_div"></div>
                </div>
            
                <div class="item">
                  <div id="client_inactive_div"></div>
                </div>
            
              </div>
            
              <!-- Left and right controls 
              <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
               <ol class="carousel-indicators">   
		    		<li data-target="#slideshow" data-slide-to="0" class="active"></li>
		    		<li data-target="#slideshow" data-slide-to="1"></li>
		    		<li data-target="#slideshow" data-slide-to="2"></li>		    		
		  		</ol>
              <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
              </a>-->
            </div>
                               

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    
    <!-- /#wrapper -->



</body>

</html>
