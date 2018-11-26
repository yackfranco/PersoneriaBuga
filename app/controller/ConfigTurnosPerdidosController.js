angular.module('Personeria').controller('ConfigTurnosPerdidosController', InitController);
InitController.$inject = ['$scope', '$state', '$sessionStorage', 'servicios'];
function InitController($scope, $state, $sessionStorage, servicios) {
   if ($sessionStorage.idusuario === undefined) {
        $state.go('login');
    }
    $scope.TipoUsuario = $sessionStorage.rol;

    $scope.NombreUsuario = $sessionStorage.nombreUsuario + " " + $sessionStorage.apellidoUsuario;
    llenarDatos();
    $scope.MostrarAlerta = false;

    function llenarDatos() {
        datos = {accion: "cargarDatos"};
        servicios.ConfigTurnosPerdidos(datos).then(function success(response) {
            console.log(response.data);
            if (response.data.respuesta == "sin datos") {
                $scope.tiempoEspera = "0";
                $scope.NumeroLlamado = "0";
            } else {
                $scope.tiempoEspera = response.data.respuesta[0].TiempoEspera;
                $scope.NumeroLlamado = response.data.respuesta[0].NumeroLlamado;
            }
        });
    }

    $scope.GuardarConfig = function () {
        datos = {accion: "guardarDatos", tiempoEspera: $scope.tiempoEspera, NumeroLlamado: $scope.NumeroLlamado};
        servicios.ConfigTurnosPerdidos(datos).then(function success(response) {
            console.log(response.data);
            if (response.data.respuesta == "Guardado Correctamente") {
                $scope.alerta = response.data.respuesta;
                $scope.tipoAlerta = "alert-success";
                $scope.MostrarAlerta = true;
                llenarDatos();
            }
        });
    }
    
     $scope.cerrarAlerta = function(){
        $scope.MostrarAlerta = false;
    }

}
