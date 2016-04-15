<?php
set_time_limit(20);
include "../ccs/Funciones.inc.php";
$query="SELECT id, compania, tamano_grabaciones, tamano_usado, ip_pbx, activo FROM Empresas WHERE (activo='1' OR activo='4' OR activo='0') ORDER BY tamano_grabaciones DESC";
		$datos=exe_query($query,6);
		//print_r($datos);
//Defino filas para dallas
$row_activo=array();
$row_demo=array();
$row_inactivo=array();

//Defino las columnas
$cols=array( 
			array ('id'=>'Caso:', 'label'=>'Topping', 'type'=>'string'),
			array ('id'=>'Limite', 'label'=>'Limite','type'=>'number'),
			array ('id'=>'Total', 'label'=>'Ocupado','type'=>'number'),			
			array ('id'=> "color", 'role'=>'style', 'type'=>'string'),			
			);



foreach ($datos as $k=> $value) {

	$activo=$value['activo'];
	$ip_pbx=$value['ip_pbx'];
	$empresa=substr($value['compania'], 0, 10);
	$limite=$value['tamano_grabaciones'];
	$total=$value['tamano_usado'];

	 if($value['ip_pbx'] == '10.255.243.10'){
            $ubi="TRIARA";
							
	  }else if($value['ip_pbx'] == '10.255.249.222'){
			$ubi="DALLAS";					
	  }  

	 if($value['tamano_usado']>=$value['tamano_grabaciones']){
		if($value['tamano_grabaciones']==0){
			//$g_color="green";
			$g_color='#5CB85C';
		}else{						
			$g_color='#DC3912'; //Rojo
		}				
	 }else{
			//$g_color="green";
			if($porcentaje>=80){
				$g_color='#DC3912'; //Rojo
			}else{
				$g_color='#5CB85C';	
			}				
	 }
				
	 //$porcentaje=($limite)/($total);
		
	/*
	 * Limite->100
	 * total-porcencaje
	 * */
		
   // $g_color='#5CB85C'; //verde default

		
		if($activo==0){ //Cuando es cliente Inactivo					
			$res_inactivo=array ('c'=> array(array('v'=>$empresa, 'f'=>null),array('v'=>$limite, 'f'=>null ), array('v'=>$total, 'f'=>null ),  array('v' => $g_color, 'f'=>null)));			
			array_push($row_inactivo, $res_inactivo);
					
		}else if($activo==1){//Cuando es cliente activo 
			$res_active=array ('c'=> array(array('v'=>$empresa, 'f'=>null),array('v'=>$limite, 'f'=>null ), array('v'=>$total, 'f'=>null ),  array('v' => $g_color, 'f'=>null)));
			array_push($row_activo, $res_active);									
		}else if($activo==4){//Cuando es Demo
				
			$res_demo=array ('c'=> array(array('v'=>$empresa, 'f'=>null),array('v'=>$limite, 'f'=>null ), array('v'=>$total, 'f'=>null ),  array('v' => $g_color, 'f'=>null)));
			array_push($row_demo, $res_demo);
		}
	//echo "----------------------------------------id: ".$emp." empresa ".$nombre_empresa." .........Activo: ".$activo."......... Total: ".$etotal_empresa." MB .......Limite: ".$limite." MB <br>";
}


?>
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
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="css/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<!-- jQuery -->
    <script src="js/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>

	<!-- Charts JavaScript -->
    <script src="js/charts_loader.js"></script>
   
  <?php 
  
  /*********************** Corta el array y genera 8 ***********/
$row_inactivo_chunk=array_chunk($row_inactivo, 8);
$row_activo_chunk=array_chunk($row_activo, 8);
$row_demo_chunk=array_chunk($row_demo, 8);	
			
$tot_inactivo=count($row_inactivo_chunk);

$values_inactive=array();
foreach ($row_inactivo_chunk as $key => $rowcinactivo) {
	$values_inactive[$k]=array('cols'=>$cols, 'rows'=>$row_inactivo_chunk[$k]);
}	

			
			
			/**/	
$values_activo=array('cols'=>$cols, 'rows'=>$row_activo);

$values_demo=array('cols'=>$cols, 'rows'=>$row_demo);

$string_active=json_encode($values_activo); //ok
$string_inactive=json_encode($values_inactive);
$string_demo=json_encode($values_demo); //ok			

?>
<script>
  
    $(document).ready(function () {
    	
		 // Cycles the carousel to a particular frame 
	    $(".slide-one").click(function(){
	      $("#Carousel").carousel(0);
	    });
	    $(".slide-two").click(function(){
	      $("#Carousel").carousel(1);
	    });
	    $(".slide-three").click(function(){
	      $("#Carousel").carousel(2);
	    });
	     $(".slide-four").click(function(){
	      $("#Carousel").carousel(3);
	    });	    
	});

    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawcharts);
    
    
    function drawcharts() {
    	
    	/********************TRIARA***********************************************/
    	
       		//Para los Clientes con cuenta grande
          var triara_options_active = {title:'Clientes Activos', titleTextStyle: {bold: true,fontSize: 18,}, height:900, width: 1500,};    
          //triara_chart_active.setColumns([0, 1, { calc: "stringify", sourceColumn: 1, type: "string",role: "annotation" },2]);         
          var triara_data_active = new google.visualization.DataTable(<?php echo $string_active; ?>);                   
          var triara_chart_active = new google.visualization.BarChart(document.getElementById('client_active_div'));
          triara_chart_active.draw(triara_data_active, triara_options_active);
               
          
             //para los clientes demo
          var triara_options_demo = {title:'Clientes Demo',titleTextStyle: {bold: true,fontSize: 18,}, height:900, width: 1500,};
          var triara_data_demo = new google.visualization.DataTable(<?php echo  $string_demo ?>);
          var triara_chart_demo= new google.visualization.BarChart(document.getElementById('client_demo_div'));
          triara_chart_demo.draw(triara_data_demo, triara_options_demo);
          
          //para los clientes inactivos
          var triara_options_inactive = {title:'Clientes Inactivos', titleTextStyle: {bold: true,fontSize: 18,}, height:900, width: 1500,};                   
          var triara_data_inactive = new google.visualization.DataTable(<?php echo  $string_inactive ?>);
          var triara_chart_inactive= new google.visualization.BarChart(document.getElementById('client_inactive_div'));
          triara_chart_inactive.draw(triara_data_inactive, triara_options_inactive);
       
    	  
       
    }
    </script>
</head>

<body>

	<div id="Carousel" class="carousel slide" data-ride="carousel">
	<!-- Indicators -->
		<ol class="carousel-indicators">
			<li data-target="#Carousel" data-slide-to="0" class="active"></li>
			<li data-target="#Carousel" data-slide-to="1"></li>
			<li data-target="#Carousel" data-slide-to="2"></li>
		</ol>						  
		<!-- Wrapper for slides -->
		<div class="carousel-inner" role="listbox" >
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
		<div class="panel panel-default">
			<!-- Default panel contents -->								 
			<input type="button" class="btn btn-info slide-one" value="Activos">         
			<input type="button" class="btn btn-info slide-two" value="Demo">
			<input type="button" class="btn btn-info slide-three" value="Inactivos">							 
		</div>							  
	</div>

</body>

</html>