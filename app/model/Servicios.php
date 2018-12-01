<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
include 'conexion.php';

$accion = $_REQUEST["accion"];

if ($accion == "cargarTabla") {
    $arreglo = DevolverUnArreglo("SELECT * from servicio");
    if ($arreglo == null) {
        http_response_code(401);
        $validar = [];
    } else {
//    $validar = array('id' => $arreglo);
        $validar = array('respuesta' => $arreglo);
    }
}


if ($accion == "guardarServicio") {
    $nombreServicio = $_REQUEST["nombreServicio"];
    $Prefijo = $_REQUEST["Prefijo"];
    $ConteoMinimo = $_REQUEST["ConteoMinimo"];
    $ConteoMaximo = $_REQUEST["ConteoMaximo"];
    $Secuencia = $_REQUEST["Secuencia"];
    $prioridad = $_REQUEST["prioridad"];
    $tv = $_REQUEST["tv"];


//Pregunta si el numero a insertar es de tipo int
    if (is_numeric($ConteoMinimo) && is_numeric($ConteoMaximo) && is_numeric($prioridad) && is_numeric($Secuencia)) {
        try {
            hacerConsulta("insert into servicio (Prefijo, Servicio,Cont_min, Cont_max, Secuencia, Prioridad, LLamadoTv, Color,ColorLetra) "
                    . "values ('$Prefijo','$nombreServicio',$ConteoMinimo,$ConteoMaximo,$Secuencia,$prioridad,$tv,'','')");
            $validar = array('respuesta' => "Registro Guardado Correctamente");
        } catch (Exception $ex) {
            
        }
    } else {
        $validar = array('respuesta' => "El Dato ingresado debe de ser un Numero");
    }
}


if ($accion == "EliminarServicio") {
    $IdServicio = $_REQUEST["IdServicio"];
    try {
        hacerConsulta("delete from servicio where IdServicio = $IdServicio");
        $validar = array('respuesta' => 'Eliminado');
    } catch (Exception $ex) {
        $validar = array('respuesta' => 'error al eliminar registro en la BD');
    }
}

if ($accion == "TraerDatosEditar") {
    $IdServicio = $_REQUEST["IdServicio"];
    try {
        $arreglo = DevolverUnArreglo("select * from servicio where IdServicio = $IdServicio");
        $validar = array('respuesta' => $arreglo);
    } catch (Exception $ex) {
        $validar = array('respuesta' => 'Error al traer datos de Editar');
    }
}

if ($accion == "editarServicio") {
    $IdServicio = $_REQUEST["IdServicio"];
    $nombreServicio = $_REQUEST["Servicio"];
    $Prefijo = $_REQUEST["Prefijo"];
    $Cmin = $_REQUEST["Cmin"];
    $Cmax = $_REQUEST["Cmax"];
    $Secuencia = $_REQUEST["Secuencia"];
    $prioridad = $_REQUEST["prioridad"];
    $tv = $_REQUEST["tv"];
    try {
        hacerConsulta("update servicio set Prefijo='$Prefijo', Servicio='$nombreServicio',Cont_min=$Cmin, Cont_max=$Cmax,"
                . " Secuencia=$Secuencia, Prioridad=$prioridad, LLamadoTv=$tv where IdServicio = $IdServicio ");
        $validar = array('respuesta' => "Editado Correctamente");
    } catch (Exception $ex) {
        $validar = array('respuesta' => 'Error al traer datos de Editar');
    }
}



echo json_encode($validar, JSON_FORCE_OBJECT);
?>