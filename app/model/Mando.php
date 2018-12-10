<?php

header("Access-Control-Allow-Origin: *");
include 'conexion.php';

$accion = $_POST["accion"];


if ($accion == "Llamar") {

    $IdUsuario = $_POST["IdUsuario"];

    //LLAMA LOS APLAZADOS
    $consulta = "select * from tablatemporal join servicio on (tablatemporal.IdServicio = servicio.IdServicio) where Estado = 'Aplazado' and FechaLlamado < (SELECT NOW() - INTERVAL 2 MINUTE) and tablatemporal.IdServicio in (select IdServicio from relacionususer join usuario on (relacionususer.IdUsuario = usuario.IdUsuario) where relacionususer.IdUsuario = $IdUsuario) order by tablatemporal.UltimoLlamado ASC, tablatemporal.IdTablaTemporal ASC LIMIT 1";
    $Llamado = DevolverUnArreglo($consulta);


    if ($Llamado == null) {
        //LLAMA LOS NORMALES CON PRIORIDAD 1
        $Llamado = DevolverUnArreglo("select * from tablatemporal join servicio on "
                . "(tablatemporal.IdServicio = servicio.IdServicio) where Estado = 'NORMAL' "
                . "and tablatemporal.Prioridad = 1 and tablatemporal.IdServicio in "
                . "(select IdServicio from relacionususer join usuario on "
                . "(relacionususer.IdUsuario = usuario.IdUsuario) where relacionususer.IdUsuario = $IdUsuario) "
                . "order by IdTablaTemporal ASC LIMIT 1");
        if ($Llamado == null) {
            //LLAMA LOS NORMALES CON PRIORIDAD 2
            $Llamado = DevolverUnArreglo("select * from tablatemporal join servicio on "
                    . "(tablatemporal.IdServicio = servicio.IdServicio) where Estado = 'NORMAL' "
                    . "and tablatemporal.Prioridad = 2 and tablatemporal.IdServicio in "
                    . "(select IdServicio from relacionususer join usuario on "
                    . "(relacionususer.IdUsuario = usuario.IdUsuario) where relacionususer.IdUsuario = $IdUsuario) "
                    . "order by IdTablaTemporal ASC LIMIT 1");
            if ($Llamado == null) {
                //LLAMA LOS NORMALES CON PRIORIDAD 3
                $Llamado = DevolverUnArreglo("select * from tablatemporal join servicio on "
                        . "(tablatemporal.IdServicio = servicio.IdServicio) where Estado = 'NORMAL' "
                        . "and tablatemporal.Prioridad = 3 and tablatemporal.IdServicio in "
                        . "(select IdServicio from relacionususer join usuario on "
                        . "(relacionususer.IdUsuario = usuario.IdUsuario) where relacionususer.IdUsuario = $IdUsuario) "
                        . "order by IdTablaTemporal ASC LIMIT 1");
                if ($Llamado == null) {
                    $validar = "No hay Turnos";
                    $tipo = "NO HAY TURNOS";
                } else {
                    $validar = $Llamado;
                    $tipo = "Prioridad 3";
                }
            } else {
                $validar = $Llamado;
                $tipo = "Prioridad 2";
            }
        } else {
            $validar = $Llamado;
            $tipo = "Prioridad 1";
        }
    } else {
        //si llega aca es porque si hay Aplazados
//        $IdTemp = $Llamado[0]['IdTablaTemporal'];
//        $ultimoLlamado = $Llamado[0]['UltimoLlamado'];
//        $ultimoLlamado = $ultimoLlamado + 1;
//        $resp = hacerConsulta("update tablatemporal set Estado='Llamando',UltimoLlamado = $ultimoLlamado where IdTablaTemporal=$IdTemp");
        $validar = $Llamado;
        $tipo = "Aplazados";
    }
    if ($validar != "No hay Turnos") {
        $fecha = (new \DateTime())->format('Y-m-d H:i:s');
        $IdTemp = $Llamado[0]['IdTablaTemporal'];
        $ultimoLlamado = $Llamado[0]['UltimoLlamado'];
        $ultimoLlamado = $ultimoLlamado + 1;
        $resp = hacerConsulta("update tablatemporal set Estado='Llamando',UltimoLlamado = $ultimoLlamado , FechaLlamado = '$fecha' where IdTablaTemporal=$IdTemp");
//        echo "perrito";
//        print_r($Llamado);

        $idauditoria = $Llamado[0]['IdAuditoria'];
//        echo $idauditoria;
//        exit();
        hacerConsulta("update Usuario set Estado= 'LLAMANDO' where IdUsuario=$IdUsuario");
        $up = hacerConsulta("update auditoria set IdUsuario= $IdUsuario, FechaLlamado = '$fecha' where IdAuditoria=$idauditoria");

        $turno = $Llamado[0]["Turno"];
        $modulo = $_REQUEST["Modulo"];
        $Tipollamado = $Llamado[0]["LlamadoTv"];

        if ($Tipollamado == "1" || $Tipollamado == "2") {
            try {
                $consulta = "insert into tv (Modulo,Turno,Descripcion,Estado) "
                        . "values ('$modulo','$turno','Modulo',$Tipollamado)";
                hacerConsulta($consulta);
                $validar = array('respuesta' => "Registro Guardado Correctamente");
            } catch (Exception $ex) {
                $validar = array('respuesta' => "Error al guardar encuesta");
            }
        } elseif ($Tipollamado == "3") {
            $consulta = "insert into tv (Modulo,Turno,Descripcion,Estado) "
                    . "values ('$modulo','$turno','Modulo',1)";

            hacerConsulta($consulta);
            $consulta = "insert into tv (Modulo,Turno,Descripcion,Estado) "
                    . "values ('$modulo','$turno','Modulo',2)";

            hacerConsulta($consulta);
            $validar = array('respuesta' => "Registro Guardado Correctamente");
        }
    }
    $validar = array('respuesta' => $Llamado, 'tipo' => $tipo);
}

