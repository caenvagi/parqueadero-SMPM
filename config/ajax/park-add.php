<?php

include('../../conexion/conexion.php');
include('../../conexion/conexion3.php');

$queryRegistro2 = $mysqli->query("  SELECT * 
                                    FROM arqueo                                    
                                    order by arqueo_id 
                                    DESC LIMIT 1; ");
$row2 = $queryRegistro2->fetch_assoc();

error_reporting(1);

if ($row2['estado'] == 'abierta'){ 

    if (isset($_POST['placa'])) {

        if (strlen($_POST['nombre']) >= 1) {

            $fecha =  date('y-m-d H:i:s');
            $nombre = trim($_POST['nombre']);
            $celular = trim($_POST['celular']);
            $placa = trim($_POST['placa']);
            $vehiculo = trim($_POST['vehiculo']);
            $categoria = trim($_POST['categoria']);
            $user = trim($_POST['user']);
            $tarifas = trim($_POST['tarifas']);
            $estado = "SI";

            $queryRegistro = $mysqli->query("           SELECT  COUNT(placa) AS placa 
                                                        FROM    cliente 
                                                        WHERE   placa='" . $placa . "' ");
            $row = $queryRegistro->fetch_assoc();

            $queryRegistro1 = $mysqli->query("  SELECT * 
                                                        FROM parqueo
                                                        WHERE placa_cli = '$placa'
                                                        order by estado 
                                                        DESC LIMIT 1; ");
            $row1 = $queryRegistro1->fetch_assoc();

            

    
            // si la placa no exite ingrese los datos a clientes y parqueo
            if (empty($row['placa'])) {

                $consulta3 = "  INSERT INTO cliente(nombre,celular,placa,vehiculo,categoria) 
                                        VALUES ('$nombre','$celular','$placa','$vehiculo','$categoria')";
                $resultado3 =   mysqli_query($mysqli, $consulta3);

                $consulta4 = "  INSERT INTO parqueo(placa_cli, tarifa, usuario, estado) 
                                        VALUES ('$placa','$tarifas','$user','$estado')";
                $resultado4 =   mysqli_query($mysqli, $consulta4);
                
                echo 'guardado cliente y parqueo';
                // header('location:../config/parqueoAjax.php?mensaje=guardado');
                
            } else {
                //si la placa exite actualice los datos del cliente e ingrese el ticket
                if ($row1['estado'] == 'NO' || $row1['estado'] == '') {
                    $sentencia = $bd->prepare(" UPDATE  cliente 
                                                SET     nombre=? , celular=?
                                                WHERE   placa = ?; ");
                    $resultado = $sentencia->execute([$nombre, $celular, $placa]);

                    $consulta5 = "  INSERT INTO parqueo(placa_cli, tarifa, usuario, estado) 
                                    VALUES ('$placa','$tarifas','$user','$estado')";
                    $resultado5 =   mysqli_query($mysqli, $consulta5);

                    echo 'guardado parqueo';
                    // header('location:parqueoAjax.php?mensaje=guardado');
                    
                } else {
                    echo 'existe en parqueo';
                    // header('location:../parqueoAjax.php?mensaje=existe');
                    
                }
                };
            };
        };
}else {
    header('location:arqueo.php?mensaje=abierta');
}