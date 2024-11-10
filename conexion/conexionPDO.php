<?php
$host="localhost";
$dbname="parqueadero";
$user="root";
$pass="";



try{
  $pdo= new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

echo "";

}catch(PDOException $e){

    echo "no se conecto";
}




?>