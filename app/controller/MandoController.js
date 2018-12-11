angular.module('Personeria').controller('MandoController', InitController);
InitController.$inject = ['$scope', '$state', '$sessionStorage', 'servicios', '$interval'];
function InitController($scope, $state, $sessionStorage, servicios, $interval) {

    if ($sessionStorage.idusuario === undefined) {
        $state.go('login');
    } else {
        if ($sessionStorage.rol == "ADMINISTRADOR") {
            $state.go('dashboard');
        }
    }

    $scope.numModulo = $sessionStorage.modulo;
    $scope.NombreUsuario = $sessionStorage.nombreUsuario;

    var datos = {};
    var TurnoActual = {};
    var IdUsuario = $sessionStorage.idusuario;
    var limite = 0;

    var IdPersonaAtendida = 0;
    var idEncuestaGlobal = 0;
    var idPersonaGlobal = 0;
    var SinTurnos = "no hay";

    $scope.ShowTerminarTurno = false;
    $scope.ShowObservaciones = false;
    $scope.ShowRepetirLlamado = false;
    $scope.ShowEditarEncuesta = false;
    $scope.ShowConfirmarTurno = false;
    $scope.ShowDisponible = true;
//    $scope.ShowConfirmarTurno = false;
//    $interval(function () {
//        console.log("1");
//    }, 2000); 

    $scope.Disponible = function () {
        datos = {accion: "Llamar", IdUsuario: IdUsuario, Modulo: $sessionStorage.modulo};
        servicios.Mando(datos).then(function success(response) {
            console.log(response.data.respuesta);
            if (response.data.tipo == "NO HAY TURNOS")
            {
                SinTurnos = false;
                alert("Ya no hay turnos Disponibles");
            } else
            {
                TurnoActual = response.data;
//                console.log(TurnoActual);
                $scope.servicio = response.data.respuesta[0].Servicio;
                $scope.turno = response.data.respuesta[0].Turno;
                $scope.modulo = response.data.modulos;
                $scope.ShowDisponible = false;
                $scope.ShowConfirmarTurno = true;
                $scope.ShowRepetirLlamado = true;
            }
        });
    }

    $scope.ClickSi = function () {
        $scope.ShowDisponible = false;
        $scope.ShowConfirmarTurno = false;
        $scope.ShowRepetirLlamado = false;
        $scope.ShowObservaciones = true;
        $scope.ShowEditarEncuesta = true;
        $scope.ShowTerminarTurno = true;
        $scope.Cedula = "";
        datos = {accion: "ClicSi", IdUsuario: IdUsuario};
        servicios.Mando(datos).then(function success(response) {

        });
//        console.log(TurnoActual);
    }

    $scope.ClickNo = function () {
        datos = {accion: "CambiarEstadoUsuario",idusuario:IdUsuario};
            servicios.Mando(datos);
        if (TurnoActual.tipo == "Aplazados") {
            datos = {accion: "LimiteLlamados",idusuario:IdUsuario};
            servicios.Mando(datos).then(function success(response) {

                console.log("Ultimo Llamado del Turno:" + TurnoActual.respuesta[0].UltimoLlamado);
                console.log("Limite:" + response.data.respuesta);

                var ultimolla = TurnoActual.respuesta[0].UltimoLlamado;
                //compara el limite con el ultimo llamado del turno
                if (parseInt(ultimolla) >= parseInt(response.data.respuesta)) {

                    console.log("aqui se elimina");
                    //Elimino El turno, porque se exedio en las vaces de llamado
                    datos = {accion: "EliminarTurno", idAuditoria: TurnoActual.respuesta[0].IdAuditoria, IdTablaTemporal: TurnoActual.respuesta[0].IdTablaTemporal, NumLlamados: response.data.respuesta, IdUsuario: IdUsuario};
                    servicios.Mando(datos).then(function success(response) {});
                } else {
                    console.log("ENTRO Aplazado 1");
                    datos = {accion: "aumentarLlamadoAplazado", IdTablaTemporal: TurnoActual.respuesta[0].IdTablaTemporal, IdUsuario: IdUsuario};
                    servicios.Mando(datos).then(function success(response) {});
                }
            });

        } else {
            console.log("Turno Normal Para Aplazar");
            datos = {accion: "aumentarLlamadoAplazado", IdTablaTemporal: TurnoActual.respuesta[0].IdTablaTemporal};
            servicios.Mando(datos).then(function success(response) {});
        }
        $scope.ShowDisponible = true;
        $scope.ShowConfirmarTurno = false;
        $scope.ShowRepetirLlamado = false;
        $scope.ShowObservaciones = false;
        $scope.ShowEditarEncuesta = false;
        $scope.ShowTerminarTurno = false;
    }

    $scope.llenartablamando = function () {
        datos = {accion: "cargarTabla", Cedula: $scope.Cedula};
        servicios.Mando(datos).then(function success(response) {
            console.log(response.data);
            if (response.data.tablausu == "nada") {
                $scope.mando = {};
            } else {
                $scope.mando = response.data.tablausu;
            }
        });
    }
    var fecha;
    $scope.CapturarFecha = function () {
        fecha = $scope.FechaNacimiento;
        console.log(fecha);
    }

    $scope.guardarPersona = function () {
        $scope.usuInsertar.Fecha = fecha;
        $scope.usuInsertar.accion = "guardarPersona";
        var datos = {};
        datos = {accion: "cargarPoblacion"};
        servicios.Mando(datos).then(function success(response) {
            console.log(response);
            $scope.Poblacion = response.data;
        });
        servicios.Mando($scope.usuInsertar).then(function success(response) {
//            console.log(response.data);
            if (response.data.respuesta == "Registro Guardado Correctamente") {
                $scope.alerta = response.data.respuesta;
                $scope.tipoAlerta = "alert-success";
                $scope.MostrarAlerta = true;
                IdPersonaAtendida = response.data.IdPersona;
                $scope.usuEncuestar = {};
//                console.log(idPersonaGlobal);
                $('#EncuestaModal').modal('show');
            } else {//
                $scope.alerta = response.data.respuesta;
                $scope.tipoAlerta = "alert-danger";
                $scope.MostrarAlerta = true;
            }
        });
        $('#CrearPersonaModal').modal('hide');
    }

    $scope.guardarEncuesta = function () {
//
        $scope.usuEncuestar.accion = "guardarEncuesta";
        $scope.usuEncuestar.IdUsuario = $sessionStorage.idusuario;
        console.log(TurnoActual);
        $scope.usuEncuestar.IdServicio = TurnoActual.respuesta[0].IdServicio;
        $scope.usuEncuestar.IdPersona = IdPersonaAtendida;
        servicios.Mando($scope.usuEncuestar).then(function success(response) {
            console.log(response.data);
            if (response.data.respuesta == "Registro Guardado Correctamente") {
                $scope.alerta = response.data.respuesta;
                $scope.tipoAlerta = "alert-success";
                $scope.MostrarAlerta = true;
                $('#EncuestaModal').modal('hide');
                $('#exampleModal').modal('hide');
                idEncuestaGlobal = response.data.idEncuesta;
            } else {
                $scope.alerta = response.data.respuesta;
                $scope.tipoAlerta = "alert-danger";
                $scope.MostrarAlerta = true;
            }
        });
    }



    $scope.DatosPersona = function (idpersona, nombre) {
        $scope.NOMBREPERSONA = nombre;
        $scope.usuEncuestar = {};
        IdPersonaAtendida = idpersona;
        console.log("IdPersonaAtendida: " + IdPersonaAtendida);
        var datos = {};
        datos = {accion: "cargarPoblacion"};
        servicios.Mando(datos).then(function success(response) {
            console.log(response);
            $scope.Poblacion = response.data;
        });
    }

    $scope.cerrarAlerta = function () {
        $scope.MostrarAlerta = false;
    }

    function llenarTabla1() {
        datos = {accion: "cargarTablaNumTurnos", IdUsuario: $sessionStorage.idusuario};
        servicios.Mando(datos).then(function success(response) {
            $scope.servicios = response.data.respuesta;
            if (response.data.contar > 0) {
                if (SinTurnos == "no hay") {
                    console.log(SinTurnos);
                    SinTurnos = "si hay";
                    console.log(SinTurnos);
                    notifi();
                }

            } else {
                SinTurnos = "no hay";
            }


        });
    }
    llenarTabla1();

    var interval = $interval(function () {
        llenarTabla1();
    }, 2000);
    $scope.Limpiarmodalcrearpersona = function () {
        $scope.usuInsertar = {};
    }
    $scope.EditarPesona = function (idpersona2) {

        console.log(idpersona2);
        datos = {accion: "TraerDatosEditar", IdPersona: idpersona2};
        servicios.Mando(datos).then(function success(response) {
            console.log(response);
            $scope.usuEditarPer = {
                idPersona: response.data.respuesta[0].IdPersona,
                NombreCompleto: response.data.respuesta[0].NombreCompleto,
                Cedula: response.data.respuesta[0].Cedula,
                Sexo: response.data.respuesta[0].Sexo,
                Direccion: response.data.respuesta[0].Direccion,
                Barrio: response.data.respuesta[0].Barrio,
                Telefono: response.data.respuesta[0].Telefono,
                Fecha: response.data.respuesta[0].FechaNacimiento,
//                accion : ""
            };
            console.log($scope.usuEditarPer);
        });
    }

    $scope.FormEditarPersona = function () {

        $scope.usuEditarPer.accion = "editarPersona";

        console.log($scope.usuEditarPer);
        servicios.Mando($scope.usuEditarPer).then(function success(response) {
            if (response.data.respuesta == "Editado Correctamente") {
                console.log("EDITADO");
                $('#EditarModal').modal('hide');
            }
        });
    }


    $scope.RepetirLlamado = function () {

        datos = {accion: "RepetirLlamado", Modulo: $sessionStorage.modulo, Turno: $scope.turno, tv: TurnoActual.respuesta[0].LlamadoTv};
        servicios.Mando(datos).then(function success(response) {
            if (response.data.respuesta == "Llamado") {
                console.log("Llamado");
            }
        });
    }

    $scope.GuardarObservacion = function () {
        $scope.ObservacionAsesor;
        datos = {accion: "GuardarObservacionAsesor", ObservacionAsesor: $scope.ObservacionAsesor, IdAuditoria: TurnoActual.respuesta[0].IdAuditoria};
        servicios.Mando(datos).then(function success(response) {
            $('#ObservacionModal').modal('hide');
        });
    }

    $scope.TerminarTurno = function () {
        datos = {accion: "TerminarTurno", idpersona: IdPersonaAtendida, IdAuditoria: TurnoActual.respuesta[0].IdAuditoria, idEncuesta: idEncuestaGlobal, IdTemporal: TurnoActual.respuesta[0].IdTablaTemporal, IdUsuario: IdUsuario};
        console.log(datos);
        servicios.Mando(datos).then(function success(response) {
            console.log("Turno Terminado");

        });
        $scope.ShowDisponible = true;
        $scope.ShowConfirmarTurno = false;
        $scope.ShowRepetirLlamado = false;
        $scope.ShowObservaciones = false;
        $scope.ShowEditarEncuesta = false;
        $scope.ShowTerminarTurno = false;
    }

    $scope.EditarEncuesta = function () {
        $scope.usuEncuestar = {};
        datos = {accion: "TraerDatosEncuesta", IdEncuesta: idEncuestaGlobal};
        servicios.Mando(datos).then(function success(response) {
            console.log(response);
            $scope.usuEncuestar = {
                Asunto: response.data.respuesta[0].Asunto,
                Escolaridad: response.data.respuesta[0].NivelEscolaridad,
                TipoPoblacion: response.data.respuesta[0].TipoPoblacion
            };
            console.log($scope.usuEncuestar);
        });
    }

    $scope.FormEditarEncuesta = function () {

        $scope.usuEncuestar.accion = "editarEncuesta";
        $scope.usuEncuestar.idencuesta = idEncuestaGlobal;
        console.log($scope.usuEncuestar);
        servicios.Mando($scope.usuEncuestar).then(function success(response) {
            if (response.data.respuesta == "Editado Correctamente") {
                console.log("EDITADO");
                $('#EncuestaEditarModal').modal('hide');
            }
        });
    }
   
//    window.onload = function () {
//        Push.requestPermission();
//    }


    function notifi() {
 
               Push.Permission.request();
        Push.create("NUEVOS TURNOS EN COLA", {
            body: 'Turnos Esperando Por Atención',
            icon: 'image/ActivarAlarma.png',
            timeout: 10000,
            vibrate: [100, 100, 100],
            onClick: function () {
                window.focus();
                this.close();
            }
        });

        var audio = document.getElementById("audio");

        audio.play();
    }

    $scope.cerrarSesionMando = function () {
        $interval.cancel(interval);
    }
var bPreguntar = true;

    window.onbeforeunload = preguntarAntesDeSalir;

    function preguntarAntesDeSalir()
    {
        if (bPreguntar)
            return "¿Seguro que quieres salir?";
    }}
