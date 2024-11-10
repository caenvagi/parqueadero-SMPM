<?php
session_start();
date_default_timezone_set('America/Bogota');
echo "<link rel='stylesheet' type='text/css' href='../css/styles.css'>";
echo "<link rel='stylesheet' type='text/css' href='../css/estilos.css'>";
echo "<link rel='stylesheet' type='text/css' href='../css/cargando.css'>";

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

// Para guardar el CLIENTE nuevo
include '../conexion/conexion.php';
error_reporting(1);
if (isset($_POST['register'])) {

    if (strlen($_POST['nombre']) >= 1) {

        $nombre = trim($_POST['nombre']);
        $celular = trim($_POST['celular']);
        $placa = trim($_POST['placa']);
        $vehiculo = trim($_POST['vehiculo']);
        $categoria = trim($_POST['categoria']);
        $user = trim($_POST['user']);
        $tarifas = trim($_POST['tarifas']);

        $queryRegistro = $mysqli->query("   SELECT COUNT(placa) AS placa 
                                            FROM cliente 
                                            WHERE placa='" . $placa . "' 
                                            ");
        $row = $queryRegistro->fetch_assoc();

        if ($row['placa'] > 0) {
            $sentencia = $bd->prepare(" UPDATE  cliente 
                                        SET     nombre=? , celular=?
                                        WHERE   placa = ?; ");
            $resultado = $sentencia->execute([$nombre, $celular, $placa]);

            if ($resultado === TRUE) {
                $consulta1 = "  INSERT INTO parqueo(placa_cli, tarifa, usuario) 
                                VALUES ('$placa','$tarifas','$user')";
                $resultado1 = mysqli_query($mysqli, $consulta1);

                header('location: parqueo.php?mensaje=parqueo');
            } else {
                header('location: parqueo.php?mensaje=falta');
            }
        } else {

            $consulta = "   INSERT INTO cliente(nombre,celular,placa,vehiculo,categoria) 
                            VALUES ('$nombre','$celular','$placa','$vehiculo','$categoria')";
            $resultado = mysqli_query($mysqli, $consulta);

            if ($resultado) {
                $consulta1 = "  INSERT INTO parqueo(placa_cli, tarifa, usuario) 
                                VALUES ('$placa','$tarifas','$user')";
                $resultado1 = mysqli_query($mysqli, $consulta1);

                header('location:parqueo.php?mensaje=guardado') ?>
                <?php
            } else {
                header('location:parqueo.php?mensaje=falta')
                ?><?php
                }
            }
        }
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

    //----------------------------------------------------------

    $query = "  SELECT      parqueo_id, 
                            placa_cli, 
                            PA.tarifa, 
                            fecha_ini,                       
                            cat_nombre,
                            tar_tiempo,
                            tar_valor,
                            tar_id_nombre,
                            categoria,

                            usuario
            FROM        parqueo as PA
            INNER JOIN  cliente as CL on PA.placa_cli = CL.placa
            INNER JOIN  categorias as CA ON CL.categoria = CA.cat_id
            INNER JOIN 	tarifas as TA ON PA.tarifa = TA.tar_id
            INNER JOIN  tar_tiempo as TT ON TA.tar_nombre = TT.tar_id_nombre
            WHERE estado = 'SI'
            order by    parqueo_id desc
            
            
            ";
    $parqueoUlt1 = $mysqli->query($query);


    //----------------------------------------------------------------------------------
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
            $hora_texto .= $hour . "  H / ";
        }
        if ($minutos > 0) {
            $hora_texto .= $minutos . " m / ";
        }
        if ($segundos > 0) {
            $hora_texto .= $segundos . " s";
        }
        return $hora_texto;
    }
    //--------------------------------------------------------------
    // Consultas
                    ?>
                <!DOCTYPE html>
                <html lang="es">

                <head>
                    <?php require '../logs/head.php'; ?>
                </head>

                <body>
                    <?php require '../logs/nav-bar.php'; ?>
                    <div class="cargando">
                        <div class="loader-outter"></div>
                        <div class="loader-inner"></div>
                    </div>

                    <!-- inicio pagina -->
                    <div id="layoutSidenav_content">
                        <main>
                            <div class="card-header BG-primary mt-1"><b style="color: white;"><i class="fas fa-tachometer-alt" style='font-size:24px'></i>&nbsp;&nbsp;Ingreso Vehiculos</b></div>
                            <div class="container-fluid px-2">
                                <div class="container mt-3">

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="card border-4 rounded-3">
                                                <div class=" card-header">
                                                    Valor a cobrar por parqueo
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-lg table-striped table-hover table-borderless table-primary align-middle text-center">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>CATEGORIA</th>
                                                                    <th>PLACA</th>
                                                                    <th>ENTRADA</th>
                                                                    <!-- <th>SALIDA</th> -->
                                                                    <th>TIEMPO TRANSCURRIDO</th>
                                                                    <th>CICLO</th>
                                                                    <th>TARIFA</th>
                                                                    <th>VALOR</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="table-group-divider">
                                                                <?php

                                                                while ($fila = $parqueoUlt1->fetch_array()) {
                                                                    $idparqueo = $fila['parqueo_id'];
                                                                    $placa = $fila['placa_cli'];
                                                                    $fechaini = $fila['fecha_ini'];
                                                                    $fechafin = $DateAndTime;
                                                                    $tarifasa = $fila['tar_id_nombre'];
                                                                    $usuario = $fila['usuario'];
                                                                    $catnombre = $fila['cat_nombre'];
                                                                    $idtiempo = $fila['tar_id'];
                                                                    $tiempos = $fila['tar_tiempo'];
                                                                    $tarifa = $fila['tar_valor'];

                                                                    $DateAndTime = date('Y-m-d G:i:s', time());
                                                                    $fecha_ini = strtotime($fechaini);
                                                                    $fecha_fin = strtotime($DateAndTime);
                                                                    $tiempo_transcurrido = $fecha_fin - $fecha_ini;

                                                                    $tiempo1 = conversorSegundosHoras($tiempo_transcurrido);

                                                                    $horas = floor($tiempo_transcurrido / 3600);
                                                                    //echo  "/hora:" . $horas;"hora/";

                                                                    $minutos = floor(($tiempo_transcurrido - ($horas * 3600)) / 60);
                                                                    //echo  ";--min: " . $minutos;



                                                                    if ($tarifasa == 1) {
                                                                        if ($minutos >= 10) {
                                                                            $fraccion =  $tarifa;
                                                                        }
                                                                        if ($minutos > 10) {
                                                                            $fracciones1 = $tarifa;
                                                                        } elseif ($minutos < 10) {
                                                                            $fracciones1 = 0;
                                                                        }
                                                                        if ($horas > 0) {
                                                                            $fraccion = ($horas * $tarifa) + $fracciones1;
                                                                        }
                                                                    } elseif ($tarifasa == 2) {
                                                                        if ($minutos > 10) {
                                                                            $fraccion =  $tarifa;
                                                                        }
                                                                        if ($minutos > 10) {
                                                                            $fracciones2 = $tarifa;
                                                                        } elseif ($minutos < 10) {
                                                                            $fracciones2 = 0;
                                                                        }
                                                                        if ($horas > 0) {
                                                                            $fraccion = floor(($horas) / 12) * $tarifa + $fracciones2;
                                                                        }
                                                                    } elseif ($tarifasa == 3) {
                                                                        if ($minutos > 10) {
                                                                            $fraccion =  $tarifa;
                                                                        }
                                                                        if ($minutos > 10) {
                                                                            $fracciones3 = $tarifa;
                                                                        } elseif ($minutos < 10) {
                                                                            $fracciones3 = 0;
                                                                        }
                                                                        if ($horas > 0) {
                                                                            $fraccion = floor(($horas / 720)) * $tarifa + $fracciones3;
                                                                        }
                                                                    }



                                                                ?>

                                                                    <tr class="table-primary">
                                                                        <td><?php echo $idparqueo ?></td>
                                                                        <td><?php echo $catnombre; ?></td>
                                                                        <td><?php echo $placa ?></td>
                                                                        <td><?php echo $fechaini; ?></td>
                                                                        <!-- <td><?php echo $fechafin ?></td> -->
                                                                        <td><?php echo $tiempo1 ?></td>
                                                                        <td><?php echo $tiempos ?></td>
                                                                        <td>$<?php echo number_format($tarifa, 0, ",", ".") ?></td>
                                                                        <td>$<?php echo number_format($fraccion, 0, ",", ".") ?></td>

                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                            <tfoot>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                    </div>
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

                                                $("#respuesta").html(datos.message); // informacion que si se encuentra la placa en la base de datos

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

                                                $("#respuesta").html(datos.message);

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
                    </script>
                </body>

                </html>