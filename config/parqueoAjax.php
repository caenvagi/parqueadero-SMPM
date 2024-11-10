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

// inicio consultas
$query = "  SELECT  *
                FROM    categorias
    ";
$categorias = $mysqli->query($query);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php require '../logs/head.php'; ?>
    
    <link rel="preconnect" href="https://fonts.googleapis.com" />
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet" />
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
            <div class="container" id="cont-parqueo1">                    
                <!-- Respuesta consulta clientes --> 
                    <div class="col col-lg-12 bg-dark mt-3 ms-3 rounded">  
                        <form action="" class="" >
                            <div class="row align-items-center">
                                <div class="col col-lg-6">
                                    <p class="text-light fs-4 m-2 p-2">Buscar</p>
                                </div>
                                <div class="col col-lg-4">
                                    <input type="search" id="search" class="form-control  " placeholder="Buscar automovil" aria-label="Buscar automovil">
                                </div>
                                <div class="col col-lg-2">
                                    <button class="btn btn-success  ">Buscar</button>
                                </div>
                            </div>
                        </form>                                    
                    </div>            
                <!-- fin Respuesta consulta clientes -->

                <!-- inicio de alertas-->
                    <div class="contenedor-toasts" id="contenedor-toasts"></div>
                    <div id="mensaje"></div>
                    <div id="alerta"></div>
                    <div id="respuestas" name="respuestas"> </div>
                    <div id="park-result">
                        <div class="card-body">
                            <ul id="container"></ul>
                        </div>
                    </div>
                <!-- fin de alertas-->

                <!-- formulario y lista de vehiculos-->
                    <div class="row">                                       
                        <!-- formulario ingresar parqueo-->
                            <div  class="col col-12 col-sm-12 col-md-3 col-lg-4 col-xl-4 m-3" >
                                <form id="parqueo" name="parqueo" action="" >
                                    <di1v class="card" id="cardForm_parqueo">
                                        <div class="header">Ingresar datos del vehiculo:</div>
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
                                            <button onclick=""  type="submit" class="btn btn-secondary btn btn-block" name="register" id="register" href="">
                                                <div class="spinner-grow spinner-grow-sm text-light" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <i class="bi bi-plus-lg text-white">&nbsp;GUARDAR</i>
                                            </button>
                                        </div>
                                    </di1v>
                                </form>
                            </div>
                        <!-- fin formulario ingresar parqueo --> 
                        <!-- cards vehiculos-->
                        <div class="col col-lg-7">
                            <div id="cards" class="row">
                            </div>
                        </div>                         
                        <!-- fin cards vehiculos-->                    
                    </div>
                <!-- fin formulario y lista de vehiculos-->    
            </div>
        </main>            
        <!-- footer -->
        <?php require '../logs/nav-footer.php'; ?>
        <!-- fin footer -->
    </div>
        <!-- <script src="../js/popper.min.js"></script> -->
        <script src="../js/funcion.js"></script>  
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
                        return false;}
                    if (event.which < 48 || event.which > 123) {
                        return false;}
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
                                    
                                    // console.log(datos.categoria);
                                    const cbxCategorias = document.getElementById('categoria');
                                    // console.log(cbxCategorias);
                                    cbxCategorias.addEventListener('focusin', get_Tarifas)
                                    const cbxTarifas = document.getElementById('tarifas');
                                    // console.log(cbxTarifas);

                                    function get_Tarifas() {
                                        let categorias = cbxCategorias.value
                                        let url = 'get_tarifas.php'
                                        let formData = new FormData()
                                        formData.append('cat_id', categorias)
                                        fetchAndSetData(url, formData, cbxTarifas)}
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
        <script type="text/javascript">
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
                    .catch(err => console.log(err))}
            function get_Tarifas() {
                let categorias = cbxCategorias.value
                let url = 'get_tarifas.php'
                let formData = new FormData()
                formData.append('cat_id', categorias)
                fetchAndSetData(url, formData, cbxTarifas)}
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
        <!-- <script type="text/javascript">
            function openInf() {
                window.open("../factura/pdf_ticket.php")}
        </script> -->
        <script type="text/javascript">
            function openRec() {
                window.open("../factura/pdf_recibo.php")}

                $("#register").click(function() {
            $("repuestas").hide();
            });

            
        </script>
</body>
</html>