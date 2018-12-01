angular.module('Personeria').controller('ConfigTvController', dashboardController);
dashboardController.$inject = ['$scope', '$state', '$sessionStorage', 'servicios'];
function dashboardController($scope, $state, $sessionStorage, $servicios) {
    if ($sessionStorage.idusuario === undefined) {
        $state.go('login');
    }
    $scope.TipoUsuario = $sessionStorage.rol;

    $scope.NombreUsuario = $sessionStorage.nombreUsuario + " " + $sessionStorage.apellidoUsuario;


    $scope.GuardarConfig = function () {
        datos = {accion: "guardarDatos", MensajeR: $scope.MensajeR, Nvideo: $scope.Nvideo, Fuente: $scope.Fuente};
        $servicios.ConfigTv(datos).then(function success(response) {
            console.log(response.data);
            if (response.data.respuesta == "Guardado Correctamente") {
                $scope.alerta = response.data.respuesta;
                $scope.tipoAlerta = "alert-success";
                $scope.MostrarAlerta = true;
//                llenarDatos();
            }
        });
    }
    
     $scope.cerrarAlerta = function(){
        $scope.MostrarAlerta = false;
    }
}
