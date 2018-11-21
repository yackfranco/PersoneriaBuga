<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
include 'conexion.php';

$user = $_POST["usuario"];
$contra = $_POST["contrasena"];
//echo "HOLA";
//print_r($contra);


$arreglo = DevolverUnArreglo("SELECT * from usuario where NombreUsuario='$user' and Contrasena = '".hash('MD5', $contra)."'");
if($arreglo == null){
    http_response_code(401);
    $validar = [];
}else {
//    $validar = array('id' => $arreglo);
        $validar = $arreglo;
}

echo json_encode($validar, JSON_FORCE_OBJECT);
?>