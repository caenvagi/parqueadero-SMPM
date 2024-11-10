<?php

//print_r($_POST);
    if(!isset($_POST['editar'])){
        header('location: tipo_cargo.php?mensaje=error');
    }

    include '../conexion/conexion3.php';

    $idcargo = $_POST['id_cargo']; 
    $nombrecargo = $_POST['nombre_cargo']; 
         
    
    $sentencia = $bd->prepare(" UPDATE  tipo_cargo 
                                SET     cargo_nombre=?
                                WHERE   id_cargo = ?;");
    $resultado = $sentencia->execute([$nombrecargo, $idcargo]);

    if($resultado === TRUE){
        header('location: tipo_cargo.php?mensaje=editado');

    }    else {
        header('location: tipo_cargo.php?mensaje=falta');

    }
?>