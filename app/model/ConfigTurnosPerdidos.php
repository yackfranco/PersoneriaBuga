<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
include 'conexion.php';

$accion = $_REQUEST["accion"];

if ($accion == "cargarDatos") {
    $cont = DevolverUnDato("select count(*) from configturnosperdidos");
    if ($cont > 0) {
        $resp = DevolverUnArreglo("SELECT * from configturnosperdidos");
    } else {
        $resp = "sin datos";
    }
    $permisoReiniciar = DevolverUnDato("select permiso from reiniciar");
    $validar = array('respuesta' => $resp, 'permiso' => $permisoReiniciar);
}

if ($accion == "guardarDatos") {
    $tiempoEspera = $_REQUEST["tiempoEspera"];
    $NumeroLlamado = $_REQUEST["NumeroLlamado"];
    $permisoReiniciar= $_REQUEST["permiso"];
    
    try {
        $cont = DevolverUnDato("select count(*) from configturnosperdidos");
        if ($cont > 0) {
            hacerConsulta("Update configturnosperdidos set TiempoEspera = $tiempoEspera, NumeroLlamado = $NumeroLlamado");
        } else {
            hacerConsulta("insert into configturnosperdidos (TiempoEspera,NumeroLlamado) values ($tiempoEspera,$NumeroLlamado)");
        }
        hacerConsulta("update reiniciar set permiso = $permisoReiniciar");
        $resp = "Guardado Correctamente";
    } catch (Exception $ex) {
        $resp = "Error al Guardar Configuracion de Los Turnos Perdidos";
    }

    $validar = array('respuesta' => $resp);
}


echo json_encode($validar, JSON_FORCE_OBJECT);
?>
