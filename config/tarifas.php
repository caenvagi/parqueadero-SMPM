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

// Para guardar el usuario nuevo
include '../conexion/conexion.php';
error_reporting(0);
if (isset($_POST['register'])) {

    if (strlen($_POST['categoria']) >= 1) {

        $Tarid = trim($_POST['tar_id']);
        $Tarcategoria = trim($_POST['categoria']);
        $Tartiempo = trim($_POST['tiempo']);
        $Tarvalor = trim($_POST['valor']);

        $consulta = "   INSERT INTO tarifas(tar_categoria,tar_nombre,tar_valor) 
                            VALUES ('$Tarcategoria','$Tartiempo','$Tarvalor')";
        $resultado = mysqli_query($mysqli, $consulta);
        if ($resultado) {
            header('location:tarifas.php?mensaje=guardado')
?>
        <?php
        } else {
            header('location:tarifas.php?mensaje=falta')
        ?>
        <?php
        }
    } else {
        header('location:tarifas.php?mensaje=nada')
        ?>

        <h3 class="bad">ingrese los datos</h3>
<?php
    }
}

// Consultas
$query = "          SELECT  * 
                    FROM    tarifas as TA
                    INNER JOIN categorias as CA ON CA.cat_id = TA.tar_categoria
                    INNER JOIN tar_tiempo as TT On TA.tar_nombre = TT.tar_id_nombre
                    ORDER BY cat_id asc
                    ";
$tarifas = $mysqli->query($query);

$query = "  SELECT  * 
            FROM    tarifas as TA
            INNER JOIN categorias as CA ON CA.cat_id = TA.tar_categoria
            INNER JOIN tar_tiempo as TT On TA.tar_nombre = TT.tar_id_nombre
            
                    ";
$editarTar = $mysqli->query($query);

