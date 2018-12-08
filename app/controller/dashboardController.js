angular.module('Personeria').controller('dashboardController', dashboardController);
dashboardController.$inject = ['$scope', '$state', '$sessionStorage', 'servicios', '$interval'];
function dashboardController($scope, $state, $sessionStorage, servicios, $interval) {
    if ($sessionStorage.idusuario === undefined) {
        $state.go('login');
    }
    $scope.TipoUsuario = $sessionStorage.rol;

    $scope.NombreUsuario = $sessionStorage.nombreUsuario + " " + $sessionStorage.apellidoUsuario;


    $interval(function () {
        llenarTabla();
        llenarTablaservicios();
        llenarTablaTipoPoblacion();
        llenarTablaTipoUsuariocant();
    }, 5000);

    function llenarTabla() {
        datos = {accion: "cargarTabla"};
        servicios.Dashboard(datos).then(function success(response) {
            console.log(response.data);
            $scope.usuario = response.data.respuesta;
        });
    }
    function llenarTablaservicios() {
        datos = {accion: "cargarTablaServicios"};
        servicios.Dashboard(datos).then(function success(response) {
            console.log(response.data);
            $scope.servicio = response.data.respuesta;
        });
    }
    function llenarTablaTipoPoblacion() {
        datos = {accion: "cargarTablaTipoPoblacion"};
        servicios.Dashboard(datos).then(function success(response) {
            console.log(response.data);
            $scope.poblacion = response.data.respuesta;
        });
    }
    function llenarTablaTipoUsuariocant() {
        datos = {accion: "cargarTablaUsuariocant"};
        servicios.Dashboard(datos).then(function success(response) {
            console.log(response.data);
            $scope.usuarioscant = response.data.respuesta;
        });
    }
}