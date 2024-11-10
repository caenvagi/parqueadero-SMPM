<?php
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

echo $meses;