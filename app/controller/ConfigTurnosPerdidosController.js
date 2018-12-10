angular.module('Personeria').controller('ConfigTurnosPerdidosController', InitController);
InitController.$inject = ['$scope', '$state', '$sessionStorage', 'servicios'];
function InitController($scope, $state, $sessionStorage, servicios) {
    if ($sessionStorage.idusuario === undefined) {
        $state.go('login');
    } else {
        if ($sessionStorage.rol == "ASESOR") {
            $state.go('Mando');
        }
    }
    $scope.TipoUsuario = $sessionStorage.rol;

    $scope.NombreUsuario = $sessionStorage.nombreUsuario;
    llenarDatos();
    $scope.MostrarAlerta = false;
    $scope.checkboxModel = {
        value1: true,
        value2: 'YES'
    };

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
            console.log(response.data.permiso);
            if (response.data.permiso == 0) {
                $scope.checkboxModel.value1 = false;
            } else {
                $scope.checkboxModel.value1 = true;
            }
            Capt();
        });
    }

    function Capt() {
        if ($scope.checkboxModel.value1) {
            $scope.opcion = "Si";
        } else {
            $scope.opcion = "No";
        }
    }


    $scope.CapturarOpcion = function () {
        if ($scope.checkboxModel.value1) {
            $scope.opcion = "Si";
        } else {
            $scope.opcion = "No";
        }
        console.log($scope.checkboxModel);
    }


    $scope.GuardarConfig = function () {
        var permiso;
       
        if ($scope.checkboxModel.value1) {
            permiso = 1;
        } else {
            permiso = 0;
        }
         console.log(permiso);
        datos = {accion: "guardarDatos", tiempoEspera: $scope.tiempoEspera, NumeroLlamado: $scope.NumeroLlamado, permiso: permiso};
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

    $scope.cerrarAlerta = function () {
        $scope.MostrarAlerta = false;
    }

}
