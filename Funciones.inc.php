<?
////carpeta contenedora del sistema
$root="ccs";
$web="http://104.152.200.251:8089/";
//////mostrar a no los errores de las consultas
$error_mysql=true;
$colores="#FF0000;#0000CC;#6633CC;#006633;#FF6600;#006666;#000000;#663366";
$correo_envio_alertas='ccs@conectatel.com.mx';


function connect_mysql () {
	 
	global $root;
	 
	//if(!$link=mysqli_connect("ccs","Call_Center","Call_C3nt3r@1nf1n1t","Call_Center_Infinit")){
	if(!$link=mysqli_connect("10.255.249.224","Contact_Center","C0nt4ctC3nt3R","Call_Center_Infinit")){
	echo"<script type='text/javascript'>
				window.alert('Error interno del Sistema, acuda con el Administrador del sistema. Error Coneccion Mysql');
				window.open('/".$root."/index.php','_self');
			 </script>";
		exit();
	}

	return $link;
}

$link=connect_mysql();


include "time_zone.php";
include "variables.php";






////////////////////ejecutar consultas ///////////////////////////////////
function exe_query($query,$opcion_row){

	global $error_mysql;
	global $link;

	$result=mysqli_query($link,$query);
	if(! $result){
		 
		if($error_mysql)
			echo "<p>Error en consulta de datos |$query| ".mysqli_error($link);
		else
			echo "<p>Error en consulta de datos";
			
			
		exit();
	}
	else 
	{
		$pattern = '/^insert/i';
		$is_insert = preg_match($pattern,$query, $matches, PREG_OFFSET_CAPTURE);
		if($is_insert == 1)
		{
			$row = mysqli_insert_id($link);
		}
	}
	

	if($opcion_row)
	{
		if($opcion_row==1)
		{
			$row=mysqli_fetch_row($result);
		}


		if($opcion_row==2)
		{
			$row=mysqli_fetch_assoc($result);
		}

		if($opcion_row==3)
		{
			$row=mysqli_num_rows($result);
		}

		if($opcion_row==4)
		{
			list($row)=mysqli_fetch_row($result);
		}


		if($opcion_row==5)
		{
			$a=0;
		 while($registro=mysqli_fetch_row($result)){
		 	$row[$a]=$registro;
		 	$a++;
		 }
		}


		if($opcion_row==6)
		{
		 $a=0;
		 while($registro=mysqli_fetch_assoc($result)){
		 	$row[$a]=$registro;
		 	$a++;
		 }
		}


		if($opcion_row > 0)
			mysqli_free_result($result);

	}
	
	return $row;	

}



/////////////funciones consultas precompiladas
function prepare($consulta){

	global $link;

	if(! $link_prepare= mysqli_prepare($link,$consulta)){
		echo "Error al preparar la consulta | ".$consulta;
		exit();
	}
	return $link_prepare;
}


function exe_prepare($link_prepare){
	
	global $link;
	
	if(! mysqli_stmt_execute($link_prepare)){
		echo "Error al ejecutar consulta preparada | ".mysqli_stmt_error($link_prepare);
		exit();
	}
	return mysqli_insert_id($link);
}

function close_prepare($link_prepare){
	mysqli_stmt_close($link_prepare);
}

///////////////////////////////////////////////////////







function cambianumero($numero)
{
	$resultado =  number_format($numero, 2,'.','');
	return $resultado;
}


/**********funcion que forme un insert y lo ingrese *//////////////////////
function insert($arre1,$arre2,$tope,$tabla){

	$arre1=split(";",$arre1);
	$arre2=split(";",$arre2);



	$a=0;
	$campos=" (";
	$valores=" (";
	while($a < $tope){
		if($a > 0)
	  $campos=$campos.",";
		 
		$campos=$campos.$arre1[$a];
		$a++;
	}
	$campos=$campos.") ";
	/////////////////////
	$a=0;
	while($a < $tope){

		if($a > 0)
	  $valores=$valores.",";
		 
		$valores=$valores."\"".trim(str_replace("\\","",$arre2[$a]))."\"";
		 
		$a++;
	}
	$valores=$valores.") ";

	$query="Insert into $tabla ".$campos." values ".$valores;

	//echo $query;

	return  exe_query($query,"");
}



