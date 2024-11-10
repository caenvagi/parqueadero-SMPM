<?php

session_start();

require '../conexion/conexion.php';

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
include '../conexion/conexion.php';
include '../conexion/conexion3.php';
error_reporting(1);

$queryRegistro1 = $mysqli->query("  SELECT * 
                                    FROM arqueo                                    
                                    order by arqueo_id 
                                    DESC LIMIT 1; ");
$row1 = $queryRegistro1->fetch_assoc();

 

if (isset($_POST['register'])) {    

        $arqueoId =trim($_POST['arqueo_id']);
        $cierre = trim($_POST['cierre']);
        $ingresos = trim($_POST['ingresos']);
        $egresos = trim($_POST['egresos']);
        $total = trim($_POST['total']);
        $dinero_final = trim($_POST['dinerofinal']);        
        $estado = trim($_POST['estado']);
        $liquidado = trim($_POST['liquidado']);
        

        if ($row1['estado'] == 'abierta'){ 

            $sentencia = $bd->prepare(" UPDATE  arqueo 
                                        SET     fecha_cierre=? , total_ingresos=? , total_egresos=? , total_cierre=? , monto_final=? , estado=?
                                        WHERE   arqueo_id = ?;");
            $resultado = $sentencia->execute([$cierre, $ingresos, $egresos, $total, $dinero_final, $estado, $arqueoId]);

            $sentencia1 = $bd->prepare("    UPDATE  caja 
                                            SET     liquidado=?
                                    
                                        ");
            $resultado1 = $sentencia1->execute([$liquidado]);
    }
    if ($resultado === TRUE) {
        header('location: arqueo.php?mensaje=editado');
        } else {
        header('location: arqueo.php?mensaje=cerrada');
    }
}