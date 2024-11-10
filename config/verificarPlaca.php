<?php
require '../conexion/conexion.php';
$placa    = $_REQUEST['placa'];

//Verificando si existe algun cliente en bd ya con dicha cedula asignada
//Preparamos un arreglo que es el que contendrá toda la información
$jsonData = array();
$selectQuery   = (" SELECT  CL.nombre,
                            CL.celular,
                            CL.vehiculo,
                            CL.categoria,
                            CA.cat_nombre
                    FROM    cliente as CL
                    INNER JOIN categorias as CA ON CL.categoria = CA.cat_id
                    WHERE placa='" . $placa . "' ");
$query         = mysqli_query($mysqli, $selectQuery);
$totalCliente  = mysqli_num_rows($query);

while ($row = $query->fetch_assoc()) {
  $nombre = $row['nombre'];
  $celular = $row['celular'];
  $vehiculo = $row['vehiculo'];
  $nombreCat = $row['cat_nombre'];
  $categoria = $row['categoria'];
}
// $json = json_encode($nombre);
// header('Content-Type: application/json; charset=utf8');
// echo $json;

//Validamos que la consulta haya retornado información
if ($totalCliente <= 0) {
  $jsonData['success'] = 0;
  $jsonData['message'] = '<p class="respuestas" id="respuestas" name="respuestas" style="color:green;background:#dcece2">No existen datos con esta Placa <strong>( ' . $placa . ' )</strong> por favor ingrese todos los datos...</p>';
  //$jsonData['message'] = '';
} else {
  //Si hay datos entonces retornas algo
  $jsonData['success'] = 1;
  $jsonData['message'] = '<p class="respuestas" id="respuestas" name="respuestas" style="color:red;background:#f7d9ee">Ya existen datos con la Placa <strong>( ' . $placa . ' )</strong> por favor verifique que sean correctos...</p>';

  $jsonData['nombre'] = "$nombre";
  $jsonData['celular'] = "$celular";
  $jsonData['vehiculo'] = "$vehiculo";
  $jsonData['categoria'] = "$categoria";
  $jsonData['nombreCat'] = "$nombreCat";
}
//Mostrando mi respuesta en formato Json
header('Content-type: application/json; charset=utf-8');
echo json_encode($jsonData);
