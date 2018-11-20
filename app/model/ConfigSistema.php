<?php

header("Access-Control-Allow-Origin: *");
include 'conexion.php';

$accion = $_POST["accion"];
if ($accion == "cargarArea") {

    $areas = DevolverUnArreglo("select * from area");
    $validar = array('areas' => $areas);
}
if ($accion == "EditarArea") {
    try {
        $idArea = $_POST["idArea"];
        $textoArea = $_POST["textoArea"];
        hacerConsulta("UPDATE area SET are_nombre = '" . $textoArea . "' where are_codigo = '" . $idArea . "'");
        $validar = array('estado' => "hecho");
    } catch (Exception $ex) {
        $validar = array('estado' => "Error");
    }
}

if ($accion == "EliminarArea") {
    try {
        $idArea = $_POST["idArea"];
        hacerConsulta("delete from area where are_codigo = '" . $idArea . "'");
        $validar = array('estado' => "hecho");
    } catch (Exception $ex) {
        $validar = array('estado' => "Error");
    }
}

if ($accion == "CrearArea") {
    try {
        $textoArea = $_POST["TextoArea"];
        $IdUsuario = $_POST["idUsuario"];
        $ultimoId = DevolverUnDato("select max(are_codigo) from area");
        $ultimoId = $ultimoId + 1;
        $hoy = getdate();
        hacerConsulta("insert into area (are_codigo, are_nombre, are_estado, are_prueba, creado_por) values ('".$ultimoId."','".$textoArea."',1,'0','".$IdUsuario."')");
        $validar = array('estado' => "hecho");
    } catch (Exception $ex) {
        $validar = array('estado' => "Error");
    }
}

header('Content-Type: application/json');
echo json_encode($validar, JSON_FORCE_OBJECT);
?>