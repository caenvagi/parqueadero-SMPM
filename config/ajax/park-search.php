<?php
include ('../../conexion/conexion.php');

date_default_timezone_set('America/Bogota'); 

function conversorSegundosHoras($tiempo_en_segundos)
{
    $anios = floor($tiempo_en_segundos / 31536000);
    $meses = floor(($tiempo_en_segundos / 2592000));
    $month =  ($anios * 12) - $meses;
    $dias = floor($tiempo_en_segundos / 86400);
    $dia = floor(($month * 30) + ($dias - ($anios * 363)));
    $horas = floor($tiempo_en_segundos / 3600);
    $hour = floor($horas - ($dias * 24));
    $minutos = floor(($tiempo_en_segundos - ($horas * 3600)) / 60);
    $segundos = $tiempo_en_segundos - ($horas * 3600) - ($minutos * 60);

    $hora_texto = "";
    if ($anios > 0) {
        $hora_texto .= $anios . " Años ";
    }
    if ($anios = 0) {
        $hora_texto .= $anios . " Años ";
    }
    if ($meses > 0) {
        $hora_texto .= $month . " Meses - ";
    }
    if ($dias > 0) {
        $hora_texto .= $dia . " Dias - ";
    }
    if ($horas > 0) {
        $hora_texto .= $hour . " Horas y ";
    }
    if ($minutos > 0) {
        $hora_texto .= $minutos . " min ";
    }
    return $hora_texto;    
}

$search = $_POST['search'];

if(!empty($search)){
    $query = "  SELECT      parqueo_id,
                            placa_cli,
                            fecha_ini,
                            tarifa,
                            usuario,
                            estado,
                            cat_imagen,
                            tar_tiempo,
                            tar_id_nombre,
                            tar_valor

                FROM        parqueo AS PA
                INNER JOIN  tarifas as TA ON PA.tarifa = TA.tar_id
                INNER JOIN  tar_tiempo as TT ON TA.tar_nombre = TT.tar_id_nombre
                INNER JOIN  categorias as CA ON TA.tar_categoria = CA.cat_id
                WHERE       placa_cli LIKE '$search%' AND estado = 'SI'
                ";

    $result = mysqli_query($mysqli,$query);
        if(!$result){
            die('Error en consulta'. mysqli_error($mysqli));
        }
    
    $DateAndTime = date('Y-m-d G:i:s', time());
    
    $json = array();
        while($row = mysqli_fetch_array($result)){
            $json[] = array( 
                'fecha_ini' => $row['fecha_ini'],                
                'tiempo' => conversorSegundosHoras(strtotime($DateAndTime) - strtotime($row['fecha_ini'])),
                'tarifas' => $row['tar_valor'],
                'cat_imagen' => $row['cat_imagen'],
                'tar_tiempo' => $row['tar_tiempo'],
                'placa_cli' => $row['placa_cli']    
            );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

