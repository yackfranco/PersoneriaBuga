<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
include 'conexion.php';

$accion = $_REQUEST["accion"];

if ($accion == "cargarTabla") {
    $arreglo = DevolverUnArreglo("SELECT * from modulo");
    if ($arreglo == null) {
        http_response_code(401);
        $validar = [];
    } else {
//    $validar = array('id' => $arreglo);
        $validar = array('modulos' => $arreglo);
    }
}


if ($accion == "guardarModulo") {
    $numModulo = $_REQUEST["numModulo"];

//Pregunta si el numero a insertar es de tipo int
    if (is_numeric($numModulo)) {
//pregunto cuantos modulos hay registrados en bd
        $cont = DevolverUnDato("select count(*) from modulo");
        if ($cont < 10) {
            $esta = DevolverUnDato("select count(*) from modulo where IdModulo = $numModulo");
            if ($esta > 0) {
                $validar = array('respuesta' => "Este modulo Ya esta Registrado");
            } else {
                try {
                    hacerConsulta("insert into modulo (IdModulo) values ($numModulo)");
                    $validar = array('respuesta' => "El Modulo se ha guardado Correctamente");
                } catch (Exception $ex) {
                    $validar = array('respuesta' => "Error al guardar en Base de Datos");
                }
            }
        } else {
            $validar = array('respuesta' => "Ya no puede registrar mas Modulos");
        }
    } else {
        $validar = array('respuesta' => "El Dato ingresado debe de ser un Numero");
    }
}


if ($accion == "eliminarModulo") {
    $numModulo = $_REQUEST["numModulo"];
    try {
        hacerConsulta("delete from modulo where IdModulo = $numModulo");
        $validar = array('respuesta' => 'Eliminado');
    } catch (Exception $ex) {
        $validar = array('respuesta' => 'error al eliminar registro en la BD');
    }
}


echo json_encode($validar, JSON_FORCE_OBJECT);
?>