/**********funcion que forme un insert y lo ingrese *//////////////////////
function update($arre1,$arre2,$tope,$tabla,$id,$valor){


	$arre1=split(";",$arre1);
	$arre2=split(";",$arre2);



	$a=0;
	$cadena="";
	while($a < $tope){
		 
	 if($a > 0)
	 	$cadena=$cadena.",";
	 $cadena=$cadena.$arre1[$a]."=\"".trim($arre2[$a])."\"";
	 $a++;
	}


	$query="Update $tabla set ".$cadena." Where ".$id."='".$valor."'";
	exe_query($query,"");
}





///////////funcion para formar un combo///////////////
function crea_combo($query,$campos,$selected){
	
	$resultado=exe_query($query,6);
	
	$a=0;
	$option="";
	
	while($resultado[$a]){
		
		if($resultado[$a][$campos[0]] == $selected)
		$selec=" selected='selected' ";
		else
		$selec=" ";
		
	$option=$option."<option value='".$resultado[$a][$campos[0]]."' $selec >".$resultado[$a][$campos[1]]."</option>";		
	$a++;	
	}
	
	return $option;
}






///////////funcion para comprobar si un dato esta en un arreglo
function esta_en_array($array,$dato){
	$posicion=-1;
	$a=0;

	while($array[$a]){

		if(trim($array[$a]) == trim($dato)){
			$posicion=$a;
			break;
		}

		$a++;
	}

	return $posicion;
}


/////funcion que regresa un arreglo como cadena///////////
function regresa_cadena($array,$separador){
	$a=0;
	$cadena="";
	 
	while($array[$a]){
	  
		if($a == 0)
			$cadena=$array[$a];

		else
			$cadena=$cadena."$separador".$array[$a];

		$a++;
	}

	return $cadena;

}



////funcion para remplazar espacion balncos por
function quita_espacios($cadena){
	return str_replace(" ","&nbsp;",$cadena);
}

function regresa_estado($estado){
	$estado_actual="No Definido";

	if($estado == 0)
		$estado_actual="Pendiente";

	if($estado == 1)
		$estado_actual="Proceso";

	if($estado == 2)
		$estado_actual="Detenida";

	if($estado == 3)
		$estado_actual="Completada";

	return $estado_actual;
}




///////////////redireccionar a pagina//////////////////
function redireccionar($pagina,$target){

	echo "
			<script type='text/javascript'>
			window.open('".$pagina."','".$target."');
					</script>";

}




///////////////redireccionar a pagina//////////////////
function alerta($leyenda){

	echo "
			<script type='text/javascript'>
			window.alert('".$leyenda."');
					</script>";

}


function atras($numero){

	echo "
			<script type='text/javascript'>
			history.back(".$numeero.")
					</script>";

}




////funcion para limpiar los numeros
function limpia_numero($numero){
	$numero=ereg_replace("[^0-9]", "",$numero);
	return $numero;
}










/////////////funcion para envia correos//////////////////////////
require "/var/www/html/ccs/includes/class.phpmailer.php";

