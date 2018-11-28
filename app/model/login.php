<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
include 'conexion.php';

$accion = $_REQUEST["accion"];

if ($accion == "cargarUsuarios") {
    $arreglo = DevolverUnArreglo("SELECT * from usuario");
    $validar = $arreglo;
}

if ($accion == "cargarModulos") {
    $arreglo = DevolverUnArreglo("SELECT * from modulo order by Idmodulo asc");
    $validar = $arreglo;
}

if ($accion == "traerRol") {
    $idusuario = $_REQUEST["idUsuario"];
    $arreglo = DevolverUnDato("SELECT rol from usuario where IdUsuario = $idusuario");
    $validar = $arreglo;
}


if ($accion == "entrar") {
    $user = $_POST["usuario"];
    $user = DevolverUnDato("select NombreUsuario from usuario where IdUsuario = $user");
    $contra = $_POST["contrasena"];
    $consulta = "SELECT * from usuario where NombreUsuario='$user' and Contrasena = '" . hash('MD5', $contra) . "'";
//    echo $consulta;
//    exit();
    $arreglo = DevolverUnArreglo($consulta);
    $validar = $arreglo;
}


echo json_encode($validar, JSON_FORCE_OBJECT);
?>
