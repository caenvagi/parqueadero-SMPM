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

$query = "              SELECT      arqueo_id,
                                    fecha_apertura,
                                    fecha_cierre,
                                    cajero as ID,
                                    US.nombre as cajero,
                                    monto_inicial,
                                    total_ingresos,
                                    total_egresos,
                                    total_cierre,
                                    monto_final,
                                    cuadre,
                                    estado,
                                    AR.usuario
                        FROM        arqueo      AS AR
                        INNER JOIN usuarios as US ON US.id = AR.cajero
            ";
$arqueo = $mysqli->query($query);

$query = "              SELECT      *
                        FROM        arqueo      AS AR
                        INNER JOIN usuarios as US ON US.id = AR.usuario                                               
                        order by arqueo_id
                        DESC LIMIT 1
            ";
$arqueoult = $mysqli->query($query);

$query = "              SELECT      sum(valor_ingreso) as ingreso
                        FROM        caja                        
                        where       liquidado = 'NO' and caja_tipo = 'ingreso'    
            ";
$ventascajero = $mysqli->query($query);

$query = "              SELECT      sum(valor_ingreso) as egreso
                        FROM        caja                       
                        where       liquidado = 'NO' and caja_tipo = 'gasto'   
            ";
$egresostotal = $mysqli->query($query);

$query = "              SELECT      
                                    (   SELECT      monto_inicial as inicial
                                        FROM        arqueo 
                                        order by    arqueo_id
                                        DESC LIMIT 1 )
                                    +    
                                    (   SELECT      ifnull(sum(valor_ingreso),0) as ingreso
                                        FROM        caja                        
                                        where       liquidado = 'NO' and caja_tipo = 'ingreso') 
                                    
                                    -
                                    (   SELECT      ifnull(sum(valor_ingreso),0) as egreso
                                        FROM        caja                       
                                        where       liquidado = 'NO' and caja_tipo = 'gasto')    
                                        
                                        as total;                                 
            ";
$total = $mysqli->query($query);







?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php require '../logs/head.php'; ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.4/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.dataTables.css">

</head>

