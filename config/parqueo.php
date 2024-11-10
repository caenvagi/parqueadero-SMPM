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

date_default_timezone_set('America/Bogota');


echo "<link rel='stylesheet' type='text/css' href='../css/cargando.css'>";


// Para guardar el CLIENTE nuevo
include '../conexion/conexion.php';

$queryRegistro2 = $mysqli->query("  SELECT * 
                                                FROM arqueo                                    
                                                order by arqueo_id 
                                                DESC LIMIT 1; ");
            $row2 = $queryRegistro2->fetch_assoc();

error_reporting(1);

if ($row2['estado'] == 'abierta'){ 

    if (isset($_POST['register'])) {

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

                header('location:parqueo.php?mensaje=guardado');
            } else {
                //si la placa exite actualice los datos del cliente e ingrese el ticket
                if ($row1['estado'] == 'NO' || $row1['estado'] == '') {
                    $sentencia = $bd->prepare(" UPDATE  cliente 
                                                SET     nombre=? , celular=?
                                                WHERE   placa = ?; ");
                    $resultado = $sentencia->execute([$nombre, $celular, $placa]);

                    $consulta4 = "  INSERT INTO parqueo(placa_cli, tarifa, usuario, estado) 
                                    VALUES ('$placa','$tarifas','$user','$estado')";
                    $resultado4 =   mysqli_query($mysqli, $consulta4);

                    header('location:parqueo.php?mensaje=guardado');
                } else {
                    header('location:parqueo.php?mensaje=existe');
                }
                };
            };
        };
}else {
    header('location:arqueo.php?mensaje=abierta');
}


// Consultas
$query = "  SELECT  * 
            FROM    categorias
                            ";
$categorias = $mysqli->query($query);

$query = "  SELECT  * 
            FROM    categorias
                            ";
$editarCat = $mysqli->query($query);

$query = "  SELECT      parqueo_id, 
                        placa_cli, 
                        fecha_ini,
                        cat_nombre,
                        tar_tiempo,
                        tar_valor,
                        usuario
            FROM        parqueo as PA
            INNER JOIN  cliente as CL on PA.placa_cli = CL.placa
            INNER JOIN  categorias as CA ON CL.categoria = CA.cat_id
            INNER JOIN 	tarifas as TA ON PA.tarifa = TA.tar_id
            INNER JOIN  tar_tiempo as TT ON TA.tar_nombre = TT.tar_id_nombre
            order by    parqueo_id desc
            limit 1
            ";
$parqueoUlt = $mysqli->query($query);

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

$query = "  SELECT  *
            FROM        parqueo AS PA
            INNER JOIN  tarifas as TA ON PA.tarifa = TA.tar_id
            INNER JOIN  tar_tiempo as TT ON TA.tar_nombre = TT.tar_id_nombre
            INNER JOIN  categorias as CA ON TA.tar_categoria = CA.cat_id
            WHERE       estado = 'SI'
            ORDER BY    fecha_ini
            
    ";
$entradas = $mysqli->query($query);

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
$cantVehdia = $mysqli->query($query)

// Consultas
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php require '../logs/head.php'; ?>
</head>

