<?php
require '../conexion/conexion.php';

$idcategoria = $mysqli->real_escape_string($_POST['cat_id']);

$consulta = "   SELECT      tar_id,
                            tar_nombre,
                            tar_tiempo,
                            tar_valor 
                FROM        tarifas AS TA
                INNER JOIN  tar_tiempo AS TT ON TA.tar_nombre = TT.tar_id_nombre
                WHERE       tar_categoria = $idcategoria 
                ORDER BY    tar_id ASC  
                ";
$resultado = $mysqli->query($consulta);


$respuesta = "";

while ($row = $resultado->fetch_assoc()) {
    $respuesta .= "<option value='" . $row['tar_id'] . "'>" . $row['tar_tiempo'] . " - $" . $row['tar_valor'] . "</option>";
}

echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
