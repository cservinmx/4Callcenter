<?php
set_time_limit(20);
//include "../../ccs/Funciones.inc.php"; //Servidor
include "../ccs/Funciones.inc.php";
		$query1="SELECT id, compania, tamano_grabaciones, tamano_usado, ip_pbx, activo FROM Empresas WHERE activo='1' AND (ip_pbx='10.255.243.10' OR ip_pbx='10.255.249.222')  ORDER BY tamano_usado DESC";
		$datos=exe_query($query1,6);
		

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
    
     <!-- DataTables CSS -->
    <link href="css/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS 
    <link href="../bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">-->


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
    
    <!-- DataTables JavaScript -->
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap.min.js"></script>
    <script>
	setTimeout(function(){
   		window.location.reload(1);
	}, 300000);
    $(document).ready(function() {
        $('#dataTables').DataTable({
        		"paging":   false,
                responsive: true,                                  
        		"order": [[ 2, "desc" ]],  
        });
        
	  $('[data-toggle="tooltip"]').tooltip();

       
    });
    </script>
    </head>

<body>

		      	<table class="table table-striped table-hover" id="dataTables">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">Id</th>
                                            <th style="width: 40%;">Cliente</th>
                                            <th style="width: 45%;">Espacio</th>
                                            <th style="width: 10%;">Ubicacion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	<?php foreach ($datos as $row_activo){
                                        	$id=$row_activo['id'];
											$compania=strtoupper ($row_activo['compania']);
											$tamano_grabaciones=$row_activo['tamano_grabaciones'];
											$tamano_usado=$row_activo['tamano_usado'];
											$ip_pbx=$row_activo['ip_pbx'];
											$porcentaje=round(($tamano_usado*(100)/($tamano_grabaciones)));
											$complemento=100-$porcentaje;


											if($tamano_grabaciones!=0 && $porcentaje>=70 ){
												$class='danger';
											}else{
												$class='success';
											}
											if($ip_pbx == '10.255.243.10'){
										            $ubi="TRIARA";
																	
											}else if($ip_pbx == '10.255.249.222'){
													$ubi="DALLAS";					
											}  
											
											
											?>
                                        <tr>
                                            <td><?= $id; ?></td>
                                            <td><?= $compania; ?><br><span class="label label-primary">Espacio configurado <?= number_format($tamano_grabaciones); ?> MB.</span></td>
                                            <td>
                                            	
                                            	<div class="progress">
												  
												
													<?php
													
												
													if($tamano_grabaciones!=0 && $porcentaje>=100 ){ ?>												
													  <div class="progress-bar progress-bar-danger" style="width: 100%">
													    <?= number_format($tamano_usado) ." MB. ".$porcentaje . "%"; ?>
													  </div>
													<?php } 
													
													if($tamano_grabaciones!=0 && ($porcentaje>=70 && $porcentaje<=100) ){ ?>													
													  <div class="progress-bar progress-bar-danger" style="width: <?= $porcentaje; ?>%" data-toggle="tooltip" data-placement="bottom" title="<?= number_format($tamano_usado)." MB."; ?>">
													    <?= number_format($tamano_usado) ." MB. ".$porcentaje . "%"; ?>
													  </div>
													<?php }
													
													if($tamano_grabaciones==0 && $porcentaje==0) { ?>																								
													  <div class="progress-bar progress-bar-success" style="width: 100%">
													    <? echo number_format($tamano_usado) ." MB.";  ?>
													  </div>
													 <?php }
													 
													 if($tamano_grabaciones!=0 && ($porcentaje>=0 && $porcentaje<70)) { ?>
														<div class="progress-bar progress-bar-success" style="width: <?= $porcentaje; ?>%" data-toggle="tooltip" data-placement="bottom" title="<?= number_format($tamano_usado)." MB."; ?>">
													    <?= number_format($tamano_usado) ." MB. ".$porcentaje . "%"; ?>
													  </div>
													<?php
													}
													
													if($tamano_grabaciones!=0 && $porcentaje===0) { ?>																								
													  <div class="progress-bar progress-bar-success" style="width: 100%">
													    <? echo number_format($tamano_usado) ." MB.";  ?>
													  </div>
													
													<?php }  ?>
													  <div class="progress-bar progress-bar-primary" style="width: <?= $complemento; ?>%">
													    <? if($tamano_grabaciones==0){echo "Libre";}else{ echo $complemento. " %"; }  ?>
													  </div>
												
												</div>
												<?php if($porcentaje!=0 && ($porcentaje>=70 && $porcentaje<100)){ ?>
												<span class="label label-danger">Espacio ocupado</span>
												<?php }else if($tamano_grabaciones!=0 && $porcentaje>=100 ){ ?>
												<span class="label label-danger">Excedido</span>
												<?php } ?>
												
												<?php if($porcentaje!=0 && $porcentaje<70){ ?>
												<span class="label label-success">Espacio ocupado</span>
												<?php } ?>
												<?php if($tamano_grabaciones==0 && $porcentaje<90){ ?>
												<span class="label label-success">Espacio ocupado</span>
												<?php } ?>
												
												<?php if($tamano_grabaciones!=0 && ($complemento>0 && $complemento<=100)){ ?>
												<span class="label label-primary">Espacio Disponible</span>
												<?php } ?>
                                            </td>
                                        
                                            <td class="center"><?= $ubi; ?></td>
                                        </tr>
                                           <?php $porcentaje=0; }  ?>                                 
                                        
                                    </tbody>
                                </table>
	
            
            <div class="panel-footer"><a href="monitoreo_detalle.php">Ver detalle</a></div>


</body>

</html>