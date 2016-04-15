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
$array_reducido=array();			
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
		$res=array('id'=>$emp, 'empresa'=>$nombre_empresa, 'activo'=>$activo, 'total'=>$etotal_empresa, 'limite'=>$limite);
			array_push($array_reducido,$res);	
			
}

//print_r($array_reducido);

//Reordena un array multidmiensional de acuerdo a la llave seleccionada. 
function array_orderby(){
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
            }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}


$arreglo_ordenado=array_orderby($array_reducido, 'total', SORT_DESC, 'empresa', SORT_ASC);
//print_r($arreglo_ordenado);

foreach ($arreglo_ordenado as $ord) {
	$empresa=$ord['empresa'];
	$activo=$ord['activo'];
	$total=$ord['total'];
	$limite=$ord['limite'];
	

			$porcentaje=($limite*(0.1))/$total;
		
				if($porcentaje>80){
					$g_color='#DC3912'; //Rojo
				}else{
					$g_color='#5CB85C';
				}
		
			if($activo==0){ //Cuando es cliente Inactivo
			
					$res_inactive=array ('c'=> array(array('v'=>$empresa, 'f'=>null),array('v'=>$limite, 'f'=>null ), array('v'=>$total, 'f'=>null ),  array('v' => $g_color, 'f'=>null)));
					array_push($row_inactivo, $res_inactive);
					
			}else if($activo==1){//Cuando es cliente activo 
									
					if($total>=75000 || $limite>=75000 ){
						$res_active=array ('c'=> array(array('v'=>$empresa, 'f'=>null),array('v'=>$limite, 'f'=>null ), array('v'=>$total, 'f'=>null ),  array('v' => $g_color, 'f'=>null)));
						array_push($row_active, $res_active);									
					}else{
						$res_active_2=array ('c'=> array(array('v'=>$empresa, 'f'=>null),array('v'=>$limite, 'f'=>null ), array('v'=>$total, 'f'=>null ),  array('v' => $g_color, 'f'=>null)));
						array_push($row_active_2, $res_active_2);
					}
					
			}else if($activo==4){//Cuando es Demo
				
					$res_demo=array ('c'=> array(array('v'=>$empresa, 'f'=>null),array('v'=>$limite, 'f'=>null ), array('v'=>$total, 'f'=>null ),  array('v' => $g_color, 'f'=>null)));
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


    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS 
    <link href="http://10.255.243.8/ccs/monitor/css/sb-admin.css" rel="stylesheet">-->

    <!-- Custom Fonts -->
    <link href="ccs/monitor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- jQuery -->
    <script src="ccs/monitor/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="ccs/monitor/js/bootstrap.min.js"></script>

    <!-- Charts JavaScript -->
      <script type="text/javascript" src="ccs/monitor/js/charts_loader.js"></script>

    <script>
  
    $(document).ready(function () {
		 // Cycles the carousel to a particular frame 
	    $(".slide-one").click(function(){
	      $("#myCarousel").carousel(0);
	    });
	    $(".slide-two").click(function(){
	      $("#myCarousel").carousel(1);
	    });
	    $(".slide-three").click(function(){
	      $("#myCarousel").carousel(2);
	    });
	     $(".slide-four").click(function(){
	      $("#myCarousel").carousel(3);
	    });
	});

    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawcharts);
    
    
    function drawcharts() {
       		//Para los Clientes con cuenta grande
          var options_active = {title:'Clientes Activos', width: 1500,height:1000, titleTextStyle: {bold: true,fontSize: 18,}, chartArea: {width: '80%'}, 
          
          	series:{1:{targetAxisIndex:1}}, hAxes:{1:{title:'Excedido', textStyle:{color: 'red'}}}
          };             
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
	
	<div id="myCarousel" class="carousel slide" data-ride="carousel" style="text-align: center;  margin: auto;">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
    <li data-target="#myCarousel" data-slide-to="1"></li>
    <li data-target="#myCarousel" data-slide-to="2"></li>
    <li data-target="#myCarousel" data-slide-to="3"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox" >
    <div class="item active">
      <div id="client_active_div"></div>  
    </div>

    <div class="item">
      <div id="client_active_2_div"></div>
    </div>

    <div class="item">
      <div id="client_demo_div"></div>	
    </div>

    <div class="item">
      <div id="client_inactive_div"></div> 
    </div>
  </div>


   <!--  <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>-->
    
       	<input type="button" class="btn btn-info slide-one" value="Activos">
        <input type="button" class="btn btn-info slide-two" value="Activos >80,000 MB">            
        <input type="button" class="btn btn-info slide-three" value="Demo">
        <input type="button" class="btn btn-info slide-four" value="Inactivos">
  </a>
  
</div>
	
	
    
</body>
</html>