function enviacorreo($host,$puerto,$user,$pass,$from,$name,$asunto,$mensaje,$destinatarios){

	global $varname;
	global $vartemp;

	$mail = new phpmailer();
	$mail->PluginDir = "/var/www/html/ccs/includes/";


	$mail->IsSMTP(); // Usamos el SMTP
	$mail->Host = "$host"; // Servidor SMTP
	$mail->Port = $puerto;
	$mail->SMTPAuth = true;
	$mail->Username   = "$user";     // SMTP server username
	$mail->Password   = "$pass";            // SMTP server password


	$mail->From = "$from";
	$mail->FromName = "$name";
	$mail->Timeout=30;






	$a=0;
	$destinatarios=split(";",$destinatarios);
		
	while($destinatarios[$a]){
		$mail->AddAddress($destinatarios[$a]);
		$a++;
	}
		
	if($varname)
		$mail->AddAttachment($vartemp, $varname);


	$mail->Subject = "$asunto";

	$mensaje_sin_html=$mensaje;
	$mensaje= nl2br($mensaje);



	$mail->Body = "$mensaje";

	$mail->AltBody = "$mensaje_sin_html";

	$exito = $mail->Send();


	$intentos=1;

	while ((!$exito) && ($intentos < 5)) {
		sleep(5);
		//echo $mail->ErrorInfo;
		$exito = $mail->Send();
		$intentos=$intentos+1;

	}


	if(!$exito)
	{
		//echo $mail->ErrorInfo;
		return false;
	}
	 
	else{
		return true;
	}



}


///////////registro de cambio de estado de los agentes
function registro_estado_agente($id_empresa,$id_campana,$id_agente,$estado,$comentarios){

	$query="Insert into Historial_estado_agentes (id_empresa,id_campana,id_agente,id_estado_agente,fecha,comentarios) values ('$id_empresa','$id_campana','$id_agente','$estado',NOW(),'$comentarios')";
	exe_query($query,"");

}


//////////////////ping ////////////7
function ping3($ping)
{
	$comm = "ping -c1 ".$ping;
	$output=shell_exec($comm);
	return $output;
}



////funcion para remplazar espacion balncos por
function quita_comillas($cadena){
	return str_replace("\"","",$cadena);
}









////////////funcion para crear combos///////////////////
function crea_opcion_combo($inicio,$fin,$opciones,$valores,$selected_opc){
	
	
	
	///////////si hay opciones sin valores
	if($opciones != "" && $valores == ""){
	 $opc=explode(";",$opciones);
	    
		$a=0;
		while($opc[$a]){
			
			if($selected_opc != "" && $selected_opc == $opc[$a])
			$select=" selected='selected' ";
			else
			$select="";
			
		echo "<option value='".$opc[$a]."' $select >".$opc[$a]."</option>";
		$a++;	
		}
	}
	
	
	
	
	/////////si hay opciones y hay valores////////////////
	if($opciones != "" && $valores != ""){
	 
	 $opc=explode(";",$opciones);
	 $val=explode(";",$valores);
  
	    
		$a=0;
		while($opc[$a]){
			
			
			if($selected_opc != "" && $selected_opc == $val[$a])
			$select=" selected='selected' ";
			else
			$select="";
			
			
		echo "<option value='".$val[$a]."' $select >".$opc[$a]."</option>";
		$a++;	
		}
	}
	
	
	
	
	/////////si no hay opciones y hay valores//////////////////////
	if($opciones == "" && $valores == ""){
		
		$select="";
	    
		while($inicio <= $fin){
			
			
			if( $selected_opc >= "0")
				  if($selected_opc == $inicio)
				  $select=" selected='selected' ";
				  else
				  $select="";
			else
			$select="";
			
			
			if($inicio < 10)
			$concat="0";
			else
			$concat="";
			
			
		echo "<option value='".$inicio."' $select >$concat".$inicio."</option>";
		$inicio++;	
		}
	}
	
	
	
	
	
	
	
}






