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
    $NumLlamados = $_POST["NumLlamados"];
    try {
        hacerConsulta("update auditoria set Estado = 'AUSENTE', NumeroLlamados = $NumLlamados , Observacion = 'cumplio Limite de Todos los llamados' where IdAuditoria =$idAuditoria");
        hacerConsulta("delete from tablatemporal where idtablatemporal = $IdTablaTemporal");
        $validar = array('respuesta' => "bien");
    } catch (Exception $ex) {
        
    }
}

if ($accion == "aumentarLlamadoAplazado") {
    $IdTablaTemporal = $_POST["IdTablaTemporal"];
    hacerConsulta("update tablatemporal set Estado = 'Aplazado' where IdTablaTemporal =$IdTablaTemporal ");
    $validar = array('respuesta' => "Bien");
}

//select * from tablatemporal join servicio on (tablatemporal.IdServicio = servicio.IdServicio) where Estado = 'Aplazado' and FechaLlamado < (SELECT NOW() - INTERVAL 2 MINUTE) and tablatemporal.IdServicio in (select IdServicio from relacionususer join usuario on (relacionususer.IdUsuario = usuario.IdUsuario) where relacionususer.IdUsuario = 4) order by tablatemporal.UltimoLlamado ASC, tablatemporal.IdTablaTemporal ASC
//select tablatemporal.* from tablatemporal join servicio on (tablatemporal.IdServicio = servicio.IdServicio) where Estado = 'NORMAL' and tablatemporal.Prioridad = 1 and tablatemporal.IdServicio in (select IdServicio from relacionususer join usuario on (relacionususer.IdUsuario = usuario.IdUsuario) where relacionususer.IdUsuario = 4) order by IdTablaTemporal ASC LIMIT 1

header('Content-Type: application/json');
echo json_encode($validar, JSON_FORCE_OBJECT);
?>