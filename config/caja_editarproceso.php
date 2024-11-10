<?php

//print_r($_POST);
if (!isset($_POST['editar'])) {
    header('location: caja_conceptos.php?mensaje=error');
}

include '../conexion/conexion3.php';

$idCat = $_POST['cat_id'];
$nombreCat = $_POST['cat_nombre'];
$descCat = $_POST['cat_descripcion'];


$sentencia = $bd->prepare(" UPDATE  caja_conceptos 
                                SET     nombre_concepto=? , observacion=?
                                WHERE   id_concepto = ?;");
$resultado = $sentencia->execute([$nombreCat, $descCat, $idCat]);

if ($resultado === TRUE) {
    header('location: caja_conceptos.php?mensaje=editado');
} else {
    header('location: caja_conceptos.php?mensaje=falta');
}
