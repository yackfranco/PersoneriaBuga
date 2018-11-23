angular.module('Personeria').controller('ModuloController', InitController);
InitController.$inject = ['$scope', '$state', '$sessionStorage', 'servicios'];
function InitController($scope, $state, $sessionStorage, servicios) {
    if ($sessionStorage.idusuario === undefined) {
        $state.go('login');
    }
    $scope.NModulo == "";
    var idModuloEliminar = 0;
    $scope.TipoUsuario = $sessionStorage.rol;
    $scope.MostrarAlerta = false;
    $scope.NombreUsuario = $sessionStorage.nombreUsuario + " " + $sessionStorage.apellidoUsuario;

//    $scope.NModulo

    var datos = {};
    llenarTabla();
    function llenarTabla() {
        datos = {accion: "cargarTabla"};
        servicios.Modulo(datos).then(function success(response) {
            console.log(response.data);
            $scope.modulo = response.data.modulos;
        });
    }

    $scope.guardarModulo = function () {
        if ($scope.NModulo == "" || $scope.NModulo == undefined) {
            alert("Por favor ingrese un numero de Modulo para guardar");
        } else {
            datos = {accion: "guardarModulo", numModulo: $scope.NModulo};
            servicios.Modulo(datos).then(function success(response) {
                console.log(response.data);
//                $('.alert').alert();
                if (response.data.respuesta == "El Modulo se ha guardado Correctamente") {
                    $scope.alerta = response.data.respuesta;
                    $scope.tipoAlerta = "alert-success";
                    $scope.MostrarAlerta = true;
                    llenarTabla();
                } else {
                    $scope.alerta = response.data.respuesta;
                    $scope.tipoAlerta = "alert-danger";
                    $scope.MostrarAlerta = true;
                }
            });
        }
    }

    $scope.cerrarAlerta = function(){
        $scope.MostrarAlerta = false;
    }

    $scope.EliminarModulo = function (IdModulo, opcion) {
        if (opcion == "var") {
            idModuloEliminar = IdModulo;
            $scope.moduloEliminar = idModuloEliminar;
        }

        if (opcion == "Eliminar") {
            datos = {accion: "eliminarModulo", numModulo: idModuloEliminar};
            servicios.Modulo(datos).then(function success(response) {
                if (response.data.respuesta == "Eliminado") {
                    $('#exampleModal').modal('hide');
                    llenarTabla();
                }
            });
        }
    }

    $scope.ComprarModulo = function () {

    }

}
