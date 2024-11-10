<?php

//print_r($_POST);
    if(!isset($_POST['editar'])){
        header('location: tipo_usuarios.php?mensaje=error');
    }

    include '../conexion/conexion3.php';

    $idrol = $_POST['id_tipo_usuario']; 
    $nombrerol = $_POST['tipo_usuario'];       
    
    $sentencia = $bd->prepare(" UPDATE  tipo_usuarios 
                                SET     tipo_usuario=?
                                WHERE   id_tipo_usuario = ?;");
    $resultado = $sentencia->execute([$nombrerol, $idrol]);

    if($resultado === TRUE){
        header('location: tipo_usuarios.php?mensaje=editado');

    }    else {
        header('location: tipo_usuarios.php?mensaje=falta');

    }
?>