if ($accion == "LimiteLlamados") {
    $respuesta = DevolverUnDato("select NumeroLlamado from configturnoperdido");
    $validar = array('respuesta' => $respuesta);
}

if ($accion == "EliminarTurno") {
    $idAuditoria = $_POST["idAuditoria"];
    $IdTablaTemporal = $_POST["IdTablaTemporal"];
    $IdUsuario = $_REQUEST["IdUsuario"];
    $NumLlamados = $_POST["NumLlamados"];
    try {
        hacerConsulta("update auditoria set Estado = 'AUSENTE', NumeroLlamados = $NumLlamados , Observacion = 'cumplio Limite de Todos los llamados' where IdAuditoria =$idAuditoria");
        hacerConsulta("delete from tablatemporal where idtablatemporal = $IdTablaTemporal");
        hacerConsulta("update usuario set Estado= 'REPOSO' where IdUsuario=$IdUsuario");
        $validar = array('respuesta' => "bien");
    } catch (Exception $ex) {
        
    }
}

if ($accion == "aumentarLlamadoAplazado") {
    $IdTablaTemporal = $_POST["IdTablaTemporal"];
    $IdUsuario = $_REQUEST["IdUsuario"];
    hacerConsulta("update tablatemporal set Estado = 'Aplazado' where IdTablaTemporal =$IdTablaTemporal ");
    hacerConsulta("update usuario set Estado = 'REPOSO' where IdUsuario=$IdUsuario");
    $validar = array('respuesta' => "Bien");
}

if ($accion == "cargarTabla") {
    $Cedula = $_REQUEST["Cedula"];
    if (is_numeric($Cedula)) {
        $arreglo = DevolverUnArreglo("SELECT IdPersona,NombreCompleto, Cedula,Telefono FROM `personas` WHERE Cedula LIKE '%$Cedula%' limit 5");
        if ($arreglo == null) {
            $arreglo = "nada";
            $validar = array('tablausu' => $arreglo);
        } else {
//    $validar = array('id' => $arreglo);
            $validar = array('tablausu' => $arreglo);
        }
    } else {
        $validar = array('respuesta' => "El Dato ingresado debe de ser un Numero");
    }
}

