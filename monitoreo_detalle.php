<?php
set_time_limit(20);
include "../../ccs/Funciones.inc.php"; //Servidor

		$query1="SELECT id, compania, tamano_grabaciones, tamano_usado, ip_pbx, activo FROM Empresas WHERE activo='1' AND (ip_pbx='10.255.243.10' OR ip_pbx='10.255.249.222') ORDER BY tamano_usado DESC";
		$datos_activo=exe_query($query1,6);
		
		$query2="SELECT id, compania, tamano_grabaciones, tamano_usado, ip_pbx, activo FROM Empresas WHERE activo='0' AND (ip_pbx='10.255.243.10' OR ip_pbx='10.255.249.222') ORDER BY tamano_usado DESC";
		$datos_inactivo=exe_query($query2,6);
		
		$query3="SELECT id, compania, tamano_grabaciones, tamano_usado, ip_pbx, activo FROM Empresas WHERE activo='4' AND (ip_pbx='10.255.243.10' OR ip_pbx='10.255.249.222') ORDER BY tamano_usado DESC";
		$datos_demo=exe_query($query3,6);

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
    	
    	
    	$("#home").click(function () {
		    $("#home").removeClass("active");
		    $("#home").addClass("active");        
		});
		
		$("#menu1").click(function () {
		    $("#menu1").removeClass("active");
		    $("#menu1").addClass("active");        
		});
		
		$("#menu2").click(function () {
		    $("#menu2").removeClass("active");
		    $("#menu2").addClass("active");        
		});
    	 
        $('#dataTables-activos').DataTable({
                responsive: true,
                "paging":   false,
                "order": [[ 2, "desc" ]],  
        });
        $('#dataTables-demo').DataTable({
                responsive: true,
                "paging":   false,
                "order": [[ 2, "desc" ]],  
        });
        $('#dataTables-inactivo').DataTable({
                responsive: true,
                "paging":   false,
                "order": [[ 2, "desc" ]],  
        });
        
        $('#dataTables').DataTable({
                responsive: true,                                  
        		"order": [[ 2, "desc" ]],  
        });
        
	  $('[data-toggle="tooltip"]').tooltip();

       
    });
    </script>
    </head>

<body>
	
	
	     <div class="container" id="tabs">
		  <ul class="nav nav-tabs">
		    <li class="active"><a data-toggle="tab" href="#home">Activos</a></li>
		    <li><a data-toggle="tab" href="#menu1">Demo</a></li>
		    <li><a data-toggle="tab" href="#menu2">Inactivos</a></li>		    
		  </ul>
		
		  <div class="tab-content" >
		    <div id="home" class="tab-pane fade in active">
		      <h3>Activos</h3>
		      	<table class="table table-striped table-hover" id="dataTables-activos">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">Id</th>
                                            <th style="width: 40%;">Cliente</th>
                                            <th style="width: 45%;">Espacio</th>
                                            <th style="width: 10%;">Ubicacion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	<?php foreach ($datos_activo as $row_activo){
                                        	$id=$row_activo['id'];
											$compania=strtoupper ($row_activo['compania']);
											$tamano_grabaciones=$row_activo['tamano_grabaciones'];
											$tamano_usado=$row_activo['tamano_usado'];
											$ip_pbx=$row_activo['ip_pbx'];
											$porcentaje=round(($tamano_usado*(100)/($tamano_grabaciones)));
											$complemento=100-$porcentaje;


											if($porcentaje>=80){
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
                                            <td><?= $compania; ?><br><span class="label label-primary">Espacio configurado <?= $tamano_grabaciones; ?> MB.</span></td>
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
		    </div>
		    <div id="menu1" class="tab-pane fade">
		      <h3>Demo</h3>
		      <table class="table table-striped table-hover" id="dataTables-demo">
                                    <thead>
                                        <tr>
                                           <th style="width: 5%;">Id</th>
                                            <th style="width: 40%;">Cliente</th>
                                            <th style="width: 45%;">Espacio</th>
                                            <th style="width: 10%;">Ubicacion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	<?php foreach ($datos_demo as $row_demo){
                                        	$id=$row_demo['id'];
											$compania=$row_demo['compania'];
											$tamano_grabaciones=$row_demo['tamano_grabaciones'];
											$tamano_usado=$row_demo['tamano_usado'];
											$ip_pbx=$row_demo['ip_pbx'];
											$porcentaje=round(($tamano_usado*(100)/($tamano_grabaciones)));
											$complemento=100-$porcentaje;
											
											if($porcentaje>=80){
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
                                           <?php }  ?>                                 
                                        
                                    </tbody>
                                </table>
		    </div>
		    <div id="menu2" class="tab-pane fade">
		      <h3>Inactivos</h3>
		      <table class="table table-striped table-hover" id="dataTables-inactivo">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">Id</th>
                                            <th style="width: 40%;">Cliente</th>
                                            <th style="width: 45%;">Espacio</th>
                                            <th style="width: 10%;">Ubicacion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	<?php foreach ($datos_inactivo as $row_inactivo){
                                        	$id=$row_inactivo['id'];
											$compania=$row_inactivo['compania'];
											$tamano_grabaciones=$row_inactivo['tamano_grabaciones'];
											$tamano_usado=$row_inactivo['tamano_usado'];
											$ip_pbx=$row_inactivo['ip_pbx'];
											$porcentaje=round(($tamano_usado*(100)/($tamano_grabaciones)));
											$complemento=100-$porcentaje;
											
											if($porcentaje>=80){
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
                                           <?php }  ?>                                 
                                        
                                    </tbody>
                                </table>
		    </div>		    
		  </div>
		</div>  
            

<div class="panel-footer"><a href="monitoreo_espacio.php">Regresar</a></div>
</body>

</html>