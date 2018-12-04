angular.module('Personeria').controller('MandoController', InitController);
InitController.$inject = ['$scope', '$state', '$sessionStorage', 'servicios', '$interval'];
function InitController($scope, $state, $sessionStorage, servicios, $interval) {

    $scope.ShowConfirmarTurno = true;
    var datos = {};
    var TurnoActual = {};
    var IdUsuario = $sessionStorage.idusuario;
    var limite = 0;
$scope.ShowTerminarTurno = true;
    var IdPersonaAtendida = 0;
    $scope.ShowDisponible = true;
//    $scope.ShowConfirmarTurno = false;
//    $interval(function () {
//        console.log("1");
//    }, 2000); 

    $scope.Disponible = function () {
        datos = {accion: "Llamar", IdUsuario: IdUsuario};
        servicios.Mando(datos).then(function success(response) {
            console.log(response.data.respuesta);
            if (response.data.tipo == "NO HAY TURNOS")
            {
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
            }
        });
    }

    $scope.ClickSi = function () {
        $scope.ShowDisponible = true;
        $scope.ShowConfirmarTurno = false;
        $scope.Cedula = "";
//        console.log(TurnoActual);
    }

    $scope.ClickNo = function () {
        if (TurnoActual.tipo == "Aplazados") {
            datos = {accion: "LimiteLlamados"};
            servicios.Mando(datos).then(function success(response) {

                console.log("Ultimo Llamado del Turno:" + TurnoActual.respuesta[0].UltimoLlamado);
                console.log("Limite:" + response.data.respuesta);

                var ultimolla = TurnoActual.respuesta[0].UltimoLlamado;
                //compara el limite con el ultimo llamado del turno
                if (parseInt(ultimolla) >= parseInt(response.data.respuesta)) {

                    console.log("aqui se elimina");
                    //Elimino El turno, porque se exedio en las vaces de llamado
                    datos = {accion: "EliminarTurno", idAuditoria: TurnoActual.respuesta[0].IdAuditoria, IdTablaTemporal: TurnoActual.respuesta[0].IdTablaTemporal, NumLlamados: response.data.respuesta};
                    servicios.Mando(datos).then(function success(response) {});
                } else {
                    console.log("ENTRO Aplazado 1");
                    datos = {accion: "aumentarLlamadoAplazado", IdTablaTemporal: TurnoActual.respuesta[0].IdTablaTemporal};
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


    $scope.guardarPersona = function () {
        $scope.usuInsertar.accion = "guardarPersona";
//        console.log($scope.serv);
        servicios.Mando($scope.usuInsertar).then(function success(response) {
            console.log(response.data);
            if (response.data.respuesta == "Registro Guardado Correctamente") {
                $scope.alerta = response.data.respuesta;
                $scope.tipoAlerta = "alert-success";
                $scope.MostrarAlerta = true;
            } else {
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

        console.log($scope.usuEncuestar);
//        console.log("perro");
        servicios.Mando($scope.usuEncuestar).then(function success(response) {
            console.log(response.data);
            if (response.data.respuesta == "Registro Guardado Correctamente") {
                $scope.alerta = response.data.respuesta;
                $scope.tipoAlerta = "alert-success";
                $scope.MostrarAlerta = true;
                $('#EncuestaModal').modal('hide');
                $('#exampleModal').modal('hide');
            } else {
                $scope.alerta = response.data.respuesta;
                $scope.tipoAlerta = "alert-danger";
                $scope.MostrarAlerta = true;
            }
        });
    }

    var datos = {};
    datos = {accion: "cargarPoblacion"};
    servicios.Mando(datos).then(function success(response) {
        console.log(response);
        $scope.Poblacion = response.data;
    });

    $scope.DatosPersona = function (idpersona, nombre) {
        $scope.NOMBREPERSONA = nombre;
        $scope.usuEncuestar = {};
        IdPersonaAtendida = idpersona;
        console.log("IdPersonaAtendida: " + IdPersonaAtendida);
    }

    $scope.cerrarAlerta = function () {
        $scope.MostrarAlerta = false;
    }

    function llenarTabla1() {
        datos = {accion: "cargarTablaNumTurnos", IdUsuario: $sessionStorage.idusuario};
        servicios.Mando(datos).then(function success(response) {
            $scope.servicios = response.data.respuesta;
        });
    }
    llenarTabla1();

    $interval(function () {
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

}
