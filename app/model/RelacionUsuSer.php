<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
include 'conexion.php';

$accion = $_REQUEST["accion"];

if ($accion == "CargarUsuarios") {
    $arreglo = DevolverUnArreglo("SELECT * from usuario where Rol = 'ASESOR'");
    $validar = array('respuesta' => $arreglo);
}

if ($accion == "ServicioRelacionados") {
    $IdUsuario = $_REQUEST["idUsuario"];
    try {
        $arreglo = DevolverUnArreglo("SELECT  servicio.Servicio, servicio.IdServicio FROM    relacionususer join servicio on (relacionususer.IdServicio = servicio.IdServicio) where relacionususer.IdUsuario = $IdUsuario");
        $validar = array('respuesta' => $arreglo);
    } catch (Exception $ex) {
        
    }
}

if ($accion == "ServicioNoRelacionados") {
    $IdUsuario = $_REQUEST["idUsuario"];
    try {
        $arreglo = DevolverUnArreglo("SELECT * FROM servicio WHERE NOT EXISTS ( SELECT relacionususer.IdServicio FROM relacionususer WHERE servicio.IdServicio = relacionususer.IdServicio and relacionususer.IdUsuario = $IdUsuario )");
        $validar = array('respuesta' => $arreglo);
    } catch (Exception $ex) {
        
    }
}

if ($accion == "RelacionarServicio") {
    $IdServicio = $_REQUEST["idServicio"];
    $IdUsuario = $_REQUEST["idUsuario"];
    try {
        hacerConsulta("insert into relacionususer (IdServicio, Idusuario) values ($IdServicio,$IdUsuario)");
        $validar = array('respuesta' => "Guardado Correctamente");
    } catch (Exception $ex) {
        $validar = array('respuesta' => "Error al guardar la relacion de usuario y servicio");
    }
}


if ($accion == "EliminarRelacionarServicio") {
    $IdServicio = $_REQUEST["idServicio"];
    $IdUsuario = $_REQUEST["idUsuario"];
    try {
        hacerConsulta("delete from relacionususer where IdServicio = $IdServicio and IdUsuario = $IdUsuario");
        $validar = array('respuesta' => "Guardado Correctamente");
    } catch (Exception $ex) {
        $validar = array('respuesta' => "Error al guardar la relacion de usuario y servicio");
    }
}


//SELECT  servicio.servicio
//FROM    servicio
//WHERE   NOT EXISTS
//        (
//        SELECT  relacionususer.IdServicio 
//        FROM    relacionususer
//        WHERE   servicio.IdServicio = relacionususer.IdServicio
//        )
//SELECT  servicio.Servicio
//        FROM    relacionususer
//        join servicio on (relacionususer.IdServicio = servicio.IdServicio) where relacionususer.IdUsuario = 4


echo json_encode($validar, JSON_FORCE_OBJECT);
?>