<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
include 'conexion.php';

$accion = $_REQUEST["accion"];

if ($accion == "cargarTabla") {
    $arreglo = DevolverUnArreglo("SELECT * from poblacion");
    $validar = array('poblacion' => $arreglo);
}


if ($accion == "guardarpoblacion") {
    $poblacion = $_REQUEST["poblacion"];

    $esta = DevolverUnDato("select count(*) from poblacion where TipoPoblacion = '$poblacion'");
    if ($esta > 0) {
        $validar = array('respuesta' => "Este tipo de población ya esta Registrado");
    } else {
        try {
            hacerConsulta("insert into poblacion (TipoPoblacion) values ('$poblacion')");
            $validar = array('respuesta' => "El tipo de poblacion se ha guardado Correctamente");
        } catch (Exception $ex) {
            $validar = array('respuesta' => "Error al guardar en Base de Datos");
        }
    }
}



if ($accion == "eliminarPoblacion") {
    $numPoblacion = $_REQUEST["numPoblacion"];
    try {
        hacerConsulta("delete from poblacion where IdPoblacion = $numPoblacion");
        $validar = array('respuesta' => 'Eliminado');
    } catch (Exception $ex) {
        $validar = array('respuesta' => 'error al eliminar registro en la BD');
    }
}


echo json_encode($validar, JSON_FORCE_OBJECT);
?>