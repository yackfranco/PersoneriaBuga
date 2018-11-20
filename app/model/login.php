<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
include 'conexion.php';

$user = $_POST["usuario"];
$contra = $_POST["contrasena"];
//echo "HOLA";
//print_r($contra);


$arreglo = DevolverUnArreglo("SELECT usuario.usu_codigo, usuario.usu_nombres, usuario.usu_apellidos,tipo_usuario.tip_usu_nombre as rol FROM usuario JOIN tipo_usuario ON (usuario.usu_tip_codigo = tipo_usuario.tip_usu_codigo)where usuario.usu_identificacion = '".$user."' and usuario.usu_contrasena = '".hash('MD5', $contra)."'");
if($arreglo == null){
    http_response_code(401);
    $validar = [];
}else {
//    $validar = array('id' => $arreglo);
        $validar = $arreglo;
}

echo json_encode($validar, JSON_FORCE_OBJECT);
?>