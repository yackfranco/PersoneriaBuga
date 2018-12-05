<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
include 'conexion.php';

$accion = $_REQUEST["accion"];

if ($accion == "DatosEmpresa") {
    $arreglo = DevolverUnArreglo("SELECT * from datosempresa");
    $validar = array('respuesta' => $arreglo);
}

echo json_encode($validar, JSON_FORCE_OBJECT);
?>
