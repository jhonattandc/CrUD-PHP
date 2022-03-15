<?php

$servidor = "mysql:dbname=empresa;host=127.0.0.1";
$usuario = "root";
$contraseña = "";

try{
    $pdo = new PDO ($servidor, $usuario, $contraseña);
    
}catch(PDOException $e){
    echo "Conexion mala :( " . $e->getMessge() . "<br>";
    die();
}

?>