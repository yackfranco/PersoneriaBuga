<?php
 header("Access-Control-Allow-Origin: *");
include 'conexion.php';

$accion = $_POST["accion"];

if ($accion == 1) {
    $idusu = $_POST["idusu"];
    $calif = $_POST["calif"];
    $lote = DevolverUnDato("select Nombre from lote where IdLote = " . $calif);
    hacerconsulta("delete from comunicacion where idusu=" . $idusu);
    $validar = array('NombreLote' => $lote);
}
if ($accion == 2) {
    $idusu = $_POST["idusu"];
    //$dato = DevolverUnDato("select count(IdUsuario) from calificacion where IdUsuario = " . $idusu);
    $estado = DevolverUnDato("select estado from relacionloteusu where IdUsu = " . $idusu);
    $validar = array('estado' => $estado);
}

if ($accion == 3) {
    $idusu = $_POST["idusu"];
    $dato2 = DevolverUnDato("update relacionloteusu set estado = 1 where idusu = " . $idusu);
    $validar = array('validar' => 'si');
}
if ($accion == 4) {
    $idusu = $_POST["idusu"];
    $modo = DevolverUnDato("select Modo from estilocalificacion where IdUsuario = " . $idusu);
    $metodo = DevolverUnDato("select Metodo from estilocalificacion where IdUsuario = " . $idusu);
    //Tomo el id de pregunta o del paquete
    $idPregunta = DevolverUnDato("select estilo from estilocalificacion where IdUsuario = " . $idusu);
    $estado = DevolverUnDato("select estado from relacionloteusu where IdUsu = " . $idusu);
    if ($metodo == "Automatico") {
        hacerConsulta("update relacionloteusu set estado = 1 where idusu = " . $idusu);
    } else if ($metodo == "Manual") {
         hacerConsulta("update relacionloteusu set estado = 0 where idusu = " . $idusu);
    }

    if ($modo == "Mono") {

        $pregunta = DevolverUnArreglo("select * from pregunta where IdPregunta = " . $idPregunta);
        $validar = array("estado" => "mono", "dato" => $estado, "Preguntas" => $pregunta, "Metodo" => $metodo);
    } else if ($modo == "Multi") {

        $pregunta = DevolverUnArreglo("select * from pregunta where IdPaquete = " . $idPregunta);
        $validar = array("estado" => "multi", "Preguntas" => $pregunta, "Metodo" => $metodo);
    }
}

if ($accion == 5) {
    //CANCELAR CALIFCACION
    $idusu = $_POST["idusu"];

    $respuesta = hacerConsulta("update relacionloteusu set estado = 0 where IdUsu =" . $idusu);
    $validar = array("estado" => $respuesta);
}

if ($accion == 6) {
    //Update a la tabla relacionLoteUsu para poner el id pregunta donde corresponde
    $idusu = $_POST["idusu"];
    $idpregunta = $_POST["idpregunta"];

    $respuesta = hacerConsulta("update relacionloteusu set idpregunta =" . $idpregunta . " where IdUsu =" . $idusu);
    $validar = array("estado" => $respuesta);
}

if ($accion == 7) {
    $idusu = $_POST["idusu"];
    //$dato = DevolverUnDato("select count(IdUsuario) from calificacion where IdUsuario = " . $idusu);
    $idcomunicacion = DevolverUnDato("select MIN(idcomunicacion) from comunicacion where IdUsu = " . $idusu);
//    Print_r($idcomunicacion);
//    exit;
    if ($idcomunicacion == NULL)
        $validar = array('estado' => "Sin Calificacion");
    else {
        hacerConsulta("delete from comunicacion where idcomunicacion = " . $idcomunicacion);
        $validar = array('estado' => "Calificado");
    }
}

if ($accion == 8) {
    $idusu = $_POST["idusu"];
    $respuesta = hacerConsulta("update relacionloteusu set estado = 1 where IdUsu =" . $idusu);
    $validar = array("estado" => $respuesta);
}


header('Content-Type: application/json');
echo json_encode($validar, JSON_FORCE_OBJECT);
?>