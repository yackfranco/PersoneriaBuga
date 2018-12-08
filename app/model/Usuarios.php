<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
include 'conexion.php';

$accion = $_REQUEST["accion"];

if ($accion == "cargarTabla") {
    $arreglo = DevolverUnArreglo("SELECT * from usuario");
    if ($arreglo == null) {
        http_response_code(401);
        $validar = [];
    } else {
//    $validar = array('id' => $arreglo);
        $validar = array('respuesta' => $arreglo);
    }
}

//
if ($accion == "guardarUsuario") {
    $NombreCompleto = $_REQUEST["NombreCompleto"];
    $Cedula = $_REQUEST["Cedula"];
    $Correo = $_REQUEST["Correo"];
    $NombreUsuario = $_REQUEST["NombreUsuario"];
    $Rol = $_REQUEST["Rol"];
    $Contrasena = $_REQUEST["Contrasena"];

//Pregunta si el numero a insertar es de tipo int
    if (is_numeric($Cedula)) {
        $sd = DevolverUnDato("select count(*) from usuario where NombreUsuario = '$NombreUsuario'");
        if ($sd > 0) {
            $validar = array('respuesta' => "El usuario ya se encuentra registrado, por favor intente otro");
        } else {
            try {
                if ($Rol == 'ASESOR') {
                    hacerConsulta("insert into usuario (NombreCompleto, Cedula,Correo, NombreUsuario, Rol, Contrasena, Estado) "
                            . "values ('$NombreCompleto','$Cedula','$Correo','$NombreUsuario','$Rol','" . md5($Contrasena) . "', 'DISPONIBLE')");
                    $validar = array('respuesta' => "Registro Guardado Correctamente");
                }elseif ($Rol == 'ADMINISTRADOR') {
                    hacerConsulta("insert into usuario (NombreCompleto, Cedula,Correo, NombreUsuario, Rol, Contrasena) "
                            . "values ('$NombreCompleto','$Cedula','$Correo','$NombreUsuario','$Rol','" . md5($Contrasena) . "')");
                    $validar = array('respuesta' => "Registro Guardado Correctamente");
                }
            } catch (Exception $ex) {
                
            }
        }
    } else {
        $validar = array('respuesta' => "El Dato ingresado debe de ser un Numero");
    }
}


if ($accion == "eliminarUsuario") {
    $IdUsuario = $_REQUEST["IdUsuario"];

    try {
        hacerConsulta("delete from usuario where IdUsuario = $IdUsuario");
        $validar = array('respuesta' => 'Eliminado');
    } catch (Exception $ex) {
        $validar = array('respuesta' => 'error al eliminar registro en la BD');
    }
}

if ($accion == "TraerDatosEditar") {
    $IdUsuario = $_REQUEST["IdUsuario"];
//    echo $IdUsuario;
//    exit();
    try {
        $arreglo = DevolverUnArreglo("select * from usuario where IdUsuario = $IdUsuario");
        $validar = array('respuesta' => $arreglo);
    } catch (Exception $ex) {
        $validar = array('respuesta' => 'Error al traer datos de Editar');
    }
}

if ($accion == "editarUsuario") {
    $IdUsuario = $_REQUEST["IdUsuario"];
    $NombreCompleto = $_REQUEST["NombreCompleto"];
    $Cedula = $_REQUEST["Cedula"];
    $Correo = $_REQUEST["Correo"];
    $NombreUsuario = $_REQUEST["Usuario"];
    $Rol = $_REQUEST["Rol"];
    $Contrasena = $_REQUEST["Contrasena"];

    try {
        $consulta = "update usuario set NombreCompleto='$NombreCompleto', Cedula='$Cedula',Correo='$Correo', NombreUsuario='$NombreUsuario',"
                . " Rol='$Rol',Contrasena = '" . md5($Contrasena) . "' where IdUsuario = $IdUsuario ";

        hacerConsulta($consulta);
        $validar = array('respuesta' => "Editado Correctamente");
    } catch (Exception $ex) {
        $validar = array('respuesta' => 'Error al traer datos de Editar');
    }
}



echo json_encode($validar, JSON_FORCE_OBJECT);
?>