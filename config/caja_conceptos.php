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
error_reporting(1);
if (isset($_POST['register'])) {

    if (strlen($_POST['cat_nombre']) >= 1) {

        $catid = trim($_POST['cat_id']);
        $Catnombre = trim($_POST['cat_nombre']);
        $Catdesc = trim($_POST['cat_descripcion']);

        $consulta = "   INSERT INTO caja_conceptos(nombre_concepto,observacion) 
                        VALUES ('$Catnombre','$Catdesc')";
        $resultado = mysqli_query($mysqli, $consulta);
        if ($resultado) {
            header('location:caja_conceptos.php?mensaje=guardado')
?>
        <?php
        } else {
            header('location:caja_conceptos.php?mensaje=falta')
        ?>
        <?php
        }
    } else {
        header('location:caja_conceptos.php?mensaje=nada')
        ?>

        <h3 class="bad">ingrese los datos</h3>
<?php
    }
}

// Consultas
$query = "  SELECT  * 
            FROM    caja_conceptos
                    ";
$conceptos = $mysqli->query($query);

$query = "  SELECT  * 
            FROM    caja_conceptos
                    ";
$editarCon = $mysqli->query($query);

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
            <div class="card-header BG-primary mt-1"><b style="color: white;"><i class="fas fa-tachometer-alt" style='font-size:24px'></i>&nbsp;&nbsp;Categorias</b></div>
            <div class="container-fluid px-2">
                <div class="container mt-3">
                    <!-- inicio de alertas -->
                    <section>
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
                                <h5><i class="far fa-thumbs-up" style="font-size:24px"></i>&nbsp;&nbsp;<strong>Ok !</strong> Concepto registrado.</h5>
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
                                <strong>Actualizacion :</strong> Concepto editado.
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
                    <div class="row justify-content">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="card p-2 ">
                                <!--  Modal trigger button  -->
                                <div class="col col-sm-3 col-md-4">
                                    <button type="button" class="btn btn-outline-primary btn-md mb-2" data-bs-toggle="modal" data-bs-target="#modalId">
                                        <strong>+ Concepto</strong>
                                    </button>
                                </div>
                                <!--  Modal trigger button  -->

                                <!-- modal ingreso categoria -->
                                <!-- Modal Body-->
                                <div class="modal fade" id="modalId" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalTitleId"><i class="fas fa-tachometer-alt" style='font-size:24px'></i>&nbsp;&nbsp;Ingresar Concepto</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="container-fluid">
                                                    <!-- formulario ingresar categoria -->
                                                    <div class="col-md-12">
                                                        <div class="card border-4 rounded-3">
                                                            <form id="categorias" name="categorias" class="row g-0 p-2" action="caja_conceptos.php" method="POST">
                                                                <div class="input-group mb-2">
                                                                    <input type="hidden" class="form-control" id="cat_id" name="cat_id" placeholder="cat_id" aria-label="cat_id" aria-describedby="basic-addon1" required autofocus>
                                                                </div>
                                                                <div class="input-group mb-2">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-tachometer-alt"></i>&nbsp;</span>
                                                                    </div>
                                                                    <input type="text" class="form-control" name="cat_nombre" placeholder="Nombre Concepto" aria-label="nombre_categoria" aria-describedby="basic-addon1" required autofocus>
                                                                </div>
                                                                <div class="input-group mb-2">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-tachometer-alt"></i>&nbsp;</span>
                                                                    </div>
                                                                    <textarea type="text" class="form-control" name="cat_descripcion" placeholder="Descripcion Concepto" aria-label="desc_categoria" aria-describedby="basic-addon1" required autofocus></textarea>
                                                                </div>

                                                                <div class="d-grid gap-2">
                                                                    <button type="submit" class="btn btn-secondary btn btn-block" name="register" href="usuarios_nuevos.php"><i class="bi bi-plus-lg text-white">&nbsp;GUARDAR</i></button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <!-- fin formulario ingresar categoria -->
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal Body-->
                                <!-- modal ingreso categoria -->

                                <!-- tabla categoria -->
                                <div class="justify-content-between m-0 col col-10 col-sm-10 col-md-12">
                                    <div>
                                        Concepto caja:
                                    </div>
                                    <table id="tabla_categoria" class="table table table-sm table-borderless table-hover mt-3 table text-center table align-middle">
                                        <thead>
                                            <tr>
                                                <th align="center">ID</th>
                                                <th align="center">CONCEPTO</th>
                                                <th align="center">DESCRIPCION</th>
                                                <th align="center">EDITAR</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($fila = $conceptos->fetch_array()) {
                                                $conId = $fila['id_concepto'];
                                                $conNom = $fila['nombre_concepto'];
                                                $conObs = $fila['observacion'];
                                            ?>
                                                <tr>
                                                    <td align="center"><?php echo $conId; ?></td>
                                                    <td align="center"><?php echo $conNom; ?></td>
                                                    <td align="center"><?php echo $conObs; ?></td>
                                                    <td align="center"><a data-bs-toggle="modal" data-bs-target="#Modalcategorias<?php echo $conId; ?>" title="Editar" class="btn btn-outline-success btn-xs">
                                                            <i class="bi bi-pencil-square"></i></a></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- tabla categoria -->

                                <!-- modal editar categorias-->
                                <!-- Modal -->
                                <?php
                                while ($fila = $editarCon->fetch_array()) {
                                    $conId = $fila['id_concepto'];
                                    $conNom = $fila['nombre_concepto'];
                                    $conObs = $fila['observacion'];
                                ?>
                                    <form id="cargo" name="cargo" class="row g-0 p-2" action="caja_editarproceso.php" method="POST">
                                        <div class="modal fade" id="ModalCategorias<?php echo $conId; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-tachometer-alt" style='font-size:24px'></i>&nbsp;Modificar Concepto <br></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-1">
                                                            <input type="hidden" name="cat_id" id="cat_id" value="<?php echo $conId; ?>" readonly></input>
                                                            <h6>Corregir Concepto:</h6>
                                                            <h7 class="mb-1"><?php echo $conNom; ?><br><br>
                                                                <h6>Por:</h6>

                                                                <label class="form-label mt-1"> </label>
                                                                <div class="input-group mb-1 mt-2">
                                                                    <div class="input-group-prepend">
                                                                        <label class="input-group-text" for="inputGroupSelect01"><i class='fas fa-tachometer-alt'></i>&nbsp;</label>
                                                                    </div>
                                                                    <input type="text" name="cat_nombre" id="cat_nombre" value="<?php echo $conNom; ?>"></input>
                                                                </div>

                                                                <label class="form-label">Descripcion: </label>
                                                                <div class="input-group mb-1">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text" id="basic-addon1"><i class='far fa-file-alt'></i></i>&nbsp;</span>
                                                                    </div>
                                                                    <textarea rows="6" cols="50" type="textarea" class="form-control" name="cat_descripcion" value="<?php echo $conObs; ?>" placeholder="Descripcion" aria-label="valor" aria-describedby="basic-addon1" required autofocus><?php echo $conObs; ?></textarea>
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
                                <!-- modal editar categorias -->


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