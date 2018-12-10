angular.module('Personeria').controller('EjemploController', dashboardController);
dashboardController.$inject = ['$scope', '$state', '$sessionStorage', 'servicios'];
function dashboardController($scope, $state, $sessionStorage, $servicios) {
    if ($sessionStorage.idusuario === undefined) {
        $state.go('login');
    } else {
        if ($sessionStorage.rol == "ASESOR") {
            $state.go('Mando');
        }
    }
    $scope.TipoUsuario = $sessionStorage.rol;

    $scope.NombreUsuario = $sessionStorage.nombreUsuario ;


    $scope.GuardarConfig = function () {
        console.log($scope.tv);
        $scope.tv.accion = "guardarDatos";
        $servicios.Ejemplo($scope.tv).then(function success(response) {
            console.log(response.data);

            if (response.data.respuesta == "Guardado Correctamente") {
                $scope.alerta = response.data.respuesta;
                $scope.tipoAlerta = "alert-success";
                $scope.MostrarAlerta = true;
//                llenarDatos();
            }
        });
    }

    $scope.add = function () {
        var f = document.getElementById('file').files[0],
                r = new FileReader();

        r.onloadend = function (e) {
            var data = e.target.result;
            //send your binary data via $http or $resource or do anything else with it
        }

        r.readAsBinaryString(f);
        console.log(r.readAsBinaryString(f));
    }

    $scope.guardarI = function () {
        console.log($scope.Imagen);
    }
    $scope.cerrarAlerta = function () {
        $scope.MostrarAlerta = false;
    }
}
