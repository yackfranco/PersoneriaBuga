<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
include 'conexion.php';

$accion = $_REQUEST["accion"];

if ($accion == "cargarDatos") {
    $cont = DevolverUnDato("select count(*) from configtv");
    if ($cont > 0) {
        $resp = DevolverUnArreglo("SELECT * from configtv");
    } else {
        $resp = "sin datos";
    }

    $validar = array('respuesta' => $resp);
}

if ($accion == "guardarDatos") {
    $MensajeR = $_REQUEST["MensajeR"];
    $Nvideo = $_REQUEST["Nvideo"];
    $Fuente = $_REQUEST["Fuente"];
    $Imagen = addslashes(file_get_contents($_FILES['Imagen']['tmp_name']));


    try {

        $cont = DevolverUnDato("select count(*) from configtv");
        if ($cont > 0) {
            hacerConsulta("Update configtv set mensaje = '$MensajeR', video = '$Nvideo', TamanoLetra = '$Fuente', logo = '$Imagen'");
   $consulta = "insert into tv (Modulo,Estado) values ('#12#.3421D',1)";
            hacerConsulta($consulta);   
 


$consulta = "insert into tv (Modulo,Estado) values ('#12#.3421D',2)";
            hacerConsulta($consulta);
 } else {
            $consulta = "insert into configtv (mensaje,video,TamanoLetra,logo) values ('$MensajeR','$Nvideo','$Fuente','$Imagen')";
            hacerConsulta($consulta);
        }
        $resp = "Guardado Correctamente";
    } catch (Exception $ex) {
        $resp = "Error al Guardar Configuracion de Los Turnos Perdidos";
    }

    $validar = array('respuesta' => $resp);
}


echo json_encode($validar, JSON_FORCE_OBJECT);
?>
