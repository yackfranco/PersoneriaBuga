<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
include 'conexion.php';

$accion = $_REQUEST["accion"];

if ($accion == "cargarTabla") {
    $arreglo = DevolverUnArreglo("SELECT * from usuario where Rol='ASESOR' ORDER BY `usuario`.`Estado` DESC");
    if ($arreglo == null) {
        http_response_code(401);
        $validar = [];
    } else {
//    $validar = array('id' => $arreglo);
        $validar = array('respuesta' => $arreglo);
    }
}
if ($accion == "cargarTablaServicios") {
    $fecha = (new \DateTime())->format('Y-m-d');
    $arreglo = DevolverUnArreglo("select servicio.Servicio, COUNT(auditoria.IdServicio) as Cantidad from auditoria JOIN servicio on (auditoria.IdServicio = servicio.IdServicio) where auditoria.FechaLlegada >= '$fecha 00:00:00' and auditoria.FechaLlegada<='$fecha 23:59:59' and Estado = 'TERMINADO' or Estado = 'AUSENTE' GROUP by auditoria.IdServicio");
    
    if ($arreglo == null) {
        http_response_code(401);
        $validar = [];
    } else {
//    $validar = array('id' => $arreglo);
        $validar = array('respuesta' => $arreglo);
    }
}
if ($accion == "cargarTablaTipoPoblacion") {
    $fecha = (new \DateTime())->format('Y-m-d');
    $arreglo = DevolverUnArreglo("select encuesta.TipoPoblacion, COUNT(encuesta.TipoPoblacion) as Cantidad from auditoria JOIN encuesta on (auditoria.IdEncuesta = encuesta.IdEncuesta) where Estado = 'TERMINADO' and auditoria.FechaLlegada >= '$fecha 00:00:00' and auditoria.FechaLlegada<='$fecha 23:59:59' GROUP by encuesta.TipoPoblacion");
    
    if ($arreglo == null) {
        http_response_code(401);
        $validar = [];
    } else {
//    $validar = array('id' => $arreglo);
         $validar = array('respuesta' => $arreglo);
    }
}

if ($accion == "cargarTablaUsuariocant") {
    $fecha = (new \DateTime())->format('Y-m-d');
    $arreglo = DevolverUnArreglo("select usuario.NombreUsuario, COUNT(auditoria.IdUsuario) as Cantidad from auditoria JOIN usuario on (auditoria.IdUsuario = usuario.IdUsuario) where auditoria.Estado = 'TERMINADO' and auditoria.FechaLlegada >= '$fecha 00:00:00' and auditoria.FechaLlegada<='$fecha 23:59:59' GROUP by usuario.IdUsuario");
    
    if ($arreglo == null) {
        http_response_code(401);
        $validar = [];
    } else {
//    $validar = array('id' => $arreglo);
        $validar = array('respuesta' => $arreglo);
    }
}

//select usuario.NombreUsuario, COUNT(auditoria.IdUsuario) as Cantidad from auditoria JOIN usuario on (auditoria.IdUsuario = usuario.IdUsuario) where auditoria.Estado = 'TERMINADO' and auditoria.FechaLlegada >= '2018-12-06 00:00:00' and auditoria.FechaLlegada<='2018-12-06 23:59:59' GROUP by usuario.IdUsuario



echo json_encode($validar, JSON_FORCE_OBJECT);
?>