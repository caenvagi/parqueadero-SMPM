<?php

session_start();

require '../conexion/conexion.php';

include '../conexion/conexion3.php';

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

// Para guardar el usuario nuevo

error_reporting(1);
function vehiculosLote()
    {
        // Conexión a la base de datos
        $conexion = new mysqli("localhost", "root", "", "parqueadero");
        // Verificar la conexión
        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }
        // Consulta SQL
        $sql = "SELECT      count(parqueo_id) as cantidad,
                                cat_imagen
                    FROM        parqueo AS PA
                    INNER JOIN  tarifas as TA ON PA.tarifa = TA.tar_id
                    INNER JOIN  tar_tiempo as TT ON TA.tar_nombre = TT.tar_id_nombre
                    INNER JOIN  categorias as CA ON TA.tar_categoria = CA.cat_id
                    WHERE       estado = 'SI'
                    ORDER BY    fecha_ini";
        $resultado = $conexion->query($sql);
        // Verificar si la consulta tuvo éxito
        if ($resultado) {
            // Procesar los resultados
            $datos = [];
            while ($fila = $resultado->fetch_assoc()) {
                $datos['cantidad'] = $fila;
            }
            // Cerrar la conexión
            $conexion->close();
            // Retornar los datos obtenidos
            return $datos;
        } else {
            // Cerrar la conexión y retornar null en caso de error
            $conexion->close();
            return null;
        }
    }
function vehiculosHoy()
{
    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "parqueadero");
    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }
    // Consulta SQL
    $sql = "SELECT      count(parqueo_id) as cantidad,
                        cat_imagen,
                        fecha_ini
            FROM        parqueo AS PA
            INNER JOIN  tarifas as TA ON PA.tarifa = TA.tar_id
            INNER JOIN  tar_tiempo as TT ON TA.tar_nombre = TT.tar_id_nombre
            INNER JOIN  categorias as CA ON TA.tar_categoria = CA.cat_id
            WHERE       date(fecha_ini) = date(NOW())
            ORDER BY    fecha_ini";
    $resultado = $conexion->query($sql);
    // Verificar si la consulta tuvo éxito
    if ($resultado) {
        // Procesar los resultados
        $datos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $datos['cantidad'] = $fila;
        }
        // Cerrar la conexión
        $conexion->close();
        // Retornar los datos obtenidos
        return $datos;
    } else {
        // Cerrar la conexión y retornar null en caso de error
        $conexion->close();
        return null;
    }
}
function vehiculosMes()
{
    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "parqueadero");
    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }
    // Consulta SQL
    $sql = "SELECT  COUNT(placa) AS total ,
                    fecha_recibo
            FROM `recibo`
            WHERE MONTH(fecha_recibo) = MONTH(CURDATE())";
    $resultado = $conexion->query($sql);
    // Verificar si la consulta tuvo éxito
    if ($resultado) {
        // Procesar los resultados
        $datos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $datos['total'] = $fila;
        }
        // Cerrar la conexión
        $conexion->close();
        // Retornar los datos obtenidos
        return $datos;
    } else {
        // Cerrar la conexión y retornar null en caso de error
        $conexion->close();
        return null;
    }
}





?>





<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="card text-start col col-lg-3 p-2 m-2">
                <div class="card-body">
                    <h4 class="card-title">vehiculos en lote</h4>
                    <p class="card-text">
                        <?php
                        // require '../function/consultas.php';
                        $datos = vehiculosLote();
                        foreach ($datos as $fila) {
                            echo $fila['cantidad'] . "<br>";
                        }
                        ?>
                    </p>
                </div>
            </div>
            <div class="card text-start col col-lg-3 p-2 m-2">
                <div class="card-body">
                    <h4 class="card-title">vehiculos hoy</h4>
                    <p class="card-text">
                        <?php
                        // require '../function/consultas.php';
                        $datos = vehiculosHoy();
                        foreach ($datos as $fila) {
                            echo $fila['cantidad'] . "<br>";
                        }
                        ?>
                    </p>
                </div>
            </div>
            <div class="card text-start col col-lg-3 p-2 m-2">
                <div class="card-body">
                    <h4 class="card-title">vehiculos mes</h4>
                    <p class="card-text">
                        <?php
                        // require '../function/consultas.php';
                        $datos = vehiculosMes();
                        foreach ($datos as $fila) {
                            echo $fila['total'] . "<br>";
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>


</body>

</html>