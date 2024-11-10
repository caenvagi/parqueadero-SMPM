<?php
    session_start();
    date_default_timezone_set('America/Bogota');
    echo "<link rel='stylesheet' type='text/css' href='../css/styles.css'>";
    echo "<link rel='stylesheet' type='text/css' href='../css/estilos.css'>";


    require '../conexion/conexion.php';

    if (!isset($_SESSION['id'])) {
        header("Location: ../../index.php");
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

    // header('Content-Type: text/html; charset=ISO-8859-1');


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

    // inicio consultas
    $query = "  SELECT  *
                        FROM    categorias
            ";
    $categorias = $mysqli->query($query);

    $query = "      SELECT  *
                    FROM        parqueo AS PA
                    INNER JOIN  tarifas as TA ON PA.tarifa = TA.tar_id
                    INNER JOIN  tar_tiempo as TT ON TA.tar_nombre = TT.tar_id_nombre
                    INNER JOIN  categorias as CA ON TA.tar_categoria = CA.cat_id
                    INNER JOIN  cliente AS CL   ON  CL.placa = PA.placa_cli
                    WHERE       estado = 'SI'
                    ORDER BY    fecha_ini
                    
            ";
    $entradas = $mysqli->query($query);

    $query = "              SELECT      *
                            FROM        recibo      AS RE
                            INNER JOIN  cliente     AS CL   ON CL.placa     = RE.placa
                            INNER JOIN  categorias  AS CA   ON CA.cat_id    = CL.categoria
                            INNER JOIN  usuarios    AS US   ON US.id        = RE.usuario              
                            ORDER BY    fecha_ini   desc
                ";
    $parqueoUlt1 = $mysqli->query($query);


    $query_catTotal = "     SELECT      cat_imagen,
                                        cat_nombre,
                                        sum(valor_pagado) as valor_pagado
                            FROM        recibo     AS RE
                            INNER JOIN  cliente    AS CL   ON CL.placa    = RE.placa
                            INNER JOIN  categorias AS CA   ON CA.cat_id = CL.categoria                   
                            WHERE      	MONTH(fecha_recibo)=MONTH(CURDATE())                  
                            GROUP BY    categoria
                            ";
    $totalCat = $mysqli->query($query_catTotal);

    $query_ventasTotal = "  SELECT      fecha_recibo,
                                        sum(valor_pagado) as valor_pagado
                            FROM        recibo     AS RE
                            INNER JOIN  cliente    AS CL   ON CL.placa    = RE.placa
                            INNER JOIN  categorias AS CA   ON CA.cat_id = CL.categoria                   
                            WHERE      	MONTH(fecha_recibo)=MONTH(CURDATE())                  
                            
                            ";
    $totalVentas = $mysqli->query($query_ventasTotal);

    $query = "  SELECT      count(parqueo_id) as cantidad,
                            cat_imagen
                FROM        parqueo AS PA
                INNER JOIN  tarifas as TA ON PA.tarifa = TA.tar_id
                INNER JOIN  tar_tiempo as TT ON TA.tar_nombre = TT.tar_id_nombre
                INNER JOIN  categorias as CA ON TA.tar_categoria = CA.cat_id
                WHERE       estado = 'SI'
                ORDER BY    fecha_ini
                
        ";
    $cantVeh = $mysqli->query($query);

    $query = "  SELECT      count(parqueo_id) as cantidad,
                            cat_imagen,
                            fecha_ini
                FROM        parqueo AS PA
                INNER JOIN  tarifas as TA ON PA.tarifa = TA.tar_id
                INNER JOIN  tar_tiempo as TT ON TA.tar_nombre = TT.tar_id_nombre
                INNER JOIN  categorias as CA ON TA.tar_categoria = CA.cat_id
                WHERE       date(fecha_ini) = date(NOW())
                ORDER BY    fecha_ini
                
        ";
    $cantVehdia = $mysqli->query($query);

    $query = "      SELECT fecha_ini 
                    FROM `parqueo` 
                    ORDER BY fecha_ini
                    DESC LIMIT 1;
                    
            ";
    $actualizacion = $mysqli->query($query);

    $query = "      SELECT fecha_ini 
                    FROM `parqueo` 
                    ORDER BY fecha_ini
                    DESC LIMIT 1;
                    
            ";
    $actualizacion1 = $mysqli->query($query);

    $query = "      SELECT fecha_ini 
                    FROM `parqueo` 
                    ORDER BY fecha_ini
                    DESC LIMIT 1;
                    
            ";
    $actualizacion2 = $mysqli->query($query);

    $query = "      SELECT  COUNT(placa) AS total ,
                        fecha_recibo
                        FROM `recibo`
                        WHERE MONTH(fecha_recibo) = MONTH(CURDATE())
                    
            ";
    $totalundmes = $mysqli->query($query);

    // fin consultas
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php require '../logs/head.php'; ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.4/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.dataTables.css">
    <script src="../js/funcion.js"></script>
</head>
<body  onload="mueveReloj()" >
    <?php require '../logs/nav-bar.php'; ?>
    <!-- inicio pagina -->
        <div id="layoutSidenav_content" class="bg-light">
            <main class="principal" >        
                <div class="loader" id="loader">
                    <div class="car"></div>
                    <div class="caseta"></div>
                    <div class="bar"></div>
                </div>
                
                <!--card datos del parqueadero --------------------------------->
                    <div id="row_informacion_2" class="container mt-3 text-white align-content-center align-items-center text-align-center justify-content-center" >
                        
                        <?php
                        while ($fila = $cantVeh->fetch_array()) {
                            $cant = $fila['cantidad'];
                            $catImg = $fila['cat_imagen'];
                        ?>
                        <div id="cards_informacion_1" class="rounded" >
                            <div class="row" >
                                <div class="col-3 col-sm-3 col-md-3 col-lg-4 col-xl-4" id="cards_informacion_logo">
                                    <i class='fas fa-road' id="cards_informacion_logo" style='font-size:50px'></i>
                                </div>
                                <div class="col-8 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                    <div class="card-body" id="cards_informacion_lote">
                                        <h6 class="card-title">Vehiculos en lote</h6>
                                        <h1 class="card-text"><?php echo $cant; ?></h1>
                                        <?php
                                        while ($fila = $actualizacion->fetch_array()) {
                                            $fecha = $fila['fecha_ini'];

                                            $DateAndTime = date('Y-m-d G:i:s', time());
                                            $fecha_ini = strtotime($fecha);
                                            $fecha_fin = strtotime($DateAndTime);
                                            $tiempo_transcurrido = $fecha_fin - $fecha_ini;

                                            $tiempo2 = conversorSegundosHoras($tiempo_transcurrido);
                                            $dias = floor(($tiempo_transcurrido / 3600) / 24);
                                            $horas = floor($tiempo_transcurrido / 3600);
                                            $minutos = floor(($tiempo_transcurrido - ($horas * 3600)) / 60);
                                            ?>
                                        <p class="card-text text-center"><small class="">Ultima actualizacion: 
                                            <br><?php echo $tiempo2; ?></small></p>
                                        <?php } ?>   
                                        </div>
                                </div>
                            </div>
                        </div>                   
                        <?php } ?>

                        <?php
                        while ($fila = $cantVehdia->fetch_array()) {
                            $cant = $fila['cantidad'];
                            $catImg = $fila['cat_imagen'];
                        ?>
                        <div id="cards_informacion_2" class="rounded" >
                            <div class="row" >
                                <div class="col-3 col-sm-3 col-md-3 col-lg-4 col-xl-4" id="cards_informacion_logo">
                                    <i class='bi bi-p-square-fill' id="cards_informacion_logo" style='font-size:50px'></i>
                                </div>
                                <div class="col-8 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                    <div class="card-body" id="cards_informacion_lote">
                                        <h6 class="card-title text-center">Vehiculos hoy</h6>
                                        <h1 class="card-text text-center"><?php echo $cant; ?></h1>
                                        <?php
                                        while ($fila = $actualizacion1->fetch_array()) {
                                            $fecha = $fila['fecha_ini'];

                                            $DateAndTime = date('Y-m-d G:i:s', time());
                                            $fecha_ini = strtotime($fecha);
                                            $fecha_fin = strtotime($DateAndTime);
                                            $tiempo_transcurrido = $fecha_fin - $fecha_ini;

                                            $tiempo2 = conversorSegundosHoras($tiempo_transcurrido);
                                            $dias = floor(($tiempo_transcurrido / 3600) / 24);
                                            $horas = floor($tiempo_transcurrido / 3600);
                                            $minutos = floor(($tiempo_transcurrido - ($horas * 3600)) / 60);
                                            ?>
                                        <p class="card-text text-center"><small class="">Ultima actualizacion:
                                            <br><?php echo $tiempo2; ?></small></p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>                  
                        <?php } ?>

                        <div id="cards_informacion_3" class="rounded" >
                            <div class="row" >
                                <div class="col-3 col-sm-3 col-md-3 col-lg-4 col-xl-4" id="cards_informacion_logo">
                                    <i class='bi bi-car-front'  id="cards_informacion_logo" style='font-size:50px'></i>
                                </div>
                                <div class="col-8 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                    <div class="card-body" id="cards_informacion_lote">
                                    <?php
                                        while ($fila = $totalundmes->fetch_array()) {
                                            $totalmes = $fila['total']; ?>
                                        <h6 class="card-title text-center">Vehiculos Mes</h6>
                                        <h1 class="card-text text-center"><?php echo $totalmes; ?></h1>
                                        <?php } ?>
                                        <?php
                                        while ($fila = $actualizacion2->fetch_array()) {
                                            $fecha = $fila['fecha_ini'];

                                            $DateAndTime = date('Y-m-d G:i:s', time());
                                            $fecha_ini = strtotime($fecha);
                                            $fecha_fin = strtotime($DateAndTime);
                                            $tiempo_transcurrido = $fecha_fin - $fecha_ini;

                                            $tiempo2 = conversorSegundosHoras($tiempo_transcurrido);
                                            $dias = floor(($tiempo_transcurrido / 3600) / 24);
                                            $horas = floor($tiempo_transcurrido / 3600);
                                            $minutos = floor(($tiempo_transcurrido - ($horas * 3600)) / 60);
                                            ?>
                                        <p class="card-text text-center"><small class="">Ultima actualizacion: 
                                            <br> <?php echo $tiempo2; ?></small></p>
                                        <?php } ?> 
                                    </div>
                                </div>
                            </div>
                        </div> 
                        
                    </div>
                <!--card datos del parqueadero --------------------------------->                        

                <!--card datos vehiculos dentro del parqueadero --------------------------------->
                    <div id="cardsDash" class="col col-xl-12 m-3" >
                        <div class="row">                    
                        </div>
                    </div> 
                <!--card datos vehiculos dentro del parqueadero ------------------------------- -->

                <!--grafico ventas -->
                    <div class="card m-3 border border-1 rounded-3">
                        <div class="container-fluid px-4">
                            <h4 class="mt-2 text-center text-grey">
                                <i class='far fa-chart-bar' style='font-size:18x'></i>&nbsp;&nbsp;Grafico de ventas por año
                            </h4>
                            <div class="row">
                                <div class="container-fluid px-3">
                                    <div class="row">

                                        <div class="col-xl-6 col-md-6 mt-2">
                                            <div class="cardes text-black m-1 border border-dark border border-1 rounded-3" style="background-color: #faf7f8" 0>
                                                <div class="">&nbsp &nbsp Grafico ventas $ por mes </div>

                                                <div class="card-body m-1 p-2">
                                                    <canvas id="grafica2"></canvas>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-md-6 mt-2">
                                            <div class="cardes text-black m-1 border border-dark border border-1 rounded-3" style="background-color: #faf7f8" 0>
                                                <div class="">&nbsp &nbsp Grafico unidades por mes </div>

                                                <div class="card-body m-1 p-2">
                                                    <canvas id="grafica3"></canvas>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!--grafico ventas -->

                <!--card ventas por categoria  2-->
                    <div class="card">
                        <div class="card-header text-center">
                            <h4><span class="material-icons">category</span>&nbsp;&nbsp;
                                Ventas por categoria <?php setlocale(LC_TIME, "spanish");
                                                            echo strftime("%B"); ?>
                        </div>
                        </h4>
                        <ul class="ventasCat">
                            <?php foreach ($totalCat as $total) { ?>
                                <li class="cat">
                                    <img class="logocat1" src="<?php echo $total['cat_imagen']; ?>"></img>
                                    <span class="info">
                                        <h7>$<?php echo number_format($total['valor_pagado'], 0, ",", "."); ?></h7>
                                        <p><?php echo $total['cat_nombre'] ?></p>
                                    </span>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                <!--card ventas por categoria -->

                <!--ventas diarias por mes-->
                    <div class="card m-3">
                        <div class="card-header text-center">
                            <h4 class="mt-2 text-center text-grey"> Ingreso Mensual <?php setlocale(LC_TIME, "spanish");
                                                                                            echo strftime("%B"); ?></h4>
                        </div>
                        <div class="card-body">
                            <table id="ventasdiarias" class="display nowrap table-hover table-sm" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>CATEGORIA</th>
                                        <th>PLACA</th>
                                        <th>ENTRADA</th>
                                        <th>SALIDA</th>
                                        <th>ESTADIA</th>
                                        <th>VALOR</th>
                                        <th>USER</th>
                                    </tr>

                                </thead>

                                <tbody class="">
                                    <?php
                                    while ($fila = $parqueoUlt1->fetch_array()) {
                                        $idparqueo = $fila['recibo_id'];
                                        $placa = $fila['placa'];
                                        $categoria = $fila['categoria'];
                                        $catnombre = $fila['cat_nombre'];
                                        $fechaini = $fila['fecha_ini'];
                                        $fechafin = $fila['fecha_fin'];
                                        $usuario = $fila['usuario'];
                                        $valor = $fila['valor_pagado'];
                                        $estadia = $fila['tiempo'];
                                    ?>
                                        <tr class="">
                                            <td><?php echo $idparqueo ?></td>
                                            <td><?php echo $catnombre; ?></td>
                                            <td><?php echo $placa ?></td>
                                            <td><?php echo $fechaini; ?></td>
                                            <td><?php echo $fechafin ?></td>
                                            <td><?php echo $estadia ?></td>
                                            <td>$<?php echo number_format($valor, 0, ",", ".") ?></td>
                                            <td><?php echo $usuario ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <!--ventas diarias por mes-->
                
            </main>
            <?php require '../logs/nav-footer.php'; ?>
        </div>
    <!-- FIN pagina -->
    <script src="https://cdn.datatables.net/2.0.4/js/dataTables.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.dataTables.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@latest/dist/Chart.min.js"></script>
    <script>
        // Obtener una referencia al elemento canvas del DOM
        const $grafica2 = document.querySelector("#grafica2");
        // Las etiquetas son las que van en el eje X.
        const etiquetas1 = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC']
        // Podemos tener varios conjuntos de datos. Comencemos con uno
        const datosVentas1 = {
            label: "<?php setlocale(LC_TIME,"spanish"); echo strftime("%Y", strtotime("- 1 YEAR")); ?>",
            data: [<?php $sql = "	SELECT 
                                                p.PeriodoId,
                                                p.Nombre,
                                                ifnull(sum(v.valor_pagado),0) as total
                                    from        Periodos P
                                    left join   recibo v on P.PeriodoId = year(v.fecha_recibo)*100 + month(v.fecha_recibo)
                                    where       anio = YEAR(DATE_SUB(NOW(),INTERVAL 1 YEAR))
                                    group by    p.PeriodoId,
                                                p.Nombre;
                                    ";
                                    $result = mysqli_query($mysqli, $sql);
                                    while ($registros = mysqli_fetch_array($result)) {
                                    ?> '<?php echo $registros["total"] ?>',
                                    <?php
                                    }
                                    ?>], // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
            backgroundColor: 'rgba(54, 10, 250, 0.2)', // Color de fondo
            borderColor: 'rgba(54, 45, 250, 1)', // Color del borde
            borderWidth: 1, // Ancho del borde
        };
        const datosVentas2 = {
            label: "<?php setlocale(LC_TIME,"spanish"); echo strftime("%Y"); ?>",
            data: [<?php $sql = "   SELECT 
                                                p.PeriodoId,
                                                p.Nombre,
                                                ifnull(sum(v.valor_pagado),0) as total
                                    from        Periodos P
                                    left join   recibo v on P.PeriodoId = year(v.fecha_recibo)*100 + month(v.fecha_recibo)
                                    where       anio = YEAR(NOW())
                                    group by    p.PeriodoId,
                                                p.Nombre;
                                    ";
                                    $result = mysqli_query($mysqli, $sql);
                                    while ($registros = mysqli_fetch_array($result)) {
                                    ?> '<?php echo $registros["total"] ?>',
                                    <?php
                                    }
                                    ?>], // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
            backgroundColor: 'rgba(245, 115, 158, 0.5)', // Color de fondo
            borderColor: 'rgba(245, 115, 158, 1)', // Color del borde
            borderWidth: 1, // Ancho del borde
        };
        new Chart($grafica2, {
            type: 'line', // Tipo de gráfica
            data: {
                labels: etiquetas1,
                datasets: [
                    datosVentas1,
                    datosVentas2,
                    // Aquí más datos...
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }],
                },
            }
        });
    </script>
    <script>
        // Obtener una referencia al elemento canvas del DOM
        const $grafica3 = document.querySelector("#grafica3");
        // Las etiquetas son las que van en el eje X.
        const etiquetas3 = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC']
        // Podemos tener varios conjuntos de datos. Comencemos con uno
        const datosVentas3 = {
            label: '<?php setlocale(LC_TIME,"spanish"); echo strftime("%Y", strtotime("- 1 YEAR")); ?>',
            data: [<?php $sql = "	SELECT 
                                                p.PeriodoId,
                                                p.Nombre,
                                                ifnull(COUNT(recibo_id),0) as recibo
                                    from        Periodos P
                                    left join   recibo v on P.PeriodoId = year(v.fecha_recibo)*100 + month(v.fecha_recibo)
                                    where       anio = YEAR(DATE_SUB(NOW(),INTERVAL 1 YEAR))
                                    group by    p.PeriodoId,
                                                p.Nombre;
                                    ";
                                    $result = mysqli_query($mysqli, $sql);
                                    while ($registros = mysqli_fetch_array($result)) {
                                    ?> '<?php echo $registros["recibo"] ?>',
                                    <?php
                                    }
                                    ?>], // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
            backgroundColor: 'rgba(199, 248, 201, 0.8)', // Color de fondo
            borderColor: 'rgba(17, 248, 27, 0.8)', // Color del borde
            borderWidth: 1, // Ancho del borde
        };
        const datosVentas4 = {
            label: '<?php setlocale(LC_TIME,"spanish"); echo strftime("%Y"); ?>',
            data: [<?php $sql = "   SELECT 
                                                p.PeriodoId,
                                                p.Nombre,
                                                ifnull(COUNT(recibo_id),0) as recibo
                                    from        Periodos P
                                    left join   recibo v on P.PeriodoId = year(v.fecha_recibo)*100 + month(v.fecha_recibo)
                                    where       anio = YEAR(NOW())
                                    group by    p.PeriodoId,
                                                p.Nombre;
                                    ";
                                    $result = mysqli_query($mysqli, $sql);
                                    while ($registros = mysqli_fetch_array($result)) {
                                    ?> '<?php echo $registros["recibo"] ?>',
                                    <?php
                                    }
                                    ?>], // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
            backgroundColor: 'rgba(246, 133, 131,1)', // Color de fondo
            borderColor: 'rgba(251, 11, 6, 0.8)', // Color del borde
            borderWidth: 1, // Ancho del borde
        };
        new Chart($grafica3, {
            type: 'bar', // Tipo de gráfica
            data: {
                labels: etiquetas3,
                datasets: [
                    datosVentas3,
                    datosVentas4,
                    // Aquí más datos...
                ]
            },
            options: {
                responsive: true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }],
                },
            }
        });
    </script>
    <script>
        $(document).ready(function() {
                $('#ventasdiarias').DataTable({
                    responsive: true,
                    language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                            },
                        },
                    "order": [[ 0, "desc" ]],
                    'pageLength': 10,
                    
                        
                    });
                                     
                });
    </script>
    <script>
        //Efecto Pre-Carga
        $(document).ready(function() {
            $(window).on("load", function() {
                        $(".loader").fadeOut(3200);
                    });
        });
    </script>
</body>
</html>