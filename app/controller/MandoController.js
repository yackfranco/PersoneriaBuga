angular.module('Personeria').controller('MandoController', InitController);
InitController.$inject = ['$scope', '$state', '$sessionStorage', 'servicios', '$interval'];
function InitController($scope, $state, $sessionStorage, servicios, $interval) {

    $scope.ShowConfirmarTurno = false;
    var datos = {};
    var TurnoActual = {};
    var IdUsuario = $sessionStorage.idusuario;
    var limite = 0;

    $scope.ShowDisponible = true;
    $scope.ShowConfirmarTurno = false;
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





}