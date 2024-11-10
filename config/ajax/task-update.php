<?php
include('../../conexion/database.php');

if (isset($_GET['placa_cli'])) {

    $placa = $_GET['placa_cli'];   
    $ticket = $_POST['ticket'];
    $fechaini = $_POST['fechaini'];
    $fechafin = $_POST['fechafin'];
    $tiempo = $_POST['tiempo'];
    $valor = $_POST['valor'];
    $usuario = $_POST['usuario'];   

    $movimiento = $_POST['caja_movimiento'];
    $desc_movimiento = $_POST['caja_desc_movimiento'];
    $egresos = $_POST['caja_egresos'];
    $liquidado = $_POST['liquidado'];
    $tipo = $_POST['caja_tipo']; 

    echo $movimiento;

    $query = "UPDATE parqueo SET estado = 'NO' WHERE placa_cli = '$placa'";
    $result = mysqli_query($connection, $query);    

     $query3 = "    INSERT INTO recibo (placa , ticket, fecha_ini , fecha_fin , tiempo , valor_pagado , usuario)
                    VALUES             ('$placa','$ticket','$fechaini','$fechafin','$tiempo','$valor','$usuario')";
     $result3 = mysqli_query($connection, $query3);

    $query4 = " INSERT INTO caja    (fecha_movimiento , movimiento , desc_movimiento , valor_ingreso , valor_egreso , user_login , liquidado , caja_tipo)
                VALUES              ('$fechafin','$movimiento','$desc_movimiento','$valor','$egresos','$usuario','$liquidado','$tipo')";
    $result4 = mysqli_query($connection,$query4);

    if (!$result3) {
        die ('la consulta a fallado');
    
    }

    header('location:../parqueoAjax.php');
}
