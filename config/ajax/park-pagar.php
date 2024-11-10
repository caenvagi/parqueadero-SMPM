<?php
include('../../conexion/database.php');
error_reporting(1);  

if(isset($_POST['placa_cli'])){
    $placa_cli = trim($_POST['placa_cli']);        
    $ticket = $_POST['ticket'];
    $fechaini = $_POST['fechaini'];    
    $fechafin = $_POST['fechafin'];    
    $tiempo_parqueo = $_POST['tiempo'];
    $valor = $_POST['valor'];
    $usuario = $_POST['usuario'];

    $movimiento = $_POST['caja_movimiento'];
    $desc_movimiento = $_POST['caja_desc_movimiento'];   
    $egresos = $_POST['caja_egresos'];
    $liquidado = $_POST['liquidado'];
    $tipo = $_POST['caja_tipo'];    

    $query = "UPDATE parqueo SET estado = 'NO' WHERE placa_cli = '$placa_cli'";
    $result = mysqli_query($connection, $query);    

    $query3 = "    INSERT INTO recibo (placa , ticket, fecha_ini , fecha_fin , tiempo , valor_pagado , usuario)
                    VALUES             ('$placa_cli','$ticket','$fechaini','$fechafin','$tiempo_parqueo','$valor','$usuario')";
    $result3 = mysqli_query($connection, $query3);

    $query4 = "    INSERT INTO caja    (fecha_movimiento , movimiento , desc_movimiento , valor_ingreso , valor_egreso , user_login , liquidado , caja_tipo)
                    VALUES              ('$fechafin','$movimiento','$desc_movimiento','$valor','$egresos','$usuario','$liquidado','$tipo')";
    $result4 = mysqli_query($connection,$query4);
    
    if (!$result) {
        die ('la consulta a fallado en pagar');                
    }    
    echo " ".'ACTUALIZADO en pagar';        
        // header('location:parqueoAjax.php');
}