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
error_reporting(1);

$queryRegistro1 = $mysqli->query("  SELECT * 
                                    FROM arqueo                                    
                                    order by arqueo_id 
                                    DESC LIMIT 1; ");
$row1 = $queryRegistro1->fetch_assoc();


 

if (isset($_POST['register'])) {

    if (strlen($_POST['cajero']) >= 1) {

        $apertura = trim($_POST['apertura']);
        $cajero = trim($_POST['cajero']);
        $inicial = trim($_POST['inicial']);
        $final = trim($_POST['final']);
        $ingresos = trim($_POST['ingresos']);
        $egresos = trim($_POST['egresos']);
        $tot_cierre = trim($_POST['total_cierre']);
        $estado = trim($_POST['estado']);
        $user = trim($_POST['id']);

        if ($row1['estado'] == 'cerrada' || $row1['estado'] == ''){ 

        $consulta = "   INSERT INTO arqueo(fecha_apertura,cajero,monto_inicial,monto_final,total_ingresos,total_egresos,total_cierre,estado,usuario) 
                            VALUES ('$apertura','$cajero','$inicial','$final','$ingresos','$egresos','$tot_cierre','$estado','$user')";
        $resultado = mysqli_query($mysqli, $consulta);
    }
        if ($resultado) {
            header('location:arqueo.php?mensaje=guardado')
?>
        <?php
        } else {
            header('location:arqueo.php?mensaje=falta')
        ?>
        <?php
        }
    } else {
        header('location:arqueo.php?mensaje=nada')
        ?>

        <h3 class="bad">ingrese los datos</h3>
<?php
    }
}
