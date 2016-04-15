<?php
set_time_limit(20);
include "Funciones.inc.php";
exec("du -m /var/spool/asterisk/monitor/",$regresa);  // Para inbound, Outbound y Manual 
exec("du -m /var/www/html/ccs/monitor", $get); //Para Monitor Manual
exec("du -m /var/lib/asterisk/sounds/call_center/", $vox); //Para buzones de Voz


$arr_empresa=array();
$ids=array();
//Agrego Para inbound, Outbound y Manual 
foreach($regresa as $key => $ret){ //obtiene los valores de la empresa y las salidads de espacio, Manual, outbound e Inbound. 
	$cadena=trim($ret);
	$pos=strpos($cadena,"/");
	$tamano_disco=trim(substr($cadena,0,$pos));
	$empresa=explode("_",$cadena);
	$id_empresa=$empresa[1];
	//echo "<br><br><br>-------$cadena-!-----------------".$tamano_disco."-----".$empresa[1];
		$add=array('id_empresa'=>$id_empresa, 'tamano_disco'=>$tamano_disco, 'cadena'=>$cadena);	
		array_push($arr_empresa,$add);
		if((!array_search($id_empresa,$ids)) && $id_empresa>0 && (!is_null($id_empresa))){			
			array_push($ids,$id_empresa);
		}
	
}

//Agrego las marcaciones manuales 
foreach($get as $value){
	$cadena=trim($value);
	$pos=strpos($cadena,"/");
	$tamano_disco=trim(substr($cadena,0,$pos));
	$empresa=explode("_",$cadena);
	$id_empresa=$empresa[1];
	
		$add=array('id_empresa'=>$id_empresa, 'tamano_disco'=>$tamano_disco, 'cadena'=>$cadena);					
		if(array_search($id_empresa,$ids)){
			//echo "<br><br><br>-------$cadena-!-----------------".$tamano_disco."-----".$empresa[1];		
			array_push($arr_empresa,$add);
		}
}
//print_r($vox);
//Agrego los buzones de Voz

foreach($vox as $val){
	$cadena=trim($val);	
	$pos=strpos($cadena,"/");
	$empresa=explode("_",$cadena);
	//print_r($empresa);
	$id_empresa=$empresa[2];

	$tamano_disco=explode("/", $empresa[0]);	
	
			//echo " id_empresa: ".$id_empresa." tamano_disco ".$tamano_disco[0]." <br>";
		$add=array('id_empresa'=>$id_empresa, 'tamano_disco'=>$tamano_disco[0], 'cadena'=>$cadena);					
		if(array_search($id_empresa,$ids)){
			//echo "<br><br><br>-------$cadena-!-----------------".$tamano_disco[0]."-----".$id_empresa;		
			array_push($arr_empresa,$add);
		}
}

$array_reducido=array();			
foreach (array_unique($ids) as $key => $emp) { //REduce el arreglo y suma el total

			$etotal_empresa=0;					
			$contador=0;		
			foreach($arr_empresa as $l=>$arremp){			
					
				if($emp==$arremp['id_empresa']){
					//echo "empresa ".$emp." Tamanio en disco ".$arremp['tamano_disco']."<br>";
					$contador=$contador+$arremp['tamano_disco'];
				}
				$etotal_empresa=$contador;
			}	
		$res=array('id'=>$emp,  'total'=>$etotal_empresa);
			array_push($array_reducido,$res);	
			
}
print_r($arr_empresa);
//print_r($array_reducido);
/// Guarda los registros en la base de datos


foreach($array_reducido as $temp){
	
	$query="UPDATE Empresas SET tamano_usado='".$temp['total']."' WHERE id='".$temp['id']."'";
	$datos=exe_query($query);
	echo "Espacio actualizado, id: ".$temp['id']."<br>";
	
	
}

?>