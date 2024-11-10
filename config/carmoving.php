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

date_default_timezone_set('America/Bogota');
echo "<link rel='stylesheet' type='text/css' href='../css/styles.css'>";
echo "<link rel='stylesheet' type='text/css' href='../css/estilos.css'>";

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php require '../logs/head.php'; ?>
</head>

<body>
    <?php require '../logs/nav-bar.php'; ?>
    <!-- inicio pagina -->
    <div id="layoutSidenav_content">
        <main>
            <div class="card-header BG-primary mt-1"><b style="color: white;"><i class="fas fa-tachometer-alt" style='font-size:24px'></i>&nbsp;&nbsp;MENSUALIDADES</b></div>
            <div class="container-fluid px-2">
                <div class="container mt-3">
                    <style>
                        #carro {
                            width: 200px;
                            height: 100px;
                            position: relative;
                            animation: moverAutomovil 8s  linear infinite;
                        }

                        #cuerpo {
                            width: 17rem;
                            height: 5rem;
                            position: absolute;
                            bottom: 0px;
                            left:-33px;
                            top:9px;
                            background-image: url(../assets/css/car-removebg-preview.png);
                            background-repeat: no-repeat;
                            background-size: cover;
                            border-radius: 20px;
                        }

                        .rueda {
                            width: 40px;
                            height: 40px;
                            background-color: #555;
                            border-radius: 50%;
                            position: absolute;
                            
                                                }

                        #rueda1 {
                            bottom: 10px;
                            left: 8px;
                            animation: wheelanimation linear .6s  infinite;
                            background-image: url(../assets/css/wheel1-removebg-preview.png);
                            background-size: contain;
                            background-repeat: no-repeat;                       
                        }

                        #rueda2 {
                            bottom: 10px;
                            right: 10px;
                            animation: wheelanimation linear .6s  infinite;
                            background-image: url(../assets/css/wheel2-removebg-preview.png);
                            background-size: cover;
                            background-repeat: no-repeat;
                        }
                        #barra{
                            
                            width: 200px;
                            height: 125px;
                            position: absolute;
                            margin-top: -150px;
                            margin-left: 380px;                            
                            background-image: url(../assets/css/003.png);
                            background-repeat: no-repeat;
                            background-size: cover;
                            animation: barra 4s 1s linear infinite;
                            transform-origin: 180px 50px;
                            z-index: -1;
                        }
                        #estante{                            
                            width: 200px;
                            height: 125px;
                            
                            margin-top: -90px;
                            margin-left: 380px;                            
                            background-image: url(../assets/css/004.png);
                            
                            background-size: cover;
                            
                        }
                        /* Rotation of the wheels */
                        @keyframes moverAutomovil {
                                0% {
                                    left: -200px;
                                }
                                /* 100% {
                                    left: 300px;
                                } */
                                 100% {
                                    left: calc(100% + 100px);
                                }  
                            }
                            @keyframes wheelanimation{                                
                                100% {
                                    transform: rotate(360deg);
                                }
                            }
                            @keyframes barra{
                                0% {                                    
                                    transform: rotate(0pxdeg);
                                }
                                50% {                                    
                                    transform: rotate(45deg);
                                }                                
                                100% {                                    
                                    transform: rotate(90deg);
                                }
                                
                            }
                            
                    </style>

                    <div class="spinner" id="spinner"></div>
                    
                    <div class="carro" id="carro">
                        <div class="cuerpo" id="cuerpo"></div>
                        <div class="rueda" id="rueda1"></div>
                        <div class="rueda" id="rueda2"></div>
                    </div>
                    <div class="estante" id="estante"></div>
                    <div class="barra" id="barra"></div>
                </div>
            </div>
        </main>
        <!-- footer -->
        <?php require '../logs/nav-footer.php'; ?>
        <!-- fin footer -->
    </div>
    
</body>

</html>