<body onload="mueveReloj()">
    <?php require '../logs/nav-bar.php'; ?>
    <div class="cargando">
        <div class="loader-outter"></div>
        <div class="loader-inner"></div>
    </div>

    <!-- inicio pagina -->
    <div id="layoutSidenav_content">
        <main>
            <div class="card-header BG-primary mt-2"><b style="color: white;"><i class="fas fa-tachometer-alt" style='font-size:24px'></i>&nbsp;&nbsp;Ingreso Vehiculos</b></div>

            <!-- inicio de alertas -->
            <section>
                <!-- inicio de recibo -->
                <?php
                if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'recibo') {
                ?>
                    <div class="alerta alert alert-success alert-dismissible fade show text-center" role="alert">
                        <h5><i class="far fa-thumbs-up" style="font-size:24px"></i>&nbsp;&nbsp;<strong>Ok !</strong>Recibo generado.</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                }
                ?>
                <!-- inicio de parqueo -->
                <?php
                if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'parqueo') {
                ?>
                    <div class="alerta alert alert-success alert-dismissible fade show text-center" role="alert">
                        <h5><i class="far fa-thumbs-up" style="font-size:24px"></i>&nbsp;&nbsp;<strong>Ok !</strong>Ticket generado.</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                }
                ?>
                <!-- inicio de falta -->
                <?php
                if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'nada') {
                ?>
                    <div class="alerta alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error !</strong> Ingresa todos los datos
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                }
                ?>
                <!-- Mensaje guardado -->
                <?php
                if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'guardado') {
                ?>
                    <div class="alerta alert alert-success alert-dismissible fade show text-center mb-1" role="alert">
                        <h5><i class="far fa-thumbs-up" style="font-size:24px"></i>&nbsp;&nbsp;<strong>Ok !</strong>&nbsp;&nbsp;Ticket y Vehiculo registrado.</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                }
                ?>
                <!-- Mensaje falta -->
                <?php
                if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'falta') {
                ?>
                    <div class="alerta alert alert-warning alert-dismissible fade show text-center" role="alert">
                        <h5><strong>Error !</strong> falta Vehiculo ya esta registrado y se encuentra dentro del parqueadero!</h5>
                        Intenta de nuevo
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                }
                ?>
                <!-- Mensaje existe -->
                <?php
                if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'existe') {
                ?>
                    <div class="alerta alert alert-warning alert-dismissible fade show text-center" role="alert">
                        <h5><strong>Error! </strong> Vehiculo ya esta registrado y se encuentra dentro del parqueadero!</h5>
                        Intenta de nuevo
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                }
                ?>
                <!-- Mensaje no funcona -->
                <?php
                if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'nofunciona') {
                ?>
                    <div class="alerta alert alert-warning alert-dismissible fade show text-center" role="alert">
                        <h5><strong>Error !</strong> no funciona la inserecion de datos!</h5>
                        Intenta de nuevo
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                }
                ?>
                <!-- Mensaje error -->
                <?php
                if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'error') {
                ?>
                    <div class="alerta alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error !</strong> Vuelve a intentar!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                }
                ?>
                <!-- Mensaje actualizar -->
                <?php
                if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'actualizado') {
                ?>
                    <div class="alerta alert alert-primary alert alert-dismissible fade show" role="alert">
                        <h5><i class="far fa-thumbs-up" style="font-size:24px"></i>&nbsp;&nbsp;<strong>Ok !</strong>Los datos del propietario fueron actualizados.</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                }
                ?>
                <!-- Mensaje eliminar -->
                <?php
                if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'eliminado') {
                ?>
                    <div class="alerta alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Eliminar :</strong> El registro fue eliminado.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                }
                ?>
                <!-- Mensaje informe diario -->
                <?php
                if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'informe') {
                ?>
                    <div class="alerta alert alert-success alert alert-dismissible fade show" role="alert">
                        <strong>
                            <h2 class="text-center">INFORME DIARIO C.E Y RECAUDO</h2>
                        </strong>
                        <h3 class="text-center">Ha sido generado</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                }
                ?>

            </section>
            <!-- fin alertas -->

            <div id="respuestas" name="respuestas"> </div>

            <div class="container" id="cont-parqueo1">
                <div class="row">
                    <!-- formulario ingresar parqueo-->
                    <div class="col col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 m-3" >
                    <form id="parqueo" name="parqueo" action="parqueo.php" method="POST">
                        <div class="input-group mb-2">
                            <input type="hidden" value="" class="form-control" id="parqueo_id" name="parqueo_id" placeholder="parqueo_id" aria-label="parqueo_id" aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <!-- <span class="input-group-text" id="basic-addon1"><i class="fas fa-tachometer-alt"></i>&nbsp;PLACA</span> -->
                            </div>
                            <input type="text" value="" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" name="placa" id="placa" placeholder="Placa" aria-label="placa" aria-describedby="basic-addon1" required='true' autofocus>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <!-- <span class="input-group-text" id="basic-addon1"><i class="fas fa-tachometer-alt"></i>&nbsp;NOMBRE</span> -->
                            </div>
                            <input type="text" value="" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" name="nombre" id="nombre" placeholder="Nombre" aria-label="nombre" aria-describedby="basic-addon1" required='true' autofocus>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <!-- <span class="input-group-text" id="basic-addon1"><i class="fas fa-tachometer-alt"></i>&nbsp;CELULAR</span> -->
                            </div>
                            <input type="number" pattern="[0-9]{10}" class="form-control" name="celular" id="celular" placeholder="celular" aria-label="celular" aria-describedby="basic-addon1" required='true' autofocus>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <!-- <span class="input-group-text" id="basic-addon1"><i class="fas fa-tachometer-alt"></i>&nbsp;VEHICULO</span> -->
                            </div>
                            <input type="text" value="" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" name="vehiculo" id="vehiculo" placeholder="Modelo" aria-label="vehiculo" aria-describedby="basic-addon1" required='true' autofocus>
                        </div>
                        <!-- <div class="input-group mb-2" id="inputcategorias">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-tachometer-alt"></i>&nbsp;categoria</span>
                                                                        </div>
                                                                        <input type="text" class="form-control" name="cat" id="cat" placeholder="Categoria" aria-label="categorias" aria-describedby="basic-addon1" required='true' autofocus>
                                                                    </div> -->
                        <div class="mb-1" id="select" name="select">
                            <!-- <label class="form-label">Categoria vehiculo </label> -->
                            <div class="input-group mb-1">
                                <div class="input-group-prepend">
                                    <!-- <span class="input-group-text" id="basic-addon1"><i class="fas fa-tachometer-alt"></i>&nbsp;CATEGORIA</span> -->
                                </div>

                                <select name="categoria" id="categoria" required='true' class="form-control" autofocus>
                                    <option hidden selected value="">Seleccione categoria de vehiculo</option>
                                    <?php
                                    //lista de categorias 
                                    $query =   "    SELECT * 
                                                                                                FROM categorias                                                                                                                                                                                  ";
                                    $categorias1 = $mysqli->query($query);

                                    while ($fila = $categorias1->fetch_array()) {
                                        $id_cat = $fila['cat_id'];
                                        $cat_nombre = $fila['cat_nombre'];
                                        $cat_img = $fila['cat_imagen'];
                                    ?>
                                        <option name="categoria1" name="categoria1" value="<?php echo $id_cat; ?>"><?php echo $cat_nombre; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-1" id="tarifas1" name="tarifas1">
                            <!-- <label class="form-label">Categoria vehiculo </label> -->
                            <div class="input-group mb-1">
                                <div class="input-group-prepend">
                                    <!-- <span class="input-group-text" id="basic-addon1"><i class="fas fa-tachometer-alt"></i>&nbsp;TARIFA</span> -->
                                </div>
                                <select name="tarifas" id="tarifas" required='true' class="form-control" autofocus>
                                    <option hidden selected value="">Seleccione la tarifa:</option>
                                </select>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <input type="hidden" value="<?php echo $id ?>" class="form-control" id="user" name="user" placeholder="user" aria-label="user" aria-describedby="basic-addon1">
                        </div>
                        <div class="d-grid gap-2">
                            <button onclick="openInf()" type="submit" class="btn btn-secondary btn btn-block" name="register" id="register" href="">
                                <div class="spinner-grow spinner-grow-sm text-light" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <i class="bi bi-plus-lg text-white">&nbsp;GUARDAR</i>
                            </button>
                        </div>
                    </form>
                    </div>
                    <!-- fin formulario ingresar parqueo -->
                    <!-- cards vehiculos-->                    
                    <div class="col col-xl-7 m-3">
                        <div class="row">
                            <?php
                            while ($fila = $entradas->fetch_array()) {
                                $ticket = $fila['parqueo_id'];
                                $placa = $fila['placa_cli'];
                                $fecha = $fila['fecha_ini'];
                                $tarVal = $fila['tarifa'];
                                $tarifa = $fila['tar_valor'];
                                $tiempo = $fila['tar_tiempo'];
                                $tarifasa = $fila['tar_id_nombre'];
                                $catimg = $fila['cat_imagen'];
                                $DateAndTime = date('Y-m-d G:i:s', time());
                                $fecha_ini = strtotime($fecha);
                                $fecha_fin = strtotime($DateAndTime);
                                $tiempo_transcurrido = $fecha_fin - $fecha_ini;
                                $tiempo1 = conversorSegundosHoras($tiempo_transcurrido);
                                $dias = floor(($tiempo_transcurrido / 3600) / 24);
                                $horas = floor($tiempo_transcurrido / 3600);
                                $minutos = floor(($tiempo_transcurrido - ($horas * 3600)) / 60);

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
                            ?>
                                <form method="POST" class="col col-lg-3" action="../config/ajax/task-update.php?placa_cli=<?php echo $placa; ?>">
                                    <input type="hidden" value="<?php echo $ticket; ?>" name="ticket"></input>
                                    <input type="hidden" value="<?php echo $placa; ?>" name="placa"></input>
                                    <input type="hidden" value="<?php echo $fecha; ?>" name="fechaini"></input>
                                    <input type="hidden" value="<?php echo $DateAndTime; ?>" name="fechafin"></input>
                                    <input type="hidden" value="<?php echo $tiempo1; ?>" name="tiempo"></input>
                                    <input type="hidden" value="<?php echo $fraccion; ?>" name="valor"></input>
                                    <input type="hidden" value="<?php echo $id; ?>" name="usuario"></input>
                                    
                                    <input type="hidden" value="4" id="caja_movimiento" class="caja_movimiento" name="caja_movimiento" readonly></input>
                                    <input type="hidden" value="Parqueo por <?php echo $tiempo; ?> - <?php echo $placa; ?>" id="caja_desc_movimiento" class="caja_desc_movimiento" name="caja_desc_movimiento" readonly></input>
                                    <input type="hidden" value="0" id="caja_egresos" class="caja_egresos" name="caja_egresos" readonly></input>
                                    <input type="hidden" value="NO" id="liquidado" class="liquidado" name="liquidado" readonly></input>
                                    <input type="hidden" value="ingreso" id="caja_tipo" class="caja_tipo" name="caja_tipo" readonly></input>

                                    <button onclick="openRec()" value="agregar" id="btn_parqueo" name="btn_parqueo" type="submit" class="btn btn-outline-secondary btn-lg p-2 m-2">
                                        <span><img class="logo_parqueo" id="logo_parqueo" src="<?php echo $catimg; ?>"></img></span>
                                        <h7 class="placa_parqueo"><?php echo $placa; ?></h7>
                                        <h7 class="tiempo_parqueo"><?php echo $tiempo1; ?></h7>
                                        <p class="ciclo_parqueo"><?php echo $tiempo; ?></p>
                                        <p class="avisos_parqueo">Valor a Pagar</p>
                                        <p class="pago_parqueo">$ <?php echo number_format($fraccion, 0, ",", ".") ?></p>
                                        <br>
                                    </button>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- cards vehiculos-->                    
                </div>
            </div>
        </main>
        <!-- footer -->
        <?php require '../logs/nav-footer.php'; ?>
        <!-- fin footer -->
    </div>

    <script src="../js/popper.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {


            $(".spinner-grow").hide(); //Oculto la animacion del boton enviar

            //Efecto Pre-Carga
            $(window).on("load", function() {
                $(".cargando").fadeOut(1000);
            });

            //Codigo para limitar la cantidad maxima que tendra dicho Input
            $('input#placa').keypress(function(event) {
                if (this.value.length === 6) {
                    return false;
                }
                if (event.which < 48 || event.which > 123) {
                    return false;
                }
            });

            //Validar la cantidad maxima en el campo celular
            // $('input#celular').keypress(function(event) {
            //     if (event.which < 48 || event.which > 57 || this.value.length === 10) {
            //         return false;
            //     }
            // });

            //Validando si existe la placa en BD antes de enviar el Form
            $("#placa").on("keyup", function() {
                var placa = $("#placa").val(); //CAPTURANDO EL VALOR DE INPUT CON ID placa
                var longitudPlaca = $("#placa").val().length; //CUENTO LONGITUD

                //Valido la longitud 
                if (longitudPlaca >= 6) {
                    var dataString = 'placa=' + placa;

                    $.ajax({
                        url: 'verificarPlaca.php',
                        type: "GET",
                        data: dataString,
                        dataType: "JSON",

                        success: function(datos) {

                            //si se encuentra la placa en la base de datos

                            if (datos.success == 1) {

                                $("#respuestas").html(datos.message); // informacion que si se encuentra la placa en la base de datos

                                $("input#placa").attr('disabled', false); //Habilitando el input placa

                                $("input#nombre").val(datos.nombre); //coloca el nombre en el input


                                $("input#celular").val(datos.celular); //coloca el nuemro de celular en el input

                                $("input#vehiculo").attr('disabled', true); //desHabilitando el input vehiculo
                                $("input#vehiculo").val(datos.vehiculo); //coloca el modelo del vehiculo en el input solo lectura

                                //$("input#cat").attr('disabled', true); //desHabilitando el input categorias
                                //$("input#cat").val(datos.nombreCat); //coloca la categoria en el input solo lectura

                                $("select#categoria").attr('disabled', true); // desactiva el select de las categorias
                                $("select#categoria").val(datos.categoria);

                                console.log(datos.categoria);
                                const cbxCategorias = document.getElementById('categoria');
                                console.log(cbxCategorias);

                                cbxCategorias.addEventListener('focusin', get_Tarifas)
                                const cbxTarifas = document.getElementById('tarifas');
                                console.log(cbxTarifas);

                                function get_Tarifas() {
                                    let categorias = cbxCategorias.value
                                    let url = 'get_tarifas.php'
                                    let formData = new FormData()
                                    formData.append('cat_id', categorias)

                                    fetchAndSetData(url, formData, cbxTarifas)
                                }

                            } else {

                                $("#respuestas").html(datos.message);

                                $("input").attr('disabled', false); //Habilito el input nombre
                                $("#register").attr('disabled', false); //Habilito el Botton

                                $("select#categoria").attr('disabled', false);
                                //$("input#cat").attr('disabled', true);

                                $("#nombre").val('');
                                $("#celular").val('');
                                $("#vehiculo").val('');
                                $("#categorias").val('');

                                //$("select#categoria").attr('disabled', false);// desactiva el select de las categorias

                                //if (datos.success == 0) {
                                //$("#placa").on("change", function() {
                                //$("input#cat").attr('disabled', true); //desHabilitando el input categorias



                                //var placa = $("#placa").val(); //CAPTURANDO EL VALOR DE INPUT CON ID placa                                    
                                //$("input#categorias").remove();
                                //});
                                //};

                                //const categorias = document.getElementById("categoriasa");
                                //const select = document.getElementById("select");
                                // //B) El container es reemplazado por el nuevo item hijo
                                //categorias.replaceWith(select);

                                //$("select#categoria").removeAttr('disabled');

                                // // seleccionado el elemento select para que nos e muestre en la pagina si existe la placa
                                // const childElement = document.getElementById("select");
                                // // revisando que el elemento select si exista
                                // if (childElement) {
                                //     // removiendo el elemento select de la pagina
                                //     childElement.remove();
                                // }

                                // const categorias = document.getElementById("categoriasa");
                                // const select = document.getElementById("select");
                                // //B) El container es reemplazado por el nuevo item hijo
                                // select.replaceWith(categorias);


                            }
                        }
                    });
                }
            });

            //Funcion para enviar el formulario de registro.


            //Muestro el efecto cargando en el boton
            $(".spinner-grow").show();

            setTimeout(function() {
                $(".spinner-grow").hide();
                $("#register").attr('disabled', false); //Desabilito el boton enviar
            }, 3000);
        });
    </script>
    <script>
        const cbxCategorias = document.getElementById('categoria');
        const cbxPlaca = document.getElementById('placa');


        cbxCategorias.addEventListener('change', get_Tarifas)
        cbxCategorias.addEventListener('focusin', get_Tarifas)
        cbxCategorias.addEventListener('focusout', get_Tarifas)
        cbxPlaca.addEventListener('focusin', get_Tarifas)
        cbxPlaca.addEventListener('focusout', get_Tarifas)


        const cbxTarifas = document.getElementById('tarifas');

        function fetchAndSetData(url, formData, targetElement) {
            return fetch(url, {
                    method: "POST",
                    body: formData,
                    mode: 'cors'
                })
                .then(response => response.json())
                .then(data => {
                    targetElement.innerHTML = data
                })
                .catch(err => console.log(err))
        }

        function get_Tarifas() {
            let categorias = cbxCategorias.value
            let url = 'get_tarifas.php'
            let formData = new FormData()
            formData.append('cat_id', categorias)

            fetchAndSetData(url, formData, cbxTarifas)
        }
        // para formatear texto en un input
        //const placa = document.getElementById('placa');

        // placa.addEventListener('input', () => {
        //     const valor = placa.value;
        //     const valorFormateado = formatearTexto(valor);
        //     placa.value = valorFormateado;
        // });

        // function formatearTexto(texto) {
        //     // Elimina los guiones existentes para evitar duplicados
        //     const textoSinGuiones = texto.replace(/-/g, '');

        //     // Divide el texto en grupos de 3 caracteres
        //     const grupos = textoSinGuiones.match(/.{1,3}/g);

        //     // Une los grupos con guiones
        //     const textoFormateado = grupos.join('-');

        //     return textoFormateado;
        // }
    </script>
    
    <script type="text/javascript">
        function openInf() {
            window.open("../factura/pdf_ticket.php")
        }
    </script>
    <script type="text/javascript">
        function openRec() {
            window.open("../factura/pdf_recibo.php")
        }
    </script>

<script>            
            function refrescarPagina() {
                location.reload();
                }

                // Refrescar cada 5 segundos
                setInterval(refrescarPagina, 60000);

                
        </script>
</body>

</html>