<?php

//print_r($_POST);
    if(!isset($_POST['editar'])){
        header('location: categorias.php?mensaje=error');
    }

    include '../conexion/conexion3.php';

    $idCat = $_POST['cat_id']; 
    $nombreCat = $_POST['cat_nombre'];
    $descCat = $_POST['cat_descripcion'];

    
    $sentencia = $bd->prepare(" UPDATE  categorias 
                                SET     cat_nombre=? , cat_descripcion=?
                                WHERE   cat_id = ?;");
    $resultado = $sentencia->execute([$nombreCat, $descCat, $idCat]);

    if($resultado === TRUE){
        header('location: categorias.php?mensaje=editado');

    }    else {
        header('location: categorias.php?mensaje=falta');

    }
?>