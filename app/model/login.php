<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
include 'conexion.php';

$accion = $_REQUEST["accion"];

if ($accion == "cargarUsuarios") {
    $arreglo = DevolverUnArreglo("SELECT * from usuario");
    $validar = $arreglo;
}

if ($accion == "cargarModulos") {
    $arreglo = DevolverUnArreglo("SELECT * from modulo order by Idmodulo asc");
    $validar = $arreglo;
}

if ($accion == "traerRol") {
    $idusuario = $_REQUEST["idUsuario"];
    $arreglo = DevolverUnDato("SELECT rol from usuario where IdUsuario = $idusuario");
    $validar = $arreglo;
}


if ($accion == "entrar") {
    if (LicenciaNube() == "se bloquea") {
        $validar = "Bloqueado";
    } else {
        $user = $_POST["usuario"];
        $user = DevolverUnDato("select NombreUsuario from usuario where IdUsuario = $user");
        $contra = $_POST["contrasena"];
        $consulta = "SELECT * from usuario where NombreUsuario='$user' and Contrasena = '" . hash('MD5', $contra) . "'";
        $arreglo = DevolverUnArreglo($consulta);
        if (empty($arreglo)) {
            $validar = "No Entro";
        } else {
            $validar = $arreglo;
        }
    }
}

if ($accion == "FechasReiniciarDatos") {
    $permiso = DevolverUnDato("select permiso from reiniciar");
    if ($permiso == 0) {
        return;
    }

    $fechaBD = DevolverUnDato("select fecha from reiniciar");
    $fechaActual = (new \DateTime())->format('Y-m-d');
    if ($fechaBD < $fechaActual) {
        hacerConsulta("update reiniciar set fecha = '$fechaActual' ");
        $servicios = DevolverUnArreglo("select * from servicio");

        foreach ($servicios as $value) {
            hacerConsulta("update servicio set secuencia = " . $value['Cont_min'] . " where idservicio = " . $value['IdServicio']);
        }
        hacerConsulta("delete from tablatemporal");
    } else {
        
    }
}

if ($accion == "ValidarEntrarLicencia") {
    $contra2 = $_REQUEST["contrase"];
   
    if ($contra2 == "1235813A100") {
        $validar = "Entro";
    } else {
        $validar = "No Entro";
    }
}

if($accion == "GuardarLicencia"){
    $fechaInicial = $_REQUEST["fecha"];
    $fechaInicial = date("Y-m-d", strtotime($fechaInicial));
    $fechaInicial = openCypher('encrypt',$fechaInicial);
   
    hacerConsulta("update pconfig set config = '$fechaInicial'");
    $validar = "Listo";
}

function LicenciaNube() {
    $fechaBD = openCypher('decrypt', DevolverUnDato("select config from pconfig"));
    $fechaActual = (new \DateTime())->format('Y-m-d');
    if ($fechaBD < $fechaActual) {
        return "se bloquea";
    } else {
        return "Bien";
    }
}

function openCypher($action = 'encrypt', $string = false) {
    $action = trim($action);
    $output = false;

    $myKey = 'oW%c76+jb2!==)(/&%%&/())(/&&%rvhu235453()(/&%%&(i????=)(()==?)';
    $myIV = 'A)2!u467a^';
    $encrypt_method = 'AES-256-CBC';

    $secret_key = hash('sha256', $myKey);
    $secret_iv = substr(hash('sha256', $myIV), 0, 16);

    if ($action && ($action == 'encrypt' || $action == 'decrypt') && $string) {
        $string = trim(strval($string));

        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $secret_key, 0, $secret_iv);
        };

        if ($action == 'decrypt') {
            $output = openssl_decrypt($string, $encrypt_method, $secret_key, 0, $secret_iv);
        };
    };

    return $output;
}

function ValidarUrl($url) {
    //fsockopen -> Abrir una conexión de sockets de dominio de Internet o Unix
    //resource fsockopen ( string destino, int puerto [, int errno [, string errstr [, float tiempo_espera]]])
    $validar = @fsockopen($url, 80, $errno, $errstr, 15);
    if ($validar) {
        fclose($validar);
        return true;
    } else
        return false;
}

echo json_encode($validar, JSON_FORCE_OBJECT);
?>