///////////////////////envio de correos en detencion de campanas
function envia_correo_detencion($id_campana,$opcion){



	$query="Select nombre,id_empresa from Campanas Where id='".$id_campana."'";
	$campanas=exe_query($query,2);

	$query="Select email2 from Empresas Where id='".$campanas['id_empresa']."'";
	$correos=exe_query($query,4);
	
	$query="Select compania From Empresas Where id='".$campanas['id_empresa']."'";
	$nombre_empresa=exe_query($query,4);
		
	if($correos == "")
		$correos="chernandez@conectatel.com.mx;aramirez@conectatel.com.mx;edorantes@conectatel.com.mx";
		
	$nombre_campana=$campanas['nombre'];
		
		
		
	if($opcion=="cron"){
			
		$query="Insert into Historial_estado_campanas (id_empresa,id_campana,estado,fecha,comentarios) values ('".$campanas['id_empresa']."','".$id_campana."','2',NOW(),'Detencion de la campana por cron')";
		exe_query($query,"");
			
		$mensaje="La Campana ".$nombre_campana." de la empresa <strong>$nombre_empresa</strong> ha sido detenida de manera automatica por el sistema
				 
				La campana estaba en proceso pero no ha respondido desde hace mas de 2 minutos
					
				Inicie de manera manual la campaña o programe su ejecucion
					
				Nota: No responda a este mensaje, ha sido generado de manera automatica";
			
		enviacorreo('ssl://smtp.gmail.com',465,'ccs@conectatel.com.mx','C0n3ctat3l@2012','ccs@conectatel.com.mx','Infinit Call Center Suite','Detencion de Campana: '.$nombre_campana.' por el sistema',$mensaje,$correos);
	}




	if($opcion=="manual"){

		global $empresa;
		global $user;
			
		$query="Insert into Historial_estado_campanas (id_empresa,id_campana,estado,fecha,comentarios) values ('".$empresa->getid()."','".$id_campana."','2',NOW(),'Detencion de la campana por usuario ".$user->getnombre()."')";
		exe_query($query,"");
			
		$mensaje="La Campana ".$nombre_campana." de la empresa <strong>$nombre_empresa</strong> ha sido detenida de manera manual por el usuario ".$user->getnombre()."
					
				Nota: No responda a este mensaje, ha sido generado de manera automatica";
			
		enviacorreo('ssl://smtp.gmail.com',465,'ccs@conectatel.com.mx','C0n3ctat3l@2012','ccs@conectatel.com.mx','Infinit Call Center Suite','Detencion de Campana: '.$nombre_campana.' por usuario',$mensaje,$correos);
	}



	if($opcion=="schedule"){
			
		$query="Insert into Historial_estado_campanas (id_empresa,id_campana,estado,fecha,comentarios) values ('".$campanas['id_empresa']."','".$id_campana."','2',NOW(),'Detencion programada de campana')";
		exe_query($query,"");
			
		$mensaje="La Campana ".$nombre_campana." de la empresa <strong>$nombre_empresa</strong> ha sido detenida de manera programada
				 
				Nota: No responda a este mensaje, ha sido generado de manera automatica";
			
		enviacorreo('ssl://smtp.gmail.com',465,'ccs@conectatel.com.mx','C0n3ctat3l@2012','ccs@conectatel.com.mx','Infinit Call Center Suite','Detencion programada de Campana: '.$nombre_campana.'',$mensaje,$correos);
	}



	if($opcion=="inicio"){
			
		$query="Insert into Historial_estado_campanas (id_empresa,id_campana,estado,fecha,comentarios) values ('".$campanas['id_empresa']."','".$id_campana."','1',NOW(),'Iniciando campana por restauracion del sistema')";
		exe_query($query,"");
			
		$mensaje="La Campana ".$nombre_campana." de la empresa <strong>$nombre_empresa</strong> ha sido reanudada manera automatica por el sistema
				 
				La campana se habia detenido por no estar resgistrandose desde hace 2 minutos
					
				Nota: No responda a este mensaje, ha sido generado de manera automatica";
			
		enviacorreo('ssl://smtp.gmail.com',465,'ccs@conectatel.com.mx','C0n3ctat3l@2012','ccs@conectatel.com.mx','Infinit Call Center Suite','Inicio automatico de Campana: '.$nombre_campana.' por el sistema',$mensaje,$correos);
	}



}









