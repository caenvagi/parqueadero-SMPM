<?php

include('../../conexion/conexion.php');

date_default_timezone_set('America/Bogota');

session_start();




if (!isset($_SESSION['id'])) {
    header("Location: index.php");
}
$id = $_SESSION['id'];
$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$usuario = $_SESSION['usuario'];
$foto = $_SESSION['avatar'];

if ($tipo_usuario == 1) {
    $where = "";
} else if ($tipo_usuario == 2) {
    $where = "WHERE id=$id";
}




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





$query = "  SELECT      parqueo_id,
                        placa_cli,
                        fecha_ini,
                        tarifa,
                        PA.usuario,
                        estado,
                        cat_imagen,
                        tar_tiempo,
                        tar_id_nombre,
                        nombre,                        
                        tar_valor
            FROM        parqueo AS PA
            INNER JOIN  usuarios as US ON PA.usuario = US.id
            INNER JOIN  tarifas as TA ON PA.tarifa = TA.tar_id
            INNER JOIN  tar_tiempo as TT ON TA.tar_nombre = TT.tar_id_nombre
            INNER JOIN  categorias as CA ON TA.tar_categoria = CA.cat_id
            WHERE       estado = 'SI'
            order by    parqueo_id DESC
            
            ";
$result = mysqli_query($mysqli,$query);

if(!$result){
    die('la consulta ha fallado'. mysqli_error($mysqli));
}

$DateAndTime = date('Y-m-d G:i:s', time());
$json = array();
while($row = mysqli_fetch_array($result)){

    $DateAndTime = date('Y-m-d G:i:s', time());
    $fecha = $row['fecha_ini'];
    $fecha_ini = strtotime($fecha);
    $fecha_fin = strtotime($DateAndTime);
    $tiempo_transcurrido = $fecha_fin - $fecha_ini;
    $tiempo1 = conversorSegundosHoras($tiempo_transcurrido);

    $dias = floor(($tiempo_transcurrido / 3600) / 24);
    $horas = floor($tiempo_transcurrido / 3600);
    $minutos = floor(($tiempo_transcurrido - ($horas * 3600)) / 60);    

    $tarVal = $row['tarifa'];
    $tarifa = $row['tar_valor'];
    $tiempo = $row['tar_tiempo'];
    $tarifasa = $row['tar_id_nombre'];
    
    if ($tarifasa == 1) {
        if ($minutos >= 10) {
            $fraccion = $tarifa;
            $fracciones1 = $fraccion;
        }
        if ($minutos < 10) {
            $fracciones1 = 0;
        }
        if ($horas >= 0) {
            $fraccion = $horas * $tarifa + $fracciones1;
        }
    }
    if ($tarifasa == 2) {
        if ($minutos < 10) {
            $fraccion = 0;
        }
        if ($minutos >= 10) {
            $fraccion = $tarifa;
        }
        if ($horas > 0) {
            $fraccion = (ceil($horas / 12) * $tarifa);
        }
    }
    if ($tarifasa == 3) {
        if ($tarifasa == 3) {
            if (($minutos < 10)) {
                $fraccion = "0";
                $fraccion1 = "0";
            } else
                                                                        if (($minutos >= 10)) {
                $fraccion1 = $tarifa;
            };
            if (($horas > 0) and ($minutos < 10)) {
                $fraccion1 = $tarifa;
            };
            if ($horas >= 0) {
                $fraccion = floor(($horas / 720)) * $tarifa + $fraccion1;
            };
        }
    }


    $json[] = array(  
        'parqueo_id' => $row['parqueo_id'],
        'fecha_ini' => $row['fecha_ini'],
        'fecha_fin' => $DateAndTime,         
        'tiempo' => $tiempo1,
        'valor' => $fraccion,
        'tarifas' => $row['tar_valor'],
        'cat_imagen' => $row['cat_imagen'],
        'tar_tiempo' => $row['tar_tiempo'],
        'estado' => $row['estado'],
        'nombre' => $row['nombre'],
        'usuario' => $id,
        'placa_cli' => $row['placa_cli']  

    );
}
$jsonstring = json_encode($json);
echo $jsonstring;