if ($accion == "guardarPersona") {

    $NombreCompleto = $_REQUEST["NombreCompleto"];
    $Cedula = $_REQUEST["Cedula"];
    $Sexo = $_REQUEST["Sexo"];
    $Direccion = $_REQUEST["Direccion"];
    $Barrio = $_REQUEST["Barrio"];
    $Telefono = $_REQUEST["Telefono"];
    $Fecha = $_REQUEST["Fecha"];

    $newDate = date("Y-m-d", strtotime($Fecha));

//Pregunta si el numero a insertar es de tipo int
    if (is_numeric($Cedula) && is_numeric($Telefono)) {
        $sd = DevolverUnDato("select count(*) from personas where Cedula = '$Cedula'");
        if ($sd > 0) {
            $validar = array('respuesta' => "El usuario ya se encuentra registrado, por favor intente otro");
        } else {
            try {
                hacerConsulta("insert into personas (NombreCompleto, Cedula,Sexo, Direccion, Barrio, Telefono, FechaNacimiento) "
                        . "values ('$NombreCompleto','$Cedula','$Sexo','$Direccion','$Barrio',$Telefono,'$newDate')");
                $idPersona = DevolverUnDato("select max(IdPersona) from personas");
                $validar = array('respuesta' => "Registro Guardado Correctamente", 'IdPersona' => $idPersona);
            } catch (Exception $ex) {
                
            }
        }
    } else {
        $validar = array('respuesta' => "El Dato ingresado debe de ser un Numero");
    }
}

if ($accion == "guardarEncuesta") {
    $idusuario = $_REQUEST["IdUsuario"];
    $IdServicio = $_REQUEST["IdServicio"];
    $IdPersona = $_REQUEST["IdPersona"];
    $TipoPoblacion = $_REQUEST["TipoPoblacion"];
    $Escolaridad = $_REQUEST["Escolaridad"];
    $Asunto = $_REQUEST["Asunto"];

    try {
        $consulta = "insert into encuesta (IdServicio, IdUsuario,Idpersona, Asunto, TipoPoblacion, NivelEscolaridad) "
                . "values ('$IdServicio','$idusuario','$IdPersona','$Asunto','$TipoPoblacion','$Escolaridad')";

        hacerConsulta($consulta);
        $idUltimoEncuesta = DevolverUnDato("select max(IdEncuesta) from Encuesta");
        $validar = array('respuesta' => "Registro Guardado Correctamente", 'idEncuesta' => $idUltimoEncuesta);
    } catch (Exception $ex) {
        $validar = array('respuesta' => "Error al guardar encuesta");
    }
}

if ($accion == "cargarPoblacion") {
    $arreglo = DevolverUnArreglo("SELECT TipoPoblacion from poblacion");
    $validar = $arreglo;
}
if ($accion == "cargarTablaNumTurnos") {
    $idusuario = $_REQUEST["IdUsuario"];
    $arreglo = DevolverUnArreglo("select servicio.Servicio, COUNT(tablatemporal.IdServicio) as Cantidad from tablatemporal JOIN servicio on (tablatemporal.IdServicio = servicio.IdServicio) where tablatemporal.Estado = 'NORMAL'OR tablatemporal.Estado = 'APLAZADO' AND tablatemporal.IdServicio in (select IdServicio from relacionususer join usuario on (relacionususer.IdUsuario = usuario.IdUsuario) where relacionususer.IdUsuario = 4) GROUP by tablatemporal.IdServicio");
    $contar = 0;
    foreach ($arreglo as $value) {
        $contar = $contar + $value['Cantidad'];
    }


    //echo ("select servicio.Servicio, COUNT(tablatemporal.IdServicio) as Cantidad from tablatemporal JOIN servicio on (tablatemporal.IdServicio = servicio.IdServicio) where tablatemporal.IdServicio in (select IdServicio from relacionususer join usuario on (relacionususer.IdUsuario = usuario.IdUsuario) where relacionususer.IdUsuario = $idusuario) GROUP by tablatemporal.IdServicio");
//    $arreglo = DevolverUnArreglo("SELECT servicio.Servicio, (select count(*) from tablatemporal where IdServicio= servicio.IdServicio AND Estado = 'NORMAL' or Estado = 'Aplazado' AND LlamadoPor='ASESOR') as Cantidad FROM `relacionususer` join servicio on (servicio.IdServicio = relacionususer.IdServicio) WHERE IdUsuario = $idusuario");
    $validar = array('respuesta' => $arreglo, 'contar' => $contar);
}