function regresa_tamano_directorio($path){
	
			  //abrimos el directorio
			  $dir = opendir($path);
			  $a=0;
			  $tamano=0;

			//hacemos uun cliclo para obtener todos los archivos del directorio
			while ($elemento = readdir($dir)) {
				if(strlen($elemento) > 4)
				$tamano = $tamano + filesize($path . $elemento);
			}

			  
			  //Cerramos el directorio
			  closedir($dir);
	
	 return $tamano;
}





function valida_args($valor, $cantidad = 0, $entero = 0) {

    global $link;

    $valor = mysqli_real_escape_string($link, $valor);
    if ($cantidad > 0) {

        if (strlen($valor) > $cantidad) {
            $valor = "";
        }
    }

    if (!is_numeric($valor) && $entero == 1) {
        $valor = "";
    }


    return $valor;
}

function getIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
        return $_SERVER['HTTP_CLIENT_IP'];

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        return $_SERVER['HTTP_X_FORWARDED_FOR'];

    return $_SERVER['REMOTE_ADDR'];
}

function log_e($id_empresa, $evento, $interfaz, $extra = '', $id_extra = '', $idUsuario) {

    if ($id_empresa > 0)
        ajusta_time_zone($id_empresa);
    //$idUsuario=$_SESSION['id_usuario'];
    $ip = getIP();
    $query = 'INSERT INTO Log_sistema(id_empresa,idUsuario,tiempo,evento,ip,interfaz,comentarios,detalle) VALUES (' . $id_empresa . ',' . $idUsuario . ',now(),\'' . $evento . '\',\'' . $ip . '\',' . $interfaz . ',"' . $extra . '", "' . $id_extra . '")';
    exe_query($query, "");
}

function regresa_espacio_directorio($id_empresa) {

    # Obtiene el tamanio permitido
    $query = " SELECT tamano_grabaciones FROM Empresas WHERE Empresas.id ='" . $id_empresa . "'";
    $tamanio_permitido = exe_query($query, 4);

    # Valida tamanio permitido 0 == No limitado
    switch ($tamanio_permitido) {
        case ($tamanio_permitido === 0):
            return false;
            # $tamano_excedido = 0;
            break;

        case ($tamanio_permitido > 0):
            # Obtiene el tamanio del directorio
            $query = " SELECT directorio FROM Voicmail_messages WHERE Voicmail_messages.id_empresa='" . $id_empresa . "'  GROUP BY directorio";
            $path = exe_query($query, 4);
            if ($path) {
                $disco = exec("du -cah $path | grep total ");
                $disco = explode("total", $disco);

                $cadena = $disco[0];
                $t_cadena = strlen($cadena);
                # Obtiene tipo medicion
                for ($i = 0; $i < $t_cadena; $i++) {

                    if ($cadena[$i] == "M") {
                        break;
                    }
                    if ($cadena[$i] == "K") {
                        $td_disco = ($td_disco * 1024) / 1048576;
                        break;
                    }
                    if ($cadena[$i] == "G") {
                        $td_disco = $td_disco * 1024;
                        break;
                    }
                    $td_disco .= $cadena[$i];
                }

                #Calcula tamanio disponible
                $tamanio_usado = ($td_disco * 100) / $tamanio_permitido;
                #Espacio en disco > al 30%
                if ($tamanio_usado >= 70) {
                    #ECHO 'excedido['.$tamanio_usado.']';
                    #$tamano_excedido = 1;
                    return true;
                } else {
                    #$tamano_excedido = 0;
                    return false;
                }
                break;
            } else {
                # $tamano_excedido = 0;
                return false;
                break;
            }

        default:
            # ECHO 'in dflt';
            # $tamano_excedido = 0;
            return false;
    }

    #return $tamano_excedido;
}





?>
