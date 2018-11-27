angular.module('Personeria').controller('ConfigTipoPoblacionController', InitController);
InitController.$inject = ['$scope', '$state', '$sessionStorage', 'servicios'];
function InitController($scope, $state, $sessionStorage, servicios) {
    if ($sessionStorage.idusuario === undefined) {
        $state.go('login');
    }
    $scope.TipoUsuario = $sessionStorage.rol;
    var idPoblacionEliminar = 0;

    var datos = {};
    llenarTabla();
    function llenarTabla() {
        datos = {accion: "cargarTabla"};
        servicios.TipoPoblacion(datos).then(function success(response) {
            console.log(response.data);
            $scope.poblacion = response.data.poblacion;
        });
    }

 $scope.guardarPoblacion = function () {
        if ($scope.NPoblacion == "" || $scope.NPoblacion == undefined) {
            alert("Por favor ingrese un tipo de población para guardar");
        } else {
            datos = {accion: "guardarpoblacion", poblacion: $scope.NPoblacion};
            servicios.TipoPoblacion(datos).then(function success(response) {
                console.log(response.data);
                $('.alert').alert();
                if (response.data.respuesta == "El tipo de poblacion se ha guardado Correctamente") {
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

    $scope.EliminarPoblacion = function (IdModulo, opcion) {
        if (opcion == "var") {
            idModuloEliminar = IdModulo;
            $scope.moduloEliminar = idModuloEliminar;
        }

        if (opcion == "Eliminar") {
            datos = {accion: "eliminarPoblacion", numPoblacion: idModuloEliminar};
            servicios.TipoPoblacion(datos).then(function success(response) {
                if (response.data.respuesta == "Eliminado") {
                    $('#exampleModal').modal('hide');
                    llenarTabla();
                }
            });
        }
    }
}
