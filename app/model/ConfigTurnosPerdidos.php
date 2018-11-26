<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
include 'conexion.php';

$accion = $_REQUEST["accion"];

if ($accion == "cargarDatos") {
    $cont = DevolverUnDato("select count(*) from configturnoperdido");
    if ($cont > 0) {
        $resp = DevolverUnArreglo("SELECT * from configturnoperdido");
    } else {
        $resp = "sin datos";
    }

    $validar = array('respuesta' => $resp);
}

if ($accion == "guardarDatos") {
    $tiempoEspera = $_REQUEST["tiempoEspera"];
    $NumeroLlamado = $_REQUEST["NumeroLlamado"];
    try {
        $cont = DevolverUnDato("select count(*) from configturnoperdido");
        if ($cont > 0) {
            hacerConsulta("Update configturnoperdido set TiempoEspera = $tiempoEspera, NumeroLlamado = $NumeroLlamado");
        } else {
            hacerConsulta("insert into configturnoperdido (TiempoEspera,NumeroLlamado) values ($tiempoEspera,$NumeroLlamado)");
        }
        $resp = "Guardado Correctamente";
    } catch (Exception $ex) {
        $resp = "Error al Guardar Configuracion de Los Turnos Perdidos";
    }

    $validar = array('respuesta' => $resp);
}


echo json_encode($validar, JSON_FORCE_OBJECT);
?>