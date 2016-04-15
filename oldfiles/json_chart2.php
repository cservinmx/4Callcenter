<?php
set_time_limit(20);


include "../Funciones.inc.php";

exec("du -m /var/spool/asterisk/monitor/",$regresa);

$json_array=array();
$a=0;

while($regresa[$a]){

$cadena=trim($regresa[$a]);
$pos=strpos($cadena,"/");
$tamano_disco=trim(substr($cadena,0,$pos));

$empresa=explode("_",$cadena);

$id_empresa=$empresa[1];
//echo "<br><br><br>-------$cadena-!-----------------".$tamano_disco."-----".$empresa[1];
$query="SELECT tamano_grabaciones, compania FROM Empresas WHERE activo='1' AND id='".$id_empresa."'";
$datos=exe_query($query,6);

$limite=$datos[0]['tamano_grabaciones'];
$nombre_empresa=$datos[0]['compania'];


$result=array('id_emresa' => $id_empresa, 'empresa'=>$nombre_empresa, 'limite'=>$limite, 'ocupado'=>$tamano_disco);
array_push($json_array, $result);
//echo "id empresa: ".$id_empresa." empresa: ".$nombre_empresa. " limite: ".$limite ." Ocupado: ".$tamano_disco ."<br>";



$a++;
}

echo json_encode($json_array);


?>