if ($accion == "TraerDatosEditar") {
    $idpersona = $_REQUEST["IdPersona"];

    $arreglo = DevolverUnArreglo("select * from personas where IdPersona = $idpersona");
    $validar = array('respuesta' => $arreglo);
}

if ($accion == "GuardarObservacionAsesor") {
    $Observacion = $_REQUEST["ObservacionAsesor"];
    $IdAuditoria = $_REQUEST["IdAuditoria"];
    $up = hacerConsulta("update auditoria set Observacion= '$Observacion' where IdAuditoria=$IdAuditoria");
    $validar = array('respuesta' => "guardado");
}

if ($accion == "editarPersona") {
    $idpersona = $_REQUEST["idPersona"];
    $NombreCompleto = $_REQUEST["NombreCompleto"];
    $Cedula = $_REQUEST["Cedula"];
    $Sexo = $_REQUEST["Sexo"];
    $Direccion = $_REQUEST["Direccion"];
    $Barrio = $_REQUEST["Barrio"];
    $Telefono = $_REQUEST["Telefono"];
    $Fecha = $_REQUEST["Fecha"];
    try {
        $consulta = "update personas set NombreCompleto='$NombreCompleto', Cedula='$Cedula',Sexo='$Sexo', Direccion='$Direccion',Barrio='$Barrio',Telefono='$Telefono',FechaNacimiento='$Fecha'"
                . " where IdPersona = $idpersona ";
        hacerConsulta($consulta);
        $validar = array('respuesta' => "Editado Correctamente");
    } catch (Exception $ex) {
        $validar = array('respuesta' => 'Error al traer datos de Editar');
    }
}

if ($accion == "RepetirLlamado") {

    $turno = $_REQUEST["Turno"];
    $modulo = $_REQUEST["Modulo"];
    $Tipollamado = $_REQUEST["tv"];

    if ($Tipollamado == "1" || $Tipollamado == "2") {
        try {
            $consulta = "insert into tv (Modulo,Turno,Descripcion,Estado) "
                    . "values ('$modulo','$turno','Modulo',$Tipollamado)";
            hacerConsulta($consulta);
            $validar = array('respuesta' => "Registro Guardado Correctamente");
        } catch (Exception $ex) {
            $validar = array('respuesta' => "Error al guardar encuesta");
        }
    } elseif ($Tipollamado == "3") {
        $consulta = "insert into tv (Modulo,Turno,Descripcion,Estado) "
                . "values ('$modulo','$turno','Modulo',1)";

        hacerConsulta($consulta);
        $consulta = "insert into tv (Modulo,Turno,Descripcion,Estado) "
                . "values ('$modulo','$turno','Modulo',2)";

        hacerConsulta($consulta);
        $validar = array('respuesta' => "Registro Guardado Correctamente");
    }
}