// Consultas
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
            <div class="card-header BG-primary mt-1"><b style="color: white;"><i class="fas fa-tachometer-alt" style='font-size:24px'></i>&nbsp;&nbsp;tarifas</b></div>
            <div class="container-fluid px-2">
                <div class="container mt-3">
                    <section>
                        <!-- inicio de alertas -->
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
                            <div class="alerta alert alert-success alert-dismissible fade show text-center " role="alert">
                                <h5><i class="far fa-thumbs-up" style="font-size:24px"></i>&nbsp;&nbsp;<strong>Ok !</strong> Usuario creado.</h5>
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
                                <h5><strong>Error !</strong> no se pudo editar!</h5>
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
                        if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'editado') {
                        ?>
                            <div class="alerta alert alert-primary alert alert-dismissible fade show" role="alert">
                                <strong>Actualizacion :</strong> Todos los datos fueron actualizados.
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
                        <!-- fin alertas -->
                    </section>
                    <div class="row justify-content">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="card p-2 ">
                                <!--  Modal trigger button  -->
                                <div class="col col-sm-3 col-md-4">
                                    <button type="button" class="btn btn-outline-primary btn-md mb-2" data-bs-toggle="modal" data-bs-target="#modalId">
                                        <strong>+ Tarifa</strong>
                                    </button>
                                </div>
                                <!--  Modal trigger button  -->

                                <!-- modal ingreso tarifa -->
                                <!-- Modal Body-->
                                <div class="modal fade" id="modalId" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalTitleId"><i class="fas fa-tachometer-alt" style='font-size:24px'></i>&nbsp;&nbsp;Ingresar Tarifas</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="container-fluid">
                                                    <!-- formulario ingresar tarifa -->
                                                    <div class="col-md-12">
                                                        <div class="card border-4 rounded-3">
                                                            <form id="tarifas" name="tarifas" class="row g-0 p-2" action="tarifas.php" method="POST">
                                                                <div class="input-group mb-2">
                                                                    <input type="hidden" class="form-control" id="tar_id" name="tar_id" placeholder="tar_id" aria-label="tar_id" aria-describedby="basic-addon1" required autofocus>
                                                                </div>

                                                                <div class="mb-1">
                                                                    <label class="form-label">Categoria vehiculo </label>
                                                                    <div class="input-group mb-1">
                                                                        <div class="input-group-prepend">
                                                                            <label class="input-group-text" for="inputGroupSelect01"><i class='fas fa-chalkboard-teacher'></i>&nbsp;</label>
                                                                        </div>
                                                                        <select name="categoria" id="categoria" required autofocus>
                                                                            <option hidden selected>Seleccione categoria de vehiculo</option>
                                                                            <?php
                                                                            //lista de categorias 
                                                                            $query =   "    SELECT * 
                                                                                            FROM categorias                                                                                                                                                                                  ";
                                                                            $categorias = $mysqli->query($query);

                                                                            while ($fila = $categorias->fetch_array()) {
                                                                                $id_cat = $fila['cat_id'];
                                                                                $cat_nombre = $fila['cat_nombre'];
                                                                                $cat_img = $fila['cat_imagen'];

                                                                            ?>
                                                                                <option name="categoria1" value="<?php echo $id_cat; ?>">*&nbsp;<?php echo $cat_nombre; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="mb-1">
                                                                    <label class="form-label">Tiempo por vehiculo </label>
                                                                    <div class="input-group mb-1">
                                                                        <div class="input-group-prepend">
                                                                            <label class="input-group-text" for="inputGroupSelect01"><i class='fas fa-chalkboard-teacher'></i>&nbsp;</label>
                                                                        </div>
                                                                        <select name="tiempo" id="tiempo" required autofocus>
                                                                            <option hidden selected>Seleccione el tiempo a cobrar</option>
                                                                            <?php
                                                                            //lista de timepos por cobrar
                                                                            $query =   "    SELECT * 
                                                                                            FROM tar_tiempo                                                                                                                                                                                  ";
                                                                            $tiempos = $mysqli->query($query);

                                                                            while ($fila = $tiempos->fetch_array()) {
                                                                                $tar_id = $fila['tar_id_nombre'];
                                                                                $tar_tmp = $fila['tar_tiempo'];
                                                                            ?>
                                                                                <option name="tiempo1" value="<?php echo $tar_id; ?>">*&nbsp;<?php echo $tar_tmp; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="input-group mb-2">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-tachometer-alt"></i>&nbsp;</span>
                                                                    </div>
                                                                    <input type="text" class="form-control" name="valor" placeholder="Valor tarifa $" aria-label="valor_tarifa" aria-describedby="basic-addon1" required autofocus>
                                                                </div>

                                                                <div class="d-grid gap-2">
                                                                    <button type="submit" class="btn btn-secondary btn btn-block" name="register" href="usuarios_nuevos.php"><i class="bi bi-plus-lg text-white">&nbsp;GUARDAR</i></button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <!-- fin formulario ingresar tarifa -->
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal Body-->
                                <!-- modal ingreso tarifa -->

                                <!-- tabla tarifa -->
                                <div class="justify-content-between m-0 col col-10 col-sm-10 col-md-12">
                                    <div>
                                        TARIFAS AUTOMOVILES:
                                    </div>
                                    <table id="tabla_tarifa" class="table table table-sm table-borderless table-hover mt-3 table text-center table align-middle">
                                        <thead>
                                            <tr>
                                                <th align="center">ID</th>
                                                <th></th>
                                                <th align="center">CATEGORIA</th>
                                                <th align="center">TIEMPO</th>
                                                <th align="center">TARIFA</th>
                                                <th align="center">EDITAR</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($fila = $tarifas->fetch_array()) {
                                                $idTar = $fila['tar_id'];
                                                $imgTar = $fila['cat_imagen'];
                                                $categoriaTar = $fila['tar_categoria'];
                                                $categoriaNom = $fila['cat_nombre'];
                                                $nombreTar = $fila['tar_tiempo'];
                                                $precioTar = $fila['tar_valor']; 
                                            ?>
                                                <tr>
                                                    <td align="center"><?php echo $idTar; ?></td>
                                                    <td align="center"><img class="avatar2" src="<?php echo $imgTar; ?>" alt=""></td>
                                                    <td align="center"><?php echo $categoriaNom; ?></td>
                                                    <td align="center"><?php echo $nombreTar; ?></td>
                                                    <td align="center">$ &nbsp;<?php echo number_format($precioTar, 0, ",", "."); ?></td>

                                                    <td align="center"><a data-bs-toggle="modal" data-bs-target="#Modaltarifas<?php echo $idTar; ?>" title="Editar" class="btn btn-outline-success btn-xs">
                                                            <i class="bi bi-pencil-square"></i></a></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- tabla tarifa -->

                                <!-- modal editar tarifas-->
                                <!-- Modal -->
                                <?php
                                while ($fila = $editarTar->fetch_array()) {
                                    $idTar = $fila['tar_id'];
                                    $imgTar = $fila['cat_imagen'];
                                    $categoriaTar = $fila['tar_categoria'];
                                    $categoriaNom = $fila['cat_nombre'];
                                    $nombreTar = $fila['tar_tiempo'];
                                    $precioTar = $fila['tar_valor'];
                                ?>
                                    <form id="cargo" name="cargo" class="row g-0 p-2" action="tarifas_editarproceso.php" method="POST">
                                        <div class="modal fade" id="Modaltarifas<?php echo $idTar; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-tachometer-alt" style='font-size:24px'></i>&nbsp;Modificar tarifa <br></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-1">
                                                            <input type="hidden" name="tar_id" id="tar_id" value="<?php echo $idTar; ?>" readonly></input>
                                                            <h6>Modificar Tarifa:</h6>
                                                            <h7 class="mb-1"><?php echo $categoriaNom; ?><br><br>
                                                                <h6>Por:</h6>

                                                                <div class="input-group mb-2">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-tachometer-alt"></i>&nbsp;</span>
                                                                    </div>
                                                                    <input type="text" class="form-control" name="valor" placeholder="Valor tarifa $" value="<?php echo $precioTar; ?>" aria-label="valor_tarifa" aria-describedby="basic-addon1" required autofocus>
                                                                </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                        <button type="submit" value="editar" class="btn btn-primary btn btn-block">Guardar cambios</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                <?php };  ?>
                                <!-- modal editar tarifas -->


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
    

</body>

</html>