<?php
set_time_limit(20);


include "Funciones.inc.php";

exec("du -m /var/spool/asterisk/monitor/",$regresa);


$a=0;

while($regresa[$a]){

$cadena=trim($regresa[$a]);
$pos=strpos($cadena,"/");
$tamano_disco=trim(substr($cadena,0,$pos));

$empresa=explode("_",$cadena);


echo "<br><br><br>-------$cadena-!-----------------".$tamano_disco."-----".$empresa[1];

$a++;
}















exit();
system("wget http://10.255.243.8/ccs/Empresas/Modulos/Audios/call_center/Grabaciones_33/001_prueba-3515.wav");

sleep(1);

system("mv /var/www/html/ccs_ivr/001_prueba-3515.wav /var/lib/asterisk/sounds/call_center/Grabaciones_33/");

echo "listo";

exit();
/*
 $path =  '/var/spool/asterisk/monitor/Inbound_33/';
    //abrimos el directorio
    $dir = opendir($path);
    $a = 0;
    $tamano = 0;

    //hacemos uun cliclo para obtener todos los archivos del directorio
    while ($elemento = readdir($dir)) {
        if (strlen($elemento) > 4)
            $tamano = $tamano + filesize($path . $elemento);
    }


    //Cerramos el directorio
    closedir($dir);

    ECHO '<< '.$tamano.' >>'; */

$id_empresa = 33;

    # Obtiene el tamanio permitido
    $query = " SELECT tamano_grabaciones FROM Empresas WHERE Empresas.id ='" . $id_empresa .
        "'";
    $tamanio_permitido = exe_query($query, 4);
    $td_disco = '';

    # Valida tamanio permitido 0 == No limitado
    switch ($tamanio_permitido) {
        case ($tamanio_permitido === 0):
            return false;
            # $tamano_excedido = 0;
            break;

        case ($tamanio_permitido > 0):
            # Obtiene el tamanio del directorio
          //  $query = " SELECT directorio FROM Voicmail_messages WHERE Voicmail_messages.id_empresa='" .
          //      $id_empresa . "'  GROUP BY directorio";
          //  $path = exe_query($query, 4);
            
            $path =  '/mnt/backup/monitor/Manual_33';
            
            
            if ($path) {
                $disco = exec("du -cah $path | grep total ");
                ECHO '['.$disco.']';
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

ECHO $tamanio_usado;
    #return $tamano_excedido;




?>

