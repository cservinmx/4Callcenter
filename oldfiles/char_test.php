<?php 


$cols=array( 
		array ('id'=>'Caso:', 'label'=>'Topping', 'type'=>'string' ),
		array ('id'=>'Limite', 'label'=>'Limite','type'=>'number' ),
		array ('id'=>'Total', 'label'=>'Ocupado','type'=>'number' ),
		array('id'=> "color", 'role'=>'style', 'type'=>'string' ),
			);

$rows=array(
			array ('c'=> array(array('v'=>'Trigarante', 'f'=>null), array('v'=>110, 'f'=>null ), array('v'=>108, 'f'=>null ), array('v' => '#DC3912', 'f'=>null))),
			array ('c'=> array(array('v'=>'Mas Negocio', 'f'=>null), array('v'=>110, 'f'=>null ), array('v'=>5, 'f'=>null ), array('v' => '#5CB85C', 'f'=>null))),
			array ('c'=> array(array('v'=>'Bodesa', 'f'=>null), array('v'=>110, 'f'=>null ), array('v'=>8, 'f'=>null ), array('v' => '#5CB85C', 'f'=>null))),
			
		);



$values=array('cols'=>$cols, 'rows'=>$rows);


$string=json_encode($values);

echo $string;
?>