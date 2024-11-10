<?php

//print_r($_POST);
if (!isset($_POST['editar'])) {
    header('location: categorias.php?mensaje=error');
}

include '../conexion/conexion3.php';

$idTar = $_POST['tar_id'];
$valorTar = $_POST['valor'];



$sentencia = $bd->prepare(" UPDATE  tarifas 
                                SET     tar_valor=?
                                WHERE   tar_id = ?;");
$resultado = $sentencia->execute([$valorTar, $idTar]);

if ($resultado === TRUE) {
    header('location: tarifas.php?mensaje=editado');
    } else {
    header('location: tarifas.php?mensaje=falta');
}