if ($accion == "TerminarTurno") {
    $idpersona = $_REQUEST["idpersona"];
    $IdAuditoria = $_REQUEST["IdAuditoria"];
    $IdEncuesta = $_REQUEST["idEncuesta"];
    $IdTemporal = $_REQUEST["IdTemporal"];
    $IdUsuario = $_REQUEST["IdUsuario"];
    $fecha = (new \DateTime())->format('Y-m-d H:i:s');

    try {
        $consulta = hacerConsulta("update auditoria set IdPersona = $idpersona, IdEncuesta = $IdEncuesta, Estado = 'TERMINADO', Fechasalio = '$fecha' where IdAuditoria=$IdAuditoria");
        hacerConsulta($consulta);
        hacerConsulta("delete from tablatemporal where IdTablaTemporal = $IdTemporal");
        hacerConsulta("update usuario set Estado = 'REPOSO' where IdUsuario=$IdUsuario");
        $validar = array('respuesta' => "Termiando Correctamente");
    } catch (Exception $ex) {
        $validar = array('respuesta' => 'Error en la consulta');
    }
}

if ($accion == "TraerDatosEncuesta") {
    $idencuesta = $_REQUEST["IdEncuesta"];
    $arreglo = DevolverUnArreglo("select * from encuesta where IdEncuesta = $idencuesta");
    $validar = array('respuesta' => $arreglo);
}

if ($accion == "editarEncuesta") {

    $IdEncuesta = $_REQUEST["idencuesta"];
    $TipoPoblacion = $_REQUEST["TipoPoblacion"];
    $Escolaridad = $_REQUEST["Escolaridad"];
    $Asunto = $_REQUEST["Asunto"];
    try {
        $consulta = hacerConsulta("update encuesta set Asunto = '$Asunto', TipoPoblacion = '$TipoPoblacion', NivelEscolaridad = '$Escolaridad' where IdEncuesta=$IdEncuesta");

        hacerConsulta($consulta);
        $validar = array('respuesta' => "Editado Correctamente");
    } catch (Exception $ex) {
        $validar = array('respuesta' => 'Error en la consulta');
    }
}
if ($accion == "ClicSi") {

    $IdUsuario = $_REQUEST["IdUsuario"];

    try {

        $consulta = hacerConsulta("update Usuario set Estado= 'ATENDIENDO' where IdUsuario=$IdUsuario");

        hacerConsulta($consulta);
        $validar = array('respuesta' => "Editado Correctamente");
    } catch (Exception $ex) {
        $validar = array('respuesta' => 'Error en la consulta');
    }
}
//select * from tablatemporal join servicio on (tablatemporal.IdServicio = servicio.IdServicio) where Estado = 'Aplazado' and FechaLlamado < (SELECT NOW() - INTERVAL 2 MINUTE) and tablatemporal.IdServicio in (select IdServicio from relacionususer join usuario on (relacionususer.IdUsuario = usuario.IdUsuario) where relacionususer.IdUsuario = 4) order by tablatemporal.UltimoLlamado ASC, tablatemporal.IdTablaTemporal ASC
//select tablatemporal.* from tablatemporal join servicio on (tablatemporal.IdServicio = servicio.IdServicio) where Estado = 'NORMAL' and tablatemporal.Prioridad = 1 and tablatemporal.IdServicio in (select IdServicio from relacionususer join usuario on (relacionususer.IdUsuario = usuario.IdUsuario) where relacionususer.IdUsuario = 4) order by IdTablaTemporal ASC LIMIT 1
//SELECT servicio.Servicio, (select count(*) from tablatemporal where IdServicio= servicio.IdServicio AND Estado = 'NORMAL' AND LlamadoPor='ASESOR') as Cantidad FROM `relacionususer` join servicio on (servicio.IdServicio = relacionususer.IdServicio) WHERE IdUsuario = " 1"
//select servicio.Servicio, COUNT(tablatemporal.IdServicio) as contar from tablatemporal JOIN servicio on (tablatemporal.IdServicio = servicio.IdServicio) where tablatemporal.IdServicio in (select IdServicio from relacionususer join usuario on (relacionususer.IdUsuario = usuario.IdUsuario) where relacionususer.IdUsuario = 4) GROUP by tablatemporal.IdServicio
header('Content-Type: application/json');
echo json_encode($validar, JSON_FORCE_OBJECT);
?>
