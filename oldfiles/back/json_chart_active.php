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
$row=array();
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
		$nombre_empresa=strtoupper ($datos[0]['compania']);
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
			if($activo==1){
				if($porcentaje>80){
					$g_color='#DC3912'; //Rojo
				}else{
					$g_color='#5CB85C';
				}
				
				
				$res=array ('c'=> array(array('v'=>$nombre_empresa, 'f'=>null),array('v'=>$limite, 'f'=>null ), array('v'=>$contador, 'f'=>null ),  array('v' => $g_color, 'f'=>null)));
				array_push($row, $res);
				
				//echo "----------------------------------------id: ".$emp." empresa ".$nombre_empresa." .........Activo: ".$activo."......... Total: ".$etotal_empresa." MB .......Limite: ".$limite." MB <br>";
			}
	
}

$values=array('cols'=>$cols, 'rows'=>$row);


$string=json_encode($values);

echo $string;
?>

