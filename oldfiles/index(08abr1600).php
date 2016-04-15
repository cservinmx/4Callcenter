<?php
set_time_limit(20);

include "../Funciones.inc.php";

exec("du -m /var/spool/asterisk/monitor/",$regresa);
$arr_empresa=array();
$ids=array();
foreach($regresa as $key => $ret){
	$cadena=trim($ret);
	$pos=strpos($cadena,"/");
	$tamano_disco=trim(substr($cadena,0,$pos));
	$empresa=explode("_",$cadena);
	$id_empresa=$empresa[1];
	//echo "<br><br><br>-------$cadena-!-----------------".$tamano_disco."-----".$empresa[1];
	
		$add=array('id_empresa'=>$id_empresa, 'tamano_disco'=>$tamano_disco);	
		array_push($arr_empresa,$add);
		if((!array_search($id_empresa,$ids)) && $id_empresa>0 && (!is_null($id_empresa))){			
			array_push($ids,$id_empresa);
		}
	
}


//Defino filas
$row_active=array();
$row_active_2=array();
$row_demo=array();
$row_inactivo=array();
//Defino las columnas
$cols=array( 
			array ('id'=>'Caso:', 'label'=>'Topping', 'type'=>'string' ),
			array ('id'=>'Limite', 'label'=>'Limite','type'=>'number' ),
			array ('id'=>'Total', 'label'=>'Ocupado','type'=>'number' ),			
			array('id'=> "color", 'role'=>'style', 'type'=>'string' )
			);
			
foreach (array_unique($ids) as $key => $emp) {
		$query="SELECT tamano_grabaciones, compania, activo FROM Empresas WHERE id='".$emp."'";
		$datos=exe_query($query,6);
		$limite=$datos[0]['tamano_grabaciones'];
		$nombre_empresa=strtoupper($datos[0]['compania']);
		$activo=$datos[0]['activo'];
		
		
			$etotal_empresa=0;					
			$contador=0;		
			foreach($arr_empresa as $l=>$arremp){			
				//echo $arremp['id_empresa']." ; ";						
				if($emp==$arremp['id_empresa']){
					//echo "empresa ".$emp." Tamanio en disco ".$arremp['tamano_disco']."<br>";
					$contador=$contador+$arremp['tamano_disco'];
				}
				$etotal_empresa=$contador;
			}	
			$porcentaje=($limite*(0.1))/$contador;
			if($porcentaje>80){
				$g_color='#DC3912'; //Rojo
			}else{
				$g_color='#5CB85C';
			}
			
			if($activo==0){ //Cuando es cliente Inactivo
			
					$res_inactive=array ('c'=> array(array('v'=>$nombre_empresa, 'f'=>null),array('v'=>$limite, 'f'=>null ), array('v'=>$contador, 'f'=>null ),  array('v' => $g_color, 'f'=>null)));
					array_push($row_inactivo, $res_inactive);
					
			}else if($activo==1){//Cuando es cliente activo 
								
				if($contador>=75000 ||$limite>=75000 ){
					$res_active=array ('c'=> array(array('v'=>$nombre_empresa, 'f'=>null),array('v'=>$limite, 'f'=>null ), array('v'=>$contador, 'f'=>null ),  array('v' => $g_color, 'f'=>null)));
					array_push($row_active, $res_active);									
				}else{
					$res_active_2=array ('c'=> array(array('v'=>$nombre_empresa, 'f'=>null),array('v'=>$limite, 'f'=>null ), array('v'=>$contador, 'f'=>null ),  array('v' => $g_color, 'f'=>null)));
					array_push($row_active_2, $res_active_2);
				}
				
			}else if($activo==4){//Cuando es Demo
			
				$res_demo=array ('c'=> array(array('v'=>$nombre_empresa, 'f'=>null),array('v'=>$limite, 'f'=>null ), array('v'=>$contador, 'f'=>null ),  array('v' => $g_color, 'f'=>null)));
					array_push($row_demo, $res_demo);
			}
			//echo "----------------------------------------id: ".$emp." empresa ".$nombre_empresa." .........Activo: ".$activo."......... Total: ".$etotal_empresa." MB .......Limite: ".$limite." MB <br>";
			
}

$values_active=array('cols'=>$cols, 'rows'=>$row_active);
$values_active_2=array('cols'=>$cols, 'rows'=>$row_active_2);
$values_inactive=array('cols'=>$cols, 'rows'=>$row_inactivo);
$values_demo=array('cols'=>$cols, 'rows'=>$row_demo);


$string_active=json_encode($values_active);
$string_active_2=json_encode($values_active_2);
$string_inactive=json_encode($values_inactive);
$string_demo=json_encode($values_demo);

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


    <!-- Custom Fonts -->
    <link href="http://10.255.243.8/ccs/monitor/css/owl.carousel.css" rel="stylesheet" type="text/css">

        <!-- jQuery -->
    <script src="http://10.255.243.8/ccs/monitor/js/jquery.js"></script>


    <!-- Charts JavaScript -->
      <script type="text/javascript" src="http://10.255.243.8/ccs/monitor/js/charts_loader.js"></script>
       <!-- OWL JS -->
    <script src="http://10.255.243.8/ccs/monitor/js/owl.carousel.js"></script>

    <script>
  
    $(document).ready(function () {
		$(".owl-carousel").owlCarousel({		
    	loop:true,    	
		items:1,
		autoplay:true,
    	autoplayTimeout:5000,
    	autoplayHoverPause:true,
   
      });
	});

    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawcharts);
    
    
    function drawcharts() {
       		//Para los Clientes con cuenta grande
          var options_active = {title:'Clientes Activos', width: 1500,height:1000, titleTextStyle: {bold: true,fontSize: 18,}};             
          var data_active = new google.visualization.DataTable(<?php echo $string_active_2; ?>);                   
          var chart_active = new google.visualization.BarChart(document.getElementById('client_active_div'));
          chart_active.draw(data_active, options_active);
          
          var options_active_2 = {title:'Clientes Activos', width: 1500,height:1000, titleTextStyle: {bold: true,fontSize: 18,}};             
          var data_active_2 = new google.visualization.DataTable(<?php echo $string_active; ?>);         
          var chart_active_2 = new google.visualization.BarChart(document.getElementById('client_active_2_div'));
          chart_active_2.draw(data_active_2, options_active_2);
          
             //para los clientes demo
          var options_demo = {title:'Clientes Demo', width: 1500,height:1000, titleTextStyle: {bold: true,fontSize: 18,}};
          var data_demo = new google.visualization.DataTable(<?php echo  $string_demo ?>);
          var chart_demo= new google.visualization.BarChart(document.getElementById('client_demo_div'));
          chart_demo.draw(data_demo, options_demo);
          
          //para los clientes inactivos
          var options_inactive = {title:'Clientes Inactivos', width: 1500,height:1000, titleTextStyle: {bold: true,fontSize: 18,}};                   
          var data_inactive = new google.visualization.DataTable(<?php echo  $string_inactive ?>);
          var chart_inactive= new google.visualization.BarChart(document.getElementById('client_inactive_div'));
          chart_inactive.draw(data_inactive, options_inactive);
       
    }
    </script>

</head>
<body>
	<div style="text-align: center">
    	<div class="owl-carousel">
        	      			
        </div>
        
        <div id="client_active_div"></div>  
			<div id="client_active_2_div"></div>
			<div id="client_demo_div"></div>					
			<div id="client_inactive_div" class="item"></div>  
    </div>
    
    
</body>
</html>