<body>
    <?php require '../logs/nav-bar.php'; ?>
    <!-- inicio pagina -->
    <div id="layoutSidenav_content">
        <main>
            <div class="card-header BG-primary mt-1"><b style="color: white;"><i class="fas fa-tachometer-alt" style='font-size:24px'></i>&nbsp;&nbsp;ARQUEO</b></div>
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
                                    <h5><i class="far fa-thumbs-up" style="font-size:24px"></i>&nbsp;&nbsp;<strong>Ok !</strong>Caja abierta.</h5>
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
                                    <h5><strong>Error !</strong> Ya hay una caja abierta!</h5>
                                    Realiza el cierre de caja.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php
                            }
                            ?>
                             <!-- Mensaje actualizar -->
                             <?php
                            if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'editado') {
                            ?>
                                <div class="alerta alert alert-primary alert-dismissible fade show text-center " role="alert">
                                    <h5><i class="far fa-thumbs-up" style="font-size:24px"></i>&nbsp;&nbsp;<strong>Ok !</strong>Se ha realizado el cierre de caja..</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php
                            }
                            ?>
                              
                      <!-- Mensaje cerrada -->
                      <?php
                            if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'cerrada') {
                            ?>
                                <div class="alerta alert alert-danger alert-dismissible fade show text-center " role="alert">
                                    <h5><i class="far fa-thumbs-up" style="font-size:24px"></i>&nbsp;&nbsp;<strong>Error !</strong>No hay ninguna Caja abierta.</h5>
                                    
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php
                            }
                            ?>
                             <!-- Mensaje no abierta -->
                             <?php
                            if (isset($_GET['mensaje']) and $_GET['mensaje'] == 'abierta') {
                            ?>
                                <div class="alerta alert alert-danger alert-dismissible fade show text-center " role="alert">
                                    <h5><i class="far fa-thumbs-up" style="font-size:24px"></i>&nbsp;&nbsp;<strong>Error !</strong>No se ha abierto una Caja.</h5>
                                    Abre la caja para poder ingresar el vehiculo
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php
                            }
                            ?>                     
                    </section>
                    <!-- fin alertas -->

                            
                        
                    <!-- modal ingreso caja abierta -->                
                        <!-- Modal Body-->
                        <div class="modal fade" id="modalabrir" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalTitleId"><i class="fas fa-tachometer-alt" style='font-size:24px'></i>&nbsp;&nbsp;Abrir Caja</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <!-- formulario ingresar arqueo -->
                                            <div class="col-md-12">
                                                <div class="card border-4 rounded-3">
                                                    <form id="tarifas" name="tarifas" class="row g-0 p-2" action="arqueo_ingresar.php" method="POST">

                                                        <div class="input-group mb-2">
                                                            <div class="col col-lg-3">
                                                                <label for="inputPassword6" class="col-form-label">fecha apertura</label>
                                                            </div>
                                                            <div class="col col-lg-1 input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-tachometer-alt"></i>&nbsp;</span>
                                                            </div>
                                                            <input type="datetime" value="<?php $fechaActual = new DateTime();
                                                                                            echo $fechaActual->format('Y-m-d H:i:s'); ?>" class="col col-lg-8 form-control" name="apertura" placeholder="Fecha apertura" aria-label="Fecha_apertura" aria-describedby="basic-addon1" required autofocus>
                                                        </div>

                                                        <input type="hidden" value="" class="col col-lg-8 form-control" name="cierre" placeholder="Fecha cierre" aria-label="Fecha_cierre" aria-describedby="basic-addon1" required autofocus>

                                                        <div class="input-group mb-2">
                                                            <div class="col col-lg-3">
                                                                <label for="inputPassword6" class="col-form-label">Cajero</label>
                                                            </div>
                                                                <div class="col col-lg-1 input-group-prepend">
                                                                    <label class="input-group-text" for="inputGroupSelect01"><i class='fas fa-chalkboard-teacher'></i>&nbsp;</label>
                                                                </div>
                                                                <select class="col col-lg-8" name="cajero" id="cajero" style="font-size:18px" required autofocus>
                                                                    <option hidden selected>Seleccione Cajero</option>
                                                                    <?php
                                                                    //lista de cajeros
                                                                    $query =   "    SELECT * 
                                                                                                FROM usuarios                                                                                                                                                                                  ";
                                                                    $tiempos = $mysqli->query($query);

                                                                    while ($fila = $tiempos->fetch_array()) {
                                                                        $user_id = $fila['id'];
                                                                        $user_nombre = $fila['nombre'];
                                                                    ?>
                                                                        <option name="cajero" value="<?php echo $user_id; ?>">&nbsp;<?php echo $user_nombre; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                        </div>

                                                        <div class="input-group mb-2">
                                                            <div class="col col-lg-3">
                                                                <label for="inputPassword6" class="col-form-label">Monto inicial</label>
                                                            </div>
                                                            <div class="col col-lg-1 input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-tachometer-alt"></i>&nbsp;</span>
                                                            </div>
                                                            <input type="number" value="" class="col col-lg-8 form-control" name="inicial" placeholder="$" aria-label="monto_inicial" aria-describedby="basic-addon1" required autofocus>
                                                        </div>
                                                        
                                                        <input type="hidden" value="0" class="col col-lg-8 form-control" name="final" placeholder="Monto final" aria-label="Monto_final" aria-describedby="basic-addon1" required autofocus>
                                                    
                                                        <input type="hidden" value="0" class="col col-lg-8 form-control" name="ingresos" placeholder="ingresos" aria-label="ingresos" aria-describedby="basic-addon1" required autofocus>
                                                        <input type="hidden" value="0" class="col col-lg-8 form-control" name="egresos" placeholder="egresos" aria-label="egresos" aria-describedby="basic-addon1" required autofocus>
                                                        <input type="hidden" value="0" class="col col-lg-8 form-control" name="total_cierre" placeholder="total_cierre" aria-label="total_cierre" aria-describedby="basic-addon1" required autofocus>
                                                        <input type="hidden" value="abierta" class="col col-lg-8 form-control" name="estado" placeholder="estado" aria-label="estado" aria-describedby="basic-addon1" required autofocus>
                                                        <input type="hidden" value="<?php echo $id ?>" class="col col-lg-8 form-control" name="id" placeholder="id" aria-label="id" aria-describedby="basic-addon1" required autofocus>                    

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
                    <!-- modal ingreso arqueo -->

                    <!-- modal cierre caja -->                
                        <!-- Modal Body-->
                        <div class="modal fade" id="modalcerrar" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalTitleId"><i class="fas fa-tachometer-alt" style='font-size:24px'></i>&nbsp;&nbsp;Cerrar Caja</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <!-- formulario ingresar arqueo -->
                                            <div class="col-md-12">
                                                <div class="card border-4 rounded-3">
                                                    <form id="cierre" name="cierre" class="row g-0 p-2" action="arqueo_cerrar.php" method="POST">
                                                    
                                                    <?php while ($row =  $arqueoult->fetch_assoc()) { ?>

                                                        <input type="text" name="arqueo_id" value="<?php echo $row['arqueo_id'];?>">

                                                        <div class="input-group mb-2">
                                                            <div class="col col-lg-3">
                                                                <label for="inputPassword6" class="col-form-label">fecha apertura</label>
                                                            </div>
                                                            <div class="col col-lg-1 input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-tachometer-alt"></i>&nbsp;</span>
                                                            </div>
                                                            <input type="datetime" value="<?php echo $row['fecha_apertura'];?>" class="col col-lg-8 form-control" name="apertura" placeholder="Fecha apertura" aria-label="Fecha_apertura" aria-describedby="basic-addon1" required autofocus readonly>
                                                        </div>

                                                        <div class="input-group mb-2">
                                                            <div class="col col-lg-3">
                                                                <label for="inputPassword6" class="col-form-label">fecha cierre</label>
                                                            </div>
                                                            <div class="col col-lg-1 input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-tachometer-alt"></i>&nbsp;</span>
                                                            </div>
                                                            <input type="datetime" value="<?php $fechaActual = new DateTime();
                                                                                            echo $fechaActual->format('Y-m-d H:i:s'); ?>"
                                                                    class="col col-lg-8 form-control" name="cierre" placeholder="Fecha cierre" aria-label="Fecha_cierre" aria-describedby="basic-addon1" required autofocus readonly>
                                                        </div>

                                                        <div class="input-group mb-2">
                                                            <div class="col col-lg-3">
                                                                <label for="inputPassword6" class="col-form-label">cajero</label>
                                                            </div>
                                                            <div class="col col-lg-1 input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-tachometer-alt"></i>&nbsp;</span>
                                                            </div>
                                                            <input type="text" value="<?php echo $row['nombre'];?>" class="col col-lg-8 form-control" name="apertura" placeholder="Fecha apertura" aria-label="Fecha_apertura" aria-describedby="basic-addon1" required autofocus readonly>
                                                        </div>  

                                                       <div class="input-group mb-2">
                                                            <div class="col col-lg-3">
                                                                <label for="inputPassword6" class="col-form-label">Dinero inicial</label>
                                                            </div>
                                                            <div class="col col-lg-1 input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-tachometer-alt"></i>&nbsp;</span>
                                                            </div>
                                                            <input type="number" value="<?php echo number_format($row['monto_inicial'],0,",",".");?>" class="col col-lg-8 form-control" name="inicial" placeholder="$" aria-label="monto_inicial" aria-describedby="basic-addon1" required autofocus readonly>
                                                        </div>                                                        

                                                        <?php while ($row =  $ventascajero->fetch_assoc()) { ?>
                                                       <div class="input-group mb-2">
                                                            <div class="col col-lg-3">
                                                                <label for="inputPassword6" class="col-form-label">Ventas turno</label>
                                                            </div>
                                                            <div class="col col-lg-1 input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-tachometer-alt"></i>&nbsp;</span>
                                                            </div>
                                                            <input type="number" value="<?php echo $row['ingreso'];?>" class="col col-lg-8 form-control" name="ingresos" placeholder="$" aria-label="ingresos" aria-describedby="basic-addon1" required autofocus readonly>
                                                        </div>
                                                        <?php } ?>

                                                        <?php while ($row =  $egresostotal->fetch_assoc()) { ?>
                                                         <div class="input-group mb-2">
                                                            <div class="col col-lg-3">
                                                                <label for="inputPassword6" class="col-form-label">Gastos turno</label>
                                                            </div>
                                                            <div class="col col-lg-1 input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-tachometer-alt"></i>&nbsp;</span>
                                                            </div>
                                                            <input type="number" value="<?php echo $row['egreso']?>" class="col col-lg-8 form-control" name="egresos" placeholder="$" aria-label="egresos" aria-describedby="basic-addon1" required autofocus readonly>
                                                        </div>
                                                        <?php } ?>

                                                        <?php while ($row =  $total->fetch_assoc()) { ?>    
                                                        <div class="input-group mb-2">
                                                            <div class="col col-lg-3">
                                                                <label for="inputPassword6" class="col-form-label">Total</label>
                                                            </div>
                                                            <div class="col col-lg-1 input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-tachometer-alt"></i>&nbsp;</span>
                                                            </div>
                                                            <input type="number" value="<?php echo $row['total']?>" class="col col-lg-8 form-control" name="total" placeholder="$" aria-label="total" aria-describedby="basic-addon1" required autofocus readonly>
                                                        </div>
                                                        <?php } ?>

                                                        <div class="input-group mb-2">
                                                            <div class="col col-lg-3">
                                                                <label for="inputPassword6" class="col-form-label">Dinero Final</label>
                                                            </div>
                                                            <div class="col col-lg-1 input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-tachometer-alt"></i>&nbsp;</span>
                                                            </div>
                                                            <input type="number" id="dinerofinal" value="" class="col col-lg-8 form-control" name="dinerofinal" placeholder="$" aria-label="dinerofinal " aria-describedby="basic-addon1" required autofocus>
                                                        </div>    
                                                       
                                                        <input type="hidden" value="cerrada" class="col col-lg-8 form-control" name="estado" placeholder="estado" aria-label="estado" aria-describedby="basic-addon1" required autofocus>
                                                        <input type="hidden" value="SI" class="col col-lg-8 form-control" name="liquidado" placeholder="liquidado" aria-label="liquidado" aria-describedby="basic-addon1" required autofocus>

                                                        <div class="d-grid gap-2">
                                                            <button type="submit" class="btn btn-secondary btn btn-block" name="register" href="usuarios_nuevos.php"><i class="bi bi-plus-lg text-white">&nbsp;GUARDAR</i></button>
                                                        </div>
                                                        <?php } ?>
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
                    <!-- modal cierre arqueo -->

                    <!-- arqueo diarias por mes-->
                                                            


                        <div class="card m-3">
                            <div class="card-header text-center">
                                <h4 class="mt-2 text-center text-grey"> Arqueo de caja <?php setlocale(LC_TIME, "spanish");
                                                                                        echo strftime("%B"); ?></h4>
                            </div>
                    
                            <div class="card-body">
                                <!--  botones modal  -->
                    <div class="row">
                        <!--  botones modal  -->
                            <!--  Modal trigger button  -->
                                <div class="col col-sm-2 col-md-2 p-10">
                                    <button type="button" class="btn btn-outline-primary btn-md mb-2" data-bs-toggle="modal" data-bs-target="#modalabrir">
                                        <strong>+ Abrir Caja</strong>
                                    </button>
                                </div>
                            <!--  Modal trigger button  -->
                            <!--  Modal trigger button  -->
                                <div class="col col-sm-2 col-md-2">
                                    <button type="button" class="btn btn-outline-success btn-md mb-2" data-bs-toggle="modal" data-bs-target="#modalcerrar">
                                        <strong>- Cerrar Caja</strong>
                                    </button>
                                </div>
                            <!--  Modal trigger button  -->                        
                    </div>
                    <!--  botones modal  -->
                                <table id="arqueo" class="display nowrap table table-hover table-bordered table-striped table-sm col col-lg-11" style="width:100%;font-size:10px">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>CAJERO</th>
                                            <th>APERTURA</th>
                                            <th>CIERRE</th>                                            
                                            <th>$-INICIAL</th>                                            
                                            <th>INGRESOS</th>
                                            <th>EGRESOS</th>                                            
                                            <th>TOTAL</th>
                                            <th>$-FINAL</th>                                           
                                            <th>CUADRE</th>
                                            <th>ESTADO</th>                                            
                                            <th>USER</th>
                                        </tr>

                                    </thead>

                                    <tbody class="">
                                        <?php
                                        while ($fila = $arqueo->fetch_array()) {
                                            $idarqueo = $fila['arqueo_id'];
                                            $apertura = $fila['fecha_apertura'];
                                            $salida = $fila['fecha_cierre'];
                                            $cajero = $fila['cajero'];
                                            $monto_ini = $fila['monto_inicial'];
                                            $monto_fin = $fila['monto_final'];
                                            $ingresos = $fila['total_ingresos'];
                                            $egresos = $fila['total_egresos'];
                                            $cierre = $fila['total_cierre'];
                                            $cuadre = $fila['cuadre'];
                                            $estado = $fila['estado'];
                                            $usuario1 = $fila['usuario'];

                                            if($cuadre < 0){$label_class1 = 'badge bg-danger';}
                                            elseif($cuadre == 0){$label_class1 = "badge bg-success" ;}
                                            elseif($cuadre > 0){$label_class1 = "badge bg-primary" ;}
                                            
                                            if ($estado == 'abierta'){$label_class = 'badge bg-danger';}
                                            elseif ($estado == 'cerrada'){$label_class = "badge bg-success" ;}
                                        ?>
                                            <tr class="">
                                                <td><?php echo $idarqueo ?></td>
                                                <td><?php echo $cajero; ?></td>
                                                <td><?php echo $apertura ?></td>
                                                <td><?php echo $salida; ?></td>                                                
                                                <td style="font-size:12px">$<?php echo number_format($monto_ini, 0, ",", ".") ?></td>                                                
                                                <td style="font-size:12px">$<?php echo number_format($ingresos, 0, ",", ".") ?></td>
                                                <td style="font-size:12px">$<?php echo number_format($egresos, 0, ",", ".") ?></td>
                                                <td style="font-size:12px">$<?php echo number_format($cierre, 0, ",", ".") ?></td>
                                                <td style="font-size:12px">$<?php echo number_format($monto_fin, 0, ",", ".") ?></td>
                                                <td style="text-align: right"><span style="font-size:12px" class="label <?php echo $label_class1; ?>">$&nbsp;<?php echo number_format($cuadre, 0, ",", "."); ?></span></td>
                                                <td><span style="font-size:12px" class="label <?php echo $label_class; ?>"><?php echo $estado; ?></span></td>
                                                <td><?php echo $usuario1 ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <!-- arqueo diarias por mes-->

                </div>
            </div>
        </main>
        <!-- footer -->
        <?php require '../logs/nav-footer.php'; ?>
        <!-- fin footer -->
    </div>
    <script src="https://cdn.datatables.net/2.0.4/js/dataTables.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.dataTables.js" crossorigin="anonymous"></script>
    
   

    <script>
        $(document).ready(function() {
            $('#arqueo').DataTable({
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
                "order": [
                    [0, "desc"]
                ],
                'pageLength': 25,


            });

        });
    </script>
</body>

</html>